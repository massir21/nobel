<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Europe/Madrid');
ini_set('memory_limit', '2048M');
set_time_limit(0);
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Importacionodon extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	//ALTER TABLE `clientes` ADD `bucalis` INT(11) NOT NULL AFTER `codigo_cliente`;
	// http://extranet.clinicadentalnobel.es:8111/Importacionodon/
	function index()
	{
	}



	public function pacientes()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(0);
		$file_name 	= FCPATH . 'Gesrivas/Pacientes.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);

		foreach ($worksheet_arr as $row) {
			if ($row[32] == 'm' || $row[32] == 'M' || $row[32] == 'h' || $row[32] == 'H') {
				$sexo = ($row[32] == 'm' || $row[32] == 'M') ? 'mujer' : 'hombre';
			} else {
				$sexo = '';
			}
			if ($row[18] != '') {
				$fec = explode('/', $row[18]);
				$fecha_nacimiento = "$fec[2]-$fec[0]-$fec[1]";
			} else {
				$fecha_nacimiento = '0000-00-00';
			}

			if ($row[38] != '') {
				list($mes, $dia, $ano) = explode('/', $row[38]);
				$fecha_creacion = "$ano-$mes-$dia";
			} else {
				$fecha_creacion = date('Y-m-d');
			}
			$telefono = '';
			if($row[14] != ''){
				$telefono = str_replace(' ', '', $row[14]);
			}else{
				if($row[12] != ''){
					$telefono = str_replace(' ', '', $row[12]);
				};
			};
			
			$registro['id_cliente'] = $row[0];
			$registro['codigo_cliente'] = $row[3];
			$registro['nombre'] = $row[6];
			$registro['apellidos'] = $row[7];
			$registro['sexo'] = $sexo;
			$registro['fecha_nacimiento'] = $fecha_nacimiento;
			$registro['email'] = (filter_var($row[16], FILTER_VALIDATE_EMAIL)) ? $row[16] : '';
			$registro['telefono'] = $telefono;
			$registro['direccion'] = $row[9];
			$registro['codigo_postal'] = $row[11];
			if ($row[8] != '') {
				$registro['dni'] = $row[8];
			}
			$registro['no_quiere_publicidad'] = ($row[41] == 'FALSO') ? 1 : 0;
			$registro['recordatorio_email'] = ($row[64] == 'FALSO') ? 0 : 1;
			$registro['recordatorio_sms'] = ($row[42] == 'FALSO') ? 0 : 1;
			if ($row[37] != '') {
				$registro['notas'] = $row[37];
			}
			$registro['fecha_creacion'] = $fecha_creacion;
			if ($row[19] != '') {
				$registro['bucalis'] = $row[19];
			}
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['id_usuario_modificacion'] = $this->session->userdata('id_usuario');
			$registro['borrado'] = 0;
			$registro['activo'] = 1;
			//printr($registro);

			$existe = $this->db->get_where('clientes', ['id_cliente' => $registro['id_cliente']]);
			if ($existe->num_rows() > 0) {
				$id_cliente = $registro['id_cliente'];
				unset($registro['id_cliente']);
				$this->db->where('id_cliente', $id_cliente);
				$this->db->update('clientes', $registro);
			} else {
				$this->db->insert('clientes', $registro);
			}
		}
	}

	function generarColorHexadecimal()
	{
		$r = mt_rand(0, 255);
		$g = mt_rand(0, 255);
		$b = mt_rand(0, 255);
		$r_hex = str_pad(dechex($r), 2, '0', STR_PAD_LEFT);
		$g_hex = str_pad(dechex($g), 2, '0', STR_PAD_LEFT);
		$b_hex = str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
		$color_hex = "#" . $r_hex . $g_hex . $b_hex;
		return $color_hex;
	}

	/*public function empleados()
	{
		$file_name 	= FCPATH . 'Gesrivas/Centros_TColabos.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		$user_centro = [];
		foreach ($worksheet_arr as $row) {
			$user_centro[$row[1]] = $row[0];
		}

		$file_name 	= FCPATH . 'Gesrivas/TColabos.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {

			$id_centro = (isset($user_centro[$row[0]])) ? $user_centro[$row[0]] : 0;
			$borrado = ($row[20] == 'S') ? 0 : 1;
			$email = 'cnobel';
			switch ($id_centro) {
				case 1:
					$email .= 'central.es';
					break;
				case 2:
					$email .= 'fuenlabrada.es';
					break;
				case 3:
					$email .= 'rivas2.es';
					break;
				case 3:
					$email .= 'rivas1.es';
					break;
				default:
					$email .= '.es';
					break;
			};
			$nombre = $row[3];
			$apellidos = $row[4];
			$nombre_corto = strtolower(substr($nombre, 0, 2));
			if($apellidos != ''){
				$apellido_corto = strtolower(substr($apellidos, 0, 3));
			}else{
				$apellido_corto = '--';
			}
			
			$email = $nombre_corto . $apellido_corto . "@" . $email;
			$color = $this->generarColorHexadecimal();


			if ($row[23] != '') {
				list($mes, $dia, $ano) = explode('/', $row[23]);
				$fecha_creacion = "$ano-$mes-$dia";
			} else {
				$fecha_creacion = date('Y-m-d');
			}

			$registro['id_usuario'] = $row[0];
			$registro['nombre'] = $nombre;
			$registro['apellidos'] = $apellidos;
			$registro['email'] = $email;
			$registro['telefono'] = $row[13];
			if ($row[12] != '') {
				$registro['nif'] = $row[12];
			}
			if ($row[11] != '') {
				$registro['domicilio'] = $row[11];
			}
			$registro['color'] = $this->generarColorHexadecimal();
			$registro['id_centro'] = $id_centro;
			$registro['password'] = $row[1] . $row[2];
			$registro['horas_semana'] = 20;
			$registro['fecha_creacion'] = $fecha_creacion;
			$registro['id_usuario_creacion'] = 1;
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['id_usuario_modificacion'] = 1;
			$registro['borrado'] = $borrado;

			$existe = $this->db->get_where('usuarios', ['id_usuario' => $registro['id_usuario']]);
			if ($existe->num_rows() > 0) {
				$id_usuario = $registro['id_usuario'];
				unset($registro['id_usuario']);
				$this->db->where('id_usuario', $id_usuario);
				$this->db->update('usuarios', $registro);
			} else {
				$this->db->insert('usuarios', $registro);
			}
			if (array_key_exists($row[0], $user_centro)) {
				// ... Guardamos el perfil
				unset($param);
				$param['id_usuario'] = $row[0];
				$param['id_perfil'] = 6;
				$this->Usuarios_model->nuevo_usuario_perfil($param);
			}
		}
	}*/

	public function servicios_familias()
	{
		$file_name 	= FCPATH . 'Gesrivas/TGrupos.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
			if ($row[10] != '') {
				list($mes, $dia, $ano) = explode('/', $row[10]);
				$fecha_creacion = "$ano-$mes-$dia";
			} else {
				$fecha_creacion = date('Y-m-d');
			}
			if ($row[12] != '') {
				list($mes, $dia, $ano) = explode('/', $row[12]);
				$fecha_modificacion = "$ano-$mes-$dia";
			} else {
				$fecha_modificacion = date('Y-m-d');
			}
			$id_familia_servicio = $row[0];
			$registro['id_familia_servicio'] = $id_familia_servicio;
			$registro['nombre_familia'] = $row[1];
			$registro['citas_online'] = "";
			$registro['fecha_creacion'] = $fecha_creacion;
			$registro['id_usuario_creacion'] = 1;
			$registro['fecha_modificacion'] = $fecha_modificacion;
			$registro['id_usuario_modificacion'] = 1;
			$registro['borrado'] = 0;


			$existe = $this->db->get_where('servicios_familias', ['id_familia_servicio' => $id_familia_servicio]);
			if ($existe->num_rows() > 0) {
				unset($registro['id_familia_servicio']);
				$this->db->where('id_familia_servicio', $id_familia_servicio);
				$this->db->update('servicios_familias', $registro);
			} else {
				$this->db->insert('servicios_familias', $registro);
			}
		}
	}

	public function servicios()
	{
		$file_name 	= FCPATH . 'Gesrivas/Tratamientos.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
			if ($row[29] != '') {
				list($mes, $dia, $ano) = explode('/', $row[29]);
				$fecha_creacion = "$ano-$mes-$dia";
			} else {
				$fecha_creacion = date('Y-m-d');
			}
			$id_servicio = $row[0];
			$registro['id_servicio'] = $id_servicio;
			$registro['nombre_servicio'] = $row[2];
			$registro['id_familia_servicio'] = $row[9];
			$registro['abreviatura'] = $row[1];
			$registro['pvp'] = 0;
			$registro['iva'] = 0;
			$registro['precio_proveedor'] = 0;
			$registro['templos'] = 0;
			$registro['duracion'] = 30;
			$registro['color'] = $this->generarColorHexadecimal();
			$registro['obsoleto'] = ($row[26] != '') ? 1 : 0;
			$registro['fecha_creacion'] = $fecha_creacion;
			$registro['id_usuario_creacion'] = 1;
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['id_usuario_modificacion'] = 1;
			$registro['borrado'] = 0;
			$existe = $this->db->get_where('servicios', ['id_servicio' => $id_servicio]);
			if ($existe->num_rows() > 0) {
				unset($registro['id_servicio']);
				$this->db->where('id_servicio', $id_servicio);
				$this->db->update('servicios', $registro);
			} else {
				$this->db->insert('servicios', $registro);
			}
		}
	}


	public function precios_servicios()
	{
		// buscamos todos los servicios
		$servicios = $this->db->get('servicios')->result();

		foreach ($servicios as $key => $s) {
			$idTratamientoTarifa = $s->id_servicio;
			$this->db->where('IdTratamiento', $idTratamientoTarifa);
			$this->db->where('inactivo', '');
			$this->db->order_by('IdTratamientoTarifa', 'DESC');
			$this->db->limit(1);
			$tarifa = $this->db->get('__tratamientos_tarifas')->result();
			if (empty($tarifa)) {
				echo 'No existe tarifa para el IdTratamiento ' . $idTratamientoTarifa . '<br>';
			} else {
				$tarifa = $tarifa[0];
				// se busca el precio de esa tarifa
				$idTratamientoTarifa = $tarifa->IdTratamientoTarifa;
				$this->db->where('IdTratamientoTarifa', $idTratamientoTarifa);
				$this->db->order_by('IdTratamientoTarifaPrecio', 'ASC');
				$this->db->limit(1);
				$precio = $this->db->get('__tratamientos_tarifas_precios')->result();
				if (empty($precio)) {
					echo 'No existe precio para el IdTratamientoTarifa ' . $idTratamientoTarifa . '<br>';
				} else {
					$precio = $precio[0];
					$this->db->where('id_servicio', $s->id_servicio);
					$this->db->update('servicios', ['pvp' => $precio->PrecioPrivado]);
				}
			}
		}
	}

	public function presupuestos()
	{
		$file_name 	= FCPATH . 'Gesrivas/Presu.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
			if ($row[14] != '') {
				list($mes, $dia, $ano) = explode('/', $row[14]);
				$fecha_creacion = "$ano-$mes-$dia";
			} else {
				$fecha_creacion = date('Y-m-d');
			}

			if ($row[15] != '') {
				$estado = 'Aceptado';
			} else {
				$estado = 'Rechazado';
			}

			$id_presupuesto = $row[18];
			$registro['id_presupuesto'] = $id_presupuesto;
			$registro['id_cliente'] = $row[0];
			$registro['id_doctor'] =  ($row[16] != '') ? $row[16] : 0;
			$registro['id_usuario'] = ($row[21] != '') ? $row[21] : 0;
			$registro['id_centro'] =  $row[22];
			$registro['nro_presupuesto'] = str_pad($id_presupuesto, 8, '0', STR_PAD_LEFT);
			$registro['fecha_validez'] = date("Y-m-d", strtotime($fecha_creacion . " +15 days"));
			$registro['estado'] = $estado;
			$registro['estado_relacionado'] = $row[27];
			$registro['dto_100'] = 0;
			$registro['totalpresupuesto'] = 0;

			$registro['n_cuotas'] = 0;
			$registro['apertura'] = 0;
			$registro['totalcuota'] = 0;

			$registro['fecha_creacion'] = $fecha_creacion;
			$registro['id_usuario_modificacion'] = 1;
			$registro['fecha_modificacion'] = date("Y-m-d H:i:s");
			$registro['borrado'] = 0;

			$existe = $this->db->get_where('presupuestos', ['id_presupuesto' => $id_presupuesto]);
			if ($existe->num_rows() > 0) {
				unset($registro['id_presupuesto']);
				$this->db->where('id_presupuesto', $id_presupuesto);
				$this->db->update('presupuestos', $registro);
			} else {
				$this->db->insert('presupuestos', $registro);
			}
		}
	}

	public function presupuestos_item()
	{
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$file_name 	= FCPATH . 'Gesrivas/PresuTto.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		/*$reader->setReadDataOnly(true);
		$reader->setReadEmptyCells(false);*/
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
			if ($row[36] == '' && $row[19] != '') {
				if ($row[14] != '') {
					list($mes, $dia, $ano) = explode('/', $row[14]);
					$fecha_creacion = "$ano-$mes-$dia";
				} else {
					$fecha_creacion = date('Y-m-d');
				}

				if ($row[25] != '') {
					$aceptado = 1;
				} else {
					$aceptado = 0;
				}

				$coste = ($row[19] != '') ? $row[19] : 0;
				if ($row[20] != '' && $row[19] != '' && $row[20] > 0) {
					$dto_propio_euros = $row[19] * ($row[20] / 100);
					$coste = $row[19] - $dto_propio_euros;
				}
				$diente_explode = explode(' # ', $row[21]);
				if (count($diente_explode) > 1) {
					$diente = str_replace('_x000d_', '', $diente_explode[1]);
				} else {
					$diente = '';
				}
				$id_servicio = $this->getIdTratamientoTarifa_id_tratamiento($row[4]);
				$id_presupuesto_item = $row[32];
				$registro['id_presupuesto_item'] = $id_presupuesto_item;
				$registro['id_presupuesto'] = $row[39];
				$registro['tipo_item'] =  'Servicio';
				if ($id_servicio != '') {
					$registro['id_item'] =  $id_servicio;
				}
				$registro['cantidad'] = ($row[18] != '') ? $row[18] : 1;
				$registro['dientes'] = $diente;
				$registro['pvp'] = ($row[19] != '') ? $row[19] : 0;
				$registro['dto'] = ($row[20] != '') ? $row[20] : 0;
				$registro['coste'] = $coste;
				$registro['id_cliente'] = $row[0];
				$registro['aceptado'] = $aceptado;
				if ($row[16] != '') {
					$registro['id_usuario'] = $row[16];
				}
				$registro['fecha_creacion'] = $fecha_creacion;
				$registro['borrado'] = 0;

				$existe = $this->db->get_where('presupuestos_items', ['id_presupuesto_item' => $id_presupuesto_item]);
				if ($existe->num_rows() > 0) {
					unset($registro['id_presupuesto_item']);
					$this->db->where('id_presupuesto_item', $id_presupuesto_item);
					$this->db->update('presupuestos_items', $registro);
				} else {
					$this->db->insert('presupuestos_items', $registro);
				}
			}
		}
	}

	public function actualizar_presupuestos_con_items()
	{
		/// obtener todos los presupuestos
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$presupuestos = $this->db->get('presupuestos')->result();
		foreach ($presupuestos as $p => $presu) {
			$q = "SELECT SUM(pvp) AS tot_sin_des, sum(coste) AS total_aceptado, SUM(pvp) AS totalpresupuesto FROM presupuestos_items WHERE id_presupuesto = $presu->id_presupuesto";

			$totales = $this->db->query($q)->row();
			$actualizar = [
				'totalpresupuesto' => $totales->totalpresupuesto,
				'total_aceptado' => $totales->total_aceptado,
				'total_sin_descuento' => $totales->tot_sin_des
			];
			$this->db->where('id_presupuesto', $presu->id_presupuesto);
			$this->db->update('presupuestos', $actualizar);
		}
	}

	public function citas()
	{
		ini_set('memory_limit', '-1');
		set_time_limit(0);
		$file_name 	= FCPATH . 'Gesrivas/DCitas.xlsx';
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadsheet = $reader->load($file_name);
		$worksheet = $spreadsheet->getActiveSheet();
		$worksheet_arr = $worksheet->toArray();
		unset($worksheet_arr[0]);
		foreach ($worksheet_arr as $row) {
			if ($row[10] != '') {
				if ($row[19] != '') {
					list($mes, $dia, $ano) = explode('/', $row[19]);
					$fecha_creacion = "$ano-$mes-$dia";
				} else {
					$fecha_creacion = date('Y-m-d');
				}
				switch ($row[5]) {
					case 0:
						$estadocita = 'Programada';
						break;
					case 1:
						$estadocita = 'Anulada';
						break;
					case 5:
						$estadocita = 'Finalizado';
						break;
					case 6:
						$estadocita = 'No vino';
						break;
					default:
						$estadocita = '-';
						break;
				}

				$IdUsu = $row[0];
				$this->db->where('IdUsu', $IdUsu);
				$usuAgd = $this->db->get('__dbo_UsuAgd')->row();
				echo $this->db->last_query() . ' - ';
				if ($usuAgd->CodExt == '1as. Visitas') {
					$id_usuario_empleado  = 1;
				} else {
					$this->db->where('Alias', $usuAgd->CodExt);
					$vcolab = $this->db->get('__dbo_VColaboradores')->row();
					echo $this->db->last_query() . '<br>';
					if (isset($vcolab->IdCol)) {
						$id_usuario_empleado = $vcolab->IdCol;
					}
				}
				//$id_servicio = $this->getIdTratamientoTarifa_id_tratamiento($row[4]);
				$id_cita = $row[40];
				$registro['id_cita'] = $id_cita;
				$registro['id_usuario_empleado'] = (isset($id_usuario_empleado)) ? $id_usuario_empleado  : (($row[0] != 0) ? $row[0] : 1);
				$registro['id_cliente'] = $row[10];
				$registro['fecha_hora_inicio'] = $this->excelToDate($row[2], $row[3]);
				$registro['duracion'] = $row[4] / 60; // esta en segundos, pasamos a minutos
				$registro['estado'] = $estadocita;
				if ($row[16] != '') {
					$registro['observaciones'] = str_replace('_x000d_', '', $row[16]);
				}
				$registro['solo_este_empleado'] = 0;
				$registro['recordatorio_sms'] = 0;
				$registro['recordatorio_email'] = 0;
				//
				$registro['fecha_creacion'] = $fecha_creacion;
				$registro['id_usuario_creador'] = 1;
				$registro['fecha_modificacion'] = $fecha_creacion;
				$registro['id_usuario_modificacion'] = 1;
				$registro['borrado'] = 0;

				$existe = $this->db->get_where('citas', ['id_cita' => $id_cita]);
				if ($existe->num_rows() > 0) {
					unset($registro['id_cita']);
					$this->db->where('id_cita', $id_cita);
					$this->db->update('citas', $registro);
				} else {
					$this->db->insert('citas', $registro);
				}
			}
		}
	}


	function update_id_usuario_empeado_citas()
	{
		$this->db->select('__dbo_VColaboradores.IdCol AS id_usuario, __dbo_UsuAgd.IdUsu');
		$this->db->from('__dbo_UsuAgd');
		$this->db->join('__dbo_VColaboradores', '__dbo_VColaboradores.Alias = __dbo_UsuAgd.CodExt');
		$idus = $this->db->get()->result();

		foreach ($idus as $key => $usu) {
			$update = [
				'id_usuario_empleado' => $usu->id_usuario
			];
			$this->db->where('id_usuario_empleado', $usu->IdUsu);
			$this->db->update('citas', $update);
		}
	}



	private function getIdTratamientoTarifa_id_tratamiento($id_tratamiento_tarifa)
	{
		$this->db->select('IdTratamiento');
		$this->db->where('IdTratamientoTarifa', $id_tratamiento_tarifa);
		$tratam =  $this->db->get('__tratamientos_tarifas')->row();
		if (isset($tratam)) {
			return $tratam->IdTratamiento;
		} else {
			return 0;
		}
	}


	function excelToDate($excelDate, $horas)
	{

		$fecha = date('Y-m-d', strtotime('1899-12-30  + ' . $excelDate . ' days'));
		$fechahora = date('H:i:s', strtotime('00:00 + ' . $horas . ' seconds'));
		return $fecha . ' ' . $fechahora;
	}

	public function fecha($fecha, $hora)
	{

		echo  date('Y-m-d', strtotime('2023-09-13 - ' . $fecha . ' days')) . ' - ';
		echo $fecha . ' y ' . $hora . ' corresponde a la fecha ' . $this->excelToDate($fecha, $hora);
	}


	function ecxelcolumn()
	{

		for ($i = 0; $i < 100; $i++) {
			$letra = chr(65 + $i % 26); // 65 es el valor ASCII de 'A'

			if ($i > 25) {
				$letra = 'A' . $letra;
			}
			if ($i > 51) {
				$letra = 'A' . $letra;
			}
			echo "$letra =>  $i<br>";
		}
	}
}
