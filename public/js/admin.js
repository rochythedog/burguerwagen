document.addEventListener('DOMContentLoaded', () => {
  loadOrders();
});

// recibir todos los pedidos
function loadOrders() {
  fetch('api/index.php?resource=pedidos')
    .then(res => res.json())
    .then(json => {
      if (json.status !== 'success') {
        alert('Error loading orders');
        return;
      }
      fillTable(json.data);
    })
    .catch(err => {
      console.error(err);
      alert('Connection error');
    });
}

function fillTable(orders) {
  const tbody = document.querySelector('#ordersTable tbody');
  tbody.innerHTML = '';

  orders.forEach(o => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${o.id}</td>
      <td>${o.customer}</td>
      <td>${o.status}</td>
      <td>${parseFloat(o.total).toFixed(2)} €</td>
      <td>${o.date}</td>
      <td>
        <button class="btn btn-sm btn-outline-primary me-1" onclick="showOrder(${o.id})">Show</button>
        <button class="btn btn-sm btn-outline-secondary me-1" onclick="updateOrderStatus(${o.id})">Update</button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteOrder(${o.id})">Delete</button>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

// SHOW
function showOrder(id) {
  fetch('api/index.php?resource=pedidos&id=' + encodeURIComponent(id))
    .then(res => res.json())
    .then(json => {
      if (json.status !== 'success') {
        alert('Order not found');
        return;
      }
      console.log('Order detail:', json.data);
      alert('Check order detail in browser console (F12).');
    })
    .catch(err => {
      console.error(err);
      alert('Connection error');
    });
}

// UPDATE
function updateOrderStatus(id) {
  const newStatus = prompt('New status (pending, paid, preparing, delivered, cancelled):');
  if (!newStatus) return;

  fetch('api/index.php?resource=pedidos', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id: id, status: newStatus })
  })
    .then(res => res.json())
    .then(json => {
      if (json.status === 'success') {
        loadOrders();
      } else {
        alert('Error updating order');
      }
    })
    .catch(err => {
      console.error(err);
      alert('Connection error');
    });
}

// DELETE
function deleteOrder(id) {
  if (!confirm('Are you sure you want to delete order #' + id + '?')) return;

  fetch('api/index.php?resource=pedidos&id=' + encodeURIComponent(id), {
    method: 'DELETE'
  })
    .then(res => res.json())
    .then(json => {
      if (json.status === 'success') {
        loadOrders();
      } else {
        alert('Error deleting order');
      }
    })
    .catch(err => {
      console.error(err);
      alert('Connection error');
    });
}
