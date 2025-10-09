const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");

const inputIdUsuario = document.getElementById("id_usuario");

const inputAbreviatura = document.getElementById("abreviatura");
const inputDescripcion = document.getElementById("descripcion");
const inputApellidos = document.getElementById("apellidos");
const inputNombres = document.getElementById("nombres");
const inputNombreCorto = document.getElementById("nombre_corto");
const inputUsuario = document.getElementById("usuario");
const inputPassword = document.getElementById("password");

const buttonSubmit = document.getElementById("btn-submit");

const inputFoto = document.getElementById("foto");

const generarNombreCorto = () => {
  nombres = inputNombres.value.split(" ");
  apellidos = inputApellidos.value.split(" ");
  inputNombreCorto.value =
    inputAbreviatura.value + " " + nombres[0] + " " + apellidos[0];
};

inputAbreviatura.addEventListener("blur", generarNombreCorto);
inputApellidos.addEventListener("blur", generarNombreCorto);
inputNombres.addEventListener("blur", generarNombreCorto);

inputFoto.addEventListener("change", function (e) {
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
  abreviatura: /^[a-zA-Z\.]{3,7}$/, // abreviatura de títulos como Ing. Tlgo. MSc. entre otros
  descripcion: /^[a-zA-ZÀ-ÿ\s]{4,128}$/, // descripción del título como Ingeniero en sistemas informáticos y de computación
  apellidos: /^[a-zA-ZÀ-ÿ\s]{3,32}$/, // apellidos o nombres del usuario
  nombre_corto: /^[a-zA-ZÀ-ÿ\s\.]{3,32}$/, // nombre corto del usuario e.g. Ing. Gonzalo Peñaherrera
  usuario: /^[a-zA-Z0-9\_\-]{4,16}$/, // Letras, numeros, guion y guion_bajo
  password:
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&+])[A-Za-z\d$@$!%*?&+]{8,15}$/, // La contraseña debe contener un dígito del 1 al 9, una letra minúscula, una letra mayúscula, un carácter especial ["$","@","!","%","*","?","&","+"], ningún espacio y debe tener entre 8 y 15 caracteres.
  correo: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
};

const campos = {
  abreviatura: false,
  descripcion: false,
  apellidos: false,
  nombres: false,
  nombre_corto: false,
  usuario: false,
  password: false,
};

const validarFormulario = (e) => {
  switch (e.target.name) {
    case "abreviatura":
      validarCampo(expresiones.abreviatura, e.target, "abreviatura");
      break;
    case "descripcion":
      validarCampo(expresiones.descripcion, e.target, "descripcion");
      break;
    case "apellidos":
      validarCampo(expresiones.apellidos, e.target, "apellidos");
      break;
    case "nombres":
      validarCampo(expresiones.apellidos, e.target, "nombres");
      break;
    case "nombre_corto":
      validarCampo(expresiones.nombre_corto, e.target, "nombre_corto");
      break;
    case "usuario":
      validarCampo(expresiones.usuario, e.target, "usuario");
      break;
    case "password":
      validarCampo(expresiones.password, e.target, "password");
      break;
    case "correo":
      validarCampo(expresiones.correo, e.target, "correo");
      break;
    case "telefono":
      validarCampo(expresiones.telefono, e.target, "telefono");
      break;
  }
};

