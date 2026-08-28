<?php
/**
 * Controlador de Panel de Cocina (KDS).
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Pedido.php';
require_once __DIR__ . '/../../Models/PedidoServicio.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$servicioModel = new PedidoServicio();
$configModel   = new Configuracion();

if ($action === 'estacionLogin') {
    $pin = $_POST['pin'] ?? '';
    if (hash_equals((string) $configModel->value('pin_estacion_kds'), $pin)) {
        Session::set('estacion_cocina_ok', true);
        redirect('/app/Views/kitchen/index.php');
    }
    $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'PIN incorrecto', 'message' => 'Usuario o contraseña de estacion incorrectos.'];
    redirect('/app/Views/kitchen/estacion.php');
}
elseif ($action === 'preparar') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    if ($id !== '') {
        $servicioModel->cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()]);
        $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Pedido en Preparación', 'message' => 'El pedido paso a preparacion.'];
    }
    redirect('/app/Views/kitchen/index.php');
}
elseif ($action === 'listo') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    if ($id !== '') {
        $servicioModel->cambiarEstado($id, 'listo', ['id_ayudante' => Auth::id()]);
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Pedido Listo', 'message' => 'El pedido esta listo para despacho.'];
    }
    redirect('/app/Views/kitchen/index.php');
}
else {
    redirect('/app/Views/kitchen/index.php');
}
