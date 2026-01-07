document.addEventListener('DOMContentLoaded', function() {
    loadData();
    setupEventListeners();
});

var appData = {
    orders: [],
    products: [],
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
    renderOrders();
    renderProducts();
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
        html += '<option value="confirm" ' + (order.estado === 'confirm' ? 'selected' : '') + '>Confirmado</option>';
        html += '<option value="preparing" ' + (order.estado === 'preparing' ? 'selected' : '') + '>Preparando</option>';
        html += '<option value="sended" ' + (order.estado === 'sended' ? 'selected' : '') + '>Enviado</option>';
        html += '<option value="delivered" ' + (order.estado === 'delivered' ? 'selected' : '') + '>Entregado</option>';
        html += '</select>';
        html += '</td>';
        
        html += '<td class="fw-bold">' + totalConverted + ' ' + currentCurrency + '</td>';
        html += '<td><button class="btn btn-sm btn-outline-primary" onclick="alert(\'Ver detalles\')"><i class="bi bi-eye"></i></button></td>';

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
