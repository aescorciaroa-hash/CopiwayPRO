<?php
/** Layout del domiciliario: mobile-first, botones tactiles grandes. */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(\App\Core\Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-ink text-gray-900 dark:text-gray-100 antialiased">
<header class="sticky top-0 z-20 bg-white dark:bg-card border-b border-gray-100 dark:border-stone-800 px-4 h-16 flex items-center justify-between">
    <div class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
            <i data-lucide="navigation" class="w-5 h-5 text-white"></i>
        </span>
        <span class="font-black">Copiway<span class="text-brand-500">PRO</span></span>
    </div>
    <div class="flex items-center gap-3">
        <?php require dirname(__DIR__) . '/partials/theme-toggle.php'; ?>
        <form method="post" action="<?= url('/logout') ?>">
            <?= csrf_field() ?>
            <button class="text-red-500"><i data-lucide="log-out" class="w-5 h-5"></i></button>
        </form>
    </div>
</header>
<main class="max-w-md mx-auto p-4"><?= $content ?></main>
<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
