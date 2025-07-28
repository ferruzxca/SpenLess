# SpenLess - Gestión de Gastos Personales

SpenLess es una aplicación web moderna para gestionar gastos personales desarrollada con PHP, MySQL, HTML, CSS y JavaScript.

## 🚀 Características

- **Registro y autenticación de usuarios** con validación completa
- **Dashboard intuitivo** con resumen de finanzas
- **Registro de ingresos y gastos** con categorías personalizadas
- **Visualización de datos** con gráficos interactivos
- **Exportación de datos** en formato CSV
- **Diseño responsive** y moderno
- **Seguridad avanzada** con encriptación de contraseñas

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Bootstrap 5.3
- **Gráficos**: Chart.js
- **Iconos**: Font Awesome 6.4

## 📋 Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensión PDO para PHP
- Extensión MySQLi para PHP

## 🔧 Instalación

### 1. Clonar o descargar el proyecto

```bash
git clone [URL_DEL_REPOSITORIO]
cd SpenLess
```

### 2. Configurar la base de datos

1. Crear una base de datos MySQL
2. Importar el archivo `database.sql`:
   ```bash
   mysql -u root -p < database.sql
   ```

### 3. Configurar la conexión a la base de datos

Editar el archivo `config.php` y actualizar las credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'spenless_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar el servidor web

#### Para Apache:
1. Colocar los archivos en el directorio web
2. Asegurar que mod_rewrite esté habilitado
3. Configurar permisos de archivos

#### Para Nginx:
```nginx
server {
    listen 80;
    server_name tu-dominio.com;
    root /ruta/a/SpenLess;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 5. Configurar permisos

```bash
chmod 755 -R /ruta/a/SpenLess
chmod 644 config.php
```

## 🚀 Uso

### Acceso inicial

1. Abrir el navegador y acceder a la URL del proyecto
2. Registrar una nueva cuenta o usar las credenciales de prueba:
   - **Email**: admin@spenless.com
   - **Contraseña**: password123

### Funcionalidades principales

1. **Registro de movimientos**: Agregar ingresos y gastos con categorías
2. **Dashboard**: Ver resumen de finanzas y gráficos
3. **Historial**: Revisar todos los movimientos registrados
4. **Exportación**: Descargar datos en formato CSV

## 📁 Estructura del Proyecto

```
SpenLess/
├── config.php              # Configuración principal
├── index.php              # Página de inicio
├── register.php           # Registro de usuarios
├── login.php             # Inicio de sesión
├── dashboard.php         # Panel principal
├── logout.php            # Cierre de sesión
├── database.sql          # Script de base de datos
├── README.md             # Este archivo
├── css/
│   └── style.css         # Estilos personalizados
├── js/
│   └── app.js            # JavaScript principal
└── includes/
    ├── header.php        # Header común
    └── footer.php        # Footer común
```

## 🔒 Seguridad

- **Contraseñas encriptadas** con `password_hash()`
- **Prevención de SQL Injection** con consultas preparadas
- **Sanitización de entradas** para prevenir XSS
- **Validación de sesiones** en todas las páginas protegidas
- **Headers de seguridad** configurados

## 🎨 Personalización

### Colores principales
- **Morado**: #6f42c1
- **Azul**: #0d6efd
- **Verde**: #198754

### Modificar estilos
Editar `css/style.css` para personalizar la apariencia.

### Agregar categorías
Modificar el array de categorías en `dashboard.php`.

## 🐛 Solución de Problemas

### Error de conexión a la base de datos
- Verificar credenciales en `config.php`
- Asegurar que MySQL esté ejecutándose
- Verificar que la base de datos existe

### Error de permisos
- Verificar permisos de archivos y directorios
- Asegurar que el servidor web puede leer los archivos

### Gráficos no se muestran
- Verificar que Chart.js esté cargado correctamente
- Revisar la consola del navegador para errores JavaScript

## 📞 Soporte

Para reportar bugs o solicitar nuevas características, crear un issue en el repositorio del proyecto.

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo LICENSE para más detalles.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crear una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abrir un Pull Request

---

**Desarrollado con ❤️ para ayudar a organizar las finanzas personales** 