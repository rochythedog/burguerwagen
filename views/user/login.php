<h1 class="mb-4"><?= htmlspecialchars($title) ?></h1>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
  <ul class="mb-0">
    <?php foreach ($errors as $e): ?>
      <li><?= htmlspecialchars($e) ?></li>
    <?php endforeach; ?>
  </ul>
</div>
<?php endif; ?>

<form method="post">
  <div class="mb-3">
    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="email" name="email" id="email" required>
  </div>
  <div class="mb-3">
    <label class="form-label" for="password">Password</label>
    <input class="form-control" type="password" name="password" id="password" required>
  </div>
  <button class="btn btn-primary" type="submit">Login</button>
</form>
