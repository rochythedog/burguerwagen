<div class="container my-5 pt-5">
    <h1 class="section-title mb-4">Mis <strong>Pedidos</strong></h1>
    
    <?php if (isset($orders) && count($orders) >= 1): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nº Pedido</th>
                        <th>Coste</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $ped): ?>
                        <tr>
                            <td>
                                <a href="index.php?controller=order&action=confirmed&id=<?= $ped->getId() ?>" class="fw-bold text-decoration-none">
                                    #<?= $ped->getId() ?>
                                </a>
                            </td>
                            <td><?= number_format($ped->getCoste(), 2) ?> €</td>
                            <td><?= $ped->getFecha() ?></td>
                            <td>
                                <?php 
                                    $status = $ped->getEstado();
                                    $badgeClass = 'bg-secondary';
                                    if($status == 'confirm') $badgeClass = 'bg-warning text-dark';
                                    elseif($status == 'preparation') $badgeClass = 'bg-info text-dark';
                                    elseif($status == 'ready') $badgeClass = 'bg-primary';
                                    elseif($status == 'sended') $badgeClass = 'bg-success';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <p class="mb-0">No tienes pedidos realizados.</p>
            <a href="index.php?controller=product&action=index" class="btn btn-primary mt-3">Ir al menú</a>
        </div>
    <?php endif; ?>
</div>
