const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input');

const inputTiposAporte = document.getElementById("tipos_aporte");
const inputNombre = document.getElementById("nombre");
const inputAbreviatura = document.getElementById("abreviatura");
const inputDescripcion = document.getElementById("descripcion");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    nombre: /^[a-zA-ZÀ-ÿ\s]{4,256}$/, // Letras y espacios, pueden llevar acentos.
    abreviatura: /^[a-zA-Z0-9.\s]{2,8}$/, // Letras y números sin espacios, pueden llevar acentos.
    // ponderacion: /^(\.[1-9]\d*|0\.[1-9]\d*|1(\.0+)?)$/, // Números entre 0.1 y 1 con hasta dos decimales.
};

const campos = {
    nombre: false,
    abreviatura: false,
    // ponderacion: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "nombre":
            validarCampo(expresiones.nombre, e.target, 'nombre');
            break;
        case "abreviatura":
            validarCampo(expresiones.abreviatura, e.target, 'abreviatura');
            break;
        // case "ponderacion":
        //     validarCampo(expresiones.ponderacion, e.target, 'ponderacion');
        //     break;
    }
};

const validarCampo = (expresion, input, campo) => {
    if (expresion.test(input.value)) {
        document.getElementById(`error-${campo}`).style.display = 'none';
        campos[campo] = true;
    } else {
        document.getElementById(`error-${campo}`).style.display = 'block';
        campos[campo] = false;
    }
};

inputs.forEach((input) => {
    input.addEventListener('keyup', validarFormulario);
    input.addEventListener('blur', validarFormulario);
});

async function fntProcesar() {
    let url = "";
    buttonText = buttonSubmit.innerHTML;
    if (buttonText.indexOf("Actualizar") !== -1) {
        url = "aportes_evaluacion/update";
    } else {
        url = "aportes_evaluacion/store";
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
            window.location.href = base_url + "aportes_evaluacion";
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

    // && inputPonderacion.value !== ""

    if (campos.nombre && campos.abreviatura && inputTiposAporte.value !== "") {
        inputNombre.classList.remove("is-invalid");
        document.getElementById("error-nombre").style.display = "none";

        inputAbreviatura.classList.remove("is-invalid");
        document.getElementById("error-abreviatura").style.display = "none";

        // inputPonderacion.classList.remove("is-invalid");
        // document.getElementById("error-ponderacion").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.nombre) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-nombre").style.display = "block";
        }
        if (!campos.abreviatura) {
            inputAbreviatura.classList.add("is-invalid");
            document.getElementById("error-abreviatura").style.display = "block";
        }
        // if (!campos.ponderacion) {
        //     inputPonderacion.classList.add("is-invalid");
        //     document.getElementById("error-ponderacion").style.display = "block";
        // }
        if (inputTiposAporte.value === "") {
            inputTiposAporte.classList.add("is-invalid");
            document.getElementById("error-tipos_aporte").style.display = "block";
        }
    }
});

