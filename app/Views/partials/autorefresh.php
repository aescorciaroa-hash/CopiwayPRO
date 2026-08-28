<?php /** Recarga la vista periodicamente. Pasa $segundos antes de incluir. */ ?>
<script>
(function () {
    const cada = <?= (int) ($segundos ?? 20) ?> * 1000;
    let ultimaInteraccion = 0;
    ['mousedown', 'keydown', 'touchstart'].forEach(ev =>
        document.addEventListener(ev, () => ultimaInteraccion = Date.now(), true));

    setInterval(() => {
        if (document.hidden) return;
        // No recargar si hay un modal abierto (overlay .fixed.inset-0 visible)
        const modal = [...document.querySelectorAll('.fixed.inset-0')]
            .some(m => m.offsetParent !== null && getComputedStyle(m).display !== 'none');
        if (modal) return;
        // No recargar si el usuario interactuo hace menos de 8s
        if (Date.now() - ultimaInteraccion < 8000) return;
        location.reload();
    }, cada);
})();
</script>
