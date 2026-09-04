# Documentación técnica de CopiwayPRO

> Sistema de gestión para una **Dark Kitchen** (cocina oculta) de hamburguesas.
> Hecho en **PHP puro con arquitectura MVC Simplificada**, **MySQLi Tradicional**, **Tailwind CSS** y **Alpine.js**.

Esta carpeta explica **todo el sistema** en lenguaje sencillo, pensada para estudiar
antes de un examen. Lee los archivos en orden.

---

## Índice

| # | Archivo | De qué trata |
|---|---------|--------------|
| 01 | [Introducción y visión general](01-Introduccion-y-Vision-General.md) | Qué es el sistema, para qué sirve, quiénes lo usan |
| 02 | [Cómo ejecutar el proyecto](02-Como-Ejecutar-el-Proyecto.md) | Levantar Laragon, la base de datos, entrar al sistema |
| 03 | [Estructura de carpetas](03-Estructura-de-Carpetas.md) | Qué hay en cada carpeta y archivo |
| 04 | [Conceptos de PHP que debes saber](04-Conceptos-de-PHP-que-Debes-Saber.md) | Clases, `mysqli`, sentencias preparadas, sesiones, `match`, arreglos… |
| 05 | [Qué es MVC y cómo fluye una petición](05-Que-es-MVC-y-Como-Fluye-una-Peticion.md) | El patrón Modelo-Vista-Controlador simplificado con este proyecto |
| 06 | [El núcleo del sistema (Core y Config)](06-El-Nucleo-del-Framework-Core.md) | `database.php`, `helpers.php`, `Session`, `Auth`, `Periodo` (el nombre del archivo dice "Framework", pero no lo hay) |
| 07 | [Punto de entrada, rutas y control de acceso](07-Rutas-y-Middleware.md) | El Front Controller `public/index.php`: normalizar la URL, el guardia por rol, el despacho a controladores y el `switch` de vistas |
| 08 | [Modelos (la capa de datos)](08-Modelos-La-Capa-de-Datos.md) | Cada modelo y sus consultas preparadas MySQLi |
| 09 | [Controladores (la lógica)](09-Controladores-La-Logica.md) | Cada controlador como script plano procesador de peticiones |
| 10 | [Vistas, layouts y frontend](10-Vistas-Layouts-y-Frontend.md) | Inclusión de layouts, Tailwind, Alpine, toasts |
| 11 | [Base de datos: tablas y triggers](11-Base-de-Datos-Tablas-y-Triggers.md) | Las 20 tablas, relaciones y los disparadores automáticos |
| 12 | [Seguridad](12-Seguridad.md) | SQL, XSS, CSRF, bcrypt, control de acceso por rol y **puntos débiles conocidos** |
| 13 | [Reglas de negocio](13-Reglas-de-Negocio.md) | Cero crédito, tarifa plana, punto de no retorno, fidelización… |
| 14 | [Ciclo de vida de un pedido](14-Ciclo-de-Vida-de-un-Pedido.md) | Del carrito del cliente hasta el cierre de caja |
| 15 | [Preguntas y respuestas para el examen](15-Preguntas-y-Respuestas-para-el-Examen.md) | Posibles preguntas con respuestas cortas |

---

## Resumen en 30 segundos

- **No usa ningún framework avanzado ni POO compleja**. Es una arquitectura **MVC Simplificada**
  con código PHP plano y tradicional.
- Conexión **MySQLi directa y global** (`$conn`) en `config/database.php` (sin PDO ni Singleton).
- **Cero namespaces o use**: Se utilizan `require_once` simples e instanciaciones directas.
- Los **Controladores** son scripts procesadores de peticiones (`$_POST`/`$_GET`), invocan los **Modelos** y redirigen.
- Los **Modelos** usan sentencias preparadas nativas de **MySQLi** (`prepare`, `bind_param`, `execute`, `get_result`).
- La base de datos tiene **triggers** que hacen trabajo automático (generar IDs,
  descontar inventario, sumar puntos de fidelidad).
- Hay **4 tipos de usuario**: Administrador, Cliente, Cocina y Domiciliario. Cada uno
  tiene su propio panel y sus propias vistas.
- **Todo pasa por `public/index.php`**: ahí están el enrutado, el control de acceso por
  rol y la lógica de cada pantalla GET. Es el archivo que hay que leer primero.
- Las **pantallas GET no tienen controlador**; los controladores solo procesan POST y
  devuelven JSON.

