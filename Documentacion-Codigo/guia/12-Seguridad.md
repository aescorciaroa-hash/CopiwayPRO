# 12 · Seguridad

El sistema aplica varias capas de protección. Todas son preguntas típicas de examen.

> Al final hay una sección de **puntos débiles conocidos**. Conviene leerla: en una
> sustentación queda mucho mejor saber qué falta que afirmar algo que el código no hace.

---

## 1. Inyección SQL → **consultas preparadas con MySQLi**

**Ataque:** el usuario escribe `' OR '1'='1` en un campo para colarse.

**Defensa:** nunca se concatena texto del usuario en el SQL. Se usan `?` como marcadores
y los valores se vinculan aparte:

```php
$stmt = $this->conn->prepare("SELECT * FROM CLIENTE WHERE correo = ? LIMIT 1");
$stmt->bind_param("s", $correo);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();
```

MySQL recibe la consulta y los datos **por canales separados**; los datos jamás se
interpretan como SQL. El primer argumento de `bind_param` es el tipo de cada valor:
`s` texto, `i` entero, `d` decimal.

> El proyecto usa la extensión **`mysqli`**, no PDO. La única aparición de PDO en todo el
> código es `database/install.php`, el script de instalación.

---

## 2. XSS (Cross-Site Scripting) → **escapar con `e()`**

**Ataque:** un cliente pone como nombre `<script>robarCookie()</script>`; cuando el
admin abre el directorio, ese script se ejecutaría en su navegador.

**Defensa:** al imprimir cualquier dato se usa `e()`:

```php
function e($value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}
```

Convierte `<` en `&lt;`, `"` en `&quot;`, etc. El navegador lo muestra como texto, no
lo ejecuta.

---

## 3. CSRF (Cross-Site Request Forgery) → **token generado, pero ⚠️ sin verificar**

**Ataque:** otra web hace que tu navegador envíe, sin que lo sepas, un
`POST /admin/personal/.../eliminar` aprovechando tu sesión abierta.

**Lo que el sistema sí hace:**
- `Session::csrf()` genera un token de 64 caracteres hex por sesión
  (`bin2hex(random_bytes(32))`) y lo guarda en `$_SESSION['_csrf']`.
- El helper `csrf_field()` lo imprime como campo oculto en los formularios:
  ```php
  <?= csrf_field() ?>   // <input type="hidden" name="_csrf" value="a1b2c3...">
  ```
- Los layouts lo publican además en `<meta name="csrf-token">`, y `Copiway.post()`
  (en `public/assets/js/app.js`) lo adjunta a las peticiones `fetch`.
- `Session::checkCsrf()` existe y compara con `hash_equals()`, que trabaja en **tiempo
  constante** (no filtra información por el tiempo de respuesta).

**⚠️ Lo que falta:** **`Session::checkCsrf()` no se llama en ningún punto del sistema.**
El token viaja de ida y vuelta, pero ningún script lo valida, así que hoy **la protección
CSRF no está activa**. No existe un `verifyCsrf()` central ni un chequeo en
`public/index.php`.

Para cerrarlo bastaría con añadir esto al inicio del bloque de POST de
`public/index.php`, antes de despachar a los controladores:

```php
if ($method === 'POST' && !Session::checkCsrf($_POST['_csrf'] ?? null)) {
    http_response_code(419);
    exit('Token de seguridad invalido. Recarga la pagina e intentalo de nuevo.');
}
```

---

## 4. Contraseñas → **hash bcrypt**

- Al registrar: `password_hash($clave, PASSWORD_BCRYPT)` → texto de 60 caracteres.
- Al entrar: `password_verify($loQueEscribio, $hashGuardado)`.
- **Nunca** se guarda ni se puede recuperar la contraseña original.
- bcrypt es lento a propósito + añade una *sal* aleatoria → dos claves iguales tienen
  hash distinto y no se pueden precalcular (rainbow tables).
- Los endpoints JSON que devuelven un usuario (`cargar` de personal, `historial` de
  clientes) hacen `unset($emp['contrasena'])` antes de responder.

---

## 5. Control de acceso por rol (RBAC)

Todo el control vive **en un solo sitio**: una función anónima al principio de
`public/index.php`, que se ejecuta **antes de cualquier enrutado**, tanto en GET como
en POST.

```php
(function () use ($uri, $method) { ... })();
```

Cómo funciona:

1. Deja pasar sin comprobar nada las rutas **100 % públicas**: `/`, `/home`, `/login`,
   `/register`, `/forgot-password`, `/logout` y `/assets/...`.
2. **Deduce el rol exigido del prefijo de la ruta** (no hay tabla de rutas ni archivo de
   configuración):

   | Prefijo | Rol exigido |
   |---|---|
   | `/admin` | `admin` |
   | `/client` | `cliente` |
   | `/kitchen` | `cocina` |
   | `/delivery` | `domiciliario` |

3. Si **no hay sesión** → flash + `redirect('/login')`.
4. Si **el rol no coincide** → `redirect(Auth::homeFor(Auth::role()))`: al usuario se le
   devuelve a su propio panel. **No se emite un 403**, se redirige.

Un cliente que escriba `/admin` a mano acaba de vuelta en `/client`. No hay forma de
saltárselo porque el chequeo está en el flujo central, no en cada vista.

