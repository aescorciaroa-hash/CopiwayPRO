<?php /** @var array $_flash */ ?>
<div x-data="toastHub()" x-cloak
     class="fixed top-4 right-4 z-[100] flex flex-col gap-3 w-[92vw] max-w-sm">
    <template x-for="t in items" :key="t.id">
        <div class="toast-item rounded-2xl shadow-lg border p-4 flex gap-3 items-start bg-white dark:bg-card"
             :class="{
                'border-brand-200 dark:border-brand-500/40': ['cart','inventory'].includes(t.type),
                'border-emerald-200 dark:border-emerald-500/40': ['success','whatsapp','cierre'].includes(t.type),
                'border-red-200 dark:border-red-500/40': t.type === 'danger',
                'border-amber-200 dark:border-amber-500/40': t.type === 'warning',
                'border-blue-200 dark:border-blue-500/40': t.type === 'staff',
                'border-purple-200 dark:border-purple-500/40': t.type === 'config',
             }">
            <div class="mt-0.5 shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                 :class="{
                    'bg-brand-100 text-brand-600 dark:bg-brand-500/15': ['cart','inventory'].includes(t.type),
                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15': ['success','whatsapp','cierre'].includes(t.type),
                    'bg-red-100 text-red-600 dark:bg-red-500/15': t.type === 'danger',
                    'bg-amber-100 text-amber-600 dark:bg-amber-500/15': t.type === 'warning',
                    'bg-blue-100 text-blue-600 dark:bg-blue-500/15': t.type === 'staff',
                    'bg-purple-100 text-purple-600 dark:bg-purple-500/15': t.type === 'config',
                 }">
                <i :data-lucide="icon(t.type)" class="w-5 h-5"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="t.title"></p>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="t.message" x-show="t.message"></p>
            </div>
            <button @click="remove(t.id)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </template>
</div>

<script>
function toastHub() {
    return {
        items: [],
        push(type, title, message) {
            const id = Date.now() + Math.random();
            this.items.push({ id, type, title, message: message || '' });
            this.$nextTick(() => window.lucide && lucide.createIcons());
            setTimeout(() => this.remove(id), 5000);
        },
        remove(id) { this.items = this.items.filter(t => t.id !== id); },
        icon(type) {
            return ({
                cart: 'shopping-cart', success: 'check-circle', whatsapp: 'message-circle',
                danger: 'alert-triangle', warning: 'alert-circle', inventory: 'package',
                staff: 'users', config: 'settings', cierre: 'file-check'
            })[type] || 'bell';
        },
        init() {
            window.toast = (type, title, message) => this.push(type, title, message);
            <?php
            $flashes = $_SESSION['_flash'] ?? [];
            unset($_SESSION['_flash']);
            foreach ($flashes as $f): ?>
            this.push(<?= json_encode($f['type']) ?>, <?= json_encode($f['title']) ?>, <?= json_encode($f['message']) ?>);
            <?php endforeach; ?>
        }
    }
}
</script>

