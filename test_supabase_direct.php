<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

try {
    // Configurar las variables de entorno
    $url = 'https://fuvewmtivcczpqarsjkw.supabase.co';
    $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk';

    // Inicializar cliente de Guzzle
    $client = new Client([
        'base_uri' => $url . '/rest/v1/',
        'headers' => [
            'apikey' => $key,
            'Authorization' => 'Bearer ' . $key,
            'Content-Type' => 'application/json'
        ]
    ]);

    // Probar conexión
    $response = $client->request('GET', 'niveles_prioridad?select=*');
    $body = $response->getBody()->getContents();
    $data = json_decode($body, true);

    echo "✅ Conexión exitosa a Supabase!\n";
    echo "✅ Datos obtenidos: " . json_encode($data) . "\n";

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
