<?php
/** @var array $insumos @var array $kpis @var array $movimientos @var array $categorias */
$fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');
$esEmpaque = fn($cat) => stripos((string) $cat, 'empaque') !== false || stripos((string) $cat, 'desechable') !== false;
$grupoConsumibles = array_filter($insumos, fn($i) => !$esEmpaque($i['categoria']));
$grupoEmpaques    = array_filter($insumos, fn($i) => $esEmpaque($i['categoria']));
?>
<div x-data="{ openInsumo: false }">

<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Abastecimiento Express</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Actualizacion rapida de existencias.</p>
    </div>
    <button @click="openInsumo = true"
            class="inline-flex items-center gap-2 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium
                   shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
        <i data-lucide="plus" class="w-4 h-4"></i> Registrar Insumo
    </button>
</div>

<!-- KPIs -->
<div class="grid gap-4 sm:grid-cols-3 mb-8">
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm flex items-center gap-4">
        <span class="w-12 h-12 rounded-2xl bg-red-100 text-red-500 dark:bg-red-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Criticos</p>
            <p class="text-xl font-black tracking-tight <?= $kpis['criticos'] > 0 ? 'text-red-500' : 'text-gray-900 dark:text-white' ?>"><?= (int) $kpis['criticos'] ?> Items</p>
        </div>
    </div>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm flex items-center gap-4">
        <span class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-500 dark:bg-blue-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="package" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Total en existencia</p>
            <p class="text-xl font-black tracking-tight text-gray-900 dark:text-white"><?= $fmt($kpis['unidades']) ?> Uni. <span class="text-xs font-bold text-gray-400">(<?= (int) $kpis['tipos'] ?> tipos)</span></p>
        </div>
    </div>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm flex items-center gap-4">
        <span class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-500 dark:bg-brand-500/15 flex items-center justify-center shrink-0">
            <i data-lucide="wallet" class="w-5 h-5"></i>
        </span>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wide">Valorizacion total</p>
            <p class="text-xl font-black tracking-tight text-emerald-600 dark:text-emerald-500"><?= money($kpis['valorizacion']) ?></p>
        </div>
    </div>
</div>

