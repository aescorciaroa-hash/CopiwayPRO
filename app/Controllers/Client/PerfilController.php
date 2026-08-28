<?php
/**
 * Controlador de Perfil y Cuenta del Cliente.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Auth.php';
require_once __DIR__ . '/../../Models/Cliente.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

$clienteModel = new Cliente();

if ($action === 'actualizar') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fechaNac = trim($_POST['fecha_nacimiento'] ?? '');

    if (empty($nombre) || empty($telefono)) {
        $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Revisa el formulario', 'message' => 'Por favor completa todos los campos requeridos.'];
        redirect('/app/Views/client/perfil.php');
    }

    $id = Auth::id();
    if ($id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE CLIENTE SET nombre = ?, telefono = ?, direccion = ?, fecha_nacimiento = ? WHERE id_cliente = ?");
        $dir = $_POST['direccion'] ?? null;
        $stmt->bind_param("sssss", $nombre, $telefono, $dir, $fechaNac, $id);
        $stmt->execute();
        $stmt->close();
        Auth::refresh(['nombre' => $nombre, 'telefono' => $telefono]);
        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Perfil Actualizado', 'message' => 'Tus datos personales se guardaron correctamente.'];
    }
    redirect('/app/Views/client/perfil.php');
}
elseif ($action === 'password') {
    $actual    = $_POST['actual'] ?? '';
    $nueva     = $_POST['nueva'] ?? '';
    $confirmar = $_POST['confirmar'] ?? '';

    $id = Auth::id();
    if ($id) {
        $cli = $clienteModel->directorio();
        global $conn;
        $stmt = $conn->prepare("SELECT contrasena FROM CLIENTE WHERE id_cliente = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row || !password_verify($actual, $row['contrasena'])) {
            $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Contraseña incorrecta', 'message' => 'La contraseña actual no coincide.'];
            redirect('/app/Views/client/perfil.php');
        }

        if (strlen($nueva) < 6 || $nueva !== $confirmar) {
            $_SESSION['_flash'][] = ['type' => 'danger', 'title' => 'Revisa los datos', 'message' => 'La nueva contraseña debe tener 6+ caracteres y coincidir.'];
            redirect('/app/Views/client/perfil.php');
        }

        $hash = password_hash($nueva, PASSWORD_BCRYPT);
        $stmtU = $conn->prepare("UPDATE CLIENTE SET contrasena = ? WHERE id_cliente = ?");
        $stmtU->bind_param("ss", $hash, $id);
        $stmtU->execute();
        $stmtU->close();

        $_SESSION['_flash'][] = ['type' => 'success', 'title' => 'Copiway', 'message' => 'Contraseña actualizada exitosamente.'];
    }
    redirect('/app/Views/client/perfil.php');
}
else {
    redirect('/app/Views/client/perfil.php');
}

