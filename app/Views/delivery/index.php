<?php
/** @var array $yo @var array $disponibles @var array $mios */
$disponible = ($yo['estado_disponibilidad'] ?? '') !== 'desconectado';
$primerPedido = $mios[0] ?? $disponibles[0] ?? null;
$direccionActiva = $primerPedido['direccion_entrega'] ?? 'Calle 10 # 5-20, Centro';
$codigoActivo = $primerPedido['codigo'] ?? '#ORD-4931';
$pedidoIdActivo = $primerPedido['id_pedido'] ?? '';
?>
<div class="w-full h-full flex flex-col md:flex-row overflow-hidden relative font-sans text-slate-800" x-data="{ pinOpen: false, pedidoId: '<?= e($pedidoIdActivo) ?>', pin: '' }">

    <!-- SIDEBAR IZQUIERDO: LISTA DE PEDIDOS -->
    <aside class="w-full md:w-[380px] shrink-0 bg-white border-r border-slate-200/80 flex flex-col h-full overflow-y-auto p-5 z-10">
        
        <!-- Header superior Sidebar -->
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <span class="w-9 h-9 rounded-xl bg-[#ff6600] text-white flex items-center justify-center shadow-md shadow-orange-500/20">
                    <i data-lucide="navigation" class="w-5 h-5"></i>
                </span>
                <span class="font-black text-xl text-slate-900 tracking-tight">Copiway<span class="text-[#ff6600]">PRO</span></span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="document.documentElement.classList.toggle('dark')" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 transition">
                    <i data-lucide="moon" class="w-5 h-5"></i>
                </button>
                <form method="post" action="<?= url('/logout') ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="p-2 rounded-xl text-red-500 hover:text-red-700 transition">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Interruptor de Estado / Disponibilidad -->
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3.5 mb-5 flex items-center justify-between">
            <span class="font-bold text-sm text-slate-700">Estado</span>
            <form method="post" action="<?= url('/delivery/disponibilidad') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="estado" value="<?= $disponible ? 'desconectado' : 'disponible' ?>">
                <button type="submit" class="flex items-center gap-2.5 font-bold text-sm <?= $disponible ? 'text-[#ff6600]' : 'text-slate-400' ?>">
                    <span class="w-12 h-6 rounded-full relative transition duration-200 p-0.5 <?= $disponible ? 'bg-[#ff6600]' : 'bg-slate-300' ?>">
                        <span class="block w-5 h-5 rounded-full bg-white transition-all duration-200 shadow-sm <?= $disponible ? 'translate-x-6' : 'translate-x-0' ?>"></span>
                    </span>
                    <?= $disponible ? 'Disponible' : 'Desconectado' ?>
                </button>
            </form>
        </div>

        <!-- Título Sección -->
        <h2 class="font-extrabold text-slate-900 text-lg mb-4">Pedidos Disponibles</h2>

        <!-- Lista de Entregas del Domiciliario -->
        <div class="space-y-4 flex-1">
            
            <!-- Pedidos Activos (Mis Entregas) -->
            <?php foreach ($mios as $p): ?>
                <div class="bg-white rounded-3xl border-2 border-[#ff6600] shadow-md overflow-hidden space-y-3">
                    <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                        <div class="bg-[#ff6600] text-white text-center text-xs font-black py-2 tracking-wide uppercase">
                            ¡COBRAR EN EFECTIVO: <?= money($p['total']) ?>!
                        </div>
                    <?php endif; ?>

                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-lg text-slate-900"><?= e($p['codigo']) ?></span>
                            <div class="text-right">
                                <span class="font-black text-sm text-slate-900 block"><?= money($p['total']) ?></span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase"><?= e($p['pago_metodo']) ?></span>
                            </div>
                        </div>

                        <div class="inline-block bg-slate-100 text-slate-700 text-xs font-bold rounded-full px-3 py-1">
                            <?= $p['estado'] === 'en_camino' ? 'En Camino' : 'Listo para recoger' ?>
                        </div>

                        <p class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6600] shrink-0"></i>
                            <?= e($p['direccion_entrega']) ?>
                        </p>

                        <!-- Items del pedido -->
                        <div class="bg-slate-50/80 rounded-2xl p-3 space-y-1 text-xs font-semibold text-slate-700 border border-slate-100">
                            <?php foreach ($p['lineas'] as $l): ?>
                                <div class="flex justify-between">
                                    <span><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></span>
                                    <span class="text-slate-400"><?= money($l['precio_unitario'] * $l['cantidad']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Botón Contactar Cliente -->
                        <a href="https://wa.me/57<?= e($p['cliente_telefono']) ?>" target="_blank"
                           class="w-full bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-2xl py-3 flex items-center justify-center gap-2 text-xs transition border border-emerald-200/50">
                            <i data-lucide="phone" class="w-4 h-4 text-emerald-500"></i> Contactar Cliente
                        </a>

                        <!-- Acciones Rápidas -->
                        <div class="grid grid-cols-4 gap-2 pt-1">
                            <a href="https://waze.com/ul?q=<?= urlencode($p['direccion_entrega']) ?>" target="_blank"
                               class="bg-blue-50 text-blue-600 font-bold rounded-xl py-2.5 text-center text-xs hover:bg-blue-100 transition">
                                Waze
                            </a>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($p['direccion_entrega']) ?>" target="_blank"
                               class="bg-emerald-50 text-emerald-600 font-bold rounded-xl py-2.5 text-center text-xs hover:bg-emerald-100 transition">
                                Maps
                            </a>
                            <button class="bg-slate-100 text-slate-600 font-bold rounded-xl py-2.5 text-center text-xs hover:bg-slate-200 transition">
                                Mapa
                            </button>
                            <button @click="pinOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; pin = ''"
                                    class="bg-[#10b981] hover:bg-[#059669] text-white font-bold rounded-xl py-2.5 text-center text-xs transition flex items-center justify-center gap-1">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Entregado
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pedidos Disponibles para tomar -->
            <?php foreach ($disponibles as $p): ?>
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden space-y-3">
                    <?php if ($p['pago_metodo'] === 'efectivo'): ?>
                        <div class="bg-[#ff6600] text-white text-center text-xs font-black py-2 tracking-wide uppercase">
                            ¡COBRAR EN EFECTIVO: <?= money($p['total']) ?>!
                        </div>
                    <?php endif; ?>

                    <div class="p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono font-black text-lg text-slate-900"><?= e($p['codigo']) ?></span>
                            <span class="font-black text-sm text-slate-900"><?= money($p['total']) ?></span>
                        </div>

                        <p class="text-xs font-semibold text-slate-600 flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6600] shrink-0"></i>
                            <?= e($p['direccion_entrega']) ?>
                        </p>

                        <form method="post" action="<?= url('/delivery/pedido/' . $p['id_pedido'] . '/tomar') ?>">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full bg-[#ff6600] hover:bg-[#e65c00] text-white font-bold rounded-2xl py-3 shadow-md shadow-orange-500/20 text-xs transition">
                                Tomar Pedido
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (!$disponible): ?>
                <p class="text-xs text-slate-400 py-10 text-center font-medium">Estás desconectado. Activa tu disponibilidad arriba para ver pedidos.</p>
            <?php elseif (!$mios && !$disponibles): ?>
                <p class="text-xs text-slate-400 py-10 text-center font-medium">No hay entregas disponibles en este momento.</p>
            <?php endif; ?>

        </div>
    </aside>

    <!-- PANEL DERECHO: MAPA INTERACTIVO PANTALLA COMPLETA -->
    <main class="flex-1 h-full relative bg-slate-100">

        <!-- Contenedor del Mapa Leaflet -->
        <div id="mapa-delivery-full" class="w-full h-full z-0"></div>

        <!-- Card Flotante Superior: Info de Destino -->
        <div class="absolute top-6 left-6 right-6 z-[500] max-w-xl mx-auto bg-white rounded-3xl p-4 shadow-xl border border-slate-100/90 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-orange-100/80 text-[#ff6600] flex items-center justify-center shrink-0">
                <i data-lucide="map-pin" class="w-6 h-6"></i>
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-slate-400 tracking-wider uppercase block">DESTINO</span>
                <h4 class="font-extrabold text-slate-900 text-base leading-tight"><?= e($direccionActiva) ?></h4>
                <p class="text-xs font-semibold text-slate-500 mt-0.5">Llegada est: 12 mins</p>
            </div>
        </div>

        <!-- Barra Flotante Inferior sobre el mapa: Acciones Rápidas Waze/Maps + Marcar Entregado -->
        <div class="absolute bottom-6 left-6 right-6 z-[500] max-w-2xl mx-auto space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <a href="https://waze.com/ul?q=<?= urlencode($direccionActiva) ?>" target="_blank"
                   class="bg-[#dbeafe] text-[#1d4ed8] font-bold rounded-2xl py-3.5 flex items-center justify-center gap-2 shadow-lg shadow-blue-500/10 hover:bg-blue-200 transition text-sm">
                    <i data-lucide="compass" class="w-5 h-5"></i> Waze
                </a>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($direccionActiva) ?>" target="_blank"
                   class="bg-[#dcfce7] text-[#15803d] font-bold rounded-2xl py-3.5 flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/10 hover:bg-emerald-200 transition text-sm">
                    <i data-lucide="navigation" class="w-5 h-5"></i> Maps
                </a>
            </div>

            <button @click="pinOpen = true; pedidoId = '<?= e($pedidoIdActivo) ?>'; pin = ''"
                    class="w-full bg-[#1e293b] hover:bg-slate-900 text-white font-bold rounded-2xl py-4 shadow-xl flex items-center justify-center gap-2 text-base transition">
                <i data-lucide="check-circle-2" class="w-5 h-5"></i> Marcar Entregado
            </button>
        </div>

    </main>

    <!-- MODAL DE PIN DE ENTREGA -->
    <div x-show="pinOpen" x-cloak class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div @click.outside="pinOpen = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-black text-slate-900">Validar Entrega</h3>
                <button @click="pinOpen = false" class="text-slate-400 hover:text-slate-600"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <form :action="'<?= url('/delivery/pedido') ?>/' + pedidoId + '/entregar'" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <p class="text-xs text-slate-500 text-center font-medium">Solicita al cliente el PIN de 4 dígitos de su pedido.</p>
                <input name="pin" x-model="pin" inputmode="numeric" maxlength="4" required autofocus
                       class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-3xl text-center tracking-[0.5em] font-mono font-black text-slate-900 focus:outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600]">
                <button type="submit" class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-bold rounded-2xl py-4 shadow-lg shadow-emerald-500/25 text-sm transition">
                    Confirmar Entrega
                </button>
            </form>
        </div>
    </div>

</div>

<!-- Script de renderizado de Mapa Fullscreen -->
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

<?php $segundos = 15; require \App\Core\App::config("paths")["views"] . "/partials/autorefresh.php"; ?>

