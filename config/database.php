<?php
/**
 * Conexion directa y tradicional a la base de datos usando MySQLi.
 * Exporta la variable global $conn.
 */

$host     = '127.0.0.1';
$user     = 'root';
$password = '';
$dbname   = 'hamburguer_copiway';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexion a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
