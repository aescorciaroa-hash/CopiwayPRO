# `app/Views/client/_modal_personalizar.php`

## Ubicación
`app/Views/client/_modal_personalizar.php`

## Propósito
El modal **"Personalizar"** de un producto: quitar ingredientes (SIN), añadir extras
(EXTRA) y elegir la cantidad antes de mandarlo al carrito.

Cubre **RF-10** (Personalización Interactiva del Pedido).

## Quién lo incluye
`client/catalogo.php`, al final, con `require __DIR__ . '/_modal_personalizar.php';`

El guion bajo del nombre (`_modal_...`) marca por convención que es un **fragmento**, no
una pantalla completa.

## ⚠️ Depende del componente de la vista que lo incluye

No tiene su propio `x-data`. Usa el de `catalogo()`: `open`, `prod`, `quitar`, `extra`,
`cantidad`, `toggle()`, `total`, `money()`. **No funciona por sí solo.**

## Los datos llegan por `fetch`, no por PHP

El contenido se pinta con plantillas de Alpine sobre `prod`, que es el JSON que devuelve
`CatalogoController` (acción `personalizar`):

```js
prod.personalizables.retirar  // ingredientes de la receta que se pueden quitar
prod.personalizables.extras   // ingredientes que se pueden añadir, con precio_extra y agotado
```

## El formulario

`POST` a `/client/carrito/agregar`, con tres campos ocultos:

```php
<input type="hidden" name="id_producto" :value="prod.id_producto">
<input type="hidden" name="cantidad"    :value="cantidad">
<input type="hidden" name="volver"      value="/client">
```

`volver` es lo que hace que, tras añadir, el cliente regrese al catálogo en vez de ir al
carrito. Lo lee `CarritoController` en `redirect($_POST['volver'] ?? ...)`.

Y dos grupos de casillas que se envían como arrays:
- `quitar[]` → los SIN (borde rojo al marcarlos, "Quitar ingredientes no tiene costo")
- `extra[]` → los EXTRA (borde verde, muestra `+$ precio`)

`CarritoController::personalizacionesDesdePost()` los convierte en el array de
personalizaciones del carrito.

## Detalles

- Un extra **agotado** se pinta con `opacity-50`, la etiqueta roja `AGOTADO` y el input
  `:disabled="ing.agotado == 1"`.
- El texto cambia en vivo: al marcar, se antepone "SIN " o "EXTRA " al nombre.
- El modal tiene `max-h-[88vh]` y el cuerpo `overflow-y-auto`, así que la cabecera y el
  pie quedan fijos aunque haya muchos ingredientes.
- `@click.outside="open = false"` lo cierra al pulsar fuera.
- El overlay usa `fixed inset-0`, que es justo lo que `partials/autorefresh.php` detecta
  para **no recargar la página con el modal abierto**.

## Notas
- El servidor vuelve a validar: `CarritoController` descarta los extras sin stock y
  rechaza el producto si `Producto::estaAgotado()`.
