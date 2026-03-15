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

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0 text-start">Nuestros mejores <strong>productos</strong> al mejor <strong>precio</strong></h2>
        <div>
            <select id="filterCategory" class="form-select" style="max-width: 200px;">
                <option value="0">Todos (<?= count($products) ?>)</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <p class="text-muted mb-5">Dinos cómo eres y te diremos qué menu va contigo.</p>

    <div class="row" id="productsContainer"></div>
</div>

<script>
// productos que vienen del servidor
const allProducts = <?= json_encode(array_values($products)) ?>;

// esperamos a que cargue main.js antes de usar las clases
document.addEventListener('DOMContentLoaded', function() {
    const productFilter = new ProductFilter(allProducts, 'productsContainer');

    // mostrar todos al entrar
    productFilter.render(0);

    // filtrar al cambiar el select
    document.getElementById('filterCategory').addEventListener('change', function() {
        productFilter.render(this.value);
    });
});
</script>

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
                <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2 mt-3">Ver otros productos</a>
            </div>
            <div class="col-md-6">
                <img src="<?= BASE_URL ?>/public/img/burguer-simpson.webp" class="img-fluid rounded shadow-lg" alt="BurguerWagen Promo">
            </div>
        </div>
    </div>
</div>

<div class="container my-5 py-5 text-center">
    <h2 class="section-title">Descubre todos nuestros <strong>productos</strong></h2>
    <a href="index.php?controller=product&action=index" class="btn btn-primary btn-lg">Ver todos los productos</a>
</div>
