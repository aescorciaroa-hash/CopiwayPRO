# `config/database.php`

## Ubicación
`config/database.php`

## Propósito
Abre la conexión a MySQL con la extensión nativa **MySQLi** y la deja en la variable
global **`$conn`**. Es lo primero que carga `public/index.php` y todos los controladores.

## Contenido completo

```php
<?php
$host     = '127.0.0.1';
$user     = 'root';
$password = '';
$dbname   = 'hamburguer_copiway';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexion a la base de datos: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
```

- **Credenciales escritas en el archivo.** Son las de Laragon por defecto: `root` sin
  contraseña. **Si cambias la clave de MySQL, este es el archivo que hay que editar.**
- `new mysqli(...)` abre la conexión al incluirse el archivo.
- `die()` si falla: el sistema no arranca sin base de datos.
- `set_charset("utf8mb4")` para que tildes y emojis se guarden bien.

## Cómo la usan los modelos

Cada modelo la recibe en su constructor y la guarda en una propiedad privada:

```php
class Producto
{
    private $conn;
    public function __construct()
    {
        global $conn;            // toma la variable global
        $this->conn = $conn;     // y la guarda como propiedad
    }
}
```

Después todas las consultas van con sentencias preparadas:
`$this->conn->prepare(...)` → `bind_param(...)` → `execute()` → `get_result()`.

## Notas
- **Sin PDO y sin patrón Singleton.** Es una conexión global y directa, a propósito, para
  que no haya capas de abstracción que explicar.
- `require_once` garantiza que la conexión se abre **una sola vez** por petición, aunque
  cinco archivos distintos pidan este mismo fichero.
- ⚠️ `config/config.php` también trae un bloque `'db' => [...]`. **Este archivo no lo
  lee**, pero `database/install.php` **sí**. O sea que los datos de conexión están
  **duplicados en dos sitios**: si cambias la contraseña de MySQL hay que editar los dos,
  o el instalador y la aplicación dejarán de coincidir.
- Al estar dentro de `config/`, este archivo no debería ser alcanzable desde el navegador.
  Ver `../../guia/12-Seguridad.md` §10 sobre en qué condiciones eso se cumple.
