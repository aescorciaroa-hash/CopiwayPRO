<?php
/**
 * Controlador de Creador Interactivo de Hamburguesas.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/Producto.php';
require_once __DIR__ . '/../../Models/Carrito.php';
require_once __DIR__ . '/../../Models/Ingrediente.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$productoModel = new Producto();
$carritoModel  = new Carrito();
$ingredModel   = new Ingrediente();
$configModel   = new Configuracion();

if ($action === 'agregar') {
    $capas = $_POST['capa'] ?? [];

    if (!$capas) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Burger vacia', 'message' => 'Agrega al menos un ingrediente.'];
        redirect('/client/creador');
    }

    global $conn;
    $res = $conn->query("SELECT * FROM PRODUCTO WHERE nombre = 'Hamburguesa Personalizada' LIMIT 1");
    $base = $res ? $res->fetch_assoc() : null;

    if (!$base) {
        $resC = $conn->query("SELECT id_categoria FROM CATEGORIA WHERE ambito = 'menu' LIMIT 1");
        $cat = $resC ? $resC->fetch_assoc() : null;
        $catId = $cat['id_categoria'] ?? uuid();

        $idBase = $productoModel->guardar([
            'id_categoria' => $catId,
            'nombre'       => 'Hamburguesa Personalizada',
            'descripcion'  => 'Creada capa por capa en el Creador Interactivo.',
            'precio_venta' => 0,
            'estado'       => 'oculto',
        ]);
    } else {
        $idBase = $base['id_producto'];
    }

    $pers = [];
    foreach ($capas as $idIng) {
        $ing = $ingredModel->find($idIng);
        if (!$ing || (float) $ing['cantidad_stock'] <= 0) continue;
        $margen = (float) $configModel->value('margen_ganancia_defecto', 30);
        $precio = (float) $ing['precio_extra'] > 0
            ? (float) $ing['precio_extra']
            : round((float) $ing['costo_unitario'] * (1 + $margen / 100));
        $pers[] = ['id_ingrediente' => $idIng, 'nombre' => $ing['nombre'], 'accion' => 'agregar', 'costo' => $precio];
    }

    $cantidad = max(1, (int) ($_POST['cantidad'] ?? 1));
    $carritoModel->agregar($idBase, $cantidad, $pers);

    $_SESSION['_flash'][] = ['type' => 'cart', 'title' => 'Copiway', 'message' => 'Hamburguesa personalizada agregada al carrito.'];
    redirect('/client/carrito');
}
else {
    redirect('/client/creador');
}

