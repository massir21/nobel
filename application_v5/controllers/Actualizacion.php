<?php

class Actualizacion extends CI_Controller {
  function __construct() {
    parent::__construct();
  }

  /*
  function index() {    
    
    $this->add_notas_horario();
    $this->add_horas_semana();
    
  }


  function add_notas_horario(){
      $query = "ALTER TABLE `usuarios_horarios` ADD `notas_horario` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fecha_fin`;";
      $this->db->query($query);

  }

  function add_horas_semana(){
    $query = "ALTER TABLE `usuarios` ADD `horas_semana` DECIMAL(3,1) NOT NULL AFTER `id_centro`;";
    $this->db->query($query);

  }
  */


  function add_column_debug(){
    $query = "SELECT CONCAT('ALTER TABLE ', table_schema, '.', table_name, ' ADD COLUMN debug INT(1) NOT NULL DEFAULT 0;') AS query
    FROM information_schema.tables
    WHERE table_schema = '".$this->db->database."'
    AND table_type = 'base table';";
    $queries = $this->db->query($query)->result();

    foreach ($queries as $key => $q) {
      $this->db->query($q->query);
    }
  }
	
  
}

?>