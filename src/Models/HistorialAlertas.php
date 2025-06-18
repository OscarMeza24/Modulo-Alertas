<?php
namespace SafeAlert\Models;

class HistorialAlertas {
    private $id;
    private $idalerta;
    private $fechaactualizacion;
    private $estadoanterior;
    private $estadoactual;
    private $idusuarioactualizacion;

    public function __construct($id = null, $idalerta = null, $fechaactualizacion = '', $estadoanterior = '', $estadoactual = '', $idusuarioactualizacion = null) {
        $this->id = $id;
        $this->idalerta = $idalerta;
        $this->fechaactualizacion = $fechaactualizacion;
        $this->estadoanterior = $estadoanterior;
        $this->estadoactual = $estadoactual;
        $this->idusuarioactualizacion = $idusuarioactualizacion;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdalerta() { return $this->idalerta; }
    public function setIdalerta($idalerta) { $this->idalerta = $idalerta; }

    public function getFechaactualizacion() { return $this->fechaactualizacion; }
    public function setFechaactualizacion($fechaactualizacion) { $this->fechaactualizacion = $fechaactualizacion; }

    public function getEstadoanterior() { return $this->estadoanterior; }
    public function setEstadoanterior($estadoanterior) { $this->estadoanterior = $estadoanterior; }

    public function getEstadoactual() { return $this->estadoactual; }
    public function setEstadoactual($estadoactual) { $this->estadoactual = $estadoactual; }

    public function getIdusuarioactualizacion() { return $this->idusuarioactualizacion; }
    public function setIdusuarioactualizacion($idusuarioactualizacion) { $this->idusuarioactualizacion = $idusuarioactualizacion; }
}
