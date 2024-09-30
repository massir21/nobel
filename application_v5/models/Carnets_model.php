<?php
class Carnets_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... CARNETS
  // -------------------------------------------------------------------
  function leer($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['tipo_especial'])) {
      $busqueda.=" AND C.id_tipo = 99 ";
    }
    if (isset($parametros['tipo_templos'])) {
      $busqueda.=" AND C.id_tipo < 99 ";
    }

    if (isset($parametros['id_empleado'])) {
      if ($parametros['id_empleado'] > 0) {
        $busqueda.=" AND C.id_usuario_creador = @id_empleado ";
      }
    }

    if (isset($parametros['id_cliente'])) {
      if ($parametros['id_cliente'] > 0) {
        $busqueda.=" AND C.id_cliente = @id_cliente ";
      }
    }

    if (isset($parametros['id_centro'])) {
      if ($parametros['id_centro'] > 0) {
        $busqueda.=" AND C.id_centro = @id_centro ";
      }
    }

    if (isset($parametros['notas'])) {
        $busqueda.=" AND C.notas = @notas ";
    }

    if (isset($parametros['bcodigo'])) {
        $parametros['bcodigo']=strtoupper($parametros['bcodigo']);
        //$parametros['buscar']=ltrim($parametros['codigo']); //20/05/20 Los quité, estimo que es la razón por la cual coloca un cliente que no es.
        //$parametros['buscar']=rtrim($parametros['codigo']); //20/05/20 Porque la línea 62 abre el compas a más clientes por el  LIKE.
        $busqueda.=" AND TRIM(C.codigo) = @bcodigo ";
    }
    
    if (isset($parametros['codigo'])) {
        $parametros['codigo']=strtoupper($parametros['codigo']);
        $parametros['buscar']=ltrim($parametros['codigo']); //20/05/20 Los quité, estimo que es la razón por la cual coloca un cliente que no es.
        $parametros['buscar']=rtrim($parametros['codigo']); //20/05/20 Porque la línea 62 abre el compas a más clientes por el  LIKE.
        $busqueda.=" AND TRIM(C.codigo) = @codigo ";
    }
    
    
    if (isset($parametros['buscar'])) {
        $parametros['buscar']=strtoupper($parametros['buscar']);
        $parametros['buscar']=ltrim($parametros['buscar']);
        $parametros['buscar']=rtrim($parametros['buscar']);

        $busqueda.=" AND (TRIM(UPPER(C.codigo)) like '%".$parametros['buscar']."%'
        or UPPER(centros.nombre_centro) like '%".$parametros['buscar']."%'
        or UPPER(CONCAT(clientes.nombre, ' ', clientes.apellidos)) like '%".$parametros['buscar']."%'
        )";
    }

    // ... Lee los carnets asociados a los pagos en los dietarios.
    if (isset($parametros['id_dietario'])) {
      if ($parametros['id_dietario'] > 0) {
        $busqueda.=" AND C.id_carnet IN
        (select id_carnet from carnets_templos_historial where id_dietario = @id_dietario and borrado = 0
        UNION
        select id_carnet from carnets_templos_servicios where id_dietario = @id_dietario and borrado = 0) ";
      }
    }

    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta ";
    }

    if (isset($parametros['sin_pasar_caja'])) {
      $busqueda.=" AND C.sin_pasar_caja = @sin_pasar_caja ";
    }

    $inner_mostrar_gastado="";
    $campo_mostrar_gastado="";
    if (isset($parametros['mostrar_gastado'])) {
      $inner_mostrar_gastado.="
      LEFT JOIN (select id_carnet,count(id) as no_gastado
      from carnets_templos_servicios where borrado = 0 and gastado = 0 group by id_carnet) CTS
      ON CTS.id_carnet = C.id_carnet ";

      $campo_mostrar_gastado=" ,IFNULL(CTS.no_gastado,0) as no_gastado ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_tipo,C.codigo,C.codigo_pack_online,C.templos,
      C.templos_disponibles,C.id_cliente,C.id_centro,C.precio,C.activo_online,
      C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
      C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
      CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
      carnets_templos_tipos.descripcion as tipo,centros.nombre_centro,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
      DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_vendido,
      DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d') as fecha_aaaammdd_vendido,
      DATE_FORMAT(C.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_vendido_abrev,
      C.notas,(select sum(pvp) from carnets_templos_servicios where id_carnet = C.id_carnet
      and borrado = 0)
      as precio_servicios,
      carnets_templos_tipos.id_tipo_padre
      $campo_mostrar_gastado
    FROM carnets_templos AS C
      LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
      LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creador
      LEFT JOIN centros on centros.id_centro = C.id_centro
      LEFT JOIN carnets_templos_tipos on carnets_templos_tipos.id_tipo = C.id_tipo
      $inner_mostrar_gastado
    WHERE C.borrado = 0 ".$busqueda." ORDER BY codigo ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  //11/04/20 Clientes Asociados
 function leer_asociados($parametros) {
    $AqConexion_model = new AqConexion_model();
    $busqueda="";
    if (isset($parametros['id_cliente'])) {
      if ($parametros['id_cliente'] > 0) {
        $busqueda.=" AND id_asociado = @id_cliente "; //id_cliente = @id_cliente //06/05/20
      }
    }
    
    $sentencia_sql="SELECT * FROM clientes_asociados WHERE borrado=0 ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
}

function leer_asociados_completo($parametros) {
    $AqConexion_model = new AqConexion_model();
    $busqueda="";
    if (isset($parametros['id_cliente'])) {
      if ($parametros['id_cliente'] > 0) {
        $busqueda.=" AND clientes_asociados.id_cliente = @id_cliente ";
      }
    }
    
    $sentencia_sql="SELECT clientes_asociados.*,CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente
     FROM clientes_asociados
     LEFT JOIN clientes on clientes.id_cliente = clientes_asociados.id_asociado 
     WHERE clientes_asociados.borrado=0 ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
}

function anadir_asociado($registro){
    $AqConexion_model = new AqConexion_model();
    $AqConexion_model->insert('clientes_asociados',$registro);
    return 1;
}

function quita_asociado($registro){
    $AqConexion_model = new AqConexion_model();
    /*
    $where['id_cliente']=$registro['id_cliente'];
    $AqConexion_model->update('clientes_asociados',$registro,$where);
   */
   $registro['fecha_borrado']=date("Y-m-d H:i:s");
    $sentencia_sql=" UPDATE clientes_asociados SET borrado = 1, fecha_borrado = @fecha_borrado where id_cliente = @id_cliente AND id_asociado=@id_asociado";
    $datos = $AqConexion_model->no_select($sentencia_sql,$registro);
 
     
     
    return 1;
}

//Fin

//21/04/20 Recargas 
function nueva_recarga($parametros) {
    $AqConexion_model = new AqConexion_model();

    
    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_servicio']=$parametros['id_servicio'];
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_empleado']=$parametros['id_empleado'];
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['templos']=$parametros['templos'];
    $registro['saldo']=$parametros['templos'];
    $registro['monto']=$parametros['importe_euros'];
    $registro['pagado']=0;
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;

    
    

    $AqConexion_model->insert('carnets_recarga',$registro);

    $sentenciaSQL="select max(id) as id from carnets_recarga";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);

    return $resultado[0]['id'];
  }
function recarga_pagada($parametros){
    $AqConexion_model = new AqConexion_model();
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['pagado']=1;
    
    $where['id_dietario']=$parametros['id_dietario'];
    $AqConexion_model->update('carnets_recarga',$registro,$where);

    return 1;

}

function nuevo_saldo($parametros){
    $AqConexion_model = new AqConexion_model();
    //$registro['id_centro']=$this->session->userdata('id_centro_usuario'); //No, porque debe quedar solo para el centro que recibio el dinero de la recarga
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['saldo']=$parametros['saldo'];
    
    $where['id']=$parametros['id'];
    $AqConexion_model->update('carnets_recarga',$registro,$where);

    return 1;

}


function recarga_historial($parametros) {
    $AqConexion_model = new AqConexion_model();
     
    $registro['id_recarga']=$parametros['id_recarga']; 
    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_servicio']=$parametros['id_servicio'];
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_empleado']=$parametros['id_empleado'];
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['id_centro_origen']=$parametros['id_centro_origen']; //id_centro de la recarga
    $registro['templos']=$parametros['templos'];
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;

    $AqConexion_model->insert('carnets_recarga_historial',$registro);

    return 1;
  }

//17/02/21 Para Informe diario cronjob
function leer_carnets_recarga($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['id_usuario'])) {
      if ($parametros['id_usuario'] > 0) {
        $busqueda.=" AND C.id_empleado = @id_usuario ";
      }
    }

    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta ";
    }
    
    
    ///////
    if (isset($parametros['fecha_inicio'])) {
      if ($parametros['fecha_inicio'] != "") {
        $busqueda.=" AND (DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d')) >= @fecha_inicio
        AND (DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d')) <= @fecha_fin ";
      }
    }
    
    if (isset($parametros['id_centro'])) {
      if ($parametros['id_centro'] > 0) {
        $busqueda.=" AND C.id_centro = @id_centro ";
      }
    }


    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_servicio,C.templos,C.id_cliente,
    C.id_centro,C.id_empleado,C.monto,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    DATE_FORMAT(C.fecha_creacion,'%H:%i') as hora,
    DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_creacion_ddmmaaaa,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    centros.nombre_centro,servicios.nombre_servicio,servicios_familias.nombre_familia,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%d-%m-%Y %H:%i') as fecha_concepto_ddmmaaaa,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d %H:%i') as fecha_concepto_aaaammdd,
    servicios.duracion,carnets_templos.codigo,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%W, %d-%b-%Y - %H:%i') as fecha_concepto_ddmmaaaa_abrev,
    CT.codigo as carnet_especial
    FROM carnets_recarga AS C
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = C.id_carnet
    LEFT JOIN dietario on dietario.id_dietario = C.id_dietario
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_empleado
    LEFT JOIN centros on centros.id_centro = C.id_centro
    LEFT JOIN carnets_templos AS CT on CT.id_carnet = dietario.id_carnet
    LEFT JOIN (servicios left join servicios_familias on
    servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
    on servicios.id_servicio = C.id_servicio
    WHERE C.borrado = 0 ".$busqueda." ORDER BY C.id_cliente ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

//FIn



function actualizar_recargas($parametros){
    
    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_servicio']=$parametros['id_servicio'];
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_empleado']=$parametros['id_empleado'];
    
    $AqConexion_model = new AqConexion_model();
    $total_templos=$parametros['templos'];
    $sentencia_sql="SELECT * FROM carnets_recarga 
    WHERE borrado=0 AND saldo>0 AND pagado=1 AND id_carnet=@id_carnet ORDER BY fecha_creacion";
    
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    if ($datos>0){
        
        for ($h=0;$h<count($datos);$h++){
            
            if ($datos[$h]['saldo']>=$total_templos){
                $xdiferencia=$datos[$h]['saldo']-$total_templos;
                unset($param);
                $param['saldo']=$xdiferencia;
                $param['id']=$datos[$h]['id']; //id de la recarga
                $actualiza_saldo=$this->nuevo_saldo($param);
                
                
                $registro['id_recarga']=$datos[$h]['id']; //id de la recarga
                $registro['id_centro_origen']=$datos[$h]['id_centro']; //id_centro de la recarga
                $registro['templos']=$total_templos;
                $registra_historico=$this->recarga_historial($registro);
                return 1;
            } //Saldo >= total
            
            
             if ($datos[$h]['saldo']<$total_templos){
                $xdiferencia=$datos[$h]['saldo'];
                $total_templos=$total_templos-$xdiferencia; //24/04/20 para que descuente del total lo que se está pagando en esta recarga.
                unset($param);
                $param['saldo']=0;
                $param['id']=$datos[$h]['id']; //id de la recarga
                $actualiza_saldo=$this->nuevo_saldo($param);
                
                
                $registro['id_recarga']=$datos[$h]['id']; //id de la recarga
                $registro['id_centro_origen']=$datos[$h]['id_centro']; //id_centro de la recarga
                $registro['templos']=$xdiferencia;
                $registra_historico=$this->recarga_historial($registro);
                
            } //Saldo >= total
            
            
        }//For
        
    }//if Datos
}

//Fin de Recargas



  function leer_un_carnet($id_carnet) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_tipo,C.codigo,C.codigo_pack_online,C.templos,
    C.templos_disponibles,C.id_cliente,C.id_centro,C.precio,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado
    FROM carnets_templos AS C
    WHERE id_carnet = @id_carnet";

    $parametros['id_carnet']=$id_carnet;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function leer_un_carnet_codigo($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_tipo,C.codigo,C.codigo_pack_online,C.templos,
    C.templos_disponibles,C.id_cliente,C.id_centro,C.precio,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado
    FROM carnets_templos AS C
    WHERE borrado=0 AND codigo = @codigo";

    //$parametros['codigo']=$codigo;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function leer_carnets_servicios($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['gastado'])) {
      $busqueda.=" AND C.gastado = @gastado ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id,C.id_carnet,C.id_servicio,C.id_centro,C.id_cliente,
    C.gastado,C.pvp,C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    servicios.nombre_servicio,servicios_familias.nombre_familia,carnets_templos.codigo,
    DATE_FORMAT(C.fecha_modificacion,'%d-%m-%Y') as fecha_modificacion_ddmmaaa,
    DATE_FORMAT(C.fecha_modificacion,'%Y-%m-%d') as fecha_modificacion_aaaammdd,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    CD.nombre_centro as centro_servicio,
    centros.nombre_centro,servicios.duracion,
    case
      when C.gastado = 1
        then 'Gastado'
      when C.gastado = 0
        then 'No Gastado'
      else
        'No Gastado'
    end as estado_servicio,
    case
      when C.gastado = 1
        then '#fad7e4'
      when C.gastado = 0
        then '#e0ffd4'
      else
        '#fad7e4'
    end as color_servicio
    FROM carnets_templos_servicios AS C
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = C.id_carnet
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN (servicios left join servicios_familias on
    servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
    on servicios.id_servicio = C.id_servicio
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_modificacion
    LEFT JOIN centros on centros.id_centro = C.id_centro
    LEFT JOIN (dietario left join centros AS CD on CD.id_centro = dietario.id_centro)
    on dietario.id_dietario = C.id_dietario
    WHERE C.borrado = 0 ".$busqueda." ORDER BY id ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function leer_carnets_servicios_logs($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['gastado'])) {
      $busqueda.=" AND C.gastado = @gastado ";
    }

    if (isset($parametros['id_usuario'])) {
      if ($parametros['id_usuario'] > 0) {
        $busqueda.=" AND C.id_usuario_creador = @id_usuario ";
      }
    }

    // ... Hago esto esto para a parte de buscar entre fechas, que solo
    // muestro los servicios que se han creado despu�s del carnet.
    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta
      AND C.fecha_creacion > (carnets_templos.fecha_creacion + INTERVAL 1 MINUTE) ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id,C.id_carnet,C.id_servicio,C.id_centro,C.id_cliente,
    C.gastado,C.pvp,C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    servicios.nombre_servicio,servicios_familias.nombre_familia,carnets_templos.codigo,
    DATE_FORMAT(C.fecha_modificacion,'%d-%m-%Y') as fecha_modificacion_ddmmaaa,
    DATE_FORMAT(C.fecha_modificacion,'%Y-%m-%d') as fecha_modificacion_aaaammdd,
    DATE_FORMAT(C.fecha_modificacion,'%W, %d-%b-%Y - %H:%i') as fecha_modificacion_abrev,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    CD.nombre_centro as centro_servicio,
    centros.nombre_centro,servicios.duracion,
    case
      when C.gastado = 1
        then 'Gastado'
      when C.gastado = 0
        then 'No Gastado'
      else
        'No Gastado'
    end as estado_servicio,
    case
      when C.gastado = 1
        then '#fad7e4'
      when C.gastado = 0
        then '#e0ffd4'
      else
        '#fad7e4'
    end as color_servicio
    FROM carnets_templos_servicios AS C
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = C.id_carnet
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN (servicios left join servicios_familias on
    servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
    on servicios.id_servicio = C.id_servicio
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_modificacion
    LEFT JOIN centros on centros.id_centro = C.id_centro
    LEFT JOIN (dietario left join centros AS CD on CD.id_centro = dietario.id_centro)
    on dietario.id_dietario = C.id_dietario
    WHERE 1=1 ".$busqueda." ORDER BY id ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function leer_carnets_historial($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['id_usuario'])) {
      if ($parametros['id_usuario'] > 0) {
        $busqueda.=" AND C.id_empleado = @id_usuario ";
      }
    }

    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_servicio,C.templos,C.id_cliente,
    C.id_centro,C.id_empleado,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    DATE_FORMAT(C.fecha_creacion,'%H:%i') as hora,
    DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y') as fecha_creacion_ddmmaaaa,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    centros.nombre_centro,servicios.nombre_servicio,servicios_familias.nombre_familia,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%d-%m-%Y %H:%i') as fecha_concepto_ddmmaaaa,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%Y-%m-%d %H:%i') as fecha_concepto_aaaammdd,
    servicios.duracion,carnets_templos.codigo,
    DATE_FORMAT(dietario.fecha_hora_concepto,'%W, %d-%b-%Y - %H:%i') as fecha_concepto_ddmmaaaa_abrev,
    CT.codigo as carnet_especial
    FROM carnets_templos_historial AS C
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = C.id_carnet
    LEFT JOIN dietario on dietario.id_dietario = C.id_dietario
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_empleado
    LEFT JOIN centros on centros.id_centro = C.id_centro
    LEFT JOIN carnets_templos AS CT on CT.id_carnet = dietario.id_carnet
    LEFT JOIN (servicios left join servicios_familias on
    servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
    on servicios.id_servicio = C.id_servicio
    WHERE C.borrado = 0 ".$busqueda." ORDER BY C.fecha_creacion ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function leer_carnets_ajustes($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['id_usuario'])) {
      if ($parametros['id_usuario'] > 0) {
        $busqueda.=" AND C.id_usuario_creador = @id_usuario ";
      }
    }

    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND C.fecha_creacion >= @fecha_desde
      AND C.fecha_creacion <= @fecha_hasta ";
    }

    if (isset($parametros['solo_ajustes_encargados'])) {
      if ($parametros['solo_ajustes_encargados'] > 0) {
        $busqueda.=" AND C.id_usuario_creador
        in (select id_usuario from usuarios_perfiles where id_perfil = 3)
        and C.id_carnet NOT IN (select id_carnet from dietario
        where id_carnet = C.id_carnet and id_servicio = 0)";
      }
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id,C.id_carnet,C.templos_disponibles,
    C.templos_disponibles_anteriores,C.id_usuario_creador,C.fecha_creacion,
    C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    centros.nombre_centro,
    DATE_FORMAT(C.fecha_creacion,'%d-%m-%Y %H:%i') as fecha,
    DATE_FORMAT(C.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_aaaammdd,
    DATE_FORMAT(C.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_abrev,
    carnets_templos.codigo
    FROM carnets_templos_ajustes AS C
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_creador
    LEFT JOIN centros on centros.id_centro = C.id_centro
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = C.id_carnet
    WHERE C.borrado = 0 ".$busqueda." ORDER BY id ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function tipos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_tipo'])) {
      if ($parametros['id_tipo'] > 0) {
        $busqueda.=" AND C.id_tipo = @id_tipo ";
      }
    }

    if (isset($parametros['id_tipo_padre'])) {
      if ($parametros['id_tipo_padre'] > 0) {
        $busqueda.=" AND C.id_tipo_padre = @id_tipo_padre ";
      }
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_tipo,C.descripcion,C.templos,C.precio,C.codigo,
    C.id_tienda,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    (select numero + 1 from carnets_codigos_centros where
    id_centro = @id_centro and id_tipo = C.id_tipo)
    as numero_siguiente,C.id_tipo_padre
    FROM carnets_templos_tipos AS C
    WHERE C.borrado = 0 ".$busqueda." ORDER BY C.templos,C.id_tipo ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    unset($param);
    $param['id_centro']=$this->session->userdata('id_centro_usuario');
    $centro = $this->Usuarios_model->leer_centros($param);
	if(is_countable($datos)){
		for ($i=0; $i<count($datos); $i++) {
		  $param['id_tipo']=$datos[$i]['id_tipo_padre'];

		  $sentencia_sql="SELECT numero from carnets_codigos_centros where id_tipo = @id_tipo
		  and id_centro = @id_centro";
		  $numeros = $AqConexion_model->select($sentencia_sql,$param);

		  // ... Si el centro el Goya, hay una excepcion, el codigo del centro
		  // va al final.
		  if ($param['id_centro']==3) {
			$datos[$i]['numero_siguiente']=$numeros[0]['numero']."".$datos[$i]['codigo']."".$centro[0]['codigo'];
		  }
		  else {
			$datos[$i]['numero_siguiente']=$numeros[0]['numero']."".$centro[0]['codigo']."".$datos[$i]['codigo'];
		  }
		}
	}

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function guardar($parametros) {
    $AqConexion_model = new AqConexion_model();

    if ($parametros['id_tipo'] != null) {
      unset($registro);

      unset($param);
      $param['id_tipo']=$parametros['id_tipo'];
      $tipo=$this->tipos($param);

      $registro['id_tipo']=$parametros['id_tipo'];

      // ... Calculamos el siguiente codigo en funcion
      // del tipo de carnet y del centro.
      if (isset($parametros['numero_carnet'])) {
        $registro['codigo']=$parametros['numero_carnet'];

        unset($param2);
        $param2['codigo']=$parametros['numero_carnet'];
        $carnet_codigo=$this->existe_codigo($param2);

        // ... Devolvemos -1 para indicar que el codigo de carnet ya existe.
        if ($carnet_codigo>0) {
          return -1;
        }
      }
      else {
        unset($param);
        $param['id_tipo']=$parametros['id_tipo'];
        $param['id_centro']=$this->session->userdata('id_centro_usuario');
        $registro['codigo']=$this->codigo($param);

        unset($param2);
        $param2['codigo']=$registro['codigo'];
        $carnet_codigo=$this->existe_codigo($param2);

        // ... Devolvemos -1 para indicar que el codigo de carnet ya existe.
        if ($carnet_codigo>0) {
          return -1;
        }
      }
      $registro['codigo']=trim($registro['codigo']);

      $registro['templos']=$tipo[0]['templos'];
      if (isset($parametros['templos_disponibles'])) {
        if ($parametros['templos_disponibles'] != "") {
          $registro['templos_disponibles']=$parametros['templos_disponibles'];
        }
        else {
          $registro['templos_disponibles']=$tipo[0]['templos'];
        }
      }
      else {
        $registro['templos_disponibles']=$tipo[0]['templos'];
      }

      $registro['id_cliente']=$parametros['id_cliente'];
      if (isset($parametros['id_centro'])) {
        $registro['id_centro']=$parametros['id_centro'];
      }
      else {
        $registro['id_centro']=$this->session->userdata('id_centro_usuario');
      }

      if ($parametros['id_tipo']==99) {
        if (isset($parametros['precio'])) {
          $registro['precio']=$parametros['precio'];
        }
        else {
          $registro['precio']=$tipo[0]['precio'];
        }
      }
      else {
        $registro['precio']=$tipo[0]['precio'];
      }

      //
      if (isset($parametros['fecha_creacion'])) {
        $registro['fecha_creacion']=$parametros['fecha_creacion'];
      }
      else {
        $registro['fecha_creacion']=date("Y-m-d H:i:s");

      }
      if (isset($parametros['notas'])) {
        $registro['notas']=$parametros['notas'];
      }

      if (isset($parametros['codigo_pack_online'])) {
        $registro['codigo_pack_online']=$parametros['codigo_pack_online'];
      }

      // ... Indica si el carnet se crear sin pasar por caja o no.
      if (isset($parametros['sin_pasar_caja'])) {
        $registro['sin_pasar_caja']=$parametros['sin_pasar_caja'];
      }
      else {
        $registro['sin_pasar_caja']=0;
      }

      $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      if (isset($parametros['borrado'])) {
        $registro['borrado']=$parametros['borrado'];
      }
      else {
        $registro['borrado']=0;
      }

      $AqConexion_model->insert('carnets_templos',$registro);

      $sentenciaSQL="select max(id_carnet) as id_carnet from carnets_templos";
      $resultado = $AqConexion_model->select($sentenciaSQL,null);

      // ... Guardamos un registro de que se pusieron los templos disponibles
      // a un valor manual, por ser un carnet antiguo o el motivo que sea.
      if (isset($parametros['templos_disponibles'])) {
        if ($parametros['templos_disponibles'] != "") {
          unset($param);
          $param['templos_disponibles']=$parametros['templos_disponibles'];
          $param['id_centro']=$this->session->userdata('id_centro_usuario');
          $param['id_carnet']=$resultado[0]['id_carnet'];

          $this->guardar_ajustes_templos($param);
        }
      }

      // .. Si es un carnet de tipo especial guardamos los servicios asociados.
      if ($parametros['id_tipo']==99) {
        if (isset($parametros['servicios_carnet'])) {
          foreach ($parametros['servicios_carnet'] as $id_servicio) {
            unset($param);
            $param['id_carnet']=$resultado[0]['id_carnet'];
            $param['id_servicio']=$id_servicio;
            $param2['id_servicio']=$id_servicio;;
            $sevicio = $this->Servicios_model->leer_servicios($param2);
            $param['id_cliente']=$parametros['id_cliente'];
            $param['pvp']=$sevicio[0]['pvp'];

            $id = $this->Carnets_model->anadir_servicio($param);
          }
        }
      }

      return $resultado[0]['id_carnet'];
    }
    else {
      return 0;
    }
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function guardar_ajustes_templos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_centro']=$parametros['id_centro'];
    if (isset($parametros['templos_disponibles_anteriores'])) {
      $registro['templos_disponibles_anteriores']=$parametros['templos_disponibles_anteriores'];
    }
    else {
      $registro['templos_disponibles_anteriores']=0;
    }
    $registro['templos_disponibles']=$parametros['templos_disponibles'];

    if (isset($parametros['sin_pasar_por_caja'])) {
      $registro['sin_pasar_por_caja']=1;
    }
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;

    $AqConexion_model->insert('carnets_templos_ajustes',$registro);

    $sentenciaSQL="select max(id) as id from carnets_templos_ajustes";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);

    return $resultado[0]['id'];
  }

//15/04/20 Proceso por lote de asignar carnets
function guardar_carnet_lotes($parametros) {
    $AqConexion_model = new AqConexion_model();

    //$registro['id_carnet']=$parametros['id_carnet']; //21/05/20 Prueba para AutoIncremento
    $registro['id_centro']=$parametros['id_centro'];
    $registro['codigo']=$parametros['codigo'];
    $registro['id_cliente']=$parametros['id_cliente'];
    
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=1; //$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=1; //$this->session->userdata('id_usuario');
    $registro['templos']=0;
    $registro['templos_disponibles']=0;
    $registro['precio']=0;
    $registro['id_tipo']=22;
    $registro['sin_pasar_caja']=0;
    $registro['activo_online']=1;
    $registro['borrado']=0;

    $AqConexion_model->insert('carnets_templos',$registro);
    return 1;
    
  } 
  
  function guardar_carnet_unico($parametros) {
    $AqConexion_model = new AqConexion_model();

    //$registro['id_carnet']=$parametros['id_carnet']; //21/05/20 Prueba para AutoIncremento
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['codigo']=$parametros['codigo'];
    $registro['id_cliente']=$parametros['id_cliente']; 
  
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['templos']=0;
    $registro['templos_disponibles']=0;
    $registro['precio']=0;
    $registro['id_tipo']=22;
    $registro['sin_pasar_caja']=0;
    $registro['activo_online']=1;
    $registro['borrado']=0;

    $AqConexion_model->insert('carnets_templos',$registro);
    return 1;
    
  } 

//Fin


  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function reasignar($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['id_cliente']=$parametros['id_cliente'];
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

    $where['id_carnet']=$parametros['id_carnet'];
    $AqConexion_model->update('carnets_templos',$registro,$where);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function ajustes_templos($parametros) {
    $AqConexion_model = new AqConexion_model();

    if ( isset($parametros) && isset($parametros['templos_disponibles']) ) {
      unset($param2);
      $param2['id_carnet']=$parametros['id_carnet'];
      $carnet_modificado=$this->leer($param2);

      $registro['templos_disponibles']=$parametros['templos_disponibles'];
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

      $where['id_carnet']=$parametros['id_carnet'];
      $AqConexion_model->update('carnets_templos',$registro,$where);

      // ... Guardo el ajuste, solo si es manual, es decir esta variable no traer� nada.
      if (!isset($parametros['ajuste_automatico'])) {
        unset($param);
        $param['templos_disponibles']=$parametros['templos_disponibles'];
        if (isset($param['templos_disponibles_anteriores'])) {
          $param['templos_disponibles_anteriores']=$parametros['templos_disponibles_anteriores'];
        }
        else {
          $param['templos_disponibles_anteriores']=$carnet_modificado[0]['templos_disponibles'];
        }
        $param['id_centro']=$this->session->userdata('id_centro_usuario');
        $param['id_carnet']=$parametros['id_carnet'];

        if (isset($parametros['sin_pasar_por_caja'])) {
          $param['sin_pasar_por_caja']=1;
        }

        $this->guardar_ajustes_templos($param);
      }
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function detalle($parametros) {
    $AqConexion_model = new AqConexion_model();

    if (isset($parametros['notas'])) {
      $registro['notas']=$parametros['notas'];
      $registro['activo_online']=$parametros['activo_online'];
      if (isset($parametros['nuevo_codigo_carnet'])) {
        $registro['codigo']=$parametros['nuevo_codigo_carnet'];
      }
      //29/05/20 Editar Precio
      if (isset($parametros['precio'])) {
        $registro['precio']=$parametros['precio'];
      }
      //29/05/20
      
      //
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

      $where['id_carnet']=$parametros['id_carnet'];
      $AqConexion_model->update('carnets_templos',$registro,$where);
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function actualizar_precio_especial($parametros) {
    $AqConexion_model = new AqConexion_model();

    //unset($param);
    //$param['id_carnet']=$parametros['id_carnet'];
    //$carnet = $this->leer($param);

    $registro['precio']=$parametros['precio'];
    //$registro['notas']=$carnet[0]['notas']." ".$parametros['notas_adicionales'];
    $registro['notas']=$parametros['notas_adicionales'];
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

    $where['id_carnet']=$parametros['id_carnet'];
    $AqConexion_model->update('carnets_templos',$registro,$where);

    // ... Leemos la linea de dietario asociada, para poner el precio que
    // corresponde al carnet, en caso de que este aun pendiente de cobro.
    unset($param);
    $param['id_carnet']=$parametros['id_carnet'];
    $param['estado']="Pendiente";
    $dietario = $this->Dietario_model->leer($param);

    if (count($dietario)>0) {
      unset($param);
      $param['precio']=$parametros['precio'];
      $param['id_dietario']=$dietario[0]['id_dietario'];

      $sentencia_sql=" UPDATE dietario SET importe_euros = @precio where id_dietario = @id_dietario ";
      $datos = $AqConexion_model->no_select($sentencia_sql,$param);
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function cambio_precio_venta_carnet($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['precio']=$parametros['precio'];
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

    $where['id_carnet']=$parametros['id_carnet'];
    $AqConexion_model->update('carnets_templos',$registro,$where);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function modificar_servicios($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['gastado']=1;
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");

    if (isset($parametros['id_usuario_modificacion'])) {
      $registro['id_usuario_modificacion']=$parametros['id_usuario_modificacion'];
    }
    else {
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    }

    $where['id']=$parametros['id'];
    $AqConexion_model->update('carnets_templos_servicios',$registro,$where);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function anadir_servicio($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_servicio']=$parametros['id_servicio'];
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['gastado']=0;
    $registro['pvp']=$parametros['pvp'];
    if (isset($parametros['sin_pasar_por_caja']))
    {
      $registro['sin_pasar_por_caja']=$parametros['sin_pasar_por_caja'];
    }
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;

    // ... guardamos tantas veces como la cantidad especificada.
    //for ($i=0; $i<$parametros['cantidad']; $i++) {
      //$AqConexion_model->insert('carnets_templos_servicios',$registro);
    //}

    $AqConexion_model->insert('carnets_templos_servicios',$registro);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function anadir_historial($parametros) {
    $AqConexion_model = new AqConexion_model();

    $registro['id_carnet']=$parametros['id_carnet'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_servicio']=$parametros['id_servicio'];
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_empleado']=$parametros['id_empleado'];
    $registro['id_centro']=$this->session->userdata('id_centro_usuario');
    $registro['templos']=$parametros['templos'];
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creador']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;

    $AqConexion_model->insert('carnets_templos_historial',$registro);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function borrar($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql=" UPDATE carnets_templos SET borrado = 1 where id_carnet = @id_carnet ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    $sentencia_sql=" UPDATE carnets_templos_servicios SET borrado = 1 where id_carnet = @id_carnet ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return 1;
  }

  function borrar_desde_dietario($parametros) {
    $AqConexion_model = new AqConexion_model();

    unset($param);
    $param['id_carnet']=$parametros['id_carnet'];
    $carnet_borrar=$this->leer($param);

    $codigo_carnet_borrado=$carnet_borrar[0]['codigo'];

    // ... Marcamos como borrado el carnet.
    $sentencia_sql=" UPDATE carnets_templos SET borrado = 1,
    codigo=CONCAT('#BORRADO#',id_carnet),
    notas = CONCAT('#BORRADO-CODIGO-".$codigo_carnet_borrado."#',notas)
    where id_carnet = @id_carnet ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    $sentencia_sql=" UPDATE carnets_templos_servicios SET borrado = 1
    where id_carnet = @id_carnet ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function borrar_servicio($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql=" UPDATE carnets_templos_servicios SET borrado = 1 where id = @id ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function borrar_carnet_elegido_pago($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql=" DELETE FROM carnets_templos_pagos where id = @id ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function borrar_pago_templos_carnets($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentencia_sql=" DELETE FROM carnets_templos_pagos where id_cliente = @id_cliente ";
    $datos = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function codigo($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos el tipo padre del carnet, porque realmente
    // el siguiente numero para los que son carnets aniversario,
    // debe ser el mismo que el de los carnets normales,
    // ej. 12 templos, usar el mismo contador que 12 templos aniversario.
    unset($param);
    $param['id_tipo']=$parametros['id_tipo'];
    $tipos=$this->tipos($param);

    unset($param);
    $param['id_centro']=$parametros['id_centro'];
    $centros=$this->Usuarios_model->leer_centros($param);

    // ... Leemos los registros
    $parametros['id_tipo']=$tipos[0]['id_tipo_padre'];
    $sentencia_sql="SELECT numero from carnets_codigos_centros
    where id_tipo = @id_tipo and id_centro = @id_centro ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    // ... Si el centro el Goya, hay una excepcion, el codigo del centro
    // va al final.
    if ($param['id_centro']==3) {
      $codigo=$datos[0]['numero'].$tipos[0]['codigo'].$centros[0]['codigo'];
    }
    else {
      $codigo=$datos[0]['numero'].$centros[0]['codigo'].$tipos[0]['codigo'];
    }

    $sentencia_sql=" UPDATE carnets_codigos_centros SET numero = numero + 1
    where id_tipo = @id_tipo and id_centro = @id_centro";
    $ok = $AqConexion_model->no_select($sentencia_sql,$parametros);

    return $codigo;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function pagotemplos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_carnet'])) {
      if ($parametros['id_carnet'] > 0) {
        $busqueda.=" AND C.id_carnet = @id_carnet ";
      }
    }

    if (isset($parametros['id_cliente'])) {
      if ($parametros['id_cliente'] > 0) {
        $busqueda.=" AND C.id_cliente = @id_cliente ";
      }
    }

    if (isset($parametros['fecha_venta'])) {
      if ($parametros['fecha_venta'] != "") {
        $busqueda.=" AND DATE_FORMAT(C.fecha_venta,'%Y-%m-%d') = @fecha_venta ";
      }
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id,C.id_carnet,C.id_cliente,
    C.fecha_venta,carnets_templos.templos_disponibles,carnets_templos.codigo,
    carnets_templos.id_tipo,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    carnets_templos.notas
    FROM carnets_templos_pagos AS C
    LEFT JOIN (carnets_templos left join clientes on clientes.id_cliente
    = carnets_templos.id_cliente)
    on carnets_templos.id_carnet = C.id_carnet
    WHERE carnets_templos.borrado = 0 ".$busqueda." ORDER BY carnets_templos.templos_disponibles ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function pagotemplos_guardar($parametros) {
    $AqConexion_model = new AqConexion_model();

    unset($param);
    $param['id_carnet']=$parametros['id_carnet'];
    $param['id_cliente']=$parametros['id_cliente'];
    $existe=$this->pagotemplos($param);

    if ($existe==0) {
      $registro['id_carnet']=$parametros['id_carnet'];
      $registro['id_cliente']=$parametros['id_cliente'];
      $registro['fecha_venta']=$parametros['fecha_venta'];

      if ($registro['fecha_venta']!="" || $registro['fecha_venta']!=null) {
        $AqConexion_model->insert('carnets_templos_pagos',$registro);
      }
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  // -------------------------------------------------------------------
  function comprobar_carnet($parametros) {
    unset($param);
    $param['codigo']=$parametros['codigo'];
    $carnet=$this->leer($param);

    // ... Si el carnet existe
    if ($carnet != 0) {
      // ... CARNETS ESPECIALES
      // ... En caso de ser un carnet personalizado, verificamos si los servicios
      // que tiene encajan con los que hay que pagar.
      if ($carnet[0]['id_tipo'] == 99) {
        unset($param);
        $param['id_carnet']=$carnet[0]['id_carnet'];
        $param['gastado']=0;
        $servicios_carnet=$this->leer_carnets_servicios($param);

        $sw=0;
        if ($servicios_carnet>0) {
          foreach ($parametros['servicios_pagar'] as $row) {
            // ... Esto lo hago para reindexar el array, ya que voy borrando elementos.
            $servicios_carnet = array_values($servicios_carnet);
            $x=0;
            foreach ($servicios_carnet as $serv) {
              if ($row['id_servicio']==$serv['id_servicio']) {
                $sw++;
                unset($servicios_carnet[$x]);
                break;
              }
              $x++;
            }
          }
        }

        // ... El carnet indicado contiene todos los servicios necesarios
        // para pagar.
        if (is_array($parametros['servicios_pagar']) && count($parametros['servicios_pagar']) == $sw) {
          unset($param);
          $param['id_carnet']=$carnet[0]['id_carnet'];
          $param['id_cliente']=$parametros['id_cliente'];
          $param['fecha_venta']=$parametros['fecha'];

          $ok=$this->Carnets_model->pagotemplos_guardar($param);

          return 1;
        }
        // ... El carnet indicado contiene alguno de los servicios a pagar.
        // por tanto a�adimos el carnet como valido para este pago.
        else if ($sw>0) {
          unset($param);
          $param['id_carnet']=$carnet[0]['id_carnet'];
          $param['id_cliente']=$parametros['id_cliente'];
          $param['fecha_venta']=$parametros['fecha'];

          $ok=$this->Carnets_model->pagotemplos_guardar($param);

          return 4;
        }
        // ... No contiene ningun servicio para el pago.
        else {
          return 2;
        }
      }

      // ... CARNETS TEMPLOS.
      // ... Sino el carnet es normal, comprobamos si tiene templos disponibles.
      else {
        if ($carnet[0]['templos_disponibles'] > 0) {
          unset($param);
          $param['id_carnet']=$carnet[0]['id_carnet'];
          $param['id_cliente']=$parametros['id_cliente'];
          $param['fecha_venta']=$parametros['fecha'];

          $ok=$this->Carnets_model->pagotemplos_guardar($param);

          return 1;
        }
        else {
          return 3;
        }
      }
    }
    else {
      return 0;
    }
  }

  // ---------------------------------------------------------------------------------------
  // ... Comprobamos si con los carnets elegidos se pueden pagar los servicios concretos.
  // ---------------------------------------------------------------------------------------
  function puedo_pagar_templos($parametros) {
    // ... Leemos los carnet seleccionados
    unset($param);
    $param['id_cliente']=$parametros['id_cliente'];
    $param['fecha']=$parametros['fecha'];
    $carnets_elegidos=$this->Carnets_model->pagotemplos($param);

    // ... Si no hay carnets elegidos, directamente no se puede pagar
    if ($carnets_elegidos == 0) {
      return 0;
    }

    // ... recogemos en una variable los servicios a pagar pasados como parametro.
    $servicios_pagar=$parametros['servicios_pagar'];

    // ... Comprobamos los servicios que puedan pagarse con un carnet especial.
    // los vamos almacenando en un array, para luego eliminar esos elementos de los
    // servicios elegidos y asi determinar si cubre los mismos y devolver un 1 como
    // Ok se puede pagar.
    $datos[] = "";

    if ($carnets_elegidos>0) {
      foreach ($carnets_elegidos as $row) {
        if ($row['id_tipo']==99) {
          unset($param);
          $param['id_carnet']=$row['id_carnet'];
          $param['gastado']=0;
          $servicios_carnet_elegido=$this->leer_carnets_servicios($param);

          if ($servicios_carnet_elegido>0) {

            $i=0;
            foreach ($servicios_pagar as $row) {
              // ... Esto lo hago para reindexar el array, ya que voy borrando elementos.
              $servicios_carnet_elegido = array_values($servicios_carnet_elegido);
              $x=0;
              foreach ($servicios_carnet_elegido as $serv) {
                if ($row['id_servicio']==$serv['id_servicio']) {
                  array_push($datos,$i);
                  unset($servicios_carnet_elegido[$x]);
                  break;
                }
                $x++;
              }

              $i++;
            }

          }
        }
      }
    }

    // ... Borramos los elementos de servicios que se pueden pagar con todos los carnets
    // especiales que haya seleccionados.
    foreach ($datos as $e) {
      unset($servicios_pagar[$e]);
    }

    // ... Si al hacer el control de los carnets especiales, ya no quedan servicios por cubrir,
    // entonces devolvemos 1 de se puede pagar sin problemas.
    if (count($servicios_pagar)==0) {
      return 1;
    }

    // ... Calculamos los templos disponibles de los carnets elegidos.
    $total_templos_elegidos=0;
    if ($carnets_elegidos>0) {
      foreach ($carnets_elegidos as $row) {
        if ($row['id_tipo']!=99) {
          $total_templos_elegidos+=$row['templos_disponibles'];
        }
      }
    }

    // ... Calculamos los templos totales que hay que pagar de los servicios
    // que queden disponibles y no hayan sido quitados ya o cubiertos por los carnets
    // especiales.
    $total_templos_pagar=0;

    if ($servicios_pagar>0) {
      foreach ($servicios_pagar as $row) {
        //if ($row['id_tipo']!=99) {
          $total_templos_pagar+=$row['templos'];
        //}
      }
    }

    // ... Si los templos elegidos cubren a los de los servicios devolvemos 1, sino 0.
    if ($total_templos_elegidos>=$total_templos_pagar) {
      return 1;
    }
    else {
      return 0;
    }
  }

  // ---------------------------------------------------------------------------------------
  // ... Marcamos todos los servicios como pagados y restamos los templos o servicios
  // a los carnet correspondientes.
  // ---------------------------------------------------------------------------------------
  function marcar_pago_templos($parametros) {
    // ... Leemos los carnet seleccionados
    unset($param);
    $param['id_cliente']=$parametros['id_cliente'];
    $param['fecha']=$parametros['fecha'];
    $carnets_elegidos=$this->Carnets_model->pagotemplos($param);

    // ... Si no hay carnets elegidos, directamente no se puede pagar.
    if ($carnets_elegidos == 0) {
      return 0;
    }

    // ... Recogemos en una variable los servicios a pagar pasados como parametro.
    $servicios_pagar=$parametros['servicios_pagar'];

    // ... CARNETS ESPECIALES
    // ... Comprobamos los servicios que puedan pagarse con un carnet especial.
    $datos[]="vacio";
    if ($carnets_elegidos>0) {
      foreach ($carnets_elegidos as $row) {
        if ($row['id_tipo']==99) {
          unset($param);
          $param['id_carnet']=$row['id_carnet'];
          $param['gastado']=0;
          $servicios_carnet_elegido=$this->leer_carnets_servicios($param);

          for ($i=0; $i<count($servicios_pagar); $i++) {
            $sw=0;
            $id_carnet_servicio=0;
            if ($servicios_carnet_elegido>0) {
              $servicios_carnet_elegido = array_values($servicios_carnet_elegido);
              $x=0;
              foreach ($servicios_carnet_elegido as $r1) {
                if ($r1['id_servicio']==$servicios_pagar[$i]['id_servicio']) {
                  $sw++;
                  $id_carnet_servicio=$r1['id'];
                  unset($servicios_carnet_elegido[$x]);
                  break;
                }
                $x++;
              }
            }

            if ($sw>0) {
              // ... Marcamos como pagado el servicio
              unset($param2);
              $param2['id_cliente']=$parametros['id_cliente'];
              $param2['id_carnet']=$row['id_carnet'];
              $param2['id_carnet_servicio']=$id_carnet_servicio;
              $param2['id_dietario']=$servicios_pagar[$i]['id_dietario'];
              $param2['id_cita']=$servicios_pagar[$i]['id_cita'];

              $this->marcar_pago_templos_servicio($param2);

              array_push($datos,$i);
            }
          }
        }
      }
    }

    // ... Borramos los elementos de servicios que se pueden pagar con todos los carnets
    // especiales que haya seleccionados, asi ya solo quedan los que se pagan con carnet templos
    // normales.
    if ($datos>0) {
      foreach ($datos as $e) {
        unset($servicios_pagar[$e]);
      }
    }

    // ... Si al hacer el control de los carnets especiales, ya no quedan servicios por cubrir,
    // entonces devolvemos 1, ya quedo todo pagado.
    if (count($servicios_pagar)==0) {
      return 1;
    }

    // ... Calculamos los templos disponibles de los carnets elegidos.
    $total_templos_elegidos=0;
    if ($carnets_elegidos>0) {
      foreach ($carnets_elegidos as $row) {
        if ($row['id_tipo']!=99) {
          $total_templos_elegidos+=$row['templos_disponibles'];
        }
      }
    }

    // ... Calculamos los templos totales que hay que pagar de los servicios
    // que queden disponibles y no hayan sido quitados ya o cubiertos por los carnets
    // especiales.
    $total_templos_pagar=0;
    if ($servicios_pagar>0) {
      foreach ($servicios_pagar as $row) {
        if ($row['id_tipo']!=99) {
          $total_templos_pagar+=$row['templos'];
        }
      }
    }

    // ... Hago esto porque si se paga algo con carnets especiales,
    // Luego los indices del array se descolocan, asi los vuelvo a poner
    // de 0 en adelante para los servicios a pagar.
    $servicios_pagar = array_values($servicios_pagar);

    // ... CARNETS TEMPLOS
    // ... Si los templos elegidos cubren a los de los servicios, marcamos todo y
    // devolvemos 1, sino 0.
    if ($total_templos_elegidos>=$total_templos_pagar) {
      if ($carnets_elegidos>0) {
        //foreach ($carnets_elegidos as $row) {
        for ($x=0; $x<count($carnets_elegidos); $x++) {
          if ($carnets_elegidos[$x]['id_tipo']!=99) {
            for ($i=0; $i<count($servicios_pagar); $i++) {
              if ($servicios_pagar[$i]['templos']>0 && $carnets_elegidos[$x]['templos_disponibles']>0) {
                $diferencia = $carnets_elegidos[$x]['templos_disponibles']-$servicios_pagar[$i]['templos'];
                $templos_iniciales=$servicios_pagar[$i]['templos'];
                //21/04/20 Si es carnet ÚNICO Actualizar recarga de forma cronológica y registtrar historial de recarga
                //obneter las recargas pagadas y con saldo>0 para y ordenadas por fechas de creación.
                if (substr($carnets_elegidos[$x]['codigo'],0,1)=='U'){
                     unset($param3);
                    $param3['id_carnet']=$carnets_elegidos[$x]['id_carnet'];
                    $param3['id_dietario']=$servicios_pagar[$i]['id_dietario'];
                    $param3['id_servicio']=$servicios_pagar[$i]['id_servicio'];
                    $param3['id_cliente']=$servicios_pagar[$i]['id_cliente'];
                    $param3['id_empleado']=$servicios_pagar[$i]['id_empleado'];
                    $param3['templos']=$templos_iniciales;
                    $ok = $this->actualizar_recargas($param3);
               }
                //Fin
                

                if ($diferencia==0) {
                  // ... El carnet queda a 0 disponibles.
                  unset($param2);
                  $param2['id_carnet']=$carnets_elegidos[$x]['id_carnet'];
                  $param2['templos_disponibles']=0;
                  $param2['ajuste_automatico']=1;
                  $ok = $this->ajustes_templos($param2);

                  // ... El servicio queda a cero y marcado como pagado.
                  unset($param2);
                  $param2['id_dietario']=$servicios_pagar[$i]['id_dietario'];
                  $param2['id_cita']=$servicios_pagar[$i]['id_cita'];
                  $param2['tipo_pago']="#templos";
                  $param2['estado']="Pagado";
                  $ok=$this->Dietario_model->marcar_pagado_en_templos($param2);

                  $servicios_pagar[$i]['templos']=0;
                  $carnets_elegidos[$x]['templos_disponibles']=0;
                }

                if ($diferencia<0) {
                  // ... El carnet queda a 0 disponibles.
                  unset($param2);
                  $param2['id_carnet']=$carnets_elegidos[$x]['id_carnet'];
                  $param2['templos_disponibles']=0;
                  $param2['ajuste_automatico']=1;
                  $this->ajustes_templos($param2);

                  // ... El servicio se le restan los templos que se pudieron quitar del carnet
                  // pero aun no queda pagado
                  $templos_iniciales=$carnets_elegidos[$x]['templos_disponibles'];
                  $servicios_pagar[$i]['templos']-=$carnets_elegidos[$x]['templos_disponibles'];
                  $carnets_elegidos[$x]['templos_disponibles']=0;
                }

                if ($diferencia>0) {
                  // ... El carnet queda con la diferencia como disponibles
                  unset($param2);
                  $param2['id_carnet']=$carnets_elegidos[$x]['id_carnet'];
                  $param2['templos_disponibles']=$diferencia;
                  $param2['ajuste_automatico']=1;
                  $this->ajustes_templos($param2);

                  // ... El servicio queda a 0 y marcado como pagado.
                  unset($param2);
                  $param2['id_dietario']=$servicios_pagar[$i]['id_dietario'];
                  $param2['id_cita']=$servicios_pagar[$i]['id_cita'];
                  $param2['tipo_pago']="#templos";
                  $param2['estado']="Pagado";
                  $ok=$this->Dietario_model->marcar_pagado_en_templos($param2);

                  $servicios_pagar[$i]['templos']=0;
                  $carnets_elegidos[$x]['templos_disponibles']=$diferencia;
                }

                // ... Guardamos el movimiento en el historial del carnet.
                unset($param3);
                $param3['id_carnet']=$carnets_elegidos[$x]['id_carnet'];
                $param3['id_dietario']=$servicios_pagar[$i]['id_dietario'];
                $param3['id_servicio']=$servicios_pagar[$i]['id_servicio'];
                $param3['id_cliente']=$servicios_pagar[$i]['id_cliente'];
                $param3['id_empleado']=$servicios_pagar[$i]['id_empleado'];
                $param3['templos']=$templos_iniciales;

                $ok = $this->anadir_historial($param3);
              }
            }
          }
        }
      }
      return 1;
    }
    else {
      return 0;
    }
  }

  // ---------------------------------------------------------------------------------------
  // ... Marcamos todos los servicios como pagados de un carnet especial y en el dietario.
  // ---------------------------------------------------------------------------------------
  function marcar_pago_templos_servicio($parametros) {
    // ... Marcamos com pagado el servicio en el dietario.
    unset($registro);
    $registro['estado']="Pagado";
    $registro['tipo_pago']="#templos";
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_cita']=$parametros['id_cita'];

    $this->Dietario_model->marcar_pagado_en_templos($registro);

    // ... Marcar en el carnet de servicios, el mismo como pagado.
    // antes leemos que empleado a realizado la cita para indicarlo
    // en el servicio que se marca en el carnet.
    $param2['id_cita']=$parametros['id_cita'];
    $cita=$this->Agenda_model->leer_citas($param2);

    unset($registro);
    $registro['id_cliente']=$parametros['id_cliente'];
    $registro['id_dietario']=$parametros['id_dietario'];
    $registro['id_usuario_modificacion']=$cita[0]['id_usuario_empleado'];
    $registro['id']=$parametros['id_carnet_servicio'];

    $this->modificar_servicios($registro);
  }

  // ... Esta funcion se encarga de recargar el carnet una vez haya sido pagado
  function aplicar_recarga_carnet($parametros) {
    // ... Leemos el carnet para extraer los templos disponibles
    unset($param);
    $param['id_carnet']=$parametros['id_carnet'];
    $carnet=$this->leer($param);

    // ... En base a los templos disponibles y a los que se quieren a�adir,
    // se recarga el carnet.
    unset($param);
    $param['id_carnet']=$parametros['id_carnet'];
    $param['templos_disponibles_anteriores']=$carnet[0]['templos_disponibles'];
    $param['templos_disponibles']=($parametros['templos_recarga']+$carnet[0]['templos_disponibles']);

    $ok = $this->ajustes_templos($param);

    return $ok;
  }

  // -------------------------------------------------------------------
  // ... IMPORTACION
  // -------------------------------------------------------------------
  function importar() {
    if (($gestor = fopen(RUTA_SERVIDOR."/recursos/carnets_pozuelo.csv", "r")) !== FALSE) {
      $i=0;

      $control = fopen(RUTA_SERVIDOR."/recursos/importar_carnets_pozuelo.sql","a+");
      if ($control == false) {
        die("No se ha podido crear el archivo de errores 1");
        exit;
      }

      while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        if ($i>0 && $i<3513) {
          if ($datos[0] == "CARNET DE 12 TEMPLOS") { $datos[0]=1; }
          if ($datos[0] == "CARNET DE 12 TEMPLOS (ANIVERSARIO)") { $datos[0]=14; }
          if ($datos[0] == "CARNET DE 20 TEMPLOS") { $datos[0]=2; }
          if ($datos[0] == "CARNET DE 30 TEMPLOS") { $datos[0]=3; }
          if ($datos[0] == "CARNET DE 40 TEMPLOS") { $datos[0]=4; }
          if ($datos[0] == "CARNET DE 50 TEMPLOS (ANIVERSARIO)") { $datos[0]=5; }
          if ($datos[0] == "CARNET DE 60 TEMPLOS") { $datos[0]=6; }
          if ($datos[0] == "CARNET DE 80 TEMPLOS") { $datos[0]=7; }
          if ($datos[0] == "CARNET DE 100 TEMPLOS") { $datos[0]=8; }
          if ($datos[0] == "CARNET DE 100 TEMPLOS (ANIVERSARIO)") { $datos[0]=13; }
          if ($datos[0] == "CARNET DE 125 TEMPLOS (ANIVERSARIO)") { $datos[0]=9; }
          if ($datos[0] == "CARNET DE 125 TEMPLOS") { $datos[0]=9; }
          if ($datos[0] == "CARNET DE 25 TEMPLOS (ANIVERSARIO)") { $datos[0]=10; }
          if ($datos[0] == "CARNET DE 75 TEMPLOS (ANIVERSARIO)") { $datos[0]=12; }

          if ($datos[0] > 0 && $datos[1] != "") {
            // ... Vemos si ya existe el carnet, en cuyo caso no se importa.
            unset($param5);
            $param5['codigo']=$datos[1];
            $carnet = $this->leer($param5);

            $id_carnet=0;
            if (isset($carnet[0]['id_carnet'])) {
              $id_carnet=$carnet[0]['id_carnet'];
            }

            // ... Solo si el carnet no existe ya.
            if ($id_carnet == 0) {
              $registro['id_cliente']=0;

              $registro['id_tipo']=$datos[0];
              $registro['numero_carnet']=$datos[1];
              $registro['templos']=str_replace(",", ".", $datos[2]);
              $a=str_replace(",", ".", $datos[2]);
              $b=str_replace(",", ".", $datos[3]);
              // templos totales - gastados = disponibles.
              $registro['templos_disponibles']=($a-$b);

              unset($param);
              $param['codigo_cliente']=$datos[4]."-P";
              $cliente = $this->Clientes_model->leer_clientes($param);
              if (isset($cliente[0]['id_cliente'])) {
                $registro['id_cliente']=$cliente[0]['id_cliente'];
              }

              if ($registro['id_cliente']==0) {
                unset($param2);
                $param2['nombre']=$datos[9];
                $param2['apellidos']=$datos[10];
                $cliente2 = $this->Clientes_model->leer_clientes($param2);
                if (isset($cliente2[0]['id_cliente'])) {
                  $registro['id_cliente']=$cliente2[0]['id_cliente'];
                }
              }

              $registro['id_centro']=4;
              $registro['precio']=str_replace(",", ".", $datos[6]); // ponemos el separados decimal a .
              $registro['notas']="#importado#";
              $registro['fecha_creacion']=$datos[8];

              $sql="INSERT INTO `carnets_templos` (`id_tipo`, `codigo`, `codigo_pack_online`,`templos`, `templos_disponibles`, `id_cliente`, `id_centro`, `precio`, `notas`,`fecha_creacion`, `id_usuario_creador`, `fecha_modificacion`,`id_usuario_modificacion`, `borrado`, `fecha_borrado`, `id_usuario_borrado`)VALUES ('".$registro['id_tipo']."', '".$registro['numero_carnet']."', NULL,'".$registro['templos']."', '".$registro['templos_disponibles']."','".$registro['id_cliente']."', '".$registro['id_centro']."','".$registro['precio']."', '#importado pozuelo','".$registro['fecha_creacion']."', '1', '2017-02-22 09:09:09','1', '0', NULL, NULL);\n";
              fputs($control,$sql);

              //$this->guardar($registro);
            }
          }
        }
        $i++;
      }

      fclose($control);
      fclose($gestor);
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // ... IMPORTACION CARNETS ESPECIALES
  // -------------------------------------------------------------------
  function importar_carnets_especiales() {
    if (($gestor = fopen(RUTA_SERVIDOR."/recursos/carnets_especiales.csv", "r")) !== FALSE) {
      $i=0;

      $control = fopen(RUTA_SERVIDOR."/recursos/importar_carnets_especiales.sql","a+");
      if ($control == false) {
        die("No se ha podido crear el archivo de errores 1");
        exit;
      }

      while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        if ($i>0 && $i<8139) {
          $datos[0]=99;

          if ($datos[0] > 0 && $datos[1] != "") {
            // ... Vemos si ya existe el carnet, en cuyo caso no se importa.
            unset($param5);
            $param5['codigo']=$datos[1];
            $carnet = $this->leer($param5);

            $id_carnet=0;
            if (isset($carnet[0]['id_carnet'])) {
              $id_carnet=$carnet[0]['id_carnet'];
            }

            // ... Solo si el carnet no existe ya.
            if ($id_carnet == 0) {
              $registro['id_cliente']=0;

              if ($datos[3]==3) { $registro['id_cliente']=842; }
              if ($datos[3]==6) { $registro['id_cliente']=1241; }
              if ($datos[3]==4) { $registro['id_cliente']=1228; }
              if ($datos[3]==7) { $registro['id_cliente']=1240; }

              $registro['id_tipo']=99;
              $registro['numero_carnet']=$datos[1];
              $registro['templos']=0;
              $registro['templos_disponibles']=0;

              //if ($registro['id_cliente']==0) {
                unset($param3);
                $param3['id_cliente']=$datos[2];
                $param3['id_centro']=$datos[3];
                $cliente_esp = $this->Site_model->leer_clientes_especial($param3);

                if (isset($cliente_esp[0]['nombre'])) {
                  unset($param2);
                  $param2['nombre']=$cliente_esp[0]['nombre'];
                  $param2['apellidos']=$cliente_esp[0]['apellidos'];
                  $cliente2 = $this->Clientes_model->leer_clientes($param2);
                  if (isset($cliente2[0]['id_cliente'])) {
                    $registro['id_cliente']=$cliente2[0]['id_cliente'];
                  }
                }
              //}

              $registro['id_centro']=$datos[3];
              $registro['precio']=str_replace(",", ".", $datos[4]); // ponemos el separados decimal a .
              $registro['notas']="##importado-especial##";
              $registro['fecha_creacion']=$datos[6];

              $sql="INSERT INTO `carnets_templos` (`id_tipo`, `codigo`, `codigo_pack_online`,`templos`, `templos_disponibles`, `id_cliente`, `id_centro`, `precio`, `notas`,`fecha_creacion`, `id_usuario_creador`, `fecha_modificacion`,`id_usuario_modificacion`, `borrado`, `fecha_borrado`, `id_usuario_borrado`)VALUES ('".$registro['id_tipo']."', '".$registro['numero_carnet']."', NULL,'".$registro['templos']."', '".$registro['templos_disponibles']."','".$registro['id_cliente']."', '".$registro['id_centro']."','".$registro['precio']."', '##importado-especial##','".$registro['fecha_creacion']."', '1', '2017-03-10 09:09:09','1', '0', NULL, NULL);\n";
              fputs($control,$sql);

              /////$this->guardar($registro);
            }
          }
        }
        $i++;
      }

      fclose($control);
      fclose($gestor);
    }

    return 1;
  }

  // -------------------------------------------------------------------
  // ... SERVICIOS CARNETS ESPECIALES
  // -------------------------------------------------------------------
  function importar_carnets_especiales_servicios() {
    if (($gestor = fopen(RUTA_SERVIDOR."/recursos/carnets_especiales_servicios.csv", "r")) !== FALSE) {
      $i=0;

      $control = fopen(RUTA_SERVIDOR."/recursos/importar_carnets_especiales_servicios.sql","a+");
      if ($control == false) {
        die("No se ha podido crear el archivo de errores 1");
        exit;
      }

      while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
        if ($i>0 && $i<20316) {
          $id_carnet=0;

          unset($param5);
          $param5['codigo']=$datos[1];
          $param5['notas']="##importado-especial##";
          $carnet = $this->leer($param5);
          if (isset($carnet[0]['id_carnet'])) {
            $id_carnet=$carnet[0]['id_carnet'];
          }

          // ... Solo si el carnet existe y es de los importados, copiamos los servicios
          if ($id_carnet > 0 && $datos[3] > 0) {
            $registro['id_cliente']=0;

            $registro['numero_carnet']=$datos[1];
            $registro['id_servicio']=$datos[3];

            if ($carnet[0]['id_cliente']>0 && $datos[6]==1) {
              $registro['id_cliente']=$carnet[0]['id_cliente'];
            }

            $registro['id_centro']=$datos[5];
            $registro['gastado']=$datos[6];
            $registro['pvp']=str_replace(",", ".", $datos[7]); // ponemos el separados decimal a .
            $registro['fecha_creacion']=$datos[8];

            $sql="INSERT INTO `carnets_templos_servicios` (`id_carnet`, `id_dietario`, `id_servicio`, `id_cliente`, `id_centro`, `gastado`, `pvp`, `fecha_creacion`, `id_usuario_creador`, `fecha_modificacion`, `id_usuario_modificacion`, `borrado`, `fecha_borrado`, `id_usuario_borrado`) VALUES('".$id_carnet."', 0, ".$registro['id_servicio'].", ".$registro['id_cliente'].", ".$registro['id_centro'].", ".$registro['gastado'].", '".$registro['pvp']."', '".$registro['fecha_creacion']."', 1, '".$registro['fecha_creacion']."', 1, 0, NULL, NULL);\n";
            fputs($control,$sql);
          }
        }
        $i++;
      }

      fclose($control);
      fclose($gestor);
    }

    return 1;
  }

  function existe_codigo($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['codigo'])) {
        $parametros['codigo']=strtoupper($parametros['codigo']);
        $busqueda.=" AND codigo = @codigo ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT codigo,id_carnet FROM carnets_templos WHERE 1=1 ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function carnets_json($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['q'])) {
      $busqueda.=" AND carnets_templos.codigo like '%".$parametros['q']."%' ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT carnets_templos.id_carnet as id,
    carnets_templos.codigo as name,
    carnets_templos.codigo as text
    FROM carnets_templos
    WHERE carnets_templos.borrado = 0 and carnets_templos.id_tipo < 99 ".$busqueda." ORDER BY codigo ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function carnets_json_todos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['q'])) {
      $busqueda.=" AND carnets_templos.codigo like '%".$parametros['q']."%' ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT carnets_templos.id_carnet as id,
    carnets_templos.codigo as name,
    carnets_templos.codigo as text
    FROM carnets_templos
    WHERE carnets_templos.borrado = 0 ".$busqueda." ORDER BY codigo ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function leer_templos_carnet_especial($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT IFNULL(SUM(servicios.templos),0) as templos
    FROM carnets_templos_servicios
    left join servicios on servicios.id_servicio = carnets_templos_servicios.id_servicio
    WHERE carnets_templos_servicios.borrado = 0
    and carnets_templos_servicios.id_carnet = @id_carnet ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos[0]['templos'];
  }

  function leer_carnets_pago_dietario($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    //14/04/20 se colocó CT.templos AS templos_detalle,carnets_templos.templos_disponibles
    //,CT.templos AS templos_detalle,carnets_templos.templos_disponibles 
    //,CTE.id_servicio AS id_servicio_detalle,carnets_templos.templos_disponibles
    $sentencia_sql=
    "
      SELECT CT.id_carnet,carnets_templos.codigo,carnets_templos.templos_disponibles,CT.templos AS templos_detalle from carnets_templos_historial AS CT
      left join carnets_templos on carnets_templos.id_carnet = CT.id_carnet
      where CT.id_dietario = @id_dietario and CT.borrado = 0
    UNION
      select CTE.id_carnet,carnets_templos.codigo,carnets_templos.templos_disponibles,CTE.gastado AS templos_detalle from carnets_templos_servicios as CTE
      left join carnets_templos on carnets_templos.id_carnet = CTE.id_carnet
      where CTE.id_dietario = @id_dietario and CTE.borrado = 0
    ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }
  
  //15/04/20 Para ticket recarga de templos o carnets
  function leer_un_carnet_templos($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Leemos los registros
    $sentencia_sql="SELECT C.id_carnet,C.id_tipo,C.codigo,C.codigo_pack_online,C.templos AS templos_saldo,
    C.templos_disponibles,C.id_cliente,C.id_centro,C.precio,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado
    FROM carnets_templos AS C
    WHERE C.id_carnet = @id_carnet";

    //$parametros['id_carnet']=$id_carnet;

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  //Fin

  /*
   * Guarda el historial de cambios de numero de carnet.
   *
   */
  function cambio_numeracion($cambio)
  {
    $AqConexion_model = new AqConexion_model();

    $AqConexion_model->insert('carnets_templos_cambios',$cambio);

    return 1;
  }

  /*
   * Comprueba si un carnet ha sido usado alguna vez
   *
   */
  function usado($id_carnet)
  {
    $AqConexion_model = new AqConexion_model();

    $parametros['id_carnet']=$id_carnet;

    $sentencia_sql="SELECT count(id) as num FROM carnets_templos_historial
    WHERE id_carnet = @id_carnet";
    $r1 = $AqConexion_model->select($sentencia_sql,$parametros);

    $sentencia_sql="SELECT count(id) as num FROM carnets_templos_servicios
    WHERE id_carnet = @id_carnet AND gastado = 1";
    $r2 = $AqConexion_model->select($sentencia_sql,$parametros);

    $sentencia_sql="SELECT count(id_carnet) as num FROM carnets_templos
    WHERE id_carnet = @id_carnet AND borrado = 1";
    $r3 = $AqConexion_model->select($sentencia_sql,$parametros);

    if ($r1[0]['num'] == 0 && $r2[0]['num'] == 0 && $r3[0]['num'] == 0)
    {
      return FALSE;
    }
    else
    {
      return TRUE;
    }
  }

  /*
   * Devuelve el id_carnet usado para pagar otro carnet
   * en templos
   */
  function carnet_usado_pago_templos($id_dietario)
  {
    $AqConexion_model = new AqConexion_model();

    $parametros['id_dietario']=$id_dietario;

    $sentencia_sql="SELECT carnets_templos_historial.id_carnet,carnets_templos.codigo
    FROM carnets_templos_historial
    LEFT JOIN carnets_templos ON carnets_templos.id_carnet = carnets_templos_historial.id_carnet
    WHERE carnets_templos_historial.id_dietario = $id_dietario and
    carnets_templos_historial.borrado = 0 and
    carnets_templos.borrado = 0 ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

}
?>
