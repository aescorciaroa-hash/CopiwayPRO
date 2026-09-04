# `app/Views/layouts/auth.php`

## Ubicación
`app/Views/layouts/auth.php`

## Propósito
El marco de **login, registro y recuperación**: una tarjeta flotante centrada sobre un
degradado suave.

## Quién lo usa
`public/index.php`, en los `case '/login'`, `'/register'` y `'/forgot-password'`.

## Variables que espera
| Variable | De dónde viene |
|---|---|
| `$content` | El HTML de la vista de auth |

## Estructura
Es el layout más corto (22 líneas):

```php
<body class="... flex items-center justify-center p-4 sm:p-8">
  <div class="fixed top-5 right-5"> theme-toggle </div>
  <div class="w-full max-w-5xl bg-white dark:bg-card rounded-[32px] shadow-xl
              overflow-hidden grid lg:grid-cols-2 min-h-[640px]">
      <?= $content ?>
  </div>
```

## Detalles

- **La tarjeta es una rejilla de 2 columnas** (`grid lg:grid-cols-2`). Por eso las vistas
  de auth (`login.php`, `register.php`, `forgot.php`) empiezan directamente con **dos
  bloques hermanos**: el panel decorativo naranja y el formulario. No traen su propio
  contenedor.
- En pantallas pequeñas la rejilla colapsa a una columna y el panel decorativo se oculta.
- El fondo es un degradado (`from-brand-50 via-white to-brand-100`, y en oscuro
  `from-ink via-ink to-black`).
- El `rounded-[32px]` es el radio que la especificación de mockups fija para esta tarjeta.

## Notas
- El botón de tema va **fijo arriba a la derecha**, fuera de la tarjeta.
- `clear_errors()` al final es especialmente importante aquí: es donde se usan `old()` y
  `error()` para repintar el formulario tras un registro fallido.
