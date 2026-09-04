# Documentación archivo por archivo — CopiwayPRO

Esta carpeta documenta **todos los archivos del proyecto**, uno por uno, con el mismo
nombre que tiene el archivo real (`Producto.php` → `Producto.php.md`) y organizada en las
**mismas carpetas** que el código.

> Para la explicación general (qué es MVC, conceptos de PHP, reglas de negocio,
> preguntas de examen) mira la carpeta hermana: **`../guia/`**.

## Cobertura

```
por-archivo/
├── public/                   (5)  index.php, router.php, .htaccess, app.css, app.js
├── config/                   (2)  database.php, config.php
├── database/                 (3)  schema.sql, seed.sql, install.php
└── app/
    ├── Core/                 (4)  Session, Auth, Periodo, helpers
    ├── Controllers/         (15)  AuthController + Admin/ Client/ Kitchen/ Delivery/
    ├── Models/              (11)  Usuario, Cliente, Empleado, Producto, Categoria,
    │                              Ingrediente, Pedido, PedidoServicio, Carrito,
    │                              Configuracion, CierreCaja
    └── Views/               (40)  layouts/ partials/ auth/ home/
                                   admin/ client/ kitchen/ delivery/
```

| Carpeta | Qué contiene | Nº de docs |
|---------|--------------|-----------|
| `public/` | Front controller y archivos estáticos | 5 |
| `config/` | Conexión MySQLi y configuración de la app | 2 |
| `database/` | Esquema, datos de ejemplo e instalador | 3 |
| `app/Core/` | Sesión, autenticación, períodos y helpers | 4 |
| `app/Controllers/` | Scripts procesadores por módulo | 15 |
| `app/Models/` | Clases de modelo con MySQLi | 11 |
| `app/Views/` | Layouts, partials y pantallas | 40 |
| | **Total** | **80** |

**No queda ningún archivo del proyecto sin documentar.**

## Formato de cada archivo `.md`

1. **Ubicación** — ruta real del archivo.
2. **Propósito** — para qué sirve, en una o dos frases.
3. **Dependencias** / **Quién la renderiza** — cómo encaja con el resto.
4. **Variables que espera** / **Acciones** / **Métodos** — el contenido, punto por punto.
5. **Notas** — relación con las reglas de negocio, y las trampas o limitaciones conocidas.

Los avisos marcados con ⚠️ señalan cosas que **no funcionan como parecen**: campos de
formulario que nadie lee, valores de maqueta, consultas SQL dentro de una vista, o
funciones escritas pero sin usar. Merece la pena leerlos antes de tocar el código.

## Por dónde empezar

| Si quieres entender… | Lee… |
|---|---|
| **Cómo funciona el sistema entero** | `public/index.php.md` — enrutado, control de acceso y qué modelo alimenta cada vista |
| Qué pasa al enviar un formulario | El `.md` del controlador y su lista de acciones |
| De dónde salen los datos de una pantalla | El `.md` de la vista, sección "Variables que espera" |
| Una consulta SQL | El `.md` del modelo |
| Cómo se monta el HTML | `app/Views/layouts/*.md` y `app/Views/partials/*.md` |

## Dos convenciones del proyecto

- **Los controladores no son clases.** Son scripts planos que ramifican según `$action`,
  así que sus docs listan **acciones**, no métodos. Quién decide el `$action` está
  explicado en `public/index.php.md`.
- **Las pantallas GET no tienen controlador.** Su lógica vive en el `switch` de
  `public/index.php`; cada doc de vista dice en qué `case` está.
- Los archivos que empiezan por guion bajo (`_modal_producto.php`, `_modales.php`) son
  **fragmentos**: dependen del componente Alpine de la vista que los incluye y no
  funcionan por su cuenta.
