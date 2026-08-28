<?php
/**
 * Controlador de Comandas Activas.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/Pedido.php';
require_once __DIR__ . '/../../Models/PedidoServicio.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$pedidoModel   = new Pedido();
$servicioModel = new PedidoServicio();

if ($action === 'crearManual') {
    $nombre    = trim($_POST['cliente'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $ids       = $_POST['producto_id'] ?? [];
    $cants     = $_POST['producto_cant'] ?? [];

    if ($telefono === '' || $direccion === '' || !$ids) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Datos incompletos', 'message' => 'Ingresa telefono, direccion y al menos un producto.'];
        redirect('/app/Views/admin/comandas/index.php');
    }

    $lineas = [];
    foreach ($ids as $i => $idProd) {
        $cant = (int) ($cants[$i] ?? 1);
        if ($idProd && $cant > 0) {
            $lineas[] = ['id_producto' => $idProd, 'cantidad' => $cant, 'personalizaciones' => []];
        }
    }

    $idCliente = $servicioModel->clienteParaManual($nombre, $telefono);
    $idPedido  = $servicioModel->crear([
        'id_cliente'        => $idCliente,
        'direccion_entrega' => $direccion,
        'canal_origen'      => $_POST['canal'] ?? 'llamada',
        'metodo_pago'       => 'efectivo',
        'aprobar_pago'      => true,
        'lineas'            => $lineas,
    ]);

    $cod = $pedidoModel->codigo($pedidoModel->find($idPedido));
    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Pedido Manual', 'message' => "Pedido manual {$cod} creado y enviado a cocina."];
    redirect('/app/Views/admin/comandas/index.php');
}
elseif ($action === 'editarDireccion') {
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $dir = trim($_POST['direccion'] ?? '');
    if ($id !== '' && $dir !== '') {
        $servicioModel->editarDireccion($id, $dir);
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Direccion Modificada', 'message' => 'La direccion de entrega fue actualizada correctamente.'];
    }
    redirect('/app/Views/admin/comandas/index.php');
}
else {
    redirect('/app/Views/admin/comandas/index.php');
}

