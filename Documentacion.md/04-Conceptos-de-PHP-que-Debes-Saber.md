# 04 · Conceptos de PHP que debes saber

Esta es la parte más importante para el examen. Cada concepto va con un ejemplo
**real del proyecto**.

---

## 1. PHP se ejecuta en el servidor

El navegador pide una URL → el servidor ejecuta el PHP → devuelve **HTML ya hecho**.
El usuario nunca ve el código PHP, solo el resultado.

```php
<?php $nombre = "Andrés"; ?>
<p>Hola <?= $nombre ?></p>     <!-- el navegador recibe: <p>Hola Andrés</p> -->
```

- `<?php ... ?>` → bloque de código PHP.
- `<?= $x ?>` → atajo de `<?php echo $x; ?>` (imprime un valor).

---

## 2. Tipos de datos y variables

```php
$texto   = "hola";        // string
$numero  = 42;            // int
$decimal = 15000.50;      // float
$activo  = true;          // bool
$lista   = [1, 2, 3];     // array (lista)
$persona = ['nombre' => 'Ana', 'edad' => 20];  // array asociativo (clave => valor)
$nada    = null;          // null (ausencia de valor)
```

En este proyecto casi todo lo que sale de la base de datos es un **array asociativo**:

```php
$cliente = ['id_cliente' => 'abc-123', 'nombre' => 'Juan', 'puntos_fidelidad' => 50];
echo $cliente['nombre'];   // Juan
```

---

## 3. Programación Orientada a Objetos (POO)

### Clase y objeto

Una **clase** es un molde. Un **objeto** es algo hecho con ese molde.

```php
class Router {
    private array $routes = [];          // propiedad (dato del objeto)

    public function get($path, $action) { // método (función del objeto)
        $this->routes[] = [$path, $action];
    }
}

$router = new Router();      // creamos un OBJETO
$router->get('/', 'HomeController@index');   // llamamos un método con ->
```

- `$this` → "yo mismo", el objeto actual.
- `->` → acceder a una propiedad o método de un objeto.

### Visibilidad

| Palabra | Significado |
|---------|-------------|
| `public` | se puede usar desde cualquier lugar |
| `private` | solo dentro de la misma clase |
| `protected` | dentro de la clase y sus hijas (herencia) |

### `static` (muy usado en este proyecto)

Un método/propiedad `static` **pertenece a la clase**, no a un objeto. Se llama con
`::` y **sin crear objeto**.

```php
class Database {
    public static function all(string $sql, array $params = []): array { ... }
}

// No hago "new Database()". Llamo directo:
$productos = Database::all("SELECT * FROM PRODUCTO");
```

En Copiway, `Database`, `Auth`, `Session`, `Producto::catalogo()`, etc. son estáticos:
son "utilidades" que no necesitan guardar estado por objeto.

### Herencia (`extends`) y clases abstractas

```php
abstract class Model {                 // abstract = no se puede instanciar sola
    protected static string $table = '';
    public static function find($id): ?array { ... }
}

class Cliente extends Model {           // Cliente HEREDA todo lo de Model
    protected static string $table = 'CLIENTE';
    protected static string $key = 'id_cliente';
}

Cliente::find('abc-123');   // usa el find() heredado, pero con la tabla CLIENTE
```

`Controller` también es abstracta: da métodos comunes (`view()`, `json()`, `validate()`)
a todos los controladores.

---

## 4. Namespaces y autoload

### Namespace = "apellido" de la clase

Evita choques de nombres. Se declara arriba del archivo:

```php
namespace App\Core;      // este archivo vive en el namespace App\Core

class Router { ... }      // su nombre completo es App\Core\Router
```

Para usar una clase de otro namespace:

```php
use App\Models\Pedido;   // "importo" el nombre
$p = Pedido::find($id);   // ahora puedo escribir solo "Pedido"
```

### Inclusiones con `require_once`

Se utiliza la función `require_once` para cargar los modelos e intermediarios necesarios en cada controlador o script:

```php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../Models/Pedido.php';

$pedidoModel = new Pedido();
```

---

## 5. Arrays y funciones útiles

```php
$totales = array_map(fn($p) => (float) $p['total'], $ventas);  // transforma cada elemento
$suma    = array_sum($totales);                                 // suma la lista
$activos = array_filter($lista, fn($x) => $x['estado'] === 'activo'); // filtra
foreach ($productos as $p) { echo $p['nombre']; }               // recorre
```

- `fn($x) => ...` → **arrow function** (función corta de una sola expresión).
  Puede usar variables de fuera sin `use`.
- `function ($x) use ($otra) { ... }` → **closure** normal; para usar variables de
  fuera hay que listarlas en `use`.

---

## 6. Operadores modernos (PHP 7/8)

