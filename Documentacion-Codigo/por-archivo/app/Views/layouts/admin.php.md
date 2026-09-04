# `app/Views/layouts/admin.php`

## Ubicación
`app/Views/layouts/admin.php`

## Propósito
El marco del **panel de administrador**: sidebar fijo de 8 secciones + cabecera con
campana de notificaciones, tema y avatar.

## Quién lo usa
`public/index.php`, en los 8 `case` de `/admin...`.

## Variables que espera
| Variable | De dónde viene |
|---|---|
| `$content` | El HTML de la vista |

Las demás las calcula el propio layout: `$current` (`current_path()`),
`$_auth` (`Auth::user()`) y el cierre `$active`.

## El menú
Está escrito como un array al principio del archivo — **es el sitio donde se añade una
sección nueva al panel**:

```php
$nav = [
    ['/admin',           'layout-dashboard', 'Tablero Analítico'],
    ['/admin/comandas',  'utensils',         'Comandas Activas'],
    ['/admin/rutas',     'map',              'Rutas & Zonas'],
    ['/admin/menu',      'book-open',        'Gestión de Menú'],
    ['/admin/inventario','package',          'Inventario Express'],
    ['/admin/personal',  'users',            'Equipo y Personal'],
    ['/admin/clientes',  'contact',          'Directorio Clientes'],
    ['/admin/ajustes',   'settings',         'Ajustes y Caja'],
];
```

Cada fila es `[ruta, icono de Lucide, etiqueta]`.

## Cómo se marca el enlace activo
```php
$active = fn($path) => $path === '/admin'
    ? ($current === '/admin' || $current === '/admin/')
    : str_starts_with($current, $path);
```

`/admin` necesita el caso especial: con `str_starts_with` se quedaría marcado en **todas**
las secciones, porque todas empiezan por `/admin`.

## Estructura
```
<aside>   sidebar fijo (sticky top-0 h-screen), logo + $nav + "Cerrar Sesión"
<div>     overlay negro para móvil (x-show="sidebar")
<header>  botón hamburguesa (móvil) · admin-notificaciones · theme-toggle · avatar
<main>    <?= $content ?>
```

## Detalles

- El sidebar es responsive con Alpine: `x-data="{ sidebar: false }"` en el `<body>`, y
  `:class="sidebar ? 'translate-x-0' : '-translate-x-full'"`. En pantallas `lg` está
  siempre visible (`lg:translate-x-0`).
- El avatar es la **inicial del nombre** en mayúscula: `substr($_auth['nombre'], 0, 1)`.
- El botón "Cerrar Sesión" es un `<form method="post">` con `csrf_field()`, no un enlace.

## Notas
- Incluye `partials/admin-notificaciones.php`, que **hace sus propias consultas SQL**.
- Ver `10-Vistas-Layouts-y-Frontend.md` para el sistema de layouts en general.
