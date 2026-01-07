document.addEventListener('DOMContentLoaded', function() {
    loadData();
    setupEventListeners();
});

var appData = {
    orders: [],
    products: [],
    users: [],
    offers: [],
    logs: []
};

var currencyRate = 1;
var currentCurrency = 'EUR';

// --- CARGAR DATOS ---
async function loadData() {
    // Peticion a la API interna (service.php)
    // IMPORTANTE: Usamos api/service.php, no el controlador
    var response = await fetch('api/service.php?action=get_data');
    var data = await response.json();
    
    appData = data;
    // asegurar arrays
    appData.users = appData.users || [];
    renderOrders();
    renderProducts();
    if (appData.offers && appData.offers.length >= 0) {
        renderOffers();
    }
    renderLogs();
}

// --- MONEDA ---
async function updateCurrency(currency) {
    if (currency === 'EUR') {
        currencyRate = 1;
        currentCurrency = 'EUR';
        renderOrders();
        return;
    }
    
    // API externa
    var res = await fetch('https://open.er-api.com/v6/latest/EUR');
    var data = await res.json();
    
    if (data.rates[currency]) {
        currencyRate = data.rates[currency];
        currentCurrency = currency;
        renderOrders();
    } else {
        alert('Error con la moneda');
    }
}

// --- cargar PEDIDOS ---
function renderOrders() {
    var tbody = document.getElementById('ordersTableBody');
    tbody.innerHTML = '';

    var filterText = document.getElementById('orderFilterUser').value.toLowerCase();
    var sortType = document.getElementById('orderSort').value;

    // Filtrar
    var filtered = [];
    for (var i = 0; i < appData.orders.length; i++) {
        var o = appData.orders[i];
        var nombre = o.usuario_nombre ? o.usuario_nombre.toLowerCase() : '';
        var email = o.usuario_email ? o.usuario_email.toLowerCase() : '';
        var id = o.id.toString();

        if (nombre.includes(filterText) || email.includes(filterText) || id.includes(filterText)) {
            filtered.push(o);
        }
    }

    // Ordenar
    filtered.sort(function(a, b) {
        if (sortType === 'date_desc') {
            return new Date(b.fecha) - new Date(a.fecha);
        }
        if (sortType === 'date_asc') {
            return new Date(a.fecha) - new Date(b.fecha);
        }
        if (sortType === 'price_desc') {
            return parseFloat(b.total) - parseFloat(a.total);
        }
        if (sortType === 'price_asc') {
            return parseFloat(a.total) - parseFloat(b.total);
        }
        return 0;
    });

    // mostrar tabla
    for (var i = 0; i < filtered.length; i++) {
        var order = filtered[i];
        var totalConverted = (parseFloat(order.total) * currencyRate).toFixed(2);
        
        var tr = document.createElement('tr');
        
        // html
        var html = '';
        html += '<td>#' + order.id + '</td>';
        html += '<td>';
        html += '<div class="fw-bold">' + (order.usuario_nombre || 'Usuario') + '</div>';
        html += '<div class="small text-muted">' + (order.usuario_email || '') + '</div>';
        html += '</td>';
        html += '<td>' + order.fecha + '</td>';
        
        // Select de estado
        html += '<td>';
        html += '<select class="form-select form-select-sm" onchange="updateStatus(' + order.id + ', this.value)">';
        html += '<option value="pending" ' + (order.estado === 'pending' ? 'selected' : '') + '>Pendiente</option>';
        html += '<option value="paid" ' + (order.estado === 'paid' ? 'selected' : '') + '>Pagado</option>';
        html += '<option value="preparing" ' + (order.estado === 'preparing' ? 'selected' : '') + '>Preparando</option>';
        html += '<option value="delivered" ' + (order.estado === 'delivered' ? 'selected' : '') + '>Entregado</option>';
        html += '<option value="cancelled" ' + (order.estado === 'cancelled' ? 'selected' : '') + '>Cancelado</option>';
        html += '</select>';
        html += '</td>';
        
        html += '<td class="fw-bold">' + totalConverted + ' ' + currentCurrency + '</td>';
        html += '<td>';
        html += '<a href="index.php?controller=order&action=detalles&id=' + order.id + '" class="btn btn-sm btn-outline-primary me-1" target="_blank"><i class="bi bi-eye"></i></a>';
        html += '<button class="btn btn-sm btn-danger" onclick="deleteOrder(' + order.id + ')"><i class="bi bi-trash"></i></button>';
        html += '</td>';

        tr.innerHTML = html;
        tbody.appendChild(tr);
    }
}

