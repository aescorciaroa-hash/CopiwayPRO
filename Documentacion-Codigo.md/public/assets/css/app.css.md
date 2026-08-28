# `public/assets/css/app.css`

## Ubicación
`public/assets/css/app.css`

## Propósito
CSS propio que **complementa a Tailwind** (que se carga por CDN). Aquí van cosas que
Tailwind no cubre bien: animaciones, la fuente base, la barra de scroll y los estilos
de resaltado SIN/EXTRA y AGOTADO.

## Contenido

| Regla | Qué hace |
|-------|----------|
| `body { font-family: 'Inter', ... }` | Fuente base del sistema |
| `::-webkit-scrollbar...` | Barra de scroll fina y sutil |
| `@keyframes sla-pulse` + `.sla-vencido` | **Animación de pulso rojo** para el pedido que lleva +15 min en cocina (regla del SLA). Aplica borde rojo y una sombra que late |
| `@keyframes toast-in` + `.toast-item` | Animación de entrada de las notificaciones (toasts) |
| `.mod-sin` | Texto **rojo y en negrita** para `SIN <ingrediente>` |
| `.mod-extra` | Texto **verde y en negrita** para `EXTRA <ingrediente>` |
| `.dark .mod-sin` / `.dark .mod-extra` | Variantes más claras para modo oscuro |
| `.badge-agotado` + `.badge-agotado span` | Marca de agua roja diagonal "AGOTADO" sobre las tarjetas de producto sin stock |
| `.map-placeholder` | Fondo a rayas para los mapas cuando no se usa Leaflet |
| `[x-cloak] { display: none !important; }` | Oculta los elementos de Alpine hasta que Alpine carga (evita el "parpadeo") |

## Notas
- Las clases `.mod-sin` / `.mod-extra` las genera el helper `mods_html()` de PHP.
- La clase `.sla-vencido` la añade la vista de comandas / KDS cuando
  `$pedido['minutos'] > 15`.
- El grueso del diseño está en las clases de **Tailwind** dentro del HTML, no aquí.
