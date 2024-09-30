<?php
class Avisos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper(array('form', 'url'));

        $this->load->model('Avisos_model');

        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
    }

    function recordarorios_contenido()
    {
        unset($parametros);
        $parametros['fecha_desde'] = date('Y-m-d', strtotime('-1 month', strtotime(date("Y-m-d"))));
        $parametros['fecha_hasta'] = date("Y-m-d H:i:s");
        $parametros['estado'] = "Pendiente";
        $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        // ... Leemos todos los recordatorios en base a los filtros
        $data['recordatorios'] = $this->Avisos_model->recordatorios($parametros);

        if ($data['recordatorios'] != 0) {
            $html = $this->load->view('avisos/recordatorios_listado_view', $data, true);
            $this->output->set_content_type('text/html');
            $this->output->set_output($html);
        } else {
            $this->output->set_content_type('text/html');
            $this->output->set_output("");
        }
    }

    function recordatorios()
    {
        $id_centro = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        unset($parametros);
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;

        // ... Controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            $id_centro = "";
        } else {
            $id_centro = $this->session->userdata('id_centro_usuario');
        }

        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-1 month', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fechaMesSiguiente = strtotime('+1 month', strtotime(date("Y-m-d")));
            $fecha_hasta = date("Y-m-d", $fechaMesSiguiente);
        }
        /*
        unset($parametros);
        $parametros['vacio'] = "";
        if ($fecha_desde != "") {
            $parametros['fecha_desde'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $parametros['fecha_hasta'] = $fecha_hasta . " 23:59:59";
        }
        if ($id_centro > 0) {
            $parametros['id_centro'] = $id_centro;
        }

        // ... Leemos todos los recordatorios en base a los filtros
        $data['registros'] = $this->Avisos_model->recordatorios($parametros);
        */
        // ... Datos a pasar al contenido
        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        $data['estado'] = $this->session->flashdata('estado');
        $data['borrado'] = $this->session->flashdata('borrado');
        $data['repetir'] = $this->session->flashdata('repetir');

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Recordatorios';
        $data['actionstitle'] = ['<form id="form_recordatorio" action="'.base_url().'avisos/nuevo_recordatorio" role="form" method="post" name="form_recordatorio"><button type="submit" class="btn btn-primary text-inverse-primary">Nuevo recordatorio</button></form>'];
        $data['content_view'] = $this->load->view('avisos/recordatorios_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 48);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }
    
    public function get_recordatorios($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            'recordatorios.fecha_hora AS fecha_hora_ddmmaaaa_hhss',
            'CONCAT(usuarios.nombre, " ", usuarios.apellidos) as usuario_creador',
            'recordatorios.estado',
            'recordatorios.recordatorio',
            'centros.nombre_centro',
            'recordatorios.id_recordatorio',

		];
		$tabla = 'recordatorios';
		$join = [
            'usuarios' => 'usuarios.id_usuario = recordatorios.id_usuario_creacion',
            'centros' => 'centros.id_centro = recordatorios.id_centro'
        ];
		$add_rule = [];
		$where = ['recordatorios.borrado' => 0];

		
		if ($this->input->get('id_centro') != '') {
			$where['recordatorios.id_centro'] = $this->input->get('id_centro');
		}
		
        if ($this->input->get('fecha_desde') != ''){
            $where['recordatorios.posponer >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['recordatorios.posponer <='] = $this->input->get('fecha_hasta');
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

    function nuevo_recordatorio()
    {
        $data['accion'] = "nuevo";

        $data['horarios'] = $this->Avisos_model->horarios_recordatorios();

       // ... Viewer con el contenido
        $data['pagetitle'] = 'Nuevo recordatorio';
        $data['content_view'] = $this->load->view('avisos/recordatorios_nuevoeditar_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 48);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function editar_recordatorio($id_recordatorio = 0)
    {
        $data['accion'] = "editar";

        $data['horarios'] = $this->Avisos_model->horarios_recordatorios();

        // ... Leemos todos los recordatorios en base a los filtros
        $parametros['id_recordatorio'] = $id_recordatorio;
        $data['registros'] = $this->Avisos_model->recordatorios($parametros);

       // ... Viewer con el contenido
        $data['pagetitle'] = '';
        $data['content_view'] = $this->load->view('avisos/recordatorios_nuevoeditar_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 48);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }


    function guardar_recordatorio()
    {
        $recordatorio['fecha_hora'] = $this->input->post('fecha') . " " . $this->input->post('hora') . ":00";
        $recordatorio['posponer'] = $recordatorio['fecha_hora'];
        $recordatorio['estado'] = $this->input->post('estado');
        $recordatorio['repetir'] = $this->input->post('repetir');
        $recordatorio['recordatorio'] = $this->input->post('recordatorio');
        $recordatorio['id_centro'] = $this->session->userdata('id_centro_usuario');
        $recordatorio['id_usuario_creacion'] = $this->session->userdata('id_usuario');
        $recordatorio['fecha_creacion'] = date("Y-m-d H:i:s");
        $recordatorio['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $recordatorio['fecha_modificacion'] = date("Y-m-d H:i:s");
        $recordatorio['borrado'] = 0;

        $this->Avisos_model->guardar_recordatorio($recordatorio);

        $this->session->set_flashdata('estado', '1');

        header("Location: " . RUTA_WWW . "/avisos/recordatorios");
    }

    function actualizar_recordatorio($id_recordatorio = 0)
    {
        $recordatorio['fecha_hora'] = $this->input->post('fecha') . " " . $this->input->post('hora') . ":00";
        $recordatorio['posponer'] = $recordatorio['fecha_hora'];
        $recordatorio['estado'] = $this->input->post('estado');
        $recordatorio['repetir'] = $this->input->post('repetir');
        $recordatorio['recordatorio'] = $this->input->post('recordatorio');
        $recordatorio['id_centro'] = $this->session->userdata('id_centro_usuario');
        $recordatorio['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
        $recordatorio['fecha_modificacion'] = date("Y-m-d H:i:s");

        $this->Avisos_model->actualizar_recordatorio($recordatorio, $id_recordatorio);

        $this->session->set_flashdata('estado', '1');

        header("Location: " . RUTA_WWW . "/avisos/recordatorios");
    }

    function borrar_recordatorio($id_recordatorio = 0)
    {
        $recordatorio['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $recordatorio['fecha_borrado'] = date("Y-m-d H:i:s");
        $recordatorio['borrado'] = 1;

        $this->Avisos_model->actualizar_recordatorio($recordatorio, $id_recordatorio);

        $this->session->set_flashdata('borrado', '1');

        header("Location: " . RUTA_WWW . "/avisos/recordatorios");
    }

    function recordarorio_realizado($id_recordatorio = 0)
    {

        $parametros['id_recordatorio'] = $id_recordatorio;
        $data['registros'] = $this->Avisos_model->recordatorios($parametros);

        switch (($data['registros'][0]['repetir'])) {

            case "1":
                $recordatorio['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $recordatorio['fecha_modificacion'] = date("Y-m-d H:i:s");
                $recordatorio['estado'] = "Realizado";
                $this->Avisos_model->actualizar_recordatorio($recordatorio, $id_recordatorio);
                break;

            case "2":

                $AqConexion_model = new AqConexion_model();
                $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
                $parametros['id_recordatorio'] = $data['registros'][0]['id_recordatorio'];

                $sentenciaSQL = "UPDATE recordatorios set posponer = DATE_ADD(posponer, INTERVAL 1 DAY),
         fecha_hora = DATE_ADD(fecha_hora, INTERVAL 1 DAY)
         where id_recordatorio = @id_recordatorio";

                $AqConexion_model->no_select($sentenciaSQL, $parametros);
                break;

            case "3":

                $AqConexion_model = new AqConexion_model();
                $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
                $parametros['id_recordatorio'] = $data['registros'][0]['id_recordatorio'];

                $sentenciaSQL = "UPDATE recordatorios set posponer = DATE_ADD(posponer, INTERVAL 1 WEEK),
         fecha_hora = DATE_ADD(fecha_hora, INTERVAL 1 WEEK)
         where id_recordatorio = @id_recordatorio";
                $AqConexion_model->no_select($sentenciaSQL, $parametros);
                break;

            case "4":

                $AqConexion_model = new AqConexion_model();
                $parametros['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
                $parametros['id_recordatorio'] = $data['registros'][0]['id_recordatorio'];

                $sentenciaSQL = "UPDATE recordatorios set posponer = DATE_ADD(posponer, INTERVAL 1 MONTH),
         fecha_hora = DATE_ADD(fecha_hora, INTERVAL 1 MONTH)
         where id_recordatorio = @id_recordatorio";
                $AqConexion_model->no_select($sentenciaSQL, $parametros);
                break;
        }
    }

    function recordarorios_posponer($id_recordatorio = 0)
    {
        $this->Avisos_model->recordarorios_posponer($id_recordatorio);
    }

    function citas_espera()
    {
        $id_centro = 0;
        $fecha_desde = "";
        $fecha_hasta = "";

        unset($parametros);
        //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
        $parametros = $_POST;
        // ... Controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') == 0) {
            $id_centro = "";
        } else {
            $id_centro = $this->session->userdata('id_centro_usuario');
        }

        if (isset($parametros['id_centro'])) {
            $id_centro = $parametros['id_centro'];
        }
        if (isset($parametros['fecha_desde'])) {
            $fecha_desde = $parametros['fecha_desde'];
        } else {
            $fechaMesPasado = strtotime('-1 month', strtotime(date("Y-m-d")));
            $fecha_desde = date('Y-m-d', $fechaMesPasado);
        }
        if (isset($parametros['fecha_hasta'])) {
            $fecha_hasta = $parametros['fecha_hasta'];
        } else {
            $fechaMesSiguiente = strtotime('+1 month', strtotime(date("Y-m-d")));
            $fecha_hasta = date("Y-m-d", $fechaMesSiguiente);
        }

        unset($parametros);
        $parametros['vacio'] = "";
        if ($fecha_desde != "") {
            $parametros['fecha_hora_inicio'] = $fecha_desde . " 00:00:00";
        }
        if ($fecha_hasta != "") {
            $parametros['fecha_hora_fin'] = $fecha_hasta . " 23:59:59";
        }
        if ($id_centro > 0) {
            $parametros['id_centro'] = $id_centro;
        }

        // ... Leemos todos los recordatorios en base a los filtros
        $data['registros'] = $this->Avisos_model->citas_espera($parametros);

        // ... Datos a pasar al contenido
        unset($parametros);
        $parametros['vacio'] = "";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);

        $data['id_centro'] = $id_centro;
        $data['fecha_desde'] = $fecha_desde;
        $data['fecha_hasta'] = $fecha_hasta;

        $data['estado'] = $this->session->flashdata('estado');
        $data['borrado'] = $this->session->flashdata('borrado');

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Citas en espera';
        $data['actionstitle'] = ['<button type="button" onclick="CitasEspera()" class="btn btn-primary text-inverse-primary">Nueva Cita en Espera</button>'];
        $data['content_view'] = $this->load->view('avisos/citas_espera_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 49);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    public function get_citas_espera($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            // FECHA EN FORMATO Y-M-d H:i:s para ordenacion correcta
            // nombre del cliente
            // servicio,
            // empleado 
            // estado 
            // modificacion
            // centro
            

		];
		$tabla = 'recordatorios';
		$join = [
            'usuarios' => 'usuarios.id_usuario = recordatorios.id_usuario_creacion',
            'centros' => 'centros.id_centro = recordatorios.id_centro'
        ];
		$add_rule = [];
		$where = ['recordatorios.borrado' => 0];

		
		if ($this->input->get('id_centro') != '') {
			$where['recordatorios.id_centro'] = $this->input->get('id_centro');
		}
		
        if ($this->input->get('fecha_desde') != ''){
            $where['recordatorios.posponer >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['recordatorios.posponer <='] = $this->input->get('fecha_hasta');
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

    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    // ... CONTROL DE CITAS EN ESPERA
    // ----------------------------------------------------------------------------- //
    // ----------------------------------------------------------------------------- //
    function citas_espera_gestion($accion = null, $id_cita = null, $id_empleado = null, $fecha_cita = null, $hora_cita = null, $id_cliente_cita = null)
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
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
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
                        unset($param3);
                        $param3['id_cliente'] = $parametros['id_cliente'];
                        $cliente_notas = $this->Clientes_model->leer_clientes($param3);
                        $data['notas_cliente'] = $cliente_notas[0]['notas'];
                    }
                } else {
                    if (isset($parametros['observaciones'])) {
                        $data['notas_cliente'] = $parametros['observaciones'];
                    }
                }

                if (isset($parametros['id_empleado'])) {
                    $data['cita'][0]['id_usuario_empleado'] = $parametros['id_empleado'];

                    unset($param);
                    $param['id_empleado'] = $parametros['id_empleado'];
                    $data['servicios'] = $this->Servicios_model->leer_servicios($param);
                }

                if (isset($parametros['solo_este_empleado'])) {
                    $data['cita'][0]['solo_este_empleado'] = $parametros['solo_este_empleado'];
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
                        $data['horas_libres'] = $this->Agenda_model->horas_libres_sin_control($param);
                    }
                }

                if (isset($parametros['hora'])) {
                    $data['cita'][0]['hora_inicio'] = $parametros['hora'];
                }

                if (isset($parametros['hora_fin'])) {
                    $data['cita'][0]['hora_fin'] = $parametros['hora_fin'];
                }
            }
        }

        // ---------------------------------------
        // ... Editar cita
        // ---------------------------------------
        if ($accion == "editar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
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

                $id_cliente_cita = $this->Clientes_model->nuevo_cliente($parametros);
            }

            unset($param);
            $param['id_cita_espera'] = $id_cita;
            $data['cita'] = $this->Avisos_model->citas_espera($param);

            $data['cita'][0]['id_usuario_empleado_actual'] = $data['cita'][0]['id_usuario_empleado'];

            $data['id_servicio_ultimo_marcado'] = $data['cita'][0]['id_servicio'];

            if ($id_cliente_cita != null) {
                $parametros['id_cliente'] = $id_cliente_cita;
            }

            if (isset($parametros['id_cliente'])) {
                $data['cita'][0]['id_cliente'] = $parametros['id_cliente'];

                unset($param5);
                $param5['id_cliente'] = $parametros['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

                $data['notas_cliente'] = "";

                // ... Leemos las notas que pueda tener el cliente asignadas
                // para mostrarlas cuando es una cita nueva.
                //if (isset($parametros['observaciones'])) {
                //  if ($parametros['observaciones']=="") {
                //    unset($param3);
                //    $param3['id_cliente']=$parametros['id_cliente'];
                //    $cliente_notas=$this->Clientes_model->leer_clientes($param3);
                //    $data['notas_cliente']=$cliente_notas[0]['notas'];
                //  }
                //  else {
                //    $data['notas_cliente']=$parametros['observaciones'];
                //  }
                //}
                //else {
                //  $data['notas_cliente']=$data['cita'][0]['observaciones'];
                //}
            } else {
                //$data['notas_cliente']=$data['cita'][0]['observaciones'];
                $data['notas_cliente'] = "";

                unset($param5);
                $param5['id_cliente'] = $data['cita'][0]['id_cliente'];
                $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
            }

            if (isset($parametros['id_empleado'])) {
                if ($parametros['id_empleado'] > 0) {
                    $data['cita'][0]['id_usuario_empleado'] = $parametros['id_empleado'];
                } else {
                    $data['cita'][0]['id_usuario_empleado'] = 0;
                }
            }

            if (isset($parametros['solo_este_empleado'])) {
                $data['cita'][0]['solo_este_empleado'] = $parametros['solo_este_empleado'];
            }

            if (isset($parametros['fecha'])) {
                if ($parametros['fecha'] != "") {
                    $parametros['fecha'] = str_replace("-", "/", $parametros['fecha']);
                    $f = explode("/", $parametros['fecha']);

                    // ... Controlo si la fecha viene en formato dd-mm-aaaa o aaaa-mm-dd
                    if (strlen($f[2]) == 4) {
                        $data['cita'][0]['fecha_inicio_aaaammdd'] = $f[2] . "/" . $f[1] . "/" . $f[0];
                        $data['cita'][0]['fecha_inicio'] = $f[0] . "-" . $f[1] . "-" . $f[2];
                    } else {
                        $data['cita'][0]['fecha_inicio_aaaammdd'] = $f[0] . "/" . $f[1] . "/" . $f[2];
                        $data['cita'][0]['fecha_inicio'] = $f[2] . "-" . $f[1] . "-" . $f[0];
                    }
                }
            }

            unset($param);
            $param['id_empleado'] = $data['cita'][0]['id_usuario_empleado'];
            $data['servicios'] = $this->Servicios_model->leer_servicios($param);

            unset($param);
            $param['id_empleado'] = $data['cita'][0]['id_usuario_empleado'];
            $param['fecha'] = $data['cita'][0]['fecha_hora_inicio'];
            //      $param['duracion']=$data['cita'][0]['duracion'];
            //      $param['id_cita_excluir']=$id_cita;

            // ... En caso de que se haya elegido otro servicio para la cita editada
            // paso la nueva duración para el recalculo de horarios disponibles.
            if (isset($parametros['id_servicio'])) {
                unset($param3);
                $param3['id_servicio'] = $parametros['id_servicio'];
                $servicio_elegido = $this->Servicios_model->leer_servicios($param3);

                $param['duracion'] = $servicio_elegido[0]['duracion'];

                $data['id_servicio_ultimo_marcado'] = $parametros['id_servicio'];
            }

            // ... Si se especifica una duracion personalizada,
            // no se guarda la del propio servicio.
            //if (isset($parametros['duracion_nueva'])) {
            //  $data['duracion_nueva']=$parametros['duracion_nueva'];
            //  $param['duracion']=$parametros['duracion_nueva'];
            //}
            //else {
            //  $data['duracion_nueva']=$param['duracion'];
            //}

            // ... Si se cambia de servicio, la duracion nueva vuelve a ser la original
            // del nuevo servicio marcado.
            if (isset($parametros['id_servicio'])) {
                if ($parametros['id_servicio'] != $parametros['id_servicio_ultimo_marcado']) {
                    $data['duracion_nueva'] = $servicio_elegido[0]['duracion'];
                    $param['duracion'] = $servicio_elegido[0]['duracion'];
                }
            }

            $data['horas_libres'] = $this->Agenda_model->horas_libres_sin_control($param);

            unset($param2);
            $param2['id_servicio'] = $data['cita'][0]['id_servicio'];
            if (isset($parametros['id_servicio'])) {
                $param2['id_servicio'] = $parametros['id_servicio'];
                $data['cita'][0]['id_servicio'] = $parametros['id_servicio'];
            }
            $servicio_marcado = $this->Servicios_model->leer_servicios($param2);
            $data['id_familia_servicio'] = $servicio_marcado[0]['id_familia_servicio'];
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Guardar una cita
        // ---------------------------------------
        if ($accion == "guardar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if (isset($parametros)) {
                unset($cita_espera);

                unset($param_serv);
                $param_serv['id_servicio'] = $parametros['id_servicio'];
                $servicio = $this->Servicios_model->leer_servicios($param_serv);

                $cita_espera['id_servicio'] = $parametros['id_servicio'];
                $cita_espera['duracion'] = $servicio[0]['duracion'];
                $cita_espera['id_usuario_empleado'] = $parametros['id_empleado'];
                $cita_espera['id_cliente'] = $parametros['id_cliente'];
                $cita_espera['fecha_hora_inicio'] = $parametros['fecha'] . " " . $parametros['hora'];
                $cita_espera['fecha_hora_fin'] = $parametros['fecha'] . " " . $parametros['hora_fin'];
                $cita_espera['notas'] = $parametros['notas'];
                $cita_espera['posponer'] = date('Y-m-d H:m:s');
                $cita_espera['estado'] = "Pendiente";
                $cita_espera['id_centro'] = $this->session->userdata('id_centro_usuario');
                $cita_espera['id_usuario_creacion'] = $this->session->userdata('id_usuario');
                $cita_espera['fecha_creacion'] = date("Y-m-d H:i:s");
                $cita_espera['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $cita_espera['fecha_modificacion'] = date("Y-m-d H:i:s");
                $cita_espera['borrado'] = 0;

                $id_cita_creada = $this->Avisos_model->guardar_cita_espera($cita_espera);

                // ... Guardamos los servicios / citas adicionales si lo hay.
                for ($i = 2; $i < 7; $i++) {
                    $item = "id_servicio" . $i;
                    if (isset($parametros[$item])) {
                        if ($parametros[$item] > 0) {
                            $cita_espera['id_servicio'] = $parametros[$item];
                            $id_cita_creada = $this->Avisos_model->guardar_cita_espera($cita_espera);
                        }
                    }
                }
            }
        }
        // ---------------------------------------

        // ---------------------------------------
        // ... Modificar una cita
        // ---------------------------------------
        if ($accion == "modificar") {
            unset($parametros);
            //while (list($key, $value) = each($_POST)) { $parametros[$key]=$value; }
            $parametros = $_POST;

            if (isset($parametros)) {
                unset($param_serv);
                $param_serv['id_servicio'] = $parametros['id_servicio'];
                $servicio = $this->Servicios_model->leer_servicios($param_serv);


                /******************************************************/
                /* añadido para editar el estado de la cita en espera */
                /******************************************************/
                if (isset($parametros['estado'])) {
                    $cita_espera['estado'] = $parametros['estado'];
                }
                /******************************************************/
                /* añadido para editar el estado de la cita en espera */
                /******************************************************/


                $cita_espera['id_servicio'] = $parametros['id_servicio'];
                $cita_espera['duracion'] = $servicio[0]['duracion'];
                $cita_espera['id_usuario_empleado'] = $parametros['id_empleado'];
                $cita_espera['id_cliente'] = $parametros['id_cliente'];
                $cita_espera['fecha_hora_inicio'] = $parametros['fecha'] . " " . $parametros['hora'];
                $cita_espera['fecha_hora_fin'] = $parametros['fecha'] . " " . $parametros['hora_fin'];
                $cita_espera['notas'] = $parametros['notas'];
                $cita_espera['posponer'] = date('Y-m-d H:m:s');
                $cita_espera['id_centro'] = $this->session->userdata('id_centro_usuario');
                $cita_espera['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
                $cita_espera['fecha_modificacion'] = date("Y-m-d H:i:s");

                $ok = $this->Avisos_model->actualizar_cita_espera($cita_espera, $id_cita);
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
            $data['cita'][0]['id_cliente'] = $this->Clientes_model->nuevo_cliente($parametros);

            unset($param5);
            $param5['id_cliente'] = $data['cita'][0]['id_cliente'];
            $data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
        }

        if (isset($data['cita'][0]['fecha_inicio_aaaammdd'])) {
            $data['fecha_completa'] = $this->Utiles_model->fecha_completa($data['cita'][0]['fecha_inicio_aaaammdd']);
        } else {
            $data['fecha_completa'] = "";
        }

        // ------------------------------------------------------------------------------
        // ... Leemos los posibles empleados que pueden tener citas.
        // ------------------------------------------------------------------------------
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

        $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);
        // ------------------------------------------------------------------------------
        // ------------------------------------------------------------------------------
        //
        // Notas del cliente elegido
        //
        // ... Modulos del cliente
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 49);
        if ($permiso) {
            $this->load->view('avisos/citas_espera_nuevoeditar_view', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

    function borrar_cita_espera($id_cita_espera = 0)
    {
        $cita_espera['id_usuario_borrado'] = $this->session->userdata('id_usuario');
        $cita_espera['fecha_borrado'] = date("Y-m-d H:i:s");
        $cita_espera['borrado'] = 1;

        $this->Avisos_model->actualizar_cita_espera($cita_espera, $id_cita_espera);

        $this->session->set_flashdata('borrado', '1');

        header("Location: " . RUTA_WWW . "/avisos/citas_espera");
    }


    //Es Original, no se toco, pero no funciona 30/05/20 *********************

    function citas_espera_listado()
    {
        // ... Marcamos como Perdidas las citas en espera que no se han agendado.
        $this->Avisos_model->citas_espera_perdidas();

        $parametros['estado'] = "Pendiente";
        $parametros['posponer_inicio'] = date("Y-m-d H:i:s");
        $parametros['posponer_fin'] = "";
        $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');

        $citas_espera = $this->Avisos_model->citas_espera($parametros);

        $citas_aviso = array();

        if ($citas_espera != 0) {
            $i = 0;
            foreach ($citas_espera as $row) {
                // ... Marcamos como Agendadas las citas que existan en los rangos de fecha
                // de las citas en espera.
                $hecho = $this->Avisos_model->citas_espera_agendadas($row);

                if ($hecho == 0) {
                    $id_cita_espera = $row['id_cita_espera'];
                    $datos = $this->Avisos_model->citas_espera_horas_libres($id_cita_espera);

                    $hora_inicio = strtotime($row['fecha_hora_inicio']);
                    $hora_fin = strtotime($row['fecha_hora_fin']);

                    foreach ($datos as $hora) {
                        $hora = strtotime($row['fecha_aaaammdd'] . " " . $hora);

                        if ($hora >= $hora_inicio && $hora <= $hora_fin) {
                            array_push($citas_aviso, $citas_espera[$i]);
                            break;
                        }
                    }
                }

                $i++;
            }
        }

        if (count($citas_aviso) != 0) {
            $data['citas_aviso'] = $citas_aviso;
            $html = $this->load->view('avisos/citas_espera_listado_view', $data, true);

            $this->output->set_content_type('text/html');
            $this->output->set_output($html);
        } else {
            $this->output->set_content_type('text/html');
            $this->output->set_output("");
        }
    }

    //27/05/20 Provisionl para probar directamente y ver el error que no muestra el Modal
    function citas_espera_listado2()
    {
        // ... Marcamos como Perdidas las citas en espera que no se han agendado.
        $this->Avisos_model->citas_espera_perdidas();

        $parametros['estado'] = "Pendiente";
        $parametros['posponer_inicio'] = date("Y-m-d H:i:s");
        $parametros['posponer_fin'] = "";
        //$parametros['id_centro']=$this->session->userdata('id_centro_usuario');
        $parametros['id_centro'] = 6;

        $citas_espera = $this->Avisos_model->citas_espera($parametros);

        //26/05/20 Para ver qu� pasa

        $c = count($citas_espera);



        echo "Paso 1 " . $citas_espera . " C " . $c . "<br>";

        $citas_aviso = array();

        if ($citas_espera != 0) //Original ($citas_espera != 0)
        {
            echo "Paso 2 ";

            // FOR Creado provisional para obligar a llenar
            /* 
          for ($i=0;$i<$c;$i++){
          echo "Cliente ".$citas_espera[$i]['cliente']."<br>";
          array_push($citas_aviso, $citas_espera[$i]); 
      }  
      */

            $i = 0;
            foreach ($citas_espera as $row) {
                // ... Marcamos como Agendadas las citas que existan en los rangos de fecha
                // de las citas en espera.
                $hecho = $this->Avisos_model->citas_espera_agendadas($row);
                echo "Paso 3 " . $hecho . "<br>";
                if ($hecho == 0) {
                    $id_cita_espera = $row['id_cita_espera'];
                    echo "<br>" . " id_cita_espera: " . $row['id_cita_espera'];
                    $datos = $this->Avisos_model->citas_espera_horas_libres($id_cita_espera);

                    $hora_inicio = strtotime($row['fecha_hora_inicio']);
                    $hora_fin = strtotime($row['fecha_hora_fin']);
                    echo "<br>" . " Fecha_hora_inicio: " . $row['fecha_hora_inicio'] . " Fecha_hora_fin: " . $row['fecha_hora_fin'];
                    foreach ($datos as $hora) {
                        echo "<br>" . " Hora: " . $hora;
                        $hora = strtotime($row['fecha_aaaammdd'] . " " . $hora);
                        echo "<br>" . " Fecha (Hora): " . $row['fecha_aaaammdd'];
                        echo "<br>" . " Paso 4 Hora: " . $hora . " Hora_inicio: " . $hora_inicio . " Hora_fin: " . $hora_fin;

                        if ($hora >= $hora_inicio && $hora <= $hora_fin) {
                            echo "<br>" . "ingres� al if del paso 4";
                            array_push($citas_aviso, $citas_espera[$i]);
                            break;
                        }
                    }
                }

                $i++;
            }
        }

        echo "Paso 5 " . $citas_aviso;
        $c = count($citas_aviso);
        echo "<br>" . " C " . $c;
        if (count($citas_aviso) != 0) {

            for ($i = 0; $i < $c; $i++) {
                echo "Cliente " . $citas_aviso[$i]['cliente'] . "<br>";
            }

            $data['citas_aviso'] = $citas_aviso;
            $html = $this->load->view('avisos/citas_espera_listado_view', $data, true);

            $this->output->set_content_type('text/html');
            $this->output->set_output($html);
        } else {
            $this->output->set_content_type('text/html');
            $this->output->set_output("");
        }
    }

    //Fin del Provisional Para ver el error


    function citas_espera_posponer()
    {
        $this->Avisos_model->citas_espera_posponer();
    }

    //25/03/23
    //tareas
    public function tareas()
    {
        $parametros['estado'] = "Pendiente";
        $id_usuario = $this->session->userdata('id_usuario');
        $tareas = $this->Avisos_model->leer_tareas($parametros);
        foreach ($tareas as $row) {
            echo "<br> " . $row['titulo'] . " ";
            $param['id_tarea'] = $row['id'];
            $asignados = $this->Avisos_model->tarea_asignados($param);
            $cadena = "";
            foreach ($asignados as $fila) {
                $cadena .= $fila['nombre'] . " " . $fila['apellidos'] . ",";
            }
            $cadena = substr($cadena, 0, -1);
            echo $cadena;
            echo "<br> Iteaciones de la tarea ";
            $iteracion = $this->Avisos_model->tarea_iteraciones($param);
            foreach ($iteracion as $fila2) {
                echo "<br>" . $fila2['nombre'] . " " . $fila2['apellidos'] . " Comentario: " . $fila2['comentario'] . " " . $fila2['fecha_creacion'];
            }
        } //Foreach  
    } //function tareas

    public function iteraciones_tareas($accion = null, $id = null)
    {
        $parametros['id_tarea'] = $id;
        if ($accion == "editar") {
            $tarea = $this->Avisos_model->leer_tarea_id($parametros);
            $iteraciones = $this->Avisos_model->tarea_iteraciones($parametros);
            $param['id_tarea'] = $id;
            $asignados = $this->Avisos_model->tarea_asignados($param);
            $cadena = "";
            foreach ($asignados as $fila) {
                $cadena .= $fila['nombre'] . " " . $fila['apellidos'] . ",";
            }
            $cadena = substr($cadena, 0, -1);
            $data['id_tarea'] = $id;
            $data['empleados'] = $cadena;
            $data['tarea'] = $tarea;
            $data['iteraciones'] = $iteraciones;
        }

        if ($accion == "grabar") {
            $insertdata['id_tarea'] = $this->input->post('id_tarea');
            $insertdata['id_usuario'] = $this->session->userdata('id_usuario');
            $insertdata['comentario'] = $this->input->post('comentario') . " [" . $this->input->post('estado') . "]";
            $insertdata['fecha_creacion'] = date("Y-m-d H:i:s");
            $this->Avisos_model->insert_tarea_iteracion($insertdata);
            unset($param);
            $updateparams['id'] = $this->input->post('id_tarea');
            $updateparams['estado'] = $this->input->post('estado');
            $this->Avisos_model->update_tarea($updateparams);
            $data['accion'] = "realizar";
        } //Grabar
        $this->load->view('avisos/tarea_iteraciones_view', $data);
    }

    public function leer_tareas($estado = null)
    {
        //if ($this->session->userdata('id_perfil') == 0) {
			// ... Leemos todos los centros
			unset($param2);
			$param2['vacio'] = "";
			$data['centros_todos'] = $this->Usuarios_model->leer_centros($param2);
		//}
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

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Tareas';
        $data['actionstitle'] = ['<button type="button" onclick="NuevaTarea()" class="btn btn-primary text-inverse-primary">Nueva</button>'];
        $data['content_view'] = $this->load->view('avisos/tareas_view', $data, true);

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
    public function crear_vista_tareas(){
        $q = "CREATE VIEW __vista_tareas AS SELECT t.id, t.titulo, t.contenido, t.estado, t.id_creador, CONCAT(u.nombre, ' ', u.apellidos) AS usuario_creador, t.fecha_creacion, t.fecha_modificacion, t.fecha_ejecucion, t.borrado, GROUP_CONCAT(DISTINCT CONCAT(u2.nombre, ' ', u2.apellidos) SEPARATOR ', ') AS usuarios_asignados FROM tareas t LEFT JOIN usuarios u ON t.id_creador = u.id_usuario LEFT JOIN tareas_asignados tu ON t.id = tu.id_tarea LEFT JOIN usuarios u2 ON tu.id_usuario = u2.id_usuario GROUP BY t.id;";
        $this->db->query($q);

    }

    public function get_tareas($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
            '__vista_tareas.fecha_creacion',
            '__vista_tareas.usuario_creador',
            '__vista_tareas.usuarios_asignados AS quienes',
            '__vista_tareas.titulo',
            '__vista_tareas.fecha_ejecucion',
            '__vista_tareas.fecha_modificacion',
            '__vista_tareas.estado',
            'centros.nombre_centro',
            '__vista_tareas.id_creador',
            'usuarios.id_centro',
            '__vista_tareas.id'
        ];
        
		$tabla = '__vista_tareas';
		$join = [
            'usuarios' => 'usuarios.id_usuario = __vista_tareas.id_creador',
            'tareas_asignados' => 'tareas_asignados.id_tarea = __vista_tareas.id',
            'usuarios AS asignados' => 'asignados.id_usuario = tareas_asignados.id_usuario',
            'centros' => 'centros.id_centro = usuarios.id_centro'
        ];
		$add_rule = [];
		$where = [
            '__vista_tareas.borrado' => 0
        ];

        if($this->session->userdata('id_perfil') > 0){
            $add_rule['where'] = 'tareas_asignados.id_creador = '.$this->session->userdata('id_usuario');
            $add_rule['or_where'] = 'tareas_asignados.id_usuario = '.$this->session->userdata('id_usuario');
        }


        if ($this->input->get('id_centro') != '') {
			$where['usuarios.id_centro'] = $this->input->get('id_centro');
		}
        if ($this->input->get('fecha_desde') != ''){
            $where['tareas.fecha_ejecucion >='] = $this->input->get('fecha_desde');
        }
        if ($this->input->get('fecha_hasta') != ''){
            $where['tareas.fecha_ejecucion <='] = $this->input->get('fecha_hasta');
        }
        if ($this->input->get('estado') != ''){
            $where['tareas.estado'] = $this->input->get('estado');
        }


		if (($table != "") && ($columna != "") && ($valor != "")) {
			$where[$table . '.' . $columna] = $valor;
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		} else {
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		}
       

        foreach ($result->data as $key => $value) {
            $param['id_tarea'] = $value->id;
            $asignados = $this->Avisos_model->tarea_asignados($param);
            $cadena_array = [];
            foreach ($asignados as $fila) {
                $cadena_array[] = strtoupper($fila['nombre'] . " " . $fila['apellidos']);
            }
            $cadena = implode('<br>', $cadena_array);
            $result->data[$key]->quienes = $cadena;
        }
		$res = json_encode($result);
		echo $res;
	}

    public function tareas_nueva($accion = null)
    {

        if ($accion == "nueva") {
            // ... Leemos todos los empleados del centro.
            unset($parametros);
            if ($this->session->userdata('id_perfil') != 0) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            // CHAINS 25/08/2024 - Añadir recepcionistas
            //$parametros['solo_empleados'] = 1;
            $parametros['solo_empleados_recepcionistas'] = 1;
            $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);
            $data['accion'] = "nueva";
            $this->load->view('avisos/tarea_nueva_view', $data);
        } //if Nueva 
        if ($accion == "grabar") {
            $id_usuario = $this->session->userdata('id_usuario');
            //tabla tareas
            $parametros['titulo'] = $_POST['titulo'];
            $parametros['estado'] = "Pendiente";
            $parametros['fecha_ejecucion'] = $_POST['fecha_ejecucion'];
            $parametros['contenido'] = $_POST['contenido'];
            $parametros['id_creador'] = $id_usuario;
            $parametros['borrado'] = 0;
            $parametros['fecha_creacion'] = date("Y-m-d H:i:s");
            $parametros['fecha_modificacion'] = date("Y-m-d H:i:s");
            $id_tarea = $this->Avisos_model->insert_tarea($parametros);

            $param['id_tarea'] = $id_tarea;
            $param['id_creador'] = $id_usuario;
            $param['quienes'] = $_POST['quienes'];
            $this->Avisos_model->insert_tareas_asignados($param);
            //$quienes = '';
            /*if (isset($_POST['quienes'])) {

                foreach ($_POST['quienes'] as $value) {
                    unset($param);
                    $param['id_tarea'] = $id_tarea;
                    $param['id_creador'] = $id_usuario;
                    $param['id_usuario'] = $value;
                    $param['fecha_creacion'] = date("Y-m-d H:i:s");
                    $this->Avisos_model->insert_tareas_asignados($param);
                }
            }*/
            $data['accion'] = "realizar";
            $this->load->view('avisos/tarea_nueva_view', $data);
        }
    }

    public function editar_tarea($accion = null, $id = null)
    {
    
        $parametros['id_tarea'] = $id;
        
            $tarea = $this->Avisos_model->leer_tarea_id($parametros);
            if ($this->session->userdata('id_perfil') != 0) {
                $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $parametros['solo_empleados'] = 1;
            $data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);
            $param['id_tarea'] = $id;
            $asignados = $this->Avisos_model->tarea_asignados($param);
            $empleados_asignados = [];
            foreach ($asignados as $key => $value) {
                $empleados_asignados[] = $value['id_usuario'];
            }
            $data['id_tarea'] = $id;
            $data['asignados'] = $empleados_asignados;
            $data['tarea'] = $tarea;
        

        if ($accion == "guardar") {
            // actualizar la tarea
            $updateparams['id'] = $this->input->post('id_tarea');
            $updateparams['titulo'] = $this->input->post('titulo');
            $updateparams['estado'] = $this->input->post('estado');
            $updateparams['fecha_ejecucion'] = $this->input->post('fecha_ejecucion');
            $updateparams['contenido'] = $this->input->post('contenido');
            $this->Avisos_model->update_tarea($updateparams);
            // actualizar empleados asignados
            $id_usuario = $this->session->userdata('id_usuario');
            $param['id_tarea'] = $this->input->post('id_tarea');
            $param['id_creador'] = $id_usuario;
            $param['quienes'] = $this->input->post('quienes');
            $this->Avisos_model->insert_tareas_asignados($param);
            $data['accion'] = "realizar";
        } //Grabar
        $this->load->view('avisos/tarea_editar_view', $data);
    }

    public function borrar_tarea($id_tarea = null)
    {
    
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
            $parametros['id_creador'] = $this->session->userdata('id_usuario');
		}
		$parametros['id_tarea'] = $id_tarea;
		$tarea = $this->Avisos_model->leer_tarea_id($parametros);
		if($tarea == 0){
			$this->session->set_userdata('msn_actionno', 'No puedes borrar esta tarea');
			redirect('avisos/leer_tareas');
		}

        // actualizar la tarea
        $updateparams['id'] = $id_tarea;
        $updateparams['borrado'] = 1;
        $this->Avisos_model->update_tarea($updateparams);
        // actualizar empleados asignados
        /*$id_usuario = $this->session->userdata('id_usuario');
        $param['id_tarea'] = $this->input->post('id_tarea');
        $param['id_creador'] = $id_usuario;
        $param['quienes'] = [];
        $this->Avisos_model->insert_tareas_asignados($param);
        $data['accion'] = "realizar";*/
        $this->session->set_userdata('msn_actionno', 'Tarea borrada');
		redirect('avisos/leer_tareas');
    }

}
