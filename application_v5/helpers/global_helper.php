<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

function site_name()
{
	$ci = &get_instance();
	return ($ci->config->item('site_name') != '') ? $ci->config->item('site_name') : '';
}

function site_title()
{
	$ci = &get_instance();
	return ($ci->config->item('site_name') != '') ? $ci->config->item('site_name') : '';
}


function euros($monto)
{
	return number_format($monto, 2, ",", ".") . '€';
}

function version()
{
	$CI = &get_instance();
	return  $CI->config->item('version');
}


function printr($data, $exit = '')
{
	echo '<pre>';
	print_r($data);
	if ($exit == '') {
		exit();
	}
}

function limpiar_string($string)
{
	$string = trim($string);
	// $string = utf8_encode($string);
	$string = str_replace(
		array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
		array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
		$string
	);

	$string = str_replace(
		array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
		array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
		$string
	);

	$string = str_replace(
		array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
		array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
		$string
	);

	$string = str_replace(
		array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
		array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
		$string
	);

	$string = str_replace(
		array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
		array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
		$string
	);

	$string = str_replace(
		array('ñ', 'Ñ', 'ç', 'Ç'),
		array('n', 'N', 'c', 'C'),
		$string
	);

	/*$string = str_replace(
        array("\\", "¨", "º", "-", "~",
            "#", "@", "|", "!", "\"",
            "·", "$", "%", "&", "/",
            "(", ")", "?", "'", "¡",
            "¿", "[", "^", "`", "]",
            "+", "}", "{", "¨", "´",
            ">", "< ", ";", ",", ":",
            "."),
        '',
        $string
    );*/

	$string = str_replace(
		array(
			"¨", "º", "~",
			"#", "|", "!",
			"·", "$", "%", "&",
			"(", ")", "?", "'", "¡",
			"¿", "[", "^", "`", "]",
			"+", "}", "{", "¨", "´",
			">", "< ", ";", ",", ":"
		),
		'',
		$string
	);
	return $string;
}

function rand_alphanumeric($length, $upper = '')
{
	if ($length > 0) {
		$rand_id = "";
		for ($i = 1; $i <= $length; $i++) {
			//mt_srand((float) microtime() * 1000000);
			$num = mt_rand(1, 36);
			$rand_id .= assign_rand_value($num);
		}
	}
	if ($upper != '') {
		$rand_id = strtoupper($rand_id);
	}
	return $rand_id;
}

function assign_rand_value($num)
{
	// accepts 1 - 36
	switch ($num) {
		case "1":
			$rand_value = "a";
			break;
		case "2":
			$rand_value = "b";
			break;
		case "3":
			$rand_value = "c";
			break;
		case "4":
			$rand_value = "d";
			break;
		case "5":
			$rand_value = "e";
			break;
		case "6":
			$rand_value = "f";
			break;
		case "7":
			$rand_value = "g";
			break;
		case "8":
			$rand_value = "h";
			break;
		case "9":
			$rand_value = "i";
			break;
		case "10":
			$rand_value = "j";
			break;
		case "11":
			$rand_value = "k";
			break;
		case "12":
			$rand_value = "l";
			break;
		case "13":
			$rand_value = "m";
			break;
		case "14":
			$rand_value = "n";
			break;
		case "15":
			$rand_value = "o";
			break;
		case "16":
			$rand_value = "p";
			break;
		case "17":
			$rand_value = "q";
			break;
		case "18":
			$rand_value = "r";
			break;
		case "19":
			$rand_value = "s";
			break;
		case "20":
			$rand_value = "t";
			break;
		case "21":
			$rand_value = "u";
			break;
		case "22":
			$rand_value = "v";
			break;
		case "23":
			$rand_value = "w";
			break;
		case "24":
			$rand_value = "x";
			break;
		case "25":
			$rand_value = "y";
			break;
		case "26":
			$rand_value = "z";
			break;
		case "27":
			$rand_value = "0";
			break;
		case "28":
			$rand_value = "1";
			break;
		case "29":
			$rand_value = "2";
			break;
		case "30":
			$rand_value = "3";
			break;
		case "31":
			$rand_value = "4";
			break;
		case "32":
			$rand_value = "5";
			break;
		case "33":
			$rand_value = "6";
			break;
		case "34":
			$rand_value = "7";
			break;
		case "35":
			$rand_value = "8";
			break;
		case "36":
			$rand_value = "9";
			break;
	}
	return $rand_value;
}

function fechaES($fecha, $condia = false)
{
	$fecha     = substr($fecha, 0, 10);
	$numeroDia = date('d', strtotime($fecha));
	$dia       = date('l', strtotime($fecha));
	$mes       = date('F', strtotime($fecha));
	$anio      = date('Y', strtotime($fecha));
	$dias_ES   = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	$dias_EN   = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
	$nombredia = str_replace($dias_EN, $dias_ES, $dia);
	$meses_ES  = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$meses_EN  = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	$nombreMes = str_replace($meses_EN, $meses_ES, $mes);
	if ($condia != false) {
		return $nombredia . " " . $numeroDia . " de " . $nombreMes . " de " . $anio;
	} else {
		return $numeroDia . " de " . $nombreMes . " de " . $anio;
	}
}

function diaSemana($dia, $lang = 'spanish')
{
	$dias_ES   = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
	$dias_EN   = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
	if ($lang == 'spanish') {
		$nombredia = $dias_ES[$dia];
	} else {
		$nombredia = $dias_EN[$dia];
	}
	return $nombredia;
}

function mesletra($mes, $lang = 'spanish')
{
	$meses_ES  = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
	$meses_EN  = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	if ($lang == 'spanish') {
		$nombredia = $meses_ES[$mes - 1];
	} else {
		$nombredia = $meses_EN[$mes - 1];
	}
	return $nombredia;
}

