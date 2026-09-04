# `app/Views/client/creador.php`

## Ubicación
`app/Views/client/creador.php`

## Propósito
El **Creador Interactivo** ("Arma tu Burger"): el cliente elige ingredientes y ve la
hamburguesa apilarse capa por capa, con el precio actualizándose en vivo.

Cubre **RF-08** (Creador Interactivo / Minijuego).

## Quién la renderiza
`public/index.php`, `case '/client/creador'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$ingredientes` | `Producto::ingredientesCreador()` — con `precio` ya calculado, `categoria`, `cantidad_stock` y `agotado` |

La vista los reagrupa por categoría antes de pintar:
```php
$porCategoria = [];
foreach ($ingredientes as $i) { $porCategoria[$i['categoria']][] = $i; }
```

## Estructura

Un solo `<form method="post" action="/client/creador/agregar">` en rejilla de 3 columnas:

- **Columna 1 — la torre.** Se dibuja con `flex-col-reverse` (así las capas nuevas se
  apilan hacia arriba): pan de abajo → las capas → pan de arriba. Debajo, el contador de
  capas, el precio total y el botón "AGREGAR AL CARRITO", deshabilitado si no hay capas.
- **Columnas 2-3 — los ingredientes**, agrupados por categoría, cada uno como una casilla
  `name="capa[]"`.

## Los ingredientes agotados se bloquean solos

```php
<input type="checkbox" name="capa[]" ... <?= $ing['agotado'] ? 'disabled' : '' ?>>
```
Además la tarjeta se atenúa y muestra `AGOTADO` en rojo en vez de las unidades disponibles.

## El componente `creador()`

| Miembro | Qué hace |
|---|---|
| `capas` | Array de `{id, nombre, precio}` |
| `toggle(e, ing)` | Añade o quita según el estado de la casilla |
| `total` | Suma de los precios de las capas |
| `color(i)` | Un color de una paleta de 7, rotando con `i % 7`, para que cada capa se vea distinta |
| `money(v)` | Formato de pesos en el navegador |

## Notas
- El precio de cada capa lo calcula `Producto::ingredientesCreador()` en el servidor:
  el `precio_extra` del ingrediente si lo tiene, o
  `costo_unitario * (1 + margen_ganancia_defecto / 100)`.
- Al enviar, `CreadorController` reutiliza (o crea) el producto oculto
  **"Hamburguesa Personalizada"** y guarda todas las capas como extras.
- El precio que se ve aquí es orientativo; el definitivo lo recalcula el servidor.
