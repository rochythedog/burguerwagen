<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="text-center mb-5">
                <h1 class="section-title mb-2">Mi <strong>Perfil</strong></h1>
                <p class="text-muted">Gestiona tus datos personales</p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    
                    <!-- Cabecera del usuario -->
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle text-primary mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill display-4"></i>
                        </div>
                        <h2 class="h4 fw-bold"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></h2>
                        <span class="badge bg-primary rounded-pill px-3">
                            <?= htmlspecialchars(ucfirst($_SESSION['user_role'] ?? 'Cliente')) ?>
                        </span>
                    </div>

                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        <?= htmlspecialchars($success) ?>
                    </div>
                    <?php endif; ?>

                    <!-- Formulario de edición -->
                    <form method="post" action="index.php?controller=user&action=update">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 border-0 border-bottom bg-transparent" name="nombre" id="nombre" value="<?= htmlspecialchars($user->getNombre() ?? $_SESSION['user_name'] ?? '') ?>" required>
                                    <label for="nombre" class="text-muted">Nombre</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control rounded-0 border-0 border-bottom bg-transparent" name="apellidos" id="apellidos" value="<?= htmlspecialchars($user->getApellidos() ?? '') ?>">
                                    <label for="apellidos" class="text-muted">Apellidos</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control rounded-0 border-0 border-bottom bg-transparent" name="email" id="email" value="<?= htmlspecialchars($user->getEmail() ?? $_SESSION['user_email'] ?? '') ?>" required>
                                    <label for="email" class="text-muted">Email</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary py-3 fw-bold">Guardar Cambios</button>
                        </div>
                    </form>

                    <hr class="my-4 opacity-10">

                    <div class="d-grid gap-2">
                        <a href="index.php?controller=order&action=index" class="btn btn-outline-primary py-2">
                            <i class="bi bi-bag-check me-2"></i> Ver mis pedidos
                        </a>
                        
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="index.php?controller=adminOrder&action=index" class="btn btn-outline-dark py-2">
                                <i class="bi bi-gear me-2"></i> Gestión de Pedidos (Admin)
                            </a>
                        <?php endif; ?>

                        <a href="index.php?controller=user&action=logout" class="btn btn-link text-danger text-decoration-none mt-2">
                            Cerrar Sesión
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