const validarCampo = (expresion, input, campo) => {
  const mensaje_original = document.getElementById(`error-${campo}`).innerHTML;

  if (expresion.test(input.value)) {
    if (campo === "nombres" || campo === "apellidos") {
      if (inputApellidos.value === inputNombres.value) {
        document.getElementById(`${campo}`).classList.add("is-invalid");
        document.getElementById(`error-${campo}`).innerHTML =
          "Los apellidos y nombres no pueden ser iguales.";
        document.getElementById(`error-${campo}`).style.display = "block";
        campos[campo] = false;
      } else {
        document.getElementById(`${campo}`).classList.remove("is-invalid");
        document.getElementById(`${campo}`).innerHTML = mensaje_original;
        document.getElementById(`error-${campo}`).style.display = "none";
        campos[campo] = true;
      }
    } else {
      input.classList.remove("is-invalid");
      document.getElementById(`error-${campo}`).style.display = "none";
      campos[campo] = true;
    }
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
    url = "usuarios/update";
  } else {
    url = "usuarios/insert";
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
      window.location.href = base_url + "usuarios";
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

  if (inputAbreviatura.value !== "") {
    if (expresiones.abreviatura.test(inputAbreviatura.value)) {
      campos["abreviatura"] = true;
    } else {
      campos["abreviatura"] = false;
    }
  }

  if (inputDescripcion.value !== "") {
    if (expresiones.descripcion.test(inputDescripcion.value)) {
      campos["descripcion"] = true;
    } else {
      campos["descripcion"] = false;
    }
  }

  if (inputApellidos.value !== "") {
    if (expresiones.apellidos.test(inputApellidos.value)) {
      campos["apellidos"] = true;
    } else {
      campos["apellidos"] = false;
    }
  }

  if (inputNombres.value !== "") {
    if (expresiones.apellidos.test(inputNombres.value)) {
      campos["nombres"] = true;
    } else {
      campos["nombres"] = false;
    }
  }

  if (inputUsuario.value !== "") {
    if (expresiones.usuario.test(inputUsuario.value)) {
      campos["usuario"] = true;
    } else {
      campos["usuario"] = false;
    }
  }

  if (inputPassword.value !== "") {
    if (expresiones.password.test(inputPassword.value)) {
      campos["password"] = true;
    } else {
      campos["password"] = false;
    }
  }

  if (
    campos.abreviatura &&
    campos.descripcion &&
    campos.apellidos &&
    campos.nombres &&
    campos.usuario &&
    campos.password &&
    countPerfilesChecked > 0
  ) {
    // alert("Felicitaciones, pasó todas las validaciones!");
    inputAbreviatura.classList.remove("is-invalid");
    document.getElementById("error-abreviatura").style.display = "none";
    inputDescripcion.classList.remove("is-invalid");
    document.getElementById("error-descripcion").style.display = "none";
    inputApellidos.classList.remove("is-invalid");
    document.getElementById("error-apellidos").style.display = "none";
    inputNombres.classList.remove("is-invalid");
    document.getElementById("error-nombres").style.display = "none";
    inputNombreCorto.classList.remove("is-invalid");
    document.getElementById("error-nombre_corto").style.display = "none";
    inputUsuario.classList.remove("is-invalid");
    document.getElementById("error-password").style.display = "none";
    inputPassword.classList.remove("is-invalid");
    document.getElementById("error-password").style.display = "none";
    document.getElementById("error-perfiles").style.display = "none";

    var extension = $("#foto").val().split(".").pop().toLowerCase();

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

    var img = document.forms["formulario"]["foto"];
    if (buttonSubmit.innerHTML !== "Actualizar") {
      if (img.value === "") {
        Swal.fire({
          title: "Error",
          text: "No ha seleccionado un archivo de imagen",
          icon: "error",
        });
        return false;
      }
    }

    // Restringir el tamaño del archivo de imagen a un máximo de 1 Mb
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
    if (!campos.abreviatura) {
      inputAbreviatura.classList.add("is-invalid");
      document.getElementById("error-abreviatura").style.display = "block";
    }
    if (!campos.descripcion) {
      inputDescripcion.classList.add("is-invalid");
      document.getElementById("error-descripcion").style.display = "block";
    }
    if (!campos.apellidos) {
      inputApellidos.classList.add("is-invalid");
      document.getElementById("error-apellidos").style.display = "block";
    }
    if (!campos.nombres) {
      inputNombres.classList.add("is-invalid");
      document.getElementById("error-nombres").style.display = "block";
    }
    if (!campos.usuario) {
      inputUsuario.classList.add("is-invalid");
      document.getElementById("error-usuario").style.display = "block";
    }
    if (!campos.password) {
      inputPassword.classList.add("is-invalid");
      document.getElementById("error-password").style.display = "block";
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
