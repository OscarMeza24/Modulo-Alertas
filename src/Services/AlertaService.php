<?php
namespace SafeAlert\Services;

use SafeAlert\Models\Alerta;
use SafeAlert\Models\NivelPrioridad;
use SafeAlert\Models\HistorialAlertas;
use SafeAlert\Exceptions\AlertaException;
use SafeAlert\Models\Notificaciones;
use SafeAlert\Database;
use GuzzleHttp\Client;

/**
 * Servicio para manejar operaciones relacionadas con alertas
 */

class AlertaService {
    private $db;
    private $baseUrl;
    
    public function __construct() {
        try {
            $this->db = Database::getInstance();
            $this->baseUrl = $this->db->getUrl();
        } catch (\Exception $e) {
            throw new AlertaException('Error al inicializar el cliente PostgREST: ' . $e->getMessage());
        }
    }

    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->baseUrl . '/' . $endpoint;
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->db->getKey()
        ]);
        
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            } elseif ($method === 'PATCH') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new AlertaException('Error en la petición HTTP: ' . curl_error($ch));
        }
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode >= 400) {
            throw new AlertaException("Error HTTP $httpCode");
        }
        
        return json_decode($response, true);
    }
    
    /**
     * Obtener todas las alertas activas
     * @return array Array de alertas
     */
    public function obtenerAlertasActivas() {
        try {
            $response = $this->makeRequest('GET', 'alertas?select=id,mensaje,estado,nivel_prioridad_id&estado=eq.activa&order=nivel_prioridad_id.desc');
            return $response;
            
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException(
                AlertaException::ERROR_OBTENER_ALERTAS . ": " . $e->getMessage(),
                AlertaException::ERROR_OBTENER_ALERTAS,
                $e
            );
        }
    }
    
    /**
     * Crear una nueva alerta
     * @param Alerta $alerta Instancia de Alerta
     * @return mixed ID de la alerta creada
     */
    public function crearAlerta($alerta) {
        try {
            // Usar la estructura correcta de la base de datos
            $data = [
                'tipo_alerta' => 'manual',
                'mensaje' => $alerta['descripcion'],
                'nivel_prioridad_id' => $alerta['id_nivel_prioridad'],
                'estado' => 'activa'
            ];
            
            // Agregar producto_id si se proporciona
            if (isset($alerta['producto_id'])) {
                $data['producto_id'] = $alerta['producto_id'];
            }
            
            // Construir la URL completa
            $url = $this->db->getUrl() . '/rest/v1/alertas';
            
            $response = $this->makeRequest('POST', 'alertas', $data);
            return $response;
            $get_url = $url . '?mensaje=eq.' . urlencode($data['mensaje']) . '&estado=eq.' . urlencode($data['estado']);
            $get_result = \SafeAlert\Utils\Http::get($get_url);
            
            // Verificar el código de respuesta del GET
            if ($get_result['statusCode'] !== 200) {
                throw new \Exception("Error al obtener los datos de la alerta: " . $get_result['statusCode']);
            }
            
            // Procesar la respuesta del GET
            $body = $get_result['body'];
            $result = json_decode($body, true);
            
            // Verificar si recibimos datos
            if (!is_array($result) || empty($result)) {
                throw new \Exception("No se encontraron datos de la alerta creada");
            }
            
            // Retornar los datos de la alerta creada con nombres de campos correctos
            $alertaCreada = $result[0];
            return [
                'id' => $alertaCreada['id'],
                'descripcion' => $alertaCreada['mensaje'],
                'estado' => $alertaCreada['estado'],
                'nivel_prioridad_id' => $alertaCreada['nivel_prioridad_id']
            ];
            
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException(
                "Error al crear alerta: " . $e->getMessage(),
                \SafeAlert\Exceptions\AlertaException::ERROR_CREAR_ALERTA,
                $e
            );
        }
    }
    
    /**
     * Actualizar el estado de una alerta
     * @param int $alertaId ID de la alerta
     * @param string $estado Nuevo estado
     * @return bool Resultado de la operación
     */
    public function actualizarAlerta($alertaId, $estado) {
        try {
            // Construir la URL completa
            $url = $this->db->getUrl() . '/rest/v1/alertas?id=eq.' . $alertaId;
            
            $response = $this->makeRequest('PATCH', 'alertas?id=eq.' . $alertaId, ['estado' => $estado]);
            
            // Crear registro en historial
            $historial = new HistorialAlertas($alertaId, date('Y-m-d H:i:s'), $estado, $estado);
            $this->crearHistorialAlerta($historial);
            
            return true;
            
            return true;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException(
                "Error al actualizar alerta: " . $e->getMessage(),
                AlertaException::ERROR_ACTUALIZAR_ALERTA,
                $e
            );
        }
    }
    
    /**
     * Crear registro en historial de alertas
     * @param HistorialAlertas $historial Instancia de HistorialAlertas
     * @return bool Resultado de la operación
     */
    private function crearHistorialAlerta($historial) {
        try {
            $data = [
                'id_alerta' => $historial['id_alerta'],
                'fecha_actualizacion' => $historial['fecha_actualizacion'],
                'estado_anterior' => $historial['estado_anterior'],
                'estado_actual' => $historial['estado_actual']
            ];
            
            $response = $this->makeRequest('POST', 'historial_alertas', $data);
            
            return true;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException("Error al crear historial de alerta: " . $e->getMessage(), AlertaException::ERROR_CREAR_HISTORIAL_ALERTA, $e);
        }
    }
    
    /**
     * Crear una notificación relacionada con una alerta
     * @param Notificaciones $notificacion Instancia de Notificaciones
     * @return bool Resultado de la operación
     */
    public function crearNotificacion($notificacion) {
        try {
            $data = [
                'id_alerta' => $notificacion['id_alerta'],
                'id_usuario' => $notificacion['id_usuario'],
                'mensaje' => $notificacion['mensaje'],
                'fecha_envio' => $notificacion['fecha_envio'],
                'estado' => $notificacion['estado'],
                'tipo_notificacion' => $notificacion['tipo_notificacion']
            ];
            
            $response = $this->makeRequest('POST', 'notificaciones', $data);
            
            return true;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException("Error al crear notificación: " . $e->getMessage(), AlertaException::ERROR_CREAR_NOTIFICACION, $e);
        }
    }

    /**
     * Obtener notificaciones pendientes
     * @return array Array de notificaciones
     */
    public function obtenerNotificacionesPendientes() {
        try {
            $response = $this->makeRequest('GET', 'notificaciones?estado=eq.pendiente&order=fecha_envio.desc');
            return $response;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException("Error al obtener notificaciones: " . $e->getMessage(), AlertaException::ERROR_OBTENER_NOTIFICACIONES, $e);
        }
    }

    /**
     * Obtener productos con stock bajo
     * @return array Array de productos con stock bajo
     */
    private function obtenerProductosBajoStock() {
        try {
            // Definir el límite de stock bajo (puedes ajustar este valor según tus necesidades)
            $limiteStockBajo = 10; // Ajusta este valor según tu negocio
            
            // Construir la URL de la consulta
            $url = $this->db->getUrl() . '/rest/v1/productos?select=id,nombre,cantidad_stock&cantidad_stock=lt.' . $limiteStockBajo;
            
            $response = $this->makeRequest('GET', 'productos?select=id,nombre,cantidad_stock&cantidad_stock=lt.' . $limiteStockBajo);
            return $response;
            
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException(
                "Error al obtener productos con stock bajo: " . $e->getMessage(),
                \SafeAlert\Exceptions\AlertaException::ERROR_CONSULTA,
                $e
            );
        }
    }

    /**
     * Eliminar una alerta
     * @param int $alertaId ID de la alerta a eliminar
     * @return bool Resultado de la operación
     */
    public function eliminarAlerta($alertaId) {
        try {
            $response = $this->makeRequest('DELETE', 'alertas?id=eq.' . $alertaId);
            return true;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException(
                "Error al eliminar alerta: " . $e->getMessage(),
                \SafeAlert\Exceptions\AlertaException::ERROR_ELIMINAR_ALERTA,
                $e
            );
        }
    }

    /**
     * Generar alertas inteligentes basadas en datos
     * @return array Array de alertas generadas
     */
    public function generarAlertasInteligentes() {
        try {
            // Obtener productos con bajo stock
            $productosBajoStock = $this->obtenerProductosBajoStock();
            
            // Crear alertas para productos con bajo stock
            foreach ($productosBajoStock as $producto) {
                $alerta = new Alerta();
                $alerta->setDescripcion("Bajo stock para producto: " . $producto['nombre']);
                $alerta->setIdUsuario(1); // ID del sistema
                $alerta->setIdniveprioridad(2); // Nivel medio
                $alerta->setIdReporteAlerta(null);
                
                $this->crearAlerta($alerta);
            }
            
            // Obtener todas las alertas recién creadas
            return $this->obtenerAlertasActivas();
            
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException("Error al generar alertas inteligentes: " . $e->getMessage(), AlertaException::ERROR_GENERAR_ALERTAS, $e);
        }
    }

    /**
     * Obtener historial de alertas
     * @return array Array de registros de historial
     */
    public function obtenerHistorialAlertas() {
        try {
            $response = $this->makeRequest('GET', 'historial_alertas?select=*&order=fecha_actualizacion.desc');
            return $response;
        } catch (\Exception $e) {
            throw new \SafeAlert\Exceptions\AlertaException("Error al obtener historial de alertas: " . $e->getMessage(), AlertaException::ERROR_OBTENER_HISTORIAL, $e);
        }
    }
}