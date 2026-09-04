# 07 · Punto de entrada, rutas y control de acceso

## Todo entra por `public/index.php`

**No hay tabla de rutas ni clase `Router`.** Hay un solo **Front Controller**,
`public/index.php`, al que el `.htaccess` manda todas las peticiones. Ese archivo hace
cuatro cosas, en este orden:

```
1. Normalizar la URL          ->  $uri
2. Guardia de acceso por rol  ->  ¿puede este usuario pedir esta ruta?
3. Despachar POST y AJAX      ->  require de un script de app/Controllers/
4. Renderizar la vista GET    ->  switch ($uri) + ob_start() + layout
```

---

## Normalizar la URL

```php
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
```

Después le quita la carpeta base (`APP_BASE`) y un posible sufijo `/public`, para que
`http://copiway2.test/admin` y `http://localhost/Copiway2/public/admin` lleguen las dos
como `$uri = '/admin'`.

---

## Control de acceso por rol

**No se comprueba en cada vista.** Se comprueba **una sola vez**, en una función anónima
al principio de `public/index.php`, antes de cualquier enrutado y tanto para GET como
para POST:

```php
(function () use ($uri, $method) {
    // 1. rutas publicas (/, /login, /register, /assets/...) salen sin comprobar nada
    $publicas = ['/', '/home', '/index.php', '/login', '/register', '/forgot', '/forgot-password', '/logout'];
    if (in_array($uri, $publicas, true) || str_starts_with($uri, '/assets/')) return;

    // 2. deduce el rol exigido del prefijo de la ruta
    if      (str_starts_with($r, '/admin'))    $rol = 'admin';
    elseif  (str_starts_with($r, '/client'))   $rol = 'cliente';
    elseif  (str_starts_with($r, '/kitchen'))  $rol = 'cocina';
    elseif  (str_starts_with($r, '/delivery')) $rol = 'domiciliario';
    if ($rol === null) return;

    if (!Auth::check())        redirect('/login');
    if (Auth::role() !== $rol) redirect(Auth::homeFor(Auth::role()));

    // 3. segundo factor: PIN de estacion para cocina (RF-54) y domiciliario (RF-64)
    if ($rol === 'cocina'       && !Session::get('estacion_cocina_ok')) redirect('/kitchen/estacion');
    if ($rol === 'domiciliario' && !Session::get('estacion_domi_ok'))   redirect('/delivery/estacion');
})();
```

Puntos a recordar:

- El rol se deduce **del prefijo de la ruta**, no de un archivo de configuración.
- Si el rol no coincide, se **redirige al panel propio del usuario**
  (`Auth::homeFor(...)`); **no** se devuelve un 403.
- Las rutas públicas salen antes de todo, haya sesión o no: así nadie queda atrapado
  sin poder volver al inicio.

| Módulo / Rol | Vistas y controladores asociados |
| :--- | :--- |
| **Cliente** (`cliente`) | `app/Views/client/`, `app/Controllers/Client/` |
| **Cocina** (`cocina`) | `app/Views/kitchen/`, `app/Controllers/Kitchen/` |
| **Domiciliario** (`domiciliario`) | `app/Views/delivery/`, `app/Controllers/Delivery/` |
| **Administrador** (`admin`) | `app/Views/admin/`, `app/Controllers/Admin/` |

> `Auth::requireRole()` existe en `app/Core/Auth.php`, y ese sí devuelve 403, pero hoy
> **no lo llama nadie**: quedó como código muerto de la versión anterior.

---

## Despacho de formularios (POST)

Los formularios apuntan a **rutas limpias**, nunca al archivo del controlador:

```php
<form method="POST" action="<?= url('/admin/ajustes/tarifa') ?>">
    <?= csrf_field() ?>
    <input name="tarifa_plana_domicilio" value="6000">
    <button type="submit">Guardar</button>
</form>
```

`public/index.php` mira la ruta, **deduce el `$action`** y hace `require` del script
de controlador correspondiente:

```php
if (str_starts_with($uri, '/admin/ajustes')) {
    if     (str_ends_with($uri, '/tarifa')) $_POST['action'] = 'tarifa';
    elseif (str_ends_with($uri, '/margen')) $_POST['action'] = 'margen';
    // ...
    require dirname(__DIR__) . '/app/Controllers/Admin/AjustesController.php';
    exit;
}
```

Y el script de controlador solo tiene que mirar esa variable:

```php
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'tarifa') {
    $configModel->save(['tarifa_plana_domicilio' => (float) $_POST['tarifa_plana_domicilio']]);
    $_SESSION['_flash'][] = ['type' => 'config', 'title' => 'Tarifa Actualizada', 'message' => '...'];
    redirect('/admin/ajustes');
}
```

Los `{id}` y `{rol}` que van dentro de la URL (p. ej.
`/admin/personal/cocina/abc-123/baja`) los extrae `public/index.php` con expresiones
regulares y los deja en `$_POST['id']` y `$_POST['rol']`, para que el controlador no
tenga que parsear nada.

### Endpoints AJAX (JSON)

Cinco rutas GET se traducen igual, pero el controlador responde JSON y termina
(alimentan los modales de "Editar" y "Ver detalle"):

| Ruta | `$action` |
|---|---|
| `/client/producto/{id}` | `personalizar` |
| `/admin/menu/producto/{id}` | `cargarProducto` |
| `/admin/comandas/{id}` | `detalle` |
| `/admin/personal/{rol}/{id}` | `cargar` |
| `/admin/clientes/{id}/historial` | `historial` |

---

## Las pantallas (GET) no tienen controlador

El mismo archivo trae un `switch ($uri)` que, para cada pantalla, consulta los modelos,
captura la vista en un buffer y la mete en su layout:

```php
case '/admin/menu':
case '/admin/menu/index':
    $productos    = (new Producto())->catalogo(false);
    $categorias   = (new Categoria())->conConteo('menu');
    $ingredientes = (new Ingrediente())->conCategoria();

    ob_start();
    require dirname(__DIR__) . '/app/Views/admin/menu/index.php';
    $content = ob_get_clean();                                 // la vista ya renderizada
    require dirname(__DIR__) . '/app/Views/layouts/admin.php';  // el layout imprime $content
    exit;
```

`ob_start()` / `ob_get_clean()` es el sustituto casero de un motor de plantillas.

El `default` del `switch` sirve la landing pública, así que **no hay página 404**:
cualquier ruta desconocida cae en el inicio.

---

## Patrón POST → Redirect (PRG)

Todos los procesadores de peticiones `POST` terminan invocando `redirect(...)` en lugar
de imprimir HTML. Esto implementa el patrón **Post-Redirect-Get**, que evita que el
navegador reenvíe el formulario al recargar con F5 y duplique operaciones sensibles como
cobros o creación de pedidos.

El mensaje de resultado viaja en `$_SESSION['_flash']` y lo pinta `partials/toast.php`
en la página siguiente.
