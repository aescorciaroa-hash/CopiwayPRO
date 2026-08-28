# 11 · Base de datos: tablas y triggers

Base de datos: **`hamburguer_copiway`** (MySQL 8, `utf8mb4`). 20 tablas.

## Idea general

- Todas las llaves primarias son **`CHAR(36)`** = un **UUID** (texto tipo
  `a1b2c3d4-...`). No son números autoincrementales.
- El UUID lo genera un **trigger** `BEFORE INSERT` si se inserta con la llave vacía (`''`).
- Los `DECIMAL(10,2)` guardan dinero (2 decimales).
- Los `ENUM(...)` limitan los valores posibles de una columna (ej. estado del pedido).

## Las 20 tablas por grupo

### Configuración y categorías
| Tabla | Para qué |
|-------|----------|
| `CONFIGURACION_SISTEMA` | **1 sola fila**: horario, tarifa de domicilio, margen, pausa de emergencia, umbral de stock, PINs de estación |
| `CATEGORIA` | categorías con `ambito`: `menu`, `insumo_alimenticio`, `empaque_desechable` |

### Cuentas (los 4 roles)
| Tabla | Notas |
|-------|-------|
| `ADMINISTRADOR` | dueño. Se crea a mano en la BD |
| `AYUDANTE_COCINA` | `activo` (soft delete), `turno`, `creado_por` → admin |
| `DOMICILIARIO` | `tipo_vehiculo`, `placa`, `base_efectivo_asignada`, `estado_disponibilidad`, `activo` |
| `CLIENTE` | `puntos_fidelidad`, `fecha_nacimiento` (para el 15%), `fecha_aceptacion_habeas_data` |
| `CODIGO_VERIFICACION` | códigos para recuperar contraseña (flujo simulado) |

Las 3 tablas de empleados/cliente guardan `contrasena` = **hash bcrypt** (60 caracteres).
`correo` y `telefono` son **UNIQUE** (no se repiten en ninguna tabla).

### Menú e inventario
| Tabla | Para qué |
|-------|----------|
| `PRODUCTO` | lo que se vende. `estado` (activo/oculto), `etiqueta_destacada` (más vendido…) |
| `INGREDIENTE` | materia prima. `cantidad_stock`, `umbral_minimo`, `costo_unitario`, `precio_extra` |
| `RECETA` | **relación N:M** producto↔ingrediente + `cantidad_necesaria`. UNIQUE(producto, ingrediente) |

### Pedidos
| Tabla | Para qué |
|-------|----------|
| `PEDIDO` | la orden. `estado` (ENUM de 6), `canal_origen` (web/whatsapp/llamada), `pin_entrega` (CHAR(4)), `subtotal`, `costo_domicilio`, `descuento_cumpleanos`, `puntos_ganados`, `total`. FK a cliente, domiciliario, ayudante, reporte |
| `DETALLE_PEDIDO` | líneas del pedido: producto, cantidad, `precio_unitario` |
| `PERSONALIZACION` | modificaciones de una línea: `accion_modificacion` = `quitar` (SIN) o `agregar` (EXTRA) |
| `PAGO` | 1 por pedido (PK = `id_pedido`). `metodo` (digital/efectivo), `estado` (pendiente/aprobado/rechazado) |
| `NOTIFICACION` | avisos generados al cambiar de estado |
| `RESENA` | calificación 1–5 + comentario. UNIQUE(id_pedido) → una reseña por pedido |

### Caja e inventario/auditoría
| Tabla | Para qué |
|-------|----------|
| `REPORTE_CAJA` | cierre del día. UNIQUE(id_admin, fecha). Totales por método |
| `MOVIMIENTO_INVENTARIO` | cada entrada/salida/ajuste de stock, con `motivo` |
| `DETALLE_AUDITORIA` | por cada ingrediente en un cierre: stock teórico vs real |
| `LIQUIDACION_DOMICILIARIO` | cuánto efectivo debe entregar cada repartidor: `base + recaudo` |

## Relaciones clave (para dibujar el diagrama entidad-relación)

