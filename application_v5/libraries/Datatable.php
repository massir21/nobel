<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// 20240722 CHAINS:
//
//		ATENCION!!!!
//
//		NO MODIFICAR ESTA CLASE PARA HACER COSAS PERSONALIZADAS CON TABLAS O CAMPOS HARDCODEADOS QUE NO SE LE PASAN COMO PARAMETRO
//		PUES HARÄ EXPLOTAR LA APLICACIÓN EN OTROS CONTROLADORES/ACCIONES
//
//


class Datatable {
    
    protected $datatable;
    private $database;
    
    public function __construct($db = NULL)
    {
        $this->datatable =& get_instance();
    }

    public function get_datatable($param, $tabla, $aColumns, $join = null, $where = null, $add_rule=null){
		
        /* CHAINS 20240206 - Se añade soporte para que en cada join se acepten arrays, a partir de ahora se puede seguir usando
                $join=[
                       "tabla" => "condicion"
                ];
                o bien como
                $join=[
                    "tabla"=> ["tipo_join" , "condicion" ]
                ];
                Donde tipo_join puede ser LEFT, RIGHT o INNER


            CHAINS 20240218 - Se añade soporte para que en un reigistro del $where puedan meterse varios valores
                ["campo"=>["valor1","valor2"]]
                Esto generará una condicion (campo=valor1 OR campo=valor2)
        */

        $iDraw = $param['draw'];
        $iColumns = $param['columns'];
        $iOrder = $param['order'];
        $iStart = $param['start'];
        $iLength = $param['length'];
        $iSearch = $param['search'];
        $select = implode(',', $aColumns);
		
        //PRIMERA CONSULTA PARA EL RECORDTOTAL
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
                if(is_array($value)){
                    $this->datatable->db->$key($value[0], $value[1]);
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }

        // EL QUE
        $this->datatable->db->select($select);
		
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                // CHAINS 20240206 - Si la condicion es un array en el primer elemento viene el tipo de join y en el segundo la condicion
                if(is_array($condicion)){
                    if(count($condicion)==2){
                        $tipoJoin=$condicion[0];
                        $condicionJoin=$condicion[1];
                        $this->datatable->db->join($t_join,$condicionJoin,$tipoJoin);
                    }
                }
                else {
                    $this->datatable->db->join($t_join, $condicion, 'left');
                }
            }
        }
		
        //CONDICION PRINCIPAL
        if(isset($where) && $where != '') {
            foreach ($where as $campo => $valor) {
                /* CHAINS 20240218 - Soporte para condiciones multiples en $where */
                if(is_array($valor)){
                    $this->datatable->db->group_start();
                    foreach($valor as $unvalor){
                        $this->datatable->db->or_where($campo,$unvalor);
                    }
                    $this->datatable->db->group_end();
                }
                else
                $this->datatable->db->where($campo, $valor);
            }
        }
		
        $recordsTotal = $this->datatable->db->get()->num_rows();
        $recordsFiltered = $recordsTotal;
        ////////////////////////////////////////////////////////////////
        //SEGUNDA CONSULTA PARA EL RECORDSFITERED
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
               if(is_array($value)){
                    $this->datatable->db->$key($value[0], $value[1]);
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }
		
        // EL QUE
        $this->datatable->db->select($select);
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                // CHAINS 20240206 - Si la condicion es un array en el primer elemento viene el tipo de join y en el segundo la condicion
                if(is_array($condicion)){
                    if(count($condicion)==2){
                        $tipoJoin=$condicion[0];
                        $condicionJoin=$condicion[1];
                        $this->datatable->db->join($t_join,$condicionJoin,$tipoJoin);
                    }
                }
                else {
                    $this->datatable->db->join($t_join, $condicion, 'left');
                }
            }
        }
		
        //CONDICION PRINCIPAL
        /*
         * CHAINS: Elimino esto por estar relacionado solo con presupuestos no puede estar presente en esta clase que es genérica
        $aceptado_pendiente=FALSE;
        $finalizado=FALSE;
        */
        if(isset($where) && $where != '') {
            foreach ($where as $campo => $valor) {
                /*
                  CHAINS: Elimino esto por estar relacionado solo con presupuestos no puede estar presente en esta clase que es genérica
                if($valor == "Aceptado pendiente"){
                    $aceptado_pendiente=TRUE;
                }elseif($valor == "Finalizado"){
                    $finalizado=TRUE;
                }else{
                    $campo = explode(' AS ', $campo);
                    $campo = end($campo);
                    $this->datatable->db->where($campo, $valor);
                }
                */
                $campo = explode(' AS ', $campo);
                $campo = end($campo);
                /* CHAINS 20240218 - Soporte para condiciones multiples en $where */
                if(is_array($valor)){
                    $this->datatable->db->group_start();
                    foreach($valor as $unvalor){
                        $this->datatable->db->or_where($campo,$unvalor);
                    }
                    $this->datatable->db->group_end();
                }
                else
                    $this->datatable->db->where($campo, $valor);
            }
        }
		
        //CONDICIONES EXTRA
        if(isset($iSearch) && $iSearch['value'] != '') {  
            $this->datatable->db->group_start(); 
            for($i=0; $i < count($aColumns); $i++) { 
                if(strpos($aColumns[$i], ".*") === false && strpos($aColumns[$i], "CASE") === false){
                    switch ($aColumns[$i]) {            
                        default:
                            //$columna_de_ordenacion = $aColumns[$i];
                            $columna_de_ordenacion = explode(' AS ', $aColumns[$i]); // $aColumns[$i];
                            break;
                    }
                    $columna_de_ordenacion = $columna_de_ordenacion[0];
                    if($i == 0){
                        $this->datatable->db->like($columna_de_ordenacion, $iSearch['value'], false);
                    }else{
                        $this->datatable->db->or_like($columna_de_ordenacion, $iSearch['value'], false);
                    }
                }else{
                    if($i == 0){
                        $i--;
                    }
                }
            }
            $this->datatable->db->group_end();
            $Data = $this->datatable->db->get();
        }else{
            $Data = $this->datatable->db->get();
        }
		
        $recordsFiltered = $Data->num_rows();
	
        //TERCERA CONSULTA PARA LOS RESULTADOS Y PAGINACIÓN
        // Ordering
        if(isset($iOrder)) {
            for($i=0; $i < count($iOrder); $i++) {
                switch ($aColumns[$iOrder[0]['column']]) {      
                    default:
                        $columna_de_ordenacion = explode(' AS ', $aColumns[$iOrder[0]['column']]);
                        break;
                }
                $columna_de_ordenacion = end($columna_de_ordenacion);
                $this->datatable->db->order_by($columna_de_ordenacion, strtoupper($iOrder[0]['dir']));
            }
        } else {
            $columna_de_ordenacion = explode(' AS ', $aColumns[0]);
            $columna_de_ordenacion = end($columna_de_ordenacion);
            $this->datatable->db->order_by($columna_de_ordenacion, 'ASC');
        }
		
        // Paging
        if(isset($iStart) && $iLength != '-1') {
            $this->datatable->db->limit($iLength, $iStart);
        } elseif(isset($iStart) && $iLength != '-1'){
            $this->datatable->db->limit($iLength, 1);
        }
        // $add_rule
        if(isset($add_rule) && ($add_rule != '')){
            foreach ($add_rule as $key => $value) {
               if(is_array($value)){
                    $this->datatable->db->$key($value[0], $value[1]);
                }else{
                    $this->datatable->db->$key($value);
                }
            }
        }
		
		
        // EL QUE
        $this->datatable->db->select($select);
		
        // DE DONDE
        $this->datatable->db->from($tabla);
        if((isset($join)) && ($join != '')){
            foreach ($join as $t_join => $condicion) {
                // CHAINS 20240206 - Si la condicion es un array en el primer elemento viene el tipo de join y en el segundo la condicion
                if(is_array($condicion)){
                    if(count($condicion)==2){
                        $tipoJoin=$condicion[0];
                        $condicionJoin=$condicion[1];
                        $this->datatable->db->join($t_join,$condicionJoin,$tipoJoin);
                    }
                }
                else {
                    $this->datatable->db->join($t_join, $condicion, 'left');
                }
            }
        }
		
        //CONDICION PRINCIPAL
        if(isset($where) && $where != '') {    
            foreach ($where as $campo => $valor){
                /*  CHAINS: Elimino esto por estar relacionado solo con presupuestos no puede estar presente en esta clase que es genérica
                if($valor=="Aceptado pendiente"||$valor=="Finalizado"){
                    $this->datatable->db->group_start();
                    $this->datatable->db->where('estado', 'Aceptado parcial');
                    $this->datatable->db->or_where('estado', 'Aceptado');
                    $this->datatable->db->group_end();
                }else{
                    $campo = explode(' AS ', $campo);
                    $campo = end($campo);
                    $this->datatable->db->where($campo, $valor);
                }
                */
                $campo = explode(' AS ', $campo);
                $campo = end($campo);
                /* CHAINS 20240218 - Soporte para condiciones multiples en $where */
                if(is_array($valor)){
                    $this->datatable->db->group_start();
                    foreach($valor as $unvalor){
                        $this->datatable->db->or_where($campo,$unvalor);
                    }
                    $this->datatable->db->group_end();
                }
                else
                    $this->datatable->db->where($campo, $valor);
            }
        } 
		
        //CONDICIONES EXTRA
        if(isset($iSearch) && $iSearch['value'] != '') {
            // 20240722 CHAINS: Mejorando busqueda por nombre sin importar el orden de nombre y apellido (soporta cambios Alfonso)
            $searchValue1=explode(" ",$iSearch['value']);
            $searchValue=[];
            foreach($searchValue1 as $ss){
                if(!empty($ss)) $searchValue[]=trim($ss);
            }
            for($j=0;$j<count($searchValue);$j++) {
                $ssearch=$searchValue[$j];
                $this->datatable->db->group_start();
                for ($i = 0; $i < count($aColumns); $i++) {
                    if (strpos($aColumns[$i], ".*") === false && strpos($aColumns[$i], "CASE") === false) {
                        switch ($aColumns[$i]) {
                            default:
                                //$columna_de_ordenacion = $aColumns[$i];
                                $columna_de_ordenacion = explode(' AS ', $aColumns[$i]); // $aColumns[$i];
                                break;
                        }
                        $columna_de_ordenacion = $columna_de_ordenacion[0];
                        if ($i == 0 && $j==0) {
                            $this->datatable->db->like($columna_de_ordenacion, $ssearch, false);
                        } else {
                            $this->datatable->db->or_like($columna_de_ordenacion, $ssearch, false);
                        }
                        /*
						// 20240722 CHAINS: ELIMINO ESTE CAMBIO - DE NUEVO ESTA ES UNA CLASE GENERICA NO PUEDEN INCLUIRSE CAMBIOS EN LOS QUE
						//  				SE HARCODEEN TABLAS PORQUE SE USA DESDE OTROS SITIOS DONDE NO SE UTILIZA LA TABLA DE CLIENTES
						
						// ES UNA CLASE GENERICA, HA DE PASARSE POR PARAMETROS TODO LO NECESARIO, SE USA DESDE SITIOS DONDE NO TIENE NADA QUE
						// VER LA TABLA CLIENTES
						
                        //Alfonso 2-7-2024 mejorando busqueda por nombre sin importar el orden del nombre y apellido
                        $busqueda = "1=1";
                        $palabras = explode(" ", $iSearch['value']);
                        $condiciones = array();
                        foreach ($palabras as $palabra) {
                            $condiciones[] = "CONCAT(TRIM(clientes.nombre), ' ', TRIM(clientes.apellidos), ' ', TRIM(clientes.telefono)) LIKE '%" . $palabra . "%'";
                        }
                        $busqueda .= " AND (" . implode(" AND ", $condiciones) . ")";
                        $this->datatable->db->where($busqueda);
                    }else{
                        $this->datatable->db->or_like($columna_de_ordenacion, $iSearch['value'],false);
                    }*/
                    } else {
                        if ($i == 0) {
                            $i--;
                        }
                    }
                }
                $this->datatable->db->group_end();
            }
            $Data = $this->datatable->db->get();
            //$recordsFiltered = $Data->num_rows();
        }else{
            $Data = $this->datatable->db->get();
        }
		
        // QUERY
       $last_query =  $this->datatable->db->last_query();

        // JSON enconding
        $Data=$Data->result();


        /*
         * CHAINS: Elimino esto por estar relacionado solo con presupuestos no puede estar presente en esta clase que es genérica

        //(Alfonso) filtros especiales referentes a estatus de los servicios

        //agregamos la cantidad de items pendientes a cualquier filtro
        $Data2=Array();
        foreach ($Data as &$row) {
            $row->numitems = $this->datatable->db->select('count(*) as total, estado')
                                    ->where('id_presupuesto',$row->id_presupuesto)
                                    ->where('aceptado',1)
                                    ->group_start()
                                        ->where('presupuestos_items.id_cita',0)
                                        ->or_where('estado','Programada')
                                    ->group_end()
                                    ->where('presupuestos_items.borrado',0)
                                    ->join('citas','presupuestos_items.id_cita=citas.id_cita','LEFT')
                                    ->group_by('id_presupuesto')
                                    ->get('presupuestos_items')->row()->total;
            if($aceptado_pendiente&&$row->numitems!=''){
                $Data2[]=$row;
            }elseif($finalizado&&$row->numitems==''){
                $Data2[]=$row;
            }elseif(!$aceptado_pendiente&&!$finalizado){
                $Data2[]=$row;
            }
        }
        $Data = $Data2;
        */




        $json = json_encode([
            "draw" => isset($iDraw) ? $iDraw : 1,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $Data,
           // "query" => $last_query
        ]);
        
        return $json;
    }
}