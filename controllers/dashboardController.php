<?php

class dashboardController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function cambiarsession()
	{
		if (isset($_SESSION['usuario'])) {

			$session = $_POST['string'];

			$_SESSION['selsubmenu'] = "";
			$_SESSION['selmenu'] = $session;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function cambiarsessionsub()
	{
		if (isset($_SESSION['usuario'])) {

			$sessionsub = $_POST['string'];

			$_SESSION['selsubmenu'] = $sessionsub;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function cambiaropen()
	{
		if (isset($_SESSION['usuario'])) {

			$sessionsub = $_POST['string'];

			$_SESSION['despliegue'] = $sessionsub;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {

			// <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/vendors.min.css">
			// <link rel="stylesheet" type="text/css" href="../../../app-assets/css/components.css">
			// <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">

			$this->_view->setCss_Specific(
				array(
					'dist/css/fontawesome/css/all',
					'dist/css/vendors.min',
					'dist/css/extensions/toastr.min',
					'dist/css/bootstrap',
					'dist/css/bootstrap-extended',
					'dist/css/colors',
					'dist/css/components',

					'dist/css/core/menu/menu-types/vertical-menu',
					'dist/css/plugins/forms/form-validation',
					'dist/css/plugins/extensions/ext-component-toastr',
					'dist/css/custom',
					'dist/css/style',
					'plugins/datatables-net/css/jquery.dataTables.min',
					'plugins/datatables-net/css/responsive.dataTables.min',



				)
			);



			// 			<script src="https://code.highcharts.com/highcharts.js"></script>
			// <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
			// <script src="https://code.highcharts.com/modules/exporting.js"></script>
			// <script src="https://code.highcharts.com/modules/export-data.js"></script>
			// <script src="https://code.highcharts.com/modules/accessibility.js"></script>

			// <script src="../../../app-assets/vendors/js/vendors.min.js"></script>
			// <script src="../../../app-assets/js/scripts/components/components-bs-toast.js"></script>
			// <script src="../../../app-assets/js/core/app-menu.js"></script>
			// <script src="../../../app-assets/js/core/app.js"></script>



			$this->_view->setJs_Specific(
				array(
					'plugins/vendors/js/vendors.min',
					'plugins/vendors/js/extensions/toastr.min',
					// 'plugins/vendors/js/forms/select/select2.full.min',
					'plugins/vendors/js/forms/validation/jquery.validate.min',
					'dist/js/core/app-menu',
					'dist/js/core/app',
					'dist/js/scripts/components/components-bs-toast',
					'dist/js/scripts/components/components-bs-toast.min',
					'dist/js/scripts/components/components-modals',
					'dist/js/scripts/components/components-modals.min',


					//data tables
					'plugins/datatables-net/js/jquery.dataTables.min',
					'plugins/datatables-net/js/dataTables.responsive.min',
					'dist/js/scripts/forms/form-input-mask',

					'plugins/vendors/js/forms/cleave/cleave.min',
					'plugins/vendors/js/tables/datatable/datatables.buttons.min',
					'plugins/vendors/js/tables/datatable/jszip.min',
					'plugins/vendors/js/tables/datatable/pdfmake.min',
					'plugins/vendors/js/tables/datatable/vfs_fonts',
					'plugins/vendors/js/tables/datatable/buttons.html5.min',
					'plugins/vendors/js/tables/datatable/buttons.print.min',
					'plugins/vendors/js/tables/datatable/dataTables.rowGroup.min',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					'plugins/highcharts9/js/highcharts',
					// <script src="https://code.highcharts.com/highcharts.js"></script>				 
					'plugins/highcharts9/modules/variable-pie',
					'plugins/highcharts9/js/highcharts-more', 
					// <script src="https://code.highcharts.com/highcharts-more.js"></script>

 

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

			$cixpt = array(
				'Almacen' => "CIXPT",
			);

			$areqpt = array(
				'Almacen' => "AREQPT",
			);

			$huapt = array(
				'Almacen' => "HUAPT",
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ConsultaVendedorAlmacen($cixpt);
			$ConsultaVendedorAlmacen = json_decode($result->ConsultaVendedorAlmacenResult, true);
			$result = $soap->ConsultaVendedorAlmacen($huapt);
			$ConsultaVendedorHuapt = json_decode($result->ConsultaVendedorAlmacenResult, true);
			$result = $soap->ConsultaVendedorAlmacen($areqpt);
			$ConsultaVendedorAreqpt = json_decode($result->ConsultaVendedorAlmacenResult, true);



			$this->_view->ConsultaVendedorAlmacen = $ConsultaVendedorAlmacen;
			$this->_view->ConsultaVendedorHuapt = $ConsultaVendedorHuapt;
			$this->_view->ConsultaVendedorAreqpt = $ConsultaVendedorAreqpt;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function VentasSede()
	{
		if (isset($_SESSION['usuario'])) {

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
			$result = $soap->ListadoVentasSede();
			$ListadoVentasSede = json_decode($result->ListadoVentasSedeResult, true);

			$this->_view->ListadoVentasSede = $ListadoVentasSede;

			$array1 = [];
			$i = 0;
			foreach ($ListadoVentasSede as $da) {
				$estado = $da['Sede'];
				$porentaje = $da['nu_total'];
				
			  $pie = array("name" => $estado, 'y' => floatval($porentaje), 'z' => floatval($porentaje));

				//   $pie = array("name" => $estado, 'y' => 505370, 'z' =>  92.9);

				// $pie = array("Peru" => $estado,  '505370',  '92.9');



				$array1 += ["$i" => $pie];
				$i++;
			}
			echo json_encode($array1);
		} else {
			$this->redireccionar('index/logout');
		}
	}
}
