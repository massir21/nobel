<?php

class TiposProveedores_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getListadoTiposProveedores($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();


        $sql = "SELECT id_tipo, nombre, pago_doctor FROM tipo_proveedores ";
        $sql .= "WHERE borrado = 0 ";

        if (isset($parametros['id_tipo']) && !empty($parametros['id_tipo'])) {
            $sql .= " AND id_tipo = @id_tipo";
        }


        $datos = $AqConexion_model->select($sql, $parametros);

        return $datos;
    }

    function nuevo_tipo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['nombre'] = $parametros['nombre'];
        $registros['fecha_creacion'] = date("Y-m-d H:i:s");
        $registros['fecha_actualizacion'] = date('Y-m-d H:i:s');
        $registros['borrado'] = 0;
        $registros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_creado'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_modificado'] = $this->session->userdata('id_usuario');

        $AqConexion_model->insert('tipo_proveedores', $registros);

        $sentenciaSQL = "select max(id_tipo) as id_tipo from tipo_proveedores";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_tipo'];
    }

    function actualizar_tipo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['nombre'] = $parametros['nombre'];
        $registros['pago_doctor'] =0;
        if(isset($parametros['pago_doctor'])){
            $registros['pago_doctor'] =1;
        }
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_modificado'] = $this->session->userdata('id_usuario');

        $where['id_tipo'] = $parametros['id_tipo'];
        $AqConexion_model->update('tipo_proveedores', $registros, $where);

        return 1;
    }

    function borrar_tipo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update tipo_proveedores set borrado = 1,
                        id_usuario_borrado = @id_usuario_borrado,
                        fecha_borrado = @fecha_borrado
                        where id_tipo = @id_tipo";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }

}