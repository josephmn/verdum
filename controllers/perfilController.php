<?php

class perfilController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}
 
	public function index()
	{
		if (isset($_SESSION['usuario'])) {

			$this->_view->conctructor_menu('perfil', '');

			$this->_view->setCss_Specific(
				array(
					'dist/css/vendors.min',
					// 'dist/css/extensions/toastr.min',
					// 'dist/css/forms/select/select2.min',
					'plugins/vendors/css/extensions/sweetalert2.min',
					'dist/css/bootstrap',
					'dist/css/bootstrap-extended',
					'dist/css/colors',
					'dist/css/components',
					'dist/css/core/menu/menu-types/vertical-menu',
					// 'dist/css/plugins/forms/form-validation',
					'dist/css/plugins/extensions/ext-component-toastr',
					'dist/css/custom',
					'dist/css/style',
				)
			);
	
			$this->_view->setJs_Specific(
				array(
					'plugins/vendors/js/vendors.min',
					'dist/js/core/app-menu',
					'dist/js/core/app',
					'plugins/vendors/js/extensions/sweetalert2.all.min',
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

			$params = array(
				'post' => 2,
				'correo' => $_SESSION['correo'],
			);

			$result = $soap->ConsultaLogin($params);
			$login = json_decode($result->ConsultaLoginResult, true);

			$this->_view->login = $login;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');

		} else {
			$this->redireccionar('index/logout');
		}
    }

	public function mantenimiento_login()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$nombres = $_POST['nombre'];
			$apellidos = $_POST['apellido'];

			$_SESSION['usuario']=$nombres.', '.$apellidos;

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
				'id' => intval($_SESSION['id']),
				'nombres' => $nombres,
				'apellidos' => $apellidos,
				'user' => intval($_SESSION['id']),
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->MantLogin($params);
			$mantelogin = json_decode($result->MantLoginResult, true);

			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					"vicon" 		=> $mantelogin[0]['v_icon'],
					"vtitle" 		=> $mantelogin[0]['v_title'],
					"vtext" 		=> $mantelogin[0]['v_text'],
					"itimer" 		=> intval($mantelogin[0]['i_timer']),
					"icase" 		=> intval($mantelogin[0]['i_case']),
					"vprogressbar" 	=> $mantelogin[0]['v_progressbar'],
				)
			);

		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cambiar_clave()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$newpasswd = $_POST['newpasswd'];

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
				'dni' => $_SESSION['correo'],
				'username' =>  $_SESSION['correo'],
				'password' => $newpasswd,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->CambioClaveUsuario($params);
			$manpassword = json_decode($result->CambioClaveUsuarioResult, true);

			header('Content-type: application/json; charset=utf-8');

			echo $json->encode(
				array(
					"vicon" 		=> $manpassword[0]['v_icon'],
					"vtitle" 		=> $manpassword[0]['v_title'],
					"vtext" 		=> $manpassword[0]['v_text'],
					"itimer" 		=> intval($manpassword[0]['i_timer']),
					"icase" 		=> intval($manpassword[0]['i_case']),
					"vprogressbar" 	=> $manpassword[0]['v_progressbar'],
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}
}
