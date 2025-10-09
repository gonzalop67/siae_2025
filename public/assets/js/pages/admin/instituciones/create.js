const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputNombre = document.getElementById("nombre");
const inputDireccion = document.getElementById("direccion");
const inputEmail = document.getElementById("email");
const inputRegimen = document.getElementById("regimen");
const inputRector = document.getElementById("rector");
const inputVicerrector = document.getElementById("vicerrector");
const inputSecretario = document.getElementById("secretario");
const inputURL = document.getElementById("url");
const inputAMIE = document.getElementById("amie");
const inputCiudad = document.getElementById("ciudad");

const inputAdminUE = document.getElementById("admin_id");
const inputAvatarAdmin = document.getElementById("avatar_admin");

inputAdminUE.addEventListener("change", function (e) {
  if (this.value !== "") {
    document.getElementById("img_admin").style.display = "block";
    fntObtenerImagen(this.value);
  } else {
    document.getElementById("img_admin").style.display = "none";
  }
});

async function fntObtenerImagen(id){
  try {
    const formData = new FormData();
    formData.append("id", id);
    let resp = await fetch(base_url + "instituciones/getAdminImage", {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      body: formData
    });
    json = await resp.json();
    if (json.ok) {
      document.querySelector("#img_admin").classList.remove("d-none");
      inputAvatarAdmin.src = base_url + "public/uploads/" + json.us_foto;
    } else {
      document.querySelector("#img_admin").classList.add("d-none");
    }
  } catch (error) {
    console.log("Ocurrió un error: " + error);
  }
}

const buttonSubmit = document.getElementById("btn-submit");

const inputLogo = document.getElementById("logo");

inputLogo.addEventListener("change", function (e) {
  document.getElementById("img_div").style.display = "block";
  // Recuperamos el input que desencadenó la acción
  const input = e.target;
  // Recuperamos la etiqueta img donde cargaremos la imagen
  $imgPreview = document.querySelector("#avatar");
  document.querySelector("#avatar").classList.remove("d-none");
  // Verificamos si existe una imagen seleccionada
  if (!input.files.length) return;
  // Recuperamos el archivo subido
  file = input.files[0];
  // Creamos la url
  objURL = URL.createObjectURL(file);
  // Modificamos el atributo src de la etiqueta img
  $imgPreview.src = objURL;
});

const expresiones = {
  nombre: /^[a-zA-ZÀ-ÿ\s]{4,64}$/, // nombre de la institución educativa
  direccion: /^[a-zA-ZÀ-ÿ\s]{4,128}$/, // dirección de la institución educativa
  regimen: /^[a-zA-ZÁáÉéÍíÓóÑñ]{5,16}$/, // régimen COSTA, SIERRA, GALAPAGOS, AMAZONIA
  administrativo: /^[a-zA-ZÀ-ÿ\s.]{4,64}$/, // administrativo e.g. MSc. Wilson Proaño
  url: /^https?:\/\/[\w\-]+(\.[\w\-]+)+[\#?]?.*$/, // URL e.g. http://colegionocturnosalamanca.com
  amie: /\d\d[a-zA-Z]\d+/, // Código AMIE de la institución educativa
  correo: /[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}/,
  ciudad: /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-.]+$/, // nombre de ciudad e.g. SANTO DOMINGO DE LOS TSÁCHILAS
};

const campos = {
  nombre: false,
  direccion: false,
  regimen: false,
  administrativo: false,
  url: false,
  amie: false,
  correo: false,
  ciudad: false,
};

const validarFormulario = (e) => {
  switch (e.target.name) {
    case "nombre":
      validarCampo(expresiones.nombre, e.target, e.target.name);
      break;
    case "direccion":
      validarCampo(expresiones.direccion, e.target, e.target.name);
      break;
    case "regimen":
      validarCampo(expresiones.regimen, e.target, e.target.name);
      break;
    case "rector":
      validarCampo(expresiones.administrativo, e.target, e.target.name);
      break;
    case "vicerrector":
      validarCampo(expresiones.administrativo, e.target, e.target.name);
      break;
    case "secretario":
      validarCampo(expresiones.administrativo, e.target, e.target.name);
      break;
    case "url":
      validarCampo(expresiones.url, e.target, e.target.name);
      break;
    case "amie":
      validarCampo(expresiones.amie, e.target, e.target.name);
      break;
    case "email":
      validarCampo(expresiones.correo, e.target, e.target.name);
      break;
    case "ciudad":
      validarCampo(expresiones.ciudad, e.target, e.target.name);
      break;
  }
};

