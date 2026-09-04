# `app/Views/client/catalogo.php`

## Ubicación
`app/Views/client/catalogo.php`

## Propósito
El **catálogo del cliente**: la rejilla de productos con filtro por categoría, el aviso de
cumpleaños, el botón de recompra rápida y el modal de personalización.

## Quién la renderiza
`public/index.php`, `case '/client'` (y `'/client/catalogo'`), con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$productos` | `Producto::catalogo()` — solo activos, con el flag `agotado` calculado |
| `$categorias` | `Categoria::menu()` |
| `$cliente` | `Cliente::find($yo['id_cliente'])` |
| `$cumple` | `Cliente::esCumpleanos($cliente)` |
| `$ultimoPedido` | El `id_pedido` más reciente del historial, o `null` |

## Secciones

1. **Banner de cumpleaños** — solo si `$cumple`. Anuncia el 15% automático.
2. **Cabecera** + botón "Pedir lo mismo de la última vez" (solo si hay `$ultimoPedido`);
   es un `POST` a `/client/historial/{id}/recomprar`.
3. **Chips de categoría** — "Todas" más una por categoría.
4. **Rejilla de productos** (hasta 4 columnas en `xl`).
5. **Botón flotante** "Ver carrito".
6. **`_modal_personalizar.php`**, incluido al final.

## El filtro de categorías es solo del navegador

No recarga la página ni consulta el servidor: **todos** los productos se pintan siempre y
Alpine oculta los que no tocan.

```php
<button @click="cat = '<?= e($c['id_categoria']) ?>'">
...
<div x-show="cat === 'todas' || cat === '<?= e($p['id_categoria']) ?>'" x-transition>
```

## Las tarjetas de producto

- Si `$p['agotado']`, la tarjeta se atenúa (`opacity-70`), se le superpone el
  `<div class="badge-agotado">` (la marca de agua roja de `app.css`) y **el botón
  "Añadir" queda `disabled`**. Es la regla RF-07 / "Bloqueo de Stock".
- `etiqueta_destacada` distinta de `'ninguna'` pinta un badge naranja
  (`str_replace('_', ' ', ...)` en mayúsculas).
- Si el producto no tiene `imagen`, se usa una foto de Unsplash por defecto.

## El componente `catalogo()`

| Miembro | Qué hace |
|---|---|
| `cat` | La categoría activa del filtro |
| `abrir(id)` | `fetch` a `/client/producto/{id}` → guarda el JSON en `prod`, resetea las selecciones y abre el modal |
| `toggle(list, id)` | Añade o quita un id de `quitar` / `extra` |
| `extrasCosto` | Suma el `precio_extra` de los extras marcados |
| `total` | `(precio + extrasCosto) * cantidad` |
| `money(v)` | Formato de pesos en el navegador (`toLocaleString('es-CO')`) |

El JSON que consume `abrir()` lo produce `CatalogoController`, acción `personalizar`.

## Notas
- El precio que se ve en el modal se calcula **en el navegador**; el precio real lo vuelve
  a calcular `PedidoServicio::crear()` en el servidor al confirmar el pedido.
