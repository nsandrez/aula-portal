/**
 * Lógica interactiva para la vista de Login Escolar
 * - Alternancia de visibilidad de contraseña (mostrar/ocultar)
 * - Formateo y validación de RUT chileno en tiempo real (Módulo 11)
 * - Validación del lado del cliente y despliegue de mensajes de error
 */

/**
 * Limpia y formatea un string a formato de RUT chileno (XX.XXX.XXX-X)
 * @param {string} rut
 * @returns {string}
 */
export function formatRut(rut) {
    // Si contiene '@', el usuario probablemente escribe un correo, no formatear como RUT
    if (rut.includes('@')) {
        return rut;
    }

    // Remover caracteres no válidos para RUT (mantener números y k/K)
    const clean = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    if (clean.length === 0) {
        return '';
    }

    if (clean.length <= 1) {
        return clean;
    }

    const cuerpo = clean.slice(0, -1);
    const dv = clean.slice(-1);

    // Formatear cuerpo con puntos de miles
    let cuerpoFormateado = '';
    let contador = 0;

    for (let i = cuerpo.length - 1; i >= 0; i--) {
        cuerpoFormateado = cuerpo[i] + cuerpoFormateado;
        contador++;
        if (contador === 3 && i > 0) {
            cuerpoFormateado = '.' + cuerpoFormateado;
            contador = 0;
        }
    }

    return `${cuerpoFormateado}-${dv}`;
}

/**
 * Valida un RUT chileno usando el algoritmo de Módulo 11
 * @param {string} rut
 * @returns {boolean}
 */
export function isValidRut(rut) {
    const clean = rut.replace(/[^0-9kK]/g, '').toUpperCase();

    if (clean.length < 8 || clean.length > 9) {
        return false;
    }

    const cuerpo = clean.slice(0, -1);
    const dv = clean.slice(-1);

    if (!/^\d+$/.test(cuerpo)) {
        return false;
    }

    let suma = 0;
    let multiplo = 2;

    for (let i = cuerpo.length - 1; i >= 0; i--) {
        suma += parseInt(cuerpo[i], 10) * multiplo;
        multiplo = multiplo === 7 ? 2 : multiplo + 1;
    }

    const resto = 11 - (suma % 11);
    const dvEsperado = resto === 11 ? '0' : resto === 10 ? 'K' : String(resto);

    return dv === dvEsperado;
}

/**
 * Valida un formato de correo electrónico
 * @param {string} email
 * @returns {boolean}
 */
export function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

/**
 * Inicializa los eventos y comportamientos del formulario de login
 */
export function initLogin() {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) {
        return;
    }

    const identifierInput = document.getElementById('identificador');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const eyeOpenIcon = document.getElementById('eye-icon-open');
    const eyeClosedIcon = document.getElementById('eye-icon-closed');
    const clientErrorContainer = document.getElementById('client-error-container');
    const clientErrorMessage = document.getElementById('client-error-message');

    // 1. Mostrar / Ocultar Contraseña
    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            if (eyeOpenIcon && eyeClosedIcon) {
                if (isPassword) {
                    eyeOpenIcon.classList.add('hidden');
                    eyeClosedIcon.classList.remove('hidden');
                    togglePasswordBtn.setAttribute('aria-label', 'Ocultar contraseña');
                } else {
                    eyeOpenIcon.classList.remove('hidden');
                    eyeClosedIcon.classList.add('hidden');
                    togglePasswordBtn.setAttribute('aria-label', 'Mostrar contraseña');
                }
            }
        });
    }

    // 2. Formateo dinámico de RUT mientras el usuario escribe
    if (identifierInput) {
        identifierInput.addEventListener('input', (event) => {
            const valor = event.target.value;

            // Solo autoformatear si NO parece un correo electrónico
            if (!valor.includes('@')) {
                const cursorPosition = event.target.selectionStart;
                const valorPrevio = valor;
                const formateado = formatRut(valor);

                if (valor !== formateado) {
                    event.target.value = formateado;
                    // Ajustar posición del cursor si es posible
                    if (cursorPosition === valorPrevio.length) {
                        event.target.setSelectionRange(formateado.length, formateado.length);
                    }
                }
            }

            // Limpiar errores visuales si el usuario empieza a corregir
            limpiarErrores();
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', () => {
            limpiarErrores();
        });
    }

    // Helper para mostrar error en el DOM
    function mostrarError(mensaje, inputFocus = null) {
        if (clientErrorContainer && clientErrorMessage) {
            clientErrorMessage.textContent = mensaje;
            clientErrorContainer.classList.remove('hidden');
        }

        if (inputFocus) {
            inputFocus.focus();
            inputFocus.classList.add('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
    }

    // Helper para limpiar mensaje de error del cliente
    function limpiarErrores() {
        if (clientErrorContainer) {
            clientErrorContainer.classList.add('hidden');
        }
        if (identifierInput) {
            identifierInput.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
        if (passwordInput) {
            passwordInput.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
    }

    // 3. Validación previa al envío (Submit)
    loginForm.addEventListener('submit', (event) => {
        limpiarErrores();

        const identificador = identifierInput ? identifierInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value : '';

        // Validar campo identificador vacío
        if (!identificador) {
            event.preventDefault();
            mostrarError('Por favor, ingresa tu correo institucional o tu RUT chileno.', identifierInput);
            return;
        }

        // Si contiene '@', validar formato de correo
        if (identificador.includes('@')) {
            if (!isValidEmail(identificador)) {
                event.preventDefault();
                mostrarError('El correo electrónico ingresado no tiene un formato válido (ejemplo: usuario@colegio.cl).', identifierInput);
                return;
            }
        } else {
            // Si no contiene '@', debe ser un RUT chileno válido
            if (!isValidRut(identificador)) {
                event.preventDefault();
                mostrarError('El RUT ingresado no es válido. Verifica números y dígito verificador (ejemplo: 12.345.678-9 o 12345678-K).', identifierInput);
                return;
            }
        }

        // Validar campo contraseña vacío
        if (!password) {
            event.preventDefault();
            mostrarError('Por favor, ingresa tu contraseña institucional.', passwordInput);
            return;
        }

        // Validar longitud mínima de contraseña
        if (password.length < 6) {
            event.preventDefault();
            mostrarError('La contraseña institucional debe tener al menos 6 caracteres.', passwordInput);
            return;
        }
    });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogin);
} else {
    initLogin();
}
