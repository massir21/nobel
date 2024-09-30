<?php
  $control = fopen(APPPATH."errors/errores.log","a+");
  
  echo APPPATH."errors/errores.log<br>";
  
  echo dirname(__FILE__);
  
  if ($control == false) {
    die("No se ha podido crear el archivo de errores. 2");
    exit;
  }
  else {
    $fecha = date("d-m-y H:i:s");

    fputs($control,$fecha." - Error en PHP: ");
    fputs($control,$severity);
    fputs($control," - Mensaje: ".$message);
    fputs($control," - Fichero: ".$filepath);
    fputs($control," - L�nea: ".$line."\n");
    
    fclose($control);
  }
?>

<script>_document.location.href="/errores/error.html";</script>