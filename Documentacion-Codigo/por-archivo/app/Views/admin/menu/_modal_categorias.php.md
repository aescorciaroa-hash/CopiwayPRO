# `app/Views/admin/menu/_modal_categorias.php`

## Ubicación
`app/Views/admin/menu/_modal_categorias.php`

## Propósito
El modal de **categorías del menú**: crear una nueva y eliminar las existentes.
Cubre **RF-35**.

## Quién lo incluye
`admin/menu/index.php`. Usa la variable `openCategorias` del componente `menuPage()`.

## Variables que espera
`$categorias` — `Categoria::conConteo('menu')`, que trae el campo calculado `items`
(cuántos productos hay en cada una).

## Contenido

### Crear
```php
<form method="post" action="<?= url('/admin/menu/categoria') ?>" class="flex gap-2">
    <?= csrf_field() ?>
    <input name="nombre" required placeholder="Nueva categoria">
    <button>Agregar</button>
</form>
```
`MenuController` la crea siempre con `'ambito' => 'menu'`: desde aquí **no se pueden crear
categorías de insumos**. Esas se gestionan por base de datos.

### Listar y eliminar
Cada categoría muestra su nombre y `· N productos`. El botón de papelera es un `POST` a
`/admin/menu/categoria/{id}/eliminar`, con confirmación del navegador:

```php
onsubmit="return confirm('Eliminar la categoria &quot;<?= e($c['nombre']) ?>&quot;?')"
```

El `&quot;` es el escape de las comillas dentro del atributo HTML.

## ⚠️ Eliminar no comprueba si está vacía

`Categoria::eliminar()` lanza el `DELETE` sin mirar si la categoría tiene productos
dentro. Quien decide es la clave foránea de MySQL, no el PHP. La lista muestra el contador
`N productos` justo para que el admin lo vea antes de pulsar.

## Notas
- La lista tiene `max-h-72 overflow-y-auto`: hace scroll si hay muchas.
