<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Tarifas extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... TARIFAS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion de la tarifa
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "Gestión de Tarifas";

        $data['registros'] = $this->Tarifas_model->leer_tarifas($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de tarifas';
        $data['actionstitle'][] = '<a href="'.base_url().'tarifas/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir Tarifa</a>';
        $data['content_view'] = $this->load->view('tarifas/tarifas_view', $data, true);

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

    function gestion($accion = null, $id_tarifa = null)
    {
        // ... Comprobamos la sesion de la tarifa
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        // ----------------------------------------------------------------------------- //
        // ... Nuevo Registro o Edici�n ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_tarifa'] = $id_tarifa;
                $data['registros'] = $this->Tarifas_model->leer_tarifas($param);
                if($accion == "editar") {
                    $data['servicios'] = $this->Tarifas_model->leer_servicios($id_tarifa);
                    $param = [];
                    $data['familias'] = $this->Servicios_model->leer_familias_servicios($param);
                }
            }

            unset($param);
            $param['vacio'] = "";

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nueva tarifa' : 'Editar tarifa';
            $data['content_view'] = $this->load->view('tarifas/tarifas_nuevoeditar_view', $data, true);

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

        // ----------------------------------------------------------------------------- //
        // ... Guardar o Actualizar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "guardar" || $accion == "actualizar") {
            if (!empty($_POST)) {
                $parametros = $_POST;
            }
            if ($accion == "guardar") {
                $id_tarifa_creada = $this->Tarifas_model->nueva_tarifa($parametros);
                $data['estado'] = $id_tarifa_creada;
            } else {
                $parametros['id_tarifa'] = $id_tarifa;
                $editing=isset($_POST['editprecios']) && $_POST['editprecios'] ? true : false;
                if(!$editing) {
                    $data['estado'] = $this->Tarifas_model->actualizar_tarifa($parametros);
                }
                else{
                    // Editar precios
                    $data['pricesedited']=$this->Tarifas_model->actualizar_precios($id_tarifa,$_POST['prices']);
                }
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_tarifa'] = $id_tarifa;
            $data['borrado'] = $this->Tarifas_model->borrar_tarifa($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ...
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {

            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Tarifas_model->leer_tarifas($parametros);



            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('tarifas/tarifas_view', $data, true);

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
