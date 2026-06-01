// Selectores de elementos
const formulario = document.getElementById('formulario');
const inputs = document.querySelectorAll('#formulario input, #formulario textarea');
const inputIdPermiso = document.getElementById('id_permiso');
const inputNombre = document.getElementById('nombre');
const inputSlug = document.getElementById('slug');
const buttonSubmit = document.getElementById('btn-submit');

// Generación automática del slug (mantiene su ejecución al salir del campo nombre)
const generarSlug = () => {
  let nombre = inputNombre.value;
  let slug = nombre.trim().toLowerCase();

  slug = slug.replace(/[àáäâèéëêìíïîòóöôùúüûñç]/g, function (match) {
    return {
      à: 'a', á: 'a', ä: 'a', â: 'a', è: 'e', é: 'e', ë: 'e', ê: 'e',
      ì: 'i', í: 'i', ï: 'i', î: 'i', ò: 'o', ó: 'o', ö: 'o', ô: 'o',
      ù: 'u', ú: 'u', ü: 'u', ñ: 'n', ç: 'c'
    }[match];
  });

  slug = slug.replace(/[^a-z0-9 -]/g, '');
  slug = slug.replace(/[\s-]+/g, '-');
  slug = slug.replace(/^-+|-+$/g, '');
  inputSlug.value = slug;
};

inputNombre.addEventListener('blur', generarSlug);

// Reglas de validación
const expresiones = {
  nombre: /^[a-zA-ZÀ-ÿ.\s]{4,64}$/,
  slug: /^[a-z0-9_-]{4,64}$/, 
  descripcion: /^[a-zA-Z0-9À-ÿ\s.,#\-\(\)\n]{10,500}$/ 
};

// Función para validar un campo individualmente
const validarCampo = (expresion, input, campo) => {
  const errorEl = document.getElementById(`error-${campo}`);
  
  if (expresion && expresion.test(input.value)) {
    input.classList.remove('is-invalid');
    if (errorEl) errorEl.style.display = 'none';
    return true; 
  } else {
    input.classList.add('is-invalid');
    if (errorEl) {
      errorEl.textContent = `El campo ${campo} no cumple con el formato requerido.`;
      errorEl.style.display = 'block';
    }
    return false; 
  }
};

// Envío a la API 
async function fntProcesar() {
  const url = buttonSubmit.innerText.trim() === 'Actualizar'
    ? '/permissions/' + inputIdPermiso.value + '/update'
    : '/permissions';

  try {
    const formData = new FormData(formulario);
    let resp = await fetch(base_url + url, {
      method: 'POST',
      mode: 'cors',
      cache: 'no-cache',
      body: formData,
    });

    const json = await resp.json();

    if (json.ok) {
      Swal.fire({
        title: '¡Completado!',
        text: json.mensaje,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      }).then(() => {
        formulario.reset();
        window.location.href = base_url + '/permissions';
      });
    } else if (json.errors) {
      Object.keys(json.errors).forEach((campo) => {
        const mensajeError = json.errors[campo];
        const errorContainer = document.getElementById(`error-${campo}`);
        const elementosByName = document.getElementsByName(campo);
        let elemento = (elementosByName.length > 0) ? elementosByName[0] : document.getElementById(campo);

        if (errorContainer) {
          errorContainer.innerHTML = mensajeError;
          errorContainer.style.display = 'block';
        }

        if (elemento) {
          elemento.classList.remove('is-valid');
          elemento.classList.add('is-invalid');
        }
      });

      Swal.fire({
        title: 'Error de Validación',
        text: 'Por favor, corrige los campos remarcados en rojo.',
        icon: 'error'
      });
    } else if (json.mensaje) {
      Swal.fire({ title: 'Error de Proceso', text: json.mensaje, icon: 'error' });
    }
  } catch (error) {
    console.error('Error crítico en el servidor:', error);
  }
}

// Evento Submit: Único punto de validación frontend
formulario.addEventListener('submit', (e) => {
  e.preventDefault();
  let formularioValido = true;

  inputs.forEach((input) => {
    const nombreCampo = input.name || input.id;
    if (!nombreCampo || !expresiones[nombreCampo]) return; 

    const esValido = validarCampo(expresiones[nombreCampo], input, nombreCampo);
    if (!esValido) {
      formularioValido = false;
    }
  });

  if (formularioValido) {
    fntProcesar();
  } else {
    Swal.fire({
      title: 'Formulario incompleto',
      text: 'Por favor, revisa las casillas marcadas en rojo antes de continuar.',
      icon: 'error',
    });
  }
});
