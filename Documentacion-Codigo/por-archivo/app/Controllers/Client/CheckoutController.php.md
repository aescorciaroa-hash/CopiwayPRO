# `app/Controllers/Client/CheckoutController.php`

## Ubicación
`app/Controllers/Client/CheckoutController.php` · namespace `App\Controllers\Client`

## Propósito
El **checkout** (`/client/checkout`): confirmar dirección, método de pago y crear el
pedido.

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Carrito`, `App\Models\Cliente`,
`App\Models\Configuracion`, `App\Models\PedidoServicio`, `App\Models\Pedido`.

## Métodos

### `index(): string` — `GET /client/checkout`
1. Si el carrito está vacío → `redirect('/client/carrito')`.
2. Calcula: `subtotal` (`Carrito::subtotal`), `descuento` (15% si es cumpleaños),
   `envio` (tarifa plana), `total = max(0, subtotal - descuento) + envio`.
3. `estado` = `Configuracion::estadoCocina()` (la vista bloquea "Pagar" si está cerrada).
4. Vista `client/checkout`.

### `confirmar(): string` — `POST /client/checkout`
1. `verifyCsrf()`. Si el carrito está vacío → redirect.
2. **Si la cocina está cerrada** → flash con el horario + `redirect('/client/checkout')`
   (regla "Horarios Automáticos").
3. Valida que haya `direccion`. Lee `metodo_pago` (`digital` o `efectivo`).
4. Recalcula el descuento de cumpleaños (no se fía del formulario).
5. `PedidoServicio::crear([...
     'canal_origen' => 'web',
     'metodo_pago'  => $metodo,
     'comprobante'  => $metodo === 'digital' ? 'PAGO-XXXXXXXX' : null,
     'aprobar_pago' => $metodo === 'digital',   // digital entra directo a cocina
     'lineas'       => Carrito::aLineas(),
   ])`.
6. `Carrito::vaciar()`.
7. Flash "Pedido Confirmado · Punto de no retorno activado" + `redirect('/client/ordenes')`.

## Notas
- El pago **digital** se aprueba al confirmar (dispara el trigger de inventario).
- El pago **efectivo** queda pendiente hasta que el domiciliario entrega y cobra.
- El descuento de cumpleaños se calcula en el servidor, nunca se confía en el cliente.
