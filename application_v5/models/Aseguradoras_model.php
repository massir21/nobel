<?php

class Aseguradoras_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getListadoAseguradoras($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $sql = "SELECT p.id_aseguradora, p.nombre as nombre_aseguradora, p.obsoleto FROM aseguradoras p ";
        $sql .= "WHERE p.borrado = 0 ";

        if (isset($parametros['obsoleto'])) {
            $sql .= " AND p.obsoleto = @obsoleto ";
        }

        $sql .= "ORDER BY p.nombre ASC ";


        $datos = $AqConexion_model->select($sql, $parametros);

        return $datos;
    }

    function nuevo_aseguradora($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registros = [];
        $registros['nombre'] = $parametros['nombre'];
        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;
        $registros['fecha_creacion'] = date('Y-m-d H:i:s');
        $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_modificacion'] = '0000-00-00';
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_borrado'] = '0000-00-00'; 
        $registros['id_usuario_borrado'] = 0;
        $registros['borrado'] = 0;

        $AqConexion_model->insert('aseguradoras', $registros);
   
        $sentenciaSQL = "select max(id_aseguradora) as id_aseguradora from aseguradoras";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);
    
        return $resultado[0]['id_aseguradora'];
    }

    function actualizar_aseguradora($parametros = [])
    {
        $AqConexion_model = new AqConexion_model();

        $registros['nombre'] = $parametros['nombre'];
        $registros['obsoleto'] = (isset($parametros['obsoleto'])) ? $parametros['obsoleto'] : 0;

        $registros['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registros['fecha_actualizacion'] = date("Y-m-d H:i:s");
        $registros['id_usuario_actualizacion'] = $this->session->userdata('id_usuario');

        $where['id_aseguradora'] = $parametros['id_aseguradora'];
        $AqConexion_model->update('aseguradoras', $registros, $where);

        return 1;
    }

    function borrar_aseguradora($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "update aseguradoras set borrado = 1,
                        id_usuario_borrado = @id_usuario_borrado,
                        fecha_borrado = @fecha_borrado
                        where id_aseguradora = @id_aseguradora";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        return 1;
    }
    
    function adjuntarFicheros($id_presupuesto, $id_aseguradora)
    {
        $data = array(
            'id_presupuesto' => $id_presupuesto,   
            'id_aseguradora' => $id_aseguradora,   
            'file_tarjeta' => '',
            'file_presupuesto' => '',
        );
        
        if ( isset($_FILES['aseguradora_tarjeta_paciente']) && !$_FILES['aseguradora_tarjeta_paciente']['error'] ){
            
            $extension = pathinfo($_FILES['aseguradora_tarjeta_paciente']['tmp_name'], PATHINFO_EXTENSION);
            $nombre_limpio = $_FILES['aseguradora_tarjeta_paciente']['name'];
            
            $this->load->helper('global_helper');
            $nombre_limpio = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $nombre_limpio);
            $nombre_limpio = limpiar_string($nombre_limpio);
            $nombre_limpio = str_replace(' ', '_', $nombre_limpio);
            $final_name = 'presu'.$id_presupuesto.'_t_'.$nombre_limpio;
            
            $directorioDestino = FCPATH . 'recursos/seguros/' . $id_aseguradora . '/';
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0755, true);
            }
            
            if ( move_uploaded_file($_FILES['aseguradora_tarjeta_paciente']['tmp_name'], $directorioDestino . $final_name) ){
                $data['file_tarjeta'] = $directorioDestino.$final_name;
            }
        }
        
        if ( isset($_FILES['aseguradora_presupuesto']) && !$_FILES['aseguradora_presupuesto']['error'] ){
            
            $extension = pathinfo($_FILES['aseguradora_presupuesto']['tmp_name'], PATHINFO_EXTENSION);
            $nombre_limpio = $_FILES['aseguradora_presupuesto']['name'];
            
            $this->load->helper('global_helper');
            $nombre_limpio = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $nombre_limpio);
            $nombre_limpio = limpiar_string($nombre_limpio);
            $nombre_limpio = str_replace(' ', '_', $nombre_limpio);
            $final_name = 'presu'.$id_presupuesto.'_p_'.$nombre_limpio;
            
            $directorioDestino = FCPATH . 'recursos/seguros/' . $id_aseguradora . '/';
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0755, true);
            }
            
            if ( move_uploaded_file($_FILES['aseguradora_presupuesto']['tmp_name'], $directorioDestino . $final_name) ){
                $data['file_presupuesto'] = $directorioDestino.$final_name;
            }
        }
        
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('seguros_archivos', $data);
    }
    
}