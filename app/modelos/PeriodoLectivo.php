<?php
class PeriodoLectivo
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerPeriodosLectivos()
    {
        $this->db->query("SELECT p.*, pe_descripcion, mo_nombre FROM sw_periodo_lectivo p, sw_periodo_estado pe, sw_modalidad m WHERE pe.id_periodo_estado = p.id_periodo_estado AND m.id_modalidad = p.id_modalidad AND pe_descripcion = 'ACTUAL' ORDER BY pe_fecha_inicio DESC");
        return $this->db->registros();
    }

    public function obtenerPeriodosL($id_modalidad)
    {
        $this->db->query("SELECT p.*, pe_descripcion FROM sw_periodo_lectivo p, sw_periodo_estado pe WHERE pe.id_periodo_estado = p.id_periodo_estado AND id_modalidad = $id_modalidad AND pe_descripcion = 'ACTUAL' ORDER BY pe_fecha_inicio DESC");
        return $this->db->registros();
    }
}
