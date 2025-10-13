<?php
class Subperiodo_evaluacion
{
    private $db;

    public function __construct()
    {
        $this->db = new Base;
    }

    public function obtenerTodos()
    {
        $this->db->query("SELECT * FROM sw_sub_periodo_evaluacion ORDER BY pe_orden ASC");
        return $this->db->registros();
    }

    public function obtener($id)
    {
        $this->db->query("SELECT * FROM sw_sub_periodo_evaluacion WHERE id_sub_periodo_evaluacion = $id");
        return $this->db->registro();
    }

    public function existeCampo($columna, $valor)
    {
        return $this->db->existValueColumn('sw_sub_periodo_evaluacion', $columna, $valor);
    }

    public function insertar($datos)
    {
        // session_start();
        // $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];

        $this->db->query('SELECT MAX(pe_orden) AS max_orden FROM sw_sub_periodo_evaluacion');
        $registro = $this->db->registro();
        $max_orden = (!empty($registro)) ? $registro->max_orden + 1 : 1;

        $this->db->query('INSERT INTO sw_sub_periodo_evaluacion (id_tipo_periodo, pe_nombre, pe_abreviatura, pe_orden) VALUES (:id_tipo_periodo, :pe_nombre, :pe_abreviatura, :orden)');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_abreviatura', $datos['pe_abreviatura']);
        $this->db->bind(':orden', $max_orden);

        $this->db->execute();

        // $this->db->query("SELECT MAX(id_sub_periodo_evaluacion) AS max_id FROM sw_sub_periodo_evaluacion");
        // $registro = $this->db->registro();
        // $max_id = $registro->max_id;

        //Insertar la relación muchos a muchos en la tabla sw_periodo_lectivo_sub_periodo
        // $this->db->query("INSERT INTO sw_periodo_lectivo_sub_periodo SET id_periodo_lectivo = :id_periodo_lectivo, id_sub_periodo_evaluacion = :id_sub_periodo_evaluacion");

        // //Vincular valores
        // $this->db->bind(':id_periodo_lectivo', $id_periodo_lectivo);
        // $this->db->bind(':id_sub_periodo_evaluacion', $max_id);

        // $this->db->execute();

        //Insertar las notas de rango para tomar examen supletorio
        // $nombre_tipo_periodo = $datos['nombre_tipo_periodo'];

        // if ($nombre_tipo_periodo === "SUPLETORIO") {
        //     $this->db->query("INSERT INTO sw_equivalencia_supletorios (id_sub_periodo_evaluacion, id_tipo_periodo, rango_desde, rango_hasta, nombre_examen) VALUES (:id_periodo_lectivo, :id_tipo_periodo, :nota_desde, :nota_hasta, :nombre_tipo_periodo)");

        //     //Vincular valores
        //     $this->db->bind(':id_sub_periodo_evaluacion', $max_id);
        //     $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        //     $this->db->bind(':nota_desde', $datos['nota_desde']);
        //     $this->db->bind(':nota_hasta', $datos['nota_hasta']);
        //     $this->db->bind(':nombre_tipo_periodo', $datos['nombre_tipo_periodo']);

        //     $this->db->execute();
        // }
    }

    public function actualizar($datos)
    {
        $this->db->query('UPDATE sw_sub_periodo_evaluacion SET id_tipo_periodo = :id_tipo_periodo, pe_nombre = :pe_nombre, pe_abreviatura = :pe_abreviatura WHERE id_sub_periodo_evaluacion = :id_sub_periodo_evaluacion');

        //Vincular valores
        $this->db->bind(':id_tipo_periodo', $datos['id_tipo_periodo']);
        $this->db->bind(':pe_nombre', $datos['pe_nombre']);
        $this->db->bind(':pe_abreviatura', $datos['pe_abreviatura']);
        $this->db->bind(':id_sub_periodo_evaluacion', $datos['id_sub_periodo_evaluacion']);

        $this->db->execute();
    }

    public function eliminar($id)
    {
        session_start();
        $id_periodo_lectivo = $_SESSION['id_periodo_lectivo'];

        //Buscar el registro relacionado si existe
        $this->db->query("SELECT * FROM sw_equivalencia_supletorios WHERE id_sub_periodo_evaluacion = :id_sub_periodo_evaluacion AND id_periodo_lectivo = :id_periodo_lectivo");

        //Vincular valores
        $this->db->bind(':id_sub_periodo_evaluacion', $id);
        $this->db->bind(':id_periodo_lectivo', $id_periodo_lectivo);

        $registro = $this->db->registro();

        if (!empty($registro)) {
            //Eliminar el registro asociado en la tabla sw_equivalencia_supletorios
            $this->db->query('DELETE FROM `sw_equivalencia_supletorios` WHERE `id_sub_periodo_evaluacion` = :id_sub_periodo_evaluacion AND id_periodo_lectivo = :id_periodo_lectivo');

            //Vincular valores
            $this->db->bind(':id_sub_periodo_evaluacion', $id);
            $this->db->bind(':id_periodo_lectivo', $id_periodo_lectivo);

            $this->db->execute();
        }

        //Eliminar el registro de definición
        $this->db->query('DELETE FROM `sw_sub_periodo_evaluacion` WHERE `id_sub_periodo_evaluacion` = :id_sub_periodo_evaluacion');

        //Vincular valores
        $this->db->bind(':id_sub_periodo_evaluacion', $id);

        return $this->db->execute();
    }
}
