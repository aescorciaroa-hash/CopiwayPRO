# `app/Views/admin/comandas/_modales.php`

## Ubicación
`app/Views/admin/comandas/_modales.php`

## Propósito
Los **tres modales** del tablero de comandas: pedido manual, detalle del pedido y
corrección de dirección.

## Quién lo incluye
`admin/comandas/index.php`, al final. El guion bajo del nombre marca que es un fragmento.

## ⚠️ Depende del componente de la vista que lo incluye
No tiene `x-data` propio: usa el `comandasPage()` de `comandas/index.php`
(`openManual`, `openDetalle`, `openDir`, `pedido`, `lineas`, `productos`, `total`…).

---

## 1. Orden Manual (RF-43)

`POST` a `/admin/comandas/manual`. Es el formulario para los pedidos que entran por
teléfono o WhatsApp.

Campos: `cliente`, `telefono`, `direccion`, `canal`, más las líneas dinámicas
`producto_id[]` y `producto_cant[]`.

Las líneas se añaden y quitan con Alpine (`addLinea()` / `quitarLinea(i)`) y el total se
calcula en vivo con los precios del array `productos` serializado desde PHP —
**sin pedir nada al servidor**.

> `ComandasController` crea este pedido con `aprobar_pago = true`, así que **entra directo
> a cocina** y se cobra al entregar. Su código llevará el prefijo `#MAN-`.

## 2. Detalle del pedido

Solo lectura. Lo llena `verDetalle(id)` con el JSON de
`GET /admin/comandas/{id}`: líneas, personalizaciones, datos del cliente y del pago.

## 3. Corrección de dirección (RF-44)

El `action` se compone en el navegador, porque el id depende del pedido que se abrió:

```php
<form :action="'<?= url('/admin/comandas') ?>/' + dirPedidoId + '/direccion'" method="post">
```

Lo precarga `editarDir(id, dir)` con la dirección actual, para corregirla sin reescribirla
entera.

## Notas
- Los tres usan `fixed inset-0` como overlay, que es lo que `partials/autorefresh.php`
  detecta para no recargar la página con un modal abierto.
- Los tres llevan `csrf_field()`.
