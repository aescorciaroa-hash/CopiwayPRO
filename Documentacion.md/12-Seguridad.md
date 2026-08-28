# 12 · Seguridad

El sistema aplica varias capas de protección. Todas son preguntas típicas de examen.

---

## 1. Inyección SQL → **consultas preparadas (PDO)**

**Ataque:** el usuario escribe `' OR '1'='1` en un campo para colarse.

**Defensa:** nunca se concatena texto del usuario en el SQL. Se usan `?` y los valores
van en un array aparte:

```php
Database::one("SELECT * FROM CLIENTE WHERE correo = ?", [$correo]);
```

MySQL recibe la consulta y los datos **por canales separados**; los datos jamás se
interpretan como SQL. Además `PDO::ATTR_EMULATE_PREPARES => false` fuerza *prepares*
reales del servidor.

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

## 3. CSRF (Cross-Site Request Forgery) → **token en cada formulario**

**Ataque:** otra web hace que tu navegador envíe, sin que lo sepas, un
`POST /logout` o `POST /admin/personal/.../eliminar` aprovechando tu sesión.

**Defensa:**
- Al abrir un formulario se incluye un token secreto:
  ```php
  <?= csrf_field() ?>   // <input type="hidden" name="_csrf" value="a1b2c3...">
  ```
- El token se genera una vez por sesión: `bin2hex(random_bytes(32))`.
- Cada `POST` pasa por `verifyCsrf()`:
  ```php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && !Session::checkCsrf($_POST['_csrf'] ?? null)) {
      http_response_code(419);
      exit('Token de seguridad inválido...');
  }
  ```
- La comparación usa `hash_equals()` (tiempo constante, no filtra información por el
  tiempo de respuesta).

Una web ajena no puede conocer tu token → su petición falsa es rechazada con **419**.

---

## 4. Contraseñas → **hash bcrypt**

- Al registrar: `password_hash($clave, PASSWORD_BCRYPT)` → texto de 60 caracteres.
- Al entrar: `password_verify($loQueEscribio, $hashGuardado)`.
- **Nunca** se guarda ni se puede recuperar la contraseña original.
- bcrypt es lento a propósito + añade una *sal* aleatoria → dos claves iguales tienen
  hash distinto y no se pueden precalcular (rainbow tables).

---

## 5. Control de acceso por rol (RBAC)

- Cada ruta protegida declara su `role` en `routes/web.php`.
- El Router llama `Auth::requireRole($role)` **antes** del controlador.
- Comprueba: (1) hay sesión, (2) el rol coincide, (3) la cuenta aún existe en la BD.
- Si falla: redirige a `/login` o muestra **403 Prohibido**.

Un cliente que escriba `/admin` a mano recibe un 403. No hay forma de saltárselo porque
el chequeo está en el flujo central, no en cada vista.

---

## 6. Sesiones seguras

- `session_regenerate_id(true)` al iniciar sesión → evita **session fixation** (que
  alguien te fije un ID de sesión conocido antes de que entres).
- La cookie de sesión (`PHPSESSID`) no viaja en la URL.
- `Auth::logout()` borra rol, usuario y carrito.

---

## 7. Segundo factor para estaciones compartidas

Cocina y Domiciliario usan tablets compartidas. Además del login normal, piden un
**PIN de estación** (`pin_estacion_kds` / `pin_estacion_domiciliario`). Sin ese flag
(`estacion_cocina_ok` / `estacion_domi_ok`) en la sesión, el panel redirige al PIN.

---

## 8. Validación de entrada

`Controller::validate()` revisa todos los datos antes de tocar la base de datos:
obligatorios, formato de correo, longitudes, fechas, opciones válidas, confirmación de
contraseña, aceptación de habeas data. Si algo falla, se vuelve al formulario con los
mensajes y los valores anteriores (sin perder lo que el usuario escribió).

---

## 9. Habeas Data (Ley 1581 de 2012, Colombia)

En el registro hay un checkbox obligatorio (`accepted`) de autorización de tratamiento
de datos. Se guarda la fecha en `CLIENTE.fecha_aceptacion_habeas_data`.

---

## 10. `public/` como única carpeta expuesta

`config/`, `app/`, `database/` están **fuera de la raíz web**. Aunque alguien conozca
la ruta, no puede pedir `config/config.php` por el navegador y leer la contraseña de
la base de datos.

---

## 11. Reglas de negocio que también son seguridad

- **PIN de entrega**: el domiciliario solo puede marcar "entregado" si teclea el PIN de
  4 dígitos que el cliente le muestra (`hash_equals`). Evita falsas entregas.
- **Cero crédito**: el pedido no entra a cocina hasta que el pago está `aprobado`.
- **Punto de no retorno**: tras confirmar el pago no se puede cancelar desde el cliente.
