<?php
class Capacidades_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... CAPACIDADES
  // -------------------------------------------------------------------
  function leer_capacidades($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['vacio']="";
    $sentenciaSQL="SET lc_time_names = 'es_VE';";
    $AqConexion_model->no_select($sentenciaSQL,$param);

    $busqueda="";
    
    if (isset($parametros['id_usuario'])) {
      $busqueda.=" AND usuarios_capacidades.id_usuario = @id_usuario ";
    }
    
    if (isset($parametros['id_capacidad'])) {
      $busqueda.=" AND usuarios_capacidades.id_capacidad = @id_capacidad ";
    }
  
    // ... Leemos los registros
    $sentencia_sql="SELECT usuarios_capacidades.id_capacidad,
    usuarios_capacidades.id_usuario,usuarios_capacidades.id_servicio,
    usuarios_capacidades.id_usuario_creacion,usuarios_capacidades.fecha_creacion,
    usuarios_capacidades.id_usuario_modificacion,usuarios_capacidades.fecha_modificacion,
    usuarios_capacidades.borrado,usuarios_capacidades.id_usuario_borrado,
    usuarios_capacidades.fecha_borrado    
    FROM usuarios_capacidades    
    WHERE usuarios_capacidades.borrado = 0 ".$busqueda." ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function nuevo_capacidad($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
     // En primer lugar borramos toda vinculacion del usuario y luego guardamos
     // los servicios indicados.
     $param['id_usuario']=$parametros['id_usuario'];
     $ok=$this->borrar_capacidades($param);
     
     if (isset($parametros['servicios'])) {
      $registro = [];
      foreach($parametros['servicios'] as $servicio) {        
        unset($registro);
        $registro['id_servicio']=$servicio;
        $registro['id_usuario']=$parametros['id_usuario'];
        $registro['fecha_creacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
        $registro['fecha_modificacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $registro['borrado']=0;
        $AqConexion_model->insert('usuarios_capacidades',$registro);      
      }
    }

    $sentenciaSQL="select max(id_capacidad) as id_capacidad from usuarios_capacidades";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);

    return $resultado[0]['id_capacidad'];
  }

  function borrar_capacidades($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    //$sentenciaSQL="DELETE FROM usuarios_capacidades WHERE id_usuario = @id_usuario";
    $sentenciaSQL = "UPDATE usuarios_capacidades SET borrado = 1, id_usuario_borrado = ".$this->session->userdata('id_usuario').", fecha_borrado = '".date('Y-m-d H:i:s')."' WHERE id_usuario = @id_usuario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  // ... Comprueba si un empleado tiene la capacidad para un servicio 1 � 0
  function empleado($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $sentenciaSQL="SELECT id_capacidad FROM usuarios_capacidades
    WHERE borrado = 0 AND id_usuario = @id_usuario_empleado
    AND id_servicio = @id_servicio ";
    
    $resultado = $AqConexion_model->select($sentenciaSQL,$parametros);
    
    if (isset($resultado[0]['id_capacidad'])) {      
      return "1";
    }
    else {      
      return "0";
    }
  }
  
}
?>