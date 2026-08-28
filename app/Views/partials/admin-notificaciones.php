<?php
global $conn;
$resN = $conn->query("SELECT p.id_pedido, p.fecha_hora, p.canal_origen, cl.nombre FROM PEDIDO p JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente WHERE p.fecha_hora >= NOW() - INTERVAL 12 HOUR ORDER BY p.fecha_hora DESC LIMIT 6");
$nuevos = $resN ? $resN->fetch_all(MYSQLI_ASSOC) : [];

$resC = $conn->query("SELECT nombre, cantidad_stock, unidad_medida FROM INGREDIENTE WHERE cantidad_stock <= umbral_minimo LIMIT 6");
$criticos = $resC ? $resC->fetch_all(MYSQLI_ASSOC) : [];

$total = count($nuevos) + count($criticos);
?>
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative w-9 h-9 rounded-full border border-gray-200 dark:border-stone-700 flex items-center justify-center text-gray-500 dark:text-gray-300 hover:text-brand-500">
        <i data-lucide="bell" class="w-4 h-4"></i>
        <?php if ($total): ?>
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-brand-500 text-white text-[10px] font-bold flex items-center justify-center"><?= $total ?></span>
        <?php endif; ?>
    </button>
    <div x-show="open" @click.outside="open = false" x-transition x-cloak
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-2xl shadow-xl p-2 z-50">
        <p class="px-3 py-2 text-sm font-black">Notificaciones</p>
        <div class="max-h-80 overflow-y-auto space-y-1">
            <?php foreach ($nuevos as $n): ?>
                <div class="flex gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-stone-800">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-brand-500 mt-0.5"></i>
                    <div class="text-sm">
                        <p class="font-bold">Nuevo pedido de <?= e($n['nombre']) ?></p>
                        <p class="text-xs text-gray-400"><?= date('h:i a', strtotime($n['fecha_hora'])) ?> · <?= e($n['canal_origen']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php foreach ($criticos as $c): ?>
                <div class="flex gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 dark:hover:bg-stone-800">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-red-500 mt-0.5"></i>
                    <div class="text-sm">
                        <p class="font-bold">Inventario critico: <?= e($c['nombre']) ?></p>
                        <p class="text-xs text-gray-400">Quedan <?= rtrim(rtrim(number_format($c['cantidad_stock'],2,'.',''), '0'),'.') ?> <?= e($c['unidad_medida']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$total): ?>
                <p class="px-3 py-6 text-center text-sm text-gray-400">Sin novedades por ahora.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
