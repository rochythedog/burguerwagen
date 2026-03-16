<div class="row">
  <div class="col-md-6">
    <?php if (!empty($product['imagen'])): ?>
      <img src="<?= htmlspecialchars($product['imagen']) ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($product['nombre']) ?>">
    <?php else: ?>
      <div class="bg-light text-muted text-center py-5 mb-3">No image</div>
    <?php endif; ?>
  </div>
  <div class="col-md-6">
    <h1><?= htmlspecialchars($product['nombre']) ?></h1>
    <p class="text-muted"><?= htmlspecialchars($product['category_name'] ?? '') ?></p>
    <p><?= nl2br(htmlspecialchars($product['descripcion'] ?? '')) ?></p>
    <p class="h4 fw-bold mb-4"><?= number_format($product['precio'], 2) ?> €</p>
    <a href="index.php?controller=order&action=add&id=<?= $product['id'] ?>" class="btn btn-success btn-lg">
      Add to cart
    </a>
    <a href="index.php?controller=product&action=index" class="btn btn-link">Back to menu</a>
  </div>
</div>
