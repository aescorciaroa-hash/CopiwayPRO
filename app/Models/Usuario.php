<?php
/**
 * Modelo Usuario (centraliza busqueda de credenciales entre los 4 tipos de cuenta).
 */
class Usuario
{
    private $conn;

    private const FUENTES = [
        'admin'        => ['ADMINISTRADOR',   'id_admin'],
        'cliente'      => ['CLIENTE',          'id_cliente'],
        'cocina'       => ['AYUDANTE_COCINA',  'id_ayudante'],
        'domiciliario' => ['DOMICILIARIO',     'id_domiciliario'],
    ];

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /** Busca una cuenta por correo en todas las tablas. */
    public function porCorreo(string $correo): ?array
    {
        foreach (self::FUENTES as $role => [$tabla, $idCol]) {
            $stmt = $this->conn->prepare("SELECT * FROM {$tabla} WHERE correo = ? LIMIT 1");
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                return $this->normalizar($role, $idCol, $row);
            }
        }
        return null;
    }

    /** Busca una cuenta por su id dentro de la tabla del rol. */
    public function porId(string $role, string $id): ?array
    {
        if (!isset(self::FUENTES[$role])) {
            return null;
        }
        [$tabla, $idCol] = self::FUENTES[$role];
        $stmt = $this->conn->prepare("SELECT * FROM {$tabla} WHERE {$idCol} = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ? $this->normalizar($role, $idCol, $row) : null;
    }

    /** Verifica si un correo o telefono ya existe en cualquier tabla. */
    public function existeCorreoOTelefono(string $correo, string $telefono): bool
    {
        foreach (self::FUENTES as [$tabla]) {
            $stmt = $this->conn->prepare("SELECT 1 FROM {$tabla} WHERE correo = ? OR telefono = ? LIMIT 1");
            $stmt->bind_param("ss", $correo, $telefono);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if ($row) {
                return true;
            }
        }
        return false;
    }

    public function verificarPassword(string $plano, string $hash): bool
    {
        return password_verify($plano, $hash);
    }

    private function normalizar(string $role, string $idCol, array $row): array
    {
        return [
            'role'     => $role,
            'id'       => $row[$idCol],
            'nombre'   => $row['nombre'] ?? '',
            'correo'   => $row['correo'] ?? '',
            'telefono' => $row['telefono'] ?? '',
            'activo'   => !array_key_exists('activo', $row) || (bool) $row['activo'],
            'row'      => $row,
        ];
    }
}

