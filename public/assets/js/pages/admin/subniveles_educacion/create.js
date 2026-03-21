const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputNivelId = document.getElementById("nivel_id");
const inputNombre = document.getElementById("nombre");
const inputSlug = document.getElementById("slug");
const inputEsBachillerato = document.getElementById("es_bachillerato");

const buttonSubmit = document.getElementById("btn-submit");

const generarSlug = () => {
  nombre = inputNombre.value;
  // 1. Eliminar espacios al inicio y final
  let slug = nombre.trim();

  // 2. Convertir a minúsculas
  slug = slug.toLowerCase();

  // 3. Eliminar acentos y caracteres especiales mapeando
  slug = slug.replace(/[àáäâèéëêìíïîòóöôùúüûñç]/g, function (match) {
    return {
      à: "a",
      á: "a",
      ä: "a",
      â: "a",
      è: "e",
      é: "e",
      ë: "e",
      ê: "e",
      ì: "i",
      í: "i",
      ï: "i",
      î: "i",
      ò: "o",
      ó: "o",
      ö: "o",
      ô: "o",
      ù: "u",
      ú: "u",
      ü: "u",
      û: "u",
      ñ: "n",
      ç: "c",
    }[match];
  });

  // 4. Reemplazar caracteres no permitidos (letras, números, guiones y espacios) por un guion
  slug = slug.replace(/[^a-z0-9 -]/g, "");

  // 5. Reemplazar espacios múltiples y guiones por un solo guion
  slug = slug.replace(/[\s-]+/g, "-");

  // 6. Eliminar guiones al inicio o al final
  slug = slug.replace(/^-+|-+$/g, "");

  inputSlug.value = slug;
};

const expresiones = {
  nombre: /^[a-zA-Z0-9À-ÿ.\s]{4,64}$/, // nombre del nivel de educacion
  slug: /^[a-zA-Z0-9\_\-]{4,64}$/, // Letras, guion y guion_bajo
};

const campos = {
  nombre: false,
  slug: false,
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
      generarSlug();
      break;
    case "slug":
      validarCampo(expresiones.slug, e.target, "slug");
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
    url = "subniveles_educacion/update";
  } else {
    url = "subniveles_educacion/store";
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
      window.location.href = base_url + "subniveles_educacion";
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

  if (inputSlug.value !== "") {
    if (expresiones.slug.test(inputSlug.value)) {
      campos["slug"] = true;
    } else {
      campos["slug"] = false;
    }
  }

  if (inputNivelId.value === "") {
    document.getElementById("error-nivel_id").style.display = "block";
  }

  if (campos.nombre && campos.slug && inputNivelId.value !== "" && inputEsBachillerato.value !== "") {
    inputNombre.classList.remove("is-invalid");
    inputSlug.classList.remove("is-invalid");
    document.getElementById("error-nombre").style.display = "none";
    document.getElementById("error-slug").style.display = "none";
    document.getElementById("error-nivel_id").style.display = "none";
    document.getElementById("error-es_bachillerato").style.display = "none";

    fntProcesar();
  } else {
    if (!campos.nombre) {
      inputNombre.classList.add("is-invalid");
      document.getElementById("error-nombre").style.display = "block";
    }
    if (!campos.slug) {
      inputSlug.classList.add("is-invalid");
      document.getElementById("error-slug").style.display = "block";
    }
    if (inputNivelId.value == ""){
      document.getElementById("error-nivel_id").style.display = "block";
    }
    if (inputEsBachillerato.value == ""){
      document.getElementById("error-es_bachillerato").style.display = "block";
    }
    Swal.fire({
      title: "Error",
      text: "Por favor rellena el formulario correctamente.",
      icon: "error",
    });
  }
});