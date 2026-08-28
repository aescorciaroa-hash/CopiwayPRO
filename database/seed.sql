-- ======================================================
-- Datos de ejemplo para CopiwayPRO
-- Se ejecuta despues de schema.sql
-- ======================================================
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM PERSONALIZACION;
DELETE FROM DETALLE_PEDIDO;
DELETE FROM PAGO;
DELETE FROM NOTIFICACION;
DELETE FROM RESENA;
DELETE FROM PEDIDO;
DELETE FROM DETALLE_AUDITORIA;
DELETE FROM LIQUIDACION_DOMICILIARIO;
DELETE FROM REPORTE_CAJA;
DELETE FROM MOVIMIENTO_INVENTARIO;
DELETE FROM RECETA;
DELETE FROM PRODUCTO;
DELETE FROM INGREDIENTE;
DELETE FROM CATEGORIA;
DELETE FROM CODIGO_VERIFICACION;
DELETE FROM CLIENTE;
DELETE FROM AYUDANTE_COCINA;
DELETE FROM DOMICILIARIO;
DELETE FROM ADMINISTRADOR;
DELETE FROM CONFIGURACION_SISTEMA;
SET FOREIGN_KEY_CHECKS = 1;

-- ---- Configuracion del sistema (singleton) ----
INSERT INTO CONFIGURACION_SISTEMA
  (id_config, horario_apertura, horario_cierre, tarifa_plana_domicilio, margen_ganancia_defecto,
   pausa_emergencia_activa, umbral_stock_critico_default, pin_estacion_kds, pin_estacion_domiciliario)
VALUES
  ('c0f10000-0000-4000-8000-000000000001', '08:00:00', '23:00:00', 6000, 50, FALSE, 10, '1234', '5678');

-- ---- Administrador (creado por el "Programador" directo en BD) ----
INSERT INTO ADMINISTRADOR (id_admin, nombre, correo, telefono, contrasena, nivel_acceso) VALUES
  ('ad11a000-0000-4000-8000-000000000001', 'Andres Escorcia', 'admin@copiway.com', '3000000000',
   '$2y$10$hNCgtNPKF1bEurJR0f1Z9.VCgJ8d550sSBJnUY.AGacAWFqL0qVNa', 'maestro');

-- ---- Empleados (creados por el Administrador) ----
INSERT INTO AYUDANTE_COCINA (id_ayudante, nombre, correo, telefono, contrasena, turno, activo, creado_por) VALUES
  ('c0c1a000-0000-4000-8000-000000000001', 'Majo Rivera', 'cocina@copiway.com', '3011111111',
   '$2y$10$T8g7.rcaBmPXaNLt7342qef9torgQ0xLrM2qcZtKwLr83qiD6OLWG', 'mixto', TRUE,
   'ad11a000-0000-4000-8000-000000000001');

INSERT INTO DOMICILIARIO
  (id_domiciliario, nombre, correo, telefono, contrasena, tipo_vehiculo, placa, base_efectivo_asignada,
   estado_disponibilidad, activo, ubicacion_lat, ubicacion_lng, creado_por)
VALUES
  ('d011a000-0000-4000-8000-000000000001', 'Camilo Torres', 'domiciliario@copiway.com', '3022222222',
   '$2y$10$FPJuPDyFkWVI3V51zRTwEOFYw7XC6oj2DHILSKGWK6fK7kat9V3Re', 'moto', 'UVH-02F', 150000,
   'disponible', TRUE, 2.9273, -75.2819, 'ad11a000-0000-4000-8000-000000000001');

-- ---- Clientes ----
INSERT INTO CLIENTE
  (id_cliente, nombre, telefono, correo, contrasena, direccion, fecha_nacimiento, puntos_fidelidad, fecha_aceptacion_habeas_data)
