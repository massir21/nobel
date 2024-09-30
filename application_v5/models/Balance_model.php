<?php
class Balance_model extends CI_Model {
  
  function __construct() {
    parent::__construct();
  }

  function leer_meses($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_mes'])) {
      $busqueda.=" WHERE meses.id_mes = @id_mes ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_mes,mes
    FROM meses ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }

  function leer_anios($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";
    
    if (isset($parametros['id_anio'])) {
      $busqueda.=" WHERE anios.id_anio = @id_anio ";
    }
    
    // ... Leemos los registros
    $sentencia_sql="SELECT id_anio,anio
    FROM anios ".$busqueda;
    $datos = $AqConexion_model->select($sentencia_sql,$parametros);
    
    return $datos;
  }


  //Obtenemos el balance de gastos generales del año actual y mes actual

  function leer_balance_gastos($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $centro_actual = "ct.id_centro <> 1";
    $excluir = $this->db->where('id_tipo_proveedor','13')->get('proveedores')->result();
    $provs="gf.id_proveedor not in ('0'"; foreach($excluir as $e){ $provs .=",'".$e->id_proveedor."'";}
    $provs .=")";
    if (isset($parametros['ano'])) {
      $anio_actual = ' @ano ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }

    if (isset($parametros['id_centro'])&&$parametros['id_centro']!=0) {
      $centro_actual = " ct.id_centro = @id_centro ";
    }

    $sentencia_sql = "SELECT  
    ct.nombre_centro AS centro,
    COALESCE(SUM(gf.total_factura), 0) AS total_facturado
      FROM (
          SELECT id_centro, nombre_centro
          FROM centros
      ) ct
      LEFT JOIN gestion_facturas gf ON ct.id_centro = gf.centro_id AND gf.borrado='0' AND ".$provs." AND YEAR(DATE(fecha_factura)) = ".$anio_actual." AND MONTH(DATE(fecha_factura)) = ".$mes_actual."
      WHERE ".$centro_actual." 
      GROUP BY ct.id_centro, ct.nombre_centro";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        //show_array($datos);
        return $datos;

  }

  function leer_consolidado_gastos($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $excluir = $this->db->where('id_tipo_proveedor','13')->get('proveedores')->result();
    $provs="id_proveedor not in ('0'"; foreach($excluir as $e){ $provs .=",'".$e->id_proveedor."'";}
    $provs .=")";

    $centro_actual = "gf.centro_id <> 1";

    if (isset($parametros['ano'])) {
      $anio_actual = ' @ano ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }

    if (isset($parametros['id_centro'])&&$parametros['id_centro']!=0) {
      $centro_actual = " gf.centro_id = @id_centro ";
    }


    $sentencia_sql = "SELECT  
           COALESCE(SUM(gf.total_factura),0) as total_consolidado,
           COUNT(gf.id_gestion_facturas) as total_facturas
        FROM gestion_facturas gf
        WHERE YEAR(DATE(fecha_factura)) = ".$anio_actual." AND ".$centro_actual." AND MONTH(DATE(fecha_factura)) = ".$mes_actual." AND borrado='0' AND ".$provs;

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        return $datos;
  }


  //Obtenemos el balance de ingresos generales del año actual y mes actual

  function leer_balance_ingresos($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');

    $centro_actual = "ct.id_centro <> 1";

    if (isset($parametros['anio'])) {
      $anio_actual = ' @anio ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }

    if (isset($parametros['id_centro'])&&$parametros['id_centro']!=0) {
      $centro_actual = " ct.id_centro = @id_centro ";
    }
    

    $sentencia_sql = "SELECT 
          ct.nombre_centro AS centro,
          COALESCE(SUM(fc.total), 0) AS total_facturado
      FROM (
          SELECT id_centro, nombre_centro
          FROM centros
      ) ct
      LEFT JOIN facturas fc ON ct.id_centro = fc.id_centro AND YEAR(DATE(fc.fecha_emision)) = ".$anio_actual." AND MONTH(DATE(fc.fecha_emision)) = ".$mes_actual."
      WHERE ".$centro_actual."
      GROUP BY ct.id_centro, ct.nombre_centro;";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        return $datos;
  }
  //Alfonso considero que los ingresos deben de consultarse en dietario y no en consultas
  function leer_balance_ingresos_dietario($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');

    $centro_actual = "ct.id_centro <> 1";

    if (isset($parametros['ano'])) {
      $anio_actual = ' @ano ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }

    if (isset($parametros['id_centro'])&&$parametros['id_centro']!=0) {
      $centro_actual = " ct.id_centro = @id_centro ";
    }
    

    $sentencia_sql = "SELECT 
          ct.id_centro, ct.nombre_centro AS centro,
          COALESCE(SUM(fc.importe_euros), 0) AS total_facturado,
          0 AS objetivo, 0 AS rentabilidad
      FROM (
          SELECT id_centro, nombre_centro
          FROM centros
      ) ct
      LEFT JOIN dietario fc ON ct.id_centro = fc.id_centro AND fc.pago_a_cuenta='1' AND fc.borrado='0' AND YEAR(DATE(fc.fecha_creacion)) = ".$anio_actual." AND MONTH(DATE(fc.fecha_creacion)) = ".$mes_actual."
      WHERE ".$centro_actual."
      GROUP BY ct.id_centro, ct.nombre_centro;";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);

        //sumamos las fascturas emitidas registradas en gastos
        $proveedores = $this->db->where('id_tipo_proveedor','13')->get('proveedores')->result();
        $provs=Array(); foreach($proveedores as $p){ $provs[]=$p->id_proveedor;}
        $this->db->select('centro_id,SUM(total_factura) as total');
        if(count($provs)>0){
          $this->db->where_in('id_proveedor',$provs);
        }                        
        $facturas_emitidas = $this->db->where('borrado',0)
                              ->where('month(fecha_factura)',$parametros['mes'])
                              ->where('year(fecha_factura)',$parametros['ano'])
                              ->group_by('centro_id')
                              ->get('gestion_facturas')->result();
        
        //echo $this->db->last_query();exit;
        foreach($datos as &$ingreso)foreach($facturas_emitidas as $emitidas)if($ingreso['id_centro']==$emitidas->centro_id){
          $ingreso['total_facturado']+=$emitidas->total;
        }
        //traer los objetivos del periodo
        $objetivos = $this->db->where('borrado',0)
                              ->where('mes',$parametros['mes'])
                              ->where('ano',$parametros['ano'])
                              ->group_by('id_centro')
                              ->get('objetivos')->result();
        foreach($datos as &$ingreso)foreach($objetivos as $obj)if($ingreso['id_centro']==$obj->id_centro){
          $ingreso['objetivo']=$obj->facturacion;
          $ingreso['rentabilidad']=round($obj->facturacion*($obj->rentabilidad/100),2);
        }
        return $datos;
  }

  function leer_consolidado_ingresos($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $centro_actual = "fc.id_centro <> 1";


    if (isset($parametros['anio'])) {
      $anio_actual = ' @anio ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }
    

    if (isset($parametros['id_centro'])) {
      $centro_actual = " fc.id_centro = @id_centro ";
    }

    $sentencia_sql = "SELECT  
            COALESCE(SUM(fc.total),0) as total_consolidado,
            COUNT(fc.id_factura) as total_facturas
        FROM facturas fc
        WHERE YEAR(DATE(fecha_emision)) = ".$anio_actual." AND ".$centro_actual." AND MONTH(DATE(fecha_emision)) = ".$mes_actual."";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        return $datos;
  }

  //Alfonso: considero que los ingresos deben consultarse en dietario y no en facturas
  function leer_consolidado_ingresos_dietario($parametros){
    
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $centro_actual = "fc.id_centro <> 1";


    if (isset($parametros['ano'])) {
      $anio_actual = ' @ano ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }
    

    if (isset($parametros['id_centro'])&&$parametros['id_centro']!=0) {
      $centro_actual = " fc.id_centro = @id_centro ";
    }

    $sentencia_sql = "SELECT  
            COALESCE(SUM(fc.importe_euros),0) as total_consolidado,
            COUNT(fc.id_dietario) as total_facturas
        FROM dietario fc
        WHERE YEAR(DATE(fecha_creacion)) = ".$anio_actual." AND ".$centro_actual." AND MONTH(DATE(fecha_creacion)) = ".$mes_actual." AND pago_a_cuenta='1'";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        //sumamos las fascturas emitidas registradas en gastos
        $proveedores = $this->db->where('id_tipo_proveedor','13')->get('proveedores')->result();
        $provs=Array(); foreach($proveedores as $p){ $provs[]=$p->id_proveedor;}
        $this->db->select('SUM(total_factura) as total');
        if(count($provs)>0){
          $this->db->where_in('id_proveedor',$provs);
        }   
        
        $facturas_emitidas = $this->db->where('month(fecha_factura)',$parametros['mes'])
                                  ->where('year(fecha_factura)',$parametros['ano'])
                                  ->where('borrado',0)
                                  ->get('gestion_facturas')->row();
        $datos[0]['total_consolidado']+=$facturas_emitidas->total;
        $datos[0]['facturas_emitidas']=$facturas_emitidas->total;
        return $datos;
  }

  



  function leer_balance_ganancias($parametros){

    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $centro_actual = "ct.id_centro <> 1";
    $centro_actual_gastos = "gf.centro_id <> 1";


    if (isset($parametros['anio'])) {
      $anio_actual = ' @anio ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }
    
    if (isset($parametros['id_centro'])) {
      $centro_actual = " ct.id_centro = @id_centro ";
      $centro_actual_gastos = "gf.centro_id = @id_centro ";
    }


    $sentencia_sql = "SELECT 
          ct.nombre_centro AS centro,
          COALESCE(SUM(gf.total_factura), 0) AS total_gastos,
          COALESCE((SELECT SUM(fi.total) 
                    FROM facturas fi 
                    WHERE fi.id_centro = ct.id_centro 
                          AND YEAR(fi.fecha_emision) = ".$anio_actual." 
                          AND MONTH(fi.fecha_emision) = ".$mes_actual."), 0) AS total_ingresos,
          COALESCE(SUM((SELECT SUM(fi.total) 
                        FROM facturas fi 
                        WHERE fi.id_centro = ct.id_centro 
                              AND YEAR(fi.fecha_emision) = ".$anio_actual." 
                              AND MONTH(fi.fecha_emision) = ".$mes_actual.")), 0) - COALESCE(SUM(gf.total_factura), 0) AS diferencia
      FROM centros ct
      LEFT JOIN gestion_facturas gf ON ct.id_centro = gf.centro_id AND YEAR(gf.fecha_factura) = ".$anio_actual." AND MONTH(gf.fecha_factura) = ".$mes_actual." AND ".$centro_actual_gastos."
      WHERE ".$centro_actual."
      GROUP BY ct.id_centro, ct.nombre_centro";

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        return $datos;
  }


  function leer_consolidado_ganancias($parametros)
  {
    $AqConexion_model = new AqConexion_model();
    $anio_actual = date('Y');
    $mes_actual  = date('n');
    $centro_actual = "ct.id_centro <> 1";
    $centro_actual_gastos = "gf.centro_id <> 1";


    if (isset($parametros['anio'])) {
      $anio_actual = ' @anio ';
    }

    if (isset($parametros['mes'])) {
      $mes_actual = ' @mes ';
    }
    
    if (isset($parametros['id_centro'])) {
      $centro_actual = " ct.id_centro = @id_centro ";
      $centro_actual_gastos = "gf.centro_id = @id_centro ";
    }

    $sentencia_sql = "SELECT 0 as objetivo,
            COALESCE(SUM((SELECT SUM(fi.total) 
                          FROM facturas fi 
                          WHERE fi.id_centro = ct.id_centro 
                                AND YEAR(fi.fecha_emision) = ".$anio_actual." 
                                AND MONTH(fi.fecha_emision) = ".$mes_actual.")), 0) - COALESCE(SUM(gf.total_factura), 0) AS ganancia
        FROM centros ct
        LEFT JOIN gestion_facturas gf ON ct.id_centro = gf.centro_id AND YEAR(gf.fecha_factura) = ".$anio_actual." AND MONTH(gf.fecha_factura) = ".$mes_actual." AND ".$centro_actual_gastos."
        WHERE ".$centro_actual;

        $datos = $AqConexion_model->select($sentencia_sql,$parametros);
        

        return $datos;
  }


  //Alfonso: considero que los ingresos deben consultarse en dietario y no de facturas
  function leer_balance_ganancias_dietario($parametros){
    $ingresos = $this->leer_balance_ingresos_dietario($parametros);
    $gastos = $this->leer_balance_gastos($parametros);
      
    if($ingresos>0)foreach($ingresos as &$ing)foreach($gastos as $gas)if($gas['centro']==$ing['centro']){
      if(!isset($ing['total_gastos'])){ $ing['total_gastos']=0; }
      if(!isset($ing['total_ingresos'])){ $ing['total_ingresos']=0; }
      $ing['total_facturado'] -= $gas['total_facturado'];
      $ing['total_ingresos'] += $ing['total_ingresos'];
      $ing['total_gastos'] += $gas['total_facturado'];
      $ing['diferencia'] = $ing['total_facturado'];
      $ing['objetivo'] = $ing['objetivo'];
    }
    return $ingresos;
  }


  function leer_consolidado_ganancias_dietario($parametros)
  {
    $total = $this->leer_balance_ganancias_dietario($parametros);
    $return[0]=Array(
      'total_ingresos'=>0,
      'total_gastos'=>0,
      'ganancia'=>0,
      'diferencia'=>0,
      'objetivo'=>0
    );
    if($total>0)foreach($total as $t){
      $return[0]['total_ingresos'] += $t['total_ingresos'];
      $return[0]['total_gastos'] += $t['total_gastos'];
      $return[0]['diferencia'] += $t['diferencia'];
      $return[0]['ganancia'] += $t['diferencia'];
      $return[0]['objetivo'] += $t['objetivo'];
    }

    return $return;
  }



  //funciones para las graficas
  function consolidado_grafica($parametros)
  {
    
    $meses = Array();
    for($i=1;$i<=12;$i++){ 
      $meses[$i-1]['id_mes']=$i; 
      $meses[$i-1]['mes']=mesletra($i);
      $parametros['mes']=$i;
      $meses[$i-1]['total_gastos']=0;
      $meses[$i-1]['total_ingresos']=0;
      $meses[$i-1]['diferencia']=0;
      $meses[$i-1]['objetivo']=0;
      
      $totales = $this->leer_consolidado_ganancias_dietario($parametros);
      if($totales>0){
        $meses[$i-1]['total_gastos'] = $totales[0]['total_gastos'];
        $meses[$i-1]['total_ingresos'] = $totales[0]['diferencia']+$totales[0]['total_gastos'];
        $meses[$i-1]['diferencia'] = $totales[0]['diferencia'];
        $meses[$i-1]['objetivo'] = $totales[0]['objetivo'];
      }
    }
    return $meses;

  }//fin de la funcion consolidado_grafica 

}



?>