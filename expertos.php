<?php
$page_title = 'Expertos Financieros';
require_once 'config.php';

// Verificar autenticación
requireAuth();

// Array de expertos organizados por categorías
$expertos = [
    'abogados' => [
        [
            'nombre' => 'Lic. María González',
            'especialidad' => 'Derecho Fiscal y Financiero',
            'telefono' => '+52 55 1234 5678',
            'email' => 'maria.gonzalez@abogados.com',
            'horario' => 'Lun-Vie 9:00-18:00',
            'ubicacion' => 'CDMX, Polanco'
        ],
        [
            'nombre' => 'Lic. Carlos Rodríguez',
            'especialidad' => 'Derecho Mercantil y Bancario',
            'telefono' => '+52 55 9876 5432',
            'email' => 'carlos.rodriguez@legal.com',
            'horario' => 'Lun-Vie 8:00-17:00',
            'ubicacion' => 'CDMX, Reforma'
        ],
        [
            'nombre' => 'Lic. Ana Martínez',
            'especialidad' => 'Derecho Laboral y Prestaciones',
            'telefono' => '+52 55 5555 1234',
            'email' => 'ana.martinez@laboral.com',
            'horario' => 'Lun-Vie 10:00-19:00',
            'ubicacion' => 'CDMX, Condesa'
        ]
    ],
    'sat' => [
        [
            'nombre' => 'C.P. Roberto Silva',
            'especialidad' => 'Contador Público - SAT',
            'telefono' => '+52 55 1111 2222',
            'email' => 'roberto.silva@sat.com',
            'horario' => 'Lun-Vie 8:00-16:00',
            'ubicacion' => 'CDMX, Centro'
        ],
        [
            'nombre' => 'C.P. Laura Fernández',
            'especialidad' => 'Auditoría Fiscal',
            'telefono' => '+52 55 3333 4444',
            'email' => 'laura.fernandez@auditoria.com',
            'horario' => 'Lun-Vie 9:00-17:00',
            'ubicacion' => 'CDMX, Santa Fe'
        ],
        [
            'nombre' => 'C.P. Miguel Torres',
            'especialidad' => 'Declaraciones Fiscales',
            'telefono' => '+52 55 7777 8888',
            'email' => 'miguel.torres@declaraciones.com',
            'horario' => 'Lun-Vie 8:30-16:30',
            'ubicacion' => 'CDMX, Coyoacán'
        ]
    ],
    'bancos' => [
        [
            'nombre' => 'Banco Nacional de México',
            'especialidad' => 'Préstamos Personales y Hipotecarios',
            'telefono' => '01 800 226 2636',
            'email' => 'prestamos@banamex.com',
            'horario' => 'Lun-Vie 9:00-18:00',
            'ubicacion' => 'Nacional'
        ],
        [
            'nombre' => 'BBVA México',
            'especialidad' => 'Financiamiento Empresarial',
            'telefono' => '01 800 226 2636',
            'email' => 'empresas@bbva.com.mx',
            'horario' => 'Lun-Vie 8:00-17:00',
            'ubicacion' => 'Nacional'
        ],
        [
            'nombre' => 'Banco Santander',
            'especialidad' => 'Créditos de Nómina',
            'telefono' => '01 800 123 4567',
            'email' => 'creditos@santander.com.mx',
            'horario' => 'Lun-Vie 9:00-18:00',
            'ubicacion' => 'Nacional'
        ]
    ],
    'ongs' => [
        [
            'nombre' => 'Fundación ProFinanzas',
            'especialidad' => 'Educación Financiera',
            'telefono' => '+52 55 9999 1111',
            'email' => 'info@profinanzas.org',
            'horario' => 'Lun-Vie 9:00-17:00',
            'ubicacion' => 'CDMX, Roma'
        ],
        [
            'nombre' => 'Asociación Mexicana de Finanzas',
            'especialidad' => 'Asesoría Financiera Gratuita',
            'telefono' => '+52 55 8888 2222',
            'email' => 'contacto@finanzasmex.org',
            'horario' => 'Lun-Vie 10:00-18:00',
            'ubicacion' => 'CDMX, Del Valle'
        ],
        [
            'nombre' => 'Centro de Apoyo Financiero',
            'especialidad' => 'Ayuda a Deudores',
            'telefono' => '+52 55 7777 3333',
            'email' => 'ayuda@apoyofinanciero.org',
            'horario' => 'Lun-Vie 8:00-16:00',
            'ubicacion' => 'CDMX, Iztapalapa'
        ]
    ],
    'otros' => [
        [
            'nombre' => 'Consultores Financieros MX',
            'especialidad' => 'Planeación Financiera Personal',
            'telefono' => '+52 55 6666 4444',
            'email' => 'info@consultoresfinancieros.mx',
            'horario' => 'Lun-Vie 9:00-18:00',
            'ubicacion' => 'CDMX, Polanco'
        ],
        [
            'nombre' => 'Instituto de Finanzas Personales',
            'especialidad' => 'Capacitación y Certificaciones',
            'telefono' => '+52 55 5555 5555',
            'email' => 'capacitacion@finanzaspersonales.mx',
            'horario' => 'Lun-Vie 8:00-17:00',
            'ubicacion' => 'CDMX, Santa Fe'
        ]
    ]
];
?>

<?php include 'includes/header.php'; ?>

<!-- Header de la página -->
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark">
            <i class="fas fa-users me-2"></i>
            Expertos Financieros
        </h2>
        <p class="text-muted">Encuentra el especialista que necesitas para resolver tus dudas financieras</p>
    </div>
