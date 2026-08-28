<?php
/** @var array $config @var array $cierre */
$t = $cierre['totales'];
?>
<div x-data="{ openCierre: false }">
<div class="mb-6">
    <h1 class="text-3xl font-black">Ajustes y Caja</h1>
    <p class="text-gray-500 dark:text-gray-400">Reglas comerciales y cierre de turno.</p>
</div>

<div class="grid gap-6 lg:grid-cols-2">

    <!-- Tarifa plana -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-1">Tarifa Plana de Domicilio</h2>
        <p class="text-sm text-gray-400 mb-4">Costo de envio unico y fijo para toda la ciudad.</p>
        <form method="post" action="<?= url('/admin/ajustes/tarifa') ?>" class="flex gap-3">
            <?= csrf_field() ?>
            <input type="number" name="tarifa_plana_domicilio" min="0" step="100" value="<?= (int) $config['tarifa_plana_domicilio'] ?>"
                   class="flex-1 rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar</button>
        </form>
    </div>

    <!-- Margen -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-1">Margen de Ganancia (Arma tu Burger)</h2>
        <p class="text-sm text-gray-400 mb-4">Margen por defecto aplicado a los insumos del creador interactivo.</p>
        <form method="post" action="<?= url('/admin/ajustes/margen') ?>" class="flex gap-3">
            <?= csrf_field() ?>
            <div class="relative flex-1">
                <input type="number" name="margen_ganancia_defecto" min="0" max="100" value="<?= (int) $config['margen_ganancia_defecto'] ?>"
                       class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">%</span>
            </div>
            <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar</button>
        </form>
    </div>

    <!-- Horario -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-1">Horario de Atencion</h2>
        <p class="text-sm text-gray-400 mb-4">Fuera de este horario el boton "Pagar" se bloquea automaticamente.</p>
        <form method="post" action="<?= url('/admin/ajustes/horario') ?>" class="flex flex-wrap gap-3 items-end">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold mb-1">Apertura</label>
                <input type="time" name="horario_apertura" value="<?= substr($config['horario_apertura'], 0, 5) ?>"
                       class="rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Cierre</label>
                <input type="time" name="horario_cierre" value="<?= substr($config['horario_cierre'], 0, 5) ?>"
                       class="rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
            <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar</button>
        </form>
    </div>

    <!-- Pausa de emergencia -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black text-lg mb-1">Pausa de Emergencia (Boton de Panico)</h2>
        <p class="text-sm text-gray-400 mb-4">Bloquea temporalmente la recepcion de pedidos sin cambiar el horario oficial.</p>
        <form method="post" action="<?= url('/admin/ajustes/pausa') ?>">
            <?= csrf_field() ?>
            <?php $pausa = (bool) $config['pausa_emergencia_activa']; ?>
            <button class="w-full rounded-xl py-3 text-sm font-black <?= $pausa ? 'bg-red-500 text-white' : 'bg-gray-100 dark:bg-stone-800' ?>">
                <?= $pausa ? 'COCINA PAUSADA (Clic para Reanudar)' : 'Pausar Recepcion de Pedidos' ?>
            </button>
        </form>
    </div>
</div>

<!-- Cierre de caja -->
<div class="mt-6 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h2 class="font-black text-lg">Reporte de Cierre de Caja</h2>
            <p class="text-sm text-gray-400">Consolida ingresos, cruza insumos y liquida a los domiciliarios.</p>
        </div>
        <button @click="openCierre = true" class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">
            Generar Cierre del Dia
        </button>
    </div>
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-gray-50 dark:bg-stone-900 p-4"><p class="text-xs text-gray-400">Total Ventas</p><p class="text-xl font-black"><?= money($t['total_ventas']) ?></p></div>
        <div class="rounded-2xl bg-gray-50 dark:bg-stone-900 p-4"><p class="text-xs text-gray-400">Efectivo</p><p class="text-xl font-black"><?= money($t['total_efectivo']) ?></p></div>
        <div class="rounded-2xl bg-gray-50 dark:bg-stone-900 p-4"><p class="text-xs text-gray-400">Digital</p><p class="text-xl font-black"><?= money($t['total_digital']) ?></p></div>
    </div>
