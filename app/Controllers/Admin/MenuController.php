<?php
/**
 * Controlador de Gestion de Menu.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Categoria.php';
require_once __DIR__ . '/../../Models/Ingrediente.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$productoModel  = new Producto();
$categoriaModel = new Categoria();

if ($action === 'guardarProducto') {
    $idCat   = $_POST['id_categoria'] ?? '';
    $nombre  = trim($_POST['nombre'] ?? '');
    $precio  = (float) ($_POST['precio'] ?? 0);
    $desc    = $_POST['descripcion'] ?? null;
    $imagen  = $_POST['imagen'] ?? null;
    $idProd  = $_POST['id_producto'] ?? '';

    if (empty($nombre) || empty($idCat)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de formulario', 'message' => 'Por favor indica el nombre y la categoria del producto.'];
        redirect('/app/Views/admin/menu/index.php');
    }

    $id = $productoModel->guardar([
        'id_producto'  => $idProd,
        'id_categoria' => $idCat,
        'nombre'       => $nombre,
        'descripcion'  => $desc,
        'precio_venta' => $precio,
        'imagen_url'   => $imagen,
    ]);

    $ings  = $_POST['receta_ingrediente'] ?? [];
    $cants = $_POST['receta_cantidad'] ?? [];
    $items = [];
    foreach ($ings as $i => $idIng) {
        $items[] = ['id_ingrediente' => $idIng, 'cantidad' => $cants[$i] ?? 0];
    }
    $productoModel->guardarReceta($id, $items);

    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Producto Guardado', 'message' => 'El producto se ha guardado exitosamente.'];
    redirect('/app/Views/admin/menu/index.php');
}
elseif ($action === 'cambiarEstado') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $p  = $productoModel->find($id);
    if ($p) {
        $nuevo = $p['estado'] === 'activo' ? 'oculto' : 'activo';
        $productoModel->cambiarEstado($id, $nuevo);
        $_SESSION['_flash'][] = ['type' => 'inventory', 'title' => 'Estado del Producto', 'message' => "Estado actualizado a {$nuevo}."];
    }
    redirect('/app/Views/admin/menu/index.php');
}
elseif ($action === 'eliminarProducto') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $ok = $productoModel->eliminar($id);
    if ($ok) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Producto Eliminado', 'message' => 'El producto fue eliminado del catalogo.'];
    } else {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'No se puede eliminar', 'message' => 'El producto tiene historial de ventas.'];
    }
    redirect('/app/Views/admin/menu/index.php');
}
elseif ($action === 'crearCategoria') {
    $nombre = trim($_POST['nombre'] ?? '');
    if ($nombre !== '') {
        $categoriaModel->crear(['nombre' => $nombre, 'ambito' => 'menu']);
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Categoria Creada', 'message' => 'La categoria se agrego al menu.'];
    }
    redirect('/app/Views/admin/menu/index.php');
}
elseif ($action === 'eliminarCategoria') {
    $id = $_GET['id'] ?? $_POST['id'] ?? '';
    $categoriaModel->eliminar($id);
    $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Categoria Eliminada', 'message' => 'La categoria fue eliminada.'];
    redirect('/app/Views/admin/menu/index.php');
}
else {
    redirect('/app/Views/admin/menu/index.php');
}

