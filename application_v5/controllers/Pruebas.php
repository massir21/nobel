<?php class Pruebas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Liquidaciones_model');
    }

	public function simular_cita_liquidacion($id_cita)
	{
		$this->Liquidaciones_model->liquidacion_cita($id_cita);
	}

}