<?php
require_once __DIR__ . '/../vendor/autoload.php';
use SafeAlert\Exception\DatabaseException;
use SafeAlert\Database;

try {
    // Obtener una instancia de la clase Database
    $database = Database::getInstance();
    
    // Verificar la conexión
    if ($database->testConnection()) {
        echo "✅ Conexión exitosa a Supabase!\n";
        
        // Intentar una consulta de prueba
        $result = $database->query('SELECT 1');
        echo "✅ Consulta exitosa! Resultado: " . json_encode($result) . "\n";
    }
} catch (DatabaseException $e) {
    echo "❌ Error en la conexión: " . $e->getMessage() . "\n";
    echo "❌ Código de error: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "❌ Error inesperado: " . $e->getMessage() . "\n";
}
