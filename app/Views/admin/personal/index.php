<?php /** @var array $empleados @var int $activos */ ?>
<div x-data="personalPage()">
<div x-data="{ nuevoOpen: false }" @keydown.escape.window="nuevoOpen = false">

<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">Colaboradores</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium mt-1"><?= $activos ?> activo(s)</p>
    </div>
    <button @click="nuevoOpen = true"
            class="inline-flex items-center gap-2 rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-3 text-sm font-medium
                   shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Nuevo Colaborador
    </button>
</div>

<!-- Tabla moderna de personal -->
<div class="bg-white dark:bg-stone-900 border border-gray-100 dark:border-stone-800 rounded-[32px] shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[640px]">
            <thead>
                <tr class="bg-gray-50/70 dark:bg-stone-950/40 text-left text-[11px] font-black uppercase tracking-wider text-gray-400">
                    <th class="px-6 sm:px-8 py-4">Colaborador</th>
                    <th class="px-4 py-4">Rol</th>
                    <th class="px-4 py-4">Estado</th>
                    <th class="px-6 sm:px-8 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                <?php foreach ($empleados as $emp): ?>
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-stone-950/40 transition-colors">
                        <td class="px-6 sm:px-8 py-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-11 h-11 rounded-2xl bg-brand-500 text-white font-black flex items-center justify-center shrink-0">
                                    <?= e(strtoupper(substr($emp['nombre'], 0, 1))) ?>
                                </span>
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900 dark:text-white truncate"><?= e($emp['nombre']) ?></p>
                                    <p class="text-xs text-gray-400 truncate font-medium">
                                        <?= e($emp['correo']) ?> · <?= e($emp['telefono']) ?>
                                        <?php if ($emp['rol'] === 'domiciliario' && $emp['placa']): ?> · <?= e($emp['placa']) ?><?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-wide rounded-full px-3 py-1
                                         <?= $emp['rol'] === 'cocina'
                                             ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400'
                                             : 'bg-brand-100 text-brand-700 dark:bg-brand-500/15 dark:text-brand-400' ?>">
                                <i data-lucide="<?= $emp['rol'] === 'cocina' ? 'chef-hat' : 'bike' ?>" class="w-3.5 h-3.5"></i>
                                <?= $emp['rol'] === 'cocina' ? 'Ayudante de cocina' : 'Domiciliario' ?>
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-wide rounded-full px-3 py-1.5
                                         <?= $emp['activo']
                                             ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400'
                                             : 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400' ?>">
                                <span class="relative flex h-2 w-2">
                                    <span class="absolute inline-flex h-full w-full rounded-full opacity-75 animate-ping
                                                 <?= $emp['activo'] ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 <?= $emp['activo'] ? 'bg-emerald-500' : 'bg-red-500' ?>"></span>
                                </span>
                                <?= $emp['activo'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td class="px-6 sm:px-8 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editar('<?= $emp['rol'] ?>', '<?= e($emp['id']) ?>')"
                                        class="w-9 h-9 rounded-2xl bg-gray-100 dark:bg-stone-800 text-gray-500 dark:text-gray-400 flex items-center justify-center
                                               hover:bg-brand-500 hover:text-white transition-all duration-300 hover:scale-105" title="Editar">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <?php if ($emp['activo']): ?>
                                    <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/baja') ?>"
                                          onsubmit="return confirm('Revocar el acceso a &quot;<?= e($emp['nombre']) ?>&quot;? Se preservara su historial.')">
                                        <?= csrf_field() ?>
                                        <button class="rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 hover:bg-amber-100 dark:hover:bg-amber-950/50 px-3.5 py-2 text-xs font-bold transition-colors">Dar de baja</button>
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/reactivar') ?>">
                                        <?= csrf_field() ?>
                                        <button class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 hover:bg-emerald-100 dark:hover:bg-emerald-950/50 px-3.5 py-2 text-xs font-bold transition-colors">Reactivar</button>
                                    </form>
                                    <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/eliminar') ?>"
                                          onsubmit="return confirm('Eliminar por completo el registro de &quot;<?= e($emp['nombre']) ?>&quot;? Esta accion borrara sus credenciales permanentemente.')">
                                        <?= csrf_field() ?>
                                        <button class="rounded-2xl bg-red-50 dark:bg-red-950/30 text-red-500 hover:bg-red-100 dark:hover:bg-red-950/50 px-3.5 py-2 text-xs font-bold transition-colors">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$empleados): ?>
                    <tr><td colspan="4" class="px-6 py-16 text-center text-gray-400 text-sm font-medium">Aun no has registrado empleados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal lateral: Nuevo Colaborador -->
