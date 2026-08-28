<?php
/**
 * Controlador de Clientes (Directorio e historial).
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/Cliente.php';
require_once __DIR__ . '/../../Models/Pedido.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$clienteModel = new Cliente();
$pedidoModel  = new Pedido();

if ($action === 'historial') {
    $id = $_GET['id'] ?? '';
    $cliente = $clienteModel->find($id);
    $pedidos = $clienteModel->historial($id);
    foreach ($pedidos as &$p) {
        $p['codigo'] = $pedidoModel->codigo($p);
    }
    unset($p);
    if ($cliente) { unset($cliente['contrasena']); }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['cliente' => $cliente ?: ['nombre' => ''], 'pedidos' => $pedidos]);
    exit;
} else {
    redirect('/app/Views/admin/clientes/index.php');
}

