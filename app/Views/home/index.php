<?php
/** @var array $productos  @var array $categorias */
?>

<!-- ============================ HERO ============================ -->
<section class="relative min-h-[92vh] flex items-center justify-center text-center px-4 overflow-hidden">
    <style>@keyframes copiway-pan{0%{transform:scale(1.06)}100%{transform:scale(1.16) translate(-2%,-1%)}}</style>

    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=1800&q=75&auto=format&fit=crop"
             alt="Hamburguesa artesanal Copiway"
             class="w-full h-full object-cover object-[60%_center]"
             style="animation:copiway-pan 32s ease-in-out infinite alternate">
        <div class="absolute inset-0 bg-gradient-to-b from-black/75 via-black/45 to-gray-50 dark:to-stone-900"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,0,0,.55),transparent_70%)]"></div>
    </div>

    <div class="relative z-10 max-w-3xl mx-auto pt-24">
        <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 text-white
                     text-[11px] font-bold tracking-[.22em] uppercase rounded-2xl px-4 py-2 mb-8 shadow-lg">
            <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span> Directo a tu puerta
        </span>

        <h1 class="text-[clamp(2.75rem,8vw,5rem)] font-black tracking-tight text-white leading-[1.04] mb-6
                   drop-shadow-[0_4px_18px_rgba(0,0,0,.55)]">
            Las Mejores Hamburguesas
            <span class="bg-gradient-to-r from-brand-400 to-brand-600 bg-clip-text text-transparent">de la Ciudad</span>
        </h1>

        <p class="text-lg font-medium text-gray-200 max-w-xl mx-auto mb-10 drop-shadow-[0_2px_8px_rgba(0,0,0,.6)]">
            Experimenta el autentico sabor artesanal. Cocinamos al momento y te entregamos rapido, sin filas ni intermediarios.
        </p>

        <div class="flex flex-wrap gap-4 justify-center">
            <a href="<?= url('/login') ?>"
               class="group bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-2xl px-8 py-4
                      flex items-center gap-2 shadow-lg shadow-brand-500/30 transition-all duration-300 hover:scale-105">
                Hacer Pedido
                <i data-lucide="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"></i>
            </a>
            <a href="#menu"
               class="bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 hover:border-white/50 text-white
                      font-medium rounded-2xl px-8 py-4 flex items-center gap-2 shadow-lg transition-all duration-300 hover:scale-105">
                <i data-lucide="utensils-crossed" class="w-4 h-4"></i> Ver Menu
            </a>
        </div>
    </div>
</section>

<!-- ========================= BARRA DE VALORES ========================= -->
<section class="relative z-10 bg-white dark:bg-stone-900 border-y border-gray-100 dark:border-stone-800 py-20 px-4">
    <div class="max-w-6xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <?php
        $benes = [
            ['zap', 'Entrega Rapida', 'Tu pedido caliente en tiempo record.'],
            ['leaf', 'Ingredientes Frescos', 'Seleccionados cada manana del mercado.'],
            ['flame', 'Hecho al Momento', 'Nada se prepara antes de tu orden.'],
            ['shield-check', 'Pago 100% Seguro', 'Pasarela encriptada. Cero fiar, cero riesgo.'],
        ];
        foreach ($benes as [$icon, $beneTitulo, $desc]): ?>
            <div class="bg-gray-50 dark:bg-stone-950/40 border border-gray-100 dark:border-stone-800 rounded-[32px] p-8
                        text-center shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500
                            flex items-center justify-center mb-4">
                    <i data-lucide="<?= $icon ?>" class="w-6 h-6"></i>
                </div>
                <h3 class="font-black tracking-tight text-lg mb-1 text-gray-900 dark:text-white"><?= $beneTitulo ?></h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400"><?= $desc ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================== MENU ============================== -->
