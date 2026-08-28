<?php
/**
 * Modelo Categoria.
 */
class Categoria
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function find($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM CATEGORIA WHERE id_categoria = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function conConteo(string $ambito): array
    {
        if ($ambito === 'menu') {
            $sql = "SELECT c.*, (SELECT COUNT(*) FROM PRODUCTO p WHERE p.id_categoria = c.id_categoria) AS items
                    FROM CATEGORIA c WHERE c.ambito = 'menu' ORDER BY c.nombre";
            $res = $this->conn->query($sql);
            return $res->fetch_all(MYSQLI_ASSOC);
        } else {
            $sql = "SELECT c.*, (SELECT COUNT(*) FROM INGREDIENTE i WHERE i.id_categoria = c.id_categoria) AS items
                    FROM CATEGORIA c WHERE c.ambito = ? ORDER BY c.nombre";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $ambito);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        }
    }

    public function menu(): array
    {
        $res = $this->conn->query("SELECT * FROM CATEGORIA WHERE ambito = 'menu' ORDER BY nombre");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function deInsumos(): array
    {
        $res = $this->conn->query("SELECT * FROM CATEGORIA WHERE ambito IN ('insumo_alimenticio','empaque_desechable') ORDER BY nombre");
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function crear(array $d): string
    {
        $id = uuid();
        $stmt = $this->conn->prepare("INSERT INTO CATEGORIA (id_categoria, nombre, ambito, descripcion) VALUES (?,?,?,?)");
        $desc = $d['descripcion'] ?? null;
        $stmt->bind_param("ssss", $id, $d['nombre'], $d['ambito'], $desc);
        $stmt->execute();
        $stmt->close();
        return $id;
    }

    public function eliminar(string $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM CATEGORIA WHERE id_categoria = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}

