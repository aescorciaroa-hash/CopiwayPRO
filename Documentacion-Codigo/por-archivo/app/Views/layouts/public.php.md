# `app/Views/layouts/public.php`

## Ubicación
`app/Views/layouts/public.php`

## Propósito
El **marco de la vitrina pública**: la barra flotante superior y el pie de página que
envuelven la landing. Es el único layout que ve alguien sin iniciar sesión.

## Quién lo usa
`public/index.php`, en el `case '/'` / `'/home'` y en el `default` del `switch`.

## Variables que espera
| Variable | De dónde viene |
|---|---|
| `$content` | El HTML de la vista, ya capturado con `ob_get_clean()` |

## Estructura
```
<html> <head> ← partials/head.php + <meta name="csrf-token">
<body>
  <header>  barra flotante negra, fija arriba (rounded-full)
            logo · Menu/Nosotros/Testimonios/Contacto · tema · "Ingresar"
  <main>    <?= $content ?>
  <footer>  4 columnas: marca, Compañía, Legal, Suscríbete + redes
  partials/toast.php  ·  assets/js/app.js  ·  clear_errors()
```

## Detalles

- Los enlaces del menú son **anclas** (`#menu`, `#nosotros`, `#testimonios`,
  `#contacto`) que apuntan a secciones de `home/index.php`. El `<html>` lleva
  `class="scroll-smooth"` para que el desplazamiento sea suave.
- El botón de tema está **escrito a mano** aquí (con colores oscuros para la barra
  negra) en vez de incluir `partials/theme-toggle.php`.
- El pie tiene un formulario de suscripción y enlaces legales (incluido "Política de
  Privacidad (Habeas Data)") que **no llevan a ninguna parte**: son `href="#"`, maqueta.
- El año del copyright sale de `date('Y')`.

## Notas
- Como todos los layouts, cierra llamando a `clear_errors()` para vaciar
  `$_SESSION['_errors']` y `_old` una vez pintada la página.
