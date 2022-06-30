<?php

class registroclienteController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {

			$this->_view->conctructor_menu('ventas', 'registrocliente');

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

			$v_username    =  $_SESSION['correo'];

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

			$tipodoc = array(
				'Type' =>	1,
				'id_cod' =>	'',
			);

			$tipocli = array(
				'Type' =>	1,
				'v_username' =>	$v_username,
				'v_ClassId' =>	'',
			);
			$priceclass = array(
				'Type' =>	1,
				'v_username' =>	$v_username,
				'v_PriceClassID' =>	'',
			);
			$condpago = array(
				'Type' =>	1,
				'v_username' =>	$v_username,
				'v_termsid' =>	'',
			);

			$znaruta = array(
				'Type' =>	1,
				'ZonaRutaID' =>	'',
				'v_username' =>	$v_username,
			);
			$paisp = array(
				'v_username' =>	$v_username,
			);

			$depa = array(
				'i_codigo' =>	0,
				'v_username' =>	$v_username,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoDocumento($tipodoc);
			$tipodocumento = json_decode($result->ListaTipoDocumentoResult, true);

			$result = $soap->ListaTipoCliente($tipocli);
			$tipocliente = json_decode($result->ListaTipoClienteResult, true);

			$result = $soap->ListaClasePrecio($priceclass);
			$claseprecio = json_decode($result->ListaClasePrecioResult, true);

			$result = $soap->ListaCondicionPago($condpago);
			$condicionpago = json_decode($result->ListaCondicionPagoResult, true);

			$result = $soap->ListaZonaRuta($znaruta);
			$zonaruta = json_decode($result->ListaZonaRutaResult, true);

			$result = $soap->Pais($paisp);
			$pais = json_decode($result->PaisResult, true);


			$result = $soap->Departamento($depa);
			$departamento = json_decode($result->DepartamentoResult, true);
			$param2 = array(
				"departamento" => 0,
				"i_idpro" => 0,
			);
			$result2 = $soap->Provincia($param2);
			$provincia = json_decode($result2->ProvinciaResult, true);
			$param3 = array(
				"provincia" => 0,
				"i_iddis" => 0,
			);
			$result3 = $soap->Distrito($param3);
			$distrito = json_decode($result3->DistritoResult, true);

			$param4 = array(
				"Type" => 1,
				"tipocli" => 'XXX',
				"subtipocli" => '',
			);

			$result4 = $soap->SubTipoCliente($param4);
			$subtipocli = json_decode($result4->SubTipoClienteResult, true);

			$estcli = array(
				"Post" => 1,
				"v_id" => '',
			);

			$result5 = $soap->EstadoCliente($estcli);
			$estadocliente = json_decode($result5->EstadoClienteResult, true);


			$this->_view->tipodocumento = $tipodocumento;
			$this->_view->tipocliente   = $tipocliente;
			$this->_view->claseprecio   = $claseprecio;
			$this->_view->condicionpago = $condicionpago;
			$this->_view->zonaruta      = $zonaruta;
			$this->_view->pais          = $pais;
			$this->_view->departamento  = $departamento;
			$this->_view->provincia = $provincia;
			$this->_view->distrito = $distrito;
			$this->_view->subtipocli = $subtipocli;
			$this->_view->estadocliente = $estadocliente;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function validar_departamento_cliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();



			$depa = $_POST['departamento'];
			$v_username    =  $_SESSION['correo'];


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


			$depa = array(
				'i_codigo' =>	$depa,
				'v_username' =>	$v_username,
			);


			$soap = new SoapClient($wsdl, $options);
			$result = $soap->Departamento($depa);
			$departamento = json_decode($result->DepartamentoResult, true);

			$c = 0;
			$filas = "";
			foreach ($departamento as $dv) {
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_codigo'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"depa" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}

	public function cargar_provincia()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$departamento = $_POST['departamento'];
			$i_idpro = $_POST['prov'];

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
				"departamento" => $departamento,
				"i_idpro" => $i_idpro,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->Provincia($param);
			$provincia = json_decode($result->ProvinciaResult, true);

			$c = 0;
			$filas = "";
			foreach ($provincia as $dv) {
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_codigo'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"data" => $filas,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cargar_distritos()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$provincia = $_POST['prov'];
			$i_iddis = $_POST['i_iddis'];

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
				"provincia" => $provincia,
				"i_iddis" => $i_iddis,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->Distrito($param);
			$distrito = json_decode($result->DistritoResult, true);

			$c = 0;
			$filas = "";
			foreach ($distrito as $dv) {
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['i_codigo'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"data" => $filas,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}

	public function cargar_subtipocliente()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$tipocli = $_POST['tipocliente'];

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
				"Type" => 1,
				"tipocli" => $tipocli,
				"subtipocli" => '',
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->SubTipoCliente($param);
			$subtipocli = json_decode($result->SubTipoClienteResult, true);

			$c = 0;
			$filas = "";
			foreach ($subtipocli as $dv) {
				$filas .= "<option value=" . $dv['v_subtipocli'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"data" => $filas,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function cargar_vendedor()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$ZonaRutaID = $_POST['ZonaRutaID'];
			// $ZonaRutaID='03JU101';
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
				"ZonaRutaID" => $ZonaRutaID,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ZonaRutaVendedor($param);
			$data = json_decode($result->ZonaRutaVendedorResult, true);

			if (count($data) > 0) {
				$v_namevend  = $data[0]['v_namevend'];
			} else {
				$v_namevend = "";
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"v_namevend" => $v_namevend,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}



	public function validar_cliente()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			// $CustId = '77683117';
			$CustId = $_POST['CustId'];

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
				"CustId" => $CustId,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ConsultaCliente($param);
			$data = json_decode($result->ConsultaClienteResult, true);

			$tipodocu = "";
			$tipocl = "";
			$subtplc = "";
			$pricecl = "";
			$terms = "";
			$znruta = "";
			$pais = "";
			$depa = "";
			$prov = "";
			$distrito = "";
			if (count($data) > 0) {
				$estado = 0;
				$v_Name  = $data[0]['v_Name'];
				$v_estado  = $data[0]['v_estado'];
				$v_cardtype  = $data[0]['v_cardtype'];
				// $tipodocu .= "<option value=" . $data[0]['v_cardtype'] . ">" . $data[0]['v_descardtype'] . "</option>";
				$tipodocu  = $data[0]['v_cardtype'];

				// $tipocl .= "<option value=" . $data[0]['v_classid'] . ">" . $data[0]['v_nameclass'] . "</option>";
				$tipocl  =  $data[0]['v_classid'];
				// $subtplc .= "<option value=" . $data[0]['v_subtipo'] . ">" . $data[0]['v_desubtipo'] . "</option>";
				$subtplc  =  $data[0]['v_subtipo'];
				// $pricecl .= "<option value=" . $data[0]['v_priceclassid'] . ">" . $data[0]['v_pricename'] . "</option>";
				$pricecl =  $data[0]['v_priceclassid'];

				// $terms .= "<option value=" . $data[0]['v_termid'] . ">" . $data[0]['v_termname'] . "</option>";
				$terms =  $data[0]['v_termid'];

				$direc  = $data[0]['v_direccion1'];
				$ref  = $data[0]['v_referencia'];
				$ruta = $data[0]['v_zonaruta'];
				$znruta .= "<option value=" . $data[0]['v_zonaruta'] . ">" . $data[0]['v_deszonaruta'] . "</option>";
				$vend  = $data[0]['v_vendedor'];
				$pais .= "<option value=" . $data[0]['i_codpais'] . ">" . $data[0]['v_pais'] . "</option>";
				$depa  = $data[0]['i_iddep'];
				// $depa .= "<option value=" . $data[0]['i_iddep'] . ">" . $data[0]['v_depa'] . "</option>";
				$prov  = $data[0]['i_codprovincia'];
				// $prov .= "<option value=" . $data[0]['i_codprovincia'] . ">" . $data[0]['v_nameprovincia'] . "</option>";
				$distrito  = $data[0]['i_coddistrito'];
				$valsunat  = $data[0]['i_valsunat'];
				// $distrito .= "<option value=" . $data[0]['i_coddistrito'] . ">" . $data[0]['v_nomdistrito'] . "</option>";
				$v_baja  = $data[0]['v_baja'];
				$v_correo  = $data[0]['v_correo'];
			} else {
				$estado = 1;
				$v_Name = "";
				$v_cardtype = "";
			}
			if (count($data) > 0) {
				header('Content-type: application/json; charset=utf-8');
				echo $json->encode(
					array(
						"estado" => $estado,
						"v_Name" => $v_Name,
						"v_estado" => $v_estado,
						"v_cardtype" => $v_cardtype,
						"tipodocu" => $tipodocu,
						"tipocl" => $tipocl,
						"subtplc" => $subtplc,
						"pricecl" => $pricecl,
						"terms" => $terms,
						"direc" => $direc,
						"ref" => $ref,
						"ruta" => $ruta,
						"znruta" => $znruta,
						"vend" => $vend,
						"pais" => $pais,
						"depa" => $depa,
						"prov" => $prov,
						"distrito" => $distrito,
						"valsunat" => $valsunat,
						"v_baja" => $v_baja,
						"v_correo" => $v_correo,
					)
				);
			} else {
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


	// raa

	public function validar_tipo_documento()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$id_cod = $_POST['tipodocu'];
			$v_username    =  $_SESSION['correo'];

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

			$tipodoc = array(
				'Type' =>	2,
				'id_cod' => $id_cod,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoDocumento($tipodoc);
			$tipodocumento = json_decode($result->ListaTipoDocumentoResult, true);
			$c = 0;
			$filas = "";
			foreach ($tipodocumento as $dv) {
				$estadotipo = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['Dato'] . ">" . $dv['Descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadotipo" => $estadotipo,
					"tipodoc" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function validar_condicion_pago()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();
			$v_termsid = $_POST['v_termsid'];
			$v_username    =  $_SESSION['correo'];
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

			$condpago = array(
				'Type' =>	2,
				'v_username' =>	$v_username,
				'v_termsid' =>  $v_termsid,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaCondicionPago($condpago);
			$condicionpago = json_decode($result->ListaCondicionPagoResult, true);
			$c = 0;
			$filas = "";
			foreach ($condicionpago as $dv) {
				$estadocnd = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['Dato'] . ">" . $dv['Descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadocnd" => $estadocnd,
					"condicion" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}





	public function validar_tipocliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_ClassId = $_POST['v_ClassId'];
			$v_username    =  $_SESSION['correo'];

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

			$tipocli = array(
				'Type' =>	2,
				'v_username' =>	$v_username,
				'v_ClassId' =>  $v_ClassId,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaTipoCliente($tipocli);
			$tipocliente = json_decode($result->ListaTipoClienteResult, true);
			$c = 0;
			$filas = "";
			foreach ($tipocliente as $dv) {
				$estadotipo = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['Dato'] . ">" . $dv['Descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadotipo" => $estadotipo,
					"tipocli" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function validar_subtipocliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$tipocli = $_POST['tipocli'];
			$subtipocli = $_POST['subtipocli'];

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

			$subtipocli = array(
				'Type' =>	2,
				'tipocli' =>	$tipocli,
				'subtipocli' =>  	$subtipocli,
			);

			$soap = new SoapClient($wsdl, $options);
			$result4 = $soap->SubTipoCliente($subtipocli);
			$subtipocli = json_decode($result4->SubTipoClienteResult, true);
			$c = 0;
			$filas = "";
			foreach ($subtipocli as $dv) {
				$estadosubtipo = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['v_subtipocli'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadosubtipo" => $estadosubtipo,
					"subtipocli" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}




	public function validar_listaprecio()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_Price = $_POST['v_Price'];
			$v_username    =  $_SESSION['correo'];

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

			$priceclass = array(
				'Type' =>	2,
				'v_username' =>	$v_username,
				'v_PriceClassID' =>	$v_Price,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaClasePrecio($priceclass);
			$claseprecio = json_decode($result->ListaClasePrecioResult, true);
			$c = 0;
			$filas = "";
			foreach ($claseprecio as $dv) {
				$estadoclase = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['Dato'] . ">" . $dv['Descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadoclase" => $estadoclase,
					"clase" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}


	public function validar_zonaruta()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			//	$zonaruta ='AJU0893';
			$zonaruta = $_POST['zonaruta'];
			$v_username    =  $_SESSION['correo'];

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

			$znaruta = array(
				'Type' =>	2,
				'ZonaRutaID' =>	$zonaruta,
				'v_username' =>	$v_username,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListaZonaRuta($znaruta);
			$zonaruta = json_decode($result->ListaZonaRutaResult, true);
			$c = 0;
			$filas = "";
			foreach ($zonaruta as $dv) {
				$estadoruta = 0;
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['Dato'] . ">" . $dv['Descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estadoruta" => $estadoruta,
					"rutas" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}

	public function validar_estado_cliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();


			$post = $_POST['post'];
			$v_id = $_POST['v_id'];

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


			$estcli = array(
				"Post" => $post,
				"v_id" => $v_id,
			);

			$soap = new SoapClient($wsdl, $options);
			$result5 = $soap->EstadoCliente($estcli);
			$estadocliente = json_decode($result5->EstadoClienteResult, true);

			$c = 0;
			$filas = "";
			foreach ($estadocliente as $dv) {
				$filas .= "<option " . $dv['v_default'] . " value=" . $dv['v_id'] . ">" . $dv['v_descripcion'] . "</option>";
				$c++;
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"estado" => $filas, $filas,
				)
			);
		} else {

			$this->redireccionar('index/logout');
		}
	}



	// SUCULENTO PARA CONSULTAR RUC DEL CLIENTE A SUNAT
	public function consulta_ruc_sunat()
	{
		$ruta = "https://ruc.com.pe/api/v1/consultas";
		$token = "ad79854f-3e40-4c68-91fa-4baa129fb9a5-257d4537-22f2-4662-9bc8-e813ef1655fd";

		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();
		$rucaconsultar = $_POST['CustId'];

		$data = array(
			"token"	=> $token,
			"ruc"   => $rucaconsultar
		);
		$data_json = json_encode($data);
		// Invocamos el servicio a ruc.com.pe
		// Ejemplo para JSON
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
			)
		);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		$leer_respuesta = json_decode($respuesta, true);


		if (isset($leer_respuesta['error'])) {
			$respuestasunatruc = 1;
		} else {
			$respuestasunatruc = 0;
			$ruc = $leer_respuesta['ruc'];
			$nombre_o_razon_social = $leer_respuesta['nombre_o_razon_social'];
			$estado_del_contribuyente = $leer_respuesta['estado_del_contribuyente'];
			$direccion_ruc = $leer_respuesta['direccion'];
			$estado_del_contribuyente = $leer_respuesta['estado_del_contribuyente'];
		}

		// header('Content-type: application/json; charset=utf-8');
		// echo $json->encode(
		// 	array(
		// 		"respuestasunatruc" => $respuestasunatruc,
		// 		"ruc" => $ruc,
		// 		"nombre_o_razon_social" => $nombre_o_razon_social,
		// 		"estado_del_contribuyente" => $estado_del_contribuyente,
		// 		"direccion_ruc" => $direccion_ruc,
		// 	)
		// );

		if (isset($leer_respuesta['error'])) {
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"respuestasunatruc" => $respuestasunatruc,
				)
			);
		} else {
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"respuestasunatruc" => $respuestasunatruc,
					"ruc" => $ruc,
					"nombre_o_razon_social" => $nombre_o_razon_social,
					"estado_del_contribuyente" => $estado_del_contribuyente,
					"direccion_ruc" => $direccion_ruc,
				)
			);
		}
	}

	// SUCULENTO PARA CONSULTAR  DNI  DEL CLIENTE A SUNAT
	public function consulta_dni_sunat()
	{
		$ruta = "https://ruc.com.pe/api/v1/consultas";
		$token = "ad79854f-3e40-4c68-91fa-4baa129fb9a5-257d4537-22f2-4662-9bc8-e813ef1655fd";

		putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
		putenv("NLS_CHARACTERSET=AL32UTF8");

		$this->getLibrary('json_php/JSON');
		$json = new Services_JSON();
		//$rucaconsultar = '77683123';
		$rucaconsultar = $_POST['CustId'];

		$data = array(
			"token"	=> $token,
			"dni"   => $rucaconsultar
		);
		$data_json = json_encode($data);
		// Invocamos el servicio a ruc.com.pe
		// Ejemplo para JSON
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $ruta);
		curl_setopt(
			$ch,
			CURLOPT_HTTPHEADER,
			array(
				'Content-Type: application/json',
			)
		);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$respuesta  = curl_exec($ch);
		curl_close($ch);
		$leer_respuesta = json_decode($respuesta, true);

		if (isset($leer_respuesta['error'])) {
			$respuestasunatdni = 1;
			$dni =  "";
			$nombre_completo = "";
		} else {
			$respuestasunatdni = 0;
			$dni = $leer_respuesta['dni'];
			$nombre_completo = $leer_respuesta['nombre_completo'];
		}


		if (isset($leer_respuesta['error'])) {
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"respuestasunatdni" => $respuestasunatdni,
				)
			);
		} else {
			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"respuestasunatdni" => $respuestasunatdni,
					"dni" => $dni,
					"nombre_completo" => $nombre_completo,
				)
			);
		}
	}


	public function guardarcliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = 0;
			$direccion1 = $_POST['direccion1'];
			$direccion2 = $_POST['direccion2'];
			$distrito = $_POST['distrito'];
			$pais = $_POST['pais'];
			$nomcliente = $_POST['nomcliente'];
			$documento = $_POST['documento'];
			$departamento = $_POST['departamento'];
			$zonaruta = $_POST['zonaruta'];
			$tipodocumento = $_POST['tipodocumento'];
			$tipocliente = $_POST['tipocliente'];
			$subtipocliente = $_POST['subtipocliente'];
			$usuario = $_SESSION['correo'];
			$listaprecio = $_POST['listaprecio'];
			$referencia = $_POST['referencia'];
			$condicionpago = $_POST['condicionpago'];
			$v_almacen  =  $_SESSION['almacen'];
			$provincia = $_POST['provincia'];
			$locationweb = $_POST['locationweb'];
			$valsunat = $_POST['valsunat'];
			$estado = $_POST['estadocliente'];
			$mail = $_POST['mail'];

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
				'post'       =>	$post,
				'direccion1' =>	$direccion1,
				'direccion2' => $direccion2,
				'distrito'	 => $distrito,
				'pais'	     => $pais,
				'nombre'	 => $nomcliente,
				'ruc'	     => $documento,
				'depa'	     => $departamento,
				'zonaruta'	 => $zonaruta,
				'tipodoc'	 => $tipodocumento,
				'tipocli'	 => $tipocliente,
				'subtipocli' => $subtipocliente,
				'usuario'	 => $usuario,
				'priceclas'	 => $listaprecio,
				'referencia' => $referencia,
				'condicion'  => $condicionpago,
				'almacen'  	 => $v_almacen,
				'provincia'  => $provincia,
				'location'   => $locationweb,
				'valsulnat'   => $valsunat,
				'estado'   => $estado,
				'correo'   => $mail,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarCliente($param);
			$data = json_decode($result->GuardarClienteResult, true);

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


	public function guardarbajacliente()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$documento = $_POST['documento'];
			$estadocliente = $_POST['estadocliente'];
			$motivo = $_POST['motivo'];
			$usuario = $_SESSION['correo'];

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
				'post'        => $post,
				'ruc'         => $documento,
				'estado'      => $estadocliente,
				'v_motivo'	  => $motivo,
				'v_username'  => $usuario,
			);
			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarBajaCliente($param);
			$data = json_decode($result->GuardarBajaClienteResult, true);

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


	public  function   send()
	{
		$enviado = 'Enviado: ' . date("Y-m-d H:i:s") . "\n";
		$subject = "Este es el asunto del mensaje - ";
		$message = 'Este es el mensaje a enviar.';

		// Cargando la librería de PHPMailer
		$this->getLibrary('phpmailer/class.phpmailer');
		$this->getLibrary('phpmailer/PHPMailerAutoload');

		// Creando una nueva instancia de PHPMailer
		$mail = new PHPMailer();


		// Indicando el uso de SMTP
		$mail->isSMTP();

		// Habilitando SMTP debugging
		// 0 = apagado (para producción)
		// 1 = mensajes del cliente
		// 2 = mensajes del cliente y servidor
		$mail->SMTPDebug = 0;

		// Agregando compatibilidad con HTML
		$mail->Debugoutput = 'html';

		// Estableciendo el nombre del servidor de email
		$mail->Host = 'smtp.gmail.com';

		// Estableciendo el puerto
		$mail->Port = 465;

		// Estableciendo el sistema de encriptación
		$mail->SMTPSecure = 'tls';

		// Para utilizar la autenticación SMTP
		$mail->SMTPAuth = true;

		// Nombre de usuario para la autenticación SMTP - usar email completo para gmail
		$mail->Username = "notificaciones.verdum@gmail.com";

		// Password para la autenticación SMTP
		$mail->Password = '$Verdum2022$';

		// Estableciendo como quién se va a enviar el mail
		$mail->From = 'notificaciones.verdum@gmail.com';


		// Estableciendo a quién se va a enviar el mail
		$mail->addAddress('programador.app03@verdum.com', 'Otro usuario');

		// El asunto del mail
		$mail->Subject = $subject . $enviado;

		// Estableciendo el mensaje a enviar
		$mail->MsgHTML($message);


		// Adjuntando una imagen
		//$mail->addAttachment('images/phpmailer_mini.png');

		// Enviando el mensaje y controlando los errores
		if (!$mail->send()) {
			echo "No se pudo enviar el correo. Intentelo más tarde.";
		} else {
			echo "Gracias por contactarnos.";
		}
	}
 
}
