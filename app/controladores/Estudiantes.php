<?php 
class Estudiantes extends Controlador
{
    private $estudianteModelo;

    public function __construct()
    {
        session_start();
        if (!isset($_SESSION['usuario_logueado'])) {
            redireccionar('/auth');
        }
        $this->estudianteModelo = $this->modelo('Estudiante');
    }

    public function existeDNI()
    {
        $ok = false;
        $error = "";

        try {
            $this->estudianteModelo->existeDNI($_POST['dni']);
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        echo json_encode([
            'ok' => $ok,
            'error' => $error
        ]);
    }
}
?>