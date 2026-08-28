<?php
/**
 * Controlador de Carrito de Compras.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/Carrito.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Ingrediente.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$carritoModel  = new Carrito();
$productoModel = new Producto();
$ingModel      = new Ingrediente();

function personalizacionesDesdePost($ingModel): array
{
    $pers = [];
    foreach ($_POST['quitar'] ?? [] as $idIng) {
        $ing = $ingModel->find($idIng);
        if ($ing) $pers[] = ['id_ingrediente' => $idIng, 'nombre' => $ing['nombre'], 'accion' => 'quitar', 'costo' => 0];
    }
    foreach ($_POST['extra'] ?? [] as $idIng) {
        $ing = $ingModel->find($idIng);
        if ($ing && (float) $ing['cantidad_stock'] > 0) {
            $pers[] = ['id_ingrediente' => $idIng, 'nombre' => $ing['nombre'], 'accion' => 'agregar', 'costo' => (float) $ing['precio_extra']];
        }
    }
    return $pers;
}

if ($action === 'agregar') {
    $id = $_POST['id_producto'] ?? $_GET['id_producto'] ?? '';
    $cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));

    if (!$id || $productoModel->estaAgotado($id)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Producto no disponible', 'message' => 'Este producto esta agotado por ahora.'];
        redirect('/app/Views/client/catalogo.php');
    }

    $pers = personalizacionesDesdePost($ingModel);
    $carritoModel->agregar($id, $cantidad, $pers);

    $prod = $productoModel->find($id);
    $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Carrito Copiway', 'message' => "{$prod['nombre']} añadido al carrito."];
    redirect($_POST['volver'] ?? '/app/Views/client/catalogo.php');
}
elseif ($action === 'actualizar') {
    $key = $_POST['key'] ?? '';
    $cantidad = (int) ($_POST['cantidad'] ?? 1);
    $pers = isset($_POST['quitar']) || isset($_POST['extra'])
        ? personalizacionesDesdePost($ingModel)
        : null;
    $carritoModel->actualizar($key, $cantidad, $pers);
    $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Carrito Copiway', 'message' => 'Carrito actualizado.'];
    redirect('/app/Views/client/carrito.php');
}
elseif ($action === 'quitar') {
    $key = $_POST['key'] ?? $_GET['key'] ?? '';
    $carritoModel->quitar($key);
    $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Carrito Copiway', 'message' => 'Producto eliminado del carrito.'];
    redirect('/app/Views/client/carrito.php');
}
elseif ($action === 'vaciar') {
    $carritoModel->vaciar();
    $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Carrito Copiway', 'message' => 'Se han eliminado todos los productos del carrito.'];
    redirect('/app/Views/client/carrito.php');
}
else {
    redirect('/app/Views/client/carrito.php');
}

