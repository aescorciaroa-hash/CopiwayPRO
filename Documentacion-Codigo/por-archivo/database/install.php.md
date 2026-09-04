# `database/install.php`

## Ubicación
`database/install.php`

## Propósito
El **instalador**: crea la base de datos desde cero, carga el esquema (tablas + triggers) y
mete los datos de ejemplo. Es lo único que hay que ejecutar para dejar el proyecto listo.

## Cómo se usa

Desde la terminal, en la carpeta del proyecto (**no** desde el navegador):

```bash
php database/install.php
```

> ⚠️ **Borra todo.** `schema.sql` empieza con `DROP DATABASE IF EXISTS hamburguer_copiway`,
> así que volver a correrlo elimina cualquier dato real y lo reemplaza por el seed.

## ⚠️ Es el único archivo que usa PDO

Todo el sistema funciona con **MySQLi**. Este script usa **PDO** porque necesita conectarse
**sin elegir base de datos** (la va a crear él):

```php
$pdo = new PDO(
    "mysql:host={$db['host']};port={$db['port']};charset={$db['charset']}",
    $db['user'], $db['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
```

Fíjate en que **no hay `dbname=`** en la cadena de conexión.

## ⚠️ Y es el único que lee `config/config.php['db']`

```php
$config = require __DIR__ . '/../config/config.php';
$db = $config['db'];
```

El resto del sistema se conecta con `config/database.php`, que tiene sus **propios valores
escritos a mano** y no lee `config.php`.

> **Consecuencia práctica: si cambias la contraseña de MySQL tienes que editar los dos
> archivos.** Solo `config.php` y el instalador funcionará pero la aplicación no; solo
> `database.php` y será al revés.

## Las dos funciones

### `split_sql(string $sql): array`
Parte un archivo `.sql` en sentencias sueltas. No basta con `explode(';')`, porque los
triggers llevan `;` **dentro** de su cuerpo. Por eso el script entiende `DELIMITER`:

```php
if (preg_match('/^DELIMITER\s+(\S+)/i', $trim, $m)) {
    $delimiter = $m[1];    // pasa a ser $$ mientras dure el bloque de triggers
    continue;
}
```

Va acumulando líneas en un buffer y cierra la sentencia cuando la línea termina con el
delimitador vigente. También descarta líneas vacías y comentarios `--`.

### `run_file(PDO $pdo, string $path, string $label): void`
Lee el archivo, lo parte con `split_sql()` y ejecuta cada sentencia en su propio
`try/catch`. **Un error no aborta la instalación**: lo imprime en `STDERR` con los primeros
160 caracteres de la sentencia y sigue. Al final informa `"N/M sentencias ejecutadas"`.

Ese contador es lo que hay que mirar: si `N` es menor que `M`, algo falló.

## El flujo completo

```php
run_file($pdo, __DIR__ . '/schema.sql', 'esquema (tablas + triggers)');
$pdo->exec("USE `{$db['name']}`");                  // el seed necesita la BD seleccionada
run_file($pdo, __DIR__ . '/seed.sql', 'datos de ejemplo (seed)');
```

El `USE` intermedio es necesario porque la conexión se abrió sin base de datos: el esquema
la crea, pero hay que seleccionarla antes de cargar el seed.

Al terminar imprime las credenciales de ejemplo (admin, cliente, cocina y domiciliario con
sus PINs).

## Notas
- Si MySQL no está iniciado, el `catch (PDOException)` da un mensaje claro y sale con
  `exit(1)`.
- `run_file()` avisa y continúa si el archivo no existe, en vez de romperse.
- El mensaje final sugiere `http://copiway.test`; con el nombre actual de la carpeta el
  dominio de Laragon sería `http://copiway2.test`.
