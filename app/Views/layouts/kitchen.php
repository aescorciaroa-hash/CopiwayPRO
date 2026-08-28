<?php /** Layout del KDS: tema oscuro fijo, tipografia grande, alto contraste. */ ?>
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta name="csrf-token" content="<?= e(\App\Core\Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-ink text-white antialiased">
<div class="min-h-screen flex">
    <aside class="w-56 shrink-0 bg-card border-r border-stone-800 flex flex-col p-4 sticky top-0 h-screen overflow-y-auto">
        <div class="flex items-center gap-2.5 mb-8">
            <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                <i data-lucide="chef-hat" class="w-5 h-5 text-white"></i>
            </span>
            <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
        </div>
        <a href="<?= url('/kitchen') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-brand-500 text-white text-sm font-bold mb-2">
            <i data-lucide="clipboard-list" class="w-4 h-4"></i> Pedidos
        </a>
        <div class="flex-1"></div>
        <form method="post" action="<?= url('/logout') ?>">
            <?= csrf_field() ?>
            <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-red-400 hover:bg-red-950/30">
                <i data-lucide="log-out" class="w-4 h-4"></i> Cerrar Sesión
            </button>
        </form>
    </aside>
    <main class="flex-1 min-w-0 p-6"><?= $content ?></main>
</div>
<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
