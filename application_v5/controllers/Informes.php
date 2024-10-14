<?php
class Informes extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('Informes_model');
    }


    //27/12/20
    //Inform Diario d pagos con tarjetas para gestoría
    // https://extranet.templodelmasaje.com/informes/proceso_centros_gestoria/5c8e15faec49a66870b949314ac062d0
    function proceso_centros_gestoria($rand = null)
    {
        if ($rand == "5c8e15faec49a66870b949314ac062d0") {
            $centros = $this->Usuarios_model->leer_centros('');

            $fecha = date("Y-m-d");
            foreach ($centros as $row) {
                $this->diario_tarjeta($fecha, $row['id_centro'], $rand);
            }

            echo "OK";
        } else {
            echo "Error";
        }

        exit;
    }

    public function diario_tarjeta($fecha = null, $id_centro = null, $rand = null)
    {
        if ($rand == "5c8e15faec49a66870b949314ac062d0" && $id_centro > 0 && $fecha != "") {
            unset($param);
            $param['id_centro'] = $id_centro;
            $data['centro'] = $this->Usuarios_model->leer_centros($param);
            echo "centro: " . $data['centro'][0]['nombre_centro'] . " " . $data['centro'][0]['email_gestoria'] . "<br>";
            $data['fecha_dia'] = date("d-m-Y", strtotime($fecha));

            //
            // Pago solo con tarjeta.
            //
            if ($data['centro'][0]['habilitado_gestoria'] == "SI" and $data['centro'][0]['email_gestoria'] != "") {
                //echo "<br> Paso";
                unset($parametros);
                $parametros['tipo_pago'] = "tarjeta";
                $parametros['fecha_inicio'] = $fecha;
                $parametros['fecha_fin'] = $fecha;
                $parametros['id_centro'] = $id_centro;
                $parametros['web_clientes'] = "no";
                $data['tarjetas'] = $this->Dietario_model->leer($parametros);

                unset($parametros);
                $parametros['tipo_pago'] = "tarjeta";
                $parametros['fecha_inicio'] = $fecha;
                $parametros['fecha_fin'] = $fecha;
                $parametros['id_centro'] = $id_centro;
                $parametros['web_clientes'] = "si";
                $data['tarjetas_web'] = $this->Dietario_model->leer($parametros);

                //var_dump($data['tarjetas']);
                //$this->load->view('informe_gestoria_email_view', $data);

                //
                // Generar contenido del email
                //
                $mensaje = $this->load->view('emails/informe_gestoria_email_view', $data, true);
                $from = "info@templodelmasaje.com";
                $asunto = "Informe Diario de Tarjetas";
                $emails_to_centro = explode(",", $data['centro'][0]['email_gestoria']);

                if (count($emails_to_centro) > 0) {
                    foreach ($emails_to_centro as $to) {
                        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                            $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                        }
                    }
                }
            }
        }
    }
    //Fin Gestoria

    // https://desarrollo.templodelmasaje.com/informes/proceso_centros/5c8e15faec49a66870b949314ac062d0
    function proceso_centros($rand = null)
    {
        if ($rand == "5c8e15faec49a66870b949314ac062d0") {
            $centros = $this->Usuarios_model->leer_centros('');

            $fecha = date("Y-m-d");
            foreach ($centros as $row) {
                $this->diario($fecha, $row['id_centro'], $rand);
            }

            echo "OK";
        } else {
            echo "Error";
        }

        exit;
    }

    //17/02/21 Provionsal para ver recargas repetdas del mismo cliente, mismo día
    public function provisional($id_centro = '', $fecha = '')
    {
        //https://extranet.templodelmasaje.com/informes/provisional
        //$fecha=date("Y-m-d",$fecha);
        //$id_centro=4;

        unset($parametros);

        $parametros['fecha_inicio'] = $fecha;
        $parametros['fecha_fin'] = $fecha;
        $parametros['id_centro'] = $id_centro;
        echo "Si " . $fecha . " " . $id_centro;
        $data['recargas_carnets'] = $this->Carnets_model->leer_carnets_recarga($parametros);
        //var_dump($data['recargas_carnets']);
        //Fecha_hora, nombre cliente, id_carnet, monto
        $i = 1;
        $c = 0;
        foreach ($data['recargas_carnets'] as $row) {
            if ($i == 1) {
                $xid_cliente = $row['id_cliente'];
                $xmonto = $row['monto'];
            }
            echo "<br>" . $row['fecha_creacion_ddmmaaaa'] . " " . $row['hora'] . " " . $row['id_carnet'] . " " . $row['cliente'] . " " . $row['monto'];
            echo "<br> I " . $i . " xid_cliente: " . $xid_cliente . " xmonto " . $xmonto;
            if ($xid_cliente == $row['id_cliente'] and $i > 1) {
                //Si es el mismo monto para el mismo cliente debe mostrarlo
                echo "<br> IF 1";
                if ($xmonto == $row['monto']) {
                    echo "<br> C: " . $c;
                    $cliente_mas_ecarga[$c] = array(
                        "fecha_hora" => $row['fecha_creacion_ddmmaaaa'] . " " . $row['hora'],
                        "id_carnet" => $row['id_carnet'],
                        "cliente" => $row['cliente'],
                        "monto" => $row['monto']
                    );
                    $c++;
                }
            }
            $xid_cliente = $row['id_cliente'];
            $xmonto = $row['monto'];
            $i++;
        } //Foreach

        if (isset($cliente_mas_ecarga)) {
            echo "<br> dos o más Recargas mismo monto ";
            foreach ($cliente_mas_ecarga as $row) {
                echo "<br>" . $row['fecha_hora'] . " " . $row['id_carnet'] . " " . $row['cliente'] . " " . $row['monto'];
            }
        }
    }
    //Fin del provisional

    public function diario($fecha = null, $id_centro = null, $rand = null, $ver = '')
    {
        if ($rand == "5c8e15faec49a66870b949314ac062d0" && $id_centro > 0 && $fecha != "") {
            unset($param);
            $param['id_centro'] = $id_centro;
            $data['centro'] = $this->Usuarios_model->leer_centros($param);
            $data['fecha_dia'] = date("d-m-Y", strtotime($fecha));
            $this->load->model('Presupuestos_model');

            //FACTURACION DIARIA
            $data['id_centro'] = $id_centro;
            // Leemos facturacion de la Mañana
            unset($param);
            $param['fecha_desde'] = $fecha . " 00:00:00";
            $param['fecha_hasta'] = $fecha . " 15:59:59";
            $param['id_centro'] = $id_centro;
            $data['facturacion_manana'] = $this->Estadisticas_model->usuarios_debug($param);

            $data['total_efectivo_manana'] = 0;
            $data['total_tarjeta_manana'] = 0;
            $data['total_transferencia_manana'] = 0; //24/03/20
            $data['total_tpv2_manana'] = 0; //05/03/23
            $data['total_financiado_manana'] = 0;
            $data['total_paypal_manana'] = 0; //05/03/23
            $data['total_habitacion_manana'] = 0;
            $data['total_manana'] = 0;
            if (is_array($data['facturacion_manana'])) {
                foreach ($data['facturacion_manana'] as $key => $row) {
                    $data['total_efectivo_manana'] += $row['ventas_efectivo'];
                    $data['total_tarjeta_manana'] += $row['ventas_tarjeta'];
                    $data['total_transferencia_manana'] += $row['ventas_transferencia']; //24/03/20
                    $data['total_tpv2_manana'] += $row['ventas_tpv2']; //05/03/23
                    $data['total_financiado_manana'] += $row['ventas_financiado']; //24/03/20
                    $data['total_paypal_manana'] += $row['ventas_paypal']; //05/03/23
                    $data['total_habitacion_manana'] += $row['ventas_habitacion'];
                    $data['total_manana'] += $row['ventas'];
                }
            }

            // Leemos facturacion de la Tarde
            unset($param);
            $param['fecha_desde'] = $fecha . " 16:00:00";
            $param['fecha_hasta'] = $fecha . " 23:59:59";
            $param['id_centro'] = $data['id_centro'];
            $data['facturacion_tarde'] = $this->Estadisticas_model->usuarios_debug($param);

            $data['total_efectivo_tarde'] = 0;
            $data['total_tarjeta_tarde'] = 0;
            $data['total_transferencia_tarde'] = 0; //24/03/20
            $data['total_tpv2_tarde'] = 0; //05/03/23
            $data['total_financiado_tarde'] = 0;
            $data['total_paypal_tarde'] = 0; //05/03/23
            $data['total_habitacion_tarde'] = 0;
            $data['total_tarde'] = 0;
            if (is_array($data['facturacion_tarde'])) {
                foreach ($data['facturacion_tarde'] as $key => $row) {
                    $data['total_efectivo_tarde'] += $row['ventas_efectivo'];
                    $data['total_tarjeta_tarde'] += $row['ventas_tarjeta'];
                    $data['total_transferencia_tarde'] += $row['ventas_transferencia']; //24/03/20
                    $data['total_tpv2_tarde'] += $row['ventas_tpv2']; //05/03/23
                    $data['total_financiado_tarde'] += $row['ventas_financiado']; //24/03/20
                    $data['total_paypal_tarde'] += $row['ventas_paypal']; //05/03/23
                    $data['total_habitacion_tarde'] += $row['ventas_habitacion'];
                    $data['total_tarde'] += $row['ventas'];
                }
            }

            // ... Totales de facturacion.
            $data['total_facturacion_efectivo'] = $data['total_efectivo_manana'] + $data['total_efectivo_tarde'];
            $data['total_facturacion_tarjeta'] = $data['total_tarjeta_manana'] + $data['total_tarjeta_tarde'];
            $data['total_facturacion_transferencia'] = $data['total_transferencia_manana'] + $data['total_transferencia_tarde']; //24/03/20
            $data['total_facturacion_tpv2'] = $data['total_tpv2_manana'] + $data['total_tpv2_tarde']; //05/03/23
            $data['total_facturacion_financiado'] = $data['total_financiado_manana'] + $data['total_financiado_tarde'];
            $data['total_facturacion_paypal'] = $data['total_paypal_manana'] + $data['total_paypal_tarde']; //05/03/23
            $data['total_facturacion_habitacion'] = $data['total_habitacion_manana'] + $data['total_habitacion_tarde'];
            $data['total_facturacion'] = $data['total_manana'] + $data['total_tarde'];


             // ... Totales de cierre de caja
             unset($param);
             $param['fecha_desde'] = $fecha . " 00:00:00";
             $param['fecha_hasta'] = $fecha . " 15:59:59";
             $param['id_centro'] = $data['id_centro'];
             $data['cierres_manana'] = $this->Caja_model->leer_cierres($param);
 
             $data['total_cierre_manana'] = 0;
             if ($data['cierres_manana'] != 0) {
                 //foreach ($data['cierres_manana'] as $key => $row) {
                 //$data['total_cierre_manana']+=$row['descuadre_efectivo']+$row['descuadre_tarjeta']+$row['descuadre_habitacion'];
                 //}
                 $row = $data['cierres_manana'][0];
                 $data['total_cierre_manana'] += $row['descuadre_efectivo'] + $row['descuadre_tarjeta'] + $row['descuadre_habitacion'] + $row['descuadre_transferencia'];
             }
 
             unset($param);
             $param['fecha_desde'] = $fecha . " 16:00:00";
             $param['fecha_hasta'] = $fecha . " 23:59:59";
             $param['id_centro'] = $data['id_centro'];
             $data['cierres_tarde'] = $this->Caja_model->leer_cierres($param);
 
             $data['total_cierre_tarde'] = 0;
             if ($data['cierres_tarde'] != 0) {
                 //foreach ($data['cierres_tarde'] as $key => $row) {
                 //$data['total_cierre_tarde']+=$row['descuadre_efectivo']+$row['descuadre_tarjeta']+$row['descuadre_habitacion'];
                 //}
                 $row = $data['cierres_tarde'][0];
                 $data['total_cierre_tarde'] += $row['descuadre_efectivo'] + $row['descuadre_tarjeta'] + $row['descuadre_habitacion'] + $row['descuadre_transferencia'];
             }

            //FACTURACION MES
            $fecha_ddmmaaaa = date('d-m-Y', strtotime($fecha));

            $data['mes_facturacion_manana'] = 0;
            $data['mes_facturacion_tarde'] = 0;
            $data['mes_facturacion_total'] = 0;

            unset($param);
            $param['mes'] = $fecha;
            $param['id_centro'] = $data['id_centro'];
            $facturacion_mes = $this->Estadisticas_model->usuarios_debug($param);
            foreach ($facturacion_mes as $key => $value) {
                $data['mes_facturacion_total'] += $value['ventas'];
            }
            
            $param['jornada'] = 1;
            $facturacion_mes_manana = $this->Estadisticas_model->usuarios_debug($param);
            foreach ($facturacion_mes_manana as $key => $value) {
                $data['mes_facturacion_manana'] += $value['ventas'];
            }

            $param['jornada'] = 2;
            $facturacion_mes_tarde = $this->Estadisticas_model->usuarios_debug($param);
            foreach ($facturacion_mes_tarde as $key => $value) {
                $data['mes_facturacion_tarde'] += $value['ventas'];
            }
            


            //
            // Líneas de dietario con Descuentos
            //
            unset($parametros);
            $parametros['lineas_con_descuento'] = 1;
            $parametros['fecha_inicio'] = $fecha;
            $parametros['fecha_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $data['lineas_con_descuento'] = $this->Dietario_model->leer($parametros);

            //
            // Líneas de dietario con Devoluciones
            //
            unset($parametros);
            $parametros['estado'] = "Devuelto";
            $parametros['fecha_inicio'] = $fecha;
            $parametros['fecha_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $data['devoluciones'] = $this->Dietario_model->leer($parametros);

            //
            // Movimientos de caja
            //
            unset($parametros);
            $parametros['fecha_desde'] = $fecha;
            $parametros['fecha_hasta'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $data['movimientos_caja'] = $this->Caja_model->leer_caja_movimientos($parametros);

            //
            // Cierres de caja con descuadres
            //
            unset($parametros);
            $parametros['descuadres_caja'] = 1;
            $parametros['fecha'] = $fecha;
            $parametros['id_centro'] = $id_centro;

            $data['descuadres_caja'] = $this->Caja_model->leer_cierres($parametros);

            // 
            // Citas sin presupuesto
            // 
            unset($parametros);
            $parametros['id_presupuesto'] = 0;
            $parametros['fecha_inicio'] = $fecha;
            $parametros['fecha_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $parametros['solo_servicios'] = 1;
            $lineas_sin_presupuesto_todas = $this->Dietario_model->leer($parametros);
            $lineas_sin_presupuesto = [];
            if(is_array($lineas_sin_presupuesto_todas)){
                $paramitem = [];
                foreach ($lineas_sin_presupuesto_todas as $key => $value) {
                    // buscar algun items del presupuesto del cliente que coincida el id_item con el id_servicio
                    // si se encuentra, que retorne el nro del presupuesto

                    $excluir = [15460, 15404, 15589, 4588, 4627, 4911, 4932, 4940, 5012, 10408, 15527, 15580, 15581, 15582];
                    if(!in_array($value['id_servicio'], $excluir)){
                        $excluir_string = 'revision';
                        if(strpos($value['servicio_completo'], $excluir_string) === false){
                            $lineas_sin_presupuesto[$key] = $lineas_sin_presupuesto_todas[$key];
                            unset($paramitem);
                            $paramitem['id_cliente'] = $value['id_cliente'];
                            $paramitem['id_item'] = $value['id_servicio'];
                            $paramitem['citas'] = 1;
                            $paramitem['aceptado'] = 1;
                            $existeitem = $this->Presupuestos_model->leer_presupuestos_items($paramitem);
                            if(!empty($existeitem)){
                                // se busca el presupuesto
                                $paramitem2['id_presupuesto'] = $existeitem[0]['id_presupuesto'];
                                $presupuesto = $this->Presupuestos_model->leer_presupuestos($paramitem2);
                                if(is_array($presupuesto)){
                                    $lineas_sin_presupuesto[$key]['nro_presupuesto'] = $presupuesto[0]['nro_presupuesto'];
                                }
                            }
                        }
                    }
                }
            }
            $data['lineas_sin_presupuesto'] = $lineas_sin_presupuesto;

            // 
            // Presupuestos creados
            // 
            unset($parametros);
            $parametros['id_presupuesto'] = 0;
            $parametros['fecha_creacion_inicio'] = $fecha;
            $parametros['fecha_creacion_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $presupuestos = $this->Presupuestos_model->leer_presupuestos($parametros);
            $nro_pres_creados = 0;
            $valor_pres_creados = 0;
            $nro_pres_repet = 0;
            $valor_pres_repet = 0;
            if(is_array($presupuestos)){
                foreach ($presupuestos as $key => $value) {
                    if($value['es_repeticio'] == 0){
                        $nro_pres_creados++;
                        $valor_pres_creados += $value['totalpresupuesto'];
                    }else{
                        $nro_pres_repet++;
                        $valor_pres_repet += $value['totalpresupuesto'];
                    }
                }
            }
            $data['nro_pres_creados'] = $nro_pres_creados;
            $data['valor_pres_creados'] = $valor_pres_creados;
            $data['nro_pres_repet'] = $nro_pres_repet;
            $data['valor_pres_repet'] = $valor_pres_repet;

            // 
            // presupuestos aceptados
            // 
            unset($parametros);
            $parametros['fecha_aceptado_inicio'] = $fecha;
            $parametros['fecha_aceptado_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $presupuestos_aceptados = $this->Presupuestos_model->leer_presupuesto_aceptados($parametros);
            //printr($this->db->last_query());
            $nro_pres_aceptados = 0;
            $valor_pres_aceptados = 0;
            if(is_array($presupuestos_aceptados)){
                foreach ($presupuestos_aceptados as $key => $value) {
                    $nro_pres_aceptados++;
                    $valor_pres_aceptados += $value['total_aceptado'];
                }
            }
            $data['nro_pres_aceptados'] = $nro_pres_aceptados;
            $data['valor_pres_aceptados'] = $valor_pres_aceptados;
            
            $data['presupuestos_aseguradoras'] = array();
            foreach ( $presupuestos_aceptados as $i => $presupuesto ){
                if ( $presupuesto['id_aseguradora'] ){
                    $presupuesto['documentos_seguro'] = $this->Aseguradoras_model->documentos_seguro( array('id_presupuesto' => $presupuesto['id_presupuesto']) );
                    $data['presupuestos_aseguradoras'][] = $presupuesto;
                }
            }

            // 
            // Producción en base a las citas
            // 
            unset($parametros);
            $parametros['fecha'] = $fecha;
            $parametros['id_centro'] = $id_centro;
            $citas_produccion = $this->Estadisticas_model->produccion_citas($parametros);
            $nro_citas_produccion = 0;
            $pvp_citas_produccion = 0;
            $dto_citas_produccion = 0;
            $cobrado_citas_produccion = 0;
            $doctores = [];
            if(is_array($citas_produccion)){
                foreach ($citas_produccion as $key => $value) {
                    $nro_citas_produccion++;
                    $pvp_citas_produccion += $value['pvp'];
                    $dto_citas_produccion += $value['descontado'];
                    $cobrado_citas_produccion += $value['importe_euros'];
                    $doctores[$value['id_usuario_empleado']]['empleado'] = $value['empleado'];
                    $doctores[$value['id_usuario_empleado']]['citas'][] = $value['importe_euros'];
                    if(!array_key_exists('pacientes', $doctores[$value['id_usuario_empleado']])){
                        $doctores[$value['id_usuario_empleado']]['pacientes'] = [];
                    }
                    if(!in_array($value['id_cliente'], $doctores[$value['id_usuario_empleado']]['pacientes'])){
                        $doctores[$value['id_usuario_empleado']]['pacientes'][] = $value['id_cliente'];
                    }
                }
            }
            $data['citas_produccion'] = $citas_produccion;
            $data['nro_citas_produccion'] = $nro_citas_produccion;
            $data['pvp_citas_produccion'] = $pvp_citas_produccion;
            $data['dto_citas_produccion'] = $dto_citas_produccion;
            $data['cobrado_citas_produccion'] = $cobrado_citas_produccion;
            $data['doctores'] = $doctores;




            //
            // Servicios pagados con carnet de templos de otro cliente al atendido.
            //
            unset($parametros);
            $parametros['servicios_otros_carnets'] = 1;
            $parametros['fecha_inicio'] = $fecha;
            $parametros['fecha_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;

            $data['servicios_otros_carnets'] = $this->Dietario_model->leer($parametros);

            if ($data['servicios_otros_carnets'] != 0) {
                for ($i = 0; $i < count($data['servicios_otros_carnets']); $i++) {
                    $cliente = $this->Informes_model->propietario_carnet($data['servicios_otros_carnets'][$i]['id_dietario']);
                    $data['servicios_otros_carnets'][$i]['propietario_carnet'] = $cliente;
                }
            }

            //
            // Citas anuladas o no vino
            //
            unset($parametros);
            $parametros['anuladas_no_vino'] = 1;
            $parametros['fecha_inicio'] = $fecha;
            $parametros['fecha_fin'] = $fecha;
            $parametros['id_centro'] = $id_centro;

            $data['anuladas_no_vino'] = $this->Dietario_model->leer($parametros);

            //
            // Registros en dietario con notas de cobro pendientes
            //
            unset($parametros);
            $parametros['fecha_desde'] = $fecha . " 00:00:00";
            $parametros['fecha_hasta'] = $fecha . " 23:59:59";
            $parametros['id_centro'] = $id_centro;
            $data['caja_notas_pago'] = $this->Informes_model->caja_notas_pago($parametros);

            
            if($ver != ''){
                $this->load->view('emails/informe_diario_email_view', $data);
            }else{
                //
                // Generar contenido del email
                //
                $mensaje = $this->load->view('emails/informe_diario_email_view', $data, true);
                $from = "diario@clinicadentalnobel.es";
                $asunto = "Informe Diario";
                $emails_to_centro = ($data['centro'][0]['emails_informe_diario'] != '')? explode(",", $data['centro'][0]['emails_informe_diario']) : [];
                if (count($emails_to_centro) > 0) {
                    foreach ($emails_to_centro as $to) {
                        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                            $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                        }
                    }
                }
            }
        }
    }

    function pruebas()
    {
        $parametros['fecha_desde'] = "2019-05-03 00:00:00";
        $parametros['fecha_hasta'] = "2019-06-24 23:59:59";
        //$parametros['id_centro']=$id_centro;

        $data['con_notas_pago'] = $this->Informes_model->caja_notas_pago($parametros);
        echo '<pre>';
        print_r($data['con_notas_pago']);
        exit();
    }

    // https://desarrollo.templodelmasaje.com/informes/proceso_abandonos/dde51e21732f2232787dc3823499e88c
    function proceso_abandonos($rand = null)
    {
        if ($rand == "dde51e21732f2232787dc3823499e88c") {
            $fecha_actual = date('Y-m-d');
            $fecha_ayer = date('Y-m-d', strtotime('-1 day', strtotime($fecha_actual)));
            $data['pedidos'] = $this->Informes_model->pedidos_abandonados($fecha_ayer);

            // ... Si hay pedidos abandonados, manda un email
            if ($data['pedidos'] != 0) {
                //
                // Generar contenido del email
                //
                $data['fecha'] = date('d-m-Y', strtotime('-1 day', strtotime($fecha_actual)));;
                $mensaje = $this->load->view('emails/informe_pedidos_abandonados_view', $data, true);
                $from = "info@templodelmasaje.com";
                $asunto = "Citas Online Abandonadas";
                $to = "abandonos@templodelmasaje.com";

                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                }
            }

            echo "OK";
        } else {
            echo "Error";
        }

        exit;
    }

    // https://desarrollo.templodelmasaje.com/informes/proceso_abandonos_clientes/dde51e21732f2232787yu3823499e88c
    function proceso_abandonos_clientes($rand = null)
    {
        if ($rand == "dde51e21732f2232787yu3823499e88c") {
            $id_cliente_anterior = 0;

            // ... En primer lugar, leo todos los clientes distintos que tienen alguna cita abandonada en el día actual.
            $fecha = date('Y-m-d H:m:s');
            $clientes = $this->Informes_model->clientes_citas_abandonadas($fecha);

            // ... Si existen clientes con citas abandonadas, procedemos.
            if ($clientes != 0) {
                foreach ($clientes as $row) {
                    $sw_caso2 = 0;
                    $sw_caso3 = 0;
                    $sw_caso4 = 0;

                    // ... Leemos todos los datos del cliente.
                    $param['id_cliente'] = $row['id_cliente'];
                    $cliente = $this->Clientes_model->leer_clientes($param);

                    // ... Leemos las citas abandonadas de un cliente
                    $id_cliente = $row['id_cliente'];
                    $fecha = date('Y-m-d H:m:s');
                    $abandonos = $this->Informes_model->leer_pedidos_abandonados($id_cliente, 0, $fecha);

                    if ($abandonos != 0) {
                        $id_cliente = $row['id_cliente'];
                        $fecha = date('Y-m-d H:m:s');

                        $citas_online = $this->Informes_model->citas_programadas_online($id_cliente, 0, $fecha, 1);
                        $citas = $this->Informes_model->citas_programadas_online($id_cliente, 0, $fecha, 0);

                        foreach ($abandonos as $item) {
                            if ($citas_online != 0) {
                                foreach ($citas_online as $cita_online) {
                                    if (strtotime($cita_online['fecha_hora_inicio']) >= strtotime($item['fecha_creacion'])) {
                                        // ... Caso 1. Si un Cliente tiene una cita con fecha igual o posterior a la tabla de abandonos,
                                        // y esa cita se ha cogido online, y el servicio que intentaba reservar es el mismo,
                                        // entonces borrar ese cliente de la tabla de abandonos.
                                        if ($item['id_servicio'] == $cita_online['id_servicio']) {
                                            // ... Borramos la cita temporal.
                                            $this->Informes_model->borrar_cita_temporal($item['id_cita']);
                                        }

                                        // Caso 2. Si un Cliente tiene una cita con fecha igual o posterior a la tabla de abandonos,
                                        // y esa cita se ha cogido online, y el servicio que intentaba reservar NO es el
                                        // mismo, entonces enviar email TIPO1 (¿hemos visto que también querías
                                        // reservar para otro servicio, pero no ha sido así, podemos ayudarte?”).
                                        if ($item['id_servicio'] != $cita_online['id_servicio'] && $sw_caso2 == 0) {
                                            $data['cliente'] = $cliente;
                                            $mensaje = $this->load->view('emails/informe_email_otro_servicio_view', $data, true);
                                            $from = "info@templodelmasaje.com";
                                            $asunto = "Citas Online Abandonadas (Otros Servicios)";
                                            $to = $cliente[0]['email'];

                                            if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                                                //$to="massir21@hotmail.com";
                                                $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);

                                                $error = $this->Utiles_model->enviar_email("abandonos@templodelmasaje.com", $from, $asunto, $mensaje);

                                                $sw_caso2 = 1;
                                            }
                                        }
                                    }
                                }
                            }

                            if ($citas != 0) {
                                foreach ($citas as $cita) {
                                    if (strtotime($cita['fecha_hora_inicio']) >= strtotime($item['fecha_creacion']) && $sw_caso3 == 0) {
                                        // Caso 3. Si un Cliente tiene una cita con fecha igual o posterior a la tabla de abandonos,
                                        // y esa cita NO se ha cogido online, entonces enviar al cliente email TIPO2 (¿más
                                        // o menos, “qué problema has tenido para no reservar online y optar por otro
                                        // medio?”).
                                        $data['cliente'] = $cliente;
                                        $mensaje = $this->load->view('emails/informe_email_no_online_view', $data, true);
                                        $from = "info@templodelmasaje.com";
                                        $asunto = "Citas Online Abandonadas - ¿Problemas?";
                                        $to = $cliente[0]['email'];

                                        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                                            //$to="massir21@hotmail.com";
                                            $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);

                                            $error = $this->Utiles_model->enviar_email("abandonos@templodelmasaje.com", $from, $asunto, $mensaje);

                                            $sw_caso3 = 1;
                                        }
                                    }
                                }
                            }

                            if ($citas == 0 && $citas_online == 0 && $sw_caso4 == 0) {
                                // Caso 4. Si un Cliente No tiene una cita con fecha igual o posterior a la tabla de
                                // abandonos, entonces enviar al cliente email TIPO3 (¿hemos visto que has
                                // intentado reservar y no terminaste, necesitas ayuda?”)
                                $data['cliente'] = $cliente;
                                $mensaje = $this->load->view('emails/informe_email_sin_terminar_view', $data, true);
                                $from = "info@templodelmasaje.com";
                                $asunto = "Citas Online Abandonadas - (Sin terminar)";
                                $to = $cliente[0]['email'];

                                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                                    //$to="massir21@hotmail.com";
                                    $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);

                                    $error = $this->Utiles_model->enviar_email("abandonos@templodelmasaje.com", $from, $asunto, $mensaje);

                                    $sw_caso4 = 1;
                                }
                            }

                            // ... Borramos la cita temporal siempre, para que ya no se vuelva a procesar.
                            //$this->Informes_model->borrar_cita_temporal($item['id_cita']);
                        }
                    }
                }

                echo "OK";
                exit;
            }
            // ... No hay citas abandonadas, lo indicamos en la salida.
            else {
                echo "NADA QUE PROCESAR";
                exit;
            }
        }
    }

    // https://desarrollo.templodelmasaje.com/informes/cumpleannos_clientes/hue51e21732f2232787dc3823499e88c
    public function cumpleannos_clientes($rand = null)
    {
        if ($rand == "hue51e21732f2232787dc3823499e88c") {
            // ... Pasamos la fecha de mañana para leer los clientes
            // que cumplan años al día siguiente.
            $fecha = date('m-d', strtotime('+1 day', strtotime(date('Y-m-d'))));
            $data['clientes'] = $this->Informes_model->cumpleannos_clientes($fecha);

            // ... Si hay clientes que cumplen años
            if ($data['clientes'] != 0) {
                $cupon = $this->db->get_where('cupones', ['id_cupon' => 145])->row_array();
                foreach ($data['clientes'] as $key => $value) {
                    if ($value['no_quiere_publicidad'] == 1) {
                        $motivo = "No quiere publicidad";
                    } elseif ($value['email'] == "") {
                        $motivo = "No tiene email conocido";
                    } elseif ($this->Informes_model->filtro_citas_pendientes($value['id_cliente']) != true) {
                        $motivo = "Tiene citas pendientes";
                    } elseif ($this->Informes_model->filtro_carnet_templos($value['id_cliente']) != true) {
                        $motivo = "Tiene templos disponibles";
                    } else {
                        $motivo = false;
                    }

                    if ($motivo != false) {

                        $value['motivo'] = $motivo;
                        $data['clientes_no_envio'][] = $value;
                    } else {
                        $datos_email = [
                            'nombre_cliente' => $value['nombre'],
                            'fecha_limite' => date('d-m-Y', strtotime('+30 day', strtotime(date('Y-m-d')))),
                            'cupon_codigo' => $cupon['codigo_cupon'],
                            'descuento' => ($cupon['descuento_euros'] != 0.00) ? $cupon['descuento_euros'] . '€ de descuento' : 'un ' . $cupon['descuento_porcentaje'] . '% de descuento',
                        ];
                        $mensaje = $this->load->view('emails/email_cumpleannos_clientes_view', $datos_email, true);
                        $from = "info@templodelmasaje.com";
                        $asunto = "Feliz Cumpleaños " . $value['nombre'];
                        $to = $value['email'];


                        if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                            $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                            if ($error == 1) {
                                $datos_insert = [
                                    'id_cliente' => $value['id_cliente'],
                                    'fecha_limite' => date('Y-m-d', strtotime('+30 day', strtotime(date('Y-m-d')))),
                                ];
                                $AqConexion_model = new AqConexion_model();
                                $AqConexion_model->insert('emails_cumpleannos_validos', $datos_insert);
                                $data['clientes_si_envio'][] = $value;
                                // Copia para templos@templodelmasaje.com
                                $to = 'templo@templodelmasaje.com';
                                $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                            } else {
                                $value['motivo'] = 'Error en el envío del email';
                                $data['clientes_no_envio'][] = $value;
                            }
                        }
                    }
                }


                //
                // Generar contenido del email
                //
                $data['fecha'] = date('d-m-Y', strtotime('+1 day', strtotime(date('Y-m-d'))));
                $mensaje = $this->load->view('emails/informe_cumpleannos_view', $data, true);
                $from = "info@templodelmasaje.com";
                $asunto = "Próximos Cumpleños de Clientes";
                $to = "info@templodelmasaje.com";

                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                }
            }

            echo "OK";
        } else {
            echo "Error";
        }

        exit;
    }

    // https://desarrollo.templodelmasaje.com/informes/registro_clientes/21f02354a1689d61fbfef2fee0f082b7
    function registro_clientes($rand = null)
    {
        if ($rand == "21f02354a1689d61fbfef2fee0f082b7") {
            // ... Fecha actual menos 12 horas
            $fecha = date('Y-m-d H:m:s', strtotime('-12 hour', strtotime(date('Y-m-d H:m:s'))));
            //$fecha = date('Y-m-d H:m:s', strtotime('-120 day',strtotime(date('Y-m-d H:m:s'))));

            $data['clientes_registrados'] = $this->Informes_model->clientes_registrados_no_verificados($fecha);

            $data['clientes_fechas_distintas'] = $this->Informes_model->clientes_registrados_fecha_alta($fecha);

            $data['clientes_registrados_verificados'] = $this->Informes_model->clientes_registrados_verificados($fecha);
            if ($data['clientes_registrados_verificados'] != 0) {
                $clientes = array();

                foreach ($data['clientes_registrados_verificados'] as $row) {
                    $datos = $this->Informes_model->clientes_telefono_igual($row['id_cliente'], $row['telefono']);
                    if ($datos != 0) {
                        array_push($clientes, $datos);
                    }
                }

                $data['clientes_telefonos_iguales'] = $clientes;
            } else {
                $data['clientes_telefonos_iguales'] = 0;
            }

            if ($data['clientes_registrados'] != 0 or $data['clientes_fechas_distintas'] != 0 or $data['clientes_telefonos_iguales'] != 0) {
                $mensaje = $this->load->view('emails/informe_registro_clientes_view', $data, true);

                $from = "info@templodelmasaje.com";
                $asunto = "Informe de registro de clientes";
                $to = "registros@templodelmasaje.com";

                if (filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);
                }
            }

            echo "OK";
        } else {
            echo "Error";
        }

        exit;
    }

    //
    // https://desarrollo.templodelmasaje.com/informes/codigos_canjeados_encuestas/51t02354t1689d81fbfef2fee0f082g7
    //
    function codigos_canjeados_encuestas($rand = null, $fecha = null)
    {
        if ($rand == "51t02354t1689d81fbfef2fee0f082g7") {
            $this->load->model('Tienda_model');

            // ... Fecha actual menos 1 dia
            if ($fecha == null) {
                $fecha = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
            }

            // ... Leemos los codigos de tienda usados a traves de carnets.
            $codigos_usados = $this->Informes_model->codigos_tienda_usados_por_carnets($fecha);

            if ($codigos_usados != 0) {
                foreach ($codigos_usados as $row) {
                    // ... Vemos si el codigo ya ha sido usado anteriormente a traves de carnet
                    // para algun pago. Si ya ha sido usado no enviamos encuesta, sino si.
                    $usado = $this->Informes_model->codigo_tienda_usado($fecha, $row['codigo_tienda']);

                    if (!$usado) {
                        $email_cliente = "";
                        $nombre_cliente = "";
                        $apellidos_cliente = "";

                        // ... Si el cliente tiene un email lo enviamos ahi, sino al
                        // email del comprador original en woocommerce.
                        if ($row['email'] != "") {
                            $email_cliente = $row['email'];
                            $nombre_cliente = $row['nombre'];
                            $apellidos_cliente = $row['apellidos'];
                        } else {
                            $datos_cliente_compra = $this->Tienda_model->datos_compra_cliente($row['codigo_tienda']);

                            if ($datos_cliente_compra != 0) {
                                $email_cliente = $datos_cliente_compra['email'];
                                $nombre_cliente = $datos_cliente_compra['nombre'];
                                $apellidos_cliente = $datos_cliente_compra['apellidos'];
                            }
                        }

                        // ... Si el email es válido, procedemos con el envio de datos.
                        if (filter_var($email_cliente, FILTER_VALIDATE_EMAIL)) {
                            $post_id = $this->Tienda_model->post_id($row['codigo_tienda']);
                            $items = $this->Tienda_model->order_item_ids($post_id);

                            if ($items != 0) {
                                $compra = $this->Tienda_model->correspondencias_tienda($items);

                                $data['compra'] = $compra;
                                $data['nombre_cliente'] = $nombre_cliente;
                                $data['apellidos_cliente'] = $apellidos_cliente;
                                $mensaje = $this->load->view('emails/informe_email_encuesta_canje_codigos_view', $data, true);

                                $from = "info@templodelmasaje.com";
                                $asunto = "Valora tu experiencia y obtén un 10% de descuento";
                                $to = $email_cliente;

                                $error = $this->Utiles_model->enviar_email($to, $from, $asunto, $mensaje);

                                // Copia para Marina
                                $error = $this->Utiles_model->enviar_email("templo@templodelmasaje.com", $from, $asunto, $mensaje);
                            }
                        }
                    }
                }
            }
        }

        exit;
    }
}
