<?php
class Site extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // --------------------------------------------------------------------------
    // ... Pagina principal del usuario. (Panel de control)
    // --------------------------------------------------------------------------
    function index()
    {
        // ... Comprobamos la sesion del usuario
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
        // usuario
        /* $param = [];
        $param['id_usuario'] =  $this->session->userdata('id_usuario');
        $data['usuario'] = $this->Usuarios_model->leer_usuarios($param);*/

        // tareas diarias y pendientes pasadas asignadas
        $param = [];
        $param['id_usuario'] = $this->session->userdata('id_usuario');
        $param['fecha'] = date('Y-m-d');
        $data['tareas_diarias_asignadas'] = $this->Site_model->leer_tareas_diarias($param);

        // tareas diarias y pendientes pasadas propias
        $param = [];
        $param['fecha'] = date('Y-m-d');
        $param['id_creador'] = $this->session->userdata('id_usuario');
        $data['tareas_diarias_propias'] = $this->Site_model->leer_tareas_diarias($param);

        // rellamdas diarias y pendientes pasadas
        $param = [];
        $param['id_centro'] = ($this->session->userdata('id_perfil') == 0) ? "" : $this->session->userdata('id_centro_usuario');
        $param['fecha'] = date('Y-m-d');
        $data['rellamadas_diarias_pendientes'] = $this->Site_model->leer_rellamadas_diarias($param);

        // rellamdas diarias y pendientes pasadas del usuario
        $param = [];
        $param['fecha'] = date('Y-m-d');
        $param['id_usuario'] = $this->session->userdata('id_usuario');
        $data['rellamadas_diarias_pendientes_propias'] = $this->Site_model->leer_rellamadas_diarias($param);

        // primeras visitas del dia, id_servicio 15404
        $param = [];
        $param['id_servicio'] = 15404;
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $param['fecha'] = date('Y-m-d');
        $data['primeras_visitas_hoy'] = $this->Site_model->leer_primeras_visitas($param);

        // primeras visitas de mañana, id_servicio 15404
        $param = [];
        $param['id_servicio'] = 15404;
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $param['fecha'] = date('Y-m-d', strtotime('+ 1 day'));
        $data['primeras_visitas_manana'] = $this->Site_model->leer_primeras_visitas($param);

        // primeras visitas del mes, id_servicio 15404
        $param = [];
        $param['id_servicio'] = 15404;
        if ($this->session->userdata('id_perfil') > 0) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        }
        $param['mes'] = date('Y-m');
        $data['primeras_visitas_mes'] = $this->Site_model->leer_primeras_visitas($param);;

        // Pacientes sin historia clinica
        $param = [];
        if ($this->session->userdata('id_perfil') == 6) {
            $param['id_doctor'] = $this->session->userdata('id_usuario');
        } elseif ($this->session->userdata('id_perfil') == 1 || $this->session->userdata('id_perfil') == 2 || $this->session->userdata('id_perfil') == 3) {
            $param['id_centro'] = $this->session->userdata('id_centro_usuario');
        } elseif ($this->session->userdata('id_perfil') == 0) {
            $param = [];
        } else {
            $param['id_centro'] = -1;
        }
        $data['citaspendientesnota'] = $this->Agenda_model->get_citas_sin_nota_por_fecha($param);
        
        //Pacientes con servicios pendientes o citas anuladas no reasignadas
        $this->load->model('Presupuestos_model');
        if ($this->session->userdata('id_perfil') == 0){
            $this->load->model('Presupuestos_model');
            $data['presupuestosServiciosPendientes'] = $this->Presupuestos_model->getPresupuestosCitasPendientes();
            $data['citasAnuladasSinReagendar'] = $this->Presupuestos_model->citasAnuladasSinReagendar();
            $data['presupuestosPendientes'] = $this->Presupuestos_model->getPresupuestosPendientes();
        }
        else {
            $data['presupuestosServiciosPendientes'] = $this->Presupuestos_model->getPresupuestosCitasPendientes($this->session->userdata('id_centro_usuario'));         
            $data['citasAnuladasSinReagendar'] = $this->Presupuestos_model->citasAnuladasSinReagendar($this->session->userdata('id_centro_usuario'));         
            $data['presupuestosPendientes'] = $this->Presupuestos_model->getPresupuestosPendientes($this->session->userdata('id_centro_usuario'));
        }

        
        if ($this->session->userdata('id_perfil') == 0 || $this->session->userdata('id_perfil') == 3) {
            // gastos mensuales
            $param = [];
            $param['mes'] = date('m');
            if ($this->session->userdata('id_perfil') != 0) {
                $param['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $data['gasto_mes'] = $this->Site_model->leer_gasto_mes($param);
            if ($this->session->userdata('id_perfil') == 0) {
                $param = [];
                $data['centros_todos'] = $this->Intercentros_model->leer_centros_nombre([]);
            }
            if (isset($data['centros_todos'])) {
                foreach ($data['centros_todos'] as $key => $value) {
                    $param = [];
                    $param['mes'] = date('m');
                    $param['id_centro'] = $value['id_centro'];
                    $data['centros_todos'][$key]['gastos_mes'] = $this->Site_model->leer_gasto_mes($param);
                }
            }
            // ingresos mensuales
            $param = [];
            $param['mes'] = date('m');
            if ($this->session->userdata('id_perfil') != 0) {
                $param['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $data['ingresos_mes'] = $this->Site_model->leer_ingresos_mes($param);
            if (isset($data['centros_todos'])) {
                foreach ($data['centros_todos'] as $key => $value) {
                    $param = [];
                    $param['mes'] = date('m');
                    $param['id_centro'] = $value['id_centro'];
                    $data['centros_todos'][$key]['ingresos_mes'] = $this->Site_model->leer_ingresos_mes($param);
                }
            }
            // rentabilidad
            $param = [];
            if ($this->session->userdata('id_perfil') != 0) {
                $param['id_centro'] = $this->session->userdata('id_centro_usuario');
            }
            $param['mes'] = date('m');
            $param['year'] = date('Y');
            $objetivo_mes = $this->Site_model->leer_objetivos_mes($param);
            $data['facturacion_mes'] = $objetivo_mes->facturacion;
            $data['rentabilidad_mes'] = $objetivo_mes->rentabilidad;
            $data['rentabilidad_euros_mes'] = $objetivo_mes->rentabilidad_euros;
            if (isset($data['centros_todos'])) {
                foreach ($data['centros_todos'] as $key => $value) {
                    $param = [];
                    $param['mes'] = date('m');
                    $param['year'] = date('Y');
                    $param['id_centro'] = $value['id_centro'];
                    $objetivo_mes = $this->Site_model->leer_objetivos_mes($param);
                    $data['centros_todos'][$key]['facturacion_mes'] = $objetivo_mes->facturacion;
                    $data['centros_todos'][$key]['rentabilidad_mes'] = $objetivo_mes->rentabilidad;
                    $data['centros_todos'][$key]['rentabilidad_euros_mes'] = $objetivo_mes->rentabilidad_euros;
                }
            }
        }

        $data['pagetitle'] = fechaES(date('Y-m-d'), 1);
        $data['content_view'] = $this->load->view('site/panelcontroladm_view', $data, true);

        // ... Modulos del usuario
        $param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);

        // ... Pagina master    
        $this->load->view($this->config->item('template_dir') . '/master', $data);
    }

    function confirmar_cita()
    {

        $parametros = $_POST;
        $param = [];
        $param['id_usuario'] = $this->session->userdata('id_usuario');
        $param['post'] = $parametros;

        $result = $this->Site_model->confirmar_cita($param);
        
        $response = ['success' => ($result > 0) ? true : false];
        echo json_encode($response);
        
       // echo $result;
    }
}
