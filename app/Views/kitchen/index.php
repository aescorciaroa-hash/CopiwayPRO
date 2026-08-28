<?php
/** @var array $tablero @var array $resumen @var array $criticos */
$cols = [
    'pendiente'      => ['Pendientes', 'stone'],
    'en_preparacion' => ['En Preparacion', 'brand'],
    'listo'          => ['Listos', 'emerald'],
];
?>
<div x-data="{ sonido: true }">
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <span class="rounded-full bg-card border border-stone-700 px-4 py-2 text-sm font-bold">
            <i data-lucide="timer" class="w-4 h-4 inline text-brand-500"></i> Pedidos activos: <?= array_sum(array_map('count', $tablero)) ?>
        </span>
    </div>
    <div class="flex items-center gap-3">
        <button @click="sonido = !sonido; window.toast('config','Sonido de Alertas', sonido ? 'Alertas sonoras activadas.' : 'Alertas sonoras silenciadas.')"
                class="rounded-full border border-stone-700 px-4 py-2 text-sm font-bold"
                :class="sonido ? 'text-brand-400' : 'text-gray-500'">
            <i :data-lucide="sonido ? 'volume-2' : 'volume-x'" class="w-4 h-4 inline"></i>
            <span x-text="sonido ? 'Sonido Activo' : 'Silenciado'"></span>
        </button>
        <span class="rounded-lg bg-black px-3 py-2 font-mono font-black text-lg" x-data="{ t: '' }" x-init="setInterval(() => t = new Date().toLocaleTimeString('es-CO'), 1000)" x-text="t"></span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-[1fr_1fr_1fr_260px] gap-4">
    <?php foreach ($cols as $estado => [$label, $color]): ?>
        <div>
            <div class="rounded-xl bg-<?= $color === 'stone' ? 'card' : $color . '-500' ?> border border-stone-800 px-4 py-3 mb-3 flex items-center justify-between">
                <span class="font-black"><?= $label ?></span>
                <span class="bg-black/30 rounded-full px-2 py-0.5 text-sm font-black"><?= count($tablero[$estado]) ?></span>
            </div>
            <div class="space-y-3">
                <?php foreach ($tablero[$estado] as $p): ?>
                    <div class="bg-card rounded-xl p-4 border border-stone-800 border-l-4 border-l-<?= $color === 'stone' ? 'brand' : $color ?>-500
                                <?= $estado === 'en_preparacion' && $p['minutos'] > 15 ? 'sla-vencido' : '' ?>">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-2xl"><?= $p['codigo'] ?></span>
                            <span class="font-mono text-sm text-gray-400">hace <?= (int) $p['minutos'] ?> min</span>
                        </div>
                        <p class="text-sm text-gray-400 mt-0.5"><?= e($p['cliente_nombre']) ?></p>
                        <div class="mt-3 space-y-2">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="border-b border-stone-700 pb-2">
                                    <p class="font-bold text-lg"><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></p>
                                    <?php if ($l['personalizaciones']): ?>
                                        <p class="text-sm"><?= mods_html($l['personalizaciones']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex gap-2 mt-3">
                            <?php if ($estado === 'pendiente'): ?>
                                <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/preparar') ?>" class="flex-1">
                                    <?= csrf_field() ?>
                                    <button class="w-full bg-brand-500 hover:bg-brand-600 rounded-lg py-2.5 font-bold">Preparar</button>
                                </form>
                            <?php elseif ($estado === 'en_preparacion'): ?>
                                <a href="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/tirilla') ?>" target="_blank"
                                   class="rounded-lg bg-stone-800 hover:bg-stone-700 px-3 py-2.5 font-bold flex items-center"><i data-lucide="printer" class="w-4 h-4"></i></a>
                                <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/listo') ?>" class="flex-1">
                                    <?= csrf_field() ?>
                                    <button class="w-full bg-emerald-500 hover:bg-emerald-600 rounded-lg py-2.5 font-bold">Marcar Listo</button>
                                </form>
                            <?php else: ?>
                                <span class="w-full text-center text-sm text-gray-400 py-2.5 flex items-center justify-center gap-1">
                                    <i data-lucide="package-check" class="w-4 h-4"></i> Esperando domiciliario
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$tablero[$estado]): ?>
                    <p class="text-center text-stone-600 text-sm py-8">Sin pedidos</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Panel lateral -->
    <div class="space-y-4">
        <div class="bg-card rounded-xl border border-red-500/40 p-4">
            <h3 class="font-black text-sm text-red-400 mb-2 flex items-center gap-1"><i data-lucide="alert-triangle" class="w-4 h-4"></i> Inventario Critico</h3>
            <?php foreach ($criticos as $c): ?>
                <div class="flex justify-between text-sm py-1">
                    <span><?= e($c['nombre']) ?></span>
                    <span class="font-bold"><?= rtrim(rtrim(number_format($c['cantidad_stock'], 2, '.', ''), '0'), '.') ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (!$criticos): ?><p class="text-xs text-gray-500">Todo en orden.</p><?php endif; ?>
        </div>
        <div class="bg-card rounded-xl border border-stone-800 p-4">
            <h3 class="font-black text-sm text-brand-400 mb-2 flex items-center gap-1"><i data-lucide="layers" class="w-4 h-4"></i> Resumen de Preparacion</h3>
            <?php foreach ($resumen as $r): ?>
                <div class="flex justify-between text-sm py-1">
                    <span class="truncate"><?= e($r['nombre']) ?></span>
                    <span class="font-bold bg-brand-500/15 text-brand-400 rounded px-1.5">x<?= (int) $r['total'] ?></span>
                </div>
            <?php endforeach; ?>
            <?php if (!$resumen): ?><p class="text-xs text-gray-500">Sin pedidos por preparar.</p><?php endif; ?>
        </div>
    </div>
</div>
</div>


<?php $segundos = 15; require \App\Core\App::config("paths")["views"] . "/partials/autorefresh.php"; ?>
