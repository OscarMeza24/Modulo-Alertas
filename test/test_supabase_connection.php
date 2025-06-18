<?php
use SafeAlert\Alertas\Database;

// Configuration values should be loaded from environment variables
// or through a proper configuration management system
// The vendor/autoload.php is already included through composer
// Ensure your environment variables are properly set before running tests

try {
    // Obtener una instancia de la base de datos
    $database = Database::getInstance();
    
    // Probar una consulta simple
    $result = $database->query('SELECT 1');
    
    echo "✅ Conexión exitosa a Supabase!\n";
    echo "✅ Resultado de la prueba: " . json_encode($result) . "\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