<!-- Tarjetas de insumo, separadas por tipo -->
<?php if ($insumos): ?>
    <?php foreach ([
        ['Insumos de Cocina', 'utensils-crossed', $grupoConsumibles],
        ['Desechables y Empaques', 'box', $grupoEmpaques],
    ] as [$secTitulo, $secIcon, $grupo]): ?>
        <?php if ($grupo): ?>
            <div class="flex items-center gap-2.5 mb-4 mt-10 first:mt-0">
                <span class="w-9 h-9 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 flex items-center justify-center">
                    <i data-lucide="<?= $secIcon ?>" class="w-4 h-4"></i>
                </span>
                <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white"><?= $secTitulo ?></h2>
                <span class="text-xs font-bold text-gray-400"><?= count($grupo) ?> item(s)</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-4">
                <?php foreach ($grupo as $i): ?>
                    <div class="bg-white dark:bg-stone-900 rounded-[32px] p-6 shadow-sm border-2 transition-all duration-300 hover:shadow-md
                                <?= $i['critico'] ? 'border-red-500 dark:border-red-500' : 'border-gray-100 dark:border-stone-800' ?>">
                        <div class="flex items-start justify-between gap-2 mb-4">
                            <div class="min-w-0">
                                <h3 class="font-black tracking-tight text-gray-900 dark:text-white truncate"><?= e($i['nombre']) ?></h3>
                                <p class="text-xs text-gray-400 font-medium mt-0.5"><?= e($i['categoria']) ?> · <?= e($i['unidad_medida']) ?></p>
                            </div>
                            <?php if ($i['critico']): ?>
                                <span class="shrink-0 inline-flex items-center gap-1 text-[10px] font-black uppercase bg-red-500 text-white rounded-full px-2.5 py-1">
                                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Critico
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mb-5 text-center">
                            <div class="rounded-2xl bg-gray-50 dark:bg-stone-950/40 py-3">
                                <p class="text-lg font-black tracking-tight text-gray-900 dark:text-white"><?= $fmt($i['cantidad_stock']) ?></p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Stock</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 dark:bg-stone-950/40 py-3">
                                <p class="text-lg font-black tracking-tight text-gray-900 dark:text-white"><?= money($i['costo_unitario']) ?></p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Costo/u</p>
                            </div>
                            <div class="rounded-2xl bg-gray-50 dark:bg-stone-950/40 py-3">
                                <p class="text-lg font-black tracking-tight text-brand-600 dark:text-brand-500"><?= money($i['valorizacion']) ?></p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide">Valor</p>
                            </div>
                        </div>

                        <!-- Abastecimiento Express: boton grande + -->
                        <div class="flex items-stretch gap-2">
                            <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>" class="shrink-0">
                                <?= csrf_field() ?><input type="hidden" name="accion" value="menos"><input type="hidden" name="valor" value="1">
                                <button class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 dark:hover:bg-stone-700 text-gray-600 dark:text-gray-300 flex items-center justify-center transition-colors" title="Restar 1">
                                    <i data-lucide="minus" class="w-5 h-5"></i>
                                </button>
                            </form>
                            <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>" class="flex-1">
                                <?= csrf_field() ?><input type="hidden" name="accion" value="mas"><input type="hidden" name="valor" value="1">
                                <button class="w-full h-12 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white font-medium flex items-center justify-center gap-2
                                               shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105" title="Sumar stock rapido">
                                    <i data-lucide="plus" class="w-5 h-5"></i> Sumar
                                </button>
                            </form>
                        </div>
                        <form method="post" action="<?= url('/admin/inventario/insumo/' . $i['id_ingrediente'] . '/ajuste') ?>"
                              class="flex items-center gap-2 mt-2" onsubmit="return this.valor.value !== ''">
                            <?= csrf_field() ?><input type="hidden" name="accion" value="set">
                            <input name="valor" type="number" step="0.01" placeholder="Fijar stock exacto"
                                   class="flex-1 rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-xs font-medium focus:outline-none focus:border-brand-500 transition-colors">
                            <button class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 dark:hover:bg-stone-700 flex items-center justify-center transition-colors" title="Fijar">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </form>

                        <?php if ($i['proveedor']): ?>
                            <p class="text-[11px] text-gray-400 font-medium mt-3 flex items-center gap-1.5">
                                <i data-lucide="truck" class="w-3 h-3"></i> <?= e($i['proveedor']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-12 text-center mb-8 shadow-sm">
        <i data-lucide="package-open" class="w-10 h-10 mx-auto text-gray-300 dark:text-stone-700 mb-3"></i>
        <p class="font-black tracking-tight text-gray-900 dark:text-white">El inventario esta vacio.</p>
        <p class="text-sm text-gray-400 font-medium mb-4">Registra los insumos comprados.</p>
        <button @click="openInsumo = true"
                class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium transition-all duration-300 hover:scale-105">Registrar Primer Insumo</button>
    </div>
<?php endif; ?>

<!-- Historial de movimientos -->
<div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] shadow-sm overflow-hidden mt-8">
    <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white p-6 sm:p-8 pb-4">Historial de Movimientos</h2>
    <?php if ($movimientos): ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[560px]">
            <thead>
                <tr class="bg-gray-50/70 dark:bg-stone-950/40 text-left text-[11px] font-black uppercase tracking-wider text-gray-400">
                    <th class="px-6 sm:px-8 py-3.5">Fecha</th>
                    <th class="px-4 py-3.5">Insumo</th>
                    <th class="px-4 py-3.5">Tipo</th>
                    <th class="px-4 py-3.5">Cantidad</th>
                    <th class="px-6 sm:px-8 py-3.5">Motivo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                <?php foreach ($movimientos as $m): ?>
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-stone-950/40 transition-colors">
                        <td class="px-6 sm:px-8 py-3.5 text-gray-400 font-medium"><?= date('d/m H:i', strtotime($m['fecha_hora'])) ?></td>
                        <td class="px-4 py-3.5 font-bold text-gray-900 dark:text-white"><?= e($m['ingrediente']) ?></td>
                        <td class="px-4 py-3.5">
                            <span class="text-[10px] font-black uppercase rounded-full px-2.5 py-1
                                <?= $m['tipo_movimiento'] === 'entrada' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400' : ($m['tipo_movimiento'] === 'salida' ? 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400') ?>">
                                <?= e($m['tipo_movimiento']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3.5 font-black tracking-tight text-gray-900 dark:text-white"><?= $fmt($m['cantidad']) ?></td>
                        <td class="px-6 sm:px-8 py-3.5 text-gray-500 font-medium"><?= e($m['motivo']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <p class="text-sm text-gray-400 font-medium py-10 text-center">No hay registros de movimientos.</p>
    <?php endif; ?>
</div>

<!-- Modal registrar insumo -->
<div x-show="openInsumo" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openInsumo = false" class="bg-white dark:bg-stone-900 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-800 w-full max-w-lg max-h-[92vh] overflow-y-auto"
         x-data="{ cantidad: '', costo: '' }">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-stone-800 sticky top-0 bg-white dark:bg-stone-900 z-10">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 flex items-center justify-center">
                    <i data-lucide="plus-circle" class="w-5 h-5"></i>
                </span>
                <h2 class="text-xl font-black tracking-tight text-gray-900 dark:text-white">Registrar Nuevo Insumo</h2>
            </div>
            <button @click="openInsumo = false" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <form method="post" action="<?= url('/admin/inventario/insumo') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <p class="text-xs text-gray-400 font-medium">Ingresa la cantidad adquirida y el costo total de compra.</p>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre del insumo</label>
                <input name="nombre" required class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Categoria</label>
                    <select name="id_categoria" required class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= e($c['id_categoria']) ?>"><?= e($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Unidad de medida</label>
                    <select name="unidad_medida" required class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                        <?php foreach (['Unidades', 'Kilogramos', 'Gramos', 'Litros', 'Mililitros', 'Paquetes'] as $u): ?>
                            <option><?= $u ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Cantidad comprada</label>
                    <input name="cantidad" x-model="cantidad" type="number" min="0" step="0.01" required
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Costo total de compra</label>
                    <input name="costo_total" x-model="costo" type="number" min="0" step="1" required
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Umbral minimo (alerta)</label>
                    <input name="umbral_minimo" type="number" min="0" value="10"
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Precio extra (si es adicional)</label>
                    <input name="precio_extra" type="number" min="0" value="0"
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Proveedor u origen (opcional)</label>
                <input name="proveedor" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div class="rounded-2xl bg-brand-500/5 dark:bg-brand-500/10 border border-brand-500/20 p-4 text-sm flex justify-between items-center">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Costo unitario calculado</span>
                <span class="font-black tracking-tight text-base text-brand-600 dark:text-brand-500"
                      x-text="(parseFloat(cantidad) > 0 ? '$ ' + Math.round(costo / cantidad).toLocaleString('es-CO') : '$ 0')"></span>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="openInsumo = false" class="rounded-2xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold hover:bg-gray-50 dark:hover:bg-stone-800 transition-colors">Cancelar</button>
                <button class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-medium transition-all duration-300 hover:scale-105">Guardar Insumo</button>
            </div>
        </form>
    </div>
</div>

</div>