> `Auth::requireRole()` (que sí devuelve 403 y además comprueba que la cuenta siga
> existiendo en la BD) está escrito en `app/Core/Auth.php` **pero no lo usa nadie**: es
> código muerto de la versión anterior.

---

## 6. Sesiones seguras

- `session_regenerate_id(true)` dentro de `Auth::login()` → evita **session fixation**
  (que alguien te fije un ID de sesión conocido antes de que entres).
- La cookie de sesión (`PHPSESSID`) no viaja en la URL.
- `Auth::logout()` borra rol, usuario y carrito.

---

## 7. Segundo factor para estaciones compartidas

Cocina y Domiciliario usan tablets compartidas. Además del login normal, piden un
**PIN de estación** (`pin_estacion_kds` / `pin_estacion_domiciliario`, guardados en
`CONFIGURACION_SISTEMA`).

- El PIN se compara con `hash_equals()` en `KdsController` / `PanelController`.
- Si acierta, se marca `estacion_cocina_ok` / `estacion_domi_ok` en la sesión.
- **El guardia de `public/index.php`** es el que, si falta ese flag, redirige a
  `/kitchen/estacion` o `/delivery/estacion` antes de dejar ver el panel.

---

## 8. Validación de entrada

**No hay un validador central.** Cada script de controlador valida a mano, con `if`,
`trim()`, `empty()` y funciones de PHP, antes de tocar la base de datos.

El caso más completo es el registro (`AuthController`, acción `register`), que acumula
los fallos en un array `$errores`:

```php
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores['correo'] = 'Ingresa un correo valido.';
if (strlen($contrasena) < 6)                     $errores['contrasena'] = 'Minimo 6 caracteres.';
if ($contrasena !== $confirmar)                  $errores['contrasena_confirmation'] = 'No coinciden.';
if (!$habeas)                                    $errores['habeas_data'] = 'Debes aceptar el tratamiento de datos.';
```

Si algo falla, se guardan en `$_SESSION['_errors']` y lo escrito en `$_SESSION['_old']`,
y se vuelve al formulario; la vista los recupera con los helpers `error()` y `old()`, así
el usuario no pierde lo que había tecleado.

Otras validaciones repartidas: el puntaje de una reseña se acota con `max(1, min(5, ...))`,
el método de pago se normaliza a `digital` o `efectivo`, y las cantidades del carrito a
`max(1, (int) ...)`.

---

## 9. Habeas Data (Ley 1581 de 2012, Colombia)

En el registro hay un checkbox obligatorio (`habeas_data`) de autorización de tratamiento
de datos. Sin él la validación falla. Se guarda la fecha en
`CLIENTE.fecha_aceptacion_habeas_data`.

---

## 10. Exposición de carpetas

La idea es que solo `public/` sea alcanzable desde el navegador. Cómo se consigue en la
práctica:

- Si Apache apunta su DocumentRoot a `public/` (dominio `copiway2.test` de Laragon),
  `app/`, `config/` y `database/` quedan **realmente** fuera de la raíz web.
- Si se abre como `http://localhost/Copiway2/public/`, el proyecto entero **sí está**
  dentro de la raíz web; lo único que protege esas carpetas es el `.htaccess` de la raíz,
  que reescribe cualquier petición hacia `public/`.

> ⚠️ Esa segunda forma solo protege mientras `mod_rewrite` esté activo y Apache respete
> los `.htaccess`. La configuración robusta es la primera: apuntar el DocumentRoot a
> `public/`. Además, `config/database.php` tiene las credenciales escritas en el archivo
> (usuario `root`, sin contraseña, que es el estándar de Laragon en desarrollo).

---

## 11. Reglas de negocio que también son seguridad

- **PIN de entrega**: el domiciliario solo puede marcar "entregado" si teclea el PIN de
  4 dígitos que el cliente le muestra (`hash_equals($p['pin_entrega'], $pin)`). Evita
  falsas entregas.
- **Autoasignación sin choques**: al tomar un pedido, el `UPDATE` lleva
  `WHERE estado = 'listo' AND id_domiciliario IS NULL`, así dos repartidores no pueden
  quedarse con el mismo.
- **Cero crédito**: el pago en efectivo queda `pendiente`; el inventario no se descuenta
  hasta que se aprueba.
- **Punto de no retorno**: tras confirmar el pedido no hay ninguna acción de cancelar en
  el panel del cliente.

---

## 12. Puntos débiles conocidos

Lo que hoy **no** está cubierto, para no afirmar de más:

| Punto | Estado |
|---|---|
| **CSRF** | El token se genera y se envía, pero **nunca se valida** (§3) |
| `Auth::requireRole()` | Escrito pero sin uso; con él tampoco se comprueba ya que la cuenta siga existiendo en la BD |
| Página 404 | No hay: el `default` del `switch` sirve la landing, así que cualquier ruta desconocida cae en el inicio |
| Recuperación de contraseña | Simulada: no genera ni valida códigos en `CODIGO_VERIFICACION` |
| Credenciales de la BD | Escritas dentro de `config/database.php`, no en variables de entorno |
| `debug` | `config/config.php` trae `'debug' => true`; en producción debería ir en `false` |
