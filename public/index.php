<?php
/**
 * Punto de entrada principal (MVC Simple).
 */
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/Core/helpers.php';
require_once dirname(__DIR__) . '/app/Core/Session.php';
require_once dirname(__DIR__) . '/app/Core/Auth.php';
require_once dirname(__DIR__) . '/app/Models/Usuario.php';
require_once dirname(__DIR__) . '/app/Models/Cliente.php';
require_once dirname(__DIR__) . '/app/Models/Producto.php';
require_once dirname(__DIR__) . '/app/Models/Categoria.php';

Session::start();

// Redirecciona a la vista principal
require_once dirname(__DIR__) . '/app/Views/home/index.php';

