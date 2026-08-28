<?php
/**
 * Controlador de Panel de Domiciliario.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Pedido.php';
require_once __DIR__ . '/../../Models/PedidoServicio.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$pedidoModel   = new Pedido();
$servicioModel = new PedidoServicio();
$configModel   = new Configuracion();

if ($action === 'estacionLogin') {
    $pin = $_POST['pin'] ?? '';
    if (hash_equals((string) $configModel->value('pin_estacion_domiciliario'), $pin)) {
        Session::set('estacion_domi_ok', true);
        redirect('/delivery');
    }
    $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'PIN incorrecto', 'message' => 'Usuario o contraseña incorrectos.'];
    redirect('/delivery/estacion');
}
elseif ($action === 'disponibilidad') {
    $estado = ($_POST['estado'] ?? '') === 'disponible' ? 'disponible' : 'desconectado';
    $idDomi = Auth::id();
    if ($idDomi) {
        global $conn;
        $stmt = $conn->prepare("UPDATE DOMICILIARIO SET estado_disponibilidad = ? WHERE id_domiciliario = ?");
        $stmt->bind_param("ss", $estado, $idDomi);
        $stmt->execute();
        $stmt->close();
    }
    redirect('/delivery');
}
elseif ($action === 'tomar') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $idDomi = Auth::id();
    if ($id && $idDomi) {
        global $conn;
        $stmtP = $conn->prepare("UPDATE PEDIDO SET id_domiciliario = ? WHERE id_pedido = ? AND estado = 'listo' AND id_domiciliario IS NULL");
        $stmtP->bind_param("ss", $idDomi, $id);
        $stmtP->execute();
        $stmtP->close();

        $stmtD = $conn->prepare("UPDATE DOMICILIARIO SET estado_disponibilidad = 'en_ruta' WHERE id_domiciliario = ?");
        $stmtD->bind_param("s", $idDomi);
        $stmtD->execute();
        $stmtD->close();

        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Pedido Asignado', 'message' => 'Tomaste el pedido correctamente.'];
    }
    redirect('/delivery');
}
elseif ($action === 'iniciarRuta') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    if ($id) {
        $servicioModel->cambiarEstado($id, 'en_camino');
        $_SESSION['_flash'][] = ['type' => 'whatsapp', 'title' => 'Ruta Iniciada', 'message' => 'Se notifico al cliente que su pedido va en camino.'];
    }
    redirect('/delivery');
}
elseif ($action === 'entregar') {
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $pin = $_POST['pin'] ?? '';
    $p   = $pedidoModel->completo($id);

    if ($p) {
        if (!hash_equals((string) $p['pin_entrega'], $pin)) {
            $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'PIN incorrecto', 'message' => 'El PIN de 4 digitos no coincide.'];
            redirect('/delivery');
        }
        if (($p['pago_metodo'] ?? '') === 'efectivo') {
            $servicioModel->aprobarPago($id);
        }
        $servicioModel->cambiarEstado($id, 'entregado');
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Pedido Entregado', 'message' => 'El pedido fue entregado con exito.'];
    }
    redirect('/delivery');
}
else {
    redirect('/delivery');
}

