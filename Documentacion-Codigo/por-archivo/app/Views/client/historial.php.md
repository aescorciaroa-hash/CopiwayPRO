# `app/Views/client/historial.php`

## Ubicación
`app/Views/client/historial.php`

## Propósito
Los **pedidos pasados**: KPIs, filtros, reseñas de 1 a 5 estrellas y la recompra en 1 clic.

Cubre **RF-09** (Recompra en 1 Clic) y **RF-19** (Calificaciones y Reseñas).

## Quién la renderiza
`public/index.php`, `case '/client/historial'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$pedidos` | El historial **ya filtrado** en `public/index.php` |
| `$filtro` | `todos` / `pendientes` / `calificados` (de `?filtro=`) |
| `$totales` | Cuántos hay en cada filtro (para los contadores de los chips) |
| `$cliente` | Para los puntos de fidelidad |
| `$invertido` | Suma de los totales, excluyendo cancelados |

El filtrado se hace **en PHP**, en `public/index.php`; los chips son enlaces normales que
recargan la página con `?filtro=`.

## Las tres tarjetas de KPI
Pedidos · Puntos Copiway · Invertido.

## Cada pedido

- Código, badge "Entregado", total y los puntos ganados (`floor($p['total'] / 1000)`,
  la misma fórmula que el trigger `trg_pago_aprobado`).
- Fecha y quién lo entregó.
- Las líneas del pedido.
- Si ya tiene `puntaje`: las 5 estrellas rellenas hasta la nota, el comentario y el sello
  "Reseña Verificada".
- Botones: **"Calificar Pedido"** (solo si no tiene puntaje) y **"Recompra en 1 clic"**.

## El modal de reseña

Un único modal compartido por todos los pedidos. El botón de cada tarjeta le pasa el id:

```php
@click="resenaOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; puntaje = 5; hover = 0"
```

y el formulario compone su destino en el navegador:
```php
<form :action="'<?= url('/client/historial') ?>/' + pedidoId + '/resena'" method="post">
```

Las estrellas usan dos variables: `puntaje` (lo elegido) y `hover` (lo que se está
señalando con el ratón). Se pintan con `n <= (hover || puntaje)`, que es el truco para la
previsualización al pasar por encima.

## Apertura directa desde la URL
```php
$abrirResena = isset($_GET['calificar']) ? (string) $_GET['calificar'] : '';
```
Con `/client/historial?calificar={id}` el modal se abre solo, ya apuntando a ese pedido.

## Notas
- La recompra **valida el stock antes de añadir nada**: si una línea está agotada,
  `HistorialController` aborta y no mete nada al carrito.
- Una reseña por pedido: la tabla `RESENA` tiene `UNIQUE(id_pedido)` y el controlador hace
  *upsert*.
