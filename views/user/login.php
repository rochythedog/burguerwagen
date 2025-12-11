<div class="container d-flex justify-content-center align-items-center login-container">
    <div class="w-100" style="max-width: 400px;">
        
        <div class="text-center mb-5">
            <h1 class="section-title mb-2">Iniciar <strong>sesión</strong></h1>
            <p class="text-muted">Accede a tu cuenta BurguerWagen</p>
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

        <form method="post" class="d-flex flex-column gap-3">
            <div class="form-floating">
                <input type="email" class="form-control rounded-0 border-0 border-bottom bg-transparent" name="email" id="email" placeholder="name@example.com" required>
                <label for="email" class="text-muted">Correo electrónico</label>
            </div>
            
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-0 border-0 border-bottom bg-transparent" name="password" id="password" placeholder="Password" required>
                <label for="password" class="text-muted">Contraseña</label>
            </div>

            <button class="btn btn-primary w-100 py-3 text-uppercase fw-bold mt-2" type="submit">Entrar</button>
            
            <div class="text-center mt-4">
                <a href="index.php?controller=user&action=register" class="text-decoration-none text-dark fw-bold small">¿No tienes cuenta? Regístrate</a>
            </div>
        </form>
    </div>
</div>
