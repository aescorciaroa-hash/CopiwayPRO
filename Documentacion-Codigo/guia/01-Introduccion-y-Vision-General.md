# 01 · Introducción y visión general

## ¿Qué es CopiwayPRO?

Es un sistema web para administrar una **Dark Kitchen** (también llamada "cocina
fantasma" o "cocina oculta"): un restaurante que **solo vende a domicilio**, no tiene
mesas ni clientes en el local.

El sistema cubre **todo el proceso**:

1. El **cliente** entra a la web, arma su pedido y paga.
2. La **cocina** ve el pedido en una pantalla y lo prepara.
3. El **domiciliario** recoge el pedido y lo lleva a la casa del cliente.
4. El **administrador** controla el menú, el inventario, el personal y el dinero.

## Los 4 roles (tipos de usuario)

| Rol | Cómo se crea | Qué puede hacer | Panel |
|-----|--------------|-----------------|-------|
| **Administrador** | Se crea directo en la base de datos (es el dueño) | Todo: menú, inventario, personal, reportes, ajustes | `/admin` |
| **Cliente** | Se registra solo desde la web | Ver menú, pedir, pagar, seguir su pedido, historial | `/client` |
| **Cocina** (Ayudante) | Lo crea el administrador | Ver pedidos, marcarlos "en preparación" y "listos", imprimir tirilla | `/kitchen` |
| **Domiciliario** | Lo crea el administrador | Tomar pedidos listos, iniciar ruta, entregar validando un PIN | `/delivery` |

> **Cocina** y **Domiciliario** además tienen un segundo login: un **PIN de estación**
> (un número corto que se comparte en la tablet de la cocina o del repartidor). Primero
> entran con su correo y contraseña, y luego escriben el PIN.

## Tecnologías usadas

| Capa | Tecnología | Para qué |
|------|-----------|----------|
| Lenguaje servidor | **PHP 8.1** | Toda la lógica |
| Base de datos | **MySQL 8** (vía Laragon) | Guardar la información |
| Acceso a datos | **MySQLi** (extensión de PHP) | Conectarse a MySQL con consultas preparadas |
| Estilos | **Tailwind CSS** (por CDN) | Diseño visual con clases utilitarias |
| Interactividad | **Alpine.js** (por CDN) | Modales, menús, cosas que cambian sin recargar |
| Mapas | **Leaflet + OpenStreetMap** | Ver rutas de domiciliarios |
| Iconos | **Lucide** | Iconos SVG |

## Decisiones de diseño importantes

- **Sin framework y sin POO compleja**: PHP plano. `app/Core` no es un mini-framework,
  son cuatro archivos de utilidades (`Session`, `Auth`, `Periodo`, `helpers`).
  Más fácil de explicar en un examen que Laravel.
- **Sin namespaces ni autoload**: todo se carga con `require_once` explícito.
- **MVC simplificado**: **Modelo** (clases con consultas), **Vista** (HTML) y
  **Controlador** (scripts planos que procesan `$_POST` / `$_GET`).
- **Front Controller**: una sola puerta de entrada (`public/index.php`), que además
  lleva el enrutado y el control de acceso por rol.
- **MySQLi + consultas preparadas**: nunca se pega texto del usuario dentro del SQL,
  se usan `?` como marcadores y `bind_param`. Esto evita **inyección SQL**.
- **Triggers en la base de datos**: algunas reglas (generar IDs, descontar inventario)
  las hace MySQL solo, no PHP.

## El dominio del negocio (vocabulario)

- **Comanda / Pedido**: una orden de comida.
- **Escandallo / Receta**: la lista de ingredientes que lleva un producto y en qué
  cantidad. Sirve para calcular el costo y para descontar inventario.
- **Insumo / Ingrediente**: materia prima (pan, carne, queso, servilletas…).
- **Tarifa plana de domicilio**: todos pagan lo mismo de envío, sin importar la distancia.
- **Cero crédito**: el pedido no entra a cocina hasta que el pago está aprobado.
- **Punto de no retorno**: una vez confirmas el pago, ya no puedes cancelar.
- **Fidelización**: por cada $1.000 de compra ganas 1 punto.
- **Cierre de caja**: el reporte del día con las ventas, el consumo de inventario y
  lo que cada domiciliario debe entregar en efectivo.
