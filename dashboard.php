<?php
$page_title = 'Dashboard';
require_once 'config.php';

// Verificar autenticación
requireAuth();

$errors = [];
$success = false;

// Procesar formulario de nuevo movimiento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = sanitizeInput($_POST['tipo'] ?? '');
    $monto = floatval($_POST['monto'] ?? 0);
    $categoria = sanitizeInput($_POST['categoria'] ?? '');
    $descripcion = sanitizeInput($_POST['descripcion'] ?? '');
    $fecha = sanitizeInput($_POST['fecha'] ?? date('Y-m-d'));
    
    // Validaciones
    if (empty($tipo)) {
        $errors[] = 'El tipo de movimiento es obligatorio';
    }
    
    if ($monto <= 0) {
        $errors[] = 'El monto debe ser mayor a 0';
    }
    
    if (empty($categoria)) {
        $errors[] = 'La categoría es obligatoria';
    }
    
    if (empty($descripcion)) {
        $errors[] = 'La descripción es obligatoria';
    }
    
    // Si no hay errores, insertar el movimiento
    if (empty($errors)) {
        try {
            $pdo = getDBConnection();
            
            $stmt = $pdo->prepare("INSERT INTO movimientos (user_id, tipo, monto, categoria, descripcion, fecha, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$_SESSION['user_id'], $tipo, $monto, $categoria, $descripcion, $fecha]);
            
            $success = true;
            $success_message = 'Movimiento registrado exitosamente.';
        } catch (PDOException $e) {
            $errors[] = 'Error al registrar el movimiento. Inténtalo de nuevo.';
        }
    }
}

// Obtener estadísticas del usuario
try {
    $pdo = getDBConnection();
    
    // Total de ingresos
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE user_id = ? AND tipo = 'ingreso'");
    $stmt->execute([$_SESSION['user_id']]);
    $total_ingresos = $stmt->fetch()['total'];
    
    // Total de gastos
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE user_id = ? AND tipo = 'gasto'");
    $stmt->execute([$_SESSION['user_id']]);
    $total_gastos = $stmt->fetch()['total'];
    
    // Balance
    $balance = $total_ingresos - $total_gastos;
    
    // Últimos movimientos
    $stmt = $pdo->prepare("SELECT * FROM movimientos WHERE user_id = ? ORDER BY fecha DESC, created_at DESC LIMIT 10");
    $stmt->execute([$_SESSION['user_id']]);
    $movimientos = $stmt->fetchAll();
    
    // Datos para gráficos
    $stmt = $pdo->prepare("SELECT categoria, SUM(monto) as total FROM movimientos WHERE user_id = ? AND tipo = 'gasto' GROUP BY categoria ORDER BY total DESC LIMIT 8");
    $stmt->execute([$_SESSION['user_id']]);
    $categorias_gastos = $stmt->fetchAll();
    
    // Datos para gráfico de balance mensual (últimos 6 meses)
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(fecha, '%Y-%m') as mes,
            SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as ingresos,
            SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END) as gastos,
            SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE -monto END) as balance
        FROM movimientos 
        WHERE user_id = ? 
        AND fecha >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(fecha, '%Y-%m')
        ORDER BY mes DESC
        LIMIT 6
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $balance_mensual = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $errors[] = 'Error al cargar los datos.';
}
?>

<?php include 'includes/header.php'; ?>

<!-- Mensajes de alerta -->
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
<?php endif; ?>

<!-- Bienvenida -->
<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold text-dark">
            <i class="fas fa-chart-line me-2"></i>
            Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
        </h2>
        <p class="text-muted">Gestiona tus finanzas personales de manera inteligente</p>
    </div>
</div>

