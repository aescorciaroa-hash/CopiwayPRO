# `app/Views/layouts/client.php`

## Ubicación
`app/Views/layouts/client.php`

## Propósito
El marco del **panel del cliente**: sidebar de 6 secciones con contador de carrito, y
cabecera con el indicador "Cocina Abierta / Cerrada".

## Quién lo usa
`public/index.php`, en los 7 `case` de `/client...`.

## ⚠️ Este layout consulta la base de datos

A diferencia de los demás, **carga dos modelos por su cuenta**:

```php
require_once dirname(__DIR__) . '/../Models/Carrito.php';
require_once dirname(__DIR__) . '/../Models/Configuracion.php';

$estado     = (new Configuracion())->estadoCocina();
$cartCount  = (new Carrito())->cantidad();
```

Lo hace porque el badge del carrito y el estado de la cocina tienen que aparecer en
**todas** las pantallas del cliente, y sería repetitivo calcularlos en los 7 `case` de
`public/index.php`.

## Variables que espera
| Variable | De dónde viene |
|---|---|
| `$content` | El HTML de la vista |

## El menú
```php
$nav = [
    ['/client',          'utensils-crossed', 'Explorar Menú'],
    ['/client/creador',  'plus-circle',      'Creador Interactivo'],
    ['/client/carrito',  'shopping-cart',    'Carrito de Pedidos'],
    ['/client/ordenes',  'package-search',   'Órdenes Activas'],
    ['/client/historial','history',          'Historial y Recompras'],
    ['/client/perfil',   'user-cog',         'Mi Perfil'],
];
```

En la fila del carrito, si `$cartCount` es mayor que 0, se pinta un **badge naranja** con
el número de unidades.

## El indicador de cocina
```php
<?= $estado['abierta'] ? 'Cocina Abierta' : 'Cocina Cerrada' ?> | <?= $estado['apertura'] ?> - <?= $estado['cierre'] ?>
```
Verde si está abierta, rojo si no. Es la señal visible de las reglas "Horarios
Automáticos" y "Pausa de Emergencia".

## Notas
- Misma mecánica de sidebar responsive con Alpine que `layouts/admin.php`.
- No incluye la campana de notificaciones (eso es solo del admin).
