<?php /** @var array $cliente */ ?>
<div x-data="{ pwdOpen: false }">
<h1 class="text-3xl font-black mb-6">Gestion de Cuenta</h1>

<div class="rounded-3xl bg-brand-500 text-white p-6 mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm text-white/80">Fidelidad y Recompensas</p>
        <p class="text-3xl font-black"><?= number_format($cliente['puntos_fidelidad'], 0, ',', '.') ?> pts</p>
    </div>
    <i data-lucide="award" class="w-10 h-10"></i>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <form method="post" action="<?= url('/client/perfil') ?>"
          class="lg:col-span-2 bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
        <?= csrf_field() ?>
        <h2 class="font-black text-lg">Informacion Personal</h2>
        <div>
            <label class="block text-sm font-bold mb-1.5">Nombre</label>
            <input name="nombre" value="<?= e($cliente['nombre']) ?>" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
        </div>
        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1.5">Correo</label>
                <input value="<?= e($cliente['correo']) ?>" disabled class="w-full rounded-xl border border-gray-200 dark:border-stone-700 bg-gray-50 dark:bg-stone-800 px-4 py-2.5 text-sm text-gray-400">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1.5">Telefono</label>
                <input name="telefono" value="<?= e($cliente['telefono']) ?>" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            </div>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1.5">Direccion Predeterminada</label>
            <input name="direccion" value="<?= e($cliente['direccion']) ?>" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1.5">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" value="<?= e($cliente['fecha_nacimiento']) ?>" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <p class="text-xs text-gray-400 mt-1">Configura tu fecha para activar tu 15% de descuento en tu cumpleanos.</p>
        </div>
        <div class="flex justify-end gap-3">
            <button type="reset" class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Descartar</button>
            <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar Cambios</button>
        </div>
    </form>

    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm h-fit">
        <h2 class="font-black text-lg mb-3">Seguridad</h2>
        <button @click="pwdOpen = true" class="w-full rounded-xl bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 py-2.5 text-sm font-bold">
            Cambiar Contrasena
        </button>
    </div>
</div>

<!-- Modal cambiar contrasena -->
<div x-show="pwdOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="pwdOpen = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-sm">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-lg font-black">Cambiar Contrasena</h2>
            <button @click="pwdOpen = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="post" action="<?= url('/client/perfil/password') ?>" class="p-6 space-y-3">
            <?= csrf_field() ?>
            <input type="password" name="actual" required placeholder="Contrasena actual" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <input type="password" name="nueva" required placeholder="Nueva contrasena" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <input type="password" name="confirmar" required placeholder="Confirmar nueva contrasena" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <div class="flex justify-end gap-3 pt-1">
                <button type="button" @click="pwdOpen = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-4 py-2 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 text-sm font-bold">Actualizar</button>
            </div>
        </form>
    </div>
</div>
</div>
