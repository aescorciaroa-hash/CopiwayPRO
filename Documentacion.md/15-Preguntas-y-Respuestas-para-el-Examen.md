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
Navegador → `public/index.php` (o envío directo a un controlador) → Carga `config/database.php` (`$conn`) → Controlador Script → Modelos (`mysqli`) → Vista → HTML / Redirección.

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

**¿Cómo se protege contra CSRF?** Token secreto por sesión en cada formulario (`csrf_field()`).

**¿Cómo se guardan las contraseñas?** Con `password_hash()` (bcrypt) y se comprueban con `password_verify()`. Nunca en texto plano.

---

## Preguntas "trampa" frecuentes

**¿La lógica de descontar inventario está en PHP?**
No. Está en el **trigger `trg_pago_aprobado`** de la base de datos. PHP solo hace
`UPDATE PAGO SET estado='aprobado'`.

**¿El carrito se guarda en la base de datos?**
No. Vive en `$_SESSION['carrito']` mientras el cliente navega. Solo al confirmar el
checkout se crean registros en `PEDIDO`, `DETALLE_PEDIDO`, etc.

**¿Qué framework usa?**
Ninguno. Es un esquema **MVC Simplificado y Tradicional** en PHP puro con `mysqli`.

**¿Qué pasa si falla un INSERT al crear un pedido?**
La transacción hace `$conn->rollback()`: se deshacen todos los INSERTs anteriores. Es "todo o nada".

