<h1 class="mb-4"><?= htmlspecialchars($title) ?></h1>

<p>This admin page uses JavaScript and the API <code>api/orders.php</code> to manage orders.</p>

<div class="table-responsive">
  <table class="table table-bordered align-middle" id="ordersTable">
    <thead>
      <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Status</th>
        <th>Total</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <!-- Filled by JS -->
    </tbody>
  </table>
</div>

<script src="<?= BASE_URL ?>/public/js/admin.js"></script>
