<?php

class tomarpedidocController extends Controller
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
				)
			);

			$v_username    =  $_SESSION['correo'];
			$v_slsperid    =  $_SESSION['v_codvend'];
			$v_codcliente = $_GET['variable1'];

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

			$venta = array(
				'post' => 0,
				'v_username' =>	$v_username,
				'v_codvend' =>	$v_slsperid,
				'v_codcliente' =>	$v_codcliente,
			);

			$detvta = array(
				'post' => 1,
				'v_username' =>	$v_username,
				'v_codvend' =>	$v_slsperid,
				'v_codcliente' =>	$v_codcliente,
			);

	 
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoVentaCliente($venta);
			$ListadoVentaCliente = json_decode($result->ListadoVentaClienteResult, true);

			$result = $soap->ListadoDetalleVentaCliente($detvta);
			$ListadoDetalleVentaCliente = json_decode($result->ListadoDetalleVentaClienteResult, true);


			$this->_view->ListadoVentaCliente = $ListadoVentaCliente;
			$this->_view->ListadoDetalleVentaCliente = $ListadoDetalleVentaCliente;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function anular_venta()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			// $username =  $_SESSION['correo'];
			// $codigoVendedor = $_SESSION['v_codvend'];
			$nu_correla = $_POST['nu_correla'];
			$ch_codclien = $_POST['ch_codclien'];

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
				"post" =>  $post,
				"ch_correla" => $nu_correla,
				"ch_codclien" => $ch_codclien,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->AnularPedidoCliente($param);
			$data = json_decode($result->AnularPedidoClienteResult, true);

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
