# `app/Views/layouts/kitchen.php`

## Ubicación
`app/Views/layouts/kitchen.php`

## Propósito
El marco del **KDS** (pantalla de cocina): sidebar blanco con el inventario crítico y el
resumen de preparación por lotes, más el área del tablero de 3 columnas.

## Quién lo usa
`public/index.php`, solo en el `case '/kitchen'`.

## ⚠️ Este layout pinta datos de negocio

No es solo un marco: **el sidebar muestra dos bloques que vienen de la consulta**, no de
la vista. Por eso espera tres variables:

| Variable | De dónde viene | Para qué |
|---|---|---|
| `$content` | La vista `kitchen/index.php` | El tablero |
| `$criticos` | `Ingrediente::criticos()` | **RF-60** — desplegable rojo "Inventario Crítico" |
| `$resumen` | Calculado en `public/index.php` recorriendo los pedidos activos | **RF-59** — "Resumen de Preparación" por lotes |

Las tres las prepara el `case '/kitchen'` de `public/index.php` antes del `require`.

Como son variables del ámbito de PHP, si algún día se renderizara este layout sin
definirlas, los `?? []` de cada bloque evitan el error y muestran
"Todo el inventario está bien" / "Sin pedidos por preparar".

## Estructura
```
<aside w-64 sticky h-screen>
    logo CopiwayPRO (chef-hat naranja #ff6600)
    enlace activo "Pedidos"
    bloque rojo   INVENTARIO CRÍTICO (n)   ← $criticos, desplegable con Alpine
    bloque ámbar  RESUMEN DE PREPARACIÓN   ← $resumen, "Producto  x3"
    (espaciador flexible)
    Modo Oscuro / Modo Claro  ·  Cerrar Sesión
<main> <?= $content ?>
```

## Detalles

- El naranja de esta pantalla está escrito a mano como `#ff6600`, no con la escala
  `brand-500` de Tailwind (`#f97316`). Es un tono ligeramente distinto, elegido para el
  alto contraste de la cocina.
- El bloque de inventario crítico es plegable: `x-data="{ open: true }"`.
- El contador del título sale de `count($criticos)`.
- Las cantidades se imprimen quitando los ceros decimales sobrantes:
  `rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.')` → `2.50` se ve como `2.5`.
- El botón de tema está escrito a mano (con texto "Modo Oscuro"/"Modo Claro") en vez de
  incluir `partials/theme-toggle.php`.

## Notas
- El fondo es `#f8fafc` en claro y `#0d0d0d` en oscuro.
- Esta pantalla **no** se ve hasta pasar el PIN de estación; ver `KdsController.php.md`.
