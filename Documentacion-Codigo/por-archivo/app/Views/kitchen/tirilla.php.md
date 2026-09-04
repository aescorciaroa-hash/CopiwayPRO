# `app/Views/kitchen/tirilla.php`

## Ubicación
`app/Views/kitchen/tirilla.php`

## Propósito
La **tirilla / sticker de empaque** imprimible de un pedido.
Cubre **RF-62** (Impresión de Tirilla o Sticker de Empaque).

## Quién la renderiza

`public/index.php`, en una ruta especial **fuera del `switch` de vistas**:

```php
if (preg_match('#^/kitchen/pedido/([^/]+)/tirilla$#', $uri, $m)) {
    $pedido = $pedidoModel->completo($m[1]);
    if (!$pedido) { http_response_code(404); exit('Pedido no encontrado'); }
    $pedido['codigo'] = $pedidoModel->codigo($pedido);
    $config = (new Configuracion())->get();
    require dirname(__DIR__) . '/app/Views/kitchen/tirilla.php';
    exit;
}
```

Se abre con `target="_blank"` desde el botón "Sticker" del KDS.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$pedido` | `Pedido::completo($id)` + la clave `codigo` |
| `$config` | `Configuracion::get()` — se pasa pero **la vista no lo usa** |

## ⚠️ Es una página HTML completa, sin layout

Trae su propio `<!DOCTYPE>`, carga Tailwind del CDN con una config mínima y define un
`<style>` propio. No usa `partials/head.php` ni `app.css`.

## El CSS de impresión

```css
body { font-family: ui-monospace, 'SF Mono', Menlo, monospace }
@media print {
    .no-print { display: none !important }
    body { background: #fff; padding: 0 }
    .ticket { box-shadow: none !important; border: none !important }
}
```

- **Monoespaciada** en toda la página, para que parezca un recibo de impresora térmica.
- La regla `@media print` quita el botón de imprimir, el fondo gris y la sombra: en papel
  solo queda el contenido.

## Qué imprime

1. **Cabecera** — "HAMBURGUER COPIWAY", "Dark Kitchen · Neiva, Huila" y un NIT.
   ⚠️ Están **escritos a mano en la plantilla**; no salen de `$config` ni de la base de
   datos, aunque `$config` esté disponible.
2. **Datos del pedido** — ticket, fecha, cliente, teléfono y dirección.
3. **Las líneas** con su precio, y debajo las personalizaciones: `SIN` en rojo y `EXTRA`
   en verde (bucle propio, no `mods_html()`).
4. **Totales** — subtotal, domicilio, descuento de cumpleaños (solo si es mayor que 0) y
   TOTAL.
5. **El PIN de entrega** en grande, con `tracking-[0.3em]`.
6. Un "código de barras" hecho con caracteres `|` — decorativo, **no es escaneable**.

## El botón de imprimir
```php
<button onclick="window.print()" class="no-print ...">Imprimir Sticker</button>
```
Llama al diálogo de impresión del navegador. Lleva `no-print` para no salir en el papel.

## Notas
- **Abrir la tirilla no cambia nada en la base de datos.** No se marca ningún campo
  `tirilla_impresa`: la ruta solo lee el pedido y lo pinta.
- Los separadores punteados (`border-dashed`) imitan el corte de una tirilla real.
