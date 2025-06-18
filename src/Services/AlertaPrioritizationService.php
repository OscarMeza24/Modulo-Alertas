<?php
namespace SafeAlert\Services;

use SafeAlert\Models\Alerta;
use SafeAlert\Models\NivelPrioridad;
use GuzzleHttp\Client;
use SafeAlert\Models\ReporteAlertas;
use SafeAlert\Exception\DatabaseException;

class AlertaPrioritizationService {
    private $openaiApiKey;
    private $database;

    public function __construct($openaiApiKey = null) {
        $this->openaiApiKey = $openaiApiKey ?? getenv('OPENAI_API_KEY');
        $this->database = \SafeAlert\Database::getInstance();
    }

    /**
     * Analiza una alerta usando IA para determinar su prioridad
     * @param Alerta $alerta
     * @return NivelPrioridad
     */
    public function analyzeAlertPriority(Alerta $alerta): NivelPrioridad {
        try {
            // Preparar el prompt para OpenAI
            $prompt = <<<EOT
            Analiza la siguiente alerta y determina su nivel de prioridad basado en:
            1. Urgencia
            2. Impacto potencial
            3. Recursos necesarios
            EOT;
            
            $prompt .= "\nAlerta: " . $alerta->getDescripcion() . "\nTipo: " . $alerta->getTipo() . "\nUbicación: " . $alerta->getUbicacion() . "\n\nResponde con un número del 1 al 5 donde:\n" .
            "1 = Baja prioridad\n" .
            "2 = Media-baja prioridad\n" .
            "3 = Media prioridad\n" .
            "4 = Media-alta prioridad\n" .
            "5 = Alta prioridad";

            // Llamar a OpenAI API
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => "Bearer {$this->openaiApiKey}",
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ]
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            $prioridad = $data['choices'][0]['message']['content'];

            // Convertir la respuesta a un nivel numérico
            $nivel = intval(trim($prioridad));
            
            if (!$nivel || $nivel < 1 || $nivel > 5) {
                throw new DatabaseException("Respuesta no válida de OpenAI: " . $prioridad);
            }

            // Buscar el nivel de prioridad correspondiente en la base de datos
            $nivelPrioridad = $this->database->obtenerPrioridades();
            $nivel = null;
            foreach ($nivelPrioridad as $p) {
                if ($p['nivel'] == $nivel) {
                    $nivel = $p;
                    break;
                }
            }

            if (!$nivel) {
                throw new DatabaseException("No se encontró nivel de prioridad válido");
            }

            return new NivelPrioridad($nivel);
        } catch (\Exception $e) {
            throw new DatabaseException("Error al analizar prioridad: " . $e->getMessage());
        }
    }

    /**
     * Obtiene todas las alertas priorizadas
     * @return array
     */
    public function obtenerAlertasPriorizadas(): array {
        try {
            $client = new \GuzzleHttp\Client([
                'base_uri' => $this->database->getUrl(),
                'headers' => [
                    'apikey' => $this->database->getKey(),
                    'Authorization' => 'Bearer ' . $this->database->getKey(),
                    'Content-Type' => 'application/json'
                ]
            ]);

            // Get alerts with their priority levels
            $response = $client->request('GET', '/rest/v1/alertas', [
                'query' => [
                    'select' => '*,niveles_prioridad!nivel_prioridad_id(nombre,peso)',
                    'order' => 'niveles_prioridad.peso.desc'
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            
            return array_map(function($row) {
                $alerta = new Alerta();
                $alerta->setId($row['id']);
                $alerta->setDescripcion($row['mensaje']);
                $alerta->setFechaCreacion($row['fecha_creacion']);
                $alerta->setIdniveprioridad($row['niveles_prioridad']['id']);
                return $alerta;
            }, $result);
        } catch (\Exception $e) {
            throw new DatabaseException("Error al obtener alertas priorizadas: " . $e->getMessage());
        }
    }

    /**
     * Genera un reporte de métricas basado en el análisis de alertas
     * @param array $alertas
     * @return ReporteAlertas
     */
    public function generateMetricsReport(array $alertas): ReporteAlertas
    {
        $reporte = new ReporteAlertas();
        $reporte->setFechaReporte(date('Y-m-d H:i:s'));
        
        // Calcular métricas
        $prioridades = array_count_values(array_map(function($a) {
            return $this->analyzeAlertPriority($a)->getNombre();
        }, $alertas));

        $reporte->setMetricas([
            'total_alertas' => count($alertas),
            'prioridades' => $prioridades,
            'promedio_urgencia' => count($alertas) > 0 ? array_sum(array_values($prioridades)) / count($alertas) : 0
        ]);
        return $reporte;
    }
}
