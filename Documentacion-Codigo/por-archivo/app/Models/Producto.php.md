# `app/Models/Producto.php`

## Ubicación
`app/Models/Producto.php`

## Propósito
Tabla `PRODUCTO`. Catálogo, recetas (escandallo), cálculo de costo, detección de
"AGOTADO" y opciones de personalización.

## Cómo se usa
No es una clase estática y no hereda de nada. Se instancia y recibe la conexión global
en el constructor:

```php
require_once __DIR__ . '/../Models/Producto.php';
$productoModel = new Producto();
$productoModel->catalogo();
```

### `const AGOTADO_EXPR` (privada)
Trozo de SQL reutilizable: subconsulta que da `1` si al producto le falta stock de
**algún** ingrediente de su receta:
```sql
(SELECT COUNT(*) FROM RECETA r JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
 WHERE r.id_producto = p.id_producto AND i.cantidad_stock < r.cantidad_necesaria) > 0 AS agotado
```

## Métodos

### `find($id): ?array`
El producto crudo por su id, o `null`.

### `guardar(array $d): string`
Crea o edita según venga o no `id_producto`. Devuelve el id.

### `cambiarEstado($id, $estado): void`
`activo` ↔ `oculto`.

### `eliminar($id): bool`
Borra solo si `!tienePedidos($id)`; si no, devuelve `false` y el controlador avisa.

| Método | Qué hace |
|--------|----------|
| `catalogo($soloActivos = true)` | productos + categoría + `agotado`. Para el cliente y la landing |
| `paraAdmin($cat, $buscar)` | catálogo completo (incluye ocultos) + `insumos` (nº de ingredientes en la receta). Filtro por categoría y búsqueda |
| `conCategoria($id)` | un producto con el nombre de su categoría |
| `estaAgotado($id): bool` | `true` si falta stock de algún ingrediente de la receta |
| `receta($id)` | la receta: cada ingrediente con nombre, unidad, `costo_unitario`, `cantidad_stock`, `ambito` |
| `costoReceta($id): array` | recorre la receta y separa `insumos` y `empaques`; devuelve `['insumos','empaques','total']` (escandallo) |
| `guardarReceta($id, $items)` | `DELETE FROM RECETA WHERE id_producto = ?` y vuelve a `INSERT` cada `{id_ingrediente, cantidad}` válido |
| `tienePedidos($id): bool` | `true` si el producto aparece en algún `DETALLE_PEDIDO` (no se puede borrar) |
| `personalizables($id): array` | `['retirar' => ...ingredientes de la receta que son comida..., 'extras' => ...ingredientes con precio_extra > 0, con flag agotado...]` |
| `ingredientesCreador(): array` | ingredientes alimenticios para "Arma tu Burger"; añade `precio` = `precio_extra` o `costo_unitario * (1 + margen/100)` |

## Notas
- `AGOTADO_EXPR` implementa la regla "Bloqueo de Stock": un producto sin insumos no se
  puede pedir.
- `guardarReceta` usa la estrategia "borrar y reinsertar" (más simple que calcular
  diferencias).
