<?php
class Caja extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... CAJA
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "";
        $data['registros'] = $this->Caja_model->leer_caja($parametros);

       // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('caja_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 15);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function cierre($accion = null, $fecha = null)
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($fecha == null) {
            $fecha = date("Y-m-d");
        }
        $data['fecha'] = $fecha;

        // ... tengo que pintar algo en pantalla porque sino me hace cosas raras con la sesion
        // del tema de las oportunidades de cerrar caja. de momento lo dejo así que parece que va.
        echo "&nbsp;";

        $param['fecha'] = $fecha;
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $saldo_actual_efectivo = $this->Caja_model->caja_saldo_actual_efectivo($param);
        $saldo_actual_tarjeta = $this->Caja_model->caja_saldo_actual_tarjeta($param);
        $saldo_actual_transferencia = $this->Caja_model->caja_saldo_actual_transferencia($param);
        $saldo_actual_tpv2 = $this->Caja_model->caja_saldo_actual_tpv2($param); 
        $saldo_actual_habitacion = $this->Caja_model->caja_saldo_actual_habitacion($param);
        $saldo_actual_paypal = $this->Caja_model->caja_saldo_actual_paypal($param);
        $saldo_actual_financiado = $this->Caja_model->caja_saldo_actual_financiado($param);

        if ($accion == "comprobar") {
            unset($parametros);
            $parametros = $_POST;
            // ... Calculamos el importe total de lo marcado por el empleado.
            $total_efectivo = 0;
            $total_efectivo += ($parametros['50_euros'] * 50);
            $total_efectivo += ($parametros['20_euros'] * 20);
            $total_efectivo += ($parametros['10_euros'] * 10);
            $total_efectivo += ($parametros['5_euros'] * 5);
            $total_efectivo += ($parametros['2_euros'] * 2);
            $total_efectivo += ($parametros['1_euros'] * 1);
            $total_efectivo += ($parametros['50_cents'] * 0.50);
            $total_efectivo += ($parametros['20_cents'] * 0.20);
            $total_efectivo += ($parametros['10_cents'] * 0.10);
            $total_efectivo += ($parametros['5_cents'] * 0.05);
            $total_efectivo += ($parametros['2_cents'] * 0.02);
            $total_efectivo += ($parametros['1_cents'] * 0.01);
            $total_efectivo = round($total_efectivo, 2);

            //
            // ... Guardamos las monedas indicadas, el intento y el total.
            //
            $parametros['total_efectivo'] = $total_efectivo;
            $parametros['oportunidad_cuadre'] = $this->session->userdata('oportunidad_cuadre'); //($this->session->userdata('oportunidad_cuadre') + 1);
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            $saldo_actual_efectivo = $this->Caja_model->caja_guardar_efectivo($parametros);

            $total_tarjeta = 0;
            $total_tarjeta = round($parametros['tarjeta'], 2);
      
            $total_transferencia = 0;
            $total_transferencia = round($parametros['transferencia'], 2);
           
            $total_tpv2 = 0;
            $total_tpv2 = round($parametros['tpv2'], 2);

            $total_paypal = 0;
            $total_paypal = round($parametros['paypal'], 2);

            $total_financiado = 0;
            $total_financiado = round($parametros['financiado'], 2);

            $total_habitacion = 0;
            $total_habitacion = round($parametros['habitacion'], 2);

            $total = round(($total_efectivo + $total_tarjeta + $total_habitacion + $total_transferencia + $total_tpv2 + $total_paypal + $total_financiado), 2);

            // ... Leemos el saldo actual de la caja
            unset($param);
            $param['fecha'] = $fecha;
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
            $saldo_actual_efectivo = $this->Caja_model->caja_saldo_actual_efectivo($param);
            $saldo_actual_tarjeta = $this->Caja_model->caja_saldo_actual_tarjeta($param);
            $saldo_actual_transferencia = $this->Caja_model->caja_saldo_actual_transferencia($param); 
            $saldo_actual_tpv2 = $this->Caja_model->caja_saldo_actual_tpv2($param); 
            $saldo_actual_habitacion = $this->Caja_model->caja_saldo_actual_habitacion($param);
            $saldo_actual_paypal = $this->Caja_model->caja_saldo_actual_paypal($param); 
            $saldo_actual_financiado = $this->Caja_model->caja_saldo_actual_financiado($param); 

            // ... La caja cuadra correctamente
            if (($total_efectivo == $saldo_actual_efectivo) && ($total_tarjeta == $saldo_actual_tarjeta) && ($total_habitacion == $saldo_actual_habitacion) && ($total_transferencia == $saldo_actual_transferencia) && ($total_tpv2 == $saldo_actual_tpv2) && ($total_paypal == $saldo_actual_paypal) && ($total_financiado == $saldo_actual_financiado)) {
               
                $data['estado_1'] = 1;
                $data['cuadre'] = $total;

                $this->session->set_userdata("oportunidad_cuadre", 0);

                $data['oportunidad_cuadre'] = $this->session->userdata('oportunidad_cuadre');

                // ... Cierre de caja con el cuadre correcto.
                unset($param);
                $param['id_centro'] = $this->session->userdata('id_centro_usuario');
                $saldo_inicial_caja = $this->Caja_model->saldo_inicial($param);

                unset($param2);
                $param2['saldo_inicial'] = $saldo_inicial_caja;
                $param2['saldo_cierre_efectivo'] = $saldo_actual_efectivo;
                $param2['saldo_cierre_tarjeta'] = $saldo_actual_tarjeta;
                $param2['saldo_cierre_transferencia'] = $saldo_actual_transferencia;
                $param2['saldo_cierre_tpv2'] = $saldo_actual_tpv2;
                $param2['saldo_cierre_habitacion'] = $saldo_actual_habitacion;
                $param2['saldo_cierre_paypal'] = $saldo_actual_paypal;
                $param2['saldo_cierre_financiado'] = $saldo_actual_financiado;
                $param2['descuadre_efectivo'] = 0;
                $param2['descuadre_tarjeta'] = 0;
                $param2['descuadre_transferencia'] = 0;
                $param2['descuadre_tpv2'] = 0; 
                $param2['descuadre_habitacion'] = 0;
                $param2['descuadre_paypal'] = 0; 
                $param2['descuadre_financiado'] = 0;
                $saldo_actual_efectivo = $this->Caja_model->cierre($param2);
            }
            // ... La caja NO cuadra.
            else {
                // ... Efectivo
                if ($total_efectivo > $saldo_actual_efectivo) {
                    $data['estado_2'] = 2;
                }
                if ($total_efectivo < $saldo_actual_efectivo) {
                    $data['estado_3'] = 3;
                }
                $data['cuadre_efectivo'] = ($total_efectivo - $saldo_actual_efectivo);

                // ... Tarjeta
                if ($total_tarjeta > $saldo_actual_tarjeta) {
                    $data['estado_4'] = 4;
                }
                if ($total_tarjeta < $saldo_actual_tarjeta) {
                    $data['estado_5'] = 5;
                }
                $data['cuadre_tarjeta'] = ($total_tarjeta - $saldo_actual_tarjeta);

                // ... Habitacion
                if ($total_habitacion > $saldo_actual_habitacion) {
                    $data['estado_6'] = 6;
                }
                if ($total_habitacion < $saldo_actual_habitacion) {
                    $data['estado_7'] = 7;
                }
                $data['cuadre_habitacion'] = ($total_habitacion - $saldo_actual_habitacion);

                //24/03/20 transferencia
                if ($total_transferencia > $saldo_actual_transferencia) {
                    $data['estado_8'] = 8;
                }
                if ($total_transferencia < $saldo_actual_transferencia) {
                    $data['estado_9'] = 9;
                }
                $data['cuadre_transferencia'] = ($total_transferencia - $saldo_actual_transferencia);

                //02/09/21 TPV""
                if ($total_tpv2 > $saldo_actual_tpv2) {
                    $data['estado_10'] = 10;
                }
                if ($total_tpv2 < $saldo_actual_tpv2) {
                    $data['estado_11'] = 11;
                }
                $data['cuadre_tpv2'] = ($total_tpv2 - $saldo_actual_tpv2);

                // ... Paypal
                if ($total_paypal > $saldo_actual_paypal) {
                    $data['estado_12'] = 12;
                }
                if ($total_paypal < $saldo_actual_paypal) {
                    $data['estado_13'] = 13;
                }
                $data['cuadre_paypal'] = ($total_paypal - $saldo_actual_paypal);
                
                // ... financiado
                if ($total_financiado > $saldo_actual_financiado) {
                    $data['estado_14'] = 14;
                }
                if ($total_financiado < $saldo_actual_financiado) {
                    $data['estado_15'] = 15;
                }
                $data['cuadre_financiado'] = ($total_financiado - $saldo_actual_financiado);



                if ($this->session->userdata('oportunidad_cuadre') < 2) {
                    $oportunidades = $this->session->userdata('oportunidad_cuadre') + 1;

                    $this->session->set_userdata("oportunidad_cuadre", $oportunidades);

                    $data['oportunidad_cuadre'] = $this->session->userdata('oportunidad_cuadre');

                    if ($data['oportunidad_cuadre'] > 1) {
                        ///
                        /// ... Se cierra la caja con el cuadre mal.
                        ///
                        unset($param);
                        $param['id_centro'] = $this->session->userdata('id_centro_usuario');
                        $saldo_inicial_caja = $this->Caja_model->saldo_inicial($param);

                        unset($param2);
                        $param2['saldo_inicial'] = $saldo_inicial_caja;
                        $param2['saldo_cierre_efectivo'] = $saldo_actual_efectivo;
                        $param2['saldo_cierre_tarjeta'] = $saldo_actual_tarjeta;
                        $param2['saldo_cierre_transferencia'] = $saldo_actual_transferencia; 
                        $param2['saldo_cierre_tpv2'] = $saldo_actual_tpv2; 
                        $param2['saldo_cierre_habitacion'] = $saldo_actual_habitacion;
                        $param2['saldo_cierre_paypal'] = $saldo_actual_paypal; 
                        $param2['saldo_cierre_financiado'] = $saldo_actual_financiado;

                        $param2['descuadre_efectivo'] = $data['cuadre_efectivo'];
                        $param2['descuadre_tarjeta'] = $data['cuadre_tarjeta'];
                        $param2['descuadre_transferencia'] = $data['cuadre_transferencia'];
                        $param2['descuadre_tpv2'] = $data['cuadre_tpv2'];
                        $param2['descuadre_habitacion'] = $data['cuadre_habitacion'];
                        $param2['descuadre_paypal'] = $data['cuadre_paypal'];
                        $param2['descuadre_financiado'] = $data['cuadre_financiado'];
                        $saldo_actual_efectivo = $this->Caja_model->cierre($param2);

                        $this->session->set_userdata("oportunidad_cuadre", 0);
                    }
                }
            }

            $data['m50_euros'] = $parametros['50_euros'];
            $data['m20_euros'] = $parametros['20_euros'];
            $data['m10_euros'] = $parametros['10_euros'];
            $data['m5_euros'] = $parametros['5_euros'];
            $data['m2_euros'] = $parametros['2_euros'];
            $data['m1_euros'] = $parametros['1_euros'];
            $data['m50_cents'] = $parametros['50_cents'];
            $data['m20_cents'] = $parametros['20_cents'];
            $data['m10_cents'] = $parametros['10_cents'];
            $data['m5_cents'] = $parametros['5_cents'];
            $data['m2_cents'] = $parametros['2_cents'];
            $data['m1_cents'] = $parametros['1_cents'];

            $data['v50_euros'] = round($parametros['50_euros'] * 50, 2, 1) . " €";
            $data['v20_euros'] = round($parametros['20_euros'] * 20, 2, 1) . " €";
            $data['v10_euros'] = round($parametros['10_euros'] * 10, 2, 1) . " €";
            $data['v5_euros'] = round($parametros['5_euros'] * 5, 2, 1) . " €";
            $data['v2_euros'] = round($parametros['2_euros'] * 2, 2, 1) . " €";
            $data['v1_euros'] = round($parametros['1_euros'] * 1, 2, 1) . " €";
            $data['v50_cents'] = round($parametros['50_cents'] * 0.50, 2, 1) . " €";
            $data['v20_cents'] = round($parametros['20_cents'] * 0.20, 2, 1) . " €";
            $data['v10_cents'] = round($parametros['10_cents'] * 0.10, 2, 1) . " €";
            $data['v5_cents'] = round($parametros['5_cents'] * 0.05, 2, 1) . " €";
            $data['v2_cents'] = round($parametros['2_cents'] * 0.02, 2, 1) . " €";
            $data['v1_cents'] = round($parametros['1_cents'] * 0.01, 2, 1) . " €";

            $data['tarjeta'] = $parametros['tarjeta'];
            $data['transferencia'] = $parametros['transferencia'];
            $data['tpv2'] = $parametros['tpv2']; 
            $data['habitacion'] = $parametros['habitacion'];
            $data['paypal'] = $parametros['paypal']; 
            $data['financiado'] = $parametros['financiado'];
        } else {
            $data['oportunidad_cuadre'] = $this->session->userdata('oportunidad_cuadre');
        }

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 15);
        if ($permiso) {
            $this->load->view('caja/caja_cierre_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function saldocierre($accion = null, $fecha = null)
    {


        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... tengo que pintar algo en pantalla porque sino me hace cosas raras con la sesion
        // del tema de las oportunidades de cerrar caja. de momento lo dejo así que parece que va.
        echo "&nbsp;";

        $param['fecha'] = $fecha;
        $part_fechas = explode("-", $fecha);
        $param['cad1'] = $part_fechas[6];
        $param['cad2'] = $part_fechas[7];

        $fecha_desde = $part_fechas[2] . "-" . $part_fechas[1] . "-" . $part_fechas[0] . " " . $part_fechas[3] . ":" . $part_fechas[4] . ":00";
        $fecha_hasta = $part_fechas[2] . "-" . $part_fechas[1] . "-" . $part_fechas[0] . " " . $part_fechas[3] . ":" . $part_fechas[4] . ":59";

        $param['fecha_desde'] = $fecha_desde;
        $param['fecha_hasta'] = $fecha_hasta;

        if ($fecha == null) {
            $fecha = date("Y-m-d");
        }
        $data['fecha'] = $part_fechas[0] . "-" . $part_fechas[1] . "-" . $part_fechas[2] . " " . $part_fechas[3] . ":" . $part_fechas[4];

        $id_usuario = $this->Usuarios_model->leer_id_usuario_nombre($param);

        if (isset($id_usuario[1])) {
            $paso = false;
            $param['id_usuario'] = $id_usuario[1]['id_usuario'];
            $param['email'] = $id_usuario[1]['email'];
        } else {
            $paso = true;
            $param['id_usuario'] = $id_usuario[0]['id_usuario'];
            $param['email'] = $id_usuario[0]['email'];
        }

        $saldo_cierre_efectivo_caja = $this->Caja_model->leer_cierre_efectivo_guardados($param);

        $saldo_cierre_tarjeta_caja = $this->Caja_model->leer_cierre_tarjeta_guardados($param);

        if (($saldo_cierre_efectivo_caja) != 0) {
        } else {
            if ($paso == false) {
                $param['id_usuario'] = $id_usuario[0]['id_usuario'];
                $param['email'] = $id_usuario[0]['email'];
            } else {
                $param['email'] = $id_usuario[1]['email'];
            }


            $saldo_cierre_efectivo_caja = $this->Caja_model->leer_cierre_efectivo_guardados($param);

            $saldo_cierre_tarjeta_caja = $this->Caja_model->leer_cierre_tarjeta_guardados($param);
        }


        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }

        $data['tarjeta'] = $saldo_cierre_tarjeta_caja[0]['saldo_cierre_tarjeta'];

        $data['m50_euros'] = $saldo_cierre_efectivo_caja[0]['b50_euros'];
        $data['m20_euros'] = $saldo_cierre_efectivo_caja[0]['b20_euros'];
        $data['m10_euros'] = $saldo_cierre_efectivo_caja[0]['b10_euros'];
        $data['m5_euros'] = $saldo_cierre_efectivo_caja[0]['b5_euros'];
        $data['m2_euros'] = $saldo_cierre_efectivo_caja[0]['m2_euros'];
        $data['m1_euros'] = $saldo_cierre_efectivo_caja[0]['m1_euros'];
        $data['m50_cents'] = $saldo_cierre_efectivo_caja[0]['m50_cents'];
        $data['m20_cents'] = $saldo_cierre_efectivo_caja[0]['m20_cents'];
        $data['m10_cents'] = $saldo_cierre_efectivo_caja[0]['m10_cents'];
        $data['m5_cents'] = $saldo_cierre_efectivo_caja[0]['m5_cents'];
        $data['m2_cents'] = $saldo_cierre_efectivo_caja[0]['m2_cents'];
        $data['m1_cents'] = $saldo_cierre_efectivo_caja[0]['m1_cents'];

        $data['v50_euros'] = round($saldo_cierre_efectivo_caja[0]['b50_euros'] * 50, 2, 1) . " €";
        $data['v20_euros'] = round($saldo_cierre_efectivo_caja[0]['b20_euros'] * 20, 2, 1) . " €";
        $data['v10_euros'] = round($saldo_cierre_efectivo_caja[0]['b10_euros'] * 10, 2, 1) . " €";
        $data['v5_euros'] = round($saldo_cierre_efectivo_caja[0]['b5_euros'] * 5, 2, 1) . " €";
        $data['v2_euros'] = round($saldo_cierre_efectivo_caja[0]['m2_euros'] * 2, 2, 1) . " €";
        $data['v1_euros'] = round($saldo_cierre_efectivo_caja[0]['m1_euros'] * 1, 2, 1) . " €";
        $data['v50_cents'] = round($saldo_cierre_efectivo_caja[0]['m50_cents'] * 0.50, 2, 1) . " €";
        $data['v20_cents'] = round($saldo_cierre_efectivo_caja[0]['m20_cents'] * 0.20, 2, 1) . " €";
        $data['v10_cents'] = round($saldo_cierre_efectivo_caja[0]['m10_cents'] * 0.10, 2, 1) . " €";
        $data['v5_cents'] = round($saldo_cierre_efectivo_caja[0]['m5_cents'] * 0.05, 2, 1) . " €";
        $data['v2_cents'] = round($saldo_cierre_efectivo_caja[0]['m2_cents'] * 0.02, 2, 1) . " €";
        $data['v1_cents'] = round($saldo_cierre_efectivo_caja[0]['m1_cents'] * 0.01, 2, 1) . " €";





        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 15);
        if ($permiso) {
            $this->load->view('caja/caja_cierre_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function movimientos($accion = null, $id = null, $fecha = null, $fecha_hasta = null, $id_centro = 0, $tipo_movimiento = 0)
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ... controlamos que el perfil sea el master,
        // sino solo mostramos lo del centro que corresponda.
        if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 4) {
            // ... Leemos todos los centros
            unset($param2);
            $param2['vacio'] = "";
            $data['centros_todos'] = $this->Usuarios_model->leer_centros($param2);
            $data['id_centro'] = $id_centro;
        }

        if ($fecha == null) {
            $fecha = date("Y-m-d");
        }
        if ($fecha_hasta == null) {
            $fecha_hasta = date("Y-m-d");
        }
        $data['fecha'] = $fecha;
        $data['fecha_hasta'] = $fecha_hasta;
        $data['tipo_movimiento'] = $tipo_movimiento;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edición ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id'] = $id;
                $data['registros'] = $this->Caja_model->leer_caja_movimientos($param);
            }


            $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
            $parametrosProveedores = ['obsoleto' => false];
            $data['proveedores'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
            $data['doctores'] = $this->Proveedores_model->getListadoDoctores();


            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo movimiento' : 'Editar movimiento';
            $data['content_view'] = $this->load->view('caja/caja_movimientos_nuevoeditar_view', $data, true);

            // ... Modulos del caja
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 15);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {

            $parametros = $_POST;

            if ($accion == "guardar") {
                $data['estado'] = $this->Caja_model->nuevo_movimiento_caja($parametros);
            } else {
                $parametros['id'] = $id;
                $data['estado'] = $this->Caja_model->actualizar_movimiento_caja($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id'] = $id;
            $data['borrado'] = $this->Caja_model->borrar_movimiento_caja($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar" || $accion == "listado") {
            unset($parametros);
            $parametros['fecha_desde'] = $fecha;
            $parametros['fecha_hasta'] = $fecha_hasta;
            $parametros['tipo_movimiento'] = $tipo_movimiento;
            // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
            // corresponda.
            if ($this->session->userdata('id_perfil') > 0 && $this->session->userdata('id_perfil') != 4) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            } else {
                if ($id_centro > 0) {
                    $parametros['id_centro'] = $id_centro;
                }
            }
			
            $data['registros'] = $this->Caja_model->leer_caja_movimientos($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Movimientos de caja';
            if ($this->session->userdata('id_perfil') > 0 && $this->session->userdata('id_perfil') != 4) {
                $data['actionstitle'] = ['<a href="'.base_url().'caja/movimientos/nuevo" class="btn btn-primary text-inverse-primary">Añadir movimiento</a>'];
            }else{
                $url = base_url().'caja/exportar_movimientos/';
                $url.= (isset($fecha)) ? $fecha.'/' : '/';
                $url.= (isset($fecha_hasta)) ? $fecha_hasta.'/' : '/';
                $url.= (isset($id_centro)) ? $id_centro.'/' : '/';
                $data['actionstitle'] = ['<a href="'.$url.'" class="btn btn-warning text-inverse-warning">Exportar CSV</a>'];
            }
            $data['content_view'] = $this->load->view('caja/caja_movimientos_view', $data, true);

            // ... Modulos del caja
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
			
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 15);
			
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }

    // --------------------------------------------------------------------
    // ....
    // --------------------------------------------------------------------
    function exportar_movimientos($fecha_desde = null, $fecha_hasta = null, $id_centro = null)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Leemos los datos registros.
        unset($parametros);
        $parametros['fecha_desde'] = $fecha_desde;
        $parametros['fecha_hasta'] = $fecha_hasta;
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        } else {
            if ($id_centro > 0) {
                $parametros['id_centro'] = $id_centro;
            }
        }
        $registros = $this->Caja_model->leer_caja_movimientos($parametros);

        $fichero = RUTA_SERVIDOR . "/recursos/movimienos_caja.csv";

        $file = fopen($fichero, "w");

        $linea = "fecha;concepto;importe;empleado;centro\n";
        fwrite($file, $linea);

        if ($registros > 0) {
            foreach ($registros as $row) {
                unset($linea);

                $linea = $row['fecha_creacion_aaaammdd_hhss'] . " " . $row['fecha_creacion_ddmmaaaa'] . ";" . $row['concepto'] . ";" . number_format($row['cantidad'], 2, ',', '.') . ";" . $row['empleado'] . ";" . $row['nombre_centro'] . "\n";

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
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function saldo($accion = null, $fecha = null)
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['vacio'] = "";
        if ($fecha == null) {
            $fecha = date("Y-m-d");
        }
        $data['hoy'] = date("d-m-Y");
        if ($accion == "guardar") {
            unset($parametros);
            foreach ($_POST as $key => $value) {
                $parametros[$key] = $value;
            }

            $data['estado'] = $this->Caja_model->guardar_saldo_inicial($parametros);
        }

        unset($parametros);
        $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        $data['saldo'] = $this->Caja_model->saldo_inicial($parametros);

        unset($parametros);
        $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        $data['saldos_iniciales'] = $this->Caja_model->leer_movimientos_saldos_iniciales($parametros);

        $data['id_usuario_validado'] = $this->session->userdata('id_usuario');
        $data['id_perfil'] = $this->session->userdata('id_perfil');

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Caja: Saldo Inicial';
        $data['content_view'] = $this->load->view('caja/caja_saldo_view', $data, true);

        // ... Modulos del caja
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 16);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function cierres($accion = null, $id = null, $fecha = null)
    {
        // ... Comprobamos la sesion del caja
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        if ($fecha == null) {
            $fecha = date("Y-m-d");
        }
        $data['fecha'] = $fecha;

        // ----------------------------------------------------------------------------- //
        // ... Principal ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "listado") {
            unset($parametros);
            $parametros['fecha'] = $fecha;
            // ... controlamos que el perfil sea el master, sino solo
            // mostramos lo del centro que corresponda.
            if ($this->session->userdata('id_perfil') > 0 && $this->session->userdata('id_perfil') != 4) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $data['registros'] = $this->Caja_model->leer_cierres($parametros);

            // ... Viewer con el contenido
            $data['pagetitle'] = 'Cierres de Caja';
            $data['content_view'] = $this->load->view('caja/caja_cierres_view', $data, true);

            // ... Modulos del caja
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 17);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }
    }
}
