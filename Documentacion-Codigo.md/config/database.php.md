# Archivo: `config/database.php`

Conexión directa a MySQL utilizando la extensión nativa **MySQLi** de PHP.

## Descripción

Este archivo inicializa la conexión a la base de datos MySQL y la exporta en la variable global `$conn`. Elimina abstracciones de nivel framework como PDO y el patrón Singleton para un enfoque plano y tradicional.

## Contenido

- Definición de credenciales de conexión (`$host`, `$user`, `$password`, `$dbname`).
- Instanciación de `new mysqli(...)`.
- Configuración de cotejo de caracteres a `utf8mb4`.
- Manejo de errores de conexión con `if ($conn->connect_error) { die(...); }`.
