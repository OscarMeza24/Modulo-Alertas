<?php
namespace SafeAlert\Config;

class Environment {
    private static $instance = null;
    private $config = [];

    private function __construct() {
        $this->loadEnvironment();
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment() {
        $envFile = __DIR__ . '/../../.env';
        if (!file_exists($envFile)) {
            throw new \Exception('Archivo .env no encontrado');
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            if (!empty($key) && !empty($value)) {
                $this->config[$key] = $value;
            }
        }
    }

    public function get(string $key, $default = null) {
        return $this->config[$key] ?? $default;
    }

    private function __clone() {}
    public function __wakeup() {}
}
