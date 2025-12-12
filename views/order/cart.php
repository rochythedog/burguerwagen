<div class="container my-5 pt-5">
    <h1 class="section-title mb-4">Carrito de <strong>Compras</strong></h1>

    <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) >= 1): ?>
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_carrito = 0;
                        foreach($_SESSION['carrito'] as $indice => $elemento): 
                        $producto = $elemento['producto'];
                        $total_producto = $elemento['precio'] * $elemento['unidades'];
                        $total_carrito += $total_producto;
                    ?>
                    <tr>
                        <td style="width: 100px;">
                            <?php if($producto->imagen): ?>
                                <img src="<?= $producto->imagen ?>" class="img-fluid rounded" style="max-height: 60px;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 60px; width: 60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?controller=product&action=show&id=<?= $producto->id ?>" class="text-decoration-none fw-bold text-dark">
                                <?= $producto->nombre ?>
                            </a>
                        </td>
                        <td><?= number_format($elemento['precio'], 2) ?> €</td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= $elemento['unidades'] ?></span>
                        </td>
                        <td class="fw-bold"><?= number_format($total_producto, 2) ?> €</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5 mb-0">Total</span>
                            <span class="h5 mb-0 fw-bold text-primary"><?= number_format($total_carrito, 2) ?> €</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="index.php?controller=order&action=confirm" class="btn btn-primary py-2 fw-bold">Tramitar Pedido</a>
                            <a href="index.php?controller=order&action=delete_all" class="btn btn-outline-danger btn-sm">Vaciar carrito</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
            <p class="lead">Tu carrito está vacío.</p>
            <a href="index.php?controller=product&action=index" class="btn btn-primary rounded-pill px-4">Ver Menú</a>
        </div>
    <?php endif; ?>
</div>
