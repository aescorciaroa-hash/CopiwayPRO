<!-- Modal: Orden Manual -->
<div x-show="openManual" x-cloak class="fixed inset-0 z-50 flex items-start justify-center p-4 overflow-y-auto bg-black/50">
    <div @click.outside="openManual = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-lg my-8">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Ingreso de Orden Manual</h2>
            <button @click="openManual = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="post" action="<?= url('/admin/comandas/manual') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <div class="grid sm:grid-cols-2 gap-3">
                <input name="cliente" placeholder="Nombre del cliente" class="rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <input name="telefono" required placeholder="Telefono" class="rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
            <input name="direccion" required placeholder="Direccion de entrega" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <select name="canal" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <option value="llamada">Llamada telefonica</option>
                <option value="whatsapp">WhatsApp</option>
            </select>

            <div class="rounded-2xl border border-gray-100 dark:border-stone-800 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-sm">Productos</h3>
                    <button type="button" @click="addLinea()" class="text-xs font-bold text-brand-600 flex items-center gap-1"><i data-lucide="plus" class="w-3.5 h-3.5"></i> Agregar</button>
                </div>
                <div class="space-y-2">
                    <template x-for="(l, i) in lineas" :key="i">
                        <div class="flex items-center gap-2">
                            <select x-model="l.id" :name="'producto_id[' + i + ']'" class="flex-1 rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-3 py-2 text-xs">
                                <option value="">Seleccionar del menu...</option>
                                <template x-for="p in productos" :key="p.id">
                                    <option :value="p.id" x-text="p.nombre + ' — ' + money(p.precio)"></option>
                                </template>
                            </select>
                            <input type="number" min="1" x-model="l.cant" :name="'producto_cant[' + i + ']'" class="w-16 rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-2 py-2 text-xs">
                            <button type="button" @click="quitarLinea(i)" class="text-red-400"><i data-lucide="x" class="w-4 h-4"></i></button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 dark:bg-stone-900 p-3 text-sm space-y-1">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-bold" x-text="money(subtotal)"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Envio (tarifa plana)</span><span class="font-bold" x-text="money(envio)"></span></div>
                <div class="flex justify-between border-t border-gray-200 dark:border-stone-700 pt-1"><span class="font-bold">Total</span><span class="font-black text-brand-600" x-text="money(subtotal + envio)"></span></div>
                <p class="text-xs text-amber-600 pt-1">Se registra como "Efectivo al Entregar" y se despacha a cocina.</p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" @click="openManual = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Enviar a Cocina</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Detalle de Orden -->
<div x-show="openDetalle" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openDetalle = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-md max-h-[85vh] overflow-y-auto">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Orden <span x-text="pedido.codigo"></span></h2>
            <button @click="openDetalle = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5 space-y-4 text-sm">
            <div class="flex gap-2">
                <span class="text-[10px] font-black uppercase rounded-full px-2 py-1 bg-gray-100 dark:bg-stone-800" x-text="(pedido.estado||'').replace('_',' ')"></span>
                <span class="text-[10px] font-black uppercase rounded-full px-2 py-1 bg-gray-100 dark:bg-stone-800" x-text="'Pago: ' + (pedido.pago_metodo||'-')"></span>
            </div>
            <div>
                <p class="font-bold">Cliente</p>
                <p class="text-gray-500" x-text="pedido.cliente_nombre + ' · ' + pedido.cliente_telefono"></p>
                <p class="text-gray-500" x-text="pedido.direccion_entrega"></p>
            </div>
            <div>
                <p class="font-bold">Domiciliario</p>
                <p class="text-gray-500" x-text="pedido.domiciliario_nombre ? (pedido.domiciliario_nombre + ' · ' + (pedido.placa||'')) : 'Por asignar'"></p>
            </div>
            <div>
                <p class="font-bold mb-1">Articulos</p>
                <template x-for="l in (pedido.lineas || [])">
                    <div class="flex justify-between py-1 border-b border-gray-100 dark:border-stone-800">
                        <span><span x-text="l.cantidad"></span>x <span x-text="l.nombre"></span></span>
                        <span x-text="'$ ' + Number(l.precio_unitario * l.cantidad).toLocaleString('es-CO')"></span>
                    </div>
                </template>
            </div>
            <div class="space-y-1 pt-1">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span x-text="'$ ' + Number(pedido.subtotal||0).toLocaleString('es-CO')"></span></div>
                <div class="flex justify-between"><span class="text-gray-500">Domicilio</span><span x-text="'$ ' + Number(pedido.costo_domicilio||0).toLocaleString('es-CO')"></span></div>
                <div class="flex justify-between font-black"><span>Total</span><span x-text="'$ ' + Number(pedido.total||0).toLocaleString('es-CO')"></span></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Editar direccion -->
<div x-show="openDir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="openDir = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-md">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Editar Direccion del Pedido</h2>
            <button @click="openDir = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form :action="'<?= url('/admin/comandas') ?>/' + dirPedidoId + '/direccion'" method="post" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <p class="text-sm text-gray-400">Actualiza el punto de entrega en tiempo real para el domiciliario.</p>
            <input name="direccion" x-model="dirActual" required class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <div class="flex justify-end gap-3">
                <button type="button" @click="openDir = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
