# Archivo: `app/Models/CierreCaja.php`

Modelo para el cálculo y generación del reporte de cierre de caja diario.

## Descripción

Calcula las métricas de cierre del turno (ventas por método de pago, consumo de insumos, liquidaciones de repartidores) y guarda el reporte utilizando transacciones nativas de MySQLi.

## Métodos

- `calcular($fecha)`: Realiza las sumatorias y agregaciones sin alterar la base de datos.
- `generar($fecha, $idAdmin)`: Inserta o actualiza el registro en `REPORTE_CAJA` y `DETALLE_AUDITORIA` de forma atómica.
