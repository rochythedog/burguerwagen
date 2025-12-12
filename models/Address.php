<?php

class Address
{
    private ?int $id = null;
    private ?int $usuario_id = null;
    private ?string $alias = null;
    private ?string $direccion = null;
    private ?string $ciudad = null;
    private ?string $cp = null;
    private ?string $pais = null;

    public function __construct() {}

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getUsuarioId(): ?int { return $this->usuario_id; }
    public function getAlias(): ?string { return $this->alias; }
    public function getDireccion(): ?string { return $this->direccion; }
    public function getCiudad(): ?string { return $this->ciudad; }
    public function getCp(): ?string { return $this->cp; }
    public function getPais(): ?string { return $this->pais; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setUsuarioId(?int $usuario_id): void { $this->usuario_id = $usuario_id; }
    public function setAlias(?string $alias): void { $this->alias = $alias; }
    public function setDireccion(?string $direccion): void { $this->direccion = $direccion; }
    public function setCiudad(?string $ciudad): void { $this->ciudad = $ciudad; }
    public function setCp(?string $cp): void { $this->cp = $cp; }
    public function setPais(?string $pais): void { $this->pais = $pais; }
}
