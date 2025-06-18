<?php
declare(strict_types=1);

namespace SafeAlert\Alertas\Exception;

class DatabaseException extends \Exception {
    public function __construct(string $message = "Error en la base de datos", int $code = 0, ?\Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
