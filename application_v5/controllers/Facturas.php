<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

class Facturas extends CI_Controller
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

        $parametrosProveedores = ['obsoleto' => false];
        $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);

        $data['proveedor'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
        $data['doctores'] = $this->Proveedores_model->getListadoDoctores();
        $data['registros'] = $this->GestionFacturas_model->getListadoGestionFacturas();
        $data['pagetitle'] = 'Gestión de facturas';
        $data['actionstitle'][] = '<a href="' . base_url() . 'facturas/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir factura</a>';

        $data['content_view'] = $this->load->view('gestion/facturas/facturas_view', $data, true);


        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        $this->load->view($this->config->item('template_dir') . '/master', $data);
        /*if ($permiso) {
            $this->load->view($this->config->item('template_dir') . '/master', $data);
        } else {
            header("Location: " . RUTA_WWW . "/errores/error_404.html");
            exit;
        }*/
    }

    function gestion($accion = null, $id_factura = null)
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        // ... Recogemos la accion a realizar
        $data['accion'] = $accion;
        $data['doctores'] = $this->Proveedores_model->getListadoDoctores();
        if ($accion === 'nuevo' || $accion === 'editar') {
            if ($accion === 'editar') {
                $param['id_gestion_facturas'] = $id_factura;
                $data['registros'] = $this->GestionFacturas_model->getListadoGestionFacturas($param);
            }
            unset($param);
            $param['vacio'] = "";

            $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
            $parametrosProveedores = ['obsoleto' => false];
            $data['proveedores'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
            
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nueva factura' : 'Editar factura';
            $data['content_view'] = $this->load->view('gestion/facturas/facturas_nuevoeditar_view', $data, true);

            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $this->load->view($this->config->item('template_dir') . '/master', $data);
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            /*if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }*/
        }

        if ($accion == "guardar" || $accion == "actualizar") {
            if (!empty($_POST)) {
                $parametros = $_POST;
            }
            if (!empty($_FILES)) {
                $file = $_FILES;
            }
            if ($accion == "guardar") {
                $id_servicio_creado = $this->GestionFacturas_model->nuevo_gestion_facturas($parametros, $file);
                $data['estado'] = $id_servicio_creado;
                // CHAINS 20240822 - Si viene de movimientos hay que crear el movimiento
                if(isset($parametros["crear_movimiento"]) && $parametros["crear_movimiento"]){
                    $parametros['cantidad']=-1*$parametros['total_factura'];
                    $this->Caja_model->nuevo_movimiento_caja($parametros);
                    redirect("/caja/movimientos");
                }

            } else {
                $parametros['id_gestion_facturas'] = $id_factura;
                $data['estado'] = $this->GestionFacturas_model->actualizar_gestion_facturas($parametros, $file);
            }
        }

        if ($accion == "borrar") {
            $parametros['id_gestion_facturas'] = $id_factura;
            $data['borrado'] = $this->GestionFacturas_model->borrar_facturas($parametros);
        }

        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
            $parametrosProveedores = ['obsoleto' => false];
            $data['proveedor'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
            
            $data['registros'] = $this->GestionFacturas_model->getListadoGestionFacturas();
            // ... Viewer con el contenido
            $data['pagetitle'] = 'Gestión de facturas';
            $data['actionstitle'][] = '<a href="' . base_url() . 'facturas/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir factura</a>';

            $data['content_view'] = $this->load->view('gestion/facturas/facturas_view', $data, true);
            // ... Modulos del usuario
            $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
            $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

            // ... Pagina master
            $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
            $this->load->view($this->config->item('template_dir') . '/master', $data);
            /*if ($permiso) {
                $this->load->view($this->config->item('template_dir') . '/master', $data);
            } else {
                header("Location: " . RUTA_WWW . "/errores/error_404.html");
                exit;
            }*/
        }
    }

    function getListadoFiltradoGestionFacturas()
    {

        $datos=[];

        $aFiltrado = [];

        if (isset($_POST['fechaFacturaDesde']) && !empty($_POST['fechaFacturaDesde'])) {
            $aFiltrado['fecha_factura_desde'] = $_POST['fechaFacturaDesde'];
        }
        if (isset($_POST['fechaFacturaHasta']) && !empty($_POST['fechaFacturaHasta'])) {
            $aFiltrado['fecha_factura_hasta'] = $_POST['fechaFacturaHasta'];
        }

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['proveedorId']) && !empty($_POST['proveedorId'])) {
            $aFiltrado['id_proveedor'] = $_POST['proveedorId'];
        }

        $datos['registros'] = $this->GestionFacturas_model->getListadoGestionFacturas($aFiltrado);
        $datos['doctores'] = $this->Proveedores_model->getListadoDoctores();
        $this->load->view('gestion/facturas/componentes/listado_detalle_factura', $datos);
    }

    function check_gestion_factura($id_factura,$check){
        $this->GestionFacturas_model->cambio_check_factura($id_factura,$check);
    }

}