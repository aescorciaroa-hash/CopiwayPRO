# Archivo: `app/Core/helpers.php`

Funciones auxiliares globales del sistema.

## Descripción

Proporciona funciones utilitarias globales para escaping HTML (`e()`), formateo de dinero (`money()`), manejo de notificaciones y generación de UUIDs (`uuid()`).

## Funciones Principales

- `uuid()`: Genera identificadores únicos de 36 caracteres.
- `e($str)`: Sanetiza cadenas para evitar XSS (`htmlspecialchars`).
- `url($path)` / `asset($path)`: Construye rutas y referencias a assets.
- `redirect($path)`: Ejecuta redirección HTTP.
- `money($amount)`: Formatea valores numéricos a representación monetaria.
