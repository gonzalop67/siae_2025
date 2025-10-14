const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputArea = document.getElementById("areas");
const inputNombre = document.getElementById("nombre");
const inputAbreviatura = document.getElementById("abreviatura");
const inputTipoAsignatura = document.getElementById("tipos_asignatura");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    nombre: /^[a-zA-Z0-9À-ÿ.\s]{4,64}$/, // nombre de la asignatura
    abreviatura: /^[a-zA-Z0-9\.]{3,8}$/, // abreviatura de la asignatura
};

const campos = {
    nombre: false,
    abreviatura: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "nombre":
            validarCampo(expresiones.nombre, e.target, "nombre");
            break;
        case "abreviatura":
            validarCampo(expresiones.abreviatura, e.target, "abreviatura");
            break;
    }
};

const validarCampo = (expresion, input, campo) => {
    if (expresion.test(input.value)) {
        input.classList.remove("is-invalid");
        document.getElementById(`error-${campo}`).style.display = "none";
        campos[campo] = true;
    } else {
        input.classList.add("is-invalid");
        document.getElementById(`error-${campo}`).style.display = "block";
        campos[campo] = false;
    }
};

inputs.forEach((input) => {
    input.addEventListener("keyup", validarFormulario);
    input.addEventListener("blur", validarFormulario);
});

async function fntProcesar() {
    let url = "";
    if (buttonSubmit.innerHTML === "Actualizar") {
        url = "asignaturas/update";
    } else {
        url = "asignaturas/store";
    }
    try {
        const formData = new FormData(formulario);
        let resp = await fetch(base_url + url, {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: formData,
        });
        json = await resp.json();
        if (json.ok) {
            formulario.reset();
            window.location.href = base_url + "asignaturas";
        } else {
            Swal.fire({
                title: json.titulo,
                text: json.mensaje,
                icon: json.tipo_mensaje,
            });
        }
    } catch (error) {
        console.log("Ocurrió un error: " + error);
    }
}

formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    if (inputNombre.value !== "") {
        if (expresiones.nombre.test(inputNombre.value)) {
            campos["nombre"] = true;
        } else {
            campos["nombre"] = false;
        }
    }

    if (inputAbreviatura.value !== "") {
        if (expresiones.abreviatura.test(inputAbreviatura.value)) {
            campos["abreviatura"] = true;
        } else {
            campos["abreviatura"] = false;
        }
    }

    if (campos.nombre && campos.abreviatura && inputArea.value !== "" && inputTipoAsignatura.value !== "") {
        inputNombre.classList.remove("is-invalid");
        document.getElementById("error-nombre").style.display = "none";

        inputAbreviatura.classList.remove("is-invalid");
        document.getElementById("error-abreviatura").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.nombre) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-nombre").style.display = "block";
        }
        if (!campos.abreviatura) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-abreviatura").style.display = "block";
        }
        if (inputArea.value === "") {
            inputArea.classList.add("is-invalid");
            document.getElementById("error-areas").style.display = "block";
        }
        if (inputTipoAsignatura.value === "") {
            inputTipoAsignatura.classList.add("is-invalid");
            document.getElementById("error-tipos_asignatura").style.display = "block";
        }
    }
});