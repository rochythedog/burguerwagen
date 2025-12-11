// cargar carrito con local storage
function loadCart() {
  const raw = localStorage.getItem('bw_cart');
  if (!raw) return [];
  try {
    return JSON.parse(raw);
  } catch (e) {
    return [];
  }
}

// guardar carrito
function saveCart(cart) {
  localStorage.setItem('bw_cart', JSON.stringify(cart));
}

// añadir producto a carrito
function addToCart(productId, name, price) {
  const cart = loadCart();
  const existing = cart.find(item => item.product_id === productId);

  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({
      product_id: productId,
      name: name,
      unit_price: price,
      quantity: 1
    });
  }

  saveCart(cart);
  alert('Product added to cart.');
}

// gestionar carrito con json
function attachCartToForm(formId, fieldName = 'cart_json') {
  const form = document.getElementById(formId);
  if (!form) return;

  const hidden = document.createElement('input');
  hidden.type = 'hidden';
  hidden.name = fieldName;
  hidden.value = JSON.stringify(loadCart());
  form.appendChild(hidden);
}
