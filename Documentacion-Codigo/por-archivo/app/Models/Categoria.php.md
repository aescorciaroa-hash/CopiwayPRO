# `app/Models/Categoria.php`

## Ubicación
`app/Models/Categoria.php` · namespace `App\Models` · **extends `Model`**

## Propósito
Tabla `CATEGORIA`. Agrupa productos e ingredientes. Cada categoría tiene un **ámbito**:
- `menu` → para productos (Hamburguesas, Bebidas…)
- `insumo_alimenticio` → ingredientes que van dentro de la comida
- `empaque_desechable` → servilletas, cajas, bolsas…

## Configuración
```php
protected static string $table = 'CATEGORIA';
protected static string $key   = 'id_categoria';
```

## Métodos propios

### `conConteo(string $ambito): array`
Cada categoría del ámbito + una subconsulta `items` con cuántos productos (si es `menu`)
o ingredientes (otros ámbitos) tiene. Se usa para las pestañas del menú y del inventario.

### `menu(): array`
`WHERE ambito = 'menu'` ordenado por nombre.

### `deInsumos(): array`
`WHERE ambito IN ('insumo_alimenticio','empaque_desechable')`.

## Notas
- El ámbito determina si una categoría aparece en "Gestión de Menú" o en "Inventario".
- `empaque_desechable` se separa en el escandallo: su costo se suma como "empaques", no
  como "insumos".
