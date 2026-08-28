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
Segundo login. `estacionLogin` compara el PIN enviado con
`Configuracion::value('pin_estacion_kds')` (usando `hash_equals`). Si coincide →
`Session::set('estacion_cocina_ok', true)` → `/kitchen`.

### `index(): string` — `GET /kitchen`
1. `requireEstacion()`.
2. `Pedido::activos()` → los reparte en `$tablero` por estado
   (`pendiente`, `en_preparacion`, `listo`), añadiendo `codigo` y `lineas`.
3. `$resumen` — consulta agregada: cuántas unidades de cada producto hay pendientes
   (para preparar en lote).
4. `$criticos` — `Ingrediente::criticos()` (stock ≤ umbral).
5. Vista `kitchen/index` (layout `kitchen`).

### `preparar(string $id): string` — `POST /kitchen/pedido/{id}/preparar`
`PedidoServicio::cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()])`.

### `listo(string $id): string` — `POST /kitchen/pedido/{id}/listo`
`PedidoServicio::cambiarEstado($id, 'listo')` — avisa a logística.

### `tirilla(string $id): string` — `GET /kitchen/pedido/{id}/tirilla`
`Pedido::completo($id)`, marca `tirilla_impresa = 1`, devuelve la vista
`kitchen/tirilla` (layout `blank`, con `window.print`).

## Notas
- Cada `cambiarEstado` crea también una `NOTIFICACION` para el cliente.
- El KDS no descuenta inventario: eso ya pasó al aprobarse el pago.
