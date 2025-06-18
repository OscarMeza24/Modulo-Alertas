<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Environment.php';
require_once __DIR__ . '/../src/Database.php';
use SafeAlert\Alertas\ReporteService;

// Services
use SafeAlert\Services\AlertaService;
use SafeAlert\Services\NivelPrioridadService;
use SafeAlert\Services\AlertaPrioritizationService;
use SafeAlert\Services\AlertaIAService;

// Models
use SafeAlert\Models\Alerta;
use SafeAlert\Models\NivelPrioridad;

echo "=== TEST DE ENTIDADES Y ENDPOINTS ===\n\n";

echo "=== 1. ENDPOINT DE NIVELES DE PRIORIDAD ===\n";
try {
    $nivelService = new NivelPrioridadService();
    
    // GET - Obtener todos los niveles
    echo "\n1.1 GET /niveles-prioridad\n";
    $niveles = $nivelService->obtenerNivelesPrioridad();
    echo "Niveles encontrados: " . count($niveles) . "\n";
    foreach ($niveles as $nivel) {
        echo "- ID: " . $nivel['id'] . ", Nombre: " . $nivel['nombre'] . ", Color: " . $nivel['color'] . "\n";
    }
    
    // POST - Crear nuevo nivel
    echo "\n1.2 POST /niveles-prioridad\n";
    $nuevoNivel = [
        'nombre' => 'Nivel Prueba ' . time(),
        'color' => '#FF0000'
    ];
    $nivelCreado = $nivelService->crearNivelPrioridad($nuevoNivel);
    echo "Nivel creado con ID: " . $nivelCreado['id'] . "\n";
    
    // PUT - Actualizar nivel
    echo "\n1.3 PUT /niveles-prioridad/{id}\n";
    $nivelActualizado = [
        'nombre' => 'Nivel Actualizado ' . time(),
        'color' => '#00FF00'
    ];
    $nivelService->actualizarNivelPrioridad($nivelCreado['id'], $nivelActualizado);
    echo "Nivel actualizado correctamente\n";
    
    // DELETE - Eliminar nivel
    echo "\n1.4 DELETE /niveles-prioridad/{id}\n";
    $nivelService->eliminarNivelPrioridad($nivelCreado['id']);
    echo "Nivel eliminado correctamente\n";
    
} catch (Exception $e) {
    echo "Error en endpoints de niveles: " . $e->getMessage() . "\n";
}

echo "\n=== 2. ENDPOINT DE ALERTAS ===\n";
try {
    $alertaService = new AlertaService();
    
    // GET - Obtener todas las alertas
    echo "\n2.1 GET /alertas\n";
    $alertas = $alertaService->obtenerAlertasActivas();
    echo "Alertas encontradas: " . count($alertas) . "\n";
    
    // POST - Crear nueva alerta
    echo "\n2.2 POST /alertas\n";
    $alertaData = [
        'descripcion' => 'Alerta de prueba ' . time(),
        'id_nivel_prioridad' => 1,
        'estado' => 'pendiente'
    ];
    $alertaCreada = $alertaService->crearAlerta($alertaData);
    echo "Alerta creada con ID: " . $alertaCreada['id'] . "\n";
    
    // PUT - Actualizar alerta
    echo "\n2.3 PUT /alertas/{id}\n";
    $alertaActualizada = [
        'descripcion' => 'Alerta actualizada ' . time(),
        'estado' => 'en_proceso'
    ];
    $alertaService->actualizarAlerta($alertaCreada['id'], $alertaActualizada);
    echo "Alerta actualizada correctamente\n";
    
    // DELETE - Eliminar alerta
    echo "\n2.4 DELETE /alertas/{id}\n";
    $alertaService->eliminarAlerta($alertaCreada['id']);
    echo "Alerta eliminada correctamente\n";
    
} catch (Exception $e) {
    echo "Error en endpoints de alertas: " . $e->getMessage() . "\n";
}

echo "\n=== 3. ENDPOINT DE REPORTES ===\n";
try {
    $reporteService = new ReporteService();
    
    // GET - Obtener reportes
    echo "\n3.1 GET /reportes\n";
    $reportes = $reporteService->obtenerReportes();
    echo "Reportes encontrados: " . count($reportes) . "\n";
    
    // POST - Generar nuevo reporte
    echo "\n3.2 POST /reportes\n";
    $reporteData = [
        'titulo' => 'Reporte Prueba ' . time(),
        'descripcion' => 'Descripción del reporte de prueba'
    ];
    $reporteCreado = $reporteService->crearReporte($reporteData);
    echo "Reporte creado con ID: " . $reporteCreado['id'] . "\n";
    
} catch (Exception $e) {
    echo "Error en endpoints de reportes: " . $e->getMessage() . "\n";
}

echo "\n=== 4. ENDPOINT DE PRIORIZACIÓN ===\n";
try {
    $prioritizationService = new AlertaPrioritizationService();
    
    // GET - Obtener alertas priorizadas
    echo "\n4.1 GET /priorizacion\n";
    $alertasPriorizadas = $prioritizationService->obtenerAlertasPriorizadas();
    echo "Alertas priorizadas encontradas: " . count($alertasPriorizadas) . "\n";
    
} catch (Exception $e) {
    echo "Error en endpoints de priorización: " . $e->getMessage() . "\n";
}

echo "\n=== 5. ENDPOINT DE IA ===\n";
try {
    $iaService = new AlertaIAService();
    
    // POST - Analizar alerta con IA
    echo "\n5.1 POST /ia/analizar\n";
    $alerta = new Alerta(null, "Descripción de prueba para análisis IA", date('Y-m-d H:i:s'), 'activa');
    $analisis = $iaService->analizarAlerta($alerta);
    echo "Análisis generado\n";
    
} catch (Exception $e) {
    echo "Error en endpoints de IA: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETADO ===\n";

echo "\n6. Verificando reportes...\n";
try {
    $reporteService = new \SafeAlert\Alertas\ReporteService();
    $clasificacion = $iaService->analizarAlerta($alerta);
    echo "Clasificación de IA: " . json_encode($clasificacion) . "\n";
    $reporte = $reporteService->generarReporte('2025-01-01', '2025-12-31');
    echo "Reporte generado: " . json_encode($reporte) . "\n";
} catch (Exception $e) {
    echo "Error al generar reporte: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DE TEST ===\n";
    