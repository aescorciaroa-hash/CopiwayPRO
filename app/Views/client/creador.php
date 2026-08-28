<?php
/** @var array $ingredientes */
$porCategoria = [];
foreach ($ingredientes as $i) { $porCategoria[$i['categoria']][] = $i; }
?>
<div x-data="creador()">
<h1 class="text-3xl font-black mb-2">Arma tu Burger</h1>
<p class="text-gray-500 dark:text-gray-400 mb-6">Construye tu hamburguesa capa por capa. Los ingredientes agotados se bloquean solos.</p>

<form method="post" action="<?= url('/client/creador/agregar') ?>" class="grid gap-6 lg:grid-cols-3">
    <?= csrf_field() ?>

    <!-- Torre de capas -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
        <h2 class="font-black mb-4">Tu Burger</h2>
        <div class="flex flex-col-reverse items-center gap-1 min-h-[200px] justify-end">
            <div class="w-40 h-6 bg-amber-700 rounded-b-full"></div>
            <template x-for="(c, i) in capas" :key="i">
                <div class="w-44 h-7 rounded-lg flex items-center justify-center text-[11px] font-bold text-white shadow"
                     :style="'background:' + color(i)" x-text="c.nombre"></div>
            </template>
            <div class="w-40 h-8 bg-amber-500 rounded-t-full"></div>
        </div>
        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-stone-800">
            <p class="text-sm text-gray-500">Capas: <span x-text="capas.length"></span></p>
            <p class="text-2xl font-black text-brand-600" x-text="money(total)"></p>
        </div>
        <button class="mt-4 w-full bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl py-3" :disabled="capas.length === 0">
            AGREGAR AL CARRITO
        </button>
    </div>

    <!-- Ingredientes -->
    <div class="lg:col-span-2 space-y-5">
        <?php foreach ($porCategoria as $cat => $lista): ?>
            <div>
                <h3 class="font-black text-sm uppercase text-gray-400 mb-2"><?= e($cat) ?></h3>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($lista as $ing): ?>
                        <label class="rounded-2xl border p-3 flex items-center justify-between cursor-pointer
                                      <?= $ing['agotado'] ? 'opacity-50 border-gray-100 dark:border-stone-800' : 'border-gray-100 dark:border-stone-800 hover:border-brand-400' ?>">
                            <div>
                                <p class="text-sm font-bold"><?= e($ing['nombre']) ?></p>
                                <p class="text-xs text-gray-400">
                                    <?= money($ing['precio']) ?>
                                    <?php if ($ing['agotado']): ?><span class="text-red-500 font-black ml-1">AGOTADO</span>
                                    <?php else: ?>· <?= (int) $ing['cantidad_stock'] ?> disp.<?php endif; ?>
                                </p>
                            </div>
                            <input type="checkbox" name="capa[]" value="<?= e($ing['id_ingrediente']) ?>" <?= $ing['agotado'] ? 'disabled' : '' ?>
                                   @change="toggle($event, {id:'<?= e($ing['id_ingrediente']) ?>', nombre:'<?= e($ing['nombre']) ?>', precio:<?= (float) $ing['precio'] ?>})"
                                   class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</form>
</div>
<script>
function creador() {
    return {
        capas: [],
        toggle(e, ing) {
            if (e.target.checked) this.capas.push(ing);
            else this.capas = this.capas.filter(c => c.id !== ing.id);
        },
        get total() { return this.capas.reduce((s, c) => s + Number(c.precio), 0); },
        color(i) {
            const cols = ['#f59e0b', '#a16207', '#22c55e', '#ef4444', '#eab308', '#f97316', '#84cc16'];
            return cols[i % cols.length];
        },
        money(v) { return '$ ' + Number(v || 0).toLocaleString('es-CO', { maximumFractionDigits: 0 }); },
    }
}
</script>
