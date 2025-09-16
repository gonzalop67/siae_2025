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

    public function obtenerPeriodoLectivo($id_periodo_lectivo)
    {
        $query = "SELECT * FROM sw_periodo_lectivo WHERE id_periodo_lectivo = $id_periodo_lectivo";
        $this->db->query($query);
        return $this->db->registro();
    }

    public function obtenerPeriodosLActuales($id_modalidad)
    {
        $this->db->query("SELECT p.*, pe_descripcion FROM sw_periodo_lectivo p, sw_periodo_estado pe WHERE pe.id_periodo_estado = p.id_periodo_estado AND id_modalidad = $id_modalidad AND pe_descripcion = 'ACTUAL' ORDER BY pe_fecha_inicio DESC");
        return $this->db->registros();
    }

    public function obtenerPeriodosL($id_modalidad)
    {
        $this->db->query("SELECT p.*, pe_descripcion FROM sw_periodo_lectivo p, sw_periodo_estado pe WHERE pe.id_periodo_estado = p.id_periodo_estado AND id_modalidad = $id_modalidad ORDER BY pe_fecha_inicio DESC");
        return $this->db->registros();
    }

    public function obtenerIdPeriodoLectivoActual()
    {
        $this->db->query("SELECT pl.id_periodo_lectivo FROM `sw_periodo_lectivo` pl, `sw_periodo_estado` pe WHERE pl.id_periodo_estado = pe.id_periodo_estado AND pe.pe_descripcion = 'ACTUAL' ORDER BY pl.pe_anio_inicio DESC LIMIT 0, 1");
        $registro = $this->db->registro();
        if (empty($registro)){
            return null; 
        }
        return $registro->id_periodo_lectivo;
    }
}
