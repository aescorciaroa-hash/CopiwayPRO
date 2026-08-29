<?php
/** @var array $pedidos */
$pasos  = ['pendiente' => 0, 'en_preparacion' => 1, 'listo' => 2, 'en_camino' => 3];
$labels = ['Recibido', 'Preparando', 'Listo', 'En camino'];
$iconos = ['package-check', 'chef-hat', 'bell', 'bike'];
?>
<h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white mb-6">Ordenes Activas</h1>

<?php if (!$pedidos): ?>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-16 text-center shadow-sm">
        <i data-lucide="package-search" class="w-10 h-10 mx-auto text-gray-300 dark:text-stone-700 mb-3"></i>
        <p class="font-black tracking-tight text-gray-900 dark:text-white">No tienes ordenes activas.</p>
        <a href="<?= url('/client') ?>"
           class="inline-block mt-4 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium transition-all duration-300 hover:scale-105">Hacer un pedido</a>
    </div>
<?php else: ?>
<div class="space-y-5">
    <?php foreach ($pedidos as $p): $nivel = $pasos[$p['estado']] ?? 0; ?>
        <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-8">
                <div>
                    <span class="font-mono font-black text-lg text-gray-900 dark:text-white"><?= $p['codigo'] ?></span>
                    <p class="text-sm text-gray-400 font-medium"><?= count($p['lineas']) ?> producto(s) · <?= money($p['total']) ?></p>
                </div>
                <div class="text-right rounded-2xl bg-brand-500/10 px-4 py-2">
                    <p class="text-[10px] text-brand-600 dark:text-brand-500 font-black uppercase">PIN de entrega</p>
                    <p class="font-mono font-black text-xl tracking-widest text-brand-600 dark:text-brand-500"><?= e($p['pin_entrega']) ?></p>
                </div>
            </div>

            <!-- Stepper -->
            <div class="flex items-start">
                <?php foreach ($labels as $i => $lbl):
                    $done = $i < $nivel; $current = $i === $nivel; $active = $i <= $nivel; ?>
                    <?php if ($i > 0): ?>
                        <div class="flex-1 h-1 rounded-full mt-5 <?= $i <= $nivel ? 'bg-brand-500' : 'bg-gray-200 dark:bg-stone-700' ?>"></div>
                    <?php endif; ?>
                    <div class="flex flex-col items-center gap-2 shrink-0 w-20">
                        <span class="w-10 h-10 rounded-2xl flex items-center justify-center transition-colors
                                     <?= $active ? 'bg-brand-500 text-white' : 'bg-gray-100 dark:bg-stone-800 text-gray-400' ?>
                                     <?= $current ? 'ring-4 ring-brand-500/20' : '' ?>">
                            <i data-lucide="<?= $done ? 'check' : $iconos[$i] ?>" class="w-5 h-5 <?= $current ? 'animate-pulse' : '' ?>"></i>
                        </span>
                        <span class="text-[10px] font-black uppercase tracking-wide text-center leading-tight
                                     <?= $active ? 'text-brand-600 dark:text-brand-500' : 'text-gray-400' ?>"><?= $lbl ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($p['estado'] === 'en_camino' && $p['domiciliario_nombre']): ?>
                <div class="mt-6 rounded-2xl bg-brand-500/10 border border-brand-500/20 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-11 h-11 rounded-2xl bg-brand-500 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="bike" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <p class="text-[11px] font-black uppercase text-brand-600 dark:text-brand-500 flex items-center gap-1.5">
                                Domiciliario en camino <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            </p>
                            <p class="font-bold text-gray-900 dark:text-white"><?= e($p['domiciliario_nombre']) ?> · <?= e(ucfirst($p['tipo_vehiculo'])) ?> <?= e($p['placa']) ?></p>
                        </div>
                    </div>
                    <a href="https://wa.me/57<?= e($p['domiciliario_telefono']) ?>" target="_blank"
                       class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium flex items-center justify-center gap-2
                              shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Contactar por WhatsApp
                    </a>
                </div>
            <?php endif; ?>

            <div class="mt-6 pt-5 border-t border-gray-100 dark:border-stone-800 space-y-1.5 text-sm">
                <?php foreach ($p['lineas'] as $l): ?>
                    <div class="font-medium text-gray-700 dark:text-gray-300">
                        <span class="text-brand-600 dark:text-brand-500 font-black"><?= (int) $l['cantidad'] ?>x</span> <?= e($l['nombre']) ?>
                        <?php if ($l['personalizaciones']): ?>
                            <span class="text-xs font-bold"><?= mods_html($l['personalizaciones']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>


<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>
