<?php
/**
 * Modelo Empleado (ayudantes de cocina y domiciliarios).
 */
class Empleado
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /** Lista combinada de empleados para la pantalla "Colaboradores". */
    public function todos(): array
    {
        $resA = $this->conn->query(
            "SELECT id_ayudante AS id, 'cocina' AS rol, nombre, correo, telefono, activo, turno,
                    NULL AS placa, NULL AS tipo_vehiculo, NULL AS base_efectivo_asignada, 'desconectado' AS estado_disponibilidad, NULL AS pedido_actual
             FROM AYUDANTE_COCINA"
        );
        $ayudantes = $resA->fetch_all(MYSQLI_ASSOC);

        $resD = $this->conn->query(
            "SELECT id_domiciliario AS id, 'domiciliario' AS rol, nombre, correo, telefono, activo, NULL AS turno,
                    placa, tipo_vehiculo, base_efectivo_asignada, COALESCE(estado_disponibilidad, 'desconectado') AS estado_disponibilidad
             FROM DOMICILIARIO"
        );
        $domis = $resD->fetch_all(MYSQLI_ASSOC);

        $todos = array_merge($ayudantes, $domis);
        usort($todos, fn($a, $b) => strcmp($a['nombre'], $b['nombre']));
        return $todos;
    }

    public function buscar(string $rol, string $id): ?array
    {
        if ($rol === 'cocina') {
            $stmt = $this->conn->prepare("SELECT * FROM AYUDANTE_COCINA WHERE id_ayudante = ?");
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM DOMICILIARIO WHERE id_domiciliario = ?");
        }
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function crear(string $rol, array $d, string $idAdmin): string
    {
        $hash = password_hash($d['contrasena'], PASSWORD_BCRYPT);
        $id = uuid();
        if ($rol === 'cocina') {
            $stmt = $this->conn->prepare(
                "INSERT INTO AYUDANTE_COCINA (id_ayudante, nombre, correo, telefono, contrasena, turno, activo, creado_por)
                 VALUES (?,?,?,?,?,?,1,?)"
            );
            $turno = $d['turno'] ?? 'mixto';
            $stmt->bind_param("sssssss", $id, $d['nombre'], $d['correo'], $d['telefono'], $hash, $turno, $idAdmin);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt = $this->conn->prepare(
                "INSERT INTO DOMICILIARIO
                   (id_domiciliario, nombre, correo, telefono, contrasena, tipo_vehiculo, placa,
                    base_efectivo_asignada, estado_disponibilidad, activo, creado_por)
                 VALUES (?,?,?,?,?,?,?,?,'desconectado',1,?)"
            );
            $vehiculo = $d['tipo_vehiculo'] ?? 'moto';
            $placa = $d['placa'] ?? null;
            $base = (float) ($d['base_efectivo_asignada'] ?? 0);
            $stmt->bind_param("sssssssds", $id, $d['nombre'], $d['correo'], $d['telefono'], $hash, $vehiculo, $placa, $base, $idAdmin);
            $stmt->execute();
            $stmt->close();
        }
        return $id;
    }

    public function actualizar(string $rol, string $id, array $d): void
    {
        if ($rol === 'cocina') {
            if (!empty($d['contrasena'])) {
                $stmt = $this->conn->prepare("UPDATE AYUDANTE_COCINA SET nombre=?, correo=?, telefono=?, contrasena=?, turno=? WHERE id_ayudante=?");
                $hash = password_hash($d['contrasena'], PASSWORD_BCRYPT);
                $turno = $d['turno'] ?? 'mixto';
                $stmt->bind_param("ssssss", $d['nombre'], $d['correo'], $d['telefono'], $hash, $turno, $id);
            } else {
                $stmt = $this->conn->prepare("UPDATE AYUDANTE_COCINA SET nombre=?, correo=?, telefono=?, turno=? WHERE id_ayudante=?");
                $turno = $d['turno'] ?? 'mixto';
                $stmt->bind_param("sssss", $d['nombre'], $d['correo'], $d['telefono'], $turno, $id);
            }
            $stmt->execute();
            $stmt->close();
        } else {
            $vehiculo = $d['tipo_vehiculo'] ?? 'moto';
            $placa = $d['placa'] ?? null;
            $base = (float) ($d['base_efectivo_asignada'] ?? 0);
            if (!empty($d['contrasena'])) {
                $stmt = $this->conn->prepare("UPDATE DOMICILIARIO SET nombre=?, correo=?, telefono=?, contrasena=?, tipo_vehiculo=?, placa=?, base_efectivo_asignada=? WHERE id_domiciliario=?");
                $hash = password_hash($d['contrasena'], PASSWORD_BCRYPT);
                $stmt->bind_param("ssssssds", $d['nombre'], $d['correo'], $d['telefono'], $hash, $vehiculo, $placa, $base, $id);
            } else {
                $stmt = $this->conn->prepare("UPDATE DOMICILIARIO SET nombre=?, correo=?, telefono=?, tipo_vehiculo=?, placa=?, base_efectivo_asignada=? WHERE id_domiciliario=?");
                $stmt->bind_param("sssssds", $d['nombre'], $d['correo'], $d['telefono'], $vehiculo, $placa, $base, $id);
            }
            $stmt->execute();
            $stmt->close();
        }
    }

    public function darDeBaja(string $rol, string $id): void
    {
        $tabla = $rol === 'cocina' ? 'AYUDANTE_COCINA' : 'DOMICILIARIO';
        $col   = $rol === 'cocina' ? 'id_ayudante' : 'id_domiciliario';
        $stmt = $this->conn->prepare("UPDATE {$tabla} SET activo = 0 WHERE {$col} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function reactivar(string $rol, string $id): void
    {
        $tabla = $rol === 'cocina' ? 'AYUDANTE_COCINA' : 'DOMICILIARIO';
        $col   = $rol === 'cocina' ? 'id_ayudante' : 'id_domiciliario';
        $stmt = $this->conn->prepare("UPDATE {$tabla} SET activo = 1 WHERE {$col} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
    }

    public function tienePedidos(string $rol, string $id): bool
    {
        $col = $rol === 'cocina' ? 'id_ayudante' : 'id_domiciliario';
        $stmt = $this->conn->prepare("SELECT COUNT(*) as cant FROM PEDIDO WHERE {$col} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return ((int)($res['cant'] ?? 0)) > 0;
    }

    public function eliminar(string $rol, string $id): bool
    {
        if ($this->tienePedidos($rol, $id)) {
            return false;
        }
        $tabla = $rol === 'cocina' ? 'AYUDANTE_COCINA' : 'DOMICILIARIO';
        $col   = $rol === 'cocina' ? 'id_ayudante' : 'id_domiciliario';
        $stmt = $this->conn->prepare("DELETE FROM {$tabla} WHERE {$col} = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->close();
        return true;
    }

    public function activos(): int
    {
        $resA = $this->conn->query("SELECT COUNT(*) as cant FROM AYUDANTE_COCINA WHERE activo = 1")->fetch_assoc();
        $resD = $this->conn->query("SELECT COUNT(*) as cant FROM DOMICILIARIO WHERE activo = 1")->fetch_assoc();
        return ((int)($resA['cant'] ?? 0)) + ((int)($resD['cant'] ?? 0));
    }
}

