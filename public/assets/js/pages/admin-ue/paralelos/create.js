const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputCurso = document.getElementById("curso");
const inputNombre = document.getElementById("nombre");
const inputJornada = document.getElementById("jornada");
const inputPeriodoLectivo = document.getElementById("periodo_lectivo");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    nombre: /^[a-zA-Z]{1,5}$/, // nombre del paralelo
};

const campos = {
    nombre: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "nombre":
            validarCampo(expresiones.nombre, e.target, "nombre");
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
        url = "paralelos/update";
    } else {
        url = "paralelos/insert";
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
            window.location.href = base_url + "paralelos";
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

    if (campos.nombre &&
        inputCurso.value !== "" &&
        inputJornada.value !== "" &&
        inputPeriodoLectivo.value !== "") {

        inputNombre.classList.remove("is-invalid");
        document.getElementById("error-nombre").style.display = "none";

        document.getElementById("error-curso").style.display = "none";
        document.getElementById("error-jornada").style.display = "none";
        document.getElementById("error-periodo_lectivo").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.nombre) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-nombre").style.display = "block";
        }

        if (inputCurso.value === "") {
            document.getElementById("error-curso").style.display = "block";
        }

        if (inputJornada.value === "") {
            document.getElementById("error-jornada").style.display = "block";
        }

        if (inputPeriodoLectivo.value === "") {
            document.getElementById("error-periodo_lectivo").style.display = "block";
        }

        Swal.fire({
            title: "Error",
            text: "Por favor rellena el formulario correctamente.",
            icon: "error",
        });
    }
});