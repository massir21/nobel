<?php

class WhatsApp extends CI_Controller {


    public function index()
    {
        // ... Leemos todos los cupones de tienda existentes.
        // $data['cupones'] = $this->Cupones_model->get_cupones();
        // ... Viewer con el contenido
        $data['pagetitle'] = 'Estado de la conexion a Whatsapp';
        $data['content_view'] = $this->load->view('whatsapp/index_view', $data, true);
        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

            $this->load->view($this->config->item('template_dir') . '/master', $data);
 
    }


}
?>
