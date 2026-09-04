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

No hay motor de plantillas ni `extract()`. Las variables llegan **por ámbito de PHP**:
`public/index.php` las declara y luego hace `require` de la vista, así que la vista las
ve directamente.

```php
// public/index.php, case '/admin':
$kpi     = $pedidoModel->kpis($rango[0], $rango[1]);
$ranking = $pedidoModel->rankingProductos($rango[0], $rango[1]);

ob_start();
require dirname(__DIR__) . '/app/Views/admin/dashboard.php';   // aqui $kpi y $ranking existen
$content = ob_get_clean();
require dirname(__DIR__) . '/app/Views/layouts/admin.php';      // el layout imprime $content
exit;
```

`ob_start()` / `ob_get_clean()` captura todo lo que imprime la vista en `$content`, y el
layout lo pinta en su hueco. Es el sustituto casero de un motor de plantillas.

Variables que arman **los propios layouts**, no el controlador:
- `$_auth = Auth::user() ?? []` — el usuario logueado (lo usan `layouts/admin.php` y
  `layouts/client.php` para la cabecera).

Los mensajes flash **no llegan por variable**: `partials/toast.php` lee
`$_SESSION['_flash']` directamente y lo vacía él mismo.

> Para saber qué variables recibe una vista, se busca su `case` en `public/index.php`.

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

| Layout | Se usa en |
|--------|-----------|
| `public` | landing pública, catálogo y vistas abiertas |
| `auth` | login / registro / recuperar (tarjeta flotante centrada `rounded-[32px]` sobre fondo claro `#f8fafc`) |
| `admin` | panel del administrador (sidebar con fondo oscuro) |
| `client` | panel del cliente (sidebar lateral y navegación) |
| `kitchen` | KDS de cocina (interfaz limpia `#f8fafc`, sidebar blanco con logo CopiwayPRO, resumen de preparación e inventario crítico, 3 columnas KDS: Pendientes, En Preparación con borde de resplandor rojo, Listos) |
| `delivery` | panel del domiciliario (diseño táctico split-screen en pantalla completa con mapa Leaflet, lista lateral de pedidos con cobrar en efectivo, Waze/Maps y validación por PIN) |

> Son **6 layouts** en total. No existe un layout "blank": las pantallas sin marco
> (`kitchen/estacion.php`, `delivery/estacion.php`, la tirilla de cocina y el reporte de
> caja) se cargan con un `require` directo desde `public/index.php`, sin envolverlas en
> ningún layout. Las respuestas JSON no pasan por ninguna vista.

> El sidebar de admin/client/kitchen es **fijo a la altura de la pantalla**
> (`sticky top-0 h-screen`), para que las acciones principales y "Cerrar Sesión" siempre estén visibles sin scroll.

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
const res = await fetch('<?= url("/admin/comandas") ?>/' + id);   // GET
this.pedido = await res.json();
```

Del otro lado, el controlador escribe el JSON a mano:

```php
header('Content-Type: application/json; charset=utf-8');
echo json_encode($pedido);
exit;
```

Los formularios normales (crear pedido, cambiar estado) **sí** recargan: envían un
`<form method="post">` a una ruta limpia y el servidor responde con `redirect()`
(patrón PRG). Ver `07-Rutas-y-Middleware.md`.
