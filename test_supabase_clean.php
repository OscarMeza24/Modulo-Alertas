<?php
// Explicitly set environment variables
putenv('SUPABASE_URL=https://fuvewmtivcczpqarsjkw.supabase.co');
putenv('SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYb29raWVzIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc0ODcyOTg0MiwiZXhwIjoyMDY0MzA1ODQyfQ.6lC0Yi0sInxhPzF2DlOPcCcPNoECBjCIh7FqOOPLNXk');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use SafeAlert\Database;
use Supabase\SupabaseClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use ReflectionObject;
use Supabase\Postgrest\Exception\PostgrestException;

// Debug: Print environment variables
echo "Debug: SUPABASE_URL: " . getenv('SUPABASE_URL') . "\n";
echo "Debug: SUPABASE_KEY: " . substr(getenv('SUPABASE_KEY'), 0, 10) . "...\n";
echo "Debug: SUPABASE_KEY length: " . strlen(getenv('SUPABASE_KEY')) . "\n";

// Verify environment variables are not empty
if (empty(getenv('SUPABASE_URL'))) {
    echo "❌ Error: SUPABASE_URL is not set in environment variables\n";
    throw new \RuntimeException("SUPABASE_URL is not set in environment variables");
}
if (empty(getenv('SUPABASE_KEY'))) {
    echo "❌ Error: SUPABASE_KEY is not set in environment variables\n";
    throw new \RuntimeException("SUPABASE_KEY is not set in environment variables");
}

// Debug: Print all constants
echo "Debug: Constants:\n";
foreach (get_defined_constants(true)['user'] as $name => $value) {
    echo "Debug: $name = $value\n";
}

// Verify constants are defined
if (!defined('SUPABASE_URL')) {
    echo "❌ Error: SUPABASE_URL constant is not defined\n";
    throw new \RuntimeException("SUPABASE_URL constant is not defined");
}
if (!defined('SUPABASE_KEY')) {
    echo "❌ Error: SUPABASE_KEY constant is not defined\n";
    throw new \RuntimeException("SUPABASE_KEY constant is not defined");
}

// First try raw HTTP request with Guzzle
echo "🔧 Intentando conexión directa con Guzzle...\n";
try {
    $client = new GuzzleClient([
        'base_uri' => getenv('SUPABASE_URL') . '/rest/v1/',
        'headers' => [
            'apikey' => getenv('SUPABASE_KEY'),
            'Authorization' => 'Bearer ' . getenv('SUPABASE_KEY'),
            'Content-Type' => 'application/json'
        ]
    ]);
    
    $response = $client->request('GET', 'public?select=*');
    $statusCode = $response->getStatusCode();
    $body = $response->getBody()->getContents();
    
    echo "Debug: HTTP Status: " . $statusCode . "\n";
    echo "Debug: Response: " . $body . "\n";
    
    try {
        $results = json_decode($body, true);
        echo "Debug: Decoded Results: " . json_encode($results, JSON_PRETTY_PRINT) . "\n";
    } catch (Exception $e) {
        echo "❌ Error decoding JSON: " . $e->getMessage() . "\n";
        echo "Debug: Raw Response: " . $body . "\n";
    }
} catch (RequestException $e) {
    echo "❌ Error en conexión directa: " . $e->getMessage() . "\n";
    echo "Debug: HTTP Code: " . $e->getCode() . "\n";
    echo "Debug: Response: " . $e->getResponse()->getBody()->getContents() . "\n";
} catch (Exception $e) {
    echo "❌ Error en conexión directa: " . $e->getMessage() . "\n";
}

