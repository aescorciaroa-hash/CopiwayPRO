# `app/Views/delivery/estacion.php`

## Ubicación
`app/Views/delivery/estacion.php`

## Propósito
El **segundo paso del login del domiciliario**: la pantalla del PIN de estación.
Cubre la mitad de **RF-64** (Autenticación en Dos Pasos del Domiciliario).

## Quién la renderiza
`public/index.php`, `case '/delivery/estacion'` — con un `require` directo,
**sin layout**.

## ⚠️ Es una página HTML completa e independiente

Igual que `kitchen/estacion.php`: trae su propio `<!DOCTYPE html>`, `<head>` y `<body>`, y
**no usa `partials/head.php`**. Carga solo Tailwind (con `brand.500 = #ff6600`), la fuente
Inter y Lucide. **Sin Alpine, sin Leaflet, sin `app.css`, sin `app.js`** — y por tanto sin
botón de tema.

Es deliberado: una pantalla ligera para el móvil del repartidor, que carga rápido incluso
con mala conexión.

## Los mensajes de error

Como no hay layout, no hay `partials/toast.php`. Los flashes se pintan a mano:

```php
<?php foreach (($_flash ?? Session::pullFlash()) as $f): ?>
```

Es uno de los dos únicos sitios del sistema donde se llama a `Session::pullFlash()`
(el otro es `kitchen/estacion.php`).

## Los formularios

1. **PIN** → `POST` a `/delivery/estacion` con `csrf_field()`:
   - `usuario` (texto, `autofocus`, `required`, sugiere "Ej: domicilio")
   - `pin` (`type="password"`, `inputmode="numeric"`)
2. **Cerrar sesión** → `POST` a `/logout`.

> ⚠️ **El campo `usuario` no se valida.** `PanelController`, en la acción `estacionLogin`,
> solo compara el `pin` con `Configuracion::value('pin_estacion_domiciliario')` mediante
> `hash_equals`.

## Notas
- El guardia de `public/index.php` redirige aquí a cualquier domiciliario que intente
  entrar a `/delivery` sin el flag `estacion_domi_ok` en la sesión.
- El botón de cerrar sesión es la única salida si alguien entró con la cuenta equivocada.
