<?php
/** @var array $tablero @var array $resumen @var array $criticos */
$cols = [
    'pendiente'      => ['Pendientes', 'stone'],
    'en_preparacion' => ['En Preparación', 'brand'],
    'listo'          => ['Listos', 'emerald'],
];
?>
<div x-data="{ sonido: true }">
    <!-- Header Superior KDS -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <span class="inline-flex items-center gap-2 bg-slate-100/90 border border-slate-200/60 rounded-full px-4 py-2 text-xs font-extrabold text-slate-700 shadow-sm">
                <i data-lucide="rotate-cw" class="w-4 h-4 text-amber-500 animate-spin-slow"></i>
                TIEMPO PROM: <span class="text-slate-900 font-black">8.5 MIN</span>
            </span>
        </div>

        <div class="flex items-center gap-3">
            <button @click="sonido = !sonido; window.toast('config','Sonido de Alertas', sonido ? 'Alertas sonoras activadas.' : 'Alertas sonoras silenciadas.')"
                    class="inline-flex items-center gap-2 bg-amber-50/80 border border-amber-200/70 rounded-full px-4 py-2 text-xs font-bold transition"
                    :class="sonido ? 'text-amber-700' : 'text-slate-400 bg-slate-100 border-slate-200'">
                <i :data-lucide="sonido ? 'volume-2' : 'volume-x'" class="w-4 h-4 text-amber-500"></i>
                <span x-text="sonido ? 'Sonido Activo' : 'Silenciado'"></span>
            </button>

            <span class="bg-[#0f172a] text-white px-4 py-2 rounded-2xl font-mono font-black text-sm tracking-wider shadow-sm"
                  x-data="{ t: '' }" x-init="setInterval(() => t = new Date().toLocaleTimeString('es-CO'), 1000)" x-text="t || '09:41:49'">
                09:41:49
            </span>
        </div>
    </div>

    <!-- Grid de 3 Columnas principales KDS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- COLUMNA 1: PENDIENTES -->
        <div>
            <div class="flex items-center justify-between mb-4 px-1">
                <div class="flex items-center gap-2.5 font-extrabold text-slate-800 text-base">
                    <span>Pendientes</span>
                    <span class="w-6 h-6 rounded-full bg-[#ff6600] text-white text-xs font-black flex items-center justify-center shadow-sm">
                        <?= count($tablero['pendiente']) ?>
                    </span>
                </div>
                <i data-lucide="clock" class="w-5 h-5 text-slate-400"></i>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['pendiente'] as $p): ?>
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100/90 space-y-4 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-black text-xl text-slate-900 block"><?= e($p['codigo']) ?></span>
                                <span class="text-xs font-medium text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Hace <?= (int) $p['minutos'] ?> min
                                </span>
                            </div>
                            <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">||</span>
                        </div>

                        <div class="space-y-2">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-slate-50/70 rounded-2xl p-3.5 border border-slate-100 flex items-start gap-3">
                                    <span class="font-extrabold text-[#ff6600] text-sm shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-snug"><?= e($l['nombre']) ?></p>
                                        <?php if ($l['personalizaciones']): ?>
                                            <p class="text-xs text-slate-500 mt-1"><?= mods_html($l['personalizaciones']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/preparar') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full bg-[#ff6600] hover:bg-[#e65c00] text-white font-bold rounded-2xl py-3.5 shadow-lg shadow-orange-500/25 text-sm transition">
                                Preparar
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['pendiente'])): ?>
                    <div class="bg-white rounded-3xl p-10 text-center border border-slate-100/80 flex flex-col items-center justify-center text-slate-400 min-h-[280px]">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-3">
                            <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-500">Sin pedidos pendientes</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 2: EN PREPARACIÓN -->
        <div>
            <div class="bg-[#ff6600] text-white rounded-2xl px-5 py-3.5 font-bold text-sm flex items-center justify-between shadow-md shadow-orange-500/20 mb-4">
                <span>En Preparación</span>
                <span class="w-6 h-6 rounded-full bg-white/20 text-white text-xs font-black flex items-center justify-center">
                    <?= count($tablero['en_preparacion']) ?>
                </span>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['en_preparacion'] as $p): ?>
                    <div class="bg-white rounded-3xl p-5 shadow-md border-2 border-red-500 space-y-4 relative overflow-hidden">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-black text-xl text-red-600 block"><?= e($p['codigo']) ?></span>
                                <span class="text-xs font-medium text-red-500 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Hace <?= (int) $p['minutos'] ?> min
                                </span>
                            </div>
                            <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">||</span>
                        </div>

                        <div class="space-y-2">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-slate-50/70 rounded-2xl p-3.5 border border-slate-100 flex items-start gap-3">
                                    <span class="font-extrabold text-[#ff6600] text-sm shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-snug"><?= e($l['nombre']) ?></p>
                                        <?php if ($l['personalizaciones']): ?>
                                            <p class="text-xs text-slate-500 mt-1"><?= mods_html($l['personalizaciones']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="flex gap-2.5 pt-1">
                            <a href="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/tirilla') ?>" target="_blank"
                               class="w-12 h-12 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl flex items-center justify-center transition shrink-0">
                                <i data-lucide="printer" class="w-5 h-5"></i>
                            </a>
                            <form method="post" action="<?= url('/kitchen/pedido/' . $p['id_pedido'] . '/listo') ?>" class="flex-1">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-bold rounded-2xl py-3.5 shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 text-sm transition">
                                    <i data-lucide="check-circle-2" class="w-5 h-5"></i> Marcar Listo
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['en_preparacion'])): ?>
                    <div class="bg-white rounded-3xl p-12 text-center border border-slate-100/80 flex flex-col items-center justify-center text-slate-300 min-h-[300px]">
                        <i data-lucide="utensils-crossed" class="w-12 h-12 mb-3 text-slate-300 stroke-[1.5]"></i>
                        <p class="text-sm font-semibold text-slate-400">Sin pedidos en curso</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA 3: LISTOS -->
        <div>
            <div class="flex items-center justify-between mb-4 px-1">
                <div class="flex items-center gap-2.5 font-extrabold text-slate-800 text-base">
                    <span>Listos</span>
                    <span class="w-6 h-6 rounded-full bg-amber-100 text-amber-800 text-xs font-black flex items-center justify-center">
                        <?= count($tablero['listo']) ?>
                    </span>
                </div>
                <i data-lucide="check-circle-2" class="w-5 h-5 text-amber-500"></i>
            </div>

            <div class="space-y-4">
                <?php foreach ($tablero['listo'] as $p): ?>
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-100/90 space-y-4 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono font-black text-xl text-slate-900 block"><?= e($p['codigo']) ?></span>
                                <span class="text-xs font-medium text-slate-400 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Hace <?= (int) $p['minutos'] ?> min
                                </span>
                            </div>
                            <?php if (!empty($p['domiciliario_nombre'])): ?>
                                <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-bold">
                                    <?= e($p['domiciliario_nombre']) ?>
                                </span>
                            <?php else: ?>
                                <span class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">||</span>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-2">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="bg-slate-50/70 rounded-2xl p-3.5 border border-slate-100 flex items-start gap-3">
                                    <span class="font-extrabold text-[#ff6600] text-sm shrink-0"><?= (int) $l['cantidad'] ?>x</span>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm leading-snug"><?= e($l['nombre']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="w-full bg-slate-50 text-slate-600 text-xs font-bold rounded-2xl py-3 flex items-center justify-center gap-2 border border-slate-100/80">
                            <i data-lucide="download" class="w-4 h-4 text-slate-400"></i> Esperando domiciliario
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($tablero['listo'])): ?>
                    <div class="bg-white rounded-3xl p-10 text-center border border-slate-100/80 flex flex-col items-center justify-center text-slate-400 min-h-[280px]">
                        <i data-lucide="package-check" class="w-12 h-12 mb-3 text-slate-300 stroke-[1.5]"></i>
                        <p class="text-sm font-semibold text-slate-400">Sin pedidos listos</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>

