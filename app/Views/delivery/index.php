<?php
/** @var array $yo @var array $disponibles @var array $mios */
$disponible = ($yo['estado_disponibilidad'] ?? '') !== 'desconectado';
$primerPedido = $mios[0] ?? $disponibles[0] ?? null;
$direccionActiva = $primerPedido['direccion_entrega'] ?? 'Calle 10 # 5-20, Centro';
$codigoActivo = $primerPedido['codigo'] ?? '#ORD-4931';
$pedidoIdActivo = $primerPedido['id_pedido'] ?? '';
$rutaEnCamino = $primerPedido && (($primerPedido['estado'] ?? '') === 'en_camino');
?>
<div class="flex-1 h-full w-full overflow-y-auto bg-gray-50 dark:bg-stone-900 font-sans"
     x-data="{ pinOpen: false, pedidoId: '<?= e($pedidoIdActivo) ?>', pin: '' }">
<div class="max-w-xl mx-auto p-4 space-y-4 pb-12">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 rounded-2xl bg-brand-500 text-white flex items-center justify-center shadow-lg shadow-brand-500/25">
                <i data-lucide="navigation" class="w-5 h-5"></i>
            </span>
            <span class="font-black text-xl tracking-tight text-gray-900 dark:text-white">Copiway<span class="text-brand-500">PRO</span></span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="Copiway.toggleTheme()"
                    class="w-10 h-10 rounded-2xl bg-white dark:bg-stone-800 border border-gray-100 dark:border-stone-700 flex items-center justify-center text-gray-500">
                <i data-lucide="moon" class="w-5 h-5 dark:hidden"></i>
                <i data-lucide="sun" class="w-5 h-5 hidden dark:block text-amber-400"></i>
            </button>
            <form method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="w-10 h-10 rounded-2xl bg-red-50 dark:bg-red-950/30 text-red-500 flex items-center justify-center">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Estado / Disponibilidad -->
    <form method="post" action="<?= url('/delivery/disponibilidad') ?>"
          class="bg-white dark:bg-stone-800 border border-gray-100 dark:border-stone-700 rounded-2xl p-4 flex items-center justify-between">
        <?= csrf_field() ?>
        <input type="hidden" name="estado" value="<?= $disponible ? 'desconectado' : 'disponible' ?>">
        <span class="font-bold text-sm text-gray-700 dark:text-gray-200">Estado de conexion</span>
        <button type="submit" class="flex items-center gap-2.5 font-black text-sm <?= $disponible ? 'text-brand-600 dark:text-brand-500' : 'text-gray-400' ?>">
            <span class="w-12 h-7 rounded-full relative transition p-0.5 <?= $disponible ? 'bg-brand-500' : 'bg-gray-300 dark:bg-stone-600' ?>">
                <span class="block w-6 h-6 rounded-full bg-white shadow-sm transition-transform <?= $disponible ? 'translate-x-5' : 'translate-x-0' ?>"></span>
            </span>
            <?= $disponible ? 'Disponible' : 'Desconectado' ?>
        </button>
    </form>

    <!-- MAPA · ocupa gran parte de la pantalla -->
    <div class="relative rounded-[32px] overflow-hidden border border-gray-100 dark:border-stone-700 shadow-sm bg-gray-100 dark:bg-stone-950 h-[52vh]">
        <div id="mapa-delivery-full" class="absolute inset-0 z-0"></div>

        <!-- Destino -->
        <div class="absolute top-4 left-4 right-4 z-[500] bg-white/95 dark:bg-stone-900/95 backdrop-blur rounded-2xl p-3.5 shadow-lg border border-gray-100 dark:border-stone-700 flex items-center gap-3 pointer-events-none">
            <span class="w-11 h-11 rounded-2xl bg-brand-500/15 text-brand-600 dark:text-brand-500 flex items-center justify-center shrink-0">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
            </span>
            <div class="min-w-0">
                <span class="text-[10px] font-black tracking-wider uppercase text-gray-400 block">Destino</span>
                <h4 class="font-black tracking-tight text-gray-900 dark:text-white leading-tight truncate"><?= e($direccionActiva) ?></h4>
                <p class="text-xs font-medium text-gray-500">Llegada est: 12 mins</p>
            </div>
        </div>

        <?php if ($rutaEnCamino): ?>
            <!-- Acciones flotantes sobre el mapa (en transito) -->
            <div class="absolute bottom-4 left-4 right-4 z-[500] space-y-2.5">
                <div class="grid grid-cols-2 gap-2.5">
                    <a href="https://waze.com/ul?q=<?= urlencode($direccionActiva) ?>" target="_blank"
                       class="bg-white dark:bg-stone-800 text-blue-600 dark:text-blue-400 font-bold rounded-2xl py-3.5 flex items-center justify-center gap-2 shadow-lg border border-gray-100 dark:border-stone-700 text-sm">
                        <i data-lucide="compass" class="w-5 h-5"></i> Waze
                    </a>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($direccionActiva) ?>" target="_blank"
                       class="bg-white dark:bg-stone-800 text-emerald-600 dark:text-emerald-400 font-bold rounded-2xl py-3.5 flex items-center justify-center gap-2 shadow-lg border border-gray-100 dark:border-stone-700 text-sm">
                        <i data-lucide="navigation" class="w-5 h-5"></i> Maps
                    </a>
                </div>
                <button @click="pinOpen = true; pedidoId = '<?= e($pedidoIdActivo) ?>'; pin = ''"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xl rounded-2xl py-5 shadow-xl shadow-emerald-500/30 flex items-center justify-center gap-2 transition-all duration-300 active:scale-[0.98]">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i> Marcar Entregado
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- MIS ENTREGAS -->
    <?php foreach ($mios as $p): ?>
        <div class="bg-white dark:bg-stone-800 rounded-[32px] border-2 border-brand-500 shadow-sm overflow-hidden">
            <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                <div class="bg-brand-500 text-white text-center text-sm font-black py-2.5 tracking-wide uppercase">
                    Cobrar en efectivo: <?= money($p['total']) ?>
                </div>
            <?php endif; ?>
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-black text-lg text-gray-900 dark:text-white"><?= e($p['codigo']) ?></span>
                    <div class="text-right">
                        <span class="font-black text-lg text-gray-900 dark:text-white block leading-none"><?= money($p['total']) ?></span>
                        <span class="text-[10px] font-black text-gray-400 uppercase"><?= e($p['pago_metodo']) ?></span>
                    </div>
                </div>

                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-black uppercase
                             <?= $p['estado'] === 'en_camino' ? 'bg-brand-500/15 text-brand-600 dark:text-brand-500' : 'bg-gray-100 dark:bg-stone-700 text-gray-600 dark:text-gray-300' ?>">
                    <span class="w-1.5 h-1.5 rounded-full bg-current <?= $p['estado'] === 'en_camino' ? 'animate-ping' : '' ?>"></span>
                    <?= $p['estado'] === 'en_camino' ? 'En Camino' : 'Listo para recoger' ?>
                </span>

                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 flex items-start gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-brand-500 shrink-0 mt-0.5"></i>
                    <?= e($p['direccion_entrega']) ?>
                </p>

                <div class="bg-gray-50 dark:bg-stone-900/50 rounded-2xl p-4 space-y-1.5 text-sm border border-gray-100 dark:border-stone-700">
                    <?php foreach ($p['lineas'] as $l): ?>
                        <div class="flex justify-between font-medium text-gray-700 dark:text-gray-300">
                            <span><span class="text-brand-600 dark:text-brand-500 font-black"><?= (int) $l['cantidad'] ?>x</span> <?= e($l['nombre']) ?></span>
                            <span class="text-gray-400"><?= money($l['precio_unitario'] * $l['cantidad']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($p['estado'] !== 'en_camino'): ?>
                    <form method="post" action="<?= url('/delivery/pedido/' . $p['id_pedido'] . '/iniciar') ?>">
                        <?= csrf_field() ?>
                        <button type="submit"
                                class="w-full bg-brand-500 hover:bg-brand-600 text-white font-black text-xl rounded-2xl py-5 flex items-center justify-center gap-2 shadow-lg shadow-brand-500/25 transition-all duration-300 active:scale-[0.98]">
                            <i data-lucide="navigation" class="w-6 h-6"></i> Iniciar Ruta
                        </button>
                    </form>
                <?php else: ?>
                    <!-- Contactar Cliente: muy visible, solo mientras el pedido esta en transito -->
                    <a href="https://wa.me/57<?= e($p['cliente_telefono']) ?>" target="_blank"
                       class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-lg rounded-2xl py-4 flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-300 active:scale-[0.98]">
                        <i data-lucide="phone" class="w-5 h-5"></i> Contactar Cliente
                    </a>
                    <div class="grid grid-cols-2 gap-2.5">
                        <a href="https://waze.com/ul?q=<?= urlencode($p['direccion_entrega']) ?>" target="_blank"
                           class="bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold rounded-2xl py-3 text-center text-sm border border-blue-100 dark:border-blue-900/40">Waze</a>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($p['direccion_entrega']) ?>" target="_blank"
                           class="bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold rounded-2xl py-3 text-center text-sm border border-emerald-100 dark:border-emerald-900/40">Maps</a>
                    </div>
                    <button @click="pinOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; pin = ''"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xl rounded-2xl py-5 flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 transition-all duration-300 active:scale-[0.98]">
                        <i data-lucide="check-circle-2" class="w-6 h-6"></i> Marcar Entregado
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- DISPONIBLES PARA TOMAR -->
    <?php if ($disponibles): ?>
        <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white pt-2">Pedidos Disponibles</h2>
    <?php endif; ?>
    <?php foreach ($disponibles as $p): ?>
        <div class="bg-white dark:bg-stone-800 rounded-[32px] border border-gray-100 dark:border-stone-700 shadow-sm overflow-hidden">
            <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                <div class="bg-brand-500 text-white text-center text-sm font-black py-2.5 tracking-wide uppercase">
                    Cobrar en efectivo: <?= money($p['total']) ?>
                </div>
            <?php endif; ?>
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="font-mono font-black text-lg text-gray-900 dark:text-white"><?= e($p['codigo']) ?></span>
                    <span class="font-black text-lg text-gray-900 dark:text-white"><?= money($p['total']) ?></span>
                </div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-300 flex items-start gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-brand-500 shrink-0 mt-0.5"></i>
                    <?= e($p['direccion_entrega']) ?>
                </p>
                <form method="post" action="<?= url('/delivery/pedido/' . $p['id_pedido'] . '/tomar') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white font-black text-lg rounded-2xl py-4 shadow-lg shadow-brand-500/25 transition-all duration-300 active:scale-[0.98]">
                        Tomar Pedido
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (!$disponible): ?>
        <p class="text-sm text-gray-400 py-10 text-center font-medium">Estas desconectado. Activa tu disponibilidad arriba para ver pedidos.</p>
    <?php elseif (!$mios && !$disponibles): ?>
        <div class="bg-white dark:bg-stone-800 border border-gray-100 dark:border-stone-700 rounded-[32px] p-12 text-center">
            <i data-lucide="package-search" class="w-10 h-10 mx-auto text-gray-300 dark:text-stone-600 mb-3"></i>
            <p class="text-sm text-gray-400 font-medium">No hay entregas disponibles en este momento.</p>
        </div>
    <?php endif; ?>

</div>

<!-- MODAL DE PIN DE ENTREGA -->
<div x-show="pinOpen" x-cloak class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div @click.outside="pinOpen = false" class="bg-white dark:bg-stone-800 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-700 w-full max-w-sm p-6 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-black tracking-tight text-gray-900 dark:text-white">Validar Entrega</h3>
            <button @click="pinOpen = false" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-700 text-gray-400 flex items-center justify-center"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <form :action="'<?= url('/delivery/pedido') ?>/' + pedidoId + '/entregar'" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <p class="text-xs text-gray-500 text-center font-medium">Solicita al cliente el PIN de 4 digitos de su pedido.</p>
            <input name="pin" x-model="pin" inputmode="numeric" maxlength="4" required autofocus
                   class="w-full rounded-2xl border-2 border-gray-200 dark:border-stone-600 bg-gray-50 dark:bg-stone-900 px-4 py-4 text-3xl text-center tracking-[0.5em] font-mono font-black text-gray-900 dark:text-white focus:outline-none focus:border-brand-500">
            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-black text-lg rounded-2xl py-4 shadow-lg shadow-emerald-500/25 transition-all duration-300 active:scale-[0.98]">
                Confirmar Entrega
            </button>
        </form>
    </div>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.Copiway && window.Copiway.map) {
        window.Copiway.map('mapa-delivery-full', {
            address: <?= json_encode($direccionActiva) ?>,
            markers: [
                { lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Sede Copiway', color: '#ff6600' },
                { address: <?= json_encode($direccionActiva) ?>, label: <?= json_encode($direccionActiva) ?>, color: '#10b981' }
            ]
        });
    }
});
</script>

<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>
