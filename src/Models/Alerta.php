<?php

namespace SafeAlert\Models;

class Alerta {
    private $id;
    private $descripcion;
    private $fechacreacion;
    private $estado;
    private $idusuario;
    private $idniveprioridad;
    private $idreportealerta;
    private $ubicacion;
    private $tipo;

    public function __construct($id = null, $descripcion = '', $fechacreacion = '', $estado = '', $idusuario = null, $idniveprioridad = null, $idreportealerta = null, $tipo = '') {
        $this->id = $id;
        $this->descripcion = $descripcion;
        $this->fechacreacion = $fechacreacion;
        $this->estado = $estado;
        $this->idusuario = $idusuario;
        $this->idniveprioridad = $idniveprioridad;
        $this->idreportealerta = $idreportealerta;
        $this->tipo = $tipo;
    }

    // Getters y Setters
    public function getTipo() { return $this->tipo; }
    public function setTipo($tipo) { $this->tipo = $tipo; }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getFechacreacion() { return $this->fechacreacion; }
    public function setFechacreacion($fechacreacion) { $this->fechacreacion = $fechacreacion; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getIdusuario() { return $this->idusuario; }
    public function setIdusuario($idusuario) { $this->idusuario = $idusuario; }

    public function getIdniveprioridad() { return $this->idniveprioridad; }
    public function setIdniveprioridad($idniveprioridad) { $this->idniveprioridad = $idniveprioridad; }

    public function getIdreportealerta() { return $this->idreportealerta; }
    public function setIdreportealerta($idreportealerta) { $this->idreportealerta = $idreportealerta; }

    public function getUbicacion() { return $this->ubicacion; }
    public function setUbicacion($ubicacion) { $this->ubicacion = $ubicacion; }
}
