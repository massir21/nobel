<?php
class Liquidaciones extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Liquidaciones_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}


	public function addmaster()
	{
		/*$this->db->query("INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES ('69', 'Lista de Liquidaciones', 'Liquidaciones', 'Empleados', '5', '4', NULL, NULL, NULL, NULL, '0', NULL, NULL, '0');");*/


		/*$this->db->query("ALTER TABLE `liquidaciones_citas` ADD `concepto` VARCHAR(500) NULL AFTER `total`;");*/
	}


	public function index()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
		} else {
			$parametros = [];
		}
		//get centros
		$data['centros'] = $this->Usuarios_model->centros($parametros);

		$data['usuarios'] = $this->Usuarios_model->leer_usuarios($parametros);
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Liquidaciones de empleados';
		//$data['actionstitle'] = ['<a href="' . base_url() . 'liquidaciones/nuevo_presupuesto" class="btn btn-primary text-inverse-primary">Nuevo presupuesto</a>'];
		if ($this->session->userdata('msn_estado') != '') {
			$data['msn_estado'] = $this->session->userdata('msn_estado');
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

		$data['content_view'] = $this->load->view('liquidaciones/liquidaciones_citas_view', $data, true);

		if ($this->input->post('submit') != '') {
			unset($parametros);
			$parametros['programadas_finalizadas'] = 1;
			$parametros['lopd_cumplimentada'] = 1;
			if ($this->input->post('id_empleado') != '') {
				$parametros['id_empleado'] = $this->input->post('id_empleado');
			}
			if ($this->input->post('fecha') != '') {
				$parametros['fecha'] = $this->input->post('fecha');
			}
			if ($this->input->post('fecha_desde') != '') {
				$parametros['fecha_desde'] = $this->input->post('fecha_desde');
			}
			if ($this->input->post('fecha_hasta') != '') {
				$parametros['fecha_hasta'] = $this->input->post('fecha_hasta');
			}
			$data['citas_agenda'] = $this->Agenda_model->leer_citas($parametros);
		}


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
	
	public function get_liquidaciones($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			'liquidaciones.id_liquidacion',
			'liquidaciones.mes',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
			'liquidaciones.fecha_hasta',
			'liquidaciones.total',
			'liquidaciones.fecha_creacion',
			'(CONCAT(U.nombre, " ", U.apellidos)) AS usuario_liquidacion',
			'U.id_centro',
			'centros.nombre_centro AS nombre_centro_usuario', // Agregado para obtener el nombre del centro
			'liquidaciones.estado',
			'liquidaciones.id_usuario',
			'liquidaciones.borrado',
			'liquidaciones.estado'
		];
		
		$tabla = 'liquidaciones';
		
		$join = [
			'usuarios' => 'liquidaciones.id_usuario = usuarios.id_usuario',
			'usuarios U' => 'liquidaciones.id_usuario_modificacion = U.id_usuario',
			'centros' => 'usuarios.id_centro = centros.id_centro' // Agregado para unirse con la tabla centros
		];
		$add_rule = [];
		$where = ['liquidaciones.borrado' => 0];
		if($this->session->userdata('id_perfil') > 0 ){
			$where['usuarios.id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		if ($this->input->get('id_usuario') != '') {
			$where['liquidaciones.id_usuario'] = $this->input->get('id_usuario');
		}
		if ($this->input->get('estado') != '') {
			$where['liquidaciones.estado'] = $this->input->get('estado');
		}
		if ($this->input->get('fecha_desde') != '') {
			$where['liquidaciones.fecha_creacion >='] = $this->input->get('fecha_desde') . " 00:00:00";
		}
		if ($this->input->get('fecha_hasta') != '') {
			$where['liquidaciones.fecha_creacion <='] = $this->input->get('fecha_hasta') . " 23:59:59";
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

	/* public function get_liquidaciones($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			'liquidaciones.id_liquidacion',
			'liquidaciones.mes',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
			'liquidaciones.fecha_hasta',
			'liquidaciones.total',
			'liquidaciones.fecha_creacion',
			'(CONCAT(U.nombre, " ", U.apellidos)) AS usuario_liquidacion',
			'U.id_centro',
			'liquidaciones.estado',
			'liquidaciones.id_usuario',
			'liquidaciones.borrado',
			'liquidaciones.estado'
		];
		$tabla = 'liquidaciones';
		$join = [
			'usuarios' => 'liquidaciones.id_usuario = usuarios.id_usuario',
			'usuarios U' => 'liquidaciones.id_usuario_modificacion = U.id_usuario'
		];
		$add_rule = [];
		$where = ['liquidaciones.borrado' => 0];
	
		// Agregar mensajes de log para depuración
		log_message('debug', 'Campos: ' . print_r($campos, true));
		log_message('debug', 'Join: ' . print_r($join, true));
		log_message('debug', 'Where: ' . print_r($where, true));
	
		if (($table != "") && ($columna != "") && ($valor != "")) {
			$where[$table . '.' . $columna] = $valor;
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		} else {
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		}
	
		// Depurar el resultado
		log_message('debug', 'Resultado: ' . print_r($result, true));
	
		$res = json_encode($result);
		echo $res;
	} */
	

	public function archivarLiquidacion()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
		} else {
			$parametros = [];
		}

		$idLiquidacion = ($_POST['id_liquidacion']);
		$nuevoestado = ($_POST['estado']);
		$archivar = $this->Liquidaciones_model->archivarLiquidacion($idLiquidacion, $nuevoestado);

		$response = ['success' => ($archivar == 1) ? true : false];
		echo json_encode($response);
	}

	public function borrarLiquidacion()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
		} else {
			$parametros = [];
		}

		$idLiquidacion = ($_POST['id_liquidacion']);
		$borrar = $this->Liquidaciones_model->borrarLiquidacion($idLiquidacion);

		$response = ['success' => ($borrar == 1) ? true : false];
		echo json_encode($response);
	}

	function ver_detalle($id_liquidacion)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$para['id_liquidacion'] = $id_liquidacion;
		$data['liquidacion'] = $this->Liquidaciones_model->leer_liquidaciones($para);

		$param['id_liquidacion'] = $id_liquidacion;
		$param['estado'] = 1;
		$data['citas_liquidacion'] = $this->Liquidaciones_model->leer_liquidaciones_citas($param);

		$param2['id_liquidacion'] = $id_liquidacion;
		$param2['estado'] = 1;
		$data['comisiones_liquidacion'] = $this->Liquidaciones_model->leer_liquidaciones_comisiones($param);

		echo $this->load->view('liquidaciones/liquidaciones_ver_detalle', $data, true);
	}



	public function doctor()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		if ($this->session->userdata('msn_estado') != '') {
			$data['estado_msn'] = $this->session->userdata('msn_estado');
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
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);
		if ($this->input->post('id_empleado') != '') {
			unset($param5);
			$param5['id_usuario'] = $this->input->post('id_empleado');
			$data['empleado'] = $this->Usuarios_model->leer_usuarios($param5);

			unset($parametros);
			$parametros['programadas_finalizadas'] = 1;
			$parametros['lopd_cumplimentada'] = 1;
			if ($this->input->post('id_empleado') != '') {
				$parametros['id_usuario'] = $this->input->post('id_empleado');
				$data['id_empleado'] = $this->input->post('id_empleado');
			}
			if ($this->input->post('fecha') != '') {
				$parametros['fecha'] = $this->input->post('fecha');
			}
			/*if ($this->input->post('fecha_desde') != '') {
				$parametros['fecha_desde'] = $this->input->post('fecha_desde');
			} else {
				$parametros['fecha_desde'] = date('Y-m-01', strtotime('first day of last month'));
			}
			$data['fecha_desde'] = $parametros['fecha_desde'];
			*/
			if ($this->input->post('fecha_hasta') != '') {
				$parametros['fecha_hasta'] = $this->input->post('fecha_hasta');
			} else {
				$parametros['fecha_hasta'] = date('Y-m-d');
			}
			$data['fecha_hasta'] = $parametros['fecha_hasta'];
			if ($this->input->post('estado') != '') {
				$parametros['estado'] = $this->input->post('estado');
			} else {
				$parametros['estado'] = 0;
			}
			$data['estado'] = $parametros['estado'];
			// comisiones del usuario
			$citas_agenda = $this->Liquidaciones_model->leer_liquidaciones_citas($parametros);
			/*
			unset($param_comisiones);
			$param_comisiones['id_usuario'] = $this->input->post('id_empleado');
			$comisiones_empleado = $this->Liquidaciones_model->leer_comisiones($param_comisiones);
			foreach ($citas_agenda as $c => $cita) {
				$idServicio = $cita['id_item'];
				$idServicioFamilia = $cita['id_familia_item'];
				$pvpCita = $cita['total'];
				$gastoCita = $cita['gastos_lab'];
				$skipToNextCita = false;
				if (is_array($comisiones_empleado)) {
					foreach ($comisiones_empleado as $key => $comision) {
						$idServicioComision = $comision['id_item'];
						$idServicioFamiliaComision = $comision['id_familia_item'];
						if ($idServicioComision > 0) { // COMISION SOBRE UN SERVICIO
							if ($idServicio === $idServicioComision) {
								$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
								$comisiones_empleado[$key]['gastoacumulado'] += $gastoCita;
								$comisiones_empleado[$key]['num_citas']++;
								$skipToNextCita = true;
								break;
							}
						} else {
							if ($idServicioFamiliaComision > 0) { // COMISION SOBRE UNA FAMILIA SERVICIO
								if ($idServicioFamilia === $idServicioFamiliaComision) {
									$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
									$comisiones_empleado[$key]['gastoacumulado'] += $gastoCita;
									$comisiones_empleado[$key]['num_citas']++;
									$skipToNextCita = true;
									break;
								}
							} else {
								// COMISION SOBRE CUALQUIER SERVICIO
								$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
								$comisiones_empleado[$key]['gastoacumulado'] += $gastoCita;
								$comisiones_empleado[$key]['num_citas']++;
								$skipToNextCita = true;
								break;
							}
						}
					}
				}
				if ($skipToNextCita) {
					continue;
				}
			}
			if (is_array($comisiones_empleado)) {
				foreach ($comisiones_empleado as $key => $co) {
					if ($co['tipo'] == 'tramo') {
						$comisiones_empleado[$key]['valoreuros'] = $this->calcularpagocomisionrango($co);
					} elseif ($co['tipo'] == 'porcentaje') {
						$comisiones_empleado[$key]['valoreuros'] = $this->calcularpagocomisionporcentaje($co);
					} else {
						$comisiones_empleado[$key]['valoreuros'] = $co['comision'] * $co['num_citas'];
					}
				}
			}
			$data['comisiones'] = $comisiones_empleado;
			*/
			$data['citas_agenda_liquidacion'] = $citas_agenda;
			// liquidaciones del empleado
			unset($paramL);
			$paramL['id_usuario'] = $this->input->post('id_empleado');
			$paramL['estado'] = 0; // No entregadas o finalizadas
			$data['liquidaciones'] = $this->Liquidaciones_model->leer_liquidaciones($paramL);

		}
		//exit();
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Buscar periodo de liquidación y doctor';
		$data['content_view'] = $this->load->view('liquidaciones/liquidaciones_buscar_view', $data, true);
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

	public function comercial()
	{
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
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$param['id_perfil'] = 7;
		$param['borrado'] = 0;
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);
		if ($this->input->post('id_empleado') != '') {
			unset($param5);
			$param5['id_usuario'] = $this->input->post('id_empleado');
			$data['empleado'] = $this->Usuarios_model->leer_usuarios($param5);

			unset($parametros);
			$parametros['programadas_finalizadas'] = 1;
			$parametros['lopd_cumplimentada'] = 1;
			if ($this->input->post('id_empleado') != '') {
				$parametros['id_empleado'] = $this->input->post('id_empleado');
				$data['id_empleado'] = $this->input->post('id_empleado');
			}
			if ($this->input->post('fecha') != '') {
				$parametros['fecha'] = $this->input->post('fecha');
			}
			if ($this->input->post('fecha_desde') != '') {
				$parametros['fecha_desde'] = $this->input->post('fecha_desde');
			} else {
				$parametros['fecha_desde'] = date('Y-m-01', strtotime('first day of last month'));
			}
			$data['fecha_desde'] = $parametros['fecha_desde'];
			if ($this->input->post('fecha_hasta') != '') {
				$parametros['fecha_hasta'] = $this->input->post('fecha_hasta');
			} else {
				$parametros['fecha_hasta'] = date('Y-m-t', strtotime('last day of last month'));
			}
			$data['fecha_hasta'] = $parametros['fecha_hasta'];
			$parametros['estado'] = 'Finalizado';
			$citas_agenda = $this->Liquidaciones_model->leer_liquidaciones_citas($parametros);
			// comisiones del usuario
			unset($param_comisiones);
			$param_comisiones['id_usuario'] = $this->input->post('id_empleado');
			$comisiones_empleado = $this->Liquidaciones_model->leer_comisiones($param_comisiones);
			foreach ($citas_agenda as $c => $cita) {
				$idServicio = $cita['id_item'];
				$idServicioFamilia = $cita['id_familia_item'];
				$pvpCita = $cita['total'];
				$skipToNextCita = false;
				if (is_array($comisiones_empleado)) {
					foreach ($comisiones_empleado as $key => $comision) {
						$idServicioComision = $comision['id_item'];
						$idServicioFamiliaComision = $comision['id_familia_item'];
						if ($idServicioComision > 0) { // COMISION SOBRE UN SERVICIO
							if ($idServicio === $idServicioComision) {
								$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
								$comisiones_empleado[$key]['num_citas']++;
								$skipToNextCita = true;
								break;
							}
						} else {
							if ($idServicioFamiliaComision > 0) { // COMISION SOBRE UNA FAMILIA SERVICIO
								if ($idServicioFamilia === $idServicioFamiliaComision) {
									$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
									$comisiones_empleado[$key]['num_citas']++;
									$skipToNextCita = true;
									break;
								}
							} else {
								// COMISION SOBRE CUALQUIER SERVICIO
								$comisiones_empleado[$key]['pvpacumulado'] += $pvpCita;
								$comisiones_empleado[$key]['num_citas']++;
								$skipToNextCita = true;
								break;
							}
						}
					}
				}
				if ($skipToNextCita) {
					continue;
				}
			}
			if (is_array($comisiones_empleado)) {
				foreach ($comisiones_empleado as $key => $co) {
					if ($co['tipo'] == 'tramo') {
						$comisiones_empleado[$key]['valoreuros'] = $this->calcularpagocomisionrango($co);
					} elseif ($co['tipo'] == 'porcentaje') {
						$comisiones_empleado[$key]['valoreuros'] = $this->calcularpagocomisionporcentaje($co);
					} else {
						$comisiones_empleado[$key]['valoreuros'] = $co['comision'] * $co['num_citas'];
					}
				}
			}

			$data['comisiones'] = $comisiones_empleado;
			$data['citas_agenda'] = $citas_agenda;
		}
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Buscar periodo de liquidación y comercial';
		$data['content_view'] = $this->load->view('liquidaciones/liquidaciones_buscar_view', $data, true);
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

	public function comisiones_citas()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->input->post('ids_liquidaciones_cita') != '') {
			$param['ids_cita'] = $this->input->post('ids_liquidaciones_cita');
			$citas_agenda = $this->Liquidaciones_model->leer_liquidaciones_para_comisiones($param);
			//printr($this->db->last_query());
			$param_comisiones['id_usuario'] = $this->input->post('id_usuario');
			$comisiones_empleado = $this->Liquidaciones_model->leer_comisiones($param_comisiones);
			$pagos_liquidacion = [];
			
			if(!is_array($comisiones_empleado) || count($comisiones_empleado) < 1){
				$response = ['success' => true, 'comisiones' =>[]];
			}else{
				$comisiones = $this->comisiones_de_citas($citas_agenda, $comisiones_empleado); //$comisiones_empleado;
				$response = ['success' => (count($comisiones) > 0) ? true : false, 'comisiones' => (count($comisiones) > 0) ? $comisiones : []];
			}
			
		} else {
			$response = ['success' => true, 'comisiones' => []];
		}

		echo json_encode($response);
	}

	public function comisiones_citas_liquidacion()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->input->post('ids_liquidaciones_cita') != '' && $this->input->post('id_liquidacion') != '') {
			$param['id_liquidacion'] = $this->input->post('id_liquidacion');
			$citas_agenda_liquidacion = $this->Liquidaciones_model->leer_liquidaciones_para_comisiones($param);
			if(!is_array($citas_agenda_liquidacion)){
				$citas_agenda_liquidacion = [];
			}
			unset($param);
			$param['ids_cita'] = $this->input->post('ids_liquidaciones_cita');
			$citas_agenda_marcadas = $this->Liquidaciones_model->leer_liquidaciones_para_comisiones($param);
			if(!is_array($citas_agenda_marcadas)){
				$citas_agenda_marcadas = [];
			}
			$citas_agenda = array_merge($citas_agenda_liquidacion, $citas_agenda_marcadas);
			//printr($citas_agenda );
			$param_comisiones['id_usuario'] = $this->input->post('id_usuario');
			$comisiones_empleado = $this->Liquidaciones_model->leer_comisiones($param_comisiones);
			$pagos_liquidacion = [];
			
			if(!is_array($comisiones_empleado) || count($comisiones_empleado) < 1){
				$response = [
					'success' => true,
					'comisiones' =>[]
				];
			}else{
				$comisiones = $this->comisiones_de_citas($citas_agenda, $comisiones_empleado); //$comisiones_empleado;
				$response = [
					'success' => (count($comisiones) > 0) ? true : false,
					'comisiones' => (count($comisiones) > 0) ? $comisiones : []
				];
			}
			
		} else {
			$response = ['success' => true, 'comisiones' => []];
		}

		echo json_encode($response);
	}

	public function registrarLiquidacion()
	{
		
		$citasLiquidacion = json_decode($_POST['citas_liquidacion']);
		$citasLiquidacion = implode(',', $citasLiquidacion);
		$comisionesLiquidacion = json_decode($_POST['comisiones_liquidacion'], true);
		$total_liquidacion = 0;
		// se crea la liquidacion
		if($this->input->post('id_liquidacion') == '' || $this->input->post('id_liquidacion') == 0){
			$parametros = [
				'id_usuario' => $this->input->post('id_empleado'),
				'fecha_desde' => $this->input->post('fecha_desde'),
				'fecha_hasta' => $this->input->post('fecha_hasta'),
				'mes' => $this->input->post('mes').'-01',
			];
			$id_liquidacion = $this->Liquidaciones_model->registrar_liquidacion($parametros);
			$actualizar = false;
		}else{
			$id_liquidacion = $this->input->post('id_liquidacion');
			$para['id_liquidacion'] = $id_liquidacion;
			$para['estado'] = 0;
			$liquidacion = $this->Liquidaciones_model->leer_liquidaciones($para);
			if(!is_array($liquidacion )){
				$response = ['success' => false];
				echo json_encode($response);
				exit();
			}
			$total_liquidacion = $liquidacion[0]['total'];
			$actualizar = true;
		}
		
		// se recorren las comisiones, añadiendo los datos a la tabla
		foreach ($comisionesLiquidacion as $key => $value) {
			$idComision = $value['id_comision'];
			$pvpAcumulado = $value['pvpacumulado'];
			$totalComision = $value['total_comision'];
			$parametrosc = [];
			$param['id_comision'] = $value['id_comision'];
			$comision = $this->Liquidaciones_model->leer_comisiones($param);
			if ($comision) {
				$parametrosc  = [
					'id_liquidacion' => $id_liquidacion,
					'id_usuario' => $this->input->post('id_empleado'),
					'id_comision' => $idComision,
					'item' => $comision[0]['item'],
					'id_item' => $comision[0]['id_item'],
					'id_familia_item' => $comision[0]['id_familia_item'],
					'tipo' => $comision[0]['tipo'],
					'importe_desde' => $comision[0]['importe_desde'],
					'importe_hasta' => $comision[0]['importe_hasta'],
					'comision' => $comision[0]['comision'],
					'pvpacumulado' => $pvpAcumulado,
					'total_comision' => $totalComision,
				];
				if($actualizar != true){
					$comision_liquidacion = $this->Liquidaciones_model->registrar_comision_liquidacion($parametrosc);
				}else{
					$comision_liquidacion = $this->Liquidaciones_model->actualizar_comision_liquidacion($parametrosc);
				}
				$total_liquidacion += $totalComision;
			}
		}
		if ($total_liquidacion > 0) {
			$this->Liquidaciones_model->actualizar_total_liquidacion($id_liquidacion, $total_liquidacion);
		}
		$this->Liquidaciones_model->citasLiquidacion($citasLiquidacion, $id_liquidacion);
		$response = ['success' => true];
		echo json_encode($response);
		exit();
	}

	public function liquidaciones_citas()
	{
		$param = [
			'fecha_desde' => '2023-08-01 00:00:00',
			'fecha_hasta' => '2023-10-01 00:00:00',
			'estado' => 'Finalizado'
		];

		$citas = $this->Liquidaciones_model->leer_citas_empleado($param);
		foreach ($citas as $key => $cita) {
			$id_cita = $cita['id_cita'];
			$this->Liquidaciones_model->liquidacion_cita($id_cita);
		}
	}


	private function calcularCosteCita($cita)
	{

		$costefinalcita = $cita['pvp'];
		$param = [
			'id_cita' => $cita['id_cita'],
		];
		$item = $this->Liquidaciones_model->presupuestos_item_detail($param);
		$pvpfinal = $cita['pvp'];
		$dto_propio_euros = 0;
		$dto_presupuesto_euros = 0;
		$comisionItem = 1;
		$gastos_lab = 0;
		if ($item) {
			$pvpfinal = $item[0]['pvp'];
			$id_presupuesto = $item[0]['id_presupuesto'];
			$param = [
				'id_presupuesto' => $id_presupuesto
			];
			$presupuesto = $this->Liquidaciones_model->leer_presupuestos($param)[0];
			$totalPresupuesto = $presupuesto['total_aceptado']; // presupuesto aceptado, con descuento aplicado
			// DESCUENTO PROPIO DEL ITEM
			if ($item[0]['dto'] > 0) {
				$dto_propio_euros = $pvpfinal * ($item[0]['dto'] / 100);
				$pvpfinal = $pvpfinal - $dto_propio_euros;
			}

			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($presupuesto['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $presupuesto['totalpresupuesto'] + $presupuesto['dto_euros'];
				$proporcion = $item[0]['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $presupuesto['dto_euros'], 2);
				$nuevoPVP = $item[0]['coste'] - $dto_presupuesto_euros;
				$item[0]['dtop'] = $dto_presupuesto_euros;
				$item[0]['coste'] = number_format($nuevoPVP, 2);
			} elseif ($presupuesto['dto_100'] > 0) {
				$dtog = $presupuesto['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $presupuesto['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $presupuesto['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item[0]['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item[0]['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item[0]['dtop'] = $dto_presupuesto_euros;
				$item[0]['coste'] = number_format($nuevoPVP, 2);
			}

			// COMISION POR PAGOS FINANCIADOS
            /* CHAINS 20240613 - Eliminamos esta parte
			$comisionTotal = $this->Liquidaciones_model->totalcomisionfinanciacion($id_presupuesto);
			if ($comisionTotal > 0) {
				$proporcion = $pvpfinal / $totalPresupuesto;
				$parteComision = $proporcion * $comisionTotal;
				$comisionItem = number_format($parteComision, 2);
				$pvpfinal = $pvpfinal - $comisionItem;
			}
            */
			// GASTOS DE LABORATORIO
			if ($item[0]['gastos_lab'] > 0) {
				$gastos_lab = $item[0]['gastos_lab'];
				$pvpfinal = $pvpfinal - $gastos_lab;
			}
		}

		return [
			'pvpfinal' => $pvpfinal,
			'dto_propio_euros' => $dto_propio_euros,
			'dto_presupuesto_euros' => $dto_presupuesto_euros,
			'comisionItem' => $comisionItem,
			'gastos_lab' => $gastos_lab
		];
	}

	private function comisiones_de_citas($citas, $comisiones)
	{

		/*
		foreach ($citas as $c => $cita) {
			$idServicio = $cita['id_item'];
			$idServicioFamilia = $cita['id_familia_item'];
			$pvpCita = $cita['total'];
			$skipToNextCita = false;
			if($cita['item'] == 'Servicio'){
				if(is_array($comisiones)){
					foreach ($comisiones as $key => $comision) {
						$idServicioComision = $comision['id_item'];
						$idServicioFamiliaComision = $comision['id_familia_item'];
						if ($idServicioComision > 0) { // COMISION SOBRE UN SERVICIO
							if ($idServicio === $idServicioComision) {
								$comisiones[$key]['pvpacumulado'] += $pvpCita;
								$comisiones[$key]['num_citas']++;
								$skipToNextCita = true; 
								break; 
							}
						} else{
							if ($idServicioFamiliaComision > 0) { // COMISION SOBRE UNA FAMILIA SERVICIO
								if ($idServicioFamilia === $idServicioFamiliaComision) {
									$comisiones[$key]['pvpacumulado'] += $pvpCita;
									$comisiones[$key]['num_citas']++;
									$skipToNextCita = true; 
									break;
								}
							} else {
								// COMISION SOBRE CUALQUIER SERVICIO
								$comisiones[$key]['pvpacumulado'] += $pvpCita;
								$comisiones[$key]['num_citas']++;
								$skipToNextCita = true;
								break;
							}
						}
					}
				}
				if ($skipToNextCita) {
					continue;
				}
			}else{
				$comisiones[] = [
					'borrado' => 0,
					'pvpacumulado' => $pvpCita,
					'id_comision' => 0,
					'id_item' => 0,
					'id_familia_item' => 0,
					'item' => 'Linea generada',
					'tipo' => 'Extra',
					'comision' =>  $pvpCita,
					'num_citas' => 1
				];
			}
		}

		
		
		if(is_array($comisiones)){
			foreach ($comisiones as $key => $co) {		
				if ($co['tipo'] == 'tramo') {
					$comisiones[$key]['valoreuros'] = $this->calcularpagocomisionrango($co);
				} elseif ($co['tipo'] == 'porcentaje') {
					$comisiones[$key]['valoreuros'] = $this->calcularpagocomisionporcentaje($co);
				} else {
					$comisiones[$key]['valoreuros'] = $co['comision'] * $co['num_citas'];
				}
			}
		}
		*/
		// Itera sobre las comisiones por tramo y busca las citas que cumplen las condiciones
		foreach ($comisiones as $key => $co) {
			foreach ($citas as &$cita) {
				if (isset($cita['evaluada']) && $cita['evaluada']){
					if(!isset($cita['evaluada_tramo'])) {
						continue;
					}
				}
				if ($co['id_item'] > 0) {
					if(isset($cita['evaluada_tramo']) && $cita['evaluada_tramo'] != 'id_item'){
						continue;
					}
					$cumpleCondicion = $cita['id_item'] == $co['id_item'];
					if ($co['tipo'] == 'tramo') {
						$cita['evaluada_tramo'] = 'id_item';
					}
				} elseif ($co['id_familia_item'] > 0) {
					if(isset($cita['evaluada_tramo']) && $cita['evaluada_tramo'] != 'id_familia_item'){
						continue;
					}
					$cumpleCondicion = $cita['id_familia_item'] == $co['id_familia_item'];
					if ($co['tipo'] == 'tramo') {
						$cita['evaluada_tramo'] = 'id_familia_item';
					}
				} else {
					if(isset($cita['evaluada_tramo']) && $cita['evaluada_tramo'] != 'item'){
						continue;
					}
					$cumpleCondicion = strtoupper($cita['item']) == strtoupper($co['item']);
					if ($co['tipo'] == 'tramo') {
						$cita['evaluada_tramo'] = 'item';
					}
				}
				if ($cumpleCondicion) {
					$cita['evaluada'] = true;
					$co['pvpacumulado'] += $cita['total'];
					$co['num_citas']++;
				}
			}
			if ($co['tipo'] == 'tramo') {
				$valores = $this->calcularpagocomisionrango($co);
				/*$comisiones[$key]['valoreuros'] = $valores['valoreuros'];
				$comisiones[$key]['valorable'] = $valores['valorable'];*/
			} elseif ($co['tipo'] == 'porcentaje') {
				$valores = $this->calcularpagocomisionporcentaje($co);
			} else {
				$comisiones[$key]['valoreuros'] = $co['comision'] * $co['num_citas'];
				$valores = [
					'valorable' => 0,
					'gastoslab' => 0,
					'valoreuros' => $co['comision'] * $co['num_citas']
				];
			}
			$comisiones[$key]['valoreuros'] = $valores['valoreuros'];
			$comisiones[$key]['gastoslab'] = $valores['gastoslab'];
			$comisiones[$key]['valorable'] = $valores['valorable'];
			$comisiones[$key]['pvpacumulado'] = $co['pvpacumulado'];
			$comisiones[$key]['num_citas'] = $co['num_citas'];
		}

		return $comisiones;
	}

	private function calcularpagocomisionrango($c)
	{
		$desde = $c['importe_desde'];
		$hasta = $c['importe_hasta'];
		$cantidad = $c['pvpacumulado'] - $c['gastoacumulado'];
		// calcular e
		if ($cantidad > $desde) {
			if ($cantidad > $hasta) {
				$aplicar = $hasta - $desde;
			} else {
				$aplicar = $cantidad - $desde;
			}
			$comision = $c['comision'];
			$resultado =  $aplicar * $comision / 100;
		} else {
			$aplicar = 0;
			$resultado = 0;
		}
		return ['valorable' => $aplicar, 'gastoslab' => $c['gastoacumulado'], 'valoreuros' => $resultado];
	}

	private function calcularpagocomisionporcentaje($c)
	{
		$cantidad = $c['pvpacumulado'];
		$comision = $c['comision'];
		$gasto = $c['gastoacumulado'];
		$resultadoAp =  $cantidad * $comision / 100;
		$resultadoGL =  $gasto * $comision / 100;
		$resultado =  $resultadoAp - $resultadoGL;
		return ['valorable' => $cantidad, 'gastoslab' => $gasto, 'valoreuros' => $resultado];
	}

	public function cambios_gastos_lab()
	{
		$cambios = $this->input->post('cambios');
		$cambiados = 0;
		foreach ($cambios as $cambio) {
			$param = [];
			$param = [
				'id_presupuesto_item' => $cambio['id_presupuesto_item'],
				'gastos_lab' => $cambio['gastos_lab'],
			];
			$result = $this->Liquidaciones_model->actualizar_gastos_laboratorio($param);
			if ($result == 1) {
				$cambiados++;
			}
		}

		$response = ['success' => ($cambiados == count($cambios)) ? true : false];
		echo json_encode($response);
	}

	public function cambios_datos_cita()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$parametros = $_POST;
		$result = $this->Liquidaciones_model->actualizar_datos_cita_liquidacion($parametros);
		$response = ['success' => ($result == 1) ? true : false];
		echo json_encode($response);
	}


	public function nueva_linea()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$registro['id_usuario'] = $this->input->post('id_usuario');
		$registro['id_presupuesto_item'] = 0;
		$registro['id_cita'] = 0;
		$registro['fecha_cita'] =  date('Y-m-d H:i:s');
		$registro['item'] =  'Servicio';
		$registro['id_item'] = 0;
		$registro['id_familia_item'] =  0;
		$registro['id_cliente'] = 0;
		$registro['pvp'] =  $this->input->post('importe');
		$registro['dto'] = 0;
		$registro['dtop'] = 0;
		$registro['com_financiacion'] = 0;
		$registro['gastos_lab'] = 0;
		$registro['total'] = $this->input->post('importe');
		$registro['concepto'] = $this->input->post('concepto');
		$registro['estado'] = 0;
		$nueva_linea = $this->Liquidaciones_model->nueva_linea($registro);
		$response = ['success' => ($nueva_linea > 1) ? true : false];
		echo json_encode($response);
	}

	public function borrarCitaDeLiquidacion()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') != 0  && $this->session->userdata('id_perfil') != 3) {
			$response = ['success' => false];
			echo json_encode($response);
		}

		$parametros = $_POST;
		$id_liquidacion_cita = $this->input->post('id_liquidacion_cita');
		$id_liquidacion = $this->input->post('id_liquidacion');
		$result = $this->Liquidaciones_model->borrarCitaLiquidacion($id_liquidacion_cita);
		// la cita liquidacion esta ya desvinculada de la liquidacion
		// hay que calcular la liquidacion con las citas que estan en esa liquidacion
		// buscamos las citas de esa liquidacion que esten liquidadas
		unset($param);
		$param['estado'] = 1;
		$param['id_liquidacion'] = $id_liquidacion;
		$citas_liqudadas = $this->Liquidaciones_model->leer_liquidaciones_para_comisiones($param);
		// el id_usuario
		$id_usuario = $citas_liqudadas[0]['id_usuario'];
		// ya tenemos los ids que estan liquidados en esa liquidacion. Leemos las comisiones registradas
		unset($param_com_reg);
		$param_com_reg['id_liquidacion'] = $id_liquidacion;
		$comisiones_registradas = $this->Liquidaciones_model->leer_liquidaciones_comisiones($param_com_reg);
		$ids_comisiones_registradas = [];
		foreach ($comisiones_registradas as $key => $comision_registrada) {
			$ids_comisiones_registradas[] = $comision_registrada['id_comision'];
		}
		// ya tenemos los ids que estan liquidados en esa liquidacion. Leemos las comisiones del usuario
		$param_comisiones['id_usuario'] = $id_usuario;
		$comisiones_empleado = $this->Liquidaciones_model->leer_comisiones($param_comisiones);
		// obtenemos las comisiones y el importe de cada una
		$comisiones = $this->comisiones_de_citas($citas_liqudadas, $comisiones_empleado);
		// recorremos las comisiones actualizadas. Al encontrar
		$comiosionesactualizadas = [];
		$total_liquidacion = 0;
		foreach ($comisiones as $key => $com) {
			// si existe el id_comision entre los que ya estaban registrados
			unset($parametrosc);
			$parametrosc  = [
				'id_liquidacion' => $id_liquidacion,
				'id_usuario' => $id_usuario,
				'id_comision' => $com['id_comision'],
				'item' => $com['item'],
				'id_item' => $com['id_item'],
				'id_familia_item' => $com['id_familia_item'],
				'tipo' => $com['tipo'],
				'importe_desde' => $com['importe_desde'],
				'importe_hasta' => $com['importe_hasta'],
				'comision' => $com['comision'],
				'pvpacumulado' => $com['pvpacumulado'],
				'total_comision' => $com['valoreuros'],
			];
			if (in_array($com['id_comision'], $ids_comisiones_registradas)) {
				// se añade a actualizadas
				$comiosionesactualizadas[] = $com['id_comision'];
				// se actualiza
				$comision_liquidacion = $this->Liquidaciones_model->actualizar_comision_liquidacion($parametrosc);
				$total_liquidacion += $com['valoreuros'];
			} else {
				$comision_liquidacion = $this->Liquidaciones_model->registrar_comision_liquidacion($parametrosc);
			}
			$total_liquidacion += $com['valoreuros'];
		}
		// se recorren las comisiones registradas, para ver si hay que borrar alguna (no actualizada)
		foreach ($ids_comisiones_registradas as $key => $id_comision) {
			if (!in_array($id_comision, $comiosionesactualizadas)) {
				unset($parametros_del);
				$parametros_del  = [
					'id_liquidacion' => $id_liquidacion,
					'id_usuario' => $id_usuario,
					'id_comision' => $id_comision,
				];
				$comision_borrada = $this->Liquidaciones_model->borrar_comision_liquidacion($parametros_del);
			}
		}

		$this->Liquidaciones_model->actualizar_total_liquidacion($id_liquidacion, $total_liquidacion);

		// recalcular la liquidacion
		$response = ['success' => ($result == 1) ? true : false];
		echo json_encode($response);
	}

	public function actualizar_liquidacion($id_liquidacion)
	{
		$this->Liquidaciones_model->actualizar_total_liquidacion($id_liquidacion, 0);
	}

	public function actualizar_liquidaciones($id_usuario = '')
	{
		// buscar las citas de liquidacion
		$this->db->where('id_liquidacion', 0);
		if($id_usuario != ''){
			$this->db->where('id_usuario', $id_usuario);
		}
		$lc = $this->db->get('liquidaciones_citas')->result();
		foreach ($lc as $key => $value) {
			$this->Liquidaciones_model->recalcularCifrasCita($value->id_cita);
		}
		printr($lc);
	}

    public function crear_liquidacion_cita_master($id_cita){
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        if ($this->session->userdata('id_perfil') != 0  && $this->session->userdata('id_perfil') != 3) {
            $response = ['success' => false];
            echo json_encode($response);
        }


        $rtval=$this->Liquidaciones_model->liquidacion_cita($id_cita);
        $response = ['success' => true,'id_liquidacion_cita'=>$rtval];
        echo json_encode($response);
    }
    public function getDientes(){
    	$id_liquidacion=$this->input->post('id_liquidacion');
    	$numero_de_diente=$this->Liquidaciones_model->getDientes($id_liquidacion);
    	$response=['success' => true, 'dientes'=>$numero_de_diente,'id_liquidacion' =>$id_liquidacion];
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
        return;
    }
}
