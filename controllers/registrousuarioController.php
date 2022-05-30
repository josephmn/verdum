<?php

class registrousuarioController extends Controller
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
					'plugins/vendors/css/pickers/pickadate/pickadate',
					'plugins/vendors/css/pickers/flatpickr/flatpickr.min',
					'plugins/vendors/css/pickers/form-flat-pickr',
					'plugins/vendors/css/pickers/form-pickadate',
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
					'plugins/vendors/js/pickers/pickadate/picker',
					'plugins/vendors/js/pickers/pickadate/picker.date',
					'plugins/vendors/js/pickers/pickadate/picker.time',
					'plugins/vendors/js/pickers/pickadate/legacy',
					'plugins/vendors/js/pickers/flatpickr/flatpickr.min',
					'dist/js/scripts/forms/pickers/form-pickers',
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

			$param = array(
				"post" => 1,
				"i_id" => 0,
			);
			
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->Perfil($param);
			$Perfil = json_decode($result->PerfilResult, true);

			$result = $soap->ListadoVendedor($param);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);

			$result = $soap->ListadoSede($param);
			$listasede = json_decode($result->ListadoSedeResult, true);

			$result = $soap->ListadoCargo($param);
			$listacargo = json_decode($result->ListadoCargoResult, true);

			$this->_view->Perfil = $Perfil;
			$this->_view->listavendedor = $listavendedor;
			$this->_view->listasede = $listasede;
			$this->_view->listacargo = $listacargo;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function cargar_tabla_usuarios()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

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
			$result = $soap->ListaUsuario();
			$listausuario = json_decode($result->ListaUsuarioResult, true);

			$filas = [];
			$i = 0;
			foreach ($listausuario as $da) {
				$propiedades1 = array("i_id" => intval($da['i_id']), "v_nombres" => $da['v_nombres'], "v_apellidos" => $da['v_apellidos'], "v_correo" => $da['v_correo'], "v_perfil" => $da['v_perfil'], "v_carnombre" => $da['v_carnombre'], "v_codvend" => $da['v_codvend'], "v_sede" => $da['v_sede'], "d_fecharegistro" => $da['d_fecharegistro']);
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
				$v_sede  = $data[0]['v_sede'];
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
						"v_sede" => $v_sede,
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


	public function lista_usuario_update()
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
				$usuario  = $data[0]['v_correo'];
				$clave  = $data[0]['v_clave'];
				$idperfil  = $data[0]['i_idperfil'];
				$idcargo  = $data[0]['i_idcargo'];
				$idsede  = $data[0]['i_idsede'];
				$idvend  = $data[0]['i_idvend'];
			} else {
				$estado = 1;
				$v_nombres = "";
				$usuario = "";
				$clave = "";
				$idperfil = "";
				$idcargo = "";
				$idsede = "";
				$idvend = "";
			}
			if (count($data) > 0) {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"estado" => $estado,
						"v_nombres" => $v_nombres,
						"usuario" => $usuario,
						"clave" => $clave,
						"idperfil" => $idperfil,
						"idcargo" => $idcargo,
						"idsede" => $idsede,
						"idvend" => $idvend,
					)
				);
			}
		} else {
			$this->redireccionar('index/logout');
		}
	}




	public function validar_perfil()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$idperfil = $_POST['idperfil'];

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

			$perfil = array(
				"post" => 2,
				"i_id" => $idperfil,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->Perfil($perfil);
			$Perfil = json_decode($result->PerfilResult, true);

			$c = 0;
			$filas = "";
			foreach ($Perfil as $dv) {
				$estadoperfil = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadoperfil" => $estadoperfil,
					"perfiles" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function validar_cargo()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$idcargo = $_POST['idcargo'];

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

			$cargo = array(
				"post" => 2,
				"i_id" => $idcargo,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoCargo($cargo);
			$listacargo = json_decode($result->ListadoCargoResult, true);

			$c = 0;
			$filas = "";
			foreach ($listacargo as $dv) {
				$estadocargo = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_carnombre'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadocargo" => $estadocargo,
					"cargos" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function validar_vendedor()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$idvend = $_POST['idvend'];
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
				"i_id" => $idvend,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoVendedor($vendedor);
			$listavendedor = json_decode($result->ListadoVendedorResult, true);

			$c = 0;
			$filas = "";
			foreach ($listavendedor as $dv) {
				$estadovendedor = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_id'] . ">" . $dv['v_nombre'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadovendedor" => $estadovendedor,
					"vendedor" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}

	public function validar_sede()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$idsede = $_POST['idsede'];
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
			$sede = array(
				"post" => 2,
				"i_id" => $idsede,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoSede($sede);
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




	public function guardar_usuario()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$nombre = $_POST['nombre'];
			$apellidos = $_POST['apellidos'];
			$nombreusuario = $_POST['nombreusuario'];
			$contraseña = $_POST['contraseña'];
			$perfil = $_POST['perfil'];
			$idcargo = $_POST['idcargo'];
			$idvend = $_POST['idvend'];
			$idsede = $_POST['idsede'];

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
				'post'         =>	$post,
				'v_nombres'    =>	$nombre,
				'v_apellidos'  => $apellidos,
				'v_correo'     => $nombreusuario,
				'v_clave'	   => $contraseña,
				'perfil'	   => $perfil,
				'i_id_cargo'   => $idcargo,
				'i_id_vend'	   => $idvend,
				'i_id_sede'	   => $idsede,

			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarUsuario($param);
			$data = json_decode($result->GuardarUsuarioResult, true);

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
