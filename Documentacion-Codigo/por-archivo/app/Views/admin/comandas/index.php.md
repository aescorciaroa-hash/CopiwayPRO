# `app/Views/admin/comandas/index.php`

## Ubicación
`app/Views/admin/comandas/index.php`

## Propósito
El **tablero de comandas activas** del administrador: todas las órdenes en curso como
tarjetas, agrupadas por estado, con el semáforo de SLA.

Cubre **RF-41** (Tablero Consolidado de Órdenes en Tiempo Real).

## Quién la renderiza
`public/index.php`, `case '/admin/comandas'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$activos` | `Pedido::activos()`, con `codigo` y `lineas` añadidos |
| `$contadores` | `Pedido::contarPorEstado()` |
| `$productos` | `Producto::catalogo()` — para el selector del pedido manual |

## El semáforo de SLA (15 minutos)

```php
$sla = $p['estado'] === 'en_preparacion' && (int) $p['minutos'] > 15;
...
<div class="... border-l-4 border-l-<?= $eColor ?>-500 <?= $sla ? 'sla-vencido' : '' ?>">
```

La clase **`.sla-vencido`** está en `assets/css/app.css`: borde rojo con una sombra que
late (`@keyframes sla-pulse`).

> Es distinta de la animación `kds-sla` del KDS de cocina, que está definida dentro de la
> propia vista `kitchen/index.php`. Hacen lo mismo pero son dos implementaciones separadas.

El campo `minutos` viene de `TIMESTAMPDIFF(MINUTE, fecha_hora, NOW())` en `Pedido::activos()`.

## El componente `comandasPage()`

| Miembro | Qué hace |
|---|---|
| `openManual` / `openDetalle` / `openDir` | Los tres modales |
| `productos` | El catálogo **serializado a JSON desde PHP** con `json_encode`, para armar el pedido manual sin pedir nada al servidor |
| `lineas`, `addLinea()`, `quitarLinea(i)` | Las líneas del pedido manual |
| `subtotal` / `envio` / `total` | Cálculo en vivo. `envio` se interpola desde PHP: `<?= (float) Configuracion::value('tarifa_plana_domicilio', 0) ?>` |
| `verDetalle(id)` | `fetch` a `/admin/comandas/{id}` → `ComandasController`, acción `detalle` |
| `editarDir(id, dir)` | Precarga el modal de corrección de dirección |

## Notas
- Los tres modales están en `_modales.php`, incluido al final.
- El botón "Editar dirección" de cada tarjeta pasa la dirección actual con
  `addslashes()` para no romper el atributo de Alpine.
