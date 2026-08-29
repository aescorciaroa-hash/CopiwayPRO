<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div @click.outside="open = false"
         class="bg-white dark:bg-stone-900 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-800 w-full max-w-md max-h-[88vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-stone-800 shrink-0">
            <h2 class="text-xl font-black tracking-tight text-gray-900 dark:text-white" x-text="prod.nombre"></h2>
            <button @click="open = false"
                    class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <form method="post" action="<?= url('/client/carrito/agregar') ?>" class="flex flex-col flex-1 min-h-0">
            <?= csrf_field() ?>
            <input type="hidden" name="id_producto" :value="prod.id_producto">
            <input type="hidden" name="cantidad" :value="cantidad">
            <input type="hidden" name="volver" value="/client">

            <div class="p-6 space-y-6 overflow-y-auto flex-1">
                <template x-if="(prod.personalizables?.retirar || []).length">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                            <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i> Retirar ingredientes
                        </p>
                        <div class="space-y-2">
                            <template x-for="ing in prod.personalizables.retirar" :key="ing.id_ingrediente">
                                <label class="flex items-center justify-between rounded-2xl border px-4 py-3 cursor-pointer transition-colors"
                                       :class="quitar.includes(ing.id_ingrediente) ? 'border-red-300 bg-red-50 dark:border-red-500/40 dark:bg-red-500/10' : 'border-gray-100 dark:border-stone-800 hover:border-gray-200 dark:hover:border-stone-700'">
                                    <span class="text-sm"
                                          :class="quitar.includes(ing.id_ingrediente) ? 'text-red-600 dark:text-red-400 font-black' : 'text-gray-700 dark:text-gray-200 font-medium'">
                                        <span x-show="quitar.includes(ing.id_ingrediente)">SIN </span><span x-text="ing.nombre"></span>
                                    </span>
                                    <input type="checkbox" name="quitar[]" :value="ing.id_ingrediente"
                                           @change="toggle('quitar', ing.id_ingrediente)"
                                           class="rounded border-gray-300 text-red-500 focus:ring-red-500">
                                </label>
                            </template>
                        </div>
                        <p class="text-[11px] text-gray-400 font-medium mt-1.5">Quitar ingredientes no tiene costo.</p>
                    </div>
                </template>

                <template x-if="(prod.personalizables?.extras || []).length">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-1.5">
                            <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Anadir extras
                        </p>
                        <div class="space-y-2">
                            <template x-for="ing in prod.personalizables.extras" :key="ing.id_ingrediente">
                                <label class="flex items-center justify-between rounded-2xl border px-4 py-3 cursor-pointer transition-colors"
                                       :class="ing.agotado ? 'opacity-50 border-gray-100 dark:border-stone-800' : (extra.includes(ing.id_ingrediente) ? 'border-emerald-300 bg-emerald-50 dark:border-emerald-500/40 dark:bg-emerald-500/10' : 'border-gray-100 dark:border-stone-800 hover:border-gray-200 dark:hover:border-stone-700')">
                                    <span class="text-sm">
                                        <span :class="extra.includes(ing.id_ingrediente) ? 'text-emerald-600 dark:text-emerald-400 font-black' : 'text-gray-700 dark:text-gray-200 font-medium'">
                                            <span x-show="extra.includes(ing.id_ingrediente)">EXTRA </span><span x-text="ing.nombre"></span>
                                        </span>
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
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Cantidad</span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="cantidad = Math.max(1, cantidad - 1)"
                                class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-stone-800 font-black text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors">−</button>
                        <span class="w-8 text-center font-black text-gray-900 dark:text-white" x-text="cantidad"></span>
                        <button type="button" @click="cantidad++"
                                class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-stone-800 font-black text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors">+</button>
                    </div>
                </div>
            </div>

            <!-- Pie flotante con total dinamico -->
            <div class="shrink-0 p-4 border-t border-gray-100 dark:border-stone-800 bg-white dark:bg-stone-900">
                <div class="rounded-[32px] bg-gray-50 dark:bg-stone-950/40 border border-gray-100 dark:border-stone-800 p-3 pl-5 flex items-center gap-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Total</p>
                        <p class="text-2xl font-black tracking-tight text-brand-600 dark:text-brand-500" x-text="money(total)"></p>
                    </div>
                    <button class="flex-1 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-2xl py-3.5 transition-all duration-300 hover:scale-105">
                        Confirmar Seleccion
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
