<?php
/** @var array $tablero @var array $resumen @var array $criticos */
$cols = [
    'pendiente'      => ['Pendientes', 'stone'],
    'en_preparacion' => ['Preparando', 'brand'],
    'listo'          => ['Listos', 'emerald'],
];
?>
<style>
@keyframes kds-sla {
    0%, 100% { border-color: #ef4444; box-shadow: 0 0 0 0 rgba(239,68,68,.45); }
    50%      { border-color: #fca5a5; box-shadow: 0 0 0 10px rgba(239,68,68,0); }
}
.kds-sla { animation: kds-sla 1s ease-in-out infinite; }
</style>

<!-- Tablero KDS · fondo oscuro para reducir fatiga visual -->
<div x-data="{ sonido: true }" class="bg-stone-900 text-stone-100 rounded-[32px] p-4 sm:p-6 min-h-[calc(100dvh-7rem)]">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-2xl bg-brand-500 flex items-center justify-center shrink-0">
                <i data-lucide="chef-hat" class="w-6 h-6 text-white"></i>
            </span>
            <div>
                <h1 class="text-2xl font-black tracking-tight text-white leading-none">Tablero de Cocina</h1>
                <p class="text-sm font-medium text-stone-400 mt-1 flex items-center gap-1.5">
                    <i data-lucide="rotate-cw" class="w-4 h-4 text-brand-500 animate-spin-slow"></i>
                    Tiempo promedio: <span class="font-black text-white">8.5 min</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="sonido = !sonido; window.toast('config','Sonido de Alertas', sonido ? 'Alertas sonoras activadas.' : 'Alertas sonoras silenciadas.')"
                    class="inline-flex items-center gap-2 rounded-2xl px-4 py-3 text-sm font-bold border transition-colors"
                    :class="sonido ? 'bg-brand-500/15 border-brand-500/30 text-brand-400' : 'bg-stone-800 border-stone-700 text-stone-400'">
                <i :data-lucide="sonido ? 'volume-2' : 'volume-x'" class="w-5 h-5"></i>
                <span x-text="sonido ? 'Sonido Activo' : 'Silenciado'"></span>
            </button>
            <span class="bg-white text-stone-900 px-5 py-3 rounded-2xl font-mono font-black text-lg tracking-widest shadow-lg"
                  x-data="{ t: '' }" x-init="setInterval(() => t = new Date().toLocaleTimeString('es-CO'), 1000)" x-text="t || '09:41:49'">
                09:41:49
            </span>
        </div>
    </div>

    <!-- Kanban: 3 columnas -->
    <div class="flex gap-5 overflow-x-auto pb-2 snap-x snap-mandatory lg:grid lg:grid-cols-3 lg:overflow-visible">

        <!-- COLUMNA 1 · PENDIENTES -->
        <div class="w-[85vw] sm:w-[400px] lg:w-auto shrink-0 snap-center">
            <div class="flex items-center gap-3 mb-4 px-1">
                <h2 class="text-xl font-black tracking-tight text-white">Pendientes</h2>
                <span class="min-w-[32px] h-8 px-2 rounded-full bg-brand-500 text-white text-sm font-black flex items-center justify-center">
                    <?= count($tablero['pendiente']) ?>
                </span>
                <i data-lucide="clock" class="w-5 h-5 text-stone-500 ml-auto"></i>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['pendiente'] as $p): ?>
                    <div class="bg-stone-800 rounded-[32px] p-5 border border-stone-700/60 space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-black text-2xl text-white block"><?= e($p['codigo']) ?></span>
                                <span class="text-sm font-medium text-stone-400 flex items-center gap-1.5 mt-0.5">
                                    <i data-lucide="clock" class="w-4 h-4"></i> Hace <?= (int) $p['minutos'] ?> min
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-stone-900/70 rounded-2xl p-4 border border-stone-700/50 space-y-2.5">
                                    <?php if ($l['personalizaciones']): ?>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($l['personalizaciones'] as $mod): ?>
                                                <?php $sin = ($mod['accion_modificacion'] ?? '') === 'quitar'; ?>
                                                <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[13px] font-black uppercase tracking-wide
                                                             <?= $sin ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' ?>">
                                                    <?= $sin ? 'SIN' : 'EXTRA' ?> <?= e($mod['nombre']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-start gap-3">
                                        <span class="font-black text-brand-500 text-xl shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                        <p class="font-bold text-white text-lg leading-snug"><?= e($l['nombre']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/preparar') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-black text-xl rounded-2xl py-5 shadow-lg shadow-brand-500/25 transition-all duration-300 active:scale-[0.98]">
                                Preparar
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['pendiente'])): ?>
                    <div class="bg-stone-800/60 rounded-[32px] p-10 text-center border border-stone-700/50 flex flex-col items-center justify-center text-stone-500 min-h-[240px]">
                        <i data-lucide="inbox" class="w-12 h-12 mb-3 stroke-[1.5]"></i>
                        <p class="text-base font-bold">Sin pedidos pendientes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 2 · PREPARANDO -->
        <div class="w-[85vw] sm:w-[400px] lg:w-auto shrink-0 snap-center">
            <div class="bg-brand-500 text-white rounded-2xl px-5 py-4 font-black tracking-tight text-xl flex items-center justify-between shadow-lg shadow-brand-500/20 mb-4">
                <span>Preparando</span>
                <span class="min-w-[32px] h-8 px-2 rounded-full bg-white/20 text-white text-sm font-black flex items-center justify-center">
                    <?= count($tablero['en_preparacion']) ?>
                </span>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['en_preparacion'] as $p): $tarde = (int) $p['minutos'] >= 15; ?>
                    <div class="bg-stone-800 rounded-[32px] p-5 space-y-4
                                <?= $tarde ? 'border-2 kds-sla' : 'border border-stone-700/60' ?>">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-black text-2xl <?= $tarde ? 'text-red-400' : 'text-white' ?> block"><?= e($p['codigo']) ?></span>
                                <span class="text-sm font-bold <?= $tarde ? 'text-red-400' : 'text-stone-400' ?> flex items-center gap-1.5 mt-0.5">
                                    <i data-lucide="clock" class="w-4 h-4"></i> Hace <?= (int) $p['minutos'] ?> min<?= $tarde ? ' · SLA SUPERADO' : '' ?>
                                </span>
                            </div>
                            <?php if ($tarde): ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-red-500 text-white text-xs font-black px-3 py-1.5 uppercase">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Demora
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-stone-900/70 rounded-2xl p-4 border border-stone-700/50 space-y-2.5">
                                    <?php if ($l['personalizaciones']): ?>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($l['personalizaciones'] as $mod): ?>
                                                <?php $sin = ($mod['accion_modificacion'] ?? '') === 'quitar'; ?>
                                                <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-[13px] font-black uppercase tracking-wide
                                                             <?= $sin ? 'bg-red-500/15 text-red-400' : 'bg-emerald-500/15 text-emerald-400' ?>">
                                                    <?= $sin ? 'SIN' : 'EXTRA' ?> <?= e($mod['nombre']) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex items-start gap-3">
                                        <span class="font-black text-brand-500 text-xl shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                        <p class="font-bold text-white text-lg leading-snug"><?= e($l['nombre']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex gap-3">
                            <a href="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/tirilla') ?>" target="_blank"
                               class="px-5 py-5 rounded-2xl bg-stone-700 hover:bg-stone-600 text-white font-bold text-base flex items-center justify-center gap-2 transition shrink-0">
                                <i data-lucide="printer" class="w-5 h-5"></i> Sticker
                            </a>
                            <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/listo') ?>" class="flex-1">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xl rounded-2xl py-5 shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition-all duration-300 active:scale-[0.98]">
                                    <i data-lucide="check-circle-2" class="w-6 h-6"></i> Marcar Listo
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['en_preparacion'])): ?>
                    <div class="bg-stone-800/60 rounded-[32px] p-12 text-center border border-stone-700/50 flex flex-col items-center justify-center text-stone-500 min-h-[300px]">
                        <i data-lucide="utensils-crossed" class="w-12 h-12 mb-3 stroke-[1.5]"></i>
                        <p class="text-base font-bold">Sin pedidos en curso</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 3 · LISTOS -->
        <div class="w-[85vw] sm:w-[400px] lg:w-auto shrink-0 snap-center">
            <div class="flex items-center gap-3 mb-4 px-1">
                <h2 class="text-xl font-black tracking-tight text-white">Listos</h2>
                <span class="min-w-[32px] h-8 px-2 rounded-full bg-emerald-500/20 text-emerald-400 text-sm font-black flex items-center justify-center">
                    <?= count($tablero['listo']) ?>
                </span>
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 ml-auto"></i>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['listo'] as $p): ?>
                    <div class="bg-stone-800 rounded-[32px] p-5 border border-stone-700/60 space-y-4">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <span class="font-mono font-black text-2xl text-white block"><?= e($p['codigo']) ?></span>
                                <span class="text-sm font-medium text-stone-400 flex items-center gap-1.5 mt-0.5">
                                    <i data-lucide="clock" class="w-4 h-4"></i> Hace <?= (int) $p['minutos'] ?> min
                                </span>
                            </div>
                            <?php if (!empty($p['domiciliario_nombre'])): ?>
                                <span class="bg-stone-700 text-white px-3 py-1.5 rounded-full text-xs font-bold shrink-0">
                                    <?= e($p['domiciliario_nombre']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-3">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-stone-900/70 rounded-2xl p-4 border border-stone-700/50 flex items-start gap-3">
                                    <span class="font-black text-brand-500 text-xl shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                    <p class="font-bold text-white text-lg leading-snug"><?= e($l['nombre']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="w-full bg-stone-900/70 text-stone-400 text-sm font-bold rounded-2xl py-4 flex items-center justify-center gap-2 border border-stone-700/50">
                            <i data-lucide="bike" class="w-5 h-5"></i> Esperando domiciliario
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['listo'])): ?>
                    <div class="bg-stone-800/60 rounded-[32px] p-10 text-center border border-stone-700/50 flex flex-col items-center justify-center text-stone-500 min-h-[240px]">
                        <i data-lucide="package-check" class="w-12 h-12 mb-3 stroke-[1.5]"></i>
                        <p class="text-base font-bold">Sin pedidos listos</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>
