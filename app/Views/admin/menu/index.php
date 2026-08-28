<?php
/** @var array $productos @var array $categorias @var array $ingredientes */
$totalProd = count($productos);
?>
<div x-data="menuPage()" x-init="init()">

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Catalogo</h1>
        <p class="text-gray-500 dark:text-gray-400">Administra productos, precios y recetas.</p>
    </div>
    <div class="flex gap-3">
        <button @click="openCategorias = true"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-stone-700 px-4 py-2.5 text-sm font-bold hover:bg-gray-100 dark:hover:bg-stone-800">
            <i data-lucide="tags" class="w-4 h-4"></i> Gestionar Categorias
        </button>
        <button @click="nuevo()"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-bold">
            <i data-lucide="plus" class="w-4 h-4"></i> Crear Producto
        </button>
    </div>
</div>

<!-- Buscador -->
<form method="get" class="mb-4">
    <div class="relative">
        <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input name="q" value="<?= e($buscar) ?>" placeholder="Buscar en el catalogo por nombre o descripcion..."
               class="w-full rounded-2xl bg-gray-100 dark:bg-stone-900 border border-transparent focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none pl-11 pr-4 py-3 text-sm transition">
        <?php if ($filtroCat): ?><input type="hidden" name="categoria" value="<?= e($filtroCat) ?>"><?php endif; ?>
    </div>
</form>

<!-- Filtros por categoria -->
<div class="flex flex-wrap gap-2 items-center mb-6">
    <a href="<?= url('/admin/menu') ?>" class="px-3.5 py-1.5 rounded-full text-sm font-bold transition <?= !$filtroCat ? 'bg-brand-500 text-white' : 'bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 dark:hover:bg-stone-700' ?>">
        Todas <span class="opacity-60"><?= $totalProd ?></span>
    </a>
    <?php foreach ($categorias as $c): ?>
        <a href="<?= url('/admin/menu?categoria=' . urlencode($c['id_categoria'])) ?>"
           class="px-3.5 py-1.5 rounded-full text-sm font-bold transition <?= $filtroCat === $c['id_categoria'] ? 'bg-brand-500 text-white' : 'bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 dark:hover:bg-stone-700' ?>">
            <?= e($c['nombre']) ?> <span class="opacity-60"><?= $c['items'] ?></span>
        </a>
    <?php endforeach; ?>
    <button type="button" @click="openCategorias = true"
            class="px-3.5 py-1.5 rounded-full text-sm font-bold border border-dashed border-gray-300 dark:border-stone-600 text-gray-500 hover:border-brand-500 hover:text-brand-500 transition inline-flex items-center gap-1">
        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Nueva Categoria
    </button>
</div>

<!-- Grid de productos -->
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
    <?php foreach ($productos as $p): ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl overflow-hidden shadow-sm flex flex-col">
            <div class="h-40 bg-gray-100 dark:bg-stone-800 bg-cover bg-center relative"
                 style="background-image:url('<?= e($p['imagen'] ?: 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=500&q=60') ?>')">
                <span class="absolute top-2.5 right-2.5 text-[11px] font-bold rounded-lg px-2 py-1 shadow-sm
                    <?= $p['estado'] === 'activo' ? 'bg-white text-gray-800' : 'bg-stone-900 text-white' ?>">
                    <?= $p['estado'] === 'activo' ? 'Disponible' : 'Oculto' ?>
                </span>
                <?php if ($p['agotado']): ?>
                    <span class="absolute top-2.5 left-2.5 text-[10px] font-black uppercase rounded-lg px-2 py-1 bg-red-500 text-white shadow-sm">Sin insumos</span>
                <?php endif; ?>
            </div>
            <div class="p-4 flex-1 flex flex-col">
                <p class="text-[11px] font-bold text-brand-500 uppercase"><?= e($p['categoria']) ?></p>
                <h3 class="font-bold mt-0.5"><?= e($p['nombre']) ?></h3>
                <p class="text-xs text-gray-400 line-clamp-2 mt-1 flex-1"><?= e($p['descripcion']) ?: 'Sin descripcion' ?></p>
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <span class="font-black text-brand-600 text-lg"><?= money($p['precio']) ?></span>
                        <span class="block text-[11px] text-gray-400"><?= $p['insumos'] ?> insumos</span>
                    </div>
                    <div class="flex gap-1.5">
                        <button @click="editar('<?= e($p['id_producto']) ?>')"
                                class="rounded-lg border border-gray-200 dark:border-stone-700 hover:bg-gray-100 dark:hover:bg-stone-800 p-2" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <form method="post" action="<?= url('/admin/menu/producto/' . $p['id_producto'] . '/estado') ?>">
                            <?= csrf_field() ?>
                            <button class="rounded-lg border border-gray-200 dark:border-stone-700 hover:bg-gray-100 dark:hover:bg-stone-800 p-2" title="Ocultar / Mostrar">
                                <i data-lucide="<?= $p['estado'] === 'activo' ? 'eye-off' : 'eye' ?>" class="w-4 h-4"></i>
                            </button>
                        </form>
                        <form method="post" action="<?= url('/admin/menu/producto/' . $p['id_producto'] . '/eliminar') ?>"
                              onsubmit="return confirm('Eliminar &quot;<?= e($p['nombre']) ?>&quot; del menu? Esta accion no se puede deshacer.')">
                            <?= csrf_field() ?>
                            <button class="rounded-lg border border-red-200 dark:border-red-900/50 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 p-2" title="Eliminar">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$productos): ?>
        <div class="col-span-full text-center py-20 text-gray-400">
            <i data-lucide="utensils-crossed" class="w-10 h-10 mx-auto mb-3"></i>
            <p class="font-bold">No hay productos que coincidan.</p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/_modal_producto.php'; ?>
