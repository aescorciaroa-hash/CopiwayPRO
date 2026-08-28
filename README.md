# CopiwayPRO — Sistema Hamburguer Copiway

Sistema web para una "Dark Kitchen": pedidos por autoservicio, pago obligatorio antes
de cocina, tablero de cocina (KDS), logística de domiciliarios y cierre de caja.

Construido con **PHP 8.1 (MVC Simplificado, MySQLi Tradicional) + MySQL + Tailwind CSS + Alpine.js**.
Sin paso de compilación: corre directo en Laragon/Apache.

## Requisitos

- Laragon (Apache + MySQL 8) — o cualquier Apache/PHP 8.1+ con la extensión `mysqli`.
- PHP 8.1 o superior.

## Instalación

1. Copia el proyecto en `C:\laragon\www\Copiway2`.
2. Inicia **Apache** y **MySQL** desde Laragon.
3. Crea la base de datos y los datos de ejemplo:

   ```
   cd C:\laragon\www\Copiway2
   php database/install.php
   ```

   Esto crea la base `hamburguer_copiway`, carga el esquema (tablas + triggers)
   y datos de prueba (`database/seed.sql`).

4. Abre el proyecto en el navegador (cualquiera de las dos funciona igual):
   - Con dominio Laragon: **http://copiway2.test**
   - Con localhost: **http://localhost/Copiway2/public/**

> La URL base se detecta sola (`app/Core/helpers.php` → `APP_BASE`), así que no hay
> que editar nada de configuración según cómo abras el proyecto. Si Laragon apunta el
> DocumentRoot a la raíz en vez de `/public`, el `.htaccess` de la raíz reenvía a `public/`.

## Cuentas de prueba

| Rol           | Correo                     | Contraseña | PIN estación |
|---------------|----------------------------|------------|--------------|
| Administrador | admin@copiway.com          | admin123   | —            |
| Cliente       | cliente@copiway.com        | cliente123 | —            |
| Cocina (KDS)  | cocina@copiway.com         | cocina123  | 1234         |
| Domiciliario  | domiciliario@copiway.com   | domi123    | 5678         |

> La cuenta de Administrador se crea directo en la base de datos (RF-25).
> El Administrador crea las cuentas de empleados. El Cliente se registra solo.

## Configuración

La conexión a la BD se define en `config/database.php` usando la extensión `mysqli` estándar.
Las variables de aplicación adicionales están en `config/config.php`.
Por defecto: `127.0.0.1:3306`, usuario `root`, sin contraseña (Laragon estándar).

## Estructura

```
config/              database.php (conexión MySQLi $conn) + config.php (app)
database/            schema.sql, seed.sql, install.php
public/              index.php (front controller + rutas) + router.php + assets/
app/Core/            helpers, Session, Auth, Periodo
app/Controllers/     un archivo por módulo (Admin/, Client/, Kitchen/, Delivery/, AuthController)
app/Models/          clases de datos con consultas preparadas MySQLi
app/Views/           plantillas PHP: por rol (admin/ client/ kitchen/ delivery/) + layouts/ + partials/
storage/             logs (ignorado por git)

Documentacion/            Entrega SENA: SQL, diagramas ER/clases, casos de uso, mockups, RF/RNF
Documentacion-Codigo/
  ├── guia/               15 tutoriales para estudiar el código (MVC, seguridad, reglas de negocio…)
  └── por-archivo/         explicación archivo por archivo
```

Todo el enrutado vive en `public/index.php`: un guardia de acceso por rol (RBAC) + login
de estación en dos pasos para Cocina/Domiciliario, luego el despacho a controladores (POST /
acciones AJAX) y el render de vistas (GET).

## Módulos implementados

- **Landing pública** — vitrina, menú, contacto.
- **Autenticación** — login unificado, registro de cliente (Habeas Data), recuperación.
- **Cliente** — catálogo con bloqueo "Agotado", personalización (SIN/EXTRA),
  creador interactivo, carrito, checkout (digital/efectivo, tarifa plana, descuento
  de cumpleaños, bloqueo por horario, punto de no retorno), rastreo de órdenes con
  PIN, historial, recompra en 1 clic, reseñas, perfil.
- **Administrador** — tablero analítico con filtros de periodo, comandas activas +
  pedido manual + corrección de dirección, rutas y flota, gestión de menú + recetas
  (escandallo con rentabilidad en vivo) + categorías, inventario express + movimientos,
  personal (alta/baja/soft delete/eliminación), directorio de clientes, ajustes
  (tarifa, margen, horario, pausa de emergencia) y cierre de caja con auditoría de
  insumos, liquidación de domiciliarios y reporte imprimible.
- **Cocina (KDS)** — login de estación con diseño en tarjeta blanca limpia, tablero Kanban KDS de 3 columnas (Pendientes / En Preparación / Listos), cronómetro por pedido, reloj digital en vivo, resplandor de borde pulsante para pedidos en preparación, resaltado de personalizaciones (SIN/EXTRA), alerta desplegable de inventario crítico, resumen de preparación por lote y tirilla de comanda imprimible.
- **Domiciliario** — login de estación rediseñado, interfaz táctica dividida en pantalla completa (split-screen), lista lateral de pedidos con banner de cobro en efectivo, botón directo de WhatsApp para contactar al cliente, integraciones instantáneas con Waze y Google Maps, mapa interactivo Leaflet en pantalla completa con tarjeta flotante de destino y barra inferior de navegación y entrega validando el PIN de 4 dígitos.

El descuento automático de inventario y la suma de puntos al aprobarse un pago los
ejecuta un **trigger de la base de datos** (`trg_pago_aprobado`).

