<?php
$inputBase = "w-full rounded-xl bg-gray-100 dark:bg-black/40 border border-transparent focus:border-brand-500
              focus:ring-2 focus:ring-brand-500/30 outline-none pl-11 pr-4 py-3.5 text-sm transition";
?>

<!-- Panel de marca (izquierda) -->
<div class="hidden lg:flex flex-col justify-between p-10 text-white bg-cover bg-center relative"
     style="background-image:linear-gradient(rgba(15,15,15,.72),rgba(15,15,15,.9)),url('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1000&q=75')">
    <a href="<?= url('/') ?>" class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
            <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
        </span>
        <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
    </a>
    <div>
        <h2 class="text-4xl font-black leading-tight mb-4">El Corazon de tu <span class="text-brand-500">Cocina</span></h2>
        <p class="text-gray-300 max-w-sm">Todo lo que necesitas para que tus hamburguesas lleguen perfectas. Controla comandas, organiza despachos y haz crecer tu Dark Kitchen sin estres.</p>
    </div>
    <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/10 p-4 flex items-center gap-3">
        <span class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center shrink-0"><i data-lucide="lock" class="w-5 h-5"></i></span>
        <div>
            <p class="font-bold text-sm">Conexion Segura</p>
            <p class="text-xs text-gray-300">Tus datos y los de tus clientes protegidos.</p>
        </div>
    </div>
</div>

<!-- Formulario (derecha) -->
<div class="p-8 sm:p-12 flex flex-col justify-center">
    <a href="<?= url('/') ?>" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-brand-600 mb-8">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver al inicio
    </a>

    <h1 class="text-3xl font-black mb-1">Iniciar Sesion</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8">Ingresa tus credenciales para continuar</p>

    <form method="post" action="<?= url('/login') ?>" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm font-bold mb-1.5">Correo Electronico</label>
            <div class="relative">
                <i data-lucide="mail" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="email" name="correo" value="<?= e(old('correo')) ?>" autofocus placeholder="ejemplo@correo.com"
                       class="<?= $inputBase ?> <?= error('correo') ? 'ring-2 ring-red-500/40 border-red-500' : '' ?>">
            </div>
            <?php if (error('correo')): ?>
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i><?= e(error('correo')) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="block text-sm font-bold">Contrasena</label>
                <a href="<?= url('/forgot-password') ?>" class="text-sm font-bold text-brand-600 hover:underline">Olvidaste tu contrasena?</a>
            </div>
            <div class="relative" x-data="{ show: false }">
                <i data-lucide="lock" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input :type="show ? 'text' : 'password'" name="contrasena" placeholder="••••••••"
                       class="<?= $inputBase ?> pr-11 <?= error('contrasena') ? 'ring-2 ring-red-500/40 border-red-500' : '' ?>">
                <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i data-lucide="eye" class="w-4 h-4" x-show="!show"></i>
                    <i data-lucide="eye-off" class="w-4 h-4" x-show="show" x-cloak></i>
                </button>
            </div>
            <?php if (error('contrasena')): ?>
                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i><?= e(error('contrasena')) ?></p>
            <?php endif; ?>
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <input type="checkbox" name="recordar" class="rounded border-gray-300 text-brand-500 focus:ring-brand-500">
            Recordar mi sesion en este equipo
        </label>

        <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-2xl py-4 shadow-lg shadow-brand-500/25
                       flex items-center justify-center gap-2 transition">
            Entrar al Sistema <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
    </form>

    <p class="text-center text-sm text-gray-400 mt-8">
        Aun no tienes una cuenta?<br class="sm:hidden">
        <a href="<?= url('/register') ?>" class="text-gray-900 dark:text-white font-black hover:text-brand-600 inline-flex items-center gap-1">
            Registrate como cliente <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </p>
</div>
