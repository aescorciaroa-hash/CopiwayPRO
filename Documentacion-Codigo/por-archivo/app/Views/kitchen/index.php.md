# `app/Views/kitchen/index.php`

## Ubicación
`app/Views/kitchen/index.php`

## Propósito
El **tablero KDS**: las comandas repartidas en tres columnas tipo Kanban
(Pendientes → Preparando → Listos), con el semáforo de SLA y los botones de avance.

Cubre **RF-55** a **RF-63**.

## Quién la renderiza
`public/index.php`, `case '/kitchen'`, con el layout **`kitchen`**.

## Variables que espera

| Variable | De dónde viene | Quién la usa |
|---|---|---|
| `$tablero` | `public/index.php` reparte `Pedido::activos()` en `['pendiente'=>[], 'en_preparacion'=>[], 'listo'=>[]]`, añadiendo `codigo` y `lineas` | Esta vista |
| `$resumen` | Cuántas unidades de cada producto faltan por preparar | **El layout**, no esta vista |
| `$criticos` | `Ingrediente::criticos()` | **El layout**, no esta vista |

## Cabecera del tablero

- Título y "Tiempo promedio: **8.5 min**" — ⚠️ es un **valor fijo escrito en la
  plantilla**, no se calcula.
- **Botón de sonido** (`x-data="{ sonido: true }"`) — alterna el icono y lanza un toast,
  pero **no reproduce ningún sonido**: es la maqueta de RF-61.
- **Reloj en vivo**:
  ```js
  x-init="setInterval(() => t = new Date().toLocaleTimeString('es-CO'), 1000)"
  ```

## Las tres columnas

En móvil son un carrusel horizontal con *snap* (`overflow-x-auto snap-x snap-mandatory`);
en `lg` pasan a una rejilla de 3 (`lg:grid lg:grid-cols-3`).

| Columna | Botón de la tarjeta |
|---|---|
| **Pendientes** | "Preparar" → `POST /kitchen/pedido/{id}/preparar` |
| **Preparando** | "Sticker" (abre la tirilla en otra pestaña) y "Marcar Listo" → `POST .../listo` |
| **Listos** | Esperando al domiciliario |

Cada columna muestra su contador y un estado vacío propio.

## El semáforo de SLA (RF-58)

```php
<?php foreach ($tablero['en_preparacion'] as $p): $tarde = (int) $p['minutos'] >= 15; ?>
    <div class="... <?= $tarde ? 'border-2 kds-sla' : 'border border-stone-700/60' ?>">
```

Pasados **15 minutos** en preparación, la tarjeta se pinta con la animación `kds-sla`
—un borde rojo que late, definido en un `<style>` al principio de esta misma vista— el
código se pone rojo y aparece el badge "Demora".

> Ojo: esta animación `kds-sla` es **propia de esta vista**. La clase `.sla-vencido` de
> `assets/css/app.css` es otra, y la usa el tablero de comandas del admin.

## Las personalizaciones (RF-57)
Se pintan como etiquetas grandes en mayúsculas: `SIN X` sobre fondo rojo translúcido,
`EXTRA Y` sobre verde. Aquí **no** se usa el helper `mods_html()`, sino un bucle propio,
porque el diseño necesita etiquetas con fondo y no texto en línea.

## Notas
- El fondo es oscuro (`bg-stone-900`) a propósito, para reducir la fatiga visual en una
  pantalla que está encendida todo el turno — aunque el layout que la envuelve es claro.
- Los botones son deliberadamente grandes (`py-5`, `text-xl`): se pulsan con guantes.
- El inventario crítico y el resumen por lotes no están aquí: los pinta el **layout**.
