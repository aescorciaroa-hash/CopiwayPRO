<?php
/** @var array $activos @var array $contadores @var array $productos */
$cols = [
    'pendiente'      => ['Pendientes', 'amber'],
    'en_preparacion' => ['En Cocina', 'blue'],
    'listo'          => ['Listos', 'purple'],
    'en_camino'      => ['En Camino', 'brand'],
];
// Estilo de cada estado para las tarjetas del tablero
$estados = [
    'pendiente'      => ['Pendiente',      'amber',   'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400'],
    'en_preparacion' => ['En Cocina',      'blue',    'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'],
    'listo'          => ['Listo',          'purple',  'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400'],
    'en_camino'      => ['En Camino',      'brand',   'bg-brand-100 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400'],
    'entregado'      => ['Entregado',      'emerald', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400'],
    'cancelado'      => ['Cancelado',      'stone',   'bg-gray-100 text-gray-500 dark:bg-stone-800 dark:text-gray-400'],
];
?>
<div x-data="comandasPage()">

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Ordenes en Tiempo Real</h1>
        <p class="text-gray-500 dark:text-gray-400">Monitoreo automatico. Los pagos digitales no requieren aprobacion manual.</p>
    </div>
    <button @click="openManual = true"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-bold">
        <i data-lucide="plus" class="w-4 h-4"></i> Registro Manual (Llamada/WhatsApp)
    </button>
</div>

<!-- Contadores -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <?php foreach ($cols as $estado => [$label, $color]): ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl p-4 text-center shadow-sm">
            <p class="text-3xl font-black text-<?= $color ?>-500"><?= str_pad((string) $contadores[$estado], 2, '0', STR_PAD_LEFT) ?></p>
            <p class="text-xs font-bold text-gray-400 uppercase mt-1"><?= $label ?></p>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($activos): ?>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    <?php foreach ($activos as $p):
        [$eLabel, $eColor, $eBadge] = $estados[$p['estado']] ?? $estados['pendiente'];
        $sla = $p['estado'] === 'en_preparacion' && (int) $p['minutos'] > 15;
    ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl p-4 shadow-sm
                    border-l-4 border-l-<?= $eColor ?>-500 <?= $sla ? 'sla-vencido' : '' ?>">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Orden ID</p>
                    <p class="font-mono font-black text-brand-500"><?= $p['codigo'] ?></p>
                </div>
                <span class="text-[10px] font-black uppercase rounded-full px-2.5 py-1 <?= $eBadge ?>"><?= $eLabel ?></span>
            </div>

            <p class="text-sm font-bold mt-2 flex items-center gap-1.5">
                <i data-lucide="user" class="w-3.5 h-3.5 text-gray-400"></i> <?= e($p['cliente_nombre']) ?>
            </p>
            <div class="text-xs text-gray-400 flex items-start gap-1.5 mt-1">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 mt-0.5 shrink-0"></i>
                <span class="flex-1"><?= e($p['direccion_entrega']) ?></span>
                <button @click="editarDir('<?= e($p['id_pedido']) ?>', '<?= e(addslashes($p['direccion_entrega'])) ?>')"
                        class="text-gray-400 hover:text-brand-500 shrink-0" title="Editar direccion">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="mt-3 rounded-xl bg-gray-50 dark:bg-stone-900 p-2.5">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">Detalle del pedido</p>
                <div class="space-y-1 text-sm">
                    <?php foreach ($p['lineas'] as $l): ?>
                        <div>
                            <span class="font-bold"><?= (int) $l['cantidad'] ?>x</span> <?= e($l['nombre']) ?>
                            <?php if ($l['personalizaciones']): ?>
                                <div class="text-xs"><?= mods_html($l['personalizaciones']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($p['domiciliario_nombre'])): ?>
                <div class="mt-3 rounded-xl bg-brand-500/10 border border-brand-500/20 p-2.5 flex items-center justify-between gap-2">
                    <span class="text-xs font-bold flex items-center gap-1.5 min-w-0">
                        <i data-lucide="bike" class="w-3.5 h-3.5 text-brand-500 shrink-0"></i>
                        <span class="truncate"><?= e($p['domiciliario_nombre']) ?><?= $p['placa'] ? ' · ' . e($p['placa']) : '' ?></span>
                    </span>
                    <span class="text-[10px] font-black uppercase rounded-full px-2 py-0.5 <?= $eBadge ?> shrink-0"><?= $eLabel ?></span>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-stone-800">
                <span class="text-[11px] text-gray-400 flex items-center gap-1">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> hace <?= (int) $p['minutos'] ?> min
                </span>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-black"><?= money($p['total']) ?></span>
                    <button @click="verDetalle('<?= e($p['id_pedido']) ?>')" class="text-gray-400 hover:text-brand-500" title="Ver detalle">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-16 text-center">
        <i data-lucide="party-popper" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
        <p class="font-bold">No hay comandas activas en este momento</p>
        <button @click="openManual = true" class="mt-4 rounded-xl bg-brand-500 text-white px-5 py-2.5 text-sm font-bold">Ingresar Orden Manual</button>
    </div>
<?php endif; ?>

<!-- Mapa de entregas (placeholder) -->
<div class="mt-6 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
    <h2 class="font-black text-lg mb-3">Mapa de Entregas</h2>
    <div id="mapa-comandas" class="rounded-2xl h-64 border border-gray-100 dark:border-stone-800 z-0"></div>
    <script>
    document.addEventListener('DOMContentLoaded', () => Copiway.map('mapa-comandas', {
        markers: [
            { lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Sede Central' },
            <?php foreach ($activos as $p): if ($p['estado'] === 'en_camino'): ?>
            { address: <?= json_encode($p['direccion_entrega']) ?>, label: <?= json_encode($p['codigo']) ?>, color: '#3b82f6' },
            <?php endif; endforeach; ?>
        ]
    }));
    </script>
</div>

<?php require __DIR__ . '/_modales.php'; ?>

</div>
<script>
function comandasPage() {
    return {
        openManual: false, openDetalle: false, openDir: false,
        pedido: {}, dirActual: '', dirPedidoId: '',
        productos: <?= json_encode(array_map(fn($p) => [
            'id' => $p['id_producto'], 'nombre' => $p['nombre'], 'precio' => (float) $p['precio'],
        ], $productos)) ?>,
        lineas: [{ id: '', cant: 1 }],
        addLinea() { this.lineas.push({ id: '', cant: 1 }); },
        quitarLinea(i) { this.lineas.splice(i, 1); },
        precio(id) { return (this.productos.find(p => p.id === id) || {}).precio || 0; },
        get subtotal() { return this.lineas.reduce((s, l) => s + this.precio(l.id) * (parseInt(l.cant) || 0), 0); },
        get envio() { return <?= (float) \App\Models\Configuracion::value('tarifa_plana_domicilio', 0) ?>; },
        money(v) { return '$ ' + Number(v || 0).toLocaleString('es-CO', { maximumFractionDigits: 0 }); },
        async verDetalle(id) {
            const res = await fetch('<?= url('/admin/comandas') ?>/' + id);
            this.pedido = await res.json();
            this.openDetalle = true;
            this.$nextTick(() => lucide.createIcons());
        },
        editarDir(id, dir) { this.dirPedidoId = id; this.dirActual = dir; this.openDir = true; },
    }
}
</script>


<?php $segundos = 15; require \App\Core\App::config("paths")["views"] . "/partials/autorefresh.php"; ?>
