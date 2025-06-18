<?php
namespace SafeAlert\Utils;

use SafeAlert\Exceptions\AlertaException;
use SafeAlert\Config\Environment;

class Http {
    public static function post($url, $data) {
        try {
            // Mostrar datos de la petición para debug
            echo "\nDetalles de la petición POST:\n";
            echo "URL: " . $url . "\n";
            echo "Datos: " . json_encode($data) . "\n";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'apikey: ' . Environment::getInstance()->get('SUPABASE_KEY'),
                'Authorization: Bearer ' . Environment::getInstance()->get('SUPABASE_KEY')
            ]);
            
            // Ejecutar la petición
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            // Mostrar detalles de la respuesta
            echo "\nDetalles de la respuesta:\n";
            echo "Código HTTP: " . $httpCode . "\n";
            echo "Error cURL: " . $error . "\n";
            echo "Respuesta: " . ($response === false ? 'Ninguna' : $response) . "\n";
            
            if ($response === false) {
                throw new DatabaseException("Error en la petición: " . $error);
            }
            
            curl_close($ch);
            
            return [
                'body' => $response,
                'statusCode' => $httpCode
            ];
            
        } catch (\Exception $e) {
            throw new DatabaseException("Error en la petición HTTP: " . $e->getMessage());
        }
    }

    public static function patch($url, $data, $headers = []) {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            
            if (empty($headers)) {
                $headers = [
                    'Content-Type' => 'application/json',
                    'apikey' => Environment::getInstance()->get('SUPABASE_KEY'),
                    'Authorization' => 'Bearer ' . Environment::getInstance()->get('SUPABASE_KEY')
                ];
            }
            
            $headerArray = [];
            foreach ($headers as $key => $value) {
                $headerArray[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            
            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if ($response === false) {
                throw new AlertaException('Error en la petición: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            return [
                'statusCode' => $statusCode,
                'body' => $response
            ];
        } catch (\Exception $e) {
            throw new AlertaException("Error en la petición HTTP: " . $e->getMessage());
        }
    }

    public static function get($url, $headers = []) {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            if (empty($headers)) {
                $headers = [
                    'Content-Type' => 'application/json',
                    'apikey' => Environment::getInstance()->get('SUPABASE_KEY'),
                    'Authorization' => 'Bearer ' . Environment::getInstance()->get('SUPABASE_KEY')
                ];
            }
            
            $headerArray = [];
            foreach ($headers as $key => $value) {
                $headerArray[] = $key . ': ' . $value;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            
            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if ($response === false) {
                throw new AlertaException('Error en la petición: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            return [
                'statusCode' => $statusCode,
                'body' => $response
            ];
        } catch (\Exception $e) {
            throw new AlertaException("Error en la petición HTTP: " . $e->getMessage());
        }
    }

    public static function delete($url, $headers = []) {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            
            if (!empty($headers)) {
                $headerArray = [];
                foreach ($headers as $key => $value) {
                    $headerArray[] = $key . ': ' . $value;
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
            }
            
            $response = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new AlertaException('Error en la petición: ' . curl_error($ch));
            }
            
            curl_close($ch);
            
            return [
                'statusCode' => $statusCode,
                'body' => $response
            ];
        } catch (\Exception $e) {
            throw new AlertaException("Error en la petición HTTP: " . $e->getMessage());
        }
    }
}
