<?php
/**
 * Modelo Configuracion.
 */
class Configuracion
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function get(): array
    {
        $res = $this->conn->query("SELECT * FROM CONFIGURACION_SISTEMA LIMIT 1");
        return $res ? ($res->fetch_assoc() ?: []) : [];
    }

    public function value(string $key, $default = null)
    {
        $cfg = $this->get();
        return $cfg[$key] ?? $default;
    }

    public function save(array $data): void
    {
        $cfg = $this->get();
        if ($cfg && !empty($cfg['id_config'])) {
            $sets = [];
            $types = "";
            $values = [];
            foreach ($data as $col => $val) {
                $sets[] = "{$col} = ?";
                if (is_int($val)) { $types .= "i"; }
                elseif (is_float($val)) { $types .= "d"; }
                else { $types .= "s"; }
                $values[] = $val;
            }
            $types .= "s";
            $values[] = $cfg['id_config'];

            $sql = "UPDATE CONFIGURACION_SISTEMA SET " . implode(", ", $sets) . " WHERE id_config = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param($types, ...$values);
            $stmt->execute();
            $stmt->close();
        }
    }

    public function cocinaAbierta(): bool
    {
        $cfg = $this->get();
        if (!empty($cfg['pausa_emergencia_activa'])) {
            return false;
        }
        $ahora    = date('H:i:s');
        $apertura = $cfg['horario_apertura'] ?? '08:00:00';
        $cierre   = $cfg['horario_cierre'] ?? '23:00:00';
        return $ahora >= $apertura && $ahora <= $cierre;
    }

    public function estadoCocina(): array
    {
        $cfg = $this->get();
        return [
            'abierta'  => $this->cocinaAbierta(),
            'pausa'    => (bool) ($cfg['pausa_emergencia_activa'] ?? false),
            'apertura' => substr($cfg['horario_apertura'] ?? '08:00', 0, 5),
            'cierre'   => substr($cfg['horario_cierre'] ?? '23:00', 0, 5),
        ];
    }
}

