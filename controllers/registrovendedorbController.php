<?php

class registrovendedorbController extends Controller
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

			$cboperfil = array(
				'post' => 1,
				'i_id' => 0,
			);
			$vendedor = array(
				"post" => 1,
				"i_id" => 0,
			);
			$almacen = array(
				"post" => 1,
				"i_id" => 0,
			);
			$zonaventa = array(
				"post" => 1,
				"i_id" => 0,
			);
			$estven = array(
				"Post" => 1,
				"v_id" => '',
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoPerfilVendedor($cboperfil);
			$ListadoPerfilVendedor = json_decode($result->ListadoPerfilVendedorResult, true);

			$result = $soap->ListadoVendedor($vendedor);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);

			$result = $soap->ListadoSede($almacen);
			$listasede = json_decode($result->ListadoSedeResult, true);

			$result = $soap->ListadoZonaVenta($zonaventa);
			$ListadoZonaVenta = json_decode($result->ListadoZonaVentaResult, true);

			$result5 = $soap->EstadoCliente($estven);
			$estadocliente = json_decode($result5->EstadoClienteResult, true);


			$this->_view->ListadoPerfilVendedor = $ListadoPerfilVendedor;
			$this->_view->listavendedor = $listavendedor;
			$this->_view->listasede = $listasede;
			$this->_view->ListadoZonaVenta = $ListadoZonaVenta;
			$this->_view->estadocliente = $estadocliente;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}




	public function consultar_vendedor()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$v_codvend = $_POST['variable1'];
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
				"v_codvend" => $v_codvend,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ConsultaVendedor($param);
			$data = json_decode($result->ConsultaVendedorResult, true);
			if (count($data) > 0) {
				$dato  = 0;
				$v_codvend  = $data[0]['v_codvend'];
				$v_documento  = $data[0]['v_documento'];
				$v_nombre  = $data[0]['v_nombre'];
				$v_cargo  = $data[0]['v_cargo'];
				$v_codsup  =  $data[0]['v_codsup'];
				$v_zonaventa  =  $data[0]['v_zonaventa'];
				$v_sede =  $data[0]['v_sede'];
				$v_jefeventa =  $data[0]['v_jefeventa'];
				$v_estado  = $data[0]['v_estado'];
				$i_idsup  = $data[0]['i_idsup'];
				$i_idzona  = $data[0]['i_idzona'];
				$i_idsede  = $data[0]['i_idsede'];
				$i_idjefe  = $data[0]['i_idjefe'];
			} else {
				$dato  = 1;
				$v_codvend  = "";
				$v_documento  = "";
				$v_nombre  = "";
				$v_cargo  = "";
				$v_codsup  = "";
				$v_zonaventa = "";
				$v_sede = "";
				$v_jefeventa = "";
				$v_estado = "";
				$i_idsup = "";
				$i_idzona = "";
				$i_idsede = "";
				$i_idjefe = "";
			}
			if (count($data) > 0) {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"dato" => $dato,
						"v_codvend" => $v_codvend,
						"v_documento" => $v_documento,
						"v_nombre" => $v_nombre,
						"v_cargo" => $v_cargo,
						"v_codsup" => $v_codsup,
						"v_zonaventa" => $v_zonaventa,
						"v_sede" => $v_sede,
						"v_jefeventa" => $v_jefeventa,
						"v_estado" => $v_estado,
						"i_idsup" => $i_idsup,
						"i_idzona" => $i_idzona,
						"i_idsede" => $i_idsede,
						"i_idjefe" => $i_idjefe,
					)
				);
			} else {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"dato" => $dato,
					)
				);
			}
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function consultar_perfilvendedor()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$perfil = $_POST['perfil'];
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
			$cboperfil = array(
				'post' => 2,
				'i_id' => $perfil,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoPerfilVendedor($cboperfil);
			$ListadoPerfilVendedor = json_decode($result->ListadoPerfilVendedorResult, true);

			$c = 0;
			$filas = "";
			foreach ($ListadoPerfilVendedor as $dv) {
				$estadoperfil = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadoperfil" => $estadoperfil,
					"perfilvend" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}

	public function consultar_supervisor()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$supervisor = $_POST['supervisor'];
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
			$vendedor = array(
				"post" => 2,
				"i_id" => $supervisor,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoVendedor($vendedor);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);
			$c = 0;
			$filas = "";
			foreach ($listavendedor as $dv) {
				$estadovend = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadovend" => $estadovend,
					"supervisor" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function consultar_zonaventa()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$zonaventa = $_POST['zonaventa'];
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
			$zonaventa = array(
				"post" => 2,
				"i_id" => $zonaventa,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoZonaVenta($zonaventa);
			$ListadoZonaVenta = json_decode($result->ListadoZonaVentaResult, true);
			$c = 0;
			$filas = "";
			foreach ($ListadoZonaVenta as $dv) {
				$estadozona = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadozona" => $estadozona,
					"zonaventa" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}

	public function consultar_almacen()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$almacen = $_POST['almacen'];
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
			$almacen = array(
				"post" => 2,
				"i_id" => $almacen,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoSede($almacen);
			$listasede = json_decode($result->ListadoSedeResult, true);
			$c = 0;
			$filas = "";
			foreach ($listasede as $dv) {
				$estadosede = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadosede" => $estadosede,
					"sede" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}





	public function consultar_jefeventa()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$jefeventa = $_POST['jefeventa'];
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
			$vendedor = array(
				"post" => 2,
				"i_id" => $jefeventa,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoVendedor($vendedor);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);
			$c = 0;
			$filas = "";
			foreach ($listavendedor as $dv) {
				$estadovend = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadovend" => $estadovend,
					"jefeventa" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	
	public function validar_usuario()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$correo = $_POST['nombreusuario'];
			$post = $_POST['post'];

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
				"post" => $post,
				"correo" => $correo,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ConsultaLogin($param);
			$data = json_decode($result->ConsultaLoginResult, true);

			if (count($data) > 0) {
				$estado = 0;
				$v_nombres  = $data[0]['v_nombres'];
				$v_apellidos  = $data[0]['v_apellidos'];
				$usuario  = $data[0]['v_correo'];
			} else {
				$estado = 1;
				$v_nombres = "";
			}
			if (count($data) > 0) {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"estado" => $estado,
						"v_nombres" => $v_nombres,
						"v_apellidos" => $v_apellidos,
						"usuario" => $usuario,
					)
				);
			}else{
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"estado" => $estado,	
					)
				);
			}


		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function guardar_vendedor()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = 0;
			$codigo = $_POST['codigo'];
			$dni = $_POST['dni'];
			$nombrevendedor = $_POST['nombrevendedor'];
			$idcargo = $_POST['idcargo'];
			$idsup = $_POST['idsup'];
			$zonavta = $_POST['zonavta'];
			$almacen = $_POST['almacen'];
			$jefevta = $_POST['jefevta'];
			$estado = $_POST['estado'];
			$usuario =  $_SESSION['correo'];

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
				'post'           =>	$post,
				'codigo'         =>	$codigo,
				'dni'            =>	$dni,
				'nombre' =>	$nombrevendedor,
				'idcargo'        =>	$idcargo,
				'idsup'          =>	$idsup,
				'idzonavta'        =>	$zonavta,
				'almacen'        =>	$almacen,
				'jefevta'        =>	$jefevta,
				'estado'         =>	$estado,
				'username'        =>	$usuario,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarVendedor($param);
			$data = json_decode($result->GuardarVendedorResult, true);

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
