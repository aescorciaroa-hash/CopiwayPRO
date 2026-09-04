# `app/Views/admin/menu/_modal_producto.php`

## Ubicación
`app/Views/admin/menu/_modal_producto.php`

## Propósito
El modal de **crear / editar producto**, con su **receta (escandallo)** y el cálculo de
rentabilidad en vivo.

Cubre **RF-34** (Configuración de Receta, Costos y Margen de Ganancia).

## Quién lo incluye
`admin/menu/index.php`. Depende de su componente `menuPage()`: no funciona por su cuenta.

## Variables que espera
`$categorias` — para el `<select>` de categoría. El resto (`form`, `receta`,
`ingredientes`, `editando`) son estado de Alpine.

## El formulario

`POST` a `/admin/menu/producto`, con `csrf_field()`.

| Campo | Notas |
|---|---|
| `id_producto` | Oculto. **Vacío = crear, con valor = editar** |
| `nombre`, `descripcion`, `imagen` | Texto |
| `id_categoria` | `<select>` con `$categorias` |
| `precio` | El que dispara el recálculo del margen |
| `etiqueta_destacada` | `ninguna`, `mas_vendido`, … |
| `receta_ingrediente[]` | Arrays paralelos: la fila *i* de uno |
| `receta_cantidad[]` | se corresponde con la fila *i* del otro |

`MenuController` los vuelve a emparejar por índice:
```php
foreach ($ings as $i => $idIng) {
    $items[] = ['id_ingrediente' => $idIng, 'cantidad' => $cants[$i] ?? 0];
}
```

## El editor de receta

Filas dinámicas: `addInsumo()` añade una y `quitarInsumo(i)` la quita. Cada fila es un
`<select>` de ingredientes más una cantidad, y muestra la unidad de medida y el coste
unitario tomados del array `ingredientes` serializado desde PHP.

## El panel de rentabilidad

Debajo del editor, en vivo mientras se escribe:

```
Costo insumos    $ X
Costo empaques   $ Y
Costo total      $ Z
Ganancia         $ (precio - Z)
Margen           N %
```

Los `getter` que lo calculan están en `menuPage()` (ver `../index.php.md`). Lo relevante
es que **separa insumos de empaques** por el `ambito` de la categoría de cada ingrediente.

## Notas
- El modal usa `items-start` y `overflow-y-auto` en el overlay porque es alto: en pantallas
  pequeñas se desplaza en vez de recortarse.
- La receta se guarda entera de una vez: `Producto::guardarReceta()` reemplaza las filas
  anteriores del producto.
