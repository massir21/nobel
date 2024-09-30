<?php
class Avisos_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * RECORDATORIOS
     *
     */
    function recordatorios($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_recordatorio'])) {
            $busqueda .= " AND R.id_recordatorio = @id_recordatorio ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND R.id_centro = @id_centro ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND R.estado = @estado ";
        }

        if (isset($parametros['repetir'])) {
            $busqueda .= " AND R.repetir = @repetir ";
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND R.posponer >= @fecha_desde AND R.posponer <= @fecha_hasta ";
        }

        if (isset($parametros['fecha_desde_inicial']) && isset($parametros['fecha_hasta_inicial'])) {
            $busqueda .= " AND R.fecha_hora >= @fecha_desde_inicial AND R.fecha_hora <= @fecha_hasta_inicial ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      R.id_recordatorio,R.fecha_hora,R.posponer,R.recordatorio,R.id_usuario_creacion,
      R.fecha_creacion,R.id_usuario_modificacion,R.fecha_modificacion,R.borrado,
      R.fecha_borrado,R.id_usuario_borrado,R.id_centro,R.estado,R.repetir,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) as usuario_creador,
      DATE_FORMAT(R.fecha_hora,'%d-%m-%Y %H:%i') as fecha_hora_ddmmaaaa_hhss,
      DATE_FORMAT(R.fecha_hora,'%Y-%m-%d %H:%i') as fecha_hora_aaaammdd_hhss,
      DATE_FORMAT(R.fecha_hora,'%Y-%m-%d') as fecha_aaaammdd,
      DATE_FORMAT(R.fecha_hora,'%H:%i') as hora,
      DATE_FORMAT(R.posponer,'%d-%m-%Y %H:%i') as posponer_ddmmaaaa_hhss,
      DATE_FORMAT(R.posponer,'%Y-%m-%d %H:%i') as posponer_aaaammdd_hhss,
      centros.nombre_centro
    FROM
      recordatorios AS R
      LEFT JOIN usuarios ON usuarios.id_usuario = R.id_usuario_creacion
      LEFT JOIN centros ON centros.id_centro = R.id_centro
    WHERE
      R.borrado = 0 {$busqueda}
    ORDER BY
      R.fecha_hora
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function guardar_recordatorio($recordatorio)
    {
        $AqConexion_model = new AqConexion_model();

        $AqConexion_model->insert('recordatorios', $recordatorio);
    }

    function actualizar_recordatorio($recordatorio, $id_recordatorio)
    {
        $AqConexion_model = new AqConexion_model();

        $where['id_recordatorio'] = $id_recordatorio;
        $AqConexion_model->update('recordatorios', $recordatorio, $where);
    }

    function recordarorios_posponer($id_recordatorio = 0)
    {
        $AqConexion_model = new AqConexion_model();

        if ($id_recordatorio > 0) {
            $parametros['id_recordatorio'] = $id_recordatorio;
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");

            $sentenciaSQL = "UPDATE recordatorios set posponer = (posponer + INTERVAL 30 MINUTE),
      id_usuario_modificacion = @id_usuario_modificacion,
      fecha_modificacion = @fecha_modificacion
      where id_recordatorio = @id_recordatorio AND id_centro = @id_centro ";

            $AqConexion_model->no_select($sentenciaSQL, $parametros);
        }
    }

    function horarios_recordatorios()
    {
        $horarios[0] = "10:00";
        $horarios[1] = "10:30";
        $horarios[2] = "11:00";
        $horarios[3] = "11:30";
        $horarios[4] = "12:00";
        $horarios[5] = "12:30";
        $horarios[6] = "13:00";
        $horarios[7] = "13:30";
        $horarios[8] = "14:00";
        $horarios[9] = "14:30";
        $horarios[10] = "15:00";
        $horarios[11] = "15:30";
        $horarios[12] = "16:00";
        $horarios[13] = "16:30";
        $horarios[14] = "17:00";
        $horarios[15] = "17:30";
        $horarios[16] = "18:00";
        $horarios[17] = "18:30";
        $horarios[18] = "19:00";
        $horarios[19] = "19:30";
        $horarios[20] = "20:00";
        $horarios[21] = "20:30";
        $horarios[22] = "21:00";
        $horarios[23] = "21:30";
        $horarios[24] = "22:00";

        return $horarios;
    }

    /**
     *
     * CITAS EN ESPERA.
     *
     */
    function citas_espera($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_cita_espera'])) {
            $busqueda .= " AND C.id_cita_espera = @id_cita_espera ";
        }

        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND C.id_centro = @id_centro ";
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND C.estado = @estado ";
        }

        if (isset($parametros['posponer_inicio']) && isset($parametros['posponer_fin'])) {
            $busqueda .= " AND C.posponer <= @posponer_inicio ";
        }

        if (isset($parametros['fecha_hora_inicio']) && isset($parametros['fecha_hora_fin'])) {
            $busqueda .= " AND C.fecha_hora_inicio >= @fecha_hora_inicio AND
      C.fecha_hora_fin <= @fecha_hora_fin ";
        }

        // ... Leemos los registros
        $sentencia_sql = "
    SELECT
      C.id_cita_espera,C.fecha_hora_inicio,C.fecha_hora_fin,C.posponer,C.id_usuario_empleado,
      C.id_usuario_creacion,C.fecha_creacion,C.id_usuario_modificacion,C.id_servicio,
      C.fecha_modificacion,C.borrado,C.fecha_borrado,C.id_usuario_borrado,C.id_cliente,
      C.id_centro,C.estado,CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente,
      clientes.telefono,
      CONCAT(usuarios.nombre, ' ', usuarios.apellidos) as usuario_creador,

      /*********************************************************************************/
      /* añadido para mostrar los datos de la última modificación de la cita en espera */
      /*********************************************************************************/

      CONCAT(modificacion.nombre, ' ', modificacion.apellidos) as usuario_modif,

      /*********************************************************************************/
      /* añadido para mostrar los datos de la última modificación de la cita en espera */
      /*********************************************************************************/

      CONCAT(empleados.nombre, ' ', empleados.apellidos) as empleado,
      DATE_FORMAT(C.fecha_hora_inicio,'%d-%m-%Y') as fecha,
      DATE_FORMAT(C.fecha_hora_inicio,'%W<br>%d-%b-%Y') as fecha_ddmmaaaa_abrv,
      DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') as fecha_aaaammdd,
      DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') as fecha_inicio_aaaammdd,
      DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') as hora_inicio,
      DATE_FORMAT(C.fecha_hora_fin,'%H:%i') as hora_fin,
      DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d %H:%i') as fecha_hora_inicio,
      DATE_FORMAT(C.fecha_hora_fin,'%Y-%m-%d %H:%i') as fecha_hora_fin,
      DATE_FORMAT(C.posponer,'%d-%m-%Y %H:%i') as posponer_ddmmaaaa_hhss,
      DATE_FORMAT(C.posponer,'%Y-%m-%d %H:%i') as posponer_aaaammdd_hhss,
      centros.nombre_centro,servicios.nombre_servicio,servicios.duracion,
      servicios_familias.nombre_familia,C.como_contactar,C.notas
    FROM
      citas_espera AS C
      LEFT JOIN usuarios ON usuarios.id_usuario = C.id_usuario_creacion

      /*********************************************************************************/
      /* añadido para mostrar los datos de la última modificación de la cita en espera */
      /*********************************************************************************/

      LEFT JOIN usuarios as modificacion ON modificacion.id_usuario = C.id_usuario_modificacion

      /*********************************************************************************/
      /* añadido para mostrar los datos de la última modificación de la cita en espera */
      /*********************************************************************************/

      LEFT JOIN usuarios as empleados ON empleados.id_usuario = C.id_usuario_empleado
      LEFT JOIN centros ON centros.id_centro = C.id_centro
      LEFT JOIN clientes ON clientes.id_cliente = C.id_cliente
      LEFT JOIN (
        servicios LEFT JOIN servicios_familias
        ON servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
        ON servicios.id_servicio = C.id_servicio
    WHERE
      C.borrado = 0 {$busqueda}
    ORDER BY
      C.fecha_hora_inicio
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function guardar_cita_espera($cita_espera)
    {
        $AqConexion_model = new AqConexion_model();

        $id_cita_espera = $AqConexion_model->insert('citas_espera', $cita_espera);
    }

    function actualizar_cita_espera($cita_espera, $id_cita_espera)
    {
        $AqConexion_model = new AqConexion_model();

        $where['id_cita_espera'] = $id_cita_espera;
        $AqConexion_model->update('citas_espera', $cita_espera, $where);
    }

    /**
     *
     * Devuelve un array con las horas disponibles que tiene una
     * cita en espera.
     */
    function citas_espera_horas_libres($id_cita_espera = 0)
    {
        $empleados_medaigual = "";

        unset($parametros);
        $parametros['id_cita_espera'] = $id_cita_espera;
        $cita_espera = $this->Avisos_model->citas_espera($parametros);
        //echo "<br>"."Modelo p1 ".$id_cita_espera;
        unset($parametros);
        $parametros['id_empleado'] = $cita_espera[0]['id_usuario_empleado'];
        $parametros['fecha'] = $cita_espera[0]['fecha'];
        $parametros['duracion'] = $cita_espera[0]['duracion'];
        //echo "<br>"."Modelo p2 ".$parametros['id_empleado'];
        // ... Si el empleado es igual 0 entonce se eligio me da igual
        // entonces extraemos los empleados para el centro, servicios y fecha indicados.
        if ($parametros['id_empleado'] == 0) //Original if ($parametros['id_empleado'] === 0)
        {
            //echo "<br>"."Modelo p3 ";
            $servicios = array(intval($cita_espera[0]['id_servicio']));
            $empleados_medaigual = $this->Avisos_model->empleados_medaigual($servicios, $cita_espera[0]['id_centro'], $cita_espera[0]['fecha'], "");
            // esto es original
            /*
      echo '<pre>';
      var_dump($empleados_medaigual);
      
      exit;
      */
        }



        $datos = array();
        //echo "<br>"."Modelo p4 ";
        // ... Si es para un empleado concreto.
        if ($empleados_medaigual == "") {
            //echo "<br>"."Modelo p5 ";
            $datos = $this->Agenda_model->horas_libres($parametros);
        }
        // ... Si se ha elegido da igual, entonces
        // calculo las horas libres de cada empleado posible
        else {
            //echo "<br>"."Modelo p6 ";
            $todo = array();

            $empleados = explode(";", $empleados_medaigual);

            foreach ($empleados as $id_empleado) {
                $parametros['id_empleado'] = $id_empleado;

                $r = $this->Agenda_model->horas_libres($parametros);

                if ($r != 0) {
                    $todo = array_merge($todo, $r);
                }
            }

            $todo = array_unique($todo);
            asort($todo);

            foreach ($todo as $item) {
                array_push($datos, $item);
            }
        }

        return $datos;
    }

    //
    // ... Devolvemos los id_empleado al elegir me da igual
    // al pedir una cita, separados por ;
    //
    function empleados_medaigual($servicios, $id_centro, $fecha, $id_empleado_medaigual)
    {
        // Leemos los empleados diferentes existentes
        // con los servicios y fecha elegida.
        unset($param);
        $param['servicios'] = $servicios;
        $param['id_centro'] = $id_centro;
        $param['fecha'] = $fecha;
        $param['id_empleado_medaigual'] = $id_empleado_medaigual;

        $empleados = $this->Agenda_model->empleados_disponibles($param);

        $r = "";
        //echo "<br>"." Antes del if ".$empleados;
        if ($empleados != 0) {
            //echo "<br>"." Paso el if de empleados";
            foreach ($empleados as $row) {
                $r .= $row['id_empleado'] . ";";
            }
            $r = substr($r, 0, -1);
        }

        return $r;
    }

    function citas_espera_perdidas()
    {
        $AqConexion_model = new AqConexion_model();

        $sentenciaSQL = "
      UPDATE citas_espera SET estado = 'Perdida' where id_cita_espera IN
      (
	SELECT id_cita_espera from (SELECT * FROM citas_espera) AS  citas_espera2
	WHERE
          borrado = 0 and
          fecha_hora_fin < now()
      )
    ";

        $parametros['vacio'] = "";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);
    }

    function citas_espera_agendadas($cita_espera = 0)
    {
        $AqConexion_model = new AqConexion_model();

        if ($cita_espera != 0) {
            $sentenciaSQL = "
      SELECT
        *
      FROM
        citas
      WHERE
        borrado = 0 and
        (fecha_hora_inicio BETWEEN @fecha_hora_inicio AND @fecha_hora_fin) and
        id_servicio = @id_servicio and
        id_usuario_empleado in
          (select id_usuario from usuarios where borrado = 0 and id_centro = @id_centro) and
        id_cliente = @id_cliente and
        duracion = @duracion
      ";
            $parametros['fecha_hora_inicio'] = $cita_espera['fecha_hora_inicio'];
            $parametros['fecha_hora_fin'] = $cita_espera['fecha_hora_fin'];
            $parametros['id_servicio'] = $cita_espera['id_servicio'];
            $parametros['id_centro'] = $cita_espera['id_centro'];
            $parametros['id_cliente'] = $cita_espera['id_cliente'];
            $parametros['duracion'] = $cita_espera['duracion'];

            $datos = $AqConexion_model->select($sentenciaSQL, $cita_espera);

            if ($datos != 0) {
                $where['id_cita_espera'] = $cita_espera['id_cita_espera'];
                $param['estado'] = "Agendada";
                $AqConexion_model->update('citas_espera', $param, $where);

                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    function citas_espera_posponer()
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        $recordatorio['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $recordatorio['fecha_modificacion'] = date("Y-m-d H:i:s");

        $sentenciaSQL = "UPDATE citas_espera set posponer = (posponer + INTERVAL 30 MINUTE),
    id_usuario_modificacion = @id_usuario_modificacion,
    fecha_modificacion = @fecha_modificacion
    where id_centro = @id_centro";

        $AqConexion_model->no_select($sentenciaSQL, $parametros);
    }

    function leer_tareas($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['estado'])) {
            if ($parametros['estado'] == "Pendiente" or $parametros['estado'] == "Finalizado")
                $busqueda .= " AND T.estado = @estado ";
        }
        $sentenciaSQL = "
            SELECT
            T.id,T.titulo,T.contenido,T.estado,
            DATE_FORMAT(T.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion,
            DATE_FORMAT(T.fecha_ejecucion,'%d-%m-%Y %H:%i') as fecha_ejecucion,
            DATE_FORMAT(T.fecha_modificacion,'%d-%m-%Y %H:%i') as fecha_modificacion,
            T.id_creador, CONCAT(empleados.nombre, ' ', empleados.apellidos) as usuario_creador
            FROM
            tareas AS T
            LEFT JOIN usuarios as empleados ON empleados.id_usuario = T.id_creador  
            WHERE
            T.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentenciaSQL, $parametros);

        return $datos;
    }

    //Leer una tarea por id
    function leer_tarea_id($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_tarea'])) {
            $busqueda .= " AND T.id = @id_tarea ";
        }
        if (isset($parametros['id_centro'])) {
            $busqueda .= " AND empleados.id = @id_centro ";
        }
        if (isset($parametros['id_creador'])) {
            $busqueda .= " AND T.id_creador = @id_creador ";
        }
        $sentenciaSQL = "
            SELECT
            T.id,T.titulo,T.contenido,T.estado,T.fecha_creacion,
            DATE_FORMAT(T.fecha_ejecucion,'%Y-%m-%d') as fecha_ejecucion,
            T.id_creador, CONCAT(empleados.nombre, ' ', empleados.apellidos) as usuario_creador
            FROM
            tareas AS T
            LEFT JOIN usuarios as empleados ON empleados.id_usuario = T.id_creador 
            WHERE
            T.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentenciaSQL, $parametros);

        return $datos;
    }


    //Función para leer las tareas sino es master
    function leer_tareas_usuario($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $id_usuario = $this->session->userdata('id_usuario');
        //$id_usuario=17;

        $busqueda = "";
        if (isset($parametros['estado'])) {
            if ($parametros['estado'] == "Pendiente" or $parametros['estado'] == "Finalizado")
                $busqueda .= " AND T.estado = @estado ";
        }
        $busqueda .= " AND (TA.id_creador = $id_usuario OR TA.id_usuario= $id_usuario) ";
        $sentenciaSQL = "
            SELECT
            T.id,T.titulo,T.contenido,T.estado,T.fecha_creacion,T.fecha_ejecucion,
            T.id_creador, CONCAT(empleados.nombre, ' ', empleados.apellidos) as usuario_creador
            FROM
            tareas_asignados AS TA
            LEFT JOIN tareas AS T ON T.id =  TA.id_tarea
            LEFT JOIN usuarios as empleados ON empleados.id_usuario = T.id_creador  
            WHERE
            T.borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentenciaSQL, $parametros);

        return $datos;
    } //Funcion

    //,  CONCAT(empleados.nombre, ' ', empleados.apellidos) as usuario_creador
    //LEFT JOIN usuarios as empleados ON empleados.id_usuario = T.id_creador  

    function tarea_asignados($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_tarea'])) {
            $busqueda .= " AND A.id_tarea = @id_tarea ";
        }
        $sentenciaSQL = "
            SELECT
            A.id_usuario,empleados.nombre,empleados.apellidos,A.id AS id_asignacion
            FROM
            tareas_asignados AS A
            LEFT JOIN usuarios as empleados ON empleados.id_usuario = A.id_usuario
            WHERE
            borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentenciaSQL, $parametros);
        return $datos;
    }

    function tarea_iteraciones($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $busqueda = "";
        if (isset($parametros['id_tarea'])) {
            $busqueda .= " AND A.id_tarea = @id_tarea ";
        }
        $sentenciaSQL = "
            SELECT
            A.id_usuario,A.comentario,empleados.nombre,empleados.apellidos,
            DATE_FORMAT(A.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion
            FROM
            tareas_iteraciones AS A
            LEFT JOIN usuarios as empleados ON empleados.id_usuario = A.id_usuario
            WHERE
            borrado = 0 " . $busqueda;
        $datos = $AqConexion_model->select($sentenciaSQL, $parametros);
        return $datos;
    }

    function insert_tarea($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('tareas', $parametros);
        $sentenciaSQL = "select max(id) as id_tarea from tareas";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);
        return $resultado[0]['id_tarea'];
    }

    function insert_tareas_asignados($parametros)
    {
        $id_tarea = $parametros['id_tarea'];
        // buscar los asignados a la tarea
        $param['id_tarea'] = $id_tarea;
        $asignados = $this->Avisos_model->tarea_asignados($param);
        $usuarios_asignado = [];
        // recorro los asignados existentes para borrarlos que no estan en los que llegan y guardar en un array los que ya estan
        if(is_array($asignados)){
            foreach ($asignados as $key => $value) {
                if (!in_array($value['id_usuario'], $parametros['quienes'])) {
                    // si el existente no esta en los que llegan, se borra
                    $this->db->where('id', $value['id_asignacion']);
                    $this->db->delete('tareas_asignados');
                } else {
                    $usuarios_asignado[] = $value['id_usuario'];
                }
            }
        }
        // recorro los que llegan para añadir los que no esten ya asignados
        foreach ($parametros['quienes'] as $key => $llega) {
            if (!in_array($llega, $usuarios_asignado)) {

                // si el existente no esta en los que llegan, se borra
                $data['id_creador'] = $parametros['id_creador'];
                $data['id_tarea'] = $id_tarea;
                $data['id_usuario'] = $llega;
                $data['fecha_creacion'] = date("Y-m-d H:i:s");
                $this->db->insert('tareas_asignados', $data);
                //$this->db->last_query();
            }
        }
        return 0;
    }

    function insert_tarea_iteracion($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('tareas_iteraciones', $parametros);
        return 0;
    }

    function update_tarea($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $updateparams['id'] = $this->input->post('id_tarea');
            $updateparams['titulo'] = $this->input->post('titulo');
            $updateparams['estado'] = $this->input->post('estado');
            $updateparams['fecha_ejecucion'] = $this->input->post('fecha_ejecucion');
            $updateparams['contenido'] = $this->input->post('contenido');
            $updateparams['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro = [];
        if(isset($parametros['titulo'])){
            $registro['titulo'] = $parametros['titulo'];
        }
        if(isset($parametros['estado'])){
            $registro['estado'] = $parametros['estado'];
        }
        if(isset($parametros['fecha_ejecucion'])){
            $registro['fecha_ejecucion'] = $parametros['fecha_ejecucion'];
        }
        if(isset($parametros['contenido'])){
            $registro['contenido'] = $parametros['contenido'];
        }
        if(isset($parametros['borrado'])){
            $registro['borrado'] = $parametros['borrado'];
        }
        
        if(count($registro) > 0){
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $where['id'] = $parametros['id'];
            $AqConexion_model->update('tareas', $registro, $where);
            return 1;
        }else{
            return 0;
        }
    }
}
