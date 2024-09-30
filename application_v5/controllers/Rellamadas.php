<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Rellamadas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
		$this->load->model('rellamadas_model');
    }

	public function addcolumns(){
		/*$q = "ALTER TABLE `servicios` ADD `rellamada` INT(3) NOT NULL DEFAULT '0' COMMENT 'rellamada a los X desde la cita finalizada' AFTER `notas`;";
		$this->db->query($q);

		$q = "ALTER TABLE `servicios_familias` ADD `rellamada` INT(3) NOT NULL DEFAULT '0' COMMENT 'rellamada a los X desde la cita finalizada' AFTER `citas_online`;";
		$this->db->query($q);

		$q = "CREATE TABLE `rellamadas` (
			`id_rellamada` INT(11) NOT NULL AUTO_INCREMENT ,
			`id_cliente` INT(11) NOT NULL ,
			`id_cita` INT(11) NOT NULL ,
			`fecha_cita` DATE NOT NULL ,
			`id_usuario_cita` INT(11) NOT NULL ,
			`id_centro` INT(3) NOT NULL,
			`id_servicio` INT(11) NOT NULL ,
			`rellamada_servicio` INT(11) NOT NULL ,
			`id_familia_servicio` INT(11) NOT NULL ,
			`rellamada_familia` INT(11) NOT NULL ,
			`rellamada_final` INT(11) NOT NULL ,
			`fecha_rellamada` DATE NOT NULL ,
			`estado` ENUM('anulada','pendiente','realizada') NOT NULL ,
			`comentarios` VARCHAR(1000) NOT NULL ,
			`id_usuario_realizada` INT(11) NOT NULL ,
			`id_usuario_creacion` INT(11) NOT NULL ,
			`fecha_creacion` TIMESTAMP NOT NULL ,
			`id_usuario_modificacion` INT(11) NOT NULL ,
			`fecha_modificacion` TIMESTAMP NOT NULL ,
			`borrado` INT(1) NOT NULL ,
			`id_usuario_borrado` INT(11) NOT NULL ,
			`fecha_borrado` TIMESTAMP NOT NULL ,
			PRIMARY KEY (`id_rellamada`)
		) ENGINE = InnoDB;";
		$this->db->query($q);
		$this->db->query("ALTER TABLE `rellamadas` ADD UNIQUE(`id_cita`);");

		$this->db->query("INSERT INTO `modulos` (`id_modulo`, `nombre_modulo`, `url`, `padre`, `orden`, `orden_item`, `id_usuario_creacion`, `fecha_creacion`, `id_usuario_modificacion`, `fecha_modificacion`, `borrado`, `id_usuario_borrado`, `fecha_borrado`, `debug`) VALUES ('87', 'Rellamadas', 'Rellamadas', 'Agenda', '1', '8', NULL, NULL, NULL, NULL, '0', NULL, NULL, '0');");*/

		$this->db->query("ALTER TABLE `rellamadas` DROP INDEX `id_cita`;");
		$this->db->query("ALTER TABLE `rellamadas` ADD `parent` INT(11) NOT NULL DEFAULT '0' AFTER `comentarios`, ADD `children` INT(11) NOT NULL DEFAULT '0' AFTER `parent`;");
	}

    // ----------------------------------------------------------------------------- //
    // ... RELLAMADAS
    // ----------------------------------------------------------------------------- //


	// CRON EJECUTAR PASADAS ALS 00:00//
	function cron($rand){
		if ($rand == "5c8e15faec49a66870b949314ac062d0")
        {
			$date = date('Y-m-d', strtotime('- 1 day'));
			$parametros['fecha'] = $date;
			$rellamadas = $this->rellamadas_model->registrar_rellamadas_dia($parametros);
			//printr($rellamadas);
			foreach ($rellamadas as $key => $rellamada) {
				if($rellamada['rellamada_final'] > 0){
					$this->rellamadas_model->nueva_rellamada($rellamada);
				}
			}
		}
	}

	function crear_rellamadas($date = ''){
		if($date == ''){
			$date = date('Y-m-d', strtotime('- 1 day'));
		}
		$parametros['fecha'] = $date;
		$rellamadas = $this->rellamadas_model->registrar_rellamadas_dia($parametros);
		//printr($rellamadas);
		foreach ($rellamadas as $key => $rellamada) {
			if($rellamada['rellamada_final'] > 0){
				$this->rellamadas_model->nueva_rellamada($rellamada);
			}
		}
	}

    function index()
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "Rellamadas";
        
		if ($this->session->userdata('id_perfil') == 0) {
			unset($param2);
			$param2['vacio'] = "";
			$data['centros_todos'] = $this->Usuarios_model->leer_centros($param2);
		}

		if ($this->session->userdata('id_perfil') > 0) {
            $parametros['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        //$data['empleados'] = $this->Usuarios_model->leer_usuarios($parametros);

		unset($param);
        $param['obsoleto'] = 0;
        $data['servicios'] = $this->Servicios_model->leer_servicios($param);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Rellamadas';
        $data['actionstitle'][] = '<button type="button" class="btn btn-primary text-inverse-primary" id="nueva_rellamada">Nueva rellamada</button>';
        $data['content_view'] = $this->load->view('agenda/rellamadas_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 87);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
    }

	function get_rellamadas($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			'rellamadas.fecha_rellamada',
			'(CONCAT(clientes.nombre, " ", clientes.apellidos)) AS cliente',
			'clientes.telefono',
			'rellamadas.fecha_cita',
			'servicios.nombre_servicio',
			'servicios_familias.nombre_familia',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
			'centros.nombre_centro',
			'rellamadas.estado',
			'rellamadas.*',
		];
		$tabla = 'rellamadas';
		$join = [
			'servicios' => 'rellamadas.id_servicio = servicios.id_servicio',
			'servicios_familias' => 'rellamadas.id_familia_servicio = servicios_familias.id_familia_servicio',
			'clientes' => 'rellamadas.id_cliente = clientes.id_cliente',
			'usuarios' => 'rellamadas.id_usuario_cita = usuarios.id_usuario',
			'centros' => 'rellamadas.id_centro = centros.id_centro',
		];
		$add_rule = [];
		$where = ['rellamadas.borrado' => 0];

		if ($this->input->get('id_cliente') != '') {
			$where['rellamadas.id_cliente'] = $this->input->get('id_cliente');
		}
		/*if ($this->input->get('id_usuario_empleado') != '') {
			$where['rellamadas.id_usuario_creacion'] = $this->input->get('id_usuario_empleado');
		}*/
		if ($this->input->get('id_centro') != '') {
			$where['rellamadas.id_centro'] = $this->input->get('id_centro');
		}
		if ($this->input->get('estado') != '') {
			$where['rellamadas.estado'] = $this->input->get('estado');
		}
		if ($this->input->get('fecha_rellamada') == '') {
			if ($this->input->get('fecha_desde') != '') {
				$where['rellamadas.fecha_rellamada >='] = $this->input->get('fecha_desde');
			}
			if ($this->input->get('fecha_hasta') != '') {
				$where['rellamadas.fecha_rellamada <='] = $this->input->get('fecha_hasta');
			}
		}else{
			$where['rellamadas.fecha_rellamada'] = $this->input->get('fecha_rellamada');
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

	function get_rellamadas_cliente($table = null, $columna = null, $valor = null)
	{
		$this->load->library('Datatable');
		$campos = [
			'rellamadas.fecha_rellamada',
			'rellamadas.fecha_cita',
			'servicios.nombre_servicio',
			'servicios_familias.nombre_familia',
			'(CONCAT(usuarios.nombre, " ", usuarios.apellidos)) AS empleado',
			'centros.nombre_centro',
			'rellamadas.estado',
			'rellamadas.*',
		];
		$tabla = 'rellamadas';
		$join = [
			'servicios' => 'rellamadas.id_servicio = servicios.id_servicio',
			'servicios_familias' => 'rellamadas.id_familia_servicio = servicios_familias.id_familia_servicio',
			'usuarios' => 'rellamadas.id_usuario_cita = usuarios.id_usuario',
			'centros' => 'rellamadas.id_centro = centros.id_centro',
		];
		$add_rule = [];
		$where = ['rellamadas.borrado' => 0];

		if ($this->input->get('id_cliente') != '') {
			$where['rellamadas.id_cliente'] = $this->input->get('id_cliente');
		}
		if ($this->input->get('id_usuario_empleado') != '') {
			$where['rellamadas.id_usuario_empleado'] = $this->input->get('id_usuario_empleado');
		}
		if ($this->input->get('id_centro') != '') {
			$where['rellamadas.id_centro'] = $this->input->get('id_centro');
		}
		if ($this->input->get('estado') != '') {
			$where['rellamadas.estado'] = $this->input->get('estado');
		}
		if ($this->input->get('fecha_rellamada') == '') {
			if ($this->input->get('fecha_desde') != '') {
				$where['rellamadas.fecha_rellamada >='] = $this->input->get('fecha_desde');
			}
			if ($this->input->get('fecha_hasta') != '') {
				$where['rellamadas.fecha_rellamada <='] = $this->input->get('fecha_hasta');
			}
		}else{
			$where['rellamadas.fecha_rellamada'] = $this->input->get('fecha_rellamada');
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

	function crearRellamada()
	{
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros = $_POST;
        $result = $this->rellamadas_model->crear_rellamada($parametros);
        $response = ['success' => ($result == 1) ? true : false];
        echo json_encode($response);
	}

	function actualizarRellamada()
	{
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros = $_POST;
        $result = $this->rellamadas_model->actualizar_rellamada($parametros);
        $response = ['success' => ($result == 1) ? true : false];
        echo json_encode($response);
	}

	function crearRellamadaVinculada()
	{
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $parametros = $_POST;
        $result = $this->rellamadas_model->crear_rellamada_vinculada($parametros);
        $response = ['success' => ($result == 1) ? true : false];
        echo json_encode($response);
	}

}
