# `app/Views/auth/login.php`

## Ubicación
`app/Views/auth/login.php`

## Propósito
La pantalla de **inicio de sesión**, única para los 4 roles.

## Quién la renderiza
`public/index.php`, `case '/login'`, dentro del layout **`auth`**.

## Variables que espera
Ninguna. Lee lo que haya en la sesión con los helpers `old()` y `error()`.

## Estructura
El layout `auth` es una rejilla de 2 columnas, así que esta vista son **dos bloques
hermanos**, sin contenedor propio:

1. **Panel de marca** (izquierda, `hidden lg:flex`) — foto de Unsplash oscurecida con un
   degradado, el eslogan y una tarjeta "Conexión Segura". Se oculta en móvil.
2. **Formulario** (derecha) — `POST` a `url('/login')`.

## Campos del formulario

| Campo | Notas |
|---|---|
| `correo` | `type="email"`, `autofocus`, repuebla con `old('correo')` |
| `contrasena` | Con botón de ojo para mostrar/ocultar (`x-data="{ show: false }"`) |
| `recordar` | Checkbox "Recordar mi sesión en este equipo" |

Más `csrf_field()`.

> ⚠️ **El campo `recordar` no hace nada.** `AuthController` no lo lee: no hay cookie
> "recuérdame" ni sesión de larga duración. Es maqueta.

## Detalles

- La variable `$inputBase` al principio guarda las clases comunes de los inputs, para no
  repetirlas. Es un patrón que se repite en `register.php` y `forgot.php`.
- Si un campo trae error, se le añade `ring-2 ring-red-500/40 border-red-500` y debajo
  aparece el mensaje con un icono de alerta.
- El botón del ojo alterna `:type="show ? 'text' : 'password'"` con Alpine.

## Notas
- Tras el login, el guardia de `public/index.php` manda a Cocina y Domiciliario a su
  pantalla de PIN de estación antes de dejarles ver el panel.
- Los errores de credenciales **no** llegan por `error()`, sino como **toast** (flash de
  tipo `danger`). `error()` aquí solo se usaría si alguien añadiera validación de campos.