<div x-show="nuevoOpen" x-cloak class="fixed inset-0 z-50">
    <div x-show="nuevoOpen" x-transition.opacity @click="nuevoOpen = false" class="absolute inset-0 bg-black/50"></div>
    <div x-show="nuevoOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="absolute right-0 top-0 h-full w-full max-w-md bg-white dark:bg-stone-900 border-l border-gray-100 dark:border-stone-800
                shadow-2xl overflow-y-auto rounded-l-[32px]">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-stone-800 sticky top-0 bg-white dark:bg-stone-900 z-10">
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </span>
                <h2 class="font-black tracking-tight text-lg text-gray-900 dark:text-white">Nuevo Colaborador</h2>
            </div>
            <button @click="nuevoOpen = false"
                    class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form method="post" action="<?= url('/admin/personal') ?>" class="p-6 space-y-3" x-data="{ rol: 'cocina', pwd: '' }">
            <?= csrf_field() ?>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre completo</label>
                <input name="nombre" required placeholder="Nombre completo"
                       class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input name="correo" type="email" required placeholder="Email"
                       class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Celular</label>
                <input name="telefono" required placeholder="Celular"
                       class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Contrasena provisoria</label>
                <div class="flex gap-2">
                    <input name="contrasena" x-model="pwd" required placeholder="Genera un PIN automatico"
                           class="flex-1 rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                    <button type="button" @click="pwd = Math.random().toString(36).slice(-8)"
                            class="rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 px-3.5 font-bold flex items-center hover:bg-brand-500 hover:text-white transition-colors" title="Generar PIN aleatorio">
                        <i data-lucide="dice-5" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Rol</label>
                <select name="rol" x-model="rol"
                        class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="cocina">Ayudante de cocina</option>
                    <option value="domiciliario">Domiciliario</option>
                </select>
            </div>
            <template x-if="rol === 'cocina'">
                <select name="turno" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                    <option value="manana">Manana</option><option value="tarde">Tarde</option>
                    <option value="noche">Noche</option><option value="mixto" selected>Mixto</option>
                </select>
            </template>
            <template x-if="rol === 'domiciliario'">
                <div class="space-y-3">
                    <select name="tipo_vehiculo" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                        <option value="moto">Moto</option><option value="bicicleta">Bicicleta</option>
                        <option value="carro">Carro</option><option value="a_pie">A pie</option>
                    </select>
                    <input name="placa" placeholder="Placa"
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                    <input name="base_efectivo_asignada" type="number" min="0" placeholder="Base efectivo asignada"
                           class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors">
                </div>
            </template>
            <button class="w-full rounded-2xl bg-brand-500 hover:bg-brand-600 text-white py-3 text-sm font-medium transition-all duration-300 hover:scale-105 mt-2">
                Crear Colaborador
            </button>
        </form>
    </div>
</div>

</div>

