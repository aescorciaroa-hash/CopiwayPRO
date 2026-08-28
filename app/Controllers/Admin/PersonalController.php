<?php
/**
 * Controlador de Equipo y Personal.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Empleado.php';
require_once __DIR__ . '/../../Models/Usuario.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$empleadoModel = new Empleado();
$usuarioModel  = new Usuario();

if ($action === 'crear') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $rol        = $_POST['rol'] ?? 'cocina';

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Revisa el formulario', 'message' => 'Llene todos los campos obligatorios.'];
        redirect('/app/Views/admin/personal/index.php');
    }

    if ($usuarioModel->existeCorreoOTelefono($correo, $telefono)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Duplicado', 'message' => 'Ese correo o telefono ya esta en uso.'];
        redirect('/app/Views/admin/personal/index.php');
    }

    $adminId = Auth::id() ?? 'admin';
    $empleadoModel->crear($rol, $_POST, $adminId);

    $_SESSION['_flash'][] = ['type' => 'staff', 'title' => 'Colaborador Registrado', 'message' => 'El empleado ha sido creado exitosamente.'];
    redirect('/app/Views/admin/personal/index.php');
}
elseif ($action === 'actualizar') {
    $rol = $_GET['rol'] ?? $_POST['rol'] ?? 'cocina';
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $empleadoModel->actualizar($rol, $id, $_POST);
    $_SESSION['_flash'][] = ['type' => 'staff', 'title' => 'Colaborador Actualizado', 'message' => 'Los datos del empleado han sido actualizados correctamente.'];
    redirect('/app/Views/admin/personal/index.php');
}
elseif ($action === 'baja') {
    $rol = $_GET['rol'] ?? $_POST['rol'] ?? 'cocina';
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $empleadoModel->darDeBaja($rol, $id);
    $_SESSION['_flash'][] = ['type' => 'staff', 'title' => 'Colaborador Dado de Baja', 'message' => 'El acceso del empleado fue revocado.'];
    redirect('/app/Views/admin/personal/index.php');
}
elseif ($action === 'reactivar') {
    $rol = $_GET['rol'] ?? $_POST['rol'] ?? 'cocina';
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $empleadoModel->reactivar($rol, $id);
    $_SESSION['_flash'][] = ['type' => 'staff', 'title' => 'Colaborador Reactivado', 'message' => 'El empleado vuelve a tener acceso.'];
    redirect('/app/Views/admin/personal/index.php');
}
elseif ($action === 'eliminar') {
    $rol = $_GET['rol'] ?? $_POST['rol'] ?? 'cocina';
    $id  = $_GET['id'] ?? $_POST['id'] ?? '';
    $ok = $empleadoModel->eliminar($rol, $id);
    if ($ok) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Gestion Humana', 'message' => 'Colaborador eliminado definitivamente.'];
    } else {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'No se puede eliminar', 'message' => 'El empleado tiene historial de pedidos.'];
    }
    redirect('/app/Views/admin/personal/index.php');
}
else {
    redirect('/app/Views/admin/personal/index.php');
}

