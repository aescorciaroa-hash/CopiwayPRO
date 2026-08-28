# 10 · Vistas, layouts y frontend

## Las vistas son PHP + HTML

Una vista es un archivo `.php` que sobre todo escribe HTML, con pequeños trozos de PHP
para pintar datos y hacer bucles.

```php
<h1><?= e($titulo) ?></h1>

<?php foreach ($productos as $p): ?>
    <div class="card">
        <h3><?= e($p['nombre']) ?></h3>
        <span><?= money($p['precio']) ?></span>
    </div>
<?php endforeach; ?>

<?php if (!$productos): ?>
    <p>No hay productos.</p>
<?php endif; ?>
```

Reglas:
- **Siempre** `e()` al imprimir datos que vienen del usuario o la BD → evita **XSS**.
- `money()`, `mods_html()`, `estado_badge()` para formatear.
- Nada de consultas SQL aquí. Los datos llegan ya listos desde el controlador.
- Se usa la sintaxis `foreach (...):  ... endforeach;` (más legible dentro de HTML que
  las llaves `{}`).

## Cómo llegan las variables a la vista

El controlador hace:
```php
return $this->view('admin/dashboard', ['ventas' => $x, 'ranking' => $y], 'admin');
```
`View::render()` hace `extract(['ventas' => $x, ...])`, y dentro de la vista ya
existen `$ventas` y `$ranking`.

Variables **globales** siempre disponibles: `$_flash` (mensajes), `$_auth` (usuario
logueado), `$_role` (su rol).

## Layouts (la plantilla "marco")

Un layout es el HTML común: `<head>`, la barra lateral, la cabecera… con un hueco
donde se mete la vista:

```php
<!-- app/Views/layouts/admin.php (resumido) -->
<body>
  <aside> ...menú lateral... <form action="/logout">Cerrar sesión</form> </aside>
  <main><?= $content ?></main>          <!-- aquí entra la vista -->
  <?php require '.../partials/toast.php'; ?>
</body>
```

Layouts disponibles:

| Layout | Se usa en |
|--------|-----------|
| `public` | landing |
| `auth` | login / registro / recuperar (tarjeta partida con imagen) |
| `admin` | panel del administrador (sidebar) |
| `client` | panel del cliente (sidebar) |
| `kitchen` | KDS (tema oscuro fijo, letra grande) |
| `delivery` | domiciliario (móvil, botones grandes) |
| `blank` | sin marco: tirillas, errores, logins de estación |

> El sidebar de admin/client/kitchen es **fijo a la altura de la pantalla**
> (`sticky top-0 h-screen`), para que "Cerrar Sesión" siempre esté visible sin scroll.

## Partials (trozos reutilizables) — `app/Views/partials/`

| Partial | Qué es |
|---------|--------|
| `head.php` | `<head>` completo: Tailwind CDN + config, fuente Inter, Leaflet, Alpine, Lucide, script de tema |
| `toast.php` | sistema de notificaciones flotantes (lee `$_flash`, con Alpine.js) |
| `theme-toggle.php` | botón sol/luna para modo claro/oscuro |
| `admin-notificaciones.php` | campana de notificaciones del admin |
| `autorefresh.php` | script que recarga la página cada N segundos (pantallas en vivo). Se pausa si hay un modal abierto o si el usuario interactuó hace poco |

Se incluyen con `<?php require ...; ?>`.

## Tailwind CSS

En vez de escribir CSS, se ponen **clases** directamente en el HTML:

```html
<div class="bg-white dark:bg-card rounded-3xl p-6 shadow-sm flex items-center gap-4">
```
- `bg-white` fondo blanco · `dark:bg-card` fondo oscuro en modo noche
- `rounded-3xl` esquinas redondeadas · `p-6` padding · `shadow-sm` sombra
- `flex items-center gap-4` caja flexible, centrada, con separación

Se carga por **CDN** (`cdn.tailwindcss.com`) con la config (colores de marca) en
`partials/head.php`:
```js
tailwind.config = { darkMode: 'class', theme: { extend: { colors: {
    brand: { 500: '#f97316', 600: '#ea580c', ... },   // naranja Copiway
    ink: '#151515', card: '#1a1a1a',
}}}}
```

### Modo oscuro
Se activa poniendo la clase `dark` en `<html>`. Un script en el `<head>` lee
`localStorage['copiway-theme']` (o la preferencia del sistema) **antes de pintar**,
para que no haya parpadeo. El botón `theme-toggle` cambia la clase y guarda la elección.

## Alpine.js — interactividad ligera

Alpine deja hacer cosas dinámicas **sin escribir un archivo JS aparte**, directo en el
HTML con atributos `x-`:

```html
<div x-data="{ abierto: false }">
    <button @click="abierto = true">Abrir modal</button>

    <div x-show="abierto" x-cloak class="fixed inset-0 bg-black/50">
        <button @click="abierto = false">Cerrar</button>
    </div>
</div>
```

- `x-data` → estado local del componente.
- `@click` → evento.
- `x-show` → muestra/oculta.
- `x-cloak` → oculto hasta que Alpine carga (evita parpadeo).
- `x-text`, `x-model`, `x-for`… → pintar texto, enlazar inputs, listas.

Se usa para: modales (crear producto, ver detalle, validar PIN), el buscador del menú,
el cálculo en vivo del margen de ganancia, el formulario de pedido manual…

## `public/assets/js/app.js` — utilidades globales (`window.Copiway`)

| Función | Qué hace |
|---------|----------|
| `Copiway.toggleTheme()` | cambia claro/oscuro y lo guarda en `localStorage` |
| `Copiway.money(v)` | formatea un número como pesos |
| `Copiway.post(url, data)` | `fetch` POST con el token CSRF ya incluido; devuelve JSON |
| `Copiway.map(id, opts)` | dibuja un mapa **Leaflet** con OpenStreetMap (sin API key); marcadores como puntos, polilínea para la ruta; filtro CSS para modo oscuro |
| `Copiway._geo(texto)` | genera coordenadas "estables" a partir de un texto de dirección (no hay geocodificación real) |

## Comunicación vista ↔ servidor sin recargar

Para los modales de detalle, el frontend hace `fetch` a una ruta que devuelve **JSON**:

```js
const res = await fetch('/admin/comandas/' + id);   // GET
this.pedido = await res.json();                       // el controlador hizo $this->json($p)
```

Los formularios normales (crear pedido, cambiar estado) **sí** recargan: envían un
`<form method="post">` y el servidor responde con `redirect()`.
