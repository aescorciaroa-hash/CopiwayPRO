<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Cocina (KDS) · CopiwayPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            500: '#ff6600',
                            600: '#e65c00'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-4 font-sans antialiased text-slate-800">

<div class="w-full max-w-md bg-white rounded-[32px] p-8 sm:p-10 shadow-2xl shadow-slate-200/60 border border-slate-100/80">
    <!-- Icono Header -->
    <div class="w-16 h-16 rounded-2xl bg-[#ff6600] flex items-center justify-center mx-auto mb-4 text-white shadow-lg shadow-orange-500/30">
        <i data-lucide="chef-hat" class="w-8 h-8"></i>
    </div>

    <!-- Título y Subtítulo -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Copiway<span class="text-[#ff6600]">PRO</span></h1>
        <p class="text-slate-500 font-semibold text-sm mt-0.5">Panel de Cocina (KDS)</p>
    </div>

    <?php foreach (($_flash ?? Session::pullFlash()) as $f): ?>
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200/60 text-red-600 text-sm font-bold p-3.5 text-center">
            <?= e($f['message'] ?: $f['title']) ?>
        </div>
    <?php endforeach; ?>

    <!-- Formulario -->
    <form method="post" action="<?= url('/kitchen/estacion') ?>" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-left text-xs font-bold text-slate-700 mb-1.5">Usuario</label>
            <input type="text" name="usuario" placeholder="Ej: cocina" autofocus required
                   class="w-full bg-[#f8fafc] border border-slate-200/80 rounded-2xl px-4 py-3.5 text-slate-800 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition">
        </div>

        <div>
            <label class="block text-left text-xs font-bold text-slate-700 mb-1.5">Contraseña</label>
            <input type="password" name="pin" placeholder="••••••••" required
                   class="w-full bg-[#f8fafc] border border-slate-200/80 rounded-2xl px-4 py-3.5 text-slate-800 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition">
        </div>

        <div class="flex gap-3 pt-2">
            <a href="<?= url('/login') ?>" class="w-1/2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl py-3.5 flex items-center justify-center gap-1.5 text-sm transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Salir
            </a>
            <button type="submit" class="w-1/2 bg-[#ff6600] hover:bg-[#e65c00] text-white font-bold rounded-2xl py-3.5 shadow-lg shadow-orange-500/30 text-sm transition">
                Entrar
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
