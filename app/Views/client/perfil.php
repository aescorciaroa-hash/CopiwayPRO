<?php /** @var array $cliente */ ?>
<div x-data="{ pwdOpen: false }">
<h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white mb-6">Gestion de Cuenta</h1>

<!-- Puntos Copiway -->
<div class="rounded-[32px] bg-brand-500 text-white p-8 mb-6 flex items-center justify-between shadow-lg shadow-brand-500/25">
    <div>
        <p class="text-xs font-bold uppercase tracking-widest text-white/80 mb-2">Fidelidad y Recompensas</p>
        <p class="text-6xl sm:text-7xl font-black tracking-tighter leading-none">
            <?= number_format($cliente['puntos_fidelidad'], 0, ',', '.') ?><span class="text-2xl font-medium tracking-normal text-white/70 ml-2">pts</span>
        </p>
    </div>
    <span class="w-24 h-24 rounded-full bg-white flex items-center justify-center shrink-0 shadow-inner">
        <i data-lucide="award" class="w-12 h-12 text-brand-500"></i>
    </span>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <form method="post" action="<?= url('/client/perfil') ?>"
          class="lg:col-span-2 bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm space-y-5">
        <?= csrf_field() ?>
        <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Informacion Personal</h2>
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre</label>
            <input name="nombre" value="<?= e($cliente['nombre']) ?>"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Correo</label>
                <input value="<?= e($cliente['correo']) ?>" disabled
                       class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 bg-gray-50 dark:bg-stone-800 px-4 py-2.5 text-sm font-medium text-gray-400">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Telefono</label>
                <input name="telefono" value="<?= e($cliente['telefono']) ?>"
                       class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Direccion Predeterminada</label>
            <input name="direccion" value="<?= e($cliente['direccion']) ?>"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="<?= e($cliente['fecha_nacimiento']) ?>"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
            <div class="mt-3 flex items-center gap-3 rounded-2xl bg-brand-500/10 border border-brand-500/20 p-3.5 text-sm font-medium text-brand-700 dark:text-brand-400">
                <i data-lucide="gift" class="w-5 h-5 shrink-0"></i>
                Configura tu fecha para activar tu 15% de descuento en tu cumpleanos.
            </div>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <button type="reset" class="rounded-2xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold hover:bg-gray-50 dark:hover:bg-stone-800 transition-colors">Descartar</button>
            <button class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-medium transition-all duration-300 hover:scale-105">Guardar Cambios</button>
        </div>
    </form>

    <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 sm:p-8 shadow-sm h-fit">
        <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white mb-2">Seguridad</h2>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Actualiza tu contrasena cuando lo necesites.</p>
        <button @click="pwdOpen = true"
                class="w-full rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 hover:bg-brand-500 hover:text-white py-3 text-sm font-medium transition-colors">
            Cambiar Contrasena
        </button>
    </div>
</div>

<!-- Modal cambiar contrasena -->
<div x-show="pwdOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div @click.outside="pwdOpen = false" class="bg-white dark:bg-stone-900 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-800 w-full max-w-sm">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-lg font-black tracking-tight text-gray-900 dark:text-white">Cambiar Contrasena</h2>
            <button @click="pwdOpen = false" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <form method="post" action="<?= url('/client/perfil/password') ?>" class="p-6 space-y-3">
            <?= csrf_field() ?>
            <input type="password" name="actual" required placeholder="Contrasena actual"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
            <input type="password" name="nueva" required placeholder="Nueva contrasena"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
            <input type="password" name="confirmar" required placeholder="Confirmar nueva contrasena"
                   class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-colors">
            <div class="flex justify-end gap-3 pt-1">
                <button type="button" @click="pwdOpen = false" class="rounded-2xl border border-gray-200 dark:border-stone-700 px-4 py-2 text-sm font-bold hover:bg-gray-50 dark:hover:bg-stone-800 transition-colors">Cancelar</button>
                <button class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 text-sm font-medium transition-all duration-300 hover:scale-105">Actualizar</button>
            </div>
        </form>
    </div>
</div>
</div>
