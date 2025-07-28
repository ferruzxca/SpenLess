<?php
$page_title = 'Inicio';
require_once 'config.php';

// Si el usuario ya está autenticado, redirigir al dashboard
if (isAuthenticated()) {
    header('Location: dashboard.php');
    exit();
}
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    <i class="fas fa-wallet me-3"></i>
                    <?php echo APP_NAME; ?>
                </h1>
                <p class="lead mb-5"><?php echo APP_SLOGAN; ?></p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="register.php" class="btn btn-light btn-lg px-4 py-3">
                        <i class="fas fa-user-plus me-2"></i>
                        Comenzar Gratis
                    </a>
                    <a href="login.php" class="btn btn-outline-light btn-lg px-4 py-3">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold text-dark mb-4">¿Por qué elegir SpenLess?</h2>
                <p class="lead text-muted">Una herramienta completa para gestionar tus finanzas personales de manera simple y efectiva.</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-chart-pie fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title fw-bold">Seguimiento Detallado</h5>
                        <p class="card-text text-muted">Registra todos tus ingresos y gastos con categorías personalizadas para un control total de tus finanzas.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-chart-line fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">Análisis Visual</h5>
                        <p class="card-text text-muted">Visualiza tus patrones de gasto con gráficos interactivos y reportes detallados para tomar mejores decisiones.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-shield-alt fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title fw-bold">Seguridad Total</h5>
                        <p class="card-text text-muted">Tus datos están protegidos con encriptación avanzada y acceso seguro a tu información financiera.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Estadísticas -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-primary text-white">
                    <div class="card-body">
                        <i class="fas fa-users fa-2x mb-3"></i>
                        <h3 class="fw-bold">1,000+</h3>
                        <p class="mb-0">Usuarios Activos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-success text-white">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-2x mb-3"></i>
                        <h3 class="fw-bold">$50M+</h3>
                        <p class="mb-0">Gastos Registrados</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-info text-white">
                    <div class="card-body">
                        <i class="fas fa-chart-bar fa-2x mb-3"></i>
                        <h3 class="fw-bold">95%</h3>
                        <p class="mb-0">Satisfacción</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-4">
                <div class="card border-0 bg-warning text-white">
                    <div class="card-body">
                        <i class="fas fa-clock fa-2x mb-3"></i>
                        <h3 class="fw-bold">24/7</h3>
                        <p class="mb-0">Disponibilidad</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-6 fw-bold text-dark mb-4">¿Listo para tomar el control de tus finanzas?</h2>
                <p class="lead text-muted mb-5">Únete a miles de usuarios que ya están organizando su dinero y mejorando su vida financiera.</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="register.php" class="btn btn-primary btn-lg px-5 py-3">
                        <i class="fas fa-rocket me-2"></i>
                        Comenzar Ahora
                    </a>
                    <a href="login.php" class="btn btn-outline-primary btn-lg px-5 py-3">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Ya tengo cuenta
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?> 