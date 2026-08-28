<?php
/**
 * Controlador de Autenticacion (login, registro, recuperacion, logout).
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Core/helpers.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Core/Auth.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Cliente.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$usuarioModel = new Usuario();
$clienteModel = new Cliente();

if ($action === 'login') {
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($correo) || empty($contrasena)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de ingreso', 'message' => 'Ingresa correo y contraseña.'];
        redirect('/app/Views/auth/login.php');
    }

    $cuenta = $usuarioModel->porCorreo($correo);

    if (!$cuenta || !$usuarioModel->verificarPassword($contrasena, $cuenta['row']['contrasena'])) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de ingreso', 'message' => 'Correo o contraseña incorrectos.'];
        redirect('/app/Views/auth/login.php');
    }

    if (!$cuenta['activo']) {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Cuenta inactiva', 'message' => 'Esta cuenta esta inactiva. Contacta al administrador.'];
        redirect('/app/Views/auth/login.php');
    }

    Auth::login($cuenta['role'], [
        'id'       => $cuenta['id'],
        'nombre'   => $cuenta['nombre'],
        'correo'   => $cuenta['correo'],
        'telefono' => $cuenta['telefono'],
    ]);

    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Bienvenido', 'message' => 'Ingreso exitoso.'];

    if ($cuenta['role'] === 'admin')        redirect('/app/Views/admin/index.php');
    if ($cuenta['role'] === 'cocina')       redirect('/app/Views/kitchen/index.php');
    if ($cuenta['role'] === 'domiciliario') redirect('/app/Views/delivery/index.php');
    redirect('/app/Views/client/catalogo.php');
}
elseif ($action === 'register') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $fechaNac   = trim($_POST['fecha_nacimiento'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (empty($nombre) || empty($correo) || empty($contrasena)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de registro', 'message' => 'Por favor completa todos los campos requeridos.'];
        redirect('/app/Views/auth/register.php');
    }

    if ($usuarioModel->existeCorreoOTelefono($correo, $telefono)) {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Registro existente', 'message' => 'Este correo o telefono ya esta registrado.'];
        redirect('/app/Views/auth/register.php');
    }

    $id = $clienteModel->registrar([
        'nombre'           => $nombre,
        'correo'           => $correo,
        'telefono'         => $telefono,
        'fecha_nacimiento' => $fechaNac,
        'contrasena'       => $contrasena,
    ]);

    Auth::login('cliente', [
        'id'       => $id,
        'nombre'   => $nombre,
        'correo'   => $correo,
        'telefono' => $telefono,
    ]);

    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Registro completado', 'message' => 'Tu cuenta ha sido creada exitosamente.'];
    redirect('/app/Views/client/catalogo.php');
}
elseif ($action === 'forgot') {
    $_SESSION['_flash'][] = ['type' => 'info', 'title' => 'Recuperacion', 'message' => 'Si el correo existe, enviamos un codigo de verificacion.'];
    redirect('/app/Views/auth/login.php');
}
elseif ($action === 'logout') {
    Auth::logout();
    $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Sesion cerrada', 'message' => 'Has salido de tu cuenta.'];
    redirect('/app/Views/auth/login.php');
}
else {
    redirect('/app/Views/auth/login.php');
}

