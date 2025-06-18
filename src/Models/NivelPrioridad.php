<?php

namespace SafeAlert\Models;

class NivelPrioridad {
    private $id;
    private $nombre;
    private $descripcion;
    private $color;

    public function __construct($id = null, $nombre = '', $descripcion = '', $color = '') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->color = $color;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getColor() { return $this->color; }
    public function setColor($color) { $this->color = $color; }
}
