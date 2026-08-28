<?php /** @var array $categorias */ ?>
<div x-show="openCategorias" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openCategorias = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-md">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Categorias del Menu</h2>
            <button @click="openCategorias = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5 space-y-4">
            <form method="post" action="<?= url('/admin/menu/categoria') ?>" class="flex gap-2">
                <?= csrf_field() ?>
                <input name="nombre" required placeholder="Nueva categoria"
                       class="flex-1 rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2.5 text-sm font-bold">Agregar</button>
            </form>
            <div class="space-y-2 max-h-72 overflow-y-auto">
                <?php foreach ($categorias as $c): ?>
                    <div class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-stone-800 px-4 py-2.5">
                        <span class="text-sm font-bold"><?= e($c['nombre']) ?>
                            <span class="text-xs text-gray-400 font-normal">· <?= $c['items'] ?> productos</span></span>
                        <form method="post" action="<?= url('/admin/menu/categoria/' . $c['id_categoria'] . '/eliminar') ?>"
                              onsubmit="return confirm('Eliminar la categoria &quot;<?= e($c['nombre']) ?>&quot;?')">
                            <?= csrf_field() ?>
                            <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
