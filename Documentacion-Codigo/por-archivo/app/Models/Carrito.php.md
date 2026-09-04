# `app/Models/Carrito.php`

## Ubicación
`app/Models/Carrito.php`

## Propósito
El **carrito de compras**, guardado en `$_SESSION['carrito']` (no en la base de datos).
Cada item guarda producto, precio base, cantidad y personalizaciones.

## Dependencias
Instancia `Producto` dentro de `agregar()` para leer nombre y precio.
Escribe directamente sobre `$_SESSION['carrito']` (no pasa por `Session::`).

## Cómo se usa
```php
require_once __DIR__ . '/../Models/Carrito.php';
$carritoModel = new Carrito();
$items = $carritoModel->items();
```

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

## Métodos (de instancia, se llaman con `->`)

| Método | Qué hace |
|--------|----------|
| `items(): array` | `$_SESSION['carrito'] ?? []` |
| `cantidad(): int` | suma de las cantidades de todos los items |
| `subtotalItem(array $item): float` | `(precio_base + Σ costos de extras) * cantidad` |
| `subtotal(): float` | suma de `subtotalItem` de todos |
| `agregar($idProducto, $cantidad, $personalizaciones)` | si ya hay un item con la misma `key` → suma cantidad; si no, crea el item |
| `actualizar($key, $cantidad, $personalizaciones = null)` | cambia cantidad; si `<= 0` lo elimina |
| `quitar($key)` / `vaciar()` | eliminar uno / todos |
| `aLineas(): array` | convierte el carrito al formato que espera `PedidoServicio::crear()` (`id_producto`, `cantidad`, `personalizaciones` con `accion` normalizada a `quitar`/`agregar`) |

> El constructor guarda `$conn` en `$this->conn`, pero el modelo **no consulta la base
> de datos**: todo vive en la sesión.

## Notas
- La **`key`** hace que dos hamburguesas idénticas (mismo producto + mismas
  personalizaciones) se agrupen, y dos distintas queden separadas.
- El carrito se pierde al cerrar sesión (`Auth::logout` borra `carrito`).
- Solo al confirmar el checkout se crean registros reales en la BD.
