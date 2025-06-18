<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use SafeAlert\Services\AlertaPrioritizationService;
use SafeAlert\Models\Alerta;
use SafeAlert\Models\NivelPrioridad;

class AlertaPrioritizationServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new AlertaPrioritizationService();
    }

    public function testAnalyzeAlertPriority(): void
    {
        // Crear una alerta de prueba
        $alerta = new Alerta();
        $alerta->setDescripcion("Incidente crítico: Sistema de seguridad comprometido");
        $alerta->setTipo("Seguridad");
        $alerta->setUbicacion("Servidor principal");

        // Analizar la prioridad
        $nivel = $this->service->analyzeAlertPriority($alerta);

        // Verificar que el nivel es válido
        $this->assertInstanceOf(NivelPrioridad::class, $nivel);
        $this->assertGreaterThanOrEqual(1, $nivel->getNivel());
        $this->assertLessThanOrEqual(5, $nivel->getNivel());
    }

    public function testGenerateMetricsReport(): void
    {
        // Crear alertas de prueba
        $alerta1 = new Alerta();
        $alerta1->setDescripcion("Sistema de seguridad comprometido");
        $alerta1->setTipo("Seguridad");
        $alerta1->setUbicacion("Servidor principal");

        $alerta2 = new Alerta();
        $alerta2->setDescripcion("Fallo en el sistema de respaldo");
        $alerta2->setTipo("Infraestructura");
        $alerta2->setUbicacion("Centro de datos");

        // Generar reporte
        $reporte = $this->service->generateMetricsReport([$alerta1, $alerta2]);

        // Verificar el reporte
        $this->assertNotNull($reporte->getFechaCreacion());
        $this->assertArrayHasKey('total_alertas', $reporte->getMetricas());
        $this->assertArrayHasKey('prioridades', $reporte->getMetricas());
        $this->assertArrayHasKey('promedio_urgencia', $reporte->getMetricas());
    }

    public function testInvalidApiKey(): void
    {
        $this->expectException(\SafeAlert\Exception\DatabaseException::class);
        
        $service = new AlertaPrioritizationService('invalid-api-key');
        $alerta = new Alerta();
        $service->analyzeAlertPriority($alerta);
    }
}
