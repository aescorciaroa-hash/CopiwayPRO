# `app/Views/partials/autorefresh.php`

## Ubicación
`app/Views/partials/autorefresh.php`

## Propósito
Recargar la página cada cierto tiempo, para las pantallas que tienen que estar **al día
sin que nadie pulse nada**: el KDS de cocina, el panel del domiciliario, las comandas del
admin y las órdenes activas del cliente.

Es la forma casera de tener "tiempo real" sin WebSockets ni polling AJAX.

## Quién lo usa

Cuatro vistas, **todas con 15 segundos**:

| Vista | Por qué |
|---|---|
| `admin/comandas/index.php` | Ver entrar los pedidos nuevos |
| `kitchen/index.php` | El tablero KDS, encendido todo el turno |
| `delivery/index.php` | Aparecen pedidos listos para tomar |
| `client/ordenes.php` | Avanza la barra de progreso del pedido |

Son justo las cuatro pantallas que dependen de lo que hace **otra persona**.

## Cómo se usa
Se define `$segundos` **antes** del `require`:

```php
<?php $segundos = 15; require dirname(__DIR__, 2) . '/partials/autorefresh.php'; ?>
```

Si no se define, el valor por defecto es **20 segundos**:
```php
const cada = <?= (int) ($segundos ?? 20) ?> * 1000;
```

El `(int)` protege contra que se cuele cualquier cosa en esa variable.

## Las tres condiciones para NO recargar

El `setInterval` no recarga a ciegas. Antes comprueba tres cosas:

```js
setInterval(() => {
    if (document.hidden) return;                       // 1
    const modal = [...document.querySelectorAll('.fixed.inset-0')]
        .some(m => m.offsetParent !== null && getComputedStyle(m).display !== 'none');
    if (modal) return;                                 // 2
    if (Date.now() - ultimaInteraccion < 8000) return; // 3
    location.reload();
}, cada);
```

1. **La pestaña está en segundo plano** (`document.hidden`) — no gastar recursos ni
   consultas en una pestaña que nadie mira.
2. **Hay un modal abierto** — busca cualquier overlay `.fixed.inset-0` que sea visible.
   Sin esto, al domiciliario se le cerraría el modal del PIN a mitad de teclearlo.
3. **El usuario interactuó hace menos de 8 segundos** — se registra con
   `mousedown`, `keydown` y `touchstart` en fase de captura (`true`), así que cuenta
   aunque el evento no llegue a burbujear.

## Notas
- Es una **recarga completa** (`location.reload()`), no una petición AJAX: la página se
  vuelve a construir entera en el servidor. Simple y suficiente para este proyecto.
- La detección de modales depende de que los overlays usen las clases `.fixed.inset-0`.
  Un modal maquetado de otra forma no frenaría la recarga.
