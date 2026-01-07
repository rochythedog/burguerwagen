<?php if (isset($order)): ?>
<div class="container my-5 pt-5 text-center">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body p-5">
            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill display-1"></i>
            </div>
            <h1 class="h2 mb-3">¡Pedido Confirmado!</h1>
            <p class="text-muted mb-4">
                Tu pedido <strong>#<?= $order->getId() ?></strong> ha sido registrado correctamente.
            </p>
            
            <div class="bg-light p-4 rounded mb-4 text-start">
                <h3 class="h5 border-bottom pb-2 mb-3">Datos de envío</h3>
                <?php if (isset($address)): ?>
                    <p class="mb-1"><strong>País:</strong> <?= htmlspecialchars($address->getPais()) ?></p>
                    <p class="mb-1"><strong>Localidad:</strong> <?= htmlspecialchars($address->getCiudad()) ?></p>
                    <p class="mb-1"><strong>Código Postal:</strong> <?= htmlspecialchars($address->getCp()) ?></p>
                    <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($address->getDireccion()) ?></p>
                <?php else: ?>
                    <p class="text-muted">Información de dirección no disponible</p>
                <?php endif; ?>
            </div>

            <?php if (isset($productos) && !empty($productos)): ?>
                <div class="text-start mb-4">
                    <h3 class="h5 border-bottom pb-2 mb-3">Productos</h3>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($productos as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <div>
                                    <?php if(isset($item->producto) && isset($item->producto->imagen)): ?>
                                        <img src="<?= htmlspecialchars($item->producto->imagen) ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item->producto->nombre ?? 'Producto') ?>
                                    <span class="text-muted small">x<?= $item->getCantidad() ?></span>
                                </div>
                                <span class="fw-bold"><?= number_format($item->getPrecioUnitario() * $item->getCantidad(), 2) ?> €</span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-top mt-2 pt-2">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary fs-5"><?= number_format($order->getTotal(), 2) ?> €</span>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="bg-info bg-opacity-10 p-3 rounded mb-4">
                <p class="mb-0 text-muted small">
                    <strong>Estado:</strong> <?= ucfirst($order->getEstado()) ?><br>
                    <strong>Fecha del pedido:</strong> <?= $order->getFecha() ?>
                </p>
            </div>

            <a href="index.php" class="btn btn-primary rounded-pill px-4 py-2">Volver al inicio</a>
        </div>
    </div>
</div>
<?php else: ?>
<div class="container my-5 pt-5 text-center">
    <h1>Tu pedido no ha podido procesarse</h1>
    <a href="index.php" class="btn btn-primary mt-3">Volver al inicio</a>
</div>
<?php endif; ?>
