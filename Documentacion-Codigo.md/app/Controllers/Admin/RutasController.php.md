# `app/Controllers/Admin/RutasController.php`

## Ubicación
`app/Controllers/Admin/RutasController.php` · namespace `App\Controllers\Admin`

## Propósito
**Rutas & Zonas** (`/admin/rutas`): mapa de domiciliarios, flota activa y despachos
recientes.

## Dependencias
`Controller`, `App\Core\Database`, `App\Models\Configuracion`, `App\Models\Pedido`.

## Métodos

### `index(): string` — `GET /admin/rutas`
Trae:
- **`contadores`** — `Pedido::contarPorEstado()`.
- **`flota`** — todos los domiciliarios `activos`, con dos subconsultas:
  `entregas_activas` (pedidos suyos en `en_camino`) y `pedido_actual` (el id del que
  lleva ahora).
- **`despachos`** — últimos 12 pedidos en estado `listo` / `en_camino` / `entregado`,
  con nombre del cliente y del domiciliario. A cada uno le añade `codigo`.
- **`tarifa`** — `Configuracion::value('tarifa_plana_domicilio')`.

Vista `admin/rutas/index` (layout `admin`). La vista dibuja el mapa con `Copiway.map`.

## Notas
- Solo lectura, no tiene métodos POST.
- Las coordenadas de los destinos son aproximadas (`Copiway._geo` a partir de la
  dirección; no hay geocodificación real).
