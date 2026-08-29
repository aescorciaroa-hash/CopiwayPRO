<?php /** @var array $pedido @var array $config */ ?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sticker <?= e($pedido['codigo']) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { colors: { brand: { 500:'#f97316', 600:'#ea580c' } } } } }</script>
<style>
    body { font-family: ui-monospace, 'SF Mono', Menlo, monospace }
    @media print { .no-print { display: none !important } body { background: #fff; padding: 0 } .ticket { box-shadow: none !important; border: none !important } }
</style>
</head>
<body class="bg-gray-100 p-6">
<div class="ticket max-w-xs mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-[13px] text-gray-900">
    <div class="text-center border-b-2 border-dashed border-gray-300 pb-3 mb-3">
        <p class="font-black text-base tracking-tight">HAMBURGUER COPIWAY</p>
        <p class="text-[11px] text-gray-500">Dark Kitchen · Neiva, Huila</p>
        <p class="text-[11px] text-gray-500">NIT 900.000.000-0</p>
    </div>
    <div class="space-y-0.5">
        <p>Ticket: <b><?= e($pedido['codigo']) ?></b></p>
        <p>Fecha: <?= date('d/m/Y H:i', strtotime($pedido['fecha_hora'])) ?></p>
        <p>Cliente: <?= e($pedido['cliente_nombre']) ?></p>
        <p>Tel: <?= e($pedido['cliente_telefono']) ?></p>
        <p class="break-words">Dir: <?= e($pedido['direccion_entrega']) ?></p>
    </div>
    <div class="border-t-2 border-dashed border-gray-300 my-3"></div>
    <?php foreach ($pedido['lineas'] as $l): ?>
        <div class="mb-2">
            <div class="flex justify-between font-bold"><span><?= (int) $l['cantidad'] ?>x <?= e($l['nombre']) ?></span><span><?= money($l['precio_unitario'] * $l['cantidad']) ?></span></div>
            <?php foreach ($l['personalizaciones'] as $p): ?>
                <p class="text-[12px] font-black pl-3 <?= $p['accion_modificacion'] === 'quitar' ? 'text-red-600' : 'text-green-700' ?>">
                    <?= $p['accion_modificacion'] === 'quitar' ? 'SIN' : 'EXTRA' ?> <?= e($p['nombre']) ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    <div class="border-t-2 border-dashed border-gray-300 my-3"></div>
    <div class="flex justify-between"><span>Subtotal</span><span><?= money($pedido['subtotal']) ?></span></div>
    <div class="flex justify-between"><span>Domicilio</span><span><?= money($pedido['costo_domicilio']) ?></span></div>
    <?php if ($pedido['descuento_cumpleanos'] > 0): ?>
        <div class="flex justify-between text-green-700"><span>Desc. cumpleanos</span><span>-<?= money($pedido['descuento_cumpleanos']) ?></span></div>
    <?php endif; ?>
    <div class="flex justify-between font-black text-base mt-1 pt-1 border-t border-gray-200"><span>TOTAL</span><span><?= money($pedido['total']) ?></span></div>
    <div class="text-center mt-4 rounded-xl bg-gray-100 py-3">
        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">PIN de entrega</p>
        <p class="font-black text-2xl tracking-[0.3em] text-gray-900"><?= e($pedido['pin_entrega']) ?></p>
    </div>
    <p class="text-center text-[11px] text-gray-400 mt-3 tracking-widest">|| |||| | ||| |||| || |||| |</p>
</div>
<button onclick="window.print()"
        class="no-print mt-5 mx-auto flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-2xl px-8 py-3 shadow-lg shadow-brand-500/30 transition-all duration-300 hover:scale-105">
    Imprimir Sticker
</button>
</body></html>
