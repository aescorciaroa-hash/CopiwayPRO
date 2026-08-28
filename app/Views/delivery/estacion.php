<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Domiciliario · CopiwayPRO</title>
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
        <i data-lucide="navigation" class="w-8 h-8"></i>
    </div>

    <!-- Título y Subtítulo -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Copiway<span class="text-[#ff6600]">PRO</span></h1>
        <p class="text-slate-500 font-semibold text-sm mt-0.5">Panel de Domiciliario</p>
        <p class="text-slate-400 text-xs mt-1.5">Segundo paso: ingresa el PIN de la estación para desbloquear el panel.</p>
    </div>

    <?php foreach (($_flash ?? Session::pullFlash()) as $f): ?>
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200/60 text-red-600 text-sm font-bold p-3.5 text-center">
            <?= e($f['message'] ?: $f['title']) ?>
        </div>
    <?php endforeach; ?>

    <!-- Formulario -->
    <form method="post" action="<?= url('/delivery/estacion') ?>" class="space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-left text-xs font-bold text-slate-700 mb-1.5">Usuario</label>
            <input type="text" name="usuario" placeholder="Ej: domicilio" autofocus required
                   class="w-full bg-[#f8fafc] border border-slate-200/80 rounded-2xl px-4 py-3.5 text-slate-800 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition">
        </div>

        <div>
            <label class="block text-left text-xs font-bold text-slate-700 mb-1.5">PIN de estación</label>
            <input type="password" name="pin" inputmode="numeric" placeholder="4 dígitos" required
                   class="w-full bg-[#f8fafc] border border-slate-200/80 rounded-2xl px-4 py-3.5 text-slate-800 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#ff6600]/20 focus:border-[#ff6600] transition">
        </div>

        <button type="submit" class="w-full bg-[#ff6600] hover:bg-[#e65c00] text-white font-bold rounded-2xl py-3.5 shadow-lg shadow-orange-500/30 text-sm transition">
            Entrar
        </button>
    </form>

    <form method="post" action="<?= url('/logout') ?>" class="mt-3">
        <?= csrf_field() ?>
        <button type="submit" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl py-3.5 flex items-center justify-center gap-1.5 text-sm transition">
            <i data-lucide="log-out" class="w-4 h-4"></i> Cerrar sesión
        </button>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>
