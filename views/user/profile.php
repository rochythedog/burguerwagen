<h1 class="mb-4">My account</h1>

<div class="mb-4">
  <h4>User info</h4>
  <p><strong>Name:</strong> <?= htmlspecialchars($user['nombre']) ?> <?= htmlspecialchars($user['apellidos']) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
</div>

<div class="mb-4">
  <h4>Addresses</h4>
  <?php if (empty($addresses)): ?>
    <p class="text-muted">No addresses saved.</p>
  <?php else: ?>
    <ul class="list-group">
      <?php foreach ($addresses as $a): ?>
        <li class="list-group-item">
          <strong><?= htmlspecialchars($a['alias'] ?: 'No alias') ?></strong><br>
          <?= htmlspecialchars($a['direccion']) ?>,
          <?= htmlspecialchars($a['cp']) ?> <?= htmlspecialchars($a['ciudad']) ?>
          (<?= htmlspecialchars($a['pais']) ?>)
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
