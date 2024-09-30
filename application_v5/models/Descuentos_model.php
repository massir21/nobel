<?php
class Descuentos_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }
  
  // -------------------------------------------------------------------
  // ... DESCUENTOS
  // -------------------------------------------------------------------
  function leer_descuentos($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_descuento'])) {
      if ($parametros['id_descuento']>0) {
        $busqueda.=" AND D.id_descuento = @id_descuento ";
      }
    }
    
    if (isset($parametros['id_familia_servicio'])) {
      if ($parametros['id_familia_servicio']>0) {
        $busqueda.=" AND D.id_familia_servicio = @id_familia_servicio ";
      }
    }
    
    if (isset($parametros['id_familia_producto'])) {
      if ($parametros['id_familia_producto']>0) {
        $busqueda.=" AND D.id_familia_producto = @id_familia_producto ";
      }
    }
    
    if (isset($parametros['pago_total_mayor'])) {      
      $busqueda.=" AND D.pago_total < @pago_total_mayor AND tipo_pago = 'mayor' ";      
    }
    
    if (isset($parametros['pago_total_menor'])) {      
      $busqueda.=" AND D.pago_total > @pago_total_menor AND tipo_pago = 'menor' ";      
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT D.id_descuento,D.pago_total,D.tipo_pago,D.descuento_euros,
    D.descuento_porcentaje,D.id_familia_servicio,D.id_familia_producto,D.descripcion,
    D.id_usuario_creacion,D.fecha_creacion,
    D.id_usuario_modificacion,D.fecha_modificacion,
    D.borrado,D.id_usuario_borrado,D.fecha_borrado,
    servicios_familias.nombre_familia as familia_servicio,
    productos_familias.nombre_familia as familia_producto
    FROM descuentos AS D
    LEFT JOIN servicios_familias ON servicios_familias.id_familia_servicio = D.id_familia_servicio
    LEFT JOIN productos_familias ON productos_familias.id_familia_producto = D.id_familia_producto
    WHERE D.borrado = 0 ".$busqueda." ORDER BY D.fecha_creacion DESC ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }
  
  function nuevo_descuento($parametros) {    
    $AqConexion_model = new AqConexion_model();
    
    // ... Datos generales como usuario.
    $registro['pago_total']=$parametros['pago_total'];
    $registro['tipo_pago']=$parametros['tipo_pago'];      
    $registro['descuento_euros']=$parametros['descuento_euros'];
    $registro['descuento_porcentaje']=$parametros['descuento_porcentaje'];
    $registro['id_familia_servicio']=$parametros['id_familia_servicio'];
    $registro['id_familia_producto']=$parametros['id_familia_producto'];    
    $registro['descripcion']=$parametros['descripcion'];    
    //
    $registro['fecha_creacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_creacion']=$this->session->userdata('id_usuario');
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    $registro['borrado']=0;
    
    $AqConexion_model->insert('descuentos',$registro);
      
    $sentenciaSQL="select max(id_descuento) as id_descuento from descuentos";
    $resultado = $AqConexion_model->select($sentenciaSQL,null);
    
    return $resultado[0]['id_descuento'];      
  }

  function actualizar_descuento($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $param['id_descuento']=$parametros['id_descuento'];    
    
    // ... Datos generales como usuario.
    $registro['pago_total']=$parametros['pago_total'];
    $registro['tipo_pago']=$parametros['tipo_pago'];      
    $registro['descuento_euros']=$parametros['descuento_euros'];
    $registro['descuento_porcentaje']=$parametros['descuento_porcentaje'];
    $registro['id_familia_servicio']=$parametros['id_familia_servicio'];
    $registro['id_familia_producto']=$parametros['id_familia_producto'];    
    $registro['descripcion']=$parametros['descripcion'];    
    //
    $registro['fecha_modificacion']=date("Y-m-d H:i:s");
    $registro['id_usuario_modificacion']=$this->session->userdata('id_usuario');
    
    $where['id_descuento']=$parametros['id_descuento'];    
    $AqConexion_model->update('descuentos',$registro,$where);
    
    return 1;    
  }

  function borrar_descuento($parametros) {
    $AqConexion_model = new AqConexion_model();
    
    $parametros['id_usuario_borrado']=$this->session->userdata('id_usuario');
    $parametros['fecha_borrado']=date("Y-m-d H:i:s");
    
    $sentenciaSQL="update descuentos set borrado = 1,
    id_usuario_borrado = @id_usuario_borrado,
    fecha_borrado = @fecha_borrado
    where id_descuento = @id_descuento";
    $AqConexion_model->no_select($sentenciaSQL,$parametros);
    
    return 1;
  }
  
  //
  // ... Comprobar posibles descuento en el pago en euros.  
  //
  function comprobar($items,$importes) {
    $AqConexion_model = new AqConexion_model();

    $parametros['vacio']="";
    $r="";
    $buscar="";
    
    for ($i=0; $i<count($items); $i++) {
      $buscar.=" id_dietario = ".$items[$i]." OR ";
    }
    $buscar = substr($buscar,0,-3);
    
    // ... Leemos los datos de cada item para saber su id_familia_servicio y id_familia_producto
    $sentencia_sql="SELECT dietario.id_dietario,
    servicios.id_familia_servicio,productos.id_familia_producto
    FROM dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    LEFT JOIN productos on productos.id_producto = dietario.id_producto
    WHERE $buscar";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
      
    // ... Creamos un array con el id_dietario,id_familia_servicio,id_familia_producto
    // e importe (con su descuento si lo tiene)
    $x=0;
    for ($i=0; $i<count($items); $i++) {
      foreach ($datos as $row) {
        if ($items[$i]==$row['id_dietario']) {          
          $array['id_dietario'][$x]=$items[$i];
          $array['id_familia_servicio'][$x]=$row['id_familia_servicio'];
          $array['id_familia_producto'][$x]=$row['id_familia_producto'];
          $array['importe'][$x]=$importes[$i];
          
          $x++;          
        }        
      }
    }
    
    //
    // ... SERVICIOS -- Verificación de Descuentos en Familia    
    //
    
    // ... Leemos las familias distintas de todos los items marcados.
    $sentencia_sql="SELECT servicios.id_familia_servicio
    FROM dietario
    LEFT JOIN servicios on servicios.id_servicio = dietario.id_servicio
    WHERE $buscar GROUP BY servicios.id_familia_servicio ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    $x=0;
    foreach ($datos as $row) {
      for ($i=0; $i<count($array); $i++) {
        if (isset($array['id_dietario'][$i])) {
          if ($row['id_familia_servicio']==$array['id_familia_servicio'][$i]) {
            $compara['id_familia_servicio'][$x]=$array['id_familia_servicio'][$i];
            
            // .. Si ya tiene valor le sumo, sino asigno, sino da error.
            if (isset($compara['total'][$x])) {
              $compara['total'][$x]+=$array['importe'][$i];
            }
            else {
              $compara['total'][$x]=$array['importe'][$i];
            }
          }
        }
      }
      $x++;      
    }
    
    for ($i=0; $i<count($compara); $i++) {
      if (isset($compara['id_familia_servicio'][$i])) {
        // Pago mayores de...
        unset($param);
        $param['id_familia_servicio']=$compara['id_familia_servicio'][$i];
        $param['pago_total_mayor']=$compara['total'][$i];
        $datos=$this->leer_descuentos($param);
        
        if ($datos!=0) {
          $r.=" - ".$datos[0]['descripcion']." <br> ";
        }
        
        // Pago menores de...
        unset($param);
        $param['id_familia_servicio']=$compara['id_familia_servicio'][$i];
        $param['pago_total_menor']=$compara['total'][$i];
        $datos=$this->leer_descuentos($param);
        
        if ($datos!=0) {
          $r.=" - ".$datos[0]['descripcion']." <br> ";
        }
      }
    }
    
    //
    // ... PRODUCTOS -- Verificación de Descuentos en Familia    
    //
    // ... Leemos las familias de productos distintas de todos los items marcados.
    $sentencia_sql="SELECT productos.id_familia_producto
    FROM dietario
    LEFT JOIN productos on productos.id_producto = dietario.id_producto
    WHERE $buscar GROUP BY productos.id_familia_producto ";
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    unset($compara);
    $x=0;
    foreach ($datos as $row) {
      for ($i=0; $i<count($array); $i++) {
        if (isset($array['id_dietario'][$i])) {
          if ($row['id_familia_producto']==$array['id_familia_producto'][$i]) {
            $compara['id_familia_producto'][$x]=$array['id_familia_producto'][$i];
            
            // .. Si ya tiene valor le sumo, sino asigno, sino da error.
            if (isset($compara['total'][$x])) {
              $compara['total'][$x]+=$array['importe'][$i];
            }
            else {
              $compara['total'][$x]=$array['importe'][$i];
            }
          }
        }
      }
      $x++;      
    }
    
    for ($i=0; $i<count($compara); $i++) {
      if (isset($compara['id_familia_producto'][$i])) {
        // Pago mayores de...
        unset($param);
        $param['id_familia_producto']=$compara['id_familia_producto'][$i];
        $param['pago_total_mayor']=$compara['total'][$i];
        $datos=$this->leer_descuentos($param);
        
        if ($datos!=0) {
          $r.=" - ".$datos[0]['descripcion']." <br> ";
        }
        
        // Pago menores de...
        unset($param);
        $param['id_familia_producto']=$compara['id_familia_producto'][$i];
        $param['pago_total_menor']=$compara['total'][$i];
        $datos=$this->leer_descuentos($param);
        
        if ($datos!=0) {
          $r.=" - ".$datos[0]['descripcion']." <br> ";
        }
      }
    }
    
    
    if ($r=="") {
      $r="0";
    }
    
    return $r;
  }
  
  // ... Comprueba si hay que aplicar descuento en templos
  // y lo aplica al array de servicios pasado, devolviendolo con los nuevos
  // resultados.
  function descuentos_templos($servicios,$actualiza_dietario,$carnet_comprobar) {
    $AqConexion_model = new AqConexion_model();
    
    $r['mensaje']="";
    $r['servicios']=$servicios;
    
    $sin_descuentos=0;
    
    // ... En primer lugar comprobamos los carnets elegidos, si hay alguno
    // especial de servicios, entonces no aplicamos descuento de templos.
    if (is_countable($servicios) && count($servicios)>1) {
      // ... Comprobamos el codigo de carnet indicado, en caso de ser
      // una comprobacion de carnet y no un pago directamente,
      // Si es un pago ya están guardado los carnets elegidos para el pago
      // en la tabla correspondiente.        
      if ($carnet_comprobar!="") {
        unset($param2);
        $param2['codigo']=$carnet_comprobar;
        $carnet=$this->Carnets_model->leer($param2);
        
        if ($carnet[0]['id_tipo']==99) {
          $sin_descuentos=1;
        }
      }

      unset($param2);      
      $param2['id_cliente']=$servicios[0]['id_cliente'];
      $param2['fecha_venta']=date("Y-m-d");
      $carnets_elegidos=$this->Carnets_model->pagotemplos($param2);
      
      //
      // ... Si no hay carnets elegidos, directamente no se puede pagar.
      if ($carnets_elegidos != 0) {
        foreach ($carnets_elegidos as $row) {          
          if ($row['id_tipo']==99) {
            $sin_descuentos=1;
          }
        }        
      }
    }
    
    //
    // ... Si es posible aplicar descuento, continuamos.
    if ($sin_descuentos==0) {      
      // ... Compruebo si vienen más de un servicio.
      if (is_array($servicios) && count($servicios)>1) {
        // ... Verificamos si hay que aplicar Descuento por Servicio.
        $duracion_anterior=99999; // ... para que la primera vez se mas alta simpre y coja el primer valor.
        $index=0;
        $tiempo=0;
        $templos_depilacion=0;
        $index_depilacion=0;
        $templos_anterior_depilacion=0;        
        $labio_ceja=0;
        $num_masajes=0; // ... contador para saber el num de servicios de tipo masaje.
        
        for ($i=0; $i<count($servicios); $i++) {
          unset($param);
          $param['id_servicio']=$servicios[$i]['id_servicio'];
          $datos_servicio = $this->Servicios_model->leer_servicios($param);
          
          // ... MESAJES. Solo si el servicio es de la familia masaje.
          if ($datos_servicio[0]['id_familia_servicio']==1) {
            $tiempo += $datos_servicio[0]['duracion'];
            $num_masajes = $num_masajes + 1;
            
            // ... Controlamos que servicio tiene la menor duracion
            // para luego aplicar el descuento ahí.
            if ($datos_servicio[0]['duracion']<$duracion_anterior) {
              $index=$i;
            }        
            $duracion_anterior=$datos_servicio[0]['duracion'];
          }
          
          // DEPILACIONES. Verificamos si hay descuentos.
          // tiene que ser un o mas servicio que sumen 5 templos pero que no sean Labio o cejas.
          //if ($datos_servicio[0]['id_familia_servicio']==7 && $datos_servicio[0]['id_servicio']!=20 && $datos_servicio[0]['id_servicio']!=22) {
          if ($datos_servicio[0]['id_familia_servicio'] == 7) {
            $templos_depilacion+=$servicios[$i]['templos'];
            
            // ... Controlamos que servicio tiene la mayor duracion
            // y a ese le quitamos 1 templo (que sería como regalar labio o cejas)
            if ($servicios[$i]['templos']>$templos_anterior_depilacion) {
              $index_depilacion=$i;
            }        
            $templos_anterior_depilacion=$servicios[$i]['templos'];
          }
          // ... Controlo ademas de que se sumen mas de 5 templos, haya algun servicio de ceja o labio.
          if ($datos_servicio[0]['id_servicio']==20 || $datos_servicio[0]['id_servicio']==22) {
            $labio_ceja=1;  
          }
        }
        
        // ... Masajes descuento templos varios masajes
      //  if ($tiempo>=60 && $num_masajes>1) {
          // $servicios[$index]['templos']=$servicios[$index]['templos']-0.5;
          
        //  $r['servicios']=$servicios;
        //  $r['mensaje'].="Aplicado Descuento de 0,5 templos por más de 60 min masaje";
          
          // ... Actualizamos cada linea de dietario con los templos que finalmente quedan
          // y la nota del descuento.
      //    if ($actualiza_dietario==1) {
      //      unset($registro);
       //     unset($where);          
          
       //     $registro['templos']=$servicios[$index]['templos'];
       //     $registro['notas_pago_descuento']="Aplicado Descuento de 0,5 templos por más de 60 min masaje";    
       //     $where['id_dietario']=$servicios[$index]['id_dietario'];
          
       //     $AqConexion_model->update('dietario',$registro,$where);
      //   }
      //  }
        
        // ... Depilaciones
        if ($templos_depilacion >= 5 && $labio_ceja == 1) {
         // $servicios[$index_depilacion]['templos']=$servicios[$index_depilacion]['templos']-1;
          
         // $r['servicios']=$servicios;
         // if ($r['mensaje']!="") { $r['mensaje'].="<br>"; }
         // $r['mensaje'].="Aplicado Descuento de 1 templo (Por Labio o Cejas)";
          
          // ... Actualizamos cada linea de dietario con los templos que finalmente quedan
          // y la nota del descuento.
          if ($actualiza_dietario==1) {
            unset($registro);
            unset($where);          
          
            $registro['templos']=$servicios[$index_depilacion]['templos'];
           // $registro['notas_pago_descuento']="Aplicado Descuento de 1 templo (Por Labio o Cejas)";
            $where['id_dietario']=$servicios[$index_depilacion]['id_dietario'];
          
            $AqConexion_model->update('dietario',$registro,$where);
          }
        }        
      }      
    }
    
    return $r;  
  }
  
}
?>