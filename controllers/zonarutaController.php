<?php

class zonarutaController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {
			$this->_view->conctructor_menu('supervisorventas', 'zonaruta');
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
					'plugins/vendors/css/animate/animate.min',

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
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min',
					'dist/js/scripts/forms/form-wizard',

					'plugins/vendors/js/pickers/pickadate/picker',
					'plugins/vendors/js/pickers/pickadate/picker.date',
					'plugins/vendors/js/pickers/pickadate/picker.time',
					'plugins/vendors/js/pickers/pickadate/legacy',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					'dist/js/scripts/forms/pickers/form-pickers',
					// imput mask
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min',

					//table basic
					'plugins/vendors/js/tables/datatable/jquery.dataTables.min',
					'plugins/vendors/js/tables/datatable/datatables.bootstrap4.min',
					'plugins/vendors/js/tables/datatable/dataTables.responsive.min',
					'plugins/vendors/js/tables/datatable/responsive.bootstrap4',
					'plugins/vendors/js/tables/datatable/datatables.checkboxes.min',
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

			$v_username    =  $_SESSION['correo'];
			$v_slsperid    =  $_SESSION['v_codvend'];

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

			$vend = array(
				'post' => 0,
				'v_username' => $v_username,
			);

			$zona = array(
				'post' =>	1,
				'departamento' =>	'',
				'zonaventa' =>	'',
				'v_usuario' =>	$v_username,
				'v_codvend' =>	'',
			);

			$vend = array(
				'post' => 0,
				'v_username' => $v_username,
			);

			//Envio del request
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoVendedorZonaRuta($vend);
			$ListadoVendedorZonaRuta = json_decode($result->ListadoVendedorZonaRutaResult, true);

			$result = $soap->ListadoSemanaRutaSupervisor($zona);
			$ListadoSemanaRutaSupervisor = json_decode($result->ListadoSemanaRutaSupervisorResult, true);

			$this->_view->ListadoVendedorZonaRuta = $ListadoVendedorZonaRuta;
			$this->_view->ListadoSemanaRutaSupervisor = $ListadoSemanaRutaSupervisor;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function crearzona()
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
					'plugins/vendors/css/animate/animate.min',
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
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min'
				)
			);

			$v_username =  $_SESSION['correo'];
			$v_codvend = $_GET['variable1'];
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
			$zona = array(
				'post' =>	0,
				'departamento' =>	'',
				'zonaventa' =>	'',
				'v_usuario' =>	$v_username,
				'v_codvend' =>	$v_codvend,
			);
			$depa = array(
				'i_codigo' =>	0,
				'v_username' =>	$v_username,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoSemanaRutaVendedor($zona);
			$ListadoSemanaRutaVendedor = json_decode($result->ListadoSemanaRutaVendedorResult, true);

			$result = $soap->Departamento($depa);
			$departamento = json_decode($result->DepartamentoResult, true);

			$this->_view->ListadoSemanaRutaVendedor = $ListadoSemanaRutaVendedor;
			$this->_view->departamento  = $departamento;

			$this->_view->setJs(array('crearzona'));
			$this->_view->renderizar('crearzona');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function editarzona()
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
					'plugins/vendors/css/animate/animate.min',
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
					'dist/js/scripts/forms/form-input-mask',
					'plugins/vendors/js/forms/cleave/cleave.min'
				)
			);

			$v_username =  $_SESSION['correo'];
			$v_codvend = $_GET['variable1'];
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
			$zona = array(
				'post' =>	1,
				'departamento' =>	'',
				'zonaventa' =>	'',
				'v_usuario' =>	$v_username,
				'v_codvend' =>	$v_codvend,
			);
			$depa = array(
				'i_codigo' =>	0,
				'v_username' =>	$v_username,
			);
			$vendedor = array(
				"post" => 3,
				"i_id" => 0,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoSemanaRutaVendedor($zona);
			$ListadoSemanaRutaVendedor = json_decode($result->ListadoSemanaRutaVendedorResult, true);

			$result = $soap->ListadoVendedor($vendedor);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);


			$result = $soap->Departamento($depa);
			$departamento = json_decode($result->DepartamentoResult, true);


			$result = $soap->ListadoPeriodo();
			$ListadoPeriodo = json_decode($result->ListadoPeriodoResult, true);


			$this->_view->listavendedor = $listavendedor;
			$this->_view->ListadoSemanaRutaVendedor = $ListadoSemanaRutaVendedor;
			$this->_view->departamento  = $departamento;
			$this->_view->ListadoPeriodo  = $ListadoPeriodo;

			$this->_view->setJs(array('editarzona'));
			$this->_view->renderizar('editarzona');
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function registro_cambioruta() //1
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$codvendant = $_POST['codvendant'];
			$codvendnew = $_POST['codvendnew'];
			$v_idzonaruta = $_POST['v_idzonaruta'];
			$i_venta = $_POST['i_venta'];
			$periodo = $_POST['periodo'];


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
				'post' => $post,
				'codvendant' => $codvendant,
				'codvendnew' => $codvendnew,
				'v_idzonaruta' => $v_idzonaruta,
				'i_venta' =>  $i_venta,
				'periodo' =>  $periodo,

			);

			$result2 = $soap->GuardarCambioZonaRuta($params);
			$GuardarCambioZonaRuta = json_decode($result2->GuardarCambioZonaRutaResult, true);

			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					"vicon" 		=> $GuardarCambioZonaRuta[0]['v_icon'],
					"vtitle" 		=> $GuardarCambioZonaRuta[0]['v_title'],
					"vtext" 		=> $GuardarCambioZonaRuta[0]['v_text'],
					"itimer" 		=> intval($GuardarCambioZonaRuta[0]['i_timer']),
					"icase" 		=> intval($GuardarCambioZonaRuta[0]['i_case']),
					"vprogressbar" 	=> $GuardarCambioZonaRuta[0]['v_progressbar'],
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}
}
