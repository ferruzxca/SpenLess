<?php
require_once 'config.php';

echo "<h1>Prueba de Base de Datos - SpenLess</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar si las tablas existen
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>Tablas encontradas:</h2>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Verificar usuarios
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $users_count = $stmt->fetch()['total'];
    echo "<p>Total de usuarios: $users_count</p>";
    
    // Verificar movimientos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM movimientos");
    $movements_count = $stmt->fetch()['total'];
    echo "<p>Total de movimientos: $movements_count</p>";
    
    // Mostrar algunos movimientos de ejemplo
    if ($movements_count > 0) {
        echo "<h2>Últimos 5 movimientos:</h2>";
        $stmt = $pdo->query("SELECT * FROM movimientos ORDER BY created_at DESC LIMIT 5");
        $movements = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Tipo</th><th>Monto</th><th>Categoría</th><th>Descripción</th><th>Fecha</th></tr>";
        foreach ($movements as $movement) {
            echo "<tr>";
            echo "<td>{$movement['id']}</td>";
            echo "<td>{$movement['user_id']}</td>";
            echo "<td>{$movement['tipo']}</td>";
            echo "<td>\${$movement['monto']}</td>";
            echo "<td>{$movement['categoria']}</td>";
            echo "<td>{$movement['descripcion']}</td>";
            echo "<td>{$movement['fecha']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Probar consultas de estadísticas
    echo "<h2>Estadísticas de prueba:</h2>";
    
    // Total ingresos
    $stmt = $pdo->query("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE tipo = 'ingreso'");
    $total_ingresos = $stmt->fetch()['total'];
    echo "<p>Total ingresos: \$$total_ingresos</p>";
    
    // Total gastos
    $stmt = $pdo->query("SELECT COALESCE(SUM(monto), 0) as total FROM movimientos WHERE tipo = 'gasto'");
    $total_gastos = $stmt->fetch()['total'];
    echo "<p>Total gastos: \$$total_gastos</p>";
    
    // Balance
    $balance = $total_ingresos - $total_gastos;
    echo "<p>Balance: \$$balance</p>";
    
    // Gastos por categoría
    $stmt = $pdo->query("SELECT categoria, SUM(monto) as total FROM movimientos WHERE tipo = 'gasto' GROUP BY categoria ORDER BY total DESC");
    $categorias = $stmt->fetchAll();
    
    echo "<h3>Gastos por categoría:</h3>";
    echo "<ul>";
    foreach ($categorias as $cat) {
        echo "<li>{$cat['categoria']}: \${$cat['total']}</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}
?> 