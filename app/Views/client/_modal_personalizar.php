<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="open = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-md max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black" x-text="prod.nombre"></h2>
            <button @click="open = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>

        <form method="post" action="<?= url('/client/carrito/agregar') ?>" class="p-5 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="id_producto" :value="prod.id_producto">
            <input type="hidden" name="cantidad" :value="cantidad">
            <input type="hidden" name="volver" value="/client">

            <template x-if="(prod.personalizables?.retirar || []).length">
                <div>
                    <p class="text-xs font-black uppercase text-gray-400 mb-2">Retirar ingredientes</p>
                    <div class="space-y-2">
                        <template x-for="ing in prod.personalizables.retirar" :key="ing.id_ingrediente">
                            <label class="flex items-center justify-between rounded-xl border border-gray-100 dark:border-stone-800 px-3 py-2">
                                <span class="text-sm" x-text="ing.nombre"></span>
                                <input type="checkbox" name="quitar[]" :value="ing.id_ingrediente"
                                       @change="toggle('quitar', ing.id_ingrediente)"
                                       class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                            </label>
                        </template>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Quitar ingredientes no tiene costo.</p>
                </div>
            </template>

            <template x-if="(prod.personalizables?.extras || []).length">
                <div>
                    <p class="text-xs font-black uppercase text-gray-400 mb-2">Anadir extras</p>
                    <div class="space-y-2">
                        <template x-for="ing in prod.personalizables.extras" :key="ing.id_ingrediente">
                            <label class="flex items-center justify-between rounded-xl border px-3 py-2"
                                   :class="ing.agotado ? 'opacity-50 border-gray-100 dark:border-stone-800' : 'border-gray-100 dark:border-stone-800'">
                                <span class="text-sm">
                                    <span x-text="ing.nombre"></span>
                                    <span class="text-emerald-600 font-bold" x-text="' +' + money(ing.precio_extra)"></span>
                                    <span x-show="ing.agotado" class="text-red-500 text-xs font-black ml-1">AGOTADO</span>
                                </span>
                                <input type="checkbox" name="extra[]" :value="ing.id_ingrediente" :disabled="ing.agotado == 1"
                                       @change="toggle('extra', ing.id_ingrediente)"
                                       class="rounded border-gray-300 text-emerald-500 focus:ring-emerald-500">
                            </label>
                        </template>
                    </div>
                </div>
            </template>

            <div class="flex items-center gap-3">
                <span class="text-sm font-bold">Cantidad</span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="cantidad = Math.max(1, cantidad - 1)" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-stone-800 font-bold">−</button>
                    <span class="w-6 text-center font-bold" x-text="cantidad"></span>
                    <button type="button" @click="cantidad++" class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-stone-800 font-bold">+</button>
                </div>
            </div>

            <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl py-3">
                Confirmar Seleccion · <span x-text="money(total)"></span>
            </button>
        </form>
    </div>
</div>
