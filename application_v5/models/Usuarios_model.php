<?php
class Usuarios_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... USUARIOS
  // -------------------------------------------------------------------
  function leer_usuarios($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['borrado'])) {
      $busqueda.=" AND usuarios.borrado = @borrado ";
    }
    
    if (isset($parametros['id_usuario'])) {
      $busqueda.=" AND usuarios.id_usuario = @id_usuario ";
    }
    
    if (isset($parametros['id_centro'])) {
      if ($parametros['id_centro'] > 0) {        
        $busqueda.=" AND usuarios.id_centro = @id_centro ";
      }
    }
    
    if (isset($parametros['email'])) {
      $busqueda.=" AND usuarios.email = @email ";
    }
    
    # ... Lee solo los usuario empleado y encargado
    if (isset($parametros['solo_empleados'])) {
      $busqueda.=" AND (usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 3 or usuarios_perfiles.id_perfil = 6) and usuarios.borrado = 0 ";
    }
    
    # ... Lee solo los usuario recepcionista y encargado.
    if (isset($parametros['solo_recepcionistas_encargados'])) {
      $busqueda.=" AND (usuarios_perfiles.id_perfil = 2 or usuarios_perfiles.id_perfil = 3) and usuarios.borrado = 0 ";
    }
    
    # ... Menos los master
    if (isset($parametros['solo_empleados_recepcionistas'])) {
      $busqueda.=" AND (usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 2 or usuarios_perfiles.id_perfil = 3 or usuarios_perfiles.id_perfil = 6) and usuarios.borrado = 0 ";
    }
    
    # ... Lee solo los usuario empleado recepcionista encargado
    # esto lo uso para asignar horarios y capacidades.
    if (isset($parametros['todos_empleados'])) {
      $busqueda.=" AND (usuarios_perfiles.id_perfil = 1
      or usuarios_perfiles.id_perfil = 2 or usuarios_perfiles.id_perfil = 3) ";
    }

    # ... Lee solo los usuario con el perfil indicado.
    if (isset($parametros['id_perfil'])) {
      $busqueda.=" AND usuarios_perfiles.id_perfil = @id_perfil";
    }
    

    # ... Lee solo los usuarios empleado y encargado
    # Pero solo que tengan horarios asignados
    if (isset($parametros['solo_empleados_con_horarios'])) {
      $busqueda.=" AND (usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 3 or usuarios_perfiles.id_perfil = 6)
      and usuarios.id_usuario in (select id_usuario from usuarios_horarios
      WHERE
      usuarios_horarios.borrado = 0 AND
      usuarios_horarios.jornada != 'Vacaciones' AND 
      usuarios_horarios.jornada != 'Baja' AND
      DATE_FORMAT(fecha_inicio,'%Y-%m-%d') <= @fecha_agenda and
      DATE_FORMAT(fecha_fin,'%Y-%m-%d') >= @fecha_agenda) ";
    }
    
    # ... Lee solo los usuarios de Velazquez
    if (isset($parametros['velazquez'])) {
      if ($parametros['velazquez']>0) {
        $busqueda.=" AND (usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 3)
        and usuarios.id_usuario in
          (select id_usuario from usuarios_horarios where
          usuarios_horarios.borrado = 0 and
          DATE_FORMAT(fecha_inicio,'%Y-%m-%d') <= @fecha_agenda and
          DATE_FORMAT(fecha_fin,'%Y-%m-%d') >= @fecha_agenda)
        and usuarios.id_centro = 10 ";
      }
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT usuarios.id_usuario,usuarios.nombre,usuarios.apellidos,
    usuarios.email,usuarios.telefono,usuarios.id_centro,usuarios.password,usuarios.nif,usuarios.domicilio,usuarios.provincia,usuarios.n_colegiado,
    usuarios.id_usuario_creacion,usuarios.fecha_creacion,usuarios.id_usuario_modificacion,
    usuarios.fecha_modificacion,usuarios.borrado,usuarios.id_usuario_borrado,
    usuarios.fecha_borrado,usuarios_perfiles.id_perfil,perfiles.nombre_perfil,
    centros.nombre_centro,centros.direccion_centro,usuarios.color,usuarios.horas_semana,
    usuarios.empresa,
    usuarios.cif
    FROM usuarios
    left join centros on centros.id_centro = usuarios.id_centro
    left join (usuarios_perfiles left join perfiles on perfiles.id_perfil
    = usuarios_perfiles.id_perfil)
    on usuarios_perfiles.id_usuario = usuarios.id_usuario
    WHERE 1=1 ".$busqueda." ORDER BY nombre,apellidos ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  //27/06/20
  function busqueda_id_usuario($parametros){
      $AqConexion_model = new AqConexion_model();
       
    $sentencia_sql="SELECT usuarios.id_usuario,usuarios.nombre,usuarios.apellidos,usuarios.n_colegiado,usuarios.id_centro FROM usuarios
       WHERE id_usuario = @id_usuario AND borrado=0";
    $datos = $AqConexion_model->select($sentencia_sql, $parametros);
    
    return $datos;
  }
  //Fin
 
  function nuevo_usuario($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    $param['id_usuario']=0;
    $param['email']=$parametros['email'];
    $ok=$this->existe_email($param);

    if (!$ok && $parametros['nombre'] != "" && $parametros['email'] != "") {      
      // ... Datos generales como usuario.
      $registro['nombre']=$parametros['nombre'];
      $registro['apellidos']=$parametros['apellidos'];
      $registro['email']=$parametros['email'];
      $registro['telefono']=$parametros['telefono'];
      $registro['nif']=$parametros['nif'];
      $registro['domicilio']=$parametros['domicilio'];
      $registro['provincia']=$parametros['provincia'];
      $registro['n_colegiado']=$parametros['n_colegiado'];
      $registro['empresa']=$parametros['empresa'];
      $registro['cif']=$parametros['cif'];

      $registro['color']=$parametros['color'];
      $registro['id_centro']=$parametros['id_centro'];
      $registro['password']=$parametros['password'];  
      $registro['horas_semana']=$parametros['horas_semana'];       
      
      $registro['fecha_creacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $registro['borrado']=0;
      
      $AqConexion_model->insert('usuarios',$registro);
      
      $sentenciaSQL="select max(id_usuario) as id_usuario from usuarios";
      
      $resultado = $AqConexion_model->select($sentenciaSQL,null);
      
      // ... Guardamos el perfil
      unset($param);
      $param['id_usuario']=$resultado[0]['id_usuario'];
      $param['id_perfil']=$parametros['id_perfil'];      
      $this->nuevo_usuario_perfil($param);
      
      return $resultado[0]['id_usuario'];
    }
    else {
      return 0;
    }
  }

  function leer_id_usuario_nombre($parametros) {
    $AqConexion_model = new AqConexion_model();

    $email=$parametros['cad1']."@".$parametros['cad2'];
    
    $sentencia_sql="SELECT * FROM usuarios
       WHERE email like '".$email."'  ";
      $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    
    return $datos;
  }

  function actualizar_usuario($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_usuario']=$parametros['id_usuario'];    
    $param['email']=$parametros['email'];
    $ok=$this->existe_email($param);

    if (!$ok && $parametros['nombre'] != "" && $parametros['email'] != "") {      
      // ... Datos generales como usuario.
      $registro['nombre']=$parametros['nombre'];
      $registro['apellidos']=$parametros['apellidos'];
      $registro['email']=$parametros['email'];
      $registro['telefono']=$parametros['telefono'];
      
      $registro['nif']=$parametros['nif'];
      $registro['domicilio']=$parametros['domicilio'];
      $registro['provincia']=$parametros['provincia'];
      $registro['n_colegiado']=$parametros['n_colegiado'];
      $registro['empresa']=$parametros['empresa'];
      $registro['cif']=$parametros['cif'];

      $registro['color']=$parametros['color'];
      $registro['id_centro']=$parametros['id_centro'];
      $registro['password']=$parametros['password'];
      $registro['horas_semana']=$parametros['horas_semana'];

      if (isset($parametros['borrado'])) {
        $registro['borrado']=$parametros['borrado'];        
      }
      else {
        $registro['borrado']=0;
      }
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      
      $where['id_usuario']=$parametros['id_usuario'];
      
      $AqConexion_model->update('usuarios',$registro,$where);
      
      // ... Guardamos el perfil
      unset($param);
      $param['id_usuario']=$parametros['id_usuario'];
      // esto es para que el usuario 1, siempre sea master y no se pueda modificar.
      if ($parametros['id_usuario'] > 1) {
        $param['id_perfil']=$parametros['id_perfil'];
      }
      else {
        $param['id_perfil']=0;
      }
      $this->nuevo_usuario_perfil($param);
      
      return 1;
    }
    else {
      return 0;
    }
  }

  function borrar_usuario($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $sentenciaSQL="delete from usuarios_tickets where id_usuario = @id_usuario";				
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    $parametros['id_usr_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    // ... Marcamos a los usuarios como borrados
    $sentenciaSQL="update usuarios set borrado = 1,
    id_usuario_borrado = @id_usr_borrado,
    fecha_borrado = @fecha_borrado
    where id_usuario = @id_usuario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Marcamos a los horarios del usuario como borrados
    //$sentenciaSQL="update usuarios_horarios set borrado = 1,
    //id_usuario_borrado = @id_usr_borrado,
    //fecha_borrado = @fecha_borrado
    //where id_usuario = @id_usuario";
    //$AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Marcamos a las capacidades del usuario como borradas
    $sentenciaSQL="update usuarios_capacidades set borrado = 1,
    id_usuario_borrado = @id_usr_borrado,
    fecha_borrado = @fecha_borrado
    where id_usuario = @id_usuario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function recuperar_usuario($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    // ... Marcamos a los usuarios como recuperados
    $sentenciaSQL="update usuarios set borrado = 0,
    id_usuario_borrado = null,
    fecha_borrado = null
    where id_usuario = @id_usuario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Marcamos a los horarios del usuario como recuperados
    //$sentenciaSQL="update usuarios_horarios set borrado = 0,
    //id_usuario_borrado = null,
    //fecha_borrado = null
    //where id_usuario = @id_usuario";
    //$AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    // ... Marcamos a las capacidades del usuario como recuperados
    $sentenciaSQL="update usuarios_capacidades set borrado = 0,
    id_usuario_borrado = null,
    fecha_borrado = null
    where id_usuario = @id_usuario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function nuevo_usuario_perfil($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales como usuario.
    $registro['id_usuario']=$parametros['id_usuario'];
    $registro['id_perfil']=$parametros['id_perfil'];    
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $param['id_usuario']=$parametros['id_usuario'];
    $AqConexion_model->delete('usuarios_perfiles',$param);
    
    $AqConexion_model->insert('usuarios_perfiles',$registro);
    
    return 1;
  }
  
  function existe_email($parametros) {
    $email_usuario="";
    
    if ($parametros['id_usuario']>0) {
      $param['id_usuario']=$parametros['id_usuario'];				
      $usuario = $this->leer_usuarios($param);
      $email_usuario=$usuario[0]['email'];
    }				
    
    $param['email']=strtolower($parametros['email']);
    
    if ($email_usuario!=$param['email']) {
      unset($param);						
      
      $param['email']=strtolower($parametros['email']);      
      $usuario=$this->leer_usuarios($param);
      
      if ($usuario > 0) {
        return true;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }
  
  // -------------------------------------------------------------------
  // ... CENTROS
  // -------------------------------------------------------------------
  function leer_centros($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND centros.id_centro = @id_centro ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_centro,nombre_centro,codigo,
    email,telefono,estado,saldo_inicial,emails_informe_diario,id_usuario_creador,razon_social_centro,cif_centro,direccion_centro,
    fecha_creacion,id_usuario_modificacion,direccion_completa,empresa,
    fecha_modificacion,borrado,id_usuario_borrado,fecha_borrado,habilitado_gestoria,email_gestoria,link_formulario_evaluacion
    FROM centros     
    WHERE borrado = 0 ".$busqueda." ORDER BY id_centro ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function nuevo_centro($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales como usuario.
    $registro['nombre_centro']=$parametros['nombre_centro'];      
    $registro['email']=$parametros['email'];
    $registro['telefono']=$parametros['telefono'];
    $registro['estado']=$parametros['estado'];
    $registro['saldo_inicial']=$parametros['saldo_inicial'];
    $registro['emails_informe_diario']=$parametros['emails_informe_diario'];

    $registro['razon_social_centro']=$parametros['razon_social_centro'];
    $registro['cif_centro']=$parametros['cif_centro'];
    $registro['direccion_centro']=$parametros['direccion_centro'];
    
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    //27/12/20
    $registro['email_gestoria']=$parametros['email_gestoria'];
    $registro['habilitado_gestoria']=$parametros['habilitado_gestoria'];
    
    
    $AqConexion_model->insert('centros',$registro);
      
    $sentenciaSQL="select max(id_centro) as id_centro from centros";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);
    
    // ... Creamos el stock en todos los productos para el nuevo centro.
    unset($param);
    $param['vacio']="";
    $productos = $this->Productos_model->leer_productos($param);
    if(is_array($productos)) { 
    foreach ($productos as $row) {
      $stock['fecha_creacion']=date("Y-m-d H:i:s");
      $stock['id_usuario_creacion']=$this->session->userdata('id_usuario');
      $stock['fecha_modificacion']=date("Y-m-d H:i:s");
      $stock['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $stock['borrado']=0;    
      $stock['cantidad_stock']=0;    
      $stock['id_producto']=$row['id_producto'];
      $stock['id_centro']=$resultado[0]['id_centro'];    
      $AqConexion_model->insert('productos_stock',$stock);
    }
  }
    //
    
    return $resultado[0]['id_centro'];      
  }

  function actualizar_centro($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_centro']=$parametros['id_centro'];    
    
    // ... Datos generales como usuario.
    $registro['nombre_centro']=$parametros['nombre_centro'];
    $registro['email']=$parametros['email'];
    $registro['telefono']=$parametros['telefono'];
    $registro['estado']=$parametros['estado'];
    $registro['saldo_inicial']=$parametros['saldo_inicial'];
    $registro['emails_informe_diario']=$parametros['emails_informe_diario'];

    $registro['razon_social_centro']=$parametros['razon_social_centro'];
    $registro['cif_centro']=$parametros['cif_centro'];
    $registro['direccion_centro']=$parametros['direccion_centro'];
	$registro['link_formulario_evaluacion']=$parametros['link_formulario_evaluacion'];


    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    //27/12/20
    $registro['email_gestoria']=$parametros['email_gestoria'];
    $registro['habilitado_gestoria']=(isset($parametros['habilitado_gestoria']))?1:0;
    
    $where['id_centro']=$parametros['id_centro'];    
    $AqConexion_model->update('centros',$registro,$where);
    
    return 1;    
  }

  function borrar_centro($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update centros set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_centro = @id_centro";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  
  // -------------------------------------------------------------------
  // ... PERFILES
  // -------------------------------------------------------------------
  function leer_perfiles($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_perfil'])) {
      $busqueda.=" AND perfiles.id_perfil = @id_perfil ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_perfil,nombre_perfil,
    id_usuario_creacion,fecha_creacion,id_usuario_modificacion,
    fecha_modificacion,borrado,id_usuario_borrado,fecha_borrado,
    '' as modulos
    FROM perfiles     
    WHERE borrado = 0 ".$busqueda." ORDER BY nombre_perfil ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    # ... Leemos los modulos asociados
    for ($i=0; $i<count($datos); $i++) {
      $param['id_perfil']=$datos[$i]['id_perfil'];
      $modulos = $this->leer_modulos($param);
      
      if ($modulos != 0) {
        foreach ($modulos as $cada) {
          $datos[$i]['modulos'].=$cada['padre']." - ".$cada['nombre_modulo']."<br>";
        }
      }
      else {
        $datos[$i]['modulos']="Sin Módulos";
      }
    }
    
    return $datos;
  }
  
  function actualizar_perfil($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $registro['nombre_perfil']=$parametros['nombre_perfil'];      
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      
    $where['id_perfil']=$parametros['id_perfil'];
      
    $AqConexion_model->update('perfiles',$registro,$where);

    # ... Guardamos los modulos indicados.
    $sentenciaSQL="delete from modulos_perfiles where id_perfil = @id_perfil";				
    $AqConexion_model->no_select($sentenciaSQL,$where);
    
    if (isset($parametros['id_modulo'])) {
      foreach($parametros['id_modulo'] as $modulo) {
        unset($registro);
        $registro['id_modulo']=$modulo;
        $registro['id_perfil']=$parametros['id_perfil'];
        $registro['fecha_creacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
        $registro['fecha_modificacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $registro['borrado']=0;
      
        $AqConexion_model->insert('modulos_perfiles',$registro);      
      }
    }
    
    return 1;
  }

  function borrar_perfil($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update perfiles set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_perfil = @id_perfil";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  function leer_modulos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_modulo'])) {
      $busqueda.=" AND modulos.id_modulo = @id_modulo ";
    }
    
    if (isset($parametros['id_perfil'])) {
      # ... Si es el perlfil es Master, siempre lee todos los modulos.      
      if ($parametros['id_perfil'] > 0) {        
          $busqueda.=" AND modulos.id_modulo in (select id_modulo from modulos_perfiles
          where id_perfil = @id_perfil and borrado = 0) ";        
      }      
      else {
        if ($this->session->userdata('id_usuario')==125) {
          $busqueda.=" AND (modulos.id_modulo = 9
          or modulos.id_modulo = 18
          or modulos.id_modulo = 23
          or modulos.id_modulo = 25
          or modulos.id_modulo = 26
          or modulos.id_modulo = 27
          or modulos.id_modulo = 28
          or modulos.id_modulo = 29
          or modulos.id_modulo = 14
          or modulos.id_modulo = 15
          or modulos.id_modulo = 16
          or modulos.id_modulo = 17          
          or modulos.id_modulo = 5
          or modulos.id_modulo = 10
          or modulos.id_modulo = 30
          or modulos.id_modulo = 33
          or modulos.id_modulo = 34
          or modulos.id_modulo = 35
          or modulos.id_modulo = 36
                    
          or modulos.id_modulo = 9
          or modulos.id_modulo = 18
          
          or modulos.id_modulo = 22
          or modulos.id_modulo = 32
          
          ) ";
        }
      }      
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_modulo,nombre_modulo,modulos.url,
    padre,orden,orden_item,id_usuario_creacion,fecha_creacion,id_usuario_modificacion,
    fecha_modificacion,borrado,id_usuario_borrado,fecha_borrado
    FROM modulos
    WHERE borrado = 0 ".$busqueda." ORDER BY orden,orden_item,nombre_modulo ";
    
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function leer_provincias()
  {
    return $this->db->get('provincias')->result_array();
  }

  function leer_comisiones($parametros)
  {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_usuario'])) {
      $busqueda.=" AND uc.id_usuario = @id_usuario ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT 
    uc.*,
    CASE 
      WHEN uc.item = 'producto' THEN pf.nombre_familia
      WHEN uc.item = 'servicio' THEN sf.nombre_familia
      ELSE NULL
    END AS nombre_familia,
    CASE 
      WHEN uc.item = 'producto' THEN p.nombre_producto
      WHEN uc.item = 'servicio' THEN s.nombre_servicio
      ELSE NULL
    END AS nombre_item
    FROM usuarios_comisiones uc
    LEFT JOIN productos_familias pf ON uc.item = 'producto' AND uc.id_familia_item = pf.id_familia_producto
    LEFT JOIN productos p ON uc.item = 'producto' AND uc.id_item = p.id_producto
    LEFT JOIN servicios_familias sf ON uc.item = 'servicio' AND uc.id_familia_item = sf.id_familia_servicio
    LEFT JOIN servicios s ON uc.item = 'servicio' AND uc.id_item = s.id_servicio
    WHERE uc.borrado = 0 ".$busqueda." ORDER BY uc.item ASC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function nueva_comision($parametros) {    
    $AqConexion_model = new AqConexion_model();
   
      // ... Datos generales como comision.
      $registro['item']=$parametros['item'];
      $registro['id_familia_item']= $parametros['id_familia_item'];
      $registro['id_item']=$parametros['id_item'];
      $registro['tipo']=$parametros['tipo'];
      $registro['comision']=$parametros['comision'];
      $registro['importe_desde']=($parametros['tipo'] == 'tramo')?$parametros['importe_desde']:0;
      $registro['importe_hasta']=($parametros['tipo'] == 'tramo')?$parametros['importe_hasta']:0;
      $registro['id_usuario']=$parametros['id_usuario'];

      $registro['fecha_creacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $registro['borrado']=0;
      
      $AqConexion_model->insert('usuarios_comisiones',$registro);
      if($this->db->affected_rows() > 0){
        return 1;
      }else{
        return 0;
      }
  }

  function actualizar_comision($parametros) {
    $AqConexion_model = new AqConexion_model();
     
      // ... Datos generales como usuario.
      if (isset($parametros['borrado'])) {
        $registro['borrado']=$parametros['borrado'];
        if($registro['borrado'] == 1){
            $registro['fecha_borrado']=date("Y-m-d H:i:s");
            $registro['id_usuario_borrado']=$this->session->userdata('id_usuario'); 
        }
      } else {
        $registro['item']=$parametros['item'];
        $registro['id_familia_item']= $parametros['id_familia_item'];
        $registro['id_item']=$parametros['id_item'];
        $registro['tipo']=$parametros['tipo'];
        $registro['comision']=$parametros['comision'];
        $registro['importe_desde']=($parametros['tipo'] == 'tramo')?$parametros['importe_desde']:0;
        $registro['importe_hasta']=($parametros['tipo'] == 'tramo')?$parametros['importe_hasta']:0;
        $registro['id_usuario']=$parametros['id_usuario'];
        $registro['fecha_modificacion']=date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
        $registro['borrado']=0;
      }
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      
      $where['id_comision']=$parametros['id_comision'];
      
      $AqConexion_model->update('usuarios_comisiones',$registro,$where);
      
      if($this->db->affected_rows() > 0){
        return 1;
      }else{
        return 0;
      }
      

  }
  function centros(){
    $centros = $this->db->where('borrado','0')->get('centros')->result_array();
    $r=Array();
    foreach ($centros as $centro) {
      $r[$centro['id_centro']] = $centro['nombre_centro'];
    }
    return $r;
  }

  
}
