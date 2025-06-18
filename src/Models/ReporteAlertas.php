<?php
namespace SafeAlert\Models;

class ReporteAlertas {
    private $fechaReporte;
    private $metricas;

    public function getFechaReporte(): string {
        return $this->fechaReporte;
    }

    public function setFechaReporte(string $fechaReporte): self {
        $this->fechaReporte = $fechaReporte;
        return $this;
    }

    public function getMetricas(): array {
        return $this->metricas;
    }

    public function setMetricas(array $metricas): self {
        $this->metricas = $metricas;
        return $this;
    }
}
