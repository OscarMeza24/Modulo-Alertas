<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SafeAlert\Database;

try {
    // Obtener una instancia de la base de datos
    $db = Database::getInstance();
    
    // Obtener todas las alertas con sus campos
    $data = $db->obtenerAlertas();
    
    // Mostrar los campos disponibles
    if (!empty($data)) {
        echo "Campos disponibles en la tabla alertas:\n";
        $firstRow = $data[0];
        foreach ($firstRow as $key => $value) {
            echo "- " . $key . "\n";
        }
    } else {
        echo "No se encontraron registros en la tabla alertas\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}