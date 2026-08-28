<?php
/** @var array $productos  @var array $categorias */
?>

<!-- HERO -->
<section class="relative min-h-[92vh] flex items-center justify-center text-center px-4
                bg-cover bg-center"
         style="background-image:linear-gradient(rgba(15,15,15,.55),rgba(15,15,15,.75)),url('https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=1600&q=70')">
    <div class="max-w-3xl mx-auto pt-24">
        <span class="inline-flex items-center gap-2 bg-stone-800/80 text-white text-xs font-bold tracking-widest rounded-full px-4 py-2 mb-8">
            <span class="w-2 h-2 rounded-full bg-brand-500"></span> DIRECTO A TU PUERTA
        </span>
        <h1 class="text-5xl sm:text-7xl font-black text-white leading-[1.05] mb-6">
            Las Mejores Hamburguesas <span class="text-brand-500">de la Ciudad</span>
        </h1>
        <p class="text-lg text-gray-200 max-w-xl mx-auto mb-10">
            Experimenta el autentico sabor artesanal. Cocinamos al momento y te entregamos rapido, sin filas ni intermediarios.
        </p>
        <div class="flex flex-wrap gap-4 justify-center">
            <a href="<?= url('/login') ?>"
               class="bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl px-7 py-4 flex items-center gap-2 transition">
                Hacer Pedido <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
            <a href="#menu"
               class="bg-white/10 hover:bg-white/20 border border-white/30 text-white font-bold rounded-xl px-7 py-4 flex items-center gap-2 transition">
                <i data-lucide="utensils-crossed" class="w-4 h-4"></i> Ver Menu
            </a>
        </div>
    </div>
</section>

<!-- BENEFICIOS -->
<section class="max-w-6xl mx-auto px-4 py-20 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <?php
    $benes = [
        ['zap', 'Entrega Rapida', 'Tu pedido caliente en tiempo record.'],
        ['leaf', 'Ingredientes Frescos', 'Seleccionados cada manana del mercado.'],
        ['flame', 'Hecho al Momento', 'Nada se prepara antes de tu orden.'],
        ['shield-check', 'Pago 100% Seguro', 'Pasarela encriptada. Cero fiar, cero riesgo.'],
    ];
    foreach ($benes as [$icon, $beneTitulo, $desc]): ?>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 dark:bg-brand-500/15 text-brand-600 flex items-center justify-center mb-4">
                <i data-lucide="<?= $icon ?>" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-lg mb-1"><?= $beneTitulo ?></h3>
            <p class="text-sm text-gray-500 dark:text-gray-400"><?= $desc ?></p>
        </div>
    <?php endforeach; ?>
</section>

<!-- MENU -->
<section id="menu" class="bg-stone-950 py-20 px-4" x-data="{ cat: 'todas' }">
    <div class="max-w-6xl mx-auto text-center mb-10">
        <span class="text-brand-500 font-bold tracking-widest text-sm">OFERTA GASTRONOMICA</span>
        <h2 class="text-4xl font-black text-white mt-2">Nuestro Menu</h2>
        <p class="text-gray-400 mt-3">Explora nuestra linea completa. Inicia sesion para pedir y personalizar.</p>
    </div>

    <div class="max-w-6xl mx-auto flex flex-wrap gap-3 justify-center mb-10">
        <button @click="cat='todas'"
                :class="cat==='todas' ? 'bg-brand-500 text-white border-brand-500' : 'text-gray-300 border-stone-700'"
                class="px-4 py-2 rounded-full border text-sm font-bold transition">Todas</button>
        <?php foreach ($categorias as $c): ?>
            <button @click="cat='<?= e($c['id_categoria']) ?>'"
                    :class="cat==='<?= e($c['id_categoria']) ?>' ? 'bg-brand-500 text-white border-brand-500' : 'text-gray-300 border-stone-700'"
                    class="px-4 py-2 rounded-full border text-sm font-bold transition"><?= e($c['nombre']) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="max-w-6xl mx-auto grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($productos as $p): ?>
            <div x-show="cat==='todas' || cat==='<?= e($p['id_categoria']) ?>'" x-transition
                 class="bg-card border border-stone-800 rounded-3xl overflow-hidden flex flex-col relative">
                <?php if (!empty($p['agotado'])): ?>
                    <div class="badge-agotado"><span>AGOTADO</span></div>
                <?php endif; ?>
                <div class="h-48 bg-stone-800 bg-cover bg-center"
                     style="background-image:url('<?= e($p['imagen'] ?: 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=640&q=70') ?>')"></div>
                <div class="p-5 flex-1 flex flex-col">
                    <?php if ($p['etiqueta_destacada'] !== 'ninguna'): ?>
                        <span class="self-start text-[10px] font-black tracking-wider bg-brand-500 text-white rounded-full px-2.5 py-1 mb-2">
                            <?= strtoupper(str_replace('_', ' ', $p['etiqueta_destacada'])) ?>
                        </span>
                    <?php endif; ?>
                    <h3 class="font-bold text-white text-lg"><?= e($p['nombre']) ?></h3>
                    <p class="text-sm text-gray-400 mt-1 line-clamp-2 flex-1"><?= e($p['descripcion']) ?></p>
                    <div class="flex items-center justify-between mt-4">
                        <span class="text-brand-500 font-black text-lg"><?= money($p['precio']) ?></span>
                        <a href="<?= url('/login') ?>" class="w-9 h-9 rounded-full bg-brand-500 hover:bg-brand-600 text-white flex items-center justify-center">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- NOSOTROS -->
