<?php
/**
 * Modelo Producto.
 */
class Producto
{
    private $conn;

    private const AGOTADO_EXPR =
        "(SELECT COUNT(*) FROM RECETA r JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
          WHERE r.id_producto = p.id_producto AND i.cantidad_stock < r.cantidad_necesaria) > 0 AS agotado";

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function find($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM PRODUCTO WHERE id_producto = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function catalogo(bool $soloActivos = true): array
    {
        $where = $soloActivos ? "WHERE p.estado = 'activo'" : '';
        $res = $this->conn->query(
            "SELECT p.*, c.nombre AS categoria, " . self::AGOTADO_EXPR . "
             FROM PRODUCTO p JOIN CATEGORIA c ON c.id_categoria = p.id_categoria
             {$where} ORDER BY p.nombre"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function paraAdmin(?string $categoria = null, ?string $buscar = null): array
    {
        $sql = "SELECT p.*, c.nombre AS categoria, " . self::AGOTADO_EXPR . ",
                (SELECT COUNT(*) FROM RECETA r WHERE r.id_producto = p.id_producto) AS insumos
                FROM PRODUCTO p JOIN CATEGORIA c ON c.id_categoria = p.id_categoria WHERE 1=1";
        
        if ($categoria && $buscar) {
            $sql .= " AND p.id_categoria = ? AND p.nombre LIKE ? ORDER BY p.nombre";
            $stmt = $this->conn->prepare($sql);
            $like = "%{$buscar}%";
            $stmt->bind_param("ss", $categoria, $like);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } elseif ($categoria) {
            $sql .= " AND p.id_categoria = ? ORDER BY p.nombre";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $categoria);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } elseif ($buscar) {
            $sql .= " AND p.nombre LIKE ? ORDER BY p.nombre";
            $stmt = $this->conn->prepare($sql);
            $like = "%{$buscar}%";
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } else {
            $res = $this->conn->query($sql . " ORDER BY p.nombre");
            return $res->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function conCategoria(string $id): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.nombre AS categoria FROM PRODUCTO p
             JOIN CATEGORIA c ON c.id_categoria = p.id_categoria WHERE p.id_producto = ?"
        );
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function estaAgotado(string $id): bool
    {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) as cant FROM RECETA r JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
             WHERE r.id_producto = ? AND i.cantidad_stock < r.cantidad_necesaria"
        );
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return ((int)($res['cant'] ?? 0)) > 0;
    }

    public function receta(string $id): array
    {
        $stmt = $this->conn->prepare(
            "SELECT r.*, i.nombre, i.unidad_medida, i.costo_unitario, i.cantidad_stock, c.ambito
             FROM RECETA r
             JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
             JOIN CATEGORIA c ON c.id_categoria = i.id_categoria
             WHERE r.id_producto = ? ORDER BY i.nombre"
        );
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    public function costoReceta(string $id): array
    {
        $rows = $this->receta($id);
        $insumos = 0.0; $empaques = 0.0;
        foreach ($rows as $r) {
            $sub = (float) $r['costo_unitario'] * (float) $r['cantidad_necesaria'];
            if ($r['ambito'] === 'empaque_desechable') { $empaques += $sub; } else { $insumos += $sub; }
        }
        return ['insumos' => $insumos, 'empaques' => $empaques, 'total' => $insumos + $empaques];
    }

    public function guardarReceta(string $idProducto, array $items): void
    {
        $stmtD = $this->conn->prepare("DELETE FROM RECETA WHERE id_producto = ?");
        $stmtD->bind_param("s", $idProducto);
        $stmtD->execute();
        $stmtD->close();

        foreach ($items as $it) {
            if (empty($it['id_ingrediente']) || (float) ($it['cantidad'] ?? 0) <= 0) {
                continue;
            }
            $stmtI = $this->conn->prepare(
                "INSERT INTO RECETA (id_receta, id_producto, id_ingrediente, cantidad_necesaria) VALUES (?,?,?,?)"
            );
            $idR = uuid();
            $cant = (float) $it['cantidad'];
            $stmtI->bind_param("sssd", $idR, $idProducto, $it['id_ingrediente'], $cant);
            $stmtI->execute();
            $stmtI->close();
        }
    }

    public function guardar(array $d): string
    {
        if (!empty($d['id_producto'])) {
            $stmt = $this->conn->prepare(
                "UPDATE PRODUCTO SET id_categoria=?, nombre=?, descripcion=?, precio_venta=?, estado=?, imagen_url=? WHERE id_producto=?"
            );
            $precio = (float) ($d['precio_venta'] ?? 0);
            $estado = $d['estado'] ?? 'activo';
            $imagen = $d['imagen_url'] ?? null;
            $stmt->bind_param("ssdssss", $d['id_categoria'], $d['nombre'], $d['descripcion'], $precio, $estado, $imagen, $d['id_producto']);
            $stmt->execute();
            $stmt->close();
            return $d['id_producto'];
        } else {
            $id = uuid();
            $stmt = $this->conn->prepare(
                "INSERT INTO PRODUCTO (id_producto, id_categoria, nombre, descripcion, precio_venta, estado, imagen_url) VALUES (?,?,?,?,?,?,?)"
            );
            $precio = (float) ($d['precio_venta'] ?? 0);
            $estado = $d['estado'] ?? 'activo';
            $imagen = $d['imagen_url'] ?? null;
            $stmt->bind_param("sssdsss", $id, $d['id_categoria'], $d['nombre'], $d['descripcion'], $precio, $estado, $imagen);
            $stmt->execute();
            $stmt->close();
            return $id;
        }
    }

    public function cambiarEstado(string $id, string $estado): void
    {
        $stmt = $this->conn->prepare("UPDATE PRODUCTO SET estado = ? WHERE id_producto = ?");
        $stmt->bind_param("ss", $estado, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function tienePedidos(string $id): bool
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as cant FROM DETALLE_PEDIDO WHERE id_producto = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return ((int)($res['cant'] ?? 0)) > 0;
    }

    public function eliminar(string $id): bool
    {
        if ($this->tienePedidos($id)) {
            return false;
        }
        $stmt = $this->conn->prepare("DELETE FROM PRODUCTO WHERE id_producto = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function personalizables(string $id): array
    {
        $stmtR = $this->conn->prepare(
            "SELECT i.id_ingrediente, i.nombre
             FROM RECETA r
             JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
             JOIN CATEGORIA c ON c.id_categoria = i.id_categoria
             WHERE r.id_producto = ? AND c.ambito = 'insumo_alimenticio'
             ORDER BY i.nombre"
        );
        $stmtR->bind_param("s", $id);
        $stmtR->execute();
        $retirar = $stmtR->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtR->close();

        $resE = $this->conn->query(
            "SELECT i.id_ingrediente, i.nombre, i.precio_extra, (i.cantidad_stock <= 0) AS agotado
             FROM INGREDIENTE i
             JOIN CATEGORIA c ON c.id_categoria = i.id_categoria
             WHERE c.ambito = 'insumo_alimenticio' AND i.precio_extra > 0
             ORDER BY i.nombre"
        );
        $extras = $resE->fetch_all(MYSQLI_ASSOC);

        return ['retirar' => $retirar, 'extras' => $extras];
    }

    public function ingredientesCreador(): array
    {
        $configModel = new Configuracion();
        $margen = (float) $configModel->value('margen_ganancia_defecto', 30);
        $res = $this->conn->query(
            "SELECT i.id_ingrediente, i.nombre, i.costo_unitario, i.precio_extra,
                    (i.cantidad_stock <= 0) AS agotado, i.cantidad_stock, c.nombre AS categoria
             FROM INGREDIENTE i
             JOIN CATEGORIA c ON c.id_categoria = i.id_categoria
             WHERE c.ambito = 'insumo_alimenticio'
             ORDER BY c.nombre, i.nombre"
        );
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as &$r) {
            $base = (float) $r['precio_extra'] > 0
                ? (float) $r['precio_extra']
                : round((float) $r['costo_unitario'] * (1 + $margen / 100));
            $r['precio'] = $base;
        }
        return $rows;
    }
}

