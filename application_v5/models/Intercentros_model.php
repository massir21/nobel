<?php
class Intercentros_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... INTERCENTROS
  // -------------------------------------------------------------------
  function datos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND D.id_centro = @id_centro ";
    }
    
    if (isset($parametros['id_cen_carnet'])) {
      $busqueda.=" AND CT.id_centro = @id_cen_carnet ";
    }
    
    if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
      $busqueda.=" AND D.fecha_hora_concepto >= @fecha_desde
      AND D.fecha_hora_concepto <= @fecha_hasta ";      
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT D.fecha_hora_concepto,S.abreviatura as servicio,
    CT.codigo,centros.nombre_centro as original_de,C2.nombre_centro as usado_en,
    round((CT.precio/CT.templos)*(select sum(templos) from carnets_templos_historial
    where id_dietario = D.id_dietario and id_carnet = C.id_carnet),2) as total,
    (select sum(templos) from carnets_templos_historial where id_dietario = D.id_dietario
    and id_carnet = C.id_carnet)    
    as templos,
    DATE_FORMAT(D.fecha_hora_concepto,'%H:%i') as hora,
    DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y') as fecha,centros.id_centro,
    (select IFNULL(round((CT.precio/CT.templos)*SUM(templos_disponibles_anteriores),2),0)
    from carnets_templos_ajustes where id_carnet = C.id_carnet and borrado = 0
    and id_centro = @id_centro) as total_sin_recargas
    from carnets_templos_historial as C
    left join dietario as D on D.id_dietario = C.id_dietario
    left join carnets_templos as CT  on CT.id_carnet = C.id_carnet
    left join centros on centros.id_centro = CT.id_centro
    left join centros as C2 on C2.id_centro = D.id_centro
    left join servicios as S on S.id_servicio = D.id_servicio
    where C.borrado = 0 and D.borrado = 0 and C.id_dietario > 0     
    and D.id_servicio > 0
    and CT.id_centro <> D.id_centro ".$busqueda."
    
    UNION ALL 
    
    SELECT D.fecha_hora_concepto,S.abreviatura as servicio,CT.codigo,
    centros.nombre_centro as original_de,C2.nombre_centro as usado_en,    
    round(
      (
      round(CT.precio /
      (SELECT sum(servicios.templos) FROM carnets_templos_servicios
      left join servicios on servicios.id_servicio = carnets_templos_servicios.id_servicio
      where id_carnet = CT.id_carnet and carnets_templos_servicios.borrado = 0),2)
      *
      D.templos
      )
    ,2) as total,D.templos,
    DATE_FORMAT(D.fecha_hora_concepto,'%H:%i') as hora,
    DATE_FORMAT(D.fecha_hora_concepto,'%d-%m-%Y') as fecha,centros.id_centro,
    0 as total_sin_recargas
    from carnets_templos_servicios as C
    left join dietario as D on D.id_dietario = C.id_dietario
    left join carnets_templos as CT on CT.id_carnet = C.id_carnet
    left join centros on centros.id_centro = CT.id_centro
    left join centros as C2 on C2.id_centro = D.id_centro
    left join servicios as S on S.id_servicio = D.id_servicio
    where C.borrado = 0 and D.borrado = 0 and C.id_dietario > 0     
    and D.id_servicio > 0
    and CT.id_centro <> D.id_centro ".$busqueda."
    
    ORDER BY original_de,fecha_hora_concepto desc";
    
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function leer_centros_nombre($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_centro'])) {
      $busqueda.=" AND centros.id_centro = @id_centro ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_centro,nombre_centro,codigo,
    email,telefono,estado,saldo_inicial,id_usuario_creador,
    fecha_creacion,id_usuario_modificacion,
    fecha_modificacion,borrado,id_usuario_borrado,fecha_borrado
    FROM centros     
    WHERE borrado = 0 ".$busqueda." ORDER BY nombre_centro ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
}
?>