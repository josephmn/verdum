<?php


class canalclienteController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {

			$this->_view->setCss_Specific(
				array(
					'dist/css/fontawesome/css/all',
					'dist/css/vendors.min',
					'dist/css/forms/wizard/bs-stepper.min',
					'plugins/vendors/css/extensions/sweetalert2.min',
					'dist/css/forms/select/select2.min',
					'dist/css/bootstrap',
					'dist/css/bootstrap-extended',
					'dist/css/colors',
					'dist/css/components',
					'dist/css/core/menu/menu-types/vertical-menu',
					'dist/css/plugins/forms/form-validation',
					'dist/css/plugins/forms/form-wizard',
					'dist/css/custom',
					'dist/css/style',
					'dist/css/plugins/forms/wizard/form-wizard',
					'plugins/vendors/css/extensions/ext-component-sweet-alerts',
					'plugins/datatables-net/css/jquery.dataTables.min',
					'plugins/datatables-net/css/responsive.dataTables.min',
					'plugins/vendors/css/pickers/pickadate/pickadate',
					'plugins/vendors/css/pickers/flatpickr/flatpickr.min',
					'plugins/vendors/css/pickers/form-flat-pickr',
					'plugins/vendors/css/pickers/form-pickadate',
				)
			);

			$this->_view->setJs_Specific(
				array(
					'plugins/vendors/js/vendors.min',
					'plugins/vendors/js/extensions/toastr.min',
					'plugins/vendors/js/forms/select/select2.full.min',
					'plugins/vendors/js/forms/validation/jquery.validate.min',
					'dist/js/core/app-menu',
					'dist/js/core/app',
					'dist/js/scripts/forms/form-wizard',
					'plugins/datatables-net/js/jquery.dataTables.min',
					'plugins/datatables-net/js/dataTables.select.min',
					'plugins/datatables-net/js/dataTables.buttons.min',
					'plugins/datatables-net/js/buttons.flash.min',
					'plugins/datatables-net/js/jszip.min',
					'plugins/datatables-net/js/pdfmake.min',
					'plugins/datatables-net/js/vfs_fonts',
					'plugins/datatables-net/js/buttons.html5.min',
					'plugins/datatables-net/js/buttons.print.min',
					'plugins/datatables-net/js/dataTables.responsive.min',
					'plugins/datatables-net/js/dataTables.checkboxes.min',
					'plugins/vendors/js/extensions/sweetalert2.all.min',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					'plugins/vendors/js/pickers/pickadate/picker',
					'plugins/vendors/js/pickers/pickadate/picker.date',
					'plugins/vendors/js/pickers/pickadate/picker.time',
					'plugins/vendors/js/pickers/pickadate/legacy',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					'dist/js/scripts/forms/pickers/form-pickers',
					// imput mask
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min',


			
					
				)
			);

			$wsdl = 'http://localhost:81/VMWEB/WSVerdumweb.asmx?WSDL';						

