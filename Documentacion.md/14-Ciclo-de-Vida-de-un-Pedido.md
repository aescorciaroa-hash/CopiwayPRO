# 14 · Ciclo de vida de un pedido

Aquí se ve **cómo colaboran las 4 partes** (cliente, cocina, domiciliario, admin) y
cómo se conectan controladores, modelos y triggers.

## Los 6 estados (`PEDIDO.estado`)

```
pendiente → en_preparacion → listo → en_camino → entregado
                                               ↘ cancelado
```

| Estado | Significado | Quién lo pone |
|--------|-------------|---------------|
| `pendiente` | creado, esperando que la cocina lo tome (pago ya aprobado) | sistema al crear |
| `en_preparacion` | la cocina lo está haciendo | Cocina ("Preparar") |
| `listo` | terminado, esperando domiciliario | Cocina ("Marcar listo") |
| `en_camino` | el domiciliario salió | Domiciliario ("Iniciar ruta") |
| `entregado` | entregado y cobrado | Domiciliario ("Entregar" + PIN) |
| `cancelado` | anulado | admin (casos excepcionales) |

---

## Paso a paso

### 1. El cliente arma el pedido
- `CatalogoController::index` muestra el menú (productos no agotados).
- El cliente personaliza (modal) → `CarritoController::agregar` → `Carrito` (sesión).
- Va al carrito → `CheckoutController::index`.
  - Si la cocina está **cerrada** → bloquea.
  - Si es su **cumpleaños** → calcula 15% de descuento sobre el subtotal.

### 2. Confirma y paga → `CheckoutController::confirmar`
```php
$idPedido = PedidoServicio::crear([
    'id_cliente'          => Auth::id(),
    'direccion_entrega'   => $direccion,
    'metodo_pago'         => $metodo,           // 'digital' o 'efectivo'
    'descuento_cumpleanos'=> $descuento,
    'lineas'              => Carrito::aLineas(),
]);
Carrito::vaciar();
redirect('/client/ordenes');
```

`PedidoServicio::crear` (todo en una **transacción**):
- calcula subtotal + tarifa − descuento = **total**,
- `INSERT PEDIDO` (estado `pendiente`, PIN de 4 dígitos),
- `INSERT DETALLE_PEDIDO` + `INSERT PERSONALIZACION`,
- `INSERT PAGO` (`pendiente`),
- si el pago es **digital** → `aprobarPago()` →
  `UPDATE PAGO SET estado='aprobado'` →
  **⚡ el trigger `trg_pago_aprobado` descuenta inventario y suma puntos.**
- (si es **efectivo**, el pago queda `pendiente` hasta la entrega — regla "Cero Crédito").

### 3. La cocina lo prepara → `KdsController`
- El KDS lista los pedidos `pendiente` / `en_preparacion` / `listo`.
- "Preparar" → `cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()])`.
- (opcional) "Imprimir tirilla" → `tirilla($id)` marca `tirilla_impresa`.
- "Marcar listo" → `cambiarEstado($id, 'listo')`.
- Cada cambio crea una `NOTIFICACION`.

### 4. El domiciliario lo lleva → `PanelController`
- Ve los pedidos `listo` sin domiciliario.
- "Tomar" → `UPDATE PEDIDO SET id_domiciliario = ?` + su estado a `en_ruta`.
- "Iniciar ruta" → `cambiarEstado($id, 'en_camino')` (avisa al cliente).
- Llega a la casa. El cliente le muestra el **PIN de entrega** (visible en "Órdenes
  Activas").
- "Entregar" → valida `hash_equals($p['pin_entrega'], $input)`:
  - si el pago era **efectivo** → `aprobarPago($id)` → **⚡ ahora sí** se descuenta
    inventario y se suman puntos,
  - `cambiarEstado($id, 'entregado')`,
  - si no le quedan más pedidos, vuelve a `disponible`.

### 5. El cliente hace seguimiento → `OrdenesController`
- Barra de progreso Recibido → Cocina → Listo → En Camino.
- Muestra el PIN de entrega.
- Al entregarse, el pedido pasa al **Historial** (`HistorialController`), donde puede:
  - **calificar** (1–5 estrellas) → `RESENA`,
  - **"Recomprar en 1 clic"** → mete las mismas líneas al carrito.

### 6. El admin cierra la caja → `AjustesController::generarCierre`
- `CierreCaja::generar($fecha, Auth::id())`:
  - totales efectivo/digital,
  - escandallo (consumo de insumos del día),
  - liquidación por domiciliario (`base + recaudo`),
  - enlaza los pedidos `entregado` del día al `REPORTE_CAJA`.

---

## Quién dispara el trigger de inventario

| Método de pago | Momento en que se aprueba el pago | Momento en que baja el inventario |
|----------------|-----------------------------------|-----------------------------------|
| **Digital** | al confirmar el checkout | inmediatamente |
| **Efectivo** | al entregar (domiciliario + PIN) | al entregar |
| **Manual** (admin) | al crearlo (`aprobar_pago = true`) | inmediatamente |

Siempre es el mismo `UPDATE PAGO SET estado = 'aprobado'` el que dispara el trigger.
