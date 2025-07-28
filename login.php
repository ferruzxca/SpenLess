<?php
$page_title = 'Iniciar Sesión';
require_once 'config.php';

$errors = [];

// Si el usuario ya está autenticado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit();
}

// Procesar formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validaciones
    if (empty($email)) {
        $errors[] = 'El correo electrónico es obligatorio';
    }
    
    if (empty($password)) {
        $errors[] = 'La contraseña es obligatoria';
    }
    
    // Si no hay errores, intentar autenticar
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            $stmt = $pdo->prepare("SELECT id, nombre, email, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Autenticación exitosa
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_email'] = $user['email'];
                
                // Redirigir al dashboard
                header('Location: dashboard.php');
                exit();
            } else {
                $errors[] = 'Credenciales incorrectas. Verifica tu correo y contraseña.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Error al autenticar. Inténtalo de nuevo.';
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0">
            <div class="card-header text-center py-4">
                <h3 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Iniciar Sesión
                </h3>
                <p class="text-light mb-0">Accede a tu cuenta de SpenLess</p>
            </div>
            
            <div class="card-body p-4">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" id="loginForm" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>Correo Electrónico
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingresa tu correo electrónico.
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-1"></i>Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required>
                            <button class="btn btn-outline-secondary" 
                                    type="button" 
                                    onclick="togglePasswordVisibility('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">
                            Por favor ingresa tu contraseña.
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Iniciar Sesión
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="mb-0">
                        ¿No tienes una cuenta? 
                        <a href="register.php" class="text-decoration-none">
                            Regístrate aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    if (!validateForm('loginForm')) {
        e.preventDefault();
    }
});
</script>

<?php include 'includes/footer.php'; ?> 