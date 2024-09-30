<?php
class Carnets extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... CARNETS
    // ----------------------------------------------------------------------------- //
    function index($accion = null, $id_carnet = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        if ($accion == "borrar") {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $ok = $this->Carnets_model->borrar($param);
        }
        unset($param);
        $param['buscar'] = "";
        $data['carnets'] = 0;
        if (isset($_POST['buscar'])) {
            if (strlen($_POST['buscar']) > 2) {
                $param['buscar'] = $_POST['buscar'];
                $param['mostrar_gastado'] = 1;
                $data['carnets'] = $this->Carnets_model->leer($param);
            }
        }
        $data['buscar'] = $param['buscar'];

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Carnets de Templos';
        if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 0) {
            $data['actionstitle'][]= '<button type="button" class="btn btn-primary text-inverse-primary" onclick="NuevoCarnet();">Nuevo Carnet</button>';
        }
        $data['content_view'] = $this->load->view('carnets/carnets_view', $data, true);
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function asignar_carnet_todos()
    {
        //echo "Paso 1 ";
        unset($param);
        $param['nada'] = "";
        //echo "Paso 2 ";
        //$clientes=$this->Clientes_model->leer_clientes($param);
        $clientes = $this->Clientes_model->leer_clientes_todos($param);
        $cuantos = count($clientes);
        //echo "Cuantos " . $cuantos . "<br>";
        for ($i = 0; $i < $cuantos; $i++) {
            //echo "I: " . $i . " " . $clientes[$i]['id_cliente'] . " " . $clientes[$i]['nombre'] . "<br>";
            $codigo_carnet = "U" . $clientes[$i]['id_cliente'];
            unset($param);
            $param['codigo'] = $codigo_carnet;
            //$existe=$this->Clientes_model->leer_clientes($param);
            $existe = $this->Carnets_model->existe_codigo($param);
            // echo "<br>" . " Valor Existe: " . $existe;
            // ... Si existe pasamos parametros a la vista para que muestre el mensaje.
            if (isset($existe) && is_countable($existe) && count($existe) > 0) {
               // echo "Ya lo tiene " . "<br>";
            } else {

                $id_carnet = "9988" . $clientes[$i]['id_cliente'];
                //$xid_carnet = $existe[0]['id_carnet'];
                //if ($xid_carnet==$id_carnet){ //Por si ese id_carnet ya está asignado a otro carnet que no es el único 06/05/20
                //    $id_carnet="99".$clientes[$i]['id_cliente'];
                //}

                echo "Paso " . $id_carnet . "<br>";
                $id_carnet_int = intval($id_carnet);
                echo "no " . $id_carnet_int;

                unset($param2);
                $param2['id_carnet'] = $id_carnet_int;
                $param2['id_centro'] = 1;
                $param2['codigo'] = $codigo_carnet;
                $param2['id_cliente'] = $clientes[$i]['id_cliente'];

                $guardar_codigo = $this->Carnets_model->guardar_carnet_lotes($param2);
                //echo " guardar ".$guardar_codigo;
            }
            //echo "<br>"." I: ".$i." Codigo ".$codigo_carnet."<br>";
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function detalle($accion = null, $id_carnet = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($accion == "guardar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;
            $parametros['id_carnet'] = $id_carnet;

            if ($this->input->post('activo_online') == "") {
                $parametros['activo_online'] = 0;
            } else {
                $parametros['activo_online'] = $this->input->post('activo_online');
            }

            // ---------------------------------------------------------
            // ... Control de cambio de numero de carnet
            // ---------------------------------------------------------
            $existe = 0;
            if ($this->input->post('codigo_carnet') != "") {
                unset($param);
                $param['id_carnet'] = $id_carnet;
                $carnet_actual = $this->Carnets_model->leer($param);

                // Si el numero de carnet que se especifica es diferente al que ya existe.
                if (trim(strtoupper($carnet_actual[0]['codigo'])) != trim(strtoupper($this->input->post('codigo_carnet')))) {
                    $parametros['nuevo_codigo_carnet'] = trim(strtoupper($this->input->post('codigo_carnet')));

                    // ... Comprobamos si existe el numero de carnet.
                    $param2['codigo'] = $parametros['nuevo_codigo_carnet'];
                    $existe = $this->Carnets_model->existe_codigo($param2);

                    // ... Si existe pasamos parametros a la vista para que muestre el mensaje.
                    if ($existe != 0) {
                        $data['carnet_existe'] = 1;
                        $data['nuevo_codigo_carnet'] = $parametros['nuevo_codigo_carnet'];
                    }
                }
            }
            // ---------------------------------------------------------

            // ... Guardamos si existe es igual 0, es decir si el nuevo numero de carnet
            // no existe o no se eres el master y por tanto no se cambia el numero.
            if ($existe == 0) {
                $data['ok'] = $this->Carnets_model->detalle($parametros);

                // ... Guardamos el cambio de numeracion realizado si se ha realizado.
                if (isset($parametros['nuevo_codigo_carnet'])) {
                    $cambio['id_carnet'] = $id_carnet;
                    $cambio['codigo_nuevo'] = $parametros['nuevo_codigo_carnet'];
                    $cambio['codigo_anterior'] = $carnet_actual[0]['codigo'];
                    $cambio['fecha_creacion'] = date("Y-m-d H:m:s");

                    $this->Carnets_model->cambio_numeracion($cambio);
                }
            }
        }

        unset($param);
        $param['id_carnet'] = $id_carnet;
        $data['carnet'] = $this->Carnets_model->leer($param);

        $data['accion'] = $accion;
        $data['id_carnet'] = $id_carnet;

        if ($data['carnet'][0]['id_tipo'] == 99) {
            unset($param);
            $param['id_carnet'] = $id_carnet;

            if ($this->session->userdata('id_perfil') > 0) {
                $data['carnets_servicios'] = $this->Carnets_model->leer_carnets_servicios($param);
            } else {
                $data['carnets_servicios'] = $this->Carnets_model->leer_carnets_servicios_logs($param);
            }
        } else {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_ajustes'] = $this->Carnets_model->leer_carnets_ajustes($param);

            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_historial'] = $this->Carnets_model->leer_carnets_historial($param);
        }

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Detalle del Carnet';
        $data['content_view'] = $this->load->view('carnets/carnets_detalle_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    function historial_csv($id_carnet = null, $fecha_desde = null, $fecha_hasta = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($id_carnet != null) {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $carnet = $this->Carnets_model->leer($param);


            unset($param);
            $param['id_carnet'] = $id_carnet;
            if ($fecha_desde != null and $fecha_hasta != null) {
                $param['fecha_desde'] = $fecha_desde;
                $param['fecha_hasta'] = $fecha_hasta;
            }
            $registros = $this->Carnets_model->leer_carnets_historial($param);
            $carnets_ajustes = $this->Carnets_model->leer_carnets_ajustes($param);


            $fichero = RUTA_SERVIDOR . "/recursos/historialCarnet.csv";

            $file = fopen($fichero, "w");

            $linea = "Historial Carnet " . $carnet[0]['codigo'] . "\n";
            fwrite($file, $linea);
            unset($linea);
            $linea = "Fecha;Cliente;Servicio;Templos;Tipo;Empleado;Centro\n";
            fwrite($file, $linea);

            //Para Ajustes
            if ($carnets_ajustes > 0) {
                //$i=0;
                foreach (array_reverse($carnets_ajustes) as $row) {
                    unset($linea);
                    $saldo = round($row['templos_disponibles'] - $row['templos_disponibles_anteriores'], 2);


                    $linea = $row['fecha_aaaammdd'] . ";" . $carnet[0]['cliente'] . ";" . "-" . ";" . $saldo . ";" . "recarga" . ";" . $row['empleado'] . ";" . $row['nombre_centro'] . "\n";
                    $linea = iconv("UTF-8", "Windows-1252", $linea);

                    fwrite($file, $linea);
                }
            }



            //Para Historial
            if ($registros > 0) {
                //$i=0;
                foreach (array_reverse($registros) as $row) {
                    unset($linea);
                    $tipo = "";
                    if ($row['templos'] > 0) {
                        $tipo = "gastado";
                    } else {
                        $tipo = "devuelto";
                    }

                    $linea = $row['fecha_concepto_ddmmaaaa'] . ";" . $row['cliente'] . ";" . $row['nombre_servicio'] . " (" . $row['duracion'] . " min)" . ";" . round($row['templos'], 2) . ";" . $tipo . ";" . $row['empleado'] . ";" . $row['nombre_centro'] . "\n";
                    $linea = iconv("UTF-8", "Windows-1252", $linea);

                    fwrite($file, $linea);
                }
            }


            fclose($file);

            if (file_exists($fichero)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($fichero) . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fichero));
                readfile($fichero);
            }

            exit;
        } //If !null
        else
            return false;
    }
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function gestion($accion = null, $id_carnet = null, $id_carnet_servicio = null, $id_cliente_venta = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // --------------------------------------------------------------------------
        // ... Guardar carnet
        // --------------------------------------------------------------------------
        if ($accion == "guardar" || $accion == "guardar_venta") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if (isset($parametros)) {
                // Si es una venta desde el dietario, inicialmente el carnet se crea
                // pero marcado como borrado a 1
                // cuando se marque como pagado, ya se le pone borrado 0.
                if ($accion == "guardar_venta") {
                    $parametros['borrado'] = 1;
                }
                if ($accion == "guardar") {
                    $parametros['sin_pasar_caja'] = 1;
                }
                $id_carnet_nuevo = $this->Carnets_model->guardar($parametros);

                // ... El codigo de carnet ya existe.
                if ($id_carnet_nuevo == -1) {
                    if (isset($parametros['numero_carnet'])) {
                        $num_cod = $parametros['numero_carnet'];
                    } else {
                        $num_cod = "";
                    }
                    echo "
                        <div class='alert alert-danger display-hide' style='margin-top: 30px; display: block; text-align: center; font-size: 14px; font-family: verdana; color: red;'>
                        El codigo " . $num_cod . " de carnet está duplicado
                        <br><br>
                        <a href='#' onclick='history.back();'>volver atrás</a>
                        </div>";
                    exit;
                }

                if ($accion == "guardar_venta" && $id_carnet_nuevo > 0) {
                    $parametros['servicios_carnet'] = "";
                    $parametros['id_carnet'] = $id_carnet_nuevo;
                    $ok = $this->Dietario_model->nuevo_carnet($parametros);
                }
            }
        }

        // ---------------------------------------
        if ($accion == "nuevo" || $accion == "nueva_venta") {
            if ($id_carnet == -99) {
                unset($parametros);
                $parametros['nombre'] = $_POST['nombre'];
                $parametros['apellidos'] = $_POST['apellidos'];
                $parametros['telefono'] = $_POST['telefono'];

                $data['id_cliente_nuevo_creado'] = $this->Clientes_model->nuevo_cliente($parametros);

                //17/02/21 Carnet Único
                $xcodigo_carnet = "U" . $data['id_cliente_nuevo_creado'];
                $xid_carnet = "9988" . $data['id_cliente_nuevo_creado'];
                $xid_carnet = intval($xid_carnet);

                unset($param2);
                $param2['id_carnet'] = $xid_carnet;
                $param2['codigo'] = $xcodigo_carnet;
                $param2['id_cliente'] = $data['id_cliente_nuevo_creado'];

                $guardar_codigo = $this->Carnets_model->guardar_carnet_unico($param2);

                //Fin

            }
        }
        // ---------------------------------------

        if ($accion == "modificar_carnet") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            $param['precio'] = $parametros['precio'];
            $param['notas_adicionales'] = $parametros['notas'];
            $param['id_carnet'] = $id_carnet;

            $ok = $this->Carnets_model->actualizar_precio_especial($param);
        }

        // ... Servicios Familia y Tipos de Carnets
        unset($param);
        $param['vacio'] = "";
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);
        $data['tipos_carnets'] = $this->Carnets_model->tipos($param);
        //$data['clientes']=$this->Clientes_model->leer_clientes($param);

        if ($id_carnet != null && $id_carnet > 0) {
            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnet'] = $this->Carnets_model->leer($param);

            unset($param);
            $param['id_carnet'] = $id_carnet;
            $data['carnets_servicios'] = $this->Carnets_model->leer_carnets_servicios($param);

            $data['id_tipo'] = 99;
            $id_cliente_venta = $data['carnet'][0]['id_cliente'];
        }

        $data['accion'] = $accion;
        $data['id_carnet'] = $id_carnet;

        if ($id_cliente_venta != null) {
            $data['id_cliente'] = $id_cliente_venta;
        } else {
            $data['id_cliente'] = 0;
        }

        if ($accion == "nuevo" || $accion == "nueva_venta") {
            if ($id_carnet == -99) {
                $data['id_cliente'] = $data['id_cliente_nuevo_creado'];
            }
        }

        unset($param5);
        $param5['id_cliente'] = $data['id_cliente'];
        $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            if ($accion == "recarga_unico") //17/04/20
                $this->load->view('carnets/carnets_recarga_unico_view', $data);
            else
                $this->load->view('carnets/carnets_nuevo_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function modificar_especial($accion = null, $id_carnet = null, $id_carnet_servicio = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos los datos del carnet elegido
        unset($param);
        $param['id_carnet'] = $id_carnet;
        $data['carnet'] = $this->Carnets_model->leer($param);

        // ... A�adimos el servicio indicado del carnet actual
        if ($accion == "anadir") {
            if ($id_carnet_servicio > 0) {
                unset($param);
                $param['id_servicio'] = $id_carnet_servicio;
                $servicio = $this->Servicios_model->leer_servicios($param);

                unset($param);
                $param['id_carnet'] = $id_carnet;
                $param['id_servicio'] = $servicio[0]['id_servicio'];
                $param['id_cliente'] = $data['carnet'][0]['id_cliente'];
                $param['id_centro'] = $this->session->userdata('id_centro_usuario');
                $param['gastado'] = 0;
                $param['pvp'] = $servicio[0]['pvp'];
                $param['sin_pasar_por_caja'] = 1;

                $this->Carnets_model->anadir_servicio($param);
            }
        }

        // ... Borramos el servicio indicado del carnet actual
        if ($accion == "quitar") {
            unset($param);
            $param['id'] = $id_carnet_servicio;
            $this->Carnets_model->borrar_servicio($param);
        }

        // ... Leemos los servicios del carnet elegido
        unset($param);
        $param['id_carnet'] = $id_carnet;
        $data['carnets_servicios'] = $this->Carnets_model->leer_carnets_servicios($param);

        // ... Servicios disponibles
        unset($param);
        $param['vacio'] = "";
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view('carnets/carnets_editar_especial_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function reasignar($accion = null, $id_carnet = null)
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

        unset($param5);
        $param5['id_cliente'] = $data['carnet'][0]['id_cliente'];
        $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

        if ($accion == "guardar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;
            $parametros['id_carnet'] = $id_carnet;

            $ok = $this->Carnets_model->reasignar($parametros);
        }

        $data['accion'] = $accion;
        $data['id_carnet'] = $id_carnet;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view('carnets/carnets_reasignar_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function ajustes($accion = null, $id_carnet = null)
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

        if ($accion == "guardar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;
            $parametros['id_carnet'] = $id_carnet;

            $ok = $this->Carnets_model->ajustes_templos($parametros);
        }

        $data['accion'] = $accion;
        $data['id_carnet'] = $id_carnet;

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view('carnets/carnets_ajustes_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ... Recargar un carnet elegido con el n�mero de templos indicado.
    function recargar($accion = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['hoy_aaaammdd'] = date("Y-m-d");
        $data['accion'] = $accion;

        if ($accion == "realizar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            $data['id_carnet'] = $parametros['id_carnet'];
            $data['templos_recarga'] = $parametros['templos_recarga'];

            unset($param);
            $param['id_carnet'] = $parametros['id_carnet'];
            $carnet_elegido = $this->Carnets_model->leer($param);

            $data['id_cliente'] = $carnet_elegido[0]['id_cliente'];
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view('carnets/carnets_recargar_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //17/04/20
    function recargar_unico($accion = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['hoy_aaaammdd'] = date("Y-m-d");
        $data['accion'] = $accion;
        $data['id_cliente'] = $id_cliente; //07/05/20

        if ($accion == "realizar") {
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            //$data['id_carnet']=$parametros['id_carnet']; //Quitar 06/05/20
            unset($param);
            $param['id_tipo'] = $parametros['id_tipo'];
            $id_tipo = $this->Carnets_model->tipos($param);
            $data['templos_recarga'] = $id_tipo[0]['templos'];
            $data['precio'] = $id_tipo[0]['precio'];

            //29/06/20 Para saber si la recarga es solo para pagar en efectivo
            $data['solo_pago'] = $parametros['solo_pago'];
            //Fin 

            unset($param);
            //$param['id_carnet']=$parametros['id_carnet']; //Quitar 06/05/20
            $param['bcodigo'] = $parametros['numero_carnet']; //06/05/20 //20/05/20 'bcodigo' Para diferenciar la busqueda de otro codigo que ya estaba en el Modelo.
            $carnet_elegido = $this->Carnets_model->leer($param);

            $data['id_cliente'] = $carnet_elegido[0]['id_cliente'];
            $data['id_carnet'] = $carnet_elegido[0]['id_carnet']; //06/05/20
        } else {  //07/05/20 Esto por el Video del 06/06/20  

            // ... Servicios Familia y Tipos de Carnets
            unset($param);
            $param['vacio'] = "";
            $data['servicios'] = $this->Servicios_model->leer_servicios($param);
            $data['tipos_carnets'] = $this->Carnets_model->tipos($param);

            unset($param5);
            if ($id_cliente != null)
                $param5['id_cliente'] = $data['id_cliente'];
            else
                $param5['id_cliente'] = 0;
            //$param5['vacio']="";
            //echo "paso 1";
            $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
            //echo "paso 2";    
        } //Else no es acción REALIZAR

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);


        if ($id_cliente != null) {
            unset($parametros);
            $parametros['id_cliente'] = $id_cliente;
            $data['cliente'] = $this->Clientes_model->leer_clientes($parametros);
        } else {
            $data['cliente'] = 0;
        }


        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view('carnets/carnets_recarga_unico_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //Fin


    function json($q = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['q'] = $_GET["q"];
        $carnets = $this->Carnets_model->carnets_json($parametros);

        $json_response = json_encode($carnets);

        echo $json_response;

        exit;
    }

    function json_todos($q = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['q'] = $_GET["q"];
        $carnets = $this->Carnets_model->carnets_json_todos($parametros);

        $json_response = json_encode($carnets);

        echo $json_response;

        exit;
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function calculadora()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['accion'] = "";
        $data['carnets_servicios'] = 0;

        // ... Servicios Familia.
        unset($param);
        $param['vacio'] = "";
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);

        // ... Tipos de Carnets Existentes.
        //unset($param);
        //$param['vacio']="";
        //$data['tipos']=$this->Carnets_model->tipos($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Calculadora de Templos';
        $data['content_view'] = $this->load->view('carnets/carnets_calculadora_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 13);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    // ----------------------------------------------------------------------------- //

    function carnet_disponibilidad($id_carnet = null)
    {
        unset($param);
        $param['id_carnet'] = $id_carnet;
        $data['carnet_disponible'] = $this->Carnets_model->leer_un_carnet($param);
        $disponible = $data['carnet_disponible'][0]['templos_disponibles'];
        return $disponible;
    }
}
