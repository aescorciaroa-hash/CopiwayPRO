<?php /** Layout generico de reserva. */ ?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta name="csrf-token" content="<?= e(\App\Core\Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-ink text-gray-900 dark:text-gray-100 antialiased">
    <?= $content ?>
    <?php require dirname(__DIR__) . '/partials/toast.php'; ?>
    <script src="<?= asset('js/app.js') ?>"></script>
    <?php clear_errors(); ?>
</body>
</html>
