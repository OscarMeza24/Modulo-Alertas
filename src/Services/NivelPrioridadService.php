<?php

namespace SafeAlert\Services;

use SafeAlert\Database;
use SafeAlert\Exceptions\AlertaException;

class NivelPrioridadService {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function obtenerNivelesPrioridad() {
        try {
            $url = $this->db->getUrl() . '/rest/v1/niveles_prioridad';
            $headers = [
                'apikey' => $this->db->getKey(),
                'Authorization' => 'Bearer ' . $this->db->getKey(),
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::get($url, $headers);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException(
                AlertaException::ERROR_OBTENER_PRIORIDADES . ": " . $e->getMessage(),
                AlertaException::ERROR_OBTENER_PRIORIDADES,
                $e
            );
        }
    }

    public function crearNivelPrioridad($nombre, $color_hex = '#FF0000', $dias_antes_vencimiento = 0) {
        try {
            // Validar los parámetros
            if (empty($nombre)) {
                throw new AlertaException(
                    AlertaException::ERROR_PARAMETRO_REQUERIDO . "nombre",
                    AlertaException::ERROR_PARAMETRO_REQUERIDO
                );
            }

            $data = [
                'nombre' => $nombre,
                'color_hex' => $color_hex,
                'dias_antes_vencimiento' => $dias_antes_vencimiento
            ];

            // Construir la URL completa
            $url = $this->db->getUrl() . '/rest/v1/niveles_prioridad';
            
            // Hacer la petición POST para crear el nivel de prioridad
            $response = \SafeAlert\Utils\Http::post($url, $data, [
                'apikey' => $this->db->getKey(),
                'Authorization' => 'Bearer ' . $this->db->getKey(),
                'Content-Type' => 'application/json'
            ]);
            
            // Verificar el código de respuesta
            if ($response['statusCode'] !== 201) {
                throw new AlertaException(
                    AlertaException::ERROR_CREAR_PRIORIDAD . " (" . $response['statusCode'] . ")",
                    AlertaException::ERROR_CREAR_PRIORIDAD
                );
            }
            
            // Decodificar la respuesta
            $nivelCreado = json_decode($response['body'], true);
            
            return $nivelCreado;
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException(
                "Error al crear nivel de prioridad: " . $e->getMessage(),
                AlertaException::ERROR_CREAR_ALERTA,
                $e
            );
        }
    }

    /**
     * Actualizar un nivel de prioridad existente
     * @param int $id ID del nivel a actualizar
     * @param array $data Datos a actualizar
     * @return array Datos del nivel actualizado
     */
    /**
     * Actualizar un nivel de prioridad existente
     * @param int $id ID del nivel a actualizar
     * @param array $data Datos a actualizar
     * @return array Datos del nivel actualizado
     */
    public function actualizarNivelPrioridad($id, $data) {
        try {
            $url = $this->db->getUrl() . '/rest/v1/niveles_prioridad?id=eq.' . $id;
            $headers = [
                'apikey' => $this->db->getKey(),
                'Authorization' => 'Bearer ' . $this->db->getKey(),
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation'
            ];
            
            $response = \SafeAlert\Utils\Http::patch($url, $data, $headers);
            
            if ($response['statusCode'] !== 200) {
                throw new AlertaException(
                    AlertaException::ERROR_ACTUALIZAR_PRIORIDAD . " (" . $response['statusCode'] . ")",
                    AlertaException::ERROR_ACTUALIZAR_PRIORIDAD
                );
            }
            
            return json_decode($response['body'], true)[0];
            
        } catch (\Exception $e) {
            throw new AlertaException(
                "Error al actualizar nivel de prioridad: " . $e->getMessage(),
                AlertaException::ERROR_ACTUALIZAR_PRIORIDAD,
                $e
            );
        }
    }

    /**
     * Eliminar un nivel de prioridad
     * @param int $id ID del nivel a eliminar
     * @return void
     */
    public function eliminarNivelPrioridad($id) {
        try {
            $url = $this->db->getUrl() . '/rest/v1/niveles_prioridad?id=eq.' . $id;
            $headers = [
                'apikey' => $this->db->getKey(),
                'Authorization' => 'Bearer ' . $this->db->getKey()
            ];
            
            $response = \SafeAlert\Utils\Http::delete($url, $headers);
            
            if ($response['statusCode'] !== 204) {
                throw new AlertaException(
                    AlertaException::ERROR_ELIMINAR_PRIORIDAD . " (" . $response['statusCode'] . ")",
                    AlertaException::ERROR_ELIMINAR_PRIORIDAD
                );
            }
        } catch (\Exception $e) {
            throw new AlertaException(
                AlertaException::ERROR_ELIMINAR_PRIORIDAD . " (" . $e->getMessage() . ")",
                AlertaException::ERROR_ELIMINAR_PRIORIDAD,
                $e
            );
        }
    }
}
