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
        redirect('/login');
    }

    $cuenta = $usuarioModel->porCorreo($correo);

    if (!$cuenta || !$usuarioModel->verificarPassword($contrasena, $cuenta['row']['contrasena'])) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Error de ingreso', 'message' => 'Correo o contraseña incorrectos.'];
        redirect('/login');
    }

    if (!$cuenta['activo']) {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Cuenta inactiva', 'message' => 'Esta cuenta esta inactiva. Contacta al administrador.'];
        redirect('/login');
    }

    $sesion = [
        'id'       => $cuenta['id'],
        'nombre'   => $cuenta['nombre'],
        'correo'   => $cuenta['correo'],
        'telefono' => $cuenta['telefono'],
    ];
    // Guardamos tambien el id con el nombre de columna propio del rol,
    // que es el que usan las vistas de cada panel.
    if ($cuenta['role'] === 'cliente') {
        $sesion['id_cliente'] = $cuenta['id'];
    } elseif ($cuenta['role'] === 'cocina') {
        $sesion['id_ayudante'] = $cuenta['id'];
    } elseif ($cuenta['role'] === 'domiciliario') {
        $sesion['id_domiciliario']      = $cuenta['id'];
        $sesion['estado_disponibilidad'] = $cuenta['row']['estado_disponibilidad'] ?? 'desconectado';
    }

    Auth::login($cuenta['role'], $sesion);

    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Bienvenido', 'message' => 'Ingreso exitoso.'];

    if ($cuenta['role'] === 'admin')        redirect('/admin');
    if ($cuenta['role'] === 'cocina')       redirect('/kitchen');
    if ($cuenta['role'] === 'domiciliario') redirect('/delivery');
    redirect('/client');
}
elseif ($action === 'register') {
    $nombre     = trim($_POST['nombre'] ?? '');
    $correo     = trim($_POST['correo'] ?? '');
    $telefono   = trim($_POST['telefono'] ?? '');
    $fechaNac   = trim($_POST['fecha_nacimiento'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar  = $_POST['contrasena_confirmation'] ?? '';
    $habeas     = !empty($_POST['habeas_data']);

    $errores = [];
    if ($nombre === '')                                    $errores['nombre']   = 'Ingresa tu nombre.';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL))       $errores['correo']   = 'Ingresa un correo valido.';
    if ($telefono === '')                                  $errores['telefono'] = 'Ingresa tu telefono.';
    if ($fechaNac === '' || !strtotime($fechaNac))         $errores['fecha_nacimiento'] = 'Ingresa tu fecha de nacimiento.';
    if (strlen($contrasena) < 6)                           $errores['contrasena'] = 'Minimo 6 caracteres.';
    if ($contrasena !== $confirmar)                        $errores['contrasena_confirmation'] = 'Las contrasenas no coinciden.';
    if (!$habeas)                                          $errores['habeas_data'] = 'Debes aceptar el tratamiento de datos.';

    if ($errores) {
        $_SESSION['_errors'] = $errores;
        $_SESSION['_old'] = ['nombre' => $nombre, 'correo' => $correo, 'telefono' => $telefono, 'fecha_nacimiento' => $fechaNac, 'habeas_data' => $habeas ? '1' : ''];
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Revisa el formulario', 'message' => 'Hay campos por corregir.'];
        redirect('/register');
    }

    if ($usuarioModel->existeCorreoOTelefono($correo, $telefono)) {
        $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Registro existente', 'message' => 'Este correo o telefono ya esta registrado.'];
        redirect('/register');
    }
    unset($_SESSION['_errors'], $_SESSION['_old']);

    $id = $clienteModel->registrar([
        'nombre'           => $nombre,
        'correo'           => $correo,
        'telefono'         => $telefono,
        'fecha_nacimiento' => $fechaNac,
        'contrasena'       => $contrasena,
    ]);

    Auth::login('cliente', [
        'id'         => $id,
        'id_cliente' => $id,
        'nombre'     => $nombre,
        'correo'     => $correo,
        'telefono'   => $telefono,
    ]);

    $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Registro completado', 'message' => 'Tu cuenta ha sido creada exitosamente.'];
    redirect('/client');
}
elseif ($action === 'forgot') {
    $_SESSION['_flash'][] = ['type' => 'info', 'title' => 'Recuperacion', 'message' => 'Si el correo existe, enviamos un codigo de verificacion.'];
    redirect('/login');
}
elseif ($action === 'logout') {
    Auth::logout();
    $_SESSION['_flash'][] = ['type' => 'warning', 'title' => 'Sesion cerrada', 'message' => 'Has salido de tu cuenta.'];
    redirect('/login');
}
else {
    redirect('/login');
}