VALUES
  ('c11e0000-0000-4000-8000-000000000001', 'Juan Perez', '3101111111', 'cliente@copiway.com',
   '$2y$10$gdLATbd1wkmTp6MA1WqVe.usH6kvEGWF10Nx7F9wpWmnpXSTPLdA.', 'Calle 10 # 5-20, Centro',
   '2000-08-28', 1200, NOW()),
  ('c11e0000-0000-4000-8000-000000000002', 'Andres Roa', '3102222222', 'andres@copiway.com',
   '$2y$10$gdLATbd1wkmTp6MA1WqVe.usH6kvEGWF10Nx7F9wpWmnpXSTPLdA.', 'Carrera 7 # 12-40', '1998-03-15', 350, NOW()),
  ('c11e0000-0000-4000-8000-000000000003', 'Maria Gomez', '3103333333', 'maria@copiway.com',
   '$2y$10$gdLATbd1wkmTp6MA1WqVe.usH6kvEGWF10Nx7F9wpWmnpXSTPLdA.', 'Av. Circunvalar # 30-10', '1995-11-02', 80, NOW());

-- ---- Categorias ----
-- Menu
INSERT INTO CATEGORIA (id_categoria, nombre, ambito) VALUES
  ('ca7e0000-0000-4000-8000-000000000001', 'Hamburguesas de Pan', 'menu'),
  ('ca7e0000-0000-4000-8000-000000000002', 'Hamburguesas de Patacon', 'menu'),
  ('ca7e0000-0000-4000-8000-000000000003', 'Perros Calientes', 'menu'),
  ('ca7e0000-0000-4000-8000-000000000004', 'Mazorcadas', 'menu'),
  ('ca7e0000-0000-4000-8000-000000000005', 'Bebidas', 'menu'),
  ('ca7e0000-0000-4000-8000-000000000006', 'Acompanamientos', 'menu'),
-- Insumos alimenticios
  ('ca7e0000-0000-4000-8000-000000000011', 'Panaderia & Panes', 'insumo_alimenticio'),
  ('ca7e0000-0000-4000-8000-000000000012', 'Carnes & Proteinas', 'insumo_alimenticio'),
  ('ca7e0000-0000-4000-8000-000000000013', 'Quesos & Lacteos', 'insumo_alimenticio'),
  ('ca7e0000-0000-4000-8000-000000000014', 'Verduras & Frescos', 'insumo_alimenticio'),
  ('ca7e0000-0000-4000-8000-000000000015', 'Salsas & Aderezos', 'insumo_alimenticio'),
  ('ca7e0000-0000-4000-8000-000000000016', 'Bebidas Insumo', 'insumo_alimenticio'),
-- Empaques
  ('ca7e0000-0000-4000-8000-000000000021', 'Empaques & Desechables', 'empaque_desechable');

-- ---- Ingredientes / insumos ----
INSERT INTO INGREDIENTE
  (id_ingrediente, id_categoria, nombre, unidad_medida, cantidad_stock, umbral_minimo, costo_unitario, precio_extra, proveedor)
