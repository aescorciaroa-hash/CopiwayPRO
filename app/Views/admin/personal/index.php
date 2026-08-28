<?php /** @var array $empleados @var int $activos */ ?>
<div x-data="personalPage()">

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Colaboradores</h1>
        <p class="text-gray-500 dark:text-gray-400"><?= $activos ?> activo(s)</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-3">
    <!-- Formulario nuevo empleado -->
    <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-6 shadow-sm h-fit">
        <h2 class="font-black text-lg mb-4">Registrar Nuevo Empleado</h2>
        <form method="post" action="<?= url('/admin/personal') ?>" class="space-y-3" x-data="{ rol: 'cocina', pwd: '' }">
            <?= csrf_field() ?>
            <input name="nombre" required placeholder="Nombre completo"
                   class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <input name="correo" type="email" required placeholder="Email"
                   class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <input name="telefono" required placeholder="Celular"
                   class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
            <div class="flex gap-2">
                <input name="contrasena" x-model="pwd" required placeholder="Contrasena provisoria"
                       class="flex-1 rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <button type="button" @click="pwd = Math.random().toString(36).slice(-8)"
                        class="rounded-xl bg-gray-100 dark:bg-stone-800 px-3 text-xs font-bold" title="Generar PIN aleatorio">
                    <i data-lucide="dice-5" class="w-4 h-4"></i>
                </button>
            </div>
            <select name="rol" x-model="rol" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                <option value="cocina">Ayudante de cocina</option>
                <option value="domiciliario">Domiciliario</option>
            </select>
            <template x-if="rol === 'cocina'">
                <select name="turno" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                    <option value="manana">Manana</option><option value="tarde">Tarde</option>
                    <option value="noche">Noche</option><option value="mixto" selected>Mixto</option>
                </select>
            </template>
            <template x-if="rol === 'domiciliario'">
                <div class="space-y-3">
                    <select name="tipo_vehiculo" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                        <option value="moto">Moto</option><option value="bicicleta">Bicicleta</option>
                        <option value="carro">Carro</option><option value="a_pie">A pie</option>
                    </select>
                    <input name="placa" placeholder="Placa"
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                    <input name="base_efectivo_asignada" type="number" min="0" placeholder="Base efectivo asignada"
                           class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm">
                </div>
            </template>
            <button class="w-full rounded-xl bg-brand-500 hover:bg-brand-600 text-white py-2.5 text-sm font-bold">Crear Colaborador</button>
        </form>
    </div>

    <!-- Lista de personal -->
    <div class="lg:col-span-2 space-y-3">
        <?php foreach ($empleados as $emp): ?>
            <div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl p-5 shadow-sm flex items-center gap-4">
                <span class="w-11 h-11 rounded-full bg-brand-500 text-white font-bold flex items-center justify-center shrink-0">
                    <?= e(strtoupper(substr($emp['nombre'], 0, 1))) ?>
                </span>
                <div class="flex-1 min-w-0">
                    <p class="font-bold"><?= e($emp['nombre']) ?>
                        <span class="text-[10px] font-black uppercase rounded-full px-2 py-0.5 ml-1 <?= $emp['rol'] === 'cocina' ? 'bg-blue-100 text-blue-700' : 'bg-brand-100 text-brand-700' ?>">
                            <?= $emp['rol'] === 'cocina' ? 'Ayudante de cocina' : 'Domiciliario' ?>
                        </span>
                    </p>
                    <p class="text-xs text-gray-400 truncate"><?= e($emp['correo']) ?> · <?= e($emp['telefono']) ?>
                        <?php if ($emp['rol'] === 'domiciliario' && $emp['placa']): ?> · <?= e($emp['placa']) ?><?php endif; ?>
                    </p>
                </div>
                <span class="text-[10px] font-black uppercase rounded-full px-2 py-1 <?= $emp['activo'] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-500' ?>">
                    <?= $emp['activo'] ? 'Activo' : 'Inactivo' ?>
                </span>
                <div class="flex items-center gap-2">
                    <button @click="editar('<?= $emp['rol'] ?>', '<?= e($emp['id']) ?>')"
                            class="rounded-lg bg-gray-100 dark:bg-stone-800 hover:bg-gray-200 p-2" title="Editar">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                    </button>
                    <?php if ($emp['activo']): ?>
                        <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/baja') ?>"
                              onsubmit="return confirm('Revocar el acceso a &quot;<?= e($emp['nombre']) ?>&quot;? Se preservara su historial.')">
                            <?= csrf_field() ?>
                            <button class="rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 hover:bg-amber-100 px-3 py-2 text-xs font-bold">Dar de baja</button>
                        </form>
                    <?php else: ?>
                        <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/reactivar') ?>">
                            <?= csrf_field() ?>
                            <button class="rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 px-3 py-2 text-xs font-bold">Reactivar</button>
                        </form>
                        <form method="post" action="<?= url('/admin/personal/' . $emp['rol'] . '/' . $emp['id'] . '/eliminar') ?>"
                              onsubmit="return confirm('Eliminar por completo el registro de &quot;<?= e($emp['nombre']) ?>&quot;? Esta accion borrara sus credenciales permanentemente.')">
                            <?= csrf_field() ?>
                            <button class="rounded-lg bg-red-50 dark:bg-red-950/30 text-red-500 hover:bg-red-100 px-3 py-2 text-xs font-bold">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$empleados): ?><p class="text-center text-gray-400 py-16 text-sm">Aun no has registrado empleados.</p><?php endif; ?>
    </div>
</div>

<!-- Modal editar empleado -->
<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="open = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <h2 class="text-xl font-black">Editar Colaborador</h2>
            <button @click="open = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form :action="accion" method="post" class="p-6 space-y-3">
            <?= csrf_field() ?>
            <input name="nombre" x-model="form.nombre" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Nombre">
            <input name="correo" x-model="form.correo" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Correo">
            <input name="telefono" x-model="form.telefono" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Telefono">
            <div class="flex gap-2">
                <input name="contrasena" x-model="form.nueva_pwd" class="flex-1 rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Nueva contrasena (opcional)">
                <button type="button" @click="form.nueva_pwd = Math.random().toString(36).slice(-8)"
                        class="rounded-xl bg-gray-100 dark:bg-stone-800 px-3"><i data-lucide="dice-5" class="w-4 h-4"></i></button>
            </div>
            <template x-if="rol === 'domiciliario'">
                <div class="space-y-3">
                    <input name="placa" x-model="form.placa" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Placa">
                    <input name="tipo_vehiculo" x-model="form.tipo_vehiculo" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Vehiculo">
                    <input name="base_efectivo_asignada" x-model="form.base_efectivo_asignada" type="number" class="w-full rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 px-4 py-2.5 text-sm" placeholder="Base efectivo">
                </div>
            </template>
            <div class="flex items-center gap-3 rounded-xl bg-gray-50 dark:bg-stone-900 p-3">
                <button type="button" @click="copiar()" class="text-xs font-bold rounded-lg bg-gray-100 dark:bg-stone-800 px-3 py-2 flex items-center gap-1"><i data-lucide="copy" class="w-3.5 h-3.5"></i> Copiar datos</button>
                <a :href="waLink" target="_blank" class="text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700 px-3 py-2 flex items-center gap-1"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WhatsApp</a>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="open = false" class="rounded-xl border border-gray-200 dark:border-stone-700 px-5 py-2.5 text-sm font-bold">Cancelar</button>
                <button class="rounded-xl bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 text-sm font-bold">Guardar Cambios</button>
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
