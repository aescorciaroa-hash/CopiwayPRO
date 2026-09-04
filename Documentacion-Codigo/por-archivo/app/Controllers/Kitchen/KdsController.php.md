# `app/Controllers/Kitchen/KdsController.php`

## Ubicación
`app/Controllers/Kitchen/KdsController.php`

## Propósito
Script procesador del **KDS** (*Kitchen Display System*): valida el **PIN de estación** y
hace avanzar los pedidos por los estados de cocina.

El tablero `/kitchen` (GET) lo arma `public/index.php`: reparte `Pedido::activos()` en las
3 columnas (`pendiente`, `en_preparacion`, `listo`), calcula el `$resumen` agregado por
producto y trae `Ingrediente::criticos()`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Pedido.php`, `Models/PedidoServicio.php`, `Models/Configuracion.php`.

## Acciones (`$action`)

### `estacionLogin` — `POST /kitchen/estacion`
Segundo factor de la estación compartida:

```php
if (hash_equals((string) $configModel->value('pin_estacion_kds'), $pin)) {
    Session::set('estacion_cocina_ok', true);
    redirect('/kitchen');
}
```

Si el PIN no coincide, flash de error y vuelve a `/kitchen/estacion`.

### `preparar` — `POST /kitchen/pedido/{id}/preparar`
`PedidoServicio::cambiarEstado($id, 'en_preparacion', ['id_ayudante' => Auth::id()])`
→ registra **quién** lo está preparando.

### `listo` — `POST /kitchen/pedido/{id}/listo`
`PedidoServicio::cambiarEstado($id, 'listo', ['id_ayudante' => Auth::id()])`.

Las tres acciones terminan en `redirect('/kitchen')` o `/kitchen/estacion` (**PRG**).

## Notas
- **El guardia del PIN no está en este archivo.** Lo aplica `public/index.php` antes de
  enrutar: si el rol es `cocina` y no existe `Session::get('estacion_cocina_ok')`, redirige
  a `/kitchen/estacion`.
- **La tirilla tampoco está aquí**: `/kitchen/pedido/{id}/tirilla` la sirve
  `public/index.php` directamente, cargando `app/Views/kitchen/tirilla.php` sin layout.
- `cambiarEstado` es quien genera la fila en `NOTIFICACION` para el cliente.
