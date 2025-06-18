<?php
namespace SafeAlert\Alertas;

use SafeAlert\Alertas\Reporte;
use SafeAlert\Database;
use PDO;

class ReporteService {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function generarReporte($fechaInicio, $fechaFin) {
        // Obtener alertas resueltas en el período
        $alertasResueltas = $this->obtenerAlertasResueltas($fechaInicio, $fechaFin);
        
        // Calcular estadísticas
        $estadisticas = $this->calcularEstadisticas($alertasResueltas);
        
        return [
            'estadisticas' => $estadisticas,
            'recomendaciones' => $this->generarRecomendaciones($estadisticas)
        ];
    }
    
    private function obtenerAlertasResueltas($fechaInicio, $fechaFin) {
        try {
            $sql = "alertas?select=*,productos!inner(precio_unitario,cantidad_stock,nombre)&fecha_creacion.gte=$fechaInicio&fecha_creacion.lte=$fechaFin&estado=eq.resuelta";
            $response = $this->db->query($sql);
            
            return json_decode($response['body'], true) ?? [];
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener alertas resueltas: ' . $e->getMessage(), 0, $e);
        }
    }
    
    public function crearReporte($data) {
        try {
            $sql = "reportes";
            $response = $this->db->query($sql, $data);
                
            $result = json_decode($response['body'], true);
            return [
                'id' => $result[0]['id'],
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'fecha_creacion' => date('Y-m-d H:i:s')
            ];
        } catch (\Exception $e) {
            throw new \Exception('Error al crear reporte: ' . $e->getMessage(), 0, $e);
        }
    }
    
    private function calcularEstadisticas($alertasResueltas) {
        $productosSalvados = 0;
        $dineroAhorrado = 0;
        $kgDesperdicioEvitado = 0;
        
        foreach ($alertasResueltas as $alerta) {
            $productosSalvados++;
            $dineroAhorrado += ($alerta['precio_unitario'] ?? 0) * ($alerta['cantidad_stock'] ?? 0);
            $kgDesperdicioEvitado += ($alerta['cantidad_stock'] ?? 0) * 0.5; // Estimación de 0.5kg por unidad
        }
        
        return [
            'productos_salvados' => $productosSalvados,
            'dinero_ahorrado' => $dineroAhorrado,
            'kg_desperdicio_evitado' => $kgDesperdicioEvitado
        ];
    }
    
    private function generarRecomendaciones($estadisticas) {
        $recomendaciones = [];
        
        if ($estadisticas['productos_salvados'] > 10) {
            $recomendaciones[] = [
                'tipo' => 'exito',
                'mensaje' => "¡Excelente trabajo! Has salvado {$estadisticas['productos_salvados']} productos del desperdicio.",
                'accion' => 'Continuar con las buenas prácticas'
            ];
        }
        
        return $recomendaciones;
    }

    public function obtenerReportes() {
        try {
            // Get all reports
            $result = $this->db->query("SELECT * FROM reportes ORDER BY created_at DESC");
            $reports = $result['body'] ?? [];
            
            // Convert each report to Reporte object
            $reportObjects = array_map(function($report) {
                // Ensure we have valid data
                if (!is_array($report)) {
                    throw new \Exception('Invalid report data format');
                }
                
                // Convert stringified JSON fields back to PHP arrays/objects
                $estadisticas = $report['estadisticas'] ? json_decode($report['estadisticas'], true) : null;
                $recomendaciones = $report['recomendaciones'] ? json_decode($report['recomendaciones'], true) : null;
                
                // Ensure we have required fields
                $id = isset($report['id']) ? $report['id'] : null;
                $fechaReporte = isset($report['fecha_reporte']) ? $report['fecha_reporte'] : null;
                
                return new Reporte(
                    $id,
                    $fechaReporte,
                    $estadisticas,
                    $recomendaciones
                );
            }, $reports);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            error_log("Error fetching reports: " . $e->getMessage());
            return [];
        }

        // Get all unique users needed
        $userIds = array_unique(array_column($reports, 'id_usuario_reporta'));
        if (!empty($userIds)) {
            $usersResponse = $this->db->query("usuarios?id=in.($userIds)&select=*", []);
            $users = array_column($usersResponse['body'] ?? [], null, 'id');
        }

        // Get all unique alert IDs
        $alertIds = array_unique(array_column($reports, 'id_alerta'));
        if (!empty($alertIds)) {
            $alertsResponse = $this->db->query("alertas?id=in.($alertIds)&select=*", []);
            $alerts = array_column($alertsResponse['body'] ?? [], null, 'id');
        }

        // Combine data
        $result = [];
        foreach ($reports as $report) {
            $report['alerta_descripcion'] = $alerts[$report['id_alerta']]['descripcion'] ?? null;
            $report['usuario_reporta'] = $users[$report['id_usuario_reporta']]['nombre'] ?? null;
            $result[] = $report;
        }

        return $result;
    }
}