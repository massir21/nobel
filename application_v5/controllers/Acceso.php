<?php

class Acceso extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$data['error'] = 0;
		$this->session->unset_userdata('ticket');
		$this->session->unset_userdata('id_usuario');
		// ... Pagina master login
		$this->load->view($this->config->item('template_dir') . '/master_login', $data);
	}
	// --------------------------------------------------------------------------
	// ... Control de acceso de los usuarios
	// --------------------------------------------------------------------------
	function validar()
	{
		if (isset($_POST['usuario'])) {
			$parametros['usuario'] = $_POST['usuario'];
			$parametros['password'] = $_POST['password'];
		} else {
			$parametros['usuario'] = "";
			$parametros['password'] = "";
		}
		// ... Validamos el usuario
		$usuario = $this->Acceso_model->ValidarUsuario($parametros);
		
		if ($usuario != 0 && $usuario[0]['id_usuario'] > 0) {
			// ... Guardamos en la sesion en ticket generado
			$ticket = $this->Ticket_model->generar_ticket($usuario[0]['id_usuario']);
			
			$this->session->set_userdata("ticket", $ticket);
			$this->session->set_userdata("id_usuario", $usuario[0]['id_usuario']);
			$this->session->set_userdata("id_perfil", $usuario[0]['id_perfil']);
			$this->session->set_userdata("id_centro_usuario", $usuario[0]['id_centro']);
			$this->session->set_userdata("nombre_usuario", $usuario[0]['nombre']);
			$this->session->set_userdata("oportunidad_cuadre", 0);
			// ... Guardamos el acceso realizado.
			unset($parametros);
			$parametros['id_usuario'] = $usuario[0]['id_usuario'];
			$parametros['fecha_inicio'] = date("Y-m-d H:i:s");
			$parametros['fecha_fin'] = date("Y-m-d H:i:s");
			$parametros['ip'] = $_SERVER['REMOTE_ADDR'];
			$parametros['ultima_url'] = $_SERVER['HTTP_REFERER'];
			$parametros['ticket'] = $ticket;
			$parametros['fecha_creacion'] = date("Y-m-d H:i:s");
			
			$usuario = $this->Acceso_model->NuevoAcceso($parametros);
			
			if ($this->session->userdata('id_perfil') == PERFIL_DOCTOR) {
				header("Location: " . RUTA_WWW . "/agenda");
			} 
			else 
			if ($this->session->userdata('id_perfil') == PERFIL_GESTORIA_LIMITADA) {
				header("Location: " . RUTA_WWW . "/facturas");
			} else {
				header("Location: " . RUTA_WWW . "/site");
			}
			exit;
		} else {
			// ... Viewer con el contenido
			$data['error'] = 1;
			// ... Pagina master
			$this->load->view($this->config->item('template_dir') . '/master_login', $data);
		}
	}
	// --------------------------------------------------------------------------
	// ... Desconectar
	// --------------------------------------------------------------------------
	function desconectar()
	{
		$parametros['id_usuario'] = $this->session->userdata('id_usuario');
		$this->Ticket_model->borrar_ticket($parametros);
		$this->session->unset_userdata('ticket');
		$this->session->unset_userdata('id_usuario');
		header("Location: " . RUTA_WWW . "/acceso");
	}
}
