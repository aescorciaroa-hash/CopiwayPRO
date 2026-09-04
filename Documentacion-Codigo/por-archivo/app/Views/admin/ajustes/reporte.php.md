# `app/Views/admin/ajustes/reporte.php`

## Ubicación
`app/Views/admin/ajustes/reporte.php`

## Propósito
El **reporte de cierre de caja imprimible**: ingresos por método de pago, consumo de
insumos (escandallo) y liquidación de cada domiciliario.

## Quién la renderiza

`public/index.php`, en una ruta especial **fuera del `switch` de vistas**:

```php
if ($uri === '/admin/ajustes/reporte') {
    $cierreModel = new CierreCaja();
    $config = (new Configuracion())->get();
    $cierre = $cierreModel->obtener($_GET['fecha'] ?? date('Y-m-d'));
    require dirname(__DIR__) . '/app/Views/admin/ajustes/reporte.php';
    exit;
}
```

La fecha se puede cambiar con `?fecha=YYYY-MM-DD`.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$cierre` | `CierreCaja::obtener($fecha)` — `totales`, `escandallo`, `liquidaciones`, … |
| `$config` | `Configuracion::get()` |

Empieza con dos atajos:
```php
$t = $cierre['totales'];
$totalRecaudo = (float) $t['total_efectivo'] + (float) $t['total_digital'];
```

## ⚠️ Es una página HTML completa, sin layout

Como la tirilla de cocina, trae su propio `<!DOCTYPE>`, carga Tailwind del CDN y define su
`@media print`:

```css
@media print { .no-print { display: none !important } }
```

Así el botón de imprimir no sale en el papel.

## Las tres secciones

1. **Resumen de Ingresos** — total de ventas, efectivo, digital y número de órdenes.
2. **Consumo de Insumos (Escandallo)** — por ingrediente, cuánto se consumió según las
   recetas vendidas y cuál es el stock real.
3. **Liquidación de Domiciliarios** — por repartidor: base asignada + recaudo en efectivo
   = **lo que debe entregar**.

Las tres tienen su estado vacío ("sin registros") por si el día no tuvo movimiento.

## Notas
- ⚠️ En la sección de escandallo, el "teórico" y el "real" salen del **mismo valor**
  (`CierreCaja::generar()` guarda `stock_teorico = stock_real`), así que la comparación
  para detectar mermas **siempre da cero**. Ver
  `../../../app/Models/CierreCaja.php.md`.
- Esta vista solo **muestra**: no guarda nada. Quien escribe en `REPORTE_CAJA` es la acción
  `generarCierre` de `AjustesController`.
- Como `obtener()` recalcula en vez de leer el reporte guardado, imprimir el reporte de un
  día pasado muestra los números **recalculados hoy**, no los congelados en su momento.
