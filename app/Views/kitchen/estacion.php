<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cocina (KDS)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { colors: { brand: { 500:'#f97316', 600:'#ea580c' }, ink:'#151515', card:'#1a1a1a' } } } }</script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-ink text-white min-h-screen flex items-center justify-center p-6">
<div class="w-full max-w-sm text-center">
    <div class="w-16 h-16 rounded-2xl bg-brand-500 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="chef-hat" class="w-8 h-8"></i>
    </div>
    <h1 class="text-2xl font-black mb-1">Panel de Cocina (KDS)</h1>
    <p class="text-gray-400 text-sm mb-6">Ingresa el PIN de estacion asignado por el administrador.</p>

    <?php foreach (($_flash ?? \App\Core\Session::pullFlash()) as $f): ?>
        <p class="mb-4 rounded-xl bg-red-950/40 text-red-300 text-sm font-bold p-3"><?= e($f['message'] ?: $f['title']) ?></p>
    <?php endforeach; ?>

    <form method="post" action="<?= url('/kitchen/estacion') ?>" class="space-y-3">
        <?= csrf_field() ?>
        <input name="usuario" placeholder="Usuario" class="w-full rounded-xl bg-card border border-stone-700 px-4 py-3 text-sm text-center">
        <input name="pin" inputmode="numeric" placeholder="PIN de estacion" autofocus
               class="w-full rounded-xl bg-card border border-stone-700 px-4 py-3 text-lg text-center tracking-widest">
        <div class="flex gap-3">
            <a href="<?= url('/login') ?>" class="flex-1 rounded-xl border border-stone-700 py-3 text-sm font-bold">Salir</a>
            <button class="flex-1 rounded-xl bg-brand-500 hover:bg-brand-600 py-3 text-sm font-bold">Entrar</button>
        </div>
    </form>
</div>
<script>lucide.createIcons();</script>
</body>
</html>
