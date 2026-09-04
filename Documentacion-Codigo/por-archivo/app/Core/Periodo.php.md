# `app/Core/Periodo.php`

## Ubicación
`app/Core/Periodo.php`

## Propósito
Traduce una palabra (`hoy`, `semana`, `semana_pasada`, `mes`, `mes_pasado`) a un
**rango de fechas** `[desde, hasta]` en formato MySQL. Lo usa el **Tablero** del admin
para filtrar las ventas.

## Contenido

### `const OPCIONES`
```php
['hoy' => 'Hoy', 'semana' => 'Esta semana', 'semana_pasada' => 'Semana pasada',
 'mes' => 'Este mes', 'mes_pasado' => 'Mes pasado']
```
Clave interna → etiqueta visible (para pintar el `<select>`).

### `rango(string $clave): array`
Devuelve `[$desde, $hasta]` como `'Y-m-d 00:00:00'` y `'Y-m-d 23:59:59'`.

Usa `DateTimeImmutable('today')` y expresiones relativas:
| Clave | desde → hasta |
|-------|---------------|
| `semana` | `monday this week` → `sunday this week` |
| `semana_pasada` | `monday last week` → `sunday last week` |
| `mes` | `first day of this month` → `last day of this month` |
| `mes_pasado` | `first day of last month` → `last day of last month` |
| `hoy` (default) | hoy → hoy |

### `valida(?string $clave): string`
Si la clave está en `OPCIONES` la devuelve; si no, devuelve `'semana'` (valor por
defecto seguro). Protege contra valores manipulados en la URL.

## Notas
- `DateTimeImmutable` = fecha que **no se modifica** al hacer `->modify(...)` (devuelve
  una copia). Evita errores sutiles.
- Lo usa el tablero analítico del admin: el `case '/admin'` de `public/index.php`,
  que lee el filtro de `?periodo=` y se lo pasa a `Periodo::valida()` y `Periodo::rango()`.
