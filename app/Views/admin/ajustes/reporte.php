<?php
/** @var array $cierre @var array $config */
$t = $cierre['totales'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Cierre de Caja · <?= e($cierre['fecha']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@media print { .no-print { display: none } } body { font-family: system-ui, sans-serif }</style>
</head>
<body class="bg-gray-100 p-6">
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-8">
    <div class="flex items-center justify-between border-b-2 border-orange-500 pb-4 mb-6">
        <div>
            <h1 class="text-xl font-black">Hamburguer Copiway</h1>
            <p class="text-sm text-gray-500">Reporte Oficial de Cierre de Caja — Dark Kitchen</p>
        </div>
        <div class="text-right text-sm text-gray-500">
            <p>Fecha: <b><?= e($cierre['fecha']) ?></b></p>
            <p>Generado: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <?php if ($cierre['activasSinEntregar'] > 0): ?>
        <p class="bg-amber-50 text-amber-700 text-xs font-bold rounded-lg p-3 mb-6">
            Advertencia: al momento del cierre habia <?= $cierre['activasSinEntregar'] ?> orden(es) activas sin entregar.
        </p>
    <?php endif; ?>

    <h2 class="font-black text-sm uppercase text-gray-400 mb-2">Resumen de Ingresos</h2>
    <table class="w-full text-sm mb-6">
        <tr class="border-b"><td class="py-2">Total ventas del turno</td><td class="py-2 text-right font-bold"><?= money($t['total_ventas']) ?></td></tr>
        <tr class="border-b"><td class="py-2">Total ordenes</td><td class="py-2 text-right font-bold"><?= (int) $t['total_ordenes'] ?></td></tr>
        <tr class="border-b"><td class="py-2">Recaudo en efectivo</td><td class="py-2 text-right font-bold"><?= money($t['total_efectivo']) ?></td></tr>
        <tr class="border-b"><td class="py-2">Recaudo digital</td><td class="py-2 text-right font-bold"><?= money($t['total_digital']) ?></td></tr>
    </table>

    <h2 class="font-black text-sm uppercase text-gray-400 mb-2">Consumo de Insumos (Escandallo)</h2>
    <table class="w-full text-sm mb-6">
        <thead><tr class="text-left text-xs text-gray-400"><th class="py-1">Insumo</th><th class="text-right">Consumido</th><th class="text-right">Stock actual</th></tr></thead>
        <tbody>
        <?php foreach ($cierre['escandallo'] as $e): ?>
            <tr class="border-b">
                <td class="py-1.5"><?= e($e['nombre']) ?></td>
                <td class="py-1.5 text-right"><?= rtrim(rtrim(number_format($e['consumido'], 2, '.', ''), '0'), '.') ?> <?= e($e['unidad_medida']) ?></td>
                <td class="py-1.5 text-right text-gray-500"><?= rtrim(rtrim(number_format($e['stock_real'], 2, '.', ''), '0'), '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$cierre['escandallo']): ?><tr><td colspan="3" class="py-2 text-gray-400">Sin ventas registradas.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <h2 class="font-black text-sm uppercase text-gray-400 mb-2">Liquidacion de Domiciliarios</h2>
    <table class="w-full text-sm mb-8">
        <thead><tr class="text-left text-xs text-gray-400"><th class="py-1">Domiciliario</th><th class="text-right">Base</th><th class="text-right">Recaudo</th><th class="text-right">A entregar</th></tr></thead>
        <tbody>
        <?php foreach ($cierre['liquidaciones'] as $l): ?>
            <tr class="border-b">
                <td class="py-1.5"><?= e($l['nombre']) ?></td>
                <td class="py-1.5 text-right"><?= money($l['base_efectivo_asignada']) ?></td>
                <td class="py-1.5 text-right"><?= money($l['recaudo']) ?></td>
                <td class="py-1.5 text-right font-bold"><?= money($l['total_entregar']) ?></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$cierre['liquidaciones']): ?><tr><td colspan="4" class="py-2 text-gray-400">Sin entregas en efectivo.</td></tr><?php endif; ?>
        </tbody>
    </table>

    <button onclick="window.print()" class="no-print w-full bg-orange-500 text-white font-bold rounded-xl py-3">
        Descargar Archivo PDF (Imprimir)
    </button>
</div>
</body>
</html>
