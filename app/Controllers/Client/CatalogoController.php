<?php
/**
 * Controlador de Catalogo.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Categoria.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$productoModel = new Producto();

if ($action === 'personalizar') {
    $id = $_GET['id'] ?? '';
    $p = $productoModel->conCategoria($id);
    if (!$p) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(404);
        echo json_encode(['error' => 'No encontrado']);
        exit;
    }
    $p['agotado'] = $productoModel->estaAgotado($id);
    $p['personalizables'] = $productoModel->personalizables($id);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($p);
    exit;
} else {
    redirect('/client');
}

