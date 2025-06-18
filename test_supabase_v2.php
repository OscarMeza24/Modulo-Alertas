<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

// Set environment variables
putenv('SUPABASE_URL=https://fuvewmtivcczpqarsjkw.supabase.co');
putenv('SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZ1dmV3bXRpdmNjenBxYXJzamt3Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk');

// Set environment variables
putenv('SUPABASE_URL=https://fuvewmtivcczpqarsjkw.supabase.co');
putenv('SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYb29raWVzIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk');

use SafeAlert\Database;

// Debug: Print environment variables
echo "Debug: SUPABASE_URL: " . getenv('SUPABASE_URL') . "\n";
echo "Debug: SUPABASE_KEY: " . substr(getenv('SUPABASE_KEY'), 0, 10) . "...\n";

echo "Debug: Constants:\n";

// Initialize database connection
try {
    $db = Database::getInstance();
    echo "Debug: Database connection successful\n";
    
    // Test query
    try {
        $client = $db->getClient();
        try {
            $response = $client->from('niveles_prioridad')->select('*')->execute();
            if (empty($response->data)) {
                echo " Advertencia: No se encontraron registros en la tabla\n";
            } else {
                echo " Se encontraron " . count($response->data) . " registros\n";
            }
        } catch (\Exception $e) {
            echo " Error en consulta: " . $e->getMessage() . "\n";
            echo "Debug: Error code: " . $e->getCode() . "\n";
            echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
            throw $e;
        }
        if (empty($response->data)) {
            echo " Advertencia: No se encontraron registros en la tabla\n";
        } else {
            echo " Se encontraron " . count($response->data) . " registros\n";
        }
    } catch (Exception $e) {
        echo " Error en consulta: " . $e->getMessage() . "\n";
        echo "Debug: Error code: " . $e->getCode() . "\n";
        echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
        throw $e;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    echo "Debug: Database test completed\n";
    echo "Debug: Script completed\n";
}

try {
    // Initialize Database class
    echo " Inicializando Database...\n";
    $database = Database::getInstance();
    echo " Database inicializado\n";
    
    // Debug: Print Database instance
    echo "Debug: Database instance: " . print_r($database, true) . "\n";
    
    // Try a simple query using Database class
    echo "🔧 Realizando consulta usando Database...\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Debug: File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Debug: Stack Trace: " . $e->getTraceAsString() . "\n";
} finally {
    echo "Debug: Database test completed\n";
}
    
    try {
        // Use the correct table name from the schema
        $tableName = 'niveles_prioridad';
        echo "Debug: Using table name from schema: " . $tableName . "\n";
        
        // Get client from Database
        $client = $database->getClient();
        echo "Debug: Client instance: " . print_r($client, true) . "\n";
        
        // First try a simple health check
        echo "🔧 Verificando conexión...\n";
        try {
            $healthCheck = $client->from('public')->select('*')->execute();
            echo "✅ Conexión verificada\n";
            echo "Debug: Health check response: " . json_encode($healthCheck->data, JSON_PRETTY_PRINT) . "\n";
        } catch (Exception $e) {
            echo "❌ Error en verificación de conexión: " . $e->getMessage() . "\n";
            echo "Debug: Error code: " . $e->getCode() . "\n";
            echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
            throw $e;
        }
        
        // Try the query
        echo "🔧 Ejecutando consulta SELECT * FROM {$tableName}...\n";
        $response = $client->from($tableName)->select('*')->execute();
        
        // Print detailed response information
        echo "✅ Consulta exitosa\n";
        echo "Debug: Response data: " . json_encode($response->data, JSON_PRETTY_PRINT) . "\n";
        echo "Debug: Response status: " . $response->status . "\n";
        echo "Debug: Response error: " . json_encode($response->error, JSON_PRETTY_PRINT) . "\n";
        
        // Check if we got any data
        if (empty($response->data)) {
            echo " Advertencia: No se encontraron registros en la tabla\n";
        } else {
            echo " Se encontraron " . count($response->data) . " registros\n";
    }
    
    // Try the query
    try {
        echo " Ejecutando consulta SELECT * FROM {$tableName}...\n";
        $response = $client->from($tableName)->select('*')->execute();
    } catch (Exception $e) {
        echo " Error en verificación de conexión: " . $e->getMessage() . "\n";
        echo "Debug: Error code: " . $e->getCode() . "\n";
        echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
        throw $e;
    }
    
    // Print detailed response information
    echo " Consulta exitosa\n";
    echo "Debug: Response data: " . json_encode($response->data, JSON_PRETTY_PRINT) . "\n";
    echo "Debug: Response status: " . $response->status . "\n";
    echo "Debug: Response error: " . json_encode($response->error, JSON_PRETTY_PRINT) . "\n";
    
    // Check if we got any data
    if (empty($response->data)) {
        echo " Advertencia: No se encontraron registros en la tabla\n";
    } else {
        echo " Se encontraron " . count($response->data) . " registros\n";
    }
} catch (Exception $e) {
    echo " Error en consulta: " . $e->getMessage() . "\n";
    echo "Debug: Error code: " . $e->getCode() . "\n";
    echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
    throw $e;
}