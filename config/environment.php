<?php
declare(strict_types=1);

namespace SafeAlert\Config;

use Dotenv\Dotenv;
use UnexpectedValueException;

/**
 * Clase para manejo de configuración del entorno
 */
class Environment
{
    private static ?Environment $instance = null;
    private ?Dotenv $dotenv = null;

    private function __construct()
    {
        $this->loadEnvironment();
    }

    public static function getInstance(): Environment
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadEnvironment(): void
    {
        try {
            $this->dotenv = Dotenv::createImmutable(__DIR__ . '/../');
            $this->dotenv->load();
        } catch (UnexpectedValueException $e) {
            throw new UnexpectedValueException('Error al cargar el archivo .env: ' . $e->getMessage());
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->dotenv === null) {
            throw new UnexpectedValueException('No se ha inicializado el entorno');
        }
        return getenv($key) ?: $default;
    }
}
