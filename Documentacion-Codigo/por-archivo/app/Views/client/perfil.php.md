# `app/Views/client/perfil.php`

## Ubicación
`app/Views/client/perfil.php`

## Propósito
La **gestión de cuenta** del cliente: puntos de fidelidad, datos personales y cambio de
contraseña.

## Quién la renderiza
`public/index.php`, `case '/client/perfil'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$cliente` | `Cliente::find($yo['id_cliente'])`, o un array con valores por defecto si no hay |

## Secciones

1. **Tarjeta de puntos** — una caja naranja grande con los `puntos_fidelidad` en tipografía
   enorme y el icono `award`.
2. **Información Personal** (2/3 del ancho) — `POST` a `/client/perfil`.
3. **Seguridad** (1/3) — un botón que abre el modal de contraseña.

## Los campos

| Campo | Editable |
|---|---|
| `nombre` | Sí |
| `correo` | **No** — el input está `disabled` y en gris |
| `telefono` | Sí |
| `direccion` | Sí (es la que se propone en el checkout) |
| `fecha_nacimiento` | Sí |

El correo no se puede cambiar porque es la llave con la que se busca la cuenta en el
login (`Usuario::porCorreo`).

Bajo la fecha de nacimiento hay un aviso naranja: *"Configura tu fecha para activar tu 15%
de descuento en tu cumpleaños."*

## El modal de contraseña

`x-data="{ pwdOpen: false }"` en el contenedor. El formulario hace `POST` a
`/client/perfil/password` con tres campos `required`: `actual`, `nueva` y `confirmar`.

La comprobación real está en el servidor (`PerfilController`): verifica la actual con
`password_verify`, exige 6+ caracteres y que la confirmación coincida.

## Notas
- El botón "Descartar" es un `<button type="reset">`: devuelve el formulario a los valores
  con los que se cargó, no recarga.
- Al guardar, `PerfilController` llama `Auth::refresh()` para que el nombre nuevo aparezca
  enseguida en la cabecera, sin volver a iniciar sesión.
