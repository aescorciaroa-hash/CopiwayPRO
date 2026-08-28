# 08 · Modelos (la capa de datos)

Los modelos están en `app/Models/`. Todos utilizan la conexión global `$conn` instanciada desde `config/database.php`.

En el constructor de cada clase de modelo se inyecta la conexión `$conn`:

```php
private $conn;

public function __construct() {
    global $conn;
    $this->conn = $conn;
}
```

---

## `Usuario` — login unificado

Sirve para buscar credenciales entre las **4 tablas de cuentas** (`ADMINISTRADOR`, `CLIENTE`, `AYUDANTE_COCINA`, `DOMICILIARIO`).

| Método | Qué hace |
|--------|----------|
| `porCorreo($correo)` | Recorre las 4 tablas buscando el correo usando consultas preparadas `mysqli`. |
| `porId($rol, $id)` | Busca en la tabla correspondiente al rol para validar la sesión activa. |
| `existeCorreoOTelefono($c, $t)` | Evita duplicados en los registros de clientes o personal. |
| `verificarPassword($plano, $hash)` | Envoltorio seguro de `password_verify()`. |

---

## `Cliente` (tabla `CLIENTE`)

| Método | Qué hace |
|--------|----------|
| `registrar($d)` | Inserta el cliente con contraseña *hasheada*, puntos en 0 y fecha de habeas data. |
| `directorio($buscar)` | Consulta para el admin con total gastado y número de pedidos. |
| `historial($idCliente)` | Historial de pedidos realizados por el cliente. |
| `esCumpleanos($cliente)` | Compara `MM-DD` de `fecha_nacimiento` con hoy para activar el 15% de descuento. |

---

## `Empleado` — gestión de personal

| Método | Qué hace |
|--------|----------|
| `todos()` | Retorna lista de `AYUDANTE_COCINA` y `DOMICILIARIO` ordenada por nombre. |
| `crear($rol, $d, $idAdmin)` | Inserta en la tabla correcta con `bind_param` y hash de contraseña. |
| `actualizar($rol, $id, $d)` | Actualiza los datos del colaborador. |
| `darDeBaja($rol, $id)` | **Soft delete**: Marca `activo = 0` conservando el historial. |
| `reactivar($rol, $id)` | Marca `activo = 1`. |
| `eliminar($rol, $id)` | Elimina el registro si no posee pedidos en el historial. |
| `activos()` | Retorna número de colaboradores activos. |

---

## `Categoria` (tabla `CATEGORIA`)

Soporta los ámbitos `menu`, `insumo_alimenticio` y `empaque_desechable` para categorizar productos e insumos de inventario.

---

## `Producto` (tabla `PRODUCTO`)

| Método | Qué hace |
|--------|----------|
| `catalogo($soloActivos)` | Productos con categoría y flag `agotado` calculado según el stock de la receta. |
| `paraAdmin($cat, $buscar)` | Catálogo completo (incluyendo ocultos) para administración. |
| `estaAgotado($id)` | Evalúa si algún ingrediente de la receta tiene stock insuficiente. |
| `receta($id)` | Receta del producto con costo y disponibilidad de ingredientes. |
| `costoReceta($id)` | Calcula costo de escandallo (insumos + empaques). |
| `guardarReceta($id, $items)` | Actualiza la lista de ingredientes de la receta. |
| `tienePedidos($id)` | Verifica si el producto tiene historial de ventas. |
| `personalizables($id)` | Ingredientes para modificación (SIN / EXTRA). |

---

## `Ingrediente` (tabla `INGREDIENTE`)

| Método | Qué hace |
|--------|----------|
| `conCategoria($buscar)` | Lista insumos con valorización y alertas de stock bajo. |
| `criticos()` | Insumos por debajo del umbral mínimo. |
| `moverStock($id, $tipo, $cantidad, $motivo, $idAdmin)` | Registra movimientos de inventario (`entrada`, `salida`, `ajuste`) en `MOVIMIENTO_INVENTARIO`. |

---

## `Pedido` (tabla `PEDIDO`) — Lectura

| Método | Qué hace |
|--------|----------|
| `codigo($pedido)` | Código formateado: `#ORD-XXXX` (web) o `#MAN-XXXX` (manual/llamada). |
| `kpis($desde, $hasta)` | Cálculo de ventas totales, órdenes y ticket promedio. |
| `ventasPorDia($desde, $hasta)` | Ventas agrupadas por día para gráficas. |
| `contarPorEstado()` | Conteo de pedidos en cada estado activo. |
| `activos()` | Pedidos en curso (`pendiente`, `en_preparacion`, `listo`, `en_camino`). |
| `completo($idPedido)` | Consulta relacional completa del pedido con sus líneas y personalizaciones. |

---

## `PedidoServicio` — Escritura y Transacciones

Modelo central para la creación de comandas e intermediación de estados:
- **`crear($datos)`**: Utiliza transacciones **MySQLi nativas** (`$conn->begin_transaction()`, `$conn->commit()`, `$conn->rollback()`).
- **`aprobarPago($idPedido)`**: Ejecuta un `UPDATE PAGO SET estado = 'aprobado'` que dispara el trigger `trg_pago_aprobado` de MySQL.
- **`cambiarEstado($idPedido, $estado, $extra)`**: Actualiza el estado del pedido en la base de datos.

---

## `Carrito` — Gestión en $_SESSION['carrito']

Almacena la cesta de compras del cliente en la superglobal `$_SESSION['carrito']` y proporciona métodos auxiliares para sumar subtotales y convertir la cesta a líneas de pedido.

---

## `Configuracion` y `CierreCaja`

- **`Configuracion`**: Maneja la tabla `CONFIGURACION_SISTEMA`, el estado de la cocina (`cocinaAbierta()`), la tarifa plana de envío y parámetros globales.
- **`CierreCaja`**: Calcula y guarda en transacciones MySQLi el reporte diario de caja (`calcular()` y `generar()`).

