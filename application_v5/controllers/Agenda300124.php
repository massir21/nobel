<?php

class Agenda extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... AGENDA
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function index($fecha = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        if ($fecha != null) {

            $date = new DateTime($fecha);
            $fecha_ddmmaaaa = date_format($date, 'd-m-Y');
            $fecha_aaaammdd = date_format($date, 'Y-m-d');

            /*if ($fecha_aaaammdd < date('Y-m-d') && $this->session->userdata('id_perfil') > 0) {
                echo "FECHA NO VÄLIDA (El pasado, pasado está)";
                exit();
            }*/
        } else {
            $fecha_ddmmaaaa = date("d-m-Y");
            $fecha_aaaammdd = date("Y-m-d");
            $fecha = date("Y-m-d");
        }

        $data['fecha'] = $fecha;

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        $data['id_centro'] = $id_centro;

        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Leemos todas las citas
        unset($parametros);
        $parametros['programadas_finalizadas'] = 1;
        $parametros['fecha'] = $fecha_ddmmaaaa;
        $parametros['id_centro'] = $id_centro;
        //$parametros['lopd_cumplimentada'] = 1;
        if ($this->session->userdata('id_perfil') == 6) {
            $parametros['id_empleado'] = $this->session->userdata('id_usuario');
        }
        $data['citas_agenda'] = $this->Agenda_model->leer_citas($parametros);
        // recorrer las citas de la acenda para indicar el saldo de su presupuesto, si tiene
        if (is_array($data['citas_agenda'])) {
            $presupuestos_dia = [];
            foreach ($data['citas_agenda'] as $key => $cita) {
                if ($cita['id_presupuesto'] != '') {
                    // es una cita de presupuesto. Se comprueba si ya se ha procesado el presu
                    if (array_key_exists($cita['id_presupuesto'], $presupuestos_dia)) {
                        // existe, se comprueba el saldo
                        if ($presupuestos_dia[$cita['id_presupuesto']]['disponible'] >= $cita['coste']) {
                            $data['citas_agenda'][$key]['saldo'] = 1;
                            $presupuestos_dia[$cita['id_presupuesto']]['disponible'] = $presupuestos_dia[$cita['id_presupuesto']]['disponible'] - $cita['coste'];
                        }
                    } else {
                        // no existe, se añade
                        $this->load->model('Presupuestos_model');
                        $param5['id_presupuesto'] = $cita['id_presupuesto'];
                        $presupuesto = $this->Presupuestos_model->leer_presupuestos($param5)[0];
                        $presupuesto['disponible'] = $presupuesto['total_pagado'] - $presupuesto['total_gastado'];
                        if ($presupuesto['disponible'] >= $cita['coste']) {
                            $data['citas_agenda'][$key]['saldo'] = 1;
                            $presupuesto['disponible'] = $presupuesto['disponible'] - $cita['coste'];
                        }
                        $presupuestos_dia[$cita['id_presupuesto']] = $presupuesto;
                    }
                }
            }
        }
        // ... Leemos todos los empleados del centro.
        unset($parametros);

        $parametros['fecha_agenda'] = $fecha;
        $parametros['id_centro'] = $id_centro;
        if ($this->session->userdata('id_perfil') == 6) {
            $parametros['id_usuario'] = $this->session->userdata('id_usuario');
        } else {
            $parametros['solo_empleados_con_horarios'] = 1;
        }
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos horarios de trabajo de cada empleado.
        if ($data['empleados'] != 0) {
            foreach ($data['empleados'] as $key => $row) {
                unset($parametros);
                $parametros['id_usuario'] = $row['id_usuario'];
                $parametros['fecha'] = $fecha_aaaammdd;
                $data['horarios'][$row['id_usuario']] = $this->Horarios_model->leer_horarios($parametros);
            }
        }

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Agenda';
        if ($this->session->userdata('id_perfil') <> 6) {
            $data['content_view'] = $this->load->view('agenda/agenda_view', $data, true);
        } else {
            $data['content_view'] = $this->load->view('agenda/agenda_doctor_view', $data, true);
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master_agenda', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... AGENDA EMPLEADOS
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //

    function empleados($fecha = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($fecha != null) {
            $date = new DateTime($fecha);
            $fecha_ddmmaaaa = date_format($date, 'd-m-Y');
            $fecha_aaaammdd = date_format($date, 'Y-m-d');
            if ($fecha_aaaammdd < date('Y-m-d')) {
                echo "FECHA NO VÄLIDA (El padasdo, pasado está)";
                exit();
            }
        } else {
            $fecha_ddmmaaaa = date("d-m-Y");
            $fecha_aaaammdd = date("Y-m-d");
            $fecha = date("Y-m-d");
        }

        $data['fecha'] = $fecha;

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 0;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Leemos todos los empleados del centro.
        unset($parametros);
        $parametros['solo_empleados_con_horarios'] = 1;
        $parametros['fecha_agenda'] = $fecha;
        if (isset($id_centro)) {
            if ($id_centro > 0) {
                $parametros['id_centro'] = $id_centro;
            }
        }
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos todos las citas y horarios de trabajo de cada empleado.
        if ($data['empleados'] != 0) {
            foreach ($data['empleados'] as $key => $row) {
                unset($parametros);
                $parametros['programadas_finalizadas'] = 1;
                $parametros['id_empleado'] = $row['id_usuario'];
                $parametros['fecha'] = $fecha_ddmmaaaa;
                // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
                // corresponda.
                if ($this->session->userdata('id_perfil') > 0) {
                    $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
                }
                $data['citas'][$row['id_usuario']] = $this->Agenda_model->leer_citas($parametros);

                unset($parametros);
                $parametros['id_usuario'] = $row['id_usuario'];
                $parametros['fecha'] = $fecha_aaaammdd;
                $data['horarios'][$row['id_usuario']] = $this->Horarios_model->horas_trabaja_empleado($parametros);
            }
        }

        $data['horas'] = $this->Agenda_model->horarios();

        $data['id_centro'] = $id_centro;

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Agenda de Empleados';
        $data['content_view'] = $this->load->view('agenda/agenda_empleados_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 12);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }



    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... CONTROL DE CITAS
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function citas($accion = null, $id_cita = null, $id_empleado = null, $fecha_cita = null, $hora_cita = null, $id_cliente_cita = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['accion'] = $accion;
        $data['notas_cliente'] = "";

        // ---------------------------------------
        // ... Nueva cita
        // ---------------------------------------
        if ($accion == "nuevo") {
            unset($parametros);
            $parametros = $_POST;
            if ($id_empleado != null) {
                $parametros['id_empleado'] = $id_empleado;
            }
            if ($fecha_cita != null) {
                $parametros['fecha'] = $fecha_cita;
            } else {
                // ... Esto lo hago porque si el parametro de la fecha viene
                // como post desde el campo de fecha del formulario
                // viene en formato dd/mm/aaaa y necestio aaaa-mm-dd
                if (isset($parametros['fecha'])) {
                    $pos = strpos($parametros['fecha'], "/");
                    if (!($pos === false)) {
                        $partes = explode("/", $parametros['fecha']);
                        $parametros['fecha'] = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
                    }
                }
            }
            if ($hora_cita != null) {
                $data['cita'][0]['hora_inicio'] = str_replace("-", ":", $hora_cita);
            }
            if ($id_cliente_cita != null) {
                $parametros['id_cliente'] = $id_cliente_cita;
            }

            if (isset($parametros)) {

                if (isset($parametros['id_empleado'])) {
                    $data['cita'][0]['id_usuario_empleado'] = $parametros['id_empleado'];

                    unset($param);
                    $param['id_empleado'] = $parametros['id_empleado'];
                    $param['obsoleto'] = 0;
                    $data['servicios'] = $this->Servicios_model->leer_servicios($param);
                }

                if (isset($parametros['id_cliente'])) {
                    $data['cita'][0]['id_cliente'] = $parametros['id_cliente'];

                    unset($param5);
                    $param5['id_cliente'] = $parametros['id_cliente'];
                    $param5['aceptados'] = TRUE;
                    $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
                    $param5['total_pendiente'] = 1;
                    $this->load->model('Presupuestos_model');
                    $presupuestos_cliente = $this->Presupuestos_model->leer_presupuestos($param5);
                    if ($presupuestos_cliente != 0 && count($presupuestos_cliente) > 0) {
                        $data['presupuestos_cliente'] = $presupuestos_cliente;
                    }
                    if (isset($parametros['id_presupuesto']) && $parametros['id_presupuesto'] != '') {
                        $param6['id_presupuesto'] = $parametros['id_presupuesto'];
                        $param6['citas'] = 1;
                        $param6['aceptado'] = 1;
                        $data['id_presupuesto'] = $parametros['id_presupuesto'];
                        $data['presupuesto_items'] = $this->Presupuestos_model->leer_presupuestos_items($param6);
                        // Para revisar que los items del presupuesto sean validos para el id_empleado
                        if (isset($data['servicios'])) {
                            $id_servicios = [];
                            foreach ($data['servicios'] as $s => $serv) {
                                $id_servicios[] = $serv['id_servicio'];
                            }
                            foreach ($data['presupuesto_items'] as $key => $presupuesto_item) {
                                if (!in_array($presupuesto_item['id_item'], $id_servicios)) {
                                    $data['presupuesto_items'][$key]['disabled'] = 'disabled';
                                }
                            }
                        }
                    }


                    if (isset($parametros['id_presupuesto_item']) && count($parametros['id_presupuesto_item']) > 0) {
                        foreach ($parametros['id_presupuesto_item'] as $key => $id_presupuesto_item) {
                            if ($id_presupuesto_item != '') {
                                $data['cita'][0]['id_presupuesto_item'][] = $id_presupuesto_item;
                            }
                        }
                    }

                    if (!isset($parametros['id_cliente_anterior'])) {
                        $parametros['id_cliente_anterior'] = 0;
                    }

                    if ($parametros['id_cliente'] != $parametros['id_cliente_anterior']) {
                        $parametros['recordatorio_sms'] = $data['cliente_elegido'][0]['recordatorio_sms'];
                        $parametros['recordatorio_email'] = $data['cliente_elegido'][0]['recordatorio_email'];
                    }
                    $data['id_cliente_anterior'] = $parametros['id_cliente'];

                    // ... Leemos las notas que pueda tener el cliente asignadas
                    // para mostrarlas cuando es una cita nueva.
                    if (isset($parametros['observaciones'])) {
                        if ($parametros['observaciones'] == "") {
                            unset($param3);
                            $param3['id_cliente'] = $parametros['id_cliente'];
                            $cliente_notas = $this->Clientes_model->leer_clientes($param3);
                            $data['notas_cliente'] = $cliente_notas[0]['notas'];
                        } else {
                            $data['notas_cliente'] = $parametros['observaciones'];
                        }
                    } else {
                        unset($param3);
                        $param3['id_cliente'] = $parametros['id_cliente'];
                        $cliente_notas = $this->Clientes_model->leer_clientes($param3);
                        $data['notas_cliente'] = $cliente_notas[0]['notas'];
                    }
                } else {
                    if (isset($parametros['observaciones'])) {
                        $data['notas_cliente'] = $parametros['observaciones'];
                    }

                    $parametros['recordatorio_sms'] = 0;
                    $parametros['recordatorio_email'] = 0;
                }


                if (isset($parametros['solo_este_empleado'])) {
                    $data['cita'][0]['solo_este_empleado'] = $parametros['solo_este_empleado'];
                }

                if (isset($parametros['recordatorio_sms'])) {
                    $data['cita'][0]['recordatorio_sms'] = $parametros['recordatorio_sms'];
                } else {
                    if (!isset($data['cita'][0]['recordatorio_sms']))
                        $data['cita'][0]['recordatorio_sms'] = "";
                }

                if (isset($parametros['recordatorio_email'])) {
                    $data['cita'][0]['recordatorio_email'] = $parametros['recordatorio_email'];
                } else {
                    $data['cita'][0]['recordatorio_email'] = "";
                }

                if (isset($parametros['codigo_proveedor'])) {
                    $data['codigo_proveedor'] = $parametros['codigo_proveedor'];
                }

                // ... Gestion de los servicios, pueden ser hasta 6 y lo vamos sumando
                // para luego calcular si hay hueco en base a la duracion total de todos.
                if (isset($parametros['id_servicio'])) {
                    $data['cita'][0]['id_servicio'] = $parametros['id_servicio'];

                    unset($param2);
                    $param2['id_servicio'] = $parametros['id_servicio'];
                    $servicio_marcado = $this->Servicios_model->leer_servicios($param2);
                    $data['id_familia_servicio'] = $servicio_marcado[0]['id_familia_servicio'];
                }
                if (isset($parametros['id_servicio2'])) {
                    if ($parametros['id_servicio2'] > 0) {
                        $data['cita'][0]['id_servicio2'] = $parametros['id_servicio2'];
                    }
                }
                if (isset($parametros['id_servicio3'])) {
                    if ($parametros['id_servicio3'] > 0) {
                        $data['cita'][0]['id_servicio3'] = $parametros['id_servicio3'];
                    }
                }
                if (isset($parametros['id_servicio4'])) {
                    if ($parametros['id_servicio4'] > 0) {
                        $data['cita'][0]['id_servicio4'] = $parametros['id_servicio4'];
                    }
                }
                if (isset($parametros['id_servicio5'])) {
                    if ($parametros['id_servicio5'] > 0) {
                        $data['cita'][0]['id_servicio5'] = $parametros['id_servicio5'];
                    }
                }
                if (isset($parametros['id_servicio6'])) {
                    if ($parametros['id_servicio6'] > 0) {
                        $data['cita'][0]['id_servicio6'] = $parametros['id_servicio6'];
                    }
                }

                if (isset($parametros['fecha'])) {
                    $data['cita'][0]['fecha_inicio_aaaammdd'] = $parametros['fecha'];

                    unset($param);
                    $param['vacio'] = "";
                    if (isset($parametros['id_empleado'])) {
                        $param['id_empleado'] = $parametros['id_empleado'];
                    }
                    if (isset($parametros['fecha'])) {
                        $param['fecha'] = $parametros['fecha'];
                    }
                    if (isset($parametros['id_servicio'])) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] = $serv[0]['duracion'];
                    }
                    if (isset($parametros['id_servicio2'])) {
                        if ($parametros['id_servicio2'] > 0) {
                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros['id_servicio2'];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);

                            $param['duracion'] += $serv[0]['duracion'];
                        }
                    }
                    if (isset($parametros['id_servicio3'])) {
                        if ($parametros['id_servicio3'] > 0) {
                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros['id_servicio3'];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);

                            $param['duracion'] += $serv[0]['duracion'];
                        }
                    }
                    if (isset($parametros['id_servicio4'])) {
                        if ($parametros['id_servicio4'] > 0) {
                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros['id_servicio4'];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);

                            $param['duracion'] += $serv[0]['duracion'];
                        }
                    }
                    if (isset($parametros['id_servicio5'])) {
                        if ($parametros['id_servicio5'] > 0) {
                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros['id_servicio5'];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);

                            $param['duracion'] += $serv[0]['duracion'];
                        }
                    }
                    if (isset($parametros['id_servicio6'])) {
                        if ($parametros['id_servicio6'] > 0) {
                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros['id_servicio6'];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);

                            $param['duracion'] += $serv[0]['duracion'];
                        }
                    }

                    if ($parametros['fecha'] != "") {
                        $date = new DateTime($param['fecha']);
                        $param['fecha'] = date_format($date, 'd-m-Y');
                        //$data['horas_libres']=$this->Agenda_model->horas_libres($param);
                        $data['horas_libres'] = $this->Agenda_model->horas_libres_sin_control($param);
                    }
                }

                if (isset($parametros['hora'])) {
                    $data['cita'][0]['hora_inicio'] = $parametros['hora'];
                }
            }
        }

        // ---------------------------------------
        // ... Editar cita
        // ---------------------------------------
        if ($accion == "editar") {
            unset($parametros);
            $parametros = $_POST;

            // ... Si el id_cita es igual a -99 entonces creamos un nuevo cliente.
            if ($id_empleado == -99) {
                unset($parametros);
                $parametros['nombre'] = $_POST['nombre'];
                $parametros['apellidos'] = $_POST['apellidos'];
                $parametros['telefono'] = $_POST['telefono'];
                if (isset($_POST['no_quiere_publicidad'])) {
                    $parametros['no_quiere_publicidad'] = $_POST['no_quiere_publicidad'];
                }
                if ($_POST['como_conocio'] !== '-1') {
                    $parametros['como_conocio'] = $_POST['como_conocio'];
                }


                $id_cliente_cita = $this->Clientes_model->nuevo_cliente($parametros);

                //19/05/20 Crear carnet Único ******************* asignar asignar carnet único **********
                $codigo_carnet = "U" . $id_cliente_cita;
                $id_carnet = "9988" . $id_cliente_cita;
                $id_carnet = intval($id_carnet);
                //echo "no ".$id_carnet." ".$codigo_carnet;
                unset($param2);
                $param2['id_carnet'] = $id_carnet;
                $param2['codigo'] = $codigo_carnet;
                $param2['id_cliente'] = $id_cliente_cita;

                $guardar_codigo = $this->Carnets_model->guardar_carnet_unico($param2);
                //echo " guardar ".$guardar_codigo;
                //Fin *********************** fin ************************* fin *********


            }


            unset($param);
            $param['id_cita'] = $id_cita;
            $data['cita'] = $this->Agenda_model->leer_citas($param);

            //para ver los servicios del empleado seleccionado
            if (isset($parametros['id_empleado'])) {
                if ($parametros['id_empleado'] > 0) {
                    $data['cita'][0]['id_usuario_empleado'] = $parametros['id_empleado'];
                }
            }
            unset($param);
            $param['id_empleado'] = $data['cita'][0]['id_usuario_empleado'];
            $data['servicios'] = $this->Servicios_model->leer_servicios($param);

            unset($param5);
            $param5['id_cliente'] = $data['cita'][0]['id_cliente'];
            $param5['aceptados'] = TRUE;
            $param5['total_pendiente'] = 1;
            $this->load->model('Presupuestos_model');
            $presupuestos_cliente = $this->Presupuestos_model->leer_presupuestos($param5);
            if ($presupuestos_cliente != 0 && count($presupuestos_cliente) > 0) {
                $data['presupuestos_cliente'] = $presupuestos_cliente;
            }

            if (isset($parametros['id_presupuesto']) && $parametros['id_presupuesto'] != '') {
                $param6['id_presupuesto'] = $parametros['id_presupuesto'];
                $param6['citas'] = 1;
                $param6['aceptado'] = 1;
                $data['id_presupuesto'] = $parametros['id_presupuesto'];
                $data['presupuesto_items'] = $this->Presupuestos_model->leer_presupuestos_items($param6);
                // Para revisar que los items del presupuesto sean validos para el id_empleado
                if (isset($data['servicios'])) {
                    $id_servicios = [];
                    foreach ($data['servicios'] as $s => $serv) {
                        $id_servicios[] = $serv['id_servicio'];
                    }
                    foreach ($data['presupuesto_items'] as $key => $presupuesto_item) {
                        if (!in_array($presupuesto_item['id_item'], $id_servicios)) {
                            $data['presupuesto_items'][$key]['disabled'] = 'disabled';
                        }
                    }

                }
            }

            if (isset($parametros['id_presupuesto_item']) && count($parametros['id_presupuesto_item']) > 0) {
                foreach ($parametros['id_presupuesto_item'] as $key => $id_presupuesto_item) {
                    if ($id_presupuesto_item != '') {
                        $data['cita'][0]['id_presupuesto_item'][] = $id_presupuesto_item;
                    }
                }
            }


            // ver si es una cita de presupuesto
            $param2['id_cita'] = $id_cita;
            $enpresupuesto = $this->Presupuestos_model->leer_presupuestos_items($param2);
            if (!empty($enpresupuesto)) {
                // se busca los presupuestos del cliente y se marca el que es
                $param5['id_cliente'] = $enpresupuesto[0]['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
                $param5['total_pendiente'] = 1;
                $data['presupuestos_cliente'] = $this->Presupuestos_model->leer_presupuestos($param5);
                $param6['id_presupuesto'] = $enpresupuesto[0]['id_presupuesto'];
                $param6['citas'] = 1;
                $param6['or_id_presupuesto_item'] = $enpresupuesto[0]['id_presupuesto_item'];
                $param6['aceptado'] = 1;

                $data['id_presupuesto'] = $enpresupuesto[0]['id_presupuesto'];
                //para el select
                $data['presupuesto_items'] = $this->Presupuestos_model->leer_presupuestos_items($param6);
                // Para revisar que los items del presupuesto sean validos para el id_empleado
                if (isset($data['servicios'])) {
                    $id_servicios = [];
                    foreach ($data['servicios'] as $s => $serv) {
                        $id_servicios[] = $serv['id_servicio'];
                    }
                    foreach ($data['presupuesto_items'] as $key => $presupuesto_item) {
                        if (!in_array($presupuesto_item['id_item'], $id_servicios)) {
                            $data['presupuesto_items'][$key]['disabled'] = 'disabled';
                        }
                    }

                }
                // para ver los que hay que marcar
                if (!isset($parametros['id_presupuesto_item'])) {
                    foreach ($enpresupuesto as $key => $value) {
                        $parametros['id_presupuesto_item'][] = $value['id_item'] . '|' . $value['id_presupuesto_item'];
                    }
                }
                if (isset($parametros['id_presupuesto_item']) && count($parametros['id_presupuesto_item']) > 0) {
                    // Para revisar que los items del presupuesto sean validos para el id_empleado
                    foreach ($parametros['id_presupuesto_item'] as $key => $id_presupuesto_item) {
                        if ($id_presupuesto_item != '') {
                            $data['cita'][0]['id_presupuesto_item'][] = $id_presupuesto_item;
                        }
                    }
                }
            }

            $data['cita'][0]['id_usuario_empleado_actual'] = $data['cita'][0]['id_usuario_empleado'];
            $data['cita'][0]['duracion_actual'] = $data['cita'][0]['duracion'];

            $data['id_servicio_ultimo_marcado'] = $data['cita'][0]['id_servicio'];

            //30/03/20
            //Que otras citas tiene el Cliente
            unset($param10);
            $param10['id_cliente'] = $data['cita'][0]['id_cliente'];
            $param10['estado'] = "Programada";
            $param10['fecha_desde'] = date('Y-m-d', strtotime('-1 days'));
            $data['otrascitas'] = $this->Agenda_model->leer_citas($param10);
            $data['citaactual'] = $id_cita;
            //Fin

            if ($id_cliente_cita != null) {
                $parametros['id_cliente'] = $id_cliente_cita;
            }

            if (isset($parametros['id_cliente'])) {
                $data['cita'][0]['id_cliente'] = $parametros['id_cliente'];
                unset($param5);
                $param5['id_cliente'] = $parametros['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
                // ... Leemos las notas que pueda tener el cliente asignadas
                // para mostrarlas cuando es una cita nueva.
                if (isset($parametros['observaciones'])) {
                    if ($parametros['observaciones'] == "") {
                        unset($param3);
                        $param3['id_cliente'] = $parametros['id_cliente'];
                        $cliente_notas = $this->Clientes_model->leer_clientes($param3);
                        $data['notas_cliente'] = $cliente_notas[0]['notas'];
                    } else {
                        $data['notas_cliente'] = $parametros['observaciones'];
                    }
                } else {
                    $data['notas_cliente'] = $data['cita'][0]['observaciones'];
                }
                if (!isset($parametros['id_cliente_anterior'])) {
                    $parametros['id_cliente_anterior'] = 0;
                }
                if ($parametros['id_cliente'] != $parametros['id_cliente_anterior']) {
                    $parametros['recordatorio_sms'] = $data['cliente_elegido'][0]['recordatorio_sms'];
                    $parametros['recordatorio_email'] = $data['cliente_elegido'][0]['recordatorio_email'];
                }
                $data['id_cliente_anterior'] = $parametros['id_cliente'];
                if ($parametros['id_cliente'] == "") {
                    $parametros['recordatorio_sms'] = 0;
                    $parametros['recordatorio_email'] = 0;
                }
            } else {
                $data['notas_cliente'] = $data['cita'][0]['observaciones'];
                unset($param5);
                $param5['id_cliente'] = $data['cita'][0]['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

                $data['id_cliente_anterior'] = $data['cita'][0]['id_cliente'];
            }


            if (isset($parametros['solo_este_empleado'])) {
                $data['cita'][0]['solo_este_empleado'] = $parametros['solo_este_empleado'];
            }

            if (isset($parametros)) {
                if (isset($parametros['recordatorio_sms'])) {
                    $data['cita'][0]['recordatorio_sms'] = $parametros['recordatorio_sms'];
                } else {
                    $data['cita'][0]['recordatorio_sms'] = "";
                }
                if (isset($parametros['recordatorio_email'])) {
                    $data['cita'][0]['recordatorio_email'] = $parametros['recordatorio_email'];
                } else {
                    $data['cita'][0]['recordatorio_email'] = "";
                }
            }

            if ($fecha_cita != null) {
                $parametros['fecha'] = $fecha_cita;
            } else {
                // ... Esto lo hago porque si el parametro de la fecha viene
                // como post desde el campo de fecha del formulario
                // viene en formato dd/mm/aaaa y necestio aaaa-mm-dd
                if (isset($parametros['fecha'])) {
                    $pos = strpos($parametros['fecha'], "/");
                    if (!($pos === false)) {
                        $partes = explode("/", $parametros['fecha']);
                        $parametros['fecha'] = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
                    }
                }
            }

            unset($param);
            $param['id_empleado'] = $data['cita'][0]['id_usuario_empleado'];
            $param['fecha'] = $data['cita'][0]['fecha_inicio'];
            $param['duracion'] = $data['cita'][0]['duracion'];
            $param['id_cita_excluir'] = $id_cita;

            // ... En caso de que se haya elegido otro servicio para la cita editada
            // paso la nueva duración para el recalculo de horarios disponibles.

            if (isset($parametros['id_servicio'])) {
                $data['cita'][0]['id_servicio'] = $parametros['id_servicio'];

                unset($param2);
                $param2['id_servicio'] = $parametros['id_servicio'];
                $servicio_marcado = $this->Servicios_model->leer_servicios($param2);
                $data['id_familia_servicio'] = $servicio_marcado[0]['id_familia_servicio'];
            }
            if (isset($parametros['id_servicio2'])) {
                if ($parametros['id_servicio2'] > 0) {
                    $data['cita'][0]['id_servicio2'] = $parametros['id_servicio2'];
                }
            }
            if (isset($parametros['id_servicio3'])) {
                if ($parametros['id_servicio3'] > 0) {
                    $data['cita'][0]['id_servicio3'] = $parametros['id_servicio3'];
                }
            }
            if (isset($parametros['id_servicio4'])) {
                if ($parametros['id_servicio4'] > 0) {
                    $data['cita'][0]['id_servicio4'] = $parametros['id_servicio4'];
                }
            }
            if (isset($parametros['id_servicio5'])) {
                if ($parametros['id_servicio5'] > 0) {
                    $data['cita'][0]['id_servicio5'] = $parametros['id_servicio5'];
                }
            }
            if (isset($parametros['id_servicio6'])) {
                if ($parametros['id_servicio6'] > 0) {
                    $data['cita'][0]['id_servicio6'] = $parametros['id_servicio6'];
                }
            }

            if (isset($parametros['fecha'])) {
                $data['cita'][0]['fecha_inicio_aaaammdd'] = $parametros['fecha'];
                unset($param);
                $param['vacio'] = "";
                if (isset($parametros['id_empleado'])) {
                    $param['id_empleado'] = $parametros['id_empleado'];
                }
                if (isset($parametros['fecha'])) {
                    $param['fecha'] = $parametros['fecha'];
                }
                if (isset($parametros['id_servicio'])) {
                    unset($param_serv);
                    $param_serv['id_servicio'] = $parametros['id_servicio'];
                    $serv = $this->Servicios_model->leer_servicios($param_serv);

                    $param['duracion'] = $serv[0]['duracion'];
                }
                if (isset($parametros['id_servicio2'])) {
                    if ($parametros['id_servicio2'] > 0) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio2'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] += $serv[0]['duracion'];
                    }
                }
                if (isset($parametros['id_servicio3'])) {
                    if ($parametros['id_servicio3'] > 0) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio3'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] += $serv[0]['duracion'];
                    }
                }
                if (isset($parametros['id_servicio4'])) {
                    if ($parametros['id_servicio4'] > 0) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio4'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] += $serv[0]['duracion'];
                    }
                }
                if (isset($parametros['id_servicio5'])) {
                    if ($parametros['id_servicio5'] > 0) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio5'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] += $serv[0]['duracion'];
                    }
                }
                if (isset($parametros['id_servicio6'])) {
                    if ($parametros['id_servicio6'] > 0) {
                        unset($param_serv);
                        $param_serv['id_servicio'] = $parametros['id_servicio6'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);

                        $param['duracion'] += $serv[0]['duracion'];
                    }
                }

                if ($parametros['fecha'] != "") {
                    $date = new DateTime($param['fecha']);
                    $param['fecha'] = date_format($date, 'd-m-Y');
                    //$data['horas_libres']=$this->Agenda_model->horas_libres($param);
                    $data['horas_libres'] = $this->Agenda_model->horas_libres_sin_control($param);
                }
            }

            if (isset($parametros['hora'])) {
                $data['cita'][0]['hora_inicio'] = $parametros['hora'];
            }

            if (isset($parametros['id_servicio'])) {
                unset($param3);
                $param3['id_servicio'] = $parametros['id_servicio'];
                $servicio_elegido = $this->Servicios_model->leer_servicios($param3);

                $param['duracion'] = $servicio_elegido[0]['duracion'];

                $data['id_servicio_ultimo_marcado'] = $parametros['id_servicio'];
            }

            // ... Si se especifica una duracion personalizada,
            // no se guarda la del propio servicio.
            if (isset($parametros['duracion_nueva'])) {
                $data['duracion_nueva'] = $parametros['duracion_nueva'];
                $param['duracion'] = $parametros['duracion_nueva'];
            } else {
                $data['duracion_nueva'] = $param['duracion'];
            }

            // ... Si se cambia de servicio, la duracion nueva vuelve a ser la original
            // del nuevo servicio marcado.
            if (isset($parametros['id_servicio'])) {
                if ($parametros['id_servicio'] != $parametros['id_servicio_ultimo_marcado']) {
                    $data['duracion_nueva'] = $servicio_elegido[0]['duracion'];
                    $param['duracion'] = $servicio_elegido[0]['duracion'];
                }
            }

            //$data['horas_libres']=$this->Agenda_model->horas_libres($param);
            $data['horas_libres'] = $this->Agenda_model->horas_libres_sin_control($param);

            unset($param2);
            $param2['id_servicio'] = $data['cita'][0]['id_servicio'];
            if (isset($parametros['id_servicio'])) {
                $param2['id_servicio'] = $parametros['id_servicio'];
                $data['cita'][0]['id_servicio'] = $parametros['id_servicio'];

                $servicio_marcado = $this->Servicios_model->leer_servicios($param2);
                $data['id_familia_servicio'] = $servicio_marcado[0]['id_familia_servicio'];
            }

            unset($param);
            $param['id_cita'] = $id_cita;
            $cita_dietario = $this->Dietario_model->leer($param);
            $data['codigo_proveedor'] = (isset($cita_dietario[0]['codigo_proveedor'])) ? $cita_dietario[0]['codigo_proveedor'] : '';

            if (isset($data['cita'][0]['id_cliente'])) {
                $data['existe_firma'] = $this->Clientes_model->existe_firma_lopd($data['cita'][0]['id_cliente']);
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Guardar una cita
        // ---------------------------------------
        if ($accion == "guardar") {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;
            //printr($parametros);
            if (isset($parametros)) {
                unset($param);

                unset($param_serv);

                // si llega id_presupuesto_item, se busca el item del presupuesto
                if (isset($parametros['id_presupuesto_item']) && count($parametros['id_presupuesto_item']) > 0) {
                    //se recorre el item
                    foreach ($parametros['id_presupuesto_item'] as $key => $presupuesto_item) {
                        $explodeitemid = explode('|', $presupuesto_item);
                        $id_servicio = $param_serv['id_servicio'] = $explodeitemid[0];
                        $id_presupuesto_item = $explodeitemid[1];
                        $id_presupuesto = $parametros['id_presupuesto'];
                        $serv = $this->Servicios_model->leer_servicios($param_serv);
                        if ($serv[0]['duracion'] == 10) {
                            $serv[0]['duracion'] = 15;
                        }
                        if ($serv[0]['duracion'] == 20) {
                            $serv[0]['duracion'] = 30;
                        }
                        $param['duracion'] = $serv[0]['duracion'];

                        $param['id_servicio'] = $id_servicio;
                        $param['id_empleado'] = $parametros['id_empleado'];
                        $param['id_cliente'] = $parametros['id_cliente'];
                        $param['fecha'] = $parametros['fecha'];
                        $param['hora'] = (isset($nuevahorainicio)) ? $nuevahorainicio : $parametros['hora'];
                        $param['observaciones'] = $parametros['observaciones'];
                        if (isset($parametros['solo_este_empleado'])) {
                            $param['solo_este_empleado'] = $parametros['solo_este_empleado'];
                        }
                        if (isset($parametros['recordatorio_sms'])) {
                            $param['recordatorio_sms'] = $parametros['recordatorio_sms'];
                        }
                        if (isset($parametros['recordatorio_email'])) {
                            $param['recordatorio_email'] = $parametros['recordatorio_email'];
                        }
                        $id_cita_creada = $this->Agenda_model->guardar_cita($param);
                        if ($id_cita_creada > 0) {
                            $this->load->model('Presupuestos_model');
                            $item_presupuesto = $this->Presupuestos_model->leer_presupuestos_items(['id_presupuesto_item' => $id_presupuesto_item]);

                            unset($param2);
                            $param2['id_cita'] = $id_cita_creada;
                            $param2['id_presupuesto'] = $id_presupuesto;
                            $param2['importe_euros'] = $item_presupuesto[0]['coste'];
                            if (isset($parametros['codigo_proveedor'])) {
                                $param2['codigo_proveedor'] = $parametros['codigo_proveedor'];
                            }
                            $r = $this->Dietario_model->copiar_cita_presupuesto($param2);
                            $this->Presupuestos_model->actualizar_presupuesto_item_cita_dietario($id_presupuesto_item, $id_cita_creada, $r);
                        }

                        if ($id_cita_creada > 0) {
                            unset($paramcitas);
                            $paramcitas['id_cita'] = $id_cita_creada;
                            $paramcitas['id_cliente'] = $parametros['id_cliente'];
                            $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                            // ... Enviamos el email de aviso al cliente
                            $to = 'citas@templodelmasaje.com';
                            $from = "info@templodelmasaje.com";
                            $asunto = "Nueva Cita N";
                            $lugar = "";
                            if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                                $lugar = " (dentro del Hotel Nuevo Madrid) ";
                            }

                            $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                            $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                            $mensaje = "Hola " . $xnombre . ", ";
                            $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                            $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                            $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";

                            //25/05/20 ***************++ grabar avisos **************+++
                            unset($paramavisos);
                            $paramavisos['id_cita'] = $citas[0]['id_cita'];
                            $paramavisos['id_centro'] = $citas[0]['id_centro'];
                            $paramavisos['centro'] = $citas[0]['nombre_centro'];
                            $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                            //$paramavisos['cliente']=$citas[0]['nombreprincipal'];
                            $paramavisos['cliente'] = $citas[0]['cliente'];
                            $paramavisos['telefono'] = $citas[0]['telefono'];
                            $paramavisos['asunto'] = "Modificar Cita";
                            $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd']; //18/06/20 ahora fecha_creacion será fecha de la cita.
                            $paramavisos['mensaje'] = $mensaje;
                            $g = $this->Agenda_model->grabar_aviso($paramavisos);
                            //Fin de grabar avisos 25/05/20

                        }

                        $segundos_horaInicial = strtotime($param['hora']);
                        $segundos_minutoAnadir = ($serv[0]['duracion']) * 60;
                        $nuevahorainicio = date("H:i", $segundos_horaInicial + $segundos_minutoAnadir);
                    }


                } else {
                    $param_serv['id_servicio'] = $parametros['id_servicio'];
                    $serv = $this->Servicios_model->leer_servicios($param_serv);

                    // ... Si la duracion es 10min o 20min lo ajustamos a multiplos de 15.
                    if ($serv[0]['duracion'] == 10) {
                        $serv[0]['duracion'] = 15;
                    }
                    if ($serv[0]['duracion'] == 20) {
                        $serv[0]['duracion'] = 30;
                    }
                    $param['duracion'] = $serv[0]['duracion'];
                    $param['id_servicio'] = $parametros['id_servicio'];
                    $param['id_empleado'] = $parametros['id_empleado'];
                    $param['id_cliente'] = $parametros['id_cliente'];
                    $param['fecha'] = $parametros['fecha'];
                    $param['hora'] = $parametros['hora'];
                    $param['observaciones'] = $parametros['observaciones'];
                    if (isset($parametros['solo_este_empleado'])) {
                        $param['solo_este_empleado'] = $parametros['solo_este_empleado'];
                    }
                    if (isset($parametros['recordatorio_sms'])) {
                        $param['recordatorio_sms'] = $parametros['recordatorio_sms'];
                    }
                    if (isset($parametros['recordatorio_email'])) {
                        $param['recordatorio_email'] = $parametros['recordatorio_email'];
                    }
                    $id_cita_creada = $this->Agenda_model->guardar_cita($param);

                    if ($id_cita_creada > 0) {
                        unset($param2);
                        $param2['id_cita'] = $id_cita_creada;
                        if (isset($parametros['codigo_proveedor'])) {
                            $param2['codigo_proveedor'] = $parametros['codigo_proveedor'];
                        }
                        $r = $this->Dietario_model->copiar_cita($param2);
                    }

                    if ($id_cita_creada > 0) {
                        unset($paramcitas);
                        $paramcitas['id_cita'] = $id_cita_creada;
                        $paramcitas['id_cliente'] = $parametros['id_cliente'];
                        $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                        // ... Enviamos el email de aviso al cliente
                        $to = 'citas@templodelmasaje.com';
                        $from = "info@templodelmasaje.com";
                        $asunto = "Nueva Cita N";
                        $lugar = "";
                        if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                            $lugar = " (dentro del Hotel Nuevo Madrid) ";
                        }

                        $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                        $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                        $mensaje = "Hola " . $xnombre . ", ";
                        $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                        $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                        $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";
                        //$mensaje.="Cliente ".$citas[0]['cliente'].", ".$citas[0]['telefono'].".";

                        //$this->Utiles_model->enviar_email($to,$from,$asunto,$mensaje);

                        //25/05/20 ***************++ grabar avisos **************+++
                        unset($paramavisos);
                        $paramavisos['id_cita'] = $citas[0]['id_cita'];
                        $paramavisos['id_centro'] = $citas[0]['id_centro'];
                        $paramavisos['centro'] = $citas[0]['nombre_centro'];
                        $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                        //$paramavisos['cliente']=$citas[0]['nombreprincipal'];
                        $paramavisos['cliente'] = $citas[0]['cliente'];
                        $paramavisos['telefono'] = $citas[0]['telefono'];
                        $paramavisos['asunto'] = "Modificar Cita";
                        $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd']; //18/06/20 ahora fecha_creacion será fecha de la cita.
                        $paramavisos['mensaje'] = $mensaje;
                        $g = $this->Agenda_model->grabar_aviso($paramavisos);
                        //Fin de grabar avisos 25/05/20

                    }


                    $segundos_horaInicial = strtotime($param['hora']);
                    $segundos_minutoAnadir = ($serv[0]['duracion']) * 60;
                    $param['hora'] = date("H:i", $segundos_horaInicial + $segundos_minutoAnadir);

                    // ---------------------------------------
                    // ... Guardamos los servicios / citas adicionales si lo hay.
                    // ---------------------------------------
                    for ($i = 2; $i < 7; $i++) {
                        $item = "id_servicio" . $i;
                        if (isset($parametros[$item])) {
                            if ($parametros[$item] > 0) {
                                $param['id_servicio'] = $parametros[$item];

                                unset($param_serv);
                                $param_serv['id_servicio'] = $parametros[$item];
                                $serv = $this->Servicios_model->leer_servicios($param_serv);
                                $serv[0]['duracion'];
                                // ... Si la duracion es 10min o 20min lo ajustamos a multiplos de 15.
                                if ($serv[0]['duracion'] == 10) {
                                    $serv[0]['duracion'] = 15;
                                }
                                if ($serv[0]['duracion'] == 20) {
                                    $serv[0]['duracion'] = 30;
                                }
                                $param['duracion'] = $serv[0]['duracion'];
                                $id_cita_creada = $this->Agenda_model->guardar_cita($param);

                                // ... Copia los datos de la cita en el dietario.
                                if ($id_cita_creada > 0) {
                                    unset($param2);
                                    $param2['id_cita'] = $id_cita_creada;
                                    $r = $this->Dietario_model->copiar_cita($param2);

                                    //27/05/20 una cita para la lista por cada aservicio  ************* 27/05/20 ***************
                                    unset($paramcitas);
                                    $paramcitas['id_cita'] = $id_cita_creada;
                                    $paramcitas['id_cliente'] = $parametros['id_cliente'];
                                    $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                                    // ... Enviamos el email de aviso al cliente
                                    $lugar = "";
                                    if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                                        $lugar = " (dentro del Hotel Nuevo Madrid) ";
                                    }
                                    $to = 'citas@templodelmasaje.com';
                                    $from = "info@templodelmasaje.com";
                                    $asunto = "Nueva Cita N";
                                    $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                                    $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                                    $mensaje = "Hola " . $xnombre . ", ";
                                    $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                                    $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                                    $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";

                                    unset($paramavisos);
                                    $paramavisos['id_cita'] = $citas[0]['id_cita'];
                                    $paramavisos['id_centro'] = $citas[0]['id_centro'];
                                    $paramavisos['centro'] = $citas[0]['nombre_centro'];
                                    $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                                    $paramavisos['cliente'] = $citas[0]['cliente'];
                                    $paramavisos['telefono'] = $citas[0]['telefono'];
                                    $paramavisos['asunto'] = "Modificar Cita";
                                    $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd'];
                                    $paramavisos['mensaje'] = $mensaje;
                                    $g = $this->Agenda_model->grabar_aviso($paramavisos);
                                }
                                $segundos_horaInicial = strtotime($param['hora']);
                                $segundos_minutoAnadir = ($serv[0]['duracion']) * 60;
                                $param['hora'] = date("H:i", $segundos_horaInicial + $segundos_minutoAnadir);
                            }
                        }
                    }
                    // ---------------------------------------
                }
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Modificar una cita
        // ---------------------------------------
        if ($accion == "modificar") {
            $id_cita_creada = 0; //25/05/20 Por un error en Log.
            unset($parametros);

            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if (isset($parametros)) {
                $parametros['id_cita'] = $id_cita;
                if (isset($parametros['id_presupuesto_item']) && count($parametros['id_presupuesto_item']) > 0) {
                    foreach ($parametros['id_presupuesto_item'] as $key => $presupuesto_item) {
                        $explodeitemid = explode('|', $presupuesto_item);
                        $parametros['id_servicio'] = $explodeitemid[0];
                    }
                }
                $ok = $this->Agenda_model->modificar_cita($parametros);

                if (isset($explodeitemid) && isset($explodeitemid[1])) {
                    $id_presupuesto_item = $explodeitemid[1];
                    $this->load->model('Presupuestos_model');
                    //VER SI ALGUN ITEM DE PRESUPUESTO TIENE ESTA CITA RELACIONADA
                    unset($param1);
                    $param1['id_cita'] = $id_cita;
                    $tieneitem = $this->Presupuestos_model->leer_presupuestos_items($param1);
                    if (!empty($tieneitem) && $tieneitem[0]['id_presupuesto_item'] != $id_presupuesto_item) {
                        //EXISTE CITA EN ALGUN ITEM Y EL ID DE ESTE NO ES IGUAL QUE EL QUE LLEGA
                        // SE DESVINCULA EL ITEM
                        $this->Presupuestos_model->actualizar_presupuesto_item_cita_dietario($tieneitem[0]['id_presupuesto_item'], 0, 0);
                        // se vincula al nuevo item
                        $this->Presupuestos_model->actualizar_presupuesto_item_cita_dietario($id_presupuesto_item, $id_cita, $tieneitem[0]['id_dietario']);
                    } else {
                        // RELACIONA LA CITA CON UN ITEM DEL PRESUPUESTO, SI ÉSTE NO TIENE CITA
                        unset($param2);
                        $param2['id_presupuesto_item'] = $id_presupuesto_item;
                        $enpresupuesto = $this->Presupuestos_model->leer_presupuestos_items($param2);
                        if (!empty($enpresupuesto)) {

                            $param2['id_cita'] = $id_cita;
                            $r = $this->Dietario_model->leer($param2);
                            unset($param2);
                            $param2['id_cita'] = $id_cita;
                            $param2['id_presupuesto'] = $enpresupuesto[0]['id_presupuesto'];
                            $param2['importe_euros'] = $enpresupuesto[0]['coste'];
                            if (isset($parametros['codigo_proveedor'])) {
                                $param2['codigo_proveedor'] = $parametros['codigo_proveedor'];
                            }
                            /*if($enpresupuesto[0]['id_cita'] == 0 || !is_array($r)){
                                $id_dietario = $this->Dietario_model->copiar_cita_presupuesto($param2);
                            }else{
                                $id_dietario = $r[0]['id_dietario'];
                                $this->Dietario_model->modificar_cita_presupuesto($id_dietario, $param2);
                            }*/
                            $id_dietario = $r[0]['id_dietario'];
                            $this->Dietario_model->modificar_cita_presupuesto($id_dietario, $param2);
                            $this->Presupuestos_model->actualizar_presupuesto_item_cita_dietario($id_presupuesto_item, $id_cita, $id_dietario);

                        }
                    }
                }

                unset($param);
                $param['id_cita'] = $id_cita;
                $data['cita'] = $this->Agenda_model->leer_citas($param);

                //27/05/20 una cita para la lista por cada aservicio  ************* 27/05/20 ***************
                unset($paramcitas);
                $paramcitas['id_cita'] = $id_cita;
                $paramcitas['id_cliente'] = $parametros['id_cliente'];
                $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                // ... Enviamos el email de aviso al cliente
                $lugar = "";
                if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                    $lugar = " (dentro del Hotel Nuevo Madrid) ";
                }
                $to = 'citas@templodelmasaje.com';
                $from = "info@templodelmasaje.com";
                $asunto = "Nueva Cita N";
                $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                $mensaje = "Hola " . $xnombre . ", ";
                $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";


                //25/05/20 ***************++ grabar avisos **************+++
                unset($paramavisos);
                $paramavisos['id_cita'] = $citas[0]['id_cita'];
                $paramavisos['id_centro'] = $citas[0]['id_centro'];
                $paramavisos['centro'] = $citas[0]['nombre_centro'];
                $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                //$paramavisos['cliente']=$citas[0]['nombreprincipal'];
                $paramavisos['cliente'] = $citas[0]['cliente'];
                $paramavisos['telefono'] = $citas[0]['telefono'];
                $paramavisos['asunto'] = "Modificar Cita";
                $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd']; //18/06/20 ahora fecha_creacion será fecha de la cita.
                $paramavisos['mensaje'] = $mensaje;
                $g = $this->Agenda_model->grabar_aviso($paramavisos);
                //Fin de grabar avisos 25/05/20


                //Fin 27/05/20


                unset($param);
                $param['id_cita'] = $id_cita;
                if (isset($parametros['codigo_proveedor'])) {
                    $param['codigo_proveedor'] = $parametros['codigo_proveedor'];
                }


                $ok = $this->Dietario_model->modificar_dietario_cita($param);


                $param_serv['id_servicio'] = $parametros['id_servicio'];
                $serv = $this->Servicios_model->leer_servicios($param_serv);

                // ... Si la duracion es 10min o 20min lo ajustamos a multiplos de 15.
                if ($serv[0]['duracion'] == 10) {
                    $serv[0]['duracion'] = 15;
                }
                if ($serv[0]['duracion'] == 20) {
                    $serv[0]['duracion'] = 30;
                }
                $param['duracion'] = $serv[0]['duracion'];
                $param['id_servicio'] = $parametros['id_servicio'];
                $param['id_empleado'] = $parametros['id_empleado'];
                $param['id_cliente'] = $parametros['id_cliente'];
                $param['fecha'] = $parametros['fecha'];
                $param['hora'] = $parametros['hora'];
                $param['observaciones'] = $parametros['observaciones'];
                if (isset($parametros['solo_este_empleado'])) {
                    $param['solo_este_empleado'] = $parametros['solo_este_empleado'];
                }
                if (isset($parametros['recordatorio_sms'])) {
                    $param['recordatorio_sms'] = $parametros['recordatorio_sms'];
                }
                if (isset($parametros['recordatorio_email'])) {
                    $param['recordatorio_email'] = $parametros['recordatorio_email'];
                }


                $segundos_horaInicial = strtotime($param['hora']);
                $segundos_minutoAnadir = ($serv[0]['duracion']) * 60;
                $param['hora'] = date("H:i", $segundos_horaInicial + $segundos_minutoAnadir);

                for ($i = 2; $i < 7; $i++) {
                    $item = "id_servicio" . $i;
                    if (isset($parametros[$item])) {
                        if ($parametros[$item] > 0) {
                            $param['id_servicio'] = $parametros[$item];

                            unset($param_serv);
                            $param_serv['id_servicio'] = $parametros[$item];
                            $serv = $this->Servicios_model->leer_servicios($param_serv);
                            $serv[0]['duracion'];
                            // ... Si la duracion es 10min o 20min lo ajustamos a multiplos de 15.
                            if ($serv[0]['duracion'] == 10) {
                                $serv[0]['duracion'] = 15;
                            }
                            if ($serv[0]['duracion'] == 20) {
                                $serv[0]['duracion'] = 30;
                            }
                            $param['duracion'] = $serv[0]['duracion'];

                            #echo $param['id_servicio']." - ".$param['fecha']." - ".$param['hora']."-".$serv[0]['duracion']."<br>";

                            $id_cita_creada = $this->Agenda_model->guardar_cita($param);

                            // ... Copia los datos de la cita en el dietario.
                            if ($id_cita_creada > 0) {
                                unset($param2);
                                $param2['id_cita'] = $id_cita_creada;
                                $r = $this->Dietario_model->copiar_cita($param2);

                                //27/05/20 una cita para la lista por cada aservicio  ************* 27/05/20 ***************
                                unset($paramcitas);
                                $paramcitas['id_cita'] = $id_cita_creada;
                                $paramcitas['id_cliente'] = $parametros['id_cliente'];
                                $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                                // ... Enviamos el email de aviso al cliente
                                $lugar = "";
                                if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                                    $lugar = " (dentro del Hotel Nuevo Madrid) ";
                                }
                                $to = 'citas@templodelmasaje.com';
                                $from = "info@templodelmasaje.com";
                                $asunto = "Nueva Cita N";
                                $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                                $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                                $mensaje = "Hola " . $xnombre . ", ";
                                $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                                $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                                $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";


                                //25/05/20 ***************++ grabar avisos **************+++
                                unset($paramavisos);
                                $paramavisos['id_cita'] = $citas[0]['id_cita'];
                                $paramavisos['id_centro'] = $citas[0]['id_centro'];
                                $paramavisos['centro'] = $citas[0]['nombre_centro'];
                                $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                                //$paramavisos['cliente']=$citas[0]['nombreprincipal'];
                                $paramavisos['cliente'] = $citas[0]['cliente'];
                                $paramavisos['telefono'] = $citas[0]['telefono'];
                                $paramavisos['asunto'] = "Modificar Cita";
                                $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd']; //18/06/20 ahora fecha_creacion será fecha de la cita.
                                $paramavisos['mensaje'] = $mensaje;
                                $g = $this->Agenda_model->grabar_aviso($paramavisos);
                                //Fin de grabar avisos 25/05/20


                                //Fin 27/05/20


                            }

                            $segundos_horaInicial = strtotime($param['hora']);
                            $segundos_minutoAnadir = ($serv[0]['duracion']) * 60;
                            $param['hora'] = date("H:i", $segundos_horaInicial + $segundos_minutoAnadir);
                        }
                    }
                }
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Anular una cita
        // ---------------------------------------
        if ($accion == "anular") {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;


            if (isset($parametros)) {
                unset($param);
                $param['id_cita'] = $id_cita;
                //27/05/20 Ocultar borrado=0 los avisos de la cita
                $ok = $this->Agenda_model->ocultar_citas_avisos($param);
                //Fin

                $param['estado'] = "Anulada";
                $param['observaciones'] = $parametros['observaciones'];

                $ok = $this->Agenda_model->cambio_estado_cita($param);

                $ok = $this->Dietario_model->cambio_estado_dietario($param);
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Finalizar una cita
        // ---------------------------------------
        if ($accion == "finalizar") {
            unset($parametros);
            $parametros = $_POST;
            if (isset($parametros)) {
                $this->load->model('Presupuestos_model');
                $param2['id_cita'] = $id_cita;
                $enpresupuesto = $this->Presupuestos_model->leer_presupuestos_items($param2);

                if (!empty($enpresupuesto)) {
                    //revisar que el presupuesto tenga saldo
                    $this->load->model('Presupuestos_model');
                    $this->load->model('Liquidaciones_model');
                    $param5['id_presupuesto'] = $enpresupuesto[0]['id_presupuesto'];
                    $pre = $this->Presupuestos_model->leer_presupuestos($param5);
                    // buscar el item del presupuesto para obtener su coste
                    $saldo_disponible = ($pre[0]['es_repeticion'] == 1) ? $enpresupuesto[0]['coste'] : $pre[0]['total_pagado'] - $pre[0]['total_gastado'];

                    if ($saldo_disponible >= $enpresupuesto[0]['coste']) {
                        unset($param);
                        $param['id_cita'] = $id_cita;
                        $ok = $this->Agenda_model->ocultar_citas_avisos($param);
                        $param['estado'] = "Finalizado";
                        $param['observaciones'] = $parametros['observaciones'];
                        $ok = $this->Agenda_model->cambio_estado_cita($param);
                        // actualizar dietario y poner como presupuesto, si no lo está ya

                        unset($param2);
                        $param2['id_dietario'] = $enpresupuesto[0]['id_dietario'];

                        $r = $this->Dietario_model->leer($param2);
                        /*if(isset($r) && count($r) == 1 && $r[0]['estado'] != 'Presupuesto'){
                            unset($param2);
                            $param2['id_cita'] = $id_cita;
                            $param2['id_presupuesto'] = $enpresupuesto[0]['id_presupuesto'];
                            $param2['importe_euros'] = $enpresupuesto[0]['coste'];
                            if (isset($parametros['codigo_proveedor'])) {
                                $param2['codigo_proveedor'] = $parametros['codigo_proveedor'];
                            }
                            $param2['estado'] = 'Presupuesto';
                            $id_dietario = $r[0]['id_dietario'];
                            $this->Dietario_model->modificar_cita_presupuesto($id_dietario, $param2);
                        }
                        */

                        unset($param);
                        $param['marcados'] = [$r[0]['id_dietario']];
                        $param['importe_euros'] = [$r[0]['importe_euros']];
                        $param['descuento_euros'] = [$r[0]['descuento_euros']];
                        $param['descuento_porcentaje'] = [$r[0]['descuento_porcentaje']];
                        $param['tipo_pago'] = '#Presupuesto';
                        $param['pagado_efectivo'] = 0;
                        $param['pagado_tarjeta'] = 0;
                        $param['pagado_tpv2'] = 0;
                        $param['pagado_paypal'] = 0;
                        $param['pagado_transferencia'] = 0;
                        $param['pagado_habitacion'] = 0;
                        $param['pagado_habitacion'] = 0;
                        $param['notas_pago_descuento'] = '';

                        $ok = $this->Dietario_model->marcar_pagado($param);


                        $this->Liquidaciones_model->liquidacion_cita($id_cita);
                        $data['id_cita_reload'] = $id_cita;
                    } else {
                        $this->load->view('agenda/agenda_mensaje_view', ['mensaje' => 'No hay saldo suficiente en el presupuesto para finalizar esta cita']);
                        return;

                    }
                }
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... No vino a una cita
        // ---------------------------------------
        if ($accion == "no_vino") {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;


            if (isset($parametros)) {
                unset($param);
                $param['id_cita'] = $id_cita;
                //27/05/20 Ocultar borrado=0 los avisos de la cita
                $ok = $this->Agenda_model->ocultar_citas_avisos($param);
                //Fin

                $param['estado'] = "No vino";
                $param['observaciones'] = $parametros['observaciones'];

                $ok = $this->Agenda_model->cambio_estado_cita($param);

                $ok = $this->Dietario_model->cambio_estado_dietario($param);
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Si el id_cita es igual a -99 entonces creamos un nuevo cliente.
        // ---------------------------------------
        if ($id_cita == -99) {
            unset($parametros);
            $parametros['nombre'] = $_POST['nombre'];
            $parametros['apellidos'] = $_POST['apellidos'];
            $parametros['telefono'] = $_POST['telefono'];
            if (isset($_POST['no_quiere_publicidad'])) {
                $parametros['no_quiere_publicidad'] = $_POST['no_quiere_publicidad'];
            }
            if (isset($_POST['como_conocio']) && $_POST['como_conocio'] !== '-1') {
                $parametros['como_conocio'] = $_POST['como_conocio'];
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
        }

        if (isset($data['cita'][0]['fecha_inicio_aaaammdd'])) {
            $data['fecha_completa'] = $this->Utiles_model->fecha_completa($data['cita'][0]['fecha_inicio_aaaammdd']);
        } else {
            $data['fecha_completa'] = "";
        }

        // ---------------------------------------
        // ... Leemos los posibles empleados que pueden tener citas.
        // ---------------------------------------
        unset($parametros);
        //$parametros['solo_empleados']=1;
        $parametros['solo_empleados_con_horarios'] = 1;
        if ($fecha_cita != null) {
            $parametros['fecha_agenda'] = $fecha_cita;
        } else {
            if (isset($_POST['fecha'])) {
                $pos = strpos($_POST['fecha'], "/");
                if (!($pos === false)) {
                    $partes = explode("/", $_POST['fecha']);
                    $parametros['fecha_agenda'] = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
                } else {
                    $parametros['fecha_agenda'] = $_POST['fecha'];
                }
            } else if (isset($data['cita'][0]['fecha_inicio_aaaammdd'])) {
                $parametros['fecha_agenda'] = $data['cita'][0]['fecha_inicio_aaaammdd'];
            } else {
                // ... Sino hay fecha ponemos esta para que la variable llegue con algo.
                $parametros['fecha_agenda'] = "1970-01-01";
            }
        }
        // ... controlamos que el perfil sea el master,
        // sino solo mostramos lo del centro que
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $parametros['fecha_agenda'] = str_replace("/", "-", $parametros['fecha_agenda']);
        $var = $parametros['fecha_agenda']; // esto es para asegurar que el formato es aaaa-mm-dd
        $parametros['fecha_agenda'] = date('Y-m-d', strtotime($var));

        //24/06/20 Porque cuando regstran un usuario nuevo la fecha_agenda va con 1970-01-01 línea 1258
        if ($id_cita == -99) {
            $parametros['fecha_agenda'] = date('Y-m-d');
            //26/06/20 Para que regrese el Empleado, fecha y Hora previamente Seleccionado desde la Vista Agenda, pero es un usuario recien creado
            $data['cita'][0]['id_usuario_empleado'] = $_POST['modal_id_usuario_empleado'];
            $data['cita'][0]['hora_inicio'] = $_POST['modal_hora_inicio'];
            $data['cita'][0]['fecha_inicio_aaaammdd'] = $_POST['modal_fecha_inicio'];
            $data['quelleva'] = 'Empleado: ' . $_POST['modal_id_usuario_empleado'] . ' Hora: ' . $_POST['modal_hora_inicio'] . ' Fecha ' . $_POST['modal_fecha_inicio'];

            unset($param);
            $param['id_usuario'] = $_POST['modal_id_usuario_empleado'];
            $data['elempleado'] = $this->Usuarios_model->busqueda_id_usuario($param);

            $parametros['solo_empleados_con_horarios'] = 1;
            if ($this->session->userdata('id_perfil') > 0) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
        }
        //24/06/20 Provisional para ver que valor lleva la fecha_agenda
        $data['xfecha_agenda'] = $parametros['fecha_agenda'];

        //Fin

        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);


        // ---------------------------------------
        // ---------------------------------------

        //
        // Notas del cliente elegido
        //
        if (isset($data['cliente_elegido'])) {
            if ($data['cliente_elegido'] > 0) {
                unset($param_notas);
                $param_notas['id_cliente'] = $data['cliente_elegido'][0]['id_cliente'];
                $param_notas['estado'] = "Pendiente";
                $data['notas_citas'] = $this->Clientes_model->notas_citas($param_notas);
            }
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 12);
        if ($permiso) {
            $this->load->view('agenda/agenda_citas_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    //25/05/20  ********************** Citas Avisos *********************
    function leer_avisos_citas($id_centro = null, $accion = null, $id_cita = null)
    {
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);
        unset($parametros);
        if ($id_centro != null) {
            if ($id_centro != 99) {
                $parametros['id_centro'] = $id_centro;
                $data['id_centro'] = $id_centro;
            } else {
                $parametros['vacio'] = "";
            }
        } else {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $parametros['vacio'] = "";
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
                $parametros['id_centro'] = $id_centro;
            }
        }

        $data['accion'] = 3; //Ver Ambos estados: Pendientes y Enviados
        if ($accion == null) //Predeterminado Pendientes
        {
            $parametros['enviado'] = 0;
            $data['accion'] = 0;
        } else {
            if ($accion != 3 or $accion != "3") {
                $parametros['enviado'] = $accion;
                $data['accion'] = $accion;
            }
        }

        //$data['registros'] = $this->Agenda_model->leer_citas_avisos($parametros);
        $this->Agenda_model->marcar_obsoletos_mes();
        // marcar obsoletos los creados hace mas de un mes

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Avisos de citas';
        $data['content_view'] = $this->load->view('agenda/citas_avisos_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 7);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_leer_avisos_citas($table = null, $columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $campos = [
            'citas_avisos.fecha_creacion',
            'citas_avisos.centro',
            'citas_avisos.cliente',
            'citas_avisos.mensaje',
            'citas_avisos.enviado',
            'citas_avisos.telefono',
            'citas_avisos.id_aviso',
            'citas_avisos.id_cita',
            'citas_avisos.id_centro'
        ];
        $tabla = 'citas_avisos';
        $join = [];
        $add_rule = [];
        $where = ['citas_avisos.borrado' => 0];
        if ($this->input->get('id_centro') != '') {
            $where['citas_avisos.id_centro'] = $this->input->get('id_centro');
        }
        if ($this->input->get('enviado') != '') {
            $where['citas_avisos.enviado'] = $this->input->get('enviado');
        } else {
            $where['citas_avisos.enviado <'] = 2;
        }
        if ($this->input->get('fecha_desde') != '') {
            $where['citas_avisos.fecha_creacion >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != '') {
            $where['citas_avisos.fecha_creacion <='] = $this->input->get('fecha_hasta');
        }
        if (($table != "") && ($columna != "") && ($valor != "")) {
            $where[$table . '.' . $columna] = $valor;
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
        }
        $res = json_encode($result);
        echo $res;
    }

    function cambiar_aviso($id_aviso = null, $estado = null)
    {
        unset($parametros);

        if ($estado == 0) //Estado actual, hay que cambiarlo
            $parametros['enviado'] = 1;
        else
            $parametros['enviado'] = 0;

        $parametros['id_aviso'] = $id_aviso;
        $r = $this->Agenda_model->actualizar_citas_avisos($parametros);
    }

    //Fin 25/05/20 *******************+ Fin Citas Avisos ***********


    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... AGENDA OTROS CENTROS
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function centros($fecha = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        if ($fecha != null) {

            $fecha_ddmmaaaa = date('d-m-Y', strtotime($fecha));
            $fecha_aaaammdd = date('Y-m-d', strtotime($fecha));

            if ($fecha_aaaammdd < date('Y-m-d')) {
                echo "FECHA NO VÄLIDA (El padasdo, pasado está)";
                exit();
            }
        } else {
            $fecha_ddmmaaaa = date("d-m-Y");
            $fecha_aaaammdd = date("Y-m-d");
            $fecha = date("Y-m-d");
        }

        $data['fecha'] = $fecha;

        if ($id_centro == null) {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }
        $data['id_centro'] = $id_centro;

        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);

        // ... Leemos todas las citas
        unset($parametros);
        $parametros['programadas_finalizadas'] = 1;
        $parametros['fecha'] = $fecha_ddmmaaaa;
        $parametros['id_centro'] = $id_centro;
        $data['citas_agenda'] = $this->Agenda_model->leer_citas($parametros);

        // ... Leemos todos los empleados del centro.
        unset($parametros);
        $parametros['solo_empleados_con_horarios'] = 1;
        $parametros['fecha_agenda'] = $fecha;
        $parametros['id_centro'] = $id_centro;
        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

        // ... Leemos horarios de trabajo de cada empleado.
        if ($data['empleados'] != 0) {
            foreach ($data['empleados'] as $key => $row) {
                unset($parametros);
                $parametros['id_usuario'] = $row['id_usuario'];
                $parametros['fecha'] = $fecha_aaaammdd;
                $data['horarios'][$row['id_usuario']] = $this->Horarios_model->leer_horarios($parametros);
            }
        }

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Centros';
        $data['content_view'] = $this->load->view('agenda/agenda_centros_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master_agenda', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... MODIFICAR LA DURACION AL ARRASTRAS LA CITA EN EL CALENDARIO
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function modificar_duracion()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Si hay permiso realizamos la accion
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 12);
        if ($permiso) {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;


            $parametros['fecha_fin_nueva'] = substr($parametros['fecha_fin_nueva'], 11, 5);

            unset($param);
            $param['id_cita'] = $parametros['id_cita'];
            $cita = $this->Agenda_model->leer_citas($param);

            $nueva_duracion = (strtotime($parametros['fecha_fin_nueva']) - strtotime($cita[0]['hora_inicio'])) / 60;

            unset($param);
            $param['id_cita'] = $parametros['id_cita'];
            $param['duracion'] = $nueva_duracion;

            $this->Agenda_model->cambio_duracion_cita($param);

            exit;
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... MODIFICAR CITA DE EMPLEADO Y DE FECHA
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function modificar_empleado_fecha()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Si hay permiso realizamos la accion
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);
        if ($permiso) {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;


            $fecha_inicio = substr($parametros['fecha_inicio_nueva'], 0, 10);
            $hora_inicio = substr($parametros['fecha_inicio_nueva'], 11, 8);

            // ... Actualizamos la cita
            unset($param);
            $param['id_cita'] = $parametros['id_cita'];
            $param['fecha_hora_inicio'] = $fecha_inicio . " " . $hora_inicio;
            $param['id_usuario_empleado'] = $parametros['id_empleado_nuevo'];


            //08/05/20 Si permanece fecha y Hora (solo cambia el empleado)  no debe enviar el correo, por ello y que saber que valores tiene antes de actualizar
            unset($paramcitas);
            $paramcitas['id_cita'] = $parametros['id_cita'];
            $citas = $this->Agenda_model->leer_citas_correo($paramcitas);
            $enviar_correo = "si";
            $fechahoracita = $citas[0]['fecha_inicio_aaaammdd'] . " " . $citas[0]['hora_inicio'];
            if ($param['fecha_hora_inicio'] == $fechahoracita)
                $enviar_correo = "no";
            //Fin

            $this->Agenda_model->cambio_empleado_fecha_cita($param);


            //07/05/20 Correo a citas@templodelmasaje.com   ****************  Correo a citas@templodelmasaje.com 07/05/20 ***
            if ($parametros['id_cita'] > 0 and $enviar_correo == "si") {
                unset($paramcitas);
                $paramcitas['id_cita'] = $parametros['id_cita'];
                //$paramcitas['id_cliente']=$parametros['id_cliente'];
                $citas = $this->Agenda_model->leer_citas_correo($paramcitas);

                // ... Enviamos el email de aviso al cliente
                $to = 'citas@templodelmasaje.com';
                $from = "info@templodelmasaje.com";
                $asunto = "Modificar Cita B";
                $lugar = "";
                if ($citas[0]['id_centro'] == 9) { //Arturo Soria
                    $lugar = " (dentro del Hotel Nuevo Madrid) ";
                }
                $xnombre = ucwords(strtolower($citas[0]['nombreprincipal']));
                $fecha_completa = $this->Utiles_model->fecha_completa($citas[0]['fecha_inicio_aaaammdd']);
                $mensaje = "Hola " . $xnombre . ", ";
                $mensaje .= "confirmamos tu cita el " . $fecha_completa . " a las " . $citas[0]['hora_inicio'];
                $mensaje .= " para el servicio " . $citas[0]['nombre_servicio'] . " en Templo del Masaje " . $citas[0]['nombre_centro'] . $lugar;
                $mensaje .= ". Por favor, lee la política de cancelación de citas: https://www.templodelmasaje.com/politica-de-cancelacion/";
                //$mensaje.="Cliente ".$citas[0]['cliente'].", ".$citas[0]['telefono'].".";

                //$this->Utiles_model->enviar_email($to,$from,$asunto,$mensaje);

                //25/05/20 ***************++ grabar avisos **************+++
                unset($paramavisos);
                $paramavisos['id_cita'] = $citas[0]['id_cita'];
                $paramavisos['id_centro'] = $citas[0]['id_centro'];
                $paramavisos['centro'] = $citas[0]['nombre_centro'];
                $paramavisos['id_cliente'] = $citas[0]['id_cliente'];
                //$paramavisos['cliente']=$citas[0]['nombreprincipal'];
                $paramavisos['cliente'] = $citas[0]['cliente'];
                $paramavisos['telefono'] = $citas[0]['telefono'];
                $paramavisos['asunto'] = "Modificar Cita";
                $paramavisos['fecha_cita'] = $citas[0]['fecha_inicio_aaaammdd']; //18/06/20 ahora fecha_creacion será fecha de la cita.
                $paramavisos['mensaje'] = $mensaje;
                $g = $this->Agenda_model->grabar_aviso($paramavisos);
                //Fin de grabar avisos 25/05/20

            }
            //Fin del correo a citas@templodelmasaje.com


            // ... Actualizamos el dietario
            unset($param);
            $param['id_cita'] = $parametros['id_cita'];
            $ok = $this->Dietario_model->modificar_dietario_cita($param);

            exit;
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... COMPROBAMOS LAS CAPACIDADES DEL EMPLEADO
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function comprobar_capacidad_empleado()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Si hay permiso realizamos la accion
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);

        if ($permiso) {
            unset($parametros);
            ////while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;


            // ... Leemos la cita
            unset($param);
            $param['id_cita'] = $parametros['id_cita'];
            $cita = $this->Agenda_model->leer_citas($param);

            // ... comprobamos si para el empleado de destino tiene la capacidad
            unset($param);
            $param['id_servicio'] = $cita[0]['id_servicio'];
            $param['id_usuario_empleado'] = $parametros['id_empleado_nuevo'];
            $puede = $this->Capacidades_model->empleado($param);

            if ($puede == "1") {
                echo "1";
                exit;
            } else {
                echo "0";
                exit;
            }
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // ... Servicio web para aplicacion de clientes.
    //
    function horas_libres_empleado($personas = null)
    {
        header('Access-Control-Allow-Origin: ' . RUTA_CITAS_ONLINE);

        $this->load->helper(array('form', 'url'));

        $empleados_medaigual = "";
        $id_empleado_medaigual = "";

        if ($personas == 2) {
            $parametros['id_empleado'] = set_value('id_empleado2');
            $parametros['fecha'] = set_value('fecha2');
            $parametros['servicios'] = set_value('servicios2');
            $parametros['duracion'] = 0;

            if ($this->input->post('id_empleado_medaigual') != "") {
                $id_empleado_medaigual = $this->input->post('id_empleado_medaigual');
            }
        } else {
            $parametros['id_empleado'] = set_value('id_empleado');
            $parametros['fecha'] = set_value('fecha');
            $parametros['servicios'] = set_value('servicios');
            $parametros['duracion'] = 0;
        }

        // ... Si el empleado es igual 0 entonce se eligio me da igual
        // entonces extraemos los empleados para el centro, servicios y fecha indicados.
        if ($parametros['id_empleado'] != "") {
            if ($parametros['id_empleado'] == 0) {
                $empleados_medaigual = $this->empleados_medaigual($parametros['servicios'], $this->input->post('id_centro'), $parametros['fecha'], $id_empleado_medaigual);
            }
        }

        $sw = 0;
        $resultado = "";

        if (isset($parametros['servicios'])) {
            $servicios = $parametros['servicios'];

            if (is_array($servicios) && count($servicios) > 0) {
                foreach ($servicios as $s) {
                    $param_serv['id_servicio'] = $s;
                    $servicio = $this->Servicios_model->leer_servicios($param_serv);
                    $parametros['duracion'] += $servicio[0]['duracion'];
                }
            }
        } else {
            $resultado = "<option value='' selected>Sin horas</option>";

            echo $resultado;
            exit;
        }

        if ($parametros['fecha'] === "") {
            $resultado = "<option value='' selected>Sin horas</option>";

            echo $resultado;
            exit;
        }
        if ($parametros['id_empleado'] === "") {
            $resultado = "<option value='' selected>Sin horas</option>";

            echo $resultado;
            exit;
        }

        // ... Elimino los servicios porque no hace falta pasarlos
        // en el calculo de horas libres.
        unset($parametros['servicios']);

        $datos = array();

        // ... Si es para un empleado concreto.
        if ($empleados_medaigual == "") {
            $datos = $this->Agenda_model->horas_libres($parametros);
        }
        // ... Si se ha elegido da igual, entonces
        // calculo las horas libres de cada empleado posible
        else {
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

        $sw = 0;
        $resultado .= "<option value='' selected>Elegir...</option>";

        if ($datos != 0) {
            foreach ($datos as $row) {
                $id = $row;
                $text = $row;
                $resultado .= "<option value='$id'>$text</option>";
                $sw = 1;
            }
        }

        if ($sw == 0) {
            $resultado = "<option value='' selected>Sin horas</option>";
        }

        echo $resultado;

        exit;
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

        if ($empleados != 0) {

            foreach ($empleados as $row) {
                $r .= $row['id_empleado'] . ";";
            }
            $r = substr($r, 0, -1);
        }

        return $r;
    }

    function citas_pacientes()
    {
        // ... Comprobamos la sesion del capacidad
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        /*if ($this->input->post('id_centro') == '') {
            if ($this->session->userdata('id_centro_usuario') == 1) {
                $id_centro = 6;
            } else {
                $id_centro = $this->session->userdata('id_centro_usuario');
            }
        }else{
            $id_centro = $this->input->post('id_centro');
        }
        $data['id_centro'] = $id_centro;*/
        // ... Leemos todos los centros disponibles para filtrar.
        // ... Leemos todos los centros disponibles para filtrar.
        unset($param);
        $param['vacio'] = "";
        $data['centros_todos'] = $this->Usuarios_model->leer_centros($param);
        unset($param);
        $param['vacio'] = "";
        if ($this->input->post('fecha_desde') != '') {
            $param['fecha_desde'] = $this->input->post('fecha_desde');
            $data['fecha_desde'] = $this->input->post('fecha_desde');
        }
        if ($this->input->post('fecha_hasta') != '') {
            $param['fecha_hasta'] = $this->input->post('fecha_hasta');
            $data['fecha_hasta'] = $this->input->post('fecha_hasta');
        }
        if ($this->input->post('id_cliente') != '') {
            $param['id_cliente'] = $this->input->post('id_cliente');
            $data['id_cliente'] = $this->input->post('id_cliente');
            $data['cliente'] = $this->Clientes_model->leer_clientes($param);
        }
        if ($this->input->post('buscar') != '') {
            //$param['id_centro'] = $id_centro;
            $data['citas'] = $this->Agenda_model->leer_citas($param);
            //printr($this->db->last_query());
        }


        // ... Viewer con el contenido
        $data['pagetitle'] = 'Citas de paciente';
        $data['content_view'] = $this->load->view('agenda/citas_paciente_view', $data, true);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master_agenda', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function marcar_en_sala()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // ... Si hay permiso realizamos la accion
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 11);
        if ($permiso) {
            $ok = $this->Agenda_model->marcar_en_sala($_POST['id_cita']);
            if ($ok == "1") {
                echo "1";
                exit;
            } else {
                echo "0";
                exit;
            }
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
}
