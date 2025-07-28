<?php
$page_title = 'Registro';
require_once 'config.php';

$errors = [];
$success = false;

// Si el usuario ya está autenticado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit();
}

// Procesar formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = sanitizeInput($_POST['nombre'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validaciones
    if (empty($nombre)) {
        $errors[] = 'El nombre completo es obligatorio';
    } elseif (strlen($nombre) < 3) {
        $errors[] = 'El nombre debe tener al menos 3 caracteres';
    }
    
    if (empty($email)) {
        $errors[] = 'El correo electrónico es obligatorio';
    } elseif (!isValidEmail($email)) {
        $errors[] = 'El correo electrónico no es válido';
    }
    
    if (empty($password)) {
        $errors[] = 'La contraseña es obligatoria';
    } elseif (strlen($password) < 6) {
        $errors[] = 'La contraseña debe tener al menos 6 caracteres';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Las contraseñas no coinciden';
    }
    
    // Si no hay errores, intentar registrar el usuario
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            // Verificar si el email ya existe
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->fetch()) {
                $errors[] = 'El correo electrónico ya está registrado';
            } else {
                // Insertar nuevo usuario
                $hashed_password = password_hash($password, PASSWORD_DEFAULT, ['cost' => HASH_COST]);
                
                $stmt = $pdo->prepare("INSERT INTO users (nombre, email, password, created_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$nombre, $email, $hashed_password]);
                
                $success = true;
                $success_message = '¡Registro exitoso! Ya puedes iniciar sesión.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Error al registrar el usuario. Inténtalo de nuevo.';
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
                    <i class="fas fa-user-plus me-2"></i>
                    Crear Cuenta
                </h3>
                <p class="text-light mb-0">Únete a SpenLess y toma el control de tus finanzas</p>
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
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Ir al Login
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="" id="registerForm" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">
                                <i class="fas fa-user me-1"></i>Nombre Completo
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>"
                                   required>
                            <div class="invalid-feedback">
                                Por favor ingresa tu nombre completo.
                            </div>
                        </div>
                        
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
                                Por favor ingresa un correo electrónico válido.
                            </div>
                        </div>
                        
                        <div class="mb-3">
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
                                La contraseña debe tener al menos 6 caracteres.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock me-1"></i>Confirmar Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                                <button class="btn btn-outline-secondary" 
                                        type="button" 
                                        onclick="togglePasswordVisibility('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Las contraseñas no coinciden.
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                Crear Cuenta
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">
                            ¿Ya tienes una cuenta? 
                            <a href="login.php" class="text-decoration-none">
                                Inicia sesión aquí
                            </a>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    if (!validateForm('registerForm')) {
        e.preventDefault();
    }
    
    // Validación adicional para confirmar contraseña
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (password.value !== confirmPassword.value) {
        showFieldError(confirmPassword, 'Las contraseñas no coinciden');
        e.preventDefault();
    }
});
</script>

<?php include 'includes/footer.php'; ?> 