<!-- Tarjetas de resumen -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="dashboard-card income-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Ingresos</h6>
                    <h3 class="fw-bold text-success" id="total-income">
                        $<?php echo number_format($total_ingresos, 2); ?>
                    </h3>
                </div>
                <div class="text-success">
                    <i class="fas fa-arrow-up fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="dashboard-card expense-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Total Gastos</h6>
                    <h3 class="fw-bold text-danger" id="total-expense">
                        $<?php echo number_format($total_gastos, 2); ?>
                    </h3>
                </div>
                <div class="text-danger">
                    <i class="fas fa-arrow-down fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="dashboard-card balance-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-1">Balance</h6>
                    <h3 class="fw-bold <?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>" id="balance">
                        $<?php echo number_format($balance, 2); ?>
                    </h3>
                </div>
                <div class="<?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas fa-wallet fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Formulario de nuevo movimiento -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Nuevo Movimiento
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="" id="movimientoForm">
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" 
                                   class="form-control" 
                                   id="monto" 
                                   name="monto" 
                                   step="0.01" 
                                   min="0.01" 
                                   required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="categoria" class="form-label">Categoría</label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Seleccionar categoría</option>
                            <option value="Alimentación">Alimentación</option>
                            <option value="Transporte">Transporte</option>
                            <option value="Vivienda">Vivienda</option>
                            <option value="Entretenimiento">Entretenimiento</option>
                            <option value="Salud">Salud</option>
                            <option value="Educación">Educación</option>
                            <option value="Ropa">Ropa</option>
                            <option value="Servicios">Servicios</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha</label>
                        <input type="date" 
                               class="form-control" 
                               id="fecha" 
                               name="fecha" 
                               value="<?php echo date('Y-m-d'); ?>" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="3" 
                                  required></textarea>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Registrar Movimiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tabla de movimientos -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Últimos Movimientos
                </h5>
                <button class="btn btn-outline-primary btn-sm" onclick="exportData()">
                    <i class="fas fa-download me-1"></i>
                    Exportar
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($movimientos)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay movimientos registrados aún.</p>
                        <p class="text-muted">¡Comienza registrando tu primer movimiento!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Categoría</th>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($movimientos as $movimiento): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($movimiento['fecha'])); ?></td>
                                        <td>
                                            <?php if ($movimiento['tipo'] === 'ingreso'): ?>
                                                <span class="badge badge-income">
                                                    <i class="fas fa-arrow-up me-1"></i>Ingreso
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-expense">
                                                    <i class="fas fa-arrow-down me-1"></i>Gasto
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($movimiento['categoria']); ?></td>
                                        <td><?php echo htmlspecialchars($movimiento['descripcion']); ?></td>
                                        <td class="<?php echo $movimiento['tipo'] === 'ingreso' ? 'text-success' : 'text-danger'; ?> fw-bold">
                                            $<?php echo number_format($movimiento['monto'], 2); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Gastos por Categoría
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="expenseChart"></canvas>
                </div>
                <?php if (empty($categorias_gastos)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de gastos para mostrar.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Balance Mensual
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="balanceChart"></canvas>
                </div>
                <?php if (empty($balance_mensual)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de balance para mostrar.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Sección de Asesoría con Expertos -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    ¿Necesitas Asesoría Financiera?
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <p class="text-muted mb-3">
                            Nuestros expertos están aquí para ayudarte con tus finanzas personales. 
                            Desde asesoría legal hasta préstamos bancarios, tenemos especialistas en cada área.
                        </p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-gavel fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Asesoría Legal</h6>
                                        <small class="text-muted">Abogados especializados en finanzas</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-calculator fa-2x text-success me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Personal SAT</h6>
                                        <small class="text-muted">Asesoría fiscal y tributaria</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-university fa-2x text-info me-3"></i>
                                    <div>
                                        <h6 class="mb-1">Préstamos Bancarios</h6>
                                        <small class="text-muted">Opciones de financiamiento</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <i class="fas fa-hands-helping fa-2x text-warning me-3"></i>
                                    <div>
                                        <h6 class="mb-1">ONGs</h6>
                                        <small class="text-muted">Organizaciones sin fines de lucro</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-4">
                            <i class="fas fa-phone-alt fa-3x text-primary mb-3"></i>
                            <h5 class="mb-3">Contacta a Nuestros Expertos</h5>
                            <p class="text-muted mb-4">
                                Encuentra el especialista que necesitas para resolver tus dudas financieras
                            </p>
                            <a href="expertos.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-address-book me-2"></i>
                                Ver Expertos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funciones de gráficos simplificadas
function createExpenseChartSimple(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
        console.error('Canvas no encontrado:', canvasId);
        return;
    }
    
    if (!data || !data.labels || !data.values || data.values.length === 0) {
        ctx.style.display = 'none';
        return;
    }
    
    try {
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: [
                        '#6f42c1', '#0d6efd', '#198754', '#dc3545',
                        '#fd7e14', '#6c757d', '#20c997', '#e83e8c'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
        console.log('Gráfico de gastos creado exitosamente');
    } catch (error) {
        console.error('Error al crear gráfico de gastos:', error);
    }
}

function createBalanceChartSimple(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) {
        console.error('Canvas no encontrado:', canvasId);
        return;
    }
    
    if (!data || !data.labels || !data.values || data.values.length === 0) {
        ctx.style.display = 'none';
        return;
    }
    
    try {
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Balance',
                    data: data.values,
                    borderColor: '#6f42c1',
                    backgroundColor: 'rgba(111, 66, 193, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        console.log('Gráfico de balance creado exitosamente');
    } catch (error) {
        console.error('Error al crear gráfico de balance:', error);
    }
}

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando gráficos...');
    
    // Datos para el gráfico de gastos por categoría
    <?php if (!empty($categorias_gastos)): ?>
    const expenseData = {
        labels: [<?php echo implode(',', array_map(function($cat) { return "'" . addslashes($cat['categoria']) . "'"; }, $categorias_gastos)); ?>],
        values: [<?php echo implode(',', array_map(function($cat) { return $cat['total']; }, $categorias_gastos)); ?>]
    };
    console.log('Datos de gastos:', expenseData);
    createExpenseChartSimple('expenseChart', expenseData);
    <?php else: ?>
    console.log('No hay datos de gastos disponibles');
    const expenseCanvas = document.getElementById('expenseChart');
    if (expenseCanvas) {
        expenseCanvas.style.display = 'none';
    }
    <?php endif; ?>

    // Datos para el gráfico de balance mensual
    <?php if (!empty($balance_mensual)): ?>
    const balanceData = {
        labels: [<?php echo implode(',', array_map(function($item) { 
            $mes = date('M Y', strtotime($item['mes'] . '-01'));
            return "'" . addslashes($mes) . "'"; 
        }, $balance_mensual)); ?>],
        values: [<?php echo implode(',', array_map(function($item) { return $item['balance']; }, $balance_mensual)); ?>]
    };
    console.log('Datos de balance:', balanceData);
    createBalanceChartSimple('balanceChart', balanceData);
    <?php else: ?>
    console.log('No hay datos de balance disponibles');
    const balanceCanvas = document.getElementById('balanceChart');
    if (balanceCanvas) {
        balanceCanvas.style.display = 'none';
    }
    <?php endif; ?>

    // Validación del formulario
    const form = document.getElementById('movimientoForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm('movimientoForm')) {
                e.preventDefault();
            }
        });
    }

    // Actualizar totales
    if (typeof updateTotals === 'function') {
        updateTotals();
    }
});
</script>

<?php include 'includes/footer.php'; ?> 