<div class="container my-5 pt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="section-title mb-1">Detalles del <strong>Pedido #<?= $order->getId() ?></strong></h1>
            <p class="text-muted">Realizado el <?= $order->getFecha() ?></p>
        </div>
        <div class="col-md-4 text-end">
            <a href="index.php?controller=order&action=index" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Volver a mis pedidos
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Datos del pedido y dirección -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Información del Pedido</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Nº Pedido:</strong> <span class="badge bg-primary">#<?= $order->getId() ?></span>
                    </p>
                    <p class="mb-2">
                        <strong>Estado:</strong> 
                        <?php 
                            $status = $order->getEstado();
                            $badgeClass = 'bg-secondary';
                            if($status == 'paid') $badgeClass = 'bg-warning text-dark';
                            elseif($status == 'preparing') $badgeClass = 'bg-info text-dark';
                            elseif($status == 'delivered') $badgeClass = 'bg-success';
                            elseif($status == 'cancelled') $badgeClass = 'bg-danger';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                    </p>
                    <p class="mb-2">
                        <strong>Fecha:</strong> <?= $order->getFecha() ?>
                    </p>
                    <p class="mb-2">
                        <strong>Moneda:</strong> <?= $order->getMoneda() ?>
                    </p>
                    <hr>
                    <p class="mb-0">
                        <strong class="text-primary">Total:</strong> <span class="h5 mb-0"><?= number_format($order->getTotal(), 2) ?> €</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Datos de envío -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Dirección de Envío</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($address)): ?>
                        <p class="mb-2">
                            <strong>Dirección:</strong><br>
                            <span><?= htmlspecialchars($address->getDireccion()) ?></span>
                        </p>
                        <p class="mb-2">
                            <strong>Localidad:</strong><br>
                            <span><?= htmlspecialchars($address->getCiudad()) ?></span>
                        </p>
                        <p class="mb-2">
                            <strong>Código Postal:</strong><br>
                            <span><?= htmlspecialchars($address->getCp()) ?></span>
                        </p>
                        <p class="mb-0">
                            <strong>País:</strong><br>
                            <span><?= htmlspecialchars($address->getPais()) ?></span>
                        </p>
                    <?php else: ?>
                        <p class="text-muted">Información de dirección no disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos del pedido -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">Productos del Pedido</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (isset($productos) && !empty($productos)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 80px;">Imagen</th>
                                        <th>Producto</th>
                                        <th class="text-center" style="width: 80px;">Cantidad</th>
                                        <th class="text-end" style="width: 100px;">Precio Unit.</th>
                                        <th class="text-end" style="width: 100px;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $subtotal_general = 0;
                                        foreach ($productos as $item): 
                                            $subtotal = $item->getPrecioUnitario() * $item->getCantidad();
                                            $subtotal_general += $subtotal;
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if(isset($item->producto) && isset($item->producto->imagen)): ?>
                                                    <img src="<?= htmlspecialchars($item->producto->imagen) ?>" class="img-fluid rounded" style="max-height: 60px;">
                                                <?php else: ?>
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px; width: 60px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($item->producto->nombre ?? 'Producto') ?></strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark"><?= $item->getCantidad() ?></span>
                                            </td>
                                            <td class="text-end">
                                                <?= number_format($item->getPrecioUnitario(), 2) ?> €
                                            </td>
                                            <td class="text-end">
                                                <strong><?= number_format($subtotal, 2) ?> €</strong>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end">
                                            <strong>Total Pedido:</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-primary fs-5"><?= number_format($order->getTotal(), 2) ?> €</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="p-4 text-muted mb-0">No hay productos en este pedido.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
