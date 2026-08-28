# `app/Models/Carrito.php`

## Ubicación
`app/Models/Carrito.php` · namespace `App\Models` · **no extiende `Model`** (servicio de sesión)

## Propósito
El **carrito de compras**, guardado en `$_SESSION['carrito']` (no en la base de datos).
Cada item guarda producto, precio base, cantidad y personalizaciones.

## Dependencias
`App\Core\Session`. Usa `Producto`.

## Formato de un item
```php
[
  'key'               => 'a1b2c3...',   // md5(id_producto + json(personalizaciones)), 12 chars
  'id_producto'       => '...',
  'nombre'            => 'Hamburguesa Clásica',
  'precio_base'       => 18000.0,
  'cantidad'          => 2,
  'personalizaciones' => [ ['id_ingrediente','nombre','accion','costo'], ... ],
]
```

## Métodos (todos `static`)

| Método | Qué hace |
|--------|----------|
| `items(): array` | `Session::get('carrito', [])` |
| `cantidad(): int` | suma de las cantidades de todos los items |
| `subtotalItem(array $item): float` | `(precio_base + Σ costos de extras) * cantidad` |
| `subtotal(): float` | suma de `subtotalItem` de todos |
| `agregar($idProducto, $cantidad, $personalizaciones)` | si ya hay un item con la misma `key` → suma cantidad; si no, crea el item |
| `actualizar($key, $cantidad, $personalizaciones = null)` | cambia cantidad; si `<= 0` lo elimina |
| `quitar($key)` / `vaciar()` | eliminar uno / todos |
| `aLineas(): array` | convierte el carrito al formato que espera `PedidoServicio::crear()` (`id_producto`, `cantidad`, `personalizaciones` con `accion` normalizada a `quitar`/`agregar`) |

## Notas
- La **`key`** hace que dos hamburguesas idénticas (mismo producto + mismas
  personalizaciones) se agrupen, y dos distintas queden separadas.
- El carrito se pierde al cerrar sesión (`Auth::logout` borra `carrito`).
- Solo al confirmar el checkout se crean registros reales en la BD.
