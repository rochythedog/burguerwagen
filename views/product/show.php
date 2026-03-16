<div class="container my-5 pt-5">
    <div class="row align-items-center">
        <div class="col-md-6 text-center mb-4 mb-md-0">
            <?php if (!empty($product['imagen'])): ?>
                <img src="<?= htmlspecialchars($product['imagen']) ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['nombre']) ?>" style="max-height: 400px;">
            <?php else: ?>
                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                    <span class="text-muted">Sin imagen</span>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($product['nombre']) ?></h1>
            <p class="lead text-muted mb-4"><?= htmlspecialchars($product['descripcion']) ?></p>
            
            <div class="d-flex align-items-center mb-4">
                <h2 class="text-primary fw-bold mb-0 me-4"><?= number_format($product['precio'], 2) ?> €</h2>
                <?php if(isset($product['stock']) && $product['stock'] > 0): ?>
                    <span class="badge bg-success">En stock</span>
                <?php else: ?>
                    <span class="badge bg-danger">Agotado</span>
                <?php endif; ?>
            </div>
            
            <button class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-bold"
                onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars($product['nombre'], ENT_QUOTES) ?>', <?= $product['precio'] ?>)">
                Añadir al carrito <i class="bi bi-cart-plus ms-2"></i>
            </button>
            
            <div class="mt-4">
                <a href="index.php?controller=product&action=index" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left"></i> Volver al menú
                </a>
            </div>
        </div>
    </div>
</div>
