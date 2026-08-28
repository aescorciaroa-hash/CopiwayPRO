<?php
/** @var array $items @var array $cliente @var float $subtotal @var float $descuento @var float $envio @var float $total @var array $estado @var bool $cumple */
?>
<div x-data="{ metodo: 'digital', banco: 'Nequi' }">
<div class="flex items-center gap-3 mb-6">
    <a href="<?= url('/client/carrito') ?>" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 flex items-center justify-center"><i data-lucide="arrow-left" class="w-4 h-4"></i></a>
    <h1 class="text-3xl font-black">Checkout</h1>
</div>

<?php if (!$estado['abierta']): ?>
    <div class="mb-4 rounded-2xl bg-red-50 dark:bg-red-950/30 text-red-600 p-4 text-sm font-bold flex items-center gap-2">
        <i data-lucide="clock" class="w-5 h-5"></i> Cocina Cerrada | Abre <?= $estado['apertura'] ?>. El pago esta bloqueado.
    </div>
<?php endif; ?>

<form method="post" action="<?= url('/client/checkout') ?>" class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-6">
    <?= csrf_field() ?>

    <div>
        <h2 class="font-black mb-2">Direccion de Entrega</h2>
        <input name="direccion" value="<?= e($cliente['direccion']) ?>" required placeholder="Calle 10 # 5-20, Centro"
               class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-3 text-sm">
    </div>

    <div>
        <h2 class="font-black mb-2">Resumen del Pedido</h2>
        <div class="space-y-1 text-sm">
            <?php foreach ($items as $item): ?>
                <div class="flex justify-between">
                    <span><?= $item['cantidad'] ?>x <?= e($item['nombre']) ?></span>
                    <span><?= money(($item['precio_unitario'] + array_sum(array_column($item['personalizaciones'] ?? [], 'costo'))) * $item['cantidad']) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="flex justify-between border-t border-gray-100 dark:border-stone-800 pt-2 text-gray-500"><span>Subtotal</span><span><?= money($subtotal) ?></span></div>
            <?php if ($descuento > 0): ?>
                <div class="flex justify-between text-emerald-600"><span>Descuento cumpleanos (15%)</span><span>− <?= money($descuento) ?></span></div>
            <?php endif; ?>
            <div class="flex justify-between text-gray-500"><span>Envio (Tarifa Plana)</span><span><?= money($envio) ?></span></div>
            <div class="flex justify-between text-lg pt-1"><span class="font-black">Total a Pagar</span><span class="font-black text-brand-600"><?= money($total) ?></span></div>
        </div>
    </div>

    <div>
        <h2 class="font-black mb-2">Metodo de Pago</h2>
        <div class="grid grid-cols-2 gap-3">
            <button type="button" @click="metodo = 'digital'"
                    :class="metodo === 'digital' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 text-brand-600' : 'border-gray-200 dark:border-stone-700'"
                    class="rounded-xl border p-4 text-sm font-bold flex flex-col items-center gap-1">
                <i data-lucide="credit-card" class="w-5 h-5"></i> Pago Digital
            </button>
            <button type="button" @click="metodo = 'efectivo'"
                    :class="metodo === 'efectivo' ? 'border-brand-500 bg-brand-50 dark:bg-brand-500/10 text-brand-600' : 'border-gray-200 dark:border-stone-700'"
                    class="rounded-xl border p-4 text-sm font-bold flex flex-col items-center gap-1">
                <i data-lucide="banknote" class="w-5 h-5"></i> Efectivo al Entregar
            </button>
        </div>
        <input type="hidden" name="metodo_pago" :value="metodo">

        <div x-show="metodo === 'digital'" x-cloak class="mt-4 rounded-xl bg-gray-50 dark:bg-stone-900 p-4">
            <p class="text-xs font-black uppercase text-gray-400 mb-2">Selecciona tu banco</p>
            <div class="grid grid-cols-3 gap-2">
                <template x-for="b in ['Nequi', 'Daviplata', 'Bancolombia']">
                    <button type="button" @click="banco = b" :class="banco === b ? 'bg-brand-500 text-white' : 'bg-white dark:bg-card border border-gray-200 dark:border-stone-700'"
                            class="rounded-lg py-2 text-xs font-bold" x-text="b"></button>
                </template>
            </div>
            <input type="hidden" name="banco" :value="banco">
            <input name="cuenta" placeholder="Numero de celular / cuenta"
                   class="mt-3 w-full rounded-lg border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-3 py-2 text-sm">
        </div>
    </div>

    <div class="rounded-xl bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 p-3 text-xs font-medium flex gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
        Punto de no retorno. Al confirmar el pago, la orden se enviara a cocina y no se admiten cambios ni cancelaciones.
    </div>

    <button <?= $estado['abierta'] ? '' : 'disabled' ?>
            class="w-full bg-brand-500 hover:bg-brand-600 disabled:opacity-40 disabled:cursor-not-allowed text-white font-black rounded-xl py-4"
            onclick="return confirm('Confirmar tu pedido por <?= money($total) ?>? Pasara directamente a cocina (punto de no retorno).')">
        CONFIRMAR Y PAGAR
    </button>
</form>
</div>
