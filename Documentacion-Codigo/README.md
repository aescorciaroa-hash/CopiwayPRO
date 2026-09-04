# Documentación del código — CopiwayPRO

Todo lo relacionado con **cómo está construido el sistema** (no la entrega formal del SENA,
que está en `../Documentacion/`).

Resumen de la arquitectura en una línea: **PHP plano, sin framework, sin namespaces y sin
autoload**; conexión **MySQLi global** (`$conn`); un **Front Controller**
(`public/index.php`) que hace enrutado, control de acceso y renderizado de vistas;
controladores que son **scripts** que ramifican según `$action`; modelos que son clases
con consultas preparadas.

## `guia/`

15 tutoriales para entender y estudiar el proyecto, en orden:

| # | Tema |
|---|------|
| 01 | Introducción y visión general |
| 02 | Cómo ejecutar el proyecto |
| 03 | Estructura de carpetas |
| 04 | Conceptos de PHP que debes saber |
| 05 | Qué es MVC y cómo fluye una petición |
| 06 | El núcleo (`config/` y `app/Core/`) |
| 07 | Punto de entrada, rutas y control de acceso |
| 08 | Modelos — la capa de datos |
| 09 | Controladores — la lógica |
| 10 | Vistas, layouts y frontend |
| 11 | Base de datos: tablas y triggers |
| 12 | Seguridad |
| 13 | Reglas de negocio |
| 14 | Ciclo de vida de un pedido |
| 15 | Preguntas y respuestas para el examen |

Empieza por `guia/01-Introduccion-y-Vision-General.md`.

## `por-archivo/`

Explicación **archivo por archivo** del código: **80 documentos**, uno por cada archivo
del proyecto. Incluye las 40 vistas de `app/Views/` (layouts, partials y pantallas), los
15 controladores, los 11 modelos, `app/Core/`, `config/`, `database/` y `public/`.

No queda ningún archivo sin documentar.

Si solo vas a leer un documento de esa carpeta, que sea **`public/index.php.md`**: es el
que conecta cada ruta con su vista, su modelo y su controlador.

## Los avisos ⚠️

Repartidos por los documentos hay avisos marcados con ⚠️. Señalan cosas que **no funcionan
como parecen**, y conviene leerlas antes de tocar nada o de sustentar el proyecto:

- el token CSRF se genera y se envía, pero **nadie lo valida**;
- campos de formulario que ningún controlador lee (`recordar` en el login, `usuario` en los
  PIN de estación, `banco` y `cuenta` en el checkout);
- valores de maqueta que parecen calculados ("Tiempo promedio: 8.5 min",
  "Llegada est: 12 mins", los testimonios de la landing);
- la auditoría de mermas del cierre de caja, que siempre da cero;
- consultas SQL dentro de vistas (`partials/admin-notificaciones.php`,
  `admin/rutas/index.php`);
- funciones escritas pero sin usar (`Auth::requireRole()`, `Pedido::delTurno()`).

## Si un detalle no cuadra

El **código real (`app/`, `public/`) siempre manda**. Si encuentras una diferencia,
corrige el `.md` correspondiente en lugar de dejarla pasar: esta documentación ya se
desincronizó una vez, cuando el proyecto pasó de un mini-framework con clases y
namespaces al PHP plano actual.
