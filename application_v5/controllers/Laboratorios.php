<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

class Laboratorios extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        $data['registros'] = $this->Laboratorios_model->getListadoLaboratorios();
        $data['pagetitle'] = 'Gestión de laboratorios';
        $data['actionstitle'][] = '<a href="' . base_url() . 'laboratorios/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir laboratorio</a>';

        $data['content_view'] = $this->load->view('laboratorios/laboratorios_view', $data, true);


        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }
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