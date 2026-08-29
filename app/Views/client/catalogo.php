<?php
/** @var array $productos @var array $categorias @var array $cliente @var bool $cumple */
?>
<div x-data="catalogo()">

<?php if ($cumple): ?>
    <div class="mb-6 rounded-[32px] bg-brand-500/10 border border-brand-500/20 p-5 flex items-center gap-4">
        <span class="w-12 h-12 rounded-2xl bg-brand-500/15 text-brand-600 dark:text-brand-500 flex items-center justify-center shrink-0">
            <i data-lucide="gift" class="w-6 h-6"></i>
        </span>
        <div>
            <p class="font-black tracking-tight text-brand-700 dark:text-brand-400">Feliz Cumpleanos, <?= e($cliente['nombre']) ?>!</p>
            <p class="text-sm font-medium text-brand-600/80 dark:text-brand-400/80">Tienes un 15% de descuento automatico en tu carrito, valido solo por hoy.</p>
        </div>
    </div>
<?php endif; ?>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Catalogo de Productos</h1>
    <?php if ($ultimoPedido): ?>
        <form method="post" action="<?= url('/client/historial/' . $ultimoPedido . '/recomprar') ?>">
            <?= csrf_field() ?>
            <button class="inline-flex items-center gap-2 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium
                           shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
                <i data-lucide="repeat" class="w-4 h-4"></i> Pedir lo mismo de la ultima vez
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="flex flex-wrap gap-2 mb-8">
    <button @click="cat = 'todas'"
            :class="cat === 'todas' ? 'bg-brand-500 text-white border-brand-500 shadow-md shadow-brand-500/25' : 'bg-white dark:bg-stone-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-stone-700 hover:border-brand-500 hover:text-brand-600'"
            class="px-5 py-2.5 rounded-2xl border text-sm font-medium transition-all duration-300 hover:scale-105">Todas</button>
    <?php foreach ($categorias as $c): ?>
        <button @click="cat = '<?= e($c['id_categoria']) ?>'"
                :class="cat === '<?= e($c['id_categoria']) ?>' ? 'bg-brand-500 text-white border-brand-500 shadow-md shadow-brand-500/25' : 'bg-white dark:bg-stone-900 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-stone-700 hover:border-brand-500 hover:text-brand-600'"
                class="px-5 py-2.5 rounded-2xl border text-sm font-medium transition-all duration-300 hover:scale-105"><?= e($c['nombre']) ?></button>
    <?php endforeach; ?>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    <?php foreach ($productos as $p): ?>
        <div x-show="cat === 'todas' || cat === '<?= e($p['id_categoria']) ?>'" x-transition
             class="group bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] overflow-hidden shadow-sm
                    hover:shadow-xl transition-all duration-300 flex flex-col relative <?= $p['agotado'] ? 'opacity-70' : '' ?>">
            <?php if ($p['agotado']): ?><div class="badge-agotado"><span>AGOTADO</span></div><?php endif; ?>
            <div class="h-44 bg-gray-100 dark:bg-stone-800 overflow-hidden">
                <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                     style="background-image:url('<?= e($p['imagen'] ?: 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=500&q=60') ?>')"></div>
            </div>
            <div class="p-5 flex-1 flex flex-col">
                <?php if ($p['etiqueta_destacada'] !== 'ninguna'): ?>
                    <span class="self-start text-[10px] font-black tracking-wide bg-brand-500 text-white rounded-full px-2.5 py-1 mb-2">
                        <?= strtoupper(str_replace('_', ' ', $p['etiqueta_destacada'])) ?>
                    </span>
                <?php endif; ?>
                <h3 class="font-black tracking-tight text-gray-900 dark:text-white"><?= e($p['nombre']) ?></h3>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 flex-1"><?= e($p['descripcion']) ?></p>
                <div class="flex items-center justify-between mt-4">
                    <span class="font-black tracking-tight text-lg text-brand-600 dark:text-brand-500"><?= money($p['precio']) ?></span>
                    <button @click="abrir('<?= e($p['id_producto']) ?>')" <?= $p['agotado'] ? 'disabled' : '' ?>
                            class="inline-flex items-center gap-1.5 rounded-2xl bg-brand-500 hover:bg-brand-600 disabled:opacity-40 text-white
                                   text-sm font-medium px-4 py-2.5 transition-all duration-300 hover:scale-105">
                        <i data-lucide="plus" class="w-4 h-4"></i> Anadir
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Carrito flotante -->
<a href="<?= url('/client/carrito') ?>"
   class="fixed bottom-24 md:bottom-8 right-6 z-40 inline-flex items-center gap-2.5 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white
          font-medium px-5 py-3.5 shadow-xl shadow-brand-500/30 transition-all duration-300 hover:scale-105">
    <i data-lucide="shopping-cart" class="w-5 h-5"></i> Ver carrito
</a>

<?php require __DIR__ . '/_modal_personalizar.php'; ?>

</div>
<script>
function catalogo() {
    return {
        cat: 'todas', open: false, prod: {}, quitar: [], extra: [], cantidad: 1,
        async abrir(id) {
            const res = await fetch('<?= url('/client/producto') ?>/' + id);
            this.prod = await res.json();
            this.quitar = []; this.extra = []; this.cantidad = 1;
            this.open = true;
            this.$nextTick(() => lucide.createIcons());
        },
        toggle(list, id) {
            const i = this[list].indexOf(id);
            if (i >= 0) this[list].splice(i, 1); else this[list].push(id);
        },
        get extrasCosto() {
            return (this.prod.personalizables?.extras || [])
                .filter(e => this.extra.includes(e.id_ingrediente))
                .reduce((s, e) => s + Number(e.precio_extra), 0);
        },
        get total() { return ((Number(this.prod.precio) || 0) + this.extrasCosto) * this.cantidad; },
        money(v) { return '$ ' + Number(v || 0).toLocaleString('es-CO', { maximumFractionDigits: 0 }); },
    }
}
</script>
