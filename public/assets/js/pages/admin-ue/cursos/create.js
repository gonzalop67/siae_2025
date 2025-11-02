const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputNombre = document.getElementById("nombre");
const inputNombreCorto = document.getElementById("nombre_corto");
const inputAbreviatura = document.getElementById("abreviatura");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    nombre: /^[a-zA-ZÀ-ÿ.\s]{4,64}$/, // nombre de la figura profesional
    abreviatura: /^[0-9a-zA-Z.\s]{3,7}$/,
};

const campos = {
    nombre: false,
    nombre_corto: false,
    abreviatura: false
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "nombre":
        case "nombre_corto":
            validarCampo(expresiones.nombre, e.target, e.target.name);
            break;
        case "abreviatura":
            validarCampo(expresiones.abreviatura, e.target, e.target.name);
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
        url = "cursos/update";
    } else {
        url = "cursos/store";
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
            window.location.href = base_url + "cursos";
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

    if (inputNombreCorto.value !== "") {
        if (expresiones.nombre.test(inputNombreCorto.value)) {
            campos["nombre_corto"] = true;
        } else {
            campos["nombre_corto"] = false;
        }
    }

    if (inputAbreviatura.value !== "") {
        if (expresiones.abreviatura.test(inputAbreviatura.value)) {
            campos["abreviatura"] = true;
        } else {
            campos["abreviatura"] = false;
        }
    }

    // Selecciona todos los checkboxes con el atributo name="perfiles"
    const countSubnivelesChecked = $(
        'input[type="checkbox"][name="subniveles[]"]:checked'
    ).length;

    if (campos.nombre && campos.nombre_corto && campos.abreviatura && countSubnivelesChecked > 0) {
        inputNombre.classList.remove("is-invalid");
        document.getElementById("error-nombre").style.display = "none";

        inputNombreCorto.classList.remove("is-invalid");
        document.getElementById("error-nombre_corto").style.display = "none";

        inputAbreviatura.classList.remove("is-invalid");
        document.getElementById("error-abreviatura").style.display = "none";

        document.getElementById("error-subniveles").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.nombre) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-nombre").style.display = "block";
        }

        if (!campos.nombre_corto) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-nombre_corto").style.display = "block";
        }

        if (!campos.abreviatura) {
            inputNombre.classList.add("is-invalid");
            document.getElementById("error-abreviatura").style.display = "block";
        }

        if (countSubnivelesChecked == 0) {
            document.getElementById("error-subniveles").style.display = "block";
        }

        Swal.fire({
            title: "Error",
            text: "Por favor rellena el formulario correctamente.",
            icon: "error",
        });
    }
});