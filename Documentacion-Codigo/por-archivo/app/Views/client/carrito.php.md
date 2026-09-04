# `app/Views/client/carrito.php`

## Ubicación
`app/Views/client/carrito.php`

## Propósito
El **carrito**: las líneas con sus personalizaciones, los botones de cantidad, quitar y
vaciar, y el resumen de pago.

## Quién la renderiza
`public/index.php`, `case '/client/carrito'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$items` | `Carrito::items()`, con `precio_unitario` añadido (copia de `precio_base`) |
| `$subtotal` | `Carrito::subtotal()` |
| `$envio` | `Configuracion::value('tarifa_plana_domicilio', 6000)` |

## Estados

- **Carrito vacío** → tarjeta centrada con icono, "Tu carrito esta vacio." y un botón a
  "Explorar Menu".
- **Con productos** → rejilla de 3 columnas: las líneas ocupan 2, el resumen 1.

## Cada línea

- Nombre del producto.
- Personalizaciones con las clases `.mod-sin` (rojo) / `.mod-extra` (verde) y el prefijo
  SIN / EXTRA.
- Precio de la línea: `(precio_unitario + Σ costos) * cantidad`.
- **Papelera** → `POST` a `/client/carrito/quitar` con la `key`.
- **− / número / +** → un `POST` a `/client/carrito/actualizar`; los botones `−` y `+` son
  el mismo `<button name="cantidad">` con el valor ya calculado en PHP
  (`$item['cantidad'] - 1` y `+ 1`). Si llega 0 o menos, el modelo elimina la línea.

La `key` identifica la línea: el mismo producto con distintas personalizaciones son
líneas separadas.

## El botón "Vaciar carrito"
Lleva `onsubmit="return confirm('Vaciar todo el carrito?')"` — la única confirmación
nativa del panel del cliente junto con la del checkout.

## ⚠️ El resumen no muestra el descuento de cumpleaños

Aquí el total es simplemente `subtotal + envio`. **El 15% de cumpleaños solo aparece en el
checkout**, que es donde se calcula (`CheckoutController`). Si un cliente cumple años,
verá un total más alto aquí que en la pantalla siguiente.

## Notas
- La leyenda "Pago seguro mediante pasarela encriptada SSL" es texto de maqueta: no hay
  ninguna pasarela de pago integrada.
