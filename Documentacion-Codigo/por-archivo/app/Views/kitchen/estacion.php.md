# `app/Views/kitchen/estacion.php`

## Ubicación
`app/Views/kitchen/estacion.php`

## Propósito
El **segundo paso del login de cocina**: la pantalla del PIN de estación.
Cubre la mitad de **RF-54** (Autenticación en Dos Pasos del Ayudante de Cocina).

## Quién la renderiza
`public/index.php`, `case '/kitchen/estacion'` — con un `require` directo,
**sin layout**.

## ⚠️ Es una página HTML completa e independiente

A diferencia de las demás vistas, trae su propio `<!DOCTYPE html>`, `<head>` y `<body>`, y
**no usa `partials/head.php`**. Carga por su cuenta:

- Tailwind desde el CDN, con una config mínima propia (solo la fuente Inter y
  `brand.500 = #ff6600` / `brand.600 = #e65c00`);
- la fuente Inter de Google Fonts;
- Lucide, y al final `lucide.createIcons()` a mano.

**No carga Alpine, ni Leaflet, ni `app.css`, ni `app.js`.** Es una pantalla deliberadamente
autónoma y ligera, pensada para una tablet en la cocina.

Consecuencia: **no hay botón de tema** y esta pantalla siempre se ve en claro.

## Los mensajes de error

Como no hay layout, tampoco hay `partials/toast.php`. Los flashes se pintan a mano:

```php
<?php foreach (($_flash ?? Session::pullFlash()) as $f): ?>
    <div class="... bg-red-50 ... text-red-600 ...">
        <?= e($f['message'] ?: $f['title']) ?>
    </div>
<?php endforeach; ?>
```

> Es **el único sitio del sistema, junto con `delivery/estacion.php`, donde se llama a
> `Session::pullFlash()`**. El `partials/toast.php` que usan los layouts lee
> `$_SESSION['_flash']` directamente.

## Los formularios

1. **PIN** → `POST` a `/kitchen/estacion`, con `csrf_field()`:
   - `usuario` (texto, `autofocus`, `required`)
   - `pin` (`type="password"`, `inputmode="numeric"` para que salga el teclado numérico)
2. **Cerrar sesión** → `POST` a `/logout`.

> ⚠️ **El campo `usuario` no se valida.** `KdsController`, en la acción `estacionLogin`,
> solo compara el `pin` con `Configuracion::value('pin_estacion_kds')` usando
> `hash_equals`. Se puede escribir cualquier cosa en "Usuario".

## Notas
- El botón de cerrar sesión es importante: si alguien entra con la cuenta equivocada, esta
  pantalla es lo único que ve (el guardia de `public/index.php` no le deja pasar a
  `/kitchen` sin el PIN), así que necesita una salida.
