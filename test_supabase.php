<?php
require_once __DIR__ . '/vendor/autoload.php';
use Supabase\Postgrest\PostgrestClient;

try {
    $url = 'https://fuvewmtivcczpqarsjkw.supabase.co';
    $key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk';
    
    $client = new PostgrestClient($url, $key);
    
    // Intentar una consulta simple
    $response = $client->from('public')->select('*')->execute();
    
    if ($response->data) {
        echo "✅ Conexión exitosa a Supabase!\n";
        echo "✅ Consulta exitosa! Resultado: " . json_encode($response->data) . "\n";
    }
} catch (Exception $e) {
    echo "❌ Error en la conexión: " . $e->getMessage() . "\n";
}
