<?php
/**
 * Controlador de Ordenes Activas del Cliente.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Models/Pedido.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$pedidoModel = new Pedido();

if ($action === 'detalle') {
    $id = $_GET['id'] ?? '';
    $p  = $pedidoModel->completo($id);
    if (!$p) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['error' => 'No encontrado']);
        exit;
    }
    $p['codigo'] = $pedidoModel->codigo($p);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($p);
    exit;
} else {
    redirect('/app/Views/client/ordenes.php');
}

