<?php
class Intercentros extends CI_Controller {
  function __construct() {
    parent::__construct();
  }

  // ----------------------------------------------------------------------------- //
  // ... PAGOS INTERCENTROS
  // ----------------------------------------------------------------------------- //
  
  /*
  function index() {
    // ... Comprobamos la sesion del horario
    $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
    if ($ok_ticket==0) { header ("Location: ".RUTA_WWW); exit; }
    
    $id_centro=6; // Por defecto betanzos
    $fecha_desde="";
    $fecha_hasta="";
    
    unset($parametros);
    if (!empty($_POST)) {$parametros = $_POST;}
    
    // ... controlamos que el perfil sea el master, sino solo mostramos
    // lo del centro que corresponda.
    if ($this->session->userdata('id_perfil') == 0) { $id_centro=6; }
    else { $id_centro=$this->session->userdata('id_centro_usuario'); }
    
    if (isset($parametros['id_centro'])) { $id_centro=$parametros['id_centro']; }
    if (isset($parametros['fecha_desde'])) { $fecha_desde=$parametros['fecha_desde']; }
    else {
      $fechaMesPasado = strtotime ('-1 month', strtotime(date("Y-m-d")));
      $fecha_desde = date('Y-m-d', $fechaMesPasado);
    }
    if (isset($parametros['fecha_hasta'])) { $fecha_hasta=$parametros['fecha_hasta']; }
    else { $fecha_hasta=date("Y-m-d"); }
    
    // ... Leemos los datos intercentros.
    unset($parametros);
    $parametros['vacio']="";
    if ($fecha_desde!="") { $parametros['fecha_desde']=$fecha_desde." 00:00:00"; }
    if ($fecha_hasta!="") { $parametros['fecha_hasta']=$fecha_hasta." 23:59:59";; }
    if ($id_centro>0) { $parametros['id_centro']=$id_centro; }    
    $data['registros'] = $this->Intercentros_model->datos($parametros);
    
    unset($parametros);
    $parametros['vacio']="";
    $data['centros'] = $this->Intercentros_model->leer_centros_nombre($parametros);
    
    $data['id_centro']=$id_centro;
    $data['fecha_desde']=$fecha_desde;
    $data['fecha_hasta']=$fecha_hasta;
    
    // ... Viewer con el contenido
    $data['pagetitle']="'Pagos Intercentros';
    $data['actiontitle'][] = '<a href="'.base_url().'intercentros/exportar/'.(isset($fecha_desde))?$fecha_desde:''.'/'.(isset($fecha_hasta))?$fecha_hasta:''.'/'.(isset($id_centro))?$id_centro:''.'" class="btn btn-warning text-inverse-warning">Exportar CSV</a>';
    $data['content_view'] = $this->load->view('intercentros/intercentros_view', $data, true);
    
    // ... Modulos del usuario
    $param_modulos['id_perfil']=$this->session->userdata('id_perfil');
    $data['modulos'] = $this->Usuarios_model->leer_modulos($param_modulos);
    
    // ... Pagina master
    $permiso=$this->Acceso_model->TienePermiso($data['modulos'],23);
    if ($permiso) { $this->load->view($this->config->item('template_dir').'/master', $data); }
    else { header ("Location: ".RUTA_WWW."/errores/error_404.html"); exit; }    
  }
  
  function exportar($fecha_desde = null, $fecha_hasta = null, $id_centro = null) {
    // ... Comprobamos la sesion del usuario
    $ok_ticket = $this->Ticket_model->recoger_ticket($this->session->userdata('ticket'));
    if ($ok_ticket==0) { header ("Location: ".RUTA_WWW); exit; }

    // ... Leemos los datos intercentros.
    unset($parametros);    
    $parametros['fecha_desde']=$fecha_desde." 00:00:00";
    $parametros['fecha_hasta']=$fecha_hasta." 23:59:59";
    $parametros['id_centro']=$id_centro;
    $registros = $this->Intercentros_model->datos($parametros);
    
    unset($parametros);
    $parametros['vacio']="";
    $centros = $this->Intercentros_model->leer_centros_nombre($parametros);
    
    unset($parametros);
    $parametros['id_centro']=$id_centro;
    $que_centro = $this->Intercentros_model->leer_centros_nombre($parametros);
    
    $fichero=RUTA_SERVIDOR."/recursos/intercentros_".$id_centro.".csv";    
    $file = fopen($fichero, "w");
    
    $linea = "Del ".$fecha_desde." al ".$fecha_hasta." (".$que_centro[0]['nombre_centro'].");\n\n";
    $linea = iconv("UTF-8", "Windows-1252", $linea);
    fwrite($file, $linea);

    if (isset($centros)) {
      if ($centros != 0) {
        foreach ($centros as $key => $c) {
          
          if ($c['id_centro'] != $id_centro) {
            $linea = $c['nombre_centro']."\n";
            $linea .= "Fecha Hora;Servicio;Templos;Carnet;Original de;Usando en;Total\n";
            $linea = iconv("UTF-8", "Windows-1252", $linea);
            fwrite($file, $linea);
    
            if ($registros > 0) {
              
              $total=0;
              $parcial=0;
              
              foreach ($registros as $row) {
                if ($row['id_centro']==$c['id_centro']) {
                  
                  if ($row['total_sin_recargas']>0 && $row['total_sin_recargas']<$row['total']) {
                    $total+=$row['total_sin_recargas'];
                    $parcial=$row['total_sin_recargas'];
                  }
                  else {
                    $total+=$row['total'];
                    $parcial=$row['total'];
                  }
                  
                  $linea = $row['fecha']." ".$row['hora'].";".$row['servicio'].";".$row['templos'].";".$row['codigo'].";".$row['original_de'].";".$row['usado_en'].";".$parcial."\n";
                  $linea = iconv("UTF-8", "Windows-1252", $linea);
                  
                  fwrite($file, $linea);
                }
              }
              
              $linea = ";;;;;;".round($total,2)."\n\n";
              
              fwrite($file, $linea);
            }
          }
        }
      }
    }
  
    fclose($file);
    
    if (file_exists($fichero)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="'.basename($fichero).'"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($fichero));
      readfile($fichero);      
    }
    
    exit;    
  }
  */
  
}