# `app/Models/Configuracion.php`

## Ubicación
`app/Models/Configuracion.php` · namespace `App\Models` · **extends `Model`**

## Propósito
Acceso a la **fila única** de `CONFIGURACION_SISTEMA`: horario, tarifa de domicilio,
margen, pausa de emergencia, umbral de stock, PINs de estación.

## Configuración
```php
protected static string $table = 'CONFIGURACION_SISTEMA';
protected static string $key   = 'id_config';
private static ?array $cache = null;   // caché en memoria
```

## Métodos propios

### `get(): array`
Devuelve la fila única. La guarda en `self::$cache` para no consultar varias veces en
la misma petición.

### `value(string $key, $default = null)`
Un ajuste concreto: `Configuracion::value('tarifa_plana_domicilio', 0)`.

### `save(array $data): void`
`self::update($cfg['id_config'], $data)` y **limpia la caché** (`self::$cache = null`)
para que el nuevo valor se vea al instante.

### `cocinaAbierta(): bool`
`false` si `pausa_emergencia_activa`. Si no: `true` si la hora actual (`date('H:i:s')`)
está entre `horario_apertura` y `horario_cierre`.

### `estadoCocina(): array`
```php
['abierta' => cocinaAbierta(), 'pausa' => (bool) pausa_emergencia_activa,
 'apertura' => 'HH:MM', 'cierre' => 'HH:MM']
```
Lo usan el checkout, el layout del cliente y el tablero.

## Notas
- Implementa las reglas "Horarios Automáticos" y "Pausa de Emergencia".
- La caché es por-petición (variable estática), no persiste entre peticiones.
