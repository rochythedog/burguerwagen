<h1 class="mb-4">Order created</h1>

<p>Your order <strong>#<?= (int)$orderId ?></strong> has been created correctly.</p>
<p>Total: <strong><?= number_format($total, 2) ?> €</strong></p>

<a href="index.php?controller=order&action=index" class="btn btn-primary mt-3">See my orders</a>
