<?php
class Horarios_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  // -------------------------------------------------------------------
  // ... HORARIOS
  // -------------------------------------------------------------------
  function leer_horarios($parametros) {
    $AqConexion_model = new AqConexion_model();

    $param['vacio']="";
    $sentenciaSQL="SET lc_time_names = 'es_VE';";
    $AqConexion_model->no_select($sentenciaSQL,$param);

    $busqueda="";

    if (isset($parametros['id_usuario'])) {
      $busqueda.=" AND usuarios_horarios.id_usuario = @id_usuario ";
    }

    if (isset($parametros['id_horario'])) {
      $busqueda.=" AND usuarios_horarios.id_horario = @id_horario ";
    }

    if (isset($parametros['fecha'])) {
      $busqueda.=" AND (DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d') <= @fecha
      AND DATE_FORMAT(usuarios_horarios.fecha_fin,'%Y-%m-%d') >= @fecha) ";
    }

    if (isset($parametros['jornada'])) {
      if(is_array($parametros['jornada'])){
        $busqueda.=" AND (";
        $total = count($parametros['jornada']);
        $i = 1;
        foreach ($parametros['jornada'] as $key => $value) {
          if($i > 1){ 
            // si no es la primera vuelta, se añade el OR
            $busqueda.=" OR ";
          }

          $busqueda.=" usuarios_horarios.jornada = '".$value."'";

          if($i == $total){ 
            // Si es la última vuelta, se cierra el paréntesis del grupo de condiciones
            $busqueda.=" ) ";
          }

          $i = $i + 1;
        }

      }else{
        $busqueda.=" ";
      }
      unset($parametros['jornada']);
      
    }else{
      $busqueda.=" AND usuarios_horarios.jornada != 'Vacaciones' AND usuarios_horarios.jornada != 'Baja'";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT usuarios_horarios.id_horario,usuarios_horarios.id_usuario,
    usuarios_horarios.fecha_inicio,usuarios_horarios.fecha_fin,
    usuarios_horarios.id_usuario_creacion,usuarios_horarios.fecha_creacion,
    usuarios_horarios.id_usuario_modificacion,usuarios_horarios.fecha_modificacion,
    usuarios_horarios.borrado,usuarios_horarios.id_usuario_borrado,
    usuarios_horarios.fecha_borrado,
    DATE_FORMAT(usuarios_horarios.fecha_inicio,'%e-%b-%Y (%W)') as fecha_inicio_f,
    DATE_FORMAT(usuarios_horarios.fecha_fin,'%e-%b-%Y (%W)') as fecha_fin_f,
    DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d') as fecha_inicio_ddmmaaaa,
    DATE_FORMAT(usuarios_horarios.fecha_fin,'%Y-%m-%d') as fecha_fin_ddmmaaaa,
    DATE_FORMAT(usuarios_horarios.fecha_inicio,'%H:%i') as hora_inicio,
    DATE_FORMAT(usuarios_horarios.fecha_fin,'%H:%i') as hora_fin,
    DATE_FORMAT(usuarios_horarios.fecha_inicio,'%Y-%m-%d %H:%i') as fecha_inicio_aaaammdd,
    usuarios_horarios.jornada,
    usuarios_horarios.notas_horario
    FROM usuarios_horarios
    WHERE usuarios_horarios.borrado = 0 ".$busqueda." ORDER BY fecha_inicio desc ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return (is_array($datos)) ? $datos : array();
  }

  function horas_trabaja_empleado($parametros) {
    // ... Leemos los horarios que tiene el empleado
    $horario=$this->leer_horarios($parametros);

    $horas_trabaja = array();

    // ... Luego leemos la hora de inicio y de fin.
    $j=0;
    for ($i=0; $i<count($horario); $i++) {
      $horas_trabaja[$j]=$horario[$i]['hora_inicio'];

      $HoraInicio=strtotime($horario[$i]['hora_inicio']);
      $HoraFin=strtotime($horario[$i]['hora_fin']);
      $Duracion=($HoraFin-$HoraInicio)/60;

      // ... Cogemos la hora inicial y le vamos sumando 15 minutos para sacar
      // las horas intermedias.
      for ($x=1; $x<($Duracion/15); $x++) {
        $j++;

        $nuevaHora=date("H:i",strtotime($horario[$i]['hora_inicio'])+(900*($x)));
        $horas_trabaja[$j]=$nuevaHora;
      }

      $j++;
    }

    return $horas_trabaja;
  }

  function hora_limite_fin_empleado($parametros) {
    $AqConexion_model = new AqConexion_model();

    $param['vacio']="";
    $sentenciaSQL="SET lc_time_names = 'es_VE';";
    $AqConexion_model->no_select($sentenciaSQL,$param);

    $busqueda="";

    if (isset($parametros['id_usuario'])) {
      $busqueda.=" AND usuarios_horarios_desglose.id_usuario = @id_usuario ";
    }

    if (isset($parametros['fecha'])) {
      $busqueda.=" AND (DATE_FORMAT(usuarios_horarios_desglose.fecha_inicio,'%Y-%m-%d') <= @fecha
      AND DATE_FORMAT(usuarios_horarios_desglose.fecha_fin,'%Y-%m-%d') >= @fecha) ";
    }

    // ... Leemos los registros
    $sentencia_sql="SELECT DATE_FORMAT(usuarios_horarios_desglose.fecha_fin,'%H:%i') as hora_fin
    FROM usuarios_horarios_desglose
    WHERE 1=1 ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return (isset($datos[0]['hora_fin'])) ? $datos[0]['hora_fin'] : null;
  }

  function nuevo_horario($parametros) {
    $AqConexion_model = new AqConexion_model();

    if (isset($parametros['jornada'])) {
      // ... Datos generales como usuario.
      $registro['id_usuario']=$parametros['id_usuario'];
      $registro['jornada']=$parametros['jornada'];
      $registro['fecha_inicio']=$parametros['fecha_inicio']." ".$parametros['hora_inicio'];
      $registro['fecha_fin']=$parametros['fecha_fin']." ".$parametros['hora_fin'];

      if(isset($parametros['notas'])){
        $registro['notas_horario'] = $parametros['notas'];
      }

      $registro['fecha_creacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
      $registro['borrado']=0;

      $AqConexion_model->insert('usuarios_horarios',$registro);

      $sentenciaSQL="select max(id_horario) as id_horario from usuarios_horarios";
      $resultado = $AqConexion_model->select($sentenciaSQL,null);

      return $resultado[0]['id_horario'];
    }
    else {
      return 0;
    }
  }

  function actualizar_horario($parametros) {
    $AqConexion_model = new AqConexion_model();

    if (isset($parametros['jornada'])) {
      // ... Datos generales como usuario.
      $registro['id_usuario']=$parametros['id_usuario'];
      $registro['jornada']=$parametros['jornada'];
      $registro['fecha_inicio']=$parametros['fecha_inicio']." ".$parametros['hora_inicio'];
      $registro['fecha_fin']=$parametros['fecha_fin']." ".$parametros['hora_fin'];
      if(isset($parametros['notas'])){
        $registro['notas_horario'] = $parametros['notas'];
      }

      $registro['fecha_modificacion']=date("Y-m-d H:i:s");
      $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');

      $where['id_horario']=$parametros['id_horario'];
      $AqConexion_model->update('usuarios_horarios',$registro,$where);

      return 1;
    }
    else {
      return 0;
    }
  }

  function borrar_horario($parametros) {
    $AqConexion_model = new AqConexion_model();

    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");

    $sentenciaSQL="update usuarios_horarios set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_horario = @id_horario";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);

    return 1;
  }

  function ultimo_horario_usuario($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentenciaSQL="select id_horario from usuarios_horarios
    where id_usuario = @id_usuario and borrado = 0 order by fecha_modificacion desc
    limit 1";
    $resultado = $AqConexion_model->select($sentenciaSQL,$parametros);

    return $resultado[0]['id_horario'];
  }

  function tiempo_jornada_empleado($parametros) {
    $AqConexion_model = new AqConexion_model();

    $sentenciaSQL="SELECT
    IFNULL(sum(TIMESTAMPDIFF(minute, fecha_inicio, fecha_fin) / 60),0) AS tiempo_jornada
    FROM usuarios_horarios where id_usuario = @id_usuario and borrado = 0
    and fecha_inicio >= @fecha_inicio
    and fecha_fin <= @fecha_fin ";
    $resultado = $AqConexion_model->select($sentenciaSQL,$parametros);

    return $resultado[0]['tiempo_jornada'];
  }

  function control_rango_fechas($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_horario'])) {
      $busqueda.=" AND usuarios_horarios.id_horario <> @id_horario ";
    }

    $sentenciaSQL="SELECT count(id_horario) as dato FROM `usuarios_horarios`
    WHERE borrado = 0 AND id_usuario = @id_usuario $busqueda
    AND fecha_inicio <= @fecha_inicio and fecha_fin >= @fecha_fin
    AND id_usuario = @id_usuario and borrado = 0 $busqueda
    OR fecha_inicio BETWEEN @fecha_inicio AND @fecha_fin
    AND id_usuario = @id_usuario and borrado = 0 $busqueda
    OR fecha_fin BETWEEN @fecha_inicio AND @fecha_fin
    AND id_usuario = @id_usuario and borrado = 0 $busqueda ";
    $resultado = $AqConexion_model->select($sentenciaSQL,$parametros);

    return $resultado[0]['dato'];
  }

  function desglosar_rango_fechas($parametros) {
    $AqConexion_model = new AqConexion_model();

    // ... Vaciamos la tabla
    $r = $AqConexion_model->vaciar("usuarios_horarios_desglose");

    $sentenciaSQL="SELECT DATE_FORMAT(fecha_inicio,'%Y-%m-%d') as fecha_inicio,
    DATE_FORMAT(fecha_fin,'%Y-%m-%d') as fecha_fin,
    DATE_FORMAT(fecha_inicio,'%H:%i:%s') as hora_inicio,
    DATE_FORMAT(fecha_fin,'%H:%i:%s') as hora_fin,
    id_usuario
    FROM usuarios_horarios WHERE borrado = 0 and fecha_inicio > '2017-02-01'
    and fecha_fin > '2017-02-01'
    order by fecha_inicio ";
    $resultado = $AqConexion_model->select($sentenciaSQL,$parametros);

    foreach ($resultado as $row) {
      $datetime1 = new DateTime($row['fecha_inicio']);
      $datetime2 = new DateTime($row['fecha_fin']);
      $r = $datetime1->diff($datetime2);
      $interval = $r->format('%a');

      //echo date_format($datetime1, 'Y-m-d H:i:s')." - ".date_format($datetime2, 'Y-m-d H:i:s')." = ".$interval."<br>";

      // ... Si la diferencia de dias es igual a 0 entonces insertamos una vez solo.
      if ($interval==0) {
        unset($param);
        $param['id_usuario']=$row['id_usuario'];
        $param['fecha_inicio']=$row['fecha_inicio']." ".$row['hora_inicio'];
        $param['fecha_fin']=$row['fecha_fin']." ".$row['hora_fin'];
        $this->insertar_horario_desglosado($param);
      }
      // ... Sino insertamos una vez, m�s los d�as adicionales.
      else {
        unset($param);
        $param['id_usuario']=$row['id_usuario'];
        $param['fecha_inicio']=$row['fecha_inicio']." ".$row['hora_inicio'];
        $param['fecha_fin']=$row['fecha_inicio']." ".$row['hora_fin'];

        $this->insertar_horario_desglosado($param);

        for ($i=1; $i<($interval+1); $i++) {
          $datetime_inicio = new DateTime($row['fecha_inicio']);
          date_add($datetime_inicio, date_interval_create_from_date_string($i.' days'));

          unset($param);
          $param['id_usuario']=$row['id_usuario'];
          $param['fecha_inicio']=date_format($datetime_inicio, 'Y-m-d')." ".$row['hora_inicio'];
          $param['fecha_fin']=date_format($datetime_inicio, 'Y-m-d')." ".$row['hora_fin'];

          $this->insertar_horario_desglosado($param);
        }
      }
    }

    return 1;
  }

  function insertar_horario_desglosado($parametros) {
    $AqConexion_model = new AqConexion_model();

    $parametros['fecha_creacion']=date("Y-m-d H:i:s");

    $sentenciaSQL="INSERT INTO usuarios_horarios_desglose
    (id_usuario,fecha_inicio,fecha_fin,fecha_creacion)
    VALUES
    (@id_usuario,@fecha_inicio,@fecha_fin,@fecha_creacion)";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);

    return 1;
  }

}
?>
