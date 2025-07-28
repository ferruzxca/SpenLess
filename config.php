<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'spenless_db');
define('DB_USER', 'root');
define('DB_PASS', 'ferr2812');

// Configuración de la aplicación
define('APP_NAME', 'SpendLess');
define('APP_SLOGAN', 'Organiza tu dinero. Mejora tu vida.');

// Configuración de seguridad
define('HASH_COST', 12);

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Función para conectar a la base de datos
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

// Función para verificar si el usuario está autenticado
function isAuthenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Función para redirigir si no está autenticado
function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit();
    }
}

// Función para sanitizar entradas
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para validar email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?> 