<section id="menu" class="bg-gray-50 dark:bg-stone-900 border-b border-gray-100 dark:border-stone-800 py-24 px-4"
         x-data="{ cat: 'todas' }">
    <div class="max-w-6xl mx-auto text-center mb-10">
        <span class="text-brand-600 dark:text-brand-500 font-black tracking-[.22em] text-xs uppercase">Oferta Gastronomica</span>
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-gray-900 dark:text-white mt-3">Nuestro Menu</h2>
        <p class="text-gray-500 dark:text-gray-400 font-medium mt-3 max-w-xl mx-auto">
            Explora nuestra linea completa. Inicia sesion para pedir y personalizar.
        </p>
    </div>

    <div class="max-w-6xl mx-auto flex flex-wrap gap-3 justify-center mb-12">
        <button @click="cat='todas'"
                :class="cat==='todas' ? 'bg-brand-500 text-white border-brand-500 shadow-md shadow-brand-500/25' : 'bg-white dark:bg-stone-950/40 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-stone-700 hover:border-brand-500 hover:text-brand-600'"
                class="px-5 py-2.5 rounded-2xl border text-sm font-medium transition-all duration-300 hover:scale-105">Todas</button>
        <?php foreach ($categorias as $c): ?>
            <button @click="cat='<?= e($c['id_categoria']) ?>'"
                    :class="cat==='<?= e($c['id_categoria']) ?>' ? 'bg-brand-500 text-white border-brand-500 shadow-md shadow-brand-500/25' : 'bg-white dark:bg-stone-950/40 text-gray-600 dark:text-gray-300 border-gray-200 dark:border-stone-700 hover:border-brand-500 hover:text-brand-600'"
                    class="px-5 py-2.5 rounded-2xl border text-sm font-medium transition-all duration-300 hover:scale-105"><?= e($c['nombre']) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="max-w-6xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($productos as $p): ?>
            <div x-show="cat==='todas' || cat==='<?= e($p['id_categoria']) ?>'" x-transition
                 class="group bg-white dark:bg-stone-950/40 border border-gray-100 dark:border-stone-800 rounded-[32px]
                        overflow-hidden flex flex-col relative shadow-sm hover:shadow-xl transition-all duration-300">
                <?php if (!empty($p['agotado'])): ?>
                    <div class="badge-agotado"><span>AGOTADO</span></div>
                <?php endif; ?>
                <div class="h-52 bg-gray-100 dark:bg-stone-800 overflow-hidden">
                    <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105"
                         style="background-image:url('<?= e($p['imagen'] ?: 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=640&q=70') ?>')"></div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <?php if ($p['etiqueta_destacada'] !== 'ninguna'): ?>
                        <span class="self-start text-[10px] font-black tracking-wider bg-brand-500 text-white rounded-full px-2.5 py-1 mb-2">
                            <?= strtoupper(str_replace('_', ' ', $p['etiqueta_destacada'])) ?>
                        </span>
                    <?php endif; ?>
                    <h3 class="font-black tracking-tight text-gray-900 dark:text-white text-lg"><?= e($p['nombre']) ?></h3>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 flex-1"><?= e($p['descripcion']) ?></p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-brand-600 dark:text-brand-500 font-black text-xl"><?= money($p['precio']) ?></span>
                        <a href="<?= url('/login') ?>"
                           class="w-10 h-10 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center
                                  transition-all duration-300 hover:scale-105">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ============================ NOSOTROS ============================ -->
<section id="nosotros" class="max-w-6xl mx-auto px-4 py-24 grid gap-12 lg:grid-cols-2 items-center">
    <div class="relative">
        <div class="grid grid-cols-2 gap-4 [&>img:nth-child(even)]:mt-8">
            <?php foreach ([
                'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=70',
                'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&q=70',
                'https://images.unsplash.com/photo-1550547660-d9450f859349?w=400&q=70',
                'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&q=70',
            ] as $img): ?>
                <img src="<?= $img ?>" class="rounded-[28px] h-44 md:h-56 w-full object-cover shadow-lg" alt="">
            <?php endforeach; ?>
        </div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 bg-brand-500 rounded-[28px]
                    flex items-center justify-center shadow-[0_0_40px_rgba(249,115,22,.45)] border-4 border-white dark:border-stone-900">
            <i data-lucide="utensils-crossed" class="w-10 h-10 text-white"></i>
        </div>
    </div>
    <div>
        <span class="text-brand-600 dark:text-brand-500 font-black tracking-[.22em] text-xs uppercase">Nuestra Historia</span>
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-gray-900 dark:text-white mt-3 mb-4">
            Reinventando el sabor desde nuestra Dark Kitchen.
        </h2>
        <p class="text-gray-500 dark:text-gray-400 font-medium leading-relaxed mb-8">
            Somos una cocina oculta: sin salon, sin meseros, todo el foco en la calidad del producto y en llevarlo
            rapido y perfecto hasta tu casa.
        </p>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[24px] p-4 text-center shadow-sm">
                <p class="text-2xl sm:text-3xl font-black tracking-tight text-brand-500">+10K</p>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mt-1 leading-tight">Pedidos Entregados</p>
            </div>
            <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[24px] p-4 text-center shadow-sm">
                <p class="text-2xl sm:text-3xl font-black tracking-tight text-brand-500">99%</p>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mt-1 leading-tight">Clientes Felices</p>
            </div>
            <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[24px] p-4 text-center shadow-sm">
                <p class="text-2xl sm:text-3xl font-black tracking-tight text-brand-500">3</p>
                <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 mt-1 leading-tight">Anos de Experiencia</p>
            </div>
        </div>
    </div>
