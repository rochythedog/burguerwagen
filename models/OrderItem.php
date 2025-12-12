<?php

class OrderItem
{
    private ?int $id = null;
    private ?int $pedido_id = null;
    private ?int $producto_id = null;
    private ?int $cantidad = null;
    private ?float $precio_unitario = null;

    public function __construct() {}

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getPedidoId(): ?int { return $this->pedido_id; }
    public function getProductoId(): ?int { return $this->producto_id; }
    public function getCantidad(): ?int { return $this->cantidad; }
    public function getPrecioUnitario(): ?float { return $this->precio_unitario; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setPedidoId(?int $pedido_id): void { $this->pedido_id = $pedido_id; }
    public function setProductoId(?int $producto_id): void { $this->producto_id = $producto_id; }
    public function setCantidad(?int $cantidad): void { $this->cantidad = $cantidad; }
    public function setPrecioUnitario(?float $precio_unitario): void { $this->precio_unitario = $precio_unitario; }
}
