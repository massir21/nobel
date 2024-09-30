<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

class Proveedores extends CI_Controller
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
        $data['registros'] = $this->Proveedores_model->getListadoProveedores();
        $data['registros_tipos'] = $this->TiposProveedores_model->getListadoTiposProveedores();
        $data['pagetitle'] = 'Gestión de proveedores';
        $data['actionstitle'][] = '<a href="' . base_url() . 'proveedores/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir proveedor</a>';

        $data['content_view'] = $this->load->view('proveedores/proveedores_view', $data, true);


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

    function gestion($accion = null, $id_proveedor = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        $paramFiltroProveedor = [];
        if ($accion === 'nuevo' || $accion === 'editar') {
            if ($accion === 'editar') {
                $paramFiltroProveedor['id_proveedor'] = $id_proveedor;
                $data['registros'] = $this->Proveedores_model->getListadoProveedores($paramFiltroProveedor);

            }
            unset($param);

            $param['vacio'] = "";
            $data['tipos_proveedores'] = $this->TiposProveedores_model->getListadoTiposProveedores();


            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo proveedor' : 'Editar proveedor';
            $data['content_view'] = $this->load->view('proveedores/proveedores_nuevoeditar_view', $data, true);

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
                $data['estado'] = $this->Proveedores_model->nuevo_proveedor($parametros);
            } else {
                $parametros['id_proveedor'] = $id_proveedor;
                $data['estado'] = $this->Proveedores_model->actualizar_proveedor($parametros);
            }
        }

        if ($accion === 'borrar') {
            $parametros['id_proveedor'] = $id_proveedor;
            $data['borrado'] = $this->Proveedores_model->borrar_proveedor($parametros);
        }

        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros_tipos'] = $this->TiposProveedores_model->getListadoTiposProveedores($parametros);
            $data['registros'] = $this->Proveedores_model->getListadoProveedores($parametros);

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('proveedores/proveedores_view.php', $data, true);

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

    function tipos($accion = null, $id_tipo = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;

        if ($accion == "nuevo" || $accion == "editar") {
            if ($accion == "editar") {
                $param['id_tipo'] = $id_tipo;
                $data['registros'] = $this->TiposProveedores_model->getListadoTiposProveedores($param);
            }


            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo tipo' : 'Editar tipo';
            $data['content_view'] = $this->load->view('proveedores/proveedores_tipo_nuevoeditar_view', $data, true);

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
                $data['estado'] = $this->TiposProveedores_model->nuevo_tipo($parametros);
            } else {
                $parametros['id_tipo'] = $id_tipo;
                $data['estado'] = $this->TiposProveedores_model->actualizar_tipo($parametros);
            }
        }

        if ($accion === 'borrar') {
            $parametros['id_tipo'] = $id_tipo;
            $data['borrado'] = $this->TiposProveedores_model->borrar_tipo($parametros);
        }


        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros_tipos'] = $this->TiposProveedores_model->getListadoTiposProveedores($parametros);
            $data['registros'] = $this->Proveedores_model->getListadoProveedores($parametros);

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('proveedores/proveedores_view.php', $data, true);

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