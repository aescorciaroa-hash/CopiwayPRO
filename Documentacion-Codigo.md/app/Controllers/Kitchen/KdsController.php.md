# `app/Controllers/Kitchen/KdsController.php`

## Ubicación
`app/Controllers/Kitchen/KdsController.php` · namespace `App\Controllers\Kitchen`

## Propósito
El **KDS** (*Kitchen Display System*): la pantalla de cocina (`/kitchen`). Muestra los
pedidos por estado y deja marcarlos "en preparación" y "listos", e imprimir la tirilla.

## Dependencias
`Controller`, `Auth`, `Session`, `Database`, `App\Models\Pedido`,
`App\Models\PedidoServicio`, `App\Models\Ingrediente`, `App\Models\Configuracion`.

## Método privado

### `requireEstacion(): void`
Si `Session::get('estacion_cocina_ok')` no está → `redirect('/kitchen/estacion')`.
Se llama al inicio de cada método (segundo factor por PIN de estación).

## Métodos

### `estacionForm()` / `estacionLogin()` — `/kitchen/estacion`
Segundo login de estación en tarjeta flotante limpia (`rounded-[32px]`, `shadow-2xl` sobre `#f8fafc`). `estacionLogin` compara el PIN enviado con `Configuracion::value('pin_estacion_kds')` (usando `hash_equals`). Si coincide → `Session::set('estacion_cocina_ok', true)` → `/kitchen`.

### `index(): string` — `GET /kitchen`
1. `requireEstacion()`.
2. `Pedido::activos()` → los reparte en `$tablero` por estado de 3 columnas (`pendiente`, `en_preparacion`, `listo`), añadiendo `codigo` y `lineas`.
3. Renderiza la barra superior interactiva con cronómetro de tiempo promedio (8.5 min), botón de activación de sonido de alertas y reloj digital en vivo.
4. `$resumen` — consulta agregada en sidebar: cuántas unidades de cada producto hay pendientes (para preparar en lote).
5. `$criticos` — `Ingrediente::criticos()` (desplegable de inventario crítico en sidebar).
6. Vista `kitchen/index` (layout `kitchen` con interfaz clara `#f8fafc`).

### `preparar(string $id): string` — `POST /kitchen/pedido/{id}/preparar`
`PedidoServicio::cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()])`. Mueve el pedido a la columna central con borde de resplandor rojo (`border-2 border-red-500`).

### `listo(string $id): string` — `POST /kitchen/pedido/{id}/listo`
`PedidoServicio::cambiarEstado($id, 'listo')` — mueve la orden a la columna de Listos y avisa a logística.

### `tirilla(string $id): string` — `GET /kitchen/pedido/{id}/tirilla`
`Pedido::completo($id)`, marca `tirilla_impresa = 1`, devuelve la vista `kitchen/tirilla` (layout `blank`, con `window.print`).

## Notas
- Cada `cambiarEstado` crea también una `NOTIFICACION` para el cliente.
- El KDS no descuenta inventario: eso ya pasó al aprobarse el pago.
