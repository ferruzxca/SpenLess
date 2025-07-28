<?php
require_once 'config.php';

echo "<h1>Insertando Datos de Prueba - SpenLess</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar si ya existe el usuario de prueba
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['admin@spenless.com']);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Insertar usuario de prueba
        $hashed_password = password_hash('password123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nombre, email, password) VALUES (?, ?, ?)");
        $stmt->execute(['Usuario Demo', 'admin@spenless.com', $hashed_password]);
        $user_id = $pdo->lastInsertId();
        echo "<p>✅ Usuario de prueba creado con ID: $user_id</p>";
    } else {
        $user_id = $user['id'];
        echo "<p>✅ Usuario de prueba ya existe con ID: $user_id</p>";
    }
    
    // Verificar si ya hay movimientos
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM movimientos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $movements_count = $stmt->fetch()['total'];
    
    if ($movements_count == 0) {
        // Insertar movimientos de prueba
        $movements = [
            ['ingreso', 5000.00, 'Salario', 'Salario mensual', '2024-01-15'],
            ['gasto', 150.00, 'Alimentación', 'Supermercado', '2024-01-16'],
            ['gasto', 80.00, 'Transporte', 'Gasolina', '2024-01-17'],
            ['ingreso', 1000.00, 'Freelance', 'Trabajo extra', '2024-01-18'],
            ['gasto', 200.00, 'Entretenimiento', 'Cena con amigos', '2024-01-19'],
            ['gasto', 120.00, 'Salud', 'Farmacia', '2024-01-20'],
            ['ingreso', 300.00, 'Inversión', 'Dividendos', '2024-01-21'],
            ['gasto', 350.00, 'Vivienda', 'Luz y agua', '2024-01-22'],
            ['gasto', 75.00, 'Alimentación', 'Restaurante', '2024-01-23'],
            ['gasto', 45.00, 'Transporte', 'Uber', '2024-01-24'],
            ['ingreso', 2500.00, 'Salario', 'Salario quincenal', '2024-02-01'],
            ['gasto', 180.00, 'Alimentación', 'Supermercado', '2024-02-02'],
            ['gasto', 90.00, 'Transporte', 'Gasolina', '2024-02-03'],
            ['gasto', 150.00, 'Entretenimiento', 'Cine', '2024-02-04'],
            ['gasto', 200.00, 'Salud', 'Dentista', '2024-02-05']
        ];
        
        $stmt = $pdo->prepare("INSERT INTO movimientos (user_id, tipo, monto, categoria, descripcion, fecha) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($movements as $movement) {
            $stmt->execute([$user_id, $movement[0], $movement[1], $movement[2], $movement[3], $movement[4]]);
        }
        
        echo "<p>✅ Se insertaron " . count($movements) . " movimientos de prueba</p>";
    } else {
        echo "<p>✅ Ya existen movimientos en la base de datos</p>";
    }
    
    // Mostrar estadísticas finales
    echo "<h2>Estadísticas finales:</h2>";
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM movimientos WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_movements = $stmt->fetch()['total'];
    echo "<p>Total de movimientos: $total_movements</p>";
    
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE user_id = ? AND tipo = 'ingreso'");
    $stmt->execute([$user_id]);
    $total_ingresos = $stmt->fetch()['total'];
    echo "<p>Total ingresos: \$$total_ingresos</p>";
    
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE user_id = ? AND tipo = 'gasto'");
    $stmt->execute([$user_id]);
    $total_gastos = $stmt->fetch()['total'];
    echo "<p>Total gastos: \$$total_gastos</p>";
    
    $balance = $total_ingresos - $total_gastos;
    echo "<p>Balance: \$$balance</p>";
    
    echo "<p style='color: green; font-weight: bold;'>✅ Datos de prueba insertados correctamente</p>";
    echo "<p><a href='login.php'>Ir al login</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 