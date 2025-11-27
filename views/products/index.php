<h1 class="mb-4"><?= htmlspecialchars($title) ?></h1>
<div class="row">
<?php foreach ($products as $p): ?>
  <div class="col-md-4 mb-3">
    <div class="card h-100">
      <?php if (!empty($p['imagen'])): ?>
        <img src="<?= htmlspecialchars($p['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['nombre']) ?>">
      <?php else: ?>
        <div class="bg-light text-muted text-center py-5">No image</div>
      <?php endif; ?>
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= htmlspecialchars($p['nombre']) ?></h5>
        <p class="card-text small text-muted"><?= htmlspecialchars($p['category_name'] ?? '') ?></p>
        <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($p['descripcion'] ?? '', 0, 120)) ?>...</p>
        <p class="fw-bold mb-3"><?= number_format($p['precio'], 2) ?> €</p>
        <a href="index.php?controller=product&action=show&id=<?= $p['id'] ?>" class="btn btn-primary">View product</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>
