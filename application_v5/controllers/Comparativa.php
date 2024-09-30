<?php class Comparativa extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

    function index(){
        if(($this->input->post('fecha_desde_1') != '') && ($this->input->post('fecha_hasta_1') !='')){
            if(($this->input->post('fecha_desde_2') != '') && ($this->input->post('fecha_hasta_2') !='')){
                // Si el intervali 1 es mas antiguo que el 2
                if($this->input->post('fecha_desde_2') > $this->input->post('fecha_desde_1')){
                    // se queda como viene, el intervalo 1 primero
                    // intervalo 1
                    $fecha_desde=date('Y-m-d', strtotime($this->input->post('fecha_desde_1')))." 00:00:00";
                    $fecha_hasta=date('Y-m-d', strtotime($this->input->post('fecha_hasta_1')))." 23:59:59";
                    $data['fecha_desde']=date('Y-m-d',strtotime($fecha_desde));
                    $data['fecha_hasta']=date('Y-m-d',strtotime($fecha_hasta));

                    // intervalo 2
                    $fecha_desde_2=date('Y-m-d', strtotime($this->input->post('fecha_desde_2')))." 00:00:00";
                    $fecha_hasta_2=date('Y-m-d', strtotime($this->input->post('fecha_hasta_2')))." 23:59:59";
                    $data['fecha_desde_2']=date('Y-m-d',strtotime($fecha_desde_2));
                    $data['fecha_hasta_2']=date('Y-m-d',strtotime($fecha_hasta_2));
                }else{
                    // se cambia el orden, el intervalo 2 primero
                    // intervalo 1
                    $fecha_desde=date('Y-m-d', strtotime($this->input->post('fecha_desde_2')))." 00:00:00";
                    $fecha_hasta=date('Y-m-d', strtotime($this->input->post('fecha_hasta_2')))." 23:59:59";
                    $data['fecha_desde']=date('Y-m-d',strtotime($fecha_desde));
                    $data['fecha_hasta']=date('Y-m-d',strtotime($fecha_hasta));

                    // intervalo 2
                    $fecha_desde_2=date('Y-m-d', strtotime($this->input->post('fecha_desde_1')))." 00:00:00";
                    $fecha_hasta_2=date('Y-m-d', strtotime($this->input->post('fecha_hasta_1')))." 23:59:59";
                    $data['fecha_desde_2']=date('Y-m-d',strtotime($fecha_desde_2));
                    $data['fecha_hasta_2']=date('Y-m-d',strtotime($fecha_hasta_2));
                }
            }else{

                // intervalo 1
                $fecha_desde = date('Y-m-d', strtotime($this->input->post('fecha_desde_1')))." 00:00:00";
                $fecha_hasta = date('Y-m-d', strtotime($this->input->post('fecha_hasta_1')))." 23:59:59";
                $data['fecha_desde']=date('Y-m-d',strtotime($fecha_desde));
                $data['fecha_hasta']=date('Y-m-d',strtotime($fecha_hasta));
            }

        }else{

            $fecha_desde = date('Y-m')."-1 00:00:00";
            $fecha_hasta = date('Y-m-t')." 23:59:59";
            $data['fecha_desde']=date('Y-m-d',strtotime($fecha_desde));
            $data['fecha_hasta']=date('Y-m-d',strtotime($fecha_hasta));
        }

        $parametros['vacio']="";
        $centros = $this->Intercentros_model->leer_centros_nombre($parametros);
        $data['centros'] = $centros;

        /// RECORRER LO CENTROS PARA IR OBTENIENDO CADA DATO DEL RANGO 1


            /*$fecha_desde = "2019-02-01 00:00:00";
            $fecha_hasta = "2019-02-28 23:59:59";
            $data['fecha_desde']=date('Y-m-d',strtotime($fecha_desde));
            $data['fecha_hasta']=date('Y-m-d',strtotime($fecha_hasta));
            $fecha_desde_2 = "2019-02-01  00:00:00";
            $fecha_hasta_2 = "2019-02-01  23:59:59";
            $data['fecha_desde_2']=date('Y-m-d',strtotime($fecha_desde_2));
            $data['fecha_hasta_2']=date('Y-m-d',strtotime($fecha_hasta_2));*/

            foreach ($centros as $key => $value) {
                $id_centro = $value['id_centro'];

                    $return[$id_centro]['int_1'] = $this->datos_centros($id_centro, $fecha_desde, $fecha_hasta);
                    if(isset($fecha_desde_2)){
                        $return[$id_centro]['int_2'] = $this->datos_centros($id_centro,$fecha_desde_2, $fecha_hasta_2);
                    }

            }

            // DECLARACIÓN DE LOS ARRAYS NECESARIOS
                $pagado_tarjeta     = [];
                $pagado_transferencia     = [];
                $pagado_efectivo    = [];
                $templos            = [];
                $pagado_habitacion  = [];
                $prod_vendidos      = [];
                $carnets_vendidos   = [];
                $serv_vendidos      = [];
                $descuentos_realizados = [];
                $ventas             = [];
                $devoluciones_realizadas = [];
                $clientes_atendidos = [];
                $clientes_unicos    = [];
                $clientes_nuevos    = [];
                $horas_trabajadas   = [];
                $horas_asignadas    = [];
                $horas_ocupacion    = [];
                $citas_finalizadas  = [];
                $citas_programadas  = [];
                $citas_anuladas     = [];
                $citas_no_vino      = [];
                $carnets_de         = [];
                $carnets_central    = [];
                $carnets_en         = [];
                $compra_productos   = [];

            // LOOP DE LOS DATOS RECOGIDOS
            foreach ($return as $key => $value) {
                if(isset($fecha_desde_2)){
                    $pagado_tarjeta[$key]   = [
                        number_format($value['int_1']['pagado_tarjeta'], 2, ',', '.')." €",
                        number_format($value['int_2']['pagado_tarjeta'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['pagado_tarjeta'], $value['int_2']['pagado_tarjeta'])
                    ];
                    
                    //05/03/20
                      $pagado_transferencia[$key]   = [
                        number_format($value['int_1']['pagado_transferencia'], 2, ',', '.')." €",
                        number_format($value['int_2']['pagado_transferencia'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['pagado_transferencia'], $value['int_2']['pagado_transferencia'])
                    ];
                      
                    //Fin

                    $pagado_efectivo[$key]   = [
                        number_format($value['int_1']['pagado_efectivo'], 2, ',', '.')." €",
                        number_format($value['int_2']['pagado_efectivo'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['pagado_efectivo'], $value['int_2']['pagado_efectivo'])
                    ];

                    $pagado_habitacion[$key]   = [
                        number_format($value['int_1']['pagado_habitacion'], 2, ',', '.')." €",
                        number_format($value['int_2']['pagado_habitacion'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['pagado_habitacion'], $value['int_2']['pagado_habitacion'])
                    ];

                    $templos[$key]   = [
                        number_format($value['int_1']['templos'], 2, ',', '.')." €",
                        number_format($value['int_2']['templos'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['templos'], $value['int_2']['templos'])
                    ];

                    $ventas[$key]   = [
                        number_format($value['int_1']['ventas'], 2, ',', '.')." €",
                        number_format($value['int_2']['ventas'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['ventas'], $value['int_2']['ventas'])
                    ];

                    $prod_vendidos[$key]    = [
                        $value['int_1']['ventas_prod']['num_items'].' | '.number_format($value['int_1']['ventas_prod']['total_valor_items'], 2, ',', '.')." €",
                        $value['int_2']['ventas_prod']['num_items'].' | '.number_format($value['int_2']['ventas_prod']['total_valor_items'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_2']['ventas_prod']['total_valor_items'], $value['int_1']['ventas_prod']['total_valor_items'])
                    ];

                    $serv_vendidos[$key]    = [
                        $value['int_1']['ventas_serv']['num_items'].' | '.number_format($value['int_1']['ventas_serv']['total_valor_items'], 2, ',', '.')." €",
                        $value['int_2']['ventas_serv']['num_items'].' | '.number_format($value['int_2']['ventas_serv']['total_valor_items'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['ventas_serv']['total_valor_items'], $value['int_2']['ventas_serv']['total_valor_items'])
                    ];

                    $carnets_vendidos[$key] = [
                        $value['int_1']['ventas_carnets']['num_items'].' | '.number_format($value['int_1']['ventas_carnets']['total_valor_items'], 2, ',', '.')." €",
                        $value['int_2']['ventas_carnets']['num_items'].' | '.number_format($value['int_2']['ventas_carnets']['total_valor_items'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_2']['ventas_carnets']['total_valor_items'], $value['int_1']['ventas_carnets']['total_valor_items'])
                    ];
                    $descuentos_realizados[$key]    = [
                        $value['int_1']['descuentos']['num_items'].' | '.number_format($value['int_1']['descuentos']['total_valor_items'], 2, ',', '.')." €",
                        $value['int_2']['descuentos']['num_items'].' | '.number_format($value['int_2']['descuentos']['total_valor_items'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_2']['descuentos']['total_valor_items'], $value['int_1']['descuentos']['total_valor_items'])
                    ];

                    $devoluciones_realizadas[$key] = [
                        $value['int_1']['devoluciones']['num_items'].' | '.number_format($value['int_1']['devoluciones']['total_valor_items'], 2, ',', '.')." €",
                        $value['int_2']['devoluciones']['num_items'].' | '.number_format($value['int_2']['devoluciones']['total_valor_items'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_2']['devoluciones']['total_valor_items'], $value['int_1']['devoluciones']['total_valor_items'])
                    ];

                    $clientes_atendidos[$key]=[
                        $value['int_1']['clientes']['atendidos'],
                        $value['int_2']['clientes']['atendidos'],
                        $this->porcentaje($value['int_1']['clientes']['atendidos'], $value['int_2']['clientes']['atendidos'])
                    ];
                    $clientes_unicos[$key]=[
                        $value['int_1']['clientes']['unicos'],
                        $value['int_2']['clientes']['unicos'],
                        $this->porcentaje($value['int_1']['clientes']['unicos'], $value['int_2']['clientes']['unicos'])
                    ];
                    $clientes_nuevos[$key]=[
                        $value['int_1']['clientes']['nuevos'],
                        $value['int_2']['clientes']['nuevos'],
                        $this->porcentaje($value['int_1']['clientes']['nuevos'], $value['int_2']['clientes']['nuevos'])
                    ];

                    $horas_trabajadas[$key] = [
                        $value['int_1']['horas']['trabajadas'],
                        $value['int_2']['horas']['trabajadas'],
                        $this->porcentaje($value['int_1']['horas']['trabajadas'], $value['int_2']['horas']['trabajadas'])
                    ];

                    $horas_asignadas[$key] = [
                        $value['int_1']['horas']['asignadas'],
                        $value['int_1']['horas']['asignadas'],
                        $this->porcentaje($value['int_1']['horas']['asignadas'], $value['int_2']['horas']['asignadas'])
                    ];
                    $horas_ocupacion[$key] = [
                        $value['int_1']['horas']['ocupacion'],
                        $value['int_1']['horas']['ocupacion'],
                        $this->porcentaje($value['int_1']['horas']['ocupacion'], $value['int_2']['horas']['ocupacion'])
                    ];

                    if($value['int_1']['citas']['total'] !== 0){$por100_1 = '('.round(($value['int_1']['citas']['online']*100)/$value['int_1']['citas']['total'],2).'%)';}else{$por100_1 = '(0%)';}
                    if($value['int_2']['citas']['total'] !== 0){$por100_2 = '('.round(($value['int_2']['citas']['online']*100)/$value['int_2']['citas']['total'],2).'%)';}else{$por100_2 = '(0%)';}
                    $citas_total[$key] =[
                        $value['int_1']['citas']['total'].' | '.$por100_1,
                        $value['int_2']['citas']['total'].' | '.$por100_2,
                        $this->porcentaje($value['int_1']['citas']['total'], $value['int_2']['citas']['total']).' | '.$this->porcentaje($value['int_1']['citas']['online'], $value['int_2']['citas']['online'])
                    ];

                    if($value['int_1']['citas']['finalizadas_total'] !== 0){$por100_1 = '('.round(($value['int_1']['citas']['finalizadas_online']*100)/$value['int_1']['citas']['finalizadas_total'],2).'%)';}else{$por100_1 = '(0%)';}
                    if($value['int_2']['citas']['finalizadas_total'] !== 0){$por100_2 = '('.round(($value['int_2']['citas']['finalizadas_online']*100)/$value['int_2']['citas']['finalizadas_total'],2).'%)';}else{$por100_2 = '(0%)';}
                    $citas_finalizadas[$key] = [
                        $value['int_1']['citas']['finalizadas_total'].' | '.$por100_1,
                        $value['int_2']['citas']['finalizadas_total'].' | '.$por100_2,
                        $this->porcentaje($value['int_1']['citas']['finalizadas_total'], $value['int_2']['citas']['finalizadas_total']).' | '.$this->porcentaje($value['int_1']['citas']['finalizadas_online'], $value['int_2']['citas']['finalizadas_online'])
                    ];

                    if($value['int_1']['citas']['programadas_total'] !== 0){$por100_1 = '('.round(($value['int_1']['citas']['programadas_online']*100)/$value['int_1']['citas']['programadas_total'],2).'%)';}else{$por100_1 = '(0%)';}
                    if($value['int_2']['citas']['programadas_total'] !== 0){$por100_2 = '('.round(($value['int_2']['citas']['programadas_online']*100)/$value['int_2']['citas']['programadas_total'],2).'%)';}else{$por100_2 = '(0%)';}
                    $citas_programadas[$key] = [
                        $value['int_1']['citas']['programadas_total'].' | '.$por100_1,
                        $value['int_2']['citas']['programadas_total'].' | '.$por100_2,
                        $this->porcentaje($value['int_1']['citas']['programadas_total'], $value['int_2']['citas']['programadas_total']).' | '.$this->porcentaje($value['int_1']['citas']['programadas_online'], $value['int_2']['citas']['programadas_total'])
                    ];
                    if($value['int_1']['citas']['anuladas_total'] !== 0){$por100_1 = '('.round(($value['int_1']['citas']['anuladas_online']*100)/$value['int_1']['citas']['anuladas_total'],2).'%)';}else{$por100_1 = '(0%)';}
                    if($value['int_2']['citas']['anuladas_total'] !== 0){$por100_2 = '('.round(($value['int_2']['citas']['anuladas_online']*100)/$value['int_2']['citas']['anuladas_total'],2).'%)';}else{$por100_2 = '(0%)';}
                    $citas_anuladas[$key] = [
                        $value['int_1']['citas']['anuladas_total'].' | '.$por100_1,
                        $value['int_2']['citas']['anuladas_total'].' | '.$por100_2,
                        $this->porcentaje($value['int_1']['citas']['anuladas_total'], $value['int_2']['citas']['anuladas_total']).' | '.$this->porcentaje($value['int_1']['citas']['anuladas_online'], $value['int_2']['citas']['anuladas_online'])
                    ];
                    if($value['int_1']['citas']['no_vino_total'] !== 0){$por100_1 = '('.round(($value['int_1']['citas']['no_vino_online']*100)/$value['int_1']['citas']['no_vino_total'],2).'%)';}else{$por100_1 = '(0%)';}
                    if($value['int_2']['citas']['no_vino_total'] !== 0){$por100_2 = '('.round(($value['int_2']['citas']['no_vino_online']*100)/$value['int_2']['citas']['no_vino_total'],2).'%)';}else{$por100_2 = '(0%)';}
                    $citas_no_vino[$key] = [
                        $value['int_1']['citas']['no_vino_total'].' | '.$por100_1,
                        $value['int_2']['citas']['no_vino_total'].' | '.$por100_2,
                        $this->porcentaje($value['int_1']['citas']['no_vino_total'], $value['int_2']['citas']['no_vino_total']).' | '.$this->porcentaje($value['int_1']['citas']['no_vino_online'], $value['int_2']['citas']['no_vino_online'])
                    ];


                    $carnets_de[$key] = [
                        $value['int_1']['carnets']['carnets_de_otros']['num'].' | '.number_format($value['int_1']['carnets']['carnets_de_otros']['total'], 2, ',', '.')." €",
                        $value['int_2']['carnets']['carnets_de_otros']['num'].' | '.number_format($value['int_2']['carnets']['carnets_de_otros']['total'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['carnets']['carnets_de_otros']['total'],$value['int_2']['carnets']['carnets_de_otros']['total'])
                    ];

                    $carnets_central[$key] = [
                        $value['int_1']['carnets']['carnets_de_central']['num'].' | '.number_format($value['int_1']['carnets']['carnets_de_central']['total'], 2, ',', '.')." €",
                        $value['int_2']['carnets']['carnets_de_central']['num'].' | '.number_format($value['int_2']['carnets']['carnets_de_central']['total'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['carnets']['carnets_de_central']['total'],$value['int_2']['carnets']['carnets_de_central']['total'])
                    ];

                    $carnets_en[$key] = [
                        $value['int_1']['carnets']['carnets_en_otros']['num'].' | '.number_format($value['int_1']['carnets']['carnets_en_otros']['total'], 2, ',', '.')." €",
                        $value['int_2']['carnets']['carnets_en_otros']['num'].' | '.number_format($value['int_2']['carnets']['carnets_en_otros']['total'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['carnets']['carnets_en_otros']['total'],$value['int_2']['carnets']['carnets_en_otros']['total'])
                    ];

                    $compra_productos[$key] = [
                        $value['int_1']['compra_prod']['num'].' | '.number_format($value['int_1']['compra_prod']['total'], 2, ',', '.')." €",
                        $value['int_2']['compra_prod']['num'].' | '.number_format($value['int_2']['compra_prod']['total'], 2, ',', '.')." €",
                        $this->porcentaje($value['int_1']['compra_prod']['total'],$value['int_2']['compra_prod']['total'])
                    ];
                }else{
                    $pagado_tarjeta[$key] = [
                        number_format($value['int_1']['pagado_tarjeta'], 2, ',', '.')." €",
                    ];
                    //05/03/20
                      $pagado_transferencia[$key] = [
                        number_format($value['int_1']['pagado_transferencia'], 2, ',', '.')." €",
                      ];
                    //Fin

                    $pagado_efectivo[$key]  = [
                         number_format($value['int_1']['pagado_efectivo'], 2, ',', '.')." €",
                    ];

                    $templos[$key]          = [
                         number_format($value['int_1']['templos'], 2, ',', '.')." €",
                    ];

                    $pagado_habitacion[$key]= [
                         number_format($value['int_1']['pagado_habitacion'], 2, ',', '.')." €",
                    ];

                    $ventas[$key]           = [
                         number_format($value['int_1']['ventas'], 2, ',', '.')." €",
                    ];

                    $prod_vendidos[$key]    = [
                        $value['int_1']['ventas_prod']['num_items'].' | '.number_format($value['int_1']['ventas_prod']['total_valor_items'], 2, ',', '.')." €",
                    ];

                    $serv_vendidos[$key]    = [
                        $value['int_1']['ventas_serv']['num_items'].' | '.number_format($value['int_1']['ventas_serv']['total_valor_items'], 2, ',', '.')." €",
                    ];

                    $carnets_vendidos[$key] = [
                        $value['int_1']['ventas_carnets']['num_items'].' | '.number_format($value['int_1']['ventas_carnets']['total_valor_items'], 2, ',', '.')." €",
                    ];
                    $descuentos_realizados[$key]    = [
                        $value['int_1']['descuentos']['num_items'].' | '.number_format($value['int_1']['descuentos']['total_valor_items'], 2, ',', '.')." €",
                    ];

                    $devoluciones_realizadas[$key] = [
                        $value['int_1']['devoluciones']['num_items'].' | '.number_format($value['int_1']['devoluciones']['total_valor_items'], 2, ',', '.')." €",
                    ];

                    $clientes_atendidos[$key]=[
                        $value['int_1']['clientes']['atendidos'],
                    ];
                    $clientes_unicos[$key]=[
                        $value['int_1']['clientes']['unicos'],
                    ];
                    $clientes_nuevos[$key]=[
                        $value['int_1']['clientes']['nuevos']
                    ];

                    $horas_trabajadas[$key] = [
                        $value['int_1']['horas']['trabajadas']
                    ];

                    $horas_asignadas[$key] = [
                        $value['int_1']['horas']['asignadas']
                    ];
                    $horas_ocupacion[$key] = [
                        $value['int_1']['horas']['ocupacion']
                    ];

                    $citas_total[$key] =[
                        $value['int_1']['citas']['total'],
                        ($value['int_1']['citas']['total'] != 0) ?
                        '('.round(($value['int_1']['citas']['online']*100)/$value['int_1']['citas']['total'],2).'%)' : '(0%)'
                    ];

                    $citas_finalizadas[$key] = [
                        $value['int_1']['citas']['finalizadas_total'],
                        ($value['int_1']['citas']['finalizadas_online'] != 0) ?
                        '('.round(($value['int_1']['citas']['finalizadas_online']*100)/$value['int_1']['citas']['finalizadas_total'],2).'%)' : '(0%)'
                    ];

                    $citas_programadas[$key] = [
                        $value['int_1']['citas']['programadas_total'],
                        ($value['int_1']['citas']['programadas_online'] != 0) ?
                        '('.round(($value['int_1']['citas']['programadas_online']*100)/$value['int_1']['citas']['programadas_total'],2).'%)' : '(0%)',
                    ];

                    $citas_anuladas[$key] = [
                        $value['int_1']['citas']['anuladas_total'],
                        ($value['int_1']['citas']['anuladas_total'] != 0) ?
                        '('.round(($value['int_1']['citas']['anuladas_online']*100)/$value['int_1']['citas']['anuladas_total'],2).'%)' : '(0%)',
                    ];

                    $citas_no_vino[$key] = [
                        $value['int_1']['citas']['no_vino_total'],
                        ($value['int_1']['citas']['no_vino_total'] != 0) ?
                        '('.round(($value['int_1']['citas']['no_vino_online']*100)/$value['int_1']['citas']['no_vino_total'],2).'%)' : '(0%)',
                    ];

                    $carnets_de[$key] = [
                        $value['int_1']['carnets']['carnets_de_otros']['num'] .' ct.',
                        number_format($value['int_1']['carnets']['carnets_de_otros']['total'], 2, ',', '.')." €",
                    ];

                    $carnets_central[$key] = [
                        $value['int_1']['carnets']['carnets_de_central']['num'] .' ct.',
                        number_format($value['int_1']['carnets']['carnets_de_central']['total'], 2, ',', '.')." €",
                    ];

                    $carnets_en[$key] = [
                        $value['int_1']['carnets']['carnets_en_otros']['num'] .' ct.',
                        number_format($value['int_1']['carnets']['carnets_en_otros']['total'], 2, ',', '.')." €",
                    ];

                    $compra_productos[$key] = [
                        $value['int_1']['compra_prod']['num'].' pr.',
                        number_format($value['int_1']['compra_prod']['total'], 2, ',', '.')." €",
                    ];
                }
            }

            // SE PASAN LOS DATOS AL ARRAY DE TRs
            $data['tr_rows'] = [
                'Facturación total'     => $ventas,
                'Facturación tarjeta'   => $pagado_tarjeta,
                'Facturación transferencia'   => $pagado_transferencia,
                'Facturación efectivo'  => $pagado_efectivo,
                'Templos'               => $templos,
                'Facturación habitacion'=> $pagado_habitacion,
                'Venta Carnets'         => $carnets_vendidos,
                'Venta Servicios'       => $serv_vendidos,
                'Venta Productos'       => $prod_vendidos,
                'Compra de productos'   => $compra_productos,
                'Descuentos'            => $descuentos_realizados,
                'Devoluciones'          => $devoluciones_realizadas,
                'Clientes atendidos'    => $clientes_atendidos,
                'Clientes distintos'    => $clientes_unicos,
                'Nuevos clientes'       => $clientes_nuevos,
                'Horas trabajadas'      => $horas_trabajadas,
                'Horas asignadas'       => $horas_asignadas,
                'Ocupación'             => $horas_ocupacion,
                'Citas'                 => $citas_total,
                'Citas finalizadas'     => $citas_finalizadas,
                'Citas programadas'     => $citas_programadas,
                'Citas anuladas'        => $citas_anuladas,
                'Citas no vino'         => $citas_no_vino,
                'Carnets de otros centros' => $carnets_de,
                'Carnets de Central'    => $carnets_central,
                'Carnets en otros centros' => $carnets_en,
            ];

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Comparativa de datos';
        $data['content_view'] = $this->load->view('comparativa/comparativa_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil']=$this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        $permiso=$this->Acceso_model->TienePermiso($data['modulos'],23);
        if ($permiso) { $this->load->view($this->config->item('template_dir').'/master', $data); }
        else { header ("Location: ".RUTA_WWW."/errores/error_404.html"); exit; }
    }

    function datos_centros($id_centro, $fecha_desde, $fecha_hasta){

        $desde = date('Y-m-d', strtotime($fecha_desde))." 00:00:00";
        $hasta = date('Y-m-d', strtotime($fecha_hasta))." 23:59:59";
        $param['fecha_desde']=$desde;
        $param['fecha_hasta']=$hasta;
        $param['id_centro']=$id_centro;

        // buscar los empleados del centro
        $todos_los_trabajadores = $this->trabajadores_del_centro($id_centro);

        $ids_trabajadores = [];
        $ids_empleados = [];
        $ids_recepcionistas = [];
        $ids_encargados = [];
        foreach ($todos_los_trabajadores as $key => $value) {
            $ids_trabajadores[]=$value->id_usuario;
            if($value->id_perfil == 1){ $ids_empleados[]=$value->id_usuario;}
            if($value->id_perfil == 2){ $ids_recepcionistas[]=$value->id_usuario;}
            if($value->id_perfil == 3){ $ids_encargados[]=$value->id_usuario;}
        }

        $facturacion    = $this->facturacion_row($id_centro, $desde, $hasta);
        $ventas_prod    = $this->dietario_row($id_centro, $desde, $hasta, 'id_producto');
        $ventas_serv    = $this->dietario_row($id_centro, $desde, $hasta, 'id_servicio');
        $ventas_carnet  = ($id_centro != 1) ? $this->venta_carnets_row($id_centro, $desde, $hasta, 'id_carnet') : $this->venta_carnets_central_row($desde, $hasta);

        if($id_centro == 1){
            $facturacion['tarjeta'] = $ventas_carnet['total_valor_items'];
            $facturacion['ventas'] = $ventas_carnet['total_valor_items'];

        }



        $descuentos     = $this->descuentos_row($id_centro, $desde, $hasta);
        $devoluciones   = $this->devoluciones_row($id_centro, $desde, $hasta);
        $clientes       = $this->clientes_row($id_centro, $desde, $hasta);
        $horas          = $this->horas_row($id_centro, $desde, $hasta, $ids_trabajadores);
        $citas          = $this->citas_row($id_centro, $desde, $hasta, $ids_trabajadores,$ids_recepcionistas,$ids_encargados);
        $carnets        = $this->carnets_rows($id_centro, $desde, $hasta);
        $compra_prod    = $this->productos_en_venta($id_centro, $desde, $hasta);

        $centro = [
            'pagado_tarjeta'        => $facturacion['tarjeta'],
            'pagado_transferencia'        => $facturacion['transferencia'], //05/03/20
            'pagado_efectivo'       => $facturacion['efectivo'],
            'templos'               => $facturacion['templos'],
            'pagado_habitacion'     => $facturacion['habitacion'],
            'ventas'                => $facturacion['ventas'],
            'ventas_prod'           => $ventas_prod,
            'ventas_serv'           => $ventas_serv,
            'ventas_carnets'        => $ventas_carnet,
            'descuentos'            => $descuentos,
            'devoluciones'          => $devoluciones,
            'clientes'              => $clientes,
            'horas'                 => $horas,
            'citas'                 => $citas,
            'carnets'               => $carnets,
            'compra_prod'           => $compra_prod
        ];
        return $centro;
    }

    function porcentaje($dato1, $dato2){
        if(($dato1 != 0)){
            $return = round((($dato2/$dato1)*100)-100, 2).'%';
        }else{
            $return = '-';
        }
        return $return;
    }

    function trabajadores_del_centro($id_centro){
        $this->db->select('usuarios.id_usuario, usuarios_perfiles.id_perfil');
        $this->db->where('usuarios.id_centro', $id_centro);
        $this->db->where('usuarios.borrado ', 0);
        $this->db->join('usuarios_perfiles', 'usuarios_perfiles.id_usuario = usuarios.id_usuario');
        return $this->db->get('usuarios')->result();
    }

    function facturacion_row($id_centro, $fecha_desde, $fecha_hasta){
        $this->db->select('dietario.*, usuarios_perfiles.id_perfil');
        $this->db->where('dietario.id_centro', $id_centro);
        $this->db->where('dietario.fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('dietario.fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('dietario.borrado ', 0);
        $estados = array('Pagado', 'Devuelto');
        $this->db->where_in('dietario.estado', $estados);
        $this->db->join('usuarios_perfiles', 'usuarios_perfiles.id_usuario = dietario.id_empleado');
        $q = $this->db->get('dietario');

        $total_rows = $q->result();
        $efectivo = 0;
        $tarjeta = 0;
        $transferencia = 0; //05/03/20
        $templos = 0;
        $habitacion = 0;
        $ventas = 0;

        foreach ($total_rows as $key => $value) {
            $tarjeta    = $tarjeta + $value->pagado_tarjeta;
            $transferencia   = $transferencia + $value->pagado_transferencia; //05/03/20
            $efectivo   = $efectivo + $value->pagado_efectivo;
            if(($value->id_servicio > 0) && (($value->id_perfil == 1) || ($value->id_perfil == 3))){
                if($value->estado == 'Pagado'){
                    $templos = $templos + $value->templos;
                }else{
                    $templos = $templos - $value->templos;
                }

            }
            $habitacion = $habitacion + $value->pagado_habitacion;
            $ventas = $ventas + $value->pagado_tarjeta +$value->pagado_efectivo + $value->pagado_habitacion + $value->pagado_transferencia;
        }

        $return = [
            'tarjeta'   => $tarjeta,
            'transferencia'   => $transferencia, //05/03/20
            'efectivo'  => $efectivo,
            'habitacion'=> $habitacion,
            'templos'   => $templos,
            'ventas'    => $ventas
        ];
        return $return;
    }

    function dietario_row($id_centro, $fecha_desde, $fecha_hasta, $column){
        $where = $column." !=";
        $this->db->where('id_centro', $id_centro);
        $this->db->where('fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $this->db->where( $where, 0);
        $estados = array('Pagado', 'Devuelto');
        $this->db->where_in('estado', $estados);
        $q = $this->db->get('dietario')->result();
        $num_prod = 0;
        $total = 0;
        $ventas = 0;
        foreach ($q as $key => $value) {
            $ventas = $ventas + 1;
            $total = $total + $value->pagado_efectivo+$value->pagado_tarjeta+$value->pagado_habitacion+$value->pagado_transferencia;
            $num_prod = $num_prod + $value->cantidad;
        }

        $return = [
            'num_ventas' =>  $ventas,
            'num_items' => $num_prod,
            'total_valor_items' => $total
        ];
        return $return;
    }

    function venta_carnets_row($id_centro, $fecha_desde, $fecha_hasta){
        $query= "SELECT IFNULL(round(sum(pagado_efectivo+pagado_tarjeta+pagado_habitacion+pagado_transferencia),2),0) as total,
        COUNT(id_dietario) AS numero_carnets
        FROM dietario WHERE dietario.borrado = 0
        AND dietario.id_carnet > 0
        AND dietario.recarga = 0
        AND (dietario.estado = 'Pagado' or dietario.estado = 'Devuelto')
        AND dietario.id_centro = $id_centro
        AND dietario.fecha_hora_concepto > '$fecha_desde' AND  dietario.fecha_hora_concepto < '$fecha_hasta'";
        $templos_row = $this->db->query($query)->row();
        /*if($id_centro == 1){
            var_dump($templos_row);exit;
        }*/

        $return = [
            'num_items' => $templos_row->numero_carnets,
            'total_valor_items' => $templos_row->total
        ];
        return $return;

    }

    function venta_carnets_central_row($fecha_desde, $fecha_hasta){
        $this->db->select_sum('precio', 'total_valor_items');
        $this->db->select('count(*) AS num_items');
        $this->db->where('fecha_creacion >=', $fecha_desde);
        $this->db->where('fecha_creacion <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $this->db->where('id_centro', 1);
        $this->db->like('codigo', 'i', 'before');
        $q = $this->db->get('carnets_templos')->result();


        $return = [
            'num_items' => $q[0]->num_items,
            'total_valor_items' => $q[0]->total_valor_items
        ];
        return $return;
    }

    function facturacion_central_row($ventas_prod, $ventas_serv, $ventas_carnet){

        $tarjeta  =$ventas_prod['total_valor_items'] + $ventas_serv['total_valor_items'] + $ventas_carnet['total_valor_items'];


        $return = [
            'tarjeta'   => $tarjeta,
            'efectivo'  => 0,
            'habitacion'=> 0,
            'templos'   => 0,
            'ventas'    => $tarjeta
        ];
        return $return;

    }

    function descuentos_row($id_centro, $fecha_desde, $fecha_hasta){

        $this->db->where('id_centro', $id_centro);
        $this->db->where('fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $this->db->where('(descuento_porcentaje != 0 OR descuento_euros != 0)');
        $estados = array('Pagado', 'Devuelto');
        $this->db->where_in('estado', $estados);
        $desc = $this->db->get('dietario')->result();
        $importe = 0;
        $pagado = 0;
        foreach ($desc as $key => $value) {
            $importe = $importe + $value->importe_euros;
            $pagado = $pagado + $value->pagado_efectivo + $value->pagado_tarjeta + $value->pagado_habitacion+$value->pagado_transferencia;
        }

        return ['total_valor_items' => $importe -  $pagado, 'num_items' => count($desc)];
    }

    function devoluciones_row($id_centro, $fecha_desde, $fecha_hasta){
        $this->db->where('id_centro', $id_centro);
        $this->db->where('fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $this->db->where('estado','Devuelto');
        $q = $this->db->get('dietario')->result();
        $num_prod = 0;
        $total = 0;
        $ventas = 0;
        foreach ($q as $key => $value) {
            $ventas = $ventas + 1;
            $total = $total + $value->pagado_efectivo+$value->pagado_tarjeta+$value->pagado_habitacion+ $value->pagado_transferencia;
            $num_prod = $num_prod + $value->cantidad;
        }

        $return = [
            'num_ventas' =>  $ventas,
            'num_items' => $num_prod,
            'total_valor_items' => $total
        ];
        return $return;
    }

    function clientes_row($id_centro, $fecha_desde, $fecha_hasta){
        // clientes atendidos//
        $this->db->where('id_centro', $id_centro);
        $this->db->where('fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $estados = array('Pagado', 'Devuelto');
        $this->db->where_in('estado', $estados);
        $clientes_atendidos = $this->db->get('dietario')->result();

        // clientes único atendidos//
        $clientes_unicos_atendidos = [];
        foreach ($clientes_atendidos as $key => $value) {
            if(!in_array($value->id_cliente, $clientes_unicos_atendidos)){
                $clientes_unicos_atendidos[] = $value->id_cliente;
            }
        }

        $id_clientes = [];
        foreach ($clientes_unicos_atendidos as $key => $value) {
            $id_cliente = $value;
            $this->db->where('id_cliente', $id_cliente);
            $this->db->order_by('fecha_hora_concepto', 'asc');
            $this->db->limit(1);
            // PRIMER PAGO DEL CLIENTE
            $primer_pago_cliente = $this->db->get('dietario')->row();

            // SI EL PRIMER PAGO SE HA PRODUCIDO ENTE EL RANGO DE FECHAS DADO...
            if(($primer_pago_cliente->fecha_hora_concepto >= $fecha_desde) && ($primer_pago_cliente->fecha_hora_concepto <= $fecha_hasta)){
                // CAPTURAMOS LOS DATOS DEL CLIENTE
                $cliente = $this->db->get_where('clientes', array('id_cliente' => $id_cliente))->row();

                // BUSCAMOS AL CLIENTE EN EL HISTORIAL ANTIGUO
                $codcli     = explode('-', $cliente->codigo_cliente)[0];
                $nomcli     = $cliente->nombre;
                $ape1cli    = $cliente->apellidos;

                $this->db->select('id');
                $this->db->where('ape1cli', $ape1cli);
                $this->db->where('nomcli', $nomcli);
                $this->db->where('codcli', $codcli);
                $this->db->limit(1);
                $cliente_antiguo = $this->db->get('clientes_historial_antiguo')->num_rows();

                // SI NO SE ENCUENTRA EN EL HISTORIAL ANTIGUO
                // CUMPLE LAS CONDICIONES DE QUE SU PRIMER PAGO SE REALIZA ENTRE LAS FECHAS DADAS
                // Y QUE NO EXISTE EN EL HISTORIAL ANTIGUO
                // POR LO QUE SE AÑADE A NUEVOS CLIENTES
                if($cliente_antiguo == 0){
                    $id_clientes[] = $id_cliente;
                }
            }
        }

        $return = [
            'atendidos' => count($clientes_atendidos),
            'unicos'    => count($clientes_unicos_atendidos),
            'nuevos'    => count($id_clientes),
        ];

        return $return;
    }

    function horas_row($id_centro, $fecha_desde, $fecha_hasta, $id_trabajadores){

        //buscamos en las citas las que estan acabadas y en las que el empleado esta en el array de ids
        $this->db->select_sum('duracion','trabajadas');
        $this->db->where('fecha_hora_inicio >=', $fecha_desde);
        $this->db->where('fecha_hora_inicio <=', $fecha_hasta);
        $this->db->where('estado', 'Finalizado');
        $this->db->where('borrado ', 0);
        $this->db->where_in('id_usuario_empleado', $id_trabajadores);
        $trabajadas =round($this->db->get('citas')->row()->trabajadas /60, 1);

        $query="SELECT round(sum(citas.duracion)/60,1) AS trabajadas FROM citas LEFT JOIN dietario ON dietario.id_cita = citas.id_cita WHERE citas.borrado = 0 AND citas.fecha_hora_inicio > '$fecha_desde' AND  citas.fecha_hora_inicio < '$fecha_hasta' AND  citas.estado = 'Finalizado' AND id_centro = $id_centro AND dietario.borrado = 0";
        $trabajadas = round($this->db->query($query)->row()->trabajadas, 2);

        // HORAS ASIGNADAS
        ////

        $query ="SELECT IFNULL(sum(TIMESTAMPDIFF(minute, fecha_inicio, fecha_fin) / 60),0) AS asignadas FROM usuarios_horarios_desglose where id_usuario in (select distinct id_empleado from dietario where borrado = 0 and id_centro = $id_centro AND dietario.fecha_hora_concepto > '$fecha_desde' AND dietario.fecha_hora_concepto < '$fecha_hasta') and fecha_inicio > '$fecha_desde' and fecha_fin < '$fecha_hasta'";
        $asignadas = round($this->db->query($query)->row()->asignadas, 2);
        /*if($id_centro == 6){
            echo $asignadas;
            exit;
        }*/
        if($asignadas != 0){
            $ocupacion = round(($trabajadas*100)/$asignadas, 2).'%';
        }else{
            $ocupacion = '0';
        }

        $return = [
            'trabajadas'=> $trabajadas,
            'asignadas' => $asignadas,
            'ocupacion' => $ocupacion,
        ];
        return $return;
    }

    function citas_row($id_centro, $fecha_desde, $fecha_hasta, $id_trabajadores, $ids_recepcionistas,$ids_encargados){

        $recepcionistas_total = array_merge($ids_encargados,$ids_recepcionistas);
        $this->db->select('id_cita, id_usuario_empleado, id_usuario_creador, estado');
        $this->db->where_in('id_usuario_empleado', $id_trabajadores);
        $this->db->where('fecha_creacion >=', $fecha_desde);
        $this->db->where('fecha_creacion <=', $fecha_hasta);
        $citas_centro_total = $this->db->get('citas');

        $citas_personal     = 0;
        $citas_online       = 0;
        $cita_otro_centro   = 0;

        $anuladas_total     = 0;
        $finalizadas_total  = 0;
        $programadas_total  = 0;
        $no_vino_total      = 0;

        $anuladas_online    = 0;
        $finalizadas_online = 0;
        $programadas_online = 0;
        $no_vino_online     = 0;

        foreach ($citas_centro_total->result() as $key => $value) {
            if(in_array($value->id_usuario_creador, $recepcionistas_total)){
                $citas_personal = $citas_personal + 1;
            }elseif($value->id_usuario_creador == 0){
                $citas_online = $citas_online + 1;
                switch ($value->estado) {
                    case 'Anulada':
                        $anuladas_online    = $anuladas_online +1;
                        break;
                    case 'Finalizado':
                        $finalizadas_online = $finalizadas_online +1;
                        break;
                    case 'Programada':
                        $programadas_online = $programadas_online +1;
                        break;
                    default:
                        $no_vino_online     = $no_vino_online +1;
                        break;
                }
            }else{
                $cita_otro_centro = $cita_otro_centro + 1;
            }

            switch ($value->estado) {
                case 'Anulada':
                    $anuladas_total     = $anuladas_total +1;
                    break;
                case 'Finalizado':
                    $finalizadas_total  = $finalizadas_total +1;
                    break;
                case 'Programada':
                    $programadas_total  = $programadas_total +1;
                    break;
                default:
                    $no_vino_total      = $no_vino_total +1;
                    break;
            }
        }


        $result = [
            'total'             => $citas_centro_total->num_rows(),
            'personal'          => $citas_personal,
            'online'            => $citas_online,
            'otro'              => $cita_otro_centro,
            'anuladas_total'    => $anuladas_total,
            'finalizadas_total' => $finalizadas_total,
            'programadas_total' => $programadas_total,
            'no_vino_total'     => $no_vino_total,
            'anuladas_online'   => $anuladas_online,
            'finalizadas_online'=> $finalizadas_online,
            'programadas_online'=> $programadas_online,
            'no_vino_online'    => $no_vino_online,
        ];

        return $result;
    }

    function carnets_rows($id_centro, $fecha_desde, $fecha_hasta){

        $param['fecha_desde']   =   $fecha_desde;
        $param['fecha_hasta']   =   $fecha_hasta;
        $param['id_centro']     =   $id_centro;

        $carnets_usados_de= $this->Intercentros_model->datos($param);
        $carnets_de_otros = ['num' => 0, 'total' => 0];
        $carnets_de_central = ['num' => 0, 'total' => 0];
        if ($carnets_usados_de != 0) {
            foreach ($carnets_usados_de as $key => $value) {
                if($value['id_centro'] == 1){
                    $carnets_de_central['num'] = $carnets_de_central['num'] +1;
                    $carnets_de_central['total'] = $carnets_de_central['total'] +$value['total'];
                }else{
                    $carnets_de_otros['num'] = $carnets_de_otros['num'] +1;
                    $carnets_de_otros['total'] = $carnets_de_otros['total'] +$value['total'];
                }
            }
        }

        unset($param['id_centro']);
        $param['id_cen_carnet']=$id_centro;
        $carnets_usados_en = $this->Intercentros_model->datos($param);
        $carnets_en_otros = ['num' => 0, 'total' => 0];
        if ($carnets_usados_en != 0) {
            foreach ($carnets_usados_en as $key => $value) {
                $carnets_en_otros['num'] = $carnets_en_otros['num'] +1;
                $carnets_en_otros['total'] = $carnets_en_otros['total'] +$value['total'];
            }
        }

        $return = [
            'carnets_de_otros'  => $carnets_de_otros,
            'carnets_de_central'=> $carnets_de_central,
            'carnets_en_otros'  => $carnets_en_otros,

        ];
        return $return;
    }

    function productos_en_venta($id_centro, $fecha_desde, $fecha_hasta){
        $familias = [1,2,4,6];
        $estado_pedido = ['Entregado','Facturado'];
        /*$this->db->select_sum('pedidos_productos.cantidad_entregada','total_productos');
        $this->db->select_sum('productos.precio_franquiciado_sin_iva * pedidos_productos.cantidad_entregada','total_pedido');*/
        $this->db->select('SUM(`pedidos_productos`.`cantidad_entregada`) AS total_productos, SUM(`productos`.`precio_franquiciado_sin_iva` * `pedidos_productos`.`cantidad_entregada`) AS total_pedido');
        $this->db->where_in('pedidos.estado', $estado_pedido);
        $this->db->where_in('productos_familias.id_familia_producto', $familias);
        $this->db->where('pedidos.id_centro', $id_centro);
        $this->db->where('pedidos.fecha_entrega >=', $fecha_desde);
        $this->db->where('pedidos.fecha_entrega <=', $fecha_hasta);
        $this->db->join('pedidos', 'pedidos_productos.id_pedido = pedidos.id_pedido');
        $this->db->join('productos', 'pedidos_productos.id_producto = productos.id_producto');
        $this->db->join('productos_familias', 'productos.id_familia_producto = productos_familias.id_familia_producto');
        $pedidos = $this->db->get('pedidos_productos')->row();

        /*$query="SELECT SUM(`pedidos_productos`.`cantidad_entregada`) AS total_productos, SUM(`productos`.`precio_franquiciado_sin_iva` * `pedidos_productos`.`cantidad_entregada`) AS total_pedido
FROM (`pedidos_productos`)
JOIN `pedidos` ON `pedidos_productos`.`id_pedido` = `pedidos`.`id_pedido`
JOIN `productos` ON `pedidos_productos`.`id_producto` = `productos`.`id_producto`
JOIN `productos_familias` ON `productos`.`id_familia_producto` = `productos_familias`.`id_familia_producto`
WHERE `pedidos`.`estado` IN ('Entregado', 'Facturado')
AND `productos_familias`.`id_familia_producto` IN (1, 2, 4, 6)
AND `pedidos`.`id_centro` =  '9'
AND `pedidos`.`fecha_entrega` >= '2019-05-01 00:00:00'
AND `pedidos`.`fecha_entrega` <= '2019-05-31 23:59:59'"*/
        $return = [
            'num' => $pedidos->total_productos,
            'total' => $pedidos->total_pedido
        ];
        return $return;
    }

    /*
    function instalar(){
        $this->db->where('nombre_modulo', 'Comparativa');
        $check = $this->db->get('modulos')->num_rows();;
        if($check == 0){
            // insertamos el modulo para el menu
            $data=[
                'nombre_modulo' => 'Comparativa',
                'url' => 'comparativa',
                'padre' => 'Estadísticas',
                'orden' => 6,
                'orden_item' => 0,
                'id_usuario_creacion' => 0,
                'fecha_creacion' => date('Y-m-d H:i:s'),
                'id_usuario_modificacion' => 0,
                'fecha_modificacion' => date('Y-m-d H:i:s'),
                'borrado' => 0,
            ];
            $this->db->insert('modulos', $data);

            $campos_index = [
                'id_cliente',
                'id_centro',
                'id_cita',
                'fecha_hora_concepto',
                'id_empleado',
                'id_servicio',
                'borrado',
                'estado'
            ];


            foreach ($campos_index as $key => $value) {
                $query = "ALTER TABLE dietario ADD INDEX (".$value.")";
                $this->db->query($query);
            }


            $campos_index = ['ape1cli','nomcli','codcli'];
            foreach ($campos_index as $key => $value) {
                $query = "ALTER TABLE clientes_historial_antiguo ADD INDEX (".$value.")";
                $this->db->query($query);
            }

            $campos_index = [
                'id_usuario_empleado',
                'id_servicio',
                'id_cita',
                'fecha_hora_inicio',
                'borrado',
                'estado'
            ];

            foreach ($campos_index as $key => $value) {
                $query = "ALTER TABLE citas ADD INDEX (".$value.")";
                $this->db->query($query);
            }

            echo "<hr><hr><hr>FIN DEL PROCESO DE INSTALACIÓN DEM MÓDULO Y OPTIMIZACIÓN DE TABLAS.<hr>";
        }else{
            echo '<!DOCTYPE html>
            <html lang="es">
                <head>
                    <meta charset="utf-8" />
                </head>
                <body>
                    <h3>El módulo Comparativa ya está instalado.</h3>
                </body>
            </html>';
        }
    }
    */
}

?>
