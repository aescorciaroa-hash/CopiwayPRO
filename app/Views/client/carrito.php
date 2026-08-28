<?php
/** @var array $items @var float $subtotal @var float $envio */
?>
<h1 class="text-3xl font-black mb-6">Tu Carrito</h1>

<?php if (!$items): ?>
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-16 text-center">
        <i data-lucide="shopping-cart" class="w-10 h-10 mx-auto text-gray-300 mb-3"></i>
        <p class="font-bold">Tu carrito esta vacio.</p>
        <a href="<?= url('/client') ?>" class="inline-block mt-4 rounded-xl bg-brand-500 text-white px-5 py-2.5 text-sm font-bold">Explorar Menu</a>
    </div>
<?php else: ?>
<div class="grid gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-3">
        <?php foreach ($items as $item): ?>
            <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-4 shadow-sm flex gap-4">
                <div class="flex-1 min-w-0">
                    <p class="font-bold"><?= e($item['nombre']) ?></p>
                    <?php if ($item['personalizaciones']): ?>
                        <p class="text-xs mt-1">
                            <?php foreach ($item['personalizaciones'] as $p): ?>
                                <span class="<?= $p['accion'] === 'quitar' ? 'mod-sin' : 'mod-extra' ?>">
                                    <?= $p['accion'] === 'quitar' ? 'SIN' : 'EXTRA' ?> <?= e($p['nombre']) ?></span>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                    <p class="text-sm text-gray-400 mt-1"><?= money(($item['precio_unitario'] + array_sum(array_column($item['personalizaciones'] ?? [], 'costo'))) * $item['cantidad']) ?></p>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <form method="post" action="<?= url('/client/carrito/quitar') ?>">
                        <?= csrf_field() ?><input type="hidden" name="key" value="<?= e($item['key']) ?>">
                        <button class="text-red-400 hover:text-red-600"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                    </form>
                    <form method="post" action="<?= url('/client/carrito/actualizar') ?>" class="flex items-center gap-1">
                        <?= csrf_field() ?><input type="hidden" name="key" value="<?= e($item['key']) ?>">
                        <button name="cantidad" value="<?= $item['cantidad'] - 1 ?>" class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-stone-800 font-bold">−</button>
                        <span class="w-6 text-center font-bold text-sm"><?= $item['cantidad'] ?></span>
                        <button name="cantidad" value="<?= $item['cantidad'] + 1 ?>" class="w-7 h-7 rounded-lg bg-gray-100 dark:bg-stone-800 font-bold">+</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <form method="post" action="<?= url('/client/carrito/vaciar') ?>" onsubmit="return confirm('Vaciar todo el carrito?')">
            <?= csrf_field() ?>
            <button class="text-sm font-bold text-red-500">Vaciar carrito</button>
        </form>
    </div>

    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm h-fit">
        <h2 class="font-black text-lg mb-4">Resumen de Pago</h2>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-bold"><?= money($subtotal) ?></span></div>
            <div class="flex justify-between"><span class="text-gray-500">Envio (Tarifa Plana)</span><span class="font-bold"><?= money($envio) ?></span></div>
            <div class="flex justify-between border-t border-gray-100 dark:border-stone-800 pt-2 text-base">
                <span class="font-black">Total Final</span><span class="font-black text-brand-600"><?= money($subtotal + $envio) ?></span>
            </div>
        </div>
        <a href="<?= url('/client/checkout') ?>" class="mt-5 block w-full text-center bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl py-3">
            IR A PAGAR
        </a>
        <p class="text-[11px] text-gray-400 mt-3 flex items-center gap-1">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Pago seguro mediante pasarela encriptada SSL.
        </p>
    </div>
</div>
<?php endif; ?>
