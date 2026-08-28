# `app/Controllers/Client/HistorialController.php`

## Ubicación
`app/Controllers/Client/HistorialController.php` · namespace `App\Controllers\Client`

## Propósito
**Historial y Recompras** (`/client/historial`): pedidos ya entregados, calificarlos y
**recomprar en 1 clic**.

## Dependencias
`Controller`, `Auth`, `Session`, `Database`, `App\Models\Pedido`, `App\Models\Producto`,
`App\Models\Carrito`, `App\Models\Cliente`.

## Métodos

### `index(): string` — `GET /client/historial`
1. Lee `?filtro=` (`todos` / `pendientes` / `calificados`).
2. Consulta los pedidos `entregado` del cliente, con `LEFT JOIN RESENA` (para saber si
   ya tienen calificación). Añade `codigo` y `lineas`.
3. Separa `calificados` (con `puntaje`) y `pendientes` (sin `puntaje`).
4. `$lista` según el filtro (`match`).
5. Pasa a la vista: `pedidos`, `filtro`, `totales` (contadores para las pestañas),
   `cliente` (para los puntos), `invertido` (suma de todos sus pedidos).

### `recomprar(string $id): string` — `POST /client/historial/{id}/recomprar`
1. `verifyCsrf()`. Verifica que el pedido sea del cliente.
2. `Pedido::detalle($id)` — las líneas originales.
3. **Valida stock:** si algún producto está agotado → flash "Stock No Disponible" y no
   duplica nada.
4. Por cada línea, reconstruye las personalizaciones (usando `id_ingrediente` real y
   `costo_aplicado`) y `Carrito::agregar(...)`.
5. Flash + `redirect('/client/carrito')`.

### `resena(string $id): string` — `POST /client/historial/{id}/resena`
1. `verifyCsrf()`. Verifica que sea del cliente y esté `entregado`.
2. `puntaje` acotado a 1–5, `comentario` opcional.
3. Si ya hay `RESENA` para ese pedido → `UPDATE`; si no → `INSERT`.
4. Flash "Reseña Registrada".

## Notas
- La recompra falló antes por guardar `id_ingrediente` vacío; ahora usa el id real de
  `Pedido::detalle` (que incluye `pe.id_ingrediente`).
- Una reseña por pedido: la tabla `RESENA` tiene `UNIQUE(id_pedido)`.
