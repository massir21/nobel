<?php
class Dietario extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... DIETARIO
    // ----------------------------------------------------------------------------- //
    public function index($accion = null, $fecha = null, $id_centro = null, $id_usuario = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['hoy'] = date("d-m-Y");

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $param['id_centro'] = $id_centro;
            }
        }
        
        $data['hoy_aaaammdd'] = $param['fecha'];
        
        if ($id_usuario >= null && $id_usuario > 0 ){
            $param['id_empleado'] = $id_usuario;
            $param['estado'] = 'Pendiente';
            if ( isset($param['fecha']) ){
                unset($param['fecha']);
            }
            
            $data['id_usuario'] = $id_usuario;
        }
        
        $dietario = $this->Dietario_model->leer($param);

        $dientesPorDietario=[];
        if(is_array($dietario) && count($dietario)){
            foreach($dietario as $entradaDietario){
                $dientesPorDietario[$entradaDietario['id_dietario']]=null;
            }
        }
        if(is_array($dietario) && count($dietario)) {
            $dientesPorDietario = $this->Dietario_model->getDientesForDietario($dientesPorDietario);
            foreach ($dietario as $key => $unDietario) {
                $dietario[$key]['dientes'] = $dientesPorDietario[$unDietario['id_dietario']];
            }
        }
        
        // ... Aqui pasamos el historial del dietario para que nos lo devuelva con
        // los numero de carnets usados para cada pago
        unset($param);
        $param['historial'] = $dietario;
        $data['dietario'] = $this->Dietario_model->carnets_pago_templos($param);

        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        $data['id_centro'] = $id_centro;
        
        
        //Filtro usuarios
        $data['usuarios_no_borrados'] = $this->Usuarios_model->leer_usuarios(array('borrado' => 0));


        // ... Viewer con el contenido
        $data['pagetitle'] = 'Dietario';
        $data['content_view'] = $this->load->view('dietario/dietario_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Mostramos la ficha del cliente con todo lo que haya podido ocurrir en el día sobre él
    // ----------------------------------------------------------------------------- //
    public function ficha($accion = null, $id_cliente = null, $fecha = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;
        $param['no_pagado'] = 2;
        $data['registros'] = $this->Dietario_model->leer($param);
        //printr( $data['registros']);
        // --------------------------------------------------------------------------
        // ... Indicamos con los carnets que se ha pagado una cita online.
        // --------------------------------------------------------------------------
        if ($data['registros'] != 0) {
            for ($i = 0; $i < count($data['registros']); $i++) {
                if ($data['registros'][$i]['id_pedido'] > 0) {
                    unset($param3);
                    $param3['id_dietario'] = $data['registros'][$i]['id_dietario'];
                    $carnets = $this->Carnets_model->leer_carnets_pago_dietario($param3);

                    $data['registros'][$i]['carnets_pagos'] = $carnets;
                }
            }
        }
        // --------------------------------------------------------------------------

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        if ($fecha == null) {
            $data['hoy'] = date("d-m-Y");
            $data['hoy_aaaammdd'] = date("Y-m-d");
        } else {
            $data['hoy'] = $fecha;
            $data['hoy_aaaammdd'] = $fecha;
        }

        $data['accion'] = $accion;
        $data['id_cliente'] = $id_cliente;
        $data['fecha_completa'] = $this->Utiles_model->fecha_completa($data['hoy_aaaammdd']);

        //
        // Notas de cobro del cliente
        //
        if (isset($id_cliente)) {
            if ($id_cliente > 0) {
                unset($param_notas);
                $param_notas['id_cliente'] = $id_cliente;
                $param_notas['estado'] = "Pendiente";
                $data['notas_cobrar'] = $this->Clientes_model->notas_cobrar($param_notas);
            }
        }

        //
        // ... Leemos los datos del ultimo ticket, para oferecer imprimirlo.
        //
        $data['ticket_ultimo'] = $this->Dietario_model->UltimoTicketCliente($id_cliente);
        $data['saldo_cliente'] = $this->Clientes_model->saldo($id_cliente);
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_cobrar_saldo_view', $data);
            //$this->load->view('dietario/dietario_ficha_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function pagosaldo($accion = null, $id_cliente = null, $fecha = null)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;
        $param['no_pagado'] = 1;
        $data['registros'] = $this->Dietario_model->leer($param);

        $data['saldo_cliente'] = $this->Clientes_model->saldo($id_cliente);
        if ($accion == "guardar") {
            unset($parametros);
            $parametros = $_POST;
            $saldodisponible = $data['saldo_cliente'];
            if(is_array($data['registros'])){
                for ($i = 0; $i < count($data['registros']); $i++) {
                    $sw = 0;
                    if (isset($parametros['marcados'])) {
                        foreach ($parametros['marcados'] as $key => $row) {
                            if ($row != "" && $row == $data['registros'][$i]['id_dietario']) {
                                $sw = 1;
                            }
                        }
                    }
                    if ($sw == 0) {
                        unset($param);
                        $param['id_dietario'] = $data['registros'][$i]['id_dietario'];
                        $param['estado'] = "No Pagado";
                        $ok = $this->Dietario_model->marcar_no_pagado($param);
                    }
                }
            }

            if (isset($parametros['marcados'])) {
                unset($param);
                $param['marcados'] = $parametros['marcados'];
                $param['importe_euros'] = $parametros['importe_euros'];
                $param['descuento_euros'] = $parametros['descuento_euros'];
                $param['descuento_porcentaje'] = $parametros['descuento_porcentaje'];
                $param['tipo_pago'] = $parametros['tipo_pago'];
                $param['pagado_efectivo'] = 0;
                $param['pagado_tarjeta'] = 0;
                $param['pagado_tpv2'] = 0;
                $param['pagado_paypal'] = 0;
                $param['pagado_transferencia'] = 0;
                $param['pagado_habitacion'] = 0;
               // $param['notas_pago_descuento'] = '';
                $param['notas_pago_descuento'] =$parametros['notas_pago_descuento'];
                if (isset($parametros['usa_saldo'])) {
                    $param['usa_saldo'] = $parametros['usa_saldo'];
                }

                $ok = $this->Dietario_model->marcar_pagado($param);
                $id_ticket = $this->Dietario_model->CrearTicket($parametros['marcados'], $id_cliente);
            }
        }

        $data['accion'] = $accion;
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['id_cliente']=$id_cliente;
        if ($fecha == null) {
            $data['hoy'] = date("d-m-Y");
            $data['hoy_aaaammdd'] = date("Y-m-d");
        } else {
            $data['hoy'] = $fecha;
            $data['hoy_aaaammdd'] = $fecha;
        }

        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_cobrar_saldo_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    // ----------------------------------------------------------------------------- //
    // ... Mostramos el historial del carnet indicado
    // ----------------------------------------------------------------------------- //
    public function carnets_pago($accion = null, $id_carnet = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_carnet'] = $id_carnet;
        $data['carnet'] = $this->Carnets_model->leer($param);

        if (is_array($data['carnet']) && $data['carnet'][0]['id_tipo'] == 99) {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_servicios'] = $this->Carnets_model->leer_carnets_servicios($param);
        } else {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_ajustes'] = $this->Carnets_model->leer_carnets_ajustes($param);

            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_historial'] = $this->Carnets_model->leer_carnets_historial($param);
        }

        $data['accion'] = $accion;
        $data['id_carnet'] = $id_carnet;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_carnets_pago_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    public function pagoeuros($accion = null, $id_cliente = null, $fecha = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... Hago una pequeña pausa, para que de tiempo a guardarse en el dietario
        // el concepto con la recarga a cobrar en efectivo.
        if ($accion == "ver_recargas") {
            usleep(1500);
        }

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;

        $param['no_pagado'] = 1;
        if ($accion == "ver_recargas") {
            $param['recarga'] = 1;
            // Si es una recarga la fecha siempre es la del día actual
            // porque las recargas se hacen al momento, sino sale
            // la fecha del dietario que puede no coincidir y entonces
            // no se muestran las recargas.
            $param['fecha'] = date("Y-m-d");
        }
        $data['registros'] = $this->Dietario_model->leer($param);
        //// AÑADIDO PARA BUSCAR MODOS DE PAGO EXCLUSIVOS DE Efectivo
        $data['en_efectivo'] = [];
        $this->db->select('id_tipo');
        $this->db->like('descripcion', 'efectivo', 'after');
        $tipos = $this->db->get('carnets_templos_tipos')->result_array();
        foreach ($tipos as $tipo => $id_tipo_value) {
            $data['en_efectivo'][] = $id_tipo_value['id_tipo'];
        }

        //// AÑADIDO PARA BUSCAR MODOS DE PAGO EXCLUSIVOS DE Efectivo
        // ... Leemos el saldo del cliente.
        $data['saldo_cliente'] = $this->Clientes_model->saldo($id_cliente);

        // .. Guardamos los conceptos marcados como pagados o no en funcion del check.
        if ($accion == "guardar") {
            unset($parametros);
            $parametros = $_POST;
            for ($i = 0; $i < count($data['registros']); $i++) {
                $sw = 0;
                if (isset($parametros['marcados'])) {
                    foreach ($parametros['marcados'] as $row) {
                        if ($row != "" && $row == $data['registros'][$i]['id_dietario']) {
                            $sw = 1;
                        }
                    }
                }

                // ... Marcamos el concepto como No pagado, porque no ha sido marcado
                if ($sw == 0) {
                    unset($param);
                    $param['id_dietario'] = $data['registros'][$i]['id_dietario'];
                    $param['estado'] = "No Pagado";
                    $ok = $this->Dietario_model->marcar_no_pagado($param);
                }
            }

            // ... Procesamos los conceptos que hay que marcar como pagados
            // en efectivo / tarjeta / habitacion.
            if (isset($parametros['marcados'])) {
                $suma = array_sum($parametros['importe_euros']);
                if($data['saldo_cliente'] - $suma < 0) {
                    show_error('el cliente no tiene saldo disponible');
                    exit();
                }
                unset($param);
                $param['marcados'] = $parametros['marcados'];
                $param['descuento_euros'] = $parametros['descuento_euros'];
                $param['descuento_porcentaje'] = $parametros['descuento_porcentaje'];
                $param['importe_euros'] = $parametros['importe_euros'];
                $param['pagado_efectivo'] = (!isset($parametros['pagado_efectivo'])) ? 0 : $parametros['pagado_efectivo'];
                $param['pagado_tarjeta'] =  (!isset($parametros['pagado_tarjeta'])) ? 0 : $parametros['pagado_tarjeta'];
                $param['pagado_transferencia'] =  (!isset($parametros['pagado_transferencia'])) ? 0 : $parametros['pagado_transferencia']; //24/03/20
                $param['pagado_tpv2'] =  (!isset($parametros['pagado_tpv2'])) ? 0 : $parametros['pagado_tpv2'];
                $param['pagado_paypal'] =  (!isset($parametros['pagado_paypal'])) ? 0 : $parametros['pagado_paypal'];
                //$param['pagado_financiado'] = $parametros['pagado_financiado'];
                $param['pagado_habitacion'] =  (!isset($parametros['pagado_habitacion'])) ? 0 : $parametros['pagado_habitacion'];
                $param['notas_pago_descuento'] =  (!isset($parametros['notas_pago_descuento'])) ? '' : $parametros['notas_pago_descuento'];
                if (isset($parametros['usa_saldo'])) {
                    $param['usa_saldo'] = $parametros['usa_saldo'];
                }

                $ok = $this->Dietario_model->marcar_pagado($param);

                // 28/05/20 Se comentó el IF y en ELSEIF para que cree el ticke así sea en efectivo.
                // ... Creamos el ticket vinculado a la compra
                //if ($parametros['pagado_efectivo'] == 0) {
                // si el efectivo es 0, se genera el ticket
                $id_ticket = $this->Dietario_model->CrearTicket($parametros['marcados'], $id_cliente);
                //} elseif ($parametros['generar_ticket'] == 1) {
                //    $id_ticket = $this->Dietario_model->CrearTicket($parametros['marcados'], $id_cliente);
                //}
            }
        } //If GUARDAR

        if ($fecha == null) {
            $data['hoy'] = date("d-m-Y");
            $data['hoy_aaaammdd'] = date("Y-m-d");
        } else {
            $data['hoy'] = $fecha;
            $data['hoy_aaaammdd'] = $fecha;
        }

        $data['accion'] = $accion;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            //$this->load->view('dietario/dietario_pagoeuros_view', $data);
            $this->load->view('dietario/dietario_cobrar_saldo_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    // 12/05/20 Tomar Foto ****************************** Tomar Foto ******************
    public function tomar_foto()
    {
        $this->load->view('tomar_foto_view');
    }


    public function guardar_foto()
    {
        $parametros = $_POST;
        $foto = $parametros['xfoto'];

        $imagenCodificada = $foto;
        if (strlen($imagenCodificada) <= 0) exit("No se recibió ninguna imagen");
        //La imagen traerá al inicio data:image/png;base64, cosa que debemos remover
        $imagenCodificadaLimpia = str_replace("data:image/png;base64,", "", urldecode($imagenCodificada));

        //Venía en base64 pero sólo la codificamos así para que viajara por la red, ahora la decodificamos y
        //todo el contenido lo guardamos en un archivo
        $imagenDecodificada = base64_decode($imagenCodificadaLimpia);

        //Calcular un nombre único
        $nombreImagenGuardada = "foto_" . uniqid() . ".png";

        //Escribir el archivo
        $fichero = RUTA_SERVIDOR . "/recursos/foto/" . $nombreImagenGuardada;
        file_put_contents($fichero, $imagenDecodificada);

        //Terminar y regresar el nombre de la foto
        exit($nombreImagenGuardada);
    }

    public function marcar($foto = null)
    {
        $carpeta = RUTA_SERVIDOR . "/recursos/foto/";

        if ($foto == null) {
            $foto = $carpeta . "foto_5ec45bb53268f.png";
            exit('nada');
        }


        $im = imagecreatefrompng($foto);

        /*
$estampa = imagecreatefrompng('logo-p.png');
$im = imagecreatefrompng($foto);

// Establecer los márgenes para la estampa y obtener el alto/ancho de la imagen de la estampa
$margen_dcho = 440; //240
$margen_inf = 230; //150
$sx = imagesx($estampa);
$sy = imagesy($estampa);
*/
        // Copiar la imagen de la estampa sobre nuestra foto usando los índices de márgen y el
        // ancho de la foto para calcular la posición de la estampa. 
        //imagecopy($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));
        //imagecopy($im, $estampa, imagesx($im) - $sx - $margen_dcho, imagesy($im) - $sy - $margen_inf, 0, 0, imagesx($estampa), imagesy($estampa));

        $xfoto = $foto;


        header('Content-type: image/png');
        //imagepng($im,$carpeta.$xfoto);
        //imagedestroy($im);


        //Texto
        $string = 'Texto tipeado por el usuario';
        $font = 5; // Fuente definida por PHP. Lee la documentación para más información: http://www.php.net/manual/es/image.examples.php
        $w = (imagefontwidth($font) * strlen($string)) + 10; // Ancho de la imagen. En este caso tendrá un margen de 5px por lado.
        $h = imagefontheight($font) + 10; // Altura de la imagen. Mismo margen (padding, en CSS).
        $im = imagecreatetruecolor($w, $h); // Crea una estructura de datos.


        $black = imagecolorallocate($im, 0, 0, 0);
        $white = imagecolorallocate($im, 255, 255, 255);
        $img2 = imagecreatefrompng($xfoto);

        $texto = 'Templo del Masaje - ' . date('d/m/Y H:i:s');
        imagestring($img2, 5, 50, 50, $texto, $white);
        //Fin d eTexto

        imagepng($img2, $carpeta . $xfoto);
        //exit($xfoto);
        imagedestroy($img2);
        imagedestroy($xfoto);
        exit();
    }

    public function verfoto($foto = null)
    {
        $carpeta = RUTA_SERVIDOR . "/recursos/foto/";
        //echo base_url();   
        //$carpeta=base_url()."recursos/foto/";    

        $foto = $carpeta . "imagen2.png";
        $string = 'Templo del Masaje';
        $font = 3; // Fuente definida por PHP. Lee la documentación para más información: http://www.php.net/manual/es/image.examples.php
        $w = (imagefontwidth($font) * strlen($string)) + 10; // Ancho de la imagen. En este caso tendrá un margen de 5px por lado.
        $h = imagefontheight($font) + 10; // Altura de la imagen. Mismo margen (padding, en CSS).
        $im = imagecreatetruecolor($w, $h); // Crea una estructura de datos.


        $text_color = imagecolorallocate($im, 255, 255, 255); // Color del texto en la imagen.
        imagestring($im, $font, 5, 5, $string, $text_color); // Esta es la línea que dibuja el texto en la imagen. Lo anterior era un "esqueleto".
        imagepng($im, $foto); // Crea la imagen y la guarda donde le digas (en este caso test/imagen.png). La carpeta debe tener permisos 777.
        imagedestroy($im); // Destruye la estructura de datos
        echo '<img src="' . $foto . '"/>'; // Muestras la imagen.

    }



    //Fin Tomar Foto ********************************** Fin Tomar Foto ********


    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    public function pagotemplos($accion = null, $id_cliente = null, $fecha = null, $id_carnet_elegido = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ----------------------------------------------------------------------------- //
        // Leemos los servicios con templos pendientes de pago del cliente.
        // ----------------------------------------------------------------------------- //
        unset($parametros);
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;
        $param['no_pagado'] = 1;
        $param['solo_pago_templos'] = 1;
        if (isset($parametros['servicios_marcados'])) {
            // .. Incluimos en la busqueda solo las lineas de dietario
            // que se corresponden con cada servicio
            if (count($parametros['servicios_marcados']) > 0) {
                $param['servicios_marcados'] = $parametros['servicios_marcados'];
                $data['servicios_marcados'] = $param['servicios_marcados'];
            }
        }
        $data['registros'] = $this->Dietario_model->leer($param);

        // ----------------------------------------------------------------------------- //
        // ... Comprobamos el carnet indicado
        // ----------------------------------------------------------------------------- //
        if ($accion == "comprobar_carnet") {
            // ... Comprobamos el codigo de carnet
            if (isset($parametros) && isset($parametros['codigo'])) {
                /// ... Pasamos el array de servicios_pagar para determinar si hay que hacer descuento
                // si hay que hacerlos, habría que modificar el array para cambiar el numero de templos a pagar
                // y devolverlo así, para que así se calcule si el carnet tiene disponibles o no el numero de templos.
                $r = $this->Descuentos_model->descuentos_templos($data['registros'], 0, $parametros['codigo']);
                $data['registros'] = $r['servicios'];
                $data['mensaje_descuento'] = $r['mensaje'];
                //

                unset($param);
                $param['codigo'] = $parametros['codigo'];
                $param['servicios_pagar'] = $data['registros'];
                if ($fecha == null) {
                    $param['fecha'] = date("Y-m-d");
                } else {
                    $param['fecha'] = $fecha;
                }
                $param['id_cliente'] = $id_cliente;

                $data['estado_carnet'] = $this->Carnets_model->comprobar_carnet($param);

                # ... Leemos posible notas de cobro para el carnet.
                unset($param_notas);
                $param_notas['carnet'] = $parametros['codigo'];
                $param_notas['estado'] = "Pendiente";
                $data['notas_cobrar'] = $this->Clientes_model->notas_cobrar($param_notas);
                $data['carnet_cobrar'] = $parametros['codigo'];
            }
        }

        // ----------------------------------------------------------------------------- //
        // .. Marcamos los servicios como pagados, en funcion a los carnets indicados
        // ----------------------------------------------------------------------------- //
        if ($accion == "marcarpago") {
            // Aqui igualmente, volvemos a comprobar los descuentos, para devolver el
            // array de servicios a pagar con descuento de templos, si lo hay.
            // adicionalmente pasamos el parametro actualiza_dietario a 1 para
            // que las lineas del dietario, se actualicen con los templos que corresponden.
            $r = $this->Descuentos_model->descuentos_templos($data['registros'], 1, "");
            $data['registros'] = $r['servicios'];
            $data['mensaje_descuento'] = $r['mensaje'];
            //

            unset($param);
            $param['id_cliente'] = $id_cliente;
            $param['fecha'] = $fecha;
            $param['servicios_pagar'] = $data['registros'];

            $ok = $this->Carnets_model->marcar_pago_templos($param);

            // ... Creamos el ticket vinculado a la compra
            //20/05/20 Se agregó foto_templo ********************* Foto Templo ***************
            $foto_templo = $parametros['foto'];
            $id_ticket = $this->Dietario_model->CrearTicketTemplos($param['servicios_pagar'], $id_cliente, $foto_templo);

            //
            // ... Actualizamos el precio de venta del carnets, en caso de ser
            // de tipo Especial.
            // Si el pago se ha hecho con templos, entonces hay que calcular
            // el valor de venta del carnet en base a los carnets usados para el pago
            //
            if ($ok == 1) {
                foreach ($data['registros'] as $row) {
                    unset($param);
                    $param['id_dietario'] = $row['id_dietario'];
                    $dietario = $this->Dietario_model->leer($param);

                    if ($dietario[0]['id_carnet'] > 0 && $dietario[0]['id_servicio'] == 0) {
                        unset($param2);
                        $param2['id_carnet'] = $dietario[0]['id_carnet'];

                        // ... Leemos los carnets con los que se pago.
                        unset($param3);
                        $param3['id_dietario'] = $dietario[0]['id_dietario'];
                        $carnets_sepago = $this->Carnets_model->leer($param3);

                        // ... Calculamos la media de templos.
                        $templos_totales = 0;
                        $items = 0;
                        foreach ($carnets_sepago as $c) {
                            // aqui está el fayo, ya que no calcula el precio por templo de cada carnet usado para pagar, sino que lo calcula en funcion del coste total de carnet especial

                            //buscamos los datos del carnet usados
                            $this->db->select('templos, precio');
                            $this->db->where('id_carnet', $c['id_carnet']);
                            $current_carnet = $this->db->get('carnets_templos')->row();
                            $precio_templo = round($current_carnet->precio / $current_carnet->templos, 2);

                            // se busca en el carnet templos historial el id_dietario y el id_carnet para saber cuanto se ha usado
                            $this->db->select('templos');
                            $this->db->where('id_carnet', $c['id_carnet']);
                            $this->db->where('id_dietario', $param3['id_dietario']);
                            $templos = $this->db->get('carnets_templos_historial')->row()->templos;
                            $precio_este_carnet = $templos * $precio_templo;
                            $templos_totales = $templos_totales + $precio_este_carnet;

                            // se ve el uso
                            // $templos_totales += $c['precio'] / $c['templos'];
                            $items++;
                        }

                        if ($templos_totales > 0 && $items > 0) {
                            //$param2['precio']=$templos_totales * $dietario[0]['templos'];
                            $param2['precio'] = $templos_totales;
                            $r = $this->Carnets_model->cambio_precio_venta_carnet($param2);
                        }
                    }
                }
            }

            //
            // ... Borramos los carnet almacenados para realizar el pago.
            //
            if ($ok == 1) {
                unset($param);
                $param['id_cliente'] = $id_cliente;
                $ok = $this->Carnets_model->borrar_pago_templos_carnets($param);
            }
        }

        // ----------------------------------------------------------------------------- //
        // .. Borramos un carnet elegido para el pago
        // ----------------------------------------------------------------------------- //
        if ($id_carnet_elegido > 0) {
            unset($param);
            $param['id'] = $id_carnet_elegido;

            $ok = $this->Carnets_model->borrar_carnet_elegido_pago($param);
        }

        // ... Leemos los carnet que se hayan ido eligiendo para el pago.
        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;
        $data['carnets_elegidos'] = $this->Carnets_model->pagotemplos($param);

        if ($accion == "comprobar_carnet" || $accion == "ver") {
            $param['servicios_pagar'] = $data['registros'];
            $data['puedo_pagar'] = $this->Carnets_model->puedo_pagar_templos($param);
        } else {
            $data['puedo_pagar'] = 0;
        }

        if ($fecha == null) {
            $data['hoy'] = date("d-m-Y");
            $data['hoy_aaaammdd'] = date("Y-m-d");
        } else {
            $data['hoy'] = $fecha;
            $data['hoy_aaaammdd'] = $fecha;
        }

        // ... Calculamos el numero de templos pendientes por pagar.
        $total_templos_servicios = 0;
        if ($data['registros'] > 0) {
            foreach ($data['registros'] as $row) {
                $total_templos_servicios += $row['templos'];
            }
        }
        $total_templos_carnets = 0;
        if ($data['carnets_elegidos'] > 0) {
            foreach ($data['carnets_elegidos'] as $row) {
                $total_templos_carnets += $row['templos_disponibles'];
            }
        }
        $data['templos_por_pagar'] = $total_templos_servicios - $total_templos_carnets;

        $data['accion'] = $accion;
        $data['id_cliente'] = $id_cliente;

        // ... Leemos los carnets que tenga ese cliente disponibles.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['carnets_cliente'] = $this->Carnets_model->leer($param);

        //11/04/20 agregar carnet d eclientes asociados
        //Buscar los clientes asociados
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $asociados = $this->Carnets_model->leer_asociados($param);
        //echo "va... ";
        if ($asociados != 0) {
            $xlista = $data['carnets_cliente'];
            $total = count($xlista);
            if ($total > 0)
                $total--;
            //echo "Hay ".$total;
            foreach ($asociados as $key => $row) {
                unset($param);
                unset($lista);
                //echo "asociado: ".$row['id_asociado'];
                $param['id_cliente'] = $row['id_cliente']; // 06/05/20 $param['id_cliente'] = $row['id_asociado'];
                $lista = $this->Carnets_model->leer($param);

                $c = 0;
                if(is_countable($lista)){
                    foreach ($lista as $key => $row) {
                        //echo "Cupon ".$row['codigo'];
                        $total++;
                        $xlista[$total] = $lista[$c];
                        $c++;
                    }
                }
            }

            $data['carnets_cliente'] = $xlista;
        }
        //Fin asociados
        // ... Comprobamos si los carnets especiales tienen algun servicio no gastado.
        if ($data['carnets_cliente'] > 0) {
            $i = 0;
            foreach ($data['carnets_cliente'] as $row) {
                if ($row['id_tipo'] == 99) {
                    unset($param);
                    $param['id_carnet'] = $row['id_carnet'];
                    $param['gastado'] = 0;
                    $servicios = $this->Carnets_model->leer_carnets_servicios($param);

                    // ... Sino encuentra nada, es que todos los servicios estan gastados
                    // por tanto borramos el carnet de la lista.
                    if ($servicios == 0) {
                        unset($data['carnets_cliente'][$i]);
                    }
                }
                $i++;
            }
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_pagotemplos_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    //Prueba sms
    public function prueba_sms()
    {

        $url = "https://api-sms.ibersontel.com/";
        echo "<br> Prueba de API SMS ";
        //$url = "https://cloud-services.ibersontel.com/";
        //SUSTITUIR VALORES POR LOS DATOS QUE TE HEMOS PROPORCIONADO PARA USAR LA API
        $Usuario = "B87654117";
        $Password = "T3mPl0-M4saJ3";
        $Api_key = "TB4A*Sw8i+Z5";

        //EJEMPLO SI SOLO ENVIAMOS 1 SMS DESCOMENTA EL CÓDIGO DE ABAJO
        //$destino="+34666606174";
        $destino = "+584246494205";
        $mensaje = "Hola esto es un SMS María Nuñez Ávila";
        echo "<br> Para: " . $destino . " Mensaje: " . $mensaje;
        $sms = array(
            "PartnerApiKey" => $Api_key,
            "customParameters" => array(),
            "RequestSMS" => array(
                "source" => 'TDM',
                "sourceTON" => 'ALPHANUMERIC',
                "destination" => $destino,
                "userData" => $mensaje,
                "refId" => ''
            )
        );

        //INICIALIZAMOS LA PETICIÓN POST
        $curl = curl_init();
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($sms));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode("$Usuario:$Password")
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //EXECUTE:
        $result = curl_exec($curl);
        $result3 = json_decode($result, true);
        echo "<br> Ver Ok: ";
        $enviado = $result3[0]['Enviado'];
        if ($enviado == "OK") {
            echo "<br> Resultado " . $enviado;
        } else {
            echo "<br> Resultado " . $enviado;
        }
        $result2 = $result;
        //Si queremos imprimir lo que devuelve la petición Curl
        echo "<br> Respuesta es: ";
        echo "<prev>";
        print_r($result);
        //Respuesta es: [{"ID_sms":"YAE2BXgjDkxXAFe6qrj3bT","Ref_SMS":"S3RQBBFQN","cuenta_sms":1,"Enviado":"OK"}]
        echo json_decode($result2);
        //print_r($result);
        if (!$result) {
            die("Fallo de conexión");
        }
        curl_close($curl);

        /*
            echo "<b> La otra forma";
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cloud-services.ibersontel.com/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                //CURLOPT_SSL_VERIFYPEER => TRUE,
                //CURLOPT_CAINFO => "c:/program files/vertrigoserv/www/api/cacert.pem",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\"PartnerApiKey\":\"TB4A*Sw8i+Z5\",\"customParameters\":[],\"RequestSMS\":{\"source\":\"FROM\",\"sourceTON\":\"ALPHANUMERIC\",\"destination\":\"+34666606174\",\"userData\":\"Hola esto es un SMS Mar\\u00eda Nu\\u00f1ez \\u00c1vila\",\"refId\":\"\"}}",
                CURLOPT_HTTPHEADER => array(
                    "authorization: Basic Qjg3NjU0MTE3OlQzbVBsMC1NNHNhSjM=",
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: b6ade6d3-7b71-7841-4914-2320964e1ee9"
                ),
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            
            curl_close($curl);
            
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }
            */
    }

    public function prueba_asociados($id_cliente = null)
    { //16/01/20 es temporal, para ver por qué no salen todos ls carnet
        //11/04/20 agregar carnet d eclientes asociados
        //Buscar los clientes asociados
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['carnets_cliente'] = $this->Carnets_model->leer($param);
        var_dump($data['carnets_cliente']);
        echo "<br> Cuantos: " . count($data['carnets_cliente']);
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $asociados = $this->Carnets_model->leer_asociados($param);

        //echo "va... ";
        if ($asociados != 0) {
            $xlista = $data['carnets_cliente'];
            $total = count($xlista);
            if ($total > 0)
                $total--;
            //echo "Hay ".$total;
            foreach ($asociados as $key => $row) {
                unset($param);
                unset($lista);
                //echo "asociado: ".$row['id_asociado'];
                $param['id_cliente'] = $row['id_cliente']; // 06/05/20 $param['id_cliente'] = $row['id_asociado'];
                $lista = $this->Carnets_model->leer($param);

                $c = 0;
                foreach ($lista as $key => $row) {
                    //echo "Cupon ".$row['codigo'];
                    $total++;
                    $xlista[$total] = $lista[$c];
                    $c++;
                }

                //array_push($data['carnets_cliente'],$lista);
            } //For
            /*
            foreach ($xlista as $key => $row) {
                echo "Cliente ".$row['cliente'];
            }
            */
            $data['carnets_cliente'] = $xlista;
        }
        //Fin asociados
        echo "<br>";
        echo "*******************************************";

        //var_dump($data['carnets_cliente']);
        $i = 0;
        foreach ($data['carnets_cliente'] as $key => $row) {
            echo "<br> I " . $i . " " . $row['id_carnet'] . " " . $row['codigo'] . " Templos:  " . $row['templos_disponibles'] . " Tipo: " . $row['id_tipo'];
            $i++;
        }

        // ... Comprobamos si los carnets especiales tienen algun servicio no gastado.
        if ($data['carnets_cliente'] > 0) {
            $i = 0;
            foreach ($data['carnets_cliente'] as $row) {
                if ($row['id_tipo'] == 99) {
                    unset($param);
                    $param['id_carnet'] = $row['id_carnet'];
                    $param['gastado'] = 0;
                    $servicios = $this->Carnets_model->leer_carnets_servicios($param);

                    // ... Sino encuentra nada, es que todos los servicios estan gastados
                    // por tanto borramos el carnet de la lista.
                    if ($servicios == 0) {
                        unset($data['carnets_cliente'][$i]);
                        echo "<br> Es: " . $i . " " . $row['id_carnet'] . " " . $row['codigo'];
                    }
                }
                $i++;
            }
        }

        echo "<br>";
        echo "********************** Segundo Paso *********************";

        //var_dump($data['carnets_cliente']);
        $i = 0;
        foreach ($data['carnets_cliente'] as $key => $row) {
            echo "<br> I " . $i . " " . $row['id_carnet'] . " " . $row['codigo'] . " Templos:  " . $row['templos_disponibles'] . " Tipo: " . $row['id_tipo'];
            $i++;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    public function completar_citas_online($id_cliente = null, $fecha = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos solo las citas online del cliente y dia concreto,
        // para marcalas todas como pagadas.
        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        $param['id_cliente'] = $id_cliente;
        $param['citas_online'] = 1;
        $param['estado'] = "Pendiente";

        $registros = $this->Dietario_model->leer($param);

        foreach ($registros as $row) {
            $this->Dietario_model->finalizar_cita_online($row['id_dietario'], $row['id_cita'], $row['id_ticket']);
        }

        // Mandar email para opinion del cliente.
        $this->Clientes_model->EmailOpinionCliente($id_cliente);

        redirect("/dietario/ficha/ver/" . $id_cliente . "/" . $fecha);
        exit;
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    public function recargar_carnet($id_cliente = null, $fecha = null, $id_carnet = null, $templos = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Se crear un concepto en el dietario con la recarga de los templos
        // a pagar en euros.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $param['id_empleado'] = $this->session->userdata('id_usuario');
        $param['id_servicio'] = 0;
        $param['id_producto'] = 0;
        $param['id_carnet'] = $id_carnet;
        $param['recarga'] = 1;
        $param['recarga_templos'] = $templos;
        // ... Aqui lo que se hace es coger la parte entera de los templos
        // y multiplicar por el valor de los templos en euros.
        // luego si hay una mitad, entonces se multiplica por el valor de medio templo.
        $partes = explode(".", $templos);
        if (isset($partes[1])) {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS) + VALOR_MEDIO_TEMPLO_EUROS;
        } else {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS);
        }
        $param['templos'] = $templos;
        $param['estado'] = "No Pagado";

        $id_dietario = $this->Dietario_model->nuevo_dietario_concepto($param);

        // ... Recargamos la pagina de pago con templos.
        header("Location: " . RUTA_WWW . "/dietario/pagotemplos/ver/" . $id_cliente . "/" . $fecha . "/0/recarga");
        exit;
    }

    // ... Esta funcion es igual a la anterior, solo que la uso para la recarga directa
    // desde el dietario, para luego poder dirigir la pagina final a otro sitio.
    public function recargar_carnet_manual($id_cliente = null, $fecha = null, $id_carnet = null, $templos = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Se crear un concepto en el dietario con la recarga de los templos
        // a pagar en euros.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $param['id_empleado'] = $this->session->userdata('id_usuario');
        $param['id_servicio'] = 0;
        $param['id_producto'] = 0;
        $param['id_carnet'] = $id_carnet;
        $param['recarga'] = 1;
        $param['recarga_templos'] = $templos;
        // ... Aqui lo que se hace es coger la parte entera de los templos
        // y multiplicar por el valor de los templos en euros.
        // luego si hay una mitad, entonces se multiplica por el valor de medio templo.
        $partes = explode(".", $templos);
        if (isset($partes[1])) {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS) + VALOR_MEDIO_TEMPLO_EUROS;
        } else {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS);
        }
        $param['templos'] = $templos;
        $param['estado'] = "No Pagado";

        $id_dietario = $this->Dietario_model->nuevo_dietario_concepto($param);

        // ... Recargamos la pagina de pago con templos.
        header("Location: " . RUTA_WWW . "/carnets/recargar/terminar");
        exit;
    }


    //17/04/20
    public function recargar_carnet_manual_unico($id_cliente = null, $fecha = null, $id_carnet = null, $templos = null, $precio = null, $solo_pago = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Se crear un concepto en el dietario con la recarga de los templos
        // a pagar en euros.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['id_cita'] = 0;
        $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
        $param['id_empleado'] = $this->session->userdata('id_usuario');
        $param['id_servicio'] = 0;
        $param['id_producto'] = 0;
        $param['id_carnet'] = $id_carnet;
        $param['recarga'] = 1;
        $param['recarga_templos'] = $templos;
        // ... Aqui lo que se hace es coger la parte entera de los templos
        // y multiplicar por el valor de los templos en euros.
        // luego si hay una mitad, entonces se multiplica por el valor de medio templo.
        /*
        $partes = explode(".", $templos);
        if (isset($partes[1])) {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS) + VALOR_MEDIO_TEMPLO_EUROS;
        } else {
            $param['importe_euros'] = (intval($templos) * VALOR_TEMPLOS_EUROS);
        }
        */
        $param['importe_euros'] = $precio;
        $param['templos'] = $templos;
        //29/06/20 Para saber si la recarga es solo para pagar en efectivo
        $param['solo_pago'] = $solo_pago;
        $param['estado'] = "No Pagado";

        $id_dietario = $this->Dietario_model->nuevo_dietario_concepto($param);

        //21/04/20 registrar recarga pagao=0 porque no se ha pagado aún.
        $param['id_dietario'] = $id_dietario;
        $id_recarga = $this->Carnets_model->nueva_recarga($param);
        //Fin

        // ... Recargamos la pagina de pago con templos.
        header("Location: " . RUTA_WWW . "/carnets/recargar_unico/terminar");
        exit;
        //return 1;
    }

    //Fin


    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    public function borrar_conceptos($id_cliente = null, $fecha = null, $id_dietario = 0)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($id_dietario > 0) {
            unset($param);
            $param['id_dietario'] = $id_dietario;
            $ok = $this->Dietario_model->borrar_concepto($param);
        }

        // ... Recargamos la pagina de la ficha de conceptos.
        header("Location: " . RUTA_WWW . "/dietario/ficha/ver/" . $id_cliente . "/" . $fecha);
        exit;
    }

    // ----------------------------------------------------------------------------- //
    // ... Devoluciones
    // ----------------------------------------------------------------------------- //
    public function devoluciones($accion = null, $id_cliente = null, $id_dietario = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['vacio'] = "";
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);
        $data['productos'] = $this->Productos_model->leer_productos($param);

        unset($param);
        $param['tipo_templos'] = "SI";
        if ($accion == "realizar") {
            unset($parametros);
            $parametros = $_POST;

            unset($param);
            $tipoDevolucion=isset($parametros["que_devolver"]) ? $parametros["que_devolver"] : 0;
            switch($tipoDevolucion){
                case 1: $tipoDevolucion='devolucion_producto'; break;
                case 2: $tipoDevolucion='devolucion_servicio'; break;
                case 3: $tipoDevolucion='devolucion_acuenta'; break;
                default: $tipoDevolucion='none'; break;
            }




            // RCG Validaciones previas
            $doDietario=true;
            $errorDevolucion='';
            $saldo=$this->Clientes_model->saldo( $parametros['id_cliente']);
            if($tipoDevolucion=='devolucion_acuenta'){
                if($saldo<=0){
                    $doDietario=false;
                    $errorDevolucion='No se puede hacer la devolución, ya que el cliente no tiene saldo.';
                    $accion='error';
                }
                else
                    if($saldo<$parametros['importe_devolver']){
                        $parametros['importe_devolver']=$saldo;
                        $accion='warning';
                        $doDietario=false;
                        $errorDevolucion='La devolución máxima posible es de '.$saldo;
                    }
            }

           // $id_dietario=0;
            if($doDietario) {
                $param['id_cliente'] = $parametros['id_cliente'];
                $param['id_cita'] = 0;
                $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
                $param['id_empleado'] = (isset($parametros['id_empleado'])) ? $parametros['id_empleado'] : $this->session->userdata('id_usuario');
                $param['id_producto'] = 0;
                $param['id_servicio'] = 0;
                if (isset($parametros['id_producto'])) {
                    $partes = explode("|", $parametros['id_producto']);
                    $param['id_producto'] = $partes[0];
                }
                if (isset($parametros['id_servicio'])) {
                    unset($partes);
                    $partes = explode("|", $parametros['id_servicio']);
                    $param['id_servicio'] = $partes[0];
                }
                $param['id_carnet'] = 0;
                $param['recarga'] = 0;
                $param['importe_euros'] = 0;
                if (isset($parametros['importe_devolver'])) {
                    $param['importe_euros'] = ($parametros['importe_devolver'] * -1);
                }
                $param['pagado_efectivo'] = 0;
                $param['pagado_tarjeta'] = 0;
                $param['pagado_transferencia'] = 0; //24/03/20
                $param['pagado_tpv2'] = 0; //31/08/21
                $param['pagado_habitacion'] = 0;
                if ($parametros['forma_pago'] == "#efectivo") {
                    $param['pagado_efectivo'] = $param['importe_euros'];
                }
                if ($parametros['forma_pago'] == "#tarjeta") {
                    $param['pagado_tarjeta'] = $param['importe_euros'];
                }
                //24/03/20
                if ($parametros['forma_pago'] == "#transferencia") {
                    $param['pagado_transferencia'] = $param['importe_euros'];
                }
                //Fin

                //31/08/21
                if ($parametros['forma_pago'] == "#tpv2") {
                    $param['pagado_tpv2'] = $param['importe_euros'];
                }
                //Fin
                if ($parametros['forma_pago'] == "#habitacion") {
                    $param['pagado_habitacion'] = $param['importe_euros'];
                }
                $param['tipo_pago'] = $parametros['forma_pago'];
                $param['templos'] = 0;
                if ($parametros['forma_pago'] == "#templos") {
                    $param['id_carnet'] = $parametros['id_carnet'];
                    $param['templos'] = $parametros['templos'];
                }

                if ($parametros['forma_pago'] == "#especial") {
                    if (isset($parametros['id_carnet_especial'])) {
                        if ($parametros['id_carnet_especial'] > 0) {
                            $param['id_carnet'] = $parametros['id_carnet_especial'];
                            $param['templos'] = 0;
                        }
                    }
                }
                if (isset($parametros['id_dietario'])) {
                    $param['id_dietario'] = $parametros['id_dietario'];
                }

                $param['estado'] = "Devuelto";
                $param['motivo_devolucion'] = $parametros['motivo_devolucion'];
                $param['que_devolver'] = $parametros['que_devolver'];


                if($param['que_devolver']==2){
                    // RCG Si se esta devolviendo un servicio se busca el ultimo servicio del cliente para ponerlo devuelto en el dietario
                    $lastDietario=$this->Dietario_model->leer([
                        'id_cliente'=>$param['id_cliente'],
                        'id_servicio'=>$param['id_servicio'],
                        'estado'=>'Pagado',
                        'devuelto'=>0
                    ]);
                    if($lastDietario>0){
                        $param['id_dietario']=$lastDietario[0]['id_dietario'];
                    }
                }
                else
                if($param['que_devolver']==1){
                    $lastDietario=$this->Dietario_model->leer([
                        'id_cliente'=>$param['id_cliente'],
                        'id_producto'=>$param['id_producto'],
                        'estado'=>'Pagado',
                        'devuelto'=>0
                    ]);
                    if($lastDietario>0){
                        $param['id_dietario']=$lastDietario[0]['id_dietario'];
                    }
                }


//echo "<pre>";var_dump($param);echo "</pre>"; echo "<hr/>";

                // ... Guardamos la devolucion en el diaetario
                $id_dietario = $this->Dietario_model->devolucion($param);


                // ... Si es una devolución a un carnet, le sumamos los templos
                // y lo guardamos en el historico de cambios.
                if (isset($parametros['id_carnet'])) {
                    if ($parametros['id_carnet'] > 0) {
                        unset($param4);
                        $param4['id_carnet'] = $parametros['id_carnet'];
                        $carnet_elegido = $this->Carnets_model->leer($param4);

                        unset($param3);
                        $param3['id_carnet'] = $parametros['id_carnet'];
                        $param3['id_dietario'] = $id_dietario;
                        $param3['id_servicio'] = $param['id_servicio'];
                        $param3['id_cliente'] = $param['id_cliente'];
                        $param3['id_empleado'] = $this->session->userdata('id_usuario');
                        $param3['templos'] = $param['templos'] * -1;
                        $ok = $this->Carnets_model->anadir_historial($param3);

                        // ... Modificamos los templos disponibles en el carnet.
                        unset($param5);
                        $param5['id_carnet'] = $parametros['id_carnet'];
                        $param5['templos_disponibles'] = ($carnet_elegido[0]['templos_disponibles'] + $param['templos']);
                        $param5['ajuste_automatico'] = 1;
                        $id = $this->Carnets_model->ajustes_templos($param5);
                    }
                }

                // ... Devolucion a carnet espeacial
                // Lo que se hace es crear otro servicio en el carnet.
                if (isset($parametros['id_carnet_especial'])) {
                    if ($parametros['id_carnet_especial'] > 0) {
                        unset($servicio_carnet);

                        $servicio_carnet['id_carnet'] = $parametros['id_carnet_especial'];
                        $servicio_carnet['id_servicio'] = $param['id_servicio'];

                        unset($param_serv);
                        $param_serv['id_servicio'] = $param['id_servicio'];
                        $servicio = $this->Servicios_model->leer_servicios($param_serv);

                        $servicio_carnet['id_cliente'] = $param['id_cliente'];
                        $servicio_carnet['pvp'] = $servicio[0]['pvp'];

                        $id = $this->Carnets_model->anadir_servicio($servicio_carnet);
                    }
                }

                // ... Crear ticket de la devolucion
                $array[0] = $id_dietario;
                $id_ticket = $this->Dietario_model->CrearTicket($array, $parametros['id_cliente']);
            }
            else{
                // No se hace la devolucion.
                $accion='error';
                unset($param5);
                $param5['id_cliente'] = $parametros['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

            }
            // Fin Realizar
        }

        $data['errorDevolucion']=isset($errorDevolucion) ? $errorDevolucion : '';
        $data['original_param']=isset($parametros) ? $parametros : [];
        $data['accion'] = $accion;
        $data['id_cliente'] = $id_cliente;
        $data['id_carnet_especial'] = 0;
        $data['codigo_carnet_especial'] = "";

        if ($id_dietario > 0) {
            unset($param);
            $param['id_dietario'] = $id_dietario;
            $param['debug'] = 1;
            $data['dietario'] = $this->Dietario_model->leer($param);

            unset($param);
            $param['id_dietario'] = $id_dietario;
            $carnet_especial = $this->Carnets_model->leer($param);

            if ($carnet_especial != 0) {
                if ($carnet_especial[0]['id_tipo'] == 99) {
                    $data['id_carnet_especial'] = $carnet_especial[0]['id_carnet'];
                    $data['codigo_carnet_especial'] = $carnet_especial[0]['codigo'];
                }
            }

            unset($param);
            $param['id_usuario'] = $data['dietario'][0]['id_empleado'];

            $empleado = $this->Usuarios_model->leer_usuarios($param);
            $data['dietario'][0]['empleado'] = (isset($empleado) && is_array($empleado) && isset($empleado[0])) ? $empleado[0]['nombre'] . ' ' . $empleado[0]['apellidos'] : '';
            $data['id_cliente'] = $data['dietario'][0]['id_cliente'];
            if($data['id_cliente'] > 0 && $data['id_cliente'] != null){
                unset($param5);
                $param5['id_cliente'] = $data['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
            }
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_devoluciones_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Resumen
    // ----------------------------------------------------------------------------- //
    public function resumen($fecha = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Vemos si se coge la fecha actual o una indicada.
        unset($param);
        if ($fecha == null) {
            $fechaMenosDia = strtotime('-1 day', strtotime(date("Y-m-d")));
            $param['fecha'] = date("Y-m-d", $fechaMenosDia);
            $fecha = date("Y-m-d", $fechaMenosDia);
        } else {
            $param['fecha'] = $fecha;
        }

        // ... controlamos que el perfil sea el master,
        // sino solo mostramos lo del centro que corresponda.
        if ($this->session->userdata('id_perfil') > 0 && $this->session->userdata('id_perfil') != 3) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $param['id_centro'] = $id_centro;
            }else{
                $param['id_centro'] = 1;
            }
        }

        $data['fecha'] = $param['fecha'];
        $data['id_centro'] = $param['id_centro'];
        // ... Leemos las citas
        /*
        $data['numero_citas'] = $this->Agenda_model->numero_citas($param);
        $data['citas_anuladas'] = $this->Agenda_model->citas_anuladas($param);
        $data['citas_novino'] = $this->Agenda_model->citas_novino($param);
        */
		$fecha_ddmmaaaa = date('d-m-Y', strtotime($param['fecha']));
        $fecha = date('Y-m-d', strtotime($param['fecha']));
        // facturacion mensual antes
        $totales_mes = [];
        unset($param);
        $param['mes'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $facturacion_mes = $this->Estadisticas_model->usuarios($param);
        $totales_mes['ventas'] = 0;
        foreach ($facturacion_mes as $key => $value) {
            $totales_mes['ventas'] += $value['ventas'];
        }
        // se buscan las citas mensuales, para totales. Estan incluidos los datos de la fecha buscada, por lo que se usan tambien apra ontener los resultados
        unset($parametros);
		$parametros['fecha'] = $fecha_ddmmaaaa;
		$parametros['id_centro'] = $param['id_centro'];
		$citas = $this->Estadisticas_model->citas($parametros);
        // se fefine lo que se quiere buscar y su valor inicial
        // mes
        $totales_mes['psicotecnico'] = 0;
        $totales_mes['tasa'] = 0;
        $totales_mes['analisis'] = 0;
        $totales_mes['podologia'] = 0;
        $totales_mes['dental'] = 0;
        // dia
        $id_clientes_primeras = [];
        $data['primerascitas_manana'] = 0;
        $data['primerascitas_tarde'] = 0;
        $data['psico_servicios_manana'] = 0;
        $data['psico_valor_manana'] = 0;
        $data['psico_servicios_tarde'] = 0;
        $data['psico_valor_tarde'] = 0;
        $data['tasa_servicios_manana'] = 0;
        $data['tasa_valor_manana'] = 0;
        $data['tasa_servicios_tarde'] = 0;
        $data['tasa_valor_tarde'] = 0;
        $data['analisis_servicios_manana'] = 0;
        $data['analisis_valor_manana'] = 0;
        $data['analisis_servicios_tarde'] = 0;
        $data['analisis_valor_tarde'] = 0;
        // se recorren las citas
        //show_array($citas);exit;
        foreach ($citas as $key => $value) {
            if($value->estado == 'Finalizado'){
                // mes
                if ($value->nombre_servicio !== null) {
                    if (str_contains(strtolower($value->nombre_servicio), 'psicotecnico')) {
                        $totales_mes['psicotecnico']  += ($value->coste != '') ? $value->coste : $value->pvp;
                    }else if (str_contains(strtolower($value->nombre_servicio), 'tasa')) {
                        $totales_mes['tasa']  += ($value->coste != '') ? $value->coste : $value->pvp;
                    }elseif (str_contains(strtolower($value->nombre_servicio), 'analisis')) {
                        $totales_mes['analisis']  += ($value->coste != '') ? $value->coste : $value->pvp;
                    }elseif(str_contains(strtolower($value->nombre_familia), 'podologia')) {
                        $totales_mes['podologia']  += ($value->coste != '') ? $value->coste : $value->pvp;
                    }
                }
                // dia
                if(date('Y-m-d', strtotime($value->fecha_hora_inicio)) == $fecha){
                    if($value->id_servicio == 15404){
                        if(!in_array($value->id_cliente, $id_clientes_primeras)){
                            $id_clientes_primeras[] = $value->id_cliente;
                            if($value->fecha_hora_inicio < $fecha . " 16:00:00"){
                                $data['primerascitas_manana']++;
                            } else{
                                $data['primerascitas_tarde']++;
                            }
                        }
                    }
                    if ($value->nombre_servicio !== null) {
                        if (str_contains(strtolower($value->nombre_servicio), 'psicotecnico')) {
                            if($value->fecha_hora_inicio < $fecha . " 16:00:00"){
                                $data['psico_servicios_manana'] += 1;
                                $data['psico_valor_manana'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }else{
                                $data['psico_servicios_tarde'] += 1;
                                $data['psico_valor_tarde'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }
                        }
                        if (str_contains(strtolower($value->nombre_servicio), 'tasa')) {
                            if($value->fecha_hora_inicio < $fecha . " 16:00:00"){
                                $data['tasa_servicios_manana'] += 1;
                                $data['tasa_valor_manana'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }else{
                                $data['tasa_servicios_tarde'] += 1;
                                $data['tasa_valor_tarde'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }
                        }
                        if (str_contains(strtolower($value->nombre_servicio), 'analisis')) {
                            if($value->fecha_hora_inicio < $fecha . " 16:00:00"){
                                $data['tasa_servicios_manana'] += 1;
                                $data['tasa_valor_manana'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }else{
                                $data['tasa_servicios_tarde'] += 1;
                                $data['tasa_valor_tarde'] += ($value->coste != '') ? $value->coste : $value->pvp;
                            }
                        }
                    }
                }
            }
        }
        $totales_mes['dental'] = $totales_mes['ventas'] - $totales_mes['psicotecnico'] - $totales_mes['tasa'] - $totales_mes['analisis'] - $totales_mes['podologia'];
        $data['totales_mes'] = $totales_mes;

        // ... Leemos los movimientos de caja
        unset($param2);
        $param2['fecha_desde'] = $fecha;
        $param2['fecha_hasta'] = $fecha;
        $param2['id_centro'] = $param['id_centro'];
        $data['movimientos_caja'] = $this->Caja_model->leer_caja_movimientos($param2);

        // ... Leemos los templos del dia
        unset($param);
        $param['fecha_desde'] = $fecha . " 00:00:00";
        $param['fecha_hasta'] = $fecha . " 23:59:59";
        $param['id_centro'] = $data['id_centro'];
        $data['templos'] = $this->Estadisticas_model->usuarios($param);

        // Leemos facturacion de la Mañana
        unset($param);
        $param['fecha_desde'] = $fecha . " 00:00:00";
        $param['fecha_hasta'] = $fecha . " 15:59:59";
        $param['id_centro'] = $data['id_centro'];
        $data['facturacion_manana'] = $this->Estadisticas_model->usuarios($param);

        $data['total_efectivo_manana'] = 0;
        $data['total_tarjeta_manana'] = 0;
        $data['total_transferencia_manana'] = 0; //24/03/20
        $data['total_tpv2_manana'] = 0; //24/03/20
        $data['total_financiado_manana'] = 0; //24/03/20
        $data['total_habitacion_manana'] = 0;
        $data['total_manana'] = 0;
        if ($data['facturacion_manana'] != 0) {
            foreach ($data['facturacion_manana'] as $key => $row) {
                $data['total_efectivo_manana'] += $row['ventas_efectivo'];
                $data['total_tarjeta_manana'] += $row['ventas_tarjeta'];
                $data['total_transferencia_manana'] += $row['ventas_transferencia']; //24/03/20
                $data['total_tpv2_manana'] += $row['ventas_tpv2']; //31/08/21
                $data['total_financiado_manana'] += $row['ventas_financiado']; //24/03/20
                $data['total_habitacion_manana'] += $row['ventas_habitacion'];
                $data['total_manana'] += $row['ventas'];
            }
        }

        // Leemos facturacion de la Tarde
        unset($param);
        $param['fecha_desde'] = $fecha . " 16:00:00";
        $param['fecha_hasta'] = $fecha . " 23:59:59";
        $param['id_centro'] = $data['id_centro'];
        $data['facturacion_tarde'] = $this->Estadisticas_model->usuarios($param);

        $data['total_efectivo_tarde'] = 0;
        $data['total_tarjeta_tarde'] = 0;
        $data['total_transferencia_tarde'] = 0; //24/03/20
        $data['total_habitacion_tarde'] = 0;
        $data['total_tpv2_tarde'] = 0;
        $data['total_financiado_tarde'] = 0;
        $data['total_tarde'] = 0;
        if ($data['facturacion_tarde'] != 0) {
            foreach ($data['facturacion_tarde'] as $key => $row) {
                $data['total_efectivo_tarde'] += $row['ventas_efectivo'];
                $data['total_tarjeta_tarde'] += $row['ventas_tarjeta'];
                $data['total_transferencia_tarde'] += $row['ventas_transferencia']; //24/03/20
                $data['total_tpv2_tarde'] += $row['ventas_tpv2']; //31/08/21
                $data['total_financiado_tarde'] += $row['ventas_financiado']; //24/03/20
                $data['total_habitacion_tarde'] += $row['ventas_habitacion'];
                $data['total_tarde'] += $row['ventas'];
            }
        }

        // ... Totales de facturacion.
        $data['total_facturacion_efectivo'] = $data['total_efectivo_manana'] + $data['total_efectivo_tarde'];
        $data['total_facturacion_tarjeta'] = $data['total_tarjeta_manana'] + $data['total_tarjeta_tarde'];
        $data['total_facturacion_transferencia'] = $data['total_transferencia_manana'] + $data['total_transferencia_tarde']; //24/03/20
        $data['total_facturacion_financiado'] = $data['total_financiado_manana'] + $data['total_financiado_tarde']; //24/03/20
        $data['total_facturacion_tpv2'] = $data['total_tpv2_manana'] + $data['total_tpv2_tarde']; //24/03/20
        $data['total_facturacion_habitacion'] = $data['total_habitacion_manana'] + $data['total_habitacion_tarde'];
        $data['total_facturacion'] = $data['total_manana'] + $data['total_tarde'];

        // ... Totales de cierre de caja
        unset($param);
        $param['fecha_desde'] = $fecha . " 00:00:00";
        $param['fecha_hasta'] = $fecha . " 25:59:59";
        $param['id_centro'] = $data['id_centro'];
        $data['cierres_manana'] = $this->Caja_model->leer_cierres($param);
        $data['total_cierre_manana'] = 0;
        if ($data['cierres_manana'] != 0) {
            //foreach ($data['cierres_manana'] as $key => $row) {
            //$data['total_cierre_manana']+=$row['descuadre_efectivo']+$row['descuadre_tarjeta']+$row['descuadre_habitacion'];
            //}
            $row = $data['cierres_manana'][0];
            $data['total_cierre_manana'] += $row['descuadre_efectivo'] + $row['descuadre_tarjeta'] + $row['descuadre_habitacion'] + $row['descuadre_transferencia'] + $row['descuadre_tpv2'];
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
            $data['total_cierre_tarde'] += $row['descuadre_efectivo'] + $row['descuadre_tarjeta'] + $row['descuadre_habitacion'] + $row['descuadre_transferencia'] + $row['descuadre_tpv2'];
            //printr($data['cierres_manana']);
        }

        // ... Productos vendidos
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['productos_resumen'] = $this->Estadisticas_model->productos_resumen_dietario($param);

        // ... Servicios vendidos
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['servicios_resumen'] = $this->Estadisticas_model->servicios_resumen_dietario($param);

        // ... Descuentos
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['descuentos_resumen'] = $this->Estadisticas_model->descuentos_resumen_dietario($param);

        // ... Devoluciones
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['devoluciones_resumen'] = $this->Estadisticas_model->devoluciones_resumen_dietario($param);

        // ... Carnets templos
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['carnets_resumen'] = $this->Estadisticas_model->carnets_resumen_dietario($param);

        // ... Cajas regalo
        unset($param);
        $param['fecha'] = $fecha;
        $param['id_centro'] = $data['id_centro'];
        $data['cajasregalo_resumen'] = $this->Estadisticas_model->cajasregalo_resumen_dietario($param);

        // Carnets usados DE otros centros
        unset($param);
        $param['fecha_desde'] = $fecha . " 00:00:00";
        $param['fecha_hasta'] = $fecha . " 23:59:59";
        $param['id_centro'] = $data['id_centro'];
        $data['carnets_usados_de'] = $this->Intercentros_model->datos($param);

        $data['total_carnets_usados_de'] = 0;
        $data['numero_carnets_usados_de'] = 0;
        if ($data['carnets_usados_de'] != 0) {
            foreach ($data['carnets_usados_de'] as $key => $row) {
                $data['total_carnets_usados_de'] += $row['total'];
            }
            $data['numero_carnets_usados_de'] = count($data['carnets_usados_de']);
        }

        // ... Leemos todos los centros
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // Carnets usados EN otros centros
        $data['total_carnets_usados_en'] = 0;
        $data['numero_carnets_usados_en'] = 0;

        foreach ($data['centros_todos'] as $key => $c) {
            if ($c['id_centro'] != $data['id_centro']) {
                unset($param);
                $param['fecha_desde'] = $fecha . " 00:00:00";
                $param['fecha_hasta'] = $fecha . " 23:59:59";
                $param['id_centro'] = $c['id_centro'];
                $param['id_cen_carnet'] = $data['id_centro'];
                $data['carnets_usados_en'] = $this->Intercentros_model->datos($param);

                if ($data['carnets_usados_en'] != 0) {
                    foreach ($data['carnets_usados_en'] as $key => $row) {
                        $data['total_carnets_usados_en'] += $row['total'];
                    }
                    $data['numero_carnets_usados_en'] += count($data['carnets_usados_en']);
                }
            }
        }

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Resumen Dietario';
        $data['content_view'] = $this->load->view('dietario/dietario_resumen_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 24);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Gestión de tickets
    // ----------------------------------------------------------------------------- //
    public function tickets($fecha_desde = null, $fecha_hasta = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... controlamos que el perfil sea el master,
        // sino solo mostramos lo del centro que corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            // ... Leemos todos los centros
            unset($param2);
            $param2['vacio'] = "";
            $data['centros_todos'] = $this->Usuarios_model->leer_centros($param2);
            $data['id_centro'] = $id_centro;
        }

        if ($fecha_desde == null) {
            $fecha_desde = date("Y-m-d");
        }
        if ($fecha_hasta == null) {
            $fecha_hasta = date("Y-m-d");
        }
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        unset($parametros);
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $parametros['id_centro'] = $id_centro;
            }
        }
        $parametros['fecha_desde'] = $fecha_desde;
        $parametros['fecha_hasta'] = $fecha_hasta;
        $data['tickets'] = $this->Dietario_model->LeerTickets($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Tickets de Compra';
        $data['content_view'] = $this->load->view('dietario/dietario_tickets_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 40);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //08/06/20
    //Para ver todos los tickets de un cliente de una fecha
    public function ticket_today($fecha = null, $id_cliente = null)
    {
        unset($param);
        if ($fecha != null) {
            $param['fecha_desde'] = $fecha;
            $param['fecha_hasta'] = $fecha;
        } else {
            $param['fecha_desde'] = "2020-06-07";
            $param['fecha_hasta'] = "2020-06-07";
        }
        if ($id_cliente != null)
            $param['id_cliente'] = $id_cliente;
        else
            $param['id_cliente'] = 42565;

        //Buscar Carnets Unico Disponibles del Cliente
        unset($parametros);
        $parametros['codigo'] = "U" . $id_cliente;
        //$carnets=$this->Carnets_model->leer($param);
        $carnets = $this->Carnets_model->leer_un_carnet_codigo($parametros);

        //

        //$data['ticket']=$this->Dietario_model->LeerTicketsToday($param);
        $data = $this->Dietario_model->LeerTicketsToday($param);
        $xid_ticket = $data[0]['id_ticket'];

        $xservicio = "";
        $c = 0;
        $datos = array();
        $conceptos = array();
        $h = 0; //Para conceptos pagados con templos;
        foreach ($data as $row) {
            if ($xid_ticket != $row['id_ticket']) {
                $xid_ticket = $row['id_ticket'];
                $datos[$c]['servicios'] = $xservicio;
                $xservicio = "";
                $c++;
                $datos[$c]['id_ticket'] = $row['id_ticket'];

                //echo "<br>".$c.") ".$row['id_ticket'];
            }
            $xservicio .= $row['abreviatura'] . " ";

            if ($row['tipo_pago'] == "#templos") {
                $conceptos[$h]['direccion_completa'] = $row['direccion_completa'];
                $conceptos[$h]['atendido_por'] = $row['atendido_por'];
                $conceptos[$h]['fecha_creacion_aaaammdd_hhmmss'] = $row['fecha_creacion_aaaammdd_hhmmss'];
                $conceptos[$h]['id_ticket'] = $row['id_ticket'];
                $conceptos[$h]['tipo_pago'] = $row['tipo_pago'];
                $conceptos[$h]['templos'] = $row['templos'];
                $conceptos[$h]['servicio_completo'] = $row['servicio_completo'];
                $conceptos[$h]['id_carnet'] = $row['id_carnet'];
                $conceptos[$h]['codigo'] = $carnets[0]['codigo'];
                $conceptos[$h]['templos_disponibles'] = $carnets[0]['templos_disponibles'];
                $h++;
            }
        } //Foreach
        $datos[$c]['servicios'] = $xservicio;

        $c = count($conceptos);
        //echo "C ".$c;
        if ($c > 0) {
            for ($i = 0; $i < $c; $i++) {
                //echo "<br>"." Ticket ".$conceptos[$i]['id_ticket']." ".$conceptos[$i]['servicio_completo']." ".$conceptos[$i]['templos'];
            }
        }

        //Fin de ver templos
        $data_plantilla['conceptos'] = $conceptos;
        $content_view = $this->load->view('pdf/ticket_plantilla_templos_lotes_view', $data_plantilla, true); //Original
        // aqui empezaria lo nuevo

        $this->load->library('pdf');
        $set_paper = array(0, 0, 250, 1500);
        $set_option = ['dpi' => 72];
        //$set_option = ['dpi'=> 72, 'default_font' => 'Courier'];
        $this->pdf->stream($content_view, "Ticket-Cliente" . $id_cliente . ".pdf", array("Attachment" => false), $set_paper, $set_option);
    }
    //Fin

    //
    //
    //
    public function ver_ticket($id_ticket = null)
    {
        // ... Leemos los datos del ticket
        unset($param);
        $param['id_ticket'] = $id_ticket;
        $data['ticket'] = $this->Dietario_model->LeerTickets($param);
        $param['debug'] = 1;
        $conceptos = $this->Dietario_model->leer($param);
        $param['historial'] = $conceptos;
        $data['conceptos'] = $this->Dietario_model->carnets_pago_templos($param);
        // ... Viewer con el código para generar el HTML
        //14/04/20
        if ($data['conceptos'][0]['recarga'] == 1 or ($data['conceptos'][0]['tipo_pago'] != "#templos" and $data['conceptos'][0]['id_carnet'] > 0)) {
            $content_view = $this->load->view('pdf/ticket_plantilla_recarga_view', $data, true);
        } else {
            if ($data['conceptos'][0]['tipo_pago'] == "#templos")
                $content_view = $this->load->view('pdf/ticket_plantilla_templos_view', $data, true);
            else
                $content_view = $this->load->view('pdf/ticket_plantilla_view', $data, true); //Original
        }
        // aqui empezaria lo nuevo
        $this->load->library('pdf');
        $set_paper = array(0, 0, 250, 1500);//1500);
        //$set_paper = null;
        $set_option = ['dpi' => 72];
        //$set_option = ['dpi'=> 72, 'default_font' => 'Courier'];
        $this->pdf->stream($content_view, "Ticket-" . $id_ticket . ".pdf", array("Attachment" => false), $set_paper, $set_option);
        //file_put_contents($ruta, $content_view);
    }

    // 24/03/20
    public function cambio_efectivo_tarjeta($id_dietario = null, $cambio = null, $id_centro = null) //04/03/20 agregado $id_centro
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_dietario'] = $id_dietario;
        $data['registros'] = $this->Dietario_model->leer($param);
        $data['id_centro'] = $id_centro; //04/03/20 si es id_centro=9 mostrar en la vista como opción de pago "Habitación"
        //
        if ($cambio == "actualizar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if (isset($parametros['tipo_pago'])) {
                $AqConexion_model = new AqConexion_model();

                //04/03/20
                $xmonto = 0;
                if ($data['registros'][0]['pagado_tarjeta'] > 0)
                    $xmonto = $xmonto + $data['registros'][0]['pagado_tarjeta'];
                if ($data['registros'][0]['pagado_efectivo'] > 0)
                    $xmonto = $xmonto + $data['registros'][0]['pagado_efectivo'];
                if ($data['registros'][0]['pagado_habitacion'] > 0)
                    $xmonto = $xmonto + $data['registros'][0]['pagado_habitacion'];
                if ($data['registros'][0]['pagado_transferencia'] > 0)
                    $xmonto = $xmonto + $data['registros'][0]['pagado_transferencia'];
                if ($data['registros'][0]['pagado_tpv2'] > 0)
                    $xmonto = $xmonto + $data['registros'][0]['pagado_tpv2'];
                //Fin    

                if ($parametros['tipo_pago'] == "#efectivo") {
                    $dietario['pagado_tarjeta'] = 0;
                    //04/03/20
                    $dietario['pagado_habitacion'] = 0;
                    $dietario['pagado_transferencia'] = 0;
                    $dietario['pagado_tpv2'] = 0;
                    //Fin
                    //$dietario['pagado_efectivo'] = $data['registros'][0]['pagado_tarjeta'];
                    $dietario['pagado_efectivo'] = $xmonto;
                }
                if ($parametros['tipo_pago'] == "#tarjeta") {
                    //$dietario['pagado_tarjeta'] = $data['registros'][0]['pagado_efectivo'];
                    $dietario['pagado_tarjeta'] = $xmonto;
                    $dietario['pagado_efectivo'] = 0;
                    //04/03/20
                    $dietario['pagado_habitacion'] = 0;
                    $dietario['pagado_transferencia'] = 0;
                    $dietario['pagado_tpv2'] = 0;
                    //Fin
                }

                //04/03/20
                if ($parametros['tipo_pago'] == "#transferencia") {
                    $dietario['pagado_tarjeta'] = 0;
                    //04/03/20
                    $dietario['pagado_habitacion'] = 0;
                    $dietario['pagado_efectivo'] = 0;
                    $dietario['pagado_tpv2'] = 0;
                    //Fin
                    //$dietario['pagado_transferencia'] = $data['registros'][0]['pagado_tarjeta'];
                    $dietario['pagado_transferencia'] = $xmonto;
                }

                //31/08/20
                if ($parametros['tipo_pago'] == "#tpv2") {
                    $dietario['pagado_tarjeta'] = 0;
                    //04/03/20
                    $dietario['pagado_habitacion'] = 0;
                    $dietario['pagado_efectivo'] = 0;
                    $dietario['pagado_tpv2'] = $xmonto;
                    //Fin
                    //$dietario['pagado_transferencia'] = $data['registros'][0]['pagado_tarjeta'];
                    $dietario['pagado_transferencia'] = 0;
                }


                if ($parametros['tipo_pago'] == "#habitacion") {
                    //$dietario['pagado_habitacion'] = $data['registros'][0]['pagado_efectivo'];
                    $dietario['pagado_habitacion'] = $xmonto;
                    $dietario['pagado_efectivo'] = 0;
                    //04/03/20
                    $dietario['pagado_transferencia'] = 0;
                    $dietario['pagado_tpv2'] = 0;
                    $dietario['pagado_tarjeta'] = 0;
                    //Fin
                }
                //Fin

                if ($id_dietario > 0) {
                    $dietario['fecha_modificacion'] = date("Y-m-d H:i:s");
                    $dietario['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                    $dietario['tipo_pago'] = $parametros['tipo_pago'];
                    $where['id_dietario'] = $id_dietario;

                    $AqConexion_model->update('dietario', $dietario, $where);
                }

                $data['guardado'] = 1;
            }
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_cambio_efectivo_tarjeta_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }



    //
    // ... Abre ficha para generar factura de los items pagados del cliente
    // y no facturados.
    //
    public function facturacion($id_cliente = null, $id_centro_facturar = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        unset($param);
        $param['fecha_inicio'] = date("Y-m-d", strtotime("-30 days " . date("Y-m-d")));
        $param['fecha_fin'] = date("Y-m-d");
        $param['id_cliente'] = $id_cliente;
        $param['id_centro'] = $id_centro_facturar;
        $param['pte_facturar'] = 1;
        $param['debug'] = 1;

        $data['registros'] = $this->Dietario_model->leer($param);
        $data['id_centro_facturar'] = $id_centro_facturar;

        unset($param5);
		$param5['id_cliente'] = $id_cliente;
		$param5['aceptados'] = TRUE;
		$this->load->model('Presupuestos_model');
		$presupuestos_cliente = $this->Presupuestos_model->leer_presupuestos($param5);
		if($presupuestos_cliente != 0 && count($presupuestos_cliente) > 0 ) {
			$data['presupuestos_cliente'] = $presupuestos_cliente;
		}

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_facturacion_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //12/10/20
    public function datos_facturacion($id_cliente = null)
    {
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['registros'] = $this->Clientes_model->leer_clientes($param);
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // vertificar si es un recibo o una factura lo que se tiene que recargar
        if(isset($_GET['recibo'])){
            $data['recibo']=1;
        }
        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/datos_facturacion_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    public function registrar_datos_facturacion($id_cliente = null)
    {
        $data['accion'] = "actualizar";
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['empresa'] = $_POST['empresa'];
        $param['cif_nif'] = $_POST['cif_nif'];
        $param['direccion_facturacion'] = $_POST['direccion_facturacion'];
        $param['codigo_postal_facturacion'] = $_POST['codigo_postal_facturacion'];
        $param['localidad_facturacion'] = $_POST['localidad_facturacion'];
        $param['provincia_facturacion'] = $_POST['provincia_facturacion'];
        $modificar = $this->Clientes_model->actualiza_facturacion($param);
        $data['llevo'] = $param;
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // vertificar si es un recibo o una factura lo que se tiene que recargar
        if(isset($_GET['recibo'])){
            $data['recibo']=1;
        }
        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/datos_facturacion_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    //Fin


    //
    // ... Abre ficha para generar factura de los items pagados del cliente
    // y no facturados.
    //
    public function generarticket($id_cliente = null, $id_centro_facturar = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        unset($param);
        $param['fecha_inicio'] = date("Y-m-d", strtotime("-30 days " . date("Y-m-d")));
        $param['fecha_fin'] = date("Y-m-d");
        $param['id_cliente'] = $id_cliente;
        $param['id_centro'] = $id_centro_facturar;
        $param['no_ticket'] = 1;
        $param['debug'] = 1;
        $data['registros'] = $this->Dietario_model->leer($param);

        $data['id_centro_facturar'] = $id_centro_facturar;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_generarticket_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Crear una factura.
    // ----------------------------------------------------------------------------- //
    public function ticket_crear($id_cliente, $id_centro_facturar)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos cada uno de los conceptos del dietario marcados.
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        // Aseguramos que ninguna concepto del dietario ha sido
        // facturado antes. Esto puede pasar si se abren dos ventanas
        // para facturar a la vez.
        $sw = 0;
        foreach ($parametros['marcar'] as $id_dietario) {
            $r = $this->Dietario_model->en_ticket($id_dietario);

            if ($r == 1) {
                $sw = 1;
            }
        }
        if ($sw == 1) {
            // ... Leemos los datos del cliente.
            unset($param);
            $param['id_cliente'] = $id_cliente;
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
            if ($permiso) {
                $this->load->view('dietario/dietario_ticket_creado_error_view', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ... Creamos los datos básicos del ticket.
        unset($param);
        $parametros['marcados'] = $parametros['marcar'];
        $data['id_ticket'] = $this->Dietario_model->CrearTicket($parametros['marcados'], $id_cliente);

        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_ticket_creado_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Crear una factura.
    // ----------------------------------------------------------------------------- //
    //modificacion Alfonso 24/01/2024
    public function factura_crear($id_cliente, $id_centro_facturar, $version=NULL)
    {
        //show_array($_POST);exit;
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        //verificamos que no contenga presupuestos ya facturados
        $sw = $this->Dietario_model->verificar_conceptos_factura($_POST['conceptos']);

        if ($sw == 1) {
            unset($param);
            $param['id_cliente'] = $id_cliente;
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
            if ($permiso) {
                $this->load->view('dietario/dietario_facturar_creado_error_view', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
        //guardamos factura
        $id_factura = $this->Dietario_model->crear_factura_new($_POST);
        

        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);
        $data['id_factura'] = $id_factura;
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // *** version de factura 2
        $data['version']=$version;

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_facturar_creado_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Crear un Recibo.
    // ----------------------------------------------------------------------------- //
    public function recibo_crear($id_cliente, $id_centro_facturar)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos cada uno de los conceptos del dietario marcados.
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;
        //printr( $parametros);
        // Aseguramos que ninguna concepto del dietario ha sido
        // facturado antes. Esto puede pasar si se abren dos ventanas
        // para facturar a la vez.
        $sw = 0;
        foreach ($parametros['marcar'] as $id_dietario) {
            $r = $this->Dietario_model->ya_tiene_recibo($id_dietario);
            if ($r == 1) {
                $sw = 1;
            }
        }
        //echo $sw;exit;
        if ($sw == 1) {
            unset($param);
            $param['id_cliente'] = $id_cliente;
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
            if ($permiso) {
                $this->load->view('dietario/dietario_facturar_creado_error_view', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ... Creamos los datos básicos del recibo.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['id_centro_facturar'] = $id_centro_facturar;
        $id_recibo = $this->Dietario_model->crear_recibo($param);

        // ... Procedemos a crear cada concepto del recibo.
        if ($id_recibo > 0) {
            foreach ($parametros['marcar'] as $key => $id_dietario) {
                unset($param);
                $param['id_dietario'] = $id_dietario;
                $param['debug'] = 1; // se cuentan los debug
                $dietario = $this->Dietario_model->leer($param);

                // ... Creamos el concepto correspondiente
                unset($param);
                $param['id_recibo'] = $id_recibo;
                $param['id_dietario'] = $dietario[0]['id_dietario'];
                $descripcion = "";
                $param['iva'] = (isset($parametros['iva'])) ? $parametros['iva'] : 0;
                if ($dietario[0]['id_servicio'] > 0) {
                    $descripcion = $dietario[0]['servicio_completo'];
                    $param['iva'] = $this->Servicios_model->iva_servicio($dietario[0]['id_servicio']);
                }
                if ($dietario[0]['id_producto'] > 0) {
                    $descripcion = $dietario[0]['producto'];
                    $param['iva'] = $this->Productos_model->iva_producto($dietario[0]['id_producto']);
                }
                if ($dietario[0]['id_carnet'] > 0) {
                    $param1['id_tipo'] = $dietario[0]['id_tipo'];
                    $tipo = $this->Carnets_model->tipos($param1);

                    $recarga = "";
                    if ($dietario[0]['recarga'] == 1) {
                        $recarga = "recarga";
                    }

                    $descripcion = $tipo[0]['descripcion'] . " (código: " . $dietario[0]['carnet'] . ") " . $recarga;
                }
                if ($dietario[0]['pago_a_cuenta'] == 1) {
                    $descripcion = "";

                    if($parametros['presupuestosrelacionados'][$key] != ''){
                        $this->load->model('Presupuestos_model');
                        $presupuestos = explode('|', $parametros['presupuestosrelacionados'][$key]);
                        foreach ($presupuestos as $k => $presupuesto) {
                            $presupuestoExplode = explode(':', $presupuesto);
                            $p_id_presupuesto = $presupuestoExplode[0];
                            $p_importe = $presupuestoExplode[1];
                            $elPresupuesto = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $p_id_presupuesto]);
                            $descripcion .= "Pago a cuenta del presupuesto #".$elPresupuesto[0]["nro_presupuesto"]." de ".euros($p_importe)." <br>";
                        }

                    }else{
                        $descripcion = "Pago a cuenta";
                    }

                }
                if ($dietario[0]['id_presupuesto'] > 0) {
                    $descripcion = $dietario[0]['nro_presupuesto'];
                    $param['iva'] = 0;
                }
                $param['descripcion'] = $descripcion;

                $iva = $param['iva'];
                if($iva > 0){
                    $iva = ($iva / 100) + 1;
                }

                if ($dietario[0]['descuento_euros'] > 0) {
                    $param['descuento_euros'] = $dietario[0]['descuento_euros'];
                    $param['descuento_porcentaje'] = ($dietario[0]['descuento_euros'] * 100) / ($dietario[0]['importe_euros']);
                } else {
                    $param['descuento_porcentaje'] = $dietario[0]['descuento_porcentaje'];
                    $param['descuento_euros'] = ($dietario[0]['descuento_porcentaje'] * $dietario[0]['importe_euros']) / 100;
                }

                /*
                if ($dietario[0]['tipo_pago_saldo'] == "#liquidacion") {
                    $param['descuento_euros'] -= $dietario[0]['importe_saldo'];
                }
                */

                // Si el pago es diferente a bono pongo los importes correspondientes.
                //if ($dietario[0]['tipo_pago']!="#bono")
                //{
                $param['importe'] = ($iva > 0) ? ($dietario[0]['importe_euros'] - $param['descuento_euros']) / $iva : $dietario[0]['importe_euros'] - $param['descuento_euros'];
                $param['iva_euros'] = ($iva > 0) ? ($param['iva'] / 100) * ($param['importe']) : 0;
                $param['total'] = ($param['importe']) + $param['iva_euros'];
                //}
                // Sino, todo va a 0 porque es pago con bono.s
                //else
                //{
                //  $param['importe']=0;
                //  $param['iva_euros']=0;
                //  $param['total']=0;
                //}

                $this->Dietario_model->crear_recibo_concepto($param);
            }
        }

        // ... Actualizamos los importes de la factura.
        $dietario = $this->Dietario_model->actualizar_recibo_importes($id_recibo);

        // ... Leemos los datos del cliente.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        $data['id_recibo'] = $id_recibo;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);

        if ($permiso) {
            $this->load->view('dietario/dietario_recibo_creado_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Ver factura
    // ----------------------------------------------------------------------------- //
    public function ver_factura($id_factura = null)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Creamos la factura en PDF
        $dietario = $this->Dietario_model->CrearPDF($id_factura);

        exit;
    }
    public function ver_recibo($id_recibo = null)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Creamos la factura en PDF
        $dietario = $this->Dietario_model->CrearPDF_recibo($id_recibo);

        exit;
    }

    // ----------------------------------------------------------------------------- //
    // ... Listado de facturas generadas
    // ----------------------------------------------------------------------------- //
    public function facturas($accion = null)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = 0;

        unset($parametros);
        $parametros['vacio'] = "";

        if ($accion == "buscar") {
            // ... filtros
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            // ... Leemos las facturas
            if (isset($parametros['fecha_desde'])) {
                $data['fecha_desde'] = $parametros['fecha_desde'];
                $data['fecha_hasta'] = $parametros['fecha_desde'];
            }
            if (isset($parametros['fecha_hasta'])) {
                $data['fecha_hasta'] = $parametros['fecha_hasta'];
            }

            if (isset($parametros['buscar'])) {
                $data['buscar'] = $parametros['buscar'];
            }
        }

        // ... Pasamos el parametro de id_centro como paramtro si no es master
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }

        // ... Leemos todas las facturas.
        $data['pagetitle'] = 'Facturas generadas';
        $data['actionstitle'][] = '<a href="' . base_url() . 'clientes/facturacion" class="btn btn-warning text-inverse-primary">Nueva factura</a>';
        $data['facturas'] = $this->Dietario_model->facturas($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('dietario/dietario_facturas_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 44);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Listado de recibosogeneradas
    // ----------------------------------------------------------------------------- //
    public function recibos($accion = null)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = 0;

        unset($parametros);
        $parametros['vacio'] = "";

        if ($accion == "buscar") {
            // ... filtros
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            // ... Leemos las facturas
            if (isset($parametros['fecha_desde'])) {
                $data['fecha_desde'] = $parametros['fecha_desde'];
                $data['fecha_hasta'] = $parametros['fecha_desde'];
            }
            if (isset($parametros['fecha_hasta'])) {
                $data['fecha_hasta'] = $parametros['fecha_hasta'];
            }

            if (isset($parametros['buscar'])) {
                $data['buscar'] = $parametros['buscar'];
            }
        }

        // ... Pasamos el parametro de id_centro como paramtro si no es master
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }

        // ... Leemos todas los recibos.
        $data['pagetitle'] = 'Recibos generados';
        $data['recibos'] = $this->Dietario_model->recibos($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('dietario/dietario_recibos_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 44);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Devolver un carnet de templos o servicios que no ha sido usando aún.
    // ----------------------------------------------------------------------------- //
    public function devolucion_carnet($id_dietario = 0)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos los datos de la línea de dietario.
        unset($param);
        $param['id_dietario'] = $id_dietario;
        $dietario = $this->Dietario_model->leer($param);

        // ... Comprobamos que lo que se lee es un carnet comprado, no recarga y pagado.
        if ($dietario[0]['id_carnet'] > 0 && $dietario[0]['recarga'] == 0 && $dietario[0]['estado'] == "Pagado") {
            $usado = $this->Carnets_model->usado($dietario[0]['id_carnet']);

            if ($usado) {
                $data['usado'] = 1;
            } else {
                // ... Leemos el id_carnet con el que se pago en templos
                // el carnet especial comprado.
                if ($dietario[0]['tipo_pago'] == "#templos") {
                    $carnet_pago_templos = $this->Carnets_model->carnet_usado_pago_templos($dietario[0]['id_dietario']);

                    if ($carnet_pago_templos != 0) {
                        $data['carnet_pago_templos'] = $carnet_pago_templos;
                    }
                }
            }
        }

        $data['dietario'] = $dietario;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_devolucion_carnet_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Realizar la devolución de un carnet no usado.
    // ----------------------------------------------------------------------------- //
    public function devolucion_carnet_realizar($id_dietario = 0)
    {
        if ($id_dietario > 0) {
            // ... Comprobamos la sesion del cliente
            $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
            if ($ok_ticket == 0) {
                header("Location: " . RUTA_WWW);
                exit;
            }

            // ... Leemos los datos de la línea de dietario.
            unset($param);
            $param['id_dietario'] = $id_dietario;
            $dietario = $this->Dietario_model->leer($param);

            // ... Comprobamos que lo que se lee es un carnet comprado, no recarga y pagado.
            if ($dietario[0]['id_carnet'] > 0 && $dietario[0]['recarga'] == 0 && $dietario[0]['estado'] == "Pagado") {
                $usado = $this->Carnets_model->usado($dietario[0]['id_carnet']);

                if ($usado) {
                    echo "Error. No se puede devolver el carnet porque ya ha sido usado";
                    exit;
                } else {
                    // Realizamos la devolucion
                    $param['id_cliente'] = $dietario[0]['id_cliente'];
                    $param['id_cita'] = 0;
                    $param['fecha_hora_concepto'] = date("Y-m-d H:i:s");
                    $param['id_empleado'] = $this->session->userdata('id_usuario');
                    $param['id_producto'] = 0;
                    $param['id_servicio'] = 0;
                    $param['id_carnet'] = 0;
                    $param['recarga'] = 0;
                    $param['importe_euros'] = $dietario[0]['importe_euros'] * -1;
                    $param['pagado_efectivo'] = 0;
                    $param['pagado_tarjeta'] = 0;
                    $param['pagado_transferencia'] = 0; //06/06/22
                    $param['pagado_habitacion'] = 0;
                    if ($this->input->post('forma_pago') == "#efectivo") {
                        $param['pagado_efectivo'] = $param['importe_euros'];
                    }
                    if ($this->input->post('forma_pago') == "#tarjeta") {
                        $param['pagado_tarjeta'] = $param['importe_euros'];
                    }
                    if ($this->input->post('forma_pago') == "#transferencia") { //06/06/22
                        $param['pagado_transferencia'] = $param['importe_euros'];
                    }
                    if ($this->input->post('forma_pago') == "#habitacion") {
                        $param['pagado_habitacion'] = $param['importe_euros'];
                    }
                    $param['tipo_pago'] = $this->input->post('forma_pago');
                    $param['templos'] = $dietario[0]['templos'];
                    $param['id_carnet'] = $dietario[0]['id_carnet'];

                    $param['estado'] = "Devuelto";
                    $param['motivo_devolucion'] = $this->input->post('motivo_devolucion');

                    if ($this->input->post('id_carnet_pago_templos') > 0 && $this->input->post('forma_pago') == "#templos") {
                        unset($param4);
                        $param4['id_carnet'] = $this->input->post('id_carnet_pago_templos');
                        $carnet_elegido = $this->Carnets_model->leer($param4);

                        $param['motivo_devolucion'] .= "<br> Carnet: <a href='<?php echo base_url();?>carnets/detalle/gestion/{$carnet_elegido[0]['id_carnet']}' target='_blank'><strong>{$carnet_elegido[0]['codigo']}</strong></a>";
                    }

                    // ... Guardamos la devolucion en el diaetario
                    $id_dietario_devolucion = $this->Dietario_model->devolucion($param);

                    // ... Si es una devolución a un carnet, le sumamos los templos
                    // y lo guardamos en el historico de cambios.
                    if ($this->input->post('id_carnet_pago_templos') > 0 && $this->input->post('forma_pago') == "#templos") {
                        unset($param3);
                        $param3['id_carnet'] = $this->input->post('id_carnet_pago_templos');
                        $param3['id_dietario'] = $id_dietario_devolucion;
                        $param3['id_servicio'] = 0;
                        $param3['id_cliente'] = $param['id_cliente'];
                        $param3['id_empleado'] = $this->session->userdata('id_usuario');
                        $param3['templos'] = $param['templos'] * -1;

                        $ok = $this->Carnets_model->anadir_historial($param3);

                        // ... Modificamos los templos disponibles en el carnet.
                        unset($param5);
                        $param5['id_carnet'] = $this->input->post('id_carnet_pago_templos');
                        $param5['templos_disponibles'] = ($carnet_elegido[0]['templos_disponibles'] + $param['templos']);
                        $param5['ajuste_automatico'] = 1;

                        $id = $this->Carnets_model->ajustes_templos($param5);
                    }

                    // Borramos el carnet, para que se pueda volver a usar su numero.
                    $carnet_borrar['id_carnet'] = $dietario[0]['id_carnet'];
                    $this->Carnets_model->borrar($carnet_borrar);
                }
            }

            $data['dietario'] = $dietario;
            $data['cerrar'] = 1;

            // ... Modulos del cliente
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
            if ($permiso) {
                $this->load->view('dietario/dietario_devolucion_carnet_view', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        } else {
            echo "error parametros";
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Pagos a cuenta por el cliente.
    // ----------------------------------------------------------------------------- //
    public function pago_a_cuenta($id_cliente = 0)
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";
        if($id_cliente > 0){
            unset($param5);
            $param5['id_cliente'] = $id_cliente;
            $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
        }
        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/pago_a_cuenta_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function realizar_pago_a_cuenta()
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($this->input->post('id_cliente') > 0) {
            // recorrer los distintos tipos de pago y crear una linea por cada uno
            $id_cliente = $this->input->post('id_cliente');
            $tipo_pago = ['efectivo', 'tarjeta', 'tpv2', 'paypal', 'transferencia', 'financiado'];
            foreach ($tipo_pago as $key => $value) {
                $vpost = 'pagado_'.$value;
                if($this->input->post($vpost) > 0){
                    $importe = $this->input->post($vpost);
                    $tipo_pago = '#'.$value;
                    $estado = ($value == 'transferencia' || $value == 'financiado') ? "Pendiente justificante" : "Pagado";
                    $id_dietario = $this->Dietario_model->realizar_pago_a_cuenta($id_cliente, $importe, $tipo_pago, $vpost, $estado);
                    $id = $this->Clientes_model->pago_a_cuenta($id_dietario, $id_cliente, $importe, $tipo_pago, 'Ingreso cuenta');
                }
            }
        }

        $data['operacion_correcta'] = 1;

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/pago_a_cuenta_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //------------------------------------------------------------------------------//
    //   PRESUPUESTOS
    // -----------------------------------------------------------------------------//
    public function presupuesto()
    {

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... controlamos que el perfil sea el master,
        // sino solo mostramos lo del centro que corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            // ... Leemos todos los centros
            unset($param2);
            $param2['vacio'] = "";
            $data['centros_todos'] = $this->Usuarios_model->leer_centros($param2);
            //$data['id_centro'] = $id_centro;
        }

        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        //$data['registros'] = $this->Usuarios_model->leer_usuarios($parametros);

        // if ($fecha_desde == null) {
        $fecha_desde = date("Y-m-d");
        //}
        //if ($fecha_hasta == null) {
        $fecha_hasta = date("Y-m-d");
        //}
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        unset($parametros);
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            //if ($id_centro > 0) {
            //$parametros['id_centro'] = $id_centro;
            //}
        }
        $parametros['fecha_desde'] = $fecha_desde;
        $parametros['fecha_hasta'] = $fecha_hasta;
        $data['tickets'] = $this->Dietario_model->LeerTickets($parametros);

        // ... Leemos los empleados
        unset($parametros);
        $parametros['solo_empleados_recepcionistas'] = 1;
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos los productos
        unset($parametros);
        $parametros['vacio'] = "";
        $data['productos'] = $this->Productos_model->leer_productos($parametros);

        // ... Leemos los servicios
        unset($param);
        $param = [];
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);

        //$data['cliente_elegido']=$this->Clientes_model->leer_clientes($param5);

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('dietario/dietario_presupuesto_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 40);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function altaCliente()
    {

        $parametros['nombre'] = $_POST['nombre'];
        $parametros['apellidos'] = $_POST['apellidos'];
        $parametros['telefono'] = $_POST['telefono'];
        if (isset($_POST['no_quiere_publicidad'])) {
            $parametros['no_quiere_publicidad'] = $_POST['no_quiere_publicidad'];
        }
        $data['cita'][0]['id_cliente'] = $this->Clientes_model->nuevo_cliente($parametros);

        //19/05/20 Crear carnet Único ******************* asignar asignar carnet único **********
        $codigo_carnet = "U" . $data['cita'][0]['id_cliente'];
        $id_carnet = "9988" . $data['cita'][0]['id_cliente'];
        $id_carnet = intval($id_carnet);
        //echo "no ".$id_carnet." ".$codigo_carnet;
        unset($param2);
        $param2['id_carnet'] = $id_carnet;
        $param2['codigo'] = $codigo_carnet;
        $param2['id_cliente'] = $data['cita'][0]['id_cliente'];

        $guardar_codigo = $this->Carnets_model->guardar_carnet_unico($param2);
        //echo " guardar ".$guardar_codigo;
        //Fin *********************** fin ************************* fin *********


        unset($param5);
        $param5['id_cliente'] = $data['cita'][0]['id_cliente'];
        $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('dietario_presupuesto_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 40);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function crearPresupuesto()
    {

        $sumaTotalProductos = 0.00;
        $sumaTotalServicios = 0.00;
        for ($x = 1; $x <= 15; $x++) {
            if ($_POST['productoNombre' . $x] != "") {
                if ($_POST['productoDescuento' . $x] != "0") {
                    $descuento = 100;
                    $descuentoproducto = ($_POST['productoDescuento' . $x]);
                    $DescuentoTotal = intval($descuento) - intval($descuentoproducto);
                    $suma = ($_POST['productoPrecio' . $x] * $DescuentoTotal) / 100;
                    $sumaTotalProductos = number_format($sumaTotalProductos, 2, '.', ',') + number_format($_POST['productoCantidad' . $x] * $suma, 2, '.', ',');
                } else {
                    $sumaTotalProductos = number_format($sumaTotalProductos, 2, '.', ',') + number_format($_POST['productoCantidad' . $x] * $_POST['productoPrecio' . $x], 2, '.', ',');
                }
            }

            if ($_POST['servicioNombre' . $x] != "") {
                if ($_POST['servicioDescuento' . $x] != "0") {
                    $descuento = 100;
                    $descuentoservicio = ($_POST['servicioDescuento' . $x]);
                    $DescuentoTotalServicio = intval($descuento) - intval($descuentoservicio);
                    $sumaServicio = ($_POST['servicioPrecio' . $x] * $DescuentoTotalServicio) / 100;
                    $sumaTotalServicios = number_format($sumaTotalServicios, 2, '.', ',') + number_format($_POST['servicioCantidad' . $x] * $sumaServicio, 2, '.', ',');
                } else {
                    $sumaTotalServicios = number_format($sumaTotalServicios, 2, '.', ',') + number_format($_POST['servicioCantidad' . $x] * $_POST['servicioPrecio' . $x], 2, '.', ',');
                }
            }
        }

        $data["datos"] = $_POST;
        $data['TotalProductos'] = $sumaTotalProductos;
        $data['TotalServicios'] = $sumaTotalServicios;
        $content_view = $this->load->view('pdf/presupuesto_plantilla_view', $data, true);
        $this->load->library('pdf');
        $this->pdf->stream($content_view, "presupuesto-" . time() . ".pdf", array("Attachment" => false));
    }
    public function primerasvisitas($accion = null, $fecha = null, $id_centro = null)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['hoy'] = date("d-m-Y");

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
        } else {
            $param['fecha'] = $fecha;
        }
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $param['id_centro'] = $id_centro;
            }
        }
        $dietario = $this->Dietario_model->leer($param);

        $data['hoy_aaaammdd'] = $param['fecha'];

        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        $data['id_centro'] = $id_centro;
        $data['pagetitle'] = 'Primeras visitas';
        // $data['actionstitle'][] = '<a href="' . base_url() . 'facturas/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir primera visita</a>';
        $parametrosFiltroVisita['isDoctor'] = true;
        $parametrosFiltroVisita['isGrouped'] = true;
        $data['registros'] = $this->Citas_model->getPrimerasVisitas($parametrosFiltroVisita);
        $data['content_view'] = $this->load->view('dietario/primeras-visitas/primeras_visitas_view.php', $data, true);


        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    public function reemplazar_x000D() {
        $tables = $this->db->list_tables();

        foreach ($tables as $table) {
            $columns = $this->db->list_fields($table);

            foreach ($columns as $column) {
                $this->db->set($column, "REPLACE($column, '_x000D_', ' ')", false);
                $this->db->update($table);
            }
        }
    }


    public function parrilla(){
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->model('Presupuestos_model');
        // ... Viewer con el contenido
        $data['pagetitle'] = 'Parrilla Primeras Visitas';
        $data['actionstitle'] = [];
        if ($this->session->userdata('msn_estado') != '') {
            $data['estado'] = $this->session->userdata('msn_estado');
            $this->session->unset_userdata('msn_estado');
        }
        if ($this->session->userdata('msn_borrado') != '') {
            $data['borrado'] = $this->session->userdata('msn_borrado');
            $this->session->unset_userdata('msn_borrado');
        }
        if ($this->session->userdata('msn_actionno') != '') {
            $data['actionno'] = $this->session->userdata('msn_actionno');
            $this->session->unset_userdata('msn_actionno');
        }
        $data['usuarios'] = $this->Presupuestos_model->get_usuarios_presupuestos();
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);


        $data['content_view'] = $this->load->view('dietario/dietario_parrilla_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 57);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function set_presupuestopagoestado(){
        $this->load->model('Presupuestos_model');
        $rtval=["result"=>false,"estado"=>""];
        $idpresupuesto=$this->input->post('id_presupuesto');
        $estado=$this->input->post('estado');
        if($idpresupuesto){
            $parametros = [
                'id_presupuesto' => $idpresupuesto,
                'pago_estado_manual' => $estado
            ];
            $c= $this->Presupuestos_model->actualizar_estado_pago_manual($parametros);
            if ($c > 0) {
                $rtval['result']=true;
                $rtval['estado']=$estado;
            }
        }
        echo json_encode($rtval);
    }

    public function get_presupuestos($table = null, $columna = null, $valor = null)
    {
        $this->load->model('Presupuestos_model');
        $this->load->model('Agenda_model');

        $this->load->library('Datatable');

        $camposParrilla =[
            'citas.id_cita',
            'citas.id_servicio',
            'citas.id_usuario_empleado',
            'citas.fecha_hora_inicio',
            'citas.estado',
            'citas.archivado_parrilla',
            'servicios.nombre_servicio',
            //'creador.creador',
            'centros.nombre_centro',
            'citas.id_cliente',
            'CONCAT(clientes.nombre, " ",clientes.apellidos) AS nombre_completo_cliente',
            'clientes.nombre AS nombre_cliente',
            'clientes.apellidos AS apellidos_cliente',
            'clientes.telefono AS telefono_cliente',
            'doctoresencita.nombre AS nombre_doctor_cita',
            'doctoresencita.apellidos AS apellidos_doctor_cita',
            'doctordietario.nombre AS nombre_doctor_dietario',
            'doctordietario.apellidos AS apellidos_doctor_dietario',
            'presupuestos.id_presupuesto',
            'presupuestos.fecha_creacion AS presupuesto_fecha_creacion',
            'presupuestos.fecha_validez AS presupuesto_fecha_validez',
            'presupuestos.estado AS presupuesto_estado',
            'presupuestos.totalpresupuesto',
            'presupuestos.total_aceptado',
            '(CASE WHEN dto_100 > 0 THEN presupuestos.dto_100
				ELSE ((presupuestos.total_sin_descuento - presupuestos.totalpresupuesto) / presupuestos.total_sin_descuento) * 100
				END ) AS descuento',
        ];
        $tablaParrilla='citas';

        $joinParrilla= [
            'servicios' => array("inner","(citas.id_servicio = servicios.id_servicio AND ".
                                "(servicios.nombre_servicio = 'PRIMERA VISITA' OR servicios.nombre_servicio = 'Derivación especialista' OR servicios.nombre_servicio = 'Old Contact'))"),
            'usuarios AS creador' => 'citas.id_usuario_creador = creador.id_usuario',
            'centros' => 'creador.id_centro = centros.id_centro',
            'clientes' => 'citas.id_cliente = clientes.id_cliente',
            'usuarios AS doctoresencita' => 'citas.id_usuario_empleado = doctoresencita.id_usuario',
            'dietario' => 'dietario.id_cita = citas.id_cita',
            'usuarios AS doctordietario'=>'dietario.id_empleado = doctordietario.id_usuario',
            'presupuestos'=>'dietario.id_presupuesto = presupuestos.id_presupuesto',
        ];

        $add_ruleParrilla=[];
        $whereParrilla=[
            'citas.borrado' => 0,
        //    'citas.estado !=' => [],
        ];
        $filter_month=date("m");
        if($this->input->get('filter_month')!= ''){
            $filter_month=$this->input->get('filter_month');
        }
        $filter_year=date("Y");
        if($this->input->get('filter_year')!= ''){
            $filter_year=$this->input->get('filter_year');
        }
        $whereParrilla['citas.fecha_hora_inicio >='] = $filter_year."-".$filter_month."-01 00:00:00";
        if($filter_year==date("Y") && $filter_month==date("m")){
            $whereParrilla['citas.fecha_hora_inicio <='] =date("Y-m-d H:i:s");
        }
        else
        $whereParrilla['citas.fecha_hora_inicio <='] = $filter_year."-".$filter_month."-".date("t",strtotime($filter_year."-".$filter_month."-01 00:00:00"))." 23:59:59";


        if ($this->input->get('id_centro') != '' && $this->input->get('id_centro')>0) {
            $whereParrilla['centros.id_centro'] = $this->input->get('id_centro');
            //$where['doctordietario.id_centro']=$this->input->get('id_centro');
        }


        if($this->input->get('archivado')!= ''){
            $whereParrilla['citas.archivado_parrilla'] = $this->input->get('archivado');
        }


        $paramsGet=$this->input->get();
         if(!isset($paramsGet['order'])){
             $paramsGet['order'][0]=['column'=>9,'dir'=>'ASC'];
         }

        if (($table != "") && ($columna != "") && ($valor != "")) {
            $where[$table . '.' . $columna] = $valor;
            $result = json_decode($this->datatable->get_datatable($paramsGet, $tablaParrilla, $camposParrilla, $joinParrilla, $where, $add_ruleParrilla));
        } else {
            $result = json_decode($this->datatable->get_datatable($paramsGet, $tablaParrilla, $camposParrilla, $joinParrilla, $whereParrilla, $add_ruleParrilla));
        }
        //$result->query = $this->db->last_query();
        $buscarPresupuestoPara=[];
        $idsClientes=[];
        $this->load->model('Observacionescita_model');
        foreach($result->data as $unresult){

            $observaciones=$this->Observacionescita_model->getObservacionesCita($unresult->id_cita);
            $unresult->count_observaciones= $observaciones ? count($observaciones) : 0;
            $unresult->ultima_observacion = $observaciones ? preg_replace('/<br\s*\/?>/i', "\n", $observaciones[0]['observacion']) : '';

            $palabras = explode(" ", $unresult->nombre_servicio);
            $unresult->nombre_servicio = "";

            // Itera sobre cada palabra y obtiene su primer caracter
            foreach ($palabras as $palabra) {
                // Agrega el primer caracter de la palabra al resultado
                $unresult->nombre_servicio .= substr($palabra, 0, 1).". ";
            }

            $unresult->fecha_inicio=date("Y-m-d",strtotime($unresult->fecha_hora_inicio));
            $unresult->hora_inicio=date("H:i:s",strtotime($unresult->fecha_hora_inicio));

            $idsClientes[$unresult->id_cliente]=$unresult->id_cliente;
            $unresult->fecha_proxima_cita=null;
            $unresult->hora_proxima_cita=null;
            $unresult->pagado=0;
            $unresult->es_presupuesto_cita=false;
            $unresult->count_presupuestos=0;
            if($unresult->presupuesto_fecha_creacion) $unresult->presupuesto_fecha_creacion=date("Y-m-d",strtotime($unresult->presupuesto_fecha_creacion));
            $parametros=[
                'id_cliente'=>$unresult->id_cliente,
                'fecha_creacion_fin'=>date("Y-m-d H:i:s",strtotime("-1 minute",strtotime($unresult->fecha_hora_inicio)))
            ];

            $presupuestos=$this->Presupuestos_model->leer_presupuestos($parametros);

            $unresult->presupuestos_anteriores=[];
            if($presupuestos > 0 ){
                $unresult->presupuestos_anteriores=$presupuestos;
            }
            if(!$unresult->id_presupuesto){
                if(!in_array($unresult->id_cliente,$buscarPresupuestoPara)) {
                    $parametros=[
                        'id_cliente'=>$unresult->id_cliente,
                        'fecha_creacion_inicio'=>date("Y-m-d",strtotime($unresult->fecha_hora_inicio))
                    ];
                    $presupuestos=$this->Presupuestos_model->leer_presupuestos($parametros);
                    if($presupuestos > 0 ){
                        $unresult->count_presupuestos=count($presupuestos);
                        $unresult->id_presupuesto=$presupuestos[0]['id_presupuesto'];
                        $unresult->nro_presupuesto=$presupuestos[0]['nro_presupuesto'];
                        $unresult->pago_estado_manual=$presupuestos[0]['pago_estado_manual'];
                        $unresult->presupuesto_fecha_creacion=date("Y-m-d",strtotime($presupuestos[0]['fecha_creacion']));
                        $unresult->presupuesto_fecha_validez = date("Y-m-d",strtotime($presupuestos[0]['fecha_validez']));
                        $unresult->presupuesto_estado = $presupuestos[0]['estado'];
                        $unresult->totalpresupuesto = $presupuestos[0]['totalpresupuesto'];
                        $unresult->total_aceptado = $presupuestos[0]['total_aceptado'];
                        $unresult->descuento = round($presupuestos[0]['dto_100'] > 0 ? $presupuestos[0]['dto_100'] :
                            ($presupuestos[0]['total_sin_descuento'] !=0 ? ($presupuestos[0]['total_sin_descuento'] - $presupuestos[0]['totalpresupuesto']) / $presupuestos[0]['total_sin_descuento'] * 100 : null),2);

                    }
                    //$buscarPresupuestoPara[]=$unresult->id_cliente;
                }
                else{

                }

                $citas = 0;
                $citas = $this->Agenda_model->leer_proximas_citas_varios_clientes([
                    'id_cliente' => [$unresult->id_cliente],
                    'from' => $unresult->fecha_hora_inicio
                ]);

                if ($citas > 0) {
                    $xcita=$citas[count($citas)-1];
                    $unresult->fecha_cita_posterior = date("Y-m-d",strtotime($xcita['fecha_hora_inicio']));
                    $unresult->hora_cita_posterior = date("H:i:s",strtotime($xcita['fecha_hora_inicio']));
                }

            }
            else{
                $unresult->es_presupuesto_cita=true;
            }
            if($unresult->id_presupuesto){
                $parampagos=[
                    'id_presupuesto'=>$unresult->id_presupuesto,

                ];
                $pagos=$this->Presupuestos_model->leer_pago_presupuesto($parampagos);
                $unresult->presupuestos_pagos=is_array($pagos) ? count($pagos) : 0;

                $citas = 0;
                $citas = $this->Agenda_model->leer_proximas_citas_varios_clientes([
                    'id_cliente' => [$unresult->id_cliente],
                    'from' => date("Y-m-d H:i:s")
                ]);
                if ($citas > 0) {
                    $xcita=$citas[count($citas)-1];
                    $unresult->fecha_proxima_cita = date("Y-m-d",strtotime($xcita['fecha_hora_inicio']));
                    $unresult->hora_proxima_cita = date("H:i:s",strtotime($xcita['fecha_hora_inicio']));
                }

            }



            $xsaldo = $this->Clientes_model->saldo($unresult->id_cliente);

            $unresult->saldo=number_format($xsaldo, 2, ',', '.');
        }


       /* if(count($idsClientes)) {
            $citas = 0;
            $citas = $this->Agenda_model->leer_proximas_citas_varios_clientes([
                'id_cliente' => $idsClientes,
            ]);
            if ($citas > 0) {
                foreach ($citas as $xcita) {
                    foreach ($result->data as $unresult) {
                        if ($xcita['id_cliente'] == $unresult->id_cliente) {
                            $unresult->fecha_proxima_cita = date("Y-m-d",strtotime($xcita['fecha_hora_inicio']));
                            $unresult->hora_proxima_cita = date("H:i:s",strtotime($xcita['fecha_hora_inicio']));
                        }
                    }
                }
            }

            $saldos=$this->Clientes_model->saldo_variosClientes($idsClientes);
            foreach($result->data as $unresult){
                if(isset($saldos[$unresult->id_cliente])){
                    $unresult->pagado=$saldos[$unresult->id_cliente];
                }
            }
        } */

      //  echo "<pre>";var_dump($result);die();
        $res = json_encode($result);
        echo $res;
        die();
    }

// Alfonso: Duetario de control de pagos de transferencia o financiado (ue requieren comprobante)
    function control_pagos($accion = null, $fecha = null,$fecha2 = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        if ($fecha == null) {
            $param['fecha'] = date("Y-m-d");
            $param['fecha2'] = $param['fecha'];
            $param['fecha'] = date("Y-m-d", strtotime("-7 days", strtotime($param['fecha'])));
        } else {
            $param['fecha'] = $fecha;
            $param['fecha2'] = $fecha2;
        }

        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $param['id_centro'] = $id_centro;
            }
        }
        $dietario = $this->Dietario_model->leer($param);

        $data['hoy_aaaammdd'] = $param['fecha'];
        $data['hoy_aaaammdd2'] =$param['fecha2'];

        // ... Aqui pasamos el historial del dietario para que nos lo devuelva con
        // los numero de carnets usados para cada pago
        unset($param);
        $param['historial'] = $dietario;
        $data['dietario'] = $this->Dietario_model->carnets_pago_templos($param);

        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        $data['id_centro'] = $id_centro;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Dietario';
        $data['content_view'] = $this->load->view('dietario/dietario_control_pagos', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
}
/*UPDATE dietario d
JOIN presupuestos_items pi ON d.id_dietario = pi.id_dietario
SET d.id_presupuesto = pi.id_presupuesto,
    d.importe_euros = pi.coste
WHERE pi.id_presupuesto > 0;*/
