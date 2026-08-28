<?php /** Layout del KDS: interfaz limpia, tipografia clara y alto contraste. */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-[#f8fafc] dark:bg-[#0d0d0d] text-slate-800 dark:text-slate-200 antialiased font-sans">
<div class="min-h-screen flex">
    <!-- Sidebar Izquierdo -->
    <aside class="w-64 shrink-0 bg-white dark:bg-[#161616] border-r border-slate-200/70 dark:border-stone-800 flex flex-col p-5 sticky top-0 h-screen overflow-y-auto">
        <!-- Logo Header -->
        <div class="flex items-center gap-3 mb-8">
            <span class="w-10 h-10 rounded-2xl bg-[#ff6600] shadow-md shadow-orange-500/30 flex items-center justify-center text-white shrink-0">
                <i data-lucide="chef-hat" class="w-6 h-6"></i>
            </span>
            <span class="font-black text-xl text-slate-900 tracking-tight">Copiway<span class="text-[#ff6600]">PRO</span></span>
        </div>

        <!-- Opción Activa: Pedidos -->
        <a href="<?= url('/kitchen') ?>" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-[#ff6600] text-white font-bold text-sm shadow-lg shadow-orange-500/25 mb-4">
            <i data-lucide="file-text" class="w-5 h-5"></i> Pedidos
        </a>

        <!-- Sección: Inventario Crítico (RF-60) -->
        <div x-data="{ open: true }" class="mb-4">
            <button @click="open = !open"
                    class="w-full bg-red-50/60 border border-red-200/70 rounded-2xl p-3.5 text-xs font-bold text-red-600 flex items-center justify-between hover:bg-red-50 transition">
                <span class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                    INVENTARIO CRÍTICO<?= !empty($criticos) ? ' (' . count($criticos) . ')' : '' ?>
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-red-400"></i>
            </button>
            <div x-show="open" class="mt-2 space-y-1.5">
                <?php foreach (($criticos ?? []) as $c): ?>
                    <div class="bg-white rounded-xl p-2.5 border border-red-100 flex items-center justify-between text-xs shadow-sm">
                        <span class="truncate font-semibold text-slate-700"><?= e($c['nombre']) ?></span>
                        <span class="font-bold bg-red-100 text-red-700 rounded-lg px-2 py-0.5">
                            <?= rtrim(rtrim(number_format((float) $c['cantidad_stock'], 2, '.', ''), '0'), '.') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($criticos)): ?>
                    <p class="text-[11px] text-slate-400 px-1">Todo el inventario está bien.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sección: Resumen de Preparación por lotes (RF-59) -->
        <div class="bg-amber-50/50 border border-amber-200/60 rounded-2xl p-4 text-xs mb-6">
            <div class="flex items-center gap-2 font-bold text-amber-700 uppercase tracking-wider mb-2.5">
                <i data-lucide="chef-hat" class="w-4 h-4"></i> Resumen de Preparación
            </div>
            <div class="space-y-1.5">
                <?php foreach (($resumen ?? []) as $nombre => $cant): ?>
                    <div class="bg-white rounded-xl p-3 border border-amber-100 flex items-center justify-between shadow-sm">
                        <span class="truncate font-semibold text-slate-700"><?= e($nombre) ?></span>
                        <span class="font-bold bg-amber-100 text-amber-800 rounded-lg px-2 py-0.5 shrink-0 ml-2">x<?= (int) $cant ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($resumen)): ?>
                    <p class="text-[11px] text-slate-400 px-1">Sin pedidos por preparar.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="flex-1"></div>

        <!-- Sidebar Footer -->
        <div class="space-y-1 pt-4 border-t border-slate-100">
            <button onclick="Copiway.toggleTheme()" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-stone-800 transition">
                <i data-lucide="moon" class="w-5 h-5 text-slate-500 dark:hidden"></i>
                <i data-lucide="sun" class="w-5 h-5 text-amber-400 hidden dark:block"></i>
                <span class="dark:hidden">Modo Oscuro</span><span class="hidden dark:inline">Modo Claro</span>
            </button>

            <form method="post" action="<?= url('/logout') ?>">
                <?= csrf_field() ?>
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-red-500 hover:text-red-700 hover:bg-red-50/50 transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- Área de contenido principal -->
    <main class="flex-1 min-w-0 p-6 md:p-8"><?= $content ?></main>
</div>

<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
