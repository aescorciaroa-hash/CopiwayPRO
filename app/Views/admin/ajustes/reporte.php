<?php
/** @var array $cierre @var array $config */
$t = $cierre['totales'];
$totalRecaudo = (float) $t['total_efectivo'] + (float) $t['total_digital'];
$pctEfectivo  = $totalRecaudo > 0 ? round($t['total_efectivo'] / $totalRecaudo * 100) : 0;
$pctDigital   = $totalRecaudo > 0 ? round($t['total_digital'] / $totalRecaudo * 100) : 0;
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Cierre de Caja · <?= e($cierre['fecha']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = { theme: { extend: {
        fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
        colors: { brand: { 50:'#fff7ed', 100:'#ffedd5', 500:'#f97316', 600:'#ea580c', 700:'#c2410c' } }
    }}}
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif }
        @media print {
            .no-print { display: none !important }
            body { background: #fff; padding: 0 }
            .paper { box-shadow: none !important; border: none !important; border-radius: 0 !important }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 sm:p-8 text-gray-900">
<div class="paper max-w-3xl mx-auto bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">

    <!-- Encabezado oficial -->
    <div class="bg-brand-500 text-white p-8 flex items-start justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center shrink-0">
                <i data-lucide="utensils-crossed" class="w-6 h-6"></i>
            </span>
            <div>
                <h1 class="text-xl font-black tracking-tight">Hamburguer Copiway</h1>
                <p class="text-[11px] font-medium text-white/80 uppercase tracking-wide">Reporte oficial de cierre de caja · Dark Kitchen</p>
            </div>
        </div>
        <div class="text-right text-xs font-medium text-white/80 shrink-0">
            <p>Fecha: <b class="text-white"><?= e($cierre['fecha']) ?></b></p>
            <p>Generado: <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>

    <div class="p-6 sm:p-10 space-y-10">

        <?php if ($cierre['activasSinEntregar'] > 0): ?>
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold rounded-2xl p-4">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                <span>Advertencia: al momento del cierre habia <?= $cierre['activasSinEntregar'] ?> orden(es) activas sin entregar.</span>
            </div>
        <?php endif; ?>

        <!-- Resumen de ingresos -->
        <section>
            <h2 class="font-black tracking-tight text-lg text-gray-900 mb-4">Resumen de Ingresos</h2>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="rounded-[24px] border border-gray-100 bg-gray-50 p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Total ventas del turno</p>
                    <p class="text-2xl font-black tracking-tight text-gray-900 mt-1"><?= money($t['total_ventas']) ?></p>
                </div>
                <div class="rounded-[24px] border border-gray-100 bg-gray-50 p-5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Total ordenes</p>
                    <p class="text-2xl font-black tracking-tight text-gray-900 mt-1"><?= (int) $t['total_ordenes'] ?></p>
                </div>
            </div>

            <!-- Efectivo vs Digital -->
            <div class="rounded-[24px] border border-gray-100 p-5">
                <div class="flex h-3 w-full overflow-hidden rounded-full bg-gray-100 mb-5">
                    <div class="bg-emerald-500" style="width: <?= $pctEfectivo ?>%"></div>
                    <div class="bg-blue-500" style="width: <?= $pctDigital ?>%"></div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i data-lucide="banknote" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Efectivo · <?= $pctEfectivo ?>%</p>
                            <p class="text-lg font-black tracking-tight text-gray-900"><?= money($t['total_efectivo']) ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </span>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Digital · <?= $pctDigital ?>%</p>
                            <p class="text-lg font-black tracking-tight text-gray-900"><?= money($t['total_digital']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Escandallo con barras de progreso -->
        <section>
            <h2 class="font-black tracking-tight text-lg text-gray-900 mb-1">Consumo de Insumos (Escandallo)</h2>
            <p class="text-xs text-gray-400 font-medium mb-4">Proporcion consumida en el turno frente al stock disponible.</p>
            <div class="space-y-4">
                <?php foreach ($cierre['escandallo'] as $e): ?>
                    <?php
                    $cons  = (float) $e['consumido'];
                    $stock = (float) $e['stock_real'];
                    $base  = $cons + $stock;
                    $pct   = $base > 0 ? round($cons / $base * 100) : 0;
                    ?>
                    <div>
                        <div class="flex items-center justify-between gap-3 text-sm mb-1.5">
                            <span class="font-bold text-gray-900"><?= e($e['nombre']) ?></span>
                            <span class="font-medium text-gray-500 text-xs text-right">
                                <?= rtrim(rtrim(number_format($e['consumido'], 2, '.', ''), '0'), '.') ?> <?= e($e['unidad_medida']) ?> consumido
                                · <?= rtrim(rtrim(number_format($e['stock_real'], 2, '.', ''), '0'), '.') ?> en stock
                            </span>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full <?= $pct >= 80 ? 'bg-red-500' : ($pct >= 50 ? 'bg-brand-500' : 'bg-emerald-500') ?>"
                                 style="width: <?= max(2, $pct) ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (!$cierre['escandallo']): ?>
                    <p class="text-sm text-gray-400 font-medium py-4">Sin ventas registradas.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Liquidacion de domiciliarios -->
        <section>
            <h2 class="font-black tracking-tight text-lg text-gray-900 mb-4">Liquidacion de Domiciliarios</h2>
            <div class="rounded-[24px] border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[480px]">
                        <thead>
                            <tr class="bg-gray-50 text-left text-[11px] font-black uppercase tracking-wider text-gray-400">
                                <th class="px-5 py-3">Domiciliario</th>
                                <th class="px-3 py-3 text-right">Base</th>
                                <th class="px-3 py-3 text-right">Recaudo</th>
                                <th class="px-5 py-3 text-right">A entregar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($cierre['liquidaciones'] as $l): ?>
                                <tr>
                                    <td class="px-5 py-3 font-bold text-gray-900"><?= e($l['nombre']) ?></td>
                                    <td class="px-3 py-3 text-right text-gray-500 font-medium"><?= money($l['base_efectivo_asignada']) ?></td>
                                    <td class="px-3 py-3 text-right text-gray-500 font-medium"><?= money($l['recaudo']) ?></td>
                                    <td class="px-5 py-3 text-right font-black tracking-tight text-brand-600"><?= money($l['total_entregar']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!$cierre['liquidaciones']): ?>
                                <tr><td colspan="4" class="px-5 py-4 text-gray-400 font-medium">Sin entregas en efectivo.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- CTA alta prioridad -->
        <button onclick="window.print()"
                class="no-print w-full bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-2xl py-4 flex items-center justify-center gap-2
                       shadow-lg shadow-brand-500/30 transition-all duration-300 hover:scale-[1.02]">
            <i data-lucide="download" class="w-5 h-5"></i> Descargar Archivo PDF (Imprimir)
        </button>
    </div>
</div>
<script>lucide.createIcons()</script>
</body>
</html>