<section id="nosotros" class="max-w-6xl mx-auto px-4 py-20 grid gap-12 lg:grid-cols-2 items-center">
    <div class="grid grid-cols-2 gap-4">
        <?php foreach ([
            'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&q=70',
            'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=400&q=70',
            'https://images.unsplash.com/photo-1550547660-d9450f859349?w=400&q=70',
            'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=400&q=70',
        ] as $img): ?>
            <img src="<?= $img ?>" class="rounded-2xl h-40 w-full object-cover" alt="">
        <?php endforeach; ?>
    </div>
    <div>
        <span class="text-brand-500 font-bold tracking-widest text-sm">NUESTRA HISTORIA</span>
        <h2 class="text-4xl font-black mt-2 mb-4">Reinventando el sabor desde nuestra Dark Kitchen.</h2>
        <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
            Somos una cocina oculta: sin salon, sin meseros, todo el foco en la calidad del producto y en llevarlo
            rapido y perfecto hasta tu casa.
        </p>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div><p class="text-3xl font-black text-brand-500">+10K</p><p class="text-xs text-gray-500">Pedidos Entregados</p></div>
            <div><p class="text-3xl font-black text-brand-500">99%</p><p class="text-xs text-gray-500">Clientes Felices</p></div>
            <div><p class="text-3xl font-black text-brand-500">3</p><p class="text-xs text-gray-500">Anos de Experiencia</p></div>
        </div>
    </div>
</section>

<!-- TESTIMONIOS -->
<section id="testimonios" class="bg-gray-100 dark:bg-stone-950 py-20 px-4">
    <div class="max-w-3xl mx-auto text-center">
        <span class="text-brand-500 font-bold tracking-widest text-sm">LO QUE DICEN DE NOSOTROS</span>
        <h2 class="text-4xl font-black mt-2 mb-10">Clientes Satisfechos</h2>
        <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-8 shadow-sm">
            <div class="flex justify-center gap-1 text-brand-500 mb-4">
                <?php for ($i = 0; $i < 5; $i++): ?><i data-lucide="star" class="w-5 h-5 fill-brand-500"></i><?php endfor; ?>
            </div>
            <p class="text-xl font-bold mb-6">"MUY BUENA"</p>
            <div class="flex items-center justify-center gap-3">
                <span class="w-10 h-10 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center">C</span>
                <div class="text-left">
                    <p class="font-bold text-sm">Cliente App</p>
                    <p class="text-xs text-gray-500">CLIENTE VERIFICADO</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CONTACTO -->
<section id="contacto" class="max-w-6xl mx-auto px-4 py-20">
    <div class="text-center mb-12">
        <span class="text-brand-500 font-bold tracking-widest text-sm">ESTAMOS PARA TI</span>
        <h2 class="text-4xl font-black mt-2">Contactanos</h2>
    </div>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        <?php foreach ([
            ['map-pin', 'Direccion', 'Cocina Oculta - Centro, Neiva, Huila'],
            ['clock', 'Horario', '08:00 a 23:00'],
            ['phone', 'Telefono', 'WhatsApp disponible'],
            ['mail', 'Email', 'soporte 24/7'],
        ] as [$icon, $t, $d]): ?>
            <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm">
                <i data-lucide="<?= $icon ?>" class="w-6 h-6 text-brand-500 mb-3"></i>
                <h3 class="font-bold"><?= $t ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?= $d ?></p>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
        <form class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="font-bold text-lg">Envianos un mensaje</h3>
            <input class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-3 text-sm" placeholder="Nombre">
            <input class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-3 text-sm" placeholder="Correo">
            <input class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-3 text-sm" placeholder="Telefono">
            <textarea rows="4" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-3 text-sm" placeholder="Mensaje"></textarea>
            <button type="button" onclick="toast('success','Mensaje enviado','Te responderemos pronto.')"
                    class="w-full bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-xl py-3">Enviar Mensaje</button>
        </form>
        <div id="mapa-contacto" class="rounded-3xl border border-gray-100 dark:border-stone-800 min-h-[320px] z-0"></div>
        <script>
        document.addEventListener('DOMContentLoaded', () => Copiway.map('mapa-contacto', {
            markers: [{ lat: COPIWAY_SEDE[0], lng: COPIWAY_SEDE[1], label: 'Hamburguer Copiway · Cocina Oculta, Centro de Neiva' }]
        }));
        </script>
    </div>
</section>

<!-- CTA FINAL -->
<section class="bg-brand-500 py-16 px-4 text-center">
    <h2 class="text-3xl sm:text-4xl font-black text-white mb-6">Listo para probar el autentico sabor?</h2>
    <a href="<?= url('/login') ?>" class="inline-block bg-stone-900 hover:bg-stone-800 text-white font-bold rounded-xl px-8 py-4">
        Pedir Ahora
    </a>
</section>
