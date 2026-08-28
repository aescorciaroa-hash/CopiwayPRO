<?php
/** @var array $insumos @var array $kpis @var array $movimientos @var array $categorias */
$fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');
?>
<div x-data="{ openInsumo: false }">

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Abastecimiento Express</h1>
        <p class="text-gray-500 dark:text-gray-400">Actualizacion rapida de existencias.</p>
    </div>
    <button @click="openInsumo = true"
            class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-bold">
        <i data-lucide="plus" class="w-4 h-4"></i> Registrar Insumo
    </button>
</div>

<!-- KPIs -->
<div class="grid gap-4 sm:grid-cols-3 mb-6">
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm flex items-center gap-4">
        <span class="w-11 h-11 rounded-2xl bg-red-100 text-red-500 dark:bg-red-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Criticos</p>
            <p class="text-xl font-black <?= $kpis['criticos'] > 0 ? 'text-red-500' : '' ?>"><?= (int) $kpis['criticos'] ?> Items</p>
        </div>
    </div>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm flex items-center gap-4">
        <span class="w-11 h-11 rounded-2xl bg-blue-100 text-blue-500 dark:bg-blue-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="package" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total en existencia</p>
            <p class="text-xl font-black"><?= $fmt($kpis['unidades']) ?> Uni. <span class="text-xs font-bold text-gray-400">(<?= (int) $kpis['tipos'] ?> tipos)</span></p>
        </div>
    </div>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm flex items-center gap-4">
        <span class="w-11 h-11 rounded-2xl bg-brand-100 text-brand-500 dark:bg-brand-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="wallet" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Valorizacion total</p>
            <p class="text-xl font-black text-emerald-600 dark:text-emerald-500"><?= money($kpis['valorizacion']) ?></p>
        </div>
    </div>
</div>

<!-- Tarjetas de insumo -->
<?php if ($insumos): ?>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">
    <?php foreach ($insumos as $i): ?>
        <div class="bg-white dark:bg-card border rounded-3xl p-5 shadow-sm <?= $i['critico'] ? 'border-red-300 dark:border-red-500/40' : 'border-gray-100 dark:border-stone-800' ?>">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-bold"><?= e($i['nombre']) ?></h3>
                    <p class="text-xs text-gray-400"><?= e($i['categoria']) ?> · <?= e($i['unidad_medida']) ?></p>
                </div>
                <?php if ($i['critico']): ?>
                    <span class="text-[10px] font-black uppercase bg-red-500 text-white rounded-full px-2 py-1">Critico</span>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-3 gap-2 my-4 text-center">
                <div><p class="text-lg font-black"><?= $fmt($i['cantidad_stock']) ?></p><p class="text-[10px] text-gray-400">Stock</p></div>
                <div><p class="text-lg font-black"><?= money($i['costo_unitario']) ?></p><p class="text-[10px] text-gray-400">Costo/u</p></div>
                <div><p class="text-lg font-black text-brand-600"><?= money($i['valorizacion']) ?></p><p class="text-[10px] text-gray-400">Valor</p></div>
            </div>
            <div class="flex items-center gap-2">
                <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>" class="flex-1">
                    <?= csrf_field() ?><input type="hidden" name="accion" value="menos"><input type="hidden" name="valor" value="1">
                    <button class="w-full rounded-lg bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 py-2 font-bold">−</button>
                </form>
                <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>" class="flex-1">
                    <?= csrf_field() ?><input type="hidden" name="accion" value="mas"><input type="hidden" name="valor" value="1">
                    <button class="w-full rounded-lg bg-brand-500 hover:bg-brand-600 text-white py-2 font-bold">+</button>
                </form>
                <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>"
                      class="flex items-center gap-1" onsubmit="return this.valor.value !== ''">
                    <?= csrf_field() ?><input type="hidden" name="accion" value="set">
                    <input name="valor" type="number" step="0.01" placeholder="Fijar"
                           class="w-20 rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-2 py-2 text-xs">
                    <button class="rounded-lg bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 p-2"><i data-lucide="check" class="w-4 h-4"></i></button>
                </form>
            </div>
            <?php if ($i['proveedor']): ?><p class="text-[11px] text-gray-400 mt-2">Proveedor: <?= e($i['proveedor']) ?></p><?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-12 text-center mb-8">
        <i data-lucide="package-open" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
        <p class="font-bold">El inventario esta vacio.</p>
        <p class="text-sm text-gray-400 mb-4">Registra los insumos comprados.</p>
        <button @click="openInsumo = true" class="rounded-xl bg-brand-500 text-white px-5 py-2.5 text-sm font-bold">Registrar Primer Insumo</button>
    </div>
