<?php
/** @var array $kpi @var array $ventasDia @var array $ranking @var array $recientes @var array $rango */

// Serie completa de dias del periodo (rellena con 0 los dias sin ventas)
$ventasMap = [];
foreach ($ventasDia as $d) { $ventasMap[$d['dia']] = (float) $d['total']; }
$serie = [];
$ini = new DateTime(substr($rango[0], 0, 10));
$fin = new DateTime(substr($rango[1], 0, 10));
$dias = (int) $ini->diff($fin)->days;
$paso = $dias > 45 ? 7 : 1;                    // en periodos largos agrupamos por semana
$diasEs = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
for ($c = $ini; $c <= $fin; $c->modify("+{$paso} day")) {
    $total = 0;
    for ($k = 0; $k < $paso; $k++) {
        $key = (clone $c)->modify("+{$k} day")->format('Y-m-d');
        $total += $ventasMap[$key] ?? 0;
    }
    $serie[] = [
        'total' => $total,
        'label' => $paso === 1 ? $diasEs[(int) $c->format('w')] : $c->format('d/m'),
    ];
    if (count($serie) >= 14) break;
}

$maxVenta = max(array_map(fn($d) => $d['total'], $serie ?: [['total' => 1]])) ?: 1;
// Techo "bonito" para el eje Y
$escala = (int) pow(10, max(0, strlen((string) (int) $maxVenta) - 2));
$ejeMax = (int) (ceil($maxVenta / max(1, $escala)) * $escala) ?: 1;
$fmtEje = fn($v) => $v >= 1000000 ? round($v / 1000000, 1) . 'M' : ($v >= 1000 ? round($v / 1000) . 'k' : (string) $v);

$totalRank = array_sum(array_map(fn($r) => (int) $r['unidades'], $ranking)) ?: 1;
$colores = ['#f97316', '#3b82f6', '#10b981', '#a855f7', '#f43f5e'];

// Construye los segmentos del donut
$acum = 0; $segmentos = [];
foreach ($ranking as $i => $r) {
    $pct = (int) $r['unidades'] / $totalRank;
    $segmentos[] = ['color' => $colores[$i % 5], 'desde' => $acum, 'hasta' => $acum + $pct, 'label' => $r['nombre'], 'pct' => $pct];
    $acum += $pct;
}
$topPct = $segmentos ? round($segmentos[0]['pct'] * 100) : 0;
?>
<div class="mb-8">
    <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Tablero General</h1>
    <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Resumen en tiempo real.</p>
</div>

