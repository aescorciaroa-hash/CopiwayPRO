<?php
/**
 * Modelo Cliente.
 */
class Cliente
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function registrar(array $d): string
    {
        $id = uuid();
        $hash = password_hash($d['contrasena'], PASSWORD_BCRYPT);
        $direccion = $d['direccion'] ?? null;
        $fechaNac = $d['fecha_nacimiento'];
        $puntos = 0;
        $fechaAceptacion = now();

        $stmt = $this->conn->prepare("INSERT INTO CLIENTE (id_cliente, nombre, telefono, correo, contrasena, direccion, fecha_nacimiento, puntos_fidelidad, fecha_aceptacion_habeas_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssis", $id, $d['nombre'], $d['telefono'], $d['correo'], $hash, $direccion, $fechaNac, $puntos, $fechaAceptacion);
        $stmt->execute();
        $stmt->close();

        return $id;
    }

    /** Directorio para el administrador con resumen de compras. */
    public function directorio(?string $buscar = null): array
    {
        $sql = "SELECT c.*,
                   (SELECT COUNT(*) FROM PEDIDO p WHERE p.id_cliente = c.id_cliente AND p.estado <> 'cancelado') AS pedidos,
                   (SELECT COALESCE(SUM(p.total),0) FROM PEDIDO p WHERE p.id_cliente = c.id_cliente AND p.estado <> 'cancelado') AS total_gastado,
                   (SELECT MAX(p.fecha_hora) FROM PEDIDO p WHERE p.id_cliente = c.id_cliente) AS ultimo_pedido
                FROM CLIENTE c WHERE 1=1";
        
        if ($buscar) {
            $sql .= " AND (c.nombre LIKE ? OR c.telefono LIKE ?)";
            $stmt = $this->conn->prepare($sql . " ORDER BY c.nombre");
            $like = "%{$buscar}%";
            $stmt->bind_param("ss", $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $data;
        } else {
            $result = $this->conn->query($sql . " ORDER BY c.nombre");
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }

    public function historial(string $idCliente): array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*, d.nombre AS domiciliario_nombre
             FROM PEDIDO p
             LEFT JOIN DOMICILIARIO d ON d.id_domiciliario = p.id_domiciliario
             WHERE p.id_cliente = ? ORDER BY p.fecha_hora DESC"
        );
        $stmt->bind_param("s", $idCliente);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $data;
    }

    public function esCumpleanos(?array $cliente): bool
    {
        return !empty($cliente['fecha_nacimiento'])
            && date('m-d', strtotime($cliente['fecha_nacimiento'])) === date('m-d');
    }
}

