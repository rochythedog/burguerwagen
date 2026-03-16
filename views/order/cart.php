<div class="container my-5 pt-5">
    <h1 class="section-title mb-4">Carrito de <strong>Compras</strong></h1>
    <div id="cart-content"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
});

function renderCart() {
    const items = cart.load();
    const container = document.getElementById('cart-content');

    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                <p class="lead">Tu carrito está vacío.</p>
                <a href="index.php?controller=product&action=index" class="btn btn-primary rounded-pill px-4">Ver Menú</a>
            </div>`;
        return;
    }

    let total = 0;
    items.forEach(item => { total += item.unit_price * item.quantity; });

    const rows = items.map((item, i) => `
        <tr>
            <td class="fw-bold">${item.name}</td>
            <td>${parseFloat(item.unit_price).toFixed(2)} €</td>
            <td>
                <span class="badge bg-light text-dark border">${item.quantity}</span>
            </td>
            <td class="fw-bold">${(item.unit_price * item.quantity).toFixed(2)} €</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="removeItem(${i})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');

    container.innerHTML = `
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Unidades</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>
        </div>
        <div class="row justify-content-end">
            <div class="col-md-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5 mb-0">Total</span>
                            <span class="h5 mb-0 fw-bold text-primary">${total.toFixed(2)} €</span>
                        </div>
                        <div class="d-grid gap-2">
                            <button onclick="checkout()" class="btn btn-primary py-2 fw-bold">Tramitar Pedido</button>
                            <button onclick="clearCart()" class="btn btn-outline-danger btn-sm">Vaciar carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
}

function removeItem(index) {
    const items = cart.load();
    items.splice(index, 1);
    cart.save(items);
    cart.updateBadge();
    renderCart();
}

function clearCart() {
    cart.clear();
    cart.updateBadge();
    renderCart();
}

function checkout() {
    const items = cart.load();
    if (items.length === 0) return;

    // sincronizar localStorage con la sesion php antes de tramitar
    fetch('api/cart_sync.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(items)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'index.php?controller=order&action=confirm';
        }
    });
}
</script>
