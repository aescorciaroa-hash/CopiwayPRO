# `app/Models/Usuario.php`

## Ubicación
`app/Models/Usuario.php`

## Propósito
**No es una tabla.** Centraliza la búsqueda de credenciales entre las **4 tablas de
cuentas** para que haya un solo login.

## Dependencias
La conexión global `$conn` de `config/database.php`, que el constructor guarda en
`$this->conn`. Nada más.

## Cómo se usa
```php
require_once __DIR__ . '/../Models/Usuario.php';
$usuarioModel = new Usuario();
$cuenta = $usuarioModel->porCorreo($correo);
```

Los métodos son **de instancia** (se llaman con `->`), salvo la constante.

## Contenido

### `private const FUENTES`
```php
'admin'        => ['ADMINISTRADOR',   'id_admin'],
'cliente'      => ['CLIENTE',          'id_cliente'],
'cocina'       => ['AYUDANTE_COCINA',  'id_ayudante'],
'domiciliario' => ['DOMICILIARIO',     'id_domiciliario'],
```
Mapa rol → `[tabla, columna_id]`.

### `porCorreo(string $correo): ?array`
Recorre las 4 tablas buscando `WHERE correo = ?`. Al primer resultado devuelve
`normalizar(...)`. Si no está en ninguna → `null`.

### `porId(string $role, string $id): ?array`
Busca en la tabla que corresponde al rol `WHERE <idCol> = ?`. Devuelve `null` si el rol
no está en `FUENTES`.

> ⚠️ Su único llamador es `Auth::requireRole()`, que **hoy no lo invoca nadie**. En la
> práctica este método está sin uso: el guardia de `public/index.php` confía en la sesión
> y no vuelve a comprobar que la cuenta siga existiendo en la base de datos.

### `existeCorreoOTelefono(string $correo, string $telefono): bool`
`true` si el correo **o** el teléfono ya están en **cualquiera** de las 4 tablas
(anti-duplicados en registro y alta de personal).

### `verificarPassword(string $plano, string $hash): bool`
Envoltorio de `password_verify()`.

### `private normalizar(string $role, string $idCol, array $row): array`
Devuelve un formato uniforme:
```php
['role', 'id', 'nombre', 'correo', 'telefono',
 'activo' => !isset($row['activo']) || (bool) $row['activo'],   // admin/cliente no tienen 'activo' -> true
 'row'    => $row]   // la fila completa (para el password, etc.)
```

## Notas
- Gracias a esta clase el `AuthController` no sabe nada de las 4 tablas: solo pide
  `$usuarioModel->porCorreo($correo)` y recibe el rol ya resuelto.
- `ADMINISTRADOR` y `CLIENTE` no tienen columna `activo`, por eso `normalizar()` asume
  `true` con `!array_key_exists('activo', $row)`.
- El nombre de la tabla se interpola en el SQL (`FROM {$tabla}`), pero **no viene del
  usuario**: sale de la constante `FUENTES`, que está escrita en el código. Los valores
  que sí vienen de fuera (`$correo`, `$id`) van siempre por `bind_param`.
