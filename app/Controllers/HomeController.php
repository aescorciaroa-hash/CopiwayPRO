<?php
/**
 * Controlador de Inicio (Landing Page).
 */
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Core/helpers.php';
require_once __DIR__ . '/../Models/Producto.php';
require_once __DIR__ . '/../Models/Categoria.php';

$productoModel  = new Producto();
$categoriaModel = new Categoria();

$productos  = $productoModel->catalogo();
$categorias = $categoriaModel->menu();

redirect('/');

