<?php

namespace SafeAlert\Models;

/**
 * Clase que representa un reporte de alerta en el sistema
 */
class ReporteAlertas {
    /** @var int|null Identificador único del reporte */
    private $id;
    /** @var int|null Identificador de la alerta asociada */
    private $idAlerta;
    /** @var string Descripción del reporte */
    private $descripcion;
    /** @var string Fecha de creación del reporte */
    private $fechaReporte;
    /** @var int|null Identificador del usuario que reporta */
    private $idUsuarioReporta;
    /** @var string|null Evidencia relacionada al reporte */
    private $evidencia;
    /** @var string Estado actual del reporte */
    private $estado;

    /**
     * Constructor de la clase ReporteAlertas
     *
     * @param int|null $id Identificador único del reporte
     * @param int|null $idAlerta Identificador de la alerta asociada
     * @param string $descripcion Descripción del reporte
     * @param string $fechaReporte Fecha de creación del reporte
     * @param int|null $idUsuarioReporta Identificador del usuario que reporta
     * @param string|null $evidencia Evidencia relacionada al reporte
     * @param string $estado Estado actual del reporte
     */
    public function __construct(
        ?int $id = null,
        ?int $idAlerta = null,
        string $descripcion = '',
        string $fechaReporte = '',
        ?int $idUsuarioReporta = null,
        ?string $evidencia = null,
        string $estado = ''
    ) {
        $this->id = $id;
        $this->idAlerta = $idAlerta;
        $this->descripcion = $descripcion;
        $this->fechaReporte = $fechaReporte;
        $this->idUsuarioReporta = $idUsuarioReporta;
        $this->evidencia = $evidencia;
        $this->estado = $estado;
    }

    // Getters y Setters
    /**
     * Obtiene el identificador del reporte
     * @return int|null
     */
    public function getId(): ?int { return $this->id; }
    
    /**
     * Establece el identificador del reporte
     * @param int|null $id
     */
    public function setId(?int $id): void { $this->id = $id; }

    /**
     * Obtiene el identificador de la alerta asociada
     * @return int|null
     */
    public function getIdAlerta(): ?int { return $this->idAlerta; }
    
    /**
     * Establece el identificador de la alerta asociada
     * @param int|null $idAlerta
     */
    public function setIdAlerta(?int $idAlerta): void { $this->idAlerta = $idAlerta; }

    /**
     * Obtiene la descripción del reporte
     * @return string
     */
    public function getDescripcion(): string { return $this->descripcion; }
    
    /**
     * Establece la descripción del reporte
     * @param string $descripcion
     */
    public function setDescripcion(string $descripcion): void { $this->descripcion = $descripcion; }

    /**
     * Obtiene la fecha de creación del reporte
     * @return string
     */
    public function getFechaReporte(): string { return $this->fechaReporte; }
    
    /**
     * Establece la fecha de creación del reporte
     * @param string $fechaReporte
     */
    public function setFechaReporte(string $fechaReporte): void { $this->fechaReporte = $fechaReporte; }

    /**
     * Obtiene el identificador del usuario que reporta
     * @return int|null
     */
    public function getIdUsuarioReporta(): ?int { return $this->idUsuarioReporta; }
    
    /**
     * Establece el identificador del usuario que reporta
     * @param int|null $idUsuarioReporta
     */
    public function setIdUsuarioReporta(?int $idUsuarioReporta): void { $this->idUsuarioReporta = $idUsuarioReporta; }

    /**
     * Obtiene la evidencia relacionada al reporte
     * @return string|null
     */
    public function getEvidencia(): ?string { return $this->evidencia; }
    
    /**
     * Establece la evidencia relacionada al reporte
     * @param string|null $evidencia
     */
    public function setEvidencia(?string $evidencia): void { $this->evidencia = $evidencia; }

    /**
     * Obtiene el estado actual del reporte
     * @return string
     */
    public function getEstado(): string { return $this->estado; }
    
    /**
     * Establece el estado actual del reporte
     * @param string $estado
     */
    public function setEstado(string $estado): void { $this->estado = $estado; }
}
