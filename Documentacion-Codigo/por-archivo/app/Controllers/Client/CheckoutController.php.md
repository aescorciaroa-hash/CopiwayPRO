# `app/Controllers/Client/CheckoutController.php`

## Ubicación
`app/Controllers/Client/CheckoutController.php`

## Propósito
Script procesador del **checkout**: convierte el carrito en un `PEDIDO` real.
Es el punto donde se aplican "cocina cerrada", el descuento de cumpleaños y el
**punto de no retorno**.

La pantalla `/client/checkout` (GET) la arma `public/index.php`, que ya calcula ahí el
subtotal, el envío, el descuento, el total y el estado de la cocina.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Carrito.php`, `Models/Cliente.php`, `Models/Configuracion.php`,
`Models/PedidoServicio.php`, `Models/Pedido.php`.

## Acciones (`$action`)

### `confirmar` — `POST /client/checkout`
`public/index.php` pone `$_POST['action'] = 'confirmar'` si el formulario no manda otra cosa.

1. Si el carrito está vacío → `/client/carrito`.
2. `Configuracion::estadoCocina()`; si `!$estado['abierta']` → flash con el horario y
   vuelve a `/client/checkout` (**bloqueo por horario / pausa de emergencia**).
3. Exige `direccion` no vacía.
4. `metodo_pago` se normaliza a `digital` o `efectivo` (cualquier otro valor cae en `efectivo`).
5. Calcula el descuento de cumpleaños: `round($subtotal * 0.15)`.
6. `PedidoServicio::crear([...])` con:
   - `canal_origen => 'web'`,
   - `comprobante` generado (`PAGO-XXXXXXXX`) solo si el pago es digital,
   - **`aprobar_pago => ($metodo === 'digital')`** — esta es la regla de *cero crédito*:
     el pago en efectivo queda `pendiente` hasta que el domiciliario entrega.
7. `Carrito::vaciar()` y redirige a `/client/ordenes` con el código del pedido en el flash.

Cualquier otro `$action` → `redirect('/client/checkout')`.

## Notas
- Aprobar el pago dispara el trigger `trg_pago_aprobado` (descuenta inventario y suma
  puntos). Ver `guia/14-Ciclo-de-Vida-de-un-Pedido.md`.
- Tras este POST ya no hay ninguna acción de cancelar en el panel del cliente: es el
  **punto de no retorno**.
