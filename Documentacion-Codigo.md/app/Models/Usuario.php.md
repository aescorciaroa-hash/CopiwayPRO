# `app/Models/Usuario.php`

## Ubicación
`app/Models/Usuario.php` · namespace `App\Models`

## Propósito
**No es una tabla.** Centraliza la búsqueda de credenciales entre las **4 tablas de
cuentas** para que haya un solo login.

## Dependencias
`App\Core\Database`.

## Contenido

### `const FUENTES`
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
Busca en la tabla que corresponde al rol `WHERE <idCol> = ?`. Lo usa
`Auth::requireRole` para comprobar que la cuenta de la sesión sigue existiendo.

### `existeCorreoOTelefono(string $correo, string $telefono): bool`
`true` si el correo **o** el teléfono ya están en **cualquiera** de las 4 tablas
(anti-duplicados en registro y alta de personal).

### `verificarPassword(string $plano, string $hash): bool`
Envoltorio de `password_verify()`.

### `private normalizar($role, $idCol, $row): array`
Devuelve un formato uniforme:
```php
['role', 'id', 'nombre', 'correo', 'telefono',
 'activo' => !isset($row['activo']) || (bool) $row['activo'],   // admin/cliente no tienen 'activo' -> true
 'row'    => $row]   // la fila completa (para el password, etc.)
```

## Notas
- Gracias a esta clase el `AuthController` no sabe nada de las 4 tablas: solo pide
  `Usuario::porCorreo` y recibe el rol.
- `ADMINISTRADOR` y `CLIENTE` no tienen columna `activo`, por eso se asume `true`.
