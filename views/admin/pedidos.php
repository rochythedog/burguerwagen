<div class="container">
    <div class="d-flex justify-content-between align-items-end mb-3">
        <div>
            <h1 class="h3 mb-1"><?= htmlspecialchars($titulo) ?></h1>
            <p class="text-muted mb-0">Gestión de pedidos en tiempo real vía API.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaPedidos">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rellenado por JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <p class="text-muted small mt-2">Este panel está hecho con JavaScript + API (fetch a <code>api/index.php?resource=pedidos</code>).</p>
</div>

<script src="<?= BASE_URL ?>/public/js/admin.js"></script>
