<!-- banner principal -->
<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">BURGUER WAGEN</h1>
        <p class="hero-subtitle">El sabor que te mueve.</p>
        <div class="card bg-transparent border-white text-white p-4" style="border: 1px solid rgba(255,255,255,0.5); max-width: 400px;">
            <h3 class="mb-0">12,50€/menú</h3>
            <p class="mb-3">con My Way¹</p>
            <small class="d-block mb-2">Ofertas descargando la WagenApp</small>
            <small class="d-block">Disponible para IOS y Android</small>
        </div>
    </div>
</div>

<!-- seccion 2 -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0 text-start">Nuestros mejores <strong>productos</strong> al mejor <strong>precio</strong></h2>
        <div>
            <button class="btn btn-outline-primary me-2">Todos (<?= count($products) ?>)</button>
            <button class="btn btn-outline-secondary me-2">Carne</button>
            <button class="btn btn-outline-secondary">Pollo</button>
        </div>
    </div>
    <p class="text-muted mb-5">Dinos cómo eres y te diremos qué menu va contigo.</p>

    <!-- productos -->
    <div class="row">
    <?php foreach ($products as $p): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 text-center p-3">
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-arrow-right"></i></button>
            </div>
            <h3 class="card-title mt-3"><?= htmlspecialchars(strtoupper($p['nombre'])) ?></h3>
            
            <div class="my-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                <?php if (!empty($p['imagen'])): ?>
                    <img src="<?= htmlspecialchars($p['imagen']) ?>" class="img-fluid" alt="<?= htmlspecialchars($p['nombre']) ?>" style="max-height: 100%;">
                <?php else: ?>
                    <div class="text-muted">Sin imagen</div>
                <?php endif; ?>
            </div>

            <div class="mt-auto">
                <div class="d-inline-block bg-success text-white rounded-circle p-2 mb-3" style="width: 40px; height: 40px; line-height: 25px;">C</div>
                <p class="price-tag"><?= htmlspecialchars($p['nombre']) ?> desde <?= number_format($p['precio'], 2) ?>€</p>
                <a href="index.php?controller=product&action=show&id=<?= $p['id'] ?>" class="btn btn-cta w-100">Pedir</a>
            </div>
        </div>
      </div>
    <?php endforeach; ?>
    </div>
</div>

<!-- menu simpson -->
<div class="bg-primary text-white py-5 mt-5" style="background-color: var(--vw-dark-blue) !important;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="display-4 fw-bold mb-4">Menú especial Simpsons:</h2>
                <h3 class="h2 mb-4">Es posible tener lo que siempre has querido</h3>
                <p class="lead mb-4">Así que no te conformes con menos. Llévate tu menú Simpsons con todas las salsas y patatas gratis!.</p>
                <ul class="list-unstyled mb-5">
                    <li class="mb-2"><i class="bi bi-dash-lg me-2"></i> Patatas con bacon y salsa Simpson</li>
                    <li class="mb-2"><i class="bi bi-dash-lg me-2"></i> Bebida Simpson amarilla especial</li>
                    <li class="mb-2"><i class="bi bi-dash-lg me-2"></i> Helado Simpson con birutas de donut</li>
                    <li class="mb-2"><i class="bi bi-dash-lg me-2"></i> Un regalo en la caja con un muñeco de Simpson</li>
                </ul>
                <a href="#" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary">Pedir ahora</a>
                <br>
                <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2 mt-3">Ver otros productos<</a>
            </div>
            <div class="col-md-6">
                <!-- imagen simpson -->
                <img src="<?= BASE_URL ?>/public/img/burguer-simpson.webp" class="img-fluid rounded shadow-lg" alt="BurguerWagen Promo">
            </div>
        </div>
    </div>
</div>

<!-- ofertas -->
<div class="container my-5 py-5 text-center">
    <h2 class="section-title">Descubre nuestras <strong>ofertas</strong></h2>
    <a href="index.php?controller=product&action=index" class="btn btn-primary btn-lg">Ver todas las ofertas</a>
</div>
