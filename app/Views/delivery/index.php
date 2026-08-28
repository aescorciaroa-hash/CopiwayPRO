<?php
/** @var array $yo @var array $disponibles @var array $mios */
$disponible = ($yo['estado_disponibilidad'] ?? '') !== 'desconectado';
?>
<div x-data="{ pinOpen: false, pedidoId: '', pin: '' }">

<!-- Estado -->
<div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl p-4 mb-4 flex items-center justify-between">
    <span class="font-bold">Estado</span>
    <form method="post" action="<?= url('/delivery/disponibilidad') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="estado" value="<?= $disponible ? 'desconectado' : 'disponible' ?>">
        <button class="flex items-center gap-2 font-bold <?= $disponible ? 'text-brand-600' : 'text-gray-400' ?>">
            <span class="w-11 h-6 rounded-full relative transition <?= $disponible ? 'bg-brand-500' : 'bg-gray-300 dark:bg-stone-700' ?>">
                <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white transition-all <?= $disponible ? 'left-[22px]' : 'left-0.5' ?>"></span>
            </span>
            <?= $disponible ? 'Disponible' : 'Desconectado' ?>
        </button>
    </form>
</div>

<!-- Mis pedidos -->
<?php if ($mios): ?>
    <h2 class="font-black text-lg mb-3">Mis Entregas</h2>
    <div class="space-y-3 mb-6">
        <?php foreach ($mios as $p): ?>
            <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl overflow-hidden">
                <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                    <div class="bg-brand-500 text-white text-center text-sm font-black py-1.5">COBRAR EN EFECTIVO: <?= money($p['total']) ?></div>
                <?php endif; ?>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-black"><?= $p['codigo'] ?></span>
                        <span class="text-[10px] font-black uppercase rounded-full px-2 py-1 <?= $p['estado'] === 'en_camino' ? 'bg-brand-100 text-brand-700' : 'bg-purple-100 text-purple-700' ?>">
                            <?= $p['estado'] === 'en_camino' ? 'En Camino' : 'Recepcionado' ?>
                        </span>
                    </div>
                    <p class="text-sm mt-1 flex items-center gap-1"><i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i><?= e($p['direccion_entrega']) ?></p>
                    <div class="text-sm text-gray-500 mt-2">
                        <?php foreach ($p['lineas'] as $l): ?><div><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></div><?php endforeach; ?>
                    </div>

                    <div id="mapa-<?= e($p['id_pedido']) ?>" class="rounded-xl h-40 my-3 border border-gray-100 dark:border-stone-800 z-0"></div>
                    <script>
                    document.addEventListener('DOMContentLoaded', () => Copiway.map('mapa-<?= e($p['id_pedido']) ?>', {
                        address: <?= json_encode($p['direccion_entrega']) ?>,
                        markers: [
                            { lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Sede' },
                            { address: <?= json_encode($p['direccion_entrega']) ?>, label: <?= json_encode($p['direccion_entrega']) ?>, color: '#10b981' }
                        ]
                    }));
                    </script>

                    <?php if ($p['estado'] === 'listo'): ?>
                        <form method="post" action="<?= url('/delivery/pedido/' . $p['id_pedido'] . '/ruta') ?>">
                            <?= csrf_field() ?>
                            <button class="w-full min-h-[48px] bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl">Iniciar Ruta (En Camino)</button>
                        </form>
                    <?php else: ?>
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <a href="https://wa.me/57<?= e($p['cliente_telefono']) ?>" target="_blank" class="min-h-[48px] bg-emerald-500 text-white font-bold rounded-xl flex items-center justify-center gap-1 text-sm"><i data-lucide="phone" class="w-4 h-4"></i></a>
                            <a href="https://waze.com/ul?q=<?= urlencode($p['direccion_entrega']) ?>" target="_blank" class="min-h-[48px] bg-gray-100 dark:bg-stone-800 font-bold rounded-xl flex items-center justify-center text-sm">Waze</a>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($p['direccion_entrega']) ?>" target="_blank" class="min-h-[48px] bg-gray-100 dark:bg-stone-800 font-bold rounded-xl flex items-center justify-center text-sm">Maps</a>
                        </div>
                        <button @click="pinOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; pin = ''"
                                class="w-full min-h-[48px] bg-stone-900 text-white font-bold rounded-xl">Marcar Entregado</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Disponibles -->
<h2 class="font-black text-lg mb-3">Pedidos Disponibles</h2>
<?php if (!$disponible): ?>
    <p class="text-sm text-gray-400 py-8 text-center">Estas desconectado. Activa tu disponibilidad para ver pedidos.</p>
<?php elseif (!$disponibles): ?>
    <p class="text-sm text-gray-400 py-8 text-center">No hay entregas disponibles en este momento.</p>
<?php else: ?>
    <div class="space-y-3">
        <?php foreach ($disponibles as $p): ?>
            <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl overflow-hidden">
                <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                    <div class="bg-brand-500 text-white text-center text-sm font-black py-1.5">COBRAR EN EFECTIVO: <?= money($p['total']) ?></div>
                <?php endif; ?>
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-black"><?= $p['codigo'] ?></span>
                        <span class="font-black"><?= money($p['total']) ?> <span class="text-[10px] text-gray-400 uppercase"><?= e($p['pago_metodo']) ?></span></span>
                    </div>
                    <p class="text-sm mt-1 flex items-center gap-1"><i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i><?= e($p['direccion_entrega']) ?></p>
                    <div class="text-sm text-gray-500 mt-1">
                        <?php foreach ($p['lineas'] as $l): ?><span><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></span> <?php endforeach; ?>
                    </div>
                    <form method="post" action="<?= url('/delivery/pedido/' . $p['id_pedido'] . '/tomar') ?>" class="mt-3">
                        <?= csrf_field() ?>
                        <button class="w-full min-h-[48px] bg-brand-100 dark:bg-brand-500/15 text-brand-700 dark:text-brand-400 font-bold rounded-xl">Tomar Pedido</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal PIN -->
<div x-show="pinOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="pinOpen = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-sm">
        <div class="p-5 border-b border-gray-100 dark:border-stone-800 flex items-center justify-between">
            <h2 class="text-lg font-black">Validar Entrega</h2>
            <button @click="pinOpen = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form :action="'<?= url('/delivery/pedido') ?>/' + pedidoId + '/entregar'" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <p class="text-sm text-gray-500 text-center">Pide al cliente el PIN de 4 digitos de su pedido.</p>
            <input name="pin" x-model="pin" inputmode="numeric" maxlength="4" required
                   class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-4 text-2xl text-center tracking-[0.5em] font-black">
            <button class="w-full min-h-[48px] bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl">Confirmar Entrega</button>
        </form>
    </div>
</div>
</div>


<?php $segundos = 15; require \App\Core\App::config("paths")["views"] . "/partials/autorefresh.php"; ?>
