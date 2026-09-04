# `app/Views/partials/admin-notificaciones.php`

## Ubicación
`app/Views/partials/admin-notificaciones.php`

## Propósito
La **campana de notificaciones** de la cabecera del admin: un desplegable con los pedidos
recientes y los insumos en stock crítico, con un contador rojo sobre el icono.

Cubre **RF-40** (Panel de Notificaciones del Administrador).

## Quién lo usa
`layouts/admin.php`, en la cabecera.

## ⚠️ Es la única vista que consulta la base de datos

Todas las demás reciben los datos ya preparados desde `public/index.php`. Esta hace sus
propias consultas al principio del archivo:

```php
global $conn;

// Pedidos de las ultimas 12 horas (max 6)
$resN = $conn->query("SELECT p.id_pedido, p.fecha_hora, p.canal_origen, cl.nombre
                      FROM PEDIDO p JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente
                      WHERE p.fecha_hora >= NOW() - INTERVAL 12 HOUR
                      ORDER BY p.fecha_hora DESC LIMIT 6");

// Insumos por debajo del umbral (max 6)
$resC = $conn->query("SELECT nombre, cantidad_stock, unidad_medida FROM INGREDIENTE
                      WHERE cantidad_stock <= umbral_minimo LIMIT 6");

$total = count($nuevos) + count($criticos);
```

Se hizo así para no tener que repetir esas dos consultas en los **8 `case`** de `/admin`
de `public/index.php`. Es una excepción consciente a la regla "nada de SQL en las vistas".

Son `query()` sin preparar, pero **no reciben ningún dato del usuario**: las dos consultas
son constantes.

> Estas notificaciones **no leen la tabla `NOTIFICACION`**. Esa tabla la llena
> `PedidoServicio::cambiarEstado()` y está pensada para el cliente; esta campana se
> calcula al vuelo desde `PEDIDO` e `INGREDIENTE`.

## Qué pinta

| Bloque | Icono | Texto |
|---|---|---|
| Pedido reciente | `shopping-bag` naranja | "Nuevo pedido de **{cliente}**" + hora (`h:i a`) y canal |
| Insumo crítico | `alert-triangle` rojo | "Inventario critico: **{insumo}**" + "Quedan X {unidad}" |
| Sin nada | — | "Sin novedades por ahora." |

El contador del badge es `$total`, y solo aparece si es mayor que 0.

## Detalles

- El desplegable usa Alpine: `x-data="{ open: false }"`, `@click.outside="open = false"`
  para cerrarlo al pulsar fuera, y `x-cloak` para que no se vea antes de que Alpine cargue.
- La lista tiene `max-h-80 overflow-y-auto`, así que hace scroll si hay muchas.
- Las cantidades se imprimen sin ceros sobrantes con el doble `rtrim`.

## Notas
- Es informativo: **los elementos no son enlaces**, no llevan a ningún sitio.
- No hay estado "leído": el contador refleja siempre lo que cumpla las dos condiciones.
