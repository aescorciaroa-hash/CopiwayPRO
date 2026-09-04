# `public/index.php`

## Ubicación
`public/index.php` — **~670 líneas**. Es el archivo más largo del proyecto.

## Propósito
El **Front Controller**: la única puerta de entrada del sistema. Todas las peticiones
pasan por aquí (el `.htaccess` reescribe todo hacia este archivo). Hace, en orden:

1. Carga la conexión, el núcleo y **todos** los modelos.
2. Normaliza la URL (`$uri`).
3. Aplica el **guardia de acceso por rol (RBAC)** + el PIN de estación.
4. Despacha los **endpoints AJAX (JSON)**.
5. Despacha los **POST** a los scripts de controlador.
6. Sirve las **vistas imprimibles** (tirilla, reporte de caja).
7. Renderiza las **vistas GET** dentro de su layout.

---

## 1. Arranque

```php
require_once dirname(__DIR__) . '/config/database.php';   // $conn (MySQLi)
require_once dirname(__DIR__) . '/app/Core/helpers.php';  // e(), url(), redirect(), APP_BASE...
require_once dirname(__DIR__) . '/app/Core/Session.php';
require_once dirname(__DIR__) . '/app/Core/Auth.php';
require_once dirname(__DIR__) . '/app/Core/Periodo.php';
// + los 11 modelos de app/Models/
Session::start();
```

No hay autoload: **todo se carga con `require_once` explícito**. Por eso este archivo
incluye los 11 modelos aunque una petición concreta solo use uno o dos.

## 2. Normalización de la URL

```php
$uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
```

Después le quita:
- la **carpeta base** (`APP_BASE`, p. ej. `/Copiway2/public`) cuando se abre desde localhost;
- un posible sufijo `/public` si Apache apunta a la raíz del proyecto.

Así `http://copiway2.test/admin` y `http://localhost/Copiway2/public/admin` llegan las dos
como `$uri = '/admin'`.

## 3. Guardia de acceso (RBAC) — la parte de seguridad

Es una **función anónima que se ejecuta al vuelo**, antes de cualquier enrutado, y vale
tanto para GET como para POST:

```php
(function () use ($uri, $method) { ... })();
```

Pasos:

1. **Rutas 100 % públicas** — `/`, `/home`, `/index.php`, `/login`, `/register`,
   `/forgot`, `/forgot-password`, `/logout`, más `/assets/...`. Salen sin comprobar nada,
   haya sesión o no (así nadie queda atrapado sin poder volver al inicio).
2. **Deduce el rol requerido del prefijo de la ruta**, aceptando también las variantes
   internas `/app/Views/<area>/....php`:

   | Prefijo | Rol exigido |
   |---|---|
   | `/admin` | `admin` |
   | `/client` | `cliente` |
   | `/kitchen` | `cocina` |
   | `/delivery` | `domiciliario` |

   Si la ruta no encaja en ninguno, no se exige nada (la resuelve el enrutado normal).
3. **¿Hay sesión?** `!Auth::check()` → flash + `redirect('/login')`.
4. **¿El rol coincide?** Si no → `redirect(Auth::homeFor(Auth::role()))`: se devuelve al
   usuario a **su propio** panel. **No** se emite un 403.
5. **Segundo factor de estación**: si el rol es `cocina` y no existe
   `Session::get('estacion_cocina_ok')`, redirige a `/kitchen/estacion`. Igual con
   `estacion_domi_ok` y `/delivery/estacion`.

> Este bloque es **el único** control de acceso del sistema. `Auth::requireRole()` existe
> en `app/Core/Auth.php` pero no lo invoca nadie.

## 4. Endpoints AJAX (JSON), solo `GET`

Traducen una ruta REST a `$_GET['action']` + `$_GET['id']` y hacen `require` del script
de controlador, que responde JSON y termina:

| Ruta | Controlador | `action` |
|---|---|---|
| `/client/producto/{id}` | `Client/CatalogoController.php` | `personalizar` |
| `/admin/menu/producto/{id}` | `Admin/MenuController.php` | `cargarProducto` |
| `/admin/comandas/{id}` | `Admin/ComandasController.php` | `detalle` |
| `/admin/personal/{rol}/{id}` | `Admin/PersonalController.php` | `cargar` |
| `/admin/clientes/{id}/historial` | `Admin/ClientesController.php` | `historial` |

`/admin/comandas/{id}` excluye explícitamente `index` y `manual` para no capturar esas rutas.

## 5. Despacho de POST y acciones

Se entra si el método es `POST` **o** si viene `?action=...`.

Antes de despachar, extrae con expresiones regulares los `{id}` y `{rol}` embebidos en la
ruta y los expone como `$_POST['id']` / `$_POST['rol']`, para que los controladores no
tengan que parsear la URL.

