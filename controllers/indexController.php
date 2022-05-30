<?php

class indexController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->_view->setCss_Specific(
			array(
				'plugins/vendors/css/extensions/sweetalert2.min',
				'dist/css/bootstrap',
				'dist/css/colors',
				'dist/css/components',
				'dist/css/pages/page-auth',
				'plugins/vendors/css/extensions/ext-component-sweet-alerts'
			)
		);

		$this->_view->setJs_Specific(
			array(
				'plugins/vendors/js/jquery/jquery.min',
				'plugins/vendors/js/vendors.min',
				'plugins/vendors/js/extensions/sweetalert2.all.min',
				'dist/js/core/app-menu',
				'dist/js/core/app',
				'plugins/vendors/js/extensions/ext-component-sweet-alerts',
			)
		);

		$this->_view->setJs(array('index'));
		$this->_view->renderizar('index', true);
	}

	public function registro()
	{
		$this->_view->setCss_Specific(
			array(
				'plugins/vendors/css/extensions/sweetalert2.min',
				'dist/css/bootstrap',
				'dist/css/colors',
				'dist/css/components',
				'dist/css/pages/page-auth',
				'plugins/vendors/css/extensions/ext-component-sweet-alerts'
			)
		);

		$this->_view->setJs_Specific(
			array(
				'plugins/vendors/js/jquery/jquery.min',
				'plugins/vendors/js/vendors.min',
				'plugins/vendors/js/extensions/sweetalert2.all.min',
				'dist/js/core/app-menu',
				'dist/js/core/app',
				'plugins/vendors/js/extensions/ext-component-sweet-alerts',
			)
		);

		$this->_view->setJs(array('registro'));
		$this->_view->renderizar('registro', true);
	}

	// public function recupera_clave()
	// {
	// 	$this->_view->setJs(array('recuperaclave'));
	// 	$this->_view->renderizar('recuperaclave', true);
	// }

	// public function nuevoregistro()
	// {

	// 	putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
	// 	putenv("NLS_CHARACTERSET=AL32UTF8");

	// 	$this->getLibrary('json_php/JSON');
	// 	$json = new Services_JSON();

	// 	$nombre = $_POST['nombre'];
	// 	$apellidos = $_POST['apellidos'];
	// 	$correo = $_POST['correo'];
	// 	$password = $_POST['password'];
	// 	$perfil = 3; //usuario normal

	// 	$wsdl = 'http://localhost/RSWEB/WSReclutamiento.asmx?WSDL';

	// 	$options = array(
	// 		"uri" => $wsdl,
	// 		"style" => SOAP_RPC,
	// 		"use" => SOAP_ENCODED,
	// 		"soap_version" => SOAP_1_1,
	// 		"connection_timeout" => 60,
	// 		"trace" => false,
	// 		"encoding" => "UTF-8",
	// 		"exceptions" => false,
	// 	);

	// 	$soap = new SoapClient($wsdl, $options);

	// 	$params = array(
	// 		'nombres'		=> $nombre,
	// 		'apellidos' 	=> $apellidos,
	// 		'correo' 		=> $correo,
	// 		'clave' 		=> $password,
	// 		'perfil' 		=> intval($perfil),
	// 	);

	// 	$result = $soap->RegistroLogin($params);
	// 	$registro = json_decode($result->RegistroLoginResult, true);

	// 	$output = 0;

	// 	if ($registro[0]['v_respuesta'] == 2) {

	// 		$params2 = array(
	// 			'correo' => $correo,
	// 		);

	// 		$result = $soap->RegistroConsulta($params2);
	// 		$registroconsulta = json_decode($result->RegistroConsultaResult, true);

	// 		// envio de correo automatico de validacion de correo
	// 		$this->getLibrary('phpmailer/class.phpmailer');
	// 		$this->getLibrary('phpmailer/PHPMailerAutoload');

	// 		$mail = new PHPMailer;

	// 		$mail->isSMTP();
	// 		$mail->SMTPDebug = 0;
	// 		$mail->SMTPAuth = true;
	// 		// $mail->SMTPSecure = 'tls';
	// 		$mail->Mailer = 'smtp';
	// 		$mail->Host = 'mail.cafealtomayo.com.pe';
	// 		$mail->Username   = 'reportes@cafealtomayo.com.pe';
	// 		$mail->Password = 'rpt4m2020';
	// 		$mail->Port = 587;

	// 		$mail->From = ('reportes@cafealtomayo.com.pe');
	// 		$mail->FromName = "VERDUM PERÚ SAC";
	// 		// $mail->setFrom('reportes@cafealtomayo.com.pe', 'no replay verdum');
	// 		$mail->addReplyTo('reportes@cafealtomayo.com.pe', 'reportes');
	// 		$mail->addAddress($registroconsulta[0]['v_correo'], ($registroconsulta[0]['v_nombres'] . ' ' . $registroconsulta[0]['v_apellidos']));
	// 		$mail->Subject = 'VALIDACION DE CORREO ELECTRÓNICO';

	// 		$mail->isHTML(true);
	// 		$mail->CharSet = "utf-8";
	// 		$mail->Subject = 'VALIDACION DE CORREO ELECTRÓNICO';
	// 		$mail->Body = "
	// 		Hola <b>" . ($registroconsulta[0]['v_nombres'] . ' ' . $registroconsulta[0]['v_apellidos']) . ",</b>
	// 		<br>
	// 		<br>
	// 		Te enviamos la clave de confirmacion para poder válidar sus datos y correo.<br>
	// 		<br>
	// 		Clave: <b>" . $registroconsulta[0]['i_clave_confirmacion'] . "</b>
	// 		<br>
	// 		<br>
	// 		Saludo,<br>
	// 		Verdum Perú S.A.C.";

	// 		if (!$mail->send()) {
	// 			$output = 0; //	ERROR AL ENVIAR CORREO
	// 		} else {
	// 			$output = 1; // SE ENVIO CORRECTAMENTE
	// 		}

	// 		header('Content-type: application/json; charset=utf-8');

	// 		echo $json->encode(
	// 			array(
	// 				"registro" => $registro[0]['v_respuesta'],
	// 				"correo" => $output,
	// 			)
	// 		);

	// 	} else {
	// 		header('Content-type: application/json; charset=utf-8');

	// 		echo $json->encode(
	// 			array(
	// 				"registro" => $registro[0]['v_respuesta'], // 0 ya se encuentra correo registrado
	// 				"correo" => $output, // 0 no se envía correo
	// 			)
	// 		);
	// 	}

	// }

	// public function validarcodigo()
	// {
	// 	putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
	// 	putenv("NLS_CHARACTERSET=AL32UTF8");

	// 	$this->getLibrary('json_php/JSON');
	// 	$json = new Services_JSON();

	// 	$codigo = $_POST['codigo'];
	// 	$email = $_POST['email'];

	// 	$wsdl = 'http://localhost/RSWEB/WSReclutamiento.asmx?WSDL';

	// 	$options = array(
	// 		"uri" => $wsdl,
	// 		"style" => SOAP_RPC,
	// 		"use" => SOAP_ENCODED,
	// 		"soap_version" => SOAP_1_1,
	// 		"connection_timeout" => 60,
	// 		"trace" => false,
	// 		"encoding" => "UTF-8",
	// 		"exceptions" => false,
	// 	);

	// 	$soap = new SoapClient($wsdl, $options);

	// 	$params = array(
	// 		'codigo'	=> $codigo,
	// 		'correo'	=> $email,
	// 	);

	// 	$result = $soap->ValidarCodigo($params);
	// 	$validacion = json_decode($result->ValidarCodigoResult, true);

	// 	header('Content-type: application/json; charset=utf-8');

	// 	echo $json->encode(
	// 		array(
	// 			"validacion" => intval($validacion[0]['v_respuesta']),
	// 		)
	// 	);
	// }

	public function login()
	{
		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();

		$correo = trim($_POST['correo']);
		$clave = md5($_POST['clave']);

	// 	$correo = '77683117';
	// 	$clave = '276b7b884ada94f7b41f9685e5fb03c7s';
	// // 	$clave = '276b7b884ada94f7b41f9685e5fb03c7';

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

		$param = array(
			"correo" => $correo,
			"clave"	=> $clave,
		);

		$soap = new SoapClient($wsdl, $options);

		$result = $soap->Login($param);
		$login = json_decode($result->LoginResult, true);

		// $estado = 2; //user no existe
		// $url = ""; //url vacío

		// validamos que el logueo sea correcto
		if ($login[0]['v_existe'] == 1) {
			// PARA OBTENER LOS MENUS
			$param1 = array(
				"perfil" => $login[0]['i_perfil']
			);
			$result1 = $soap->Menu($param1);
			$menu = json_decode($result1->MenuResult, true);

			// PARA OBTENER LOS SUBMENUS
			$param2 = array(
				"perfil" => $login[0]['i_perfil']
			);

			$result2 = $soap->SubMenu($param2);
			$submenu = json_decode($result2->SubMenuResult, true);

			$estado = 1; // logueo exitoso
			$url = "/verdum/" . $menu[0]['v_link'] . "/index";
			$_SESSION['id'] = $login[0]['i_id'];
			$_SESSION['usuario'] = $login[0]['v_nombres'] . ', ' . $login[0]['v_apellidos'];
			$_SESSION['perfil'] = $login[0]['v_perfil'];
			$_SESSION['menus'] = $menu;
			$_SESSION['submenus'] = $submenu;
			$_SESSION['selmenu'] = "dashboard";
			$_SESSION['selsubmenu'] = "";
			$_SESSION['despliegue'] = "";
			$_SESSION['foto'] = $login[0]['v_foto'];
			$_SESSION['carnombre'] = $login[0]['v_carnombre'];
			$_SESSION['sede'] = $login[0]['v_sede'];
			$_SESSION['almacen'] = $login[0]['v_almacen'];
			$_SESSION['correo'] = $login[0]['v_correo'];
			$_SESSION['v_token'] = $login[0]['v_token'];
			$_SESSION['v_codvend'] = $login[0]['v_codvend'];

		} else if ($login[0]['v_existe'] == 0) {
			// $estado = 0; //clave incorrecta
			// $url = "";

			$estado = 0; //clave incorrecta
			$url = "";
			$_SESSION['id'] = "";
			$_SESSION['usuario'] = "";
			$_SESSION['perfil'] = "";
			$_SESSION['menus'] ="";
			$_SESSION['submenus'] ="";
			$_SESSION['selmenu'] = "dashboard";
			$_SESSION['selsubmenu'] = "";
			$_SESSION['despliegue'] = "";
			$_SESSION['foto'] = "";
			$_SESSION['carnombre'] = "";
			$_SESSION['sede'] ="";
			$_SESSION['almacen'] = "";
			$_SESSION['correo'] = "";
			$_SESSION['v_token'] = "";
			$_SESSION['v_codvend'] = "";
		};


		header('Content-type: application/json; charset=utf-8');

		echo $json->encode(
			array(
				"estado" => intval($estado),
				"url" => $url,
			)
		);
	}

	public function logout()
	{
		if (isset($_SESSION['usuario'])) {
			session_destroy();
			unset($_SESSION['usuario']);
			$this->redireccionar('index');
		} else {
			session_destroy();
			unset($_SESSION['usuario']);
			$this->redireccionar('index');
		}
	}
}
