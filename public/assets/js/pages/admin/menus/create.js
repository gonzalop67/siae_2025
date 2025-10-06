const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputTexto = document.getElementById("texto");
const inputEnlace = document.getElementById("enlace");
const inputIcono = document.getElementById("icono");

const inputPerfil = document.getElementById("perfil");

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
    texto: /^[a-zA-Z0-9À-ÿ.\s]{4,64}$/, // texto del menu
    enlace: /^[a-zA-Z0-9\/\#]{1,64}$/,
};

const campos = {
    texto: false,
    enlace: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "texto":
            validarCampo(expresiones.texto, e.target, e.target.name);
            break;
        case "enlace":
            validarCampo(expresiones.enlace, e.target, e.target.name);
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
        url = "menus/update";
    } else {
        url = "menus/insert";
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
            window.location.href = base_url + "menus";
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

    // Selecciona todos los checkboxes con el atributo name="perfiles"
    const countPerfilesChecked = $(
        'input[type="checkbox"][name="perfiles[]"]:checked'
    ).length;

    if (inputTexto.value !== "") {
        if (expresiones.texto.test(inputTexto.value)) {
            campos["texto"] = true;
        } else {
            campos["texto"] = false;
        }
    }

    if (inputEnlace.value !== "") {
        if (expresiones.enlace.test(inputEnlace.value)) {
            campos["enlace"] = true;
        } else {
            campos["enlace"] = false;
        }
    }

    if (campos.texto &&
        campos.enlace &&
        countPerfilesChecked > 0) {

        inputTexto.classList.remove("is-invalid");
        document.getElementById("error-texto").style.display = "none";

        inputEnlace.classList.remove("is-invalid");
        document.getElementById("error-enlace").style.display = "none";

        document.getElementById("error-perfiles").style.display = "none";

        fntProcesar();
    } else {
        if (!campos.texto) {
            inputTexto.classList.add("is-invalid");
            document.getElementById("error-texto").style.display = "block";
        }

        if (!campos.enlace) {
            inputEnlace.classList.add("is-invalid");
            document.getElementById("error-enlace").style.display = "block";
        }

        if (countPerfilesChecked == 0) {
            document.getElementById("error-perfiles").style.display = "block";
        }

        Swal.fire({
            title: "Error",
            text: "Por favor rellena el formulario correctamente.",
            icon: "error",
        });
    }
});