<?php require __DIR__ . '/_modal_categorias.php'; ?>

</div>

<script>
function menuPage() {
    return {
        openProducto: false,
        openCategorias: false,
        ingredientes: <?= json_encode(array_map(fn($i) => [
            'id' => $i['id_ingrediente'], 'nombre' => $i['nombre'],
            'costo' => (float) $i['costo_unitario'], 'unidad' => $i['unidad_medida'], 'ambito' => $i['ambito'],
        ], $ingredientes)) ?>,
        form: {},
        receta: [],
        editando: false,
        init() { window.lucide && lucide.createIcons(); },
        nuevo() {
            this.editando = false;
            this.form = { id_producto: '', nombre: '', id_categoria: '', precio: '', descripcion: '', imagen: '', etiqueta_destacada: 'ninguna' };
            this.receta = [];
            this.openProducto = true;
            this.$nextTick(() => lucide.createIcons());
        },
        async editar(id) {
            const res = await fetch('<?= url('/admin/menu/producto') ?>/' + id);
            const p = await res.json();
            this.editando = true;
            this.form = {
                id_producto: p.id_producto, nombre: p.nombre, id_categoria: p.id_categoria,
                precio: p.precio, descripcion: p.descripcion || '', imagen: p.imagen || '',
                etiqueta_destacada: p.etiqueta_destacada
            };
            this.receta = (p.receta || []).map(r => ({ id_ingrediente: r.id_ingrediente, cantidad: parseFloat(r.cantidad_necesaria) }));
            this.openProducto = true;
            this.$nextTick(() => lucide.createIcons());
        },
        addInsumo() { this.receta.push({ id_ingrediente: this.ingredientes[0]?.id || '', cantidad: 1 }); },
        quitarInsumo(i) { this.receta.splice(i, 1); },
        infoIng(id) { return this.ingredientes.find(x => x.id === id) || { costo: 0, unidad: '' }; },
        get costoInsumos() {
            return this.receta.reduce((s, r) => {
                const ing = this.infoIng(r.id_ingrediente);
                return s + (ing.ambito === 'empaque_desechable' ? 0 : ing.costo * (parseFloat(r.cantidad) || 0));
            }, 0);
        },
        get costoEmpaques() {
            return this.receta.reduce((s, r) => {
                const ing = this.infoIng(r.id_ingrediente);
                return s + (ing.ambito === 'empaque_desechable' ? ing.costo * (parseFloat(r.cantidad) || 0) : 0);
            }, 0);
        },
        get costoTotal() { return this.costoInsumos + this.costoEmpaques; },
        get ganancia() { return (parseFloat(this.form.precio) || 0) - this.costoTotal; },
        get margen() {
            const p = parseFloat(this.form.precio) || 0;
            return p > 0 ? Math.round(this.ganancia / p * 100) : 0;
        },
        money(v) { return '$ ' + Number(v || 0).toLocaleString('es-CO', { maximumFractionDigits: 0 }); },
    }
}
</script>
