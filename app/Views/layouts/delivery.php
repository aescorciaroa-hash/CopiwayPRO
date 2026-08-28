<?php
/** Layout del domiciliario: diseño interactivo en pantalla completa con mapa táctico. */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="<?= e(Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased font-sans overflow-hidden">
<div class="h-screen w-screen flex overflow-hidden relative">
    <?= $content ?>
</div>
<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>

