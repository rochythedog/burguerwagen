<h1 class="mb-4"><?= htmlspecialchars($title) ?></h1>

<?php if (empty($orders)): ?>
  <p class="text-muted">You do not have any orders yet.</p>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr><th>ID</th><th>Date</th><th>Status</th><th>Total</th></tr>
      </thead>
      <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= $o['id'] ?></td>
          <td><?= $o['fecha'] ?></td>
          <td><?= $o['estado'] ?></td>
          <td><?= number_format($o['total'], 2) ?> €</td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
