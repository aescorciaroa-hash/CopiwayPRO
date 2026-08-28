<?php /** Boton reutilizable para alternar modo claro / oscuro. */ ?>
<button type="button" onclick="Copiway.toggleTheme()"
        class="w-9 h-9 rounded-full border border-gray-200 dark:border-stone-700 flex items-center justify-center
               text-gray-500 dark:text-gray-300 hover:text-brand-500 hover:border-brand-400 transition"
        title="Cambiar tema">
    <i data-lucide="moon" class="w-4 h-4 dark:hidden"></i>
    <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
</button>
