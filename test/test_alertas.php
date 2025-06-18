<?php
require_once __DIR__ . '/../vendor/autoload.php';
use SafeAlert\Alertas\Alerta;
use SafeAlert\Alertas\ReporteAlertas;
use SafeAlert\Alertas\NivelPrioridad;

// Configuración de pruebas
$testAlerta = [
    'descripcion' => 'Alerta de prueba para testing',
    'fecha_reporte' => date('Y-m-d H:i:s'),
    'id_usuario_reporta' => 1,
    'evidencia' => 'Evidencia de prueba',
    'estado' => 'pendiente'
];

try {
    // 1. Prueba de creación de alerta
    $alerta = new Alerta();
    $alerta->setDescripcion($testAlerta['descripcion']);
    $alerta->setFechaReporte($testAlerta['fecha_reporte']);
    $alerta->setIdUsuarioReporta($testAlerta['id_usuario_reporta']);
    $alerta->setEvidencia($testAlerta['evidencia']);
    $alerta->setEstado($testAlerta['estado']);
    
    if ($alerta->save()) {
        echo "✅ Alerta creada exitosamente\n";
    } else {
        echo "❌ Error al crear alerta\n";
    }

    // 2. Prueba de consulta de alertas
    $alertas = Alerta::getAll();
    if (!empty($alertas)) {
        echo "✅ Consulta de alertas exitosa\n";
        echo "Total de alertas: " . count($alertas) . "\n";
    } else {
        echo "❌ No se pudieron obtener alertas\n";
    }

    // 3. Prueba de creación de reporte
    $reporte = new ReporteAlertas();
    $reporte->setIdAlerta($alerta->getId());
    $reporte->setDescripcion('Reporte de prueba');
    $reporte->setFechaReporte(date('Y-m-d H:i:s'));
    $reporte->setIdUsuarioReporta(1);
    $reporte->setEvidencia('Evidencia del reporte');
    $reporte->setEstado('pendiente');
    
    if ($reporte->save()) {
        echo "✅ Reporte creado exitosamente\n";
    } else {
        echo "❌ Error al crear reporte\n";
    }

    // 4. Prueba de niveles de prioridad
    $prioridades = NivelPrioridad::getAll();
    if (!empty($prioridades)) {
        echo "✅ Niveles de prioridad cargados exitosamente\n";
        echo "Niveles disponibles: " . implode(', ', array_column($prioridades, 'nombre')) . "\n";
    } else {
        echo "❌ No se pudieron obtener niveles de prioridad\n";
    }

} catch (Exception $e) {
    echo "❌ Error en las pruebas: " . $e->getMessage() . "\n";
}
