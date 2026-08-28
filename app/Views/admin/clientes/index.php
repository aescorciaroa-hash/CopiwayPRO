<?php /** @var array $clientes */ ?>
<div x-data="clientesPage()">

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-black">Directorio de Clientes</h1>
        <p class="text-gray-500 dark:text-gray-400"><?= count($clientes) ?> cliente(s) registrado(s)</p>
    </div>
    <form method="get" class="relative">
        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        <input name="q" value="<?= e($buscar) ?>" placeholder="Buscar por nombre o telefono"
               class="rounded-xl border border-gray-200 dark:border-stone-700 dark:bg-stone-900 pl-9 pr-4 py-2.5 text-sm w-72">
    </form>
</div>

<div class="bg-white dark:bg-card border border-gray-100 dark:border-stone-800 rounded-3xl overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-left text-xs text-gray-400 uppercase bg-gray-50 dark:bg-stone-900">
                <tr>
                    <th class="px-6 py-3">Nombre</th><th class="px-6 py-3">Telefono</th><th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Pedidos</th><th class="px-6 py-3">Total Gastado</th>
                    <th class="px-6 py-3">Ultimo Pedido</th><th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-stone-800">
                <?php foreach ($clientes as $c): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-stone-900">
                        <td class="px-6 py-3.5 font-bold"><?= e($c['nombre']) ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?= e($c['telefono']) ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?= e($c['correo']) ?></td>
                        <td class="px-6 py-3.5"><?= (int) $c['pedidos'] ?></td>
                        <td class="px-6 py-3.5 font-bold"><?= money($c['total_gastado']) ?></td>
                        <td class="px-6 py-3.5 text-gray-500"><?= $c['ultimo_pedido'] ? date('d/m/Y', strtotime($c['ultimo_pedido'])) : '—' ?></td>
                        <td class="px-6 py-3.5 text-right">
                            <button @click="verHistorial('<?= e($c['id_cliente']) ?>')"
                                    class="text-xs font-bold text-brand-600 hover:underline">Ver historial</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$clientes): ?>
                    <tr><td colspan="7" class="px-6 py-16 text-center text-gray-400">No hay clientes que coincidan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal historial -->
<div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div @click.outside="open = false" class="bg-white dark:bg-card rounded-2xl shadow-xl w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-stone-800">
            <div>
                <h2 class="text-xl font-black">Historial del Cliente</h2>
                <p class="text-sm text-gray-400" x-text="cliente.nombre"></p>
            </div>
            <button @click="open = false" class="text-gray-400"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <div class="p-5 overflow-y-auto space-y-2">
            <template x-for="p in pedidos" :key="p.id_pedido">
                <div class="rounded-xl border border-gray-100 dark:border-stone-800 p-3 flex items-center justify-between">
                    <div>
                        <p class="font-mono font-bold text-sm" x-text="p.codigo"></p>
                        <p class="text-xs text-gray-400" x-text="new Date(p.fecha_hora).toLocaleDateString('es-CO') + (p.domiciliario_nombre ? ' · ' + p.domiciliario_nombre : '')"></p>
                    </div>
                    <span class="font-bold text-sm" x-text="'$ ' + Number(p.total).toLocaleString('es-CO')"></span>
                </div>
            </template>
            <p x-show="pedidos.length === 0" class="text-center text-gray-400 py-10 text-sm">Este cliente no tiene pedidos.</p>
        </div>
    </div>
</div>

</div>
<script>
function clientesPage() {
    return {
        open: false, cliente: {}, pedidos: [],
        async verHistorial(id) {
            const res = await fetch('<?= url('/admin/clientes') ?>/' + id + '/historial');
            const data = await res.json();
            this.cliente = data.cliente; this.pedidos = data.pedidos;
            this.open = true;
            this.$nextTick(() => lucide.createIcons());
        }
    }
}
</script>
