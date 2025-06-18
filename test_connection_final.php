<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use SafeAlert\Database;

try {
    // Inicializar base de datos
    $database = Database::getInstance();
    
    // Probar conexión
    if ($database->testConnection()) {
        echo "✅ Conexión exitosa a Supabase!\n";
    } else {
        echo "❌ Fallo en la conexión a Supabase\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
