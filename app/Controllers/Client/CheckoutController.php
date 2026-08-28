<?php
/**
 * Controlador de Checkout.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Carrito.php';
require_once __DIR__ . '/../../Models/Cliente.php';
require_once __DIR__ . '/../../Models/Configuracion.php';
require_once __DIR__ . '/../../Models/PedidoServicio.php';
require_once __DIR__ . '/../../Models/Pedido.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$carritoModel  = new Carrito();
$clienteModel  = new Cliente();
$configModel   = new Configuracion();
$servicioModel = new PedidoServicio();
$pedidoModel   = new Pedido();

if ($action === 'confirmar') {
    if (!$carritoModel->items()) {
        redirect('/app/Views/client/carrito.php');
    }

    $estado = $configModel->estadoCocina();
    if (!$estado['abierta']) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Cocina cerrada', 'message' => "El horario de atencion es {$estado['apertura']} - {$estado['cierre']}."];
        redirect('/app/Views/client/checkout.php');
    }

    $direccion = trim($_POST['direccion'] ?? '');
    $metodo    = ($_POST['metodo_pago'] ?? 'efectivo') === 'digital' ? 'digital' : 'efectivo';

    if ($direccion === '') {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Falta la direccion', 'message' => 'Ingresa una direccion de entrega.'];
        redirect('/app/Views/client/checkout.php');
    }

    $cliente = $clienteModel->directorio();
    $subtotal = $carritoModel->subtotal();
    $descuento = $clienteModel->esCumpleanos($cliente) ? round($subtotal * 0.15) : 0;

    $idPedido = $servicioModel->crear([
        'id_cliente'          => Auth::id() ?? 'cliente-anon',
        'direccion_entrega'   => $direccion,
        'canal_origen'        => 'web',
        'metodo_pago'         => $metodo,
        'descuento_cumpleanos'=> $descuento,
        'comprobante'         => $metodo === 'digital' ? ('PAGO-' . strtoupper(substr(uuid(), 0, 8))) : null,
        'aprobar_pago'        => $metodo === 'digital',
        'lineas'              => $carritoModel->aLineas(),
    ]);

    $carritoModel->vaciar();
    $cod = $pedidoModel->codigo($pedidoModel->find($idPedido));
    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Pedido Confirmado', 'message' => "Tu orden {$cod} ha sido enviada a cocina."];
    redirect('/app/Views/client/ordenes.php');
}
else {
    redirect('/app/Views/client/checkout.php');
}

