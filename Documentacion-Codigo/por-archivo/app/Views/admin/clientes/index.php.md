# `app/Views/admin/clientes/index.php`

## Ubicación
`app/Views/admin/clientes/index.php`

## Propósito
El **Directorio de Clientes**: la base de clientes con su gasto y número de pedidos, y un
modal con el historial de cada uno.

Cubre **RF-38** (Consulta de Base de Clientes con Historial de Pedidos).

## Quién la renderiza
`public/index.php`, `case '/admin/clientes'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$clientes` | `Cliente::directorio($buscar)` — con `pedidos` y `total_gastado` calculados por subconsulta |

La búsqueda llega por `?q=` y la resuelve `public/index.php`; el formulario es un `GET`
normal, no AJAX.

## La tabla
Nombre, contacto, número de pedidos, total gastado, puntos de fidelidad y un botón
"Ver historial".

Los totales excluyen los pedidos `cancelado` (está en el SQL de `Cliente::directorio()`).

## El componente `clientesPage()`

```js
async verHistorial(id) {
    const res = await fetch('<?= url('/admin/clientes') ?>/' + id + '/historial');
    const data = await res.json();
    this.cliente = data.cliente; this.pedidos = data.pedidos;
    this.open = true;
    this.$nextTick(() => lucide.createIcons());
}
```

El JSON lo produce `ClientesController`, acción `historial`, que **elimina el campo
`contrasena`** antes de responder.

El `$nextTick(() => lucide.createIcons())` es necesario porque los iconos del contenido
recién insertado por Alpine aún no existían cuando Lucide hizo su pasada inicial. Es un
patrón que se repite en todos los modales cargados por `fetch`.

## Notas
- Es una pantalla de **solo lectura**: desde aquí no se edita ni se borra ningún cliente.
- Los clientes creados por un pedido manual también salen en el directorio, con el correo
  inventado `<telefono>@manual.copiway`.
