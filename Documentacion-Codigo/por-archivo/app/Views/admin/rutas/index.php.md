# `app/Views/admin/rutas/index.php`

## Ubicación
`app/Views/admin/rutas/index.php`

## Propósito
**Despacho y Rutas** (`/admin/rutas`): KPIs de logística, mapa de la flota, estado de cada
domiciliario y tabla de despachos recientes.

Cubre **RF-49** (mapa de domiciliarios en tiempo real).

## Quién la renderiza
`public/index.php`, `case '/admin/rutas'`, con el layout **`admin`**.
Es lo que antes hacía `RutasController`.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$contadores` | `Pedido::contarPorEstado()` |
| `$flota` | Los `Empleado::todos()` filtrados a `rol === 'domiciliario'` |
| `$despachos` | Los pedidos activos en estado `en_camino` |
| `$tarifa` | `Configuracion::value('tarifa_plana_domicilio', 6000)` |

## Secciones

1. **4 KPIs** — En Camino, Listos en Cocina, Repartidores Activos y Tarifa de Envío.
   Se pintan desde un array en línea, sin repetir el marcado.
2. **Mapa** (`#mapa-rutas`) — `Copiway.map()` con un marcador naranja para la sede
   (`COPIWAY_SEDE`) y uno azul por cada despacho `en_camino`. Las direcciones se pasan
   como texto y `Copiway._geo()` les inventa unas coordenadas estables.
3. **Flota de Domiciliarios** — nombre, badge de estado, vehículo y placa.
4. **Despachos Recientes** — tabla con `estado_badge()` para el color del estado.

## ⚠️ Esta vista hace consultas SQL

En el bucle de la flota:

```php
<?= !empty($d['pedido_actual']) ? ' · lleva ' . e((new Pedido())->codigo((new Pedido())->find($d['pedido_actual']))) : '' ?>
```

Instancia el modelo `Pedido` **dos veces por fila** y lanza una consulta por domiciliario.
Rompe la regla "nada de SQL en las vistas" y es un caso claro de **N+1**: lo correcto sería
que `public/index.php` trajera el código del pedido ya resuelto junto con la flota.

Con la flota pequeña de este proyecto no se nota, pero es el punto que hay que arreglar si
el panel se pone lento.

## Otro detalle a vigilar

El badge de estado compara contra dos valores para "disponible":

```php
$est === 'disponibles' || $est === 'disponible' ? 'bg-emerald-100 ...' : ...
```

`'disponibles'` en plural **no existe** en la base de datos: el `ENUM` solo tiene
`disponible`, `en_ruta` y `desconectado`. Es una comprobación de más, inofensiva.

## Notas
- "GPS Activo" es un adorno: no hay geolocalización real de los repartidores.
- El indicador de coordenadas es aproximado; ver `public/assets/js/app.js.md`.
