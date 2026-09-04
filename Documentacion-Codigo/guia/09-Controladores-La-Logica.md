# 09 · Controladores (la lógica)

## Qué es un controlador en este proyecto

**No es una clase.** Es un **script PHP plano** que se ejecuta de arriba abajo, mira la
variable `$action` y hace lo que toque. Siempre tiene la misma forma:

```php
<?php
// 1. Cargar lo que necesita (no hay autoload)
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../Core/helpers.php';
require_once __DIR__ . '/../../Core/Session.php';
require_once __DIR__ . '/../../Models/LoQueSea.php';

// 2. Leer la accion pedida
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// 3. Instanciar los modelos
$modelo = new LoQueSea();

// 4. Una rama por accion
if ($action === 'guardar') {
    // validar $_POST -> llamar al modelo -> dejar un flash -> redirigir
    $_SESSION['_flash'][] = ['type' => 'success', 'title' => '...', 'message' => '...'];
    redirect('/admin/loquesea');
}
elseif ($action === 'otra') { ... }
else {
    redirect('/admin/loquesea');   // accion desconocida: de vuelta a la pantalla
}
```

Puntos clave:

- **No hay clase base `Controller`**, ni `$this->view()`, ni `$this->json()`, ni
  `$this->validate()`. Cada script valida a mano con `if` y `trim()`.
- **No hay namespaces.** Todo se carga con `require_once` y se instancia con `new`.
- **Los controladores no pintan pantallas.** Solo procesan POST y devuelven JSON. Las
  vistas GET las arma `public/index.php`.
- Toda acción POST termina en `redirect(...)` → patrón **Post-Redirect-Get**.

## Quién decide el `$action`

Los formularios **no** mandan un campo `action`. Lo deduce `public/index.php` mirando el
final de la ruta, y recién entonces hace `require` del script:

```php
if (str_starts_with($uri, '/admin/menu')) {
    if     (str_ends_with($uri, '/producto')) $_POST['action'] = 'guardarProducto';
    elseif (str_contains($uri, '/estado'))    $_POST['action'] = 'cambiarEstado';
    // ...
    require dirname(__DIR__) . '/app/Controllers/Admin/MenuController.php';
    exit;
}
```

También extrae los `{id}` y `{rol}` de la URL y los deja en `$_POST['id']` / `$_POST['rol']`.

## Dónde está la lógica de cada pantalla GET

Las pantallas **no tienen controlador**. Su código está en el `switch ($uri)` de
`public/index.php`, que consulta los modelos y renderiza la vista con su layout:

| Pantalla | Dónde está su lógica |
|---|---|
| `/` (landing) | `public/index.php`, `case '/'` (era `HomeController`) |
| `/admin` (tablero) | `public/index.php`, `case '/admin'` (era `DashboardController`) |
| `/admin/rutas` | `public/index.php`, `case '/admin/rutas'` (era `RutasController`) |
| `/client/ordenes` | `public/index.php`, `case '/client/ordenes'` (era `OrdenesController`) |
| El resto de pantallas | su `case` correspondiente en `public/index.php` |

> Esos cuatro controladores **ya no existen como archivo**: se absorbieron en el front
> controller durante la simplificación.

---

## `AuthController.php` — login, registro, recuperación

| `$action` | Ruta | Qué hace |
|---|---|---|
| `login` | POST `/login` | `Usuario::porCorreo` → `verificarPassword` (bcrypt) → comprueba `activo` → `Auth::login` → redirige según el rol |
| `register` | POST `/register` | Valida campo por campo, exige **habeas data**, rechaza correo/teléfono duplicado, `Cliente::registrar` y entra directo |
| `forgot` | POST `/forgot-password` | Flujo **simulado**: siempre el mismo mensaje, no consulta la BD |
| `logout` | POST `/logout` | `Auth::logout()` + flash + `/login` |

En el registro, los errores se guardan en `$_SESSION['_errors']` y lo escrito en
`$_SESSION['_old']`; la vista los lee con los helpers `error()` y `old()`.

> El logout **por GET** no pasa por aquí: lo resuelve `public/index.php` directamente.
> Y no existe ningún login de desarrollo sin contraseña.

---

## Panel del **Administrador** (`app/Controllers/Admin/`)

### `ComandasController.php`
| `$action` | Qué hace |
|---|---|
| `detalle` | `Pedido::completo($id)` + `codigo` → **JSON** para el modal (404 si no existe) |
| `crearManual` | Pedido por teléfono/WhatsApp: `PedidoServicio::clienteParaManual()` busca o crea el cliente por teléfono, y `PedidoServicio::crear()` va con **`aprobar_pago = true`** (entra directo a cocina) |
| `editarDireccion` | `PedidoServicio::editarDireccion($id, $dir)` |

### `MenuController.php`
| `$action` | Qué hace |
|---|---|
| `cargarProducto` | JSON del producto **+ su receta**, para el modal de edición |
| `guardarProducto` | Crea o edita el producto y **su receta** (arrays `receta_ingrediente[]` / `receta_cantidad[]`) |
| `cambiarEstado` | Alterna `activo` ↔ `oculto` |
| `eliminarProducto` | `Producto::eliminar($id)`; si devuelve `false` (tiene ventas) avisa y no borra |
| `crearCategoria` / `eliminarCategoria` | Categorías de ámbito `menu` |

