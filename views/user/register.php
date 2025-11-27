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
    <label class="form-label" for="name">First name</label>
    <input class="form-control" type="text" name="name" id="name" required>
  </div>
  <div class="mb-3">
    <label class="form-label" for="last_name">Last name</label>
    <input class="form-control" type="text" name="last_name" id="last_name">
  </div>
  <div class="mb-3">
    <label class="form-label" for="email">Email</label>
    <input class="form-control" type="email" name="email" id="email" required>
  </div>
  <div class="mb-3">
    <label class="form-label" for="password">Password</label>
    <input class="form-control" type="password" name="password" id="password" required>
  </div>
  <div class="mb-3">
    <label class="form-label" for="password2">Repeat password</label>
    <input class="form-control" type="password" name="password2" id="password2" required>
  </div>
  <button class="btn btn-primary" type="submit">Create account</button>
</form>
