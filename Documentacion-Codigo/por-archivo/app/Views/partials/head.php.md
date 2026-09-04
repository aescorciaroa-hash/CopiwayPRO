# `app/Views/partials/head.php`

## Ubicación
`app/Views/partials/head.php`

## Propósito
El contenido del `<head>` que comparten **los 6 layouts**: meta tags, título, favicon,
Tailwind con su configuración de marca, las librerías de CDN y el script anti-parpadeo
del modo oscuro.

## Quién lo usa
Los 6 layouts, con `require dirname(__DIR__) . '/partials/head.php';`.

## Variables que espera
| Variable | Opcional | Efecto |
|---|---|---|
| `$title` | Sí | Se convierte en `"<título> · CopiwayPRO"`. Si no se define, queda solo `"CopiwayPRO"` |

```php
$title = $title ?? 'CopiwayPRO';
<title><?= e($title === 'CopiwayPRO' ? $title : $title . ' · CopiwayPRO') ?></title>
```

## Qué carga

| Recurso | Origen |
|---|---|
| **Tailwind CSS** | `https://cdn.tailwindcss.com` (script, modo *play CDN*) |
| **Fuente Inter** | Google Fonts (pesos 400–900) |
| **Leaflet** 1.9.4 (CSS + JS) | `unpkg.com` — los mapas |
| **Alpine.js** 3.x (`defer`) | `unpkg.com` — la interactividad |
| **Lucide** | `unpkg.com` — los iconos SVG |
| `assets/css/app.css` | Local, vía el helper `asset()` |

> Todo por CDN: **el proyecto no tiene paso de compilación** ni `node_modules`. Como
> contrapartida, **necesita conexión a internet** para verse bien.

## La configuración de Tailwind

Va inline, justo después de cargar el script:

```js
tailwind.config = {
    darkMode: 'class',
    theme: { extend: {
        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        colors: {
            brand: { 50:'#fff7ed', ..., 500:'#f97316', 600:'#ea580c', ..., 900:'#7c2d12' },
            ink:  '#151515',
            card: '#1a1a1a',
        },
        borderRadius: { '4xl': '2rem' },
    }}
}
```

- **`darkMode: 'class'`** — el modo oscuro se activa poniendo la clase `dark` en `<html>`,
  no por la preferencia del sistema. Eso es lo que permite el botón de tema.
- `brand` es la escala naranja de la marca; `ink` y `card` son los grises del modo oscuro.

## El favicon
Es un SVG inline en un `data:` URI con el emoji 🍔. Sin archivo de imagen.

## El script anti-parpadeo (importante)

```js
(function () {
    try {
        var t = localStorage.getItem('copiway-theme');
        if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    } catch (e) {}
})();
```

Se ejecuta **en el `<head>`, antes de pintar el `<body>`**. Si esperara al final de la
página, el usuario vería un fogonazo blanco antes de que se aplicara el tema oscuro.

- Si no hay nada guardado, respeta la preferencia del sistema operativo.
- El `try/catch` cubre los navegadores que bloquean `localStorage`.

## Notas
- Quien **cambia** el tema es `Copiway.toggleTheme()` en `assets/js/app.js`; este script
  solo lo **aplica** al cargar.
- Cada layout añade además su propio `<meta name="csrf-token">` antes de este `require`.
