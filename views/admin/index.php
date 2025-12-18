<div class="container-fluid my-5 pt-5">
    <div class="row">
        <!-- Sidebar / Tabs -->
        <div class="col-md-2 bg-light p-3 rounded">
            <h4 class="mb-4 text-primary fw-bold">Admin Panel</h4>
            <div class="nav flex-column nav-pills" id="adminTabs" role="tablist">
                <button class="nav-link active text-start" id="tab-orders" data-bs-toggle="pill" data-bs-target="#content-orders" type="button">
                    <i class="bi bi-receipt me-2"></i> Pedidos
                </button>
                <button class="nav-link text-start" id="tab-products" data-bs-toggle="pill" data-bs-target="#content-products" type="button">
                    <i class="bi bi-box-seam me-2"></i> Productos
                </button>
                <button class="nav-link text-start" id="tab-logs" data-bs-toggle="pill" data-bs-target="#content-logs" type="button">
                    <i class="bi bi-journal-text me-2"></i> Logs
                </button>
            </div>
            
            <hr class="my-4">
            
            <div class="mb-3">
                <label class="form-label small fw-bold">Moneda</label>
                <select id="currencySelector" class="form-select form-select-sm">
                    <option value="EUR" selected>EUR (€)</option>
                    <option value="USD">USD ($)</option>
                    <option value="GBP">GBP (£)</option>
                </select>
                <small class="text-muted" style="font-size: 0.7rem;">Powered by open.er-api.com</small>
            </div>
        </div>

        <!-- Content -->
        <div class="col-md-10">
            <div class="tab-content p-3" id="adminTabContent">
                
                <!-- ORDERS TAB -->
                <div class="tab-pane fade show active" id="content-orders">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Gestión de Pedidos</h2>
                        <div class="d-flex gap-2">
                            <input type="text" id="orderFilterUser" class="form-control" placeholder="Filtrar por usuario...">
                            <select id="orderSort" class="form-select" style="width: 200px;">
                                <option value="date_desc">Fecha (Reciente)</option>
                                <option value="date_asc">Fecha (Antigua)</option>
                                <option value="price_desc">Precio (Alto)</option>
                                <option value="price_asc">Precio (Bajo)</option>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover border">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <!-- JS render -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PRODUCTS TAB -->
                <div class="tab-pane fade" id="content-products">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Gestión de Productos</h2>
                        <button class="btn btn-success" onclick="openProductModal()">
                            <i class="bi bi-plus-lg"></i> Nuevo Producto
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover border align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <!-- JS render -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- LOGS TAB -->
                <div class="tab-pane fade" id="content-logs">
                    <h2 class="mb-4">Historial de Actividad</h2>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped border">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Tipo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <!-- JS render -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="prodId" name="id">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="prodNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Precio (€)</label>
                        <input type="number" step="0.01" class="form-control" id="prodPrecio" name="precio" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" id="prodDesc" name="descripcion"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Imagen</label>
                        <input type="text" class="form-control" id="prodImagen" name="imagen">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="saveProduct()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/admin.js?v=<?= time() ?>"></script>
