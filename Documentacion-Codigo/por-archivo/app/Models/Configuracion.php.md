# `app/Models/Configuracion.php`

## Ubicación
`app/Models/Configuracion.php`

## Propósito
Acceso a la **fila única** de `CONFIGURACION_SISTEMA`: horario, tarifa de domicilio,
margen, pausa de emergencia, umbral de stock, PINs de estación.

## Cómo se usa
No es una clase estática y no hereda de nada. Se instancia y recibe la conexión global
en el constructor:

```php
require_once __DIR__ . '/../Models/Configuracion.php';
$configModel = new Configuracion();
$configModel->get();
```

## Métodos

### `get(): array`
`SELECT * FROM CONFIGURACION_SISTEMA LIMIT 1` — la fila única. **No hay caché**: cada
llamada consulta la base de datos.

### `value(string $key, $default = null)` — **el único `static`**
Un ajuste suelto, sin tener que instanciar el modelo:
```php
$tarifa = (float) Configuracion::value('tarifa_plana_domicilio', 6000);
```
Internamente toma `global $conn` y hace su propia consulta.

### `save(array $data): void`
Construye el `UPDATE ... SET col = ?, col = ?` a mano a partir de las claves del array,
deduciendo el tipo de cada valor para `bind_param` (`i` entero, `d` decimal, `s` texto),
y filtra por el `id_config` de la fila única. Si no hay fila, no hace nada.

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
- La comparación de horario es de **cadenas** `'HH:MM:SS'`, así que un horario que
  cruce la medianoche (p. ej. 18:00–02:00) no funcionaría.
- `value()` y `get()` consultan cada vez; en una pantalla que las llame varias veces se
  repiten las consultas. Es aceptable en este proyecto (la tabla tiene una sola fila).
