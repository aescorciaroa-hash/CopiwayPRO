# `database/schema.sql`

## Ubicación
`database/schema.sql`

## Propósito
Crea **desde cero** la base de datos: la borra si existe, la recrea, crea las **20
tablas** y todos los **triggers**. Lo ejecuta `database/install.php`.

## Estructura del archivo (por pasos, según los comentarios)

| Paso | Qué crea |
|------|----------|
| 1 | `DROP DATABASE IF EXISTS` + `CREATE DATABASE hamburguer_copiway CHARACTER SET utf8mb4` + `USE` |
| 2 | `CONFIGURACION_SISTEMA` (fila única), `CATEGORIA` |
| 3 | `ADMINISTRADOR`, `AYUDANTE_COCINA`, `DOMICILIARIO`, `CLIENTE`, `CODIGO_VERIFICACION` |
| 4 | `PRODUCTO`, `INGREDIENTE`, `RECETA` |
| 5 | `REPORTE_CAJA` |
| 6 | `PEDIDO`, `DETALLE_PEDIDO`, `PERSONALIZACION`, `PAGO`, `NOTIFICACION`, `RESENA` |
| 7 | `MOVIMIENTO_INVENTARIO`, `DETALLE_AUDITORIA`, `LIQUIDACION_DOMICILIARIO` |
| 8 | Los triggers |

## Detalles importantes de las tablas

- Todas las **llaves primarias** son `CHAR(36) NOT NULL DEFAULT ''` (un UUID como texto).
- Los `ENUM(...)` limitan valores: por ejemplo
  `PEDIDO.estado ENUM('pendiente','en_preparacion','listo','en_camino','entregado','cancelado')`.
- `correo` y `telefono` son `UNIQUE` en las tablas de cuentas.
- `PAGO` tiene como PK `id_pedido` (relación **1:1** con `PEDIDO`).
- `RESENA.id_pedido` es `UNIQUE` (una reseña por pedido).
- `RECETA` tiene `UNIQUE(id_producto, id_ingrediente)` (relación **N:M**).
- Muchas FK con `ON DELETE CASCADE` (al borrar un pedido se borran sus detalles, etc.).

## Los triggers (Paso 8)

### `trg_id_*` (uno por tabla)
```sql
CREATE TRIGGER trg_id_cliente BEFORE INSERT ON CLIENTE FOR EACH ROW
BEGIN IF NEW.id_cliente = '' THEN SET NEW.id_cliente = UUID(); END IF; END
```
Genera el UUID de la llave si se inserta vacía.

### `trg_pedido_pin`
`BEFORE INSERT ON PEDIDO`: genera el `id_pedido` **y** el `pin_entrega`
(`LPAD(FLOOR(RAND()*10000), 4, '0')`) si vienen vacíos.

### `trg_pago_aprobado` ⭐ (el importante)
`BEFORE UPDATE ON PAGO`, solo cuando `NEW.estado = 'aprobado' AND OLD.estado <> 'aprobado'`:
1. `INSERT MOVIMIENTO_INVENTARIO` (`salida`) por cada ingrediente de la receta de cada
   línea, **excepto** los que el cliente pidió `quitar` (`NOT EXISTS ... PERSONALIZACION`).
2. `INSERT MOVIMIENTO_INVENTARIO` (`salida`) por cada EXTRA (`accion_modificacion = 'agregar'`).
3. `UPDATE INGREDIENTE` restando esos movimientos de `cantidad_stock` (mínimo 0).
4. `UPDATE CLIENTE SET puntos_fidelidad += FLOOR(total/1000)` y
   `UPDATE PEDIDO SET puntos_ganados = FLOOR(total/1000)`.
5. `SET NEW.fecha_pago = NOW()`.

## Notas
- El bloque de triggers usa `DELIMITER $$` porque contienen `;` internos.
  `install.php` sabe manejar esto.
- Por esto en PHP basta con `UPDATE PAGO SET estado = 'aprobado'` y el inventario y los
  puntos se actualizan solos.
