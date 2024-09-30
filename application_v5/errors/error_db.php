<?php
	$control = fopen(APPPATH."errors/errores.log","a+");

	if ($control == false) {
		die("No se ha podido crear el archivo de errores.");
		exit;
	}
	else {
		$fecha = date("d-m-y H:i:s");
		fputs($control,$fecha." - Error en BBDD: ");
		fputs($control,$heading);
		fputs($control," - Mensaje: ".$message."\n");
		fclose($control);
  }

?>

<script>document.location.href="<?php echo base_url();?>errores/error_db.html";</script>
