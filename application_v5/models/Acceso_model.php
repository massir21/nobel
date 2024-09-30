<?php
  class Acceso_model extends CI_Model {
    function __construct() {
      parent::__construct();
  }
		
  function ValidarUsuario($parametros) {			
    $AqConexion_model = new AqConexion_model();
    
    $sentencia_sql="SELECT usuarios.id_usuario,usuarios.nombre,usuarios.apellidos,
    perfiles.id_perfil,usuarios.id_centro
    FROM usuarios
    left join (usuarios_perfiles left join perfiles on perfiles.id_perfil
    = usuarios_perfiles.id_perfil)
    on usuarios_perfiles.id_usuario = usuarios.id_usuario
    WHERE usuarios.email = @usuario
    and usuarios.password = @password and usuarios.borrado = 0 ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;			
  }

  function NuevoAcceso($parametros) {
    $AqConexion_model = new AqConexion_model();
    $AqConexion_model->insert('usuarios_accesos',$parametros);
  }
  
  function TienePermiso($modulos,$id_modulo) {
    $sw=0;
    
    foreach ($modulos as $key => $row) {
      if ($row['id_modulo']==$id_modulo) { $sw=1; }
    }
    
    if ($sw==1) {      
      return true;
    }
    else {      
      return false;
    }
  }

}
?>