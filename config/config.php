<?php
/**
 * Configuracion general del sistema Copiway.
 * Ajusta aqui los datos de conexion si tu entorno cambia.
 */

return [

    // ---- Base de datos (Laragon / MySQL) ----
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'name'     => 'hamburguer_copiway',
        'user'     => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],

    // ---- Aplicacion ----
    'app' => [
        'name'     => 'CopiwayPRO',
        'nombre_legal' => 'Hamburguer Copiway',
        // URL base publica. Con Laragon suele ser http://copiway.test
        // Si abres el proyecto como http://localhost/Copiway/public deja '/Copiway/public'
        'base_url' => '',
        'timezone' => 'America/Bogota',
        'debug'    => true,
    ],

    // ---- Rutas de carpetas ----
    'paths' => [
        'root'    => dirname(__DIR__),
        'app'     => dirname(__DIR__) . '/app',
        'views'   => dirname(__DIR__) . '/app/Views',
        'uploads' => dirname(__DIR__) . '/public/uploads',
        'storage' => dirname(__DIR__) . '/storage',
    ],
];
