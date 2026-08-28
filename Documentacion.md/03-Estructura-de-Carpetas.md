# 03 · Estructura de carpetas

```
Copiway/
├── public/                  ← ÚNICA carpeta expuesta al navegador
│   ├── index.php            ← Punto de entrada: carga conexión y vistas directas
│   ├── router.php           ← Servidor de pruebas PHP
│   ├── .htaccess            ← Apache: redirecciona peticiones a public/
│   └── assets/
│       ├── css/app.css      ← Estilos personalizados
│       └── js/app.js        ← Utilidades JavaScript frontend
│
├── config/
│   ├── database.php         ← Conexión directa MySQLi ($conn) y manejo de errores
│   └── config.php           ← URL base, zona horaria y ajustes de la app
│
├── app/
│   ├── Core/                ← Funciones y utilidades esenciales
│   │   ├── Session.php      ← Manejo de sesiones PHP y notificaciones flash
│   │   ├── Auth.php         ← Métodos de autenticación y verificación de credenciales
│   │   ├── Periodo.php      ← Traducción de períodos ("hoy", "semana", "mes")
│   │   └── helpers.php      ← Funciones globales: e(), url(), money(), redirect(), uuid()
│   │
│   ├── Controllers/         ← LÓGICA: Scripts planos que procesan peticiones ($_POST/$_GET)
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── Admin/           ← 8 scripts procesadores del panel administrativo
│   │   ├── Client/          ← 7 scripts procesadores del cliente
│   │   ├── Kitchen/         ← Panel de cocina (KDS)
│   │   └── Delivery/        ← Panel de domiciliario
│   │
│   ├── Models/              ← DATOS: Clases inyectadas con $conn y consultas MySQLi
│   │   ├── Usuario.php      ← Consulta de usuarios en las tablas de cuentas
│   │   ├── Cliente.php, Empleado.php, Categoria.php, Ingrediente.php
│   │   ├── Producto.php     ← Catálogo, recetas, costo y disponibilidad
│   │   ├── Pedido.php       ← Consultas y reporte de pedidos (KPIs, listados)
│   │   ├── PedidoServicio.php ← Creación de pedidos con transacciones y cambio de estado
│   │   ├── Carrito.php      ← Carrito guardado en $_SESSION['carrito']
│   │   ├── Configuracion.php← Ajustes generales y estado de la cocina
│   │   └── CierreCaja.php   ← Cálculo del reporte de caja
│   │
│   └── Views/               ← VISTAS: Archivos HTML + PHP tradicional
│       ├── layouts/         ← Layouts principales (admin, client, kitchen, delivery)
│       ├── partials/        ← Componentes reutilizables (head, toast, theme-toggle)
│       ├── auth/            ← Pantallas de login, register, forgot
│       ├── admin/, client/, kitchen/, delivery/, home/, errors/
│
├── database/
│   ├── schema.sql          ← Estructura: tablas + triggers
│   ├── seed.sql            ← Datos de prueba
│   └── install.php         ← Script de instalación automática
│
└── storage/                ← Archivos temporales o de registro
```

## La regla de oro: `public/` es la única puerta

Solo la carpeta `public/` está expuesta al navegador. Todo lo demás
(`app/`, `config/`, `database/`) está **fuera del alcance web**. Así, aunque alguien
sepa la ruta, no puede pedir `config/database.php` por el navegador y ver la contraseña
de la base de datos.

## Nombres y convenciones

- **Controladores**: Procesadores de peticiones directos localizados en `app/Controllers/...`.
- **Modelos**: Clases en singular en `app/Models/...` inyectadas con la conexión global `$conn`.
- **Vistas**: Vistas tradicionales PHP + HTML en `app/Views/...`.
- **Sin Namespaces**: El proyecto utiliza inclusión explícita con `require_once` de forma sencilla y directa.

