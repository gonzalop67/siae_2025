// ==========================================
// 1. SELECCIÓN DE ELEMENTOS DEL DOM
// ==========================================
const formulario = document.getElementById("formulario");
// Selecciona inputs de texto/password y textareas
const inputs = document.querySelectorAll("#formulario input:not([type='checkbox']):not([type='file']), #formulario textarea");

const inputIdUsuario = document.getElementById("id_usuario");
const inputAbreviatura = document.getElementById("us_titulo");
const inputDescripcion = document.getElementById("us_titulo_descripcion");
const inputApellidos = document.getElementById("us_apellidos");
const inputNombres = document.getElementById("us_nombres");
const inputNombreCorto = document.getElementById("us_shortname");
const inputNombreCompleto = document.getElementById("us_fullname");
const inputUsuario = document.getElementById("us_login");
const inputEmail = document.getElementById("us_email");
const inputPassword = document.getElementById("us_password");
const buttonSubmit = document.getElementById("btn-save");
const inputFoto = document.getElementById("us_foto");

// ==========================================
// 2. CONFIGURACIÓN DE REGLAS Y BLINDAJE (JS)
// ==========================================
const expresiones = {
    abreviatura: /^[a-zA-Z\.]{3,7}$/,
    descripcion: /^[a-zA-ZÀ-ÿ\s\.\,\-\_\:\;\(\)\n]{4,500}$/, // Permite saltos de línea y puntuación básica
    apellidos: /^[a-zA-ZÀ-ÿ\s]{3,32}$/,
    nombres: /^[a-zA-ZÀ-ÿ\s]{3,32}$/,
    nombre_corto: /^[a-zA-ZÀ-ÿ\s\.]{3,32}$/,
    usuario: /^[a-zA-Z0-9\_\-]{4,16}$/,
    password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&+])[A-Za-z\d$@$!%*?&+]{8,}$/,
    correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/
};

// BLINDAJE F12: Lista de campos estrictamente obligatorios
const camposObligatorios = {
    abreviatura: true,
    descripcion: true,
    apellidos: true,
    nombres: true,
    usuario: true,
    correo: true,
    password: true
};

const campos = {};

// ==========================================
// 3. LÓGICA DE GENERACIÓN AUTOMÁTICA Y MEDIOS
// ==========================================
const generarNombreCorto = () => {
    const nombres = inputNombres.value.trim().split(" ");
    const apellidos = inputApellidos.value.trim().split(" ");

    if (nombres.length && apellidos.length && nombres[0] !== "" && apellidos[0] !== "") {
        const prefijo = inputAbreviatura.value.trim() ? inputAbreviatura.value.trim() + " " : "";
        inputNombreCorto.value = `${prefijo}${nombres[0]} ${apellidos[0]}`;
        inputNombreCompleto.value = inputApellidos.value.trim() + " " + inputNombres.value.trim();
        validarCampo(expresiones.nombre_corto, inputNombreCorto, "nombre_corto");
    }
};

inputAbreviatura.addEventListener("blur", generarNombreCorto);
inputApellidos.addEventListener("blur", generarNombreCorto);
inputNombres.addEventListener("blur", generarNombreCorto);

inputFoto.addEventListener("change", function (e) {
    // Recuperamos el input que desencadenó la acción
    const input = e.target;
    // Recuperamos la etiqueta img donde cargaremos la imagen
    imgPreview = document.querySelector("#us_avatar");
    // Verificamos si existe una imagen seleccionada
    if (!input.files.length) return;
    // Recuperamos el archivo subido
    file = input.files[0];
    // Creamos la url
    objURL = URL.createObjectURL(file);
    // Modificamos el atributo src de la etiqueta img
    imgPreview.src = objURL;
});


// ==========================================
// 4. NÚCLEO DE VALIDACIÓN DINÁMICA (TEXTO/TEXTAREA)
// ==========================================
const validarCampo = (expresion, input, campo) => {
    const errorContainer = document.getElementById(`error-${campo}`);
    const valor = input.value.trim();

    // Caso 1: Obligatorio vacío
    const esRequerido = input.hasAttribute("required") || camposObligatorios[campo];
    if (esRequerido && valor === "") {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if (errorContainer) {
            errorContainer.innerHTML = "Este campo es estrictamente obligatorio.";
            errorContainer.style.display = "block";
        }
        campos[campo] = false;
        return false;
    }

    // Caso 2: Opcional vacío
    if (!esRequerido && valor === "") {
        input.classList.remove("is-invalid");
        input.classList.remove("is-valid");
        if (errorContainer) errorContainer.style.display = "none";
        campos[campo] = true;
        return true;
    }

    // Caso 3: Formato incorrecto (Regex)
    if (expresion && !expresion.test(valor)) {
        input.classList.remove("is-valid");
        input.classList.add("is-invalid");
        if (errorContainer) {
            if (errorContainer.innerHTML === "" || errorContainer.innerHTML.includes("obligatorio")) {
                errorContainer.innerHTML = "El formato ingresado no es válido.";
            }
            errorContainer.style.display = "block";
        }
        campos[campo] = false;
        return false;
    }

    // Caso 4: Nombres vs Apellidos no idénticos
    if ((campo === "nombres" || campo === "apellidos") && inputNombres.value !== "" && inputApellidos.value !== "") {
        if (inputApellidos.value.trim().toLowerCase() === inputNombres.value.trim().toLowerCase()) {
            input.classList.remove("is-valid");
            input.classList.add("is-invalid");
            if (errorContainer) {
                errorContainer.innerHTML = "Los apellidos y nombres no pueden ser iguales.";
                errorContainer.style.display = "block";
            }
            campos[campo] = false;
            return false;
        }
    }

    // Éxito
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
    if (errorContainer) errorContainer.style.display = "none";
    campos[campo] = true;
    return true;
};