VALUES
  ('a11e0000-0000-4000-8000-000000000001', 'ca7e0000-0000-4000-8000-000000000011', 'Pan de Hamburguesa', 'Unidades', 60, 15, 1500, 0, 'Panaderia La 15'),
  ('a11e0000-0000-4000-8000-000000000002', 'ca7e0000-0000-4000-8000-000000000012', 'Carne 120g', 'Unidades', 45, 12, 3200, 6000, 'Carnes del Huila'),
  ('a11e0000-0000-4000-8000-000000000003', 'ca7e0000-0000-4000-8000-000000000012', 'Carne 150g', 'Unidades', 30, 10, 3900, 6500, 'Carnes del Huila'),
  ('a11e0000-0000-4000-8000-000000000004', 'ca7e0000-0000-4000-8000-000000000013', 'Queso Cheddar', 'Unidades', 0, 10, 900, 3000, 'Lacteos Neiva'),
  ('a11e0000-0000-4000-8000-000000000005', 'ca7e0000-0000-4000-8000-000000000012', 'Tocineta', 'Gramos', 800, 200, 25, 6000, 'Carnes del Huila'),
  ('a11e0000-0000-4000-8000-000000000006', 'ca7e0000-0000-4000-8000-000000000014', 'Lechuga', 'Gramos', 1200, 300, 8, 0, 'Plaza de Mercado'),
  ('a11e0000-0000-4000-8000-000000000007', 'ca7e0000-0000-4000-8000-000000000014', 'Tomate', 'Gramos', 1500, 300, 6, 0, 'Plaza de Mercado'),
  ('a11e0000-0000-4000-8000-000000000008', 'ca7e0000-0000-4000-8000-000000000014', 'Cebolla', 'Gramos', 900, 250, 5, 0, 'Plaza de Mercado'),
  ('a11e0000-0000-4000-8000-000000000009', 'ca7e0000-0000-4000-8000-000000000015', 'Salsa de la Casa', 'Mililitros', 3000, 500, 3, 1500, 'Cocina Copiway'),
  ('a11e0000-0000-4000-8000-000000000010', 'ca7e0000-0000-4000-8000-000000000012', 'Salchicha', 'Unidades', 50, 15, 1800, 3500, 'Carnes del Huila'),
  ('a11e0000-0000-4000-8000-000000000011', 'ca7e0000-0000-4000-8000-000000000014', 'Papa a la Francesa', 'Gramos', 5000, 1000, 4, 0, 'Congelados SA'),
  ('a11e0000-0000-4000-8000-000000000012', 'ca7e0000-0000-4000-8000-000000000014', 'Mazorca', 'Unidades', 40, 10, 1200, 0, 'Plaza de Mercado'),
  ('a11e0000-0000-4000-8000-000000000013', 'ca7e0000-0000-4000-8000-000000000016', 'Gaseosa 400ml', 'Unidades', 80, 20, 1800, 0, 'Distribuidora Coca-Cola'),
  ('e11a0000-0000-4000-8000-000000000001', 'ca7e0000-0000-4000-8000-000000000021', 'Bolsa Kraft', 'Unidades', 300, 50, 350, 0, 'Empaques del Sur'),
  ('e11a0000-0000-4000-8000-000000000002', 'ca7e0000-0000-4000-8000-000000000021', 'Caja Combo', 'Unidades', 150, 40, 800, 0, 'Empaques del Sur');

-- ---- Productos ----
INSERT INTO PRODUCTO (id_producto, id_categoria, nombre, descripcion, precio, imagen, estado, etiqueta_destacada) VALUES
  ('ab0d0000-0000-4000-8000-000000000001', 'ca7e0000-0000-4000-8000-000000000001', 'Hamburguesa Clasica Copiway', 'Pan, carne 120g, queso, lechuga, tomate y salsa de la casa.', 15000, 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=640&q=70', 'activo', 'mas_vendido'),
  ('ab0d0000-0000-4000-8000-000000000002', 'ca7e0000-0000-4000-8000-000000000001', 'Hamburguesa Tocineta', 'Doble carne, tocineta crocante, queso cheddar y salsa ahumada.', 17000, 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=640&q=70', 'activo', 'recomendado'),
  ('ab0d0000-0000-4000-8000-000000000003', 'ca7e0000-0000-4000-8000-000000000001', 'Hamburguesa Doble Carne', 'Dos carnes de 150g, doble queso, cebolla caramelizada.', 21000, 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=640&q=70', 'activo', 'especialidad'),
  ('ab0d0000-0000-4000-8000-000000000004', 'ca7e0000-0000-4000-8000-000000000002', 'Hamburguesa de Patacon', 'Base de patacon, carne 150g, queso y hogao.', 16000, 'https://images.unsplash.com/photo-1553979459-d2229ba7433a?w=640&q=70', 'activo', 'ninguna'),
  ('ab0d0000-0000-4000-8000-000000000005', 'ca7e0000-0000-4000-8000-000000000003', 'Perro Caliente Sencillo', 'Pan, salchicha, queso, papitas y salsas.', 10000, 'https://images.unsplash.com/photo-1612392166886-ee8475b03af2?w=640&q=70', 'activo', 'ninguna'),
  ('ab0d0000-0000-4000-8000-000000000006', 'ca7e0000-0000-4000-8000-000000000003', 'Perro Caliente Suizo', 'Pan, salchicha, queso suizo y cebolla caramelizada.', 13000, 'https://images.unsplash.com/photo-1619740455993-9e612b1af08a?w=640&q=70', 'activo', 'nuevo'),
  ('ab0d0000-0000-4000-8000-000000000007', 'ca7e0000-0000-4000-8000-000000000004', 'Mazorcada Personal', 'Mazorca desgranada, carne, pollo, queso y salsas.', 14000, 'https://images.unsplash.com/photo-1606850246029-dd00bd5f3a5f?w=640&q=70', 'activo', 'ninguna'),
  ('ab0d0000-0000-4000-8000-000000000008', 'ca7e0000-0000-4000-8000-000000000005', 'Gaseosa 400ml', 'Bebida gaseosa personal 400ml.', 4000, 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=640&q=70', 'activo', 'ninguna'),
  ('ab0d0000-0000-4000-8000-000000000009', 'ca7e0000-0000-4000-8000-000000000006', 'Papas Fritas', 'Porcion de papas a la francesa crocantes.', 6000, 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=640&q=70', 'activo', 'ninguna');

-- ---- Recetas (producto -> ingrediente + empaque) ----
INSERT INTO RECETA (id_receta, id_producto, id_ingrediente, cantidad_necesaria) VALUES
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000002', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000004', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000006', 20),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000007', 25),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'a11e0000-0000-4000-8000-000000000009', 30),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000001', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000002', 'a11e0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000002', 'a11e0000-0000-4000-8000-000000000002', 2),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000002', 'a11e0000-0000-4000-8000-000000000005', 40),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000002', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000003', 'a11e0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000003', 'a11e0000-0000-4000-8000-000000000003', 2),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000003', 'a11e0000-0000-4000-8000-000000000008', 30),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000003', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000004', 'a11e0000-0000-4000-8000-000000000003', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000004', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000005', 'a11e0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000005', 'a11e0000-0000-4000-8000-000000000010', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000005', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000006', 'a11e0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000006', 'a11e0000-0000-4000-8000-000000000010', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000006', 'e11a0000-0000-4000-8000-000000000001', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000007', 'a11e0000-0000-4000-8000-000000000012', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000007', 'e11a0000-0000-4000-8000-000000000002', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000008', 'a11e0000-0000-4000-8000-000000000013', 1),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000009', 'a11e0000-0000-4000-8000-000000000011', 200),
  (UUID(), 'ab0d0000-0000-4000-8000-000000000009', 'e11a0000-0000-4000-8000-000000000001', 1);

