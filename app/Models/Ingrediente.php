<?php
/**
 * Modelo Ingrediente.
 */
class Ingrediente
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function find($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM INGREDIENTE WHERE id_ingrediente = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function conCategoria(?string $buscar = null): array
    {
        $sql = "SELECT i.*, c.nombre AS categoria, c.ambito,
                       (i.cantidad_stock * i.costo_unitario) AS valorizacion,
                       (i.cantidad_stock <= i.umbral_minimo) AS critico
                FROM INGREDIENTE i JOIN CATEGORIA c ON c.id_categoria = i.id_categoria WHERE 1=1";
        
        if ($buscar) {
            $sql .= " AND i.nombre LIKE ?";
            $stmt = $this->conn->prepare($sql . " ORDER BY critico DESC, i.nombre");
            $like = "%{$buscar}%";
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } else {
            $res = $this->conn->query($sql . " ORDER BY critico DESC, i.nombre");
            return $res->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function criticos(): array
    {
        $res = $this->conn->query("SELECT * FROM INGREDIENTE WHERE cantidad_stock <= umbral_minimo ORDER BY cantidad_stock ASC");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function kpis(): array
    {
        $res = $this->conn->query(
            "SELECT COUNT(*) AS tipos,
                    COALESCE(SUM(cantidad_stock),0) AS unidades,
                    COALESCE(SUM(cantidad_stock * costo_unitario),0) AS valorizacion,
                    SUM(CASE WHEN cantidad_stock <= umbral_minimo THEN 1 ELSE 0 END) AS criticos
             FROM INGREDIENTE"
        );
        $row = $res ? $res->fetch_assoc() : null;
        return $row ?: ['tipos' => 0, 'unidades' => 0, 'valorizacion' => 0, 'criticos' => 0];
    }

    public function moverStock(string $idIngrediente, string $tipo, float $cantidad, string $motivo, ?string $idAdmin = null): void
    {
        $ing = $this->find($idIngrediente);
        if (!$ing) return;

        $nuevo = (float) $ing['cantidad_stock'];
        if ($tipo === 'entrada')      $nuevo += $cantidad;
        elseif ($tipo === 'salida')   $nuevo = max(0, $nuevo - $cantidad);
        else                          $nuevo = $cantidad;

        $stmt = $this->conn->prepare("UPDATE INGREDIENTE SET cantidad_stock = ? WHERE id_ingrediente = ?");
        $stmt->bind_param("ds", $nuevo, $idIngrediente);
        $stmt->execute();
        $stmt->close();

        $stmtM = $this->conn->prepare(
            "INSERT INTO MOVIMIENTO_INVENTARIO (id_movimiento, id_ingrediente, id_admin, tipo_movimiento, cantidad, motivo)
             VALUES (?,?,?,?,?,?)"
        );
        $idMov = uuid();
        $stmtM->bind_param("ssssds", $idMov, $idIngrediente, $idAdmin, $tipo, $cantidad, $motivo);
        $stmtM->execute();
        $stmtM->close();
    }

    public function guardar(array $d): string
    {
        $costo  = (float) ($d['costo_unitario'] ?? 0);
        $umbral = (float) ($d['umbral_minimo'] ?? 0);
        $stock  = (float) ($d['cantidad_stock'] ?? 0);
        $extra  = (float) ($d['precio_extra'] ?? 0);
        $prov   = $d['proveedor'] ?? null;

        if (!empty($d['id_ingrediente'])) {
            $stmt = $this->conn->prepare(
                "UPDATE INGREDIENTE SET id_categoria=?, nombre=?, unidad_medida=?, costo_unitario=?, umbral_minimo=?, cantidad_stock=?, precio_extra=?, proveedor=? WHERE id_ingrediente=?"
            );
            $stmt->bind_param("sssddddss", $d['id_categoria'], $d['nombre'], $d['unidad_medida'], $costo, $umbral, $stock, $extra, $prov, $d['id_ingrediente']);
            $stmt->execute();
            $stmt->close();
            return $d['id_ingrediente'];
        } else {
            $id = uuid();
            $stmt = $this->conn->prepare(
                "INSERT INTO INGREDIENTE (id_ingrediente, id_categoria, nombre, unidad_medida, costo_unitario, umbral_minimo, cantidad_stock, precio_extra, proveedor) VALUES (?,?,?,?,?,?,?,?,?)"
            );
            $stmt->bind_param("ssssdddds", $id, $d['id_categoria'], $d['nombre'], $d['unidad_medida'], $costo, $umbral, $stock, $extra, $prov);
            $stmt->execute();
            $stmt->close();
            return $id;
        }
    }

    public function movimientos(int $limite = 50): array
    {
        $res = $this->conn->query(
            "SELECT m.*, i.nombre AS ingrediente FROM MOVIMIENTO_INVENTARIO m
             JOIN INGREDIENTE i ON i.id_ingrediente = m.id_ingrediente
             ORDER BY m.fecha_hora DESC LIMIT {$limite}"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }
}