| Operador | Nombre | Ejemplo | Qué hace |
|----------|--------|---------|----------|
| `??` | *null coalescing* | `$_GET['q'] ?? ''` | si no existe o es null, usa lo de la derecha |
| `?:` | *elvis* | `$nombre ?: 'Anónimo'` | si es "falsy", usa lo de la derecha |
| `?->` | *nullsafe* | `$user?->getName()` | si `$user` es null, no explota, devuelve null |
| `...` | *spread* | `$fn(...$params)` | "desempaqueta" un array como argumentos |
| `[...]` | *destructuring* | `[$a, $b] = [1, 2]` | asigna varias variables a la vez |

### `match` (usado en `Auth`, `Controller::validate`, `Periodo`)

Es como un `switch` pero:
- compara con `===` (tipo estricto),
- **devuelve un valor**,
- no necesita `break`.

```php
$panel = match ($rol) {
    'admin'        => '/admin',
    'cocina'       => '/kitchen',
    'domiciliario' => '/delivery',
    default        => '/client',
};
```

---

## 7. MySQLi: hablar con MySQL

**MySQLi** es la extensión nativa de PHP para conectarse y realizar operaciones en bases de datos MySQL.

### Conexión (en `config/database.php`)

```php
$host = '127.0.0.1';
$user = 'root';
$password = '';
$dbname = 'hamburguer_copiway';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
```

### Consultas preparadas (¡lo más importante para el examen!)

**Nunca** se concatena la entrada del usuario directamente en la SQL:

```php
// ❌ MAL (Vulnerable a inyección SQL)
$result = $conn->query("SELECT * FROM CLIENTE WHERE correo = '$correo'");
```

**Siempre** se utiliza `prepare` y `bind_param`:

```php
// ✅ BIEN
$stmt = $conn->prepare("SELECT * FROM CLIENTE WHERE correo = ?");
$stmt->bind_param("s", $correo); // "s" indica tipo string
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();
$stmt->close();
```

PHP y MySQL tratan `$correo` como **dato puro**, nunca como código SQL ejecutable. Esto evita ataques de inyección SQL.

---

## 8. Sesiones

HTTP "no tiene memoria": cada petición es independiente. La **sesión** permite
recordar cosas entre páginas (que el usuario está logueado, su carrito…).

```php
session_start();                       // arranca/retoma la sesión (cookie PHPSESSID)
$_SESSION['auth_role'] = 'admin';      // guardar
$rol = $_SESSION['auth_role'] ?? null; // leer
unset($_SESSION['auth_role']);         // borrar
```

Copiway lo envuelve en la clase `Session` (`Session::set()`, `::get()`, `::forget()`).
El carrito, por ejemplo, vive en `$_SESSION['carrito']`.

### Mensajes "flash"

Son mensajes que **duran una sola petición**: se guardan, se muestran en la siguiente
página (como un *toast*) y se borran.

```php
Session::flash('success', 'Ingreso exitoso', 'Bienvenido');
// ... redirect ...
// en la siguiente página, el layout llama Session::pullFlash() y los pinta
```

---

## 9. Contraseñas: hashing

**Nunca** se guarda la contraseña tal cual. Se guarda un **hash** (un texto irreversible).

```php
// Al registrarse:
$hash = password_hash('miClave123', PASSWORD_BCRYPT);
// -> $2y$10$abcdef...  (60 caracteres, distinto cada vez por la "sal")

// Al iniciar sesión:
if (password_verify('loQueEscribio', $hashGuardado)) {
    // contraseña correcta
}
```

- `bcrypt` es **lento a propósito**, para que un atacante no pueda probar millones
  de claves por segundo.
- Dos usuarios con la misma clave tienen hashes distintos (por la *sal* aleatoria).

---

## 10. `Buffer de salida` (output buffering)

Sirve para "capturar" el HTML que genera un archivo en vez de mandarlo directo al
navegador. El motor de vistas lo usa:

```php
ob_start();          // empieza a "grabar" todo lo que se imprima
require $file;        // el archivo de la vista imprime HTML... pero se guarda
$content = ob_get_clean();  // $content = ese HTML, y deja de grabar

// luego mete $content dentro del layout y ahí sí lo devuelve
```

---

## 11. Excepciones (manejo de errores)

```php
try {
    $pdo->beginTransaction();
    // ... varios INSERT ...
    $pdo->commit();               // si todo salió bien, confirma
} catch (\Throwable $e) {
    $pdo->rollBack();             // si algo falló, DESHACE todo
    throw $e;                     // y vuelve a lanzar el error
}
```

Esto se usa en `PedidoServicio::crear()`: crear un pedido son **muchos INSERT**
(pedido + detalles + personalizaciones + pago). Si uno falla, se deshacen todos con
`rollBack()`. Esto es una **transacción**: "todo o nada".

---

## 12. Tipado (type hints)

PHP moderno permite decir de qué tipo es cada cosa:

```php
public static function rango(string $clave): array { ... }
//                          ^tipo del parámetro  ^tipo que devuelve

public function user(): ?array { ... }   // ?array = "array O null"
```

Ayuda a detectar errores antes y a que el código se entienda.
