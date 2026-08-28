<?php
$inp = "w-full rounded-xl bg-gray-100 dark:bg-black/40 border border-transparent focus:border-brand-500
        focus:ring-2 focus:ring-brand-500/30 outline-none pl-11 pr-4 py-3 text-sm transition";
$err = fn(string $k) => error($k)
    ? '<p class="text-red-500 text-xs mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>' . e(error($k)) . '</p>'
    : '';
$ring = fn(string $k) => error($k) ? 'ring-2 ring-red-500/40 border-red-500' : '';
?>

<!-- Formulario (izquierda) -->
<div class="p-8 sm:p-12 flex flex-col justify-center order-2 lg:order-1">
    <a href="<?= url('/') ?>" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-brand-600 mb-6">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver al inicio
    </a>

    <h1 class="text-3xl font-black mb-1">Crear Cuenta</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-6">Registrate para pedir en Copiway.</p>

    <form method="post" action="<?= url('/register') ?>" class="space-y-4"
          x-data="{ ok: <?= old('habeas_data') ? 'true' : 'false' ?> }">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm font-bold mb-1.5">Nombres y Apellidos</label>
            <div class="relative">
                <i data-lucide="user" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="nombre" value="<?= e(old('nombre')) ?>" placeholder="Ej. Juan Carlos Perez"
                       class="<?= $inp ?> <?= $ring('nombre') ?>">
            </div>
            <?= $err('nombre') ?>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1.5">Correo Electronico</label>
            <div class="relative">
                <i data-lucide="mail" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="email" name="correo" value="<?= e(old('correo')) ?>" placeholder="correo@ejemplo.com"
                       class="<?= $inp ?> <?= $ring('correo') ?>">
            </div>
            <?= $err('correo') ?>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1.5">Telefono</label>
                <div class="relative">
                    <i data-lucide="phone" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input name="telefono" value="<?= e(old('telefono')) ?>" placeholder="Ej. 300 123 4567"
                           class="<?= $inp ?> <?= $ring('telefono') ?>">
                </div>
                <?= $err('telefono') ?>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1.5">Fecha de Nacimiento</label>
                <div class="relative">
                    <i data-lucide="calendar" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="date" name="fecha_nacimiento" value="<?= e(old('fecha_nacimiento')) ?>"
                           class="<?= $inp ?> <?= $ring('fecha_nacimiento') ?>">
                </div>
                <?= $err('fecha_nacimiento') ?>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1.5">Contrasena</label>
                <div class="relative">
                    <i data-lucide="lock" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="contrasena" placeholder="Minimo 6 caracteres" class="<?= $inp ?> <?= $ring('contrasena') ?>">
                </div>
                <?= $err('contrasena') ?>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1.5">Confirmar</label>
                <div class="relative">
                    <i data-lucide="check-circle" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="contrasena_confirmation" placeholder="Repite la contrasena" class="<?= $inp ?>">
                </div>
            </div>
        </div>

        <label class="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-400">
            <input type="checkbox" name="habeas_data" value="1" x-model="ok" <?= old('habeas_data') ? 'checked' : '' ?>
                   class="mt-0.5 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
            <span>Acepto los <b>terminos de servicio</b> y la <b>politica de tratamiento de datos</b>.</span>
        </label>
        <?= $err('habeas_data') ?>

        <button :disabled="!ok" :class="ok ? 'bg-brand-500 hover:bg-brand-600 shadow-lg shadow-brand-500/25' : 'bg-gray-300 dark:bg-stone-700 cursor-not-allowed'"
                class="w-full text-white font-bold rounded-2xl py-4 flex items-center justify-center gap-2 transition">
            Completar Registro <i data-lucide="user-plus" class="w-4 h-4"></i>
        </button>
    </form>

    <p class="text-center text-sm text-gray-400 mt-6">
        Ya tienes una cuenta registrada?
        <a href="<?= url('/login') ?>" class="text-gray-900 dark:text-white font-black hover:text-brand-600">Inicia sesion aqui</a>
    </p>
</div>

<!-- Panel de beneficios (derecha) -->
<div class="hidden lg:flex flex-col items-center justify-center p-10 text-white text-center bg-cover bg-center order-1 lg:order-2"
     style="background-image:linear-gradient(rgba(124,45,18,.55),rgba(15,15,15,.9)),url('https://images.unsplash.com/photo-1552566626-52f8b828add9?w=1000&q=75')">
    <span class="w-16 h-16 rounded-full bg-brand-500/90 flex items-center justify-center mb-6">
        <i data-lucide="users" class="w-8 h-8"></i>
    </span>
    <h2 class="text-4xl font-black leading-tight mb-3">Sabor que <span class="text-brand-500">Conecta!</span></h2>
    <p class="text-gray-300 max-w-xs mb-8">Pide tus favoritas en segundos y sigue tu orden hasta tu puerta.</p>
    <div class="rounded-2xl bg-white/10 backdrop-blur border border-white/10 p-5 space-y-3 text-left w-full max-w-xs">
        <?php foreach (['Pide rapido y facil', 'Monitorea tu pedido', 'Ofertas exclusivas'] as $b): ?>
            <p class="flex items-center gap-3 font-bold text-sm">
                <span class="w-6 h-6 rounded-full bg-brand-500 flex items-center justify-center shrink-0"><i data-lucide="check" class="w-3.5 h-3.5"></i></span>
                <?= $b ?>
            </p>
        <?php endforeach; ?>
    </div>
</div>
