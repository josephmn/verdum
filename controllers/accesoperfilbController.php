<?php

class accesoperfilbController extends Controller
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

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function cargar_menu_perfil()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$variable1 = $_POST['variable1'];
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
			$params = array(
				'post' => 1,
				'i_id_user' => $variable1,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoMenu($params);
			$data = json_decode($result->ListadoMenuResult, true);

			$filas = [];
			$i = 0;
			foreach ($data as $da) {
				$propiedades1 = array("i_id" => intval($da['i_id']), "Nombre" => $da['v_nombre'], "submenu" => $da['i_submenu'], "status" => $da['v_status']);
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

	public function carga_menu_perfil_acceso()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$variable1 = $_POST['variable1'];

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
			$params = array(
				'post' => 2,
				'i_id_user' => $variable1,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoMenu($params);
			$data = json_decode($result->ListadoMenuResult, true);

			$filas = [];
			$i = 0;
			foreach ($data as $da) {
				$propiedades1 = array("i_id" => intval($da['i_id']), "Nombre" => $da['v_nombre'], "submenu" => $da['i_submenu'], "perfil" => $da['v_descripcion'], "status" => $da['v_status']);
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
	
	public function guardar_acceso_menu()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$id_perfil = $_POST['variable1'];
			$ids = $_POST['ids'];

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
			$i = 0;
			foreach ($ids as $ch) {
				$params[$i] = array(
					'post'			=> intval($post),
					'id_perfil'			=> intval($id_perfil),
					'id_menu' 			=> intval($ch),
				);
				$result = $soap->GuardarAccesoMenu($params[$i]);
				$data = json_decode($result->GuardarAccesoMenuResult, true);
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

// ********************************************************************* desde aqui control de acceso para submenu
public function cargar_submenu_perfil()
{
	if (isset($_SESSION['usuario'])) {

		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();
		$variable1 = $_POST['variable1'];
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
		$params = array(
			'post' => 1,
			'i_id_user' => $variable1,
		);
		$soap = new SoapClient($wsdl, $options);
		$result = $soap->ListadoSubMenu($params);
		$data = json_decode($result->ListadoSubMenuResult, true);

		$filas = [];
		$i = 0;
		foreach ($data as $da) {
			$propiedades1 = array("i_id" => intval($da['i_id']), "Nombre" => $da['v_nombre'], "v_menu" => $da['v_menu'], "status" => $da['v_status']);
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
public function cargar_submenu_perfil_acceso()
{
	if (isset($_SESSION['usuario'])) {

		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();
		$variable1 = $_POST['variable1'];
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
		$params = array(
			'post' => 2,
			'i_id_user' => $variable1,
		);
		$soap = new SoapClient($wsdl, $options);
		$result = $soap->ListadoSubMenu($params);
		$data = json_decode($result->ListadoSubMenuResult, true);

		$filas = [];
		$i = 0;
		foreach ($data as $da) {
			$propiedades1 = array("i_id" => intval($da['i_id']), "Nombre" => $da['v_nombre'], "v_menu" => $da['v_menu'], "status" => $da['v_status']);
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


public function guardar_acceso_submenu()
{
	if (isset($_SESSION['usuario'])) {

		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();

		$post = $_POST['post'];
		$id_perfil = $_POST['variable1'];
		$ids = $_POST['ids'];

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
		$i = 0;
		foreach ($ids as $ch) {
			$params[$i] = array(
				'post'			=> intval($post),
				'id_perfil'			=> intval($id_perfil),
				'id_submenu' 			=> intval($ch),
			);
			$result = $soap->GuardarAccesoSubMenu($params[$i]);
			$data = json_decode($result->GuardarAccesoSubMenuResult, true);
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