-- ---- Pedidos de ejemplo (para el tablero del administrador y la cocina) ----
INSERT INTO PEDIDO
  (id_pedido, id_cliente, id_domiciliario, id_ayudante, direccion_entrega, fecha_hora, estado, canal_origen,
   pin_entrega, tirilla_impresa, subtotal, costo_domicilio, descuento_cumpleanos, puntos_ganados, total)
VALUES
  ('0dee0000-0000-4000-8000-000000000001', 'c11e0000-0000-4000-8000-000000000001', NULL, NULL,
   'Calle 10 # 5-20, Centro', NOW() - INTERVAL 8 MINUTE, 'pendiente', 'web', '4821', FALSE, 32000, 6000, 0, 38, 38000),
  ('0dee0000-0000-4000-8000-000000000002', 'c11e0000-0000-4000-8000-000000000002', NULL, 'c0c1a000-0000-4000-8000-000000000001',
   'Carrera 7 # 12-40', NOW() - INTERVAL 18 MINUTE, 'en_preparacion', 'web', '1937', TRUE, 21000, 6000, 0, 27, 27000),
  ('0dee0000-0000-4000-8000-000000000003', 'c11e0000-0000-4000-8000-000000000003', NULL, 'c0c1a000-0000-4000-8000-000000000001',
   'Av. Circunvalar # 30-10', NOW() - INTERVAL 25 MINUTE, 'listo', 'whatsapp', '7702', TRUE, 10000, 6000, 0, 16, 16000),
  ('0dee0000-0000-4000-8000-000000000004', 'c11e0000-0000-4000-8000-000000000001', 'd011a000-0000-4000-8000-000000000001', 'c0c1a000-0000-4000-8000-000000000001',
   'Calle 10 # 5-20, Centro', NOW() - INTERVAL 40 MINUTE, 'en_camino', 'web', '5533', TRUE, 39000, 6000, 0, 45, 45000),
  ('0dee0000-0000-4000-8000-000000000005', 'c11e0000-0000-4000-8000-000000000002', 'd011a000-0000-4000-8000-000000000001', 'c0c1a000-0000-4000-8000-000000000001',
   'Carrera 7 # 12-40', NOW() - INTERVAL 3 HOUR, 'entregado', 'web', '2213', TRUE, 30000, 6000, 0, 36, 36000),
  ('0dee0000-0000-4000-8000-000000000006', 'c11e0000-0000-4000-8000-000000000003', 'd011a000-0000-4000-8000-000000000001', 'c0c1a000-0000-4000-8000-000000000001',
   'Av. Circunvalar # 30-10', NOW() - INTERVAL 5 HOUR, 'entregado', 'llamada', '9080', TRUE, 14000, 6000, 0, 20, 20000);

