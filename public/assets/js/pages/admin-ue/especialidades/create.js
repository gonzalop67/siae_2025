const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputFigura = document.getElementById("figura");
const inputCategoria = document.getElementById("categoria");
const inputAbreviatura = document.getElementById("abreviatura");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    figura: /^[a-zA-ZÀ-ÿ\s]{4,50}$/, // figura profesional de la especialidad
    abreviatura: /^[a-zA-Z\.]{3,15}$/, // abreviatura de títulos como Ing. Tlgo. MSc. entre otros
};

const campos = {
    figura: false,
    abreviatura: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "figura":
            validarCampo(expresiones.figura, e.target, "figura");
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
        url = "especialidades/update";
    } else {
        url = "especialidades/store";
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
            window.location.href = base_url + "especialidades";
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

    if (inputFigura.value !== "") {
        if (expresiones.figura.test(inputFigura.value)) {
            campos["figura"] = true;
        } else {
            campos["figura"] = false;
        }
    }

    if (inputAbreviatura.value !== "") {
        if (expresiones.figura.test(inputAbreviatura.value)) {
            campos["abreviatura"] = true;
        } else {
            campos["abreviatura"] = false;
        }
    }

    if (campos.figura && campos.abreviatura && inputCategoria.value !== "") {
        inputFigura.classList.remove("is-invalid");
        document.getElementById("error-figura").style.display = "none";

        inputCategoria.classList.remove("is-invalid");
        document.getElementById("error-categoria").style.display = "none";

        inputAbreviatura.classList.remove("is-invalid");
        document.getElementById("error-abreviatura").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.figura) {
            inputFigura.classList.add("is-invalid");
            document.getElementById("error-figura").style.display = "block";
        }

        if (!campos.abreviatura) {
            inputAbreviatura.classList.add("is-invalid");
            document.getElementById("error-abreviatura").style.display = "block";
        }

        if (inputCategoria.value === "") {
            inputCategoria.classList.add("is-invalid");
            document.getElementById("error-categoria").style.display = "block";
        }
    }
});