Después, según el prefijo de `$uri`, **deduce el `$action` del final de la ruta** y hace
`require` del script correspondiente:

```php
if (str_starts_with($uri, '/admin/ajustes')) {
    if     (str_ends_with($uri, '/tarifa')) $_POST['action'] = 'tarifa';
    elseif (str_ends_with($uri, '/margen')) $_POST['action'] = 'margen';
    // ...
    require dirname(__DIR__) . '/app/Controllers/Admin/AjustesController.php';
    exit;
}
```

Bloques que existen: auth (`/login`, `/register`, `/forgot-password`, `/logout`),
`/admin/comandas`, `/admin/menu`, `/admin/inventario`, `/admin/personal`,
`/admin/clientes`, `/admin/ajustes`, `/client/carrito`, `/client/checkout`,
`/client/creador`, `/client/perfil`, `/client/historial`, `/kitchen/...` y `/delivery/...`.

Dos traducciones a tener en cuenta:
- los formularios de auth no mandan `action`; se deduce de la ruta
  (`/forgot-password` → `forgot`, el resto → el nombre de la ruta);
- en delivery, el segmento `iniciar` de la URL se traduce a la acción `iniciarRuta`.

## 6. Salidas especiales antes del enrutado de vistas

- **`GET /logout`** — `Auth::logout()` + flash + `/login`.
- **`GET /kitchen/pedido/{id}/tirilla`** — carga el pedido, le añade `codigo`, y hace
  `require` de `app/Views/kitchen/tirilla.php` **sin layout** (404 si no existe el pedido).
- **`GET /admin/ajustes/reporte`** — el reporte de cierre imprimible
  (`app/Views/admin/ajustes/reporte.php`), con la fecha en `?fecha=`.

## 7. Enrutado de vistas GET — el `switch ($uri)`

Cada `case` hace lo mismo: **consulta los modelos, captura la vista en un buffer y la
mete en su layout**.

```php
case '/admin/menu':
case '/admin/menu/index':
case '/app/Views/admin/menu/index.php':
    $productos    = (new Producto())->catalogo(false);
    $categorias   = (new Categoria())->conConteo('menu');
    $ingredientes = (new Ingrediente())->conCategoria();

    ob_start();
    require dirname(__DIR__) . '/app/Views/admin/menu/index.php';
    $content = ob_get_clean();                                 // la vista ya renderizada
    require dirname(__DIR__) . '/app/Views/layouts/admin.php';  // el layout imprime $content
    exit;
```

`ob_start()` / `ob_get_clean()` es el sustituto casero de un motor de plantillas: la vista
escribe HTML, se captura en `$content`, y el layout lo imprime en su hueco.

Cada ruta acepta **tres formas**: la limpia (`/admin/menu`), la que termina en `/index`
y la variante interna `/app/Views/...` (que usan algunas redirecciones antiguas).

Vistas que resuelve el `switch`:

| Área | Rutas | Layout |
|---|---|---|
| Auth | `/login`, `/register`, `/forgot-password` | `auth` |
| Cocina | `/kitchen/estacion` | *(sin layout)* |
| Cocina | `/kitchen` | `kitchen` |
| Domiciliario | `/delivery/estacion` | *(sin layout)* |
| Domiciliario | `/delivery` | `delivery` |
| Admin | `/admin`, `/admin/comandas`, `/admin/rutas`, `/admin/menu`, `/admin/inventario`, `/admin/personal`, `/admin/clientes`, `/admin/ajustes` | `admin` |
| Cliente | `/client`, `/client/carrito`, `/client/checkout`, `/client/creador`, `/client/historial`, `/client/ordenes`, `/client/perfil` | `client` |
| Público | `/`, `/home` y el `default` | `public` |

> **Importante:** la lógica de estas pantallas vive aquí dentro, no en un controlador.
> Los antiguos `DashboardController`, `RutasController`, `OrdenesController` y
> `HomeController` fueron absorbidos por estos `case`. Por ejemplo, todo el tablero
> analítico (`Periodo::rango`, `Pedido::kpis`, `ventasPorDia`, `rankingProductos`,
> `recientes`, `contarPorEstado`, `Empleado::activos`, `Configuracion::estadoCocina`)
> está dentro del `case '/admin'`.

El `default` del `switch` sirve la landing pública, así que **no hay página 404**:
cualquier ruta desconocida cae en el inicio.

## Notas
- Es el archivo que hay que leer primero para entender cualquier pantalla: dice qué
  modelo alimenta qué vista.
- No hay tabla de rutas ni clase `Router`: el "enrutador" son estos `if` y este `switch`.
- Ver `guia/07-Rutas-y-Middleware.md` (el flujo general) y `guia/12-Seguridad.md` §5
  (el guardia de acceso en detalle).
