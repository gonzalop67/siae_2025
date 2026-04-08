<?php
if (!isset($_SESSION['usuario_logueado'])) {
    redireccionar('/auth');
}
