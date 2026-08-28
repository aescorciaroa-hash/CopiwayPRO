# Archivo: `public/index.php`

Punto de entrada principal del sistema (MVC Simplificado).

## Descripción

Todas las peticiones públicas pasan por este archivo. Se encarga de requerir la conexión a la base de datos `config/database.php`, los helpers auxiliares `app/Core/helpers.php`, inicializar la sesión y cargar la vista principal.

## Contenido

```php
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/Core/helpers.php';
require_once dirname(__DIR__) . '/app/Core/Session.php';
require_once dirname(__DIR__) . '/app/Core/Auth.php';
require_once dirname(__DIR__) . '/app/Models/Usuario.php';
require_once dirname(__DIR__) . '/app/Models/Cliente.php';
require_once dirname(__DIR__) . '/app/Models/Producto.php';
require_once dirname(__DIR__) . '/app/Models/Categoria.php';

Session::start();

require_once dirname(__DIR__) . '/app/Views/home/index.php';
```
