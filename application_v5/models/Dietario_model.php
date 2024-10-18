<?php
class Dietario_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('EvaluacionesGoogle_model');

    }

    // -------------------------------------------------------------------
    // ... DIETARIO
    // -------------------------------------------------------------------
    function leer($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if ((isset($parametros['debug'])) && ($parametros['debug'] == 1)) {
            $busqueda = "
      WHERE (DC.borrado = 0 OR DC.debug = 1) ";
        } else {
            $busqueda = "
      WHERE DC.borrado = 0 ";
        }

        if (isset($parametros['id_dietario'])) {
            if ($parametros['id_dietario'] > 0) {
                $busqueda .= " AND DC.id_dietario = @id_dietario ";
            }
        }

        if (isset($parametros['fecha'])&&!isset($parametros['fecha2'])) {
            if ($parametros['fecha'] != "") {
                $busqueda .= " AND (
        DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') = @fecha or (DC.estado = 'No Pagado')
        or (DC.estado = 'Pendiente' and (DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') <= @fecha))
        ) ";
            }
        }
        if (isset($parametros['fecha'])&&isset($parametros['fecha2'])) {
            $parametros['ff'] = $parametros['fecha2'];
            if ($parametros['fecha'] != ""&&$parametros['ff'] != "") {
                
                $busqueda .= " AND ( DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') >= @fecha and DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') <= @ff )";
            }
        }

        //27/12/20
        //Para informes diario de tarjetas controlador informes.php funcion "diario_tarjeta"
        if (isset($parametros['web_clientes'])) {
            if ($parametros['web_clientes'] == "si") {
                $busqueda .= " AND ( DC.id_empleado = 1 OR DC.id_pedido>0 ) ";
            } else {
                $busqueda .= " AND DC.id_empleado != 1 AND DC.id_pedido=0 ";
            }
        }
        //Fin


        if (isset($parametros['fecha_inicio'])) {
            if ($parametros['fecha_inicio'] != "") {
                $busqueda .= " AND (DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d')) >= @fecha_inicio
        AND (DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d')) <= @fecha_fin ";
            }
        }

        if (isset($parametros['fecha_desde'])) {
            if ($parametros['fecha_desde'] != "") {
                $busqueda .= " AND DC.fecha_hora_concepto >= @fecha_desde
        AND DC.fecha_hora_concepto <= @fecha_hasta ";
            }
        }

        if (isset($parametros['id_empleado'])) {
            if ($parametros['id_empleado'] > 0) {
                $busqueda .= " AND DC.id_empleado = @id_empleado ";
            }
        }


        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                $busqueda .= " AND DC.id_cliente = @id_cliente ";
            }
        }

        if (isset($parametros['id_carnet'])) {
            if ($parametros['id_carnet'] > 0) {
                $busqueda .= " AND DC.id_carnet = @id_carnet ";
            }
        }

        if (isset($parametros['id_cita'])) {
            if ($parametros['id_cita'] > 0) {
                $busqueda .= " AND DC.id_cita = @id_cita ";
            }
        }

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND DC.id_centro = @id_centro ";
            }
        }

        if (isset($parametros['id_ticket'])) {
            if ($parametros['id_ticket'] > 0) {
                $busqueda .= " AND DC.id_ticket = @id_ticket ";
            }
        }

        if (isset($parametros['cajas_regalo'])) {
            $busqueda .= " AND DC.id_servicio IN (select id_servicio from servicios
      where id_familia_servicio = 12) ";
        }

        if (isset($parametros['estado'])) {
            if(is_array($parametros['estado'])){
                $pr=[];
                foreach ($parametros['estado'] as $cadena) {
                    $cadena = "'" . $cadena . "'";
                    $pr[]=$cadena;
                }
                $cadena_condicion = implode(",", $pr);
                $busqueda.=" AND DC.estado IN (".$cadena_condicion.") ";
            }
            else {
                $busqueda .= " AND DC.estado = @estado ";
            }
        }

        if (isset($parametros['recarga'])) {
            $busqueda .= " AND DC.recarga = @recarga ";
        }

        if (isset($parametros['citas_online'])) {
            if ($parametros['citas_online'] == 1) {
                $busqueda .= " AND DC.id_pedido > 0 ";
            }
        }

        if (isset($parametros['tipo_pago'])) {
            if ($parametros['tipo_pago'] != "") {
                $busqueda .= " AND DC.tipo_pago like #tipo_pago ";
            }
        }

        if (isset($parametros['no_pagado'])) {
            if ($parametros['no_pagado'] == 1) {
                $busqueda .= " AND (
                    DC.estado = 'No Pagado' OR 
                    DC.estado = 'Pendiente') AND DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') <= @fecha AND DC.id_pedido = 0 ";
            }
            if ($parametros['no_pagado'] == 2) {
                $busqueda .= " AND (
                    DC.estado = 'No Pagado' OR 
                    DC.estado = 'Pendiente' OR
                    (DC.estado = 'Presupuesto' AND citas.estado = 'Programada')
                ) AND DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') <= @fecha ";
            }
        }

        if (isset($parametros['pte_facturar'])) {
            $busqueda .= "
                AND (DC.estado = 'Pagado' OR DC.estado = 'Devuelto')
                AND (
                    DC.pagado_efectivo <> 0 
                    OR DC.pagado_tarjeta <> 0 
                    OR DC.pagado_transferencia <> 0 
                    OR DC.pagado_tpv2 <> 0 
                    OR DC.pagado_financiado <> 0 
                    OR DC.pagado_paypal <> 0 
                    OR (
                        DC.pagado_efectivo = 0 
                        AND DC.pagado_tarjeta = 0 
                        AND DC.pagado_transferencia = 0 
                        AND DC.pagado_tpv2 = 0 
                        AND DC.pagado_financiado = 0 
                        AND DC.pagado_paypal = 0 
                        AND EXISTS (
                            SELECT 1 
                            FROM clientes_saldos CS 
                            WHERE CS.id_cliente = @id_cliente
                        )
                    )
                )
                /*AND DC.id_presupuesto = 0*/
                AND DC.id_dietario NOT IN (SELECT id_dietario from recibos_conceptos where borrado = 0) ";
        }

        if (isset($parametros['no_ticket'])) {
            $busqueda .= "
      AND (DC.id_ticket = 0)";
        }

        if (isset($parametros['solo_pago_templos'])) {
            if ($parametros['solo_pago_templos'] == 1) {
                $busqueda .= " AND (
          (
            DC.id_servicio > 0 and
            DC.templos > 0 and
            DC.id_servicio <> 125 and
            DC.id_servicio <> 126
          )
          OR
          (DC.id_carnet in (select id_carnet
          from carnets_templos where id_tipo = 99
          and id_carnet = DC.id_carnet))
        )";
            }
        }

        if (isset($parametros['solo_servicios'])) {
            if ($parametros['solo_servicios'] == 1) {
                $busqueda .= " AND DC.id_servicio > 0 ";
            }
        }

        // RCG 20240506 - Añadido parametro id_servicio
        if(isset($parametros['id_servicio'])){
            $busqueda.=" AND DC.id_servicio = @id_servicio";
        }


        if (isset($parametros['servicios_marcados'])) {
            if (count($parametros['servicios_marcados']) > 0) {
                $busqueda .= " AND (";
                $busqueda_servicios = "";
                foreach ($parametros['servicios_marcados'] as $cada_servicio) {
                    $busqueda_servicios .= " DC.id_dietario = " . $cada_servicio . " OR ";
                }
                $rest = substr($busqueda_servicios, 0, strlen($busqueda_servicios) - 3);
                $busqueda .= " " . $rest . " ) ";
                unset($parametros['servicios_marcados']);
            }
        }

        if (isset($parametros['carnet_especial_distinto_precio'])) {
            $busqueda .= "
      AND
      (
        DC.id_carnet > 0 and
        DC.estado = 'Pagado' and
        DC.recarga = 0 and
        carnets_templos.id_tipo = 99 and
        carnets_templos.precio <>
          (
            select sum(pvp) from carnets_templos_servicios
            where id_carnet = DC.id_carnet and
            borrado = 0
          )
      )";
        }

        if (isset($parametros['lineas_con_descuento'])) {
            $busqueda .= "
      AND
      (
        DC.estado = 'Pagado' and
        (DC.descuento_euros <> 0 or descuento_porcentaje <> 0)
      )";
        }

        if (isset($parametros['servicios_otros_carnets'])) {
            $busqueda .= "
      AND
      (
        DC.estado = 'Pagado' and
        DC.id_dietario in (select id_dietario from pagos_cliente_distinto_carnet)
      )";
        }

        if (isset($parametros['anuladas_no_vino'])) {
            $busqueda .= "
      AND
      (
        (DC.estado = 'Anulada' or DC.estado = 'No vino')
      )";
        }

        if (isset($parametros['id_presupuesto'])) {
            $busqueda .= " AND DC.id_presupuesto = @id_presupuesto ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT DC.id_dietario,DC.id_presupuesto,DC.id_pedido,DC.id_cliente,DC.id_cita,DC.id_ticket,DC.foto_templo,DC.solo_pago,
    DC.fecha_hora_concepto,DC.id_empleado,DC.id_servicio,DC.id_producto,
    DC.id_carnet,DC.recarga,DC.recarga_templos,DC.importe_euros,DC.templos,DC.estado,DC.codigo_proveedor,
    DC.id_usuario_creador as id_usuario_creador_dc,DC.fecha_creacion as fecha_creacion_dc,
    DC.id_usuario_modificacion as id_usuario_modificacion_dc,DC.cantidad,DC.notas_pago_descuento,
    DC.fecha_modificacion as fecha_modificacion_dc,DC.borrado as borrado_dc,
    DC.id_usuario_borrado as id_usuario_borrado_dc,DC.fecha_borrado as fecha_borrado_dc,
    DC.devuelto,
    DC.justificante_pagado,
    CONCAT(clientes.nombre, ' ', clientes.apellidos) As cliente,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos) As empleado,DC.id_centro,
    CONCAT(UC.nombre, ' ', UC.apellidos) As recepcionista,UC.id_centro as id_centro_recepcionista,
    CONCAT(UC2.nombre, ' ', UC2.apellidos) As recepcionista_inicio,
    CONCAT(UC3.nombre, ' ', UC3.apellidos) As recepcionista_pagado,
    DATE_FORMAT(DC.fecha_hora_concepto,'%H:%i') as hora,DC.pago_a_cuenta,
    DATE_FORMAT(DC.fecha_hora_concepto,'%W<br>%d-%b-%Y') as fecha_hora_concepto_ddmmaaaa_abrv,
    DATE_FORMAT(DC.fecha_hora_concepto,'%W, %d-%b-%Y - %H:%i') as fecha_hora_concepto_ddmmaaaa_abrev2,
    DATE_FORMAT(DC.fecha_hora_concepto,'%d-%m-%Y') as fecha_hora_concepto_ddmmaaaa,
    DATE_FORMAT(DC.fecha_hora_concepto,'%Y-%m-%d') as fecha_hora_concepto_aaaammdd,
    DATE_FORMAT(DC.fecha_creacion,'%Y-%m-%d') as fecha_creacion_aaaammdd,
    (DATEDIFF(now(),DC.fecha_modificacion)) as dias_modificacion,
    DATE_FORMAT(DC.fecha_modificacion,'%W, %d-%b-%Y - %H:%i') as fecha_modificacion_abrev,
    DATE_FORMAT(DC.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_creacion_abrev,
    DATE_FORMAT(DC.fecha_pagado,'%W, %d-%b-%Y - %H:%i') as fecha_pagado_abrev,
    servicios.abreviatura as servicio,servicios.precio_proveedor,servicios.nombre_servicio as servicio_completo,
    carnets_templos.codigo as carnet,carnets_templos.codigo_pack_online,carnets_templos.notas as notas_carnet,
    productos.nombre_producto as producto,carnets_templos.precio as pvp_carnet,
    DC.id_presupuesto, presupuestos.nro_presupuesto,
    presupuestos.totalpresupuesto,
    citas.observaciones,citas.estado AS estado_cita,centros.nombre_centro,DC.motivo_devolucion,
    carnets_templos.id_tipo,DC.descuento_euros,DC.descuento_porcentaje,
    DC.pagado_efectivo,DC.pagado_tarjeta,DC.pagado_habitacion,DC.pagado_transferencia,DC.pagado_paypal,DC.pagado_tpv2,clientes_saldos.importe as importe_saldo,
    clientes_saldos.tipo_pago as tipo_pago_saldo,
    (DC.pagado_efectivo+DC.pagado_tarjeta+DC.pagado_habitacion+DC.pagado_transferencia+DC.pagado_paypal+DC.pagado_tpv2+DC.pagado_financiado) as importe_total_final,
    (select sum(pvp) from carnets_templos_servicios where id_carnet = DC.id_carnet
    and borrado = 0) as pvp_carnet_especial,
    case
      when DC.estado = 'Pagado' OR citas.estado = 'Finalizado'
      then 'rgb(0 255 0 / 34%) !important'
      when DC.estado = 'No Pagado'
      then '#fad7e4'
      when DC.estado = 'Anulada'
      then '#f9ca8e'
      when DC.estado = 'No vino'
      then '#f9ca8e'
      when DC.estado = 'Cierre Caja'
      then '#eee'
      when DC.estado = 'Devuelto'
      then '#faf1d7'
      else '#fff'
    end as color_estado,DC.recarga,DC.tipo_pago,'' as carnets_pagos,
                        servicios.maxdescuento,
     DC.comisionfinanciacion
    FROM dietario AS DC
    LEFT JOIN clientes on clientes.id_cliente = DC.id_cliente
    LEFT JOIN usuarios on usuarios.id_usuario = DC.id_empleado
    LEFT JOIN usuarios as UC on UC.id_usuario = DC.id_usuario_modificacion
    LEFT JOIN usuarios as UC2 on UC2.id_usuario = DC.id_usuario_creador
    LEFT JOIN usuarios as UC3 on UC3.id_usuario = DC.id_usuario_pagado
    LEFT JOIN carnets_templos on carnets_templos.id_carnet = DC.id_carnet
    LEFT JOIN servicios on servicios.id_servicio = DC.id_servicio
    LEFT JOIN productos on productos.id_producto = DC.id_producto
    LEFT JOIN presupuestos on presupuestos.id_presupuesto = DC.id_presupuesto
    /*LEFT JOIN presupuestos_items on presupuestos.id_presupuesto = presupuestos_items.id_presupuesto*/
   /* 
   RCG: No pongo este join porque ralentiza mucho la consulta, lo haré en el controlador
   LEFT JOIN 
        (SELECT * FROM presupuestos_items WHERE presupuestos_items.dientes IS NOT NULL) AS pitems 
            ON pitems.id_dietario=DC.id_dietario
      
    */
    LEFT JOIN citas on citas.id_cita = DC.id_cita
    LEFT JOIN centros on centros.id_centro = DC.id_centro
    LEFT JOIN clientes_saldos on clientes_saldos.id_dietario = DC.id_dietario 
    " . $busqueda . " ORDER BY DC.fecha_hora_concepto,id_cliente,DC.fecha_creacion ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function nuevo_dietario_concepto($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['id_cita'] = $parametros['id_cita'];
        if (isset($parametros['fecha_hora_concepto'])) {
            $registro['fecha_hora_concepto'] = $parametros['fecha_hora_concepto'];
        } else {
            $registro['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        }
        $registro['id_empleado'] = ($parametros['id_empleado'] > 0) ? $parametros['id_empleado'] : $this->session->userdata('id_usuario');
        $registro['id_servicio'] = $parametros['id_servicio'];
        if (isset($parametros['codigo_proveedor'])) {
            $registro['codigo_proveedor'] = $parametros['codigo_proveedor'];
        }
        $registro['id_producto'] = $parametros['id_producto'];
        $registro['id_carnet'] = $parametros['id_carnet'];
        if (isset($parametros['recarga'])) {
            $registro['recarga'] = $parametros['recarga'];
        } else {
            $registro['recarga'] = 0;
        }
        if (isset($parametros['recarga_templos'])) {
            $registro['recarga_templos'] = $parametros['recarga_templos'];
        } else {
            $registro['recarga_templos'] = 0;
        }
        if (isset($parametros['cantidad'])) {
            $registro['cantidad'] = $parametros['cantidad'];
        } else {
            $registro['cantidad'] = 1;
        }
        if (isset($parametros['id_presupuesto'])) {
            $registro['id_presupuesto'] = $parametros['id_presupuesto'];
        }
        $registro['importe_euros'] = $parametros['importe_euros'];
        $registro['templos'] = $parametros['templos'];
        $registro['estado'] = $parametros['estado'];
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;
        //29/06/20 Para saber si la recarga es solo para pagar en efectivo
        if (isset($parametros['solo_pago']))
            $registro['solo_pago'] = $parametros['solo_pago'];
        //else
        //    $registro['solo_pago']="";     

        $AqConexion_model->insert('dietario', $registro);

        $sentenciaSQL = "select max(id_dietario) as id from dietario";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);

        return $resultado[0]['id'];
    }

    function nuevo_producto($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        unset($param);
        $param['id_parametro'] = $parametros['id_producto'];
        $producto = $this->Productos_model->leer_productos($parametros);

        unset($param);
        $param['id_cliente'] = $parametros['id_cliente'];
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = null;

        if (isset($parametros['id_empleado_venta']) && $parametros['id_empleado_venta'] > 0) {
            $param['id_empleado'] = $parametros['id_empleado_venta'];
        } else {
            $param['id_empleado'] = $this->session->userdata('id_usuario');
        }
        $param['id_servicio'] = 0;
        $param['id_producto'] = $parametros['id_producto'];
        $param['id_carnet'] = 0;
        $param['importe_euros'] = ($producto[0]['pvp'] * $parametros['cantidad']);
        $param['cantidad'] = $parametros['cantidad'];
        $param['templos'] = 0;
        $param['estado'] = "Pendiente";

        $id_dietario = $this->nuevo_dietario_concepto($param);

        return $id_dietario;
    }

    function nuevo_carnet($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $carnet = $this->Carnets_model->leer_un_carnet($parametros['id_carnet']);

        unset($param);
        $param['id_cliente'] = $parametros['id_cliente'];
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = null;
        $param['id_empleado'] = $this->session->userdata('id_usuario');
        $param['id_servicio'] = 0;
        $param['id_producto'] = 0;
        $param['id_carnet'] = $parametros['id_carnet'];
        $param['importe_euros'] = $carnet[0]['precio'];
        // ... Si es un carnet de templos guardamos 0, si es de servicios
        // calculamos cuantos templos suman los servicios,
        // ya que este carnet puede ser pagado con uno de templos.
        if ($carnet[0]['id_tipo'] != 99) {
            $param['templos'] = 0;
        } else {
            unset($param2);
            $param2['id_carnet'] = $parametros['id_carnet'];
            $param['templos'] = $this->Carnets_model->leer_templos_carnet_especial($param2);
        }
        $param['estado'] = "Pendiente";

        $id_dietario = $this->nuevo_dietario_concepto($param);

        return $id_dietario;
    }

    function copiar_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $citas = $this->Agenda_model->leer_citas($parametros);

        unset($param);
        $param['id_cliente'] = $citas[0]['id_cliente'];
        $param['id_cita'] = $citas[0]['id_cita'];
        $param['fecha_hora_concepto'] = $citas[0]['fecha_inicio_aaaammdd'] . " " . $citas[0]['hora_inicio'];
        $param['id_empleado'] = $citas[0]['id_usuario_empleado'];
        $param['id_servicio'] = $citas[0]['id_servicio'];
        $param['id_producto'] = 0;
        $param['id_carnet'] = "";
        /* CHAINS 20240204 - Comprobamos si tiene prcio de tarifa */
        $param['importe_euros'] = $citas[0]['pvptarifa']!==null ? $citas[0]['pvptarifa'] : $citas[0]['pvp'];
        /* FIN CHAINS 20240204 - Comprobamos si tiene prcio de tarifa */
        $param['templos'] = $citas[0]['templos'];
        $param['estado'] = "Pendiente";
        if (isset($parametros['codigo_proveedor'])) {
            $param['codigo_proveedor'] = $parametros['codigo_proveedor'];
        }

        $id_dietario = $this->nuevo_dietario_concepto($param);

        return $id_dietario;
    }

    function copiar_cita_presupuesto($parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $citas = $this->Agenda_model->leer_citas($parametros);
        unset($param);
        $param['id_cliente'] = $citas[0]['id_cliente'];
        $param['id_cita'] = $citas[0]['id_cita'];
        $param['fecha_hora_concepto'] = $citas[0]['fecha_inicio_aaaammdd'] . " " . $citas[0]['hora_inicio'];
        $param['id_empleado'] = $citas[0]['id_usuario_empleado'];
        $param['id_servicio'] = $citas[0]['id_servicio'];
        $param['id_producto'] = 0;
        $param['id_carnet'] = "";
        $param['id_presupuesto'] = (isset($parametros['id_presupuesto'])) ? $parametros['id_presupuesto'] : 0;
        $param['importe_euros'] = (isset($parametros['importe_euros'])) ? $parametros['importe_euros'] : 0;
        $param['templos'] = $citas[0]['templos'];
        $param['estado'] = "Pendiente";
        if (isset($parametros['codigo_proveedor'])) {
            $param['codigo_proveedor'] = $parametros['codigo_proveedor'];
        }
        //printr($param);
        $id_dietario = $this->nuevo_dietario_concepto($param);

        return $id_dietario;
    }

    function modificar_cita_presupuesto($id_dietario, $parametros)
    {
        $AqConexion_model = new AqConexion_model();
        $citas = $this->Agenda_model->leer_citas($parametros);
        unset($param);
        
        $param['id_cliente'] = $citas[0]['id_cliente'];
        $param['id_cita'] = $citas[0]['id_cita'];
        $param['fecha_hora_concepto'] = $citas[0]['fecha_inicio_aaaammdd'] . " " . $citas[0]['hora_inicio'];
        $param['id_empleado'] = $citas[0]['id_usuario_empleado'];
        $param['id_servicio'] = $citas[0]['id_servicio'];
        $param['id_producto'] = 0;
        $param['id_carnet'] = "";
        $param['id_presupuesto'] = (isset($parametros['id_presupuesto'])) ? $parametros['id_presupuesto'] : 0;
        $param['importe_euros'] = (isset($parametros['importe_euros'])) ? $parametros['importe_euros'] : 0;
        $param['templos'] = $citas[0]['templos'];
        $param['estado'] = "Pendiente";
        $param['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $param['fecha_modificacion'] = date("Y-m-d H:i:s");
        
        $where['id_dietario'] = $id_dietario;
        $AqConexion_model->update('dietario', $param, $where);
        return $this->db->affected_rows();
    }

    function modificar_dietario_cita($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $cita = $this->Agenda_model->leer_citas($parametros);

        unset($registro);
        $registro['fecha_hora_concepto'] = $cita[0]['fecha_inicio_aaaammdd'] . " " . $cita[0]['hora_inicio'];
        $registro['id_cliente'] = $cita[0]['id_cliente'];
        $registro['id_empleado'] = $cita[0]['id_usuario_empleado'];
        $registro['id_servicio'] = $cita[0]['id_servicio'];
        // CHAINS 20240204 - Se comprueba si tiene precio tarifa
        $registro['importe_euros'] = ($cita[0]['coste'] != '' && $cita[0]['coste'] > 0) ? $cita[0]['coste'] :
            ($cita[0]['pvptarifa'] !== null ? $cita[0]['pvptarifa'] : $cita[0]['pvp']);
        $registro['templos'] = $cita[0]['templos'];
        if (isset($parametros['codigo_proveedor'])) {
            $registro['codigo_proveedor'] = $parametros['codigo_proveedor'];
        }
        //
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_cita'] = $parametros['id_cita'];
        $AqConexion_model->update('dietario', $registro, $where);

        return 1;
    }

    function marcar_no_pagado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Marcamos pagado en el dietario
        unset($registro);
        $registro['estado'] = $parametros['estado'];
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $where['id_dietario'] = $parametros['id_dietario'];
        $AqConexion_model->update('dietario', $registro, $where);

        return 1;
    }

    //
    // ... Marcamos por pagado un concepto del dietario en los diferentes
    // tipos de pagos elegidos y con los descuentos que correspondan.
    //
    function marcar_pagado($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Recogemos lo que hay que pagar en cada una de las formas,
        // si no llega el parametro, es porque es un pago en templos
        // entonces los pongo a 0.
        if (!isset($parametros['pagado_efectivo'])) {
            $parametros['pagado_efectivo'] = 0;
        }
        if (!isset($parametros['pagado_tarjeta'])) {
            $parametros['pagado_tarjeta'] = 0;
        }
        if (!isset($parametros['pagado_tpv2'])) {
            $parametros['pagado_tpv2'] = 0;
        } 
        if (!isset($parametros['pagado_paypal'])) {
            $parametros['pagado_paypal'] = 0;
        }
        if (!isset($parametros['pagado_transferencia'])) {
            $parametros['pagado_trasnferencia'] = 0;
        } 
        /*if (!isset($parametros['pagado_financiado'])) {
            $parametros['pagado_financiado'] = 0;
        }*/
        if (!isset($parametros['pagado_habitacion'])) {
            $parametros['pagado_habitacion'] = 0;
        }
        if (!isset($parametros['descuento_euros'])) {
            $parametros['descuento_euros'] = 0;
        }
        if (!isset($parametros['descuento_porcentaje'])) {
            $parametros['descuento_porcentaje'] = 0;
        }


        $pagado_efectivo = $parametros['pagado_efectivo'];
        $pagado_tarjeta = $parametros['pagado_tarjeta'];
        $pagado_tpv2 = $parametros['pagado_tpv2'];
        $pagado_paypal = $parametros['pagado_paypal'];
        $pagado_transferencia = $parametros['pagado_transferencia'];
        //$pagado_financiado = $parametros['pagado_financiado'];
        
        $pagado_habitacion = $parametros['pagado_habitacion'];
        $notas_pago_descuento = $parametros['notas_pago_descuento'];

        for ($i = 0; $i < count($parametros['marcados']); $i++) {
            // ... Extraemos el id_dietario y el importe total a pagar
            // del este concepto marcado, ya con los descuentos aplicados.
            $id_dietario = $parametros['marcados'][$i];
            $concepto_importe = $parametros['importe_euros'][$i];

            if(isset($parametros['notas_pago_descuento'][$i])){
                $notas_pago_descuento=$parametros['notas_pago_descuento'][$i];
            }
            else $notas_pago_descuento='';


            // ... Ponemos la variables de las formas de pago a 0
            // por si no pasa por alguna condicion, que siempre tenga un valor.
            unset($registro);
            unset($where);
            $registro['pagado_efectivo'] = 0;
            $registro['pagado_tarjeta'] = 0;
            $registro['pagado_tpv2'] = 0; 
            $registro['pagado_paypal'] = 0; 
            $registro['pagado_transferencia'] = 0; 
            //$registro['pagado_financiado'] = 0; 
            $registro['pagado_habitacion'] = 0;

            // ... Establecemos la variable que marca los diferentes tipos de pago elegidos
            $tipos_pagos = "";

            // ... Comprobamos lo que hay que marcar en Efectivo
            if ($pagado_efectivo > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_efectivo) > 0) {
                    $registro['pagado_efectivo'] = $pagado_efectivo;
                    $pagado_efectivo = 0;
                }
                if (($concepto_importe - $pagado_efectivo) == 0) {
                    $registro['pagado_efectivo'] = $concepto_importe;
                    $pagado_efectivo = 0;
                }
                if (($concepto_importe - $pagado_efectivo) < 0) {
                    $registro['pagado_efectivo'] = $concepto_importe;
                    $pagado_efectivo -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_efectivo'];

                $tipos_pagos .= "#efectivo";
            }

            // ... Comprobamos lo que hay que marcar con Tarjeta
            if ($pagado_tarjeta > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_tarjeta) > 0) {
                    $registro['pagado_tarjeta'] = $pagado_tarjeta;
                    $pagado_tarjeta = 0;
                }
                if (($concepto_importe - $pagado_tarjeta) == 0) {
                    $registro['pagado_tarjeta'] = $concepto_importe;
                    $pagado_tarjeta = 0;
                }
                if (($concepto_importe - $pagado_tarjeta) < 0) {
                    $registro['pagado_tarjeta'] = $concepto_importe;
                    $pagado_tarjeta -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_tarjeta'];

                $tipos_pagos .= "#tarjeta";
            }

            // ... Comprobamos lo que hay que marcar con TPV2
            if ($pagado_tpv2 > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_tpv2) > 0) {
                    $registro['pagado_tpv2'] = $pagado_tpv2;
                    $pagado_tpv2 = 0;
                }
                if (($concepto_importe - $pagado_tpv2) == 0) {
                    $registro['pagado_tpv2'] = $concepto_importe;
                    $pagado_tpv2 = 0;
                }
                if (($concepto_importe - $pagado_tpv2) < 0) {
                    $registro['pagado_tpv2'] = $concepto_importe;
                    $pagado_tpv2 -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_tpv2'];

                $tipos_pagos .= "#tpv2";
            }

            // ... Comprobamos lo que hay que marcar con PayPal
            if ($pagado_paypal > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_paypal) > 0) {
                    $registro['pagado_paypal'] = $pagado_paypal;
                    $pagado_paypal = 0;
                }
                if (($concepto_importe - $pagado_paypal) == 0) {
                    $registro['pagado_paypal'] = $concepto_importe;
                    $pagado_paypal = 0;
                }
                if (($concepto_importe - $pagado_paypal) < 0) {
                    $registro['pagado_paypal'] = $concepto_importe;
                    $pagado_paypal -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_paypal'];

                $tipos_pagos .= "#paypal";
            }

            // ... Comprobamos lo que hay que marcar con Transferencia
            if ($pagado_transferencia > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_transferencia) > 0) {
                    $registro['pagado_transferencia'] = $pagado_transferencia;
                    $pagado_transferencia = 0;
                }
                if (($concepto_importe - $pagado_transferencia) == 0) {
                    $registro['pagado_transferencia'] = $concepto_importe;
                    $pagado_transferencia = 0;
                }
                if (($concepto_importe - $pagado_transferencia) < 0) {
                    $registro['pagado_transferencia'] = $concepto_importe;
                    $pagado_transferencia -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_transferencia'];

                $tipos_pagos .= "#transferencia";
            }

            // ... Comprobamos lo que hay que marcar con financiado
            /*
                if ($pagado_financiado > 0 && $concepto_importe > 0) {
                    if (($concepto_importe - $pagado_financiado) > 0) {
                        $registro['pagado_financiado'] = $pagado_financiado;
                        $pagado_financiado = 0;
                    }
                    if (($concepto_importe - $pagado_financiado) == 0) {
                        $registro['pagado_financiado'] = $concepto_importe;
                        $pagado_transferencia = 0;
                    }
                    if (($concepto_importe - $pagado_financiado) < 0) {
                        $registro['pagado_financiado'] = $concepto_importe;
                        $pagado_financiado -= $concepto_importe;
                    }

                    $concepto_importe -= $parametros['pagado_financiado'];

                    $tipos_pagos .= "#financiado";
                }
            */

            // ... Comprobamos lo que hay que marcar en Habitación
            if ($pagado_habitacion > 0 && $concepto_importe > 0) {
                if (($concepto_importe - $pagado_habitacion) > 0) {
                    $registro['pagado_habitacion'] = $pagado_habitacion;
                    $pagado_habitacion = 0;
                }
                if (($concepto_importe - $pagado_habitacion) == 0) {
                    $registro['pagado_habitacion'] = $concepto_importe;
                    $pagado_habitacion = 0;
                }
                if (($concepto_importe - $pagado_habitacion) < 0) {
                    $registro['pagado_habitacion'] = $concepto_importe;
                    $pagado_habitacion -= $concepto_importe;
                }

                $concepto_importe -= $parametros['pagado_habitacion'];

                $tipos_pagos .= "#habitacion";
            }

            // ... Si todos los metodos de pago son a 0, porque son una caja de regalo
            // entonces ponemos como forma de pago efectivo.
            if ($tipos_pagos == "") {
                $tipos_pagos = "#efectivo";
            }
            if (isset($parametros['tipo_pago'])) {
                $tipos_pagos = $parametros['tipo_pago'];
            }

            // ... Actualizamos el concepto del dietario y marcamos las formas de pago elegidas
            // y que cantidad para cada cosa.
            $registro['estado'] = "Pagado";
            $registro['tipo_pago'] = $tipos_pagos;
            $registro['descuento_euros'] = $parametros['descuento_euros'][$i];
            $registro['descuento_porcentaje'] = $parametros['descuento_porcentaje'][$i];
            $registro['notas_pago_descuento'] = $notas_pago_descuento;
            $registro['fecha_pagado'] = date("Y-m-d H:i:s");
            $registro['id_usuario_pagado'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $where['id_dietario'] = $id_dietario;
            $AqConexion_model->update('dietario', $registro, $where);
			$this->EvaluacionesGoogle_model->dietarioPagado($id_dietario);

            $parametros['pagado_efectivo'] = $pagado_efectivo;
            $parametros['pagado_tarjeta'] = $pagado_tarjeta;
            $parametros['pagado_tpv2'] = $pagado_tpv2; 
            $parametros['pagado_paypal'] = $pagado_paypal; 
            $parametros['pagado_transferencia'] = $pagado_transferencia;
            //$parametros['pagado_financiado'] = $pagado_financiado; 
            $parametros['pagado_habitacion'] = $pagado_habitacion;

            // ... Leemos el concepto del dietario, para que en caso
            // de tener cita, marcarla como finalizada.
            unset($param);
            $param['id_dietario'] = $id_dietario;
            $dietario = $this->Dietario_model->leer($param);

            if (isset($dietario) && is_array($dietario)) {
                //
                // ... Actualizamos la cita
                //
                if ($dietario[0]['id_cita'] > 0) {
                    unset($registro);
                    $registro['estado'] = "Finalizado";
                    $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                    $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

                    unset($where);
                    $where['id_cita'] = $dietario[0]['id_cita'];
                    $AqConexion_model->update('citas', $registro, $where);

                    $this->load->model('Liquidaciones_model');
                    $id_cita = $dietario[0]['id_cita'];
                    $this->Liquidaciones_model->liquidacion_cita($id_cita);
                }

                //
                // ... Restamos el stock en caso de ser un producto lo pagado.
                //
                if ($dietario[0]['id_producto'] > 0) {
                    unset($param2);
                    $param2['id_producto'] = $dietario[0]['id_producto'];
                    $param2['cantidad'] = $dietario[0]['cantidad'];
                    $dietario = $this->Productos_model->actualizar_stock_venta($param2);
                }

                //
                // ... Realizamos la recarga en el carnet si se paga una recarga.
                //
                if (isset($dietario[0]['recarga']) && $dietario[0]['recarga'] == 1 && isset($dietario[0]['id_carnet']) && $dietario[0]['id_carnet'] > 0) {
                    unset($param2);
                    $param2['id_dietario'] = $id_dietario; //21/04/20 Para colocar campo pagado=1 en la recarga
                    $param2['id_carnet'] = $dietario[0]['id_carnet'];
                    $param2['templos_recarga'] = $dietario[0]['recarga_templos'];
                    $xdietario = $this->Carnets_model->aplicar_recarga_carnet($param2); //13/01/21 estaba $dietario, pero generaba error en tabla "clientes_saldo"
                    $pagar_recarga = $this->Carnets_model->recarga_pagada($param2); //21/04/20 pagado=1
                }

                //
                // ... Actualizamos el precio de venta del carnets, en caso de ser
                // de tipo Especial.
                //
                if (isset($dietario[0]['id_carnet']) && $dietario[0]['id_carnet'] > 0 && isset($dietario[0]['id_servicio']) && $dietario[0]['id_servicio'] == 0) {
                    unset($param2);
                    $param2['id_carnet'] = $dietario[0]['id_carnet'];
                    $param2['precio'] = $dietario[0]['pagado_efectivo'] + $dietario[0]['pagado_tarjeta'] + $dietario[0]['pagado_habitacion'] + $dietario[0]['pagado_transferencia'] + $dietario[0]['pagado_tpv2'];

                    $r = $this->Carnets_model->cambio_precio_venta_carnet($param2);
                }

                //
                // ... Marcamos el carnet como borrado 0, una vez se ha pagado.
                //
                if (isset($dietario[0]['id_carnet']) && $dietario[0]['id_carnet'] > 0 && $dietario[0]['id_servicio'] == 0 && $dietario[0]['recarga'] == 0) {
                    unset($param2);
                    unset($where);

                    $where['id_carnet'] = $dietario[0]['id_carnet'];
                    $param2['borrado'] = 0;

                    $AqConexion_model->update('carnets_templos', $param2, $where);
                }

                //
                // ... Si está marcado usar saldo del cliente al marca el concepto como pagado
                //
                if (isset($parametros['usa_saldo'][$i])) {
                    if ($parametros['usa_saldo'][$i] > 0) {
                        $id_saldo = $this->Clientes_model->pago_a_cuenta($dietario[0]['id_dietario'], $dietario[0]['id_cliente'], ($parametros['usa_saldo'][$i] * -1), "#liquidacion", "");
                    }
                }
            }
        }

        return 1;
    }

    function marcar_pagado_en_templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        // ... Marcamos pagado en el dietario
        unset($registro);
        $registro['estado'] = $parametros['estado'];
        $registro['tipo_pago'] = $parametros['tipo_pago'];
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['fecha_pagado'] = date("Y-m-d H:i:s");
        $registro['id_usuario_pagado'] = $this->session->userdata('id_usuario');

        $where['id_dietario'] = $parametros['id_dietario'];
        $AqConexion_model->update('dietario', $registro, $where);
		$this->EvaluacionesGoogle_model->dietarioPagado($parametros['id_dietario']);


        // ... Finalizamos la cita si es un servicio.
        if (isset($parametros['id_cita'])) {
            unset($registro);
            $registro['estado'] = "Finalizado";
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

            unset($where);
            $where['id_cita'] = $parametros['id_cita'];
            $AqConexion_model->update('citas', $registro, $where);
        }

        // ... Marcamos el carnet como borrado 0, una vez se ha pagado.
        unset($param);
        $param['id_dietario'] = $parametros['id_dietario'];
        $dietario = $this->Dietario_model->leer($param);
        if ($dietario > 0) {
            if ($dietario[0]['id_carnet'] > 0 && $dietario[0]['id_servicio'] == 0 && $dietario[0]['recarga'] == 0) {
                unset($param2);
                unset($where);

                $where['id_carnet'] = $dietario[0]['id_carnet'];
                $param2['borrado'] = 0;

                $AqConexion_model->update('carnets_templos', $param2, $where);
            }
        }

        return 1;
    }

    // ... Devuelve el array de dietarios pasado con los carnets con los que se pago.
    function carnets_pago_templos($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if ($parametros['historial'] != 0) {
            for ($i = 0; $i < count($parametros['historial']); $i++) {
                if ($parametros['historial'][$i]['estado'] == "Pagado") {
                    $mystring = $parametros['historial'][$i]['tipo_pago'];
                    $findme = "#templos";
                    $pos = strpos($mystring, $findme);

                    if ($pos !== false) {
                        unset($param);
                        $param['id_dietario'] = $parametros['historial'][$i]['id_dietario'];
                        //$carnets=$this->Carnets_model->leer($param);
                        $carnets = $this->Carnets_model->leer_carnets_pago_dietario($param);

                        if ($carnets != 0) {
                            $parametros['historial'][$i]['carnets_pagos'] = $carnets;
                        } else {
                            $parametros['historial'][$i]['carnets_pagos'] = 0;
                        }
                    } //if false

                    //15/04/20 para Recarga obtener templos disponibles y los templos recargados
                    if ($parametros['historial'][$i]['recarga'] == 1 or ($parametros['historial'][$i]['tipo_pago'] != "#templos" and $parametros['historial'][$i]['id_carnet'] > 0)) {
                        unset($param);
                        $param['id_carnet'] = $parametros['historial'][$i]['id_carnet'];
                        //$carnets=$this->Carnets_model->leer($param);
                        $carnets = $this->Carnets_model->leer_un_carnet_templos($param);

                        if ($carnets != 0) {
                            $parametros['historial'][$i]['carnets_pagos'] = $carnets;
                        } else {
                            $parametros['historial'][$i]['carnets_pagos'] = 0;
                        }
                    } //if recarga


                    //Fin

                } // if pagado
            } //For

            return $parametros['historial'];
        } else {
            return 0;
        }
    }




    function borrar_concepto($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $dietario = $this->leer($parametros);

        $parametros['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $parametros['fecha_borrado'] = date("Y-m-d H:i:s");

        // ... Si es un producto, lo borramos del dietario sin mas.
        if ($dietario[0]['id_producto'] > 0) {
            $sentenciaSQL = "update dietario set borrado = 1,
      id_usuario_borrado = @id_usuario_borrado,
      fecha_borrado = @fecha_borrado
      where id_dietario = @id_dietario";
            $AqConexion_model->no_select($sentenciaSQL, $parametros);
        }

        // ... Si es un carnet vendido y no una recarga en uno existente
        // borramos la linea del dietario y el carnet lo marcamos como borrado.
        // Si es recarga, solo se borra el concepto del dietario
        if ($dietario[0]['id_carnet'] > 0) {
            $sentenciaSQL = "update dietario set borrado = 1,
      id_usuario_borrado = @id_usuario_borrado,
      fecha_borrado = @fecha_borrado
      where id_dietario = @id_dietario";
            $AqConexion_model->no_select($sentenciaSQL, $parametros);

            if ($dietario[0]['recarga'] == 0) {
                unset($param);
                $param['id_carnet'] = $dietario[0]['id_carnet'];
                $ok = $this->Carnets_model->borrar_desde_dietario($param);
            }
        }

        // ... Si es un servicio, borramos la linea del dietario y anulamos la cita.
        if ($dietario[0]['id_cita'] > 0) {
            $sentenciaSQL = "update dietario set borrado = 1,
      id_usuario_borrado = @id_usuario_borrado,
      fecha_borrado = @fecha_borrado
      where id_dietario = @id_dietario";
            $AqConexion_model->no_select($sentenciaSQL, $parametros);

            unset($param);
            $param['id_cita'] = $dietario[0]['id_cita'];
            $param['estado'] = "Anulada";

            $ok = $this->Agenda_model->cambio_estado_cita($param);
        }

        return 1;
    }

    function cambio_estado_dietario($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");

        // ... Leemos los registros
        $sentencia_sql = " UPDATE dietario SET estado = @estado,
    id_usuario_modificacion = @id_usuario_modificacion,
    fecha_modificacion = @fecha_modificacion
    where id_cita = @id_cita ";
        $datos = $AqConexion_model->no_select($sentencia_sql, $parametros);

        return $datos;
    }

    function devolucion($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['id_cliente'] = $parametros['id_cliente'];
        $registro['id_cita'] = $parametros['id_cita'];
        $registro['fecha_hora_concepto'] = $parametros['fecha_hora_concepto'];
        $registro['id_empleado'] = $parametros['id_empleado'];
        $registro['id_servicio'] = $parametros['id_servicio'];
        $registro['id_producto'] = $parametros['id_producto'];
        $registro['id_carnet'] = $parametros['id_carnet'];
        $registro['importe_euros'] = $parametros['importe_euros'];
        $registro['pagado_efectivo'] = $parametros['pagado_efectivo'];
        $registro['pagado_tarjeta'] = $parametros['pagado_tarjeta'];
        $registro['pagado_transferencia'] = $parametros['pagado_transferencia']; //24/03/20
        $registro['pagado_habitacion'] = $parametros['pagado_habitacion'];
        $registro['tipo_pago'] = $parametros['tipo_pago'];
        $registro['templos'] = $parametros['templos'];
        $registro['estado'] = $parametros['estado'];
        $registro['motivo_devolucion'] = $parametros['motivo_devolucion'];
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        //
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['id_dietario_devolucion'] = $parametros['id_dietario'];

        $registro['borrado'] = 0;
        if ($parametros['que_devolver'] == 3) {
            $registro['pago_a_cuenta'] = 1;
        }

   //     echo "<pre>";var_dump($registro);die();

        $AqConexion_model->insert('dietario', $registro);

        $sentenciaSQL = "select max(id_dietario) as id from dietario";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);
        
        
        // Actualiza el id_dietario que se devuelve, si llegó
        if(isset($parametros['id_dietario']) && $parametros['id_dietario'] > 0){
            
            //Para no perder la información de los dientes cuando se desligue el item
            $dientes = $this->getDientesForDietario([$parametros['id_dietario']]);
            if (!empty($dientes)){
              $AqConexion_model->no_select("UPDATE dietario SET dientes = '".implode(',', $dientes)."' 
                WHERE id_dietario = ".$parametros['id_dietario']." OR id_dietario = ".$resultado[0]['id'], []);
            }
            
            $parametros_id_dietario['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $parametros_id_dietario['fecha_modificacion'] = date("Y-m-d H:i:s");

            // ... Leemos los registros
            $sentencia_sql = "UPDATE dietario SET devuelto = 1,
            id_usuario_modificacion = @id_usuario_modificacion,
            fecha_modificacion = @fecha_modificacion
            where id_dietario = ".$parametros['id_dietario']."";
            $datos = $AqConexion_model->no_select($sentencia_sql, $parametros_id_dietario);


            // RCG Obtenemos el dietario
            $sql="SELECT * FROM dietario WHERE  id_dietario=".$parametros['id_dietario'];
            $dietariooldA=$AqConexion_model->select($sql,null);
            $dietarioOld=$dietariooldA[0];
            // Si es de un presupuesto liberar el item
            if($dietarioOld['id_presupuesto']){
                $sqlpresup="SELECT * FROM presupuestos_items WHERE id_presupuesto=".$dietarioOld['id_presupuesto'].
                                " AND id_dietario=".$parametros['id_dietario'];
                $presupA=$AqConexion_model->select($sqlpresup,null);
                if($presupA>0){
                    $idPresupuestoItem=$presupA[0]['id_presupuesto_item'];
                    $sqlupdpresu="UPDATE presupuestos_items SET id_cita=0, id_dietario=0,
                              id_usuario_modificacion = @id_usuario_modificacion,
                            fecha_modificacion = @fecha_modificacion
                         WHERE id_presupuesto_item=".$idPresupuestoItem;
                    $AqConexion_model->no_select($sqlupdpresu, $parametros_id_dietario);
                }
            }

            // RCG Borrar línea de liquidación doctor asociada que se habría creado al realizar ese servicio.
            if($dietarioOld['id_cita']){
                $sqldelliq="DELETE FROM liquidaciones_citas WHERE id_cita=".$dietarioOld['id_cita'];
                $AqConexion_model->no_select($sqldelliq,null);
            }


        }


        // RCG Borrar linea de liquidacion doctor asociada al servicio


        // ... Actualizamos el stock si se devuelve un producto.
        if ($parametros['id_producto'] > 0) {
            unset($param2);
            $param2['id_producto'] = $parametros['id_producto'];
            $param2['cantidad'] = 1;
            $dietario = $this->Productos_model->actualizar_stock_devolucion($param2);
        }

        if ($parametros['que_devolver'] == 3) {
            // se crea un gasto en el saldo del cliente
            $this->Clientes_model->pago_a_cuenta($resultado[0]['id'], $parametros['id_cliente'], ($parametros['importe_euros']), "#devolucion", $parametros['motivo_devolucion']);
        }
        if ($parametros['tipo_pago'] == '#saldo_cuenta') {
            // se crea un ingresoo en el saldo del cliente
            $this->Clientes_model->pago_a_cuenta($resultado[0]['id'], $parametros['id_cliente'], (-1*$parametros['importe_euros']), "#devolucion", $parametros['motivo_devolucion']);
        }


        return $resultado[0]['id'];
    }


    //
    // ... Crear ticket de compra
    //
    function CrearTicket($marcados, $id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        if (count($marcados) > 0) {
            // ... Creamos el ticket
            unset($registro);
            $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
            $registro['id_cliente'] = $id_cliente;
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;

            $AqConexion_model->insert('dietario_tickets', $registro);

            $sentenciaSQL = "select max(id_ticket) as id_ticket from dietario_tickets";
            $resultado = $AqConexion_model->select($sentenciaSQL, null);
            $id_ticket = $resultado[0]['id_ticket'];

            // ... Vinculamos el ticket a cada línea del dietario.
            foreach ($marcados as $id_dietario) {
                unset($registro);
                $id_diet = $this->db->get_where('dietario', array('id_dietario' => $id_dietario))->row();
                if ($id_diet->borrado == 1) {
                    $registro['borrado'] = 0;
                }
                $registro['id_ticket'] = $id_ticket;
                $where['id_dietario'] = $id_dietario;
                $AqConexion_model->update('dietario', $registro, $where);
            }

            return $id_ticket;
        } else {
            return 0;
        }
    }

    //
    // ... Crear ticket de compra para templos
    //
    function CrearTicketTemplos($servicios, $id_cliente, $foto_templo)
    {
        $AqConexion_model = new AqConexion_model();

        if (count($servicios) > 0) {
            // ... Creamos el ticket
            unset($registro);
            $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
            $registro['id_cliente'] = $id_cliente;
            $registro['fecha_creacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
            $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
            $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
            $registro['borrado'] = 0;

            $AqConexion_model->insert('dietario_tickets', $registro);

            $sentenciaSQL = "select max(id_ticket) as id_ticket from dietario_tickets";
            $resultado = $AqConexion_model->select($sentenciaSQL, null);
            $id_ticket = $resultado[0]['id_ticket'];

            // ... Vinculamos el ticket a cada línea del dietario pagada en templos
            for ($i = 0; $i < count($servicios); $i++) {
                unset($registro);

                $registro['id_ticket'] = $id_ticket;
                $registro['foto_templo'] = $foto_templo;
                $where['id_dietario'] = $servicios[$i]['id_dietario'];
                $AqConexion_model->update('dietario', $registro, $where);
            }

            return $id_ticket;
        }

        return 0;
    }

    //
    // ... Leer ticket de compra
    //
    function LeerTickets($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_ticket'])) {
            if ($parametros['id_ticket'] > 0) {
                $busqueda .= " AND T.id_ticket = @id_ticket ";
            }
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
            $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

            if ($parametros['fecha_desde'] != "") {
                $busqueda .= " AND T.fecha_creacion >= @fecha_desde
        AND T.fecha_creacion <= @fecha_hasta ";
            }
        }

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND T.id_centro = @id_centro ";
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT T.id_ticket,T.id_centro,T.id_cliente,
    T.id_usuario_creador,T.fecha_creacion,
    T.id_usuario_modificacion,T.fecha_modificacion,
    T.borrado,T.id_usuario_borrado,T.fecha_borrado,
    DATE_FORMAT(T.fecha_creacion,'%Y-%m-%d') as fecha_creacion_aaaammdd,
    DATE_FORMAT(T.fecha_creacion,'%Y-%m-%d %H:%i:%s') as fecha_creacion_aaaammdd_hhmmss,
    DATE_FORMAT(T.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_creacion_abrev,
    centros.nombre_centro,CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente,
    centros.direccion_completa,
    CONCAT(usuarios.nombre) As atendido_por
    FROM dietario_tickets AS T
    LEFT JOIN centros on centros.id_centro = T.id_centro
    LEFT JOIN clientes on clientes.id_cliente = T.id_cliente
    LEFT JOIN usuarios on usuarios.id_usuario = T.id_usuario_creador
    WHERE T.borrado = 0 " . $busqueda . " ORDER BY T.fecha_creacion ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //08/06/20
    //Todos los tickets de un cliente de una fecha
    function LeerTicketsToday($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['id_ticket'])) {
            if ($parametros['id_ticket'] > 0) {
                $busqueda .= " AND T.id_ticket = @id_ticket ";
            }
        }

        if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
            $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
            $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

            if ($parametros['fecha_desde'] != "") {
                $busqueda .= " AND dietario.fecha_hora_concepto >= @fecha_desde
        AND dietario.fecha_hora_concepto <= @fecha_hasta ";
            }
        }

        if (isset($parametros['id_centro'])) {
            if ($parametros['id_centro'] > 0) {
                $busqueda .= " AND T.id_centro = @id_centro ";
            }
        }

        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                $busqueda .= " AND T.id_cliente = @id_cliente ";
            }
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT T.id_ticket,T.id_centro,T.id_cliente,
    T.id_usuario_creador,T.fecha_creacion,
    T.id_usuario_modificacion,T.fecha_modificacion,
    T.borrado,T.id_usuario_borrado,T.fecha_borrado,
    DATE_FORMAT(T.fecha_creacion,'%Y-%m-%d') as fecha_creacion_aaaammdd,
    DATE_FORMAT(T.fecha_creacion,'%Y-%m-%d %H:%i:%s') as fecha_creacion_aaaammdd_hhmmss,
    DATE_FORMAT(T.fecha_creacion,'%W, %d-%b-%Y - %H:%i') as fecha_creacion_abrev,
    centros.nombre_centro,CONCAT(clientes.nombre, ' ', clientes.apellidos) as cliente,
    centros.direccion_completa,
    CONCAT(usuarios.nombre) As atendido_por,
    dietario.fecha_hora_concepto,dietario.id_servicio,dietario.id_carnet,dietario.templos,dietario.tipo_pago,
    servicios.abreviatura,servicios.nombre_servicio as servicio_completo,
    carnets_templos.codigo,carnets_templos.templos_disponibles
    FROM dietario_tickets AS T
    LEFT JOIN centros on centros.id_centro = T.id_centro
    LEFT JOIN clientes on clientes.id_cliente = T.id_cliente
    LEFT JOIN usuarios on usuarios.id_usuario = T.id_usuario_creador
    LEFT JOIN dietario on dietario.id_ticket = T.id_ticket
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    LEFT JOIN carnets_templos on carnets_templos.id_carnet=dietario.id_carnet
    WHERE T.borrado = 0 " . $busqueda . " ORDER BY T.fecha_creacion ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }


    //Fin

    //
    // ... Leemos el ultimo id_ticket de un cliente.
    //
    function UltimoTicketCliente($id_cliente)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_cliente'] = $id_cliente;

        // ... Leemos los registros
        $sentencia_sql = "SELECT T.id_ticket,
    DATE_FORMAT(T.fecha_creacion,'%d-%m-%Y %H:%i') as fecha_creacion_aaaammdd
    FROM dietario_tickets AS T
    WHERE T.borrado = 0 AND id_cliente = @id_cliente ORDER BY id_ticket DESC limit 1 ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    // ... Carnets asociados al ticket
    //
    function CarnetsTicketCliente($id_ticket)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_ticket'] = $id_ticket;

        // ... Leemos los registros
        $sentencia_sql = "SELECT distinct codigo as codigo
    FROM carnets_templos
    WHERE borrado = 0 and
      id_carnet in
        (select ,id_carnet from dietario where id_ticket = @id_ticket and borrado = 0)
    ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    //
    // ... Finaliza una cita online
    //
    function finalizar_cita_online($id_dietario, $id_cita, $id_ticket)
    {
        $AqConexion_model = new AqConexion_model();

        //
        // finaliza el pago en el dietario.
        $dietario['estado'] = "Pagado";
        $dietario['fecha_pagado'] = date("Y-m-d H:i:s");
        $dietario['id_usuario_pagado'] = $this->session->userdata('id_usuario');
        $dietario['fecha_modificacion'] = date("Y-m-d H:i:s");
        $dietario['id_usuario_modificacion'] = $this->session->userdata('id_usuario');

        $where['id_dietario'] = $id_dietario;

        $AqConexion_model->update('dietario', $dietario, $where);
		$this->EvaluacionesGoogle_model->dietarioPagado($id_dietario);


        //
        // finaliza la cita
        $cita['estado'] = "Finalizado";

        unset($where);
        $where['id_cita'] = $id_cita;

        $AqConexion_model->update('citas', $cita, $where);

        //
        // Actualiza creador y modificador del ticket.
        $ticket['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $ticket['id_usuario_creador'] = $this->session->userdata('id_usuario');
        unset($where);
        $where['id_ticket'] = $id_ticket;

        $AqConexion_model->update('dietario_tickets', $ticket, $where);
    }

    //
    function ya_tiene_recibo($id_dietario)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT count(id_concepto) as numero
    FROM recibos_conceptos
    WHERE borrado = 0 and id_dietario = @id_dietario ";

        $parametros['id_dietario'] = $id_dietario;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos[0]['numero'] == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    //
    function en_ticket($id_dietario)
    {
        $AqConexion_model = new AqConexion_model();

        $sentencia_sql = "SELECT id_ticket
    FROM dietario
    WHERE borrado = 0 and id_dietario = @id_dietario ";

        $parametros['id_dietario'] = $id_dietario;
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos[0]['id_ticket'] == 0) {
            return 0;
        } else {
            return 1;
        }
    }

    //
    //
    //
    function crear_recibo($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if (isset($parametros['id_cliente'])) {
            if ($parametros['id_cliente'] > 0) {
                // ... Campos de la tabla
                $registros['id_cliente'] = $parametros['id_cliente'];
                $registros['id_centro'] = $parametros['id_centro_facturar'];
                $registros['fecha_emision'] = date("Y-m-d H:i:s");
                $registros['fecha_vto'] = date("Y-m-d H:i:s");
                $registros['numero_recibo'] = $this->SiguienteNumeroRecibo($parametros['id_centro_facturar']);
                $registros['estado'] = "";
                $registros['importe'] = 0;
                $registros['descuento'] = 0;
                $registros['iva'] = 0;
                $registros['irpf'] = 0;
                $registros['total'] = 0;
                //version
                if (isset($parametros['version'])) {
                    $registros['version'] = $parametros['version'];
                } else {
                    $registros['version'] = 0;
                }

                // ... Control de campos fijos, para que guarde un valor por defecto, sino se indica nada.
                if (isset($parametros['id_usuario_creacion'])) {
                    $registros['id_usuario_creacion'] = $parametros['id_usuario_creacion'];
                } else {
                    $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
                }

                if (isset($parametros['fecha_creacion'])) {
                    $registros['fecha_creacion'] = $parametros['fecha_creacion'];
                } else {
                    $registros['fecha_creacion'] = date("Y-m-d H:i:s");
                }

                if (isset($parametros['id_usuario_modificacion'])) {
                    $registros['id_usuario_modificacion'] = $parametros['id_usuario_modificacion'];
                } else {
                    $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                }

                if (isset($parametros['fecha_modificacion'])) {
                    $registros['fecha_modificacion'] = $parametros['fecha_modificacion'];
                } else {
                    $registros['fecha_modificacion'] = date("Y-m-d H:i:s");
                }

                if (isset($parametros['borrado'])) {
                    $registros['borrado'] = $parametros['borrado'];
                } else {
                    $registros['borrado'] = 0;
                }

                $AqConexion_model->insert('recibos', $registros);

                // ... Devuelve el id del registro generado.
                $sentencia_sql = "SELECT MAX(id_recibo) as id FROM recibos";
                $datos = $AqConexion_model->select($sentencia_sql, $parametros);

                return $datos[0]['id'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //
    //
    //
    function crear_recibo_concepto($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        if (isset($parametros['id_recibo'])) {
            if ($parametros['id_recibo'] > 0) {
                // ... Campos de la tabla
                $registros['id_recibo'] = $parametros['id_recibo'];
                $registros['id_dietario'] = $parametros['id_dietario'];
                $registros['descripcion'] = $parametros['descripcion'];
                $registros['importe'] = $parametros['importe'];
                $registros['descuento_euros'] = $parametros['descuento_euros'];
                $registros['descuento_porcentaje'] = $parametros['descuento_porcentaje'];
                $registros['iva'] = $parametros['iva'];
                $registros['iva_euros'] = $parametros['iva_euros'];
                $registros['irpf'] = 0;
                $registros['irpf_euros'] = 0;
                $registros['total'] = $parametros['total'];

                // ... Control de campos fijos, para que guarde un valor por defecto, sino se indica nada.
                if (isset($parametros['id_usuario_creacion'])) {
                    $registros['id_usuario_creacion'] = $parametros['id_usuario_creacion'];
                } else {
                    $registros['id_usuario_creacion'] = $this->session->userdata('id_usuario');
                }

                if (isset($parametros['fecha_creacion'])) {
                    $registros['fecha_creacion'] = $parametros['fecha_creacion'];
                } else {
                    $registros['fecha_creacion'] = date("Y-m-d H:i:s");
                }

                if (isset($parametros['id_usuario_modificacion'])) {
                    $registros['id_usuario_modificacion'] = $parametros['id_usuario_modificacion'];
                } else {
                    $registros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                }

                if (isset($parametros['fecha_modificacion'])) {
                    $registros['fecha_modificacion'] = $parametros['fecha_modificacion'];
                } else {
                    $registros['fecha_modificacion'] = date("Y-m-d H:i:s");
                }

                if (isset($parametros['borrado'])) {
                    $registros['borrado'] = $parametros['borrado'];
                } else {
                    $registros['borrado'] = 0;
                }

                $AqConexion_model->insert('recibos_conceptos', $registros);
                // resucitar registro dietario

                $id_dietario = $this->db->get_where('dietario', array('id_dietario' => $parametros['id_dietario']))->row();
                if ($id_dietario->borrado == 1) {
                    $data = ['borrado' => 0];
                    $this->db->where('id_dietario', $parametros['id_dietario']);
                    $this->db->update('dietario', $data);
                }

                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    //
    //
    function actualizar_recibo_importes($id_recibo)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_recibo'] = $id_recibo;

        // ... Leemos el actual numero de factura.
        $sentencia_sql = " SELECT ifnull(SUM(importe),0) as importe,
    ifnull(SUM(descuento_euros),0) as descuento,
    ifnull(SUM(iva_euros),0) as iva,
    ifnull(SUM(irpf_euros),0) as irpf,
    ifnull(SUM(total),0) as total
    FROM recibos_conceptos
    WHERE borrado = 0 and id_recibo = @id_recibo ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        if ($datos > 0) {
            unset($param);
            $param['id_recibo'] = $id_recibo;
            $param['importe'] = $datos[0]['importe'];
            $param['descuento'] = $datos[0]['descuento'];
            $param['iva'] = $datos[0]['iva'];
            $param['total'] = $datos[0]['total'];

            $sentenciaSQL = "update recibos set
      importe = @importe, descuento = @descuento, iva = @iva, total = @total
      where id_recibo = @id_recibo";
            $AqConexion_model->no_select($sentenciaSQL, $param);

            return 1;
        } else {
            return 0;
        }
    }

    /* Lee los Facturas en base a los parametros indicandos
  *
  * @id_factura int
  * @cadena_busqueda string
  *
  * Return hash_array con los datos
  *
  */
  function facturas($parametros)
  {
      $AqConexion_model = new AqConexion_model();

      $busqueda = "";

      if (isset($parametros['id_factura'])) {
          $busqueda .= " AND F.id_factura = @id_factura ";
      }

      if (isset($parametros['id_centro'])) {
          if ($parametros['id_centro'] > 1) {
              $busqueda .= " AND F.id_centro = @id_centro ";
          }
      }

      if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
          if ($parametros['fecha_desde'] != "") {
              $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
              $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

              $busqueda .= " AND F.fecha_emision >= @fecha_desde AND F.fecha_emision <= @fecha_hasta ";
          }
      }

      if (isset($parametros['buscar'])) {
          $parametros['buscar'] = ltrim($parametros['buscar']);
          $parametros['buscar'] = rtrim($parametros['buscar']);
          $parametros['buscar'] = str_replace(" ", "%", $parametros['buscar']);

          if ($parametros['buscar'] != "") {
              $busqueda .= " AND (
        CONCAT(IFNULL(clientes.nombre,''),' ',IFNULL(clientes.apellidos,''))
        like '%" . $parametros['buscar'] . "%'
      )";
          }
      }

      // ... Leemos los registros
      $sentencia_sql = "SELECT F.id_factura,F.fecha_emision,F.fecha_vto,F.id_centro,
  F.id_cliente,F.numero_factura,F.estado,F.importe,F.descuento,F.iva,F.irpf,F.total,
  round((F.descuento*100)/F.importe,2) as descuento_porcentaje,
  round((F.iva*100)/(F.importe),2) as iva_porcentaje,
  F.id_usuario_creacion,F.fecha_creacion,F.id_usuario_modificacion,
  F.fecha_modificacion,F.borrado,F.id_usuario_borrado,F.fecha_borrado,
  DATE_FORMAT(F.fecha_emision,'%Y-%m-%d') as fecha_emision_aaaammdd,
  DATE_FORMAT(F.fecha_emision,'%d-%m-%Y') as fecha_emision_ddmmaaaa,
  CONCAT(IFNULL(clientes.nombre,''), ' ', IFNULL(clientes.apellidos,'')) as cliente,
  centros.nombre_centro, F.version
  FROM facturas AS F
  LEFT JOIN clientes ON clientes.id_cliente = F.id_cliente
  LEFT JOIN centros ON centros.id_centro = F.id_centro
  WHERE F.borrado = 0 " . $busqueda . " ORDER BY F.id_factura DESC";
      $datos = $AqConexion_model->select($sentencia_sql, $parametros);

      return $datos;
  }


  function recibos($parametros)
  {
      $AqConexion_model = new AqConexion_model();

      $busqueda = "";

      if (isset($parametros['id_recibo'])) {
          $busqueda .= " AND F.id_recibo = @id_recibo ";
      }

      if (isset($parametros['id_centro'])) {
          if ($parametros['id_centro'] > 1) {
              $busqueda .= " AND F.id_centro = @id_centro ";
          }
      }

      if (isset($parametros['fecha_desde']) && isset($parametros['fecha_hasta'])) {
          if ($parametros['fecha_desde'] != "") {
              $parametros['fecha_desde'] = $parametros['fecha_desde'] . " 00:00:00";
              $parametros['fecha_hasta'] = $parametros['fecha_hasta'] . " 23:59:59";

              $busqueda .= " AND F.fecha_emision >= @fecha_desde AND F.fecha_emision <= @fecha_hasta ";
          }
      }

      if (isset($parametros['buscar'])) {
          $parametros['buscar'] = ltrim($parametros['buscar']);
          $parametros['buscar'] = rtrim($parametros['buscar']);
          $parametros['buscar'] = str_replace(" ", "%", $parametros['buscar']);

          if ($parametros['buscar'] != "") {
              $busqueda .= " AND (
        CONCAT(IFNULL(clientes.nombre,''),' ',IFNULL(clientes.apellidos,''))
        like '%" . $parametros['buscar'] . "%'
      )";
          }
      }

      // ... Leemos los registros
      $sentencia_sql = "SELECT F.id_recibo,F.fecha_emision,F.fecha_vto,F.id_centro,
  F.id_cliente,F.numero_recibo,F.estado,F.importe,F.descuento,F.iva,F.irpf,F.total,
  round((F.descuento*100)/F.importe,2) as descuento_porcentaje,
  round((F.iva*100)/(F.importe),2) as iva_porcentaje,
  F.id_usuario_creacion,F.fecha_creacion,F.id_usuario_modificacion,
  F.fecha_modificacion,F.borrado,F.id_usuario_borrado,F.fecha_borrado,
  DATE_FORMAT(F.fecha_emision,'%Y-%m-%d') as fecha_emision_aaaammdd,
  DATE_FORMAT(F.fecha_emision,'%d-%m-%Y') as fecha_emision_ddmmaaaa,
  CONCAT(IFNULL(clientes.nombre,''), ' ', IFNULL(clientes.apellidos,'')) as cliente,
  centros.nombre_centro, F.version
  FROM recibos AS F
  LEFT JOIN clientes ON clientes.id_cliente = F.id_cliente
  LEFT JOIN centros ON centros.id_centro = F.id_centro
  WHERE F.borrado = 0 " . $busqueda . " ORDER BY F.id_recibo DESC";
      $datos = $AqConexion_model->select($sentencia_sql, $parametros);

      return $datos;
  }

    /* Lee los Conceptos de una factura
  *
  * @id_factura int
  * @cadena_busqueda string
  *
  * Return hash_array con los datos
  *
  */
  function facturas_conceptos($parametros)
  {
      $AqConexion_model = new AqConexion_model();

      $busqueda = "";
      if (isset($parametros['id_factura'])) {
          $busqueda .= " AND F.id_factura = @id_factura ";
      }

      // ... Leemos los registros
      $sentencia_sql = "SELECT F.id_concepto,F.id_factura,F.id_dietario,F.descripcion,
  F.importe,F.iva,F.iva_euros,F.irpf,F.irpf_euros,F.descuento_euros,
  F.descuento_porcentaje,F.total,
  F.id_usuario_creacion,F.fecha_creacion,F.id_usuario_modificacion,
  F.fecha_modificacion,F.borrado,F.id_usuario_borrado,F.fecha_borrado,
  dietario.tipo_pago,dietario.id_presupuesto,
  DATE_FORMAT(dietario.fecha_hora_concepto,'%d-%m-%Y') as fecha_hora_concepto
  FROM facturas_conceptos AS F
  LEFT JOIN dietario on dietario.id_dietario = F.id_dietario
  WHERE F.borrado = 0 " . $busqueda . " ORDER BY F.fecha_creacion ";
      $datos = $AqConexion_model->select($sentencia_sql, $parametros);

      return $datos;
  }
  function recibo_conceptos($parametros)
  {
      $AqConexion_model = new AqConexion_model();

      $busqueda = "";
      if (isset($parametros['id_recibo'])) {
          $busqueda .= " AND F.id_recibo = @id_recibo ";
      }

      // ... Leemos los registros
      $sentencia_sql = "SELECT F.id_concepto,F.id_recibo,F.id_dietario,F.descripcion,
  F.importe,F.iva,F.iva_euros,F.irpf,F.irpf_euros,F.descuento_euros,
  F.descuento_porcentaje,F.total,
  F.id_usuario_creacion,F.fecha_creacion,F.id_usuario_modificacion,
  F.fecha_modificacion,F.borrado,F.id_usuario_borrado,F.fecha_borrado,
  dietario.tipo_pago,dietario.id_presupuesto,
  DATE_FORMAT(dietario.fecha_hora_concepto,'%d-%m-%Y') as fecha_hora_concepto
  FROM recibos_conceptos AS F
  LEFT JOIN dietario on dietario.id_dietario = F.id_dietario
  WHERE F.borrado = 0 " . $busqueda . " ORDER BY F.fecha_creacion ";
      $datos = $AqConexion_model->select($sentencia_sql, $parametros);

      return $datos;
  }
  function facturas_conceptos2($id_factura)
  {
      return $this->db->where('id_factura',$id_factura)->get('facturas_conceptos')->result_array();
  }

   //
    //
    function SiguienteNumeroRecibo($id_centro)
    {
        $AqConexion_model = new AqConexion_model();

        $parametros['id_centro'] = $id_centro;
        $sentencia_sql = "SELECT numero_recibo,codigo_recibo FROM centros
    WHERE borrado = 0 and id_centro = @id_centro";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        $numero_recibo = $datos[0]['numero_recibo'] + 1;

        $sentenciaSQL = "update centros set numero_recibo = numero_recibo + 1
    where id_centro = @id_centro";
        $AqConexion_model->no_select($sentenciaSQL, $parametros);

        if ($numero_recibo < 10) {
            $numero_recibo = "000" . $numero_recibo;
        }
        if ($numero_recibo > 9 && $numero_recibo < 100) {
            $numero_recibo = "00" . $numero_recibo;
        }
        if ($numero_recibo > 99 && $numero_recibo < 1000) {
            $numero_recibo = "0" . $numero_recibo;
        }

        $r = $datos[0]['codigo_recibo'] . "-" . $numero_recibo;

        return $r;
    }

    function factura_desglose_iva($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        if (isset($parametros['id_factura'])) {
            $busqueda .= " AND id_factura = @id_factura ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT iva,ifnull(SUM(iva_euros),0) as iva_suma
    FROM facturas_conceptos
    WHERE borrado = 0 " . $busqueda . "
    GROUP by iva";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }
    function recibo_desglose_iva($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";
        if (isset($parametros['id_recibo'])) {
            $busqueda .= " AND id_recibo = @id_recibo ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT iva,ifnull(SUM(iva_euros),0) as iva_suma
    FROM recibos_conceptos
    WHERE borrado = 0 " . $busqueda . "
    GROUP by iva";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }
    // ... Creamos el PDF del recibo.
    function CrearPDF_recibo($id_recibo = null)
    {
        // ... Leemos los datos de la factura.
        $param['id_recibo'] = $id_recibo;
        $data['recibo'] = $this->recibos($param);
        $data['conceptos'] = $this->recibo_conceptos($param);
        
        //show_array($data['conceptos']);exit;
        $data['ivas_desglose'] = $this->recibo_desglose_iva($param);
        // recorrer los conceptos. Si el id_presupuesto es mayor que 0
        $data['desglose_presupuestos'] = [];
        foreach ($data['conceptos'] as $key => $concepto) {
            if($concepto['id_presupuesto'] > 0){
                    // se busca el presupuesto para obtener el nro_presupuesto
                $this->load->model('Presupuestos_model');
                $presupuesto = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $concepto['id_presupuesto']]);
                $items_presupuesto = $this->Presupuestos_model->leer_presupuestos_items(['aceptado' => 1, 'id_presupuesto' => $concepto['id_presupuesto']]);
                $data['desglose_presupuestos'][$presupuesto[0]['nro_presupuesto']] = $items_presupuesto;
            }
        }
        


        //show_array($data['conceptos']);
        //show_array($data['desglose_presupuestos']);exit;



        // ... Leemos los datos del centro.
        unset($param);
        $param['id_centro'] = $data['recibo'][0]['id_centro'];
        $data['centro'] = $this->Usuarios_model->leer_centros($param);

        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $data['recibo'][0]['id_cliente'];
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        // ... Viewer con el código para generar el HTML
        $content_view = $this->load->view('facturas/recibos_plantilla_view', $data, true);
        
        //echo $content_view; exit;

        // ... Nombre del fichero de la factura
        $numero_recibo = $data['recibo'][0]['numero_recibo'];

        if ($data['recibo'][0]['numero_recibo'] != "") {
            $this->load->library('pdf');
            $set_paper = array(0, 0, 250, 1500);
            $set_option = ['dpi' => 72, 'default_font' => 'Courier'];
            $this->pdf->stream($content_view, $numero_recibo . ".pdf", array("Attachment" => false), '', '');
            // sistema antiguo
            // require_once(RUTA_SERVIDOR."/recursos/motorPDF/dompdf_config.inc.php");
            // $dompdf = new DOMPDF();
            // $dompdf->load_html($content_view);
            // $dompdf->render();
            // $dompdf->stream($numero_factura.".pdf",array("Attachment" => false)); // ... esto lanza el PDF.   
        }
    }

    // ... Creamos el PDF de la factura.
    function CrearPDF($id_factura = null)
    {
        // ... Leemos los datos de la factura.
        $param['id_factura'] = $id_factura;
        $data['factura'] = $this->facturas($param);
        if($data['factura'][0]['version']==2){
            $data['conceptos'] = $this->facturas_conceptos2($data['factura'][0]['id_factura']);
        }else{
            $data['conceptos'] = $this->facturas_conceptos($param);
        }

        //show_array($data['conceptos']);exit;
        $data['ivas_desglose'] = $this->factura_desglose_iva($param);
        // recorrer los conceptos. Si el id_presupuesto es mayor que 0
        $data['desglose_presupuestos'] = [];
        //proceso normal para facturas version = 0'
        if($data['factura'][0]['version']!=2){
            foreach ($data['conceptos'] as $key => $concepto) {
                if($concepto['id_presupuesto'] > 0){
                     // se busca el presupuesto para obtener el nro_presupuesto
                    $this->load->model('Presupuestos_model');
                    $presupuesto = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $concepto['id_presupuesto']]);
                    $items_presupuesto = $this->Presupuestos_model->leer_presupuestos_items(['aceptado' => 1, 'id_presupuesto' => $concepto['id_presupuesto']]);
                    $data['desglose_presupuestos'][$presupuesto[0]['nro_presupuesto']] = $items_presupuesto;
                }
            }
        }


        //show_array($data['conceptos']);
        //show_array($data['desglose_presupuestos']);exit;



        // ... Leemos los datos del centro.
        unset($param);
        $param['id_centro'] = $data['factura'][0]['id_centro'];
        $data['centro'] = $this->Usuarios_model->leer_centros($param);

        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $data['factura'][0]['id_cliente'];
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        // ... Viewer con el código para generar el HTML
        //$this->load->view('facturas/facturas_plantilla_view2', $data);exit;
        if($data['factura'][0]['version']!=2){
            $content_view = $this->load->view('facturas/facturas_plantilla_view', $data, true);
        }elseif($data['factura'][0]['version']==2){
            $content_view = $this->load->view('facturas/facturas_plantilla_view2', $data, true);
        }
        //echo $content_view; exit;

        // ... Nombre del fichero de la factura
        $numero_factura = $data['factura'][0]['numero_factura'];

        if ($data['factura'][0]['numero_factura'] != "") {
            $this->load->library('pdf');
            $set_paper = array(0, 0, 250, 1500);
            $set_option = ['dpi' => 72, 'default_font' => 'Courier'];
            $this->pdf->stream($content_view, $numero_factura . ".pdf", array("Attachment" => false), '', '');
            // sistema antiguo
            // require_once(RUTA_SERVIDOR."/recursos/motorPDF/dompdf_config.inc.php");
            // $dompdf = new DOMPDF();
            // $dompdf->load_html($content_view);
            // $dompdf->render();
            // $dompdf->stream($numero_factura.".pdf",array("Attachment" => false)); // ... esto lanza el PDF.   
        }
    }

    function realizar_pago_a_cuenta($id_cliente, $importe, $tipo_pago, $vpost, $estado)
    {
        $AqConexion_model = new AqConexion_model();

        $registro['id_cliente'] = $id_cliente;
        $registro['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $registro['fecha_pagado'] = date("Y-m-d H:i:s");
        $registro['id_empleado'] = $this->session->userdata('id_usuario');
        $registro['importe_euros'] = $importe;
        $registro['tipo_pago'] = $tipo_pago;
        $registro['estado'] = $estado;
        $registro['id_centro'] = $this->session->userdata('id_centro_usuario');
        $registro[$vpost] = $importe;
        
        //
        $registro['id_servicio'] = 0;
        $registro['id_producto'] = 0;
        $registro['id_carnet'] = 0;
        $registro['templos'] = 0;
        $registro['pago_a_cuenta'] = 1;
        $registro['fecha_creacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_creador'] = $this->session->userdata('id_usuario');
        $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
        $registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $registro['borrado'] = 0;

        $AqConexion_model->insert('dietario', $registro);

        $sentenciaSQL = "select max(id_dietario) as id from dietario";
        $resultado = $AqConexion_model->select($sentenciaSQL, null);
		
		$id_dietario = $resultado[0]['id'];
        $this->EvaluacionesGoogle_model->dietarioPagado($id_dietario);

        return $resultado[0]['id'];
    }

    function dietario_json($parametros)
    {
        $AqConexion_model = new AqConexion_model();

        $busqueda = "";

        if (isset($parametros['q'])) {
            $busqueda .= " AND CONCAT(TRIM(usuarios.nombre), ' ', TRIM(usuarios.apellidos), ' ', TRIM(usuarios.telefono)) like '%" . $parametros['q'] . "%' ";
        }

        // ... Leemos los registros
        $sentencia_sql = "SELECT usuarios.id_usuario as id,
    CONCAT(usuarios.nombre, ' ', usuarios.apellidos, ' (', usuarios.telefono, ')') as name
    FROM usuarios
    WHERE usuarios.borrado = 0 " . $busqueda . " ORDER BY nombre,apellidos ";
        $datos = $AqConexion_model->select($sentencia_sql, $parametros);

        return $datos;
    }

    function verificar_conceptos_factura($conceptos){
        $ids = Array();
        foreach($conceptos as $c){ $ids[]=$c['id_dietario'];}
        $dietario = $this->db->where_in('id_dietario',$ids)->get('dietario')->result();
        $contador = 0 ;
        foreach($dietario as $d)if($d->facturado==$d->importe_euros){ $contador=1; }
        return $contador;
    }
    function crear_factura_new($data){
        $centro = $this->db->where('id_centro',$data['id_centro'])->get('centros')->row();
        $folio = $centro->codigo_factura."-".str_pad($centro->numero_factura+1, 4, '0', STR_PAD_LEFT);
        $fecha = date("Y-m-d H:i:s");
        $array_factura = Array(
            "fecha_emision"=>$fecha,
            "fecha_vto"=>$fecha,
            "estado"=>'',
            "borrado"=>0,
            "id_cliente"=>$data['id_cliente'],
            "id_centro"=>$data['id_centro'],
            "numero_factura"=>$folio,
            "importe"=>$data['importe'],
            "descuento"=>$data['descuento'],
            "iva"=>$data['iva'],
            "irpf"=>$data['irpf'],
            "total"=>$data['total'],
            "id_usuario_creacion"=>$data['id_usuario_creacion'],
            "fecha_creacion"=>$fecha,
            "id_usuario_modificacion"=>$data['id_usuario_modificacion'],
            "fecha_modificacion"=>$fecha,
            "version"=>2
        );
        $this->db->insert("facturas",$array_factura);
        $id_factura = $this->db->insert_id();
        //insetamos los conceptos de la factura
        $conceptos_temp = $data['conceptos'];
        $insert_concepto=Array();
        foreach($data['conceptos'] as &$c){
            $c['id_factura'] = $id_factura;
            $c['fecha_creacion'] = $fecha;
            $c['fecha_modificacion'] = $fecha;
            unset($c['id_dietario']);
            $insert_concepto[]=$c;
        }

        //show_array($insert_concepto); exit;
        $this->db->insert_batch("facturas_conceptos",$insert_concepto);
        //sumamos uno al numero de factura
        $this->db->where('id_centro',$data['id_centro'])->update("centros",Array("numero_factura"=>$centro->numero_factura+1));
        //modificamos la columna de facturado del dietario
        $update_array=Array();
        foreach($conceptos_temp as $ct){
            $update_array[]=Array(
                "id_dietario"=>$ct['id_dietario'],
                "facturado"=>$ct["total"]
            );
        }
        
        $this->db->update_batch('dietario',$update_array,'id_dietario');

    }


    function getDientesForDietario($ids){
        
        $AqConexion_model = new AqConexion_model();
        
        $sql="SELECT id_dietario,dientes FROM presupuestos_items WHERE id_dietario IN (".implode(",",array_keys($ids)).")";
        $datos = $AqConexion_model->select($sql, null);
        if(is_array($datos)) {
            foreach ($datos as $dato) {
                $ids[$dato['id_dietario']] = $dato['dientes'];
            }
        }
        
        $sql="SELECT id_dietario,dientes FROM dietario WHERE id_dietario IN (".implode(",",array_keys($ids)).")";
        $datos = $AqConexion_model->select($sql, null);
        if(is_array($datos)) {
            foreach ($datos as $dato) {
                if ( empty($ids[$dato['id_dietario']]) ){
                  $ids[$dato['id_dietario']] = $dato['dientes'];
                }
            }
        }
        
        return $ids;
    }


    function actualizar_comFinanciacion($iddietario,$com){
        $AqConexion_model = new AqConexion_model();
        $where=" id_dietario =".$iddietario;
        $AqConexion_model->update('dietario', ['comisionfinanciacion'=>$com], $where);
    }
    function crearDietarioFicticio($data){
        $AqConexion_model = new AqConexion_model();
        $AqConexion_model->insert('dietario', $data);
        $str = $this->db->insert_id();
        return $str;
    }
}