```
ADMINISTRADOR ──< AYUDANTE_COCINA        (creado_por)
ADMINISTRADOR ──< DOMICILIARIO           (creado_por)
ADMINISTRADOR ──< REPORTE_CAJA

CLIENTE ──< PEDIDO
DOMICILIARIO ──< PEDIDO   (opcional)
AYUDANTE_COCINA ──< PEDIDO (opcional)
REPORTE_CAJA ──< PEDIDO   (opcional, al cerrar caja)

PEDIDO ──1:1── PAGO
PEDIDO ──< DETALLE_PEDIDO ──< PERSONALIZACION
PEDIDO ──< NOTIFICACION
PEDIDO ──1:1── RESENA

CATEGORIA ──< PRODUCTO
CATEGORIA ──< INGREDIENTE
PRODUCTO ──< RECETA >── INGREDIENTE       (N:M)
INGREDIENTE ──< MOVIMIENTO_INVENTARIO
PERSONALIZACION >── INGREDIENTE
```

`──<` significa "uno a muchos". `>──<` es "muchos a muchos".

---

## Triggers (disparadores) — lo que MySQL hace SOLO

Un **trigger** es código SQL que se ejecuta automáticamente cuando pasa algo en una
tabla (INSERT / UPDATE / DELETE).

### 1. Triggers de ID (uno por tabla) — `trg_id_*`
```sql
CREATE TRIGGER trg_id_cliente BEFORE INSERT ON CLIENTE FOR EACH ROW
BEGIN IF NEW.id_cliente = '' THEN SET NEW.id_cliente = UUID(); END IF; END
```
**Traducción:** justo antes de insertar, si la llave viene vacía, genera un UUID.
Así el PHP puede insertar sin preocuparse por el ID (aunque `Model::insert()` también
genera uno con `uuid()` — cualquiera de los dos sirve).

### 2. `trg_pedido_pin` — ID + PIN del pedido
Antes de insertar un `PEDIDO`: genera el `id_pedido` si falta **y** genera el
`pin_entrega` (4 dígitos aleatorios) si viene vacío.

### 3. `trg_pago_aprobado` — EL TRIGGER IMPORTANTE ⭐

Se dispara `BEFORE UPDATE ON PAGO`, **solo cuando** `estado` pasa a `'aprobado'`
(y antes no lo era). Hace 4 cosas de una:

```sql
IF NEW.estado = 'aprobado' AND OLD.estado <> 'aprobado' THEN
```

**a) Descuenta los ingredientes de la receta** (menos los que el cliente pidió SIN):
```sql
INSERT INTO MOVIMIENTO_INVENTARIO (id_ingrediente, tipo_movimiento, cantidad, motivo)
SELECT r.id_ingrediente, 'salida', r.cantidad_necesaria * dp.cantidad, CONCAT('Venta pedido ', NEW.id_pedido)
FROM DETALLE_PEDIDO dp
JOIN RECETA r ON r.id_producto = dp.id_producto
WHERE dp.id_pedido = NEW.id_pedido
  AND NOT EXISTS ( SELECT 1 FROM PERSONALIZACION p
                   WHERE p.id_detalle = dp.id_detalle
                     AND p.id_ingrediente = r.id_ingrediente
                     AND p.accion_modificacion = 'quitar' );
```

**b) Descuenta los EXTRAS** que el cliente agregó:
```sql
INSERT INTO MOVIMIENTO_INVENTARIO (...)
SELECT p.id_ingrediente, 'salida', dp.cantidad, CONCAT('Extra pedido ', NEW.id_pedido)
FROM PERSONALIZACION p JOIN DETALLE_PEDIDO dp ON dp.id_detalle = p.id_detalle
WHERE dp.id_pedido = NEW.id_pedido AND p.accion_modificacion = 'agregar';
```

**c) Baja el `cantidad_stock` de cada `INGREDIENTE`** según esos movimientos
(nunca por debajo de 0, con `GREATEST(... , 0)`).

**d) Suma puntos de fidelidad** al cliente y guarda `puntos_ganados` en el pedido:
```sql
SET c.puntos_fidelidad = c.puntos_fidelidad + FLOOR(ped.total / 1000);   -- 1 punto por cada $1.000
```

Y pone `NEW.fecha_pago = NOW()`.

> **Por eso** en PHP basta con hacer
> `UPDATE PAGO SET estado='aprobado' WHERE id_pedido=?` y todo el inventario y los
> puntos se actualizan solos. La lógica de inventario vive en la base de datos, no en PHP.

## `database/install.php`

Script en PHP que:
1. Se conecta a MySQL (sin elegir base de datos).
2. Lee `schema.sql`, lo parte por el delimitador y ejecuta cada sentencia (maneja el
   `DELIMITER $$` de los triggers).
3. Lee `seed.sql` y lo ejecuta.
4. Imprime las credenciales de ejemplo.
