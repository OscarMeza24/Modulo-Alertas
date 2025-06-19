<?php
declare(strict_types=1);
namespace SafeAlert;

use SafeAlert\Utils\Http;
use SafeAlert\Exceptions\AlertaException;

const PORTADOR = 'Bearer';

// El autoloader ya se carga automáticamente a través de composer
// Initialize the Supabase client
// The autoloader is already loaded automatically through composer

class Database {
    private static $instance = null;
    private $url;
    private $key;

    public function getUrl(): string {
        return $this->url;
    }

    public function getKey(): string {
        return $this->key;
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        try {
            $config = \SafeAlert\Config\Environment::getInstance();
            $this->url = $config->get('SUPABASE_URL');
            $this->key = $config->get('SUPABASE_KEY');
            
            if (empty($this->url)) {
                throw new AlertaException("SUPABASE_URL no está definido", AlertaException::ERROR_CONFIGURACION);
            }
            if (empty($this->key)) {
                throw new AlertaException("SUPABASE_KEY no está definido", AlertaException::ERROR_CONFIGURACION);
            }
        } catch (\Exception $e) {
            throw new AlertaException("Error al inicializar la base de datos: " . $e->getMessage(), AlertaException::ERROR_CONFIGURACION, $e);
        }
    }

    public function query($sql, $params = []) {
        try {
            $url = $this->url . '/rest/v1/' . $sql;
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            if (empty($params)) {
                return \SafeAlert\Utils\Http::get($url, $headers);
            } else {
                return \SafeAlert\Utils\Http::post($url, $params, $headers);
            }
        } catch (\Exception $e) {
            throw new AlertaException("Error en la consulta: " . $e->getMessage(), AlertaException::ERROR_CONSULTA, $e);
        }
    }

    public function update($table, $id, $data) {
        try {
            $url = $this->url . '/rest/v1/' . $table . '?id=eq.' . $id;
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            return \SafeAlert\Utils\Http::patch($url, $data, $headers);
        } catch (\Exception $e) {
            throw new AlertaException("Error al actualizar: " . $e->getMessage(), AlertaException::ERROR_ACTUALIZAR, $e);
        }
    }


    public function testConnection(): bool {
        try {
            $url = $this->url . '/rest/v1/niveles_prioridad?select=*';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::get($url, $headers);
            return !empty($response['body']);
        } catch (\Exception $e) {
            throw new AlertaException("Error al probar conexión: " . $e->getMessage(), AlertaException::ERROR_CONFIGURACION, $e);
        }
    }

    // CRUD methods
    public function obtenerPrioridades()
    {
        try {
            $url = $this->url . '/rest/v1/niveles_prioridad?select=*';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::get($url, $headers);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException("Error al obtener prioridades: " . $e->getMessage(), AlertaException::ERROR_CONSULTA, $e);
        }
    }
    
    public function obtenerAlertas(int $limit = 100, int $offset = 0)
    {
        try {
            $url = $this->url . '/rest/v1/alertas?select=*';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::get($url, $headers);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException("Error al obtener alertas: " . $e->getMessage(), AlertaException::ERROR_CONSULTA, $e);
        }
    }

    public function crearAlerta($alerta) {
        try {
$url = $this->url . '/rest/v1/alertas';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::post($url, $alerta, $headers);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException("Error al crear alerta: " . $e->getMessage(), AlertaException::ERROR_CONSULTA, $e);
        }
    }

    public function actualizarAlerta($id, $datos) {
        try {
            $url = $this->url . '/rest/v1/alertas?id=eq.' . $id;
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            return \SafeAlert\Utils\Http::patch($url, $datos, $headers);
        } catch (\Exception $e) {
            throw new AlertaException("Error al actualizar alerta: " . $e->getMessage(), AlertaException::ERROR_ACTUALIZAR, $e);
        }
    }

    public function eliminarAlerta($id) {
        try {
            $url = $this->url . '/rest/v1/alertas?id=eq.' . $id;
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            return \SafeAlert\Utils\Http::delete($url, $headers);
        } catch (\Exception $e) {
            throw new AlertaException("Error al eliminar alerta: " . $e->getMessage(), AlertaException::ERROR_ELIMINAR_ALERTA, $e);
        }
    }

    // Métodos para Productos
    public function obtenerProductos($filtro = []) {
        try {
            $url = $this->url . '/rest/v1/productos';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            if (!empty($filtro)) {
                $url .= '?';
                foreach ($filtro as $campo => $valor) {
                    $url .= $campo . '=eq.' . urlencode($valor) . '&';
                }
                $url = rtrim($url, '&');
            }
            
            return \SafeAlert\Utils\Http::get($url, $headers);
        } catch (\Exception $e) {
            throw new AlertaException("Error al obtener productos: " . $e->getMessage(), AlertaException::ERROR_OBTENER_PRODUCTOS, $e);
        }
    }

    // Métodos para Tipos de Alerta
    public function obtenerTiposAlerta() {
        try {
            $url = $this->url . '/rest/v1  /tipos_alerta?select=*';
            $headers = [
                'apikey' => $this->key,
                'Authorization' => PORTADOR . ' ' . $this->key,
                'Content-Type' => 'application/json'
            ];
            
            $response = \SafeAlert\Utils\Http::get($url, $headers);
            return json_decode($response['body'], true);
        } catch (\Exception $e) {
            throw new AlertaException("Error al obtener tipos de alerta: " . $e->getMessage(), AlertaException::ERROR_CONSULTA, $e);
        }
    }
}

