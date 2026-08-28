# `public/assets/js/app.js`

## Ubicación
`public/assets/js/app.js`

## Propósito
JavaScript propio del frontend. Define el objeto global **`window.Copiway`** con
utilidades que se usan en todas las páginas: cambio de tema, formato de dinero, envío
de formularios por `fetch` y **dibujo de mapas con Leaflet**.

## Contenido

### `window.Copiway` — objeto de utilidades

| Función | Qué hace |
|---------|----------|
| `toggleTheme()` | Alterna la clase `dark` en `<html>` y guarda la elección en `localStorage['copiway-theme']`. Repinta los iconos de Lucide |
| `money(value)` | Formatea un número como pesos: `15000` → `"$ 15.000"` (usa `toLocaleString('es-CO')`) |
| `post(url, data)` | Hace un `fetch` POST: arma un `FormData`, le añade el token CSRF que está en `<meta name="csrf-token">`, y devuelve la respuesta como JSON. Se usa para acciones AJAX |

### `window.COPIWAY_SEDE`
Coordenadas fijas de la cocina (`[2.9345, -75.2809]`, centro de Neiva). Punto de
partida de todas las rutas en los mapas.

### `window.Copiway.map(elId, opts)` — dibuja un mapa Leaflet
Parámetros de `opts`:
- `center` `[lat, lng]` — centro inicial (por defecto la sede).
- `dark` — usa filtro oscuro (por defecto según la clase `dark`).
- `markers` — `[{lat, lng, label, color}]` o `[{address, label}]`.
- `route` — `[[lat,lng], ...]` para dibujar una línea de ruta.
- `address` — si no hay coords, genera un punto estable a partir del texto.

Pasos que hace:
1. Busca el `<div>` por id; si no existe o ya está listo, sale.
2. Crea el mapa con `L.map(...)`.
3. Añade capa de teselas de **OpenStreetMap** (`tile.openstreetmap.org`) — **sin API key**.
4. Si es modo oscuro, aplica un filtro CSS (`invert`, `hue-rotate`…) a las teselas.
5. Dibuja los marcadores como puntos de color (`L.divIcon`).
6. Si hay ruta o dirección, dibuja una **polilínea** azul.
7. Ajusta el zoom para que se vean todos los puntos (`fitBounds`).

### `window.Copiway._geo(text)`
Convierte un texto de dirección en unas coordenadas **pseudo-aleatorias pero estables**
alrededor de la sede (hash del texto → pequeño desplazamiento de lat/lng). **No es
geocodificación real** — es un sustituto para la demo.

### Al cargar la página
```js
document.addEventListener('DOMContentLoaded', () => { if (window.lucide) lucide.createIcons(); });
document.addEventListener('alpine:initialized', () => { if (window.lucide) lucide.createIcons(); });
```
Dibuja los iconos de Lucide cuando el DOM y Alpine están listos.

## Notas
- Depende de **Leaflet** (cargado por CDN en `partials/head.php`).
- Las direcciones no se geocodifican de verdad; los puntos en el mapa son aproximados.
