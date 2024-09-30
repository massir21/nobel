<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

class Balance extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Balance_model');
        $this->load->model('GestionFacturas_model');
        $this->load->model('Proveedores_model');
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
        $data['id_centro']="0";
        $data['mes']=date('m');
        $data['ano']=date('Y');
        if(isset($_POST['id_centro'])){
            $data['id_centro']=$_POST['id_centro'];
            $data['mes']=$_POST['mes'];
            $data['ano']=$_POST['ano'];
        }
        $parametros = $data;
        $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
        //los años a consultar seran el actual y 4 años anteriores 5 años en total
        $data['todos_anios']=Array();
        for($i=0;$i<5;$i++){ $data['todos_anios'][]=date('Y')-$i;}
        $data['todos_meses']=[1,2,3,4,5,6,7,8,9,10,11,12];
        

        $data['proveedor'] = $this->Proveedores_model->getListadoProveedores($parametrosProveedores);
        $data['doctores'] = $this->Proveedores_model->getListadoDoctores();
        $data['pagetitle'] = 'Gestión de balances';

        $data['balance_gasto_centro'] = $this->Balance_model->leer_balance_gastos($parametros);
        $data['balance_gasto_consolidado']   = $this->Balance_model->leer_consolidado_gastos($parametros);
        $data['gastos_familias'] = $this->GestionFacturas_model->getListadoGestionFacturasFamilias($data['id_centro'], $data['mes'], $data['ano']);

        
        $data['balance_ingreso_centro'] = $this->Balance_model->leer_balance_ingresos_dietario($parametros);
        $data['balance_ingreso_consolidado']   = $this->Balance_model->leer_consolidado_ingresos_dietario($parametros);


        $data['balance_ganancias_centro'] = $this->Balance_model->leer_balance_ganancias_dietario($parametros);
        $data['balance_ganancias_consolidado'] = $this->Balance_model->leer_consolidado_ganancias_dietario($parametros);

        $data['data_grafica_consolidado'] = $this->Balance_model->consolidado_grafica($parametros);

        $data['content_view'] = $this->load->view('gestion/balance/balance_view', $data, true);
        
        

        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        $permiso = $this->Acceso_model->TienePermiso($data['modulos'], 4);
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }


    //
    function getBalanceGastos()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_consolidado_gastos($aFiltrado);


        $this->load->view('gestion/balance/componentes/card_monto_gastos', $datos);

    }//fin de la funcion getMontosBalance


    function getGridGastos()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_balance_gastos($aFiltrado);


        $this->load->view('gestion/balance/componentes/listado_detalle_gastos', $datos);

    }//fin de la funcion getGridGastos



    function getBalanceIngresos()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_consolidado_ingresos($aFiltrado);


        $this->load->view('gestion/balance/componentes/card_monto_ingresos', $datos);

    }//fin de la funcion getMontosBalance


    function getGridIngresos()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_balance_ingresos($aFiltrado);


        $this->load->view('gestion/balance/componentes/listado_detalle_ingresos', $datos);

    }//fin de la funcion getGridGastos


    function getBalanceGanancias()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_consolidado_ganancias($aFiltrado);


        $this->load->view('gestion/balance/componentes/card_monto_ganancia', $datos);

    }//fin de la funcion getMontosBalance


    function getGridGanancias()
    {
        $aFiltrado = [];

        if (isset($_POST['centroId']) && !empty($_POST['centroId'])) {
            $aFiltrado['id_centro'] = $_POST['centroId'];
        }

        if (isset($_POST['anio']) && !empty($_POST['anio'])) {
            $aFiltrado['anio'] = $_POST['anio'];
        }

        if (isset($_POST['mes']) && !empty($_POST['mes'])) {
            $aFiltrado['mes'] = $_POST['mes'];
        }


        $datos['registros'] = $this->Balance_model->leer_balance_ganancias($aFiltrado);


        $this->load->view('gestion/balance/componentes/listado_detalle_ganancias', $datos);

    }//fin de la funcion getGridGastoss
}