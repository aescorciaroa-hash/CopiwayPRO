<?php
/**
 * Controlador de Ajustes y Cierre de Caja.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Configuracion.php';
require_once __DIR__ . '/../../Models/CierreCaja.php';
require_once __DIR__ . '/../../Models/Pedido.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$configModel = new Configuracion();
$cierreModel = new CierreCaja();

if ($action === 'tarifa') {
    $tarifa = (float) ($_POST['tarifa_plana_domicilio'] ?? 0);
    $configModel->save(['tarifa_plana_domicilio' => $tarifa]);
    $_SESSION['_flash'][] = ['type' => 'config', 'title' => 'Tarifa Actualizada', 'message' => 'La tarifa plana de domicilio ha sido guardada.'];
    redirect('/app/Views/admin/ajustes/index.php');
}
elseif ($action === 'margen') {
    $margen = (float) ($_POST['margen_ganancia_defecto'] ?? 30);
    $configModel->save(['margen_ganancia_defecto' => $margen]);
    $_SESSION['_flash'][] = ['type' => 'config', 'title' => 'Margen Actualizado', 'message' => 'El margen de ganancia ha sido guardado correctamente.'];
    redirect('/app/Views/admin/ajustes/index.php');
}
elseif ($action === 'horario') {
    $apertura = ($_POST['horario_apertura'] ?? '08:00') . ':00';
    $cierre   = ($_POST['horario_cierre'] ?? '23:00') . ':00';
    $configModel->save(['horario_apertura' => $apertura, 'horario_cierre' => $cierre]);
    $_SESSION['_flash'][] = ['type' => 'config', 'title' => 'Horario Actualizado', 'message' => 'El horario de atencion ha sido guardado correctamente.'];
    redirect('/app/Views/admin/ajustes/index.php');
}
elseif ($action === 'pausa') {
    $actual = (bool) $configModel->value('pausa_emergencia_activa');
    $configModel->save(['pausa_emergencia_activa' => $actual ? 0 : 1]);
    $msg = $actual ? 'Recepcion de pedidos reanudada.' : 'Recepcion de pedidos pausada.';
    $_SESSION['_flash'][] = ['type' => 'config', 'title' => 'Estado del Local', 'message' => $msg];
    redirect('/app/Views/admin/ajustes/index.php');
}
elseif ($action === 'generarCierre') {
    $fecha = $_POST['fecha'] ?? date('Y-m-d');
    $adminId = Auth::id() ?? 'admin';
    $cierreModel->generar($fecha, $adminId);
    $_SESSION['_flash'][] = ['type' => 'cierre', 'title' => 'Cierre Generado', 'message' => 'El reporte de cierre de caja fue generado correctamente.'];
    redirect('/app/Views/admin/ajustes/index.php');
}
else {
    redirect('/app/Views/admin/ajustes/index.php');
}

