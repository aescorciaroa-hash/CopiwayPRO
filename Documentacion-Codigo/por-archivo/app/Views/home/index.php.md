# `app/Views/home/index.php`

## Ubicación
`app/Views/home/index.php`

## Propósito
La **landing pública**: la vitrina que ve cualquiera sin iniciar sesión.

## Quién la renderiza
`public/index.php`, en los `case '/'` y `'/home'` **y en el `default` del `switch`** —
así que también es la que aparece ante cualquier ruta desconocida (no hay página 404).
Usa el layout **`public`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$productos` | `Producto::catalogo()` — los mismos que ve el cliente logueado |
| `$categorias` | `Categoria::menu()` |

## Las 7 secciones

| # | Sección | `id` | Contenido |
|---|---|---|---|
| 1 | **Hero** | — | Pantalla casi completa (`min-h-[92vh]`), titular y llamada a la acción |
| 2 | **Barra de valores** | — | Los diferenciales del negocio |
| 3 | **Menú** | `#menu` | La rejilla de productos con chips de categoría |
| 4 | **Nosotros** | `#nosotros` | Rejilla de 2 columnas con la historia |
| 5 | **Testimonios** | `#testimonios` | Reseñas |
| 6 | **Contacto** | `#contacto` | Datos + mapa Leaflet (`#mapa-contacto`) |
| 7 | **CTA final** | — | Último empujón a registrarse |

Los `id` son los destinos de los enlaces del `<header>` de `layouts/public.php`, que
navegan con `scroll-smooth`.

## Detalles

- El **menú reutiliza la misma lógica del catálogo del cliente**: filtro por categoría con
  Alpine (sin recargar) y la marca de agua `AGOTADO` sobre los productos sin stock. Pero
  aquí los productos **no se pueden añadir al carrito**: los botones llevan a `/login`.
- El **mapa de contacto** se dibuja con `Copiway.map('mapa-contacto', ...)` centrado en
  `window.COPIWAY_SEDE` (las coordenadas fijas de Neiva que define `app.js`).
- Los testimonios están **escritos en la plantilla**: no salen de la tabla `RESENA`.

## Notas
- Es la única vista que se sirve tanto con sesión como sin ella: el guardia de
  `public/index.php` deja pasar `/` y `/home` a todo el mundo, para que nadie quede
  atrapado sin poder volver al inicio.
