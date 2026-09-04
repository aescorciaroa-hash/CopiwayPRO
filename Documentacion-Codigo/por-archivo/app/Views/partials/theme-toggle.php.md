# `app/Views/partials/theme-toggle.php`

## Ubicación
`app/Views/partials/theme-toggle.php`

## Propósito
El botón redondo de **sol / luna** para alternar el modo claro y oscuro.

## Quién lo usa
`layouts/auth.php`, `layouts/admin.php` y `layouts/client.php`.

> `layouts/public.php` y `layouts/kitchen.php` **no lo incluyen**: escriben su propio
> botón porque necesitan colores distintos (la barra negra de la landing, y el botón con
> texto "Modo Oscuro / Modo Claro" del sidebar de cocina). `layouts/delivery.php` no
> tiene botón de tema.

## Contenido completo (8 líneas)
```php
<button type="button" onclick="Copiway.toggleTheme()"
        class="w-9 h-9 rounded-full border ... hover:text-brand-500 ..."
        title="Cambiar tema">
    <i data-lucide="moon" class="w-4 h-4 dark:hidden"></i>
    <i data-lucide="sun"  class="w-4 h-4 hidden dark:block"></i>
</button>
```

## Cómo funciona

**No tiene lógica de JavaScript propia.** Solo dos cosas:

1. `onclick="Copiway.toggleTheme()"` — la función vive en `assets/js/app.js`: alterna la
   clase `dark` en `<html>`, guarda la elección en `localStorage['copiway-theme']` y
   repinta los iconos de Lucide.
2. **Los dos iconos están siempre en el DOM**, y es Tailwind quien decide cuál se ve:
   - `dark:hidden` en la luna → visible en claro, oculta en oscuro.
   - `hidden dark:block` en el sol → al revés.

   Así el cambio es instantáneo, sin tocar el DOM ni esperar a JavaScript.

## Notas
- Quien **aplica** el tema al cargar la página (antes de pintar, para que no parpadee) es
  el script de `partials/head.php`. Este botón solo lo **cambia**.
- No es un `<form>` ni envía nada al servidor: el tema es una preferencia local del
  navegador, no se guarda en la base de datos.
