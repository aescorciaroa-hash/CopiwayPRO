# `app/Views/partials/toast.php`

## Ubicación
`app/Views/partials/toast.php`

## Propósito
El sistema de **notificaciones flotantes** (toasts). Pinta los mensajes que quedaron en
la sesión tras un POST, y deja disponible una función global para lanzarlos desde JS.

## Quién lo usa
Los 6 layouts, justo antes de cargar `app.js`.

## De dónde saca los mensajes

**Los lee directamente de `$_SESSION` y los borra**, dentro del método `init()` del
componente Alpine:

```php
$flashes = $_SESSION['_flash'] ?? [];
unset($_SESSION['_flash']);
foreach ($flashes as $f): ?>
    this.push(<?= json_encode($f['type']) ?>, <?= json_encode($f['title']) ?>, <?= json_encode($f['message']) ?>);
<?php endforeach;
```

> Ojo: **no llama a `Session::pullFlash()`**, aunque ese método existe y hace lo mismo.
> Trabaja sobre la superglobal directamente. Las dos pantallas de PIN de estación
> (`kitchen/estacion.php`, `delivery/estacion.php`) **no usan este partial** y pintan los
> flashes con su propio bucle `foreach (($_flash ?? Session::pullFlash()) as $f)`.

El `json_encode()` es lo que hace segura la interpolación de PHP a JavaScript: escapa
comillas y saltos de línea del mensaje.

## El componente Alpine: `toastHub()`

| Método | Qué hace |
|---|---|
| `push(type, title, message)` | Añade el toast, repinta los iconos de Lucide y programa su borrado **a los 5 segundos** |
| `remove(id)` | Lo quita de la lista (también lo llama la "X") |
| `icon(type)` | Traduce el tipo al nombre de un icono de Lucide |
| `init()` | Publica `window.toast(...)` y lanza los flashes de PHP |

Como `init()` hace `window.toast = (type, title, message) => this.push(...)`, **cualquier
script de la página puede lanzar un toast** con `toast('success', 'Título', 'Mensaje')`.

## Los 9 tipos de toast

| Tipo | Color | Icono |
|---|---|---|
| `cart` | naranja (brand) | `shopping-cart` |
| `inventory` | naranja (brand) | `package` |
| `success` | verde | `check-circle` |
| `whatsapp` | verde | `message-circle` |
| `cierre` | verde | `file-check` |
| `danger` | rojo | `alert-triangle` |
| `warning` | ámbar | `alert-circle` |
| `staff` | azul | `users` |
| `config` | morado | `settings` |

Cualquier otro tipo cae en el icono `bell` sin color de borde.

> `AuthController` usa además el tipo `info` en la acción `forgot`. No está en las listas
> de color, así que ese toast se pinta sin borde de color y con el icono `bell`.

## Detalles

- El contenedor va `fixed top-4 right-4 z-[100]`, apilando los toasts en columna.
- La animación de entrada es la clase `.toast-item` de `assets/css/app.css`.
- `x-cloak` evita que se vea el bloque antes de que Alpine arranque.

## Notas
- Es el destino final de todos los `$_SESSION['_flash'][] = [...]` de los controladores:
  ahí se cierra el patrón **POST → Redirect → GET**.
