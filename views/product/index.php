<div class="container my-5 pt-5">
    <div class="text-center mb-5">
        <h1 class="section-title">Nuestro <strong>Menú</strong></h1>
        <p class="text-muted">Descubre nuestra selección de hamburguesas y complementos</p>
    </div>

    <div class="row">
        <?php if (isset($products) && !empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3 border-0 shadow-sm">
                        <div class="d-flex justify-content-end">
                            <a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-circle">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        
                        <div class="my-4 d-flex align-items-center justify-content-center" style="height: 200px;">
                            <?php if (!empty($product['imagen'])): ?>
                                <img src="<?= htmlspecialchars($product['imagen']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['nombre']) ?>" style="max-height: 100%;">
                            <?php else: ?>
                                <div class="text-muted bg-light w-100 h-100 d-flex align-items-center justify-content-center rounded">Sin imagen</div>
                            <?php endif; ?>
                        </div>

                        <h3 class="card-title h5 mt-2"><?= htmlspecialchars($product['nombre']) ?></h3>
                        
                        <div class="mt-auto">
                            <p class="price-tag mb-3"><?= number_format($product['precio'], 2) ?> €</p>
                            <div class="d-grid gap-2">
                                <a href="index.php?controller=product&action=show&id=<?= $product['id'] ?>" class="btn btn-outline-primary rounded-pill">
                                    Ver detalles
                                </a>
                                <a href="index.php?controller=order&action=add&id=<?= $product['id'] ?>" class="btn btn-primary rounded-pill">
                                    Añadir al carrito
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-info">No hay productos disponibles en este momento.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