</section>

<!-- =========================== TESTIMONIOS =========================== -->
<section id="testimonios" class="bg-gray-50 dark:bg-stone-900 border-y border-gray-100 dark:border-stone-800 py-24 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <span class="text-brand-600 dark:text-brand-500 font-black tracking-[.22em] text-xs uppercase">Lo que dicen de nosotros</span>
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-gray-900 dark:text-white mt-3 mb-10">Clientes Satisfechos</h2>
        <div class="bg-white dark:bg-stone-950/40 border border-gray-100 dark:border-stone-800 rounded-[32px] p-10 shadow-sm">
            <div class="flex justify-center gap-1 text-brand-500 mb-5">
                <?php for ($i = 0; $i < 5; $i++): ?><i data-lucide="star" class="w-5 h-5 fill-brand-500"></i><?php endfor; ?>
            </div>
            <p class="text-xl font-medium text-gray-700 dark:text-gray-200 italic mb-8">"MUY BUENA"</p>
            <div class="flex items-center justify-center gap-3">
                <span class="w-11 h-11 rounded-2xl bg-brand-500 text-white font-black flex items-center justify-center">C</span>
                <div class="text-left">
                    <p class="font-black tracking-tight text-sm text-gray-900 dark:text-white">Cliente App</p>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-brand-600 dark:text-brand-500">Cliente verificado</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================= CONTACTO ============================= -->
<section id="contacto" class="max-w-6xl mx-auto px-4 py-24">
    <div class="text-center mb-12">
        <span class="text-brand-600 dark:text-brand-500 font-black tracking-[.22em] text-xs uppercase">Estamos para ti</span>
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-gray-900 dark:text-white mt-3">Contactanos</h2>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        <?php foreach ([
            ['map-pin', 'Direccion', 'Cocina Oculta - Centro, Neiva, Huila'],
            ['clock', 'Horario', '08:00 a 23:00'],
            ['phone', 'Telefono', 'WhatsApp disponible'],
            ['mail', 'Email', 'soporte 24/7'],
        ] as [$icon, $t, $d]): ?>
            <div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-6 text-center
                        flex flex-col items-center shadow-sm hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 flex items-center justify-center mb-3">
                    <i data-lucide="<?= $icon ?>" class="w-5 h-5"></i>
                </div>
                <h3 class="font-black tracking-tight text-gray-900 dark:text-white"><?= $t ?></h3>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1"><?= $d ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <form class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] p-8 shadow-sm space-y-4">
            <h3 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Envianos un mensaje</h3>
            <input class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-5 py-4 text-sm font-medium
                          focus:outline-none focus:border-brand-500 transition-colors" placeholder="Nombre">
            <input class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-5 py-4 text-sm font-medium
                          focus:outline-none focus:border-brand-500 transition-colors" placeholder="Correo">
            <input class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-5 py-4 text-sm font-medium
                          focus:outline-none focus:border-brand-500 transition-colors" placeholder="Telefono">
            <textarea rows="4" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-5 py-4 text-sm font-medium
                                      focus:outline-none focus:border-brand-500 transition-colors resize-none" placeholder="Mensaje"></textarea>
            <button type="button" onclick="toast('success','Mensaje enviado','Te responderemos pronto.')"
                    class="w-full bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-2xl py-4 flex items-center justify-center gap-2
                           transition-all duration-300 hover:scale-105">
                Enviar Mensaje <i data-lucide="send" class="w-4 h-4"></i>
            </button>
        </form>

        <div id="mapa-contacto"
             class="rounded-[32px] overflow-hidden border border-gray-100 dark:border-stone-800 min-h-[340px] shadow-sm z-0"></div>
        <script>
        document.addEventListener('DOMContentLoaded', () => Copiway.map('mapa-contacto', {
            markers: [{ lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Hamburguer Copiway · Cocina Oculta, Centro de Neiva' }]
        }));
        </script>
    </div>
</section>

<!-- ============================= CTA FINAL ============================= -->
<section class="px-4 pb-24">
    <div class="max-w-6xl mx-auto bg-brand-500 rounded-[32px] py-16 px-6 text-center shadow-lg shadow-brand-500/25">
        <h2 class="text-3xl sm:text-4xl font-black tracking-tight text-white mb-6">Listo para probar el autentico sabor?</h2>
        <a href="<?= url('/login') ?>"
           class="inline-flex items-center gap-2 bg-stone-900 hover:bg-stone-800 text-white font-medium rounded-2xl px-8 py-4
                  transition-all duration-300 hover:scale-105">
            Pedir Ahora <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
</section>
