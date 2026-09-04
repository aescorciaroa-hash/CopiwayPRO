# `app/Controllers/Delivery/PanelController.php`

## Ubicación
`app/Controllers/Delivery/PanelController.php`

## Propósito
Script procesador del **panel del domiciliario**: PIN de estación, disponibilidad,
autoasignación de pedidos, inicio de ruta y **entrega validada por PIN**.

La pantalla `/delivery` (GET) la arma `public/index.php`, que separa `Pedido::activos()`
en `$disponibles` (estado `listo` y sin domiciliario) y `$mios` (los asignados a mí).

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Pedido.php`, `Models/PedidoServicio.php`, `Models/Configuracion.php`.

## Acciones (`$action`)

### `estacionLogin` — `POST /delivery/estacion`
`hash_equals(Configuracion::value('pin_estacion_domiciliario'), $pin)` →
`Session::set('estacion_domi_ok', true)` y `/delivery`.

### `disponibilidad` — `POST /delivery/disponibilidad`
`UPDATE DOMICILIARIO SET estado_disponibilidad = ?` con `disponible` o `desconectado`
(cualquier valor distinto de `disponible` cae en `desconectado`).

### `tomar` — `POST /delivery/pedido/{id}/tomar`
Dos `UPDATE` preparados:

```sql
UPDATE PEDIDO SET id_domiciliario = ?
 WHERE id_pedido = ? AND estado = 'listo' AND id_domiciliario IS NULL;
UPDATE DOMICILIARIO SET estado_disponibilidad = 'en_ruta' WHERE id_domiciliario = ?;
```

Las condiciones `estado = 'listo' AND id_domiciliario IS NULL` evitan que **dos
domiciliarios se queden con el mismo pedido**.

### `iniciarRuta` — `POST /delivery/pedido/{id}/iniciar`
`PedidoServicio::cambiarEstado($id, 'en_camino')` → notifica al cliente.
`public/index.php` traduce el segmento `iniciar` de la URL a la acción `iniciarRuta`.

### `entregar` — `POST /delivery/pedido/{id}/entregar`
1. `Pedido::completo($id)`.
2. **`hash_equals((string) $p['pin_entrega'], $pin)`** — si el PIN de 4 dígitos no coincide,
   flash de error y **no** se entrega.
3. Si `pago_metodo === 'efectivo'` → `PedidoServicio::aprobarPago($id)`
   (**aquí** se dispara `trg_pago_aprobado` para los pedidos en efectivo).
4. `PedidoServicio::cambiarEstado($id, 'entregado')`.

Cualquier otro `$action` → `redirect('/delivery')`.

## Notas
- El guardia del PIN de estación está en `public/index.php`, no aquí.
- El PIN de entrega lo genera el trigger `trg_pedido_pin` al crear el pedido; el cliente
  lo ve en `/client/ordenes`.
- WhatsApp, Waze y Google Maps son **enlaces de la vista** (`delivery/index.php`); este
  script no interviene.
