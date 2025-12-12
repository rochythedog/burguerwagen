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
                <p class="mb-1"><strong>Provincia:</strong> <?= htmlspecialchars($order->getProvincia()) ?></p>
                <p class="mb-1"><strong>Localidad:</strong> <?= htmlspecialchars($order->getLocalidad()) ?></p>
                <p class="mb-0"><strong>Dirección:</strong> <?= htmlspecialchars($order->getDireccion()) ?></p>
            </div>

            <?php if (isset($productos)): ?>
                <div class="text-start mb-4">
                    <h3 class="h5 border-bottom pb-2 mb-3">Productos</h3>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($productos as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <div>
                                    <?php if(isset($item->producto->imagen)): ?>
                                        <img src="<?= $item->producto->imagen ?>" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php endif; ?>
                                    <?= htmlspecialchars($item->producto->nombre) ?>
                                    <span class="text-muted small">x<?= $item->getUnidades() ?></span>
                                </div>
                                <span class="fw-bold"><?= number_format($item->producto->precio * $item->getUnidades(), 2) ?> €</span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-top mt-2 pt-2">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary fs-5"><?= number_format($order->getCoste(), 2) ?> €</span>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>

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
