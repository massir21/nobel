<?php

class Velazquez extends CI_Controller {
  function __construct() {
    parent::__construct();
  }

  function index() {
    // ... Servicios
    unset($param);
    $param['velazquez']=1;
    $data['servicios'] = $this->Servicios_model->leer_servicios($param);
    
    // ... Empleados
    unset($param);
    $param['velazquez']=1;
    $param['fecha_agenda']="2017-07-18";
    $data['empleados'] = $this->Usuarios_model->leer_usuarios($param);
    
    unset($parametros);
	foreach ($_POST as $key => $value) {
		$parametros[$key] = $value;
	}
    
    // ... Horarios
    $data['horarios']=0;
    if (isset($parametros)) {
      if ($parametros['fecha'] != "" && $parametros['id_empleado'] != "" && $parametros['id_servicio'] != "") {
	$f = explode("/", $parametros['fecha']);
	$fecha_aaaammdd=$f[2]."-".$f[1]."-".$f[0];
	
	unset($param);
	$param['id_servicio']=$parametros['id_servicio'];
	$servicio = $this->Servicios_model->leer_servicios($param);
	
	unset($param);
	$param['id_empleado']=$parametros['id_empleado'];	
	$date = new DateTime($fecha_aaaammdd);
        $param['fecha']=date_format($date,'d-m-Y');
	$param['duracion']=$servicio[0]['duracion'];
	
	$data['horarios']=$this->Agenda_model->horas_libres($param);
	
	//
	// ... Guarda la cita
	//
	if ($parametros['pedir_cita']==1) {
	  unset($param);
	  $param['telefono']=$parametros['telefono'];
	  $cliente=$this->Clientes_model->leer_clientes($param);
	  
	  $id_cliente=0;
	  if ($cliente>0) {
	    $id_cliente=$cliente[0]['id_cliente'];
	  }
	  else {
	    unset($param);
	    $param['nombre']=$parametros['nombre'];
	    $param['apellidos']=$parametros['apellidos'];
	    $param['telefono']=$parametros['telefono'];
	    $id_cliente=$this->Clientes_model->nuevo_cliente($parametros);
	  }
	  
	  unset($param);
	  $param['duracion']=$servicio[0]['duracion'];        
	  $param['id_servicio']=$parametros['id_servicio'];
	  $param['id_empleado']=$parametros['id_empleado'];
	  $param['id_cliente']=$id_cliente;
	  $param['fecha']=$fecha_aaaammdd;
	  $param['hora']=$parametros['horario'];
	  $param['observaciones']=$parametros['observaciones'];
	  $id_cita_creada = $this->Agenda_model->guardar_cita($param);
	  
	  // ... Copia los datos de la cita en el dietario.
	  if ($id_cita_creada > 0) {
	    unset($param2);
	    $param2['id_cita']=$id_cita_creada;	    
	    $r=$this->Dietario_model->copiar_cita($param2);
	  }
	  
	  unset($param2);
	  $param2['id_usuario']=$parametros['id_empleado'];	  
	  $empleado = $this->Usuarios_model->leer_usuarios($param2);	  
	  $data['que_servicio']=$servicio[0]['nombre_servicio'];
	  $data['que_empleado']=$empleado[0]['nombre']." ".$empleado[0]['apellidos'];
	  
	  $data['finalizado']=1;
	}
      }
    }
    
    $this->load->view('templates/master_velazquez', $data);
  }

}

?>