<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Servicios extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    // ----------------------------------------------------------------------------- //
    // ... SERVICIOS
    // ----------------------------------------------------------------------------- //
    function index()
    {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $data['vacio'] = "";

        unset($parametros);
        $parametros['vacio'] = "Gestión de Servicios";
        $data['registros'] = $this->Servicios_model->leer_servicios($parametros);
        $data['registros_familias'] = $this->Servicios_model->leer_familias_servicios($parametros);

        // ... Viewer con el contenido
        $data['pagetitle'] = 'Gestión de servicios';
        $data['actionstitle'][] = '<a href="' . base_url() . 'servicios/gestion/nuevo" class="btn btn-primary text-inverse-primary">Añadir producto</a>';
        $data['content_view'] = $this->load->view('servicios/servicios_view', $data, true);

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

    function gestion($accion = null, $id_servicio = null)
    {
        // ... Comprobamos la sesion del servicio
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
                $param['id_servicio'] = $id_servicio;
                $data['registros'] = $this->Servicios_model->leer_servicios($param);

                $data['productos_tienda'] = $this->Servicios_model->leer_productos_tienda($id_servicio);
            }

            unset($param);
            $param['vacio'] = "";
            $data['familias_servicios'] = $this->Servicios_model->leer_familias_servicios($param);

            unset($param);;
            $param = [
                "activo" => 1
            ];
            $data['tarifas'] = $this->Tarifas_model->leer_tarifas($param);
            $data['precios'] = $this->Tarifas_model->leer_precios($id_servicio);
            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nuevo servicio' : 'Editar servicio';
            $data['content_view'] = $this->load->view('servicios/servicios_nuevoeditar_view', $data, true);

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
                $id_servicio_creado = $this->Servicios_model->nuevo_servicio($parametros);
                $data['estado'] = $id_servicio_creado;

                $this->Servicios_model->asociar_productos_tienda($id_servicio_creado, $this->input->post('productos_tienda'));
                if (isset($_POST['pvptarifas'])) {
                    $parametros = $_POST['pvptarifas'];
                    $this->Tarifas_model->actualizar_precios_tarifas($id_servicio, $parametros);
                }
            } else {
                $parametros['id_servicio'] = $id_servicio;
                $data['estado'] = $this->Servicios_model->actualizar_servicio($parametros);

                $this->Servicios_model->asociar_productos_tienda($parametros['id_servicio'], $this->input->post('productos_tienda'));
                if (isset($_POST['pvptarifas'])) {
                    $parametros = $_POST['pvptarifas'];
                    $this->Tarifas_model->actualizar_precios_tarifas($id_servicio, $parametros);
                }
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_servicio'] = $id_servicio;
            $data['borrado'] = $this->Servicios_model->borrar_servicio($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros'] = $this->Servicios_model->leer_servicios($parametros);
            $data['registros_familias'] = $this->Servicios_model->leer_familias_servicios($parametros);

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('servicios/servicios_view', $data, true);

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

    function familias($accion = null, $id_familia_servicio = null)
    {
        // ... Comprobamos la sesion del servicio
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
                $param['id_familia_servicio'] = $id_familia_servicio;
                $data['registros'] = $this->Servicios_model->leer_familias_servicios($param);
            }

            // ... Viewer con el contenido
            $data['pagetitle'] = ($accion == 'nuevo') ? 'Nueva familia' : 'Editar familia';
            $data['content_view'] = $this->load->view('servicios/servicios_familias_nuevoeditar_view', $data, true);

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
                $data['estado'] = $this->Servicios_model->nuevo_familia_servicio($parametros);
            } else {
                $parametros['id_familia_servicio'] = $id_familia_servicio;
                $data['estado'] = $this->Servicios_model->actualizar_familia_servicio($parametros);
            }
        }

        // ----------------------------------------------------------------------------- //
        // ... Borrar ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "borrar") {
            $parametros['id_familia_servicio'] = $id_familia_servicio;
            $data['borrado'] = $this->Servicios_model->borrar_familia_servicio($parametros);
        }

        // ----------------------------------------------------------------------------- //
        // ... Principal ... 
        // ----------------------------------------------------------------------------- //
        if ($accion == "" || $accion == "guardar" || $accion == "actualizar" || $accion == "borrar") {
            unset($parametros);
            $parametros['vacio'] = "";
            $data['registros_familias'] = $this->Servicios_model->leer_familias_servicios($parametros);
            $data['registros'] = $this->Servicios_model->leer_servicios($parametros);

            // ... Viewer con el contenido
            $data['content_view'] = $this->load->view('servicios/servicios_view', $data, true);

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

    function actualizarRellamada()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }

        $parametros = $_POST;
        $result = $this->Servicios_model->actualizar_rellamada($parametros);
        // recalcular la liquidacion
        $response = ['success' => ($result == 1) ? true : false];
        echo json_encode($response);
    }
    
    function actualizarDisponibilidadSinPresu()
    {
        // ... Comprobamos la sesion del cliente
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket == 0) {
            header("Location: " . RUTA_WWW);
            exit;
        }
    
        $parametros = $_POST;
        $result = $this->Servicios_model->actualizar_disponibilidad($parametros);
        // recalcular la liquidacion
        $response = ['success' => ($result == 1) ? true : false];
        echo json_encode($response);
    }

    /*
    public function addHijosServicios()
    {
        if ($this->db->field_exists('padre', 'servicios')) {

        } else {
           $this->db->query('ALTER TABLE `servicios` ADD `padre` INT(11) NOT NULL AFTER `obsoleto`, ADD `parte_padre` DECIMAL(5,2) NOT NULL AFTER `padre`');
           $this->db->query('ALTER TABLE `servicios` CHANGE `nombre_servicio` `nombre_servicio` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL');
        }
        

        $file_name 	= FCPATH . 'Gesrivas/serviciosdisgregados_mas.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
            $nombre_servicio = trim($row[0]);
            $fase = $row[1];
            $parte = intval($row[2]);
            $nombre_servicio_completo = $fase.' '.$nombre_servicio;
            $this->db->where('nombre_servicio', $nombre_servicio_completo);
            $this->db->where('borrado', 0);
            $existe = $this->db->get('servicios')->num_rows();
            echo $existe.'=>'.$nombre_servicio.'<br>'; 
            
            if($existe == 0){
                $this->db->where('nombre_servicio', $nombre_servicio);
                $this->db->where('borrado', 0);
                $padre = $this->db->get('servicios')->row();

                if ($padre !== null) {
                    $registro['nombre_servicio'] = $nombre_servicio_completo;
                    $registro['id_familia_servicio'] = $padre->id_familia_servicio;
                    $registro['abreviatura'] =$padre->abreviatura.' '.$fase;
                    $registro['pvp'] = $padre->pvp * $parte / 100;
                    $registro['iva'] = $padre->iva;
                    $registro['precio_proveedor'] = 0;
                    $registro['templos'] = 0;
                    $registro['duracion'] = $padre->duracion;
                    $registro['color'] = $padre->color;
                    $registro['obsoleto'] = $padre->obsoleto;
                    $registro['padre'] = $padre->id_servicio;
                    $registro['parte_padre'] = $parte;
                    $registro['fecha_creacion'] = date("Y-m-d H:i:s");
                    $registro['id_usuario_creacion'] = 1;
                    $registro['fecha_modificacion'] = date("Y-m-d H:i:s");
                    $registro['id_usuario_modificacion'] = 1;
                    $registro['borrado'] = 0;
                    $this->db->insert('servicios', $registro);

                } else {
                    echo 'No existe el padre de '.$nombre_servicio_completo.'<br>';
                }
            }
            


		}

    }
    */

    /*
    function cajas_regalo() {
        // ... Comprobamos la sesion del servicio
        $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
        if ($ok_ticket==0) { header ("Location: ".RUTA_WWW); exit; }
        
        unset($param);
        while (list($key, $value) = each($_POST)) { $param[$key]=$value; }
        
        unset($parametros);
        if (isset($param)) {
        if (isset($param['fecha_inicio'])) {
            $parametros['fecha_inicio']=$param['fecha_inicio'];
            $parametros['fecha_fin']=$param['fecha_fin'];
            $data['fecha_inicio']=$param['fecha_inicio'];
            $data['fecha_fin']=$param['fecha_fin'];
        }
        if (isset($param['fecha_fin'])) {
            $parametros['fecha_fin']=$param['fecha_fin'];
            $data['fecha_fin']=$param['fecha_fin'];
        }      
        if (isset($param['id_centro'])) {
            $parametros['id_centro']=$param['id_centro'];
            $data['id_centro']=$param['id_centro'];
        }            
        }
        $parametros['cajas_regalo']=1;
        $parametros['estado']="Pagado";
        // ... controlamos que el perfil sea el master, sino solo mostramos lo del centro que
        // corresponda.
        if ($this->session->userdata('id_perfil') > 0) {
        $parametros['id_centro']=$this->session->userdata('id_centro_usuario');
        }    
        $data['registros'] = $this->Dietario_model->leer($parametros);
        
        // ... Exportar a Fichero csv    
        $fichero=RUTA_SERVIDOR."/recursos/cajas_regalo_".$this->session->userdata('id_usuario').".csv";
        $file = fopen($fichero, "w");
        
        $linea = "fecha;centro;servicio;cod proveedor;rembolso proveedor;cliente;empleado\n";
        $linea = iconv("UTF-8", "Windows-1252", $linea);
        fwrite($file, $linea);
        
        foreach ($data['registros'] as $row) {
        $linea = $row['fecha_hora_concepto_aaaammdd'].";".$row['nombre_centro'].";".$row['servicio'].";".$row['codigo_proveedor'].";".round($row['precio_proveedor'],2).";".$row['cliente'].";".$row['empleado']."\n";
        $linea = str_replace(".",",",$linea);
        $linea = iconv("UTF-8", "Windows-1252", $linea);
        fwrite($file, $linea);
        }
        
        unset($parametros);
        $parametros['vacio']="";
        $data['centros'] = $this->Usuarios_model->leer_centros($parametros);
        
        // ... Viewer con el contenido
        $data['content_view'] = $this->load->view('servicios_cajas_regalo_view', $data, true);
        
        // ... Modulos del usuario
        $param_modulos['id_perfil']=$this->session->userdata('id_perfil');
        $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
        
        // ... Pagina master
        $permiso=$this->Acceso_model->TienePermiso($data['modulos'],18);
        if ($permiso) { $this->load->view($this->config->item('template_dir').'/master', $data); }
        else { header ("Location: ".RUTA_WWW."/errores/error_404.html"); exit; }    
    }
    */
}
