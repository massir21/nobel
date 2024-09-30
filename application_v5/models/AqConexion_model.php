<?php
class AqConexion_model extends CI_Model {
    function __construct() {
	parent::__construct();
    }

    function select($sentencia_sql,$parametros) {
	$sentencia_sql_preparada = $this->prepare($sentencia_sql,$parametros);
	
	$query_es = $this->db->query("SET lc_time_names = 'es_ES'");
	
	$query = $this->db->query($sentencia_sql_preparada);
    
        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        else {
            return 0;
        }
    }
    
    function no_select($sentencia_sql,$parametros) {
	$sentencia_sql_preparada = $this->prepare($sentencia_sql,$parametros);
	$query = $this->db->query($sentencia_sql_preparada);
        $this->log_query();
        return $query;        
    }
    
    function no_select_sinpreparar($sentencia_sql) {	
        $query = $this->db->query($sentencia_sql);  
        $this->log_query();      
        return $query;        
    }
    
    function insert($tabla,$parametros) {
        $query = $this->db->insert($tabla, $parametros);
        $this->log_query();
        return $query;
        
    }
    
    function update($tabla,$parametros,$where) {
        $query = $this->db->update($tabla, $parametros,$where);
        
        $this->log_query();
        return $query;
    }
    
    function delete($tabla,$parametros) {
        $query = $this->db->delete($tabla, $parametros);
        $this->log_query();
        return $query;
    }

    function vaciar($tabla) {
        $query = $this->db->empty_table($tabla);
        
        return $query;
    }
    
    // ... Prepara todos los parametros y la sentenciaSQL para la ejecución
    // escapando los parametros y remplazandolos en la sentencia.
    // Hay dos tipo de parametros los que empiezan por @loquesea, que se utilizará el escapado normal
    // o los parametros con son para busquedas con like, esos se indicaran como #loquesea
    private function prepare($sentencia_sql,$parametros) {
	if (isset($parametros)) {
	    if ($parametros != null) {	    
		foreach(array_keys($parametros) as $key) {		    
		    $sentencia_sql = str_replace("@".$key,$this->db->escape($parametros[$key]),$sentencia_sql);
		    $sentencia_sql = str_replace("#".$key,"'%".$this->db->escape_like_str($parametros[$key])."%'",$sentencia_sql);
		}		
	    }
	}

        return $sentencia_sql;
    }

    public function log_query(){
        if($this->config->item('conectar_a_master') == TRUE){
            $data = $this->db->last_query();
            $master = $this->load->database($this->config->item('db_master'), TRUE);
            $master->query($data);
        }  
    }
    
} 
?>