# `app/Controllers/Delivery/PanelController.php`

## Ubicación
`app/Controllers/Delivery/PanelController.php` · namespace `App\Controllers\Delivery`

## Propósito
El **panel del domiciliario** (`/delivery`): ver pedidos listos, tomarlos, iniciar ruta
y entregar validando el **PIN de 4 dígitos** del cliente.

## Dependencias
`Controller`, `Auth`, `Session`, `Database`, `App\Models\Pedido`,
`App\Models\PedidoServicio`, `App\Models\Configuracion`.

## Método privado

### `requireEstacion(): void`
Si no está `Session::get('estacion_domi_ok')` → `redirect('/delivery/estacion')`.

## Métodos

### `estacionForm()` / `estacionLogin()` — `/delivery/estacion`
Segundo login por PIN (`pin_estacion_domiciliario`), `hash_equals`. Éxito →
`estacion_domi_ok = true` → `/delivery`.

### `index(): string` — `GET /delivery`
1. `$yo` — la fila del domiciliario logueado.
2. `$disponibles` — pedidos en estado `listo` **sin** domiciliario.
3. `$mios` — pedidos asignados a mí en estado `listo`/`en_camino`.
4. Un closure `$enriquecer` añade `codigo` y `lineas` a cada lista.
5. Vista `delivery/index` (layout `delivery`).

### `disponibilidad(): string` — `POST /delivery/disponibilidad`
`UPDATE DOMICILIARIO SET estado_disponibilidad = 'disponible' | 'desconectado'`.

### `tomar(string $id): string` — `POST /delivery/pedido/{id}/tomar`
Si el pedido está `listo` y sin domiciliario:
`UPDATE PEDIDO SET id_domiciliario = ?` + `UPDATE DOMICILIARIO SET estado_disponibilidad = 'en_ruta'`.

### `iniciarRuta(string $id): string` — `POST /delivery/pedido/{id}/ruta`
Si el pedido es mío: `PedidoServicio::cambiarEstado($id, 'en_camino')` (avisa al cliente).

### `entregar(string $id): string` — `POST /delivery/pedido/{id}/entregar`
1. `Pedido::completo($id)`. Si no es mío → redirect.
2. **Valida el PIN:** `hash_equals($p['pin_entrega'], $this->input('pin'))`. Si no
   coincide → flash "PIN incorrecto".
3. Si el pago era **efectivo** → `PedidoServicio::aprobarPago($id)` (ahora sí se descuenta
   inventario y se suman puntos, vía trigger).
4. `PedidoServicio::cambiarEstado($id, 'entregado')`.
5. Si no me quedan pedidos activos → vuelvo a `disponible`.

## Notas
- El PIN se generó al crear el pedido (trigger `trg_pedido_pin`) y lo ve el cliente en
  "Órdenes Activas".
- El pago en efectivo se aprueba **al entregar** (regla "Cero Crédito").
