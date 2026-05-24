const URL_INTERMEDIADOR = 'http://127.0.0.1:8000/api';

// Elementos del DOM
const sectionLogin = document.getElementById('section-login');
const sectionDashboard = document.getElementById('section-dashboard');
const formLogin = document.getElementById('form-login');
const formTransaccion = document.getElementById('form-transaccion');
const btnLogout = document.getElementById('btn-logout');

// Control de flujo de pantallas según la existencia del Token
function verificarSesion() {
    const token = localStorage.getItem('token_comercio');
    if (token) {
        sectionLogin.classList.add('hidden');
        sectionDashboard.classList.remove('hidden');
    } else {
        sectionLogin.classList.remove('hidden');
        sectionDashboard.classList.add('hidden');
    }
}

// 1. Manejo del Inicio de Sesión (Obtener JWT)
formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('login-error');
    
    errorDiv.style.display = 'none';

    try {
        const response = await fetch(`${URL_INTERMEDIADOR}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Error al autenticar');
        }

        // Guardar el Token en el almacenamiento local del navegador
        localStorage.setItem('token_comercio', data.token);
        verificarSesion();
    } catch (err) {
        errorDiv.innerText = err.message;
        errorDiv.style.display = 'block';
    }
});

// 2. Envío de Transacción Protegida con el Token JWT
formTransaccion.addEventListener('submit', async (e) => {
    e.preventDefault();
    const cuenta_origen = document.getElementById('cuenta_origen').value;
    const cuenta_destino = document.getElementById('cuenta_destino').value;
    const monto = document.getElementById('monto').value;
    const alertDiv = document.getElementById('tx-alert');

    alertDiv.style.display = 'none';
    alertDiv.className = 'alert';

    const token = localStorage.getItem('token_comercio');

    try {
        const response = await fetch(`${URL_INTERMEDIADOR}/transaccion`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}` // Envío mandatorio del JWT
            },
            body: JSON.stringify({ cuenta_origen, cuenta_destino, monto })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Error al procesar la transacción');
        }

        alertDiv.innerText = `🎉 ¡Éxito! ${data.mensaje}. ID Registro: ${data.detalle.id}`;
        alertDiv.classList.add('alert-success');
        alertDiv.style.display = 'block';
        formTransaccion.reset();

    } catch (err) {
        alertDiv.innerText = `❌ ${err.message}`;
        alertDiv.classList.add('alert-error');
        alertDiv.style.display = 'block';
    }
});

// 3. Destrucción de Sesión (Logout)
btnLogout.addEventListener('click', () => {
    localStorage.removeItem('token_comercio');
    verificarSesion();
});

// Inicializar el estado de la aplicación al cargar la página
verificarSesion();