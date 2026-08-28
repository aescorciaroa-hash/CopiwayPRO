<?php /** @var array $contadores @var array $flota @var array $despachos */ ?>
<div class="flex items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Despacho y Rutas</h1>
        <p class="text-gray-500 dark:text-gray-400">Seguimiento en vivo de la flota.</p>
    </div>
    <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-500 bg-emerald-50 dark:bg-emerald-950/30 rounded-full px-3 py-1.5">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> GPS Activo
    </span>
</div>

<div class="grid gap-4 sm:grid-cols-4 mb-6">
    <?php foreach ([
        ['En Camino', $contadores['en_camino'] . ' pedido(s)', 'truck'],
        ['Listos en Cocina', $contadores['listo'] . ' pedido(s)', 'chef-hat'],
        ['Repartidores Activos', count(array_filter($flota, fn($d) => $d['estado_disponibilidad'] !== 'desconectado')), 'users'],
        ['Tarifa de Envio', money($tarifa), 'dollar-sign'],
    ] as [$label, $valor, $icon]): ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl p-4 shadow-sm">
            <i data-lucide="<?= $icon ?>" class="w-5 h-5 text-brand-500 mb-2"></i>
            <p class="text-xs text-gray-400"><?= $label ?></p>
            <p class="text-lg font-black"><?= $valor ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-3">Rutas de Entrega</h2>
        <div id="mapa-rutas" class="rounded-2xl h-80 border border-gray-100 dark:border-stone-800 z-0"></div>
        <script>
        document.addEventListener('DOMContentLoaded', () => Copiway.map('mapa-rutas', {
            markers: [
                { lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Sede Central', color: '#f97316' },
                <?php foreach ($despachos as $d): if ($d['estado'] === 'en_camino'): ?>
                { address: <?= json_encode($d['direccion_entrega']) ?>, label: <?= json_encode($d['codigo'] . ' · ' . $d['cliente_nombre']) ?>, color: '#3b82f6' },
                <?php endif; endforeach; ?>
            ]
        }));
        </script>
        <div class="flex gap-4 mt-3 text-xs text-gray-400">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span> Sede Central</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> En Ruta</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Cliente</span>
        </div>
    </div>

    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-3">Flota de Domiciliarios</h2>
        <div class="space-y-3">
            <?php foreach ($flota as $d): ?>
                <div class="rounded-xl border border-gray-100 dark:border-stone-800 p-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-sm"><?= e($d['nombre']) ?></span>
                        <span class="text-[10px] font-black uppercase rounded-full px-2 py-1
                            <?= $d['estado_disponibilidad'] === 'en_ruta' ? 'bg-blue-100 text-blue-700'
                                : ($d['estado_disponibilidad'] === 'disponible' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-500') ?>">
                            <?= str_replace('_', ' ', $d['estado_disponibilidad']) ?>
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-0.5">
                        <?= e(ucfirst($d['tipo_vehiculo'])) ?><?= $d['placa'] ? ' · ' . e($d['placa']) : '' ?>
                        <?= $d['pedido_actual'] ? ' · lleva ' . e(\App\Models\Pedido::codigo(\App\Models\Pedido::find($d['pedido_actual']))) : '' ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <?php if (!$flota): ?><p class="text-sm text-gray-400">Sin domiciliarios activos.</p><?php endif; ?>
        </div>
    </div>
</div>

<div class="mt-6 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
    <h2 class="font-black text-lg mb-4">Despachos Recientes</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-stone-800">
                <tr><th class="py-2">Pedido</th><th>Cliente</th><th>Direccion</th><th>Domiciliario</th><th>Estado</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                <?php foreach ($despachos as $p): [$lbl, $cls] = estado_badge($p['estado']); ?>
                    <tr>
                        <td class="py-2.5 font-mono font-bold"><?= $p['codigo'] ?></td>
                        <td><?= e($p['cliente_nombre']) ?></td>
                        <td class="text-gray-500"><?= e($p['direccion_entrega']) ?></td>
                        <td class="text-gray-500"><?= e($p['domiciliario_nombre'] ?: 'Por asignar') ?></td>
                        <td><span class="text-[10px] font-black uppercase rounded-full px-2 py-1 <?= $cls ?>"><?= $lbl ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
