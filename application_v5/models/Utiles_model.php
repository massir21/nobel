<?php

class Utiles_model extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function limpiar_caracteres_especiales($text) {
    $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
    $text = strtolower($text);
    $patron = array (
      // Espacios, puntos y comas por guion
      '/[\, ()-]+/' => '_',

      // Vocales
      '/&agrave;/' => 'a',
      '/&egrave;/' => 'e',
      '/&igrave;/' => 'i',
      '/&ograve;/' => 'o',
      '/&ugrave;/' => 'u',

      '/&aacute;/' => 'a',
      '/&eacute;/' => 'e',
      '/&iacute;/' => 'i',
      '/&oacute;/' => 'o',
      '/&uacute;/' => 'u',

      '/&acirc;/' => 'a',
      '/&ecirc;/' => 'e',
      '/&icirc;/' => 'i',
      '/&ocirc;/' => 'o',
      '/&ucirc;/' => 'u',

      '/&atilde;/' => 'a',
      '/&etilde;/' => 'e',
      '/&itilde;/' => 'i',
      '/&otilde;/' => 'o',
      '/&utilde;/' => 'u',

      '/&auml;/' => 'a',
      '/&euml;/' => 'e',
      '/&iuml;/' => 'i',
      '/&ouml;/' => 'o',
      '/&uuml;/' => 'u',

      '/&auml;/' => 'a',
      '/&euml;/' => 'e',
      '/&iuml;/' => 'i',
      '/&ouml;/' => 'o',
      '/&uuml;/' => 'u',

      // Otras letras y caracteres especiales
      '/&aring;/' => 'a',
      '/&ntilde;/' => 'n',

      // Agregar aqui mas caracteres si es necesario
    );

    $text = preg_replace(array_keys($patron),array_values($patron),$text);
    return $text;
  }

  function enviar_email($to,$from,$asunto,$mensaje) {

    // se revisa el sistema que esta en uso para decidir a que email se envian los correos.
    // si es localhost, se envian a tiobavie
    // si es en desarrollo, se envian a massir21
    // si no es ninguna de las opciones anteriores, se envia al emila que se recibe en las variables

    /*if(isset($_SERVER['HTTP_HOST'])){
      if($_SERVER['HTTP_HOST'] == 'localhost'){
        $to="tiobavie@hotmail.com"; 
      }else{
        $dominio = $_SERVER['HTTP_HOST'];
        $domain_array = explode('.', $dominio);
        if($domain_array[0] == 'desarrollo'){
          $to="massir21@hotmail.com"; 
        }
      }
    }*/

    $to = ($this->config->item('send_mail_to') != FALSE) ? $this->config->item('send_mail_to') : $to;

	$config = [
		'protocol'   => 'smtp',
		//'smtp_host' => 'ssl://serv318.controldeservidor.com',//    ' 'mail.templodelmasaje.com';
		'smtp_host' => 'ssl://templodelmasaje.loading.net',//    ' 'mail.templodelmasaje.com';
		'smtp_port' =>  '465',
		'smtp_user'  => 'diario@clinicadentalnobel.es', //    'pedidos@templodelmasaje.com';
		'smtp_pass'  => 'O195q3#zr',
		'mailtype' => 'html',
		'charset_email' =>  'utf-8',
		'bcc_batch_mode' =>  '',
		'newline' =>  "\r\n",
		'crlf' =>  "\r\n",
		'validation' => TRUE
	];


    $this->load->library('email',$config);
    $this->email->from($from, 'Clínica Dental Nobel');
    $this->email->to($to);
    $this->email->subject($asunto);
    $this->email->set_mailtype("html");
    $this->email->message($mensaje);

    // Ready to send email and check whether the email was successfully sent
    if (!$this->email->send()) {
      // Raise error message
      show_error($this->email->print_debugger());
      exit;

      return 0;
    }
    else {
      // Show success notification or other things here
      return 1;
    }
  }

  function leer_paises($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    // ... Si leemos un registro concreto
    if (isset($parametros['id_pais'])) {
      $busqueda.=" AND paises.id_pais = @id_pais ";
    }

    $sentencia_sql="SELECT id_pais,nombre_pais FROM paises WHERE 1=1 ".$busqueda." ORDER BY nombre_pais ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function leer_provincias($parametros) {
    $AqConexion_model = new AqConexion_model();

    $busqueda="";

    if (isset($parametros['id_provincia'])) {
                    $busqueda.=" AND provincias.id_provincia = @id_provincia ";
    }

    if (isset($parametros['id_pais'])) {
                    $busqueda.=" AND provincias.id_pais = @id_pais ";
    }

    $sentencia_sql="SELECT id_provincia,id_pais,nombre_provincia
    FROM provincias WHERE 1=1 ".$busqueda." ORDER BY nombre_provincia ";

    $datos = $AqConexion_model->select($sentencia_sql,$parametros);

    return $datos;
  }

  function pais_ip($ip) {
    include(RUTA_SERVIDOR."/recursos/geoip.inc");

    $gi = geoip_open(RUTA_SERVIDOR."/recursos/GeoIP.dat",GEOIP_STANDARD);

    $datos['codigo']=geoip_country_code_by_addr($gi, $ip);
    $datos['nombre_pais']=geoip_country_name_by_addr($gi, $ip);

    geoip_close($gi);

    return $datos;
  }

  function fecha_completa($fecha) {
    $f = new DateTime($fecha);

    $fecha_completa=$f->format('l, d M Y');

    $fecha_completa = str_replace('Monday','Lunes',$fecha_completa);
    $fecha_completa = str_replace('Tuesday','Martes',$fecha_completa);
    $fecha_completa = str_replace('Wednesday','Miércoles',$fecha_completa);
    $fecha_completa = str_replace('Thursday','Jueves',$fecha_completa);
    $fecha_completa = str_replace('Friday','Viernes',$fecha_completa);
    $fecha_completa = str_replace('Saturday','Sábado',$fecha_completa);
    $fecha_completa = str_replace('Sunday','Domingo',$fecha_completa);

    $fecha_completa = str_replace('Jan','Ene',$fecha_completa);
    $fecha_completa = str_replace('Apr','Abr',$fecha_completa);
    $fecha_completa = str_replace('Aug','Ago',$fecha_completa);
    $fecha_completa = str_replace('Dec','Dic',$fecha_completa);

    return $fecha_completa;
  }

}
?>