// Escucha en tiempo real para inputs y textareas
const validarFormulario = (e) => {
    const nombreCampo = e.target.name || e.target.id;
    const expresionAsociada = expresiones[nombreCampo] || null;
    validarCampo(expresionAsociada, e.target, nombreCampo);
};

inputs.forEach((input) => {
    input.addEventListener("keyup", validarFormulario);
    input.addEventListener("blur", validarFormulario);
    input.addEventListener("input", validarFormulario);
});

// ==========================================
// 5. FUNCIÓN EXCLUSIVA PARA VALIDAR CHECKBOXES
// ==========================================
const validarCheckboxesPerfiles = () => {
    const perfilesChecked = $('input[type="checkbox"][name="perfiles[]"]:checked').length;
    const perfilErrorBlock = document.getElementById("error-perfiles");
    const perfilContainer = document.getElementById("perfiles-container");

    if (perfilesChecked === 0) {
        if (perfilContainer) perfilContainer.classList.add("is-invalid", "border", "border-danger", "p-2", "rounded");
        if (perfilErrorBlock) {
            perfilErrorBlock.innerHTML = "Debe asignar al menos un perfil al usuario.";
            perfilErrorBlock.style.display = "block";
        }
        return false; // Retorna falso si no hay ninguno seleccionado
    } else {
        if (perfilContainer) perfilContainer.classList.remove("is-invalid", "border", "border-danger", "p-2", "rounded");
        if (perfilErrorBlock) perfilErrorBlock.style.display = "none";
        return true; // Retorna verdadero si seleccionó al menos uno
    }
};

// Escucha en tiempo real cuando el usuario marca/desmarca un checkbox (Reemplaza al $(document).on)
document.addEventListener("change", function (e) {
    if (e.target && e.target.type === "checkbox" && e.target.name === "perfiles[]") {
        validarCheckboxesPerfiles();
    }
});

// ==========================================
// 6. PROCESAMIENTO Y ENVÍO DE DATOS (FETCH)
// ==========================================
async function fntProcesar() {
    const url = buttonSubmit.innerText.trim() === "Actualizar" ? "/users/" + inputIdUsuario.value + "/update" : "/users";
    try {
        const formData = new FormData(formulario);
        let resp = await fetch(base_url + url, {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: formData,
        });
        const json = await resp.json();
        if (json.ok) {
            Swal.fire({
                title: "¡Completado!",
                text: json.mensaje,
                icon: "success",
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                formulario.reset();
                window.location.href = base_url + "/users";
            });
        } else if (json.errors) {

            // CAPA DE DESPLIEGUE DINÁMICO EN CADA DIV DE ERROR

            // Recorremos el diccionario de errores que envió el backend [campo => mensaje]
            Object.keys(json.errors).forEach((campo) => {
                const mensajeError = json.errors[campo];

                // 1. Buscamos el div contenedor del error (ej: error-nombres, error-perfiles)
                const errorContainer = document.getElementById(`error-${campo}`);

                // 2. Buscamos el elemento visual (input, textarea o contenedor especial de perfiles)
                let elemento = document.getElementsByName(campo)[0] || document.getElementById(campo);
                if (campo === "perfiles") {
                    elemento = document.getElementById("perfiles-container");
                }

                // 3. Inyectamos el texto del backend y mostramos los estilos de alerta
                if (errorContainer) {
                    errorContainer.innerHTML = mensajeError;
                    errorContainer.style.display = "block";
                }

                if (elemento) {
                    elemento.classList.remove("is-valid");
                    elemento.classList.add("is-invalid");
                    // Si es el bloque de perfiles, agregamos bordes adicionales de Bootstrap
                    if (campo === "perfiles") {
                        elemento.classList.add("border", "border-danger", "p-2", "rounded");
                    }
                }
            });

            // Pequeña alerta para avisar al usuario que hay anomalías abajo
            Swal.fire({
                title: "Error de Validación",
                text: "Por favor, corrige los campos remarcados en rojo.",
                icon: "error"
            });
        } else if (json.mensaje) {
            Swal.fire({
                title: "Error de Proceso",
                text: json.mensaje,
                icon: "error"
            });
        }
    } catch (error) {
        console.error("Error crítico en el servidor: ", error);
    }
}

// Evento Submit Final
formulario.addEventListener("submit", (e) => {
    e.preventDefault();
    let formularioValido = true;

    // 1. Validar dinámicamente todos los inputs y textareas
    inputs.forEach((input) => {
        const nombreCampo = input.name || input.id;
        if (!nombreCampo) return;

        const expresionAsociada = expresiones[nombreCampo] || null;
        const esValido = validarCampo(expresionAsociada, input, nombreCampo);

        if (!esValido) {
            formularioValido = false;
        }
    });

    // 2. Validar obligatoriamente los checkboxes llamando a la nueva función
    const perfilesValidos = validarCheckboxesPerfiles();
    if (!perfilesValidos) {
        formularioValido = false;
    }

    // 3. Resolución final de envío
    if (formularioValido) {
        fntProcesar();
    } else {
        Swal.fire({
            title: "Formulario incompleto",
            text: "Por favor, revisa las casillas marcadas en rojo antes de continuar.",
            icon: "error",
        });
    }
});
