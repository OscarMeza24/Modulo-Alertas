<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/config.php';

use GuzzleHttp\Client;

try {
    // Obtener configuración
    $config = require __DIR__ . '/config.php';
    
    // Crear cliente HTTP
    $client = new Client([
        'base_uri' => $config['supabase_url'] . '/rest/v1/',
        'headers' => [
            'apikey' => $config['supabase_key'],
            'Authorization' => 'Bearer ' . $config['supabase_key'],
            'Content-Type' => 'application/json'
        ]
    ]);

    // Datos de la alerta
    $data = [
        'tipo_alerta' => 'manual',
        'mensaje' => 'Prueba de alerta',
        'nivel_prioridad_id' => 1,
        'estado' => 'activa'
    ];

    // Hacer la petición
    $response = $client->request('POST', 'alertas', [
        'json' => $data,
        'debug' => true
    ]);

    // Mostrar la respuesta completa
    echo "Código de respuesta: " . $response->getStatusCode() . "\n";
    echo "Cuerpo de la respuesta:\n";
    echo $response->getBody()->getContents() . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if (isset($response)) {
        echo "Código de respuesta: " . $response->getStatusCode() . "\n";
        echo "Cuerpo de la respuesta:\n";
        echo $response->getBody()->getContents() . "\n";
    }
}
