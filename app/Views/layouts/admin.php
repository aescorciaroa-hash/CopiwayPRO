<?php
/** Layout del panel de administrador: sidebar fijo + contenido. */
$nav = [
    ['/admin',          'layout-dashboard', 'Tablero Analítico'],
    ['/admin/comandas',  'utensils',         'Comandas Activas'],
    ['/admin/rutas',     'map',              'Rutas & Zonas'],
    ['/admin/menu',      'book-open',         'Gestión de Menú'],
    ['/admin/inventario','package',           'Inventario Express'],
    ['/admin/personal',  'users',             'Equipo y Personal'],
    ['/admin/clientes',  'contact',           'Directorio Clientes'],
    ['/admin/ajustes',   'settings',          'Ajustes y Caja'],
];
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$active = fn($path) => $path === '/admin'
    ? ($current === '/admin' || $current === '/admin/')
    : str_starts_with($current, $path);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(\App\Core\Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-ink text-gray-900 dark:text-gray-100 antialiased" x-data="{ sidebar: false }">
<div class="min-h-screen flex">

    <!-- Sidebar -->
    <aside class="fixed lg:sticky lg:top-0 inset-y-0 left-0 z-40 w-64 h-screen bg-white dark:bg-card border-r border-gray-100 dark:border-stone-800
                  flex flex-col transition-transform lg:translate-x-0 shrink-0"
           :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-16 flex items-center gap-2.5 px-5 border-b border-gray-100 dark:border-stone-800">
            <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                <i data-lucide="utensils-crossed" class="w-5 h-5 text-white"></i>
            </span>
            <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
        </div>
        <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
            <?php foreach ($nav as [$path, $icon, $label]): ?>
                <a href="<?= url($path) ?>"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition
                          <?= $active($path)
                              ? 'bg-brand-500 text-white'
                              : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-stone-800' ?>">
                    <i data-lucide="<?= $icon ?>" class="w-4.5 h-4.5" style="width:18px;height:18px"></i>
                    <?= $label ?>
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

    <!-- Contenido -->
    <div class="flex-1 min-w-0 flex flex-col">
        <header class="h-16 bg-white dark:bg-card border-b border-gray-100 dark:border-stone-800 flex items-center justify-between px-4 sm:px-8 sticky top-0 z-20">
            <button @click="sidebar=true" class="lg:hidden text-gray-500"><i data-lucide="menu" class="w-6 h-6"></i></button>
            <div class="hidden lg:block"></div>
            <div class="flex items-center gap-4">
                <?php require dirname(__DIR__) . '/partials/admin-notificaciones.php'; ?>
                <?php require dirname(__DIR__) . '/partials/theme-toggle.php'; ?>
                <div class="flex items-center gap-2 pl-3 border-l border-gray-200 dark:border-stone-700">
                    <span class="w-8 h-8 rounded-full bg-brand-500 text-white text-sm font-bold flex items-center justify-center">
                        <?= e(strtoupper(substr($_auth['nombre'] ?? 'A', 0, 1))) ?>
                    </span>
                    <span class="hidden sm:block text-sm font-bold"><?= e($_auth['nombre'] ?? 'Administrador') ?></span>
                </div>
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