const validarCampo = (expresion, input, campo) => {
  const mensaje_original = document.getElementById(`error-${campo}`).innerHTML;

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
  if (buttonSubmit.innerHTML === "Actualizar los datos de la institución") {
    url = "instituciones/update";
  } else {
    url = "instituciones/store";
  }
  try {
    const formData = new FormData(formulario);
    let copiar_y_pegar = document.getElementById("copiar_y_pegar").checked;
    copiar_y_pegar = (copiar_y_pegar == false) ? '0' : '1';
    formData.append("copiar_y_pegar", copiar_y_pegar);
    let resp = await fetch(base_url + url, {
      method: "POST",
      mode: "cors",
      cache: "no-cache",
      body: formData,
    });
    json = await resp.json();
    if (json.ok) {
      formulario.reset();
      window.location.href = base_url + "instituciones";
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

  if (inputDireccion.value !== "") {
    if (expresiones.direccion.test(inputDireccion.value)) {
      campos["direccion"] = true;
    } else {
      campos["direccion"] = false;
    }
  }

  if (inputEmail.value !== "") {
    if (expresiones.correo.test(inputEmail.value)) {
      campos["email"] = true;
    } else {
      campos["email"] = false;
    }
  }

  if (inputRegimen.value !== "") {
    if (expresiones.regimen.test(inputRegimen.value)) {
      campos["regimen"] = true;
    } else {
      campos["regimen"] = false;
    }
  }

  if (inputRector.value !== "") {
    if (expresiones.administrativo.test(inputRector.value)) {
      campos["rector"] = true;
    } else {
      campos["rector"] = false;
    }
  }

  if (inputVicerrector.value !== "") {
    if (expresiones.administrativo.test(inputVicerrector.value)) {
      campos["vicerrector"] = true;
    } else {
      campos["vicerrector"] = false;
    }
  }

  if (inputSecretario.value !== "") {
    if (expresiones.administrativo.test(inputSecretario.value)) {
      campos["secretario"] = true;
    } else {
      campos["secretario"] = false;
    }
  }

  if (inputURL.value !== "") {
    if (expresiones.url.test(inputURL.value)) {
      campos["url"] = true;
    } else {
      campos["url"] = false;
    }
  }

  if (inputAMIE.value !== "") {
    if (expresiones.amie.test(inputAMIE.value)) {
      campos["amie"] = true;
    } else {
      campos["amie"] = false;
    }
  }

  if (inputCiudad.value !== "") {
    if (expresiones.ciudad.test(inputCiudad.value)) {
      campos["ciudad"] = true;
    } else {
      campos["ciudad"] = false;
    }
  }

  if (
    campos.nombre &&
    campos.direccion &&
    campos.email &&
    campos.regimen &&
    campos.rector &&
    campos.vicerrector &&
    campos.secretario &&
    campos.url &&
    campos.amie &&
    campos.ciudad
  ) {
    // alert("Felicitaciones, pasó todas las validaciones!");
    inputNombre.classList.remove("is-invalid");
    document.getElementById("error-nombre").style.display = "none";
    inputDireccion.classList.remove("is-invalid");
    document.getElementById("error-direccion").style.display = "none";
    inputEmail.classList.remove("is-invalid");
    document.getElementById("error-email").style.display = "none";
    inputRegimen.classList.remove("is-invalid");
    document.getElementById("error-regimen").style.display = "none";
    inputRector.classList.remove("is-invalid");
    document.getElementById("error-rector").style.display = "none";
    inputVicerrector.classList.remove("is-invalid");
    document.getElementById("error-vicerrector").style.display = "none";
    inputSecretario.classList.remove("is-invalid");
    document.getElementById("error-secretario").style.display = "none";
    inputURL.classList.remove("is-invalid");
    document.getElementById("error-url").style.display = "none";
    inputAMIE.classList.remove("is-invalid");
    document.getElementById("error-amie").style.display = "none";
    inputCiudad.classList.remove("is-invalid");
    document.getElementById("error-ciudad").style.display = "none";

    var extension = $("#logo").val().split(".").pop().toLowerCase();

    if (extension != "") {
      if (jQuery.inArray(extension, ["png", "jpg", "jpeg"]) == -1) {
        Swal.fire({
          title: "Error",
          text: "La extensión del archivo de imagen debe ser .png o .jpg o .jpeg",
          icon: "error",
        });

        return false;
      }
    }

    // Restringir el tamaño del archivo de imagen a un máximo de 1 Mb
    var img = document.forms["formulario"]["logo"];
    if (buttonSubmit.innerHTML !== "Actualizar los datos de la institución") {
      if (img.value === "") {
        Swal.fire({
          title: "Error",
          text: "No ha seleccionado un archivo de imagen",
          icon: "error",
        });
        return false;
      }
    }

    if (img.value !== "") {
      if (parseFloat(img.files[0].size / (1024 * 1024)) >= 1) {
        Swal.fire({
          title: "Error",
          text: "El tamaño del archivo de imagen debe ser menor que 1 MB",
          icon: "error",
        });
        return false;
      }
    }

    fntProcesar();
  } else {
    if (!campos.nombre) {
      inputNombre.classList.add("is-invalid");
      document.getElementById("error-nombre").style.display = "block";
    }

    if (!campos.direccion) {
      inputDireccion.classList.add("is-invalid");
      document.getElementById("error-direccion").style.display = "block";
    }

    if (!campos.email) {
      inputEmail.classList.add("is-invalid");
      document.getElementById("error-email").style.display = "block";
    }

    if (!campos.regimen) {
      inputRegimen.classList.add("is-invalid");
      document.getElementById("error-regimen").style.display = "block";
    }

    if (!campos.rector) {
      inputRector.classList.add("is-invalid");
      document.getElementById("error-rector").style.display = "block";
    }

    if (!campos.vicerrector) {
      inputVicerrector.classList.add("is-invalid");
      document.getElementById("error-vicerrector").style.display = "block";
    }

    if (!campos.secretario) {
      inputSecretario.classList.add("is-invalid");
      document.getElementById("error-secretario").style.display = "block";
    }

    if (!campos.url) {
      inputURL.classList.add("is-invalid");
      document.getElementById("error-url").style.display = "block";
    }

    Swal.fire({
      title: "Error",
      text: "Por favor rellena el formulario correctamente.",
      icon: "error",
    });
  }
});
