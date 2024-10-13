<?php
class Presupuestos extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Presupuestos_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	/*
	Precio inicial, si descuento es totalpresupuesto / (1 - (100/descuento))
	*/

	public function create_tables()
	{
		//$this->db->query("ALTER TABLE `presupuestos` ADD `mostrar_financiacion` BOOLEAN NOT NULL AFTER `total_pendiente`, ADD `anticipo_financiacion` DECIMAL(8,2) NOT NULL AFTER `mostrar_financiacion`");
	}

	public function renombrar_hijos()
	{
		$this->db->where('padre >', 0);
		$hijos = $this->db->get('servicios')->result();

		foreach ($hijos as $key => $value) {
			$this->db->where('id_servicio', $value->padre);
			$padre =  $this->db->get('servicios')->row();
			$nombre_padre = $padre->nombre_servicio;
			$nombre_hijo = $value->nombre_servicio;
			$nombre_hijo_rem = str_replace($nombre_padre, "", $nombre_hijo);
			$nombre_final = $nombre_hijo_rem . " " . $nombre_padre;

			$data['nombre_servicio'] = $nombre_final;
			$this->db->where('id_servicio', $value->id_servicio);
			$this->db->update('servicios', $data);
		}
	}

	public function index()
	{
		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		/* CHAINS - Esta vista solo la mostramos si hay id_cliente en el GET */
		if ($this->input->get('id_cliente') == '') {
			$this->porcliente();
			return;
		}

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Presupuestos';
		$data['actionstitle'] = ['<a href="' . base_url() . 'presupuestos/nuevo_presupuesto" class="btn btn-primary text-inverse-primary">Nuevo presupuesto</a>'];
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
		if ($this->input->get('id_cliente') != '') {
			unset($param);
			$param5['id_cliente'] = $this->input->get('id_cliente');
			$data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);
		}
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_view', $data, true);

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


	public function porcliente()
	{
		/* CHAINS - Esta vista solo la mostramos si no hay id_cliente en el GET */
		if ($this->input->get('id_cliente') != '') {
			$this->index();
			return;
		}

		// ... Comprobamos la sesion del cliente
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Presupuestos';
		$data['actionstitle'] = ['<a href="' . base_url() . 'presupuestos/nuevo_presupuesto" class="btn btn-primary text-inverse-primary">Nuevo presupuesto</a>'];
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

		$data['content_view'] = $this->load->view('presupuestos/presupuestos_por_cliente_view', $data, true);

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

	public function get_jsonpresupuestos()
	{
		$params = [];
		if (isset($_GET['id_cliente']) && $_GET['id_cliente']) {
			$params['id_cliente'] = $_GET['id_cliente'];
		} else
        if (isset($_GET['id_presupuesto']) && $_GET['id_presupuesto']) {
			$presup = $this->Presupuestos_model->leer_presupuestos(array('id_presupuesto' => $_GET['id_presupuesto']));
			if ($presup) {
				if (is_array($presup)) {
					$params['id_cliente'] = $presup[0]['id_cliente'];
				}
			}
		} else
        if (isset($_GET['id_dietario']) && $_GET['id_dietario']) {
			$diet = $this->Dietario_model->leer(['id_dietario' => $_GET['id_dietario']]);
			if ($diet) {
				if (is_array($diet)) {
					$params['id_cliente'] = $diet[0]['id_cliente'];
				}
			}
		}


		if (isset($_GET['estado']) && !empty($_GET['estado'])) {
			$params['estado'] = $_GET['estado'];
		}
		$rtval = [];
		$data = [];
		if (isset($params['id_cliente'])) {
			$data = $this->Presupuestos_model->leer_presupuestos($params);
			if ($data) {
				foreach ($data as $xdata) {
					if ($xdata['total_pendiente'] != 0 && $xdata['totalcomisionfinanciacion'] <= 0) {
						$rtval[] = array(
							'id_presupuesto' => $xdata['id_presupuesto'],
							'total' => $xdata['totalpresupuesto'],
							'nro_presupuesto' => $xdata['nro_presupuesto']
						);
					}
				}
			}
		}

		echo json_encode(array('presupuestos' => $rtval, 'data' => $data));
		die();
	}

	public function get_presupuestosporcliente($table = null, $columna = null, $valor = null)
	{

		$this->load->library('Datatable');

		$camposClientes = [
			'presupuestos.fecha_modificacion',
			'presupuestos.nro_presupuesto',
			'(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
			'clientes.id_cliente',
            'clientes.nombre',
            'clientes.apellidos',
			'COALESCE(temporl.items_sinfinalizar, 0) AS items_sinfinalizar',
		];
		$tablaClientes = 'clientes';

		$condicionesPresupuesto = 'presupuestos.borrado = 0';

		$whereClientes = [];

		$addRulesClientes = [
			'group_by' => 'clientes.id_cliente'
		];

		if ($this->input->get('id_cliente') != '') {
			$condicionesPresupuesto .= ' AND presupuestos.id_cliente = ' . $this->input->get('id_cliente');
		}
		if ($this->input->get('id_usuario') != '') {
			$condicionesPresupuesto .= ' AND presupuestos.id_usuario = ' . $this->input->get('id_usuario');
		}
		if ($this->input->get('estado') != '') {
			$estado = $this->input->get('estado');
			if ($estado == 'Aceptado pendiente' || $estado == 'Finalizado') {
				$condicionesPresupuesto .= " AND presupuestos.estado IN ('Aceptado parcial','Aceptado') ";
			} else {
				$condicionesPresupuesto .= " AND presupuestos.estado ='" . $estado . "' ";
			}
			if ($estado == 'Finalizado') {
				$addRulesClientes['having'] = 'items_sinfinalizar = 0';
			} else if ($estado == 'Aceptado pendiente') {
				$addRulesClientes['having'] = 'items_sinfinalizar > 0';
			}
		}
		if ($this->input->get('revisado') != '') {
			$condicionesPresupuesto .= " AND presupuestos.revisado = " . $this->input->get('revisado') . " ";
		}
		if ($this->input->get('fecha_desde') != '') {
			$condicionesPresupuesto .= " AND presupuestos.fecha_creacion >='" . $this->input->get('fecha_desde') . " 00:00:00' ";
		}
		if ($this->input->get('fecha_hasta') != '') {
			$condicionesPresupuesto .= " AND presupuestos.fecha_creacion <='" . $this->input->get('fecha_hasta') . " 23:59:59' ";
		}
		if ($this->input->get('fecha_validez') != '') {
			$condicionesPresupuesto .= " AND presupuestos.fecha_validez >='" . $this->input->get('fecha_validez') . "' ";
		}
		if ($this->input->get('rechazados') != '') {
			if (!$this->input->get('rechazados')) {
				$condicionesPresupuesto .= " AND presupuestos.estado != 'Rechazado' ";
			}
		}

		// Filtro adicional basado en el perfil del usuario
		$filtroCentro = '';
		if ($this->session->userdata('id_perfil') > 0) {
			$filtroCentro = ' AND presupuestos.id_centro = ' . $this->session->userdata('id_centro_usuario');
			$condicionesPresupuesto .= $filtroCentro;
		}

		$joinClientes = [
			'presupuestos' => array("inner", '(clientes.id_cliente = presupuestos.id_cliente AND ' . $condicionesPresupuesto . ')'),
			"(SELECT id_presupuesto, COALESCE(COUNT(*), 0) AS items_sinfinalizar
			 FROM `presupuestos_items` pi
			 LEFT JOIN citas ci ON pi.id_cita = ci.id_cita
			 WHERE aceptado = 1 AND (pi.id_cita = 0 OR (ci.estado != 'Programada' AND ci.estado != 'Finalizado')) AND pi.borrado = 0
			 GROUP BY id_presupuesto) AS temporl" => 'temporl.id_presupuesto = presupuestos.id_presupuesto',
		];

		$result = json_decode($this->datatable->get_datatable($this->input->get(), $tablaClientes, $camposClientes, $joinClientes, $whereClientes, $addRulesClientes), true);

		$idsClientes = [];
		$resultado = [];
		foreach ($result['data'] as $result1) {
			$idsClientes[$result1['id_cliente']] = $result1['id_cliente'];
		}
		if (count($idsClientes)) {
			$sqlExtra = 'SELECT ' .
				'presupuestos.fecha_modificacion, ' .
				'presupuestos.nro_presupuesto, ' .
				'(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente, ' .
				'presupuestos.fecha_creacion, ' .
				'presupuestos.totalpresupuesto, ' .
				'presupuestos.total_aceptado, ' .
				'(presupuestos.total_pendiente * -1) AS pendiente, ' .
				'(CASE WHEN dto_100 > 0 THEN presupuestos.dto_100 ' .
				'ELSE ((presupuestos.total_sin_descuento - presupuestos.totalpresupuesto) / presupuestos.total_sin_descuento) * 100 ' .
				'END) AS descuento, ' .
				'presupuestos.estado, ' .
				'(DATE_FORMAT(presupuestos.fecha_modificacion, "%d-%m-%Y %H:%i")) AS f_modificacion, ' .
				'presupuestos.*, ' .
				'(DATE_FORMAT(presupuestos.fecha_creacion, "%d-%m-%Y %H:%i")) AS f_creacion, ' .
				'(DATE_FORMAT(DATE(presupuestos.fecha_validez), "%d-%m-%Y")) AS f_validez, ' .
				'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado, ' .
				'presupuestos.fecha_validez, ' .
				'presupuestos.es_repeticion, ' .
				'presupuestos.id_presupuesto, ' .
				'COALESCE(temporl.items_sinfinalizar, 0) AS items_sinfinalizar, ' .
				'tempor2.total_pendiente_calculado ' .
				'FROM presupuestos ' .
				'LEFT JOIN clientes ON presupuestos.id_cliente = clientes.id_cliente ' .
				'LEFT JOIN usuarios ON presupuestos.id_usuario = usuarios.id_usuario ' .
				'LEFT JOIN (SELECT id_presupuesto, COALESCE(COUNT(*), 0) AS items_sinfinalizar ' .
				'FROM `presupuestos_items` pi ' .
				'LEFT JOIN citas ci ON pi.id_cita = ci.id_cita ' .
				"WHERE aceptado = 1 AND (pi.id_cita = 0 OR (ci.estado != 'Programada' AND ci.estado != 'Finalizado')) AND pi.borrado = 0 " .
				'GROUP BY id_presupuesto) AS temporl ON temporl.id_presupuesto = presupuestos.id_presupuesto ' .
				"LEFT JOIN (SELECT id_presupuesto, SUM(coste) AS total_pendiente_calculado
							FROM `presupuestos_items` pip
							LEFT JOIN citas cip ON pip.id_cita = cip.id_cita
							WHERE aceptado = 1 AND (pip.id_cita = 0 OR (NOT cip.estado IN ('Programada','Finalizado'))) AND pip.borrado = 0
							GROUP BY id_presupuesto) AS tempor2 ON tempor2.id_presupuesto = presupuestos.id_presupuesto ";
			$sqlExtra .= "WHERE " . $condicionesPresupuesto . $filtroCentro . " AND presupuestos.id_cliente IN (" . implode(",", $idsClientes) . ") " .
				"ORDER BY presupuestos.id_cliente ASC, presupuestos.fecha_creacion DESC";
			$AqConexion_model = new AqConexion_model();
			$resultado = $AqConexion_model->select($sqlExtra, null);
		}

		$finalResult = [];
		foreach ($result['data'] as $result1) {
			foreach ($resultado as $rres) {
				if ($rres['id_cliente'] == $result1['id_cliente']) {
					if (!isset($finalResult[$rres['id_cliente']])) {
						$finalResult[$rres['id_cliente']] = $rres;
						$finalResult[$rres['id_cliente']]['otrospresus'] = [];
					} else {
						$finalResult[$rres['id_cliente']]['otrospresus'][] = $rres;
					}
				}
			}
		}
		$result['data'] = array_values($finalResult);
		$res = json_encode($result);
		echo $res;
		die();


		/*
        $campos = [
            'presupuestos.fecha_modificacion',
            'presupuestos.nro_presupuesto',
            '(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
            'presupuestos.fecha_creacion',
            'presupuestos.totalpresupuesto',
            'presupuestos.total_aceptado',
            '(presupuestos.total_pendiente * -1 ) AS pendiente',
            '(CASE WHEN dto_100 > 0 THEN presupuestos.dto_100
				ELSE ((presupuestos.total_sin_descuento - presupuestos.totalpresupuesto) / presupuestos.total_sin_descuento) * 100
				END ) AS descuento',
            'presupuestos.estado',
            '(DATE_FORMAT(presupuestos.fecha_modificacion,"%d-%m-%Y %H:%i") ) AS f_modificacion',
            'presupuestos.*',
            '(DATE_FORMAT(presupuestos.fecha_creacion,"%d-%m-%Y %H:%i") ) AS f_creacion',
            '(DATE_FORMAT(DATE(presupuestos.fecha_validez),"%d-%m-%Y") ) AS f_validez',
            '(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
            'presupuestos.fecha_validez',
            'presupuestos.es_repeticion',
            'presupuestos.id_presupuesto',
            'COALESCE(temporl.items_sinfinalizar, 0) AS items_sinfinalizar',
        ];
        $tabla = 'presupuestos';
        $join = [
            'clientes' => 'presupuestos.id_cliente = clientes.id_cliente',
            'usuarios' => 'presupuestos.id_usuario = usuarios.id_usuario',
            "(SELECT id_presupuesto,COALESCE(COUNT(*), 0) AS items_sinfinalizar
FROM `presupuestos_items` pi
LEFT JOIN citas ci ON pi.id_cita=ci.id_cita
WHERE aceptado=1 AND (pi.id_cita=0 OR (ci.estado!='Programada' AND ci.estado!='Finalizado')) AND pi.borrado=0
GROUP BY id_presupuesto  ) AS temporl" => 'temporl.id_presupuesto = presupuestos.id_presupuesto',
        ];
        $add_rule = [];
        $where = ['presupuestos.borrado' => 0];

        /*if ($this->session->userdata('id_perfil') > 0) {
            $where['presupuestos.id_centro'] = $this->session->userdata('id_centro_usuario');
        }

        if ($this->input->get('id_cliente') != '') {
            $where['presupuestos.id_cliente'] = $this->input->get('id_cliente');
        }
        if ($this->input->get('id_usuario') != '') {
            $where['presupuestos.id_usuario'] = $this->input->get('id_usuario');
        }
        if ($this->input->get('estado') != '') {
            $where['presupuestos.estado'] = $this->input->get('estado');
            if($where['presupuestos.estado']=='Aceptado pendiente' || $where['presupuestos.estado']=='Finalizado'){
                $where['presupuestos.estado']=array('Aceptado parcial','Aceptado');
            }
            if($this->input->get('estado')=='Finalizado'){
                $add_rule['having']='items_sinfinalizar = 0';
            }
            else
                if($this->input->get('estado')=='Aceptado pendiente'){
                    $add_rule['having']='items_sinfinalizar > 0';
                }
        }
        if ($this->input->get('revisado') != '') {
            $where['presupuestos.revisado'] = $this->input->get('revisado');
        }
        if ($this->input->get('fecha_desde') != '') {
            $where['presupuestos.fecha_creacion >='] = $this->input->get('fecha_desde') . " 00:00:00";
        }
        if ($this->input->get('fecha_hasta') != '') {
            $where['presupuestos.fecha_creacion <='] = $this->input->get('fecha_hasta') . " 23:59:59";
        }
        if ($this->input->get('fecha_validez') != '') {
            $where['presupuestos.fecha_validez >='] = $this->input->get('fecha_validez');
        }

        if($this->input->get('rechazados') != ''){
            if(!$this->input->get('rechazados')){
                $where['presupuestos.estado !='] = 'Rechazado';
            }
        }



        if (($table != "") && ($columna != "") && ($valor != "")) {
            $where[$table . '.' . $columna] = $valor;
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
        } else {

            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));

        }
        //$result->query = $this->db->last_query();
        $res = json_encode($result);
        echo $res;
		*/
	}

	public function get_presupuestos($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			/*'presupuestos.id_presupuesto',*/
			/*"'' as items",*/
			'presupuestos.fecha_modificacion',
			'presupuestos.nro_presupuesto',
			'(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
			'presupuestos.fecha_creacion',
			'presupuestos.totalpresupuesto',
			'presupuestos.total_aceptado',
			'(presupuestos.total_pendiente * -1 ) AS pendiente',
			'(CASE WHEN dto_100 > 0 THEN presupuestos.dto_100
				ELSE ((presupuestos.total_sin_descuento - presupuestos.totalpresupuesto) / presupuestos.total_sin_descuento) * 100
				END ) AS descuento',
			'presupuestos.estado',
			'(DATE_FORMAT(presupuestos.fecha_modificacion,"%d-%m-%Y %H:%i") ) AS f_modificacion',
			'presupuestos.*',
			'(DATE_FORMAT(presupuestos.fecha_creacion,"%d-%m-%Y %H:%i") ) AS f_creacion',
			'(DATE_FORMAT(DATE(presupuestos.fecha_validez),"%d-%m-%Y") ) AS f_validez',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
			'presupuestos.fecha_validez',
			'presupuestos.es_repeticion',
			'presupuestos.id_presupuesto',
			'COALESCE(temporl.items_sinfinalizar, 0) AS items_sinfinalizar',
			'tempor2.total_pendiente_calculado',
		];
		$tabla = 'presupuestos';
		$join = [
			'clientes' => 'presupuestos.id_cliente = clientes.id_cliente',
			'usuarios' => 'presupuestos.id_usuario = usuarios.id_usuario',
			"(SELECT id_presupuesto,COALESCE(COUNT(*), 0) AS items_sinfinalizar
FROM `presupuestos_items` pi
LEFT JOIN citas ci ON pi.id_cita=ci.id_cita
WHERE aceptado=1 AND (pi.id_cita=0 OR (ci.estado!='Programada' AND ci.estado!='Finalizado')) AND pi.borrado=0
GROUP BY id_presupuesto  ) AS temporl" => 'temporl.id_presupuesto = presupuestos.id_presupuesto',
			"(SELECT id_presupuesto, SUM(coste)  AS total_pendiente_calculado
              FROM `presupuestos_items` pip
              LEFT JOIN citas cip ON pip.id_cita=cip.id_cita
                WHERE aceptado=1 AND aceptado=1 AND (pip.id_cita=0 OR  (NOT cip.estado IN ('Programada','Finalizado'))) AND pip.borrado = 0
                GROUP BY id_presupuesto ) AS tempor2 " => 'tempor2.id_presupuesto = presupuestos.id_presupuesto',
		];
		$add_rule = [];
		$where = ['presupuestos.borrado' => 0];

		/*if ($this->session->userdata('id_perfil') > 0) {
			$where['presupuestos.id_centro'] = $this->session->userdata('id_centro_usuario');
		}*/

		if ($this->input->get('id_cliente') != '') {
			$where['presupuestos.id_cliente'] = $this->input->get('id_cliente');
		}
		if ($this->input->get('id_usuario') != '') {
			$where['presupuestos.id_usuario'] = $this->input->get('id_usuario');
		}
		if ($this->input->get('estado') != '') {
			$where['presupuestos.estado'] = $this->input->get('estado');
			/* CHAINS 20240218 - Para arreglar el filtro de estasdo que se había metido en Datatable */
			if ($where['presupuestos.estado'] == 'Aceptado pendiente' || $where['presupuestos.estado'] == 'Finalizado') {
				$where['presupuestos.estado'] = array('Aceptado parcial', 'Aceptado');
			}
			if ($this->input->get('estado') == 'Finalizado') {
				$add_rule['having'] = 'items_sinfinalizar = 0';
			} else
                if ($this->input->get('estado') == 'Aceptado pendiente') {
				$add_rule['having'] = 'items_sinfinalizar > 0';
			}

			/* FIN CHAINS 20240218 */
		}
		if ($this->input->get('revisado') != '') {
			$where['presupuestos.revisado'] = $this->input->get('revisado');
		}
		if ($this->input->get('fecha_desde') != '') {
			$where['presupuestos.fecha_creacion >='] = $this->input->get('fecha_desde') . " 00:00:00";
		}
		if ($this->input->get('fecha_hasta') != '') {
			$where['presupuestos.fecha_creacion <='] = $this->input->get('fecha_hasta') . " 23:59:59";
		}
		if ($this->input->get('fecha_validez') != '') {
			$where['presupuestos.fecha_validez >='] = $this->input->get('fecha_validez');
		}

		if ($this->input->get('rechazados') != '') {
			if (!$this->input->get('rechazados')) {
				$where['presupuestos.estado !='] = 'Rechazado';
			}
		}

		if (($table != "") && ($columna != "") && ($valor != "")) {
			$where[$table . '.' . $columna] = $valor;
			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		} else {

			$result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $campos, $join, $where, $add_rule));
		}
		//$result->query = $this->db->last_query();
		$res = json_encode($result);
		echo $res;
	}

	public function nuevo_presupuesto($id_cliente = '')
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
		}

		if ($this->session->userdata('id_perfil') > 0) {
			$parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
		}

		$data['accion'] = "nuevo";
		// ... Leemos el cliente, si llega
		if ($id_cliente != null) {
			unset($param);
			$param['id_cliente'] = $id_cliente;
			$data['cliente'] = $this->Clientes_model->leer_clientes($param);
		}

		//se buscan los usuarios doctores
		unset($param);
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);

		unset($param);
		$param = [
			'activo' => 1
		];
		$data['tarifas'] = $this->Tarifas_model->leer_tarifas($param);

		// ... Leemos los servicios
		unset($param);
		$param = [];
		$param['solo_padre'] = 1;
		$param['obsoleto'] = 0;
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);

		// 20240201 - Otros precios de tarifa
		$data['precios'] = [];
		if (isset($data['tarifas']) && is_array($data['tarifas'])) {
			foreach ($data['tarifas']  as $tarifa) {
				$precios = $this->Tarifas_model->leer_servicios($tarifa['id_tarifa']);
				$finalprecios = [];
				foreach ($precios as $precio) {
					if ($precio['pvp_tarifa'] !== null) {
						$finalprecios['id_servicio_' . $precio['id_servicio']] = floatval($precio['pvp_tarifa']);
					}
				}
				$data['precios']['tarifa_' . $tarifa['id_tarifa']] = $finalprecios;
			}
		}
		// 20240201 - Fin Chains
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}
		
		$data['aseguradoras'] = $this->Aseguradoras_model->getListadoAseguradoras();

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Nuevo presupuesto';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_nuevo_view', $data, true);

		// ... Modulos del caja
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

	public function crearPresupuesto()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		
		$this->form_validation->set_rules('id_cliente', 'Cliente', 'required');
		$this->form_validation->set_rules('fecha_validez', 'Fecha de validez', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$id_cliente = $this->input->post('id_cliente');
		if ($this->form_validation->run() == false) {
			$this->session->set_userdata('mensaje', validation_errors());
			if ($id_cliente != '') {
				redirect('Presupuestos/nuevo_presupuesto/' . $id_cliente);
			} else {
				redirect('Presupuestos/nuevo_presupuesto');
			}
		}
		$id_doctor = $this->input->post('id_doctor');
		$fecha_validez = $this->input->post('fecha_validez');
		$estado = $this->input->post('estado');
		$dto_euros = $this->input->post('dto_euros');
		$dto_100 = $this->input->post('dto_100');
		$con_cuota = $this->input->post('com_cuota');
		$totalpresupuesto = $this->input->post('totalpresupuesto');
		$cuotas = $this->input->post('cuotas');
		$apertura = $this->input->post('apertura');
		$totalcuota = $this->input->post('totalcuota');

		$ids_servicios = $this->input->post('id_servicio[]');
		$cant_servicios = $this->input->post('servicioCantidad[]');
		$pvp_servicios = $this->input->post('servicioPrecio[]');
		$dto_servicios = $this->input->post('servicioDescuento[]');
		$dto_euros_servicios = $this->input->post('servicioDescuentoE[]');
		$dientes_servicios = $this->input->post('servicioDientes[]');
		$items = [];

		foreach ($ids_servicios as $key => $id_servicio_padre) {
			if ($ids_servicios[$key] != '' && $cant_servicios[$key] > 0) {
				unset($param);
				$param['padre'] = $id_servicio_padre;
				$hijos = $this->Servicios_model->leer_servicios($param);
				$es_padre = true;
				if ($hijos == 0) {
					unset($param);
					$param['id_servicio'] = $id_servicio_padre;
					$hijos = $this->Servicios_model->leer_servicios($param);
					$es_padre = false;
				}
				foreach ($hijos as $s => $servicio) {
					$id_servicio = $servicio['id_servicio'];
					// cambiar aqui:  añadir un item por cada diente que llegue
					if ($es_padre == true) {
						$pvp_servic = $pvp_servicios[$key] / (100 / $servicio['parte_padre']);
						$dto_servic = $dto_euros_servicios[$key] / (100 / $servicio['parte_padre']);
					} else {
						$pvp_servic = $pvp_servicios[$key];
						$dto_servic = $dto_euros_servicios[$key];
					}
					if ($dientes_servicios[$key] != '') {
						$ndientes = explode(',', $dientes_servicios[$key]);
						foreach ($ndientes as $d => $nd) {
							$items[] = [
								'id_cliente' => $id_cliente,
								'tipo_item' => 'Servicio',
								'id_item' => $id_servicio,
								'cantidad' => 1,
								'dientes' => $nd,
								'pvp' => $pvp_servic,
								'dto' => $dto_servicios[$key],
								'dto_euros' => number_format($dto_servic / count($ndientes), 2),
								'coste' => $pvp_servic - ($pvp_servic * $dto_servicios[$key] / 100) - (number_format($dto_servic / count($ndientes), 2)),
							];
						}
					} else {
						for ($i = 0; $i < $cant_servicios[$key]; $i++) {
							$items[] = [
								'id_cliente' => $id_cliente,
								'tipo_item' => 'Servicio',
								'id_item' => $id_servicio,
								'cantidad' => 1, //$cant_servicios[$key],
								'dientes' => $dientes_servicios[$key],
								'pvp' => $pvp_servic,
								'dto' => $dto_servicios[$key],
								'dto_euros' => $dto_servic,
								'coste' => $pvp_servic - ($pvp_servic * $dto_servicios[$key] / 100) - $dto_servic,
							];
						}
					}
				}
			}
		}

		if (count($items) < 1) {
			$this->session->set_userdata('mensaje', 'No se ha indicado ningun artículo para el presupuesto');
			if ($id_cliente != '') {
				redirect('Presupuestos/nuevo_presupuesto/' . $id_cliente);
			} else {
				redirect('Presupuestos/nuevo_presupuesto');
			}
		}
		$parametros = $_POST;
		$parametros['dto_euros'] = 0;
		$parametros['dto_100'] = 0;
		$id_presupuesto = $this->Presupuestos_model->nuevo_presupuesto($parametros);
		foreach ($items as $key => $item) {
			// aqui es donde calcula el precio real de cada servicio
			$item['id_presupuesto'] = $id_presupuesto;
			$item['coste'] = $item['pvp'];
			// DESCUENTO PROPIO DEL ITEM
			if ($item['dto'] > 0) {
				$dto_propio_euros = $item['pvp'] * ($item['dto'] / 100);
				$item['coste'] = $item['coste'] - $dto_propio_euros;
			}
			if ($item['dto_euros'] > 0) {
				$item['coste'] = $item['coste'] - $item['dto_euros'];
			}
			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($parametros['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] + $parametros['dto_euros'];
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $parametros['dto_euros'], 2);
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros;
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			} elseif ($parametros['dto_100'] > 0) {
				$dtog = $parametros['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $parametros['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			}
			$this->Presupuestos_model->nuevo_item_presupuesto($item);
		}
		$this->Presupuestos_model->recalcular_totales($id_presupuesto);
		$this->session->set_userdata('msn_estado', 1);
		
		if ( $this->input->post('mostrar_aseguradoras') && $this->input->post('id_aseguradora') ){
			$this->Aseguradoras_model->adjuntarFicheros($id_presupuesto, $this->input->post('id_aseguradora') );
		}
		
		redirect('Presupuestos');
	}

	public function editar_presupuesto($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		// ... Leemos los productos
		$data['accion'] = "editar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] != 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes editar este presupuesto. Ya ha sido entregado al cliente');
			redirect('presupuestos');
		}

		unset($param);
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);
		// 20240201 - Otros precios de tarifa

		unset($param);
		$param = [
			'activo' => 1
		];
		$data['tarifas'] = $this->Tarifas_model->leer_tarifas($param);


		$data['precios'] = [];
		if (isset($data['tarifas']) && is_array($data['tarifas'])) {
			foreach ($data['tarifas']  as $tarifa) {
				$precios = $this->Tarifas_model->leer_servicios($tarifa['id_tarifa']);
				$finalprecios = [];
				foreach ($precios as $precio) {
					if ($precio['pvp_tarifa'] !== null) {
						$finalprecios['id_servicio_' . $precio['id_servicio']] = floatval($precio['pvp_tarifa']);
					}
				}
				$data['precios']['tarifa_' . $tarifa['id_tarifa']] = $finalprecios;
			}
		}
		// 20240201 - Fin Chains

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);
		// ... Leemos los servicios
		unset($param);
		$param = [];
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Editar presupuesto';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_editar_view', $data, true);

		// ... Modulos del caja
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

	public function actualizarPresupuesto()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$this->form_validation->set_rules('id_presupuesto', 'Presupuesto', 'required');
		$this->form_validation->set_rules('id_cliente', 'Cliente', 'required');
		$this->form_validation->set_rules('fecha_validez', 'Fecha de validez', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$this->form_validation->set_rules('totalpresupuesto', 'Estado', 'required');
		$id_cliente = $this->input->post('id_cliente');
		$id_presupuesto = $this->input->post('id_presupuesto');
		if ($this->form_validation->run() == false) {
			$this->session->set_userdata('mensaje', validation_errors());
			if ($id_presupuesto != '') {
				redirect('Presupuestos/editar_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/nuevo_presupuesto');
			}
		}
		$id_doctor = $this->input->post('id_doctor');
		$fecha_validez = $this->input->post('fecha_validez');
		$estado = $this->input->post('estado');
		$dto_euros = $this->input->post('dto_euros');
		$dto_100 = $this->input->post('dto_100');
		$com_cuota = $this->input->post('con_cuota');
		$totalpresupuesto = $this->input->post('totalpresupuesto');
		$cuotas = $this->input->post('cuotas');
		$apertura = $this->input->post('apertura');
		$totalcuota = $this->input->post('totalcuota');

		$ids_servicios = $this->input->post('id_servicio[]');
		$ids_servicios_items = $this->input->post('ids_servicios_items[]');
		$cant_servicios = $this->input->post('servicioCantidad[]');
		$pvp_servicios = $this->input->post('servicioPrecio[]');
		$dto_servicios = $this->input->post('servicioDescuento[]');
		$dto_euros_servicios = $this->input->post('servicioDescuentoE[]');
		$dientes_servicios = $this->input->post('servicioDientes[]');
		$items_actualizar = [];
		$items_nuevos = [];
		foreach ($ids_servicios as $key => $id_servicio) {
			if ($ids_servicios[$key] != '' && $cant_servicios[$key] > 0) {
				if ($ids_servicios_items[$key] == 0) {

					if ($cant_servicios[$key] > 0) {
						// cambiar aqui:  añadir un item por cada diente que llegue
						if ($dientes_servicios[$key] != '') {
							$ndientes = explode(',', $dientes_servicios[$key]);
							foreach ($ndientes as $d => $nd) {
								$items_nuevos[] = [
									'id_presupuesto' => $id_presupuesto,
									'id_cliente' => $id_cliente,
									'tipo_item' => 'Servicio',
									'id_item' => $id_servicio,
									'cantidad' => 1,
									'dientes' => $nd,
									'pvp' => $pvp_servicios[$key],
									'dto' => $dto_servicios[$key],
									'dto_euros' => number_format($dto_euros_servicios[$key] / count($ndientes), 2),
									'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - (number_format($dto_euros_servicios[$key] / count($ndientes), 2))
								];
							}
						} else {
							for ($i = 0; $i < $cant_servicios[$key]; $i++) {
								$items_nuevos[] = [
									'id_presupuesto' => $id_presupuesto,
									'id_cliente' => $id_cliente,
									'tipo_item' => 'Servicio',
									'id_item' => $id_servicio,
									'cantidad' => 1, // $cant_servicios[$key],
									'dientes' => $dientes_servicios[$key],
									'pvp' => $pvp_servicios[$key],
									'dto' => $dto_servicios[$key],
									'dto_euros' => $dto_euros_servicios[$key],
									'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - $dto_euros_servicios[$key]
								];
							}
						}
					}
				} else {
					$items_actualizar[$ids_servicios_items[$key]] = [
						'id_presupuesto_item' => $ids_servicios_items[$key],
						'id_presupuesto' => $id_presupuesto,
						'id_cliente' => $id_cliente,
						'tipo_item' => 'Servicio',
						'id_item' => $id_servicio,
						'cantidad' => $cant_servicios[$key],
						'dientes' => $dientes_servicios[$key],
						'pvp' => $pvp_servicios[$key],
						'dto' => $dto_servicios[$key],
						'dto_euros' => $dto_euros_servicios[$key],
						'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - $dto_euros_servicios[$key]
					];
				}
			}
		}

		if (count($items_actualizar) < 1 && count($items_nuevos) < 1) {
			$this->session->set_userdata('mensaje', 'No se ha indicado ningun artículo para el presupuesto');
			if ($id_presupuesto != '') {
				redirect('Presupuestos/editar_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/presupuestos');
			}
		}
		$parametros = $_POST;
		$parametros['dto_euros'] = 0;
		$parametros['dto_100'] = 0;
		$param['id_presupuesto'] = $id_presupuesto;
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param);
		if (isset($parametros['es_repeticion']) && $parametros['es_repeticion'] == 1 && !str_starts_with($presupuesto[0]['nro_presupuesto'], 'R')) {
			$parametros['nro_presupuesto'] = 'R-' . $presupuesto[0]['nro_presupuesto'];
		}
		$id_presupuesto_actualizado = $this->Presupuestos_model->actualizar_presupuesto($parametros);
		$this->Presupuestos_model->recalcular_totales($id_presupuesto);
		$nuevos_items = 0;
		$actualizados_items = 0;
		foreach ($items_nuevos as $key => $item) {
			$item['coste'] = $item['pvp'];
			// DESCUENTO PROPIO DEL ITEM
			if ($item['dto'] > 0) {
				$dto_propio_euros = $item['pvp'] * ($item['dto'] / 100);
				$item['coste'] = $item['coste'] - $dto_propio_euros;
			}
			if ($item['dto_euros'] > 0) {
				$item['coste'] = $item['coste'] - $item['dto_euros'];
			}
			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($parametros['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] + $parametros['dto_euros'];
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $parametros['dto_euros'], 2);
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros;
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			} elseif ($parametros['dto_100'] > 0) {
				$dtog = $parametros['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $parametros['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			}
			$insert_id = $this->Presupuestos_model->nuevo_item_presupuesto($item);
			$nuevos_items = $nuevos_items + $insert_id;
		}
		foreach ($items_actualizar as $key => $item) {
			$item['coste'] = $item['pvp'];
			// DESCUENTO PROPIO DEL ITEM
			if ($item['dto'] > 0) {
				$dto_propio_euros = $item['pvp'] * ($item['dto'] / 100);
				$item['coste'] = $item['coste'] - $dto_propio_euros;
			}
			if ($item['dto_euros'] > 0) {
				$item['coste'] = $item['coste'] - $item['dto_euros'];
			}
			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($parametros['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] + $parametros['dto_euros'];
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $parametros['dto_euros'], 2);
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros;
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			} elseif ($parametros['dto_100'] > 0) {
				$dtog = $parametros['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $parametros['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			}
			$actualizado_id = $this->Presupuestos_model->actualizar_item_presupuesto($item);
			$actualizados_items = $actualizados_items + $actualizado_id;
		}

		if ($id_presupuesto_actualizado < 1 && $nuevos_items < 1 && $actualizados_items > 1) {
			$this->session->set_userdata('mensaje', 'No se han realizado cambios en el presupuesto');
			if ($id_presupuesto != '') {
				redirect('Presupuestos/editar_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/presupuestos');
			}
		} else {
			$this->session->set_userdata('msn_estado', 1);
			redirect('Presupuestos');
		}
	}

	public function duplicar_presupuesto($id_presupuesto)
	{

		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$data['accion'] = "clonar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] == 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes clonar este presupuesto, no ha sido entregado al cliente. Puedes editarlo');
			redirect('presupustos');
		}

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);

		// ... Leemos los servicios
		unset($param);
		$param = [];
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}
		// ... Viewer con el contenido
		$data['pagetitle'] = 'Duplicar presupuesto';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_duplicar_view', $data, true);

		// ... Modulos del caja
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

	public function duplicar_presupuesto_nuevo($id_presupuesto)
	{

		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$data['accion'] = "clonar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] == 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes clonar este presupuesto, no ha sido entregado al cliente. Puedes editarlo');
			redirect('presupustos');
		}

		$nuevo_id_presupuesto = $this->Presupuestos_model->duplicar_presupuesto($id_presupuesto);

		$param['id_presupuesto'] = $nuevo_id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);

		unset($param);
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $nuevo_id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);

		// ... Leemos los servicios
		unset($param);
		$param = [];
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Editar presupuesto duplicado';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_editar_view', $data, true);

		// ... Modulos del caja
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

	public function duplicar_rechazar_presupuesto_nuevo($id_presupuesto)
	{

		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$data['accion'] = "clonar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] == 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes clonar este presupuesto, no ha sido entregado al cliente. Puedes editarlo');
			redirect('presupustos');
		}

		// inicio de rechazar el original 
		// se buscan los items del presupuesto para marcarlos como rechazados
		$ids_presupuesto_item_array = $this->Presupuestos_model->leer_presupuestos_items($param);
		foreach ($ids_presupuesto_item_array as $key => $value) {
			$item = [
				'id_presupuesto_item' => $value['id_presupuesto_item'],
				'aceptado' => '0'
			];
			$this->Presupuestos_model->actualizar_estado_item_presupuesto($item);
		}

		$param_p['id_presupuesto'] = $id_presupuesto;
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param_p);
		$parametros = [
			'id_presupuesto' =>  $id_presupuesto,
			'estado' => 'Rechazado',
			'estado_relacionado' => 'Rechazado ->' . $data['registro'][0]['estado_relacionado'],
			'total_aceptado' => 0,
		];
		$id_presupuesto_actualizado = $this->Presupuestos_model->actualizar_estado_presupuesto($parametros);
		// fin de rechazar el original 


		$nuevo_id_presupuesto = $this->Presupuestos_model->duplicar_presupuesto($id_presupuesto);

		$param['id_presupuesto'] = $nuevo_id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);

		unset($param);
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $nuevo_id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);

		// ... Leemos los servicios
		unset($param);
		$param = [];
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Editar presupuesto duplicado';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_editar_view', $data, true);

		// ... Modulos del caja
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


	public function gestionar_estado($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);

		if ($data['registro'] == 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto');
			redirect('presupuestos');
		}
		if ($this->session->userdata('id_perfil') > 0) {
			if ($data['registro'][0]['estado'] != 'Pendiente') {
				$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto, ya ha sido gestionado.');
				redirect('presupuestos');
			}
			if ($data['registro'][0]['fecha_validez'] < date('Y-m-d')) {
				$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto, ha vencido');
				redirect('presupuestos');
			}
		}

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Estado del presupuesto';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_gestionar_estado_view', $data, true);

		// ... Modulos del caja
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

	public function actualizarEstadoPresupuesto()
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$this->form_validation->set_rules('id_presupuesto', 'Presupuesto', 'required');
		$this->form_validation->set_rules('total_aceptado', 'Total aceptado presupuesto', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$id_presupuesto = $this->input->post('id_presupuesto');
		if ($this->form_validation->run() == false) {
			$this->session->set_userdata('mensaje', validation_errors());
			if ($id_presupuesto != '') {
				redirect('Presupuestos/editar_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/nuevo_presupuesto');
			}
		}

		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);

		if ($data['registro'] == 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto');
			redirect('presupuestos');
		}
		if ($this->session->userdata('id_perfil') > 0) {

			if ($data['registro'][0]['estado'] != 'Pendiente') {
				$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto, ya ha sido gestionado.');
				redirect('presupuestos');
			}
			if ($data['registro'][0]['fecha_validez'] < date('Y-m-d')) {
				$this->session->set_userdata('msn_actionno', 'No puedes gestionar este presupuesto, ha vencido');
				redirect('presupuestos');
			}
			if ($this->session->userdata('id_perfil') != 3) {
				if ($data['registro'][0]['id_centro'] != $this->session->userdata('id_centro_usuario')) {
					$this->session->set_userdata('msn_actionno', 'Solo puedes gestionar el estado de los presupuestos que hayan sido creados tu centro de trabajo');
					redirect('presupuestos');
				}
			}
		}

		$ids_presupuesto_item_array = $this->input->post('ids_presupuesto_item[]');
		foreach ($ids_presupuesto_item_array as $key => $value) {
			$item = [
				'id_presupuesto_item' => $value,
				'aceptado' => 1
			];
			$this->Presupuestos_model->actualizar_estado_item_presupuesto($item);
		}
		$param_p['id_presupuesto'] = $id_presupuesto;
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param_p);

		$parametros = [
			'id_presupuesto' =>  $id_presupuesto,
			'estado' => $this->input->post('estado'),
			'estado_relacionado' => $this->input->post('estado_relacionado'),
			'total_aceptado' => $this->input->post('total_aceptado'),
		];
		if ($presupuesto[0]['es_repeticion'] == 1) {
			$parametros['totalpresupuesto'] = 0;
			$parametros['total_pendiente'] = 0;
		}
		$id_presupuesto_actualizado = $this->Presupuestos_model->actualizar_estado_presupuesto($parametros);
		$this->Presupuestos_model->recalcular_totales($id_presupuesto);
		if ($id_presupuesto_actualizado < 1) {
			$this->session->set_userdata('mensaje', 'No se han realizado cambios en el presupuesto');
			if ($id_presupuesto != '') {
				redirect('Presupuestos/gestionar_estado/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/presupuestos');
			}
		} else {
			// si se ha actualizado el presupuesto y ha sido aceptado, se crea la cartera de ese presupuesto.
			if ($this->input->post('estado') == 'Aceptado parcial' || $this->input->post('estado') == 'Aceptado') {
				if ($presupuesto[0]['es_repeticion'] != 1) {
					$this->Presupuestos_model->crear_presupuestos_saldos($id_presupuesto);
				}
			}
			$this->session->set_userdata('msn_estado', 1);
			redirect('Presupuestos');
		}
	}

	public function borrar_presupuesto($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'] == 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes borrar este presupuesto');
			redirect('presupustos');
		}

		// ... Leemos los productos
		$data['accion'] = "editar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['eliminar']  = $this->Presupuestos_model->borrar_presupuesto($id_presupuesto);
		$this->session->set_userdata('msn_borrado', 1);
		redirect('Presupuestos');
	}

	function eliminar_item()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$this->form_validation->set_rules('id_presupuesto_item', 'ID presupuesto item', 'required');
		if ($this->form_validation->run() == false) {
			$response = [
				'error' => 1,
				'msn' => validation_errors()
			];
			echo json_encode($response);
			exit();
		}
		$parametros = null;;
		$parametros['borrado'] = 1;
		$parametros['id_presupuesto_item'] = $this->input->post('id_presupuesto_item');
		$estado = $this->Presupuestos_model->actualizar_item_presupuesto($parametros);

		if ($estado) {
			$response = array(
				'status' => 'success',
				'message' => 'Registro borrado'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Ocurrió un error al borrar el registro'
			);
		}
		$json_response = json_encode($response);
		header('Content-Type: application/json');
		echo $json_response;
	}

	function ver_detalle($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		/*if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}*/
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		$data['id_cliente'] = $data['registro'][0]['id_cliente'];
		// ... Leemos los items del presupuesto
		/*
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Producto';
		$data['productos_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);
		*/
		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_perfil'] = 6;
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);

		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$param['aceptado'] = 2;
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);
        $data['comentarios']=$this->Presupuestos_model->cargarMotivoPresupuestoItem($id_presupuesto);
		$data['pagos'] = $this->Presupuestos_model->leer_presupuestos_pagos($param);
		$this->load->model('Liquidaciones_model');
		$data['liquidaciones'] = $this->Liquidaciones_model->leer_liquidaciones_presupuesto($id_presupuesto);
		if ($data['liquidaciones'] == 0) $data['liquidaciones'] = [];

		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$data['documentos'] = $this->Clientes_model->documentos_cliente($param);
		echo $this->load->view('presupuestos/presupuestos_ver_detalle', $data, true);
	}
	function ver_pdf($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		/*if ($this->session->userdata('id_perfil') > 0) {
			$param['id_centro'] = $this->session->userdata('id_centro_usuario');
		}*/
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] == 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes clonar este presupuesto, no ha sido entregado al cliente. Puedes editarlo');
			redirect('presupustos');
		}

		$paramd['id_usuario'] = $data['registro'][0]['id_doctor'];
		$data['doctor'] = $this->Usuarios_model->leer_usuarios($paramd);

		$paramc['id_centro'] = $data['doctor'][0]['id_centro'];
		$data['centro'] = $this->Usuarios_model->leer_centros($paramc);

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		//$param['aceptado'] = 1;
		/* CHAINS 20240218 - Se añade el parámetro para decidir la dirección de ordenado */
		$servicios_items = $this->Presupuestos_model->leer_presupuestos_items($param, 'ASC');
		// manejamos aqui los elementos como lo haciamos en el pdf
		// se pasa a ala vista los datos formateados para que sea mas sencillo de procesar ahí
		$item_entrada = ['entrada' => '', 'mensualidad' => '', 'pvp' => 0, 'dto' => 0, 'coste' => 0, 'dto_euros' => 0, 'cantidad' => 0];
		$ver_item_entrada = false;
		$padres = [];
		$servicios = [];
		$notas_servicios = [];
		
		// CHAINS - 20240304 - Primero hacemos una primera barrida para los $items_entrada
		$listadoParaMensualidades = [];
		foreach ($servicios_items as $i => $value) {
			if (stripos($value['nombre_item'], "entrada") === false && stripos($value['nombre_item'], "mensualidad") === false) continue;
			$pvp_item = $value['cantidad'] * $value['pvp'];
			$dto_item = ($data['registro'][0]['es_repeticion'] == 1) ? 100.00 : $value['dto'];
			$dto_item_euros = ($data['registro'][0]['es_repeticion'] == 1) ? 0 : $value['dto_euros'];
			$coste_item = $pvp_item * (1 - $dto_item / 100) - $dto_item_euros;

			$nombre_familia = $value['nombre_familia'];
			if (!isset($listadoParaMensualidades[$nombre_familia])) {
				$listadoParaMensualidades[$nombre_familia] = array(
					'entrada' => null,
					'entradaobject' => $value,
					'mensualidad' => null,
					'pvp' => 0,
					'dto' => 0,
					'cantidad' => 0,
					'dto_euros' => 0,
					'coste' => 0,
				);
			}
			if (stripos($value['nombre_item'], "entrada") !== false) {
				$listadoParaMensualidades[$nombre_familia]['entrada'] = $value['id_presupuesto_item'];
			} else
            if (stripos($value['nombre_item'], "mensualidad") !== false) {
				if (!$listadoParaMensualidades[$nombre_familia]['mensualidad']) $listadoParaMensualidades[$nombre_familia]['mensualidad'] = [];
				$listadoParaMensualidades[$nombre_familia]['mensualidad'][] = $value['id_presupuesto_item'];
			}
			$listadoParaMensualidades[$nombre_familia]['pvp'] += $pvp_item;
			$listadoParaMensualidades[$nombre_familia]['dto'] = $dto_item;
			$listadoParaMensualidades[$nombre_familia]['cantidad'] += $value['cantidad'];
			$listadoParaMensualidades[$nombre_familia]['dto_euros'] += $dto_item_euros;
			$listadoParaMensualidades[$nombre_familia]['coste'] +=  $coste_item;
		}

		$listadoFinalEntradas = [];
		$incluirSolos = [];

		foreach ($listadoParaMensualidades as $familia => $itemAgrupadoMensualidades) {
			if ($itemAgrupadoMensualidades['pvp'] > $itemAgrupadoMensualidades['coste']) {
				$listadoParaMensualidades[$familia]['dto_euros'] = $itemAgrupadoMensualidades['pvp'] - $itemAgrupadoMensualidades['coste'];
			}
			$tieneAmbos = false;
			if ($itemAgrupadoMensualidades['entrada'] && count($itemAgrupadoMensualidades['mensualidad']) > 0) {
				$tieneAmbos = true;
			}
			if (!$tieneAmbos) {
				if ($itemAgrupadoMensualidades['entrada']) $incluirSolos[] = $itemAgrupadoMensualidades['entrada'];
				if (count($itemAgrupadoMensualidades['mensualidad'])) {
					foreach ($itemAgrupadoMensualidades['mensualidad'] as $idd) $incluirSolos[] = $idd;
				}
			} else {
				$listadoParaMensualidades[$familia]['cantidad'] = 1;
				$listadoFinalEntradas[$familia] = $listadoParaMensualidades[$familia];
			}
		}

		foreach ($servicios_items as $i => $value) {

			$pvp_unidad_item = $value['pvp'];
			$pvp_item = $value['cantidad'] * $value['pvp'];
			$dto_item = ($data['registro'][0]['es_repeticion'] == 1) ? 100.00 : $value['dto'];
			$dto_item_euros = ($data['registro'][0]['es_repeticion'] == 1) ? 0 : $value['dto_euros'];
			$coste_item = $pvp_item * (1 - $dto_item / 100) - $dto_item_euros;
			if (stripos($value['nombre_item'], "entrada") !== false || stripos($value['nombre_item'], "mensualidad") !== false) {
				if (in_array($value['id_presupuesto_item'], $incluirSolos)) {
					// no es ni entrada ni hijo. Se revisa a ver si se agrupa por servicio
					if (!array_key_exists($value['id_item'], $servicios)) {
						// no existe. Se crea elemento asociado al dtocrear array con el descuento que llegue
						$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
						$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
						$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
						$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
						$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_unidad_item;
						$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
						$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
						$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
					} else {
						// existe. se revisa si existe el elemento con el mismo descuento
						if (!array_key_exists($value['dto'], $servicios[$value['id_item']])) {
							$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
							$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
							$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
							$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
							$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_unidad_item;
							$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
							$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
							$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
						} else {
							$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
							$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
							$servicios[$value['id_item']][$value['dto']]['cantidad'] += $value['cantidad'];
							$servicios[$value['id_item']][$value['dto']]['dientes'] .= ', ' . $value['dientes'];
							$servicios[$value['id_item']][$value['dto']]['pvp'] = $pvp_unidad_item;
							$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
							$servicios[$value['id_item']][$value['dto']]['dto_euros'] += $dto_item_euros;
							$servicios[$value['id_item']][$value['dto']]['coste'] += $coste_item;
						}
					}
				} else continue;
			} else
            if ($value['padre'] > 0) {
				// si es un elemento hijo, se agrupa al padre
				if (!array_key_exists($value['padre'], $padres) || !isset($padres[$value['padre']][$value['dientes']])) {
					$padres[$value['padre']][$value['dientes']]['coste'] =  $coste_item;
					$padres[$value['padre']][$value['dientes']]['pvp'] =  $pvp_item;
					$padres[$value['padre']][$value['dientes']]['dto'] =  $dto_item;
					$padres[$value['padre']][$value['dientes']]['dto_euros'] =  $dto_item_euros;
					if (!isset($padres[$value['padre']][$value['dientes']]['subcontadores']))
						$padres[$value['padre']][$value['dientes']]['subcontadores'] =  [];
					if (!isset($padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']]))
						$padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']] =  0;
					$padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']]++;
				} else {
					$padres[$value['padre']][$value['dientes']]['coste'] +=  $coste_item;
					$padres[$value['padre']][$value['dientes']]['pvp'] +=  $pvp_item;
					$padres[$value['padre']][$value['dientes']]['dto'] =  $dto_item;
					$padres[$value['padre']][$value['dientes']]['dto_euros'] +=  $dto_item_euros;
					if (!isset($padres[$value['padre']][$value['dientes']]['subcontadores']))
						$padres[$value['padre']][$value['dientes']]['subcontadores'] =  [];
					if (!isset($padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']]))
						$padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']] =  0;
					$padres[$value['padre']][$value['dientes']]['subcontadores'][$value['id_item']]++;
				}
			} else {
				// no es ni entrada ni hijo. Se revisa a ver si se agrupa por servicio
				if (!array_key_exists($value['id_item'], $servicios)) {
					// no existe. Se crea elemento asociado al dtocrear array con el descuento que llegue
					$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
					$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
					$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
					$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
					$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_unidad_item;
					$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
					$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
					$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
				} else {
					// existe. se revisa si existe el elemento con el mismo descuento
					if (!array_key_exists($value['dto'], $servicios[$value['id_item']])) {
						$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
						$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
						$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
						$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
						$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_unidad_item;
						$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
						$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
						$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
					} else {
						$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
						$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
						$servicios[$value['id_item']][$value['dto']]['cantidad'] += $value['cantidad'];
						$servicios[$value['id_item']][$value['dto']]['dientes'] .= ', ' . $value['dientes'];
						$servicios[$value['id_item']][$value['dto']]['pvp'] = $pvp_unidad_item;
						$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
						$servicios[$value['id_item']][$value['dto']]['dto_euros'] += $dto_item_euros;
						$servicios[$value['id_item']][$value['dto']]['coste'] += $coste_item;
					}
				}
			}
			
			if ( $value['notas'] && !in_array($value['notas'], $notas_servicios) ){
				$notas_servicios[] = $value['notas'];
			}
		}
		
		// Ajuste de las unidades de los padres:
		foreach ($padres as $idpadre => $dientespadre) {
			foreach ($dientespadre as $iddiente => $datadientespadre) {
				$padres[$idpadre][$iddiente]['unidades'] = 1;
				$max = 1;
				foreach ($datadientespadre['subcontadores'] as $ccc) {
					if ($ccc > $max) $max = $ccc;
				}
				$padres[$idpadre][$iddiente]['unidades'] = $max;
			}
		}


		$data['servicios_items'] = $servicios_items;
		/*$data['item_entrada'] = $item_entrada;
        $data['ver_item_entrada'] = $ver_item_entrada;*/
		$data['listaentradas'] = $listadoFinalEntradas;
		$data['padres'] = $padres;
		$data['servicios'] = $servicios;
		$data['notas_servicios'] = $notas_servicios;
		//  echo "<pre>";print_r($data);die();
		
		$content_view = $this->load->view('pdf/presupuestos_pdf_view', $data, true);
		$this->load->library('pdf');
		$this->pdf->stream($content_view, "pr-" . $data['registro'][0]['id_cliente'] - $id_presupuesto . "-" . time() . ".pdf", array("Attachment" => false));
	}



	function ver_pdf_porsi($id_presupuesto)
	{
		// CHAINS 20240105 - Se cambia la impresion pdf se deja este por si hay algun problema y hay que recuperarlo
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		/*if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }*/
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);
		if ($data['registro'][0]['estado'] == 'Borrador') {
			$this->session->set_userdata('msn_actionno', 'No puedes clonar este presupuesto, no ha sido entregado al cliente. Puedes editarlo');
			redirect('presupustos');
		}

		$paramd['id_usuario'] = $data['registro'][0]['id_doctor'];
		$data['doctor'] = $this->Usuarios_model->leer_usuarios($paramd);

		$paramc['id_centro'] = $data['doctor'][0]['id_centro'];
		$data['centro'] = $this->Usuarios_model->leer_centros($paramc);

		// ... Leemos los items del presupuesto
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		//$param['aceptado'] = 1;
		/* CHAINS 20240218 - Se añade el parámetro para decidir la dirección de ordenado */
		$servicios_items = $this->Presupuestos_model->leer_presupuestos_items($param, 'ASC');
		// manejamos aqui los elementos como lo haciamos en el pdf
		// se pasa a ala vista los datos formateados para que sea mas sencillo de procesar ahí
		$item_entrada = ['entrada' => '', 'mensualidad' => '', 'pvp' => 0, 'dto' => 0, 'coste' => 0, 'dto_euros' => 0, 'cantidad' => 0];
		$ver_item_entrada = false;
		$padres = [];
		$servicios = [];

		foreach ($servicios_items as $i => $value) {
			// Si el presupuesto es de repetición, el descuento de cada item es siempre el 100%
			$pvp_item = $value['cantidad'] * $value['pvp'];
			$dto_item = ($data['registro'][0]['es_repeticion'] == 1) ? 100.00 : $value['dto'];
			$dto_item_euros = ($data['registro'][0]['es_repeticion'] == 1) ? 0 : $value['dto_euros'];
			$coste_item = $pvp_item * (1 - $dto_item / 100) - $dto_item_euros;
			// si el nombre item es entrada, mensualidad, se agrupa
			if (stripos($value['nombre_item'], "entrada") !== false || stripos($value['nombre_item'], "mensualidad") !== false) {
				if (stripos($value['nombre_item'], "entrada") !== false) {
					$item_entrada['entrada'] = str_ireplace('entrada', '', $value['nombre_item']);
				}
				if (stripos($value['nombre_item'], "mensualidad") !== false) {
					$item_entrada['mensualidad'] = str_ireplace('mensualidad', '', $value['nombre_item']);
				}
				$item_entrada['pvp'] +=  $pvp_item;
				$item_entrada['dto'] = $dto_item;
				$item_entrada['cantidad'] += $value['cantidad'];
				$item_entrada['dto_euros'] += $dto_item_euros;
				$item_entrada['coste'] +=  $coste_item;
				$ver_item_entrada = true;
			} else if ($value['padre'] > 0) {
				// si es un elemento hijo, se agrupa al padre
				if (!array_key_exists($value['padre'], $padres) || !isset($padres[$value['padre']][$value['dientes']])) {
					$padres[$value['padre']][$value['dientes']]['coste'] =  $coste_item;
					$padres[$value['padre']][$value['dientes']]['pvp'] =  $pvp_item;
					$padres[$value['padre']][$value['dientes']]['dto'] =  $dto_item;
					$padres[$value['padre']][$value['dientes']]['dto_euros'] =  $dto_item_euros;
				} else {
					$padres[$value['padre']][$value['dientes']]['coste'] +=  $coste_item;
					$padres[$value['padre']][$value['dientes']]['pvp'] +=  $pvp_item;
					$padres[$value['padre']][$value['dientes']]['dto'] =  $dto_item;
					$padres[$value['padre']][$value['dientes']]['dto_euros'] +=  $dto_item_euros;
				}
			} else {
				// no es ni entrada ni hijo. Se revisa a ver si se agrupa por servicio
				if (!array_key_exists($value['id_item'], $servicios)) {
					// no existe. Se crea elemento asociado al dtocrear array con el descuento que llegue
					$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
					$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
					$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
					$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
					$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_item;
					$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
					$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
					$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
				} else {
					// existe. se revisa si existe el elemento con el mismo descuento
					if (!array_key_exists($value['dto'], $servicios[$value['id_item']])) {
						$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
						$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
						$servicios[$value['id_item']][$value['dto']]['cantidad'] = $value['cantidad'];
						$servicios[$value['id_item']][$value['dto']]['dientes'] = $value['dientes'];
						$servicios[$value['id_item']][$value['dto']]['pvp'] =  $pvp_item;
						$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
						$servicios[$value['id_item']][$value['dto']]['dto_euros'] = $dto_item_euros;
						$servicios[$value['id_item']][$value['dto']]['coste'] = $coste_item;
					} else {
						$servicios[$value['id_item']][$value['dto']]['nombre_item'] = $value['nombre_item'];
						$servicios[$value['id_item']][$value['dto']]['nombre_familia'] = $value['nombre_familia'];
						$servicios[$value['id_item']][$value['dto']]['cantidad'] += $value['cantidad'];
						$servicios[$value['id_item']][$value['dto']]['dientes'] .= ', ' . $value['dientes'];
						$servicios[$value['id_item']][$value['dto']]['pvp'] = $pvp_item;
						$servicios[$value['id_item']][$value['dto']]['dto'] = $value['dto'];
						$servicios[$value['id_item']][$value['dto']]['dto_euros'] += $dto_item_euros;
						$servicios[$value['id_item']][$value['dto']]['coste'] += $coste_item;
					}
				}
			}
		}
		//printr($padres);
		$data['servicios_items'] = $servicios_items;
		$data['item_entrada'] = $item_entrada;
		$data['ver_item_entrada'] = $ver_item_entrada;
		$data['padres'] = $padres;
		$data['servicios'] = $servicios;
		//printr($servicios);
		$content_view = $this->load->view('pdf/presupuestos_pdf_view_porsi', $data, true);
		$this->load->library('pdf');
		$this->pdf->stream($content_view, "pr-" . $data['registro'][0]['id_cliente'] - $id_presupuesto . "-" . time() . ".pdf", array("Attachment" => false));
	}




	function dietario($accion = null, $fecha = null, $id_centro = null)
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
		$data['usuarios'] = $this->Presupuestos_model->get_usuarios_presupuestos();

		// ... Viewer con el contenido
		$data['pagetitle'] = 'Dietario de presupuestos';
		$data['actionstitle'] = ['<a href="javascript:PagoPresupuesto()" class="btn btn-primary text-inverse-primary">Registrar pago</a>'];
		$data['content_view'] = $this->load->view('presupuestos/dietario_view', $data, true);
		//$data['content_view'] = $this->load->view('presupuestos/presupuestos_view', $data, true);
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

	public function get_presupuestos_pagos($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			'presupuestos_pagos.fecha_creacion',
			'presupuestos.nro_presupuesto',
			'(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
			'presupuestos_pagos.concepto',
			'presupuestos_pagos.cantidad',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS usuario',
			'dietario.estado',
			'presupuestos_pagos.*',
			'(DATE_FORMAT(presupuestos_pagos.fecha_creacion,"%d-%m-%Y %H:%i") ) AS f_creacion',
		];
		$tabla = 'presupuestos_pagos';
		$join = [
			'presupuestos' => 'presupuestos_pagos.id_presupuesto = presupuestos.id_presupuesto',
			'dietario' => 'presupuestos_pagos.id_dietario = dietario.id_dietario',
			'clientes' => 'presupuestos_pagos.id_cliente = clientes.id_cliente',
			'usuarios' => 'presupuestos_pagos.id_usuario_creacion = usuarios.id_usuario',
		];
		$add_rule = [];
		$where = ['presupuestos_pagos.borrado' => 0];

		if ($this->input->get('id_presupuesto') != '') {
			$where['presupuestos_pagos.id_presupuesto'] = $this->input->get('id_presupuesto');
		}
		if ($this->input->get('id_cliente') != '') {
			$where['presupuestos_pagos.id_cliente'] = $this->input->get('id_cliente');
		}
		if ($this->input->get('id_usuario') != '') {
			$where['presupuestos_pagos.id_usuario'] = $this->input->get('id_usuario');
		}
		if ($this->input->get('estado') != '') {
			$where['presupuestos_pagos.estado'] = $this->input->get('estado');
		}
		if ($this->input->get('fecha_desde') != '') {
			$where['presupuestos_pagos.fecha_creacion >='] = $this->input->get('fecha_desde');
		}
		if ($this->input->get('fecha_hasta') != '') {
			$where['presupuestos_pagos.fecha_creacion >='] = $this->input->get('fecha_hasta');
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

	public function pago_presupuesto()
	{
		// ... Comprobamos la sesion del capacidad
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$data['vacio'] = "";
		// ... Modulos del usuario
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

		// ... Pagina master
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 57);
		if ($permiso) {
			$this->load->view('presupuestos/pago_presupuesto_view', $data);
		} else {
			header("Location: " . RUTA_WWW . "/errores/error_404.html");
			exit;
		}
	}

	public function realizar_pago_presupuesto()
	{
		// ... Comprobamos la sesion del capacidad
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		if ($this->input->post('id_cliente') > 0) {
			unset($param5);
			$param5['id_cliente'] = $this->input->post('id_cliente');
			$data['cliente_elegido'] = $this->Clientes_model->leer_clientes($param5);

			unset($param);
			$param['id_cliente'] = $this->input->post('id_cliente');
			$param['total_pendiente'] = 1;
			$data['presupuestos_cliente'] = $this->Presupuestos_model->leer_presupuestos_pendiente($param);
		}

		if ($this->input->post('accion') == 'submit') {
			// se guarada en pagos y despues en dietario
			$id_presupuesto = $this->input->post('id_presupuesto');
			$id_cliente = $this->input->post('id_cliente');
			$importe = $this->input->post('importe');
			$tipo_pago = $this->input->post('tipo_pago');
			$concepto = $this->input->post('concepto');
			$data['operacion_correcta'] = $this->Presupuestos_model->realizar_pago_presupuesto($id_presupuesto, $id_cliente, $importe, $tipo_pago, $concepto);
		}
		/*if ($this->input->post('id_presupuesto') > 0) {
            unset($param1);
			$param1['id_cliente'] = $this->input->post('id_cliente');
			$param1['total_pendiente'] = 1;
			$data['presupuestos_cliente'] = $this->Presupuestos_model->leer_presupuestos($param1);
        }*/


		// ... Modulos del usuario
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
		// ... Pagina master
		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 57);
		if ($permiso) {
			$this->load->view('presupuestos/pago_presupuesto_view', $data);
		} else {
			header("Location: " . RUTA_WWW . "/errores/error_404.html");
			exit;
		}
	}

	public function pagoeuros()
	{
		// ... Comprobamos la sesion del capacidad
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$parametros = $_POST;
		$param['id_presupuesto'] = $parametros['id_presupuesto'];
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param);
		$parametros['id_cliente'] = $presupuesto[0]['id_cliente'];
		$data['operacion_correcta'] = $this->Presupuestos_model->pagoeuros($parametros);
		$this->session->set_userdata('msn_estado', $data['operacion_correcta']);
		header("Location: " . RUTA_WWW . "/Presupuestos");
		exit;
	}

	function pagoeurosajax()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		$parametros = $_POST;
		$param['id_presupuesto'] = $parametros['id_presupuesto'];
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param);
		$parametros['id_cliente'] = $presupuesto[0]['id_cliente'];
		$data['operacion_correcta'] = $this->Presupuestos_model->pagoeuros($parametros);

		if ($data['operacion_correcta'] == true) {
			$response = array(
				'status' => 'success',
				'message' => 'Pago registrado'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Ocurrió un error al registrar el pago'
			);
		}
		$json_response = json_encode($response);
		header('Content-Type: application/json');
		echo $json_response;
	}

	public function cargarJustificante()
	{
		$this->load->library('upload');
		if (!empty($_FILES['fileToUpload']['name']) && $this->input->post('id_dietario') != '') {
			$nombre_archivo = $_FILES['fileToUpload']['name'];
			$nombre_archivo_sin_espacios = str_replace(' ', '-', $nombre_archivo);
			$nombre_archivo_limpio = preg_replace('/[^a-zA-Z0-9_.-]/', '', $nombre_archivo_sin_espacios);
			$config['upload_path'] = FCPATH . 'recursos/justificantes/';
			$config['allowed_types'] = '*';
			$config['file_name'] =  $this->input->post('id_dietario') . '-' . $nombre_archivo_limpio;
			$this->upload->initialize($config);
			if ($this->upload->do_upload('fileToUpload')) {



				$param = [
					'id_dietario' => $this->input->post('id_dietario'),
					'id_presupuesto' => $this->input->post('id_presupuesto'),
					'comisionfinanciacion' => $this->input->post('comisionfinanciacion'),
					'fileurl' => 'recursos/justificantes/' . $config['file_name']
				];
				$result = $this->Presupuestos_model->marcarPagado($param);
				if ($result) {
					// RCG - Calcular los gastos de financiacion por item
					$params2 = [];
					$params2['comision'] = isset($_POST['comisionfinanciacion']) ? $_POST['comisionfinanciacion'] : 0;
					$params2['presupuestos'] = isset($_POST['presupfinanciacion']) ? array_keys($_POST['presupfinanciacion']) : [];
					$params2['id_dietario'] = $param['id_dietario'];
					$this->Presupuestos_model->procesarGastosFinanciacion($params2);
				}
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function asignarFinanciacionPresu()
	{
		$result = null;
		$this->load->model('Dietario_model');
		$dietarios = $this->Dietario_model->leer(['id_dietario' => $this->input->post('id_dietario')]);
		if ($dietarios) {
			$presupids = isset($_POST['presupfinanciacion']) ? array_keys($_POST['presupfinanciacion']) : [];
			if (count($presupids)) {
				//$comision = $dietarios[0]['comisionfinanciacion'];
				$comision = isset($_POST['comisionfinanciacion2']) && !empty($_POST['comisionfinanciacion2'])
					? $_POST['comisionfinanciacion2'] : $dietarios[0]['comisionfinanciacion'];

				// RCG - Calcular los gastos de financiacion por item
				$params2 = [];
				$params2['comision'] = $comision;
				$params2['presupuestos'] = $presupids;
				$params2['id_dietario'] = $this->input->post('id_dietario');
				$this->Presupuestos_model->procesarGastosFinanciacion($params2);
				$this->Dietario_model->actualizar_comFinanciacion($params2['id_dietario'], $comision);
				$result = true;
			} else $result = false;
		} else {
			$result = false;
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function updateGastoLaboratorio()
	{
		$this->form_validation->set_rules('id_presupuesto_item', 'Item', 'required');
		$this->form_validation->set_rules('gastos_lab', 'Gasto', 'required');

		if ($this->form_validation->run() != false) {


			/* CHAINS 20240305 - Validar si ya está liquidado */
			$itempresupuesto1 = $this->Presupuestos_model->leer_presupuestos_items(['id_presupuesto_item' => $this->input->post('id_presupuesto_item')]);
			$itempresupuesto = null;
			if ($itempresupuesto1 > 0) {
				$itempresupuesto = $itempresupuesto1[0];
			}

			if (!$itempresupuesto) return false;

			$presupuesto1 = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $itempresupuesto['id_presupuesto']]);
			$presupuesto = $presupuesto1[0];



			$this->load->model('Liquidaciones_model');
			$liquidacion = $this->Liquidaciones_model->leer_liquidacion_itempresupuesto($this->input->post('id_presupuesto_item'));

			$archivada = false;
			$liquidacionBase = null;
			if ($liquidacion > 0) {
				if ($liquidacion[0]['id_liquidacion'] > 0) {

					$liquidacionBase1 = $this->Liquidaciones_model->leer_liquidaciones(['id_liquidacion' => $liquidacion[0]['id_liquidacion']]);

					if ($liquidacionBase1 > 0) {
						$liquidacionBase = $liquidacionBase1[0];
						if ($liquidacionBase['estado']) $archivada = true;
					}
				}

				if ($liquidacion[0]['estado'] == 1) {
					if ($archivada) {
						$registro = [];
						$registro['id_usuario'] = $liquidacion[0]['id_usuario'];
						$registro['id_presupuesto_item'] = 0;
						$registro['id_cita'] = 0;
						$registro['fecha_cita'] = date('Y-m-d H:i:s');
						$registro['item'] = 'Servicio';
						$registro['id_item'] = 0;
						$registro['id_familia_item'] = 0;
						$registro['id_cliente'] = 0;
						$registro['pvp'] = ($liquidacion[0]['gastos_lab'] - $this->input->post('gastos_lab'));
						$registro['dto'] = 0;
						$registro['dtop'] = 0;
						$registro['com_financiacion'] = 0;
						$registro['gastos_lab'] = 0;
						$registro['total'] = ($liquidacion[0]['gastos_lab'] - $this->input->post('gastos_lab'));
						$registro['concepto'] = 'Ajuste gastos de laboratorio en ' . $itempresupuesto['nombre_item'] . ' del presupueto ' . $presupuesto['nro_presupuesto'];
						$registro['estado'] = 0;
						$nueva_linea = $this->Liquidaciones_model->nueva_linea($registro);
						$response = ['success' => ($nueva_linea > 1) ? true : false];
					} else {
						// esta procesada pero no esta archivada
						$param = [];
						$param = [
							'id_presupuesto_item' => $this->input->post('id_presupuesto_item'),
							'gastos_lab' => $this->input->post('gastos_lab'),
						];
						$this->Liquidaciones_model->actualizar_gastos_laboratorio($param);
					}
				} else {
					// Hay linea de liquidacion pero no esta procesada
					$param = [];
					$param = [
						'id_presupuesto_item' => $this->input->post('id_presupuesto_item'),
						'gastos_lab' => $this->input->post('gastos_lab'),
					];
					$this->Liquidaciones_model->actualizar_gastos_laboratorio($param);
				}
			} else {
				// No hay linea de liquidacion

			}
			$param = [
				'id_presupuesto_item' => $this->input->post('id_presupuesto_item'),
				'gastos_lab' => $this->input->post('gastos_lab'),
			];
			$result = $this->Presupuestos_model->actualizar_item_presupuesto($param);

			/* FIN CHAINS */

			$result = true;
		} else {
			$result = false;
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function updateDientesItem()
	{
		$this->form_validation->set_rules('id_presupuesto_item', 'Item', 'required');
		$this->form_validation->set_rules('dientes', 'Diente', 'required');

		if ($this->form_validation->run() != false) {
			$param = [
				'id_presupuesto_item' => $this->input->post('id_presupuesto_item'),
				'dientes' => $this->input->post('dientes'),
			];
			$result = $this->Presupuestos_model->actualizar_item_presupuesto($param);
		} else {
			$result = false;
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($result));
	}

	public function master_presupuesto($id_presupuesto)
	{
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		if ($this->session->userdata('id_perfil') <> 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes editar este presupuesto.');
			redirect('presupuestos');
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
		if ($this->session->userdata('mensaje') != '') {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}
		// ... Leemos los productos
		$data['accion'] = "editar";
		$param['id_presupuesto'] = $id_presupuesto;
		$data['registro']  = $this->Presupuestos_model->leer_presupuestos($param);

		unset($param);
		$param = [];
		$data['empleados'] = $this->Usuarios_model->leer_usuarios($param);

		unset($param);
		$param['id_perfil'] = 6;
		$param['borrado'] = 0;
		$data['doctores'] = $this->Usuarios_model->leer_usuarios($param);
		unset($param);
		$param['id_presupuesto'] = $id_presupuesto;
		$param['tipo_item'] = 'Servicio';
		$data['servicios_items'] = $this->Presupuestos_model->leer_presupuestos_items($param);

		// ... Leemos los servicios
		unset($param);
		$param = [];
		$data['servicios'] = $this->Servicios_model->leer_servicios($param);
		if ($this->session->userdata('mensaje' != '')) {
			$data['mensaje'] = $this->session->userdata('mensaje');
			$this->session->unset_userdata('mensaje');
		}

		// 20240201 - Otros precios de tarifa

		unset($param);
		$param = [
			'activo' => 1
		];
		$data['tarifas'] = $this->Tarifas_model->leer_tarifas($param);


		$data['precios'] = [];
		if (isset($data['tarifas']) && is_array($data['tarifas'])) {
			foreach ($data['tarifas']  as $tarifa) {
				$precios = $this->Tarifas_model->leer_servicios($tarifa['id_tarifa']);
				$finalprecios = [];
				foreach ($precios as $precio) {
					if ($precio['pvp_tarifa'] !== null) {
						$finalprecios['id_servicio_' . $precio['id_servicio']] = floatval($precio['pvp_tarifa']);
					}
				}
				$data['precios']['tarifa_' . $tarifa['id_tarifa']] = $finalprecios;
			}
		}
		// 20240201 - Fin Chains


		// ... Viewer con el contenido
		$data['pagetitle'] = 'Edición MASTER de presupuesto';
		$data['content_view'] = $this->load->view('presupuestos/presupuestos_editar_master_view', $data, true);

		// ... Modulos del caja
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

	public function actualizarPresupuestoMaster()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		if ($this->session->userdata('id_perfil') <> 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes editar este presupuesto.');
			redirect('presupuestos');
		}

		$this->form_validation->set_rules('id_presupuesto', 'Presupuesto', 'required');
		$this->form_validation->set_rules('nro_presupuesto', 'Nº de presupuesto', 'required');
		$this->form_validation->set_rules('fecha_creacion', 'Fecha de creación', 'required');
		$this->form_validation->set_rules('id_usuario', 'Usuario de creación', 'required');
		$this->form_validation->set_rules('revisado', 'Presupuesto revisado', 'trim|required');
		$this->form_validation->set_rules('id_cliente', 'Cliente', 'required');
		$this->form_validation->set_rules('fecha_validez', 'Fecha de validez', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$this->form_validation->set_rules('totalpresupuesto', 'Estado', 'required');
		$id_cliente = $this->input->post('id_cliente');
		$id_presupuesto = $this->input->post('id_presupuesto');

		if ($this->form_validation->run() == false) {
			$this->session->set_userdata('mensaje', validation_errors());
			if ($id_presupuesto != '') {
				redirect('Presupuestos/master_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/nuevo_presupuesto');
			}
		}

		$id_doctor = $this->input->post('id_doctor');
		$fecha_validez = $this->input->post('fecha_validez');
		$estado = $this->input->post('estado');
		$dto_euros = $this->input->post('dto_euros');
		$dto_100 = $this->input->post('dto_100');
		$com_cuota = $this->input->post('con_cuota');
		$totalpresupuesto = $this->input->post('totalpresupuesto');
		$cuotas = $this->input->post('cuotas');
		$apertura = $this->input->post('apertura');
		$totalcuota = $this->input->post('totalcuota');

		$ids_servicios = $this->input->post('id_servicio[]');
		$ids_servicios_items = $this->input->post('ids_servicios_items[]');
		$cant_servicios = $this->input->post('servicioCantidad[]');
		$pvp_servicios = $this->input->post('servicioPrecio[]');
		$dto_servicios = $this->input->post('servicioDescuento[]');
		$dto_euros_servicios = $this->input->post('servicioDescuentoE[]');
		$dientes_servicios = $this->input->post('servicioDientes[]');
		$items_actualizar = [];
		$items_nuevos = [];

		foreach ($ids_servicios as $key => $id_servicio) {
			if ($ids_servicios[$key] != '' && $cant_servicios[$key] > 0) {
				if ($ids_servicios_items[$key] == 0) {

					if ($cant_servicios[$key] > 0) {
						// cambiar aqui:  añadir un item por cada diente que llegue
						if ($dientes_servicios[$key] != '') {
							$ndientes = explode(',', $dientes_servicios[$key]);
							//if (count($ndientes) > 0) {
							foreach ($ndientes as $d => $nd) {
								$items_nuevos[] = [
									'id_presupuesto' => $id_presupuesto,
									'id_cliente' => $id_cliente,
									'tipo_item' => 'Servicio',
									'id_item' => $id_servicio,
									'cantidad' => 1,
									'dientes' => $nd,
									'pvp' => $pvp_servicios[$key],
									'dto' => $dto_servicios[$key],
									'dto_euros' => number_format($dto_euros_servicios[$key] / count($ndientes), 2),
									'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - (number_format($dto_euros_servicios[$key] / count($ndientes), 2))
								];
							}
						} else {
							for ($i = 0; $i < $cant_servicios[$key]; $i++) {
								$items_nuevos[] = [
									'id_presupuesto' => $id_presupuesto,
									'id_cliente' => $id_cliente,
									'tipo_item' => 'Servicio',
									'id_item' => $id_servicio,
									'cantidad' => 1, //$cant_servicios[$key],
									'dientes' => $dientes_servicios[$key],
									'pvp' => $pvp_servicios[$key],
									'dto' => $dto_servicios[$key],
									'dto_euros' => $dto_euros_servicios[$key],
									'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - $dto_euros_servicios[$key]
								];
							}
						}
					}
				} else {
					$items_actualizar[$ids_servicios_items[$key]] = [
						'id_presupuesto_item' => $ids_servicios_items[$key],
						'id_presupuesto' => $id_presupuesto,
						'id_cliente' => $id_cliente,
						'tipo_item' => 'Servicio',
						'id_item' => $id_servicio,
						'cantidad' => $cant_servicios[$key],
						'dientes' => $dientes_servicios[$key],
						'pvp' => $pvp_servicios[$key],
						'dto' => $dto_servicios[$key],
						'dto_euros' => $dto_euros_servicios[$key],
						'coste' => $pvp_servicios[$key] - ($pvp_servicios[$key] * $dto_servicios[$key] / 100) - $dto_euros_servicios[$key],
					];
				}
			}
		}

		if (count($items_actualizar) < 1 && count($items_nuevos) < 1) {
			$this->session->set_userdata('mensaje', 'No se ha indicado ningun artículo para el presupuesto');
			if ($id_presupuesto != '') {
				redirect('Presupuestos/editar_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/presupuestos');
			}
		}
		$parametros = $_POST;
		$parametros['dto_euros'] = 0;
		$parametros['dto_100'] = 0;
		$param_p['id_presupuesto'] = $id_presupuesto;
		$presupuesto  = $this->Presupuestos_model->leer_presupuestos($param_p);
		if (isset($parametros['es_repeticion']) && $parametros['es_repeticion'] == 1 && !str_starts_with($presupuesto[0]['nro_presupuesto'], 'R')) {
			$parametros['nro_presupuesto'] = 'R-' . $presupuesto[0]['nro_presupuesto'];
		}
		$id_presupuesto_actualizado = $this->Presupuestos_model->actualizar_presupuesto($parametros);
		$this->Presupuestos_model->recalcular_totales($id_presupuesto);
		$nuevos_items = 0;
		$actualizados_items = 0;
		foreach ($items_nuevos as $key => $item) {
			$item['coste'] = $item['pvp'];
			// DESCUENTO PROPIO DEL ITEM
			if ($item['dto'] > 0) {
				$dto_propio_euros = $item['pvp'] * ($item['dto'] / 100);
				$item['coste'] = $item['coste'] - $dto_propio_euros;
			}
			if ($item['dto_euros'] > 0) {
				$item['coste'] = $item['coste'] - $item['dto_euros'];
			}
			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($parametros['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] + $parametros['dto_euros'];
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $parametros['dto_euros'], 2);
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros;
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			} elseif ($parametros['dto_100'] > 0) {
				$dtog = $parametros['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $parametros['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			}
			$insert_id = $this->Presupuestos_model->nuevo_item_presupuesto($item);
			$nuevos_items = $nuevos_items + $insert_id;
		}
		foreach ($items_actualizar as $key => $item) {
			$item['coste'] = $item['pvp'];
			// DESCUENTO PROPIO DEL ITEM
			if ($item['dto'] > 0) {
				$dto_propio_euros = $item['pvp'] * ($item['dto'] / 100);
				$item['coste'] = $item['coste'] - $dto_propio_euros;
			}
			if ($item['dto_euros'] > 0) {
				$item['coste'] = $item['coste'] - $item['dto_euros'];
			}
			// DESCUENTO GENERAL EUROS PRIMERO Y PORCENTAJE DESPUES
			if ($parametros['dto_euros'] > 0) {
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] + $parametros['dto_euros'];
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento;
				$dto_presupuesto_euros = number_format($proporcion * $parametros['dto_euros'], 2);
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros;
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			} elseif ($parametros['dto_100'] > 0) {
				$dtog = $parametros['dto_100'];  // descuento, en %
				$descuentoNum = number_format($dtog / 100, 2); // descuento, en multiplicador
				$totalPresupuestoSinDescuento = $parametros['totalpresupuesto'] / (1 - $descuentoNum); // precio total sin descuento
				$descuentoEur = $totalPresupuestoSinDescuento - $parametros['totalpresupuesto']; // total descontado del presupuesto
				$proporcion = $item['coste'] / $totalPresupuestoSinDescuento; //Proporcion del item en el total del presupuesto
				$dto_presupuesto_euros = number_format($proporcion * $descuentoEur, 2); // descuento especifico sobre el item  
				$nuevoPVP = $item['coste'] - $dto_presupuesto_euros; // nuevo precio, con el descuento especifico aplicado
				$item['dtop'] = $dto_presupuesto_euros;
				$item['coste'] = number_format($nuevoPVP, 2);
			}
			$actualizado_id = $this->Presupuestos_model->actualizar_item_presupuesto($item);
			$actualizados_items = $actualizados_items + $actualizado_id;
		}

		if ($id_presupuesto_actualizado < 1 && $nuevos_items < 1 && $actualizados_items > 1) {
			$this->session->set_userdata('mensaje', 'No se han realizado cambios en el presupuesto');
			if ($id_presupuesto != '') {
				redirect('Presupuestos/master_presupuesto/' . $id_presupuesto);
			} else {
				redirect('Presupuestos/presupuestos');
			}
		} else {
			$this->session->set_userdata('msn_estado', 1);
			redirect('Presupuestos/master_presupuesto/' . $id_presupuesto);
		}
	}

	public function add_cita_presupuesto_item()
	{
		if (!$this->input->server('HTTP_X_REQUESTED_WITH') || strtolower($this->input->server('HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
			$response = array('success' => false, 'error' => true, 'msn' => 'Error en el formato de la petición');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			$response = array('success' => false, 'error' => true, 'msn' => 'No puedes realizar esta acción (1)');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}

		if ($this->session->userdata('id_perfil') != 0 && $this->session->userdata('id_perfil') != 3) {
			$response = array('success' => false, 'error' => true, 'msn' => 'No puedes realizar esta acción (2)');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}

		unset($parametros);
		$parametros = $_POST;
		if (isset($parametros)) {
			unset($param);
			unset($param_serv);

			$id_presupuesto_item = $parametros['id_presupuesto_item'];
			unset($param);
			$param['id_presupuesto_item'] = $id_presupuesto_item;
			$presupuesto_item = $this->Presupuestos_model->leer_presupuestos_items($param);

			if (!isset($presupuesto_item[0])) {
				$response = array('success' => false, 'error' => true, 'msn' => 'No se ha encontrado el servicio del presupuesto');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($response));
				return;
			}
			$presupuesto_item = $presupuesto_item[0];

			$presupuesto = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $presupuesto_item['id_presupuesto']]);
			$id_servicio = $param_serv['id_servicio'] = $presupuesto_item['id_item'];
			$serv = $this->Servicios_model->leer_servicios($param_serv);
			if ($serv[0]['duracion'] == 10) {
				$serv[0]['duracion'] = 15;
			}
			if ($serv[0]['duracion'] == 20) {
				$serv[0]['duracion'] = 30;
			}
			if ($parametros['fecha_cita'] != '') {
				$fecha_hora = explode('T', $parametros['fecha_cita']);
			} else {
				$fecha_hora = ['2000-01-01', '01:01:01'];
			}
			$param['duracion'] = $serv[0]['duracion'];
			$param['id_servicio'] = $id_servicio;
			$param['id_empleado'] = ($parametros['id_doctor'] != '') ? $parametros['id_doctor'] : 1;
			$param['id_cliente'] = $presupuesto_item['id_cliente'];
			$param['fecha'] = $fecha_hora[0];
			$param['hora'] = $fecha_hora[1];
			$id_cita_creada = $this->Agenda_model->guardar_cita($param);
			if ($id_cita_creada > 0) {
				unset($param2);
				$param2['id_cita'] = $id_cita_creada;
				$param2['id_presupuesto'] = $presupuesto_item['id_presupuesto'];
				$param2['importe_euros'] = $presupuesto_item['coste'];
				$r = $this->Dietario_model->copiar_cita_presupuesto($param2);
				$this->Presupuestos_model->actualizar_presupuesto_item_cita_dietario($id_presupuesto_item, $id_cita_creada, $r);

				$finalizada = false;
				if (isset($parametros['finalizar']) && $parametros['finalizar'] == 1) {
					unset($param);
					$param['estado'] = "Finalizado";
					$param['observaciones'] = 'Cita generada';
					$q = "UPDATE citas SET estado = 'Finalizado', observaciones = 'Cita generada', id_usuario_modificacion = " . $this->session->userdata('id_usuario') . ", fecha_modificacion = '" . date('Y-m-d H:i:s') . "' WHERE id_cita = " . $id_cita_creada;
					$ok = $this->db->query($q);

					$q = "UPDATE dietario SET estado = 'Presupuesto', id_usuario_modificacion = " . $this->session->userdata('id_usuario') . ", fecha_modificacion = '" . date('Y-m-d H:i:s') . "' WHERE id_dietario = " . $r;
					$ok = $this->db->query($q);

					$finalizada = true;
					if ($parametros['id_doctor'] != '') {
						$this->load->model('Liquidaciones_model');
						$this->Liquidaciones_model->liquidacion_cita($id_cita_creada);
					}
				}

				if (isset($parametros['pagada']) && $parametros['pagada'] == 1) {
					//crear el pago
					$pvp = $presupuesto_item['pvp'];
					$dto =  $presupuesto_item['dto'];
					$dtop =  $presupuesto_item['dtop'];
					$coste = $pvp - ($pvp * $dto / 100) - ($pvp * $dtop / 100);
					$par = [
						'pagado_efectivo' => $coste,
						'id_cliente' => $presupuesto[0]['id_cliente'],
						'id_presupuesto' => $presupuesto[0]['id_presupuesto'],
					];
					$this->Presupuestos_model->pagoeuros($par);
				}

				$param3['id_presupuesto_item'] = $id_presupuesto_item;
				$presupuesto_item3 = $this->Presupuestos_model->leer_presupuestos_items($param3);

				$response = array('success' => true, 'error' => false, 'msn' => 'Cita creada', 'finalizada' => $finalizada, 'fecha_hora_inicio' => $fecha_hora[0] . ' ' . $fecha_hora[1], 'id_presupuesto_item' => $id_presupuesto_item, 'id_usuario_empleado' => $presupuesto_item3[0]['id_usuario_empleado'], 'empleado' => $presupuesto_item3[0]['empleado'], 'estado_cita' => $presupuesto_item3[0]['estado_cita'], 'estado_dietario' => $presupuesto_item3[0]['estado_dietario']);

				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($response));
				return;
			}
		} else {
			$response = array('success' => false, 'error' => true, 'msn' => 'No se han recibido datos');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
	}

	public function edit_cita_presupuesto_item()
	{
		if (!$this->input->server('HTTP_X_REQUESTED_WITH') || strtolower($this->input->server('HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
			$response = array('success' => false, 'error' => true, 'msn' => 'Error en el formato de la petición');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			$response = array('success' => false, 'error' => true, 'msn' => 'No puedes realizar esta acción (1)');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}

		if ($this->session->userdata('id_perfil') != 0) {
			$response = array('success' => false, 'error' => true, 'msn' => 'No puedes realizar esta acción (2)');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}

		unset($parametros);
		$parametros = $_POST;
		if (isset($parametros)) {
			unset($param);
			unset($param_serv);

			$id_presupuesto_item = $parametros['id_presupuesto_item'];
			unset($param);
			$param['id_presupuesto_item'] = $id_presupuesto_item;
			$presupuesto_item = $this->Presupuestos_model->leer_presupuestos_items($param);

			if (!isset($presupuesto_item[0])) {
				$response = array('success' => false, 'error' => true, 'msn' => 'No se ha encontrado el servicio del presupuesto');
				$this->output->set_content_type('application/json');
				$this->output->set_output(json_encode($response));
				return;
			}
			$presupuesto_item = $presupuesto_item[0];
			$presupuesto = $this->Presupuestos_model->leer_presupuestos(['id_presupuesto' => $presupuesto_item['id_presupuesto']]);
			$id_servicio = $param_serv['id_servicio'] = $presupuesto_item['id_item'];
			$serv = $this->Servicios_model->leer_servicios($param_serv);
			if ($serv[0]['duracion'] == 10) {
				$serv[0]['duracion'] = 15;
			}
			if ($serv[0]['duracion'] == 20) {
				$serv[0]['duracion'] = 30;
			}
			if ($parametros['fecha_cita'] != '') {
				$fecha_hora = explode('T', $parametros['fecha_cita']);
			} else {
				$fecha_hora = ['2000-01-01', '01:01:01'];
			}
			$param['duracion'] = $serv[0]['duracion'];
			$param['id_servicio'] = $id_servicio;
			$param['id_empleado'] = $param2['id_empleado'] = ($parametros['id_doctor'] != '') ? $parametros['id_doctor'] : 1;
			$param['id_cliente'] = $presupuesto_item['id_cliente'];
			$param['fecha'] = $fecha_hora[0];
			$param['hora'] = $fecha_hora[1];
			$param['id_cita'] = $presupuesto_item['id_cita'];

			$this->Agenda_model->modificar_cita($param);

			if ($presupuesto_item['id_dietario'] > 0) {
				$q = "UPDATE dietario SET fecha_hora_concepto = '" . $fecha_hora[0] . " " . $fecha_hora[1] . "', id_empleado = " . $param['id_empleado'] . ", id_usuario_modificacion = " . $this->session->userdata('id_usuario') . ", fecha_modificacion = '" . date('Y-m-d H:i:s') . "' WHERE id_dietario = " .  $presupuesto_item['id_dietario'];
				$ok = $this->db->query($q);
			}
			// hasta aqui, todo correcto
			if (isset($parametros['finalizar']) && $parametros['finalizar'] == 1) {
				unset($param);
				$param['estado'] = "Finalizado";
				$param['observaciones'] = 'Cita generada';
				$q = "UPDATE citas SET estado = 'Finalizado', observaciones = 'Cita generada', id_usuario_modificacion = " . $this->session->userdata('id_usuario') . ", fecha_modificacion = '" . date('Y-m-d H:i:s') . "' WHERE id_cita = " . $presupuesto_item['id_cita'];
				$ok = $this->db->query($q);
				$finalizada = true;
				if ($parametros['id_doctor'] != '') {
					$this->load->model('Liquidaciones_model');
					$this->Liquidaciones_model->liquidacion_cita($presupuesto_item['id_cita']);
				}
			}

			if (isset($parametros['pagada']) && $parametros['pagada'] == 1) {
				//crear el pago
				$pvp = $presupuesto_item['pvp'];
				$dto =  $presupuesto_item['dto'];
				$dtop =  $presupuesto_item['dtop'];
				$coste = $pvp - ($pvp * $dto / 100) - ($pvp * $dtop / 100);
				$par = [
					'pagado_efectivo' => $coste,
					'id_cliente' => $presupuesto[0]['id_cliente'],
					'id_presupuesto' => $presupuesto[0]['id_presupuesto'],
				];
				$this->Presupuestos_model->pagoeuros($par);
			}

			if (isset($parametros['novino']) && $parametros['novino'] == 1) {
				unset($param);
				$param['id_cita'] = $presupuesto_item['id_cita'];
				$ok = $this->Agenda_model->ocultar_citas_avisos($param);
				$param['estado'] = "No vino";
				$param['observaciones'] = 'No Vino - Generada';
				$ok = $this->Agenda_model->cambio_estado_cita($param);
				$ok = $this->Dietario_model->cambio_estado_dietario($param);
			}

			$param3['id_presupuesto_item'] = $id_presupuesto_item;
			$presupuesto_item3 = $this->Presupuestos_model->leer_presupuestos_items($param3);

			$response = array('success' => true, 'error' => false, 'msn' => 'Cita editada', 'fecha_hora_inicio' => $fecha_hora[0] . ' ' . $fecha_hora[1], 'id_presupuesto_item' => $id_presupuesto_item, 'empleado' => $presupuesto_item3[0]['empleado'], 'estado_cita' => $presupuesto_item3[0]['estado_cita']);
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		} else {
			$response = array('success' => false, 'error' => true, 'msn' => 'No se han recibido datos');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
	}

	function borrarPagoPresupuesto()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$parametros = $_POST;
		$data['operacion_correcta'] = $this->Presupuestos_model->borrarpagoeuros($parametros);

		if ($data['operacion_correcta'] == true) {
			$response = array(
				'status' => 'success',
				'message' => 'Pago eliminado'
			);
		} else {
			$response = array(
				'status' => 'error',
				'message' => 'Ocurrió un error al eliminar el pago'
			);
		}
		$json_response = json_encode($response);
		header('Content-Type: application/json');
		echo $json_response;
	}

	function buscarServicio()
	{

		if (!$this->input->server('HTTP_X_REQUESTED_WITH') || strtolower($this->input->server('HTTP_X_REQUESTED_WITH')) !== 'xmlhttprequest') {
			$response = array('success' => false, 'error' => true, 'msn' => 'Error en el formato de la petición');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			$response = array('success' => false, 'error' => true, 'msn' => 'No puedes realizar esta acción (1)');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
		unset($parametros);
		$parametros = $_POST;
		if (isset($parametros)) {
			$parametros['citas'] = 1;
			$parametros['citas'] = 1;
			$parametros['aceptado'] = 1;
			$presupuesto_item = $this->Presupuestos_model->leer_presupuestos_items($parametros);
			$presupuestos = [];
			foreach ($presupuesto_item as $key => $value) {
				if (!in_array($value['id_presupuesto'], $presupuestos)) {
					$presupuestos[] = $value['id_presupuesto'];
				}
			}
			$response = array('success' => true, 'error' => false, 'presupuestos' => $presupuestos, 'query' => $this->db->last_query());
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		} else {
			$response = array('success' => false, 'error' => true, 'msn' => 'No se han recibido datos');
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($response));
			return;
		}
	}

	function rechazar_presupuestos_vencidos($rand)
	{
		if ($rand == "5c8e15faec49a66870b949314ac062d0") {

			$this->db->where('fecha_validez <', date('Y-m-d'));
			$this->db->where('estado', 'Pendiente');
			$this->db->where('borrado', 0);
			$vencidos = $this->db->get('presupuestos')->result();

			foreach ($vencidos as $key => $value) {
				$parametros = [
					'id_presupuesto' =>  $value->id_presupuesto,
					'estado' => 'Rechazado',
					'estado_relacionado' => 'Rechazado CRON ->' . $value->estado_relacionado,
					'total_aceptado' => 0,
				];
				$id_presupuesto_actualizado = $this->Presupuestos_model->actualizar_estado_presupuesto($parametros);
			}
			echo "OK";
		} else {
			echo "Error";
		}

		exit;
	}


	public function json_getfinanciacion($id_dietario)
	{
		$rtval = [];
		if ($id_dietario <= 135968997/* && $id_dietario!=135967017*/) {
			$rtval['error'] = 'No se dispone de información de presupuestos financiados para este registro';
		} else {
			$rtval['id_dietario'] = $id_dietario;
			$presupuestos = $this->Presupuestos_model->getPresupuestosFinanciados($id_dietario);
			$rtval['result'] = $presupuestos;
		}
		echo json_encode($rtval);
	}
	
	function json_getnotas($id_presupuesto)
	{
		
		$this->db->where('id_presupuesto', $id_presupuesto);
		$notas = $this->db->get('presupuestos_notas')->result();
		
		if (empty($notas)){
			$result = array(
				'id_nota_presupuesto' => '',
				'id_presupuesto' => $id_presupuesto,
				'comentarios' => '',
				'estado' => 'pendiente'
			);
		}
		else {
			$result = array(
				'id_nota_presupuesto' => $notas[0]->id_nota_presupuesto,
				'id_presupuesto' => $notas[0]->id_presupuesto,
				'comentarios' => $notas[0]->comentarios,
				'estado' => $notas[0]->estado
			);
		}
		
		echo json_encode($result);
	}
	
	function json_savenotas(){
		
		$registro = array();
		foreach ( $this->input->post() as $key => $value ){
			$registro[$key] = $this->input->post($key);
		}
		
		$AqConexion_model = new AqConexion_model();
		
		if ( empty($registro['id_nota_presupuesto']) ){
			unset($registro['id_nota_presupuesto']);
			$AqConexion_model->insert('presupuestos_notas',$registro);

		}
		else {
			$where['id_nota_presupuesto']=$registro['id_nota_presupuesto'];
			$AqConexion_model->update('presupuestos_notas',$registro,$where);
		}
	}
	public function rechazar_cita_presupuesto_item()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		if ($this->session->userdata('id_perfil') <> 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes editar este item de presupuesto.');
			redirect('presupuestos');
		}
		$id_presupuesto_item = $this->input->post('id_presupuesto_item');
		$motivo = $this->input->post('motivo');
		if (!empty($id_presupuesto_item)) {
			$parametros['id_presupuesto_item'] = $id_presupuesto_item;
			$presupuesto_item = $this->Presupuestos_model->leer_presupuestos_items($parametros,'DESC');
			$id_presupuesto=$presupuesto_item[0]['id_presupuesto'];
            $nota=$this->Presupuestos_model->guardarNotaPresupuesto($id_presupuesto,$id_presupuesto_item,$motivo);
			$presupuesto_item = $this->Presupuestos_model->rechazarItemPresupuesto($id_presupuesto_item);
			$this->session->set_userdata('mensaje', 'El item #'.$id_presupuesto_item.' del presupuesto #'.$id_presupuesto. 'ha sido rechazado, por favor, revisar en notas de presupuesto el motivo del rechazo.');
            redirect('Presupuestos');
		} else {
			$this->session->set_userdata('mensaje', 'No se pudo rechazar el item #'.$id_presupuesto_item.' por favor intente nuevamente, si persiste el problema contacte al tecnico');
            redirect('Presupuestos');
		}
	}
	public function presupuesto_item_laboratorio_cero()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}

		if ($this->session->userdata('id_perfil') <> 0) {
			$this->session->set_userdata('msn_actionno', 'No puedes editar este item de presupuesto.');
			redirect('presupuestos');
		}
		$id_presupuesto_item = $this->input->post('id_presupuesto_item');
		$motivo = $this->input->post('motivo');
		if (!empty($id_presupuesto_item)) {
			$parametros['id_presupuesto_item'] = $id_presupuesto_item;
			$presupuesto_item = $this->Presupuestos_model->leer_presupuestos_items($parametros,'DESC');
			$id_presupuesto=$presupuesto_item[0]['id_presupuesto'];
            $nota=$this->Presupuestos_model->guardarNotaPresupuesto($id_presupuesto,$id_presupuesto_item,$motivo);
            $response = array('success' => true, 'error' => false);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($response));
        return;
		} else {
            $response = array('success' => false, 'error' => false);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($response));
		}
	}
	public function presupuesto_cargar_comentario()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$parametros=['id_presupuesto_item' => $this->input->post('id_presupuesto_item')];
        $presupuestos_items=$this->Presupuestos_model->leer_presupuestos_items($parametros);
        foreach ($presupuestos_items as $key => $value) {
        	$id_presupuesto=$value['id_presupuesto'];
        }
        $comentarios=$this->Presupuestos_model->cargarComentarioPresupuestoItem($id_presupuesto_item);
        if(empty($comentarios)){
        	$comentarios='Sin comentarios';
        }
        $response = array('success' => true, 'error' => false, 'comentarios'  => $comentarios,'id_presupuesto_item'=>$id_presupuesto_item);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
        return;
	}	
	public function get_diente()
	{
		// ... Comprobamos la sesion del usuario
		$ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
		if ($ok_ticket == 0) {
			header("Location: " . RUTA_WWW);
			exit;
		}
		$id_presupuesto_item=$this->input->post('id_presupuesto_item');
		$parametros=['id_presupuesto_item' => $id_presupuesto_item];
        $diente=$this->Presupuestos_model->getDiente($parametros);
        if(empty($diente)){
        	$diente='-';
        }
        $response = array('success' => true, 'error' => false, 'diente'  => $diente,'id_presupuesto_item'=>$id_presupuesto_item);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
        return;
	}		
}
