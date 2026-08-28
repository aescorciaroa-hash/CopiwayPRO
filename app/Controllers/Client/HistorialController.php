<?php
/**
 * Controlador de Historial de Pedidos del Cliente.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Pedido.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Carrito.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$pedidoModel   = new Pedido();
$productoModel = new Producto();
$carritoModel  = new Carrito();

if ($action === 'recomprar') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $pedido = $pedidoModel->find($id);

    if ($pedido) {
        $lineas = $pedidoModel->detalle($id);
        foreach ($lineas as $l) {
            if ($productoModel->estaAgotado($l['id_producto'])) {
                $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Stock No Disponible', 'message' => "No se puede duplicar el pedido: {$l['nombre']} se encuentra agotado."];
                redirect('/client/historial');
            }
        }
        foreach ($lineas as $l) {
            $pers = [];
            foreach ($l['personalizaciones'] as $p) {
                $pers[] = [
                    'id_ingrediente' => $p['id_ingrediente'],
                    'nombre'         => $p['nombre'],
                    'accion'         => $p['accion_modificacion'] === 'quitar' ? 'quitar' : 'agregar',
                    'costo'          => (float) $p['costo_aplicado'],
                ];
            }
            $carritoModel->agregar($l['id_producto'], (int) $l['cantidad'], $pers);
        }
        $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Recompra en 1-Clic', 'message' => 'Pedido duplicado en tu carrito.'];
        redirect('/client/carrito');
    }
    redirect('/client/historial');
}
elseif ($action === 'resena') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $puntaje = max(1, min(5, (int) ($_POST['puntaje'] ?? 5)));
    $comentario = trim($_POST['comentario'] ?? '');

    if ($id !== '') {
        global $conn;
        $stmtEx = $conn->prepare("SELECT id_resena FROM RESENA WHERE id_pedido = ?");
        $stmtEx->bind_param("s", $id);
        $stmtEx->execute();
        $ex = $stmtEx->get_result()->fetch_assoc();
        $stmtEx->close();

        if ($ex) {
            $stmtUp = $conn->prepare("UPDATE RESENA SET puntaje = ?, comentario = ? WHERE id_pedido = ?");
            $stmtUp->bind_param("iss", $puntaje, $comentario, $id);
            $stmtUp->execute();
            $stmtUp->close();
        } else {
            $stmtIns = $conn->prepare("INSERT INTO RESENA (id_resena, id_pedido, puntaje, comentario, fecha) VALUES (?,?,?,?,NOW())");
            $idRes = uuid();
            $stmtIns->bind_param("ssis", $idRes, $id, $puntaje, $comentario);
            $stmtIns->execute();
            $stmtIns->close();
        }
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Reseña Registrada', 'message' => 'Muchas gracias por tu valoracion.'];
    }
    redirect('/client/historial');
}
else {
    redirect('/client/historial');
}

