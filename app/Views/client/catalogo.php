<?php
/** @var array $productos @var array $categorias @var array $cliente @var bool $cumple */
?>
<div x-data="catalogo()">

<?php if ($cumple): ?>
    <div class="mb-6 rounded-3xl bg-brand-500 text-white p-5 flex items-center gap-4">
        <i data-lucide="gift" class="w-8 h-8"></i>
        <div>
            <p class="font-black">Feliz Cumpleanos, <?= e($cliente['nombre']) ?>!</p>
            <p class="text-sm text-white/90">Tienes un 15% de descuento automatico en tu carrito, valido solo por hoy.</p>
        </div>
    </div>
<?php endif; ?>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <h1 class="text-3xl font-black">Catalogo de Productos</h1>
    <?php if ($ultimoPedido): ?>
        <form method="post" action="<?= url('/client/historial/' . $ultimoPedido . '/recomprar') ?>">
            <?= csrf_field() ?>
            <button class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-bold">
                <i data-lucide="repeat" class="w-4 h-4"></i> Pedir lo mismo de la ultima vez
            </button>
        </form>
    <?php endif; ?>
</div>

<div class="flex flex-wrap gap-2 mb-6">
    <button @click="cat = 'todas'" :class="cat === 'todas' ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 dark:border-stone-700'"
            class="px-4 py-2 rounded-full border text-sm font-bold">Todas</button>
    <?php foreach ($categorias as $c): ?>
        <button @click="cat = '<?= e($c['id_categoria']) ?>'"
                :class="cat === '<?= e($c['id_categoria']) ?>' ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 dark:border-stone-700'"
                class="px-4 py-2 rounded-full border text-sm font-bold"><?= e($c['nombre']) ?></button>
    <?php endforeach; ?>
</div>

<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    <?php foreach ($productos as $p): ?>
        <div x-show="cat === 'todas' || cat === '<?= e($p['id_categoria']) ?>'" x-transition
             class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl overflow-hidden shadow-sm flex flex-col relative
                    <?= $p['agotado'] ? 'opacity-70' : '' ?>">
            <?php if ($p['agotado']): ?><div class="badge-agotado"><span>AGOTADO</span></div><?php endif; ?>
            <div class="h-44 bg-gray-100 dark:bg-stone-800 bg-cover bg-center"
                 style="background-image:url('<?= e($p['imagen'] ?: 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=500&q=60') ?>')"></div>
            <div class="p-4 flex-1 flex flex-col">
                <?php if ($p['etiqueta_destacada'] !== 'ninguna'): ?>
                    <span class="self-start text-[10px] font-black tracking-wide bg-brand-500 text-white rounded-full px-2 py-1 mb-2">
                        <?= strtoupper(str_replace('_', ' ', $p['etiqueta_destacada'])) ?>
                    </span>
                <?php endif; ?>
                <h3 class="font-bold"><?= e($p['nombre']) ?></h3>
                <p class="text-xs text-brand-500/80 dark:text-brand-400/80 mt-1 line-clamp-2 flex-1"><?= e($p['descripcion']) ?></p>
                <div class="flex items-center justify-between mt-3">
                    <span class="font-black text-brand-600"><?= money($p['precio']) ?></span>
                    <button @click="abrir('<?= e($p['id_producto']) ?>')" <?= $p['agotado'] ? 'disabled' : '' ?>
                            class="w-9 h-9 rounded-full bg-brand-500 hover:bg-brand-600 disabled:opacity-40 text-white flex items-center justify-center">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

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
