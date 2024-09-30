<?php
/*
 CREATE TABLE `laboratorios` (
  `id_laboratorio` int(11) NOT NULL,
  `nombre` varchar(500) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `email` varchar(100) NULL,
  `obsoleto` int(11) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `id_usuario_creacion` int(11) NOT NULL,
  `fecha_modificacion` datetime NOT NULL,
  `id_usuario_modificacion` int(11) NOT NULL,
  `fecha_actualizacion` datetime NOT NULL,
  `id_usuario_actualizacion` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL,
  `fecha_borrado` datetime NOT NULL,
  `id_usuario_borrado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `laboratorios`
  ADD PRIMARY KEY (`id_laboratorio`);
ALTER TABLE `laboratorios`
  MODIFY `id_laboratorio` int(11) NOT NULL AUTO_INCREMENT;



INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES ('81', 'Laboratorios', 'laboratorios', 'Master', '9', '0', '1', '2024-01-01 23:00:00', '1', '2024-01-01 23:00:00', '0', NULL, NULL, '0');


 *
 */


class Laboratorios_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getListadoLaboratorios($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $sql = "SELECT p.id_laboratorio, p.nombre, p.telefono, p.email, p.obsoleto FROM laboratorios p ";
        $sql .= "WHERE p.borrado = 0 ";

        if (isset($parametros['obsoleto'])) {
            $sql .= " AND p.obsoleto = @obsoleto ";
        }

        $sql .= "ORDER BY p.nombre ASC ";


        $datos = $AqConexion_model->select($sql, $parametros);

        return $datos;
    }

    function nuevo_laboratorio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['nombre'] = $parametros['nombre'];
        $registros['telefono'] = $parametros['telefono'];
        if(isset($parametros['email'])){
            $registros['email']=$parametros['email'];
        }
        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;
        $registros['fecha_creacion'] = date('Y-m-d H:i:s');
        $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_modificacion'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['borrado'] = 0;

        $AqConexion_model->insert('laboratorios', $registros);

        $sentenciaSQL = "select max(id_laboratorio) as id_laboratorio from laboratorios";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_laboratorio'];
    }

    function actualizar_laboratorio($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $registros['nombre'] = $parametros['nombre'];
        $registros['telefono'] = $parametros['telefono'];
        if(isset($parametros['email'])){
            $registros['email']=$parametros['email'];
        }
        else $registros['email']='';

        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;

        $registros['fecha_modificacion'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');

        $where['id_laboratorio'] = $parametros['id_laboratorio'];
        $AqConexion_model->update('laboratorios', $registros, $where);

        return 1;
    }

    function borrar_laboratorio($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update laboratorios set borrado = 1,
                        id_usuario_borrado = @id_usuario_borrado,
                        fecha_borrado = @fecha_borrado
                        where id_laboratorio = @id_laboratorio";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }
}