<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

try {
    // Crear cliente HTTP
    $client = new Client([
        'base_uri' => 'https://fuvewmtivcczpqarsjkw.supabase.co/rest/v1/',
        'headers' => [
            'apikey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk',
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF1DlOPcCcPNoECBjCIh7FqOOPLNXk',
            'Content-Type' => 'application/json'
        ]
    ]);

    // Hacer la petición GET
    $response = $client->request('GET', 'alertas');

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
