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
export function formatearRut(rut) {
    // Si contiene '@', el usuario probablemente escribe un correo, no formatear como RUT
    if (rut.includes('@')) {
        return rut;
    }

    // Remover caracteres no válidos para RUT (mantener números y k/K)
    const limpio = rut.replace(/[^0-9kK]/g, '').toUpperCase();
    if (limpio.length === 0) {
        return '';
    }

    if (limpio.length <= 1) {
        return limpio;
    }

    const cuerpo = limpio.slice(0, -1);
    const dv = limpio.slice(-1);

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
export function esRutValido(rut) {
    const limpio = rut.replace(/[^0-9kK]/g, '').toUpperCase();

    if (limpio.length < 8 || limpio.length > 9) {
        return false;
    }

    const cuerpo = limpio.slice(0, -1);
    const dv = limpio.slice(-1);

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
 * @param {string} correo
 * @returns {boolean}
 */
export function esCorreoValido(correo) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.trim());
}

/**
 * Inicializa los eventos y comportamientos del formulario de login
 */
export function iniciarLogin() {
    const formularioLogin = document.getElementById('login-form');
    if (!formularioLogin) {
        return;
    }

    const campoIdentificador = document.getElementById('identificador');
    const campoContrasena = document.getElementById('password');
    const botonAlternarContrasena = document.getElementById('toggle-password');
    const iconoOjoAbierto = document.getElementById('eye-icon-open');
    const iconoOjoCerrado = document.getElementById('eye-icon-closed');
    const contenedorErrorCliente = document.getElementById('client-error-container');
    const mensajeErrorCliente = document.getElementById('client-error-message');

    // 1. Mostrar / Ocultar Contraseña
    if (botonAlternarContrasena && campoContrasena) {
        botonAlternarContrasena.addEventListener('click', () => {
            const esTipoPassword = campoContrasena.type === 'password';
            campoContrasena.type = esTipoPassword ? 'text' : 'password';

            if (iconoOjoAbierto && iconoOjoCerrado) {
                if (esTipoPassword) {
                    iconoOjoAbierto.classList.add('hidden');
                    iconoOjoCerrado.classList.remove('hidden');
                    botonAlternarContrasena.setAttribute('aria-label', 'Ocultar contraseña');
                } else {
                    iconoOjoAbierto.classList.remove('hidden');
                    iconoOjoCerrado.classList.add('hidden');
                    botonAlternarContrasena.setAttribute('aria-label', 'Mostrar contraseña');
                }
            }
        });
    }

    // 2. Formateo dinámico de RUT mientras el usuario escribe
    if (campoIdentificador) {
        campoIdentificador.addEventListener('input', (evento) => {
            const valor = evento.target.value;

            // Solo autoformatear si NO parece un correo electrónico
            if (!valor.includes('@')) {
                const posicionCursor = evento.target.selectionStart;
                const valorPrevio = valor;
                const formateado = formatearRut(valor);

                if (valor !== formateado) {
                    evento.target.value = formateado;
                    if (posicionCursor === valorPrevio.length) {
                        evento.target.setSelectionRange(formateado.length, formateado.length);
                    }
                }
            }

            limpiarErrores();
        });
    }

    if (campoContrasena) {
        campoContrasena.addEventListener('input', () => {
            limpiarErrores();
        });
    }

    // Función para mostrar error visual en el formulario
    function mostrarError(mensaje, campoEnfoque = null) {
        if (contenedorErrorCliente && mensajeErrorCliente) {
            mensajeErrorCliente.textContent = mensaje;
            contenedorErrorCliente.classList.remove('hidden');
        }

        if (campoEnfoque) {
            campoEnfoque.focus();
            campoEnfoque.classList.add('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
    }

    // Función para limpiar mensaje de error del cliente
    function limpiarErrores() {
        if (contenedorErrorCliente) {
            contenedorErrorCliente.classList.add('hidden');
        }
        if (campoIdentificador) {
            campoIdentificador.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
        if (campoContrasena) {
            campoContrasena.classList.remove('border-amber-400', 'ring-2', 'ring-amber-400/40');
        }
    }

    // 3. Validación previa al envío (Submit)
    formularioLogin.addEventListener('submit', (evento) => {
        limpiarErrores();

        const identificador = campoIdentificador ? campoIdentificador.value.trim() : '';
        const password = campoContrasena ? campoContrasena.value : '';

        // Validar campo identificador vacío
        if (!identificador) {
            evento.preventDefault();
            mostrarError('Por favor, ingresa tu correo institucional o tu RUT chileno.', campoIdentificador);
            return;
        }

        // Si contiene '@', validar formato de correo
        if (identificador.includes('@')) {
            if (!esCorreoValido(identificador)) {
                evento.preventDefault();
                mostrarError('El correo electrónico ingresado no tiene un formato válido (ejemplo: usuario@colegio.cl).', campoIdentificador);
                return;
            }
        } else {
            // Si no contiene '@', debe ser un RUT chileno válido
            if (!esRutValido(identificador)) {
                evento.preventDefault();
                mostrarError('El RUT ingresado no es válido. Verifica números y dígito verificador (ejemplo: 12.345.678-9 o 12345678-K).', campoIdentificador);
                return;
            }
        }

        // Validar campo contraseña vacío
        if (!password) {
            evento.preventDefault();
            mostrarError('Por favor, ingresa tu contraseña institucional.', campoContrasena);
            return;
        }

        // Validar longitud mínima de contraseña
        if (password.length < 6) {
            evento.preventDefault();
            mostrarError('La contraseña institucional debe tener al menos 6 caracteres.', campoContrasena);
            return;
        }
    });
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarLogin);
} else {
    iniciarLogin();
}
