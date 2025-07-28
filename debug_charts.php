<?php
require_once 'config.php';

echo "<h1>Debug de Gráficos - SpenLess</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green;'>✅ Conexión a la base de datos exitosa</p>";
    
    // Simular sesión de usuario (ID 1)
    $_SESSION['user_id'] = 1;
    
    // Obtener datos de gastos por categoría
    $stmt = $pdo->prepare("SELECT categoria, SUM(monto) as total FROM movimientos WHERE user_id = ? AND tipo = 'gasto' GROUP BY categoria ORDER BY total DESC LIMIT 8");
    $stmt->execute([$_SESSION['user_id']]);
    $categorias_gastos = $stmt->fetchAll();
    
    echo "<h2>Datos de Gastos por Categoría:</h2>";
    if (!empty($categorias_gastos)) {
        echo "<ul>";
        foreach ($categorias_gastos as $cat) {
            echo "<li>{$cat['categoria']}: \${$cat['total']}</li>";
        }
        echo "</ul>";
        
        // Generar JavaScript para el gráfico
        echo "<h3>JavaScript generado:</h3>";
        echo "<pre>";
        echo "const expenseData = {\n";
        echo "    labels: [" . implode(',', array_map(function($cat) { 
            return "'" . addslashes($cat['categoria']) . "'"; 
        }, $categorias_gastos)) . "],\n";
        echo "    values: [" . implode(',', array_map(function($cat) { 
            return $cat['total']; 
        }, $categorias_gastos)) . "]\n";
        echo "};\n";
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos de gastos</p>";
    }
    
    // Obtener datos de balance mensual
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
    
    echo "<h2>Datos de Balance Mensual:</h2>";
    if (!empty($balance_mensual)) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Mes</th><th>Ingresos</th><th>Gastos</th><th>Balance</th></tr>";
        foreach ($balance_mensual as $item) {
            $mes = date('M Y', strtotime($item['mes'] . '-01'));
            echo "<tr>";
            echo "<td>$mes</td>";
            echo "<td>\${$item['ingresos']}</td>";
            echo "<td>\${$item['gastos']}</td>";
            echo "<td>\${$item['balance']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Generar JavaScript para el gráfico
        echo "<h3>JavaScript generado:</h3>";
        echo "<pre>";
        echo "const balanceData = {\n";
        echo "    labels: [" . implode(',', array_map(function($item) { 
            $mes = date('M Y', strtotime($item['mes'] . '-01'));
            return "'" . addslashes($mes) . "'"; 
        }, $balance_mensual)) . "],\n";
        echo "    values: [" . implode(',', array_map(function($item) { 
            return $item['balance']; 
        }, $balance_mensual)) . "]\n";
        echo "};\n";
        echo "</pre>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay datos de balance mensual</p>";
    }
    
    // Verificar si hay movimientos en general
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM movimientos WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total_movements = $stmt->fetch()['total'];
    
    echo "<h2>Resumen:</h2>";
    echo "<p>Total de movimientos para el usuario: $total_movements</p>";
    
    if ($total_movements == 0) {
        echo "<p style='color: red;'>❌ No hay movimientos. Ejecuta <a href='insert_test_data.php'>insert_test_data.php</a> para crear datos de prueba.</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?> 