<!-- Modal editar empleado -->
<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="open = false" class="bg-white dark:bg-stone-900 rounded-[32px] shadow-2xl border border-gray-100 dark:border-stone-800 w-full max-w-lg">
        <div class="flex items-center justify-between p-6 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black tracking-tight text-gray-900 dark:text-white">Editar Colaborador</h2>
            <button @click="open = false" class="w-9 h-9 rounded-full bg-gray-100 dark:bg-stone-800 text-gray-400 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
        <form :action="accion" method="post" class="p-6 space-y-3">
            <?= csrf_field() ?>
            <input name="nombre" x-model="form.nombre" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Nombre">
            <input name="correo" x-model="form.correo" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Correo">
            <input name="telefono" x-model="form.telefono" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Telefono">
            <div class="flex gap-2">
                <input name="contrasena" x-model="form.nueva_pwd" class="flex-1 rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Nueva contrasena (opcional)">
                <button type="button" @click="form.nueva_pwd = Math.random().toString(36).slice(-8)"
                        class="rounded-2xl bg-brand-500/10 text-brand-600 dark:text-brand-500 px-3.5 hover:bg-brand-500 hover:text-white transition-colors"><i data-lucide="dice-5" class="w-4 h-4"></i></button>
            </div>
            <template x-if="rol === 'domiciliario'">
                <div class="space-y-3">
                    <input name="placa" x-model="form.placa" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Placa">
                    <input name="tipo_vehiculo" x-model="form.tipo_vehiculo" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Vehiculo">
                    <input name="base_efectivo_asignada" x-model="form.base_efectivo_asignada" type="number" class="w-full rounded-2xl border border-gray-200 dark:border-stone-700 dark:bg-stone-950/40 px-4 py-2.5 text-sm font-medium focus:outline-none focus:border-brand-500 transition-colors" placeholder="Base efectivo">
                </div>
            </template>
            <div class="flex items-center gap-3 rounded-2xl bg-gray-50 dark:bg-stone-950/40 p-3">
                <button type="button" @click="copiar()" class="text-xs font-bold rounded-xl bg-gray-100 dark:bg-stone-800 px-3 py-2 flex items-center gap-1 hover:bg-gray-200 dark:hover:bg-stone-700 transition-colors"><i data-lucide="copy" class="w-3.5 h-3.5"></i> Copiar datos</button>
                <a :href="waLink" target="_blank" class="text-xs font-bold rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-400 px-3 py-2 flex items-center gap-1 hover:bg-emerald-200 dark:hover:bg-emerald-500/25 transition-colors"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WhatsApp</a>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="open = false" class="rounded-2xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold hover:bg-gray-50 dark:hover:bg-stone-800 transition-colors">Cancelar</button>
                <button class="rounded-2xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-medium transition-all duration-300 hover:scale-105">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

</div>
<script>
function personalPage() {
    return {
        open: false, rol: 'cocina', form: {}, accion: '',
        async editar(rol, id) {
            this.rol = rol;
            const res = await fetch('<?= url('/admin/personal') ?>/' + rol + '/' + id);
            const e = await res.json();
            this.form = {
                nombre: e.nombre, correo: e.correo, telefono: e.telefono, nueva_pwd: '',
                placa: e.placa || '', tipo_vehiculo: e.tipo_vehiculo || '', base_efectivo_asignada: e.base_efectivo_asignada || ''
            };
            this.accion = '<?= url('/admin/personal') ?>/' + rol + '/' + id;
            this.open = true;
            this.$nextTick(() => lucide.createIcons());
        },
        get waLink() {
            const t = `Hola ${this.form.nombre}, tus accesos Copiway: usuario ${this.form.correo}` +
                      (this.form.nueva_pwd ? `, clave ${this.form.nueva_pwd}` : '');
            return 'https://wa.me/?text=' + encodeURIComponent(t);
        },
        copiar() {
            navigator.clipboard.writeText(`Usuario: ${this.form.correo}\nClave: ${this.form.nueva_pwd || '(sin cambios)'}`);
            window.toast('staff', 'Copiado', 'Credenciales copiadas al portapapeles.');
        }
    }
}
</script>
