-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS spenless_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Usar la base de datos
USE spenless_db;

-- Crear tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear tabla de movimientos
CREATE TABLE IF NOT EXISTS movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipo ENUM('ingreso', 'gasto') NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    categoria VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    fecha DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_fecha (fecha),
    INDEX idx_tipo (tipo),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de ejemplo (opcional)
-- Usuario de prueba: admin@spenless.com / password123
INSERT INTO users (nombre, email, password) VALUES 
('Raul Ferruzca', 'ferruzca@spenless.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Movimientos de ejemplo
INSERT INTO movimientos (user_id, tipo, monto, categoria, descripcion, fecha) VALUES 
(1, 'ingreso', 5000.00, 'Salario', 'Salario mensual', '2024-01-15'),
(1, 'gasto', 150.00, 'Alimentación', 'Supermercado', '2024-01-16'),
(1, 'gasto', 80.00, 'Transporte', 'Gasolina', '2024-01-17'),
(1, 'ingreso', 1000.00, 'Freelance', 'Trabajo extra', '2024-01-18'),
(1, 'gasto', 200.00, 'Entretenimiento', 'Cena con amigos', '2024-01-19'),
(1, 'gasto', 120.00, 'Salud', 'Farmacia', '2024-01-20'),
(1, 'ingreso', 300.00, 'Inversión', 'Dividendos', '2024-01-21'),
(1, 'gasto', 350.00, 'Vivienda', 'Luz y agua', '2024-01-22'),
(1, 'gasto', 75.00, 'Alimentación', 'Restaurante', '2024-01-23'),
(1, 'gasto', 45.00, 'Transporte', 'Uber', '2024-01-24'); 