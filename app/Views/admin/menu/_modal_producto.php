<?php /** @var array $categorias */ ?>
<div x-show="openProducto" x-cloak class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto bg-black/50">
    <div @click.outside="openProducto = false"
         class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-4xl my-8">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black" x-text="editando ? 'Editar Producto' : 'Nuevo Producto'"></h2>
            <button @click="openProducto = false" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form method="post" action="<?= url('/admin/menu/producto') ?>" class="grid lg:grid-cols-3 gap-6 p-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id_producto" :value="form.id_producto">

            <!-- Columna izquierda: datos + receta -->
            <div class="lg:col-span-2 space-y-4">
                <div>
                    <label class="block text-sm font-bold mb-1.5">Nombre del producto</label>
                    <input name="nombre" x-model="form.nombre" required
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1.5">Categoria</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($categorias as $c): ?>
                            <button type="button" @click="form.id_categoria = '<?= e($c['id_categoria']) ?>'"
                                    :class="form.id_categoria === '<?= e($c['id_categoria']) ?>' ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 dark:border-stone-700'"
                                    class="px-3 py-1.5 rounded-full border text-xs font-bold"><?= e($c['nombre']) ?></button>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="id_categoria" :value="form.id_categoria" required>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5">Precio de venta</label>
                        <input name="precio" type="number" min="0" step="100" x-model="form.precio" required
                               class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5">Etiqueta destacada</label>
                        <select name="etiqueta_destacada" x-model="form.etiqueta_destacada"
                                class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                            <option value="ninguna">Ninguna</option>
                            <option value="mas_vendido">Mas Vendido</option>
                            <option value="recomendado">Recomendado</option>
                            <option value="nuevo">Nuevo</option>
                            <option value="especialidad">Especialidad</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1.5">Descripcion para el menu</label>
                    <textarea name="descripcion" x-model="form.descripcion" rows="2"
                              class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm"
                              placeholder="Ej: Pan, carne 120g, queso, lechuga, tomate y salsas"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1.5">Imagen del producto (URL)</label>
                    <input name="imagen" x-model="form.imagen" placeholder="https://..."
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>

                <!-- Receta / Escandallo -->
                <div class="rounded-2xl border border-gray-100 dark:border-stone-800 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-bold text-sm">Receta e Insumos (Escandallo)</h3>
                        <button type="button" @click="addInsumo()"
                                class="text-xs font-bold text-brand-600 flex items-center gap-1">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Agregar insumo
                        </button>
                    </div>
                    <template x-if="receta.length === 0">
                        <p class="text-xs text-gray-400 py-3">No has asignado insumos a esta receta.</p>
                    </template>
                    <div class="space-y-2">
                        <template x-for="(r, i) in receta" :key="i">
                            <div class="flex items-center gap-2">
                                <select x-model="r.id_ingrediente" :name="'receta_ingrediente[' + i + ']'"
                                        class="flex-1 rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-3 py-2 text-xs">
                                    <template x-for="ing in ingredientes" :key="ing.id">
                                        <option :value="ing.id" x-text="ing.nombre + ' (' + ing.unidad + ')'"></option>
                                    </template>
                                </select>
                                <input type="number" min="0" step="0.01" x-model="r.cantidad" :name="'receta_cantidad[' + i + ']'"
                                       class="w-24 rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-2 py-2 text-xs">
                                <span class="w-24 text-right text-xs text-gray-400" x-text="money(infoIng(r.id_ingrediente).costo * (parseFloat(r.cantidad)||0))"></span>
                                <button type="button" @click="quitarInsumo(i)" class="text-red-400 hover:text-red-600"><i data-lucide="x" class="w-4 h-4"></i></button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Columna derecha: rentabilidad + preview -->
            <div class="space-y-4">
                <div class="rounded-2xl bg-gray-50 dark:bg-stone-900 border border-gray-100 dark:border-stone-800 p-4">
                    <h3 class="font-bold text-sm mb-3">Analisis de Rentabilidad</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Costo insumos</dt><dd class="font-bold" x-text="money(costoInsumos)"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Costo empaques</dt><dd class="font-bold" x-text="money(costoEmpaques)"></dd></div>
                        <div class="flex justify-between border-t border-gray-200 dark:border-stone-700 pt-2"><dt class="text-gray-500">Costo total/porcion</dt><dd class="font-bold" x-text="money(costoTotal)"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Precio de venta</dt><dd class="font-bold" x-text="money(form.precio)"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Ganancia neta</dt><dd class="font-black text-emerald-600" x-text="money(ganancia)"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Margen</dt><dd class="font-black" :class="margen >= 40 ? 'text-emerald-600' : 'text-amber-500'"><span x-text="margen"></span>%</dd></div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-gray-100 dark:border-stone-800 overflow-hidden">
                    <div class="h-28 bg-gray-100 dark:bg-stone-800 bg-cover bg-center" :style="form.imagen ? 'background-image:url(' + form.imagen + ')' : ''"></div>
                    <div class="p-3">
                        <p class="text-[11px] text-gray-400">Vista previa en menu</p>
                        <p class="font-bold text-sm" x-text="form.nombre || 'Nombre del producto'"></p>
                        <p class="text-brand-600 font-black" x-text="money(form.precio)"></p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 flex justify-end gap-3 border-t border-gray-100 dark:border-stone-800 pt-4">
                <button type="button" @click="openProducto = false"
                        class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold"
                        x-text="editando ? 'Guardar Cambios' : 'Guardar y Publicar en Menu'"></button>
            </div>
        </form>
    </div>
</div>
