<?php
/**
 * Controlador de Inventario Express.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Ingrediente.php';
require_once __DIR__ . '/../../Models/Categoria.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$ingredienteModel = new Ingrediente();

if ($action === 'guardarInsumo') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $catId     = $_POST['id_categoria'] ?? '';
    $unidad    = $_POST['unidad_medida'] ?? '';
    $cantidad  = (float) ($_POST['cantidad'] ?? 0);
    $costoTot  = (float) ($_POST['costo_total'] ?? 0);

    if (empty($nombre) || empty($catId) || $cantidad <= 0) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de validacion', 'message' => 'Por favor llena todos los campos obligatorios.'];
        redirect('/app/Views/admin/inventario/index.php');
    }

    $costoUnit = $cantidad > 0 ? round($costoTot / $cantidad, 2) : 0;
    $id = $ingredienteModel->guardar([
        'id_categoria'   => $catId,
        'nombre'         => $nombre,
        'unidad_medida'  => $unidad,
        'cantidad_stock' => $cantidad,
        'umbral_minimo'  => (float) ($_POST['umbral_minimo'] ?? 10),
        'costo_unitario' => $costoUnit,
        'precio_extra'   => (float) ($_POST['precio_extra'] ?? 0),
        'proveedor'      => $_POST['proveedor'] ?? null,
    ]);

    $ingredienteModel->moverStock($id, 'entrada', $cantidad, 'Registro inicial de compra', Auth::id());
    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Insumo Registrado', 'message' => 'El insumo ha sido guardado exitosamente.'];
    redirect('/app/Views/admin/inventario/index.php');
}
elseif ($action === 'ajustar') {
    $id     = $_GET['id'] ?? $_POST['id'] ?? '';
    $accion = $_POST['accion'] ?? 'set';
    $valor  = (float) ($_POST['valor'] ?? 1);

    if ($id !== '') {
        if ($accion === 'set') {
            $ingredienteModel->moverStock($id, 'ajuste', $valor, 'Ajuste manual de inventario', Auth::id());
        } else {
            $tipo   = $accion === 'mas' ? 'entrada' : 'salida';
            $motivo = $accion === 'mas' ? 'Reabastecimiento rapido' : 'Salida / merma rapida';
            $ingredienteModel->moverStock($id, $tipo, $valor, $motivo, Auth::id());
        }
        $_SESSION['_flash'][] = ['type' => 'inventory', 'title' => 'Stock Actualizado', 'message' => 'La cantidad del insumo fue actualizada.'];
    }
    redirect('/app/Views/admin/inventario/index.php');
}
else {
    redirect('/app/Views/admin/inventario/index.php');
}

