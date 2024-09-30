<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

class Citasextra extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function archivarparrilla($id_cita,$toarchive){
        $this->layout='';
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            echo 'ERROR';
            exit;
        }

        $this->load->model('Agenda_model');
        $cita=$this->Agenda_model->leer_citas(array('id_cita'=>$id_cita));
        if($cita>0){
            $lacita=$cita[0];
            $this->Agenda_model->modificar_cita_simple(['id_cita'=>$id_cita,'archivado_parrilla'=>$toarchive]);
        }

        echo json_encode(
            array(
                'result'=>'ok'
            )
        );
        die();
    }

    function observaciones($id_cita){
        $this->layout='';

        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            echo 'ERROR';
            exit;
        }
        $this->load->model('Observacionescita_model');

        $data['registros'] = $this->Observacionescita_model->getObservacionesCita($id_cita);
        $data['id_cita'] = $id_cita;

        // ... Viewer con el contenido
        echo /*$data['content_view'] =*/ $this->load->view('citasextra/observaciones_view.php', $data, true);


    }

    function borrar_observacion($id_observacion){
        $params=[
          'id_observacion'=>$id_observacion,
          'borrado'=>1
        ];
        $this->load->model('Observacionescita_model');
        $this->Observacionescita_model->guardar_observacion($params);
        echo json_encode(
            array(
                'result'=>'ok'
            )
        );
        die();
    }

    function guardar_observacion($id_cita){
        $parametros=[
            'id_cita'=>$id_cita,
            'observacion'=>isset($_POST['observacion']) ? $_POST['observacion'] : '' ,
        ];
        if(isset($_POST['idobservacion'])){
            $parametros['id_observacion']=$_POST['idobservacion'];
        }
        $this->load->model('Observacionescita_model');

        $this->Observacionescita_model->guardar_observacion($parametros);

        echo json_encode(
            array(
                'result'=>'ok'
            )
        );
        die();
    }


    function gestion($accion = null, $id_laboratorio = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        $paramFiltroLaboratorio = [];
        if ($accion === 'nuevo' || $accion === 'editar') {
            if ($accion === 'editar') {
                $paramFiltroLaboratorio['id_laboratorio'] = $id_laboratorio;
                $data['registros'] = $this->Laboratorios_model->getListadoLaboratorios($paramFiltroLaboratorio);

            }
            unset($param);

            $param['vacio'] = "";


            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo laboratorio' : 'Editar laboratorio';
            $data['content_view'] = $this->load->view('laboratorios/laboratorios_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

        if ($accion === 'guardar' || $accion === 'actualizar') {
            if (!empty($_POST)) {
                $parametros = $_POST;
            }

            if ($accion == "guardar") {
                $data['estado'] = $this->Laboratorios_model->nuevo_laboratorio($parametros);
            } else {
                $parametros['id_laboratorio'] = $id_laboratorio;
                $data['estado'] = $this->Laboratorios_model->actualizar_laboratorio($parametros);
            }
        }

        if ($accion === 'borrar') {
            $parametros['id_laboratorio'] = $id_laboratorio;
            $data['borrado'] = $this->Laboratorios_model->borrar_laboratorio($parametros);
        }

        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Laboratorios_model->getListadoLaboratorios($parametros);

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('laboratorios/laboratorios_view.php', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }
        }

    }


}