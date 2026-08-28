<?php /** Layout de autenticacion: tarjeta central sobre fondo suave. */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-50 via-white to-brand-100 dark:from-ink dark:via-ink dark:to-black
             text-gray-900 dark:text-gray-100 antialiased flex items-center justify-center p-4 sm:p-8">

<div class="fixed top-5 right-5 z-10"><?php require dirname(__DIR__) . '/partials/theme-toggle.php'; ?></div>

<div class="w-full max-w-5xl bg-white dark:bg-card rounded-[32px] shadow-xl overflow-hidden
            grid lg:grid-cols-2 min-h-[640px]">
    <?= $content ?>
</div>

<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
