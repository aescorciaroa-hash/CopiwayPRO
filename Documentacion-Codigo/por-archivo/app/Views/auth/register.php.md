# `app/Views/auth/register.php`

## Ubicación
`app/Views/auth/register.php`

## Propósito
El **registro de clientes**. Es el único formulario del sistema que crea cuentas por sí
solo: las de personal las crea el administrador.

## Quién la renderiza
`public/index.php`, `case '/register'`, dentro del layout **`auth`**.

## Variables que espera
Ninguna. Repuebla el formulario con `old()` y pinta los fallos con `error()`.

## Estructura
Dos bloques hermanos (el layout `auth` es una rejilla de 2 columnas), pero aquí el orden
está **invertido** con `order-2 lg:order-1` / `order-1 lg:order-2`:

1. **Formulario** — a la izquierda en escritorio, pero **primero** en móvil.
2. **Panel de beneficios** — a la derecha, con los tres puntos "Pide rápido y fácil",
   "Monitorea tu pedido", "Ofertas exclusivas".

## Campos del formulario

| Campo | Validación en `AuthController` |
|---|---|
| `nombre` | No vacío |
| `correo` | `FILTER_VALIDATE_EMAIL` |
| `telefono` | No vacío |
| `fecha_nacimiento` | `type="date"`, debe pasar `strtotime` |
| `contrasena` | Mínimo 6 caracteres |
| `contrasena_confirmation` | Debe coincidir |
| `habeas_data` | **Obligatorio** (Ley 1581 de 2012) |

## Dos ayudas locales

Al principio se definen dos *arrow functions* para no repetir el marcado de errores:

```php
$err  = fn(string $k) => error($k) ? '<p class="text-red-500 ...">' . e(error($k)) . '</p>' : '';
$ring = fn(string $k) => error($k) ? 'ring-2 ring-red-500/40 border-red-500' : '';
```

## El botón deshabilitado hasta aceptar el Habeas Data

```php
<form x-data="{ ok: <?= old('habeas_data') ? 'true' : 'false' ?> }">
    <input type="checkbox" name="habeas_data" value="1" x-model="ok" ...>
    <button :disabled="!ok" :class="ok ? 'bg-brand-500 ...' : 'bg-gray-300 cursor-not-allowed'">
```

El botón "Completar Registro" está **gris y deshabilitado** hasta marcar la casilla.
Fíjate en que el estado inicial de `ok` sale de `old('habeas_data')`: si el registro falló
por otra razón, la casilla sigue marcada al volver.

> Es una ayuda visual, no la defensa real: `AuthController` **vuelve a comprobarlo** en el
> servidor, porque el JavaScript se puede saltar.

## Notas
- La `fecha_nacimiento` que se guarda aquí es la que activa el **descuento del 15% en el
  cumpleaños** (`Cliente::esCumpleanos`).
- Si algo falla, el controlador deja los mensajes en `$_SESSION['_errors']` y lo escrito
  en `$_SESSION['_old']`; el layout llama `clear_errors()` al final para vaciarlos.
