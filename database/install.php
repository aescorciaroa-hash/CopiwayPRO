<?php
/**
 * Instalador de la base de datos de CopiwayPRO.
 *
 * Uso desde la terminal (en la carpeta del proyecto):
 *     php database/install.php
 *
 * Crea la base de datos "hamburguer_copiway", carga el esquema
 * (tablas + triggers) y luego los datos de ejemplo (seed.sql).
 *
 * Requiere que MySQL este iniciado en Laragon.
 */

$config = require __DIR__ . '/../config/config.php';
$db = $config['db'];

echo "== Instalador de base de datos CopiwayPRO ==\n";

try {
    $pdo = new PDO(
        "mysql:host={$db['host']};port={$db['port']};charset={$db['charset']}",
        $db['user'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    fwrite(STDERR, "\nNo se pudo conectar a MySQL: " . $e->getMessage()
        . "\nAsegurate de iniciar MySQL en Laragon y vuelve a intentar.\n");
    exit(1);
}

/**
 * Divide un script SQL en sentencias, respetando los bloques DELIMITER $$
 * usados para crear triggers.
 */
function split_sql(string $sql): array
{
    $statements = [];
    $delimiter = ';';
    $buffer = '';
    $lines = preg_split('/\r\n|\r|\n/', $sql);

    foreach ($lines as $line) {
        $trim = trim($line);

        // Ignora comentarios de linea completa
        if ($trim === '' || str_starts_with($trim, '--')) {
            continue;
        }

        // Cambio de delimitador
        if (preg_match('/^DELIMITER\s+(\S+)/i', $trim, $m)) {
            $delimiter = $m[1];
            continue;
        }

        $buffer .= $line . "\n";

        // Si la linea termina con el delimitador actual, cerramos la sentencia
        if (str_ends_with(rtrim($line), $delimiter)) {
            $stmt = trim(substr(rtrim($buffer), 0, -strlen($delimiter)));
            if ($stmt !== '') {
                $statements[] = $stmt;
            }
            $buffer = '';
        }
    }

    if (trim($buffer) !== '') {
        $statements[] = trim($buffer);
    }

    return $statements;
}

function run_file(PDO $pdo, string $path, string $label): void
{
    if (!is_file($path)) {
        echo "  (omitido: no existe {$path})\n";
        return;
    }
    echo "-> Ejecutando {$label}...\n";
    $sql = file_get_contents($path);
    $statements = split_sql($sql);
    $ok = 0;
    foreach ($statements as $stmt) {
        try {
            $pdo->exec($stmt);
            $ok++;
        } catch (PDOException $e) {
            fwrite(STDERR, "   ! Error en una sentencia:\n     " . $e->getMessage()
                . "\n     " . substr(preg_replace('/\s+/', ' ', $stmt), 0, 160) . "...\n");
        }
    }
    echo "   {$ok}/" . count($statements) . " sentencias ejecutadas.\n";
}

run_file($pdo, __DIR__ . '/schema.sql', 'esquema (tablas + triggers)');

// El seed necesita seleccionar la base de datos
$pdo->exec("USE `{$db['name']}`");
run_file($pdo, __DIR__ . '/seed.sql', 'datos de ejemplo (seed)');

echo "\nListo. Credenciales de acceso de ejemplo:\n";
echo "  Administrador : admin@copiway.com        / admin123\n";
echo "  Cliente       : cliente@copiway.com      / cliente123\n";
echo "  Cocina        : cocina@copiway.com       / cocina123   (PIN estacion: 1234)\n";
echo "  Domiciliario  : domiciliario@copiway.com / domi123     (PIN estacion: 5678)\n";
echo "\nAbre el proyecto en el navegador (ej: http://copiway.test).\n";
