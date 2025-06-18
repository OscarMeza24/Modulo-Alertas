<?php
declare(strict_types=1);

namespace SafeAlert\Services;

use SafeAlert\Models\Alerta;
use SafeAlert\Database;
use SafeAlert\Exceptions\AlertaException;
use OpenAI\Client;

class AlertaIAService
{
    private Client $openai;
    private Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->openai = new Client([
            'api_key' => getenv('OPENAI_API_KEY'),
        ]);
    }

    /**
     * Analiza una alerta usando IA para determinar su prioridad y sugerencias
     */
    public function analizarAlerta(Alerta $alerta): array
    {
        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Analiza la siguiente alerta y proporciona una prioridad (alta, media, baja) y sugerencias de acción. Considera la urgencia y el impacto potencial.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Alerta: {$alerta->getDescripcion()}"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;
            
            // Parsear la respuesta para extraer prioridad y sugerencias
            $prioridad = $this->extraerPrioridad($content);
            $sugerencias = $this->extraerSugerencias($content);

            return [
                'prioridad' => $prioridad,
                'sugerencias' => $sugerencias
            ];
        } catch (\Exception $e) {
            throw new AlertaException(
                "Error al analizar alerta con IA: " . $e->getMessage(),
                AlertaException::ERROR_GENERAR_ALERTAS
            );
        }
    }

    /**
     * Genera alertas inteligentes basadas en un análisis de texto
     */
    public function generarAlertasInteligentes(string $texto): array
    {
        try {
            $response = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Analiza el siguiente texto y genera alertas relevantes con su descripción y prioridad.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Texto: {$texto}"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;
            
            // Parsear la respuesta para crear alertas
            $alertas = $this->parsearAlertas($content);
            
            // Guardar las alertas en la base de datos
            foreach ($alertas as $alerta) {
                $this->database->crearAlerta($alerta);
            }

            return $alertas;
        } catch (\Exception $e) {
            throw new AlertaException(
                "Error al generar alertas inteligentes: " . $e->getMessage(),
                AlertaException::ERROR_GENERAR_ALERTAS
            );
        }
    }

    private function extraerPrioridad(string $texto): string
    {
        // Implementar lógica para extraer la prioridad del texto
        // Esto puede ser más complejo dependiendo del formato de respuesta
        return 'media';
    }

    private function extraerSugerencias(string $texto): array
    {
        // Implementar lógica para extraer sugerencias del texto
        return [];
    }

    private function parsearAlertas(string $texto): array
    {
        // Implementar lógica para parsear alertas del texto
        return [];
    }
}
