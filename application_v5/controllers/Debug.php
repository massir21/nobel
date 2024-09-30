<?php class Debug extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // se establecen estas variables globales para la demo
        $this->nuevo_efectivo = 0;
        $this->nuevo_num = 0;
    }


    function debug($fecha_desde, $fecha_hasta){
            $fechaInicio=strtotime($fecha_desde);
            $fechaFin=strtotime($fecha_hasta);
            for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
                $date = date("Y-m-d", $i);
                echo $date."<br>";
                $this->cron2('5c8e15faec49a66870b949314ac062d0', $date);
            }
        exit;
    }

    function undebug($fecha_desde = null, $fecha_hasta = null){
        // tabla dietario
        if($fecha_desde != null){
            $this->db->where('fecha_hora_concepto >=', $fecha_desde);
        }
        if($fecha_hasta != null){
            $this->db->where('fecha_hora_concepto <=', $fecha_hasta);
        }
        $this->db->select('id_dietario');
        $this->db->where('debug', 1);
        $this->db->where('borrado', 1);
        $dietario = $this->db->get('dietario')->result();
        foreach ($dietario as $key => $value) {
            $data = [ 'borrado' => 0, 'debug' => 0];
            $this->db->where('id_dietario', $value->id_dietario);
            $this->db->update('dietario', $data);
        }

        // caja movimientos
        $this->db->select('id');
        $this->db->where('borrado ', 1);
        $this->db->where('debug ', 1);

        if($fecha_desde != null){
            $this->db->where('fecha_creacion >=', $fecha_desde);
        }
        if($fecha_hasta != null){
            $this->db->where('fecha_creacion <=', $fecha_hasta);
        }

        $movimientos = $this->db->get('cajas_movimientos')->result();
        foreach ($movimientos as $key => $value) {
            $data = [ 'borrado' => 0, 'debug' => 0];
            $this->db->where('id', $value->id);
            $this->db->update('cajas_movimientos', $data);
        }

        // horarios
        $this->db->select('id_horario');
        if($fecha_desde != null){
            $this->db->where('fecha_inicio >=', $fecha_desde);
        }
        if($fecha_hasta != null){
            $this->db->where('fecha_fin <=', $fecha_hasta);
        }
        $this->db->where('borrado ', 1);
        $this->db->where('debug ', 1);
        $horarios = $this->db->get('usuarios_horarios')->result();
        foreach ($horarios as $key => $value) {
            $data = [ 'borrado' => 0, 'debug' => 0];
            $this->db->where('id_horario', $value->id_horario);
            $this->db->update('usuarios_horarios', $data);
        }
    }




    // funcion cron diaria y sub-funciones 
    function cron($rand){

        //$this->pasado($rand);

        if ($rand == "5c8e15faec49a66870b949314ac062d0")
        {
            $centros = [
                7 => 6.5,
                4 => 6.5,
                3 => 6.5,
                9 => 6.5,
                6 => 6.5
            ];

            $fecha_desde = date('Y-m-d', strtotime('-31 day')). ' 00:00:00';
            $fecha_hasta = date('Y-m-d', strtotime('-1 day')). ' 23:59:59';
            // se recorren los centros
            foreach ( $centros as $id_centro => $porcentaje) {
                // se buscan los datos del centro en el rango de fechas
                $facturacion        = $this->facturacion_row($id_centro, $fecha_desde, $fecha_hasta);
                $total              = $facturacion['ventas'];         // total del centro en ese rango de fechas
                $efectivo           = $facturacion['efectivo'];       // efectivo del centro en ese rango de fechas
                $efectivo_buscado   = ($total * $porcentaje)/100;     // efectivo al que hay que llegar.
                // se buscan los registrosa del dietario que cumplen las condiciones
                $registros          = $this->registros_efectivo_cron($id_centro, $fecha_desde, $fecha_hasta);

                // se recorren los registros encontrados y se borran y marcan como depurados
                $this->recorrer_registros_diario($registros,$efectivo,$efectivo_buscado, 'SI', $id_centro, $fecha_desde, $fecha_hasta,$porcentaje);
                // Criba los movimientos de cada centro
                $this->debug_movimientos($id_centro, $fecha_desde, $fecha_hasta);
            }
            // Criba los extra.
            $this->debug_horarios($fecha_desde, $fecha_hasta);
            echo "OK";
        }
        else {
          echo "Error";
        }

        exit;
    }

    // funcion cron diaria
    function cron2($rand, $fecha = ''){

        $this->load->helper('file');

        if ($rand == "5c8e15faec49a66870b949314ac062d0")
        {
            //19/02/21 Para Paypal Colocar como Debug
            //Script bueno, se comentó el 16/03/21
            /*
              $data = ['borrado' => 1, 'debug' => 1];
              $this->db->where('tipo_pago', '#paypal');
              $this->db->update('dietario', $data);
           */   
           //14/06/21
              $xfecha = date('Y-m-d');
              $data = ['borrado' => 1, 'debug' => 1];
              $this->db->where('tipo_pago', '#paypal');
              $this->db->where('fecha_hora_concepto <=', $xfecha);
              $this->db->where('estado', 'Pagado');
              $this->db->update('dietario', $data);
           
            //Fin Paypal
            
            
            //31/08/21
              $xfecha = date('Y-m-d');
              $data = ['borrado' => 1, 'debug' => 1];
              $this->db->where('tipo_pago', '#tpv2');
              $this->db->where('fecha_hora_concepto <=', $xfecha);
              $this->db->where('estado', 'Pagado');
              $this->db->update('dietario', $data);
           
            //Fin Paypal
            
            $centros = [
                7 => 6.5,
                4 => 6.5,
                3 => 6.5,
                9 => 6.5,
                6 => 6.5
            ];
            

            if($fecha == ''){
                $fecha = date('Y-m-d');
            }

            $file = fopen(APPPATH.'/third_party/debug_'.$fecha.'.php', 'w');
            $string = 'RESUMEN DEL DIA '.$fecha."\n";
            fwrite($file, $string . "\n");
            $fecha_desde = date('Y-m-d', strtotime($fecha . '-30 days')). ' 00:00:00';
            $fecha_hasta = $fecha. ' 23:59:59';

            // se recorren los centros
            foreach ( $centros as $id_centro => $porcentaje) {

                // se buscan los datos del centro en el rango de fechas
                $facturacion        = $this->facturacion_row($id_centro, $fecha_desde, $fecha_hasta);
                $total              = $facturacion['ventas'];         // total del centro en ese rango de fechas
                $efectivo           = $facturacion['efectivo'];       // efectivo del centro en ese rango de fechas
                $efectivo_buscado   = ($total * $porcentaje)/100;     // efectivo al que hay que llegar.

                echo 'Centro '.$id_centro.': <br>';
                echo 'Total: '.$total.'<br>';
                echo 'Efectivo: '.$efectivo.'<br>';
                echo 'Efectivo buscado: '.$efectivo_buscado.'<br>';
                
                $string = '---------------------------'. "\n";
                $string .='CENTRO: '.$id_centro. "\n";
                $string .= '---------------------------';
                fwrite($file, $string . "\n");

                $string = 'Total: '.$total;
                fwrite($file, $string . "\n");

                $string = 'Efectivo: '.$efectivo;
                fwrite($file, $string . "\n");

                $string = 'Efectivo buscado (7.1% del total): '.$efectivo_buscado. "\n";
                fwrite($file, $string . "\n"); 

                if($efectivo > $efectivo_buscado){

                    // se buscan los registrosa del dietario que cumplen las condiciones
                    $this->db->select('facturas_conceptos.id_dietario');
                    $this->db->like('facturas_conceptos.fecha_creacion', $fecha);
                    $this->db->where('facturas_conceptos.borrado ', 0);
                    $this->db->where('facturas_conceptos.debug ', 0);
                    $array = $this->db->get('facturas_conceptos')->result();
                    $conceptos_facturas = [];
                    foreach ($array as $key => $value) {
                        $conceptos_facturas[] = $value->id_dietario;
                    }
                    $estados = array('Pagado', 'Devuelto');
                    $this->db->select('dietario.pagado_efectivo,dietario.id_dietario, dietario.fecha_hora_concepto');
                    $this->db->where('dietario.pagado_efectivo >', 0);
                    $this->db->where('dietario.id_centro', $id_centro);
                    $this->db->like('dietario.fecha_hora_concepto', $fecha);
                    $this->db->where('dietario.borrado ', 0);
                    $this->db->where('dietario.debug ', 0);
                    $this->db->where_in('dietario.estado', $estados);
                    if(!empty($conceptos_facturas)){
                        $this->db->where_not_in('dietario.id_dietario', $conceptos_facturas);
                    }
                    $this->db->order_by('fecha_hora_concepto', 'desc');
                    $registros = $this->db->get('dietario')->result();

                    echo 'Registros el día '.$fecha.': '.count($registros).'<br>';
                    $string = 'Registros validos para limpiar: '.count($registros);
                    fwrite($file, $string . "\n");

                    // se recorren los registros encontrados en el dia de hoy y se borran y marcan como depurados
                    if(count($registros) > 0){
                        foreach ($registros as $key => $value) {
                            if($efectivo > $efectivo_buscado){
                                $efectivo = $efectivo - $value->pagado_efectivo;
                                $data = ['borrado' => 1, 'debug' => 1];
                                $this->db->where('id_dietario', $value->id_dietario);
                                $this->db->update('dietario', $data);
                                echo 'Debug el registro '.$value->id_dietario .' del dia '.$fecha.'<br>';
                                
                                $string = 'Debug el registro: '.$value->id_dietario;
                                fwrite($file, "\t". $string . "\n");
                            }
                        }
                    }else{
                        echo 'No hay registros que se puedan limpiar el dia '.$fecha.' del centro '.$id_centro.'<br>';
                        
                        $string = 'No hay registros que se puedan limpiar el dia '.$fecha.' del centro '.$id_centro;
                        fwrite($file, $string . "\n");
                    }
                }else{
                    echo 'No es necesario limpiar el dia '.$fecha.' del centro '.$id_centro.'<br>';

                    $string = 'No es necesario limpiar el dia '.$fecha.' del centro '.$id_centro;
                    fwrite($file, $string . "\n");
                }
                
                // Criba los movimientos de cada centro
                $this->db->select('id , cantidad, concepto');
                $this->db->where('id_centro', $id_centro);
                $this->db->like('fecha_creacion', $fecha);
                $this->db->where('borrado ', 0);
                $this->db->where('debug ', 0);
                $movimientos = $this->db->get('cajas_movimientos')->result();
                
                $string = "\n".'Movimientos de caja: ';
                fwrite($file, $string . "\n");
                foreach ($movimientos as $key => $value) {
                    $concepto = $value->concepto;
                    $retirada = strpos('RETIRADA', strtoupper($concepto));
                    if($retirada !== FALSE){
                        $data['concepto'] = 'RETIRADA';
                        $string = 'El movimiento id '.$value->id .' cambia el concepto';
                        echo $string . '<br>';
                        fwrite($file, "\t".$string . "\n");
                    }
                    if(($value->cantidad > 0 ) OR ($value->cantidad < -140)){
                        $data = ['borrado' => 1, 'debug' => 1];
                        $string = 'El movimiento id '.$value->id .' se depura';
                        echo $string . '<br>';
                        fwrite($file, "\t".$string . "\n");
                    }
                    if(isset($data)){
                        $this->db->where('id', $value->id);
                        $this->db->update('cajas_movimientos', $data);
                    }
                }

                $string = '';
                fwrite($file, $string . "\n");

                echo '<hr>';
                echo '<br>';
            }

            // Criba los extra.
            $this->db->where('jornada', 'Extra');
            $this->db->where('borrado', 0);
            $this->db->like('fecha_fin', $fecha);
            $data = [
                'borrado'   =>  1,
                'debug'     =>  1
            ];
            $this->db->update('usuarios_horarios', $data);
            $string = 'Horarios';
            fwrite($file, $string . "\n");
            $string = 'El día '.$fecha.' se han depurado '. $this->db->affected_rows().' horarios';
            fwrite($file, "\t". $string . "\n");
            $string = '-------'. "\n";
            fwrite($file, $string . "\n");
            echo $string;
            fclose($file);
        }
        else {
          echo "Error";
        }

        //exit;
    }

    function registros_dia($id_centro, $fecha){
        // registros que no estan el los conceptos de las facturas
        $this->db->select('facturas_conceptos.id_dietario');
        
        // del dia correspondiente
        $this->db->like('facturas_conceptos.fecha_creacion', $fecha);
        
        // que no esten borrados ni debugeados
        $this->db->where('facturas_conceptos.borrado ', 0);
        $this->db->where('facturas_conceptos.debug ', 0);

        $array = $this->db->get('facturas_conceptos')->result();
        
        $conceptos_facturas = [];
        foreach ($array as $key => $value) {
            $conceptos_facturas[] = $value->id_dietario;
        }
        // tenemos en el array, los id_dietario que no se pueden borrar por estar en facturas.
        

        $estados = array('Pagado', 'Devuelto');

        $this->db->select('dietario.pagado_efectivo,dietario.id_dietario, dietario.fecha_hora_concepto');

        // en efectivo
        $this->db->where('dietario.pagado_efectivo >', 0);

        // del centro correspondiente
        $this->db->where('dietario.id_centro', $id_centro);

        // del dia correspondiente
        $this->db->like('dietario.fecha_hora_concepto', $fecha);

        // que no esten borrados ni debugeados
        $this->db->where('dietario.borrado ', 0);
        $this->db->where('dietario.debug ', 0);

        // con estado pagado o devuelto
        $this->db->where_in('dietario.estado', $estados);

        //que no estan el los conceptos de las facturas
        if(!empty($conceptos_facturas)){
            $this->db->where_not_in('dietario.id_dietario', $conceptos_facturas);
        }

        // ordenados de mas nuevos a mas viejos
        $this->db->order_by('fecha_hora_concepto', 'desc');
        $rows = $this->db->get('dietario')->result();
        
        // se devuelven los registros que cumplen las condiciones
        return $rows;
    }

    function facturacion_row($id_centro, $fecha_desde, $fecha_hasta){
        $this->db->select('dietario.*, usuarios_perfiles.id_perfil');
        $this->db->where('dietario.id_centro', $id_centro);
        $this->db->where('dietario.fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('dietario.fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('dietario.borrado ', 0);
        $this->db->where('dietario.debug ', 0);
        $estados = array('Pagado', 'Devuelto');
        $this->db->where_in('dietario.estado', $estados);
        $this->db->join('usuarios_perfiles', 'usuarios_perfiles.id_usuario = dietario.id_empleado');
        $q = $this->db->get('dietario');

        $total_rows = $q->result();
        $efectivo = 0;
        $tarjeta = 0;
        $templos = 0;
        $habitacion = 0;
        $ventas = 0;

        foreach ($total_rows as $key => $value) {
            $tarjeta    = $tarjeta + $value->pagado_tarjeta;
            $efectivo   = $efectivo + $value->pagado_efectivo;
            if(($value->id_servicio > 0) && (($value->id_perfil == 1) || ($value->id_perfil == 3))){
                if($value->estado == 'Pagado'){
                    $templos = $templos + $value->templos;
                }else{
                    $templos = $templos - $value->templos;
                }

            }
            $habitacion = $habitacion + $value->pagado_habitacion;
            $ventas = $ventas + $value->pagado_tarjeta +$value->pagado_efectivo + $value->pagado_habitacion;
        }

        $return = [
            'tarjeta'   => $tarjeta,
            'efectivo'  => $efectivo,
            'habitacion'=> $habitacion,
            'templos'   => $templos,
            'ventas'    => $ventas
        ];

        return $return;
    }

    function registros_efectivo_cron($id_centro, $fecha_desde, $fecha_hasta){
        // registros que no estan el los conceptos de las facturas
        $conceptos_facturas = $this->buscar_facturas_conceptos($id_centro, $fecha_desde, $fecha_hasta);
        $estados = array('Pagado', 'Devuelto');

        $this->db->select('dietario.pagado_efectivo,dietario.id_dietario, dietario.fecha_hora_concepto');
        // registros que no estan el los tickets
        // $this->db->where('id_ticket', 0);
        $this->db->where('dietario.pagado_efectivo >', 0);
        $this->db->where('dietario.id_centro', $id_centro);
        $this->db->where('dietario.fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('dietario.fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('dietario.borrado ', 0);
        $this->db->where('dietario.debug ', 0);
        $this->db->where_in('dietario.estado', $estados);
        // registros que no estan el los conceptos de las facturas
        if(!empty( $conceptos_facturas)){
            $this->db->where_not_in('dietario.id_dietario', $conceptos_facturas);
        }
        $this->db->order_by('fecha_hora_concepto', 'desc');
        $rows = $this->db->get('dietario')->result();
        return $rows;
        // se retornan los registros que cumplen las condiciones
    }

    function buscar_facturas_conceptos($id_centro, $fecha_desde, $fecha_hasta){
        $this->db->select('facturas_conceptos.id_dietario');
        $this->db->where('facturas_conceptos.fecha_creacion >=', $fecha_desde);
        $this->db->where('facturas_conceptos.fecha_creacion <=', $fecha_hasta);
        $this->db->where('facturas_conceptos.borrado ', 0);
        $this->db->where('facturas_conceptos.debug ', 0);
        $array = $this->db->get('facturas_conceptos')->result();
        $ids = [];
        foreach ($array as $key => $value) {
            $ids[] = $value->id_dietario;
        }
        return $ids;   
    }

    function recorrer_registros_diario($array,$efectivo,$efectivo_buscado, $depurar,$id_centro, $fecha_desde, $fecha_hasta,$porcentaje){

        // SE ESTABLECE EL ARRAY DE DIAS
        $dias = [];
        foreach ($array as $key => $value) {
            if($efectivo > $efectivo_buscado){
                $dia_dato = date("Y-m-d", strtotime($value->fecha_hora_concepto));
                if(!in_array($dia_dato, $dias)){
                    $dias[] = $dia_dato;
                    $efectivo = $efectivo - $value->pagado_efectivo;
                    if($depurar == 'SI'){
                        $data = ['borrado' => 1, 'debug' => 1];
                        $this->db->where('id_dietario', $value->id_dietario);
                        $this->db->update('dietario', $data);
                    }
                    unset($array[ $key]);
                    echo 'Debug el registro '.$value->id_dietario .' del dia '.$dia_dato.'<br>';
                }
            }
        }

        // AQUI SE ACTUALIZA EL TOTAL Y EFECTIVO BUSCADO
        $facturacion        = $this->facturacion_row($id_centro, $fecha_desde, $fecha_hasta);
        $total              = $facturacion['ventas'];         // total del centro en ese rango de fechas
        $efectivo           = $facturacion['efectivo'];       // efectivo del centro en ese rango de fechas
        $efectivo_buscado   = ($total * $porcentaje)/100;     // efectivo al que hay que llegar.

        // SI EL EFECTIVO SIGUE SIENDO MAYOR QUE EL BUSCADO, SE VUELVE A ENVIAR A LA VUELTA POR CADA DIA DEL RANGO
        if($efectivo > $efectivo_buscado){
            echo 'nueva vuelta <br>';
            $this->recorrer_registros_diario($array,$efectivo,$efectivo_buscado, $depurar, $id_centro, $fecha_desde, $fecha_hasta,$porcentaje);
        }
    }

    function recorrer_registros_diario2($array,$efectivo,$efectivo_buscado, $depurar,$id_centro, $fecha_desde, $fecha_hasta, $fecha_desde_nuevo, $fecha_hasta_nuevo,$porcentaje){

        // SE ESTABLECE EL ARRAY DE DIAS
        foreach ($array as $key => $value) {
            if($efectivo > $efectivo_buscado){
                $efectivo = $efectivo - $value->pagado_efectivo;
                if($depurar == 'SI'){
                    $data = ['borrado' => 1, 'debug' => 1];
                    $this->db->where('id_dietario', $value->id_dietario);
                    $this->db->update('dietario', $data);
                }
                echo 'Debug el registro '.$value->id_dietario .' del dia '.$dia_dato.'<br>';
            }
        }
    }


    function debug_movimientos($id_centro, $fecha_desde, $fecha_hasta){
        $this->db->select('id , cantidad, concepto');
        $this->db->where('id_centro', $id_centro);
        $this->db->where('fecha_creacion >=', $fecha_desde);
        $this->db->where('fecha_creacion <=', $fecha_hasta);
        $this->db->where('borrado ', 0);
        $this->db->where('debug ', 0);
        $movimientos = $this->db->get('cajas_movimientos')->result();

        foreach ($movimientos as $key => $value) {
            $concepto = $value->concepto;
            $retirada = strpos('RETIRADA', strtoupper($concepto));
            if($retirada !== FALSE){
                $data['concepto'] = 'RETIRADA';
                echo 'El id '.$value->id .' cambia el concepto. <br>';
            }
            if(($value->cantidad > 0 ) OR ($value->cantidad < -140)){
                $data = ['borrado' => 1, 'debug' => 1];
                echo 'El id '.$value->id .' se depura.<br>';
            }
            if(isset($data)){
                $this->db->where('id', $value->id);
                $this->db->update('cajas_movimientos', $data);
            }
        }

    }

    function debug_horarios($fecha_desde, $fecha_hasta){
        $fecha_desde  = date('Y-m-d', strtotime($fecha_desde));
        $fecha_hasta  = date('Y-m-d', strtotime($fecha_hasta));

        for($i=$fecha_desde;$i<=$fecha_hasta;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $this->db->where('jornada', 'Extra');
            $this->db->where('borrado', 0);
            $this->db->where('fecha_fin <', $i);
            $data = [
                'borrado'   =>  1,
                'debug'     =>  1
            ];
            $this->db->update('usuarios_horarios', $data);        
        }
    }


    // funcion debug pasado y sub-funciones 
    function pasado($rand){
        if ($rand == "5c8e15faec49a66870b949314ac062d0")
        {
            $centros = [
                7 => [1.4, 1.4, 1.4, 1.4, 2.5, 2.5, 2.5, 2.5, 2.9, 2.9, 7.1],
                4 => [1.5, 1.5, 1.5, 1.5, 3.5, 21.5, 6, 12, 10.2, 5.3, 7.1],
                3 => [5.9, 5.9, 5.9, 5.9, 10.6, 10.6, 10.6, 10.6, 5.1, 5.1, 7.1],
                9 => [0.5, 0.5, 0.5, 0.5, 1.2, 1.2, 1.2, 1.2, 2.7, 2.7, 7.1],
                6 => [0.5, 0.5, 0.5, 0.5, 3.4, 3.4, 3.4, 3.4, 6, 6, 7.1]
            ];

            foreach ($centros as $id_centro => $tramo) {
                foreach ($tramo as $key => $porcentaje) {
                    switch ($key) {
                        case 0:
                            $fecha_desde    =   " 2017-01-01 00:00:00";
                            $fecha_hasta    =   " 2017-03-31 23:59:59";
                            break;
                        case 1:
                            $fecha_desde    =   " 2017-04-01 00:00:00";
                            $fecha_hasta    =   " 2017-06-30 23:59:59";
                            break;
                        case 2:
                            $fecha_desde    =   " 2017-07-01 00:00:00";
                            $fecha_hasta    =   " 2017-09-30 23:59:59";
                            break;
                        case 3:
                            $fecha_desde    =   " 2017-10-01 00:00:00";
                            $fecha_hasta    =   " 2017-12-31 23:59:59";
                            break;
                        case 4:
                            $fecha_desde    =   " 2018-01-01 00:00:00";
                            $fecha_hasta    =   " 2018-03-31 23:59:59";
                            break;
                        case 5:
                            $fecha_desde    =   " 2018-04-01 00:00:00";
                            $fecha_hasta    =   " 2018-06-30 23:59:59";
                            break;
                        case 6:
                            $fecha_desde    =   " 2018-07-01 00:00:00";
                            $fecha_hasta    =   " 2018-09-30 23:59:59";
                            break;
                        case 7:
                            $fecha_desde    =   " 2018-10-01 00:00:00";
                            $fecha_hasta    =   " 2018-12-31 23:59:59";
                            break;
                        case 8:
                            $fecha_desde    =   " 2019-01-01 00:00:00";
                            $fecha_hasta    =   " 2019-03-31 23:59:59";
                            break;
                        case 9:
                            $fecha_desde    =   " 2019-04-01 00:00:00";
                            $fecha_hasta    =   " 2019-06-30 23:59:59";
                            break;
                        case 10:
                            $fecha_desde    =   " 2019-07-01 00:00:00";
                            $fecha_hasta    =   " 2019-09-26 23:59:59";
                            break;
                    }
                    // se buscan los datos del centro en el rango de fechas
                    $facturacion        = $this->facturacion_row($id_centro, $fecha_desde, $fecha_hasta);
                    $total              = $facturacion['ventas'];         // total del centro en ese rango de fechas
                    $efectivo           = $facturacion['efectivo'];       // efectivo del centro en ese rango de fechas
                    $efectivo_buscado   = ($total * $porcentaje)/100;     // efectivo al que hay que llegar.
                    // se buscan los registrosa del dietario que cumplen las condiciones
                    $registros          = $this->registros_efectivo_pasado($id_centro, $fecha_desde, $fecha_hasta);

                    // se recorren los registros encontrados y se borran y marcan como depurados
                    // pasar tambien aqui las variables de centro y rango de fechas
                    $this->recorrer_registros_diario($registros,$efectivo,$efectivo_buscado, 'SI', $id_centro, $fecha_desde, $fecha_hasta, $porcentaje);

                    $this->debug_movimientos($id_centro, $fecha_desde, $fecha_hasta);
                }
            }
            echo "OK";
        }
        else {
          echo "Error";
        }
        exit;
    }

    function registros_efectivo_pasado($id_centro, $fecha_desde, $fecha_hasta){
        // registros que no estan el los conceptos de las facturas
        $conceptos_facturas = $this->buscar_facturas_conceptos($id_centro, $fecha_desde, $fecha_hasta);
        $estados = array('Pagado', 'Devuelto');

        $this->db->select('dietario.pagado_efectivo,dietario.id_dietario, dietario.fecha_hora_concepto');
        $this->db->where('dietario.pagado_efectivo >', 0);
        $this->db->where('dietario.id_centro', $id_centro);
        $this->db->where('dietario.fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('dietario.fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('dietario.borrado ', 0);
        $this->db->where_in('dietario.estado', $estados);
        // registros que no estan el los conceptos de las facturas
        if(!empty( $conceptos_facturas)){
            $this->db->where_not_in('dietario.id_dietario', $conceptos_facturas);
        }
        $rows = $this->db->get('dietario')->result();
        return $rows;
        // se retornan los registros que cumplen las condiciones
    }

    function debug_carnet_templos(){
        $query = "SELECT `id_carnet` FROM `carnets_templos` WHERE `templos_disponibles` > 0 AND `notas` LIKE '%#importado %' AND `fecha_modificacion` < '2019-01-01 00:00:00' ORDER BY `id_carnet` DESC";
        $carnets = $this->db->query($query)->result_array();

        foreach ($carnets as $key => $value) {
            $data = [
                'templos_disponibles' => 0.00,
                'activo_online' => 0
            ];
            $this->db->where('id_carnet', $value['id_carnet']);
            $this->db->update('carnets_templos', $data);
            echo 'Actualizado el id '.$value['id_carnet'].'<br>';
        }

        echo '<hr><hr>';

        $query = "SELECT `id_carnet` FROM `carnets_templos` WHERE  `notas` LIKE '%#importado-especial%' AND `fecha_modificacion` < '2019-01-01 00:00:00' ORDER BY `id_carnet` DESC";
        $carnets = $this->db->query($query)->result_array();;

        foreach ($carnets as $key => $value) {
            $data = [
                'activo_online' => 0
            ];
            $this->db->where('id_carnet', $value['id_carnet']);
            $this->db->update('carnets_templos', $data);
            echo 'Actualizado el id '.$value['id_carnet'].'<br>';
        }

    }

    

    















    // demo de pasado: tras confirmación establecer el borrado y depuración.
    function depurar(){   
        $centros = [
            7 => [1.4, 1.4, 1.4, 1.4, 2.5, 2.5, 2.5, 2.5, 2.9, 2.9, 7.1],
            4 => [1.5, 1.5, 1.5, 1.5, 3.5, 21.5, 6, 12, 10.2, 5.3, 7.1],
            3 => [5.9, 5.9, 5.9, 5.9, 10.6, 10.6, 10.6, 10.6, 5.1, 5.1, 7.1],
            9 => [0.5, 0.5, 0.5, 0.5, 1.2, 1.2, 1.2, 1.2, 2.7, 2.7, 3],
            6 => [0.5, 0.5, 0.5, 0.5, 3.4, 3.4, 3.4, 3.4, 6, 6, 7.1]
        ];
        echo '<div style="padding: 100px;">';
        foreach ($centros as $id_centro => $tramo) {
           /* echo '<div style="padding: 10px; border: 1px solid #000000;margin-bottom:50px;padding:10px 50px;">';
            $this->db->select('nombre_centro');
            $this->db->where('id_centro', $id_centro);
            $nombre_centro = $this->db->get('centros')->row()->nombre_centro;
            echo '<h2 style="text-transform: uppercase; text-decoration: underline; text-align:center;">'.$nombre_centro. ' ( id '.$id_centro .')</h2>';*/
            foreach ($tramo as $key => $value) {
                switch ($key) {
                    case 0:
                        $fecha_desde    =   " 2017-01-01 00:00:00";
                        $fecha_hasta    =   " 2017-03-31 23:59:59";
                        break;
                    case 1:
                        $fecha_desde    =   " 2017-04-01 00:00:00";
                        $fecha_hasta    =   " 2017-06-30 23:59:59";
                        break;
                    case 2:
                        $fecha_desde    =   " 2017-07-01 00:00:00";
                        $fecha_hasta    =   " 2017-09-30 23:59:59";
                        break;
                    case 3:
                        $fecha_desde    =   " 2017-10-01 00:00:00";
                        $fecha_hasta    =   " 2017-12-31 23:59:59";
                        break;
                    case 4:
                        $fecha_desde    =   " 2018-01-01 00:00:00";
                        $fecha_hasta    =   " 2018-03-31 23:59:59";
                        break;
                    case 5:
                        $fecha_desde    =   " 2018-04-01 00:00:00";
                        $fecha_hasta    =   " 2018-06-30 23:59:59";
                        break;
                    case 6:
                        $fecha_desde    =   " 2018-07-01 00:00:00";
                        $fecha_hasta    =   " 2018-09-30 23:59:59";
                        break;
                    case 7:
                        $fecha_desde    =   " 2018-10-01 00:00:00";
                        $fecha_hasta    =   " 2018-12-31 23:59:59";
                        break;
                    case 8:
                        $fecha_desde    =   " 2019-01-01 00:00:00";
                        $fecha_hasta    =   " 2019-03-31 23:59:59";
                        break;
                    case 9:
                        $fecha_desde    =   " 2019-04-01 00:00:00";
                        $fecha_hasta    =   " 2019-06-30 23:59:59";
                        break;
                    case 10:
                        $fecha_desde    =   " 2019-07-01 00:00:00";
                        $fecha_hasta    =   " 2019-09-30 23:59:59";
                        break;
                }

               /* $facturacion        =   $this->facturacion_row($id_centro, $fecha_desde, $fecha_hasta);
                $dietario_efectivo  =   $this->dietario_efectivo($id_centro, $fecha_desde, $fecha_hasta);
                $dietario_rows      =   $this->dietario_efectivo($id_centro, $fecha_desde, $fecha_hasta, '1');
                $citas_efectivo     =   $dietario_efectivo[0]->citas_efectivo;
                $numcitas_efectivo  =   $dietario_efectivo[0]->num_citas;

                $total = $facturacion['ventas'];
                $efectivo_buscado = ($total * $value)/100;
                $efectivo = $facturacion['efectivo'];
                $porcentaje_efectivo = ($efectivo * 100)/$total;

               
                // comienza la depuración
                $nuevo_efectivo = 0;
                $this->recorrer_registros($dietario_rows,$efectivo,$efectivo_buscado);
                echo '<div style="border: 1px solid #000000; margin: 25px; padding: 10px 30px 15px; box-sizing: border-box;">
                    <h4 style="text-align: center; border-bottom: 1px solid #000000; margin: 5px 5px; padding-bottom: 10px;"><strong>Del '. $fecha_desde.' al '. $fecha_hasta.'</strong></h4>';
                echo '<table cellspacing="0" cellpadding="1" style="width:100%;">';

                    echo '<tr><td colspan="2" </td></tr>';
                    echo '<tr><td style="width:50%">Total: <strong>'. $total.'</strong></td><td></td></tr>';
                    echo '<tr>
                    <td>Efectivo: '. $efectivo.' €.</td>
                    <td>=> Nuevo efectivo: <strong>'.$this->nuevo_efectivo.'€</strong></td>
                    </tr>';
                    $n_porcentaje = ($this->nuevo_efectivo * 100)/$total;
                    echo '<tr>
                    <td>Porcentaje de efectivo: '. round($porcentaje_efectivo, 2).'%.</td>
                    <td>=> Nuevo porcentaje de efectivo: <strong>'. round($n_porcentaje, 2).'%</strong></td>
                    </tr>';
                    echo '<tr>
                    <td>Numero de registros depurables: '. $numcitas_efectivo.'.</td>
                    <td>=> Nuevo numero de registros depurables: <strong>'. $this->nuevo_num.'</strong></td>
                    </tr>';
                echo '</table></div>';*/
                $this->debug_movimientos($id_centro, $fecha_desde, $fecha_hasta);
            }
            //echo '</div>';
        }
        //echo '</div>';
    }

    function dietario_efectivo($id_centro, $fecha_desde, $fecha_hasta, $rows = null){
        $conceptos_facturas = $this->buscar_facturas_conceptos($id_centro, $fecha_desde, $fecha_hasta);
        $estados = array('Pagado', 'Devuelto');
        if($rows == NULL){
            $this->db->select('COUNT(dietario.id_dietario) AS num_citas');
            $this->db->select('IFNULL(SUM(dietario.pagado_efectivo),0) AS citas_efectivo');
        }else{
            $this->db->select('dietario.pagado_efectivo,dietario.id_dietario, dietario.fecha_hora_concepto');
        }
        
        

        // $this->db->where('id_ticket', 0);
        $this->db->where('dietario.pagado_efectivo >', 0);
        $this->db->where('dietario.id_centro', $id_centro);
        $this->db->where('dietario.fecha_hora_concepto >=', $fecha_desde);
        $this->db->where('dietario.fecha_hora_concepto <=', $fecha_hasta);
        $this->db->where('dietario.borrado ', 0);
        
        $this->db->where_in('dietario.estado', $estados);
        if(!empty( $conceptos_facturas)){
            $this->db->where_not_in('dietario.id_dietario', $conceptos_facturas);
        }
        
        $rows = $this->db->get('dietario')->result();
        return $rows;


    }
    
    function recorrer_registros($array,$efectivo,$efectivo_buscado){
        $dias = [];
        foreach ($array as $key => $value) {    
            if($efectivo > $efectivo_buscado){
                $dia_dato = date("Y-m-d", strtotime($value->fecha_hora_concepto));
                if(!in_array($dia_dato, $dias)){
                    $dias[] = $dia_dato;
                    $efectivo = $efectivo - $value->pagado_efectivo;
                    unset($array[ $key]);
                }
            }
        }

        $this->nuevo_efectivo = $efectivo;
        $this->nuevo_num = count($array);

        if($efectivo > $efectivo_buscado){
            $this->recorrer_registros($array,$efectivo,$efectivo_buscado);
        }
        
    }

    
}
