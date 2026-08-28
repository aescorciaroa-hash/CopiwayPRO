<?php /** Layout para la landing y paginas publicas. */ ?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta name="csrf-token" content="<?= e(Session::csrf()) ?>">
    <?php require dirname(__DIR__) . '/partials/head.php'; ?>
</head>
<body class="bg-gray-50 dark:bg-ink text-gray-900 dark:text-gray-100 antialiased">

<header class="fixed top-3 inset-x-0 z-50 px-4">
    <div class="max-w-6xl mx-auto bg-stone-900/95 backdrop-blur border border-stone-800 rounded-full
                px-4 sm:px-6 py-3 flex items-center justify-between text-white shadow-lg">
        <a href="<?= url('/') ?>" class="flex items-center gap-2.5 shrink-0">
            <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                <i data-lucide="utensils-crossed" class="w-5 h-5 text-white"></i>
            </span>
            <span class="font-black text-lg tracking-tight">Copiway<span class="text-brand-500">PRO</span></span>
        </a>
        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-300">
            <a href="#menu" class="hover:text-white">Menu</a>
            <a href="#nosotros" class="hover:text-white">Nosotros</a>
            <a href="#testimonios" class="hover:text-white">Testimonios</a>
            <a href="#contacto" class="hover:text-white">Contacto</a>
        </nav>
        <div class="flex items-center gap-3">
            <button type="button" onclick="Copiway.toggleTheme()"
                    class="w-9 h-9 rounded-full border border-stone-700 flex items-center justify-center text-gray-300 hover:text-brand-500">
                <i data-lucide="moon" class="w-4 h-4 dark:hidden"></i>
                <i data-lucide="sun" class="w-4 h-4 hidden dark:block"></i>
            </button>
            <a href="<?= url('/login') ?>"
               class="bg-brand-500 hover:bg-brand-600 text-white font-bold text-sm rounded-full px-5 py-2.5 flex items-center gap-2 transition">
                Ingresar <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</header>

<main><?= $content ?></main>

<footer class="bg-stone-950 text-gray-400 pt-16 pb-8 px-4">
    <div class="max-w-6xl mx-auto grid gap-10 md:grid-cols-4">
        <div>
            <div class="flex items-center gap-2.5 text-white mb-4">
                <span class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center">
                    <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                </span>
                <span class="font-black text-lg">Copiway<span class="text-brand-500">PRO</span></span>
            </div>
            <p class="text-sm leading-relaxed">Dark Kitchen de hamburguesas artesanales. Directo de nuestra cocina a tu puerta, sin intermediarios.</p>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3">Compania</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#nosotros" class="hover:text-white">Nosotros</a></li>
                <li><a href="#menu" class="hover:text-white">Menu</a></li>
                <li><a href="#contacto" class="hover:text-white">Contacto</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3">Legal</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">Politica de Privacidad (Habeas Data)</a></li>
                <li><a href="#" class="hover:text-white">Terminos de Servicio</a></li>
                <li><a href="#" class="hover:text-white">Politica de Cookies</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold mb-3">Suscribete</h4>
            <form class="flex gap-2">
                <input type="email" placeholder="Tu correo"
                       class="flex-1 bg-stone-900 border border-stone-800 rounded-lg px-3 py-2 text-sm text-white">
                <button class="bg-brand-500 hover:bg-brand-600 text-white rounded-lg px-3">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
            <div class="flex gap-3 mt-4">
                <a href="#" class="hover:text-white"><i data-lucide="instagram" class="w-5 h-5"></i></a>
                <a href="#" class="hover:text-white"><i data-lucide="facebook" class="w-5 h-5"></i></a>
                <a href="#" class="hover:text-white"><i data-lucide="twitter" class="w-5 h-5"></i></a>
            </div>
        </div>
    </div>
    <p class="text-center text-xs text-gray-600 mt-12">© <?= date('Y') ?> Hamburguer Copiway · Neiva, Huila</p>
</footer>

<?php require dirname(__DIR__) . '/partials/toast.php'; ?>
<script src="<?= asset('js/app.js') ?>"></script>
<?php clear_errors(); ?>
</body>
</html>
