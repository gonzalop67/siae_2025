<?php

//Para redireccionar página
function redireccionar($pagina)
{
    header('Location: ' . RUTA_URL . $pagina);
}

// function elimina_acentos($cadena)
// {
//     $tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
//     $replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
//     return (strtr($cadena, $tofind, $replac));
// }
