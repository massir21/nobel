<?php
class Tasks extends CI_Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function index() {

		header("Location: " . RUTA_WWW);
		exit;

	}

	public function tareas_diarias() {

		// ****************************************************************
		// Pamomoos a RECHAZADOS los prpesupuestsos en Borrador, que ya caducaron
		$this->load->model('Presupuestos_model');
		$this->Presupuestos_model->tasks_caducados_a_rechazados();
	}
}


