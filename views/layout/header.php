<?php
$userName = $_SESSION['user_name'] ?? null;
$userRole = $_SESSION['user_role'] ?? null;
$c = $_GET['controller'] ?? 'home';
$a = $_GET['action'] ?? 'index';
?>
<!doctype html>
  <html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= APP_NAME ?> - <?= isset($title) ? htmlspecialchars($title) : '' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="<?= BASE_URL ?>/public/css/styles.css?v=<?= time() ?>" rel="stylesheet">
    <style>
        .hero-section {
            background-image: url('<?= BASE_URL ?>/public/img/home-banner.webp') !important;
        }
        .vw-line {
            top: 105% !important;
        }
        .hero-title {
        }
        .vw-logo {
            height: 80px;
            width: auto;
            filter: drop-shadow(0 0 15px rgba(255,255,255,0.4));
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .vw-logo:hover {
            transform: scale(1.15);
        }
    </style>
</head>
<body>

<header class="vw-header">
    <div class="container-fluid px-5 position-relative">
        <!-- linea blanca -->
        <div class="vw-line"></div>

        <div class="d-flex justify-content-between align-items-center">
            <!-- menu izquierda -->
            <div class="d-flex align-items-center">
                <button class="btn text-white d-flex align-items-center gap-2 fw-bold border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mainMenu">
                    <i class="bi bi-list fs-3"></i> <span class="text-uppercase">Menú</span>
                </button>
            </div>

            <!-- logo -->
            <div class="text-center">
                <a class="navbar-brand d-block" href="<?= BASE_URL ?>/index.php">
                    <img src="<?= BASE_URL ?>/public/img/logo.webp" alt="BurguerWagen" class="vw-logo">
                </a>
            </div>
            
            <!-- iconos derecha -->
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-white"><i class="bi bi-search fs-5"></i></a>
                
                <!-- Icono Carrito -->
                <a href="index.php?controller=order&action=cart" class="text-white position-relative">
                    <i class="bi bi-cart3 fs-5"></i>
                    <?php 
                    $count = 0;
                    if(isset($_SESSION['carrito'])){
                        foreach($_SESSION['carrito'] as $element){
                            $count += $element['unidades'];
                        }
                    }
                    if($count > 0): 
                    ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            <?= $count ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if ($userName): ?>
                  <a href="index.php?controller=user&action=profile" class="text-white"><i class="bi bi-person-circle fs-5"></i></a>
                  <a href="index.php?controller=user&action=logout" class="text-white"><i class="bi bi-box-arrow-right fs-5"></i></a>
                <?php else: ?>
                  <a href="index.php?controller=user&action=login" class="text-white"><i class="bi bi-person fs-5"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- menu lateral -->
<div class="offcanvas offcanvas-start vw-offcanvas" tabindex="-1" id="mainMenu">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">MENÚ</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link text-white <?= ($c == 'home') ? 'text-decoration-underline' : '' ?>" href="index.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-white <?= ($c == 'product' && $a == 'index') ? 'text-decoration-underline' : '' ?>" href="index.php?controller=product&action=index">Menu</a>
        </li>
        <?php if ($userName): ?>
          <li class="nav-item">
            <a class="nav-link text-white <?= ($c == 'order' && $a == 'index') ? 'text-decoration-underline' : '' ?>" href="index.php?controller=order&action=index">My orders</a>
          </li>
        <?php endif; ?>
        <?php if ($userRole === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link text-white <?= ($c == 'adminOrder' && $a == 'index') ? 'text-decoration-underline' : '' ?>" href="index.php?controller=adminOrder&action=index">Admin orders</a>
          </li>
        <?php endif; ?>
    </ul>
  </div>
</div>

<main class="container-fluid p-0">
