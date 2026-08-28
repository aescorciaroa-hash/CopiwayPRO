# `app/Controllers/HomeController.php`

## Ubicación
`app/Controllers/HomeController.php` · namespace `App\Controllers`

## Propósito
Controlador de la **página de inicio pública** (landing). Es la única página que ve
alguien sin iniciar sesión (además del login/registro).

## Dependencias
`App\Core\Controller`, `App\Models\Producto`, `App\Models\Categoria`.

## Métodos

### `index(): string` — ruta `GET /`
```php
return $this->view('home/index', [
    'title'      => 'Inicio',
    'productos'  => Producto::catalogo(),   // solo activos, con marca "agotado"
    'categorias' => Categoria::menu(),      // categorías de tipo 'menu'
], 'public');
```
Muestra la vista `home/index` dentro del layout `public`. Esa vista tiene: hero,
características, menú con chips de categoría, sección "nosotros", testimonios, contacto
con mapa y footer.

## Notas
- No requiere rol (ruta pública).
- Reutiliza el mismo `Producto::catalogo()` que usa el panel del cliente.