</div>

<!-- Navegación por categorías -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm category-nav">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2 col-6">
                        <a href="#abogados" class="btn btn-outline-primary w-100">
                            <i class="fas fa-gavel me-2"></i>Abogados
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#sat" class="btn btn-outline-success w-100">
                            <i class="fas fa-calculator me-2"></i>SAT
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#bancos" class="btn btn-outline-info w-100">
                            <i class="fas fa-university me-2"></i>Bancos
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#ongs" class="btn btn-outline-warning w-100">
                            <i class="fas fa-hands-helping me-2"></i>ONGs
                        </a>
                    </div>
                    <div class="col-md-2 col-6">
                        <a href="#otros" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-cogs me-2"></i>Otros
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Abogados -->
<section id="abogados" class="mb-5 expertos-section">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-primary mb-4">
                <i class="fas fa-gavel me-2"></i>
                Abogados Especializados
            </h3>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($expertos['abogados'] as $experto): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="expert-icon bg-primary bg-opacity-10 me-3">
                            <i class="fas fa-gavel text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($experto['nombre']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($experto['especialidad']); ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($experto['ubicacion']); ?></p>
                        <p class="text-muted mb-2"><i class="fas fa-clock me-2"></i><?php echo htmlspecialchars($experto['horario']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="tel:<?php echo htmlspecialchars($experto['telefono']); ?>" class="btn btn-primary btn-sm contact-btn">
                            <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($experto['telefono']); ?>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($experto['email']); ?>" class="btn btn-outline-primary btn-sm contact-btn">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sección de SAT -->
<section id="sat" class="mb-5 expertos-section">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-success mb-4">
                <i class="fas fa-calculator me-2"></i>
                Personal SAT
            </h3>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($expertos['sat'] as $experto): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="expert-icon bg-success bg-opacity-10 me-3">
                            <i class="fas fa-calculator text-success"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($experto['nombre']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($experto['especialidad']); ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($experto['ubicacion']); ?></p>
                        <p class="text-muted mb-2"><i class="fas fa-clock me-2"></i><?php echo htmlspecialchars($experto['horario']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="tel:<?php echo htmlspecialchars($experto['telefono']); ?>" class="btn btn-success btn-sm contact-btn">
                            <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($experto['telefono']); ?>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($experto['email']); ?>" class="btn btn-outline-success btn-sm contact-btn">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sección de Bancos -->
<section id="bancos" class="mb-5 expertos-section">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-info mb-4">
                <i class="fas fa-university me-2"></i>
                Préstamos Bancarios
            </h3>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($expertos['bancos'] as $experto): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="expert-icon bg-info bg-opacity-10 me-3">
                            <i class="fas fa-university text-info"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($experto['nombre']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($experto['especialidad']); ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($experto['ubicacion']); ?></p>
                        <p class="text-muted mb-2"><i class="fas fa-clock me-2"></i><?php echo htmlspecialchars($experto['horario']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="tel:<?php echo htmlspecialchars($experto['telefono']); ?>" class="btn btn-info btn-sm contact-btn">
                            <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($experto['telefono']); ?>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($experto['email']); ?>" class="btn btn-outline-info btn-sm contact-btn">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sección de ONGs -->
<section id="ongs" class="mb-5 expertos-section">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-warning mb-4">
                <i class="fas fa-hands-helping me-2"></i>
                Organizaciones No Gubernamentales
            </h3>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($expertos['ongs'] as $experto): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="expert-icon bg-warning bg-opacity-10 me-3">
                            <i class="fas fa-hands-helping text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($experto['nombre']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($experto['especialidad']); ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($experto['ubicacion']); ?></p>
                        <p class="text-muted mb-2"><i class="fas fa-clock me-2"></i><?php echo htmlspecialchars($experto['horario']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="tel:<?php echo htmlspecialchars($experto['telefono']); ?>" class="btn btn-warning btn-sm contact-btn">
                            <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($experto['telefono']); ?>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($experto['email']); ?>" class="btn btn-outline-warning btn-sm contact-btn">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Sección de Otros -->
<section id="otros" class="mb-5 expertos-section">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold text-secondary mb-4">
                <i class="fas fa-cogs me-2"></i>
                Otros Servicios
            </h3>
        </div>
    </div>
    <div class="row g-4">
        <?php foreach ($expertos['otros'] as $experto): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm card-hover">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="expert-icon bg-secondary bg-opacity-10 me-3">
                            <i class="fas fa-cogs text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($experto['nombre']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($experto['especialidad']); ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-2"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($experto['ubicacion']); ?></p>
                        <p class="text-muted mb-2"><i class="fas fa-clock me-2"></i><?php echo htmlspecialchars($experto['horario']); ?></p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="tel:<?php echo htmlspecialchars($experto['telefono']); ?>" class="btn btn-secondary btn-sm contact-btn">
                            <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($experto['telefono']); ?>
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($experto['email']); ?>" class="btn btn-outline-secondary btn-sm contact-btn">
                            <i class="fas fa-envelope me-2"></i>Enviar Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Información adicional -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h5 class="mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    Información Importante
                </h5>
                <p class="mb-0">
                    <strong>Nota:</strong> Los contactos proporcionados son para fines informativos. 
                    Te recomendamos verificar la información directamente con cada especialista antes de realizar cualquier consulta.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Smooth scrolling para los enlaces de navegación
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?> 