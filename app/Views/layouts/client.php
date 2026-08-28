<?php
require_once dirname(__DIR__) . '/../Models/Carrito.php';
require_once dirname(__DIR__) . '/../Models/Configuracion.php';

$configModelLayout = new Configuracion();
$carritoModelLayout = new Carrito();

$estado = $configModelLayout->estadoCocina();
$nav = [
    ['/client',            'utensils-crossed', 'Explorar Menú'],
    ['/client/creador',     'plus-circle',      'Creador Interactivo'],
    ['/client/carrito',     'shopping-cart',    'Carrito de Pedidos'],
    ['/client/ordenes',     'package-search',   'Órdenes Activas'],
    ['/client/historial',   'history',          'Historial y Recompras'],
    ['/client/perfil',      'user-cog',         'Mi Perfil'],
];
$current = current_path();
$cartCount = $carritoModelLayout->cantidad();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-ink text-gray-900 dark:text-gray-100 antialiased" x-data="{ sidebar: false }">
<div class="min-h-screen flex">

    <aside class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-40 w-64 h-screen bg-white dark:bg-card border-r border-gray-100 dark:border-stone-800 flex flex-col transition-transform lg:translate-x-0 shrink-0"
           :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 flex items-center gap-2.5 px-5 border-b border-gray-100 dark:border-stone-800">
            <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                <i data-lucide="utensils-crossed" class="w-5 h-5 text-white"></i>
            </span>
            <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
        </div>
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <?php foreach ($nav as [$path, $icon, $label]):
                $act = $path === '/client' ? ($current === '/client') : str_starts_with($current, $path); ?>
                <a href="<?= url($path) ?>"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition
                          <?= $act ? 'bg-brand-500 text-white' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-stone-800' ?>">
                    <i data-lucide="<?= $icon ?>" style="width:18px;height:18px"></i>
                    <span class="flex-1"><?= $label ?></span>
                    <?php if ($path === '/client/carrito' && $cartCount): ?>
                        <span class="bg-brand-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <form method="post" action="<?= url('/logout') ?>" class="p-3 border-t border-gray-100 dark:border-stone-800">
            <?= csrf_field() ?>
            <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30">
                <i data-lucide="log-out" class="w-4 h-4"></i> Cerrar Sesión
            </button>
        </form>
    </aside>
    <div x-show="sidebar" @click="sidebar=false" class="fixed inset-0 bg-black/40 z-30 lg:hidden" x-cloak></div>

    <div class="flex-1 min-w-0 flex flex-col">
        <header class="h-16 bg-white dark:bg-card border-b border-gray-100 dark:border-stone-800 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-20">
            <button @click="sidebar=true" class="lg:hidden text-gray-500"><i data-lucide="menu" class="w-6 h-6"></i></button>
            <div></div>
            <div class="flex items-center gap-4">
                <span class="hidden sm:flex items-center gap-2 text-xs font-bold rounded-full px-3 py-1.5
                    <?= $estado['abierta'] ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30' : 'bg-red-50 text-red-600 dark:bg-red-950/30' ?>">
                    <span class="w-2 h-2 rounded-full <?= $estado['abierta'] ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                    <?= $estado['abierta'] ? 'Cocina Abierta' : 'Cocina Cerrada' ?> | <?= $estado['apertura'] ?> - <?= $estado['cierre'] ?>
                </span>
                <?php require dirname(__DIR__) . '/partials/theme-toggle.php'; ?>
                <span class="w-8 h-8 rounded-full bg-brand-500 text-white text-sm font-bold flex items-center justify-center">
                    <?= e(strtoupper(substr($_auth['nombre'] ?? 'C', 0, 1))) ?>
                </span>
            </div>
        </header>
        <main class="flex-1 p-4 sm:p-8"><?= $content ?></main>
    </div>
</div>

<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
