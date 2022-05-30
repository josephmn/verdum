<?php

class accesousuariobController extends Controller
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
					'plugins/vendors/css/extensions/ext-component-sweet-alerts',
					'plugins/datatables-net/css/jquery.dataTables.min',
					'plugins/datatables-net/css/buttons.dataTables.min',
					'plugins/datatables-net/css/responsive.dataTables.min',
					'plugins/datatables-net/css/dataTables.checkboxes'

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
					'plugins/vendors/js/extensions/sweetalert2.all.min'
				)
			);

			// $v_usuario = $_SESSION['usuario'];
			$v_usuario = $_GET['variable1'];
			$v_token = $_SESSION['v_token'];

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
			$tipo = array(
				'post' => 1,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$Faq1 = array(
				'post' => 2,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$Faq2 = array(
				'post' => 3,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoRestriccion($tipo);
			$tipo = json_decode($result->ListaTipoRestriccionResult, true);

			$result = $soap->ListaTipoRestriccion($Faq1);
			$Faq1 = json_decode($result->ListaTipoRestriccionResult, true);

			$result = $soap->ListaTipoRestriccion($Faq2);
			$Faq2 = json_decode($result->ListaTipoRestriccionResult, true);


			$this->_view->tipo = $tipo;
			$this->_view->Faq1 = $Faq1;
			$this->_view->Faq2 = $Faq2;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cargar_filtros_Faq()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_tipo = $_POST['tipo'];
			$v_usuario = $_POST['variable1'];
			// $v_usuario = $_SESSION['correo'];
			$v_token = $_SESSION['v_token'];


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

			$Faq1 = array(
				'post' => 2,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => 	$v_tipo,
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$Faq2 = array(
				'post' => 3,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => 	$v_tipo,
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoRestriccion($Faq1);
			$Faq1 = json_decode($result->ListaTipoRestriccionResult, true);

			$result = $soap->ListaTipoRestriccion($Faq2);
			$Faq2 = json_decode($result->ListaTipoRestriccionResult, true);


			$a = 0;
			$faq1 = "";
			foreach ($Faq1 as $dv) {
				$faq1 .= "<option " . $dv['v_default'] . " value=" . $dv['v_id_tipo'] . ">" . $dv['v_tipo'] . "</option>";
				$a++;
			}

			$b = 0;
			$faq2 = "";
			foreach ($Faq2 as $dv) {
				$faq2 .= "<option " . $dv['v_default'] . " value=" . $dv['v_id_tipo'] . ">" . $dv['v_tipo'] . "</option>";
				$b++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"Faq1" => $faq1,
					"Faq2" => $faq2,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function cargar_filtros_Faq2()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_tipo = $_POST['tipo'];
			$v_faq1 = $_POST['faq1'];
			$v_usuario = $_POST['variable1'];
			$v_token = $_SESSION['v_token'];

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


			$Faq2 = array(
				'post' => 4,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'tipo' => 	$v_tipo,
				'v_faq1' => $v_faq1,
				'v_faq2' => '',
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoRestriccion($Faq2);
			$Faq2 = json_decode($result->ListaTipoRestriccionResult, true);


			$b = 0;
			$faq2 = "";
			foreach ($Faq2 as $dv) {
				$faq2 .= "<option " . $dv['v_default'] . " value=" . $dv['v_id_tipo'] . ">" . $dv['v_tipo'] . "</option>";
				$b++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"Faq2" => $faq2,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}






	public function listado_dato_temporal()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$post = $_POST['post'];
			$v_usuario = $_POST['variable1'];
			// $v_usuario = $_SESSION['correo'];
			$v_token = $_SESSION['v_token'];


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

			$table1 = array(
				'post' => $post,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoDatoTemporal($table1);
			$data = json_decode($result->ListadoDatoTemporalResult, true);

			if (count($data) > 0) {
				$estado = 0;
				$faq1  = $data[0]['Faq1'];
				$faq2  = $data[0]['Faq2'];
				$faq3  = $data[0]['Faq3'];
			} else {
				$estado = 1;
			}
			if (count($data) > 0) {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"estado" => $estado,
						"faq1" => $faq1,
						"faq2" => $faq2,
						"faq3" => $faq3,
					)
				);
			}
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function table1_restriccion_user_dato()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$post = $_POST['post'];
			$v_usuario = $_POST['variable1'];
			// $v_usuario = $_SESSION['correo'];
			$v_token = $_SESSION['v_token'];


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

			$table1 = array(
				'post' => $post,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoRestriccionDato($table1);
			$table1 = json_decode($result->ListadoRestriccionDatoResult, true);

			$table1filas = [];
			$a = 0;
			foreach ($table1 as $tb1) {
				$propiedades1 = array("i_id_row" => intval($tb1['i_id_row']), "v_tipo" => $tb1['v_tipo'], "i_iD" => $tb1['i_iD'], "v_descripcion" => $tb1['v_descripcion'], "v_faq1" => $tb1['v_faq1'], "v_faq2" => $tb1['v_faq2'], "v_estado" => $tb1['v_estado']);
				$table1filas += ["$a" => $propiedades1];
				$a++;
			}

			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					'data' => $table1filas
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function table2_restriccion_user_dato()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$post = $_POST['post'];
			$v_usuario = $_POST['variable1'];
			// $v_usuario = $_SESSION['correo'];
			$v_token = $_SESSION['v_token'];

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
			$table2 = array(
				'post' => $post,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoRestriccionDato($table2);
			$table2 = json_decode($result->ListadoRestriccionDatoResult, true);


			$table2filas = [];
			$b = 0;
			foreach ($table2 as $tb2) {
				$propiedades2 = array("i_id_row" => intval($tb2['i_id_row']), "v_tipo" => $tb2['v_tipo'], "i_iD" => $tb2['i_iD'], "v_descripcion" => $tb2['v_descripcion'], "v_faq1" => $tb2['v_faq1'], "v_faq2" => $tb2['v_faq2'], "v_estado" => $tb2['v_estado']);
				$table2filas += ["$b" => $propiedades2];
				$b++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					'data' => $table2filas
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function guardar_dato_restriccion()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();


			$post = $_POST['post'];
			$v_usuario = $_POST['variable1'];
			// $v_usuario = $_SESSION['correo'];
			$v_token = $_SESSION['v_token'];
			$v_tipo = $_POST['tipo'];
			$v_faq1 = $_POST['faq1'];
			$v_faq2 = $_POST['faq2'];


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
				'post' => $post,
				'v_username' => $v_usuario,
				'v_token' => $v_token,
				'v_tipo' => 	$v_tipo,
				'v_Faq1' => $v_faq1,
				'v_Faq2' => $v_faq2,
			);


			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarDatoTemporal($param);
			$data = json_decode($result->GuardarDatoTemporalResult, true);

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


	// PARA GUARDAR ACCESO  A DATOS SEGUN USUARIO
	public function guardar_acceso_datos_usuario()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$ids = $_POST['ids'];
			$v_tipo = $_POST['tipo'];
			$v_username = $_POST['variable1'];

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

			$soap = new SoapClient($wsdl, $options);
			$i = 0;
			foreach ($ids as $ch) {
				$params[$i] = array(
					'post'			=> intval($post),
					'i_id_row'			=> intval($ch),
					'v_tipo' 			=> $v_tipo,
					'v_username' 			=> $v_username,
				);
				$result = $soap->GuardarRestriccionDato($params[$i]);
				$data = json_decode($result->GuardarRestriccionDatoResult, true);
				$i++;
			};


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
}
