// clase del carrito
class Cart {
    constructor(storageKey = 'bw_cart') {
        this.storageKey = storageKey;
    }

    load() {
        const raw = localStorage.getItem(this.storageKey);
        if (!raw) return [];
        try {
            return JSON.parse(raw);
        } catch (e) {
            return [];
        }
    }

    save(items) {
        localStorage.setItem(this.storageKey, JSON.stringify(items));
    }

    clear() {
        localStorage.removeItem(this.storageKey);
    }

    addItem(productId, name, price) {
        // guardar en localStorage
        const items = this.load();
        const existing = items.find(item => item.product_id === productId);

        if (existing) {
            existing.quantity += 1;
        } else {
            items.push({
                product_id: productId,
                name: name,
                unit_price: price,
                quantity: 1
            });
        }

        this.save(items);
        window.location.href = 'index.php?controller=order&action=cart';
    }

    getTotalItems() {
        return this.load().reduce((sum, item) => sum + item.quantity, 0);
    }

    updateBadge() {
        // actualizar el contador del icono del carrito
        const badge = document.getElementById('cart-badge');
        if (!badge) return;
        const total = this.getTotalItems();
        if (total > 0) {
            badge.textContent = total;
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }
    }

    attachToForm(formId, fieldName = 'cart_json') {
        const form = document.getElementById(formId);
        if (!form) return;

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = fieldName;
        hidden.value = JSON.stringify(this.load());
        form.appendChild(hidden);
    }
}

// clase para filtrar productos por categoria
class ProductFilter {
    constructor(products, containerId) {
        this.products = products;
        this.containerId = containerId;
    }

    filter(categoryId) {
        if (categoryId == 0) {
            return this.products;
        }
        return this.products.filter(p => p.categoria_id == categoryId);
    }

    render(categoryId) {
        const filtered = this.filter(categoryId);
        const container = document.getElementById(this.containerId);
        if (!container) return;

        if (filtered.length === 0) {
            container.innerHTML = '<div class="col-12 text-center text-muted py-5">No hay productos en esta categoría.</div>';
            return;
        }

        container.innerHTML = filtered.map(product => `
            <div class="col-md-4 mb-4">
                <div class="card h-100 text-center p-3">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-arrow-right"></i></button>
                    </div>
                    <h3 class="card-title mt-3">${product.nombre.toUpperCase()}</h3>
                    <div class="my-4" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                        ${product.imagen
                            ? `<img src="${product.imagen}" class="img-fluid" alt="${product.nombre}" style="max-height: 100%;">`
                            : '<div class="text-muted">Sin imagen</div>'}
                    </div>
                    <div class="mt-auto">
                        <div class="d-inline-block bg-success text-white rounded-circle p-2 mb-3" style="width: 40px; height: 40px; line-height: 25px;">C</div>
                        <p class="price-tag">${product.nombre} desde ${parseFloat(product.precio).toFixed(2)}€</p>
                        <a href="index.php?controller=product&action=show&id=${product.id}" class="btn btn-cta w-100">Pedir</a>
                    </div>
                </div>
            </div>
        `).join('');
    }
}

// carrito global
const cart = new Cart();

// actualizar el badge del carrito en cada pagina
document.addEventListener('DOMContentLoaded', function() {
    cart.updateBadge();
});

// funciones que usan el carrito, las vistas las llaman directamente
function addToCart(productId, name, price) {
    cart.addItem(productId, name, price);
}

function attachCartToForm(formId, fieldName = 'cart_json') {
    cart.attachToForm(formId, fieldName);
}
