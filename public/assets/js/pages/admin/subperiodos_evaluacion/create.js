const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputNombre = document.getElementById("nombre");
const inputAbreviatura = document.getElementById("abreviatura");
const inputTipoPeriodo = document.getElementById("tipo_periodo");

// inputTipoPeriodo.addEventListener("change", function (e) {
//   e.preventDefault();
//   let optionSelectedText = this.options[this.selectedIndex].text;
//   if (optionSelectedText === "SUPLETORIO") {
//     document.getElementById("div_rango").style.display = "block";
//   } else {
//     document.getElementById("div_rango").style.display = "none";
//   }
// })

const buttonSubmit = document.getElementById("btn-submit");

const expresiones = {
  nombre: /^[a-zA-ZÀ-ÿ\s]{4,64}$/, // nombre del subperiodo de evaluación
  abreviatura: /^[a-zA-Z0-9\.]{2,16}$/, // abreviatura del subperiodo de evaluación
};

const campos = {
  nombre: false,
  abreviatura: false,
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

inputs.forEach((input) => {
  input.addEventListener("keyup", validarFormulario);
  input.addEventListener("blur", validarFormulario);
});

async function fntProcesar() {
  let url = "";
  if (buttonSubmit.innerHTML === "Actualizar") {
    url = "subperiodos_evaluacion/update";
  } else {
    url = "subperiodos_evaluacion/store";
  }
  try {
    const formData = new FormData(formulario);
    let optionSelectedText = inputTipoPeriodo.options[inputTipoPeriodo.selectedIndex].text;
    formData.append("nombre_tipo_periodo", optionSelectedText);
    let resp = await fetch(base_url + url, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      body: formData,
    });
    json = await resp.json();
    if (json.ok) {
      formulario.reset();
      window.location.href = base_url + "subperiodos_evaluacion";
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

  if (campos.nombre &&
    campos.abreviatura &&
    inputTipoPeriodo.value !== ""
  ) {
    inputNombre.classList.remove("is-invalid");
    document.getElementById("error-nombre").style.display = "none";

    inputAbreviatura.classList.remove("is-invalid");
    document.getElementById("error-abreviatura").style.display = "none";

    document.getElementById("error-tipo_periodo").style.display = "none";

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
    if (inputTipoPeriodo.value === "") {
      document.getElementById("error-tipo_periodo").style.display = "block";
    }

    Swal.fire({
      title: "Error",
      text: "Por favor rellena el formulario correctamente.",
      icon: "error",
    });
  }
});