</div>

<!-- Modal cierre -->
<div x-show="openCierre" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openCierre = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-lg max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Cierre de Caja</h2>
            <button @click="openCierre = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <?php if ($cierre['activasSinEntregar'] > 0): ?>
                <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 text-amber-700 p-3 text-xs font-bold">
                    Tienes <?= $cierre['activasSinEntregar'] ?> orden(es) activas aun sin entregar. Al confirmar, la caja se consolidara.
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl bg-gray-50 dark:bg-stone-900 p-3"><p class="text-xs text-gray-400">Total Ventas</p><p class="font-black"><?= money($t['total_ventas']) ?></p></div>
                <div class="rounded-xl bg-gray-50 dark:bg-stone-900 p-3"><p class="text-xs text-gray-400">Total Ordenes</p><p class="font-black"><?= (int) $t['total_ordenes'] ?></p></div>
            </div>

            <div>
                <p class="font-bold mb-1">Desglose por metodo de pago</p>
                <div class="flex justify-between py-1 border-b border-gray-100 dark:border-stone-800"><span class="text-gray-500">Efectivo</span><span><?= money($t['total_efectivo']) ?></span></div>
                <div class="flex justify-between py-1"><span class="text-gray-500">Tarjetas / Transferencias</span><span><?= money($t['total_digital']) ?></span></div>
            </div>

            <div>
                <p class="font-bold mb-1">Consumo de Insumos (Escandallo)</p>
                <?php foreach ($cierre['escandallo'] as $e): ?>
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-stone-800">
                        <span class="text-gray-500"><?= e($e['nombre']) ?></span>
                        <span><?= rtrim(rtrim(number_format($e['consumido'], 2, '.', ''), '0'), '.') ?> <?= e($e['unidad_medida']) ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (!$cierre['escandallo']): ?><p class="text-gray-400 text-xs py-2">Sin ventas registradas hoy.</p><?php endif; ?>
            </div>

            <div>
                <p class="font-bold mb-1">Liquidacion de Domiciliarios</p>
                <?php foreach ($cierre['liquidaciones'] as $l): ?>
                    <div class="rounded-xl border border-gray-100 dark:border-stone-800 p-3 mb-2">
                        <p class="font-bold"><?= e($l['nombre']) ?> <span class="text-xs text-gray-400 font-normal">· <?= (int) $l['entregas'] ?> entregas efectivo</span></p>
                        <div class="flex justify-between text-xs mt-1"><span class="text-gray-500">Base asignada</span><span><?= money($l['base_efectivo_asignada']) ?></span></div>
                        <div class="flex justify-between text-xs"><span class="text-gray-500">Recaudo efectivo</span><span><?= money($l['recaudo']) ?></span></div>
                        <div class="flex justify-between text-xs font-bold"><span>Total a entregar</span><span><?= money($l['total_entregar']) ?></span></div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$cierre['liquidaciones']): ?><p class="text-gray-400 text-xs">Sin entregas en efectivo hoy.</p><?php endif; ?>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <form method="post" action="<?= url('/admin/ajustes/cierre') ?>" class="flex-1"
                      onsubmit="return confirm('Confirmar cierre de caja del dia? La caja se consolidara.')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">
                    <button class="w-full rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Confirmar y Cerrar Caja</button>
                </form>
                <a href="<?= url('/admin/ajustes/cierre?fecha=' . date('Y-m-d')) ?>" target="_blank"
                   class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold flex items-center gap-2">
                    <i data-lucide="download" class="w-4 h-4"></i> Ver / PDF
                </a>
            </div>
        </div>
    </div>
</div>
</div>
