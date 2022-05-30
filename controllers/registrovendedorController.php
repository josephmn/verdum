<?php

class registrovendedorController extends Controller
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
					// 'plugins/vendors/css/pickers/pickadate/pickadate',
					// 'plugins/vendors/css/pickers/flatpickr/flatpickr.min',
					// 'plugins/vendors/css/pickers/form-flat-pickr',
					// 'plugins/vendors/css/pickers/form-pickadate','
				)
			);

			$this->_view->setJs_Specific(
				array(
					'plugins/vendors/js/vendors.min',
					'plugins/vendors/js/extensions/toastr.min',
					'plugins/vendors/js/forms/wizard/bs-stepper.min',
					'plugins/vendors/js/forms/select/select2.full.min',
					'plugins/vendors/js/forms/validation/jquery.validate.min',
					'dist/js/core/app-menu',
					'dist/js/core/app',
					'dist/js/scripts/forms/form-wizard',
					'plugins/datatables-net/js/jquery.dataTables.min',
					'plugins/datatables-net/js/dataTables.responsive.min',
					'plugins/vendors/js/extensions/sweetalert2.all.min',
					// 'plugins/vendors/js/pickers/pickadate/picker',
					// 'plugins/vendors/js/pickers/pickadate/picker.date',
					// 'plugins/vendors/js/pickers/pickadate/picker.time',
					// 'plugins/vendors/js/pickers/pickadate/legacy',
					// 'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					// 'dist/js/scripts/forms/pickers/form-pickers',
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min',
					'plugins/vendors/js/tables/datatable/datatables.buttons.min',
					'plugins/vendors/js/tables/datatable/jszip.min',
					'plugins/vendors/js/tables/datatable/pdfmake.min',
					'plugins/vendors/js/tables/datatable/vfs_fonts',
					'plugins/vendors/js/tables/datatable/buttons.html5.min',
					'plugins/vendors/js/tables/datatable/buttons.print.min',
					'plugins/vendors/js/tables/datatable/dataTables.rowGroup.min',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min'
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

			$perven = array(
				'post' => 1,
			);

			$soap = new SoapClient($wsdl, $options);	

			$result = $soap->ListadoPersonalVendedor($perven);
			$ListadoPersonalVendedor = json_decode($result->ListadoPersonalVendedorResult, true);
 
			$this->_view->ListadoPersonalVendedor = $ListadoPersonalVendedor;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function guardar_zonaruta_vendedor()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = 0;
			$post = $_POST['post'];
			$departamento = $_POST['departamento'];
			$zonaventa = $_POST['variable3'];
			$v_codvend = $_POST['codvend'];
			$v_username    =  $_SESSION['correo'];

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
				'departamento' =>	$departamento,
				'zonaventa' => $zonaventa,
				'v_usuario'	 => $v_username,
				'v_codvend'	 => $v_codvend,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarZonaRutaVendedor($param);
			$data = json_decode($result->GuardarZonaRutaVendedorResult, true);

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
