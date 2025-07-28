// Funciones de validación de formularios
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'Este campo es obligatorio');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });
    
    // Validación específica para email
    const emailInput = form.querySelector('input[type="email"]');
    if (emailInput && emailInput.value.trim()) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailInput.value)) {
            showFieldError(emailInput, 'Ingrese un email válido');
            isValid = false;
        }
    }
    
    // Validación para contraseña
    const passwordInput = form.querySelector('input[name="password"]');
    if (passwordInput && passwordInput.value.trim()) {
        if (passwordInput.value.length < 6) {
            showFieldError(passwordInput, 'La contraseña debe tener al menos 6 caracteres');
            isValid = false;
        }
    }
    
    return isValid;
}

function showFieldError(field, message) {
    clearFieldError(field);
    field.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Función para mostrar alertas
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container');
    if (container) {
        container.insertBefore(alertDiv, container.firstChild);
        
        // Auto-dismiss después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Función para formatear moneda
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN'
    }).format(amount);
}

// Función para crear gráfico de gastos
function createExpenseChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    // Verificar si hay datos
    if (!data || !data.labels || !data.values || data.values.length === 0) {
        ctx.style.display = 'none';
        return null;
    }
    
    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: [
                    '#6f42c1',
                    '#0d6efd',
                    '#198754',
                    '#dc3545',
                    '#fd7e14',
                    '#6c757d',
                    '#20c997',
                    '#e83e8c'
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    return chart;
}

// Función para crear gráfico de línea de balance
function createBalanceChart(canvasId, data) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;
    
    // Verificar si hay datos
    if (!data || !data.labels || !data.values || data.values.length === 0) {
        ctx.style.display = 'none';
        return null;
    }
    
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
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return formatCurrency(value);
                        }
                    }
                }
            }
        }
    });
    
    return chart;
}

// Función para actualizar totales en tiempo real
function updateTotals() {
    // Los totales ya se calculan en el servidor y se muestran directamente
    // Esta función se mantiene para futuras actualizaciones dinámicas
    
    const totalIncomeEl = document.getElementById('total-income');
    const totalExpenseEl = document.getElementById('total-expense');
    const balanceEl = document.getElementById('balance');
    
    if (totalIncomeEl && totalExpenseEl && balanceEl) {
        const income = parseFloat(totalIncomeEl.textContent.replace(/[^0-9.-]+/g, ''));
        const expense = parseFloat(totalExpenseEl.textContent.replace(/[^0-9.-]+/g, ''));
        const balance = income - expense;
        
        balanceEl.textContent = formatCurrency(balance);
        balanceEl.className = balance >= 0 ? 'text-success' : 'text-danger';
    }
}

// Función para confirmar eliminación
function confirmDelete(message = '¿Está seguro de que desea eliminar este elemento?') {
    return confirm(message);
}

// Función para mostrar/ocultar contraseña
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.querySelector(`[onclick="togglePasswordVisibility('${inputId}')"] i`);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers de Bootstrap
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Actualizar totales si estamos en el dashboard
    if (document.querySelector('.dashboard-card')) {
        updateTotals();
    }
    
    // Auto-hide alerts después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    });
});

// Función para exportar datos (opcional)
function exportData(format = 'csv') {
    const table = document.querySelector('.table');
    if (!table) return;
    
    let csv = '';
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(col => {
            rowData.push('"' + col.textContent.replace(/"/g, '""') + '"');
        });
        csv += rowData.join(',') + '\n';
    });
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'spenless_data.csv';
    a.click();
    window.URL.revokeObjectURL(url);
} 