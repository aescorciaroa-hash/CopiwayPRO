<?php /** @var array $pedido @var array $config */ ?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><title>Tirilla <?= e($pedido['codigo']) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<style>@media print { .no-print { display:none } } body { font-family: monospace }</style>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-xs mx-auto bg-white p-5 text-sm">
    <div class="text-center border-b-2 border-dashed pb-3 mb-3">
        <p class="font-black text-base">HAMBURGUER COPIWAY</p>
        <p class="text-xs">Dark Kitchen · Neiva, Huila</p>
        <p class="text-xs">NIT 900.000.000-0</p>
    </div>
    <p>Ticket: <b><?= e($pedido['codigo']) ?></b></p>
    <p>Fecha: <?= date('d/m/Y H:i', strtotime($pedido['fecha_hora'])) ?></p>
    <p>Cliente: <?= e($pedido['cliente_nombre']) ?></p>
    <p>Tel: <?= e($pedido['cliente_telefono']) ?></p>
    <p class="break-words">Dir: <?= e($pedido['direccion_entrega']) ?></p>
    <div class="border-t-2 border-dashed my-3"></div>
    <?php foreach ($pedido['lineas'] as $l): ?>
        <div class="mb-1">
            <div class="flex justify-between"><span><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></span><span><?= money($l['precio_unitario'] * $l['cantidad']) ?></span></div>
            <?php foreach ($l['personalizaciones'] as $p): ?>
                <p class="text-xs pl-3 <?= $p['accion_modificacion'] === 'quitar' ? 'text-red-600' : 'text-green-700' ?>">
                    <?= $p['accion_modificacion'] === 'quitar' ? 'SIN' : 'EXTRA' ?> <?= e($p['nombre']) ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    <div class="border-t-2 border-dashed my-3"></div>
    <div class="flex justify-between"><span>Subtotal</span><span><?= money($pedido['subtotal']) ?></span></div>
    <div class="flex justify-between"><span>Domicilio</span><span><?= money($pedido['costo_domicilio']) ?></span></div>
    <?php if ($pedido['descuento_cumpleanos'] > 0): ?>
        <div class="flex justify-between"><span>Desc. cumpleanos</span><span>-<?= money($pedido['descuento_cumpleanos']) ?></span></div>
    <?php endif; ?>
    <div class="flex justify-between font-black text-base"><span>TOTAL</span><span><?= money($pedido['total']) ?></span></div>
    <div class="text-center mt-4">
        <p class="text-xs">PIN DE ENTREGA</p>
        <p class="font-black text-2xl tracking-widest"><?= e($pedido['pin_entrega']) ?></p>
    </div>
    <p class="text-center text-xs mt-3">|| |||| | ||| |||| || |||| |</p>
</div>
<button onclick="window.print()" class="no-print mt-4 mx-auto block bg-orange-500 text-white font-bold rounded-xl px-6 py-2.5">Imprimir</button>
</body></html>