<!-- KPIs · Bento Grid -->
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6 mb-6">
    <?php
    $tarjetas = [
        ['credit-card', 'Ventas Totales', money($kpi['ventas']), 'Periodo', 'text-emerald-500', 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400'],
        ['layout-grid', 'Ordenes Hoy', number_format($ordenesHoy), 'Hoy', 'text-brand-500', 'bg-gray-100 text-gray-500 dark:bg-stone-800 dark:text-gray-400'],
        ['trending-up', 'Ticket Promedio', money($kpi['ticket']), 'Promedio', 'text-blue-500', 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'],
        ['users', 'Empleados Activos', number_format($empleados), 'En nomina', 'text-purple-500', 'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400'],
    ];
    foreach ($tarjetas as $i => [$icon, $label, $valor, $sub, $color, $badge]):
        $featured = $i === 0;
        $span = match ($i) {
            0       => 'sm:col-span-2 sm:row-span-2 lg:col-span-2 lg:row-span-2',
            3       => 'sm:col-span-2 lg:col-span-4',
            default => 'lg:col-span-2',
        };
    ?>
        <div class="<?= $span ?> rounded-[32px] p-6 shadow-sm flex flex-col justify-between transition-all duration-300 hover:shadow-md
                    <?= $featured
                        ? 'bg-brand-500 text-white'
                        : 'bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800' ?>">
            <div class="flex items-center justify-between mb-3">
                <span class="w-11 h-11 rounded-2xl flex items-center justify-center
                             <?= $featured ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-stone-800 ' . $color ?>">
                    <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
                </span>
                <span class="text-[10px] font-black uppercase tracking-wide rounded-full px-2.5 py-1
                             <?= $featured ? 'bg-white/20 text-white' : $badge ?>"><?= $sub ?></span>
            </div>
            <div>
                <p class="text-sm font-medium <?= $featured ? 'text-white/80' : 'text-gray-500 dark:text-gray-400' ?>"><?= $label ?></p>
                <p class="<?= $featured ? 'text-4xl' : 'text-2xl' ?> font-black tracking-tight mt-0.5"><?= $valor ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="grid gap-6 lg:grid-cols-3 mb-6">
    <!-- Ventas Diarias -->
    <div class="lg:col-span-2 bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Ventas Diarias</h2>
            <form method="get" class="text-sm">
                <select name="periodo" onchange="this.form.submit()"
                        class="rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 font-medium
                               focus:outline-none focus:border-brand-500 transition-colors">
                    <?php foreach ($opciones as $k => $label): ?>
                        <option value="<?= $k ?>" <?= $periodo === $k ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <?php if (array_sum(array_map(fn($d) => $d['total'], $serie)) > 0): ?>
            <div class="flex gap-3">
                <!-- Eje Y -->
                <div class="w-10 shrink-0 flex flex-col justify-between h-56 py-1 text-[10px] font-bold text-gray-400 text-right">
                    <?php for ($i = 4; $i >= 0; $i--): ?>
                        <span><?= $fmtEje((int) round($ejeMax / 4 * $i)) ?></span>
                    <?php endfor; ?>
                </div>
                <!-- Barras + rejilla -->
                <div class="relative flex-1">
                    <div class="absolute inset-0 flex flex-col justify-between">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <span class="border-t border-dashed border-gray-100 dark:border-stone-800"></span>
                        <?php endfor; ?>
                    </div>
                    <div class="relative flex items-end gap-2 sm:gap-4 h-56 px-1">
                        <?php foreach ($serie as $d): $pct = $d['total'] > 0 ? max(2, round($d['total'] / $ejeMax * 100)) : 0; ?>
                            <div class="flex-1 flex flex-col items-center h-full justify-end group">
                                <span class="text-[10px] font-bold text-gray-500 mb-1 opacity-0 group-hover:opacity-100 transition"><?= money($d['total']) ?></span>
                                <div class="w-full bg-brand-500 group-hover:bg-brand-600 rounded-t-lg transition-all duration-300" style="height: <?= $pct ?>%"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-2">
                <div class="w-10 shrink-0"></div>
                <div class="flex-1 flex gap-2 sm:gap-4 px-1">
                    <?php foreach ($serie as $d): ?>
                        <span class="flex-1 text-center text-[11px] font-bold text-gray-400"><?= $d['label'] ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400 py-16 text-sm font-medium">Sin ventas registradas en este periodo.</p>
        <?php endif; ?>
    </div>

    <!-- Productos mas vendidos -->
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm">
        <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white mb-1">Productos mas vendidos</h2>
        <p class="text-xs text-gray-400 font-medium mb-4">Participacion por volumen</p>
        <?php if ($segmentos): ?>
            <div class="relative w-40 h-40 mx-auto mb-5">
                <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                    <?php foreach ($segmentos as $s): ?>
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="<?= $s['color'] ?>" stroke-width="4"
                                stroke-dasharray="<?= round($s['pct'] * 100, 2) ?> <?= round((1 - $s['pct']) * 100, 2) ?>"
                                stroke-dashoffset="<?= round(-$s['desde'] * 100, 2) ?>"></circle>
                    <?php endforeach; ?>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-xl font-black tracking-tight text-gray-900 dark:text-white"><?= $topPct ?>%</span>
                    <span class="text-[10px] text-gray-400 text-center px-5 leading-tight truncate max-w-full"><?= e($segmentos[0]['label']) ?></span>
                    <span class="text-[9px] font-black text-brand-500 mt-0.5">TOP #1</span>
                </div>
            </div>
            <div class="space-y-2">
                <?php foreach ($ranking as $i => $r): ?>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 min-w-0">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background: <?= $colores[$i % 5] ?>"></span>
                            <span class="truncate font-medium text-gray-600 dark:text-gray-300"><?= e($r['nombre']) ?></span>
                        </span>
                        <span class="font-bold text-gray-500 shrink-0"><?= $r['unidades'] ?>u · <?= round((int)$r['unidades'] / $totalRank * 100) ?>%</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-400 py-16 text-sm font-medium">Aun no hay ventas para rankear.</p>
        <?php endif; ?>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <!-- Ordenes recientes · tabla moderna -->
    <div class="lg:col-span-2 bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] shadow-sm overflow-hidden">
        <div class="flex items-center justify-between p-6 sm:p-8 pb-4">
            <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Ordenes Recientes</h2>
            <a href="<?= url('/admin/comandas') ?>"
               class="text-sm font-medium text-brand-600 dark:text-brand-500 flex items-center gap-1 rounded-2xl px-3 py-1.5
                      hover:bg-brand-500/10 transition-all duration-300 hover:scale-105">
                Ver todas <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[520px]">
                <thead>
                    <tr class="bg-gray-50/70 dark:bg-stone-950/40 text-left text-[11px] font-black uppercase tracking-wider text-gray-400">
                        <th class="px-6 sm:px-8 py-3.5">Codigo</th>
                        <th class="px-4 py-3.5">Cliente</th>
                        <th class="px-4 py-3.5 text-right">Total</th>
                        <th class="px-4 py-3.5">Estado</th>
                        <th class="px-6 sm:px-8 py-3.5 text-right">Ver</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                    <?php foreach ($recientes as $p): ?>
                        <?php $badge = [
                            'pendiente' => 'bg-brand-100 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400',
                            'en_preparacion' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
                            'listo' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-400',
                            'en_camino' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-400',
                            'entregado' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400',
                            'cancelado' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                        ][$p['estado']] ?? 'bg-gray-100 text-gray-700 dark:bg-stone-800 dark:text-gray-400'; ?>
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-stone-950/40 transition-colors">
                            <td class="px-6 sm:px-8 py-4 font-mono font-bold text-gray-400"><?= $pedidoModel->codigo($p) ?></td>
                            <td class="px-4 py-4 font-bold text-gray-900 dark:text-white truncate max-w-[160px]"><?= e($p['cliente_nombre']) ?></td>
                            <td class="px-4 py-4 text-right font-black tracking-tight text-gray-900 dark:text-white"><?= money($p['total']) ?></td>
                            <td class="px-4 py-4">
                                <span class="text-[10px] font-black uppercase tracking-wide rounded-full px-2.5 py-1 <?= $badge ?>"><?= str_replace('_', ' ', $p['estado']) ?></span>
                            </td>
                            <td class="px-6 sm:px-8 py-4 text-right">
                                <a href="<?= url('/admin/comandas') ?>"
                                   class="inline-flex w-9 h-9 rounded-2xl bg-gray-100 dark:bg-stone-800 text-gray-500 dark:text-gray-400
                                          items-center justify-center hover:bg-brand-500 hover:text-white transition-all duration-300 hover:scale-105">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$recientes): ?>
                        <tr><td colspan="5" class="py-12 text-center text-gray-400 text-sm font-medium">Sin ordenes todavia.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Despacho y logistica -->
    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Despacho y Logistica</h2>
            <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-500">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> En Vivo
            </span>
        </div>
        <div class="space-y-2.5 text-sm">
            <div class="flex justify-between items-center rounded-2xl bg-gray-50 dark:bg-stone-950/40 px-4 py-3">
                <span class="text-gray-500 dark:text-gray-400 font-medium">En cocina</span><span class="font-bold text-gray-900 dark:text-white"><?= $contadores['en_preparacion'] ?> pedidos</span>
            </div>
            <div class="flex justify-between items-center rounded-2xl bg-gray-50 dark:bg-stone-950/40 px-4 py-3">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Listos</span><span class="font-bold text-gray-900 dark:text-white"><?= $contadores['listo'] ?> pedidos</span>
            </div>
            <div class="flex justify-between items-center rounded-2xl bg-gray-50 dark:bg-stone-950/40 px-4 py-3">
                <span class="text-gray-500 dark:text-gray-400 font-medium">En camino</span><span class="font-bold text-gray-900 dark:text-white"><?= $contadores['en_camino'] ?> pedidos</span>
            </div>
            <div class="flex justify-between items-center rounded-2xl bg-gray-50 dark:bg-stone-950/40 px-4 py-3">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Tarifa plana</span><span class="font-black tracking-tight text-brand-600 dark:text-brand-500"><?= money(Configuracion::value('tarifa_plana_domicilio')) ?></span>
            </div>
            <div class="flex justify-between items-center rounded-2xl bg-gray-50 dark:bg-stone-950/40 px-4 py-3">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Estado del local</span>
                <span class="font-bold <?= $estadoCocina['abierta'] ? 'text-emerald-500' : 'text-red-500' ?>">
                    <?= $estadoCocina['abierta'] ? 'Abierto' : ($estadoCocina['pausa'] ? 'Pausado' : 'Cerrado') ?>
                </span>
            </div>
        </div>
        <a href="<?= url('/admin/rutas') ?>"
           class="mt-5 w-full inline-flex items-center justify-center gap-2 bg-brand-500 hover:bg-brand-600 text-white
                  rounded-2xl py-3 text-sm font-medium transition-all duration-300 hover:scale-105">
            <i data-lucide="map" class="w-4 h-4"></i> Ver Mapa de Rutas
        </a>
    </div>
</div>
