# `app/Views/layouts/delivery.php`

## Ubicación
`app/Views/layouts/delivery.php`

## Propósito
El marco del **panel del domiciliario**. Es deliberadamente mínimo: solo prepara una
pantalla completa sin scroll, porque el diseño *split-screen* (lista + mapa) lo arma
entera la vista `delivery/index.php`.

## Quién lo usa
`public/index.php`, solo en el `case '/delivery'`.

## Variables que espera
| Variable | De dónde viene |
|---|---|
| `$content` | El HTML de `delivery/index.php` |

## Contenido completo (19 líneas)
```php
<body class="bg-[#f8fafc] dark:bg-[#0d0d0d] ... overflow-hidden">
<div class="h-screen w-screen flex overflow-hidden relative">
    <?= $content ?>
</div>
<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
```

## Detalles

- **`overflow-hidden` en el `<body>` y `h-screen w-screen`**: la pantalla nunca hace
  scroll global. El scroll vive dentro del sidebar de pedidos de la vista.
  Es lo correcto para una app que se usa en el móvil, sobre la moto.
- El `flex` del contenedor es lo que permite que la vista ponga dos hijos —el sidebar
  de pedidos y el mapa— uno al lado del otro.
- `relative` para que el mapa y las tarjetas flotantes puedan posicionarse encima.

## Notas
- No tiene sidebar de navegación ni cabecera: el domiciliario solo tiene una pantalla.
- Cerrar sesión y el resto de acciones están dentro de la vista.
- Esta pantalla **no** se ve hasta pasar el PIN de estación; ver `PanelController.php.md`.