### `InventarioController.php`
| `$action` | Qué hace |
|---|---|
| `guardarInsumo` | Calcula `costo_unitario = costo_total / cantidad`, crea el insumo **con stock 0** y registra un movimiento de `entrada` con la cantidad real |
| `ajustar` | `set` → movimiento `ajuste`; `mas` → `entrada`; `menos` → `salida`. Todo vía `Ingrediente::moverStock` |

### `PersonalController.php`
| `$action` | Qué hace |
|---|---|
| `cargar` | JSON del empleado **sin la contraseña**, para el modal |
| `crear` | Valida, rechaza duplicados con `Usuario::existeCorreoOTelefono`, `Empleado::crear` |
| `actualizar` | `Empleado::actualizar($rol, $id, $_POST)` |
| `baja` / `reactivar` | **Soft delete**: `activo = 0` / `activo = 1` |
| `eliminar` | Solo si el empleado no tiene pedidos; si no, avisa y no borra |

### `ClientesController.php`
| `$action` | Qué hace |
|---|---|
| `historial` | JSON con el cliente (sin `contrasena`) y sus pedidos con `codigo` |

### `AjustesController.php`
| `$action` | Qué hace |
|---|---|
| `tarifa` / `margen` / `horario` | `Configuracion::save([...])` |
| `pausa` | **Alterna** `pausa_emergencia_activa` |
| `generarCierre` | `CierreCaja::generar($fecha, Auth::id())` |

> No hay acción de "vista previa" del cierre: la pantalla ya recibe el cálculo desde
> `public/index.php`.

---

## Panel del **Cliente** (`app/Controllers/Client/`)

### `CatalogoController.php`
| `$action` | Qué hace |
|---|---|
| `personalizar` | JSON del producto + `agotado` + `personalizables` (los ingredientes que se pueden quitar o agregar) |

### `CarritoController.php`
| `$action` | Qué hace |
|---|---|
| `agregar` | Rechaza el producto si `estaAgotado()`; convierte `quitar[]` / `extra[]` en personalizaciones y llama `Carrito::agregar` |
| `actualizar` | Cambia cantidad y, solo si el formulario las mandó, las personalizaciones |
| `quitar` / `vaciar` | Sobre `$_SESSION['carrito']` |

La función auxiliar `personalizacionesDesdePost()` es la que traduce el formulario:
los `quitar[]` son **SIN** (costo 0) y los `extra[]` son **EXTRA** (con `precio_extra`),
descartando los ingredientes sin stock.

### `CreadorController.php`
| `$action` | Qué hace |
|---|---|
| `agregar` | Reutiliza (o crea) el producto oculto **"Hamburguesa Personalizada"** y mete cada capa como un extra. El precio de la capa es su `precio_extra`, o `costo_unitario * (1 + margen/100)` si no tiene |

### `CheckoutController.php`
| `$action` | Qué hace |
|---|---|
| `confirmar` | Bloquea si la cocina está cerrada, exige dirección, calcula el 15% de cumpleaños, `PedidoServicio::crear()` con **`aprobar_pago` solo si el pago es digital**, vacía el carrito y va a `/client/ordenes` |

### `HistorialController.php`
| `$action` | Qué hace |
|---|---|
| `recomprar` | **Primero** valida que ninguna línea esté agotada; solo entonces mete todo al carrito |
| `resena` | *Upsert* manual sobre `RESENA` (la tabla tiene `UNIQUE(id_pedido)`), puntaje acotado a 1–5 |

### `PerfilController.php`
| `$action` | Qué hace |
|---|---|
| `actualizar` | `UPDATE CLIENTE ...` + **`Auth::refresh()`** para que la cabecera muestre el nombre nuevo |
| `password` | `password_verify` de la actual, exige 6+ caracteres y confirmación, `password_hash` |

---

## Panel de **Cocina** (`app/Controllers/Kitchen/KdsController.php`)

| `$action` | Qué hace |
|---|---|
| `estacionLogin` | `hash_equals` contra `pin_estacion_kds` → `Session::set('estacion_cocina_ok', true)` |
| `preparar` | `cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()])` |
| `listo` | `cambiarEstado($id, 'listo', ['id_ayudante' => Auth::id()])` |

> **La tirilla no está aquí.** `/kitchen/pedido/{id}/tirilla` la sirve `public/index.php`,
> cargando la vista sin layout. Y el guardia del PIN tampoco: lo aplica el front controller.

---

## Panel del **Domiciliario** (`app/Controllers/Delivery/PanelController.php`)

| `$action` | Qué hace |
|---|---|
| `estacionLogin` | `hash_equals` contra `pin_estacion_domiciliario` → `estacion_domi_ok` |
| `disponibilidad` | `UPDATE DOMICILIARIO SET estado_disponibilidad = 'disponible' \| 'desconectado'` |
| `tomar` | Se autoasigna el pedido con `WHERE estado = 'listo' AND id_domiciliario IS NULL` (evita que dos repartidores tomen el mismo) y se pone `en_ruta` |
| `iniciarRuta` | `cambiarEstado($id, 'en_camino')`. La URL dice `/iniciar`; el front controller lo traduce |
| `entregar` | Valida el **PIN de 4 dígitos** con `hash_equals`; si el pago era efectivo llama `aprobarPago()`; luego `cambiarEstado($id, 'entregado')` |

---

## Resumen para el examen

- Un controlador aquí es **un script, no una clase**.
- Se elige qué hacer con `$action`, que arma `public/index.php` a partir de la ruta.
- Los controladores **solo** procesan POST y devuelven JSON; las pantallas las monta el
  front controller.
- Todo POST acaba en `redirect()` (**PRG**) dejando antes un mensaje en `$_SESSION['_flash']`.
