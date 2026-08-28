<?php
/**
 * Controlador del Tablero Analitico.
 */
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Core/Periodo.php';
require_once __DIR__ . '/../../Models/Pedido.php';
require_once __DIR__ . '/../../Models/Empleado.php';
require_once __DIR__ . '/../../Models/Configuracion.php';

redirect('/admin');

