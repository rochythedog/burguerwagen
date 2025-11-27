<?php
$userName = $_SESSION['user_name'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= APP_NAME ?> - <?= isset($title) ? htmlspecialchars($title) : '' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">BurguerWagen</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=product&action=index">Menu</a></li>
        <?php if ($userName): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=order&action=index">My orders</a></li>
        <?php endif; ?>
        <?php if ($userRole === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=adminOrder&action=index">Admin orders</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if ($userName): ?>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=profile">Hello, <?= htmlspecialchars($userName) ?></a></li>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=logout">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=login">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php?controller=user&action=register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-4">
