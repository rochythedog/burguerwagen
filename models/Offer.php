<?php

class Offer
{
    private ?int $id = null;
    private ?string $nombre = null;
    private ?float $valor = null;
    private ?bool $es_porcentaje = true;
    private ?string $inicio = null;
    private ?string $fin = null;
    private ?bool $activa = true;

    public function __construct() {}

    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getValor(): ?float { return $this->valor; }
    public function isEsPorcentaje(): ?bool { return $this->es_porcentaje; }
    public function getInicio(): ?string { return $this->inicio; }
    public function getFin(): ?string { return $this->fin; }
    public function isActiva(): ?bool { return $this->activa; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setNombre(?string $nombre): void { $this->nombre = $nombre; }
    public function setValor(?float $valor): void { $this->valor = $valor; }
    public function setEsPorcentaje(?bool $es_porcentaje): void { $this->es_porcentaje = $es_porcentaje; }
    public function setInicio(?string $inicio): void { $this->inicio = $inicio; }
    public function setFin(?string $fin): void { $this->fin = $fin; }
    public function setActiva(?bool $activa): void { $this->activa = $activa; }
}
