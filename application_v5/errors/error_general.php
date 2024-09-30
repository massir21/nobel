<?php		
		$control = fopen(APPPATH."errors/errores.log","a+");
		
		if ($control == false) {
				die("No se ha podido crear el archivo de errores 1");
				exit;
		}
		else {
				$fecha = date("d-m-y H:i:s");
		
				fputs($control,$fecha." - Error en General: ");
				fputs($control,$heading);
				fputs($control," - Mensaje: ".$message."\n");        
				fclose($control);
		}		
?>

<script>vdocument.location.href="/errores/error.html";</script>