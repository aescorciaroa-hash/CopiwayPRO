# `app/Models/Cliente.php`

## Ubicación
`app/Models/Cliente.php`

## Propósito
Tabla `CLIENTE`. Registro de clientes, directorio para el admin, historial y el
chequeo de cumpleaños.

## Cómo se usa
No es una clase estática y no hereda de nada. Se instancia y recibe la conexión global
en el constructor:

```php
require_once __DIR__ . '/../Models/Cliente.php';
$clienteModel = new Cliente();
$clienteModel->find($id);
```

## Métodos

### `find($id): ?array`
El cliente por su id, o `null`.

### `pedidosActivos(string $idCliente): array`
Los pedidos del cliente que aún no se entregaron, con los datos del domiciliario
(nombre, teléfono, vehículo, placa). Alimenta la pantalla `/client/ordenes`.

### `registrar(array $d): string`
`INSERT INTO CLIENTE` con:
- `contrasena` = `password_hash($d['contrasena'], PASSWORD_BCRYPT)`,
- `puntos_fidelidad` = 0,
- `fecha_aceptacion_habeas_data` = `now()`.
Devuelve el id nuevo.

### `directorio(?string $buscar = null): array`
`SELECT c.*` + 3 subconsultas correlacionadas por cliente:
`pedidos` (COUNT), `total_gastado` (SUM), `ultimo_pedido` (MAX fecha). Con filtro
opcional por nombre o teléfono (`LIKE`). Ordenado por nombre.

### `historial(string $idCliente): array`
Todos los pedidos del cliente + nombre del domiciliario (`LEFT JOIN`), más recientes
primero.

### `esCumpleanos(?array $cliente): bool`
`true` si `fecha_nacimiento` no está vacía **y** su `MM-DD` es igual al de hoy.
Acepta `null` sin romper. Activa el 15% de descuento.

## Notas
- `directorio` alimenta la pantalla "Directorio de Clientes" del admin.
- El descuento de cumpleaños se aplica en `CheckoutController` (15% sobre el subtotal).
