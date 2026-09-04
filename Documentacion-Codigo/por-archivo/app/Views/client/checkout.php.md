# `app/Views/client/checkout.php`

## Ubicación
`app/Views/client/checkout.php`

## Propósito
El **checkout**: dirección, resumen con descuentos, método de pago y el aviso del punto de
no retorno. Es la última pantalla antes de que exista el pedido.

## Quién la renderiza
`public/index.php`, `case '/client/checkout'`, con el layout **`client`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$items` | `Carrito::items()` |
| `$cliente` | `Cliente::find(...)`, o un array vacío por defecto |
| `$subtotal`, `$envio` | Carrito y configuración |
| `$descuento` | `$cumple ? round($subtotal * 0.15) : 0` |
| `$total` | `max(0, subtotal - descuento) + envio` |
| `$estado` | `Configuracion::estadoCocina()` |
| `$cumple` | `Cliente::esCumpleanos($cliente)` |

## El bloqueo por horario

Aparece **dos veces**:

1. Un aviso rojo arriba si `!$estado['abierta']`:
   "Cocina Cerrada | Abre HH:MM. El pago esta bloqueado."
2. El botón de confirmar lleva `disabled` y `disabled:opacity-40 disabled:cursor-not-allowed`.

> Es solo la mitad de la defensa: `CheckoutController` **vuelve a comprobar**
> `estadoCocina()` antes de crear el pedido, porque el `disabled` se puede quitar desde el
> navegador.

## El resumen
Líneas del pedido → Subtotal → (Descuento cumpleaños en verde, solo si `$descuento > 0`)
→ Envío → **Total a Pagar**.

## El método de pago

Dos botones tipo pestaña manejados con Alpine (`x-data="{ metodo: 'digital', banco: 'Nequi' }"`),
y un campo oculto que es lo que realmente se envía:

```php
<input type="hidden" name="metodo_pago" :value="metodo">
```

Si el método es `digital` se despliega un bloque con tres bancos (Nequi, Daviplata,
Bancolombia) y un campo de cuenta.

> ⚠️ **`banco` y `cuenta` no se guardan en ninguna parte.** `CheckoutController` solo lee
> `direccion` y `metodo_pago`. El selector de banco es maqueta: no hay pasarela de pago,
> y el `comprobante` que se guarda es un código inventado (`PAGO-XXXXXXXX`).

## El punto de no retorno

Un aviso azul lo anuncia, y el botón añade una confirmación del navegador:

```php
onclick="return confirm('Confirmar tu pedido por $ X? Pasara directamente a cocina (punto de no retorno).')"
```

## Notas
- Tras confirmar, `CheckoutController` crea el pedido, vacía el carrito y redirige a
  `/client/ordenes`.
- Con pago **digital** el pago se aprueba al instante (y el trigger descuenta inventario);
  con **efectivo** queda pendiente hasta la entrega. Es la regla "Cero Crédito".
