# Documentación código por código — CopiwayPRO

Esta carpeta documenta **cada archivo del proyecto por separado**, con el mismo nombre
que tiene el archivo real y organizada en las **mismas carpetas** que el código.

> Para una explicación general (qué es MVC, conceptos de PHP, reglas de negocio,
> preguntas de examen) mira la otra carpeta: **`Documentacion.md/`**.
> Esta carpeta es la referencia archivo por archivo.

## Cómo está organizada

```
Documentacion-Codigo.md/
├── public/
│   ├── index.php.md          ← documenta public/index.php
│   ├── router.php.md
│   ├── .htaccess.md
│   └── assets/
│       ├── css/app.css.md
│       └── js/app.js.md
├── config/
│   ├── database.php.md       ← documenta la conexión global MySQLi
│   └── config.php.md
├── app/
│   ├── Core/
│   │   ├── Session.php.md
│   │   ├── Auth.php.md
│   │   ├── Periodo.php.md
│   │   └── helpers.php.md
│   ├── Controllers/
│   │   ├── HomeController.php.md
│   │   ├── AuthController.php.md
│   │   ├── Admin/    (8 archivos)
│   │   ├── Client/   (7 archivos)
│   │   ├── Kitchen/  (1 archivo)
│   │   └── Delivery/ (1 archivo)
│   ├── Models/       (11 archivos)
│   └── Views/
│       ├── layouts/  (7 archivos)
│       ├── partials/ (5 archivos)
│       └── ...       (una carpeta por módulo)
└── database/
    ├── schema.sql.md
    ├── seed.sql.md
    └── install.php.md
```

## Formato de cada archivo `.md`

Cada documento tiene esta estructura:

1. **Ubicación** — ruta real del archivo.
2. **Propósito** — para qué sirve, en una o dos frases.
3. **Contenido** — métodos y funciones principales.
4. **Notas** — relación con el funcionamiento general y reglas de negocio.

## Índice rápido de carpetas

| Carpeta | Qué contiene | Nº archivos |
|---------|--------------|-------------|
| `public/` | Punto de entrada y archivos estáticos | 5 |
| `config/` | Conexión MySQLi y configuración de la app | 2 |
| `app/Core/` | Sesión, autenticación, períodos y helpers | 4 |
| `app/Controllers/` | Scripts de controladores por módulo | 19 |
| `app/Models/` | Clases de modelos con MySQLi | 11 |
| `app/Views/` | Plantillas HTML y layouts | ~40 |
| `database/` | Esquema, datos de ejemplo, instalador | 3 |

