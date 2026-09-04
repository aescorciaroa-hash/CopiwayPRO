# 03 · Estructura de carpetas

```
Copiway2/
├── public/                  ← ÚNICA carpeta que debería ver el navegador
│   ├── index.php            ← Front Controller: enrutado + control de acceso + vistas
│   ├── router.php           ← Solo para el servidor de pruebas de PHP (php -S)
│   ├── .htaccess            ← Apache: manda todo a index.php
│   └── assets/
│       ├── css/app.css      ← Estilos personalizados
│       └── js/app.js        ← Utilidades JavaScript (window.Copiway)
│
├── .htaccess                ← Reenvía a public/ si el DocumentRoot es la raíz
│
├── config/
│   ├── database.php         ← Conexión MySQLi global ($conn). AQUÍ van los datos reales
│   └── config.php           ← Zona horaria, base_url, debug y rutas de carpetas
│
├── app/
│   ├── Core/                ← 4 archivos de utilidades (NO es un framework)
│   │   ├── Session.php      ← Sesión, mensajes flash y token CSRF
│   │   ├── Auth.php         ← Login, rol en sesión y consultas de autenticación
│   │   ├── Periodo.php      ← Traduce "hoy" / "semana" / "mes" a un rango de fechas
│   │   └── helpers.php      ← Funciones globales: e(), url(), money(), redirect(), uuid()
│   │
│   ├── Controllers/         ← LÓGICA: 15 scripts planos que procesan $_POST / $_GET
│   │   ├── AuthController.php  ← login, registro, recuperación, logout
│   │   ├── Admin/           ← 6: Ajustes, Clientes, Comandas, Inventario, Menu, Personal
│   │   ├── Client/          ← 6: Carrito, Catalogo, Checkout, Creador, Historial, Perfil
│   │   ├── Kitchen/         ← 1: KdsController (panel de cocina)
│   │   └── Delivery/        ← 1: PanelController (panel de domiciliario)
│   │
│   ├── Models/              ← DATOS: 11 clases con consultas preparadas MySQLi
│   │   ├── Usuario.php      ← Login unificado sobre las 4 tablas de cuentas
│   │   ├── Cliente.php, Empleado.php, Categoria.php, Ingrediente.php
│   │   ├── Producto.php     ← Catálogo, recetas, costo y disponibilidad
│   │   ├── Pedido.php       ← Consultas y reportes de pedidos (KPIs, listados)
│   │   ├── PedidoServicio.php ← Crear pedidos (transacción) y cambiar de estado
│   │   ├── Carrito.php      ← Carrito guardado en $_SESSION['carrito']
│   │   ├── Configuracion.php← Ajustes generales y estado de la cocina
│   │   └── CierreCaja.php   ← Cálculo y generación del reporte de caja
│   │
│   └── Views/               ← VISTAS: HTML + PHP tradicional
│       ├── layouts/         ← 6 layouts: public, auth, admin, client, kitchen, delivery
│       ├── partials/        ← 5 componentes: head, toast, theme-toggle,
│       │                       admin-notificaciones, autorefresh
│       ├── auth/            ← login, register, forgot
│       └── home/, admin/, client/, kitchen/, delivery/
│
├── database/
│   ├── schema.sql          ← Estructura: 20 tablas + 20 triggers
│   ├── seed.sql            ← Datos de prueba
│   └── install.php         ← Instalador (crea la BD y ejecuta los dos .sql)
│
├── storage/                ← Archivos temporales o de registro (logs)
│
├── Documentacion/          ← Entrega SENA: SQL, diagramas ER/clases, casos de uso,
│                             mockups, requerimientos y diagramas de actividad
└── Documentacion-Codigo/   ← Documentación del código
    ├── guia/               ← Estos 15 tutoriales (para estudiar / examen)
    └── por-archivo/        ← Explicación archivo por archivo
```

## La regla de oro: `public/` es la única puerta

La idea es que solo `public/` sea alcanzable desde el navegador, para que nadie pueda
pedir `config/database.php` y leer la contraseña de la base de datos. En la práctica
depende de cómo abras el proyecto:

- **Con el dominio de Laragon** (`http://copiway2.test`), Apache apunta su DocumentRoot
  a `public/`, y `app/`, `config/` y `database/` quedan **realmente** fuera de la raíz web.
- **Con `http://localhost/Copiway2/public/`**, el proyecto entero **sí está** dentro de
  la raíz web; lo único que tapa esas carpetas es el `.htaccess` de la raíz, que reescribe
  cualquier petición hacia `public/`.

> ⚠️ La forma robusta es la primera. La segunda solo protege mientras `mod_rewrite` esté
> activo y Apache respete los `.htaccess`. Ver `12-Seguridad.md` §10.

## Dónde está la lógica de cada cosa

Es la pregunta que más confunde al leer el proyecto:

| Si quieres tocar… | Ve a… |
|---|---|
| Lo que **muestra** una pantalla | `app/Views/<area>/<pantalla>.php` |
| Los **datos** que recibe esa pantalla | el `case` correspondiente en `public/index.php` |
| Lo que pasa al **enviar un formulario** | el script de `app/Controllers/...` y su `$action` |
| Una **consulta SQL** | el modelo de `app/Models/` |
| Quién **puede entrar** a una ruta | el guardia del principio de `public/index.php` |

## Nombres y convenciones

- **Controladores**: scripts planos en `app/Controllers/...`. No son clases: se ejecutan
  de arriba abajo y ramifican según `$action`.
- **Modelos**: clases en singular en `app/Models/...`, que reciben la conexión global
  `$conn` en su constructor.
- **Vistas**: PHP + HTML en `app/Views/...`, renderizadas con `ob_start()` y metidas en
  un layout desde `public/index.php`.
- **Sin namespaces ni autoload**: todo se carga con `require_once` explícito.
