<?php

class accesousuarioController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {
			$this->_view->conctructor_menu('configuracion', 'accesousuario');
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
					// 'plugins/vendors/css/pickers/form-pickadate',
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
					'plugins/vendors/js/forms/cleave/cleave.min'
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
			$tipo = array(
				'post' => 1,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$Faq1 = array(
				'post' => 2,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$Faq2 = array(
				'post' => 3,
				'tipo' => '',
				'v_faq1' => '',
				'v_faq2' => '',
			);

			$soap = new SoapClient($wsdl, $options);	
			$result = $soap->Listauseracceso();
			$listauseracceso = json_decode($result->ListauseraccesoResult, true);

			$result = $soap->ListaTipoRestriccion($tipo);
			$tipo = json_decode($result->ListaTipoRestriccionResult, true);

			$result = $soap->ListaTipoRestriccion($Faq1);
			$Faq1 = json_decode($result->ListaTipoRestriccionResult, true);

			$result = $soap->ListaTipoRestriccion($Faq2);
			$Faq2 = json_decode($result->ListaTipoRestriccionResult, true);


			$this->_view->listauseracceso = $listauseracceso;
			$this->_view->tipo = $tipo;
			$this->_view->Faq1 = $Faq1;
			$this->_view->Faq2 = $Faq2;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}
	
}

