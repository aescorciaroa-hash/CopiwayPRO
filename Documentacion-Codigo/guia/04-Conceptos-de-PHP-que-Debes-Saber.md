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

## 3. Programación Orientada a Objetos (la parte que se usa aquí)

### Clase y objeto

Una **clase** es un molde. Un **objeto** es algo hecho con ese molde.

```php
class Producto {
    private $conn;                        // propiedad (dato del objeto)

    public function __construct() {       // constructor
        global $conn;
        $this->conn = $conn;              // inyecta la conexión global
    }

    public function find($id) { ... }     // método (función del objeto)
}

$productoModel = new Producto();          // creamos un OBJETO
$p = $productoModel->find('abc-123');     // llamamos un metodo con ->
```

- `$this` → "yo mismo", el objeto actual.
- `->` → acceder a una propiedad o método de un objeto.

En Copiway **todos los modelos siguen exactamente este patrón**: constructor que toma
`global $conn`, y métodos que hacen consultas preparadas.

### Visibilidad

| Palabra | Significado |
|---------|-------------|
| `public` | se puede usar desde cualquier lugar |
| `private` | solo dentro de la misma clase |
| `protected` | dentro de la clase y sus hijas (herencia) |

Los modelos guardan `private $conn` y exponen sus consultas como `public function`.

### `static`

Un método `static` **pertenece a la clase**, no a un objeto. Se llama con `::` y
**sin crear objeto**:

```php
Session::start();
Auth::login('cliente', $datos);
Periodo::rango('semana');
Configuracion::value('tarifa_plana_domicilio', 6000);
```

En Copiway son estaticas las utilidades de `app/Core/` (`Session`, `Auth`, `Periodo`),
porque no necesitan guardar estado por objeto. **Los modelos NO son estáticos**: hay
que instanciarlos con `new` porque cada uno guarda su propia conexión.

> Ojo con esto en el examen: se escribe `new Producto()` y `$productoModel->catalogo()`,
> **no** `Producto::catalogo()`.

### Constantes de clase

```php
class Pedido {
    public const ESTADOS = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado', 'cancelado'];
}
```

Se leen con `Pedido::ESTADOS`. `Periodo::OPCIONES` funciona igual.

### Herencia y clases abstractas

PHP las tiene (`extends`, `abstract`), pero **este proyecto no las usa**: no hay clase
base `Model` ni clase base `Controller`. Cada modelo es una clase independiente y cada
controlador es un script plano. Se decidió así para que no haya nada oculto que haya
que explicar.

---

## 4. Cómo se cargan los archivos: `require_once`

**El proyecto no usa namespaces ni autoload.** No hay `namespace App\Core;`, no hay
`use App\Models\Pedido;` y no hay Composer. Cada archivo carga a mano lo que necesita:

```php
require_once __DIR__ . '/../../config/database.php';   // la conexión $conn
require_once __DIR__ . '/../Core/helpers.php';
require_once __DIR__ . '/../Models/Pedido.php';

$pedidoModel = new Pedido();
```

- `require_once` incluye el archivo **una sola vez**, aunque se pida varias veces. Por eso
  no importa que cinco controladores pidan `Session.php`: solo se carga la primera vez.
- `__DIR__` es la carpeta del archivo actual; con `/../` se sube de nivel. Así las rutas
  funcionan sin importar desde dónde se llame al script.
- `public/index.php` carga de entrada el núcleo y **los 11 modelos**, porque es la puerta
  de todas las peticiones.

> Un namespace sería el "apellido" de una clase, para evitar choques de nombres cuando
> hay muchas librerías. Aquí, con 11 modelos propios y sin dependencias externas, no hace
> falta: los nombres no chocan con nada.

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

### `match` (usado en `Periodo::rango`)

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
