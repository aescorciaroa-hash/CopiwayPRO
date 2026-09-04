# `app/Views/delivery/index.php`

## Ubicación
`app/Views/delivery/index.php`

## Propósito
El **panel del domiciliario**: interruptor de disponibilidad, mapa a pantalla casi
completa, mis pedidos asignados, los disponibles para tomar y el modal del PIN de entrega.

Cubre **RF-65** a **RF-70**.

## Quién la renderiza
`public/index.php`, `case '/delivery'`, con el layout **`delivery`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$yo` | `Auth::user()` — se usa `estado_disponibilidad` |
| `$disponibles` | Pedidos en estado `listo` **sin** domiciliario asignado |
| `$mios` | Pedidos asignados a este domiciliario |

Ambas listas ya traen `codigo` y `lineas`.

## Variables que calcula la vista

```php
$disponible      = ($yo['estado_disponibilidad'] ?? '') !== 'desconectado';
$primerPedido    = $mios[0] ?? $disponibles[0] ?? null;
$direccionActiva = $primerPedido['direccion_entrega'] ?? 'Calle 10 # 5-20, Centro';
$codigoActivo    = $primerPedido['codigo'] ?? '#ORD-4931';
$rutaEnCamino    = $primerPedido && (($primerPedido['estado'] ?? '') === 'en_camino');
```

> ⚠️ Los valores por defecto (`'Calle 10 # 5-20, Centro'`, `'#ORD-4931'`) son **datos de
> maqueta**: se ven cuando el domiciliario no tiene ningún pedido, para que el mapa y la
> tarjeta de destino no salgan vacíos.

## Secciones

1. **Cabecera** — logo, botón de tema y cerrar sesión (el layout `delivery` no trae nada).
2. **Interruptor de disponibilidad** — un `<form>` que hace `POST` a
   `/delivery/disponibilidad`. El campo oculto lleva **el estado contrario** al actual,
   así que el mismo botón alterna:
   ```php
   <input type="hidden" name="estado" value="<?= $disponible ? 'desconectado' : 'disponible' ?>">
   ```
   El "switch" es puro CSS: `translate-x-5` / `translate-x-0` sobre el círculo blanco.
3. **Mapa** (`h-[52vh]`) — Leaflet vía `Copiway.map('mapa-delivery-full', ...)`, con una
   tarjeta flotante de destino encima.
4. **Acciones flotantes** — solo si `$rutaEnCamino`: botones a **Waze** y **Google Maps**
   (con la dirección en `urlencode`) y el botón que abre el modal del PIN.
5. **Mis pedidos** — cada tarjeta con "Iniciar ruta" (`POST .../iniciar`), WhatsApp al
   cliente (`https://wa.me/57<telefono>`), enlaces a Waze/Maps y el banner naranja de
   cobro en efectivo.
6. **Pedidos disponibles** — "Tomar" (`POST .../tomar`).

## El modal del PIN de entrega (RF-69)

Un único modal compartido; el botón le pasa el id del pedido y el `action` se compone en
el navegador:

```php
<form :action="'<?= url('/delivery/pedido') ?>/' + pedidoId + '/entregar'" method="post">
```

El texto le recuerda al repartidor: *"Solicita al cliente el PIN de 4 digitos de su
pedido."* La comparación real la hace `PanelController` con `hash_equals`.

## Detalles

- "Llegada est: 12 mins" es un **texto fijo**, no un cálculo de ruta.
- El indicador "GPS Activo" tampoco usa geolocalización real: las coordenadas del destino
  las inventa `Copiway._geo()` a partir del texto de la dirección.
- El `57` de los enlaces de WhatsApp (indicativo de Colombia) va escrito en la plantilla.

## Auto-refresco
```php
<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>
```
Cada 15 segundos, **salvo** que haya un modal abierto o el repartidor acabe de tocar la
pantalla — justo para que no se cierre el modal del PIN mientras lo teclea.
