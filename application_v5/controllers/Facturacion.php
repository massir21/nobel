<?php class Facturacion extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Facturacion_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	/*
	public function fecha_hasta_check()
	{
		$fecha_desde = $this->input->post('fecha_desde');
		$fecha_hasta = $this->input->post('fecha_hasta');
		if (($fecha_hasta > $fecha_desde) OR ($fecha_hasta === ""))
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('fecha_hasta_check', 'La fecha de vencimiento no puede ser anterior a la de inicio.');
			return false;
		}
	}

	function index()
	{
		$data = [
			'id_centro' => $this->input->post('id_centro'),
			'fecha_desde' => $this->input->post('fecha_desde'),
			'fecha_hasta' => $this->input->post('fecha_hasta'),
		];
		$parametros['vacio']="";
		$centros = $this->Intercentros_model->leer_centros_nombre($parametros);
		$data['centros'] = $centros;
		// ... Viewer con el contenido
		$data['content_view'] = $this->load->view('facturacion/index', $data, true);

		// ... Modulos del usuario
		$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
		$data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

		$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 56);
		if ($permiso)
		{
			$this->load->view($this->config->item('template_dir').'/master', $data);
		}
		else
		{
			header("Location: " . RUTA_WWW . "/errores/error_404.html");
			exit;
		}
	}

	function seleccion_pedidos(){
		
		$this->form_validation->set_rules('fecha_desde', 'Fecha de inicio', 'required');
		$this->form_validation->set_rules('fecha_hasta', 'Fecha de fin', 'required|callback_fecha_hasta_check');
		$this->form_validation->set_rules('id_centro', 'Centro', 'required');
		if ($this->form_validation->run()) {
			$fecha_desde = date('Y-m-d', strtotime($this->input->post('fecha_desde'))) . " 00:00:00";
			$fecha_hasta = date('Y-m-d', strtotime($this->input->post('fecha_hasta'))) . " 23:59:59";
			$id_centro = $this->input->post('id_centro');

			// Pedidos del centro entre fechas
			$parametros['id_centro'] 	= $id_centro;
			$parametros['fecha_desde'] 	= $fecha_desde;
			$parametros['fecha_hasta'] 	= $fecha_hasta;
			$pedidos = $this->Facturacion_model->get_pedidos($parametros);

			// datos del centro
			$centro = $this->db->get_where('centros', array('id_centro' => $id_centro))->row();
			// datos del centro para factura
			$datos_centro = explode('<br>', $centro->direccion_completa);
			$centro->cif = str_replace('C.I.F. n.', '', $datos_centro[2]);
			$centro->dir1 = $datos_centro[3];
			$centro->dir2 = $datos_centro[4];
			$centro->tel = str_replace('Tlf:', '', $datos_centro[5]);
			// datos de TDM para factura
			$tdm = (object)[
					'empresa' 	=> 'TEMPLO DEL MASAJE SL',
					'cif' 		=> 'B87654117',
					'dir1'		=> 'C/ CERÁMICA 17',
					'dir2'		=> '28224 MADRID',
					'tel'		=> '91 373 3193',
					'mail' 		=> 'info@templodelmasaje.com',
			];


			$data = [
				'id_centro' 	=> $id_centro,
				'fecha_desde' 	=> $fecha_desde,
				'fecha_hasta' 	=> $fecha_hasta,
				'pedidos' 		=> $pedidos,
				'centro'		=> $centro,
				'tdm' 			=> $tdm,
			];

			// ... Viewer con el contenido
			$data['content_view'] = $this->load->view('facturacion/tabla_pedidos', $data, true);

			// ... Modulos del usuario
			$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
			$data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);

			$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 56);
			if ($permiso)
			{
				$this->load->view($this->config->item('template_dir').'/master', $data);
			}
			else
			{
				header("Location: " . RUTA_WWW . "/errores/error_404.html");
				exit;
			}
		}
		else
		{	
			var_dump(validation_errors());exit();
			$this->session->set_flashdata('mensaje', 'Errores en la validación de datos');
			redirect('Facturacion', 'refresh');
		}
	}

	function mas_facturacion()
	{
		$this->form_validation->set_rules('fecha_desde', 'Fecha de inicio', 'required');
		$this->form_validation->set_rules('fecha_hasta', 'Fecha de fin', 'required|callback_fecha_hasta_check');
		$this->form_validation->set_rules('id_centro', 'Centro', 'required');

		if ($this->form_validation->run())
		{
			$fecha_desde = date('Y-m-d', strtotime($this->input->post('fecha_desde'))) . " 00:00:00";
			$fecha_hasta = date('Y-m-d', strtotime($this->input->post('fecha_hasta'))) . " 23:59:59";
			$id_centro = $this->input->post('id_centro');
			$pedidos = $this->input->post('id_pedido');
			$centro = $this->db->get_where('centros', array('id_centro' => $id_centro))->row();
			$subtotal = 0;

			// DATOS DEL CENTRO PARA MOSTRAR EN FACTURA
			$datos_centro = explode('<br>', $centro->direccion_completa);
			$centro->cif = str_replace('C.I.F. n.', '', $datos_centro[2]);
			$centro->dir1 = $datos_centro[3];
			$centro->dir2 = $datos_centro[4];
			$centro->tel = str_replace('Tlf:', '', $datos_centro[5]);

			// DATOS DE TDM PARA LA FACTURA
			$tdm = (object)[
				'empresa' 	=> 'TEMPLO DEL MASAJE SL',
				'cif' 		=> 'B87654117',
				'dir1'		=> 'C/ CERÁMICA 17',
				'dir2'		=> '28224 MADRID',
				'tel'		=> '91 373 3193',
				'mail' 		=> 'info@templodelmasaje.com',
			];

			$data = [
				'id_centro' 	=> $id_centro,
				'fecha_desde' 	=> $fecha_desde,
				'fecha_hasta' 	=> $fecha_hasta,
				'num_fact'		=> $this->input->post('num_fact'),
				'fecha_fact'	=> $this->input->post('fecha_fact'),
				'centro'		=> $centro,
				'tdm' 			=> $tdm,
			];

			//RECORRE LOS CAMPOS GENERADOS PARA VARIOS
			$des = $this->input->post('descripcion');
			if(count($des) > 1){
				$extra = [];
				$extra_total =0;
				for ($i = 1; $i < count($des); $i++) {
					if($this->input->post('descripcion')[$i] != ''){
						$ref = $this->input->post('ref')[$i];
						$descripcion = $this->input->post('descripcion')[$i];
						$cantidad  = $this->input->post('cantidad')[$i];
						$coste = $this->input->post('coste_u')[$i];
						$total = $cantidad * $coste;

						$extra[] = (object)[
							'ref' => $ref,
							'descripcion' => $descripcion,
							'cantidad' => $cantidad,
							'coste' => number_format($coste, 2, ",", "."),
							'total' => number_format($total, 2, ",", "."),
						];
						$extra_total += $total;
					}
				}
				$data['extra'] = (object)[
					'rows' => $extra,
					'total' => number_format($extra_total, 2, ",", "."),
				];
				$subtotal = $subtotal + $extra_total;
			}

			// PEDIDOS
			if((isset($pedidos)) && (is_array($pedidos))){
				$data['pedidos'] = $pedidos;
				$productos = [];
				$p_total = 0;
				foreach ($pedidos as $int => $id_pedido) {
					$productos_pedido = $this->Facturacion_model->productos_en_pedido($id_pedido);
					foreach ($productos_pedido as $key => $value) {
						$p_sin_iva = $value->precio_sin_iva;
						$cantidad = $value->cantidad;
						$p_subtotal = $p_sin_iva * $cantidad;

						$value->precio_sin_iva = number_format($p_sin_iva, 2, ",", ".");
						$value->subtotal = number_format($p_subtotal, 2, ",", ".");
						$value->descuento = number_format(0, 2, ",", ".");;
						$p_total += $p_subtotal;
						$productos[] = $value;
					}
				}
				$data['productos'] = (object)[
					'rows'		=>	$productos,
					'total'		=>	number_format($p_total, 2, ",", ".")
				];
				$subtotal = $subtotal + $p_total;
			}

			// CITAS ONLINE
			$parametros['id_centro']	=	$id_centro;
			$parametros['fecha_desde'] 	= 	$fecha_desde;
			$parametros['fecha_hasta'] 	= 	$fecha_hasta;
			$citas_online = $this->Facturacion_model->get_citas_online($parametros);
			if(count($citas_online) > 0){
				$citas_total_sin_iva = 0;
				$citas_total_con_iva = 0;
				foreach ($citas_online as $key => $value) {
						$precio_con_iva = $value->precio_con_iva;
						$precio_sin_iva = ($precio_con_iva / 1.21) * -1;
						$precio_comision =  ($precio_con_iva / 1.21) * 0.25;
						
						$value->comision = number_format($precio_comision, 2, ",", ".");
						$value->precio_sin_iva = number_format($precio_sin_iva, 2, ",", ".");

						$citas_total_sin_iva += $precio_sin_iva;
						$citas_total_con_iva += $precio_con_iva;
				}
				$comision = ($citas_total_sin_iva * 0.25) * -1;

				$data['citas'] = (object)[
					'rows'		=> $citas_online,
					'total' 	=> number_format($citas_total_sin_iva, 2, ",", "."),
					'comision'	=> number_format($comision, 2, ",", ".")
					//'total_citas_online'	=> ($citas_total_sin_iva - ($citas_total_sin_iva * 0.25)) * -1,
				];

				$subtotal = $subtotal + $citas_total_sin_iva;
				$subtotal = $subtotal + $comision;
			}

			// INTERCENTROS
			$param['vacio']="";
			$todos_los_centros = $this->Intercentros_model->leer_centros_nombre($param);
			$parametros['nombre_centro'] =  $centro->nombre_centro;
			$pagos_intercentro = $this->Facturacion_model->intercentros($parametros);
			//echo '<pre>';
	    		//print_r($pagos_intercentro);
	    		//exit();
			if(count($pagos_intercentro) > 0){
				$total_intercentros = 0;
	    		$total_comisiones = 0;
	    		foreach ($pagos_intercentro as $key => $value) {
	    			if ($value['total_sin_recargas']>0 && $value['total_sin_recargas']<$value['total'])
	    			{
	    				$value['total'] = $value['total_sin_recargas'];
	            	}

	    			if(($value['original_de'] != $centro->nombre_centro)) // son de otro centro
	    			{
	    				// $total_servicio = ($value['total'] / 1.21) * -1;
	    				$total_servicio = $value['total'] * -1;
	    			}
	    			else
	    			{
	    				// $total_servicio = $value['total'] / 1.21;
	    				$total_servicio = $value['total'];
	    			}

	    			$total = $value['total'];
	    			if(substr($value['codigo'], -5) == 'E_WEB')
	    			{
	    				$tipo = "Tipo E (10%)"; 
	    				$comision = 0.10;
	    			}
	    			elseif((substr($value['codigo'], -5) == 'A_WEB') OR (substr($value['codigo'], -2) == 'Ai'))
	    			{
	    				$comision = 0.10;
	    				$tipo = "Tipo A (10%)"; 
	    			}
	    			elseif((substr($value['codigo'], -5) == 'B_WEB') OR (substr($value['codigo'], -2) == 'Bi'))
	    			{
	    				$comision = 0.10;
	    				$tipo = "Tipo B (10%)"; 
	    			}
	    			elseif((substr($value['codigo'], -5) == 'C_WEB') OR (substr($value['codigo'], -2) == 'Ci'))
	    			{
	    				$comision = 0.07;
	    				$tipo = "Tipo C (7%)"; 
	    			}
	    			elseif((substr($value['codigo'], -5) == 'D_WEB') OR (substr($value['codigo'], -2) == 'Di'))
	    			{
	    				$comision = 0.05;
	    				$tipo = "Tipo D (5%)"; 
	    			}else{
	    				$comision = 0;
	    				$tipo = ""; 
	    			}

	    			$total_comision =  $total_servicio * $comision * -1;
	    			$carnet_intercentro = (object)[
	    				'fecha' 		=> $value['fecha'] .' '. $value['hora'],
	    				'servicio' 		=> $value['servicio'],
	    				'templos'		=> $value['templos'],
	    				'carnet' 		=> $value['codigo'],
	    				'carnet_tipo'	=> $tipo,
	    				'original_de'	=> $value['original_de'],
	    				'usado_en'		=> $value['usado_en'],
	    				'sin_iva'		=> $value['total'] / 1.21,
	    				'con_iva'		=> $value['total'],
	    				'total_comision'=> number_format($total_comision, 2, ",", "."),
	    				'tota_sin_recargas' => $value['total_sin_recargas'],
	    				'total_servicio'=> number_format($total_servicio, 2, ",", ".")
	    			];

	    			$c_u_rows[] = $carnet_intercentro;
	    			$total_intercentros += $total_servicio;
	    			$total_comisiones += $total_comision;
	    		}


		    	$data['intercentros'] = (object)[
		    		'rows' 		=> $c_u_rows,
					'total'		=> number_format($total_intercentros, 2, ",", "."),
					'comision'  => number_format($total_comisiones, 2, ",", ".")
				];
				$subtotal = $subtotal + $total_intercentros;
				$subtotal = $subtotal + $total_comisiones;
			}

    		// TOTALES
			$impuestos = $subtotal * 0.21;
			$total = $subtotal + $impuestos;
			$data['total_factura'] = [
					'bruto' => number_format($subtotal, 2, ",", "."),
					'IVA'	=> number_format($impuestos, 2, ",", "."),
					'total'	=> number_format($total, 2, ",", "."),
			];
			
			$data['data'] = $data;
			$this->session->set_userdata('data', $data);
			

			$data['content_view'] = $this->load->view('facturacion/mas_facturacion', $data, true);
			$param_modulos['id_perfil'] = $this->session->userdata('id_perfil');
			$data['modulos']            = $this->Usuarios_model->leer_modulos($param_modulos);
			$permiso = $this->Acceso_model->TienePermiso($data['modulos'], 56);
			if ($permiso)
			{
				$this->load->view($this->config->item('template_dir').'/master', $data);
			}
			else
			{
				header("Location: " . RUTA_WWW . "/errores/error_404.html");
				exit;
			}
		}
		else
		{
			var_dump(validation_errors());exit();
			$this->session->set_flashdata('mensaje', 'Errores en la validación de datos');
			redirect('Facturacion', 'refresh');
		}

	}

	function excel()
	{
		
		$data = $this->session->userdata('data');
		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Registros');

		$columnas = ['A','B','C','D','E','F','G','H'];
		$titulo = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 21
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			]
		];
		$titulo2 = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 12
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			]
		];
		$empresa1 = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => '000000'),
				'size' => 14
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			],
		];
		$empresa2 = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => '000000'),
				'size' => 14
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		];
		$empresa2_texto = [
			'font' => [
				'bold' => false,
				'color' => array('rgb' => '000000'),
				'size' => 10
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
		];
		$titulo_tabla = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 12
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '888888')
			]
		];
		$titulo_tabla_left = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 12
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '888888')
			],
			'borders' => [
		        'allborders' =>[
		            'style' => PHPExcel_Style_Border::BORDER_THICK,
		            'color' => array('rgb' => 'FFFFFF'),
		        ],
		    ],
		];
		$total_titulo = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 12
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => '888888')
			],
			'borders' => [
		        'allborders' =>[
		            'style' => PHPExcel_Style_Border::BORDER_THICK,
		            'color' => array('rgb' => 'FFFFFF'),
		         ],
		    ],
		];
		$total_factura_cantidad = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FFFFFF'),
				'size' => 18
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FF0000')
			]
		];
		$total_factura_titulo = [
			'font' => [
				'bold' => true,
				'color' => array('rgb' => 'FF0000'),
				'size' => 18
			],
			'alignment' => [
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			]
		];
		$texto_inferior = [
			'font' => [
				'bold' => false,
				'color' => array('rgb' => '888888'),
				'size' => 10
			],
			'alignment' => [
				'horizontal' 	=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' 		=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
			],
			'fill' =>[
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => array('rgb' => 'FFFFFF')
			],
			'borders' => [
		        'allborders' =>[
		            'style' => PHPExcel_Style_Border::BORDER_THICK,
		            'color' => array('rgb' => '888888'),
		        ],
		    ],
		];
		
		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
		$this->excel->getActiveSheet()->setTitle('Registros');
		// DImensiones de las tablas
		foreach ($columnas as $key => $value) {
			$this->excel->getActiveSheet()->getColumnDimension($value)->setWidth(15); 
		}
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(8);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
		// Primera linea, con el titluo
		$this->excel->setActiveSheetIndex(0)->mergeCells('A1:E1');
		$this->excel->getActiveSheet()->getStyle('A1')->applyFromArray($titulo);
		$this->excel->getActiveSheet()->setCellValue("A1", 'FACTURA: '.$data['centro']->nombre_centro);

		$this->excel->setActiveSheetIndex(0)->mergeCells('F1:G1');
		$this->excel->getActiveSheet()->getStyle('F1')->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("F1", 'fecha: '.$data['fecha_fact']);

		$this->excel->setActiveSheetIndex(0)->mergeCells('H1:J1');
		$this->excel->getActiveSheet()->getStyle('H1')->applyFromArray($titulo2);
		$this->excel->getActiveSheet()->setCellValue("H1", 'Factura: '.$data['num_fact']);
		// Datos de las empresas: Linea 3
		// unir lineas 3,4,5,6,8,9
		$merge_empresas = [3,4,5,6,8,9];
		foreach ($merge_empresas as $key => $value) {
			$this->excel->setActiveSheetIndex(0)->mergeCells('B'.$value.':E'.$value.'');
			$this->excel->setActiveSheetIndex(0)->mergeCells('F'.$value.':I'.$value.'');
		}

		$this->excel->getActiveSheet()->getStyle('B3')->applyFromArray($empresa1);
		$this->excel->getActiveSheet()->getStyle('F3')->applyFromArray($empresa2);

		// fijos
		$this->excel->getActiveSheet()->setCellValue("B3", $data['tdm']->empresa);
		$this->excel->getActiveSheet()->setCellValue("B4", $data['tdm']->cif);
		$this->excel->getActiveSheet()->setCellValue("B5", $data['tdm']->dir1);
		$this->excel->getActiveSheet()->setCellValue("B6", $data['tdm']->dir2);
		$this->excel->getActiveSheet()->setCellValue("B8", $data['tdm']->tel);
		$this->excel->getActiveSheet()->setCellValue("B9", $data['tdm']->mail);
		
		// del centro
		$this->excel->getActiveSheet()->getStyle("F4")->applyFromArray($empresa2_texto);
		$this->excel->getActiveSheet()->getStyle("F5")->applyFromArray($empresa2_texto);
		$this->excel->getActiveSheet()->getStyle("F6")->applyFromArray($empresa2_texto);
		$this->excel->getActiveSheet()->getStyle("F8")->applyFromArray($empresa2_texto);
		$this->excel->getActiveSheet()->getStyle("F9")->applyFromArray($empresa2_texto);

		$this->excel->getActiveSheet()->setCellValue("F3", $data['centro']->empresa);
		$this->excel->getActiveSheet()->setCellValue("F4", $data['centro']->cif);
		$this->excel->getActiveSheet()->setCellValue("F5", $data['centro']->dir1);
		$this->excel->getActiveSheet()->setCellValue("F6", $data['centro']->dir2);
		$this->excel->getActiveSheet()->setCellValue("F8", $data['centro']->tel);
		$this->excel->getActiveSheet()->setCellValue("F9", $data['centro']->email);
		
		$linea = 12;
		if(count($data['extra']->rows) > 0){
			//ponemos el titulo
			$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':J'.$linea.'');
			$this->excel->getActiveSheet()->getStyle('A'.$linea.'')->applyFromArray($titulo_tabla);
			$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', 'VARIOS');
			$linea ++;
			$this->excel->setActiveSheetIndex(0)->mergeCells('B'.$linea.':G'.$linea.'');
			$Cabeceras = [
				'A' => ['A'.$linea.':A'.$linea.'','Ref'],
				'B' => ['B'.$linea.':G'.$linea.'','Descripcion'],
				'H' => ['H'.$linea.':H'.$linea.'','Cantidad'],
				'I' => ['I'.$linea.':I'.$linea.'','Precio'],
				'J' => ['J'.$linea.':J'.$linea.'','Total']
			];
			foreach ($Cabeceras as $key => $value) {
				$columna = $value[0];
				$this->excel->setActiveSheetIndex(0)->mergeCells($columna);
				$this->excel->getActiveSheet()->getStyle($columna)->applyFromArray($titulo_tabla_left);
				$this->excel->getActiveSheet()->setCellValueExplicit($key.$linea, $value[1]);
			}
			$linea++;
			$variables = [
				'A' => 'ref',
				'B' => 'descripcion',
				'H' => 'cantidad',
				'I' => 'coste',
				'J' => 'total'
			];
			foreach ($data['extra']->rows as $k => $row) {
				$this->excel->setActiveSheetIndex(0)->mergeCells('B'.$linea.':G'.$linea.'');
				foreach ($variables as $key => $value) {
					$columna = $key.$linea;
					if(($key == 'J') OR ($key == 'I') OR ($key == 'H')){
						$row->$value = str_replace('.', '', $row->$value);
						$row->$value = str_replace(',', '.', $row->$value);
						//$this->excel->getActiveSheet()->setCellValueExplicit($columna, $numero,PHPExcel_Cell_DataType::TYPE_NUMERIC);
					}
					$this->excel->getActiveSheet()->setCellValue($columna, $row->$value);
				}
				$linea ++;
			}
			$linea ++;
		}

		if(!empty($data['productos']->rows)){
			$linea ++;
			//ponemos el titulo
			$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':J'.$linea.'');
			$this->excel->getActiveSheet()->getStyle('A'.$linea.'')->applyFromArray($titulo_tabla);
			$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', 'PRODUCTOS');
			$linea ++;
			$cabeceras = [
				'A' => ['A'.$linea.':A'.$linea.'','Ref'],
				'B' => ['B'.$linea.':C'.$linea.'','Familia'],
				'D' => ['D'.$linea.':F'.$linea.'','Producto'],
				'G' => ['G'.$linea.':G'.$linea.'','%dto'],
				'H' => ['H'.$linea.':H'.$linea.'','Cantidad'],
				'I' => ['I'.$linea.':I'.$linea.'','Precio'],
				'J' => ['J'.$linea.':J'.$linea.'','Total'],
			];
			foreach ($cabeceras as $key => $value) {
				$columna = $value[0];
				$this->excel->setActiveSheetIndex(0)->mergeCells($columna);
				$this->excel->getActiveSheet()->getStyle($columna)->applyFromArray($titulo_tabla_left);
				$this->excel->getActiveSheet()->setCellValueExplicit($key.$linea, $value[1]);
			}
			$linea++;
			$variables = [
				'A' => 'ref',
				'B' => 'familia',
				'D' => 'producto',
				'G'	=> 'descuento',
				'H' => 'cantidad',
				'I' => 'precio_sin_iva',
				'J' => 'subtotal'
			];
			foreach ($data['productos']->rows as $k => $row) {
				$this->excel->setActiveSheetIndex(0)->mergeCells('B'.$linea.':C'.$linea.'');
				$this->excel->setActiveSheetIndex(0)->mergeCells('D'.$linea.':F'.$linea.'');
				foreach ($variables as $key => $value) {
					$columna = $key.$linea;
					if(($key == 'J') OR ($key == 'I') OR ($key == 'H') OR ($key == 'G')) {
						$row->$value = str_replace('.', '', $row->$value);
						$row->$value = str_replace(',', '.', $row->$value);
						$this->excel->getActiveSheet()->getStyle($columna)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
						//$this->excel->getActiveSheet()->setCellValueExplicit($columna, $numero,PHPExcel_Cell_DataType::TYPE_NUMERIC);
					}
					$this->excel->getActiveSheet()->setCellValue($columna, $row->$value);
				}
				$linea ++;
			}
			$linea ++;
		}

		if(!empty($data['citas']->rows)){
			$linea ++;
			//ponemos el titulo
			$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':J'.$linea.'');
			$this->excel->getActiveSheet()->getStyle('A'.$linea.'')->applyFromArray($titulo_tabla);
			$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', 'CITAS ONLINE');
			$linea ++;
			$cabeceras = [
				'A' => ['A'.$linea.':B'.$linea.'', 'Fecha'],
				'C' => ['C'.$linea.':D'.$linea.'', 'Servicio'],
				'E' => ['E'.$linea.':F'.$linea.'', 'Cliente'],
				'G' => ['G'.$linea.':H'.$linea.'', 'Empleado'],
				'I' => ['I'.$linea.':I'.$linea.'', 'Comisión'],
				'J' => ['J'.$linea.':J'.$linea.'', 'Precio'],
			];
			foreach ($cabeceras as $key => $value) {
				$columna = $value[0];
				$this->excel->setActiveSheetIndex(0)->mergeCells($columna);
				$this->excel->getActiveSheet()->getStyle($columna)->applyFromArray($titulo_tabla_left);
				$this->excel->getActiveSheet()->setCellValueExplicit($key.$linea, $value[1]);
			}
			$linea++;
			$variables = [
				'A' => 'fecha',
				'C' => 'servicio',
				'E' => 'cliente',
				'G'	=> 'empleado',
				'I' => 'comision',
				'J' => 'precio_sin_iva',
			];
			foreach ($data['citas']->rows as $k => $row) {
				$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':B'.$linea.'');
				$this->excel->setActiveSheetIndex(0)->mergeCells('C'.$linea.':D'.$linea.'');
				$this->excel->setActiveSheetIndex(0)->mergeCells('E'.$linea.':F'.$linea.'');
				$this->excel->setActiveSheetIndex(0)->mergeCells('G'.$linea.':H'.$linea.'');
				foreach ($variables as $key => $value) {
					$columna = $key.$linea;
					if(($key == 'J') OR ($key == 'I')) {
						$row->$value = str_replace('.', '', $row->$value);
						$row->$value = str_replace(',', '.', $row->$value);
						//$this->excel->getActiveSheet()->setCellValueExplicit($columna, $numero,PHPExcel_Cell_DataType::TYPE_NUMERIC);
					}
					$this->excel->getActiveSheet()->setCellValue($columna, $row->$value);
				}
				$linea ++;
			}
			$linea ++;
		}

		if(!empty($data['intercentros']->rows)){
			$linea ++;
			//ponemos el titulo
			$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':J'.$linea.'');
			$this->excel->getActiveSheet()->getStyle('A'.$linea.'')->applyFromArray($titulo_tabla);
			$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', 'PAGOS INTERCENTROS');
			$linea ++;
            $cabeceras = [
				'A' => ['A'.$linea.':B'.$linea.'','Fecha'],
				'C' => ['C'.$linea.':C'.$linea.'','Servicio'],
				'D'	=> ['D'.$linea.':D'.$linea.'','Templos'],
				'E' => ['E'.$linea.':E'.$linea.'','Carnet'],
				'F' => ['F'.$linea.':F'.$linea.'','Tipo'],
				'G' => ['G'.$linea.':G'.$linea.'','Original'],
				'H' => ['H'.$linea.':H'.$linea.'','Usado'],
				'I' => ['I'.$linea.':I'.$linea.'','Comisión'],
				'J' => ['J'.$linea.':J'.$linea.'','Precio'],
			];
			foreach ($cabeceras as $key => $value) {
				$columna = $value[0];
				$this->excel->setActiveSheetIndex(0)->mergeCells($columna);
				$this->excel->getActiveSheet()->getStyle($columna)->applyFromArray($titulo_tabla_left);
				$this->excel->getActiveSheet()->setCellValueExplicit($key.$linea, $value[1]);
			}
			$linea++;
			$variables = [
				'A' => 'fecha',
				'C' => 'servicio',
				'D' => 'templos',
				'E' => 'carnet',
				'F'	=> 'carnet_tipo',
				'G' => 'original_de',
				'H' => 'usado_en',
				'I' => 'total_comision',
				'J' => 'total_servicio',
			];
			foreach ($data['intercentros']->rows as $k => $row) {
				foreach ($variables as $key => $value) {
					$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':B'.$linea.'');
					$columna = $key.$linea;
					if(($key == 'J') OR ($key == 'I')) {
						$row->$value = str_replace('.', '', $row->$value);
						$row->$value = str_replace(',', '.', $row->$value);
						//$this->excel->getActiveSheet()->setCellValueExplicit($columna, $numero,PHPExcel_Cell_DataType::TYPE_NUMERIC);
					}
					$this->excel->getActiveSheet()->setCellValue($columna, $row->$value);
				}
				$linea ++;
			}
			$linea ++;
		}

		$totales = [];
		if(isset($data['extra']->total)){
			$totales['TOTAL VARIOS'] = $data['extra']->total;
		}

		if(isset($data['productos']->total)){
			$totales['TOTAL PRODUCTOS'] =$data['productos']->total;
		}

		if(isset($data['citas']->total)){
			$totales['TOTAL CITAS ONLINE'] = $data['citas']->total;
		}

		if(isset($data['citas']->comision)){
			$totales['TOTAL COMISIÓN CITAS ONLINE'] = $data['citas']->comision;
		}

		if(isset($data['intercentros']->total)){
			$totales['TOTAL INTERCENTROS'] = $data['intercentros']->total;
		}

		if(isset($data['citas']->comision)){
			$totales['TOTAL COMISIÓN INTERCENTROS'] = $data['intercentros']->comision;
		}

		if(count($totales) > 0){
			foreach ($totales as $key => $value) {
				$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':I'.$linea.'');
				$this->excel->getActiveSheet()->getStyle('A'.$linea.':I'.$linea.'')->applyFromArray($total_titulo);
				$this->excel->getActiveSheet()->getStyle('J'.$linea)->applyFromArray($total_titulo);
				$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', $key);
				$value = str_replace('.', '', $value);
				$value = str_replace(',', '.', $value);
				$this->excel->getActiveSheet()->setCellValue('J'.$linea.'', $value);
				$linea ++;
			}
		}

		$linea++;
		$total_factura = [
			'SUBTOTAL' 	=> $data['total_factura']['bruto'],
			'21% IVA'	=> $data['total_factura']['IVA'],
			'TOTAL'		=>$data['total_factura']['total']
		];

		foreach ($total_factura as $key => $value) {
			if($key == 'TOTAL'){
				$estilo1 = $total_factura_titulo;
				$estilo2 = $total_factura_cantidad;
			}else{
				$estilo1 = $total_titulo;
				$estilo2 = $total_titulo;
			}
			$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':H'.$linea.'');
			$this->excel->setActiveSheetIndex(0)->mergeCells('I'.$linea.':J'.$linea.'');
			$this->excel->getActiveSheet()->getStyle('A'.$linea.':H'.$linea.'')->applyFromArray($estilo1);
			$this->excel->getActiveSheet()->getStyle('I'.$linea.':J'.$linea.'')->applyFromArray($estilo2);
			$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', $key);
			$value = str_replace('.', '', $value);
			$value = str_replace(',', '.', $value);
			$this->excel->getActiveSheet()->setCellValue('I'.$linea.'', $value);
			$linea++;
		}

		$linea++;

		$linea2 = $linea + 8;
		$texto = "Templo del Masaje S.L. es el Responsable del tratamiento de los datos personales del Interesado y le informa que estos datos serán tratados de conformidad con lo dispuesto en las normativas vigentes en protección de datos personales, el Reglamento (UE) 2016/679 y la Ley Orgánica 15/1999, con la finalidad de prestación de servicios profesionales y comunicación sobre productos y servicios. Los datos se conservarán mientras exista un interés mutuo para la finalidad descrita. Así mismo, se cederán los datos para cumplir con la finalidad del tratamiento y con las obligaciones legales que pudieran derivarse de la relación contractual. El Interesado puede ejercer sus derechos de acceso, rectificación, portabilidad y supresión de sus datos y a la limitación u oposición a su tratamiento dirigiendo un escrito a: info@templodelmasaje.com";
		$this->excel->setActiveSheetIndex(0)->mergeCells('A'.$linea.':J'.$linea2.'');
		$this->excel->getActiveSheet()->getStyle('A'.$linea.':J'.$linea2.'')->getAlignment()->setWrapText(true);
		$this->excel->getActiveSheet()->getStyle('A'.$linea.':J'.$linea2.'')->applyFromArray($texto_inferior);
		$this->excel->getActiveSheet()->setCellValue('A'.$linea.'', $texto);

		//Le ponemos un nombre al archivo que se va a generar.
		$archivo = "pruebatemplos.xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$archivo.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		//Hacemos una salida al navegador con el archivo Excel.
		$objWriter->save('php://output');

		exit();
		
	}

	function intercentros()
	{
		$parametros['id_centro']  = 3;
    	$parametros['fecha_desde']  =   "2019-02-03 00:00:00";
    	$parametros['fecha_hasta']  =   "2019-03-31 23:59:59";

    	$pagos_intercentro = $this->Facturacion_model->intercentros($parametros);
    	echo '<pre>';
    	print_r($pagos_intercentro);
	}
	*/


	/*
	function instalar()
	{
		$this->db->where('nombre_modulo', 'Facturacion');
		$check = $this->db->get('modulos')->num_rows();
		if($check < 1)
		{
			// se busca el id más alto de la tabla modulos para sumarle 1
			$this->db->select('id_modulo');
			$this->db->order_by('id_modulo', 'desc');
			$this->db->limit(1);
			$last_id = $this->db->get('modulos')->row()->id_modulo + 1;

			$data = [
				'id_modulo'               => $last_id,
				'nombre_modulo' => 'Facturacion',
				'url' => 'facturacion',
				'padre' => 'Master',
				'orden' => 8,
				'orden_item' => 8,
				'id_usuario_creacion' => 0,
				'fecha_creacion' => date('Y-m-d H:i:s'),
				'id_usuario_modificacion' => 0,
				'fecha_modificacion' => date('Y-m-d H:i:s'),
				'borrado' => 0,
			];
			$this->db->insert('modulos', $data);

			echo "<hr><hr><hr>FIN DEL PROCESO DE INSTALACIÓN DEM MÓDULO Y OPTIMIZACIÓN DE TABLAS.<hr>";
		}
		else
		{
			echo '<!DOCTYPE html>
			<html lang="es">
				<head>
					<meta charset="utf-8" />
				</head>
				<body>
					<h3>El módulo Facturacion ya está instalado.</h3>
				</body>
			</html>';
		}
	}
	*/
}

/* End of file Facturacion.php */