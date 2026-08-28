# `database/seed.sql`

## Ubicación
`database/seed.sql`

## Propósito
Carga **datos de ejemplo** para poder probar el sistema: configuración, usuarios,
categorías, productos, ingredientes, recetas y algunos pedidos. Se ejecuta **después**
de `schema.sql`.

## Estructura del archivo

1. **Limpieza**: `SET FOREIGN_KEY_CHECKS = 0;` + `DELETE FROM` de todas las tablas +
   `SET FOREIGN_KEY_CHECKS = 1;`. Así el seed se puede volver a correr sin errores de
   llaves foráneas.
2. **`CONFIGURACION_SISTEMA`** (fila única): horario `08:00`–`23:00`, tarifa `6000`,
   margen `50%`, PIN KDS `1234`, PIN domiciliario `5678`.
3. **`ADMINISTRADOR`**: `admin@copiway.com` (hash bcrypt de `admin123`).
4. **`AYUDANTE_COCINA`**: `cocina@copiway.com` (`cocina123`).
5. **`DOMICILIARIO`**: `domiciliario@copiway.com` (`domi123`), moto `UVH-02F`, base
   `150000`.
6. **`CLIENTE`**: `cliente@copiway.com` (`cliente123`) y otros. El primero tiene
   `fecha_nacimiento` = **hoy** para que se vea el descuento del 15%.
7. **`CATEGORIA`**: 6 de menú (Hamburguesas de Pan, de Patacón, Perros, Mazorcadas,
   Bebidas, Acompañamientos) + varias de insumos y de empaques.
8. **`INGREDIENTE`**: panes, carnes, quesos, verduras, salsas, bebidas, empaques…
   con su `cantidad_stock`, `umbral_minimo`, `costo_unitario` y `precio_extra`.
9. **`PRODUCTO`** + **`RECETA`**: cada hamburguesa con su lista de ingredientes.
10. Algunos **`PEDIDO`** de ejemplo en distintos estados, con su `PAGO`, `DETALLE_PEDIDO`
    y `PERSONALIZACION`.

## IDs "legibles"

Los UUID del seed están escritos a mano con un patrón (`ad11a000-...` para admin,
`c11e0000-...` para cliente, `ca7e0000-...` para categoría, etc.) para que sean fáciles
de reconocer al depurar. Como todos terminan en `...0001`, `...0002`… el código corto
de pedido (`Pedido::codigo`) usa los **últimos** 4 caracteres.

## Notas
- ⚠️ Al correr `install.php` otra vez, este `DELETE FROM` **borra todos los datos
  reales** y los reemplaza por el seed. Úsalo solo para empezar de cero.
- Las contraseñas están hasheadas con bcrypt; no se pueden "leer".
- Si el usuario cambia su correo/clave desde la app o por SQL, el seed ya no refleja el
  estado real de la BD.
