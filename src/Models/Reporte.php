<?php
namespace SafeAlert\Alertas;

class Reporte {
    private $id;
    private $fechaReporte;
    private $estadisticas;
    private $recomendaciones;

    public function __construct(
        $id = null,
        $fechaReporte = null,
        $estadisticas = null,
        $recomendaciones = null
    ) {
        $this->id = $id;
        $this->fechaReporte = $fechaReporte;
        $this->estadisticas = $estadisticas;
        $this->recomendaciones = $recomendaciones;
    }

    public function getId() {
        return $this->id;
    }

    public function getFechaReporte() {
        return $this->fechaReporte;
    }

    public function getEstadisticas() {
        return $this->estadisticas;
    }

    public function getRecomendaciones() {
        return $this->recomendaciones;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFechaReporte($fechaReporte) {
        $this->fechaReporte = $fechaReporte;
    }

    public function setEstadisticas($estadisticas) {
        $this->estadisticas = $estadisticas;
    }

    public function setRecomendaciones($recomendaciones) {
        $this->recomendaciones = $recomendaciones;
    }
}
