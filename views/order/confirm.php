<?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) >= 1): ?>
    <div class="container my-5 pt-5">
        <h1 class="section-title mb-4">Confirmar <strong>Pedido</strong></h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Dirección de Envío</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="index.php?controller=order&action=make" method="POST">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="direccion" class="form-label">Dirección completa</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" required placeholder="Calle, número, piso...">
                                </div>
                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="cp" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="cp" name="cp" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="pais" class="form-label">País</label>
                                    <input type="text" class="form-control" id="pais" name="pais" value="España" required>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-grid">
                                <button type="submit" class="btn btn-success btn-lg fw-bold">Confirmar y Pagar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 bg-light">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Resumen</h5>
                        <?php 
                            $total = 0;
                            foreach($_SESSION['carrito'] as $elemento){
                                $total += $elemento['precio'] * $elemento['unidades'];
                            }
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Total a pagar</span>
                            <span class="h4 mb-0 fw-bold text-primary"><?= number_format($total, 2) ?> €</span>
                        </div>
                        <a href="index.php?controller=order&action=cart" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Volver al carrito</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container my-5 pt-5 text-center">
        <p class="lead">No tienes productos en el carrito.</p>
        <a href="index.php" class="btn btn-primary">Ir al inicio</a>
    </div>
<?php endif; ?>
