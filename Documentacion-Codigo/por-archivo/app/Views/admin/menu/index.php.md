# `app/Views/admin/menu/index.php`

## Ubicación
`app/Views/admin/menu/index.php`

## Propósito
La **Gestión de Menú**: la tabla de productos con búsqueda y filtro, y los accesos a los
modales de producto y categorías.

Cubre **RF-32** (Catálogo con Etiquetas Destacadas), **RF-33** (Ocultamiento Temporal) y
**RF-35** (Gestión de Categorías).

## Quién la renderiza
`public/index.php`, `case '/admin/menu'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$productos` | `Producto::catalogo(false)` — **incluye los ocultos** |
| `$categorias` | `Categoria::conConteo('menu')` |
| `$ingredientes` | `Ingrediente::conCategoria()` — para el editor de recetas |

Además lee `?q=` y `?categoria=` de la URL para el filtro:
```php
$buscar    = $buscar    ?? ($_GET['q'] ?? '');
$filtroCat = $filtroCat ?? ($_GET['categoria'] ?? null);
```

## Acciones por producto

| Botón | Ruta |
|---|---|
| Editar | Abre el modal (`editar(id)` → `fetch`) |
| Ocultar / Activar | `POST /admin/menu/producto/{id}/estado` |
| Eliminar | `POST /admin/menu/producto/{id}/eliminar` |

## El componente `menuPage()` — el más elaborado del proyecto

Lo comparte con `_modal_producto.php`. Su parte más interesante es el **cálculo de
rentabilidad en vivo** del escandallo:

| Getter | Qué calcula |
|---|---|
| `costoInsumos` | Suma `costo * cantidad` de los ingredientes que **no** son `empaque_desechable` |
| `costoEmpaques` | Lo mismo, pero **solo** de los de ámbito `empaque_desechable` |
| `costoTotal` | Los dos juntos |
| `ganancia` | `precio - costoTotal` |
| `margen` | `round(ganancia / precio * 100)` |

Separar insumos de empaques es una regla del negocio: en el escandallo se miran por
separado el coste de la comida y el del empaque.

Todo esto se calcula **en el navegador**, con el array `ingredientes` que PHP serializa
con `json_encode` al cargar la página. Por eso el margen se actualiza al teclear el precio,
sin ninguna petición al servidor.

Otros miembros: `nuevo()` (formulario en blanco), `editar(id)` (`fetch` a
`/admin/menu/producto/{id}` → `MenuController`, acción `cargarProducto`), `addInsumo()`,
`quitarInsumo(i)` e `infoIng(id)`.

## Notas
- Los dos modales están en `_modal_producto.php` y `_modal_categorias.php`.
- Un producto **con historial de ventas no se puede eliminar**: `Producto::eliminar()`
  devuelve `false` y el controlador solo avisa. Para retirarlo se usa "Ocultar".
