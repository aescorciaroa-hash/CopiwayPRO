# `app/Models/PedidoServicio.php`

## Ubicación
`app/Models/PedidoServicio.php`

## Propósito
La capa de **escritura** de pedidos: crearlos (con transacción), aprobar el pago,
cambiar de estado y corregir la dirección. Es la contraparte de `Pedido`, que solo lee.

## Cómo se usa
```php
require_once __DIR__ . '/../Models/PedidoServicio.php';
$servicioModel = new PedidoServicio();
$idPedido = $servicioModel->crear([...]);
```

## Métodos

### `crear(array $datos): string` — el más importante
Registra el pedido completo **dentro de una transacción MySQLi**
(`begin_transaction` / `commit` / `rollback` en un `try/catch`). Si cualquier `INSERT`
falla, se deshace todo: es "todo o nada".

Entrada esperada:
```php
[
  'id_cliente'           => '...',
  'direccion_entrega'    => 'Calle 10 # 5-20',
  'canal_origen'         => 'web' | 'llamada' | 'whatsapp',   // por defecto 'web'
  'metodo_pago'          => 'digital' | 'efectivo',           // por defecto 'efectivo'
  'descuento_cumpleanos' => 0.0,
  'comprobante'          => 'PAGO-XXXXXXXX' | null,
  'aprobar_pago'         => true | false,
  'lineas'               => [ ['id_producto', 'cantidad', 'personalizaciones' => [...]], ... ],
]
```

Pasos:

1. Lee la **tarifa de domicilio** de `Configuracion::get()`.
2. Calcula el subtotal recorriendo las líneas: `(precio del producto + Σ extras) * cantidad`.
   Los extras solo suman si su `accion` es `agregar` (los `quitar` no descuentan).
3. `total = max(0, subtotal - descuento) + tarifa` — el descuento **no** se aplica al envío.
4. Genera el `id_pedido` con `uuid()` y el **PIN de entrega en PHP**:
   ```php
   $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
   ```
5. `INSERT INTO PEDIDO` con estado `'pendiente'`.
6. Por cada línea: `INSERT INTO DETALLE_PEDIDO` y, por cada personalización con
   `id_ingrediente`, un `INSERT INTO PERSONALIZACION` (`accion_modificacion` +
   `costo_aplicado`).
7. `INSERT INTO PAGO` con estado `'pendiente'` y `fecha_pago` a `NULL`.
8. Si `metodo === 'digital'` **o** `aprobar_pago` viene con valor → llama `aprobarPago()`.
9. `commit()` y devuelve el `id_pedido`.

> El **PIN lo genera PHP**, no el trigger. `trg_pedido_pin` solo actúa si el PIN llega
> vacío, y aquí nunca llega vacío. El trigger queda como red de seguridad para inserciones
> hechas a mano por SQL.

### `aprobarPago(string $idPedido): void`
```sql
UPDATE PAGO SET estado = 'aprobado' WHERE id_pedido = ? AND estado <> 'aprobado'
```

Es **la línea que dispara todo el trabajo automático**: el trigger `trg_pago_aprobado`
descuenta el inventario según las recetas (restando los SIN, sumando los EXTRA) y suma
los puntos de fidelidad. La condición `estado <> 'aprobado'` evita aprobar dos veces y
descontar el inventario por duplicado.

Se llama desde tres sitios:
- checkout con pago **digital** → al confirmar;
- **pedido manual** del admin → al crearlo;
- **entrega en efectivo** → cuando el domiciliario valida el PIN.

### `cambiarEstado(string $idPedido, string $estado, array $extra = []): void`
Tres variantes de `UPDATE` según lo que traiga `$extra`:

| `$extra` | SQL |
|---|---|
| `['id_domiciliario' => ...]` | `SET estado = ?, id_domiciliario = ?` |
| `['id_ayudante' => ...]` | `SET estado = ?, id_ayudante = COALESCE(id_ayudante, ?)` |
| vacío | `SET estado = ?` |

El `COALESCE` hace que el ayudante quede registrado **solo la primera vez**: si otro
cocinero mueve la tarjeta después, no se sobrescribe quién la tomó.

Además, **siempre** inserta una fila en `NOTIFICACION`:
```php
$msg = 'Tu pedido ahora esta: ' . str_replace('_', ' ', $estado);
```

### `clienteParaManual(string $nombre, string $telefono): string`
Para los pedidos que entran por teléfono o WhatsApp. Busca un `CLIENTE` **por teléfono**;
si existe devuelve su id, y si no crea uno mínimo:

- `nombre` → el que den, o `'Cliente manual'`;
- `correo` → `<telefono>@manual.copiway` (inventado, para cumplir el `UNIQUE`);
- `contrasena` → el hash bcrypt de un `uuid()` aleatorio, **imposible de adivinar**
  (ese cliente no puede iniciar sesión);
- `fecha_nacimiento` → `'2000-01-01'`;
- `fecha_aceptacion_habeas_data` → `NOW()`.

### `editarDireccion(string $idPedido, string $direccion): void`
`UPDATE PEDIDO SET direccion_entrega = ?`. Lo usa el admin desde el tablero de comandas.

## Notas
- Es el único modelo que abre transacciones junto con `CierreCaja`.
- El `catch (\Throwable $e)` hace `rollback()` y **relanza** la excepción, así que un
  fallo no queda en silencio.
- La regla "Cero Crédito" vive en el paso 8: el pedido en efectivo entra a cocina, pero
  su pago sigue `pendiente` y **el inventario no se descuenta** hasta la entrega.
