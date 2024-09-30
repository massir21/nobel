<?php

class Proveedores_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getListadoProveedores($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $sql = "SELECT p.id_proveedor, p.nombre as nombreProveedor, p.obsoleto, p.id_tipo_proveedor, tp.nombre as tipoProveedor, tp.pago_doctor FROM proveedores p ";
        $sql .= "INNER JOIN tipo_proveedores tp ON(p.id_tipo_proveedor = tp.id_tipo) ";
        $sql .= "WHERE p.borrado = 0 ";

        if (isset($parametros['id_proveedor']) && !empty($parametros['id_proveedor'])) {
            $sql .= " AND p.id_proveedor = @id_proveedor";
        }

        if (isset($parametros['obsoleto'])) {
            $sql .= " AND p.obsoleto = @obsoleto ";
        }

        $sql .= "ORDER BY p.nombre ASC ";


        $datos = $AqConexion_model->select($sql, $parametros);

        return $datos;
    }

    function nuevo_proveedor($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['nombre'] = $parametros['nombre'];
        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;
        $registros['fecha_creacion'] = date('Y-m-d H:i:s');
        $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_modificacion'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['borrado'] = 0;
        $registros['id_tipo_proveedor'] = $parametros['id_tipo_proveedor'];


        $AqConexion_model->insert('proveedores', $registros);

        $sentenciaSQL = "select max(id_proveedor) as id_proveedor from proveedores";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id_proveedor'];
    }

    function actualizar_proveedor($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $registros['nombre'] = $parametros['nombre'];
        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;

        $registros['fecha_modificacion'] = $this->session->userdata('id_usuario');
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['id_tipo_proveedor'] = $parametros['id_tipo_proveedor'];

        $where['id_proveedor'] = $parametros['id_proveedor'];
        $AqConexion_model->update('proveedores', $registros, $where);

        return 1;
    }

    function borrar_proveedor($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update proveedores set borrado = 1,
                        id_usuario_borrado = @id_usuario_borrado,
                        fecha_borrado = @fecha_borrado
                        where id_proveedor = @id_proveedor";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }
    function getListadoDoctores(){
        $doctores = $this->db->select("usuarios.id_usuario, nombre, apellidos,usuarios.borrado, id_centro, '' as centro")
        ->join('usuarios',"usuarios.id_usuario=usuarios_perfiles.id_usuario")
        ->where('id_perfil',6)
        ->group_by('usuarios.id_usuario')
        ->order_by('nombre')
        ->get('usuarios_perfiles')->result_array();
        foreach ($doctores as &$doctor) {
            $doctor['centro'] =  $this->db->where('id_centro',$doctor['id_centro'])->get('centros')->row()->nombre_centro;
        }
        return $doctores;
    }
}