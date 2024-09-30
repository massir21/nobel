<?php
class Clientes extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function alter()
    {
        $this->db->query("ALTER TABLE `clientes` ADD `como_conocio` ENUM('','Redes sociales','Busqueda en Google','Referido','Prensa o radio','Web','Otros medios') NOT NULL AFTER `dni_tutor`;");
    }

    // ----------------------------------------------------------------------------- //
    // ... CLIENTES
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($param);
        $param['buscar'] = "";
        $data['registros'] = 0;
        if (isset($_POST['buscar'])) {
            if (strlen($_POST['buscar']) > 2) {
                $param['buscar'] = $_POST['buscar'];
                $param['ultimo_centro'] = 1;
                $data['registros'] = $this->Clientes_model->leer_clientes($param);
            }
        }

        $data['buscar'] = $param['buscar'];

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de pacientes';
        if ($this->session->userdata('id_perfil') == 3 || $this->session->userdata('id_perfil') == 2 || $this->session->userdata('id_perfil') == 0) {
            $data['actionstitle'][] = '<button type="button" class="btn btn-warning text-inverse-warning" onclick="Fusionar();">Fusionar Pacientes Marcados</button>';
        }
        if($this->session->userdata('id_perfil')==6){
            // CHAINS 20240219 - El doctor no puede crear clientes
        }
        else {
            $data['actionstitle'][] = '<a href="' . base_url() . 'clientes/gestion/nuevo" class="btn btn-primary text-inverse-primary">Nuevo paciente</a>';
        }
        $data['content_view'] = $this->load->view('clientes/clientes_view', $data, true);

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

    public function get_clientes($table = null, $columna = null, $valor = null)
    {
        if ($this->input->get('search')['value'] != '') {
            $this->load->library('Datatable');
            $campos = [
                'clientes.id_cliente',
                '(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
                'clientes.fecha_creacion',
                'centros.nombre_centro',
                'clientes.email',
                'clientes.telefono',
                'clientes.codigo_postal',
                //'DATE_FORMAT(clientes.fecha_creacion,"%d-%m-%Y") as fecha_creacion_ddmmaaaa',
                'clientes.nombre',
                'clientes.apellidos',
            ];
            $tabla = 'clientes';
            $join = [
                'clientes_ultimos_centros' => 'clientes_ultimos_centros.id_cliente = clientes.id_cliente',
                'centros' => 'centros.id_centro = clientes_ultimos_centros.id_centro',
            ];
            $add_rule = [];
            $where = ['clientes.borrado' => 0];

            /*if ($this->input->get('id_cliente') != '') {
                $where['presupuestos.id_cliente'] = $this->input->get('id_cliente');
            }
            if ($this->input->get('id_usuario') != '') {
                $where['presupuestos.id_usuario'] = $this->input->get('id_usuario');
            }
            if ($this->input->get('estado') != '') {
                $where['presupuestos.estado'] = $this->input->get('estado');
            }
            if ($this->input->get('fecha_desde') != '') {
                $where['presupuestos.fecha_creacion >='] = $this->input->get('fecha_desde');
            }
            if ($this->input->get('fecha_hasta') != '') {
                $where['presupuestos.fecha_creacion >='] = $this->input->get('fecha_hasta');
            }
            if ($this->input->get('fecha_validez') != '') {
                $where['presupuestos.fecha_validez >='] = $this->input->get('fecha_validez');
            }*/

            if (($table != "") && ($columna != "") && ($valor != "")) {
                $where[$table . '.' . $columna] = $valor;
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            } else {
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            }
            $res = json_encode($result);
        } else {
            $res = json_encode([
                "draw" => isset($param['draw']) ? $param['draw'] : 1,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
            ]);
        }

        echo $res;
    }

    //21/10/20 ************************** Ficha Salud ****************** Ficha Salud ***************
    function salud($accion = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $parametros = $_POST;

        //
        // ... Guardamos la imagen de la firma, si existe.
        //

        if (isset($parametros['firma_img'])) {
            if ($parametros['firma_img'] != "") {
                $base64_string = $parametros['firma_img'];

                $file = time() . "_firma.png";

                $ifp = fopen(RUTA_SERVIDOR . "/recursos/firmas/" . $file, 'wb');
                // split the string on commas
                // $data[ 0 ] == "data:image/png;base64"
                // $data[ 1 ] == <actual base64 string>
                $data = explode(',', $base64_string);

                // we could add validation here with ensuring count( $data ) > 1
                fwrite($ifp, base64_decode($data[1]));

                // clean up the file resource
                fclose($ifp);

                $parametros['firma_img'] = $file;
            }
        }




        $parametros['id_cliente'] = $id_cliente;

        if ($accion == "nuevo") {
            $data['estado'] = $this->Clientes_model->nueva_ficha($parametros);
            //22/10/20 Recargar
            if ($accion == "nuevo") {
                if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
                    redirect("/clientes/historialpopup/ver/" . $id_cliente);
                }
                redirect("/clientes/historial/ver/" . $id_cliente);
            }
        }

        $data['registros'] = 0;

        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('clientes/clientes_view', $data, true);

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

    function clon($accion = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $parametros = $_POST;




        $parametros['id_cliente'] = $id_cliente;

        if ($accion == "nuevo") {
            $data['estado'] = $this->Clientes_model->nueva_ficha($parametros);
        }
        $data['accion'] = "nuevo";
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('clientes/ficha_salud_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function ver_ficha($id = null, $accion = null)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['accion'] = $accion;

        if ($accion == "actualizar") {
            $param = $_POST;
            $param['id'] = $id;
            $result = $this->Clientes_model->ficha_actualizar($param);
        }

        $parametros['id'] = $id;
        $data['registros'] = $this->Clientes_model->ficha_detalles($parametros);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('clientes/ficha_salud_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //Fin Ficha Salud *******

    function gestion($accion = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edición ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {

            if($this->session->userdata('id_perfil')==6){
                // CHAINS 20240219 - El doctor no puede crear ni editar
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }

            if ($accion == "editar") {
                $param['id_cliente'] = $id_cliente;
                $data['registros'] = $this->Clientes_model->leer_clientes($param);
            }

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo cliente' : 'Editar cliente';
            $data['content_view'] = $this->load->view('clientes/clientes_nuevoeditar_view', $data, true);

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

        // ----------------------------------------------------------------------------- //
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {

            if($this->session->userdata('id_perfil')==6){
                // CHAINS 20240219 - El doctor no puede crear ni editar
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }

            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if ($accion == "guardar") {
                $data['estado'] = $this->Clientes_model->nuevo_cliente($parametros);

                //15/04/20 Crear carnet Único ******************* asignar asignar carnet único **********
                $codigo_carnet = "U" . $data['estado'];
                $id_carnet = "9988" . $data['estado'];
                $id_carnet = intval($id_carnet);
                //echo "no ".$id_carnet." ".$codigo_carnet;
                unset($param2);
                $param2['id_carnet'] = $id_carnet;
                $param2['codigo'] = $codigo_carnet;
                $param2['id_cliente'] = $data['estado'];

                $guardar_codigo = $this->Carnets_model->guardar_carnet_unico($param2);
                //echo " guardar ".$guardar_codigo;
                //Fin *********************** fin ************************* fin *********
            } else {
                /*echo '<pre>';
                print_r($parametros);
                exit();*/

                $parametros['id_cliente'] = $id_cliente;
                $data['estado'] = $this->Clientes_model->actualizar_cliente($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            if($this->session->userdata('id_perfil')==6){
                // CHAINS 20240219 - El doctor no puede crear ni editar
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }

            $parametros['id_cliente'] = $id_cliente;
            $data['borrado'] = $this->Clientes_model->borrar_cliente($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {

            //22/10/20 Recargar
            if ($accion == "actualizar") {
                if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
                    redirect("/clientes/historialpopup/ver/" . $id_cliente);
                }
                $urlredirect="/clientes/historial/ver/" . $id_cliente;
                if(isset($_GET['frompresupuesto'])){
                    $urlredirect.="?frompresupuesto=".$_GET['frompresupuesto'];
                }
                redirect($urlredirect);
            }


            //unset($parametros);
            //$parametros['vacio']="";
            $data['registros'] = 0;

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('clientes/clientes_view', $data, true);

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
    }

    function historial($accion = null, $id_cliente = null, $aleatorio = null, $tipo_consentimiento = null, $id_doctor = null, $descripTrata = null)
    {
        $descripcionTratamiento = '';
        if ($descripTrata) {
            $descripcionTratamiento = str_replace('_', ' ', $descripTrata);
        }
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
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
        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        unset($parametros);
        $parametros['id_cliente'] = $id_cliente;
        $data['registros'] = $this->Clientes_model->leer_clientes($parametros);
        $data['salud'] = $this->Clientes_model->leer_fichas_salud($parametros);
        $data['existe_firma'] = $this->Clientes_model->existe_firma_lopd($id_cliente);

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['debug'] = 1;
        $historial = $this->Dietario_model->leer($param);

        $data['lista_consentimientos'] = $this->Clientes_model->leer_lista_consentimientos($id_cliente);
        $data['lista_subficha'] = $this->Clientes_model->leer_subficha($id_cliente);

        //Changes
        $param['id_usuario'] = $this->session->userdata('id_usuario');
        $data['elempleado'] = $this->Usuarios_model->busqueda_id_usuario($param);
        $idCentro = 1;
        if (array_key_exists('id_centro', $data['elempleado'][0])) {
            $idCentro = $data['elempleado'][0]['id_centro'];
        }
        $data['doctores'] = $this->Clientes_model->leer_doctores($param['id_usuario'], $idCentro);

        // ... Aqui pasamos el historial del dietario para que nos lo devuelva con
        // los numero de carnets usados para cada pago
        unset($param);
        $param['historial'] = $historial;
        $data['historial'] = $this->Dietario_model->carnets_pago_templos($param);
        //$data['historial']=$historial;

        $dientesPorDietario=[];
        if(is_array($data['historial']) && count($data['historial'])){
            foreach($data['historial'] as $entradaDietario){
                $dientesPorDietario[$entradaDietario['id_dietario']]=null;
            }
        }

        if(is_array($data['historial']) && count($data['historial'])) {
            $dientesPorDietario = $this->Dietario_model->getDientesForDietario($dientesPorDietario);
            foreach ($data['historial'] as $key => $unDietario) {
                $data['historial'][$key]['dientes'] = $dientesPorDietario[$unDietario['id_dietario']];
            }
        }




        // ... Leemos el historial de la antigua aplicacion que se usaba, para mostrar
        // la actividad del cliente.
        unset($param);
        $param['nombre'] = $data['registros'][0]['nombre'];
        $param['apellidos'] = $data['registros'][0]['apellidos'];
        $param['telefono'] = $data['registros'][0]['telefono'];
        $data['facturacion_total_antigua'] = $this->Clientes_model->historial_antiguo_facturacion($param);

        $visitas_totales_historicas = $this->Clientes_model->visitas_totales_historicas($param);
        $dias_desde_primera_visita_historicas = $this->Clientes_model->dias_desde_primera_visita_historicas($param);
        if ($dias_desde_primera_visita_historicas > 0) {
            $data['frecuencia_annos_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 365);
            $data['frecuencia_mes_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 30);
            $data['frecuencia_semana_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 7);
        } else {
            $data['frecuencia_annos_historica'] = 0;
            $data['frecuencia_mes_historica'] = 0;
            $data['frecuencia_semana_historica'] = 0;
        }

        // ... Leemos los carnets que tenga ese cliente disponibles.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['carnets'] = $this->Carnets_model->leer($param);
        $data['asociados'] = $this->Carnets_model->leer_asociados_completo($param);

        //
        // OTROS DATOS
        //
        // ... Citas totales
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['citas_totales'] = $this->Clientes_model->citas_totales($param);
        $data['facturacion_total'] = $this->Clientes_model->facturacion_total($param);
        $data['descuentos_total'] = $this->Clientes_model->descuentos_totales_euros($param);
        $data['productos_comprados'] = $this->Clientes_model->numero_productos_comprados($param);

        $facturacion_servicios = $this->Clientes_model->facturacion_servicios_total($param);
        $facturacion_productos = $this->Clientes_model->facturacion_productos_total($param);
        $data['citas_anuladas'] = $this->Clientes_model->citas_anuladas($param);
        $data['citas_no_vino'] = $this->Clientes_model->citas_no_vino($param);
        $data['importe_no_vino'] = $this->Clientes_model->importe_no_vino($param);
        $data['importe_anuladas'] = $this->Clientes_model->importe_anuladas($param);

        $visitas_totales = $this->Clientes_model->visitas_totales($param);
        if ($visitas_totales > 0) {
            $rentabilidad = ($facturacion_servicios + $facturacion_productos - $data['citas_no_vino']) / $visitas_totales;
        } else {
            $rentabilidad = 0;
        }
        $data['rentabilidad'] = $rentabilidad;

        $data['frecuencia_ultimo_anno'] = $this->Clientes_model->visitas_12_meses($param);
        $data['frecuencia_ultimo_mes'] = $this->Clientes_model->visitas_1_mes($param);
        $data['frecuencia_ultimo_semana'] = $this->Clientes_model->visitas_1_semana($param);
        $data['frecuencia_ultimo_3_mes'] = $this->Clientes_model->visitas_3_meses($param);

        $dias_desde_primera_visita = $this->Clientes_model->dias_desde_primera_visita($param);
        if ($dias_desde_primera_visita > 0) {
            $data['frecuencia_annos'] = $visitas_totales / ($dias_desde_primera_visita / 365);
            $data['frecuencia_mes'] = $visitas_totales / ($dias_desde_primera_visita / 30);
            $data['frecuencia_semana'] = $visitas_totales / ($dias_desde_primera_visita / 7);
        } else {
            $data['frecuencia_annos'] = 0;
            $data['frecuencia_mes'] = 0;
            $data['frecuencia_semana'] = 0;
        }

        $data['empleados_favoritos'] = $this->Clientes_model->empleados_favoritos($param);
        $data['pago_con_templos'] = $this->Clientes_model->pago_con_templos($param);
        $data['facturacion_servicios_productos_recargas_total'] = $this->Clientes_model->facturacion_servicios_productos_recargas_total($param);
        $data['porcentaje_pagado_templos'] = $this->Clientes_model->porcentaje_pagado_templos($param);
        $data['centros_visitados'] = $this->Clientes_model->centros_visitados($param);
        $data['servicios_realizados'] = $this->Clientes_model->servicios_realizados($param);
        $data['carnets_vendidos'] = $this->Clientes_model->carnets_vendidos($param);
        $data['antelacion_anulaciones'] = $this->Clientes_model->antelacion_anulaciones($param);
        $data['valor_unitario_templo'] = 0;
        if ($data['carnets_vendidos'] > 0) {
            $r = 0;
            $total_carnets = 0;
            foreach ($data['carnets_vendidos'] as $row) {
                if ($row['id_tipo'] != 99) {
                    if ($row['templos'] > 0) {
                        $r += ($row['total_precio'] / ($row['templos'] * $row['numero'])) * $row['numero'];
                        $total_carnets += $row['numero'];
                    }
                }
            }

            if ($total_carnets > 0) {
                $data['valor_unitario_templo'] = $r / $total_carnets;
            } else {
                $data['valor_unitario_templo'] = 0;
            }
        }

        //
        // NOTAS CITAS
        //
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['notas_citas'] = $this->Clientes_model->notas_citas($param);

        //
        // NOTAS COBRAR
        //
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['notas_cobrar'] = $this->Clientes_model->notas_cobrar($param);

        // SALDOS
        $data['lista_saldos'] = $this->Clientes_model->leer_pago_a_cuenta($id_cliente);
        $data['saldo'] = $this->Clientes_model->saldo($id_cliente);

        $data['id_cliente'] = $id_cliente;

        //17/06/21 Aleatorio para tratar de actualizar el PDF de consentimiento
        if ($aleatorio) {
            $data['aleatorio'] = $aleatorio;
            $data['tipo_consentimiento'] = $tipo_consentimiento;
            $data['id_doctor'] = $id_doctor;
            //$datoDesc=$this->Clientes_model->leer_consentimiento_firmado($aleatorio);
            $data['descripcionTratamiento'] = $descripcionTratamiento;
            //$data['descripcionTratamiento'] = $datoDesc[0]['descripcionTratamiento'];
        }
        //Fin aleatorio
        // documentos
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['documentos'] = $this->Clientes_model->documentos_cliente($param);

        //Evolutivo
        $param_evolutivo['id_cliente'] = $id_cliente;
        $data['notas_evolutivo'] = $this->Clientes_model->leer_evolutivo_cliente($param_evolutivo);

        //Notas internas
        $param_notas_internas['id_cliente'] = $id_cliente;
        $data['notas_internas'] = $this->Clientes_model->leer_notas_internas_cliente($param_notas_internas);

        // CHAINS 20240223 - Seleccionar usuario
        unset($parametros);
        $parametros['id_perfil'] = 6;
        $parametros['borrado']  = 0;
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['usuarios'] = $this->Usuarios_model->leer_usuarios($parametros);
        $estaElActual=false;
        foreach($data['usuarios'] as $us){
            if($us['id_usuario']==$this->session->userdata('id_usuario')) $estaElActual=true;
        }
        if(!$estaElActual){
            unset($parametros);
            $parametros['id_usuario']=$this->session->userdata('id_usuario');
            $us2=$this->Usuarios_model->leer_usuarios($parametros);
            if($us2>0){
                $data['usuarios']=array_merge($data['usuarios'],$us2);
            }
        }
        // FIN CHAINS 20240223

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Ficha de paciente';
        $data['content_view'] = $this->load->view('clientes/clientes_historial_view', $data, true);

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

    function historialpopup($accion = null, $id_cliente = null, $aleatorio = null, $tipo_consentimiento = null, $id_doctor = null, $descripTrata = null)
    {
        if ($descripTrata) {
            $descripcionTratamiento = str_replace('_', ' ', $descripTrata);
        }
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
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

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        unset($parametros);
        $parametros['id_cliente'] = $id_cliente;
        $data['registros'] = $this->Clientes_model->leer_clientes($parametros);

        //21/10/20 Fichas de salud del cliente
        $data['salud'] = $this->Clientes_model->leer_fichas_salud($parametros);

        $data['existe_firma'] = $this->Clientes_model->existe_firma_lopd($id_cliente);

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['debug'] = 1;
        $historial = $this->Dietario_model->leer($param);

        // Consentmients 30/06/21
        $data['lista_consentimientos'] = $this->Clientes_model->leer_lista_consentimientos($id_cliente);
        //var_dump($data['lista_consentimientos']);

        //sub_fichas
        $data['lista_subficha'] = $this->Clientes_model->leer_subficha($id_cliente);
        $param['id_usuario'] = $this->session->userdata('id_usuario');
        $data['elempleado'] = $this->Usuarios_model->busqueda_id_usuario($param);
        $idCentro = 1;
        if (array_key_exists('id_centro', $data['elempleado'][0])) {
            $idCentro = $data['elempleado'][0]['id_centro'];
        }
        $data['doctores'] = $this->Clientes_model->leer_doctores($param['id_usuario'], $idCentro);
        // ... Aqui pasamos el historial del dietario para que nos lo devuelva con
        // los numero de carnets usados para cada pago
        unset($param);
        $param['historial'] = $historial;
        $data['historial'] = $this->Dietario_model->carnets_pago_templos($param);
        //$data['historial']=$historial;

        $dientesPorDietario=[];
        if(is_array($data['historial']) && count($data['historial'])){
            foreach($data['historial'] as $entradaDietario){
                $dientesPorDietario[$entradaDietario['id_dietario']]=null;
            }
        }

        if(is_array($data['historial']) && count($data['historial'])) {
            $dientesPorDietario = $this->Dietario_model->getDientesForDietario($dientesPorDietario);
            foreach ($data['historial'] as $key => $unDietario) {
                $data['historial'][$key]['dientes'] = $dientesPorDietario[$unDietario['id_dietario']];
            }
        }


        // ... Leemos el historial de la antigua aplicacion que se usaba, para mostrar
        // la actividad del cliente.
        unset($param);
        $param['nombre'] = $data['registros'][0]['nombre'];
        $param['apellidos'] = $data['registros'][0]['apellidos'];
        $param['telefono'] = $data['registros'][0]['telefono'];
        $data['facturacion_total_antigua'] = $this->Clientes_model->historial_antiguo_facturacion($param);

        $visitas_totales_historicas = $this->Clientes_model->visitas_totales_historicas($param);
        $dias_desde_primera_visita_historicas = $this->Clientes_model->dias_desde_primera_visita_historicas($param);
        if ($dias_desde_primera_visita_historicas > 0) {
            $data['frecuencia_annos_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 365);
            $data['frecuencia_mes_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 30);
            $data['frecuencia_semana_historica'] = $visitas_totales_historicas / ($dias_desde_primera_visita_historicas / 7);
        } else {
            $data['frecuencia_annos_historica'] = 0;
            $data['frecuencia_mes_historica'] = 0;
            $data['frecuencia_semana_historica'] = 0;
        }

        // ... Leemos los carnets que tenga ese cliente disponibles.
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['carnets'] = $this->Carnets_model->leer($param);

        //11/04/20 Clientes Asociados
        $data['asociados'] = $this->Carnets_model->leer_asociados_completo($param);
        //Fin

        //
        // OTROS DATOS
        //
        // ... Citas totales
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['citas_totales'] = $this->Clientes_model->citas_totales($param);
        $data['facturacion_total'] = $this->Clientes_model->facturacion_total($param);
        $data['descuentos_total'] = $this->Clientes_model->descuentos_totales_euros($param);
        $data['productos_comprados'] = $this->Clientes_model->numero_productos_comprados($param);

        $facturacion_servicios = $this->Clientes_model->facturacion_servicios_total($param);
        $facturacion_productos = $this->Clientes_model->facturacion_productos_total($param);
        $data['citas_anuladas'] = $this->Clientes_model->citas_anuladas($param);
        $data['citas_no_vino'] = $this->Clientes_model->citas_no_vino($param);
        $data['importe_no_vino'] = $this->Clientes_model->importe_no_vino($param);
        $data['importe_anuladas'] = $this->Clientes_model->importe_anuladas($param);

        $visitas_totales = $this->Clientes_model->visitas_totales($param);
        if ($visitas_totales > 0) {
            $rentabilidad = ($facturacion_servicios + $facturacion_productos - $data['citas_no_vino']) / $visitas_totales;
        } else {
            $rentabilidad = 0;
        }
        $data['rentabilidad'] = $rentabilidad;

        $data['frecuencia_ultimo_anno'] = $this->Clientes_model->visitas_12_meses($param);
        $data['frecuencia_ultimo_mes'] = $this->Clientes_model->visitas_1_mes($param);
        $data['frecuencia_ultimo_semana'] = $this->Clientes_model->visitas_1_semana($param);
        $data['frecuencia_ultimo_3_mes'] = $this->Clientes_model->visitas_3_meses($param);

        $dias_desde_primera_visita = $this->Clientes_model->dias_desde_primera_visita($param);
        if ($dias_desde_primera_visita > 0) {
            $data['frecuencia_annos'] = $visitas_totales / ($dias_desde_primera_visita / 365);
            $data['frecuencia_mes'] = $visitas_totales / ($dias_desde_primera_visita / 30);
            $data['frecuencia_semana'] = $visitas_totales / ($dias_desde_primera_visita / 7);
        } else {
            $data['frecuencia_annos'] = 0;
            $data['frecuencia_mes'] = 0;
            $data['frecuencia_semana'] = 0;
        }

        $data['empleados_favoritos'] = $this->Clientes_model->empleados_favoritos($param);

        $data['pago_con_templos'] = $this->Clientes_model->pago_con_templos($param);

        $data['facturacion_servicios_productos_recargas_total'] = $this->Clientes_model->facturacion_servicios_productos_recargas_total($param);

        $data['porcentaje_pagado_templos'] = $this->Clientes_model->porcentaje_pagado_templos($param);

        $data['centros_visitados'] = $this->Clientes_model->centros_visitados($param);

        $data['servicios_realizados'] = $this->Clientes_model->servicios_realizados($param);

        $data['carnets_vendidos'] = $this->Clientes_model->carnets_vendidos($param);

        $data['antelacion_anulaciones'] = $this->Clientes_model->antelacion_anulaciones($param);

        $data['valor_unitario_templo'] = 0;
        if ($data['carnets_vendidos'] > 0) {
            $r = 0;
            $total_carnets = 0;
            foreach ($data['carnets_vendidos'] as $row) {
                if ($row['id_tipo'] != 99) {
                    if ($row['templos'] > 0) {
                        $r += ($row['total_precio'] / ($row['templos'] * $row['numero'])) * $row['numero'];
                        $total_carnets += $row['numero'];
                    }
                }
            }

            if ($total_carnets > 0) {
                $data['valor_unitario_templo'] = $r / $total_carnets;
            } else {
                $data['valor_unitario_templo'] = 0;
            }
        }

        //
        // NOTAS CITAS
        //
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['notas_citas'] = $this->Clientes_model->notas_citas($param);

        //
        // NOTAS COBRAR
        //
        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['notas_cobrar'] = $this->Clientes_model->notas_cobrar($param);

        // SALDOS
        $data['lista_saldos'] = $this->Clientes_model->leer_pago_a_cuenta($id_cliente);
        $data['saldo'] = $this->Clientes_model->saldo($id_cliente);

        $data['id_cliente'] = $id_cliente;

        //17/06/21 Aleatorio para tratar de actualizar el PDF de consentimiento
        if ($aleatorio) {
            $data['aleatorio'] = $aleatorio;
            $data['tipo_consentimiento'] = $tipo_consentimiento;
            $data['id_doctor'] = $id_doctor;
            //$datoDesc=$this->Clientes_model->leer_consentimiento_firmado($aleatorio);
            $data['descripcionTratamiento'] = $descripcionTratamiento;
            //$data['descripcionTratamiento'] = $datoDesc[0]['descripcionTratamiento'];
        }
        //Fin aleatorio

        // CHAINS 20240223 - Seleccionar usuario
        unset($parametros);
        $parametros['id_perfil'] = 6;
        $parametros['borrado']  = 0;
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $data['usuarios'] = $this->Usuarios_model->leer_usuarios($parametros);
        $estaElActual=false;
        foreach($data['usuarios'] as $us){
            if($us['id_usuario']==$this->session->userdata('id_usuario')) $estaElActual=true;
        }
        if(!$estaElActual){
            unset($parametros);
            $parametros['id_usuario']=$this->session->userdata('id_usuario');
            $us2=$this->Usuarios_model->leer_usuarios($parametros);
            if($us2>0){
                $data['usuarios']=array_merge($data['usuarios'],$us2);
            }
        }
        // FIN CHAINS 20240223


        //Evolutivo
        $param_evolutivo['id_cliente'] = $id_cliente;
        $data['notas_evolutivo'] = $this->Clientes_model->leer_evolutivo_cliente($param_evolutivo);

        //Notas internas
        $param_notas_internas['id_cliente'] = $id_cliente;
        $data['notas_internas'] = $this->Clientes_model->leer_notas_internas_cliente($param_notas_internas);

        unset($param_doc);
        $param_doc['id_cliente'] = $id_cliente;
        $data['documentos'] = $this->Clientes_model->documentos_cliente($param_doc);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 7);
        if ($permiso) {
            $this->load->view('clientes/clientes_historial_popup_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function historiaclinicapdf($id_cliente){
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }


        $data=[];


        $parametros=[];
        $parametros['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($parametros);

        $paramd['id_usuario'] = $this->session->userdata('id_usuario');
        $data['usuario'] = $this->Usuarios_model->leer_usuarios($paramd);

        $paramc['id_centro'] = $data['usuario'][0]['id_centro'];
        $data['centro'] = $this->Usuarios_model->leer_centros($paramc);

        $param_evolutivo['id_cliente'] = $id_cliente;
        $data['notas_evolutivo'] = $this->Clientes_model->leer_evolutivo_cliente($param_evolutivo);

        $content_view = $this->load->view('pdf/cliente_historiaclinica_pdf_view', $data, true);
        $this->load->library('pdf');
        $this->pdf->stream($content_view, "hc-" .  $id_cliente . "-" . time() . ".pdf", array("Attachment" => false));

    }

    function ver_historial_antiguo($id_cliente)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        unset($parametros);
        $parametros['id_cliente'] = $id_cliente;
        $data['registros'] = $this->Clientes_model->leer_clientes($parametros);

        unset($param);
        $param['nombre'] = $data['registros'][0]['nombre'];
        $param['apellidos'] = $data['registros'][0]['apellidos'];
        $param['telefono'] = $data['registros'][0]['telefono'];
        $data['historial_antiguo'] = $this->Clientes_model->historial_antiguo($param);

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 7);
        if ($permiso) {
            $this->load->view('clientes/clientes_historial_antiguo_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //11/05/20 Historial CSV **************
    function historial_csv($id_cliente = null, $fecha_desde = null, $fecha_hasta = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        //$data['accion'] = $accion;



        unset($parametros);
        $parametros['id_cliente'] = $id_cliente;
        $data['registros'] = $this->Clientes_model->leer_clientes($parametros);

        $data['existe_firma'] = $this->Clientes_model->existe_firma_lopd($id_cliente);


        unset($param);
        $param['id_cliente'] = $id_cliente;
        $param['debug'] = 1;
        if ($fecha_desde != null and $fecha_hasta != null) {
            $param['fecha_desde'] = $fecha_desde;
            $param['fecha_hasta'] = $fecha_hasta;
        }
        $historial = $this->Dietario_model->leer($param);

        // ... Aqui pasamos el historial del dietario para que nos lo devuelva con
        // los numero de carnets usados para cada pago
        unset($param);
        $param['historial'] = $historial;
        $data['historial'] = $this->Dietario_model->carnets_pago_templos($param);
        //$data['historial']=$historial;


        $datos = $data['registros'];
        $registros = $data['historial'];

        $fichero = RUTA_SERVIDOR . "/recursos/historial_csv.csv";

        $file = fopen($fichero, "w");

        $linea = "Nombre " . $datos[0]['nombre'] . " " . $datos[0]['apellidos'] . " DNI: " . $datos[0]['dni'] . "\n";
        fwrite($file, $linea);
        unset($linea);
        $linea = "Fecha Hora;Centro;Empleado;Serv/Prod/Carnet;Euros;Templos;Estado\n";
        fwrite($file, $linea);

        if ($registros > 0) {
            //$i=0;
            foreach (array_reverse($registros) as $row) {
                unset($linea);
                //$i++;
                //echo $i.")".$row['fecha_hora_concepto_ddmmaaaa']." ".$row['hora']." ".$row['nombre_centro']." ".$row['empleado']." ".$row['estado']."<br>";
                $concepto = "";
                if ($row['servicio'] != "")
                    $concepto .= $row['servicio'];
                if ($row['producto'] != "")
                    $concepto .= $row['producto'];
                if ($row['cantidad'] > 1)
                    $concepto .= " " . $row['cantidad'];
                if ($row['carnet'] != "")
                    $concepto .= " " . $row['carnet'];
                if ($row['recarga'] == 1)
                    $concepto .= " (Recarga)";

                $monto = 0;
                if ($row['tipo_pago'] != "#templos")
                    $monto = $row['importe_total_final'];

                $templos = "-";
                if ($row['templos'] > 0 && $row['tipo_pago'] == "#templos")
                    $templos = round($row['templos'], 0);


                $linea = $row['fecha_hora_concepto_ddmmaaaa'] . " " . $row['hora'] . ";" . $row['nombre_centro'] . ";" . $row['empleado'] . ";" . $concepto . ";" . $monto . ";" . $templos . ";" . $row['estado'] . "\n";
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
    //Fin Historial CSV


    // 11/04/20 Añadir Asociados
    function nuevo_asociado($id_cliente = null, $id_asociado = null)
    {
        $registro['fecha_hora'] = date("Y-m-d H:i:s");
        $registro['borrado'] = 0;
        $registro['id_cliente'] = $id_cliente;
        $registro['id_asociado'] = $id_asociado;
        $this->Carnets_model->anadir_asociado($registro);
        //redirect('https://desarrollo.templodelmasaje.com/clientes/historial/ver/'.$id_cliente);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente . '?tab8default') {
            redirect("/clientes/historialpopup/ver/" . $id_cliente);
        }
        redirect("/clientes/historial/ver/" . $id_cliente . '?tab8default');
    }

    function borrar_asociado($id_cliente = null, $id_asociado = null)
    {

        $registro['borrado'] = 1;
        $registro['id_cliente'] = $id_cliente;
        $registro['id_asociado'] = $id_asociado;
        $this->Carnets_model->quita_asociado($registro);
        //redirect('https://desarrollo.templodelmasaje.com/clientes/historial/ver/'.$id_cliente);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente . '?tab8default') {
            redirect("/clientes/historialpopup/ver/" . $id_cliente);
        }
        redirect("/clientes/historial/ver/" . $id_cliente . '?tab8default');
    }
    //Fin


    //
    //
    //
    function nueva_nota_cita($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['accion'] = "nuevo";

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Nueva nota para ' . $data['cliente'][0]['nombre'] . " " . $data['cliente'][0]['apellidos'];
        $data['content_view'] = $this->load->view('clientes/clientes_nota_cita_view', $data, true);

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

    function nueva_nota_cita_agenda($id_cliente = null)
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

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 7);
        if ($permiso) {
            $this->load->view('clientes/clientes_nota_cita_agenda_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // ... Editar una nota cita.
    //
    function editar_nota_cita($id_nota_cita = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_nota_cita'] = $id_nota_cita;
        $data['registros'] = $this->Clientes_model->notas_citas($param);

        $data['accion'] = "editar";

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Editar nota de ' . $data['cliente'][0]['nombre'] . " " . $data['cliente'][0]['apellidos'];
        $data['content_view'] = $this->load->view('clientes/clientes_nota_cita_view', $data, true);

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

    //
    // ... Crear una nota cita.
    //
    function crear_nota_cita($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        $parametros['id_cliente'] = $id_cliente;
        $this->Clientes_model->crear_notas_citas($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $id_cliente . "?tab5default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $id_cliente . "?tab5default");
        exit;
    }
    //
    function crear_nota_cita_agenda($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        $parametros['id_cliente'] = $id_cliente;
        $parametros['estado'] = "Pendiente";
        $this->Clientes_model->crear_notas_citas($parametros);

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        $data['accion'] = "guardar";

        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 7);
        if ($permiso) {
            $this->load->view('clientes/clientes_nota_cita_agenda_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    //
    // ... Actualizar una nota cita.
    //
    function actualizar_nota_cita($id_nota_cita = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_nota_cita'] = $id_nota_cita;
        $nota = $this->Clientes_model->notas_citas($param);

        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        $parametros['id_nota_cita'] = $id_nota_cita;
        $this->Clientes_model->actualizar_notas_citas($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $nota[0]['id_cliente']) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $nota[0]['id_cliente'] . "?tab5default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $nota[0]['id_cliente'] . "?tab5default");
        exit;
    }

    //
    // ... Borrar una nota cita.
    //
    function borrar_nota_cita($id_nota_cita = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_nota_cita'] = $id_nota_cita;
        $nota = $this->Clientes_model->notas_citas($param);

        unset($parametros);
        $parametros['id_nota_cita'] = $id_nota_cita;
        $this->Clientes_model->borrar_notas_citas($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $nota[0]['id_cliente']) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $nota[0]['id_cliente'] . "?tab5default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $nota[0]['id_cliente'] . "?tab5default");
        exit;
    }

    function borrar_consentimiento($id = null, $id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($parametros);
        $parametros['id'] = $id;
        $this->Clientes_model->borrar_consentimiento($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $id_cliente . "?tab10default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $id_cliente . "?tab10default");
        exit;
    }

    //
    // ... Finalizar las notas marcadas
    //
    function finalizar_notas_citas($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if (isset($_POST['citas'])) {
            foreach ($_POST['citas'] as $id_nota_cita) {
                unset($parametros);
                $parametros['id_nota_cita'] = $id_nota_cita;
                $this->Clientes_model->finalizar_notas_citas($parametros);
            }
        }
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $id_cliente . "?tab5default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $id_cliente . "?tab5default");
        exit;
    }

    //
    //
    //
    function finalizar_una_nota_citas($id_nota_cita = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        if ($id_nota_cita > 0) {
            unset($parametros);
            $parametros['id_nota_cita'] = $id_nota_cita;
            $this->Clientes_model->finalizar_notas_citas($parametros);

            echo "OK";
        } else {
            echo "FALLO";
        }

        exit;
    }

    //
    //
    // NOTAS COBRAR
    //
    //
    //
    function nueva_nota_cobrar($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['accion'] = "nuevo";

        unset($param);
        $param['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Nueva nota para ' . $data['cliente'][0]['nombre'] . " " . $data['cliente'][0]['apellidos'];
        $data['content_view'] = $this->load->view('clientes/clientes_nota_cobrar_view', $data, true);

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

    //
    // ... Editar una nota cobrar.
    //
    function editar_nota_cobrar($id_nota_cobrar = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        unset($param);
        $param['id_nota_cobrar'] = $id_nota_cobrar;
        $data['registros'] = $this->Clientes_model->notas_cobrar($param);

        $data['accion'] = "editar";

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Editar nota de ' . $data['cliente'][0]['nombre'] . " " . $data['cliente'][0]['apellidos'];
        $data['content_view'] = $this->load->view('clientes/clientes_nota_cobrar_view', $data, true);

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

    //
    // ... Crear una nota cobrar.
    //
    function crear_nota_cobrar($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;
        //
        // ... Guardamos la imagen de la firma, si existe.
        //
        if (isset($parametros['firma_img'])) {
            if ($parametros['firma_img'] != "") {
                $base64_string = $parametros['firma_img'];
                $file = time() . "_firma.png";
                $ifp = fopen(RUTA_SERVIDOR . "/recursos/firmas/" . $file, 'wb');
                // split the string on commas
                // $data[ 0 ] == "data:image/png;base64"
                // $data[ 1 ] == <actual base64 string>
                $data = explode(',', $base64_string);
                // we could add validation here with ensuring count( $data ) > 1
                fwrite($ifp, base64_decode($data[1]));
                // clean up the file resource
                fclose($ifp);
                $parametros['firma_img'] = $file;
            }
        }

        $parametros['id_cliente'] = $id_cliente;
        $this->Clientes_model->crear_notas_cobrar($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $id_cliente . "?tab6default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $id_cliente . "?tab6default");
        exit;
    }

    //
    // ... Actualizar una nota cobrar.
    //
    function actualizar_nota_cobrar($id_nota_cobrar = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        unset($param);
        $param['id_nota_cobrar'] = $id_nota_cobrar;
        $nota = $this->Clientes_model->notas_cobrar($param);
        $parametros = $_POST;
        $parametros['id_nota_cobrar'] = $id_nota_cobrar;
        $this->Clientes_model->actualizar_notas_cobrar($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $nota[0]['id_cliente']) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $nota[0]['id_cliente'] . "?tab6default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $nota[0]['id_cliente'] . "?tab6default");
        exit;
    }

    //
    // ... Borrar una nota cobrar.
    //
    function borrar_nota_cobrar($id_nota_cobrar = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        unset($param);
        $param['id_nota_cobrar'] = $id_nota_cobrar;
        $nota = $this->Clientes_model->notas_cobrar($param);
        unset($parametros);
        $parametros['id_nota_cobrar'] = $id_nota_cobrar;
        $this->Clientes_model->borrar_notas_cobrar($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $nota[0]['id_cliente']) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $nota[0]['id_cliente'] . "?tab6default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $nota[0]['id_cliente'] . "?tab6default");
        exit;
    }
    // ... Finalizar las notas marcadas
    //
    function finalizar_notas_cobrar($id_cliente = null)
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        if (isset($_POST['cobros'])) {
            foreach ($_POST['cobros'] as $id_nota_cobrar) {
                unset($parametros);
                $parametros['id_nota_cobrar'] = $id_nota_cobrar;
                $this->Clientes_model->finalizar_notas_cobrar($parametros);
            }
        }
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            header("Location: " . RUTA_WWW . "/clientes/historialpopup/ver/" . $id_cliente  . "?tab6default");
        }
        header("Location: " . RUTA_WWW . "/clientes/historial/ver/" . $id_cliente . "?tab6default");
        exit;
    }
    //
    function finalizar_una_nota_cobrar($id_nota_cobrar = null)
    {
        // ... Comprobamos la sesion
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        if ($id_nota_cobrar > 0) {
            unset($parametros);
            $parametros['id_nota_cobrar'] = $id_nota_cobrar;
            $this->Clientes_model->finalizar_notas_cobrar($parametros);

            echo "OK";
        } else {
            echo "FALLO";
        }
        exit;
    }
    // ... Fusionamos los clientes indicados en uno solo.
    // El cliente de referencia, será el más antiguo
    function fusionar()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... Recogemos los parametros
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;
        // ... Verificamos de los clientes marcado, cual es el más antiguo, en base a esto
        // marcamos a este como cliente de referencia para volcar el historico del resto a él.
        if (isset($parametros['marcados'])) {
            $param['clientes_marcados'] = $parametros['marcados'];
            $this->Clientes_model->fusionar($param);
        }
        $data['estado_fusion'] = 1;
        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('clientes/clientes_view', $data, true);
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


    function exportar_csv()
    {
        unset($parametros);
        $parametros['vacio'] = "";
        $parametros['campos'] = $this->input->post();
        $this->load->dbutil();
        $query = $this->Clientes_model->exportar_clientes($parametros);
        $delimiter = ";";
        $newline = "\r\n";
        $enclosure = '';
        $result = $this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
        $this->load->helper('download');
        $file = "clientes_" . date('Y-m-d') . ".csv";
        force_download($file, $result);
    }


    function productos_comprados($idcliente){
        $rtval=[];
        if(isset($idcliente)){
            $productos=$this->Clientes_model->productos_comprados(['id_cliente'=>$idcliente,'importes'=>1]);
            if($productos>0){
                $rtval=$productos;
            }
        }
        $rr=[];
        foreach($rtval as $rrv){
            $rr[]=['id'=>$rrv['id_producto'],'text'=>$rrv['nombre_familia'].' - '.$rrv['nombre_producto'],
                    'total_a_devolver'=>$rrv['importe_euros']-($rrv['descuento_euros'])-($rrv['descuento_porcentaje']/100)*$rrv['importe_euros']
                ];
        }
        echo json_encode($rr);
        exit;
    }

    function servicios_realizados($idcliente,$devuelto=null){
        $rtval=[];
        if(isset($idcliente)){
            $params=['id_cliente'=>$idcliente,'importes'=>1];
            if($devuelto!==null){
                $params['devuelto']=$devuelto;
            }
            $servicios=$this->Clientes_model->servicios_realizados($params);
            if($servicios>0){
                $rtval=$servicios;
            }
        }
        $rr=[];
        foreach($rtval as $rrv){
            $rr[]=['id'=>$rrv['id_servicio'],'text'=>$rrv['nombre_familia'].' - '.$rrv['nombre_servicio'],
                'total_a_devolver'=>$rrv['importe_euros']-($rrv['descuento_euros'])-($rrv['descuento_porcentaje']/100)*$rrv['importe_euros']
            ];
        }
        echo json_encode($rr);
        exit;
    }

    function ultimo_servicio($idcliente,$idservicio){
        $servicio=[];
        if(isset($idcliente) && isset($idservicio)){
            $servicios=$this->Dietario_model->leer([
                'id_cliente'=>$idcliente,
                'id_servicio'=>$idservicio,
                'estado'=>'Pagado',
                'devuelto'=>0
            ]);
            if($servicios>0){
                $servicio=$servicios[0];
                $servicio['total_a_devolver']=$servicio['importe_euros']-($servicio['descuento_euros'])-($servicio['descuento_porcentaje']/100)*$servicio['importe_euros'];
            }
        }
        echo json_encode($servicio);
        exit;
    }

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
        $parametros['q'] = str_replace('_', ' ', $parametros['q']);
        $clientes = $this->Clientes_model->clientes_json($parametros);
        $json_response = json_encode($clientes);
        echo $json_response;
        exit;
    }

    function ultimos_centros_genera($q = null)
    {
        unset($parametros);
        $parametros['vacio'] = "";
        // ... Solo si son las 8am ejecuta el proceso de ultimos centros.
        if (date('H:i') == "09:00") {
            $clientes = $this->Clientes_model->clientes_ultimos_centros_genera($parametros);
        }
        $r = $this->Horarios_model->desglosar_rango_fechas($parametros);
        echo "OK";
        exit;
    }

    function proteccion_de_datos($id_cliente = 0)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['existe'] = $this->Clientes_model->existe_firma_lopd($id_cliente);
        $parametros['id_cliente'] = $id_cliente;
        $data['cliente'] = $this->Clientes_model->leer_clientes($parametros);
        // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('clientes/proteccion_de_datos', $data, true);
        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // ... Pagina master
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    function guardar_proteccion_datos()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;
        //
        // ... Guardamos la imagen de la firma, si existe.
        //
        if (isset($parametros['firma_img']) && isset($parametros['firma_img'])) {
            if ($parametros['firma_img'] != "" && $parametros['id_cliente'] > 0) {
                $base64_string = $parametros['firma_img'];
                $file = time() . "_firma.png";
                $ifp = fopen(RUTA_SERVIDOR . "/recursos/firmas_lopd/" . $file, 'wb');
                // split the string on commas
                // $data[ 0 ] == "data:image/png;base64"
                // $data[ 1 ] == <actual base64 string>
                $data = explode(',', $base64_string);
                // we could add validation here with ensuring count( $data ) > 1
                fwrite($ifp, base64_decode($data[1]));
                // clean up the file resource
                fclose($ifp);
                $parametros['firma_img'] = $file;
                $recibir_informacion = 0;
                if (isset($parametros['recibir_informacion'])) {
                    $recibir_informacion = 1;
                }
                $this->Clientes_model->crear_firma_lopd($parametros['id_cliente'], $parametros['dni'], $file, 1, $recibir_informacion);
                $this->Clientes_model->actualizar_lopd_cliente($parametros['id_cliente'], $parametros['dni'], $recibir_informacion);
                // ... Viewer con el contenido
                $data2['gracias'] = 1;
                $data2['content_view'] = $this->load->view('clientes/proteccion_de_datos', $data2, true);
                // ... Modulos del usuario
                $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
                $data2['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
                // ... Pagina master
                $this->load->view($this->config->item('template_dir') . '/master', $data2);
            } else {
                echo "No hay datos de firma. No se ha podido realizar la operación";
                exit;
            }
        }
    }

    function ver_firma_lopd($id_cliente = 0)
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... Leemos los datos de la firma
        $data['datos_firma'] = $this->Clientes_model->leer_firma_lopd($id_cliente);
        // ... Viewer con el código para generar el HTML
        $content_view = $this->load->view('clientes/proteccion_de_datos_pdf', $data, true);
        $this->load->library('pdf');
        $set_option = ['dpi' => 72];
        $this->pdf->stream($content_view, "Firma-" . $id_cliente . ".pdf", array("Attachment" => false), '', $set_option);
    }

    public function consentimiento($id_cliente = 0)
    {
        //https://extranet.templodelmasaje.com/clientes/consentimiento/5381
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // ... Leemos los datos de la firma
        $data['datos_firma'] = $this->Clientes_model->leer_firma_consentimiento($id_cliente);
        //Imagen o firma del Consentimiento
        $id_doctor = null;
        $descripcionTratamiento = "";
        $parametros = $_POST;
        //
        // ... Guardamos la imagen de la firma, si existe.
        //
        //Va grabar solo si tiene firma y aleatorio.
        //Si tiene solo aleatorio, sin irma, no crea y regresa el mismo id_cliente con aleatorio.
        $accion = "nuevo";
        if (isset($parametros['aleatorio'])) { //Si tiene solo aleatorio, sin irma, no crea y regresa el mismo id_cliente con aleatorio.
            $accion = "crear";
            $aleatorio = $parametros['aleatorio'];
            $tipo_consentimiento = $parametros['tipo_consentimiento'];
            $id_doctor = $parametros['id_doctor'];
            $descripcionTratamiento = $parametros['descripcionTratamiento'];
            //$datoDesc=$this->Clientes_model->leer_consentimiento_firmado($aleatorio);
            $data['descripcionTratamiento'] = $descripcionTratamiento;
            //$data['descripcionTratamiento'] = $datoDesc[0]['descripcionTratamiento'];
        }

        if (isset($parametros['firma_img_consen'])) {
            if ($parametros['firma_img_consen'] != "") {
                $accion = "grabar";
                $base64_string = $parametros['firma_img_consen'];
                $file = time() . "_firma.png";
                $ifp = fopen(RUTA_SERVIDOR . "/recursos/consentimientos/" . $file, 'wb');
                $data = explode(',', $base64_string);
                fwrite($ifp, base64_decode($data[1]));
                fclose($ifp);
                $parametros['firma_img'] = $file;
            } else {
                if (isset($parametros['aleatorio'])) { //Si tiene solo aleatorio, sin irma, no crea y regresa el mismo id_cliente con aleatorio.
                    $accion = "crear";
                    $aleatorio = $parametros['aleatorio'];
                    $id_doctor = $parametros['id_doctor'];
                    $descripcionTratamiento = $parametros['descripcionTratamiento'];
                }
            }
        }
        // ... Viewer con el código para generar el HTML
        //https://extranet.templodelmasaje.com/clientes/consentimiento/5381
        $i = 0;

        if ($accion == "nuevo") {
            //08/11/23
            $descripcionTratamiento = "";
            if (isset($_POST['tratamiento'])) {
                $descripcionTratamiento = $_POST['tratamiento'];
            }
            $data['descripcionTratamiento'] = $descripcionTratamiento;
            $descripcionProdedimiento = "";
            if (isset($_POST['procedimiento'])) {
                $descripcionProdedimiento = $_POST['procedimiento'];
            }
            $data['descripcionProcedimiento'] = $descripcionProdedimiento;
            $descripcionRiesgos = "";
            if (isset($_POST['riesgos'])) {
                $descripcionRiesgos = $_POST['riesgos'];
            }
            $data['descripcionRiesgos'] = $descripcionRiesgos;
            $descripcionProblema = "";
            if (isset($_POST['problema'])) {
                $descripcionProblema = $_POST['problema'];
            }
            $data['descripcionProblema'] = $descripcionProblema;
            $descriptionProcTerapeuticos = "";
            if (isset($_POST['procTerapeuticos'])) {
                $descriptionProcTerapeuticos = $_POST['procTerapeuticos'];
            }
            $data['descriptionProcTerapeuticos'] = $descriptionProcTerapeuticos;
            $descriptionProc = "";
            if (isset($_POST['procedimiento'])) {
                $descriptionProc = $_POST['procedimiento'];
            }
            $data['descriptionProc'] = $descriptionProc;

            // PARA SACAR EL CENTRO
            $data['usuario'] = $this->Usuarios_model->leer_usuarios(['id_usuario' => $this->session->userdata('id_usuario')]);
            $data['nombre_centro'] = $data['usuario'][0]['nombre_centro'];
            $data['direccion_centro'] = $data['usuario'][0]['direccion_centro'];

            //25/10/23 Buscar datos del doctor
            $param99['id_usuario'] = $_POST['selectDoctores'];
            $id_doctor = $_POST['selectDoctores'];
            $doctor = $this->Usuarios_model->busqueda_id_usuario($param99);
            $data['datos_doctor'] = $doctor;
            if (isset($_POST['selectConsentimniento'])) {
                $i = $_POST['selectConsentimniento'];
                switch ($i) {
                    case 1:
                        $tipo_consentimiento = 1;
                        $content_view = $this->load->view('clientes/consentimiento_acidohialuronico_view', $data, true);
                        break;
                    case 2:
                        $tipo_consentimiento = 2;
                        $content_view = $this->load->view('clientes/consentimiento_bioestimulacion_view', $data, true);
                        break;
                    case 3:
                        $tipo_consentimiento = 3;
                        $content_view = $this->load->view('clientes/consentimiento_hilos_view', $data, true);
                        break;
                    case 4:
                        $tipo_consentimiento = 4;
                        $content_view = $this->load->view('clientes/consentimiento_implantes_view', $data, true);
                        break;
                    case 5:
                        $tipo_consentimiento = 5;
                        $content_view = $this->load->view('clientes/consentimiento_mesoterapia_view', $data, true);
                        break;
                    case 6:
                        $tipo_consentimiento = 6;
                        $content_view = $this->load->view('clientes/consentimiento_toxina_view', $data, true);
                        break;
                    case 7:
                        $tipo_consentimiento = 7;
                        $content_view = $this->load->view('clientes/consentimiento_alineadores_ezclear_view', $data, true);
                        break;
                    case 8:
                        $tipo_consentimiento = 8;
                        $content_view = $this->load->view('clientes/consentimiento_alineadores_generico_view', $data, true);
                        break;
                    case 9:
                        $tipo_consentimiento = 9;
                        $content_view = $this->load->view('clientes/consentimiento_cirugia_peirapical_sedo_view', $data, true);
                        break;
                    case 10:
                        $tipo_consentimiento = 10;
                        $content_view = $this->load->view('clientes/consentimiento_elevacion_sinusal_view', $data, true);
                        break;
                    case 11:
                        $tipo_consentimiento = 11;
                        $content_view = $this->load->view('clientes/consentimiento_endodoncia_sedo_view', $data, true);
                        break;
                    case 12:
                        $tipo_consentimiento = 12;
                        $content_view = $this->load->view('clientes/consentimiento_cigomatico_view', $data, true);
                        break;
                    case 13:
                        $tipo_consentimiento = 13;
                        $content_view = $this->load->view('clientes/consentimiento_blanqueamiento_view', $data, true);
                        break;
                    case 14:
                        $tipo_consentimiento = 14;
                        $content_view = $this->load->view('clientes/consentimiento_implantologia_view', $data, true);
                        break;
                    case 15:
                        $tipo_consentimiento = 15;
                        $content_view = $this->load->view('clientes/consentimiento_injertos_conectivos_view', $data, true);
                        break;
                    case 16:
                        $tipo_consentimiento = 16;
                        $content_view = $this->load->view('clientes/consentimiento_injertos_oseos_view', $data, true);
                        break;
                    case 17:
                        $tipo_consentimiento = 17;
                        $content_view = $this->load->view('clientes/consentimiento_invisalign_view', $data, true);
                        break;
                    case 18:
                        $tipo_consentimiento = 18;
                        $content_view = $this->load->view('clientes/consentimiento_ortodoncia_view', $data, true);
                        break;
                    case 19:
                        $tipo_consentimiento = 19;
                        $content_view = $this->load->view('clientes/consentimiento_protesis_sobre_implante_view', $data, true);
                        break;
                    case 20:
                        $tipo_consentimiento = 20;
                        $content_view = $this->load->view('clientes/consentimiento_pulpectomia_view', $data, true);
                        break;
                    case 21:
                        $tipo_consentimiento = 21;
                        $content_view = $this->load->view('clientes/consentimiento_reendodoncia_view', $data, true);
                        break;
                    case 22:
                        $tipo_consentimiento = 22;
                        $content_view = $this->load->view('clientes/consentimiento_pulpar_inmaduro_view', $data, true);
                        break;
                    case 23:
                        $tipo_consentimiento = 23;
                        $content_view = $this->load->view('clientes/consentimiento_clausula_view', $data, true);
                        break;
                    case 24:
                        $tipo_consentimiento = 24;
                        $content_view = $this->load->view('clientes/consentimiento_extracion_dental_view', $data, true);
                        break;
                    case 25:
                        $tipo_consentimiento = 25;
                        $content_view = $this->load->view('clientes/consentimiento_implante_view', $data, true);
                        break;
                    case 26:
                        $tipo_consentimiento = 26;
                        $content_view = $this->load->view('clientes/consentimiento_obturacion_view', $data, true);
                        break;
                    case 27:
                        $tipo_consentimiento = 27;
                        $content_view = $this->load->view('clientes/consentimiento_odontopedriatia_view', $data, true);
                        break;
                    case 28:
                        $tipo_consentimiento = 28;
                        $content_view = $this->load->view('clientes/consentimiento_ortodoncia2_view', $data, true);
                        break;
                    case 29:
                        $tipo_consentimiento = 29;
                        $content_view = $this->load->view('clientes/consentimiento_periodontal_view', $data, true);
                        break;
                    case 30:
                        $tipo_consentimiento = 30;
                        $content_view = $this->load->view('clientes/consentimiento_protesis_fija_view', $data, true);
                        break;
                    case 31:
                        $tipo_consentimiento = 31;
                        $content_view = $this->load->view('clientes/consentimiento_protesis_removible_view', $data, true);
                        break;
                    case 32:
                        $tipo_consentimiento = 32;
                        $content_view = $this->load->view('clientes/consentimiento_limpieza_dental_view', $data, true);
                        break;
                    case 33:
                        $tipo_consentimiento = 33;
                        $content_view = $this->load->view('clientes/consentimiento_pulpotomia_view', $data, true);
                        break;
                    case 34:
                        $tipo_consentimiento = 34;
                        $content_view = $this->load->view('clientes/consentimiento_cirugia_microtornillos_view', $data, true);
                        break;
                    case 35:
                        $tipo_consentimiento = 35;
                        $content_view = $this->load->view('clientes/consentimiento_odontologia_general_view', $data, true);
                        break;
                    case 36:
                        $tipo_consentimiento = 36;
                        $content_view = $this->load->view('clientes/consentimiento_quad_helix_view', $data, true);
                        break;
                    case 37:
                        $tipo_consentimiento = 37;
                        $content_view = $this->load->view('clientes/consentimiento_periodoncia_view', $data, true);
                        break;
                    case 38:
                        $tipo_consentimiento = 38;
                        $content_view = $this->load->view('clientes/consentimiento_documento_rgpd_view', $data, true);
                        break;
                }
            } else {
                $content_view = $this->load->view('clientes/consentimiento_acidohialuronico_view', $data, true);
            }
        }

        //
        $descripTrata = str_replace(' ', '_', $descripcionTratamiento);
        $descripTrata = urlencode($descripTrata);
        if ($accion == "nuevo") {
            $this->load->library('pdf');
            $set_option = ['dpi' => 72];
            $aleatorio = rand(1000, 9999);
            $ruta = RUTA_SERVIDOR . "/recursos/consentimientos/preConsentimiento_" . $id_cliente . "_" . $aleatorio . ".pdf";
            $output = $this->pdf->output($content_view, $ruta);
            file_put_contents($ruta, $output);
            if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
                redirect("/clientes/historialpopup/ver/" . $id_cliente . "/" . $aleatorio . "/" . $tipo_consentimiento . "/" . $id_doctor . "/" . $descripTrata);
            }
            redirect("/clientes/historial/ver/" . $id_cliente . "/" . $aleatorio . "/" . $tipo_consentimiento . "/" . $id_doctor . "/" . $descripTrata);
        }
        if ($accion == "crear") {
            if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
                redirect("/clientes/historialpopup/ver/" . $id_cliente . "/" . $aleatorio . "/" . $tipo_consentimiento . "/" . $id_doctor . "/" . $descripTrata);
            }
            redirect("/clientes/historial/ver/" . $id_cliente . "/" . $aleatorio . "/" . $tipo_consentimiento . "/" . $id_doctor . "/" . $descripTrata);
        }
        if ($accion == "grabar") {
            unset($param2);
            $param2['id_cliente'] = $id_cliente;
            $param2['aleatorio'] = $aleatorio;
            $param2['id_doctor'] = $id_doctor;
            $param2['descripcionTratamiento'] = $descripcionTratamiento;

            $param2['tratamiento'] = "Por determinar";
            if ($tipo_consentimiento == 1) {
                $param2['tratamiento'] = "Ácido Hialurónico";
            }
            if ($tipo_consentimiento == 2) {
                $param2['tratamiento'] = "Bioestimulación Facial";
            }
            if ($tipo_consentimiento == 3) {
                $param2['tratamiento'] = "PDO PDC PLL";
            }
            if ($tipo_consentimiento == 4) {
                $param2['tratamiento'] = "Implantes con ácido poliláctico";
            }
            if ($tipo_consentimiento == 5) {
                $param2['tratamiento'] = "Mesoterapia";
            }
            if ($tipo_consentimiento == 6) {
                $param2['tratamiento'] = "Toxina Botulínica";
            }
            if ($tipo_consentimiento == 7) {
                $param2['tratamiento'] = "Alineadores EZCLEAR";
            }
            if ($tipo_consentimiento == 8) {
                $param2['tratamiento'] = "Alineadores Genérico";
            }
            if ($tipo_consentimiento == 9) {
                $param2['tratamiento'] = "Cirugía Peirapical SEDO";
            }
            if ($tipo_consentimiento == 10) {
                $param2['tratamiento'] = "Cirugía elevación SINUSAL";
            }
            if ($tipo_consentimiento == 11) {
                $param2['tratamiento'] = "Endodoncia SEDO";
            }
            if ($tipo_consentimiento == 12) {
                $param2['tratamiento'] = "Implantes Cigomáticos";
            }
            if ($tipo_consentimiento == 13) {
                $param2['tratamiento'] = "Blanqueamiento";
            }
            if ($tipo_consentimiento == 14) {
                $param2['tratamiento'] = "Implantología";
            }
            if ($tipo_consentimiento == 15) {
                $param2['tratamiento'] = "Injertos Conectivos";
            }
            if ($tipo_consentimiento == 16) {
                $param2['tratamiento'] = "Injertos Óseos";
            }
            if ($tipo_consentimiento == 17) {
                $param2['tratamiento'] = "Invisalign";
            }
            if ($tipo_consentimiento == 18) {
                $param2['tratamiento'] = "Ortodoncia";
            }
            if ($tipo_consentimiento == 19) {
                $param2['tratamiento'] = "Prótesis Sobre Implante";
            }
            if ($tipo_consentimiento == 20) {
                $param2['tratamiento'] = "Pulpectomía";
            }
            if ($tipo_consentimiento == 21) {
                $param2['tratamiento'] = "Reendodoncia";
            }
            if ($tipo_consentimiento == 22) {
                $param2['tratamiento'] = "Pulpar Diente Inmaduro";
            }
            if ($tipo_consentimiento == 23) {
                $param2['tratamiento'] = "Cláusula Informativa";
            }
            if ($tipo_consentimiento == 24) {
                $param2['tratamiento'] = "Extracción Dental";
            }
            if ($tipo_consentimiento == 25) {
                $param2['tratamiento'] = "Implante Dental";
            }
            if ($tipo_consentimiento == 26) {
                $param2['tratamiento'] = "Obturación o Empaste Dental";
            }
            if ($tipo_consentimiento == 27) {
                $param2['tratamiento'] = "Odontopediatria";
            }
            if ($tipo_consentimiento == 28) {
                $param2['tratamiento'] = "Tratamiento Ortodoncia";
            }
            if ($tipo_consentimiento == 29) {
                $param2['tratamiento'] = "Tratamiento Periodontal";
            }
            if ($tipo_consentimiento == 30) {
                $param2['tratamiento'] = "Prótesis Fija";
            }
            if ($tipo_consentimiento == 31) {
                $param2['tratamiento'] = "Prótesis Removible";
            }
            if ($tipo_consentimiento == 32) {
                $param2['tratamiento'] = "Limpieza Dental";
            }
            if ($tipo_consentimiento == 33) {
                $param2['tratamiento'] = "Pulpotomía";
            }
            if ($tipo_consentimiento == 34) {
                $param2['tratamiento'] = "Cirugía Microtornillos";
            }
            if ($tipo_consentimiento == 35) {
                $param2['tratamiento'] = "Odontologia General";
            }
            if ($tipo_consentimiento == 36) {
                $param2['tratamiento'] = "Quad Helix";
            }
            if ($tipo_consentimiento == 37) {
                $param2['tratamiento'] = "Periodoncia";
            }
            if ($tipo_consentimiento == 38) {
                $param2['tratamiento'] = "Documento RGPD";
            }
            $param2['firma'] = $file;
            $param2['id_centro'] = 1;

            $descripcionTratamiento = "";
            if (isset($_POST['tratamiento'])) {
                $descripcionTratamiento = $_POST['tratamiento'];
            }
            $data['descripcionTratamiento'] = $descripcionTratamiento;
            $descripcionProdedimiento = "";
            if (isset($_POST['procedimiento'])) {
                $descripcionProdedimiento = $_POST['procedimiento'];
            }
            $data['descripcionProcedimiento'] = $descripcionProdedimiento;
            $descripcionRiesgos = "";
            if (isset($_POST['riesgos'])) {
                $descripcionRiesgos = $_POST['riesgos'];
            }
            $data['descripcionRiesgos'] = $descripcionRiesgos;
            $descripcionProblema = "";
            if (isset($_POST['problema'])) {
                $descripcionProblema = $_POST['problema'];
            }
            $data['descripcionProblema'] = $descripcionProblema;
            $descriptionProcTerapeuticos = "";
            if (isset($_POST['procTerapeuticos'])) {
                $descriptionProcTerapeuticos = $_POST['procTerapeuticos'];
            }
            $data['descriptionProcTerapeuticos'] = $descriptionProcTerapeuticos;
            $descriptionProc = "";
            if (isset($_POST['procedimiento'])) {
                $descriptionProc = $_POST['procedimiento'];
            }
            $data['descriptionProc'] = $descriptionProc;

            // PARA SACAR EL CENTRO
            $data['usuario'] = $this->Usuarios_model->leer_usuarios(['id_usuario' => $this->session->userdata('id_usuario')]);
            $data['nombre_centro'] = $data['usuario'][0]['nombre_centro'];
            $data['direccion_centro'] = $data['usuario'][0]['direccion_centro'];

            $estado = $this->Clientes_model->nuevo_consentimiento($param2);
            $data['datos_firma'] = $this->Clientes_model->leer_consentimiento_firmado($aleatorio);
            //25/10/23
            $param99['id_usuario'] = $id_doctor;
            //$id_doctor=$_POST['selectDoctores'];
            $doctor = $this->Usuarios_model->busqueda_id_usuario($param99);
            $data['datos_doctor'] = $doctor;
            if ($tipo_consentimiento == 1) {
                $content_view = $this->load->view('clientes/consentimiento_acidohialuronico_view', $data, true);
            }
            if ($tipo_consentimiento == 2) {
                $content_view = $this->load->view('clientes/consentimiento_bioestimulacion_view', $data, true);
            }
            if ($tipo_consentimiento == 3) {
                $content_view = $this->load->view('clientes/consentimiento_hilos_view', $data, true);
            }
            if ($tipo_consentimiento == 4) {
                $content_view = $this->load->view('clientes/consentimiento_implantes_view', $data, true);
            }
            if ($tipo_consentimiento == 5) {
                $content_view = $this->load->view('clientes/consentimiento_mesoterapia_view', $data, true);
            }
            if ($tipo_consentimiento == 6) {
                $content_view = $this->load->view('clientes/consentimiento_toxina_view', $data, true);
            }
            if ($tipo_consentimiento == 7) {
                $content_view = $this->load->view('clientes/consentimiento_alineadores_ezclear_view', $data, true);
            }
            if ($tipo_consentimiento == 8) {
                $content_view = $this->load->view('clientes/consentimiento_alineadores_generico_view', $data, true);
            }
            if ($tipo_consentimiento == 9) {
                $content_view = $this->load->view('clientes/consentimiento_cirugia_peirapical_sedo_view', $data, true);
            }
            if ($tipo_consentimiento == 10) {
                $content_view = $this->load->view('clientes/consentimiento_elevacion_sinusal_view', $data, true);
            }
            if ($tipo_consentimiento == 11) {
                $content_view = $this->load->view('clientes/consentimiento_endodoncia_sedo_view', $data, true);
            }
            if ($tipo_consentimiento == 12) {
                $content_view = $this->load->view('clientes/consentimiento_cigomatico_view', $data, true);
            }
            if ($tipo_consentimiento == 13) {
                $content_view = $this->load->view('clientes/consentimiento_blanqueamiento_view', $data, true);
            }
            if ($tipo_consentimiento == 14) {
                $content_view = $this->load->view('clientes/consentimiento_implantologia_view', $data, true);
            }
            if ($tipo_consentimiento == 15) {
                $content_view = $this->load->view('clientes/consentimiento_injertos_conectivos_view', $data, true);
            }
            if ($tipo_consentimiento == 16) {
                $content_view = $this->load->view('clientes/consentimiento_injertos_oseos_view', $data, true);
            }
            if ($tipo_consentimiento == 17) {
                $content_view = $this->load->view('clientes/consentimiento_invisalign_view', $data, true);
            }
            if ($tipo_consentimiento == 18) {
                $content_view = $this->load->view('clientes/consentimiento_ortodoncia_view', $data, true);
            }
            if ($tipo_consentimiento == 19) {
                $content_view = $this->load->view('clientes/consentimiento_protesis_sobre_implante_view', $data, true);
            }
            if ($tipo_consentimiento == 20) {
                $content_view = $this->load->view('clientes/consentimiento_pulpectomia_view', $data, true);
            }
            if ($tipo_consentimiento == 21) {
                $content_view = $this->load->view('clientes/consentimiento_reendodoncia_view', $data, true);
            }
            if ($tipo_consentimiento == 22) {
                $content_view = $this->load->view('clientes/consentimiento_pulpar_inmaduro_view', $data, true);
            }
            if ($tipo_consentimiento == 23) {
                $content_view = $this->load->view('clientes/consentimiento_clausula_view', $data, true);
            }
            if ($tipo_consentimiento == 24) {
                $content_view = $this->load->view('clientes/consentimiento_extracion_dental_view', $data, true);
            }
            if ($tipo_consentimiento == 25) {
                $content_view = $this->load->view('clientes/consentimiento_implante_view', $data, true);
            }
            if ($tipo_consentimiento == 26) {
                $content_view = $this->load->view('clientes/consentimiento_obturacion_view', $data, true);
            }
            if ($tipo_consentimiento == 27) {
                $content_view = $this->load->view('clientes/consentimiento_odontopedriatia_view', $data, true);
            }
            if ($tipo_consentimiento == 28) {
                $content_view = $this->load->view('clientes/consentimiento_ortodoncia2_view', $data, true);
            }
            if ($tipo_consentimiento == 29) {
                $content_view = $this->load->view('clientes/consentimiento_periodontal_view', $data, true);
            }
            if ($tipo_consentimiento == 30) {
                $content_view = $this->load->view('clientes/consentimiento_protesis_fija_view', $data, true);
            }
            if ($tipo_consentimiento == 31) {
                $content_view = $this->load->view('clientes/consentimiento_protesis_removible_view', $data, true);
            }
            if ($tipo_consentimiento == 32) {
                $content_view = $this->load->view('clientes/consentimiento_limpieza_dental_view', $data, true);
            }
            if ($tipo_consentimiento == 33) {
                $content_view = $this->load->view('clientes/consentimiento_pulpotomia_view', $data, true);
            }
            if ($tipo_consentimiento == 34) {
                $content_view = $this->load->view('clientes/consentimiento_cirugia_microtornillos_view', $data, true);
            }
            if ($tipo_consentimiento == 35) {
                $content_view = $this->load->view('clientes/consentimiento_odontologia_general_view', $data, true);
            }
            if ($tipo_consentimiento == 36) {
                $content_view = $this->load->view('clientes/consentimiento_quad_helix_view', $data, true);
            }
            if ($tipo_consentimiento == 37) {
                $content_view = $this->load->view('clientes/consentimiento_periodoncia_view', $data, true);
            }
            if ($tipo_consentimiento == 38) {
                $content_view = $this->load->view('clientes/consentimiento_documento_rgpd_view', $data, true);
            }
            //Crear el PDF Firmado
            $this->load->library('pdf');
            $set_option = ['dpi' => 72];
            //$this->pdf->stream($content_view, "PreConsentimiento-".$id_cliente.".pdf", array("Attachment" => false), '', $set_option);
            $ruta = RUTA_SERVIDOR . "/recursos/consentimientos/Consentimiento_" . $id_cliente . "_" . $aleatorio . ".pdf";
            $output = $this->pdf->output($content_view, $ruta);
            file_put_contents($ruta, $output);
            //Fin PDF Firmado
            if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
                redirect("/clientes/historialpopup/ver/" . $id_cliente);
            }
            redirect("/clientes/historial/ver/" . $id_cliente);
        }
    }

    public function subficha($id_cliente = 0)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros = $_POST;
        $parametros['id_cliente'] = $id_cliente;
        $this->Clientes_model->crear_subficha($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            redirect("/clientes/historialpopup/ver/" . $id_cliente);
        }
        redirect("/clientes/historial/ver/" . $id_cliente);
    }

    public function editar_subficha($id = 0)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['subficha'] = $this->Clientes_model->leer_subficha_id($id);
        $this->load->view('clientes/editar_subficha_view', $data);
    }

    public function modificar_subficha($id = null, $id_cliente = null)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros = $_POST;
        $parametros['id_cliente'] = $id_cliente;
        $parametros['id'] = $id;
        $this->Clientes_model->modificar_subficha($parametros);
        if ($_SERVER['HTTP_REFERER'] == base_url() . 'clientes/historialpopup/ver/' . $id_cliente) {
            redirect("/clientes/historialpopup/ver/" . $id_cliente);
        }
        redirect("/clientes/historial/ver/" . $id_cliente);
    }

    /**
     * DOCUMENTOS
     */
    public function nuevodocumento()
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('fecha_estudio', 'Fecha del estudio', 'trim|required');
        $this->form_validation->set_rules('tipo', 'Tipo de estudio', 'trim|required');
        if ($_FILES['nuevodoc']['name'] == '') {
            $this->form_validation->set_rules('imagen', 'Documento', 'trim|required');
        }
        $redirect = ($this->input->post('redirectto') != '') ? $this->input->post('redirectto') : base_url() . '/clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab12default';

        $parametros = $_POST;
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect($redirect);
        } else {
            $_FILES['file']['name'] = $_FILES['nuevodoc']['name'];
            $_FILES['file']['type'] = $_FILES['nuevodoc']['type'];
            $_FILES['file']['tmp_name'] = $_FILES['nuevodoc']['tmp_name'];
            $_FILES['file']['error'] = $_FILES['nuevodoc']['error'];
            $_FILES['file']['size'] = $_FILES['nuevodoc']['size'];
            $extension = pathinfo($_FILES['file']['tmp_name'], PATHINFO_EXTENSION);
            $nombre_limpio = $_FILES['file']['name'];
            $this->load->helper('global_helper');
            $nombre_limpio = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $nombre_limpio);
            $nombre_limpio = limpiar_string($nombre_limpio);
            $nombre_limpio = str_replace(' ', '_', $nombre_limpio);
            $final_name = $nombre_limpio;
            $directorioDestino = FCPATH . 'recursos/clientes_docs/' . $this->input->post('id_cliente') . '/';
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0755, true);
            }
            move_uploaded_file($_FILES['file']['tmp_name'], $directorioDestino . $final_name);
            $parametros['documento'] = $final_name;
            $documento = $this->Clientes_model->nuevo_documento($parametros);
            redirect($redirect);
        }
    }

    public function edit_documento()
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('fecha_estudio', 'Fecha del estudio', 'trim|required');
        $this->form_validation->set_rules('tipo', 'Tipo de estudio', 'trim|required');
        $parametros = $_POST;
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
        } else {
            $estado = $this->Clientes_model->actualizar_documento($parametros);
            if (isset($estado) && $estado == 1) {
                if (isset($parametros['borrado']) && $parametros['borrado'] == 1) {
                    $this->session->set_userdata('errorform', 'Documento borrado');
                } else {
                    $this->session->set_userdata('errorform', 'Documento actualizado');
                }
            } else {
                $this->session->set_userdata('errorform', 'Acción no realizada');
            }
        }
        $redirect = ($this->input->post('redirectto') != '') ? $this->input->post('redirectto') : base_url() . '/clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab12default';
        redirect($redirect);
    }

    public function nuevo_evolutivo()
    {

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_evolutivo', 'Nota evolutivo', 'trim|required');
        $parametros = $_POST;
        $parametros['nota'] = $parametros['nota_evolutivo'];
        // CHAINS 20240223
        if($this->input->post('nota_escritor')!=""){
            $parametros['id_usuario_doctor'] = $this->input->post('nota_escritor');
        }
        // FIN CHAINS 20240223
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect('/clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab2default');
        } else {
            $documento = $this->Clientes_model->nuevo_evolutivo($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect('clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab2default');
        }
    }

    public function editar_evolutivo()
    {

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_evolutivo', 'Nota evolutivo', 'trim|required');
        $parametros = $_POST;

        $parametros['nota'] = $parametros['nota_evolutivo'];
        // CHAINS 20240223
        if($this->input->post('nota_escritor')!=""){
            $parametros['id_usuario_doctor'] = $this->input->post('nota_escritor');
        }
        // 20240312
        if($this->input->post('fecha_nota')!=""){
                $fnota=$this->input->post('fecha_nota')." ".date("H:i:s");

            $parametros['fecha_nota']=$fnota;
        }
        // FIN CHAINS 20240223

        //printr($parametros);
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect('/clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab2default');
        } else {
            $documento = $this->Clientes_model->editar_evolutivo($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect('clientes/historial/ver/' . $this->input->post('id_cliente') . '?tab2default');
        }
    }

    public function borrar_evolutivo($id, $id_cliente)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros['borrado'] = 1;
        $parametros['id'] = $id;
        $documento = $this->Clientes_model->editar_evolutivo($parametros);
        $this->session->set_userdata('msn_borrado', '1');
        redirect('clientes/historial/ver/' . $id_cliente . '?tab2default');
    }

    public function nuevo_evolutivo_popup()
    {

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_evolutivo', 'Nota evolutivo', 'trim|required');
        $parametros = $_POST;
        $parametros['nota'] = $parametros['nota_evolutivo'];
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect('/clientes/historialpopup/ver/' . $this->input->post('id_cliente') . '?tab2default');
        } else {
            $documento = $this->Clientes_model->nuevo_evolutivo($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect('clientes/historialpopup/ver/' . $this->input->post('id_cliente') . '?tab2default');
        }
    }

    public function editar_evolutivo_popup()
    {

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_evolutivo', 'Nota evolutivo', 'trim|required');
        $parametros = $_POST;

        $parametros['nota'] = $parametros['nota_evolutivo'];
        //printr($parametros);
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect('/clientes/historialpopup/ver/' . $this->input->post('id_cliente') . '?tab2default');
        } else {
            $documento = $this->Clientes_model->editar_evolutivo($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect('clientes/historialpopup/ver/' . $this->input->post('id_cliente') . '?tab2default');
        }
    }

    public function borrar_evolutivo_popup($id, $id_cliente)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros['borrado'] = 1;
        $parametros['id'] = $id;
        $documento = $this->Clientes_model->editar_evolutivo($parametros);
        $this->session->set_userdata('msn_borrado', '1');
        redirect('clientes/historialpopup/ver/' . $id_cliente . '?tab2default');
    }

    /***NOTAS INTERNAS DE PACIENTES */

    function crear_tabla_notas_internas(){
        $q = "CREATE TABLE `clientes_notas_internas` (
            `id_nota_interna` int(11) NOT NULL AUTO_INCREMENT,
            `id_cliente` int(11) DEFAULT NULL,
            `fecha_nota` timestamp NULL DEFAULT NULL,
            `nota` text DEFAULT NULL,
            `tipo` varchar(45) DEFAULT NULL,
            `id_usuario_creador` int(11) DEFAULT NULL,
            `fecha_creacion` timestamp NULL DEFAULT NULL,
            `id_usuario_modificacion` int(11) DEFAULT NULL,
            `fecha_modificacion` timestamp NULL DEFAULT NULL,
            `borrado` int(11) DEFAULT NULL,
            `fecha_borrado` timestamp NULL DEFAULT NULL,
            `id_usuario_borrado` int(11) DEFAULT NULL,
            PRIMARY KEY (`id_nota_interna`)
          ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
          $this->db->query($q);
    }

    public function nueva_nota_interna($popup = 0)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_interna_content', 'Nota', 'trim|required');
        $parametros = $_POST;
        $historial = ($popup == 0) ? '/clientes/historial/ver/' : '/clientes/historialpopup/ver/';
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect($historial . $this->input->post('id_cliente') . '?tab14default');
        } else {
            $creada = $this->Clientes_model->nueva_nota_interna($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect($historial . $this->input->post('id_cliente') . '?tab14default');
        }
    }
    public function editar_nota_interna($popup = 0)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_nota_interna', 'ID nota', 'trim|required');
        $this->form_validation->set_rules('id_cliente', 'ID cliente', 'trim|required');
        $this->form_validation->set_rules('nota_interna_content', 'Nota', 'trim|required');
        $this->form_validation->set_rules('fecha_nota_interna', 'Fecha', 'trim|required');
        $parametros = $_POST;
        $historial = ($popup == 0) ? '/clientes/historial/ver/' : '/clientes/historialpopup/ver/';
        if ($this->form_validation->run() != true) {
            $this->session->set_userdata('errorform', validation_errors());
            redirect($historial . $this->input->post('id_cliente') . '?tab14default');
        } else {
            $documento = $this->Clientes_model->editar_nota_interna($parametros);
            $this->session->set_userdata('msn_estado', 1);
            redirect($historial . $this->input->post('id_cliente') . '?tab14default');
        }
    }

    public function borrar_nota_interna($id_nota_interna, $id_cliente, $popup = 0)
    {
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros['borrado'] = 1;
        $parametros['id_nota_interna'] = $id_nota_interna;
        $documento = $this->Clientes_model->editar_nota_interna($parametros);
        $this->session->set_userdata('msn_borrado', '1');
        $historial = ($popup == 0) ? '/clientes/historial/ver/' : '/clientes/historialpopup/ver/';
        redirect($historial . $id_cliente . '?tab14default');
    }


    public function arreglar_fusiones()
    {


        // Ruta al archivo de texto
        $archivo = RUTA_SERVIDOR . "/recursos/logs/fusiones_clientes.log";

        // Leer el archivo línea por línea
        $lineas = file($archivo, FILE_IGNORE_NEW_LINES);

        // Variables para almacenar los números encontrados
        $resultados = [];

        // Recorrer cada línea del archivo
        $fusiones = 0;
        $fusiones_array = [];
        foreach ($lineas as $linea) {
            // Comprobar si la línea contiene "Cliente más Antiguo"
            if (strpos($linea, 'INICIO PROCESO FUSIÓN') !== false) {
                $fusiones++;
            }

            // Comprobar si la línea contiene "Cliente más Antiguo"
            if (strpos($linea, 'Cliente más Antiguo') !== false) {
                // Obtener el número después de "id"
                preg_match('/id (\d+)/', $linea, $matches);
                if(isset($matches[1])){
                    $fusiones_array[$fusiones]['clientes_marcados'][] = $matches[1];
                }
            }

            // Comprobar si la línea contiene "Copiando datos del cliente"
            if (strpos($linea, 'Copiando datos del cliente,') !== false) {
                // Obtener el número después de "id"
                preg_match('/id (\d+)/', $linea, $matches);
                if(isset($matches[1])){
                    $fusiones_array[$fusiones]['clientes_marcados'][] = $matches[1];
                }
            }
        }
        //printr($fusiones_array);
        // Imprimir los resultados
        foreach ($fusiones_array as $array) {
           $this->Clientes_model->arreglar_fusiones($array);
        }
    }


    //(Alfonso) vista de facturacion a clientes ******************/
    //cargar lista de clientes para facturar
    function facturacion(){
        $data['pagetitle'] = 'Nueva factura';
        $param['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($param);
        //var_dump($data['centros']);exit;
        $data['content_view'] = $this->load->view('facturas/llenado_factura', $data, true);
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }
    //consulta servicios pagados y no facturados
    function servicios_pagados($id_cliente = null, $id_centro=null){
        //traer los servicios presupuestados a este cliente en ese centro
        //$data['presupuestos'] = $this->Clientes_model->servicios_presupuesto($id_cliente);
        $data['servicios'] = $this->Clientes_model->servicios_pagados($id_cliente);
        $param['id_cliente'] = $id_cliente;
        $data['id_cliente'] = $id_cliente;
        $data['id_usuario'] = $this->session->userdata('id_usuario');
        $data['cliente'] = $this->Clientes_model->leer_clientes($param);
        $data['id_centro_facturar']=$id_centro;
        $data['saldo_cliente'] = $this->Clientes_model->saldo_clientes($id_cliente);
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 14);
        if ($permiso) {
            $this->load->view('dietario/dietario_facturacion_servicios', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    function checkinfocompleta($id_cliente){
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $fields=[];
        $cliente=[];
        $param=[];
        $param['id_cliente'] = $id_cliente;
        $registros = $this->Clientes_model->leer_clientes($param);
        $completo=true;
        if($registros>0){
            $cliente=$registros[0];
            // Obligatorios
            /* nombre
            appellidos
            direccion
            codigo postal
            telefono
            fecha de nacimiento
            dni
            */
            $fecNac=true;
            if(isset($cliente['nombre']) && !empty($cliente['nombre'])){} else{$fields[]='nombre'; $completo=false;}
            if(isset($cliente['apellidos']) && !empty($cliente['apellidos'])){} else{$fields[]='apellidos'; $completo=false;}
            if(isset($cliente['direccion']) && !empty($cliente['direccion'])){} else{$fields[]='direccion'; $completo=false;}
            if(isset($cliente['fecha_nacimiento_aaaammdd']) && !empty($cliente['fecha_nacimiento_aaaammdd'])){
                if($cliente['fecha_nacimiento_aaaammdd']=='0000-00-00'){
                    $fields[]='fecha_nacimiento_aaaammdd'; $completo=false; $fecNac=false;
                }
            } else{$fields[]='fecha_nacimiento_aaaammdd'; $completo=false; $fecNac=false;}
            if($fecNac) {
                if (isset($cliente['edad']) && $cliente['edad'] > 14) {
                    if (isset($cliente['dni']) && !empty($cliente['dni'])) {
                    } else {
                        $fields[] = 'dni';
                        $completo = false;
                    }
                } else {
                    if (isset($cliente['dni_tutor']) && !empty($cliente['dni_tutor'])) {
                    } else {
                        $fields[] = 'dni_tutor';
                        $completo = false;
                    }
                }
            }
            else{
                if (isset($cliente['dni']) && !empty($cliente['dni'])){}else{ $fields[] = 'dni'; $completo = false;}
            }
            //if(isset($cliente['edad']) && !empty($cliente['edad'])){} else{$fields[]='edad'; $completo=false;}
            if(isset($cliente['telefono']) && !empty($cliente['telefono'])){} else{$fields[]='telefono'; $completo=false;}
            if(isset($cliente['codigo_postal']) && !empty($cliente['codigo_postal'])){} else{$fields[]='codigo_postal'; $completo=false;}


        }
        else $completo=false;
        echo json_encode(array('ok'=>$completo,'fields'=>$fields,'client'=>$cliente));
    }
}
