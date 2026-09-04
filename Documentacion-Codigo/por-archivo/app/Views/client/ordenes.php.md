# `app/Views/client/ordenes.php`

## Ubicación
`app/Views/client/ordenes.php`

## Propósito
El **seguimiento en vivo** de los pedidos que aún no se han entregado: barra de progreso,
PIN de entrega y datos del domiciliario.

Cubre **RF-16** (Rastreo de Estado) y **RF-17** (Visualización del PIN).

## Quién la renderiza
`public/index.php`, `case '/client/ordenes'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$pedidos` | `Cliente::pedidosActivos($idCliente)`, con `codigo` y `lineas` añadidos |

## Los tres arrays de la barra de progreso

Definidos al principio del archivo:
```php
$pasos  = ['pendiente' => 0, 'en_preparacion' => 1, 'listo' => 2, 'en_camino' => 3];
$labels = ['Recibido', 'Preparando', 'Listo', 'En camino'];
$iconos = ['package-check', 'chef-hat', 'bell', 'bike'];
```

`$pasos` traduce el estado del pedido a un índice; a partir de ahí:
- los pasos **anteriores** (`$i < $nivel`) muestran un check;
- el paso **actual** (`$i === $nivel`) lleva su icono con `animate-pulse` y un anillo
  `ring-4 ring-brand-500/20`;
- los **posteriores** quedan en gris.

Las barras entre iconos se pintan naranjas hasta el nivel alcanzado.

## El PIN de entrega
En una caja naranja arriba a la derecha de cada tarjeta, en monoespaciada y con
`tracking-widest`. Es el número que el cliente le enseña al domiciliario, y lo genera
`PedidoServicio::crear()`.

## El bloque del domiciliario
Solo aparece si `estado === 'en_camino'` **y** el pedido tiene domiciliario asignado.
Muestra nombre, vehículo y placa, un punto verde con `animate-ping`, y un botón de
**WhatsApp**: `https://wa.me/57<telefono>`.

> El `57` (indicativo de Colombia) va escrito a mano en la plantilla.

## Auto-refresco
La última línea del archivo:
```php
<?php $segundos = 15; require dirname(__DIR__) . "/partials/autorefresh.php"; ?>
```
La pantalla se recarga sola cada 15 segundos para reflejar el avance del pedido.

## Notas
- Si no hay pedidos activos, muestra un estado vacío con un botón a "Hacer un pedido".
- El estado `entregado` no aparece aquí: esos pedidos pasan a `/client/historial`.