// --- mostrar PRODUCTOS ---
function renderProducts() {
    var tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = '';
    
    for (var i = 0; i < appData.products.length; i++) {
        var prod = appData.products[i];
        var tr = document.createElement('tr');
        
        var html = '';
        html += '<td>' + prod.id + '</td>';
        html += '<td><img src="' + (prod.imagen || '') + '" style="width: 40px; height: 40px; object-fit: cover;" class="rounded"></td>';
        html += '<td>' + prod.nombre + '</td>';
        html += '<td>' + parseFloat(prod.precio).toFixed(2) + ' €</td>';
        
        //  JSON.stringify
        var prodString = JSON.stringify(prod).replace(/"/g, '&quot;');
        
        html += '<td>';
        html += '<button class="btn btn-sm btn-warning me-1" onclick="editProduct(' + prodString + ')"><i class="bi bi-pencil"></i></button>';
        html += '<button class="btn btn-sm btn-danger" onclick="deleteProduct(' + prod.id + ')"><i class="bi bi-trash"></i></button>';
        html += '</td>';

        tr.innerHTML = html;
        tbody.appendChild(tr);
    }
}

// --- mostrar OFERTAS ---
function renderOffers() {
    var tbody = document.getElementById('offersTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    if (!appData.offers) return;
    
    for (var i = 0; i < appData.offers.length; i++) {
        var offer = appData.offers[i];
        var tr = document.createElement('tr');
        
        var tipo = offer.es_porcentaje == 1 ? '<span class="badge bg-info">%</span>' : '<span class="badge bg-success">€</span>';
        var estado = offer.activa == 1 ? '<span class="badge bg-success">Activa</span>' : '<span class="badge bg-danger">Inactiva</span>';
        
        var html = '';
        html += '<td><strong>' + offer.nombre + '</strong></td>';
        html += '<td>' + tipo + ' ' + parseFloat(offer.valor).toFixed(2) + '</td>';
        html += '<td>' + estado + '</td>';
        html += '<td>' + (offer.inicio || '—') + '</td>';
        html += '<td>' + (offer.fin || '—') + '</td>';
        
        var offerString = JSON.stringify(offer).replace(/"/g, '&quot;');
        html += '<td>';
        html += '<button class="btn btn-sm btn-warning me-1" onclick="editOffer(' + offerString + ')"><i class="bi bi-pencil"></i></button>';
        html += '<button class="btn btn-sm btn-danger" onclick="deleteOffer(' + offer.id + ')"><i class="bi bi-trash"></i></button>';
        html += '</td>';

        tr.innerHTML = html;
        tbody.appendChild(tr);
    }
}

// --- mostrar LOGS ---
function renderLogs() {
    var tbody = document.getElementById('logsTableBody');
    tbody.innerHTML = '';
    
    for (var i = 0; i < appData.logs.length; i++) {
        var log = appData.logs[i];
        var tr = document.createElement('tr');
        
        var html = '';
        html += '<td>' + log.timestamp + '</td>';
        html += '<td>' + (log.usuario_email || 'Sistema') + '</td>';
        html += '<td><span class="badge bg-secondary">' + log.tipo + '</span></td>';
        html += '<td>' + log.accion + '</td>';
        
        tr.innerHTML = html;
        tbody.appendChild(tr);
    }
}

// --- ACCIONES ---

async function updateStatus(id, status) {
    var formData = new FormData();
    formData.append('id', id);
    formData.append('status', status);
    
    await fetch('api/service.php?action=update_order_status', {
        method: 'POST',
        body: formData
    });
    
    loadData(); // recargar para ver los cambios
}

async function deleteOrder(id) {
    var confirmacion = confirm('¿Seguro que quieres eliminar este pedido? Esta acción no se puede deshacer.');
    if(confirmacion == false) {
        return;
    }
    
    var formData = new FormData();
    formData.append('id', id);
    
    var res = await fetch('api/service.php?action=delete_order', {
        method: 'POST',
        body: formData
    });
    var data = await res.json();
    
    if(data.success) {
        loadData();
        alert('Pedido eliminado correctamente');
    } else {
        alert('Error al eliminar el pedido');
    }
}

// --- Crear Pedido (ADMIN) ---
function openOrderModal() {
    // Reset form
    document.getElementById('orderForm').reset();
    document.getElementById('orderItemsBody').innerHTML = '';
    document.getElementById('orderTotal').innerText = '0.00';

    // Cargar usuarios en select
    var userSel = document.getElementById('orderUser');
    userSel.innerHTML = '';
    for (var i = 0; i < appData.users.length; i++) {
        var u = appData.users[i];
        var opt = document.createElement('option');
        opt.value = u.id;
        opt.textContent = (u.nombre ? u.nombre + ' - ' : '') + u.email;
        userSel.appendChild(opt);
    }
    // Habilitar direcciones al seleccionar usuario
    var addrSel = document.getElementById('orderAddress');
    addrSel.innerHTML = '';
    addrSel.disabled = true;

    // Listener on change (ensure not multiplied)
    userSel.onchange = async function() {
        await loadAddressesForUser(this.value);
    };
    if (appData.users.length > 0) {
        loadAddressesForUser(appData.users[0].id);
    }

    // Arrancar con una fila de item
    addOrderItemRow();

    var modalEl = document.getElementById('orderModal');
    new bootstrap.Modal(modalEl).show();
}

async function loadAddressesForUser(userId) {
    var addrSel = document.getElementById('orderAddress');
    addrSel.innerHTML = '';
    addrSel.disabled = true;
    if (!userId) return;

    var res = await fetch('api/service.php?action=get_user_addresses&user_id=' + encodeURIComponent(userId));
    var data = await res.json();
    for (var i = 0; i < data.length; i++) {
        var a = data[i];
        var opt = document.createElement('option');
        opt.value = a.id;
        opt.textContent = (a.alias ? a.alias + ' - ' : '') + a.direccion + ', ' + a.ciudad + ' ' + a.cp;
        addrSel.appendChild(opt);
    }
    addrSel.disabled = data.length === 0;
}

function addOrderItemRow() {
    var tbody = document.getElementById('orderItemsBody');
    var tr = document.createElement('tr');

    var prodSelectHtml = '<select class="form-select form-select-sm order-prod-select" onchange="recalcOrderRow(this)">';
    prodSelectHtml += '<option value="">Selecciona producto</option>';
    for (var i = 0; i < appData.products.length; i++) {
        var p = appData.products[i];
        prodSelectHtml += '<option value="' + p.id + '" data-price="' + parseFloat(p.precio).toFixed(2) + '">' + p.nombre + '</option>';
    }
    prodSelectHtml += '</select>';

    tr.innerHTML = ''+
        '<td>' + prodSelectHtml + '</td>'+
        '<td><input type="number" class="form-control form-control-sm order-qty" value="1" min="1" onchange="recalcOrderRow(this)" /></td>'+
        '<td class="order-row-price">0.00</td>'+
        '<td><button type="button" class="btn btn-sm btn-link text-danger" onclick="removeOrderItemRow(this)"><i class="bi bi-x"></i></button></td>';
    tbody.appendChild(tr);
}

function removeOrderItemRow(btn) {
    var tr = btn.closest('tr');
    tr.parentNode.removeChild(tr);
    recalcOrderTotal();
}

function recalcOrderRow(el) {
    var tr = el.closest('tr');
    var sel = tr.querySelector('.order-prod-select');
    var qtyInput = tr.querySelector('.order-qty');
    var priceCell = tr.querySelector('.order-row-price');
    var price = 0.0;
    var qty = parseInt(qtyInput.value || '1', 10);
    if (sel && sel.value) {
        var opt = sel.options[sel.selectedIndex];
        var p = parseFloat(opt.getAttribute('data-price')) || 0;
        price = p * qty;
    }
    priceCell.textContent = price.toFixed(2);
    recalcOrderTotal();
}

function recalcOrderTotal() {
    var rows = document.querySelectorAll('#orderItemsBody tr');
    var total = 0.0;
    rows.forEach(function(row) {
        var cell = row.querySelector('.order-row-price');
        var v = parseFloat(cell.textContent || '0');
        total += isNaN(v) ? 0 : v;
    });
    document.getElementById('orderTotal').textContent = total.toFixed(2);
}

async function saveOrder() {
    var userId = document.getElementById('orderUser').value;
    var addrId = document.getElementById('orderAddress').value;
    var status = document.getElementById('orderStatus').value;
    var currency = document.getElementById('orderCurrency').value;

    var items = [];
    var rows = document.querySelectorAll('#orderItemsBody tr');
    rows.forEach(function(row){
        var sel = row.querySelector('.order-prod-select');
        var qty = row.querySelector('.order-qty');
        if (sel && sel.value && qty && parseInt(qty.value, 10) > 0) {
            items.push({ producto_id: parseInt(sel.value, 10), cantidad: parseInt(qty.value, 10) });
        }
    });

    if (!userId || !addrId || items.length === 0) {
        alert('Selecciona usuario, dirección y al menos un producto.');
        return;
    }

    var formData = new FormData();
    formData.append('usuario_id', userId);
    formData.append('direccion_id', addrId);
    formData.append('estado', status);
    formData.append('moneda', currency);
    formData.append('items', JSON.stringify(items));

    var res = await fetch('api/service.php?action=save_order', { method: 'POST', body: formData });
    var data = await res.json();
    if (data && data.success) {
        var modalEl = document.getElementById('orderModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
        loadData();
        alert('Pedido creado correctamente (#' + data.pedido_id + ')');
    } else {
        alert('Error al crear el pedido');
    }
}

function openProductModal() {
    document.getElementById('productForm').reset();
    document.getElementById('prodId').value = '';
    document.getElementById('productModalTitle').innerText = 'Nuevo Producto';
    
    var modalEl = document.getElementById('productModal');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function editProduct(prod) {
    document.getElementById('prodId').value = prod.id;
    document.getElementById('prodNombre').value = prod.nombre;
    document.getElementById('prodPrecio').value = prod.precio;
    document.getElementById('prodDesc').value = prod.descripcion;
    document.getElementById('prodImagen').value = prod.imagen;
    
    document.getElementById('productModalTitle').innerText = 'Editar Producto';
    
    var modalEl = document.getElementById('productModal');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
}

async function saveProduct() {
    var form = document.getElementById('productForm');
    var formData = new FormData(form);
    
    var res = await fetch('api/service.php?action=save_product', {
        method: 'POST',
        body: formData
    });
    var data = await res.json();
    
    if(data.success) {
        var modalEl = document.getElementById('productModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        
        loadData();
    } else {
        alert('Error al guardar');
    }
}

async function deleteProduct(id) {
    var confirmacion = confirm('¿Seguro que quieres eliminar este producto?');
    if(confirmacion == false) {
        return;
    }
    
    var formData = new FormData();
    formData.append('id', id);
    
    var res = await fetch('api/service.php?action=delete_product', {
        method: 'POST',
        body: formData
    });
    var data = await res.json();
    
    if(data.success) {
        loadData();
    } else {
        alert('Error al eliminar');
    }
}

// --- OFERTAS ---
function openOfferModal() {
    document.getElementById('offerForm').reset();
    document.getElementById('offerId').value = '';
    document.getElementById('modalOfferTitle').textContent = 'Nueva Oferta';
    
    var modalEl = document.getElementById('offerModal');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function editOffer(offer) {
    document.getElementById('offerId').value = offer.id;
    document.getElementById('offerNombre').value = offer.nombre;
    document.getElementById('offerValor').value = offer.valor;
    document.getElementById('offerEsPorcentaje').checked = offer.es_porcentaje == 1;
    document.getElementById('offerInicio').value = offer.inicio || '';
    document.getElementById('offerFin').value = offer.fin || '';
    document.getElementById('offerActiva').checked = offer.activa == 1;
    
    document.getElementById('modalOfferTitle').textContent = 'Editar Oferta';
    
    var modalEl = document.getElementById('offerModal');
    var modal = new bootstrap.Modal(modalEl);
    modal.show();
}

async function saveOffer() {
    var id = document.getElementById('offerId').value;
    var nombre = document.getElementById('offerNombre').value;
    var valor = document.getElementById('offerValor').value;
    var es_porcentaje = document.getElementById('offerEsPorcentaje').checked ? 1 : 0;
    var inicio = document.getElementById('offerInicio').value;
    var fin = document.getElementById('offerFin').value;
    var activa = document.getElementById('offerActiva').checked ? 1 : 0;
    
    if (!nombre || !valor) {
        alert('Por favor completa todos los campos requeridos');
        return;
    }
    
    var formData = new FormData();
    formData.append('id', id);
    formData.append('nombre', nombre);
    formData.append('valor', valor);
    formData.append('es_porcentaje', es_porcentaje);
    formData.append('inicio', inicio);
    formData.append('fin', fin);
    formData.append('activa', activa);
    
    var res = await fetch('api/service.php?action=save_offer', {
        method: 'POST',
        body: formData
    });
    var data = await res.json();
    
    if(data.success) {
        var modalEl = document.getElementById('offerModal');
        var modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();
        
        loadData();
        alert('Oferta guardada correctamente');
    } else {
        alert('Error al guardar la oferta');
    }
}

async function deleteOffer(id) {
    var confirmacion = confirm('¿Seguro que quieres eliminar esta oferta?');
    if(confirmacion == false) {
        return;
    }
    
    var formData = new FormData();
    formData.append('id', id);
    
    var res = await fetch('api/service.php?action=delete_offer', {
        method: 'POST',
        body: formData
    });
    var data = await res.json();
    
    if(data.success) {
        loadData();
        alert('Oferta eliminada correctamente');
    } else {
        alert('Error al eliminar la oferta');
    }
}

// --- EVENTOS ---
function setupEventListeners() {
    // Input de filtro
    var inputFiltro = document.getElementById('orderFilterUser');
    inputFiltro.addEventListener('input', function() {
        renderOrders();
    });

    // Select de pedido
    var selectOrden = document.getElementById('orderSort');
    selectOrden.addEventListener('change', function() {
        renderOrders();
    });

    // Select de currency (moneda)
    var selectMoneda = document.getElementById('currencySelector');
    selectMoneda.addEventListener('change', function(e) {
        updateCurrency(e.target.value);
    });
}
