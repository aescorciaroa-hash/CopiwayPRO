# 02 · Cómo ejecutar el proyecto

## Requisitos

- **Laragon** (trae Apache + MySQL + PHP).
- El proyecto está en `C:\laragon\www\Copiway2`.
- PHP 8.1, MySQL 8.

## Paso 1 · Iniciar los servicios

Abre Laragon y pulsa **"Iniciar todo"** (Apache + MySQL).

## Paso 2 · Crear la base de datos

El proyecto trae un instalador que **crea la base de datos, las tablas, los triggers
y datos de ejemplo**. En una terminal, dentro de `C:\laragon\www\Copiway2`:

```bash
php database/install.php
```

Esto ejecuta:
- `database/schema.sql` → crea la BD `hamburguer_copiway`, 20 tablas y los triggers.
- `database/seed.sql` → mete datos de prueba (productos, ingredientes, usuarios…).

> ⚠️ **Ojo:** volver a correr `install.php` **borra todos los datos** y los deja como
> el seed. Úsalo solo cuando quieras empezar de cero.

## Paso 3 · Abrir en el navegador

- Con Laragon: `http://copiway2.test`
- Si lo abres como carpeta: `http://localhost/Copiway2/public/`

Las dos funcionan igual: la URL base se detecta sola en `app/Core/helpers.php`
(`APP_BASE`), no hay que editar nada de `config/config.php`.

## Configuración de la conexión

La conexión real está en **`config/database.php`**, con los datos escritos directamente
en el archivo:

```php
$host     = '127.0.0.1';
$user     = 'root';
$password = '';               // Laragon por defecto no tiene contraseña
$dbname   = 'hamburguer_copiway';

$conn = new mysqli($host, $user, $password, $dbname);
```

> ⚠️ **Los datos de conexión están duplicados.** `config/config.php` trae otro bloque
> `'db' => [...]`, y ese lo lee **`database/install.php`** (el instalador), mientras que
> la aplicación se conecta con `config/database.php`. Si cambias la contraseña de MySQL
> **hay que editar los dos archivos**, o el instalador funcionará y la aplicación no (o al
> revés). De `config.php` se usa además `app.base_url`, que lee `app/Core/helpers.php`
> para calcular `APP_BASE`.

## Usuarios de ejemplo (los del seed)

| Rol | Correo | Contraseña | PIN estación |
|-----|--------|-----------|--------------|
| Administrador | `admin@copiway.com` | `admin123` | — |
| Cliente | `cliente@copiway.com` | `cliente123` | — |
| Cocina | `cocina@copiway.com` | `cocina123` | `1234` |
| Domiciliario | `domiciliario@copiway.com` | `domi123` | `5678` |

> Si cambiaste el correo o la contraseña del admin y ya no entras, la forma correcta
> de arreglarlo es generar el hash con PHP:
> ```bash
> php -r "echo password_hash('miNuevaClave', PASSWORD_BCRYPT);"
> ```
> y pegarlo en la columna `contrasena` **usando HeidiSQL** (no `cmd`, porque el `$`
> del hash se rompe en la consola de Windows).

## Ejecutar sin Laragon (solo pruebas)

PHP trae un servidor web incorporado:

```bash
php -S 127.0.0.1:8899 -t public public/router.php
```

Y abres `http://127.0.0.1:8899`. El archivo `public/router.php` solo sirve para este
servidor de pruebas; con Apache manda el `public/.htaccess`.

## El modo debug

En `config/config.php`, `'debug' => true` deja ver los errores de PHP en pantalla
(útil para desarrollar). **Para producción se pone `'debug' => false`**.
