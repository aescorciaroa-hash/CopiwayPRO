<?php
/** @var array $pedidos */
$pasos = ['pendiente' => 0, 'en_preparacion' => 1, 'listo' => 2, 'en_camino' => 3];
$labels = ['Recibido', 'En preparacion', 'Listo', 'En camino'];
?>
<h1 class="text-3xl font-black mb-6">Ordenes Activas</h1>

<?php if (!$pedidos): ?>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-16 text-center">
        <i data-lucide="package-search" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
        <p class="font-bold">No tienes ordenes activas.</p>
        <a href="<?= url('/client') ?>" class="inline-block mt-4 rounded-xl bg-brand-500 text-white px-5 py-2.5 text-sm font-bold">Hacer un pedido</a>
    </div>
<?php else: ?>
<div class="space-y-5">
    <?php foreach ($pedidos as $p): $nivel = $pasos[$p['estado']] ?? 0; ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                <div>
                    <span class="font-mono font-black text-lg"><?= $p['codigo'] ?></span>
                    <p class="text-sm text-gray-400"><?= count($p['lineas']) ?> producto(s) · <?= money($p['total']) ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">PIN de entrega</p>
                    <p class="font-mono font-black text-xl tracking-widest text-brand-600"><?= e($p['pin_entrega']) ?></p>
                </div>
            </div>

            <!-- Barra de progreso -->
            <div class="flex items-center">
                <?php foreach ($labels as $i => $lbl): ?>
                    <div class="flex flex-col items-center <?= $i > 0 ? 'flex-1' : '' ?>">
                        <?php if ($i > 0): ?>
                            <div class="w-full h-1 -mt-4 mb-3 <?= $i <= $nivel ? 'bg-brand-500' : 'bg-gray-200 dark:bg-stone-700' ?>"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="flex justify-between -mt-2">
                <?php foreach ($labels as $i => $lbl): ?>
                    <div class="flex flex-col items-center text-center" style="width: 25%">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black
                            <?= $i <= $nivel ? 'bg-brand-500 text-white' : 'bg-gray-200 dark:bg-stone-700 text-gray-400' ?>">
                            <?= $i < $nivel ? '✓' : $i + 1 ?>
                        </span>
                        <span class="text-[11px] font-bold mt-1 <?= $i <= $nivel ? 'text-brand-600' : 'text-gray-400' ?>"><?= $lbl ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($p['estado'] === 'en_camino' && $p['domiciliario_nombre']): ?>
                <div class="mt-5 rounded-2xl bg-brand-50 dark:bg-brand-500/10 p-4 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black uppercase text-brand-600">Domiciliario en camino</p>
                        <p class="font-bold"><?= e($p['domiciliario_nombre']) ?> · <?= e(ucfirst($p['tipo_vehiculo'])) ?> <?= e($p['placa']) ?></p>
                    </div>
                    <a href="https://wa.me/57<?= e($p['domiciliario_telefono']) ?>" target="_blank"
                       class="rounded-xl bg-emerald-500 text-white px-4 py-2 text-sm font-bold flex items-center gap-1">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> Contactar
                    </a>
                </div>
            <?php endif; ?>

            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-stone-800 text-sm text-gray-500">
                <?php foreach ($p['lineas'] as $l): ?>
                    <div><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?>
                        <?php if ($l['personalizaciones']): ?>
                            <span class="text-xs"><?= mods_html($l['personalizaciones']) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>


<?php $segundos = 15; require \App\Core\App::config("paths")["views"] . "/partials/autorefresh.php"; ?>
