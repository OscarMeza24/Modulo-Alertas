<?php
require_once __DIR__ . '/vendor/autoload.php';

// Configurar las variables de entorno
putenv('SUPABASE_URL=https://fuvewmtivcczpqarsjkw.supabase.co');
putenv('SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk');

try {
    // Obtener una instancia de la clase Database
    $database = \SafeAlert\Database::getInstance();
    
    // Verificar la conexión
    if ($database->testConnection()) {
        echo "✅ Conexión exitosa a Supabase!\n";
        
        // Intentar una consulta de prueba usando el cliente
        $client = $database->getClient();
        $result = $client->from('niveles_prioridad')->select('*')->execute();
        $data = $result->data;
        echo "✅ Consulta exitosa! Resultado: " . json_encode($data) . "\n";
    }
} catch (\SafeAlert\Exception\DatabaseException $e) {
    echo "❌ Error en la conexión: " . $e->getMessage() . "\n";
    echo "❌ Código de error: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "❌ Error inesperado: " . $e->getMessage() . "\n";
}
