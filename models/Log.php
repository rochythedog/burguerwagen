<?php

class Log
{
    private ?int $id = null;
    private ?int $usuario_id = null;
    private ?string $tipo = null;
    private ?string $accion = null;
    private ?string $timestamp = null;

    public function __construct() {}

    public function getId(): ?int { return $this->id; }
    public function getUsuarioId(): ?int { return $this->usuario_id; }
    public function getTipo(): ?string { return $this->tipo; }
    public function getAccion(): ?string { return $this->accion; }
    public function getTimestamp(): ?string { return $this->timestamp; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setUsuarioId(?int $usuario_id): void { $this->usuario_id = $usuario_id; }
    public function setTipo(?string $tipo): void { $this->tipo = $tipo; }
    public function setAccion(?string $accion): void { $this->accion = $accion; }
    public function setTimestamp(?string $timestamp): void { $this->timestamp = $timestamp; }
}
