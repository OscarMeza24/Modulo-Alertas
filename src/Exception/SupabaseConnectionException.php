<?php
declare(strict_types=1);

namespace SafeAlert\Alertas\Exception;

class SupabaseConnectionException extends \Exception {
    public function __construct(string $message = "Error al conectar con Supabase", int $code = 0, ?\Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
