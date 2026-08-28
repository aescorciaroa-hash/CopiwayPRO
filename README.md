# CopiwayPRO — Sistema Hamburguer Copiway

Sistema web para una "Dark Kitchen": pedidos por autoservicio, pago obligatorio antes
de cocina, tablero de cocina (KDS), logística de domiciliarios y cierre de caja.

Construido con **PHP 8.1 (MVC Simplificado, MySQLi Tradicional) + MySQL + Tailwind CSS + Alpine.js**.
Sin paso de compilación: corre directo en Laragon/Apache.

## Requisitos

- Laragon (Apache + MySQL 8) — o cualquier Apache/PHP 8.1+ con la extensión `mysqli`.
- PHP 8.1 o superior.

## Instalación

1. Copia el proyecto en `C:\laragon\www\Copiway`.
2. Inicia **Apache** y **MySQL** desde Laragon.
3. Crea la base de datos y los datos de ejemplo:

   ```
   cd C:\laragon\www\Copiway
   php database/install.php
   ```

   Esto crea la base `hamburguer_copiway`, carga el esquema (tablas + triggers)
   y datos de prueba (`database/seed.sql`).

4. Abre el proyecto en el navegador:
   - Con dominio Laragon: **http://copiway.test**
   - Con localhost: **http://localhost/Copiway/public/**
     (en este caso edita `config/config.php` → `app.base_url = '/Copiway/public'`)

> Si Laragon apunta el DocumentRoot a la raíz del proyecto en vez de `/public`,
> el archivo `.htaccess` de la raíz reenvía todo a `public/` automáticamente.

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
config/database.php  Conexión global MySQLi ($conn)
config/config.php    Configuración de la app (URL base, zona horaria)
database/            schema.sql, seed.sql, install.php
public/              Punto de entrada (index.php) + assets
app/Core/            Helpers globales, sesión, autenticación y períodos
app/Controllers/     Scripts de controladores por módulo (Admin, Client, Kitchen, Delivery)
app/Models/          Clases de modelo con consultas preparadas MySQLi
app/Views/           Plantillas PHP y layouts tradicionales
```

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
- **Cocina (KDS)** — login de estación en dos pasos, tablero Kanban (Pendientes /
  En Preparación / Listos), cronómetro por pedido, SLA >15 min con borde pulsante,
  resaltado SIN/EXTRA, inventario crítico, resumen por producto, tirilla imprimible.
- **Domiciliario** — login de estación, disponibilidad, pedidos disponibles,
  autoasignación, ruta, contacto de última milla, validación de PIN, cobro en
  efectivo y cierre del ciclo.

El descuento automático de inventario y la suma de puntos al aprobarse un pago los
ejecuta un **trigger de la base de datos** (`trg_pago_aprobado`).
