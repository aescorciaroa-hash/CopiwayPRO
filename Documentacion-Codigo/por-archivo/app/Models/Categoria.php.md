# `app/Models/Categoria.php`

## Ubicación
`app/Models/Categoria.php`

## Propósito
Tabla `CATEGORIA`. Agrupa productos e ingredientes. Cada categoría tiene un **ámbito**:
- `menu` → para productos (Hamburguesas, Bebidas…)
- `insumo_alimenticio` → ingredientes que van dentro de la comida
- `empaque_desechable` → servilletas, cajas, bolsas…

## Cómo se usa
No es una clase estática y no hereda de nada. Se instancia y recibe la conexión global
en el constructor:

```php
require_once __DIR__ . '/../Models/Categoria.php';
$categoriaModel = new Categoria();
$categoriaModel->menu();
```

## Métodos

### `conConteo(string $ambito): array`
Cada categoría del ámbito + una subconsulta `items` con cuántos productos (si es `menu`)
o ingredientes (otros ámbitos) tiene. Se usa para las pestañas del menú y del inventario.

### `menu(): array`
`WHERE ambito = 'menu'` ordenado por nombre.

### `deInsumos(): array`
`WHERE ambito IN ('insumo_alimenticio','empaque_desechable')`.

### `find($id): ?array`
La categoría por su id, o `null`.

### `crear(array $d): string`
`INSERT INTO CATEGORIA (id_categoria, nombre, ambito)` con un `uuid()` generado en PHP.
Devuelve el id nuevo. Desde el panel siempre se llama con `'ambito' => 'menu'`.

### `eliminar(string $id): bool`
`DELETE FROM CATEGORIA WHERE id_categoria = ?`. Devuelve siempre `true`.

> ⚠️ No comprueba si la categoría tiene productos o ingredientes dentro. Si los tiene,
> el borrado lo frena (o lo propaga) la clave foránea de MySQL, no el PHP.

## Notas
- El ámbito determina si una categoría aparece en "Gestión de Menú" o en "Inventario".
- `conConteo('menu')` usa `query()` directo (el ámbito es una constante del código);
  las demás ramas usan sentencias preparadas.
- `empaque_desechable` se separa en el escandallo: su costo se suma como "empaques", no
  como "insumos".
