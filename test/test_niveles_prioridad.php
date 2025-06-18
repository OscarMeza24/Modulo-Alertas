<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SafeAlert\Database;

try {
    // Obtener una instancia de la base de datos
    $db = Database::getInstance();
    
    // Obtener todos los niveles de prioridad
    $response = $db->getPostgrest()->request('GET', 'niveles_prioridad');
    $body = $response->getBody()->getContents();
    $data = json_decode($body, true);
    
    // Mostrar los niveles disponibles
    if (!empty($data)) {
        echo "Niveles de prioridad disponibles:\n";
        foreach ($data as $nivel) {
            echo "ID: " . $nivel['id'] . ", Nombre: " . $nivel['nombre'] . ", Color: " . $nivel['color_hex'] . "\n";
        }
    } else {
        echo "No se encontraron niveles de prioridad\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