function validUrl($str = '')
{
	$CI = &get_instance();
	if ($str == '') {
		$str = uri_string();
	}
	$stre = explode('/', $str);
	if (count($stre) > 1) {
		array_pop($stre);
		$str = implode('/', $stre);
	}
	if (!array_key_exists($str, $CI->router->routes)) {
		show_404();
		exit();
	}
}

function show($data = [])
{
	$CI = &get_instance();
	$data['error']    = (validation_errors()) ? validation_errors() : $CI->session->userdata('error');
	$data['info']     = $CI->session->userdata('info');
	$data['success']  = $CI->session->userdata('success');
	$data['error']     = $CI->session->userdata('error');
	$data['message']  = $CI->session->userdata('message');
	$CI->load->view('base', $data);
}


function registrar_accion($tipo, $string)
{
	$path = APPPATH . 'registros/' . $tipo;
	$ruta       = explode('/', $path);
	$deep       = count($ruta);
	$ruta_check = '';
	for ($i = 0; $i < $deep; $i++) {
		if ($ruta_check != '') {
			$ruta_check = $ruta_check . '/' . $ruta[$i];
		} else {
			$ruta_check = $ruta[$i];
		}
		if (!file_exists($ruta_check)) {
			mkdir($ruta_check, 0777, true);
		}
	}
	$file = fopen($path . '/' . date('Y-m-d') . '.csv', 'a+');
	fwrite($file, date('Y-m-d H:i:s') . ' -> ' . $string . "\n");
	fclose($file);
}

function is_ajax()
{
	$CI = &get_instance();
	$stream_clean = $CI->security->xss_clean($CI->input->raw_input_stream);
	$request = json_decode($stream_clean);
	if (isset($request) && is_array($request) && count($request) < 1) {
		show_404();
		die();
	} else {
		return $request;
	}
}

function getgender($name)
{
	/*$name = str_replace(' ', '', $name);
	$name = str_replace('%20', '', $name);
	$name= limpiar_string($name);
	$url = 'https://api.genderize.io?name='.$name;
	$url = file_get_contents($url);
	$response = json_decode($url);
	return ($response->gender == '') ? $name : $response->gender;*/

	return $name;
	
}

function validAjaxForm()
{
	$CI = &get_instance();
	if ($CI->form_validation->run() === false) {
		$response = [
			'error'            => 1,
			'error_validation' => $CI->form_validation->error_array(),
			'csrf'             => $CI->security->get_csrf_hash(),
		];
		echo json_encode($response);
		exit();
	}
}

function validApixForm($post, $params)
{
	$CI = &get_instance();
	foreach ($params as $field => $value) {
		if (isset($value[3])) {
			$CI->form_validation->set_rules($value[0], $value[1], $value[2], $value[3]);
		} else {
			$CI->form_validation->set_rules($value[0], $value[1], $value[2]);
		}
	}

	$CI->form_validation->set_data($post);
	if ($CI->form_validation->run() === false) {
		$msn = '';
		foreach ($CI->form_validation->error_array() as $key => $value) {
			$msn .=  $value . '<br>';
		}
		$status   = 400;
		$response = ['status' => $status, 'msg' => $msn];
		$CI->response($response, $status);
	}
}

function returnAjax($response)
{
	echo json_encode($response);
	exit();
}

function timezone($time)
{
	$dt = new DateTimeImmutable($time, new DateTimeZone('UTC'));
	return $dt->setTimezone(new DateTimeZone('Europe/Madrid'))->format('Y-m-d H:i:s');
}


function generate_uuid_v1()
{
	// Obtener el timestamp actual en 100 nanosegundos desde 15 de octubre de 1582
	$time = microtime(true) * 10000000 + 0x01b21dd213814000;
	// Convertir el timestamp en un número binario de 60 bits
	$time_low = pack('V', $time & 0xffffffff);
	$time_mid = pack('v', ($time >> 32) & 0xffff);
	$time_hi_and_version = pack('v', (($time >> 48) & 0x0fff) | 0x1000);
	// Obtener la dirección MAC del sistema
	$mac = hex2bin(str_replace(':', '', substr(exec('ifconfig'), 36, 17)));
	// Crear un número aleatorio de 14 bits
	$random = random_bytes(2);
	// Crear el UUID
	$uuid = bin2hex($time_low) . '-' . bin2hex($time_mid) . '-' . bin2hex($time_hi_and_version) . '-' . substr(bin2hex($random), 0, 4) . '-' . bin2hex($mac);
	return $uuid;
}

function generate_uuid_v3($name, $namespace)
{
	// Verificar que el namespace sea un UUID válido
	if (!is_uuid($namespace)) {
		throw new Exception('El namespace no es un UUID válido');
	}
	// Crear el UUID a partir del hash MD5 del namespace y el identificador de nombre
	$uuid = md5(pack('H*', $namespace) . $name);
	// Establecer la versión y la variante del UUID
	$uuid[12] = hexdec($uuid[12]) & 0x0f | 0x30; // versión 3 (basada en MD5)
	$uuid[16] = hexdec($uuid[16]) & 0x3f | 0x80; // variante de reserva
	// Formatear el UUID
	$uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
	return $uuid;
}

function generate_uuid_v4()
{
	$bytes = random_bytes(16);
	$bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40); // Versión 4
	$bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80); // Variante reservada
	return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
}

function compararFechas($a, $b)
{
	$fechaA = strtotime($a->fecha_rellamada);
	$fechaB = strtotime($b->fecha_rellamada);
	return $fechaA - $fechaB;
}

//*** Alfonso */
function show_array($array){
	echo "<pre>";print_r($array);echo "</pre>";
}
