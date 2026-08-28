<?php
$inp = "w-full rounded-xl bg-gray-100 dark:bg-black/40 border border-transparent focus:border-brand-500
        focus:ring-2 focus:ring-brand-500/30 outline-none pl-11 pr-4 py-3.5 text-sm transition";
?>

<!-- Panel de marca (izquierda) -->
<div class="hidden lg:flex flex-col justify-between p-10 text-white bg-cover bg-center"
     style="background-image:linear-gradient(rgba(15,15,15,.72),rgba(15,15,15,.9)),url('https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=1000&q=75')">
    <a href="<?= url('/') ?>" class="flex items-center gap-2.5">
        <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center"><i data-lucide="utensils-crossed" class="w-5 h-5"></i></span>
        <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
    </a>
    <div>
        <h2 class="text-4xl font-black leading-tight mb-4">Recupera tu <span class="text-brand-500">acceso</span></h2>
        <p class="text-gray-300 max-w-sm">Te enviaremos un codigo de verificacion para que crees una nueva contrasena en dos pasos.</p>
    </div>
    <span></span>
</div>

<!-- Formulario (derecha) -->
<div class="p-8 sm:p-12 flex flex-col justify-center">
    <a href="<?= url('/login') ?>" class="flex items-center gap-2 text-sm font-medium text-gray-400 hover:text-brand-600 mb-8">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Volver a iniciar sesion
    </a>

    <h1 class="text-3xl font-black mb-1">Recuperar Contrasena</h1>
    <p class="text-gray-500 dark:text-gray-400 mb-8">Ingresa el correo o celular registrado en tu cuenta.</p>

    <form method="post" action="<?= url('/forgot-password') ?>" class="space-y-5">
        <?= csrf_field() ?>
        <div>
            <label class="block text-sm font-bold mb-1.5">Correo o Celular</label>
            <div class="relative">
                <i data-lucide="at-sign" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input name="identificador" required placeholder="correo@ejemplo.com" class="<?= $inp ?>">
            </div>
        </div>
        <button class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-2xl py-4 shadow-lg shadow-brand-500/25
                       flex items-center justify-center gap-2 transition">
            Enviar Codigo <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    </form>
</div>
