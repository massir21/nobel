<?php

class Gestion extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		if ($this->Ticket_model->recoger_ticket($this->session->userdata('ticket')) == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$this->load->model('Gestion_model');
		$this->load->model('Estadisticas_model');
		$this->load->model('GestionFacturas_model');
	}

	function index()
	{
		// ... Comprobamos la sesion del servicio
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$parametrosProveedores = ['obsoleto' => false];
		$data['id_centro'] = "0";
		$data['mes'] = date('m');
		$data['ano'] = date('Y');

		if (isset($_POST['id_centro'])) {
			$data['id_centro'] = $_POST['id_centro'];
			$data['mes'] = $_POST['mes'];
			$data['ano'] = $_POST['ano'];
		}
		$data['centros'] = $this->Gestion_model->get_resumen_presupuestos($data['id_centro'], $data['mes'], $data['ano']);
		$data['familias_servicios'] = $this->Gestion_model->get_familias_servicios();
		$data['presupuestos_anuales'] = $this->Gestion_model->presupuestos_anuales($data['id_centro'], $data['ano']);
		$data['presupuestos_doctores'] = $this->Gestion_model->get_presupuestos_doctores($data['id_centro'], $data['mes'], $data['ano']);
		$data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
		$data['citas_produccion'] = $this->Gestion_model->produccion_citas($data);
		$data['doctores_con_cita'] = $this->Gestion_model->doctores_con_cita($data['id_centro'], $data['mes'], $data['ano']);
		//$data['gastos_familias'] = $this->GestionFacturas_model->getListadoGestionFacturasFamilias($data['id_centro'], $data['mes'], $data['ano']);
		$parametros = $data;
		//los años a consultar seran el actual y 4 años anteriores 5 años en total
		$data['todos_anios'] = array();
		for ($i = 0; $i < 5; $i++) {
			$data['todos_anios'][] = date('Y') - $i;
		}
		$data['todos_meses'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

		$data['proveedor'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
		$data['pagetitle'] = 'Control comercial';

		$data['content_view'] = $this->load->view('gestion/comercial/comercial_view', $data, true);



		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
		$this->load->view($this->config->item('template_dir') . '/master', $data);
	}
	function objetivos()
	{
		// ... Comprobamos la sesion del servicio
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$parametrosProveedores = ['obsoleto' => false];
		$data['id_centro'] = "0";
		$data['mes'] = date('m');
		$data['ano'] = date('Y');

		if (isset($_GET['id_centro'])) {
			$data['id_centro'] = $_GET['id_centro'];
			$data['mes'] = $_GET['mes'];
			$data['ano'] = $_GET['ano'];
		}
		$data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
		//los años a consultar seran el actual y 4 años anteriores 5 años en total
		$data['todos_anios'] = array();
		for ($i = 0; $i < 5; $i++) {
			$data['todos_anios'][] = date('Y') - $i;
		}
		$data['todos_meses'] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
		$data['pagetitle'] = 'Objetivos mensuales';
		$data['content_view'] = $this->load->view('gestion/objetivos/objetivos_view', $data, true);
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
		$this->load->view($this->config->item('template_dir') . '/master', $data);
	}
	function load_tabla_objetivos()
	{
		$data['objetivos'] = $this->Gestion_model->get_objetivos();
		$this->load->view('gestion/objetivos/tabla_objetivos', $data);
	}
	function agregar_objetivo()
	{
		// 0 ya existe
		// 1 insertado
		echo $this->Gestion_model->insertar_objetivo($_POST);
	}
	function editar_objetivo()
	{
		$this->Gestion_model->editar_objetivo($_POST);
	}
	function borrar_objetivo($id_objetivo)
	{
		$this->Gestion_model->borrar_objetivo($id_objetivo);
	}
	/*
	public function addModuloPacientes347()
	{
		$this->db->order_by('id_modulo', 'desc');
		$this->db->limit(1);
		$lastm = $this->db->get('modulos')->row();
		$id_modulo = $lastm->id_modulo + 1;
		$this->db->query("INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES (".$id_modulo.", 'Pacientes 347', 'Gestion/pacientes347', 'Gestión', '7', '4', NULL, NULL, NULL, NULL, '0', NULL, NULL, '0');");

	}
	*/
	public function pacientes347()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

       

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Pacientes 347';
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

		$data['content_view'] = $this->load->view('gestion/pacientes347_view', $data, true);

		// ... Modulos del cliente
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

		// ... Pagina master
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 123);
		if ($permiso) {
			$this->load->view($this->config->item('template_dir') . '/master', $data);
		} else {
			header("Location: " . RUTA_WWW . "/errores/error_404.html");
			exit;
		}
	}

	public function get_pacientes347($table = null, $columna = null, $valor = null)
    {

            $this->load->library('Datatable');
			if($this->input->get('minimo') != ''){
				$where['dietario.fecha_hora_concepto >='] = $this->input->get('fecha_desde'). ' 00:00:00';
			}
			$minimo = ($this->input->get('minimo') != '') ? $this->input->get('minimo')  : 3000;
            $campos = [
                'clientes.id_cliente',
                '(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
				'clientes.dni',
                'clientes.email',
                'clientes.telefono',
				'(COALESCE(SUM(CASE WHEN dietario.estado = "Devuelto" THEN -dietario.importe_euros ELSE dietario.importe_euros END ), 0)) AS total_importe',
				'dietario.fecha_hora_concepto',
            ];
            $tabla = 'clientes';
            $join = [
                'dietario' => 'dietario.id_cliente = clientes.id_cliente',
            ];
            $add_rule = [
				'where_not_in' => ['dietario.tipo_pago', ["#saldo_cuenta", "#presupuesto"]],
				'where_in' => ['dietario.estado', ["Pagado", "Devuelto"]],
				'group_by' => 'clientes.id_cliente, clientes.nombre',
				'having' =>'total_importe >= '.$minimo
			];
            $where = [
				'clientes.borrado' => 0,
				'dietario.tipo_pago !=' => 'NULL',
			];

			if($this->input->get('fecha_desde') != ''){
				$where['dietario.fecha_hora_concepto >='] = $this->input->get('fecha_desde'). ' 00:00:00';
			}else{
				$where['dietario.fecha_hora_concepto >='] = date('Y-01-01 00:00:00');
			}
			if($this->input->get('fecha_hasta') != ''){
				$where['dietario.fecha_hora_concepto <='] = $this->input->get('fecha_hasta'). ' 23:59:59';
			}else{
				$where['dietario.fecha_hora_concepto <='] = date('Y-12-31 23:59:59');
			}

            if (($table != "") && ($columna != "") && ($valor != "")) {
                $where[$table . '.' . $columna] = $valor;
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            } else {
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            }
			$result->query = $this->db->last_query();
			//printr($result);
            $res = json_encode($result);
       

        echo $res;
    }

	public function addModuloCtrlGLab()
	{
		$this->db->order_by('id_modulo', 'desc');
		$this->db->limit(1);
		$lastm = $this->db->get('modulos')->row();
		$id_modulo = $lastm->id_modulo + 1;
		$this->db->query("INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES (".$id_modulo.", 'Control G. Lab', 'Gestion/ctrlGLab', 'Gestión', '7', '5', NULL, NULL, NULL, NULL, '0', NULL, NULL, '0');");
	}

	public function ctrlGLab()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		// modulo relacionado
		$this->db->where('url', 'Gestion/ctrlGLab');
		$this->db->where('borrado', 0);
		$this->db->order_by('id_modulo', 'desc');
		$this->db->limit(1);
		$id_modulo = $this->db->get('modulos')->row()->id_modulo;

		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
		} else {
			$parametros = [];
		}
		//get centros
		$data['centros'] = $this->Usuarios_model->centros($parametros);
		$data['usuarios'] = $this->Usuarios_model->leer_usuarios($parametros);

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Control de Gastos de laboratorio';
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
		$param = [];
		
		if($this->input->post('fecha_desde') != ''){
			$param['fecha_desde'] = $this->input->post('fecha_desde');
		}
		if($this->input->post('fecha_hasta') != ''){
			$param['fecha_hasta'] = $this->input->post('fecha_hasta');
		}
		if($this->input->post('estado') != ''){
			$param['estado'] = $this->input->post('estado');
		}
		if($this->input->post('id_cliente') != ''){
			$param['id_cliente'] = $this->input->post('id_cliente');
		}
		if($this->input->post('id_usuario') != ''){
			$param['id_usuario'] = $this->input->post('id_usuario');
		}
		
		$datos = $this->Gestion_model->ctrlGLab($param);
		$data['clientes'] = $datos['clientes'];
		$data['total'] = $datos['total'];
		$data['content_view'] = $this->load->view('gestion/ctrlglab_view', $data, true);

		// ... Modulos del cliente
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

		// ... Pagina master
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], $id_modulo);
		if ($permiso) {
			$this->load->view($this->config->item('template_dir') . '/master', $data);
		} else {
			header("Location: " . RUTA_WWW . "/errores/error_404.html");
			exit;
		}
	}

	public function get_ctrlGLab($table = null, $columna = null, $valor = null)
    {

            $this->load->library('Datatable');
			if($this->input->get('minimo') != ''){
				$where['dietario.fecha_hora_concepto >='] = $this->input->get('fecha_desde'). ' 00:00:00';
			}
			$minimo = ($this->input->get('minimo') != '') ? $this->input->get('minimo')  : 3000;
            $campos = [
                'liquidaciones_citas.fecha_cita',
                '(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
				'servicios.nombre_servicio',
                '(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS usuario',
                'liquidaciones_citas.pvp',
				'liquidaciones_citas.dto',
				'liquidaciones_citas.dtop',
				'liquidaciones_citas.gastos_lab',
				'liquidaciones_citas.total',
				'liquidaciones_citas.estado',
				'liquidaciones_citas.id_cita',
				'liquidaciones_citas.id_usuario',
				'liquidaciones_citas.id_cliente',
            ];
            $tabla = 'liquidaciones_citas';
            $join = [
                'clientes' => 'clientes.id_cliente = liquidaciones_citas.id_cliente',
				'servicios' => 'servicios.id_servicio = liquidaciones_citas.id_item',
				'usuarios' => 'usuarios.id_usuario = liquidaciones_citas.id_usuario'
            ];
			$palabras_clave = array('implante', 'corona', 'sobredentadura', 'protesis', 'hueso', 'membrana', 'chincheta', 'ferula', 'entrada', 'laboratorio');
			$palabras_clave = implode(',', $palabras_clave);
            $add_rule = [
				'like' => ['servicios.nombre_servicio', $palabras_clave],
				'or_where' =>['liquidaciones_citas.gastos_lab >', 0],
				'group_by' => 'liquidaciones_citas.id_liquidacion_cita',
				'order_by' => ['liquidaciones_citas.fecha_cita','asc'], 
				'order_by' => ['liquidaciones_citas.id_cliente','asc'],
			];
            $where = [
				'liquidaciones_citas.borrado' => 0,
			];

			if($this->input->get('fecha_desde') != ''){
				$where['liquidaciones_citas.fecha_cita >='] = $this->input->get('fecha_desde'). ' 00:00:00';
			}else{
				$where['liquidaciones_citas.fecha_cita >='] = date('Y-01-01 00:00:00');
			}
			if($this->input->get('fecha_hasta') != ''){
				$where['liquidaciones_citas.fecha_cita <='] = $this->input->get('fecha_hasta'). ' 23:59:59';
			}else{
				$where['liquidaciones_citas.fecha_cita <='] = date('Y-12-31 23:59:59');
			}
			if($this->input->get('id_usuario') != ''){
				$where['liquidaciones_citas.id_usuario'] = $this->input->get('id_usuario');
			}
			if($this->input->get('id_cliente') != ''){
				$where['liquidaciones_citas.id_cliente'] = $this->input->get('id_cliente');
			}
			if($this->input->get('estado') != ''){
				$where['liquidaciones_citas.estado'] = $this->input->get('estado');
			}

            if (($table != "") && ($columna != "") && ($valor != "")) {
                $where[$table . '.' . $columna] = $valor;
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            } else {
                $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
            }
			$result->last_query = $this->db->last_query();
			//printr($result);
            $res = json_encode($result);
       

        echo $res;
    }
}