try {
    // Initialize Database class
    echo "🔧 Inicializando Database...\n";
    $database = Database::getInstance();
    echo "✅ Database inicializado\n";
    
    // Get client from Database
    $client = $database->getClient();
    echo "Debug: Client instance: " . print_r($client, true) . "\n";
    
    // Verify client is initialized
    if (!$client) {
        throw new \RuntimeException("Client instance is not initialized");
    }
    
    // Verify client configuration
    try {
        // Get URL from environment variable since it's not directly accessible from client
        echo "Debug: Client URL: " . getenv('SUPABASE_URL') . "\n";
        // Get headers from client configuration
        echo "Debug: Client headers: " . json_encode([
            'apikey' => getenv('SUPABASE_KEY'),
            'Authorization' => 'Bearer ' . getenv('SUPABASE_KEY')
        ]) . "\n";
    } catch (Exception $e) {
        echo "❌ Error getting client configuration: " . $e->getMessage() . "\n";
    }
    
    // First try a health check
    echo "🔧 Verificando conexión...\n";
    try {
        // Build the query
        $query = $client->from('niveles_prioridad')->select('*');
        
        // Execute the query
        $healthCheck = $query->execute();
        echo "✅ Conexión verificada\n";
        echo "Debug: Health check response: " . json_encode($healthCheck->data, JSON_PRETTY_PRINT) . "\n";
        echo "Debug: Response status: " . $healthCheck->status . "\n";
        echo "Debug: Response error: " . json_encode($healthCheck->error, JSON_PRETTY_PRINT) . "\n";
    } catch (\Exception $e) {
        echo "❌ Error en verificación de conexión: " . $e->getMessage() . "\n";
        echo "Debug: Error code: " . $e->getCode() . "\n";
        echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
        
        // Get detailed error info if available
        try {
            // Handle different types of exceptions
            if ($e instanceof \Exception || $e instanceof \SafeAlert\Alertas\Exception\SupabaseConnectionException) {
                echo "Debug: SupabaseException detected\n";
                echo "Debug: Error message: " . $e->getMessage() . "\n";
                echo "Debug: Error code: " . $e->getCode() . "\n";
            } elseif ($e instanceof \SafeAlert\Alertas\Exception\SupabaseConnectionException) {
                echo "Debug: SupabaseConnectionException detected\n";
                echo "Debug: Error message: " . $e->getMessage() . "\n";
            } else {
                echo "Debug: Generic exception detected\n";
                echo "Debug: Error message: " . $e->getMessage() . "\n";
            }
        } catch (Exception $innerE) {
            echo "Debug: Could not access response properties: " . $innerE->getMessage() . "\n";
        }
        throw $e;
    }
} catch (Exception $e) {
    echo " Error en verificación de conexión: " . $e->getMessage() . "\n";
    echo "Debug: Error code: " . $e->getCode() . "\n";
    echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
    throw $e;
}

// Try a simple query
echo "🔧 Realizando consulta...\n";

// Use the correct table name from the schema
$tableName = 'niveles_prioridad';
echo "Debug: Using table name: " . $tableName . "\n";

// Try the query
echo "🔧 Ejecutando consulta SELECT * FROM {$tableName}...\n";
try {
    // Build the query
    $query = $client->from($tableName)->select('*');
    
    // Execute the query
    $response = $query->execute();
    
    // Print detailed response
    echo "✅ Consulta exitosa\n";
    echo "Debug: Response data: " . json_encode($response->data, JSON_PRETTY_PRINT) . "\n";
    echo "Debug: Response status: " . $response->status . "\n";
    echo "Debug: Response error: " . json_encode($response->error, JSON_PRETTY_PRINT) . "\n";
    
    // Check if we got any data
    if (empty($response->data)) {
        echo "⚠️ Advertencia: No se encontraron registros en la tabla\n";
    } else {
        echo "✅ Se encontraron " . count($response->data) . " registros\n";
    }
} catch (Exception $e) {
    echo "❌ Error en consulta: " . $e->getMessage() . "\n";
    echo "Debug: Error code: " . $e->getCode() . "\n";
    echo "Debug: Error trace: " . $e->getTraceAsString() . "\n";
    echo "Debug: Previous error: " . $e->getPrevious() ? $e->getPrevious()->getMessage() : 'No previous error' . "\n";
    
    // Get all properties of the exception
    $reflection = new ReflectionObject($e);
    $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
    foreach ($properties as $property) {
        $property->setAccessible(true);
        echo "Debug: " . $property->getName() . ": " . $property->getValue($e) . "\n";
    }
    
    throw $e;
}

echo "✅ Prueba completada exitosamente\n";
