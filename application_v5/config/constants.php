<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Constantes generales
|--------------------------------------------------------------------------
|
*/
/*

if($_SERVER['HTTP_HOST'] == 'localhost'){

    define('RUTA_WWW', 'http://localhost/desarrollo.templodelmasaje.com');
    define('RUTA_CITAS_ONLINE', 'http://localhost/dev-clientes.templodelmasaje.com');
    define('TABLA_TICKETS', 'usuarios_tickets');
    define('RUTA_SERVIDOR', 'C:/webs/desarrollo.templodelmasaje.com');
    define('EMAIL_FROM', 'info@templodelmasaje.com');
    define('VALOR_TEMPLOS_EUROS', 6.0); // Valor de templos en euros.
    define('VALOR_MEDIO_TEMPLO_EUROS', 3.0); // Valor de templos en euros.

}elseif($_SERVER['HTTP_HOST'] == 'extranet.templodelmasaje.com'){

    define('RUTA_WWW', 'https://extranet.templodelmasaje.com');
    define('RUTA_CITAS_ONLINE', 'https://clientes.templodelmasaje.com');
    define('TABLA_TICKETS', 'usuarios_tickets');
    define('RUTA_SERVIDOR', '/var/www/vhosts/ciriloflorez.es/extranet.templodelmasaje.com');
    define('EMAIL_FROM', 'info@templodelmasaje.com');
    define('VALOR_TEMPLOS_EUROS', 6.0); // Valor de templos en euros.
    define('VALOR_MEDIO_TEMPLO_EUROS', 3.0); // Valor de templos en euros.

}else{

    define('RUTA_WWW', 'https://desarrollo.templodelmasaje.com');
    define('RUTA_CITAS_ONLINE', 'https://dev-clientes.templodelmasaje.com');
    define('TABLA_TICKETS', 'usuarios_tickets');
    define('RUTA_SERVIDOR', '/var/www/vhosts/ciriloflorez.es/desarrollo.templodelmasaje.com');
    define('EMAIL_FROM', 'info@templodelmasaje.com');
    define('VALOR_TEMPLOS_EUROS', 6.0); // Valor de templos en euros.
    define('VALOR_MEDIO_TEMPLO_EUROS', 3.0); // Valor de templos en euros.
    
}
*/

/* End of file constants.php */
/* Location: ./application/config/constants.php */
