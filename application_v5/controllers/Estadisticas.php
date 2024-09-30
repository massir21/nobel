<?php
class Estadisticas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... ESTADISTICAS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $id_centro = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            $id_centro = 6;
        } else {
            $id_centro = $this->session->userdata('id_centro_usuario');
        }

        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        unset($parametros);
        $parametros['vacio'] = "";
        if ($fecha_desde != "") {
            $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";;
        }
        if ($id_centro > 0) {
            $parametros['id_centro'] = $id_centro;
        }

        $data['registros'] = $this->Estadisticas_model->usuarios($parametros);

        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_usuarios_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 9);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Generales
    // ----------------------------------------------------------------------------- //
    function gestion($accion = null, $id_usuario_empleado = null, $id_centro = null, $fecha_desde = null, $fecha_hasta = null)
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        $data['id_centro_uusuario_estadistica'] = $id_centro;

        // ... Lemos parametros.
        //unset($parametros);
        //if (!empty($_POST)) {$parametros = $_POST;}

        unset($parametros);
        $parametros['id_usuario'] = $id_usuario_empleado;
        $data['usuario'] = $this->Usuarios_model->leer_usuarios($parametros);
        $data['pagetitle']="Estadísticas";
        // ... Viewer con el contenido
        if ($accion == "horastrabajadas") {
            unset($param);
            $param['id_usuario_empleado'] = $id_usuario_empleado;
            $param['id_centro'] = $id_centro;
            $data['registros'] = $this->Estadisticas_model->horas_trabajadas($param);

            $data['content_view'] = $this->load->view('estadisticas/estadisticas_horastrabajadas_view', $data, true);
        }
        if ($accion == "templos") {
            unset($param);
            $param['id_usuario_empleado'] = $id_usuario_empleado;
            $param['id_centro'] = $id_centro;
            $data['registros'] = $this->Estadisticas_model->templos($param);
            //$data['registros'] = $this->Estadisticas_model->templos_devueltos($param);

            $data['content_view'] = $this->load->view('estadisticas/estadisticas_templos_view', $data, true);
        }
        if ($accion == "ventasproductos") {
            unset($param);
            $param['id_usuario_empleado'] = $id_usuario_empleado;
            $param['id_centro'] = $id_centro;
            if ($fecha_desde != null) {
                $param['fecha_desde'] = $fecha_desde;
            }
            if ($fecha_hasta != null) {
                $param['fecha_hasta'] = $fecha_hasta;
            }
            $data['registros'] = $this->Estadisticas_model->productos($param);

            $data['content_view'] = $this->load->view('estadisticas/estadisticas_ventasproductos_view', $data, true);
        }
        if ($accion == "ventas") {
            unset($param);
            $param['id_usuario_empleado'] = $id_usuario_empleado;
            $param['id_centro'] = $id_centro;
            $data['registros'] = $this->Estadisticas_model->ventas($param);

            $data['content_view'] = $this->load->view('estadisticas/estadisticas_ventas_view', $data, true);
        }
        if ($accion == "ventas_proveedores") {
            unset($param);
            $param['id_usuario_empleado'] = $id_usuario_empleado;
            $param['id_centro'] = $id_centro;
            $data['registros'] = $this->Estadisticas_model->ventas_proveedores($param);

            $data['content_view'] = $this->load->view('estadisticas/estadisticas_ventas_proveedores_view', $data, true);
        }


        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 9);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... LOG: Logins.
    // ----------------------------------------------------------------------------- //
    function loglogins()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $id_usuario = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        // ... Leemos los parametros externos.
        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Fechas Elegidas
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-1 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        // ... Empleado elegido
        if (isset($parametros['id_usuario'])) {
            $id_usuario = $parametros['id_usuario'];
        }

        // ... Leemos los datos del log
        unset($parametros);
        if ($fecha_desde != "") {
            $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        }
        $parametros['id_usuario'] = $id_usuario;
        $data['registros'] = $this->Estadisticas_model->loglogins($parametros);

        // ... Leemos todos los empleados del centro.
        unset($parametros);
        $parametros['todos_empleados'] = 1;
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['id_usuario'] = $id_usuario;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Inicios y cierres de sesión';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_loglogins_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 25);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    function historicocitas()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $id_cliente = 0;
        $estado = "";
        $fecha_desde = "";
        $fecha_hasta = "";
        $data['registros'] = 0;

        // ... Leemos los parametros externos.
        unset($parametros);
        /*while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }*/
        $parametros = $_POST;

        // ... Cliente elegido
        if (isset($parametros['id_cliente'])) {
            $id_cliente = $parametros['id_cliente'];

            unset($param5);
            $param5['id_cliente'] = $parametros['id_cliente'];
            $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
        }

        // ... Fechas Elegidas
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        }
        // ... Estado
        if (isset($parametros['estado'])) {
            $estado = $parametros['estado'];
        }

        // ... Leemos los datos del log
        if (isset($parametros['fecha_desde'])) {
            unset($parametros);
            if ($fecha_desde != "") {
                $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
            }
            if ($fecha_hasta != "") {
                $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
            }
            $parametros['id_cliente'] = $id_cliente;
            if ($estado != "") {
                $parametros['estado'] = $estado;
            }

            $parametros['solo_servicios'] = 1;
            $data['registros'] = $this->Dietario_model->leer($parametros);
        }

        // ... Leemos todos los clientes
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['id_cliente'] = $id_cliente;
        $data['estado'] = $estado;
        $data['desplegable_clientes'] = 1;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Historico Citas';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_historicocitas_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 26);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    // ----------------------------------------------------------------------------- //
    // ... Carnets sin pasar por caja
    // ----------------------------------------------------------------------------- //
    function carnetsinpasarcaja()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $id_usuario = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        // ... Leemos los parametros externos.
        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Fechas Elegidas
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-1 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        // ... Empleado elegido
        if (isset($parametros['id_usuario'])) {
            $id_usuario = $parametros['id_usuario'];
        }

        // ... Leemos los datos del log
        unset($param);
        $param['id_empleado'] = $id_usuario;
        if ($fecha_desde != "") {
            $param['fecha_desde'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $param['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        }
        $param['sin_pasar_caja'] = 1;
        $data['registros'] = $this->Carnets_model->leer($param);

        // ... Leemos todos los empleados del centro.
        unset($parametros);
        $parametros['todos_empleados'] = 1;
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['id_usuario'] = $id_usuario;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Carnets sin pasar por caja';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_carnetsinpasarcaja_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 27);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Por clientes
    // ----------------------------------------------------------------------------- //
    function clientes($accion = null)
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos los centros
        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        // ... Leemos todos los empleados del centro.
        unset($parametros);
        $parametros['todos_empleados'] = 1;
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');;
        }
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos los servicios y familias
        unset($parametros);
        $parametros['vacio'] = "";
        $data['servicios'] = $this->Servicios_model->leer_servicios($parametros);

        unset($parametros);
        $parametros['vacio'] = "";
        $data['servicios_familias'] = $this->Servicios_model->leer_familias_servicios($parametros);

        unset($param);
        $param['id_servicio'] = "";
        $param['form'] = "form_estadisticas_clientes";
        $data['script_servicios'] = $this->Servicios_model->javacript_familias_servicios($param);

        // ... Leemos los productos
        unset($parametros);
        $parametros['vacio'] = "";
        $data['productos'] = $this->Productos_model->leer_productos($parametros);

        // ... Leemos los productos
        unset($parametros);
        $parametros['vacio'] = "";
        $data['tipos_carnets'] = $this->Carnets_model->tipos($parametros);

        //
        // ... RESULTADOS
        //
        if ($accion == "buscar") {
            unset($parametros);
            if (!empty($_POST)) {
                $parametros = $_POST;
            }

            if ($parametros['acudido_centro_periodo'] == "0") {
                $parametros['acudido_centro_periodo'] = "Cualquier período";
            }
            if ($parametros['atendido_periodo'] == "0") {
                $parametros['atendido_periodo'] = "Cualquier período";
            }
            if ($parametros['atendido_solo_periodo'] == "0") {
                $parametros['atendido_solo_periodo'] = "Cualquier período";
            }
            if ($parametros['hecho_servicio_periodo'] == "0") {
                $parametros['hecho_servicio_periodo'] = "Cualquier período";
            }
            if ($parametros['comprado_producto_periodo'] == "0") {
                $parametros['comprado_producto_periodo'] = "Cualquier período";
            }
            if ($parametros['comprado_carnet_periodo'] == "0") {
                $parametros['comprado_carnet_periodo'] = "Cualquier período";
            }
            $parametros['ultimo_centro'] = 1;
            $data['registros'] = $this->Clientes_model->leer_clientes($parametros);
            $filtros = "";
            // ... Items de filtros elegidos.
            if (isset($parametros['fecha_desde_creacion']) && isset($parametros['fecha_hasta_creacion'])) {
                if ($parametros['fecha_desde_creacion'] != "" && $parametros['fecha_hasta_creacion'] != "") {
                    $filtros .= "- Creado entre el " . $parametros['fecha_desde_creacion'] . " hasta " . $parametros['fecha_hasta_creacion'] . "<br>";
                }
            }

            if (isset($parametros['que_venga_condicion']) && isset($parametros['que_venga_veces']) && isset($parametros['que_venga_periodo'])) {
                if ($parametros['que_venga_condicion'] != "0" && $parametros['que_venga_veces'] > 0) {
                    if ($parametros['que_venga_condicion'] == 0) {
                        $filtros .= "- Que venga " . $parametros['que_venga_condicion'] . " de " . $parametros['que_venga_veces'] . " veces  / Cualquier período <br>";
                    } else {
                        $filtros .= "- Que venga " . $parametros['que_venga_condicion'] . " de " . $parametros['que_venga_veces'] . " veces  / " . $parametros['que_venga_periodo'] . "<br>";
                    }
                }
            }

            if (isset($parametros['fecha_desde_ultima_visita']) && isset($parametros['fecha_hasta_ultima_visita'])) {
                if ($parametros['fecha_desde_ultima_visita'] != "" && $parametros['fecha_hasta_ultima_visita'] != "") {
                    $filtros .= "- Última vistita entre " . $parametros['fecha_desde_ultima_visita'] . " hasta " . $parametros['fecha_hasta_ultima_visita'] . "<br>";
                }
            }

            if (isset($parametros['consumo_periodo']) && isset($parametros['consumo_condicion']) && isset($parametros['consumo_importe'])) {
                if ($parametros['consumo_periodo'] != "0" && $parametros['consumo_importe'] > 0 && $parametros['consumo_condicion'] != "0") {
                    $filtros .= "- Consumo " . $parametros['consumo_periodo'] . " sea " . $parametros['consumo_condicion'] . " a " . $parametros['consumo_importe'] . " €<br>";
                }
            }

            if (isset($parametros['acudido_centro']) && isset($parametros['acudido_centro_periodo'])) {
                if ($parametros['acudido_centro'] != "0" && $parametros['acudido_centro_periodo'] != "0") {
                    unset($param);
                    $param['id_centro'] = $parametros['acudido_centro'];
                    $centro = $this->Usuarios_model->leer_centros($param);
                    $filtros .= "- Que haya acudido a " . $centro[0]['nombre_centro'] . " / " . $parametros['acudido_centro_periodo'] . "<br>";
                }
            }

            if (isset($parametros['atendido_empleado']) && isset($parametros['atendido_periodo'])) {
                if ($parametros['atendido_empleado'] != "0" && $parametros['atendido_periodo'] != "0") {
                    unset($param);
                    $param['id_usuario'] = $parametros['atendido_empleado'];
                    $datos = $this->Usuarios_model->leer_usuarios($param);

                    $filtros .= "- Que haya sido atendido por " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " / " . $parametros['atendido_periodo'] . "<br>";
                }
            }

            if (isset($parametros['atendido_solo_empleado']) && isset($parametros['atendido_solo_periodo'])) {
                if ($parametros['atendido_solo_empleado'] != "0" && $parametros['atendido_solo_periodo'] != "0") {
                    unset($param);
                    $param['id_usuario'] = $parametros['atendido_solo_empleado'];
                    $datos = $this->Usuarios_model->leer_usuarios($param);

                    $filtros .= "- Pide solo con " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " / " . $parametros['atendido_solo_periodo'] . "<br>";
                }
            }

            if (isset($parametros['id_familia_servicio']) && isset($parametros['hecho_servicio_periodo'])) {
                if ($parametros['id_familia_servicio'] != "0" && $parametros['hecho_servicio_periodo'] != "0") {
                    unset($param);
                    $param['id_familia_servicio'] = $parametros['id_familia_servicio'];
                    $familia = $this->Servicios_model->leer_familias_servicios($param);

                    if ($parametros['id_servicio'] != "0") {
                        unset($param);
                        $param['id_servicio'] = $parametros['id_servicio'];
                        $servicio = $this->Servicios_model->leer_servicios($param);
                    } else {
                        $servicio[0]['nombre_servicio'] = "";
                    }

                    $filtros .= "- Que haya hecho " . $familia[0]['nombre_familia'] . " " . $servicio[0]['nombre_servicio'] . " / " . $parametros['hecho_servicio_periodo'] . "<br>";
                }
            }

            if (isset($parametros['comprado_producto']) && isset($parametros['comprado_producto_periodo'])) {
                if ($parametros['comprado_producto'] != "0" && $parametros['comprado_producto_periodo'] != "0") {
                    unset($param);
                    $param['id_producto'] = $parametros['comprado_producto'];
                    $producto = $this->Productos_model->leer_productos($param);

                    $filtros .= "- Que haya comprado " . $producto[0]['nombre_producto'] . " / " . $parametros['comprado_producto_periodo'] . "<br>";
                }
            }

            if (isset($parametros['comprado_carnet']) && isset($parametros['comprado_carnet_periodo'])) {
                if ($parametros['comprado_carnet'] != "0" && $parametros['comprado_carnet_periodo'] != "0") {
                    unset($param);
                    $param['id_tipo'] = $parametros['comprado_carnet'];
                    $carnet = $this->Carnets_model->tipos($param);

                    $filtros .= "- Que haya comprado un carnet " . $carnet[0]['descripcion'] . " / " . $parametros['comprado_carnet_periodo'] . "<br>";
                }
            }

            if (isset($parametros['que_repita_condicion']) && isset($parametros['que_repita_veces']) && isset($parametros['que_repita_empleado'])) {
                if ($parametros['que_repita_condicion'] != "0" && $parametros['que_repita_veces'] > 0 && $parametros['que_repita_empleado'] != "0") {
                    unset($param);
                    $param['id_usuario'] = $parametros['que_repita_empleado'];
                    $empleado = $this->Usuarios_model->leer_usuarios($param);

                    $filtros .= "- Que repita " . $parametros['que_repita_condicion'] . " de " . $parametros['que_repita_veces'] . " con " . $empleado[0]['nombre'] . " " . $empleado[0]['apellidos'] . "<br>";
                }
            }

            if (isset($parametros['que_haya_anulado_condicion'])) {
                if ($parametros['que_haya_anulado_condicion'] != "0") {
                    if ($parametros['que_haya_anulado_periodo'] == "0") {
                        $parametros['que_haya_anulado_periodo'] = "Cualquier";
                    }
                    $filtros .= "- Que haya Anulado Cita Más" . $parametros['que_haya_anulado_condicion'] . " de " . $parametros['que_haya_anulado_veces'] . " veces  / " . $parametros['que_haya_anulado_periodo'] . "<br>";
                }
            }

            if (isset($parametros['que_no_vino_condicion']) && isset($parametros['que_no_vino_veces'])) {
                if ($parametros['que_no_vino_condicion'] != "0" && $parametros['que_no_vino_veces'] > 0) {
                    if ($parametros['que_no_vino_periodo'] == "0") {
                        $parametros['que_no_vino_periodo'] = "Cualquier";
                    }
                    $filtros .= "- Que haya No Vino " . $parametros['que_no_vino_condicion'] . " de " . $parametros['que_no_vino_veces'] . " veces / " . $parametros['que_no_vino_periodo'] . "<br>";
                }
            }

            if ($parametros['que_acuda_centros'] > 0) {
                $filtros .= "- Que acuda a más de " . $parametros['que_acuda_centros'] . " centros distintos <br>";
            }

            if (isset($parametros['rentabilidad_condicion']) && isset($parametros['rentabilidad'])) {
                if ($parametros['rentabilidad_condicion'] != "0" && $parametros['rentabilidad'] != "") {
                    $filtros .= "- Cuya rentabilidad sea " . $parametros['rentabilidad_condicion'] . " de " . $parametros['rentabilidad'] . "% *(Facturacion Servicios + Productos) - Citas que no vino / Visitas totales<br>";
                }
            }

            $filtros = str_replace("Anno", "Año", $filtros);
            $filtros = str_replace("_", " ", $filtros);

            $data['filtros'] = $filtros;

            // ... Exportacion a CSV
            $fichero = RUTA_SERVIDOR . "/recursos/estadistica_clientes_" . $this->session->userdata('id_usuario') . ".csv";
            $file = fopen($fichero, "w");

            $filtros_csv = trim($filtros);
            $filtros_csv = str_replace("<br>", "\n", $filtros);
            $filtros_csv = str_replace("-", " ", $filtros_csv);

            $filtros_csv = iconv("UTF-8", "Windows-1252", $filtros_csv);
            fwrite($file, $filtros_csv . "\n\n");

            $linea = "fecha_alta;nombre;apellidos;telefono;ultimo_centro_visitado;ultimo_empleado_atendio;ultima_recepcionista_atendio\n";
            fwrite($file, $linea);

            if ($data['registros'] > 0) {
                foreach ($data['registros'] as $row) {
                    unset($linea);
                    $linea = $row['fecha_creacion_ddmmaaaa'] . ";" . $row['nombre'] . ";" . $row['apellidos'] . ";" . $row['telefono'] . ";" . $row['ultimo_centro'] . ";" . $row['ultimo_empleado'] . ";" . $row['ultima_recepcionista'] . "\n";
                    $linea = iconv("UTF-8", "Windows-1252", $linea);
                    fwrite($file, $linea);
                }
            }

            fclose($file);
        }

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas de Clientes';
        if(isset($data['registros'])){
            $data['actionstitle'] = ['<a href="'.base_url().'recursos/estadistica_clientes_'.$this->session->userdata('id_usuario').'.csv" class="btn btn-warning text-inverse-warning">Exportar csv</a>'];
        }
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_clientes_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 29);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ... Por carnets
    // ----------------------------------------------------------------------------- //
    function carnets_desglose($id_centro = null, $id_tipo = null)
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['id_centro'] = $id_centro;
        $parametros['id_tipo'] = $id_tipo;
        $data['datos'] = $this->Estadisticas_model->carnets_sin_usar_tipo_centro($parametros);

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas de Carnets - Desglose de Carnets Sin Usa';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_carnets_desglose_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 30);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    function generales_dias()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $id_centro = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            $id_centro = 6;
        } else {
            $id_centro = $this->session->userdata('id_centro_usuario');
        }

        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        unset($parametros);
        $parametros['vacio'] = "";
        if ($fecha_desde != "") {
            $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";;
        }
        if ($id_centro > 0) {
            $parametros['id_centro'] = $id_centro;
        }

        $data['registros'] = $this->Estadisticas_model->general_dias($parametros);

        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas Generales por días';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_general_dias_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 33);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    //
    // Ventas Online con tarjeta.
    //
    function ventas_online()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Filtros.
        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        } else {
            $id_centro = 0;
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        // ... Ventas Online.
        $parametros['citas_online'] = 1;
        $parametros['estado'] = 'Pagado';
        if (isset($parametros['tipo_pago'])) {
            if ($parametros['tipo_pago'] != "") {
                $parametros['tipo_pago'] = $parametros['tipo_pago'];
            } else {
                unset($parametros['tipo_pago']);
            }
        }
        $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        $data['ventas_online'] = $this->Dietario_model->leer($parametros);

        // ... Numero de clientes registrados
        $data['numero_clientes_registrados'] = $this->Estadisticas_model->numero_clientes_registrados();

        // ... Numero de clientes verificados
        $data['numero_clientes_verificados'] = $this->Estadisticas_model->numero_clientes_verificados();

        // ... Numero de clientes que han codigo citas online
        $data['numero_clientes_con_citas_online'] = $this->Estadisticas_model->numero_clientes_con_citas_online();

        //
        //
        //
        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas Citas Online';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_online_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 42);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // Códigos tienda online
    //
    function codigo_tienda_online()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Filtros.
        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        } else {
            $id_centro = 0;
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        // ... Código tienda online
        $parametros['id_centro'] = $id_centro;
        $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        $data['ventas_online'] = $this->Estadisticas_model->codigos_tienda_online($parametros);

        //
        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas Códigos Tienda Online';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_codigos_tienda_online_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 47);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    /**
     *
     * Se comparar dos array de las estadisticas de centrod
     *
     * @param array arrayA
     * @param array arrayB
     * @param string nombre del campo descriptivo
     * @param string nombre del campo valor
     *
     * @return array con los datos comparados
     * 
     */
    private function comparador_centros($arrayA, $arrayB, $nombre, $valor)
    {
        //foreach ($arrayA as $row)
        //{
        //  echo $row[$nombre]." ---> ".$row[$valor]."<br>";
        //}
        //exit;

        if ($arrayA != 0) {
            // Array para almacenar los datos comparados
            $comparador = array();

            // indica para controlar el array comparador
            $idx = 0;

            // Primero pasamos el bucle por el arrayA para ver coincidencias con el arrayB
            for ($i = 0; $i < count($arrayA); $i++) {
                $sw = 0;

                for ($x = 0; $x < count($arrayB); $x++) {
                    if ($arrayA[$i][$nombre] == $arrayB[$x][$nombre]) {
                        $comparador[$idx]['nombre'] = $arrayA[$i][$nombre];
                        $comparador[$idx]['cantidad_1'] = $arrayA[$i][$valor];
                        $comparador[$idx]['cantidad_2'] = $arrayB[$x][$valor];
                        $idx++;

                        $sw = 1;
                    }
                }

                if ($sw == 0) {
                    $comparador[$idx]['nombre'] = $arrayA[$i][$nombre];
                    $comparador[$idx]['cantidad_1'] = $arrayA[$i][$valor];
                    $comparador[$idx]['cantidad_2'] = 0;
                    $idx++;
                }
            }

            // Por ultimo pasamos bucle al array comparador contra arrayB
            // para ver si hay valores de arrayB que no estuvieran en arrayA.
            if ($arrayB != 0) {
                for ($i = 0; $i < count($arrayB); $i++) {
                    $sw = 0;

                    for ($x = 0; $x < count($comparador); $x++) {
                        if ($arrayB[$i][$nombre] == $comparador[$x]['nombre']) {
                            $sw = 1;
                        }
                    }

                    if ($sw == 0) {
                        $comparador[$idx]['nombre'] = $arrayB[$i][$nombre];
                        $comparador[$idx]['cantidad_1'] = 0;
                        $comparador[$idx]['cantidad_2'] = $arrayB[$i][$valor];
                        $idx++;
                    }
                }
            }

            return $comparador;
        } else {
            return 0;
        }
    }

    //
    // Envio de SMS
    //
    function envio_sms()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Filtros.    
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }

        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        $data['sms_enviados'] = $this->Estadisticas_model->sms_enviados($parametros);

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas SMS Enviados';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_sms_enviados_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 51);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // Pagos por TPV
    //
    function tpv()
    {
        // ... Comprobamos la sesion del horario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        if (!empty($_POST)) {
            $parametros = $_POST;
        }

        // ... Filtros.    
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-7 day', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }

        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fecha_hasta = date("Y-m-d");
        }

        $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        $data['pagos_tpv'] = $this->Estadisticas_model->pagos_tpv($parametros);
        $data['pagos_tpv_online'] = $this->Estadisticas_model->pagos_tpv_online($parametros);
        $data['pagos_tpv_bonos'] = $this->Estadisticas_model->pagos_tpv_bonos($parametros);

        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Estadísticas Pago por TPV';
        $data['content_view'] = $this->load->view('estadisticas/estadisticas_tpv_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 51);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
}
