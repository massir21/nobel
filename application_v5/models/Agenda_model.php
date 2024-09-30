<?php
/* CHAINS 20240222
 *
 * ALTER TABLE citas ADD archivado_parrilla TINYINT(1) NOT NULL DEFAULT 0;
 */
class Agenda_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // -------------------------------------------------------------------
    // ... AGENDA
    // -------------------------------------------------------------------
    function leer_citas($parametros)
    {
     $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $firma_lopd = "";
        $orden = "";

        if (isset($parametros['id_cita'])) {
            if ($parametros['id_cita'] > 0) {
                $busqueda .= " AND C.id_cita = @id_cita ";
            }
        }

        if (isset($parametros['id_cita_excluir'])) {
            // CHAINS 20240127 - Tenemos si es una cita o varias como array

            if(is_array($parametros['id_cita_excluir'])){
                if(count($parametros['id_cita_excluir'])) {
                    $busqueda .= " AND C.id_cita NOT IN (".implode(",", $parametros['id_cita_excluir']).") ";
                    unset($parametros['id_cita_excluir']);
                }
                else {
                    unset($parametros['id_cita_excluir']);
                }
            }
            else
                if ($parametros['id_cita_excluir'] > 0) {
                    $busqueda .= " AND C.id_cita <> @id_cita_excluir ";
                }
            // FIN CHAINS 20240127 - Añadimos los ids a excluir de la busqueda si existen
        }

        // CHAINS 20240128 - Añadir parametro groupcodemd5 para poder buscar
        if(isset($parametros['groupcodemd5'])){
            $busqueda.= " AND C.groupcodemd5 = @groupcodemd5 ";
        }
        // FIN CHAINS 20240128

        if (isset($parametros['id_servicio'])) {
            if ($parametros['id_servicio'] > 0) {
                $busqueda .= " AND C.id_servicio = @id_servicio ";
            }
        }

        if (isset($parametros['id_empleado'])) {
            if ($parametros['id_empleado'] > 0) {
                $busqueda .= " AND C.id_usuario_empleado = @id_empleado ";
            }
        }

        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                $busqueda .= " AND C.id_cliente = @id_cliente ";
            }
        }

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND C.id_usuario_empleado in (select id_usuario from usuarios where id_centro = @id_centro) ";
            }
        }

        if (isset($parametros['fecha'])) {
            if ($parametros['fecha'] != "") {
                $parametros['fecha'] = str_replace("-", "/", $parametros['fecha']);
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%d/%m/%Y') = @fecha ";
            }
        }

        if (isset($parametros['fecha_desde'])) {
            $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') >= @fecha_desde ";
        }

        if (isset($parametros['fecha_hasta'])) {
            $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') <= @fecha_hasta ";
        }

        if (isset($parametros['fecha_aaaammdd'])) {
            if ($parametros['fecha_aaaammdd'] != "") {
                $parametros['fecha_aaaammdd'] = str_replace("-", "/", $parametros['fecha_aaaammdd']);
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y/%m/%d') = @fecha_aaaammdd ";
            }
        }

        if (isset($parametros['hora'])) {
            if ($parametros['hora'] != "") {
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') = @hora ";
            }
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND C.estado = @estado ";
        }

        if (isset($parametros['lopd_cumplimentada'])) {
            $firma_lopd = " (select count(id) from clientes_firmas_lopd where id_cliente = C.id_cliente) as existe_firma, ";
        }

        if (isset($parametros['programadas_finalizadas'])) {
            if ($parametros['programadas_finalizadas'] == 1) {
                $busqueda .= " AND (C.estado = 'Programada' OR C.estado = 'Finalizado') AND C.duracion > 0 ";
            }
        }

        if (isset($parametros['orden_cliente'])) {
            $orden .= " id_cliente,fecha_hora_inicio ";
        } else {
            $orden .= " fecha_hora_inicio ";
        }

        //if (isset($parametros['empleados_medaigual'])) {
        //  $busqueda.="AND ( ";
        //  $datos=explode(";",$parametros['empleados_medaigual']);
        //  foreach ($datos as $id_empleado) {
        //    $busqueda.=" C.id_usuario_empleado = ".$id_empleado." OR";
        //  }
        //  $busqueda=substr($busqueda, 0, -3);
        //  $busqueda.=") ";
        //}
        // CHAINS 20240127 - Añadimos groupcodemd5

        // CHAINS 202400203: Añadimos id_tarifa y pvptarifa al resultado de laquery
        // ... Leemos los registros
        $sentencia_sql = "SELECT 
        C.id_cita,
        C.id_servicio,
        C.id_usuario_empleado,
        C.id_cliente,
        C.id_tarifa,
        C.duracion,
        C.estado, 
        {$firma_lopd}
        C.observaciones,
        DATE_FORMAT(C.fecha_hora_inicio,'%d-%m-%Y') as fecha_inicio,
        DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') as hora_inicio,
        DATE_FORMAT(C.fecha_hora_inicio + INTERVAL C.duracion MINUTE,'%H:%i') as hora_fin,
        DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') as fecha_inicio_aaaammdd,
        C.fecha_hora_inicio,
        C.id_usuario_creador,
        C.fecha_creacion,
        C.id_usuario_modificacion,
        C.fecha_modificacion,
        C.borrado,
        C.id_usuario_borrado,
        C.fecha_borrado,
        CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
        clientes.telefono,
        servicios.abreviatura as servicio,
        CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
        usuarios.color,
        servicios.pvp,
        tarifas_precioservicio.pvp AS pvptarifa,
        servicios.templos,
        C.solo_este_empleado,
        servicios.color as color_servicio,
        C.recordatorio_sms,
        C.recordatorio_email,
        clientes.no_quiere_publicidad,
        presupuestos_items.dientes,
        presupuestos_items.id_presupuesto,
        presupuestos.nro_presupuesto,
        (1 - 1) AS saldo,
        presupuestos_items.coste,
        C.en_sala,
        C.groupcodemd5
    FROM citas AS C
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
    LEFT JOIN tarifas_precioservicio ON (servicios.id_servicio = tarifas_precioservicio.id_servicio AND tarifas_precioservicio.id_tarifa=C.id_tarifa)
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
    LEFT JOIN presupuestos_items on presupuestos_items.id_cita = C.id_cita
    LEFT JOIN presupuestos on presupuestos_items.id_presupuesto = presupuestos_items.id_presupuesto
    WHERE C.borrado = 0 " . $busqueda . " GROUP BY C.id_cita ORDER BY " . $orden ;

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        // CHAINS 20240127: obtenerAgrupado Si viene el parametro se devuelve eventos agrupados
        if(isset($parametros['obtenerAgrupado']) && $parametros['obtenerAgrupado']){
            $datos=$this->reformatCitasAgrupadas($datos);
        }
        // CHAINS 20240127: obtenerAgrupado
        return $datos;
    }

    // CHAINS 20240127: Agrupa los eventos con el mismo Servicio, empleado, cliente, inicio y fin
    public static function getCitaGroupValue($cita,$useid=false){
        // ALTER TABLE `citas` ADD `groupcodemd5` VARCHAR(32) NULL DEFAULT NULL AFTER `debug`;
        if(isset($cita['groupcodemd5'])) return $cita['groupcodemd5'];

        /*$fecha_inicio=isset($cita['fecha_inicio']) ? $cita['fecha_inicio'] : null;
        $hora_inicio=isset($cita['hora_inicio']) ? $cita['hora_inicio'] : null;
        $hora_fin=isset($cita['hora_fin']) ? $cita['hora_fin'] : null;
        if(isset($cita['fecha_hora_inicio'])){
            $fecha_inicio=date("Y-m-d",strtotime($cita['fecha_hora_inicio']));
            $hora_inicio=date("H:i:s",strtotime($cita['fecha_hora_inicio']));
            $hora_fin=date("H:i:s",strtotime("+".$cita['duracion']." seconds",strtotime($cita['fecha_hora_inicio'])));

        }
        $groupdata=md5(($useid ? $cita['id_cita'] : '').$cita['id_servicio']."_".$cita['id_usuario_empleado']."_".$cita['id_cliente']."_".
            $fecha_inicio."_".$hora_inicio.$hora_fin);
        */
        $groupdata=md5(($useid ? $cita['id_cita'] : '').$cita['id_servicio']."_".$cita['id_usuario_empleado']."_".$cita['id_cliente']."_".
            $cita['fecha_hora_inicio']."_".$cita['duracion']);

        return $groupdata;
    }
    private function reformatCitasAgrupadas($datos){
        if(!is_array($datos)) return $datos;
        $rtval=[];
        foreach($datos as $key => $cita){
            $groupdata=self::getCitaGroupValue($cita);

            if(!isset($rtval[$groupdata])) {
                $rtval[$groupdata]=$cita;
            }
            else{
                $rtval[$groupdata]['otrosdientes']=
                    isset($rtval[$groupdata]['otrosdientes']) ?
                        array_merge($rtval[$groupdata]['otrosdientes'],array($cita['dientes']))
                        : array($cita['dientes']);
            }
        }
        return $rtval;
    }
    // CHAINS 20240127: Fin Agrupa los eventos con el mismo Servicio, empleado, cliente, inicio y fin

    //08/05/20 ********************************** Para Enviar datos de la Cita al Correo cuando sea: Nueva, Modifica o Arrasta la cita.
    function leer_citas_correo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $firma_lopd = "";
        $orden = "";

        if (isset($parametros['id_cita'])) {
            if ($parametros['id_cita'] > 0) {
                $busqueda .= " AND C.id_cita = @id_cita ";
            }
        }

        if (isset($parametros['id_cita_excluir'])) {
            if ($parametros['id_cita_excluir'] > 0) {
                $busqueda .= " AND C.id_cita <> @id_cita_excluir ";
            }
        }

        if (isset($parametros['id_servicio'])) {
            if ($parametros['id_servicio'] > 0) {
                $busqueda .= " AND C.id_servicio = @id_servicio ";
            }
        }

        if (isset($parametros['id_empleado'])) {
            if ($parametros['id_empleado'] > 0) {
                $busqueda .= " AND C.id_usuario_empleado = @id_empleado ";
            }
        }

        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                $busqueda .= " AND C.id_cliente = @id_cliente ";
            }
        }

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND C.id_usuario_empleado in (select id_usuario from usuarios where id_centro = @id_centro) ";
            }
        }

        if (isset($parametros['fecha'])) {
            if ($parametros['fecha'] != "") {
                $parametros['fecha'] = str_replace("-", "/", $parametros['fecha']);
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%d/%m/%Y') = @fecha ";
            }
        }

        if (isset($parametros['fecha_aaaammdd'])) {
            if ($parametros['fecha_aaaammdd'] != "") {
                $parametros['fecha_aaaammdd'] = str_replace("-", "/", $parametros['fecha_aaaammdd']);
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%Y/%m/%d') = @fecha_aaaammdd ";
            }
        }

        if (isset($parametros['hora'])) {
            if ($parametros['hora'] != "") {
                $busqueda .= " AND DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') = @hora ";
            }
        }

        if (isset($parametros['estado'])) {
            $busqueda .= " AND C.estado = @estado ";
        }

        if (isset($parametros['lopd_cumplimentada'])) {
            $firma_lopd = " (select count(id) from clientes_firmas_lopd where id_cliente = C.id_cliente) as existe_firma, ";
        }

        if (isset($parametros['programadas_finalizadas'])) {
            if ($parametros['programadas_finalizadas'] == 1) {
                $busqueda .= " AND (C.estado = 'Programada' OR C.estado = 'Finalizado') AND C.duracion > 0 ";
            }
        }

        if (isset($parametros['orden_cliente'])) {
            $orden .= " id_cliente,fecha_hora_inicio ";
        } else {
            $orden .= " fecha_hora_inicio ";
        }

        //if (isset($parametros['empleados_medaigual'])) {
        //  $busqueda.="AND ( ";
        //  $datos=explode(";",$parametros['empleados_medaigual']);
        //  foreach ($datos as $id_empleado) {
        //    $busqueda.=" C.id_usuario_empleado = ".$id_empleado." OR";
        //  }
        //  $busqueda=substr($busqueda, 0, -3);
        //  $busqueda.=") ";
        //}

        // ... Leemos los registros
        $sentencia_sql = "SELECT C.id_cita,C.id_servicio,C.id_usuario_empleado,C.id_cliente,
    C.duracion,C.estado, {$firma_lopd}
    C.observaciones,DATE_FORMAT(C.fecha_hora_inicio,'%d-%m-%Y') as fecha_inicio,
    DATE_FORMAT(C.fecha_hora_inicio,'%H:%i') as hora_inicio,
    DATE_FORMAT(C.fecha_hora_inicio + INTERVAL C.duracion MINUTE,'%H:%i') as hora_fin,
    DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') as fecha_inicio_aaaammdd,
    C.id_usuario_creador,C.fecha_creacion,C.id_usuario_modificacion,
    C.fecha_modificacion,C.borrado,C.id_usuario_borrado,C.fecha_borrado,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,clientes.telefono,clientes.nombre AS nombreprincipal,
    servicios.abreviatura as servicio,servicios.nombre_servicio,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,
    usuarios.color,servicios.pvp,servicios.templos,
    C.solo_este_empleado,servicios.color as color_servicio,
    centros.nombre_centro,centros.id_centro,
    C.recordatorio_sms,C.recordatorio_email,clientes.no_quiere_publicidad
    FROM citas AS C
    LEFT JOIN clientes on clientes.id_cliente = C.id_cliente
    LEFT JOIN servicios on servicios.id_servicio = C.id_servicio
    LEFT JOIN usuarios on usuarios.id_usuario = C.id_usuario_empleado
    LEFT JOIN (dietario left join centros on centros.id_centro = dietario.id_centro)
    on dietario.id_cita = C.id_cita
    WHERE C.borrado = 0 " . $busqueda . " ORDER BY " . $orden;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }
    //Fin 08/05/20 *************************** Fin de Citas Correos ***********************+++

    //25/05/20 Avisos Citas
    function grabar_aviso($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        //18/06/20 ahora fecha_creacion ser� fecha de la cita.
        if (isset($parametros['fecha_cita'])) {
            $parametros['fecha_creacion'] = $parametros['fecha_cita'];
        } else {
            $parametros['fecha_creacion'] = date("Y-m-d H:i:s");
        }
        //Fin 18/06/20


        //Buscar si la cita existe, si existe se modifica fecha_creacion y mensaje.
        $sentencia_sql = "SELECT * FROM citas_avisos
               WHERE  id_cita=@id_cita ORDER BY id_aviso";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);
        if ($datos != 0) { //Si existe
            $sentencia_sql = " UPDATE citas_avisos SET mensaje=@mensaje,enviado=0,fecha_creacion=@fecha_creacion,cliente=@cliente,id_cliente=@id_cliente,telefono=@telefono where id_cita = @id_cita ";
            $graba = $AqConexion_model->no_select($sentencia_sql, $parametros);
            return 1;
        } else {  //No existe la cita, se crea una nueva.
            unset($registro);
            $registro['id_cita'] = $parametros['id_cita'];
            $registro['id_centro'] = $parametros['id_centro'];
            $registro['centro'] = $parametros['centro'];
            $registro['id_cliente'] = $parametros['id_cliente'];
            $registro['cliente'] = $parametros['cliente'];
            $registro['telefono'] = $parametros['telefono'];
            $registro['asunto'] = $parametros['asunto'];
            $registro['mensaje'] = $parametros['mensaje'];
            //$registro['fecha_creacion']=date("Y-m-d H:i:s");
            $registro['fecha_creacion'] = $parametros['fecha_creacion'];
            $registro['enviado'] = 0;
            $registro['borrado'] = 0;
            $AqConexion_model->insert('citas_avisos', $registro);
            return 1;
        }
    }

    function leer_citas_avisos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        $firma_lopd = "";
        $orden = "";

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND id_centro = @id_centro";
            }
        }

        if (isset($parametros['enviado'])) {

            $busqueda .= " AND enviado = @enviado";
        }

        $sentencia_sql = "SELECT * FROM citas_avisos
               WHERE borrado=0 " . $busqueda . " ORDER BY cliente,fecha_creacion";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function actualizar_citas_avisos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = " UPDATE citas_avisos SET enviado = @enviado where id_aviso = @id_aviso ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    function ocultar_citas_avisos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = " UPDATE citas_avisos SET borrado = 1 WHERE  id_cita=@id_cita ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    function marcar_obsoletos_mes()
    {
        $AqConexion_model = new AqConexion_model();
        $parametros = [];
        $sentencia_sql = " UPDATE citas_avisos SET enviado = 2 WHERE enviado = 0 AND borrado = 0 AND fecha_creacion < DATE_SUB(NOW(), INTERVAL 1 MONTH);";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }


    //Fin 20/05/20 Avisos Citas



    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function guardar_cita($parametros)
    {

        $AqConexion_model = new AqConexion_model();

        // ... Leemos los datos del servicio a incluir en la cita.
        unset($param);
        $param['id_servicio'] = $parametros['id_servicio'];
        $servicios = $this->Servicios_model->leer_servicios($param);

        // ... Comprobamos que no exista una cita igual ya.
        unset($param);
        $param['id_servicio'] = $parametros['id_servicio'];
        $param['id_empleado'] = $parametros['id_empleado'];
        $param['id_cliente'] = $parametros['id_cliente'];
        $param['duracion'] = $servicios[0]['duracion'];
        $param['fecha_aaaammdd'] = $parametros['fecha'];
        $param['hora'] = $parametros['hora'];
        $param['estado'] = "Programada";

        // CHAINS 20240127 - Añadimos los ids a excluir de la busqueda si existen
        if(isset($parametros['id_cita_excluir'])){
            $param['id_cita_excluir']=$parametros['id_cita_excluir'];
        }
        // FIN CHAINS 20240127 - Añadimos los ids a excluir de la busqueda si existen

        $cita = $this->leer_citas($param);

        // ... Sino existe una cita igual, la guardamos.
        if ($cita == 0) {
            $registro['id_servicio'] = $parametros['id_servicio'];
            $registro['id_usuario_empleado'] = $parametros['id_empleado'];
            $registro['id_cliente'] = $parametros['id_cliente'];
            $registro['fecha_hora_inicio'] = $parametros['fecha'] . " " . $parametros['hora'] . ":00";
            $registro['duracion'] = $servicios[0]['duracion'];
            $registro['estado'] = "Programada";
            if (isset($parametros['observaciones'])) {
                $registro['observaciones'] = $parametros['observaciones'];
            } else {
                $registro['observaciones'] = "";
            }
            if (isset($parametros['solo_este_empleado'])) {
                $registro['solo_este_empleado'] = $parametros['solo_este_empleado'];
            } else {
                $registro['solo_este_empleado'] = 0;
            }

            if (isset($parametros['recordatorio_sms'])) {
                if ($parametros['recordatorio_sms'] == "") {
                    $registro['recordatorio_sms'] = 0;
                } else {
                    $registro['recordatorio_sms'] = 1;
                }
            } else {
                $registro['recordatorio_sms'] = 0;
            }
            if (isset($parametros['recordatorio_email'])) {
                if ($parametros['recordatorio_email'] == "") {
                    $registro['recordatorio_email'] = 0;
                } else {
                    $registro['recordatorio_email'] = 1;
                }
            } else {
                $registro['recordatorio_email'] = 0;
            }

            // CHAINS 20240203 - Tarifa
            if(key_exists('id_tarifa',$parametros)){
                $registro['id_tarifa']=$parametros['id_tarifa'];
            }
            else $registro['id_tarifa']=0;
            // CHAINS Fin Tarifa


            //
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;

            // CHAINS: 20240127 - Añadimos el md5 para agrupar citas
            $registro['groupcodemd5']=self::getCitaGroupValue($registro);
            // FIN CHAINS 20240127 - Fin md5 para agrupar citas
            $AqConexion_model->insert('citas', $registro);

            $sentenciaSQL = "select max(id_cita) as id_cita from citas";
            $resultado = $AqConexion_model->select($sentenciaSQL, null);
			
			$id_cita_creada = $resultado[0]['id_cita'];

            if ($id_cita_creada > 0) {
                $this->load->model('Notificaciones_model');
                $this->Notificaciones_model->insertar_notificaciones($id_cita_creada);
            }

            return $resultado[0]['id_cita'];
        } else {
            return 0;
        }
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // CHAINS 20240222 - Modificar cita simple
    function modificar_cita_simple($parametros){
        if(!isset($parametros['id_cita'])) return 0;

        $AqConexion_model = new AqConexion_model();
        unset($param);

        if (isset($parametros['observaciones'])) {
            $registro['observaciones'] = $parametros['observaciones'];
        }

        if (isset($parametros['solo_este_empleado'])) {
            $registro['solo_este_empleado'] = $parametros['solo_este_empleado'];
        } else {
            $registro['solo_este_empleado'] = 0;
        }

        if (isset($parametros['recordatorio_sms'])) {
            if ($parametros['recordatorio_sms'] == "") {
                $registro['recordatorio_sms'] = 0;
            } else {
                $registro['recordatorio_sms'] = 1;
            }
        } else {
            $registro['recordatorio_sms'] = 0;
        }
        if (isset($parametros['recordatorio_email'])) {
            if ($parametros['recordatorio_email'] == "") {
                $registro['recordatorio_email'] = 0;
            } else {
                $registro['recordatorio_email'] = 1;
            }
        } else {
            $registro['recordatorio_email'] = 0;
        }

        if(key_exists('id_tarifa',$parametros)){
            $registro['id_tarifa']=$parametros['id_tarifa'];
        }
        else $registro['id_tarifa']=0;
        // CHAINS Fin Tarifa

        // CHAINS 20240222 - Archivado parrilla
        if(key_exists('archivado_parrilla',$parametros)){
            $registro['archivado_parrilla']=$parametros['archivado_parrilla'];
        }
        // CHAINS FIN Archivado parrilla
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');


        // CHAINS 20240128 - Cambiar groupcodemd5
        if(isset($parametros['groupcodemd5'])){
            $registro['groupcodemd5']=$parametros['groupcodemd5'];
        }
        // FIN CHAINS groupcodemd5
        $where['id_cita'] = $parametros['id_cita'];
        $AqConexion_model->update('citas', $registro, $where);

        return 1;

    }



    function modificar_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        unset($param);
        $param['id_servicio'] = $parametros['id_servicio'];
        $servicios = $this->Servicios_model->leer_servicios($param);

        $registro['id_servicio'] = $parametros['id_servicio'];
        $registro['id_usuario_empleado'] = $parametros['id_empleado'];
        $registro['id_cliente'] = $parametros['id_cliente'];

        $parametros['fecha'] = str_replace("/", "-", $parametros['fecha']);
        $datos = explode('-', $parametros['fecha']);
        $parametros['fecha'] = $datos[0] . "-" . $datos[1] . "-" . $datos[2];

        $registro['fecha_hora_inicio'] = $parametros['fecha'] . " " . $parametros['hora'] . ":00";

        // ... Si se especifica una duracion personalizada,
        // no se guarda la del propio servicio.
        if (isset($parametros['duracion_nueva'])) {
            if ($parametros['duracion_nueva'] > 0) {
                $registro['duracion'] = $parametros['duracion_nueva'];
            }
        } else {
            $registro['duracion'] = $servicios[0]['duracion'];
        }
        if (isset($parametros['observaciones'])) {
            $registro['observaciones'] = $parametros['observaciones'];
        }

        if (isset($parametros['solo_este_empleado'])) {
            $registro['solo_este_empleado'] = $parametros['solo_este_empleado'];
        } else {
            $registro['solo_este_empleado'] = 0;
        }

        if (isset($parametros['recordatorio_sms'])) {
            if ($parametros['recordatorio_sms'] == "") {
                $registro['recordatorio_sms'] = 0;
            } else {
                $registro['recordatorio_sms'] = 1;
            }
        } else {
            $registro['recordatorio_sms'] = 0;
        }
        if (isset($parametros['recordatorio_email'])) {
            if ($parametros['recordatorio_email'] == "") {
                $registro['recordatorio_email'] = 0;
            } else {
                $registro['recordatorio_email'] = 1;
            }
        } else {
            $registro['recordatorio_email'] = 0;
        }

        if(key_exists('id_tarifa',$parametros)){
            $registro['id_tarifa']=$parametros['id_tarifa'];
        }
        else $registro['id_tarifa']=0;
        // CHAINS Fin Tarifa

        // CHAINS 20240222 - Archivado parrilla
        if(key_exists('archivado_parrilla',$parametros)){
            $registro['archivado_parrilla']=$parametros['archivado_parrilla'];
        }
        // CHAINS FIN Archivado parrilla
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');


        // CHAINS 20240128 - Cambiar groupcodemd5
        if(isset($parametros['groupcodemd5'])){
            $registro['groupcodemd5']=$parametros['groupcodemd5'];
        }
        // FIN CHAINS groupcodemd5
        $where['id_cita'] = $parametros['id_cita'];
        $AqConexion_model->update('citas', $registro, $where);

        return 1;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function cambio_estado_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");

        // ... Leemos los registros
        $sentencia_sql = " UPDATE citas SET estado = @estado,
    observaciones = @observaciones, id_usuario_modificacion = @id_usuario_modificacion,
    fecha_modificacion = @fecha_modificacion
    where id_cita = @id_cita ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function horarios()
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['vacio'] = "";

        // ... Leemos los registros
        $sentencia_sql = " SELECT TIME_FORMAT(horario,'%H:%i') as horario
    FROM horarios ORDER by horario ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function horas_ocupadas_empleado($parametros)
    {
        // ... Leemos las citas que nos interesen, filtrando por servicio,
        // empleado, cliente o fecha concreta.
        //$parametros['estado']="Programada";
        $parametros['programadas_finalizadas'] = 1;
        $citas = $this->leer_citas($parametros);

        $horas_ocupadas = array();

        // ... Lo siguiente que hago es sacar el listado de horas ocupadas
        // del filtro elegido, devolviendo una array con todas las horas.
        $j = 0;
        for ($i = 0; $i < count($citas); $i++) {
            $horas_ocupadas[$j]['id_cita'] = $citas[$i]['id_cita'];
            $horas_ocupadas[$j]['fecha_inicio'] = $citas[$i]['fecha_inicio'];
            $horas_ocupadas[$j]['hora_inicio'] = $citas[$i]['hora_inicio'];
            $horas_ocupadas[$j]['id_servicio'] = $citas[$i]['id_servicio'];
            $horas_ocupadas[$j]['id_usuario_empleado'] = $citas[$i]['id_usuario_empleado'];
            $horas_ocupadas[$j]['id_cliente'] = $citas[$i]['id_cliente'];
            $horas_ocupadas[$j]['duracion'] = $citas[$i]['duracion'];
            $horas_ocupadas[$j]['estado'] = $citas[$i]['estado'];
            $horas_ocupadas[$j]['observaciones'] = $citas[$i]['observaciones'];
            $horas_ocupadas[$j]['fecha_inicio_aaaammdd'] = $citas[$i]['fecha_inicio_aaaammdd'];
            $horas_ocupadas[$j]['cliente'] = $citas[$i]['cliente'];
            $horas_ocupadas[$j]['servicio'] = $citas[$i]['servicio'];
            $horas_ocupadas[$j]['empleado'] = $citas[$i]['empleado'];
            $horas_ocupadas[$j]['color'] = $citas[$i]['color'];

            // ... Cogemos la hora inicial y le vamos sumando 15 minutos para sacar
            // las horas intermedias que estan ocupadas.
            for ($x = 1; $x < ($citas[$i]['duracion'] / 15); $x++) {
                $j++;

                $nuevaHora = date("H:i", strtotime($citas[$i]['hora_inicio']) + (900 * ($x)));

                $horas_ocupadas[$j]['id_cita'] = $citas[$i]['id_cita'];
                $horas_ocupadas[$j]['fecha_inicio'] = $citas[$i]['fecha_inicio'];
                $horas_ocupadas[$j]['hora_inicio'] = $nuevaHora;
                $horas_ocupadas[$j]['id_servicio'] = $citas[$i]['id_servicio'];
                $horas_ocupadas[$j]['id_usuario_empleado'] = $citas[$i]['id_usuario_empleado'];
                $horas_ocupadas[$j]['id_cliente'] = $citas[$i]['id_cliente'];
                $horas_ocupadas[$j]['duracion'] = $citas[$i]['duracion'];
                $horas_ocupadas[$j]['estado'] = $citas[$i]['estado'];
                $horas_ocupadas[$j]['observaciones'] = $citas[$i]['observaciones'];
                $horas_ocupadas[$j]['fecha_inicio_aaaammdd'] = $citas[$i]['fecha_inicio_aaaammdd'];
                $horas_ocupadas[$j]['cliente'] = $citas[$i]['cliente'];
                $horas_ocupadas[$j]['servicio'] = $citas[$i]['servicio'];
                $horas_ocupadas[$j]['empleado'] = $citas[$i]['empleado'];
                $horas_ocupadas[$j]['color'] = $citas[$i]['color'];
            }

            $j++;
        }

        return $horas_ocupadas;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function horas_ocupadas($parametros)
    {
        // ... Leemos las citas que nos interesen, filtrando por servicio,
        // empleado, cliente o fecha concreta.
        //$parametros['estado']="Programada";
        $parametros['programadas_finalizadas'] = 1;
        $citas = $this->leer_citas($parametros);

        $horas_ocupadas = array();

        // ... Lo siguiente que hago es sacar el listado de horas ocupadas
        // del filtro elegido, devolviendo una array con todas las horas.
        if (is_array($citas)) {
            for ($i = 0; $i < count($citas); $i++) {
                array_push($horas_ocupadas, $citas[$i]['hora_inicio']);

                // ... Cogemos la hora inicial y le vamos sumando 15 minutos para sacar
                // las horas intermedias que estan ocupadas.
                for ($x = 1; $x < ($citas[$i]['duracion'] / 15); $x++) {
                    $nuevaHora = date("H:i", strtotime($citas[$i]['hora_inicio']) + (900 * $x));
                    array_push($horas_ocupadas, $nuevaHora);
                }
            }
        }
        return $horas_ocupadas;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function horas_libres_sin_control($parametros)
    {
        $horarios = $this->horarios();
        $horas_libres = array();

        foreach ($horarios as $dato) {
            array_push($horas_libres, $dato['horario']);
        }

        return $horas_libres;
    }

    //
    //
    //
    function horas_libres($parametros)
    {
        $horas_libres = array();

        $horarios = $this->horarios();
        $horas_ocupadas = $this->horas_ocupadas($parametros);

        foreach ($horarios as $dato) {
            $sw = 0;

            foreach ($horas_ocupadas as $ocupada) {
                if ($dato['horario'] == $ocupada) {
                    $sw = 1;
                }
            }

            if ($sw == 0) {
                array_push($horas_libres, $dato['horario']);
            }
        }

        // ... Si hay que discriminar por la duraci�n hay que quitar
        // los rangos de horas que no encajen con la duraci�n.
        if (isset($parametros['duracion'])) {
            $horas_libres_copia = $horas_libres;

            unset($horas_libres);
            $horas_libres = array();

            // Leemos la hora limite de fin en la que trabaja el empleado.
            unset($param5);
            if(isset($parametros['id_empleado']) && $parametros['id_empleado'] !== ''){
                $param5['id_usuario'] = $parametros['id_empleado'];
                //$date = new DateTime($parametros['fecha']);
                $param5['fecha'] = date('Y-m-d', strtotime($parametros['fecha'])); //$param5['fecha']=date_format($date, 'Y-m-d');
                $limite_fin_empleado = $this->Horarios_model->hora_limite_fin_empleado($param5);

                if($limite_fin_empleado !== null){
                    for ($i = 0; $i < count($horas_libres_copia); $i++) {
                        if (($i + 1) < count($horas_libres_copia)) {
                            //... Esto lo pongo porque los servicios con duraciones inferiores
                            // a menos de 15 min salian a 0 y no mostraba horas disponibles.
                            if ($parametros['duracion'] > 15) {
                                $hora_a_hhmm = date("H:i", strtotime($horas_libres_copia[$i]) + (($parametros['duracion'] - 15) * 60));
                                $hora_a = strtotime($horas_libres_copia[$i]) + ($parametros['duracion'] * 60);
                                $hora_b = strtotime($horas_libres_copia[$i + 1]);
                            } else {
                                $hora_a_hhmm = date("H:i", strtotime($horas_libres_copia[$i]));
                                $hora_a = strtotime($horas_libres_copia[$i]);
                                $hora_b = strtotime($horas_libres_copia[$i]);
                            }
                            $limite_fin = strtotime($limite_fin_empleado);

                            if ($hora_a >= $hora_b && $hora_a <= $limite_fin) {
                                $sw = 0;

                                // ... Comprobamos si la hora no est� ocupada,
                                // pasa por los rangos de horas ocupadas.
                                foreach ($horas_ocupadas as $ocupada) {
                                    $A = strtotime($hora_a_hhmm);
                                    $B = strtotime($ocupada);
                                    $C = strtotime($ocupada) + (($parametros['duracion'] - 15) * 60);

                                    if ($A >= $B && $A <= $C) {
                                        $sw = 1;
                                    }
                                }

                                if ($sw == 0) {
                                    array_push($horas_libres, $horas_libres_copia[$i]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // ... Leemos las horas que el empleado puede trabajar ese dia.
        // las que no esten se quitan de las horas disponibles.
        unset($param);
        if (isset($parametros['empleados_medaigual'])) {
            $param['empleados_medaigual'] = $parametros['empleados_medaigual'];
        } else {
            $param['id_usuario'] = $parametros['id_empleado'];
        }
        //$date = new DateTime($parametros['fecha']);
        $param['fecha'] = date('Y-m-d', strtotime($parametros['fecha'])); //$param5['fecha']=date_format($date, 'Y-m-d');
        $horas_empleado = $this->Horarios_model->horas_trabaja_empleado($param);

        $horas_libres_final = array();

        foreach ($horas_libres as $hlibre) {
            foreach ($horas_empleado as $hempleado) {
                if ($hlibre == $hempleado) {
                    array_push($horas_libres_final, $hlibre);
                }
            }
        }

        /* Aqui lo que hacemos es quitar todas las horas que no sean en punto,
     * salvo que entre una hora y otra haya citas, entonce permito mostrar
     * los cuartos y medias horas, siempre y cuando hagan que la cita
     * est� pegada a otra, tanto por arriba o bien por abajo (o bien al inicio o
     * final de jornada). Adem�s, si se muestra un cuarto de hora, autom�ticamente
     * se deja de mostrar la hora en punto siguiente (se saltar�a a la pr�xima).
    */
        $fecha_elegida = date("Y-m-d", strtotime($parametros['fecha']));
        $horas_libres_devolver = array();

        // Lo uso para controlar cuando hay una hora en punto que no se tiene que mostrar.
        $sw2 = 0;

        foreach ($horas_libres_final as $hh) {
            // ... Esto lo uso para saber que fraci�n de la hora es la que estoy gestionando.
            $pos00 = strpos($hh, ":00");
            $pos15 = strpos($hh, ":15");
            $pos30 = strpos($hh, ":30");
            $pos45 = strpos($hh, ":45");

            // ... Aqui se determina si alguna fraccion, media hora o cuarto se tiene que mostrar.
            $sw = 0;
            $hh_menos_15min = strtotime($hh) - (900);
            $hh_mas_duracion_servicios = strtotime($hh) + ($parametros['duracion'] * 60);

            foreach ($horas_ocupadas as $ocupada) {
                $hh_ocupada = strtotime($ocupada);

                // ... Uso dos valores para sw, para saber si es
                // una coincidencia de horas por encima de cita o por debajo de cita.
                // si es por debajo puede que haya que excluir la siguiente hora en punto.
                if ($hh_ocupada == $hh_menos_15min) {
                    $sw = 2;
                }

                if ($hh_ocupada == $hh_mas_duracion_servicios) {
                    $sw = 1;
                }
            }

            // ... Si la hora de fin del empleado es igual a la
            // hora que estamos comprobando m�s la duraci�n de servicios
            // entonces se muestra, para mostrar las horas que se pegan
            // al final de la jornada del empleado.
            $hh_fin_empleado = strtotime($limite_fin_empleado);
            if ($hh_fin_empleado == $hh_mas_duracion_servicios) {
                $sw = 1;
            }

            if ((($pos15 === false && $pos30 === false && $pos45 === false) || ($sw > 0)) && $sw2 == 0) {
                // Si la fecha / hora es menor a la actual, no la pone
                // ya que no se permiten usar fechas / horas pasadas.
                if (strtotime($fecha_elegida . " " . $hh) > strtotime(date('Y-m-d') . " " . date('H:i'))) {
                    array_push($horas_libres_devolver, $hh);

                    if (($pos15 == true or $pos45 == true) && $sw == 2) {
                        $sw2 = 1;
                    }
                }
            }

            // ... Si la hora es en punto, vuelve a poner el sw2 a 0, para que
            // ya vuelvan a mostrarse la horas en punto.
            if ($pos00 == true) {
                $sw2 = 0;
            }
        }

        return $horas_libres_devolver;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function cambio_duracion_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = " UPDATE citas SET duracion = @duracion where id_cita = @id_cita ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function cambio_empleado_fecha_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = " UPDATE citas SET id_usuario_empleado = @id_usuario_empleado,
    fecha_hora_inicio = @fecha_hora_inicio where id_cita = @id_cita ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    // -------------------------------------------------------------------
    function numero_citas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = " SELECT COUNT(id_cita) as numero_citas from citas AS C
    where (C.estado = 'Programada' OR C.estado = 'Finalizado')
    AND C.duracion > 0 AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = @fecha
    AND C.id_usuario_empleado in
    (select id_usuario from usuarios where id_centro = @id_centro) ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['numero_citas'];
    }

    function citas_anuladas($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = " SELECT COUNT(id_cita) as citas_anuladas from citas AS C
    where C.estado = 'Anulada' AND C.duracion > 0
    AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = @fecha
    AND C.id_usuario_empleado in
    (select id_usuario from usuarios where id_centro = @id_centro) ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas_anuladas'];
    }

    function citas_novino($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Leemos los registros
        $sentencia_sql = " SELECT COUNT(id_cita) as citas_novino from citas AS C
    where C.estado = 'No vino' AND C.duracion > 0
    AND DATE_FORMAT(C.fecha_hora_inicio,'%Y-%m-%d') = @fecha
    AND C.id_usuario_empleado in
    (select id_usuario from usuarios where id_centro = @id_centro) ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['citas_novino'];
    }

    /*
  * Devuelve todas las citas asociadas a un pedido.
  *
  * $id_pedido integer
  * $personas integer opcional
  *
  * Return array
  *
  */
    function leer_citas_temporales($id_pedido, $personas)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";

        if ($personas > 0) {
            $buscar = " and CT.personas = @personas ";
            $parametros['personas'] = $personas;
        }

        $sentencia_sql = "
    SELECT
      CT.id_cita,CT.id_pedido,CT.id_servicio,CT.pvp,CT.templos,
      CT.id_centro,CT.id_usuario_empleado,CT.id_cliente,
      CT.fecha_hora_inicio,CT.duracion,CT.observaciones,
      UPPER(servicios_familias.nombre_familia) as nombre_familia,
      UPPER(servicios.nombre_servicio) as nombre_servicio,
      UPPER(CONCAT(clientes.nombre,' ',clientes.apellidos)) as cliente,
      UPPER(CONCAT(usuarios.nombre,' ',usuarios.apellidos)) as empleado,
      centros.nombre_centro,
      DATE_FORMAT(CT.fecha_hora_inicio,'%d-%m-%Y') as fecha,
      DATE_FORMAT(CT.fecha_hora_inicio,'%H:%i') as hora
    FROM citas_temporales AS CT
      LEFT JOIN (servicios LEFT JOIN servicios_familias On servicios_familias.id_familia_servicio = servicios.id_familia_servicio)
        ON servicios.id_servicio = CT.id_servicio
      LEFT JOIN clientes ON clientes.id_cliente = CT.id_cliente
      LEFT JOIN usuarios ON usuarios.id_usuario = CT.id_usuario_empleado
      LEFT JOIN centros ON centros.id_centro = CT.id_centro
    WHERE
      CT.borrado = 0 and
      CT.id_pedido = @id_pedido
      $buscar
    ";

        $parametros['id_pedido'] = $id_pedido;

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    /*
  * Leer datos de un pedido por codigo
  *
  * $codigo integer
  *
  * Return array
  *
  */
    function leer_pedido_codigo($codigo)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "
    SELECT
      CP.id_pedido,CP.codigo,CP.id_cliente,CP.total,CP.templos,
      CP.estado,CP.fecha_creacion,CP.borrado,
      UPPER(CONCAT(clientes.nombre,' ',clientes.apellidos)) as cliente
    FROM citas_pedidos as CP
      LEFT JOIN clientes on clientes.id_cliente = CP.id_cliente
    WHERE
      CP.borrado = 0 and
      CP.codigo = @codigo
    ";

        $parametros['codigo'] = $codigo;

        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    /*
  * Lee los empleados disponibles en base a los servicios y centro indicado.
  *
  * Return hash_array con los datos
  *
  */
    function empleados_disponibles($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $buscar = "";
        $servicios = "";
        $contador = 0;

        if (isset($parametros['servicios'])) {
            if ($parametros['servicios'] != "") {
                $servicios = $parametros['servicios'];

                $buscar .= " AND usuarios.id_usuario IN
        (select id_usuario from usuarios_capacidades where borrado = 0 AND (";
                foreach ($servicios as $s) {
                    $buscar .= " id_servicio = $s or ";
                }
                $buscar = substr($buscar, 0, -3);
                $buscar .= "))";

                $contador = count($servicios);
            }
        }

        if (isset($parametros['id_centro'])) {
            $buscar .= " AND usuarios.id_centro = " . $parametros['id_centro'] . " ";
        }

        if (isset($parametros['fecha'])) {
            /*$buscar.=" AND usuarios.id_usuario IN
      (select distinct id_usuario
        from usuarios_horarios_desglose
        where DATE_FORMAT(fecha_inicio,'%d-%m-%Y') = '".$parametros['fecha']."') ";*/

            $buscar .= " AND usuarios.id_usuario IN
      (select distinct UHD.id_usuario
        from usuarios_horarios_desglose AS UHD
        LEFT JOIN usuarios_horarios AS UH ON UH.id_usuario = UHD.id_usuario
        where DATE_FORMAT(UHD.fecha_inicio,'%d-%m-%Y') = '" . $parametros['fecha'] . "'
        AND UH.jornada != 'Vacaciones'
        AND UH.jornada != 'Baja'
        AND DATE_FORMAT(UH.fecha_inicio,'%d-%m-%Y') = '" . $parametros['fecha'] . "') ";
        }

        if (isset($parametros['id_empleado_medaigual'])) {
            if ($parametros['id_empleado_medaigual'] > 0) {
                $buscar .= " AND usuarios.id_usuario <> " . $parametros['id_empleado_medaigual'];
            }
        }

        $sentencia_sql = "
    SELECT
      usuarios.id_usuario as id_empleado,
      UPPER(CONCAT(nombre , ' ' , apellidos)) as empleado
    FROM usuarios
    LEFT JOIN usuarios_perfiles ON usuarios_perfiles.id_usuario = usuarios.id_usuario
    WHERE
      usuarios.borrado = 0 AND
      (usuarios_perfiles.id_perfil = 1 or usuarios_perfiles.id_perfil = 3)
      $buscar
    ORDER BY nombre,apellidos
    ";
        $datos = $AqConexion_model->select($sentencia_sql, '');


        // ... Quitamos los empleados que no puedan dar todos los servicios.
        // creando un nuevo array con las coincidencias.
        $resultado = [];
        if ($datos != 0) {
            $x = 0;
            for ($i = 0; $i < count($datos); $i++) {
                if ($servicios != "") {
                    $num_servicios = $this->empleados_servicios_disponibles($datos[$i]['id_empleado'], $servicios);
                    if ($contador == $num_servicios) {
                        $resultado[$x] = $datos[$i];
                        $x++;
                    }
                }
            }
        }

        return $resultado;
    }

    function empleados_servicios_disponibles($id_usuario, $servicios)
    {
        $AqConexion_model = new AqConexion_model();

        $sw = 0;
        $buscar = "(";
        $parametros['id_usuario'] = $id_usuario;

        foreach ($servicios as $s) {
            $buscar .= " id_servicio = $s or ";
            $sw = 1;
        }
        $buscar = substr($buscar, 0, -3);

        $buscar .= ")";

        if ($sw == 0) {
            $buscar = "";
        }

        $sentencia_sql = "
    SELECT
      count(id_servicio) as num_servicios
    FROM usuarios_capacidades
    WHERE
      id_usuario = @id_usuario and
      borrado = 0 and
      $buscar
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos[0]['num_servicios'];
    }

    function marcar_en_sala($id_cita)
    {
        $AqConexion_model = new AqConexion_model();
        unset($param);
        $registro['en_sala'] = date("Y-m-d H:i:s");
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_cita'] = $id_cita;
        $AqConexion_model->update('citas', $registro, $where);

        return 1;
    }


    // CHAINS 20240208 - Obtener citas de varios clientes
    function leer_proximas_citas_varios_clientes($parametros){
        $AqConexion_model = new AqConexion_model();

        $where=' fecha_hora_inicio > NOW() ';
        if(isset($parametros['from'])){
            $where=" fecha_hora_inicio  > '".$parametros['from']."' ";
        }
        if(isset($parametros['id_cliente'])){
            if(!is_array($parametros['id_cliente'])){
                $parametros['id_cliente']=[$parametros['id_cliente']];
            }
			if(count($parametros['id_cliente'])==1) $parametros['id_cliente'][]=0;
            $where.=" AND id_cliente IN (".implode(",",$parametros['id_cliente']).") ";
            unset($parametros['id_cliente']);
        }



        $sql="SELECT id_cliente,id_cita,fecha_hora_inicio ".
                " FROM citas ".
                " WHERE borrado = 0 AND ".$where." GROUP BY id_cliente HAVING fecha_hora_inicio=MIN(fecha_hora_inicio)";

        $datos = $AqConexion_model->select($sql, $parametros);
        return $datos;
    }
    // FIN CHAINS - leer citas varios clientes

    function get_citas_sin_nota_por_fecha($params){
        $AqConexion_model = new AqConexion_model();

        $parametros=[];
        if(isset($params['id_centro'])){
            $parametros['id_centro']=$params['id_centro'];
        }
        if(isset($params['id_doctor'])){
            $parametros['id_doctor']=$params['id_doctor'];
        }

        $sql="SELECT  DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m-%d') AS fecha_cita ,citas.id_cliente,CONCAT(clientes.nombre,' ',clientes.apellidos) AS nombre_cliente,
            citas.id_usuario_empleado AS id_doctor,
            CONCAT(usuarios.nombre,' ', usuarios.apellidos) AS nombre_doctor,usuarios.id_centro,centros.nombre_centro, xxx.fecha_nota, xxx.id_cliente as id_cliente_nota, xxx.id_usuario_doctor
  FROM citas";
        $sql.="
            INNER JOIN usuarios ON usuarios.id_usuario=citas.id_usuario_empleado
            INNER JOIN usuarios_perfiles ON usuarios.id_usuario = usuarios_perfiles.id_usuario
            INNER JOIN centros ON usuarios.id_centro=centros.id_centro
            INNER JOIN clientes ON citas.id_cliente=clientes.id_cliente";
        $sql.=" LEFT JOIN (
      SELECT DATE_FORMAT(clientes_evolutivo.fecha_nota,'%Y-%m-%d') AS fecha_nota,clientes_evolutivo.id_cliente,clientes_evolutivo.id_usuario_doctor
      FROM clientes_evolutivo
      WHERE clientes_evolutivo.fecha_nota<=NOW() AND 
                DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 1 MONTH),'%Y-%m-%d') <= DATE_FORMAT(clientes_evolutivo.fecha_nota,'%Y-%m-%d')
  ) AS xxx ON (citas.id_cliente = xxx.id_cliente AND DATE_FORMAT(citas.fecha_hora_inicio,'%Y-%m-%d') = fecha_nota) ";
        $sql.=" WHERE fecha_nota IS NULL AND citas.borrado = 0 AND citas.id_cliente NOT IN (10020,9436,3755) AND 
        usuarios_perfiles.id_perfil=6 AND citas.estado='Finalizado' AND 
        citas.fecha_hora_inicio < NOW() AND citas.fecha_hora_inicio>DATE_SUB(NOW(),INTERVAL 1 MONTH)";


  if(isset($params['id_centro'])){
    $sql.=" AND usuarios.id_centro = ".$params['id_centro']." ";
  }
  if(isset($params['id_doctor'])){
    $sql.=" AND citas.id_usuario_empleado = ".$params['id_doctor']." ";
  }

  $sql.=" GROUP BY citas.id_cliente,fecha_cita
  ORDER BY citas.id_cliente DESC";

        $datos = $AqConexion_model->select($sql, $parametros);
     //   echo "<pre>"; var_dump($sql); var_dump($datos);echo "</pre><hr></hr>";
        return $datos;
    }

}
