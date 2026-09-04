# `app/Views/auth/forgot.php`

## Ubicación
`app/Views/auth/forgot.php`

## Propósito
La pantalla de **recuperación de contraseña**.

## Quién la renderiza
`public/index.php`, en los `case '/forgot-password'` y `'/forgot'`, dentro del layout
**`auth`**.

## Variables que espera
Ninguna.

## Estructura
Los dos bloques habituales del layout `auth`: panel de marca a la izquierda (con otra foto
de Unsplash) y un formulario mínimo a la derecha.

## El formulario
Un solo campo:

```php
<form method="post" action="<?= url('/forgot-password') ?>">
    <?= csrf_field() ?>
    <input name="identificador" required placeholder="correo@ejemplo.com">
    <button>Enviar Codigo</button>
</form>
```

## ⚠️ El flujo está simulado

`AuthController`, en la acción `forgot`, **ignora el campo `identificador`** y siempre
hace exactamente lo mismo:

```php
$_SESSION['_flash'][] = ['type' => 'info', 'title' => 'Recuperacion',
                         'message' => 'Si el correo existe, enviamos un codigo de verificacion.'];
redirect('/login');
```

No consulta la tabla `CODIGO_VERIFICACION`, no genera ningún código y no envía ningún
correo. El panel de la izquierda promete un flujo "en dos pasos" que **no está construido**.

> Para implementarlo de verdad harían falta: generar un código en `CODIGO_VERIFICACION`,
> una forma de enviarlo, y una segunda pantalla para introducirlo y fijar la contraseña
> nueva.

## Notas
- Devolver siempre el mismo mensaje ("si el correo existe…") es, de hecho, la práctica
  correcta: no revela si un correo está registrado o no.
- El tipo de toast que usa es `info`, que no está en la tabla de colores de
  `partials/toast.php`, así que se pinta sin borde de color y con el icono `bell`.
