# 09 · Controladores (la lógica)

Un controlador es una clase con métodos. Cada método atiende una ruta. El patrón
siempre es el mismo:

```php
public function index(): string
{
    // 1. (si es POST) verificar CSRF y validar datos
    // 2. pedir datos a los modelos
    // 3a. si es GET  -> devolver una vista:   return $this->view('...', [...], 'layout');
    // 3b. si es POST -> hacer el cambio y redirigir:  redirect('/...'); return '';
}
```

Todos heredan de `App\Core\Controller` (métodos `view`, `json`, `input`, `validate`,
`verifyCsrf`, `back`).

---

## `HomeController`
- `index()` → la landing page pública (`/`). Muestra menú, "nosotros", contacto, mapa.

## `AuthController` (login, registro, recuperar)

| Método | Ruta | Qué hace |
|--------|------|----------|
| `showLogin()` | GET `/login` | si ya hay sesión, redirige al panel; si no, muestra el login |
| `login()` | POST `/login` | valida → `Usuario::porCorreo` → `password_verify` → comprueba `activo` → `Auth::login` → redirige según el rol (cocina/domi pasan primero por su login de estación) |
| `showRegister()` / `register()` | `/register` | registra un **cliente**: valida, comprueba duplicados, exige aceptar habeas data, hashea la clave, inicia sesión |
| `showForgot()` / `forgot()` | `/forgot-password` | flujo **simulado** (en producción enviaría un código) |
| `logout()` | POST `/logout` | `Auth::logout()` + mensaje + a `/login` |
| `devLogin($correo)` | GET `/_dev/login/{correo}` | **solo con debug**: login sin contraseña, para pruebas |

---

## Panel del **Administrador** (`app/Controllers/Admin/`)

### `DashboardController`
- `index()` → Tablero. Filtra por `Periodo` (hoy/semana/mes…). Trae KPIs, ventas por
  día, ranking de productos, pedidos recientes, contadores por estado, estado de cocina.
- `datos()` → los mismos datos en JSON (para refresco sin recargar).

### `ComandasController`
- `index()` → tablero de comandas del turno (`Pedido::delTurno`) como tarjetas.
- `detalle($id)` → JSON con el pedido completo (para el modal).
- `crearManual()` → crea un pedido tomado por teléfono/WhatsApp: busca o crea el cliente
  (`PedidoServicio::clienteParaManual`), arma las líneas y llama `PedidoServicio::crear`
  con `aprobar_pago = true` (entra directo a cocina).
- `editarDireccion($id)` → corrige la dirección de entrega.

### `RutasController`
- `index()` → mapa de domiciliarios, pedidos en ruta, despachos recientes.

### `MenuController`
- `index()` → catálogo del admin + categorías + ingredientes (para los modales).
- `guardarProducto()` → crea o edita un producto y **su receta** (arrays
  `receta_ingrediente[]` y `receta_cantidad[]`).
- `cambiarEstado($id)` → activo ↔ oculto.
- `eliminarProducto($id)` → solo si `!Producto::tienePedidos($id)`.
- `datosProducto($id)` → JSON del producto + receta (para el modal de edición).
- `crearCategoria()` / `eliminarCategoria($id)`.

### `InventarioController`
- `index()` → insumos + KPIs + movimientos.
- `guardarInsumo()` → registra un ingrediente nuevo; calcula
  `costo_unitario = costo_total / cantidad`.
- `ajustar($id)` → `+1`, `-1` o "fijar" un valor; usa `Ingrediente::moverStock`.

### `PersonalController`
- `index()` → lista combinada (`Empleado::todos`).
- `crear()` → alta de cocina o domiciliario.
- `datos($rol, $id)` → JSON para el modal de edición.
- `actualizar($rol, $id)` / `baja($rol, $id)` / `reactivar($rol, $id)` / `eliminar($rol, $id)`.

### `ClientesController`
- `index()` → directorio (`Cliente::directorio`).
- `historial($id)` → JSON con las compras del cliente (para el modal).

### `AjustesController`
- `index()` → ajustes + resumen de caja.
- `tarifa()` / `margen()` / `horario()` / `pausa()` → guardan cada ajuste con
  `Configuracion::save`.
- `vistaPrevia()` → JSON con el cálculo del cierre (`CierreCaja::calcular`).
- `generarCierre()` → guarda el cierre (`CierreCaja::generar`).

---

## Panel del **Cliente** (`app/Controllers/Client/`)

### `CatalogoController`
- `index()` → menú, categorías, si es cumpleaños, estado de cocina, último pedido (recompra).
- `personalizar($id)` → JSON con los ingredientes que se pueden quitar/agregar.

### `CarritoController`
- `index()` → muestra el carrito.
- `agregar()` / `actualizar()` / `quitar()` / `vaciar()` → operan sobre `Carrito`.
  Convierte los `quitar[]` / `extra[]` del formulario en el array de personalizaciones.

### `CreadorController`
- `index()` → "Arma tu Burger": lista de ingredientes con precio.
- `agregar()` → reutiliza (o crea) un producto oculto "Hamburguesa Personalizada" y
  añade las capas elegidas como extras.

### `CheckoutController`
- `index()` → si la cocina está cerrada, bloquea. Muestra resumen, calcula descuento
  de cumpleaños.
- `confirmar()` → crea el pedido (`PedidoServicio::crear`) con el método de pago
  elegido; vacía el carrito; redirige a "Órdenes Activas".

### `OrdenesController`
- `index()` → pedidos activos del cliente con su barra de progreso.
- `detalle($id)` → JSON del pedido.

### `HistorialController`
- `index()` → pedidos pasados, puntos, ahorro; permite filtrar.
- `recomprar($id)` → mete las mismas líneas del pedido en el carrito (validando stock).
- `resena($id)` → guarda/actualiza la calificación (1–5 estrellas).

### `PerfilController`
- `index()` → datos del cliente + puntos.
- `actualizar()` → guarda nombre, correo, teléfono, dirección, fecha de nacimiento.
- `password()` → cambia la contraseña (verifica la actual primero).

---

## Panel de **Cocina** (`app/Controllers/Kitchen/KdsController.php`)

- `estacionForm()` / `estacionLogin()` → segundo login con el **PIN de estación**
  (`pin_estacion_kds`). Marca `estacion_cocina_ok` en la sesión.
- `requireEstacion()` (privado) → si no está el flag, redirige al login de estación.
- `index()` → tablero KDS: pedidos por estado (`pendiente`, `en_preparacion`, `listo`),
  resumen agregado por producto, ingredientes críticos.
- `preparar($id)` → estado → `en_preparacion` + registra el ayudante.
- `listo($id)` → estado → `listo` (avisa a logística).
- `tirilla($id)` → marca `tirilla_impresa` y muestra la tirilla imprimible.

---

## Panel del **Domiciliario** (`app/Controllers/Delivery/PanelController.php`)

- `estacionForm()` / `estacionLogin()` → PIN de estación (`pin_estacion_domiciliario`).
- `index()` → pedidos listos sin domiciliario + mis pedidos asignados (con sus líneas).
- `disponibilidad()` → me pongo `disponible` / `desconectado`.
- `tomar($id)` → me asigno un pedido `listo` (y me pongo `en_ruta`).
- `iniciarRuta($id)` → estado → `en_camino` (avisa al cliente).
- `entregar($id)` → valida el **PIN de 4 dígitos** que da el cliente
  (`hash_equals($p['pin_entrega'], $input)`); si es efectivo aprueba el pago; estado →
  `entregado`; si ya no me quedan pedidos, vuelvo a `disponible`.
