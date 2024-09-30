<?php

class Ticket_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function generar_ticket($id_usuario)
	{
		// ... En primer lugar generamos un numero aleatorio que sera parte del ticket.
		$seq = rand(9999, 999999);

		// ... El ticket esta compuesto por el id_usuario + un numero aleatorio, esto generara una complicada cadena complicada de descifrar.
		$nuevoticket = "$id_usuario$seq";

		// ... Vamos a Encriptar el ticket
		$nuevoticket_encriptado = $this->encriptar($nuevoticket);

		// ... Vamos a pasar el md5 al ticket sin encriptar
		$nuevoticket_md5ado = md5($nuevoticket);

		// ... Creamos la estructura de como queda el ticket.
		$ticket = $nuevoticket_encriptado . "-" . $nuevoticket_md5ado . "-" . $seq;

		// ... Borramos cada vez que se genera un nuevo ticket, todos aquellos menores de la (fecha de hoy -1), de esta forma vamos
		// depurando la tabla de tickets y no se queda llena de tickets basura. No importa que haga el delete cada vez que entra alguien
		// es muy rapido y sino hay nada que borrar no da fallo ni nada. Resto 1 dia a la fecha actual, porque asi no se puede producir el
		// problema de que un usuario entre a las 23:59 y otro a las 00:01 y este ultimo borre el ticket del primero, con la resta de ese dia
		// nunca se producira problemas.
		$AqConexion_model = new AqConexion_model();
		$sentencia_sql = "DELETE FROM " . TABLA_TICKETS . " WHERE DATE(fecha_creacion) < CURDATE() - 1";
		$resultado = $AqConexion_model->no_select($sentencia_sql, null);

		// Antes de insertar el ticket, lo borramos, no sea que ya exista uno igual, aunque esto sea improble.
		// No importa cuantos borra, ya s� que como m�ximo borrar� uno.
		$parametros['ticket'] = $ticket;
		$sentencia_sql = "DELETE FROM " . TABLA_TICKETS . " WHERE ticket = @ticket ";
		$resultado = $AqConexion_model->no_select($sentencia_sql, $parametros);

		// ... Insertamos el ticket del usuario.
		$parametros['id_usuario'] = $id_usuario;
		$parametros['fecha_creacion'] = date("Y-m-d H:i:s");
		$resultado = $AqConexion_model->insert(TABLA_TICKETS, $parametros);

		$this->recoger_ticket($ticket);

		return $ticket;
	}

	function recoger_ticket($ticket_completo)
	{
		// ... Tiempo m�ximo de inactividad (en minutos) tras el cual se le desconecta.
		$tiempo_caducidad = $this->caducidad_ticket();

		// ... El ticket_completo es lo que pasa de script en script. Son 3 campos separados por un |
		// El 1� de los campos es el ticket en s�.
		// El 2� es el ticket encriptado con MD5: encriptaci�n de un solo sentido.
		// El 3� es el numero aleatorio generado para pegar al ticket
		$ticket = "";
		$parte_md5 = "";
		$seq = "";
		if ($ticket_completo !== null) {
			$partes_ticket = explode("-", $ticket_completo);
			if (isset($partes_ticket[2])) {
				$ticket = $partes_ticket[0];
				$parte_md5 = $partes_ticket[1];
				$seq = $partes_ticket[2];
			}
		}

		// ... Desencriptamos lo que es el ticket en s�.
		$ticket_desencriptado = $this->desencriptar($ticket);

		// ... Vamos a ver si han toquetado en el ticket MD5ando el ticket y viendo si coincide con lo
		// que hay en el segundo campo del ticket (parte_md5).
		$ticket_desencriptado_md5ado = md5($ticket_desencriptado);
		if ($ticket_desencriptado_md5ado != $parte_md5) {
			// ... El ticket se ha manipulado.
			header("Location: " . RUTA_WWW . "/errores/error_ticket.html");
			exit;
		}

		$resultado = "";

		$AqConexion_model = new AqConexion_model();
		$parametros['tiempo_caducidad'] = $tiempo_caducidad;
		$parametros['ticket_completo'] = $ticket_completo;
		$sentencia_sql = "SELECT CASE WHEN (((EXTRACT(day from now()) - EXTRACT(day from fecha_creacion)) *24*60)+
				((EXTRACT(hour from now()) - EXTRACT(hour from fecha_creacion)) *60) + EXTRACT(minute from now()) -
	EXTRACT(minute from fecha_creacion)) > @tiempo_caducidad
				THEN 'caducado' ELSE 'correcto' END AS dif,id_usuario
				FROM usuarios_tickets WHERE ticket = @ticket_completo";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		if (isset($resultado[0]['dif']) && $resultado[0]['dif'] == "correcto") {
			$parametros_update['fecha_creacion'] = date("Y-m-d H:i:s");
			$where['ticket'] = $ticket_completo;
			$ok = $AqConexion_model->update(TABLA_TICKETS, $parametros_update, $where);

			unset($parametros_update);
			unset($where);
			$parametros_update['fecha_fin'] = date("Y-m-d H:i:s");
			$where['ticket'] = $ticket_completo;
			$ok = $AqConexion_model->update("usuarios_accesos", $parametros_update, $where);

			return $resultado[0]['id_usuario'];
		} else {
			$where_delete['ticket'] = $ticket_completo;
			$resultado = $AqConexion_model->delete(TABLA_TICKETS, $where_delete);

			//	... La sesi�n se ha caducado
			header("Location: " . RUTA_WWW . "/errores/error_ticket.html");

			return 0;
		}
	}

	private function caducidad_ticket()
	{
		return 600;
	}

	function borrar_ticket($parametros)
	{
		$AqConexion_model = new AqConexion_model();

		// ... Comprobamos si existe un ticket de foro para el usuario indicado.
		$sentencia_sql = "DELETE FROM usuarios_tickets WHERE id_usuario = @id_usuario ";
		$resultado = $AqConexion_model->no_select($sentencia_sql, $parametros);
	}

	function comprobar_usuario_validado($id_usuario)
	{
		// ... Tiempo de inactividad que no permite entrar a otro usuario
		$tiempo_caducidad = 5;

		$resultado = "";

		$AqConexion_model = new AqConexion_model();
		$parametros['tiempo_caducidad'] = $tiempo_caducidad;
		$parametros['id_usuario'] = $id_usuario;
		$sentencia_sql = "SELECT CASE WHEN (((EXTRACT(day from now()) - EXTRACT(day from fecha_creacion)) *24*60)+
							((EXTRACT(hour from now()) - EXTRACT(hour from fecha_creacion)) *60) + EXTRACT(minute from now()) -
				EXTRACT(minute from fecha_creacion)) > @tiempo_caducidad
							THEN 'caducado' ELSE 'correcto' END AS dif,id_usuario
							FROM usuarios_tickets WHERE id_usuario = @id_usuario and fecha_creacion = (select max(fecha_creacion) from usuarios_tickets)";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		if ($resultado > 0) {
			if ($resultado[0]['dif'] == "correcto") {
				return 0;
			} else {
				return 1;
			}
		} else {
			return 1;
		}
	}

	/*
    private function encriptar($ticket) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $key = "^Q-A!9m9u6o<?-L]Je+fcfKEna?D31@3";
        $text = $ticket;
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);

        $crypttext=base64_encode($crypttext);
        $crypttext=$this->strToHex($crypttext);

        return $crypttext;
    }
    */
	private function encriptar($ticket)
	{
		$this->load->library('encryption');
		return $this->encryption->encrypt($ticket);
	}

	/*
    private function desencriptar($ticket) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $key = "^Q-A!9m9u6o<?-L]Je+fcfKEna?D31@3";
        $strticket=$this->hexToStr($ticket);
        $text = base64_decode($strticket);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);

        return rtrim($decrypttext);
    }
    */
	private function desencriptar($ticket)
	{
		$this->load->library('encryption');
		return $this->encryption->decrypt($ticket);
	}

	private function strToHex($string)
	{
		$hex = '';
		for ($i = 0; $i < strlen($string); $i++) {
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}

	private function hexToStr($hex)
	{
		$string = '';
		for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
			$string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
		}
		return $string;
	}

	function generar_ticket_sala($parametros)
	{
		$AqConexion_model = new AqConexion_model();

		// ... Comprobamos si existe un ticket de sala para el usuario indicado.
		$sentencia_sql = "SELECT id FROM salas_accesos WHERE id_sala = @id_sala and id_usuario = @id_usuario and ticket = @ticket
				and id_congreso = @id_congreso ";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		// ... Si ya existe el ticket entonces actualizamos la fecha de finalizacion.
		if ($resultado > 0) {
			$sentencia_sql = "UPDATE salas_accesos SET fecha_fin = now() WHERE id_sala = @id_sala  and id_usuario = @id_usuario
						and ticket = @ticket and id_congreso = @id_congreso ";
			$ok = $AqConexion_model->no_select($sentencia_sql, $parametros);
		}
		// ... Creamos un nuevo ticket de sala
		else {
			$parametros['fecha_inicio'] = date("Y-m-d H:i:s");
			$parametros['fecha_fin'] = date("Y-m-d H:i:s");
			$ok = $AqConexion_model->insert("salas_accesos", $parametros);
		}
	}

	function cerrar_ticket_salas($parametros)
	{
		$AqConexion_model = new AqConexion_model();

		// ... Comprobamos si existe un ticket de sala para el usuario indicado.
		$sentencia_sql = "SELECT id FROM salas_accesos WHERE id_usuario = @id_usuario and ticket = @ticket
				and id_congreso = @id_congreso ";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		// ... Si ya existe el ticket entonces actualizamos la fecha de finalizacion.
		if ($resultado > 0) {
			$sentencia_sql = "UPDATE salas_accesos SET fecha_fin = now(),ticket=''
						WHERE id_usuario = @id_usuario and ticket = @ticket and id_congreso = @id_congreso ";
			$ok = $AqConexion_model->no_select($sentencia_sql, $parametros);
		}
	}

	function generar_ticket_foro($parametros)
	{
		$AqConexion_model = new AqConexion_model();

		// ... Comprobamos si existe un ticket de foro para el usuario indicado.
		$sentencia_sql = "SELECT id FROM foros_accesos WHERE id_usuario = @id_usuario and ticket = @ticket
				and id_congreso = @id_congreso ";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		// ... Si ya existe el ticket entonces actualizamos la fecha de finalizacion.
		if ($resultado > 0) {
			$sentencia_sql = "UPDATE foros_accesos SET fecha_fin = now() WHERE id_usuario = @id_usuario
						and ticket = @ticket and id_congreso = @id_congreso ";
			$ok = $AqConexion_model->no_select($sentencia_sql, $parametros);
		}
		// ... Creamos un nuevo ticket de foro
		else {
			$parametros['fecha_inicio'] = date("Y-m-d H:i:s");
			$parametros['fecha_fin'] = date("Y-m-d H:i:s");
			$ok = $AqConexion_model->insert("foros_accesos", $parametros);
		}
	}

	function cerrar_ticket_foros($parametros)
	{
		$AqConexion_model = new AqConexion_model();

		// ... Comprobamos si existe un ticket de foro para el usuario indicado.
		$sentencia_sql = "SELECT id FROM foros_accesos WHERE id_usuario = @id_usuario and ticket = @ticket
				and id_congreso = @id_congreso ";
		$resultado = $AqConexion_model->select($sentencia_sql, $parametros);

		// ... Si ya existe el ticket entonces actualizamos la fecha de finalizacion.
		if ($resultado > 0) {
			$sentencia_sql = "UPDATE foros_accesos SET fecha_fin = now(),ticket=''
						WHERE id_usuario = @id_usuario and ticket = @ticket and id_congreso = @id_congreso ";
			$ok = $AqConexion_model->no_select($sentencia_sql, $parametros);
		}
	}
}
