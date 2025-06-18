<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SafeAlert\Database;

try {
    // Obtener una instancia de la base de datos
    $db = Database::getInstance();
    
    // Obtener la estructura de la tabla alertas
    $response = $db->getPostgrest()->request('GET', 'alertas?select=*');
    $body = $response->getBody()->getContents();
    $data = json_decode($body, true);
    
    // Mostrar los campos disponibles
    if (!empty($data)) {
        echo "Campos disponibles en la tabla alertas:\n";
        $firstRow = $data[0];
        foreach ($firstRow as $key => $value) {
            echo "- " . $key . "\n";
        }
        
        // Mostrar un ejemplo de registro
        echo "\nEjemplo de registro:\n";
        print_r($data[0]);
    } else {
        echo "No se encontraron registros en la tabla alertas\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
