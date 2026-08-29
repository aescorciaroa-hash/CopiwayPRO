<?php
/** @var array $pedidos @var string $filtro @var array $totales @var array $cliente @var float $invertido */
$abrirResena = isset($_GET['calificar']) ? (string) $_GET['calificar'] : '';
?>
<div x-data="{ resenaOpen: <?= $abrirResena !== '' ? 'true' : 'false' ?>, pedidoId: '<?= e($abrirResena) ?>', puntaje: 5, hover: 0 }">

<h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white mb-6">Historial de Pedidos</h1>

<div class="grid gap-4 sm:grid-cols-3 mb-8">
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Pedidos</p>
        <p class="text-3xl font-black tracking-tight text-gray-900 dark:text-white mt-1"><?= $totales['todos'] ?></p>
    </div>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Puntos Copiway</p>
        <p class="text-3xl font-black tracking-tight text-brand-600 dark:text-brand-500 mt-1"><?= number_format($cliente['puntos_fidelidad'], 0, ',', '.') ?></p>
    </div>
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Invertido</p>
        <p class="text-3xl font-black tracking-tight text-emerald-600 dark:text-emerald-500 mt-1"><?= money($invertido) ?></p>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-8">
    <?php foreach (['todos' => 'Todos', 'pendientes' => 'Pendientes', 'calificados' => 'Calificados'] as $k => $lbl): ?>
        <a href="<?= url('/client/historial?filtro=' . $k) ?>"
           class="px-5 py-2.5 rounded-2xl border text-sm font-medium transition-all duration-300 hover:scale-105
                  <?= $filtro === $k
                      ? 'bg-brand-500 text-white border-brand-500 shadow-md shadow-brand-500/25'
                      : 'bg-white dark:bg-stone-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-stone-700 hover:border-brand-500 hover:text-brand-600' ?>">
            <?= $lbl ?> (<?= $totales[$k] ?>)
        </a>
    <?php endforeach; ?>
</div>

<div class="space-y-4">
    <?php foreach ($pedidos as $p): ?>
        <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm hover:shadow-md transition-all">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-5 border-b border-gray-100 dark:border-stone-800">
                <div class="flex items-center gap-3">
                    <span class="font-mono font-black text-lg text-gray-900 dark:text-white"><?= $p['codigo'] ?></span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-black uppercase rounded-full px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400">
                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Entregado
                    </span>
                </div>
                <div class="text-right">
                    <p class="font-black tracking-tight text-lg text-gray-900 dark:text-white"><?= money($p['total']) ?></p>
                    <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-600 dark:text-emerald-500">+<?= number_format(floor($p['total'] / 1000)) ?> pts ganados</p>
                </div>
            </div>

            <p class="text-xs text-gray-400 font-medium mt-3 flex items-center gap-1.5 flex-wrap">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> <?= date('d/m/Y', strtotime($p['fecha_hora'])) ?>
                <?php if ($p['domiciliario_nombre']): ?>
                    <span>·</span> <i data-lucide="bike" class="w-3.5 h-3.5 text-brand-500"></i> Entregado por <?= e($p['domiciliario_nombre']) ?>
                <?php endif; ?>
            </p>

            <div class="mt-4 rounded-2xl bg-gray-50 dark:bg-stone-950/40 border border-gray-100 dark:border-stone-800 p-4 space-y-1.5 text-sm">
                <?php foreach ($p['lineas'] as $l): ?>
                    <div class="font-medium text-gray-700 dark:text-gray-300">
                        <span class="text-brand-600 dark:text-brand-500 font-black"><?= (int) $l['cantidad'] ?>x</span> <?= e($l['nombre']) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($p['puntaje']): ?>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <div class="flex text-brand-500">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <i data-lucide="star" class="w-4 h-4 <?= $i < $p['puntaje'] ? 'fill-brand-500' : 'text-gray-300 dark:text-stone-700' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="text-xs text-gray-500 font-medium italic"><?= e($p['comentario']) ?></span>
                    <span class="text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400 rounded-full px-2 py-0.5">Resena Verificada</span>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3 mt-5">
                <?php if (!$p['puntaje']): ?>
                    <button @click="resenaOpen = true; pedidoId = '<?= e($p['id_pedido']) ?>'; puntaje = 5; hover = 0"
                            class="rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 hover:bg-brand-500 hover:text-white px-4 py-2.5 text-sm font-medium transition-colors flex items-center gap-2">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i> Calificar Pedido
                    </button>
                <?php endif; ?>
                <form method="post" action="<?= url('/client/historial/' . $p['id_pedido'] . '/recomprar') ?>">
                    <?= csrf_field() ?>
                    <button class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-medium flex items-center gap-2
                                   shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
                        <i data-lucide="repeat" class="w-4 h-4"></i> Recompra en 1 clic
                    </button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$pedidos): ?>
        <p class="text-center text-gray-400 py-16 text-sm font-medium">No hay pedidos en este filtro.</p>
    <?php endif; ?>
</div>

<!-- Modal resena -->
<div x-show="resenaOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div @click.outside="resenaOpen = false" class="bg-white dark:bg-stone-900 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-800 w-full max-w-sm overflow-hidden">
        <div class="p-6 text-center border-b border-gray-100 dark:border-stone-800">
            <span class="w-12 h-12 mx-auto rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 flex items-center justify-center mb-3">
                <i data-lucide="sparkles" class="w-6 h-6"></i>
            </span>
            <h2 class="text-lg font-black tracking-tight text-gray-900 dark:text-white">Califica tu Experiencia</h2>
            <p class="text-xs font-medium text-gray-400 mt-1">Tu opinion nos ayuda a mejorar cada pedido.</p>
        </div>
        <form :action="'<?= url('/client/historial') ?>/' + pedidoId + '/resena'" method="post" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="puntaje" :value="puntaje">
            <div class="flex justify-center gap-1.5">
                <template x-for="n in 5" :key="n">
                    <button type="button" @click="puntaje = n" @mouseenter="hover = n" @mouseleave="hover = 0" class="transition-transform hover:scale-110">
                        <i data-lucide="star" class="w-9 h-9 transition-colors"
                           :class="n <= (hover || puntaje) ? 'text-brand-500 fill-brand-500' : 'text-gray-300 dark:text-stone-700'"></i>
                    </button>
                </template>
            </div>
            <textarea name="comentario" rows="3" placeholder="ej. La hamburguesa estaba deliciosa!"
                      class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-3 text-sm font-medium resize-none focus:outline-none focus:border-brand-500 transition-colors"></textarea>
            <div class="flex gap-3">
                <button type="button" @click="resenaOpen = false" class="flex-1 rounded-2xl border border-gray-200 dark:border-stone-700 px-4 py-3 text-sm font-bold hover:bg-gray-50 dark:hover:bg-stone-800 transition-colors">Cancelar</button>
                <button class="flex-1 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-3 text-sm font-medium transition-all duration-300 hover:scale-105">Enviar reseña</button>
            </div>
        </form>
    </div>
</div>
</div>