INSERT INTO DETALLE_PEDIDO (id_detalle, id_pedido, id_producto, cantidad, precio_unitario) VALUES
  ('de7a0000-0000-4000-8000-000000000001', '0dee0000-0000-4000-8000-000000000001', 'ab0d0000-0000-4000-8000-000000000001', 1, 15000),
  ('de7a0000-0000-4000-8000-000000000002', '0dee0000-0000-4000-8000-000000000001', 'ab0d0000-0000-4000-8000-000000000002', 1, 17000),
  ('de7a0000-0000-4000-8000-000000000003', '0dee0000-0000-4000-8000-000000000002', 'ab0d0000-0000-4000-8000-000000000003', 1, 21000),
  ('de7a0000-0000-4000-8000-000000000004', '0dee0000-0000-4000-8000-000000000003', 'ab0d0000-0000-4000-8000-000000000005', 1, 10000),
  ('de7a0000-0000-4000-8000-000000000005', '0dee0000-0000-4000-8000-000000000004', 'ab0d0000-0000-4000-8000-000000000003', 1, 21000),
  ('de7a0000-0000-4000-8000-000000000006', '0dee0000-0000-4000-8000-000000000004', 'ab0d0000-0000-4000-8000-000000000006', 1, 13000),
  ('de7a0000-0000-4000-8000-000000000007', '0dee0000-0000-4000-8000-000000000004', 'ab0d0000-0000-4000-8000-000000000009', 1, 6000),
  ('de7a0000-0000-4000-8000-000000000008', '0dee0000-0000-4000-8000-000000000005', 'ab0d0000-0000-4000-8000-000000000001', 2, 15000),
  ('de7a0000-0000-4000-8000-000000000009', '0dee0000-0000-4000-8000-000000000006', 'ab0d0000-0000-4000-8000-000000000007', 1, 14000);

-- Personalizacion de ejemplo: SIN cebolla y EXTRA tocineta
INSERT INTO PERSONALIZACION (id_personalizacion, id_detalle, id_ingrediente, accion_modificacion, costo_aplicado) VALUES
  (UUID(), 'de7a0000-0000-4000-8000-000000000003', 'a11e0000-0000-4000-8000-000000000008', 'quitar', 0),
  (UUID(), 'de7a0000-0000-4000-8000-000000000003', 'a11e0000-0000-4000-8000-000000000005', 'agregar', 6000);

-- Pagos (insertados ya aprobados / pendientes; no dispara el trigger de UPDATE)
INSERT INTO PAGO (id_pedido, metodo, comprobante, estado, fecha_pago) VALUES
  ('0dee0000-0000-4000-8000-000000000001', 'digital', 'NEQUI-8842', 'aprobado', NOW() - INTERVAL 8 MINUTE),
  ('0dee0000-0000-4000-8000-000000000002', 'digital', 'NEQUI-1290', 'aprobado', NOW() - INTERVAL 18 MINUTE),
  ('0dee0000-0000-4000-8000-000000000003', 'efectivo', NULL, 'pendiente', NULL),
  ('0dee0000-0000-4000-8000-000000000004', 'efectivo', NULL, 'aprobado', NOW() - INTERVAL 40 MINUTE),
  ('0dee0000-0000-4000-8000-000000000005', 'digital', 'BANCOLOMBIA-5521', 'aprobado', NOW() - INTERVAL 3 HOUR),
  ('0dee0000-0000-4000-8000-000000000006', 'efectivo', NULL, 'aprobado', NOW() - INTERVAL 5 HOUR);

-- Resena de ejemplo
INSERT INTO RESENA (id_resena, id_pedido, puntaje, comentario, fecha) VALUES
  (UUID(), '0dee0000-0000-4000-8000-000000000005', 5, 'Excelente, llego caliente y rapido.', NOW() - INTERVAL 2 HOUR);
