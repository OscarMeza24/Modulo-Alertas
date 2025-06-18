<?php
declare(strict_types=1);

use SafeAlert\Alertas\Database;
use Composer\Autoload\ClassLoader;

$loader = new ClassLoader();
$loader->add('SafeAlert', __DIR__ . '/../vendor/autoload.php');
$loader->register();

try {
    // Obtener instancia de la base de datos
    $db = Database::getInstance();
    
    // Ejemplo 1: Obtener todas las alertas activas
    echo "\n=== Alertas Activas ===\n";
    $alertas = $db->obtenerAlertas(['estado' => 'activa']);
    foreach ($alertas as $alerta) {
        echo "Alerta ID: " . $alerta['id'] . " - " . $alerta['mensaje'] . "\n";
    }
    
    // Ejemplo 2: Obtener productos con stock bajo
    echo "\n=== Productos con Stock Bajo ===\n";
    $productos = $db->getPostgrest()
        ->from(TABLE_PRODUCTOS)
        ->select('*')
        ->lt('cantidad_stock', 5)  // Productos con menos de 5 unidades
        ->execute();
    
    foreach ($productos->data as $producto) {
        echo "Producto: " . $producto['nombre'] .
             " - Stock: " . $producto['cantidad_stock'] .
             " - Fecha Vencimiento: " . $producto['fecha_caducidad'] . "\n";
    }
    
    // Ejemplo 3: Crear una nueva alerta
    echo "\n=== Crear Nueva Alerta ===\n";
    $nuevaAlerta = [
        'producto_id' => 1,  // Deberías cambiar esto por un ID real
        'tipo_alerta' => 'stock_bajo',
        'mensaje' => 'Alerta de stock bajo para producto de prueba',
        'nivel_prioridad_id' => 2,  // Medio
        'estado' => 'activa'
    ];
    
    $response = $db->getPostgrest()
        ->from(TABLE_ALERTAS)
        ->insert([$nuevaAlerta])
        ->execute();
    
    echo "Alerta creada con éxito! ID: " . $response->data[0]['id'] . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
