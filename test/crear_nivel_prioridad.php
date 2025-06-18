<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use SafeAlert\Services\NivelPrioridadService;

try {
    // Crear un servicio de niveles de prioridad
    $nivelService = new NivelPrioridadService();
    
    // Crear un nivel de prioridad por defecto
    $nivel = $nivelService->crearNivelPrioridad(
        'Alta',
        '#FF0000',
        0
    );
    
    echo "Nivel de prioridad creado con éxito:\n";
    print_r($nivel);
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
