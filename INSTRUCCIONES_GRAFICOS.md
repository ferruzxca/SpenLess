# Instrucciones para Arreglar los Gráficos

## 🔧 Pasos para solucionar los problemas:

### 1. Verificar la Base de Datos
Primero, asegúrate de que la base de datos esté configurada correctamente:

```bash
# Crear la base de datos
mysql -u root -p < database.sql
```

### 2. Insertar Datos de Prueba
Accede a `insert_test_data.php` en tu navegador para crear datos de prueba:
```
http://localhost/SpenLess/insert_test_data.php
```

### 3. Verificar los Datos
Accede a `debug_charts.php` para verificar que los datos se están generando:
```
http://localhost/SpenLess/debug_charts.php
```

### 4. Probar la Conexión
Accede a `test_db.php` para verificar la conexión:
```
http://localhost/SpenLess/test_db.php
```

## 🐛 Posibles Problemas y Soluciones:

### Problema 1: No hay datos en la base de datos
**Solución**: Ejecuta `insert_test_data.php` para crear datos de prueba.

### Problema 2: Chart.js no se carga
**Solución**: Verifica que tengas conexión a internet para cargar Chart.js desde CDN.

### Problema 3: Errores en la consola del navegador
**Solución**: Abre las herramientas de desarrollador (F12) y revisa la consola para ver errores específicos.

### Problema 4: Los gráficos no se muestran
**Solución**: Verifica que:
1. Los datos se están generando correctamente (usar `debug_charts.php`)
2. Chart.js está cargado (verificar en la consola del navegador)
3. Los elementos canvas existen en el DOM

## 📊 Verificación de Funcionamiento:

1. **Accede al dashboard**: `http://localhost/SpenLess/dashboard.php`
2. **Inicia sesión** con las credenciales de prueba:
   - Email: `admin@spenless.com`
   - Contraseña: `password123`
3. **Verifica los gráficos** en la parte inferior de la página
4. **Revisa la consola** del navegador para mensajes de debug

## 🔍 Debug Avanzado:

Si los gráficos siguen sin funcionar, puedes:

1. **Verificar los datos** con `debug_charts.php`
2. **Revisar la consola** del navegador para errores JavaScript
3. **Verificar la red** para asegurar que Chart.js se carga correctamente
4. **Probar manualmente** creando un gráfico simple en la consola del navegador

## 📝 Notas Importantes:

- Los gráficos ahora tienen funciones simplificadas integradas en el dashboard
- Se agregaron mensajes de debug en la consola
- Los gráficos se ocultan automáticamente si no hay datos
- Se agregaron validaciones para evitar errores

## 🎯 Resultado Esperado:

Después de seguir estos pasos, deberías ver:
- ✅ Gráfico de dona para "Gastos por Categoría"
- ✅ Gráfico de línea para "Balance Mensual"
- ✅ Totales correctos en las tarjetas superiores
- ✅ Mensajes informativos si no hay datos 