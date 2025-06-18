<?php

class Notificaciones {
    private $id;
    private $id_alerta;
    private $id_usuario;
    private $mensaje;
    private $fecha_envio;
    private $estado;
    private $tiponotification;

    public function __construct($id = null, $id_alerta = null, $id_usuario = null, $mensaje = '', $fecha_envio = '', $estado = '', $tiponotification = '') {
        $this->id = $id;
        $this->id_alerta = $id_alerta;
        $this->id_usuario = $id_usuario;
        $this->mensaje = $mensaje;
        $this->fecha_envio = $fecha_envio;
        $this->estado = $estado;
    }

    // Getters y Setters
    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getIdAlerta() { return $this->id_alerta; }
    public function setIdAlerta($id_alerta) { $this->id_alerta = $id_alerta; }

    public function getIdUsuario() { return $this->id_usuario; }
    public function setIdUsuario($id_usuario) { $this->id_usuario = $id_usuario; }

    public function getMensaje() { return $this->mensaje; }
    public function setMensaje($mensaje) { $this->mensaje = $mensaje; }

    public function getFechaEnvio() { return $this->fecha_envio; }
    public function setFechaEnvio($fecha_envio) { $this->fecha_envio = $fecha_envio; }

    public function getEstado() { return $this->estado; }
    public function setEstado($estado) { $this->estado = $estado; }

    public function getTiponotification() {
        return $this->tiponotification;
    }

    public function setTiponotification($tiponotification) {
        $this->tiponotification = $tiponotification;
    }
}
