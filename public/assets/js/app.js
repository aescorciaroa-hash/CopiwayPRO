// Utilidades globales de CopiwayPRO

window.Copiway = {
    // Alterna el modo claro / oscuro y lo recuerda
    toggleTheme() {
        const html = document.documentElement;
        html.classList.toggle('dark');
        try {
            localStorage.setItem('copiway-theme', html.classList.contains('dark') ? 'dark' : 'light');
        } catch (e) {}
        if (window.lucide) lucide.createIcons();
    },

    // Formatea numero como pesos: 15000 -> "$ 15.000"
    money(value) {
        return '$ ' + Number(value || 0).toLocaleString('es-CO', { maximumFractionDigits: 0 });
    },

    // Helper fetch JSON con CSRF
    async post(url, data) {
        const body = new FormData();
        Object.entries(data || {}).forEach(([k, v]) => body.append(k, v));
        const meta = document.querySelector('meta[name="csrf-token"]');
        if (meta) body.append('_csrf', meta.content);
        const res = await fetch(url, { method: 'POST', body, headers: { 'X-Requested-With': 'fetch' } });
        return res.json();
    }
};

// Coordenadas de la sede (Dark Kitchen, Centro de Neiva)
window.COPIWAY_SEDE = [2.9345, -75.2809];

/**
 * Dibuja un mapa Leaflet.
 *   opts.center   [lat, lng]           centro inicial
 *   opts.dark     bool                 usa tiles oscuros
 *   opts.markers  [{lat,lng,label,color}]
 *   opts.route    [[lat,lng], ...]     polilinea opcional
 *   opts.address  "texto"              si no hay coords, se genera un punto estable desde el texto
 */
window.Copiway.map = function (elId, opts) {
    opts = opts || {};
    const el = document.getElementById(elId);
    if (!el || !window.L || el.dataset.ready) return;
    el.dataset.ready = '1';

    const dark = opts.dark ?? document.documentElement.classList.contains('dark');
    const center = opts.center || window.COPIWAY_SEDE;
    const map = L.map(el, { zoomControl: true, attributionControl: false }).setView(center, 14);

    // OpenStreetMap: sin API key. En modo oscuro se aplica un filtro CSS.
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    if (dark) {
        el.querySelectorAll('.leaflet-tile-pane').forEach(p => p.style.filter =
            'invert(1) hue-rotate(180deg) brightness(0.9) contrast(0.85) saturate(0.6)');
        const pane = map.getPane('tilePane');
        if (pane) pane.style.filter = 'invert(1) hue-rotate(180deg) brightness(0.9) contrast(0.85) saturate(0.6)';
    }

    const dot = (color) => L.divIcon({
        className: '', iconSize: [16, 16], iconAnchor: [8, 8],
        html: `<span style="display:block;width:16px;height:16px;border-radius:50%;background:${color};border:3px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></span>`
    });

    const pts = [];
    (opts.markers || []).forEach(m => {
        let lat = m.lat, lng = m.lng;
        if (lat == null && m.address) { const p = Copiway._geo(m.address); lat = p[0]; lng = p[1]; }
        if (lat == null) return;
        const mk = L.marker([lat, lng], { icon: dot(m.color || '#f97316') }).addTo(map);
        if (m.label) mk.bindPopup(m.label);
        pts.push([lat, lng]);
    });

    let route = opts.route;
    if (!route && opts.address) route = [window.COPIWAY_SEDE, Copiway._geo(opts.address)];
    if (route && route.length > 1) {
        L.polyline(route, { color: '#3b82f6', weight: 4, opacity: .8 }).addTo(map);
        route.forEach(p => pts.push(p));
    }

    if (pts.length > 1) map.fitBounds(pts, { padding: [40, 40] });
    else if (pts.length === 1) map.setView(pts[0], 15);

    setTimeout(() => map.invalidateSize(), 150);
    return map;
};

// Punto pseudo-aleatorio pero estable alrededor de la sede a partir de un texto
window.Copiway._geo = function (text) {
    let h = 0;
    for (let i = 0; i < (text || '').length; i++) h = (h * 31 + text.charCodeAt(i)) & 0xffffff;
    const dLat = ((h % 1000) / 1000 - 0.5) * 0.05;
    const dLng = (((h >> 10) % 1000) / 1000 - 0.5) * 0.05;
    return [window.COPIWAY_SEDE[0] + dLat, window.COPIWAY_SEDE[1] + dLng];
};

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
});
document.addEventListener('alpine:initialized', () => {
    if (window.lucide) lucide.createIcons();
});