<?php endif; ?>

<!-- Historial de movimientos -->
<div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
    <h2 class="font-black text-lg mb-4">Historial de Movimientos</h2>
    <?php if ($movimientos): ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-xs text-gray-400 uppercase border-b border-gray-100 dark:border-stone-800">
                <tr><th class="py-2">Fecha</th><th>Insumo</th><th>Tipo</th><th>Cantidad</th><th>Motivo</th></tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                <?php foreach ($movimientos as $m): ?>
                    <tr>
                        <td class="py-2.5 text-gray-400"><?= date('d/m H:i', strtotime($m['fecha_hora'])) ?></td>
                        <td class="font-bold"><?= e($m['ingrediente']) ?></td>
                        <td>
                            <span class="text-[10px] font-black uppercase rounded-full px-2 py-1
                                <?= $m['tipo_movimiento'] === 'entrada' ? 'bg-emerald-100 text-emerald-700' : ($m['tipo_movimiento'] === 'salida' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') ?>">
                                <?= e($m['tipo_movimiento']) ?>
                            </span>
                        </td>
                        <td><?= $fmt($m['cantidad']) ?></td>
                        <td class="text-gray-500"><?= e($m['motivo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-sm text-gray-400 py-8 text-center">No hay registros de movimientos.</p>
    <?php endif; ?>
</div>

<!-- Modal registrar insumo -->
<div x-show="openInsumo" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openInsumo = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-lg"
         x-data="{ cantidad: '', costo: '' }">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Registrar Nuevo Insumo</h2>
            <button @click="openInsumo = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="post" action="<?= url('/admin/inventario/insumo') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <p class="text-xs text-gray-400">Ingresa la cantidad adquirida y el costo total de compra.</p>
            <div>
                <label class="block text-sm font-bold mb-1.5">Nombre del insumo</label>
                <input name="nombre" required class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1.5">Categoria</label>
                    <select name="id_categoria" required class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= e($c['id_categoria']) ?>"><?= e($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5">Unidad de medida</label>
                    <select name="unidad_medida" required class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                        <?php foreach (['Unidades', 'Kilogramos', 'Gramos', 'Litros', 'Mililitros', 'Paquetes'] as $u): ?>
                            <option><?= $u ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1.5">Cantidad comprada</label>
                    <input name="cantidad" x-model="cantidad" type="number" min="0" step="0.01" required
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5">Costo total de compra</label>
                    <input name="costo_total" x-model="costo" type="number" min="0" step="1" required
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1.5">Umbral minimo (alerta)</label>
                    <input name="umbral_minimo" type="number" min="0" value="10"
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5">Precio extra (si es adicional)</label>
                    <input name="precio_extra" type="number" min="0" value="0"
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1.5">Proveedor u origen (opcional)</label>
                <input name="proveedor" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
            <div class="rounded-xl bg-gray-50 dark:bg-stone-900 border border-gray-100 dark:border-stone-800 p-3 text-sm flex justify-between">
                <span class="text-gray-500">Costo unitario calculado</span>
                <span class="font-black text-brand-600"
                      x-text="(parseFloat(cantidad) > 0 ? '$ ' + Math.round(costo / cantidad).toLocaleString('es-CO') : '$ 0')"></span>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="openInsumo = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar Insumo</button>
            </div>
        </form>
    </div>
</div>

</div>
