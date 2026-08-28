<?php
/** @var array $pedidos @var string $filtro @var array $totales @var array $cliente @var float $invertido */
?>
<div x-data="{ resenaOpen: false, pedidoId: '', puntaje: 5 }">

<h1 class="text-3xl font-black mb-2">Historial de Pedidos</h1>
<div class="grid gap-4 sm:grid-cols-3 mb-6">
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
        <p class="text-xs text-gray-400">Pedidos</p><p class="text-2xl font-black"><?= $totales['todos'] ?></p>
    </div>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
        <p class="text-xs text-gray-400">Puntos Copiway</p><p class="text-2xl font-black text-brand-600"><?= number_format($cliente['puntos_fidelidad'], 0, ',', '.') ?></p>
    </div>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm">
        <p class="text-xs text-gray-400">Invertido</p><p class="text-2xl font-black"><?= money($invertido) ?></p>
    </div>
</div>

<div class="flex gap-2 mb-6">
    <?php foreach (['todos' => 'Todos', 'pendientes' => 'Pendientes', 'calificados' => 'Calificados'] as $k => $lbl): ?>
        <a href="<?= url('/client/historial?filtro=' . $k) ?>"
           class="px-4 py-2 rounded-full border text-sm font-bold <?= $filtro === $k ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 dark:border-stone-700' ?>">
            <?= $lbl ?> (<?= $totales[$k] ?>)
        </a>
    <?php endforeach; ?>
</div>

<div class="space-y-4">
    <?php foreach ($pedidos as $p): ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <span class="font-mono font-black"><?= $p['codigo'] ?></span>
                <span class="text-sm text-gray-400"><?= date('d/m/Y', strtotime($p['fecha_hora'])) ?> · <?= money($p['total']) ?></span>
            </div>
            <div class="text-sm text-gray-500 mb-3">
                <?php foreach ($p['lineas'] as $l): ?>
                    <div><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></div>
                <?php endforeach; ?>
                <?php if ($p['domiciliario_nombre']): ?>
                    <p class="text-xs mt-1">Entregado por <?= e($p['domiciliario_nombre']) ?></p>
                <?php endif; ?>
            </div>

            <?php if ($p['puntaje']): ?>
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex text-brand-500">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 <?= $i < $p['puntaje'] ? 'fill-brand-500' : '' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-xs text-gray-500"><?= e($p['comentario']) ?></span>
                    <span class="text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 rounded-full px-2 py-0.5">Resena Verificada</span>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3">
                <?php if (!$p['puntaje']): ?>
                    <button @click="resenaOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; puntaje = 5"
                            class="rounded-xl border border-gray-200 dark:border-stone-700 px-4 py-2 text-sm font-bold">Calificar Pedido</button>
                <?php endif; ?>
                <form method="post" action="<?= url('/client/historial/' . $p['id_pedido'] . '/recomprar') ?>">
                    <?= csrf_field() ?>
                    <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 text-sm font-bold flex items-center gap-2">
                        <i data-lucide="repeat" class="w-4 h-4"></i> Recompra en 1 clic
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$pedidos): ?>
        <p class="text-center text-gray-400 py-16 text-sm">No hay pedidos en este filtro.</p>
    <?php endif; ?>
</div>

<!-- Modal resena -->
<div x-show="resenaOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="resenaOpen = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-sm">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-lg font-black">Califica tu Experiencia</h2>
            <button @click="resenaOpen = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form :action="'<?= url('/client/historial') ?>/' + pedidoId + '/resena'" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="puntaje" :value="puntaje">
            <div class="flex justify-center gap-1">
                <template x-for="n in 5" :key="n">
                    <button type="button" @click="puntaje = n">
                        <i data-lucide="star" class="w-8 h-8" :class="n <= puntaje ? 'text-brand-500 fill-brand-500' : 'text-gray-300'"></i>
                    </button>
                </template>
            </div>
            <textarea name="comentario" rows="3" placeholder="ej. La hamburguesa estaba deliciosa!"
                      class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm"></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" @click="resenaOpen = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-4 py-2 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 text-sm font-bold">Enviar</button>
            </div>
        </form>
    </div>
</div>
</div>