			$options = array(
				"uri" => $wsdl,
				"style" => SOAP_RPC,
				"use" => SOAP_ENCODED,
				"soap_version" => SOAP_1_1,
				"connection_timeout" => 60,
				"trace" => false,
				"encoding" => "UTF-8",
				"exceptions" => false,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoCanalesCliente();
			$canales = json_decode($result->ListadoCanalesClienteResult, true);


			$result = $soap->ListadoPeriodo();
			$ListadoPeriodo = json_decode($result->ListadoPeriodoResult, true);

			$this->_view->canales = $canales;
			$this->_view->ListadoPeriodo = $ListadoPeriodo;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function  Actualizar_canal_cliente()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$periodo = $_POST['periodo'];

			$wsdl = 'http://localhost:81/VMWEB/WSVerdumweb.asmx?WSDL';
			$options = array(
				"uri" => $wsdl,
				"style" => SOAP_RPC,
				"use" => SOAP_ENCODED,
				"soap_version" => SOAP_1_1,
				"connection_timeout" => 15,
				"trace" => false,
				"encoding" => "UTF-8",
				"exceptions" => false,
			);

			$param = array(
				'post'       =>	$post,
				'periodo' =>	$periodo,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ActualizarCanalCliente($param);
			$data = json_decode($result->ActualizarCanalClienteResult, true);

			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					"vicon" 		=> $data[0]['v_icon'],
					"vtitle" 		=> $data[0]['v_title'],
					"vtext" 		=> $data[0]['v_text'],
					"itimer" 		=> intval($data[0]['i_timer']),
					"icase" 		=> intval($data[0]['i_case']),
					"vprogressbar" 	=> $data[0]['v_progressbar'],
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function cargar_datos() //ok
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$dia = date("Ymd"); //brindar formato
			$timezone = -5;
			// $dia2 = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));
			// $fconcat = date("YmdHis", time()); //formato año+hora, indice para registro de archivo

			if (!empty($_SESSION['usuario'])) {

				$nombrearchivo = $_FILES["documento"]["name"];

				if (is_array($_FILES) && count($_FILES) > 0) {
					// CONSULTAMOS A LA BASE DE DATOS, SI EL TIPO DE ARCHIVO A SUBIR ES PERMITIDO
					$extdoc = explode("/", $_FILES["documento"]["type"]);

					$wsdl = 'http://localhost:81/VMWEB/WSVerdumweb.asmx?WSDL';

					$options = array(
						"uri" => $wsdl,
						"style" => SOAP_RPC,
						"use" => SOAP_ENCODED,
						"soap_version" => SOAP_1_1,
						"connection_timeout" => 60,
						"trace" => false,
						"encoding" => "UTF-8",
						"exceptions" => false,
					);
					$soap = new SoapClient($wsdl, $options);

					$params = array(
						'modulo'	=> 'subirarchivo', // 
						'mime'		=> $extdoc[0], // image / application --> mime clasificado por php
						'type'		=> $extdoc[1], // jpeg / pdf / msword --> tipo de archivo clasificado por php input file
					);

					$result = $soap->ConsultaTipoArchivo($params);
					$tipoarchivo = json_decode($result->ConsultaTipoArchivoResult, true);

					if (count($tipoarchivo) > 0) {
						$destino = "temp/" . $_SESSION['usuario'] . "_" . $dia . "." . $tipoarchivo[0]["v_archivo"]; // este es con fecha
						if (copy($_FILES['documento']['tmp_name'], $destino)) {

							if (file_exists($destino)) {

								$this->getLibrary('PHPExcel/PHPExcel');


								$inputFileType = PHPExcel_IOFactory::identify($destino);
								$objReader = PHPExcel_IOFactory::createReader($inputFileType);
								$objPHPExcel = $objReader->load($destino);

								$sheet = $objPHPExcel->getSheet(0);
								$ultimacolumna = $sheet->getHighestColumn();
								$cantidadfilas = $sheet->getHighestRow();


								if ($ultimacolumna == 'D') {
									for ($i = 2; $i <= $cantidadfilas; $i++) {
										$_DATOS_EXCEL[$i]['idcliente'] 			= $sheet->getCell('A' . $i)->getValue();
										$_DATOS_EXCEL[$i]['iddireccion'] 		= $sheet->getCell('B' . $i)->getValue();
										$_DATOS_EXCEL[$i]['canal'] 			  = $sheet->getCell('C' . $i)->getValue();
										$_DATOS_EXCEL[$i]['subcanal'] 			= $sheet->getCell('D' . $i)->getValue();
									}
									unlink($destino);

									$soap = new SoapClient($wsdl, $options);

									$j = 0;
									$k = 0;
									
									foreach ($_DATOS_EXCEL as $campo) {
										if (
											$campo['idcliente'] == '-' &&
											$campo['iddireccion'] == '-' &&
											$campo['canal'] == '00000000' &&
											$campo['subcanal'] == '-'
										) {
											$j++;
										} else {
											$param = array(
												"post"			        => 0,
												"v_ruc"			        => $campo['idcliente'],
												"v_id_direccion"	    => $campo['iddireccion'],
												"v_nombre_canal"	    => $campo['canal'],
												"v_nombre_subcanal"		=> $campo['subcanal']
											);
											$result = $soap->GuardarCanalCliente($param);
											// $mantVentas = json_decode($result->MantenimientoVentasResult, true);
											$k++;
										}
									}

									header('Content-type: application/json; charset=utf-8');
									echo $json->encode(
										array(
											// "dato"		 	=> "carga correcta ".$cantidadfilas,
											// "count"		 	=> $j.' - '.$k,
											"vicon"		 	=> "success",
											"vtitle" 		=> "Carga de archivo",
											"vtext" 		=> "Se cargaron " . $k . " filas correctamente, se recargar la página para actualizar sus registros",
											"itimer" 		=> 3000,
											"icase" 		=> 2,
											"vprogressbar" 	=> true,
										)
									);


								} else {
									//Archivo no contiene el formato correcto.
									header('Content-type: application/json; charset=utf-8');
									echo $json->encode(
										array(
											// "dato" 		=> "error6 - Archivo no contiene el formato correcto",
											"vicon" 		=> "error",
											"vtitle" 		=> "Archivo no contiene las columnas correctas, favor de descargar el formato de guia para comparar...",
											"vtext" 		=> "No se pudo cargar el archivo!",
											"itimer" 		=> 3000,
											"icase" 		=> 2, //"archivo no se pudo guardar em ruta destino";
											"vprogressbar" 	=> true
										)
									);
								}
							} else {
								//Archivo no encontrado en la ruta para cargar
								header('Content-type: application/json; charset=utf-8');
								echo $json->encode(
									array(
										// "dato" => "error5 - Archivo no encontrado en la ruta para cargar"
										"vicon" 		=> "error",
										"vtitle" 		=> "Archivo no existe en la carpeta para cargar...",
										"vtext" 		=> "No se pudo cargar el archivo!",
										"itimer" 		=> 3000,
										"icase" 		=> 3, //"archivo no se pudo guardar em ruta destino";
										"vprogressbar" 	=> true
									)
								);
							}
						} else {
							//Error al copiar el archivo a la carpeta temp
							header('Content-type: application/json; charset=utf-8');
							echo $json->encode(
								array(
									// "dato" => "erro4 - Error al copiar el archivo a la carpeta temp"
									"vicon" 		=> "error",
									"vtitle" 		=> "No se encontro carpeta de destino para guardar el archivo a cargar...",
									"vtext" 		=> "No se pudo cargar el archivo!",
									"itimer" 		=> 3000,
									"icase" 		=> 4, //"archivo no se pudo guardar em ruta destino";
									"vprogressbar" 	=> true
								)
							);
						}
					} else {
						//Archivo no permitidos en el sistema
						header('Content-type: application/json; charset=utf-8');
						echo $json->encode(
							array(
								// "dato" => "error3 - Archivo no permitidos en el sistema"
								"vicon" 		=> "error",
								"vtitle" 		=> "Tipo de archivo no permitido para cargarlo en el sistema...",
								"vtext" 		=> "No se pudo cargar el archivo!",
								"itimer" 		=> 3000,
								"icase" 		=> 5, //"archivo no se pudo guardar em ruta destino";
								"vprogressbar" 	=> true
							)
						);
					}
				} else {
					//Archivo de origen no existe, error al cargar archivo al sistema
					header('Content-type: application/json; charset=utf-8');
					echo $json->encode(
						array(
							// "dato" => "error2 - Archivo de origen no existe, error al cargar archivo al sistema"
							"vicon" 		=> "error",
							"vtitle" 		=> "Archivo de ruta de origen no se puedo copiar, verifique si el documento existe en la carpeta de origen...",
							"vtext" 		=> "No se pudo cargar el archivo!",
							"itimer" 		=> 3000,
							"icase" 		=> 6, //"archivo no se pudo guardar em ruta destino";
							"vprogressbar" 	=> true
						)
					);
				}
			} else {
				//No se puede cargar, usuario no esta asignado a ningun proveedor
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						// "dato" => "error - No se puede cargar, usuario no esta asignado a ningun proveedor"
						"vicon" 		=> "error",
						"vtitle" 		=> "Usuario no esta asignado a ningun proveedor, no puede cargar archivos...",
						"vtext" 		=> "No se pudo cargar el archivo!",
						"itimer" 		=> 3000,
						"icase" 		=> 7, //"archivo no se pudo guardar em ruta destino";
						"vprogressbar" 	=> true
					)
				);
			}
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function descargar_formato()
	{
		if (isset($_SESSION['usuario'])) {

			$this->getLibrary('PHPExcel/PHPExcel');

			// Crea un nuevo objeto PHPExcel
			$objPHPExcel = new PHPExcel();

			// Establecer propiedades
			$objPHPExcel->getProperties()
				->setCreator("Verdum")
				->setLastModifiedBy("Portal web Verdum")
				->setTitle("Formato Canal Cliente")
				->setSubject("Formato Canal Cliente")
				->setDescription("Importar Formato Canal Cliente")
				->setKeywords("Excel Office 2007 openxml")
				->setCategory("Formatos");

			//titulos del reporte y datos donde va cada informacion
			$boldArray = array('font' => array('bold' => true,), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
			$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($boldArray);

			// COLOCAR MARCO A LAS CELDAS
			$rango = 'A1:D1';
			$styleArray = array(
				'font' => array('name' => 'Arial', 'size' => 9),
				'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => '#FFFFFF')))
			);
			$objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'IDCLIENTE');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', 'IDDIRECCION');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C1', 'CANAL');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D1', 'SUBCANAL');

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '77683117');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', 'DEFAULT');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', 'INSTITUCIONAL');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', 'CENCOSUD');


			// $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 2)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
			// $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 3)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);

			// $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, 2)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);
			// $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, 3)->setDataType(PHPExcel_Cell_DataType::TYPE_STRING);

			// Renombrar Hoja
			$objPHPExcel->getActiveSheet()->setTitle('formato_canal');

			// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
			$objPHPExcel->setActiveSheetIndex(0);

			// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Canal_Cliente.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
		} else {
			$this->redireccionar('index/logout');
		}
	}



}
