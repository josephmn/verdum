<?php

class tomarpedidoController extends Controller
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

			$client = array(
				'post' =>	1,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
				'v_busqueda' =>	'',
			);


			$ventas = array(
				'post' =>	1,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
			);

			$nrclient = array(
				'post' =>	2,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
			);

			$totsol = array(
				'post' =>	3,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
			);

			$nrprod = array(
				'post' =>	4,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoClienteRuta($client);
			$clientesruta = json_decode($result->ListadoClienteRutaResult, true);

			$result = $soap->ListadoVentasDay($ventas);
			$qtyventas = json_decode($result->ListadoVentasDayResult, true);

			$result = $soap->ListadoVentasDay($nrclient);
			$nrclient = json_decode($result->ListadoVentasDayResult, true);

			$result = $soap->ListadoVentasDay($totsol);
			$totsol = json_decode($result->ListadoVentasDayResult, true);

			$result = $soap->ListadoVentasDay($nrprod);
			$nrprod = json_decode($result->ListadoVentasDayResult, true);

			$this->_view->clientesruta = $clientesruta;
			$this->_view->qtyventas = $qtyventas;
			$this->_view->nroclientes = $nrclient;
			$this->_view->totalsoles = $totsol;
			$this->_view->totalprod = $nrprod;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cargar_table_enruta()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_username =  $_SESSION['correo'];
			$v_slsperid = $_SESSION['v_codvend'];
			$v_busqueda = $_POST['v_busqueda'];

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

			$client = array(
				'post' =>	1,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
				'v_busqueda' =>	$v_busqueda,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoClienteRuta($client);
			$data = json_decode($result->ListadoClienteRutaResult, true);


			// if (count($data) > 0) {
			// 	$estado = 0;
			// }

			$filas = [];
			$i = 0;
			foreach ($data as $da) {
				$propiedades1 = array(
					"v_codliente" => ($da['v_codliente']),
					"v_razonSocial" => $da['v_razonSocial'],
					"v_clasificacion" => $da['v_clasificacion'],
					"v_direccion" => $da['v_direccion'],
					"v_fechaUltimaVisita" => $da['v_fechaUltimaVisita'],
					"v_ruta" => $da['v_ruta'],
					"v_estado" => $da['v_estado']
				);
				$filas += ["$i" => $propiedades1];
				$i++;
			}
			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					// 'estado' => $estado,
					'data' => $filas,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function cargar_table_fuera_ruta()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_username =  $_SESSION['correo'];
			$v_slsperid = $_SESSION['v_codvend'];
			$v_busqueda = $_POST['v_busqueda'];

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

			$client = array(
				'post' =>	2,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
				'v_busqueda' =>	$v_busqueda,
			);


			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoClienteRuta($client);
			$data = json_decode($result->ListadoClienteRutaResult, true);

			$filas = [];
			$i = 0;
			foreach ($data as $da) {
				$propiedades1 = array(
					"v_codliente" => ($da['v_codliente']),
					"v_razonSocial" => $da['v_razonSocial'],
					"v_clasificacion" => $da['v_clasificacion'],
					"v_direccion" => $da['v_direccion'],
					"v_fechaUltimaVisita" => $da['v_fechaUltimaVisita'],
					"v_ruta" => $da['v_ruta'],
					"v_estado" => $da['v_estado']
				);
				$filas += ["$i" => $propiedades1];
				$i++;
			}
			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					'data' => $filas
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cargar_table_atendidos()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_username =  $_SESSION['correo'];
			$v_slsperid = $_SESSION['v_codvend'];
			$v_busqueda = $_POST['v_busqueda'];

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

			$client = array(
				'post' =>	3,
				'v_username' =>	$v_username,
				'v_slsperid' =>	$v_slsperid,
				'v_busqueda' =>	$v_busqueda,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoClienteRuta($client);
			$data = json_decode($result->ListadoClienteRutaResult, true);

			$filas = [];
			$i = 0;
			foreach ($data as $da) {
				$propiedades1 = array(
					"v_codliente" => ($da['v_codliente']),
					"v_razonSocial" => $da['v_razonSocial'],
					"v_clasificacion" => $da['v_clasificacion'],
					"v_direccion" => $da['v_direccion'],
					"v_fechaUltimaVisita" => $da['v_fechaUltimaVisita'],
					"v_ruta" => $da['v_ruta'],
					"v_estado" => $da['v_estado']
				);
				$filas += ["$i" => $propiedades1];
				$i++;
			}
			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					'data' => $filas
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}
}
