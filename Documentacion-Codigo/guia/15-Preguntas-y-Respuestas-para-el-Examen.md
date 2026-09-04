# 15 · Preguntas y respuestas para el examen

Respuestas cortas, listas para repasar.

---

## Arquitectura

**¿Qué es MVC?**
Un patrón que separa el código en 3: **Modelo** (datos y reglas), **Vista**
(presentación/HTML) y **Controlador** (coordina: procesa la petición `$_POST`/`$_GET`, pide datos al modelo y redirige o renderiza).

**¿Cómo se conecta a la base de datos?**
Con la extensión `mysqli` estándar de PHP mediante la variable global `$conn` definida en `config/database.php`.

**¿Cómo llega una petición desde el navegador hasta la respuesta?**
Navegador → `public/index.php` (**siempre**, el `.htaccess` manda todo ahí) → carga
`config/database.php` (`$conn`), el núcleo y los modelos → **guardia de acceso por rol**
→ si es POST, `require` del script de controlador; si es GET, el `case` del `switch`
→ Modelos (`mysqli`) → Vista + layout → HTML, o `redirect()` si era POST.

**¿Hay un archivo de rutas o una clase `Router`?**
No. El "enrutador" son los `if` y el `switch ($uri)` de `public/index.php`.

**¿Los controladores son clases?**
No. Son **scripts planos** que se ejecutan de arriba abajo y ramifican según `$action`.
No hay clase base `Controller`, ni `$this->view()`, ni namespaces, ni autoload: todo se
carga con `require_once`.

**¿Dónde está la lógica de una pantalla como el tablero del admin?**
En `public/index.php`, dentro de su `case`. Las pantallas GET no tienen controlador.

**¿Qué hace un Modelo en este proyecto?**
Contiene las consultas SQL a la base de datos usando sentencias preparadas nativas de `mysqli` (`prepare`, `bind_param`, `execute`, `get_result`).

---

## PHP

**¿Cómo se consulta la base de datos con MySQLi de forma segura?**
Mediante **consultas preparadas** con `$conn->prepare(...)`, vinculando los parámetros con `$stmt->bind_param(...)` y ejecutando con `$stmt->execute()`. Esto previene la inyección SQL.

**¿Qué es `$this->conn`?**
Es la propiedad del modelo donde se inyecta la conexión global de MySQLi (`global $conn; $this->conn = $conn;`) en el constructor.

**¿Qué es `??`?**
*Null coalescing*: `$a ?? $b` → si `$a` existe y no es null, usa `$a`; si no, `$b`.

---

## Base de datos

**¿Qué extensión de BD se utiliza?**
La extensión `mysqli` nativa de PHP (sin PDO ni patrón Singleton).

**¿Qué es una consulta preparada y por qué se usa?**
Una consulta con marcadores `?` cuyos valores se envían y vinculan explícitamente (`$stmt->bind_param(...)`). Evita la **inyección SQL** porque los valores enviados no se interpretan como código ejecutable.

**Nombra los triggers de este sistema.**
- `trg_id_*` (uno por tabla): generan el UUID de la llave si viene vacía.
- `trg_pedido_pin`: genera el id y el PIN de 4 dígitos del pedido.
- `trg_pago_aprobado`: cuando el pago pasa a `aprobado`, descuenta el inventario según las recetas (restando los "SIN" y sumando los "EXTRA") y suma los puntos de fidelidad (`FLOOR(total/1000)`).

**¿Cómo se manejan las transacciones?**
Con los métodos nativos de MySQLi: `$conn->begin_transaction()`, `$conn->commit()` y `$conn->rollback()`.

---

## Seguridad

**¿Cómo se protege contra inyección SQL?** Consultas preparadas de MySQLi (`prepare` + `bind_param`).

**¿Cómo se protege contra XSS?** Escapando toda salida con `e()` (`htmlspecialchars`).

**¿Cómo se protege contra CSRF?** Se genera un token secreto por sesión y se pone en
cada formulario con `csrf_field()`. ⚠️ **Pero hoy nadie lo verifica**:
`Session::checkCsrf()` existe y no se llama en ningún punto, así que la protección no
está activa. Ver `12-Seguridad.md` §3.

**¿Cómo se controla que un cliente no entre a `/admin`?** Con el guardia del principio de
`public/index.php`: deduce el rol exigido del prefijo de la ruta y, si no coincide,
**redirige** al panel propio del usuario con `Auth::homeFor()` (no devuelve 403).

**¿Cómo se guardan las contraseñas?** Con `password_hash()` (bcrypt) y se comprueban con `password_verify()`. Nunca en texto plano.

---

## Preguntas "trampa" frecuentes

**¿La lógica de descontar inventario está en PHP?**
No. Está en el **trigger `trg_pago_aprobado`** de la base de datos. PHP solo hace
`UPDATE PAGO SET estado='aprobado'`.

**¿El carrito se guarda en la base de datos?**
No. Vive en `$_SESSION['carrito']` mientras el cliente navega, y `Auth::logout()` lo
borra. Solo al confirmar el checkout se crean registros en `PEDIDO`, `DETALLE_PEDIDO`, etc.

> Ojo: el requerimiento **RF-20** ("Persistencia del Carrito") pide sincronizarlo con la
> base de datos. Eso **no está implementado**. Si te preguntan por RF-20, la respuesta
> honesta es que el carrito persiste durante la sesión, no entre dispositivos.

**¿Qué framework usa?**
Ninguno. Es un esquema **MVC Simplificado y Tradicional** en PHP puro con `mysqli`.

**¿Qué pasa si falla un INSERT al crear un pedido?**
La transacción hace `$conn->rollback()`: se deshacen todos los INSERTs anteriores. Es "todo o nada".

