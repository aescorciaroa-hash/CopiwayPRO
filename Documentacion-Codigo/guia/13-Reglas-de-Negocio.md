# 13 · Reglas de negocio

Estas son las reglas propias del negocio "Dark Kitchen". Cada una dice **qué es**,
**dónde está en el código** y **por qué**.

---

## 1. Cero Crédito (el pago va primero)

- **Qué:** un pedido **no llega a la cocina** hasta que su `PAGO.estado = 'aprobado'`.
- **Dónde:** `PedidoServicio::crear()` inserta el pago como `pendiente`. Solo si el
  método es `digital` o es un pedido manual, llama `aprobarPago()`. El pedido en efectivo
  se aprueba cuando el domiciliario lo entrega y cobra.
- **Por qué:** una cocina fantasma no puede permitirse preparar comida que quizá nadie
  pague.

## 2. Tarifa Plana de Domicilio

- **Qué:** el envío cuesta lo mismo para todos, sin importar la distancia.
- **Dónde:** `CONFIGURACION_SISTEMA.tarifa_plana_domicilio`; se suma en
  `PedidoServicio::crear()` como `costo_domicilio`. El admin la cambia en Ajustes.
- **Por qué:** simplicidad y previsibilidad para el cliente.

## 3. Punto de No Retorno

- **Qué:** una vez el cliente confirma el pago, ya no puede cancelar.
- **Dónde:** el checkout muestra un aviso azul; después no hay ninguna acción de
  "cancelar" en el panel del cliente.
- **Por qué:** protege el trabajo ya iniciado en cocina.

## 4. Horarios Automáticos

- **Qué:** fuera del horario (`horario_apertura`–`horario_cierre`) no se puede pagar.
- **Dónde:** `Configuracion::cocinaAbierta()`; `CheckoutController::index()` bloquea el
  botón "Pagar" si `!estadoCocina['abierta']`.
- **Por qué:** no aceptar pedidos que no se pueden preparar.

## 5. Pausa de Emergencia (Botón de Pánico)

- **Qué:** el admin puede cerrar la recepción de pedidos al instante.
- **Dónde:** `CONFIGURACION_SISTEMA.pausa_emergencia_activa`; si está en `true`,
  `cocinaAbierta()` devuelve `false` aunque sea horario.
- **Por qué:** saturación, falta de personal, corte de luz…

## 6. Fidelización (puntos)

- **Qué:** 1 punto por cada $1.000 gastados. Se ganan al aprobarse el pago.
- **Dónde:** trigger `trg_pago_aprobado`: `FLOOR(ped.total / 1000)` → suma a
  `CLIENTE.puntos_fidelidad` y guarda `PEDIDO.puntos_ganados`.

## 7. Descuento de Cumpleaños (15%)

- **Qué:** el día del cumpleaños del cliente, 15% de descuento **sobre el subtotal**
  (no sobre el envío).
- **Dónde:** `Cliente::esCumpleanos()` compara `MM-DD`; `CheckoutController` calcula
  `round($subtotal * 0.15)` y lo pasa como `descuento_cumpleanos`.

## 8. Inventario por Receta (Escandallo)

- **Qué:** cada producto tiene una **receta** (`RECETA`) con los ingredientes y sus
  cantidades. Sirve para:
  - calcular el **costo** del producto (`Producto::costoReceta`),
  - **descontar** el inventario automáticamente al vender (trigger).
- **Dónde:** tabla `RECETA`; trigger `trg_pago_aprobado`.

## 9. Bloqueo de Stock ("AGOTADO")

- **Qué:** si a un producto le falta stock de **cualquier** ingrediente de su receta,
  aparece como "AGOTADO" y no se puede pedir.
- **Dónde:** `Producto::AGOTADO_EXPR` (subconsulta) y `Producto::estaAgotado()`.

## 10. SLA de Cocina (semáforo de tiempo)

- **Qué:** un pedido que lleva **más de 15 minutos** en preparación se resalta con
  borde rojo pulsante.
- **Dónde:** vista de comandas / KDS: `$p['minutos'] > 15` → clase `sla-vencido`.
  El campo `minutos` viene de `TIMESTAMPDIFF(MINUTE, fecha_hora, NOW())`.

## 11. Resaltado SIN / EXTRA

- **Qué:** las personalizaciones se muestran claras para la cocina: `SIN Cebolla` en
  **rojo**, `EXTRA Tocineta` en **verde**.
- **Dónde:** helper `mods_html()` + clases CSS `.mod-sin` / `.mod-extra`.

## 12. Soft Delete de Personal

- **Qué:** al "dar de baja" un empleado no se borra: se pone `activo = 0`. Solo se
  puede borrar de verdad si **nunca** tuvo pedidos.
- **Dónde:** `Empleado::darDeBaja()` / `Empleado::eliminar()`.
- **Por qué:** conservar el historial y los reportes.

## 13. Botón de Última Milla / Validación por PIN

- **Qué:** el domiciliario solo marca "entregado" si teclea el **PIN de 4 dígitos** que
  le muestra el cliente.
- **Dónde:** `PanelController::entregar()` → `hash_equals($p['pin_entrega'], $input)`.
  El PIN se genera al crear el pedido (trigger `trg_pedido_pin`).

## 14. Pedido Manual (Llamada / WhatsApp)

- **Qué:** el admin registra pedidos que llegan por teléfono. Se crea un cliente mínimo
  si no existe (por teléfono).
- **Dónde:** `ComandasController::crearManual()` + `PedidoServicio::clienteParaManual()`.
  Entra directo a cocina (`aprobar_pago = true`), se cobra al entregar.
- El código del pedido lleva prefijo **`#MAN-`** en vez de `#ORD-`.

## 15. Cierre de Caja Inteligente

- **Qué:** el reporte del día junta 3 cosas:
  1. **Ventas** por método (efectivo vs digital).
  2. **Escandallo**: cuánto se consumió de cada insumo según las recetas vendidas
     (stock teórico vs real → detectar mermas/robos).
  3. **Liquidación por domiciliario**: `base_efectivo_asignada + recaudo` = lo que cada
     repartidor debe entregar.
- **Dónde:** `CierreCaja::calcular()` (solo calcula) y `CierreCaja::generar()` (guarda
  en `REPORTE_CAJA` + `DETALLE_AUDITORIA` + `LIQUIDACION_DOMICILIARIO` y enlaza los
  pedidos del día).

## 16. Creador Interactivo ("Arma tu Burger")

- **Qué:** el cliente construye una hamburguesa capa por capa; el precio se calcula con
  el **margen de ganancia** configurado sobre el costo de cada ingrediente.
- **Dónde:** `Producto::ingredientesCreador()` usa
  `costo_unitario * (1 + margen/100)`. `CreadorController` guarda todo como extras de un
  producto oculto "Hamburguesa Personalizada".
