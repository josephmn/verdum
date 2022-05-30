<?php

class tomarpedidobController extends Controller
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

			// $v_username    =  $_SESSION['correo'];

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

			$prod = array(
				'post' =>	1,
				'v_codprod' =>	'',
				'v_ruc' =>	$v_codcliente,
				'v_codVend' =>	$v_slsperid,
			);


			$cli = array(
				'post' =>	1,
				'v_slsperid' =>	$v_slsperid,
				'v_codcliente' =>	$v_codcliente,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoProducto($prod);
			$listadoproducto = json_decode($result->ListadoProductoResult, true);

			$result = $soap->ListadoDireccionCliente($cli);
			$listadodireccion = json_decode($result->ListadoDireccionClienteResult, true);

			$this->_view->listadoproducto = $listadoproducto;
			$this->_view->listadodireccion = $listadodireccion;

			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function valida_cliente_sunat()
	{
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_documento = $_POST['variable1'];

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

			$prod = array(
				'v_documento' =>	$v_documento,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoClienteSunat($prod);
			$data = json_decode($result->ListadoClienteSunatResult, true);

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


	 	public function buscar_producto()
	{
		function html_caracteres($string)
		{
			$string = str_replace(
				array('&amp;', '&Ntilde;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', 'C&eacute;'),
				array('&', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'é'),

				$string
			);
			return $string;
		}
		if (isset($_SESSION['usuario'])) {

			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_codprod = $_POST['codprod'];
			$v_codcliente = $_POST['v_ruc'];
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

			$prod = array(
				'post' =>	2,
				'v_codprod' =>	$v_codprod ,
				'v_ruc' =>	$v_codcliente,
				'v_codVend' =>	$v_slsperid,
			);

	

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoProducto($prod);
			$data = json_decode($result->ListadoProductoResult, true);

			if (count($data) > 0) {
				$v_nombreproducto  = $data[0]['v_nombreproducto'];
				$v_nombreproducto  = utf8_decode(html_caracteres($v_nombreproducto));
				$v_undmedida  = $data[0]['v_undmedida'];
			} else {
				$v_nombreproducto = "";
				$v_undmedida = "";
			}

			header('Content-type: application/json; charset=utf-8');
			echo $json->encode(
				array(
					"v_nombreproducto" => $v_nombreproducto,
					"v_undmedida" => $v_undmedida,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}




	public function valores_producto()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");

			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$v_codVend   = $_SESSION['v_codvend'];
			$v_ruc = $_POST['v_ruc'];
			$v_CodProd = $_POST['v_CodProd'];

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
				"v_codVend" => $v_codVend,
				"v_ruc" => $v_ruc,
				"v_CodProd" => $v_CodProd,
			);

			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoProductoPrecioVenta($param);
			$data = json_decode($result->ListadoProductoPrecioVentaResult, true);
			if (count($data) > 0) {
				$stock  = $data[0]['stock'];
			} else {
				$stock = 0;
			}
			$cabecera = "<tr>      
						<th class='text-center'  height='30%'  bgcolor='#404040'><b  style='color:#ffffff'  >Tope</b></th>                            
                        <th class='text-center'  height='60%'  bgcolor='#404040'><b  style='color:#ffffff'  >Corte</b></th>
                        <th class='text-center'  height='50%'  bgcolor='#404040'><b  style='color:#ffffff'  >Precio</b></th>
                    </tr>";
			$c = 0;
			$filas = "";
			foreach ($data as $dv) {
				$filas .= "<tr>
						<td  width='30%'   class='text-center'>" . $dv['tope'] . "</td>
                        <td  width='60%'   class='text-center'>" . $dv['Comentario'] . "</td>
                        <td  width='50%'   class='text-center'>" . $dv['precio'] . "</td>
                    </tr>";
				$c++;
			}

			header('Content-type:application/json;charset=utf-8');
			echo $json->encode(
				array(
					"cabecera" => $cabecera,
					"filas" => $filas,
					"stock" => $stock,
				)
			);
		} else {
			$this->redireccionar('index/logout');
		}
	}


	public function guardar_venta()
	{
		if (isset($_SESSION['usuario'])) {
			putenv("NLS_LANG=SPANISH_SPAIN.AL32UTF8");
			putenv("NLS_CHARACTERSET=AL32UTF8");
			$this->getLibrary('json_php/JSON');
			$json = new Services_JSON();

			$post = $_POST['post'];
			$codigoVendedor = $_SESSION['v_codvend'];
			$codCliente = $_POST['codCliente'];
			$horaInicio = $_POST['horaInicio'];
			$status = $_POST['status'];
			$total = $_POST['total'];
			$data = $_POST['data'];
			$username =  $_SESSION['correo'];
			$fueraruta = $_POST['fueraruta'];
			$v_id_envio = $_POST['v_id_envio'];
			$v_direccion = $_POST['v_direccion'];
			$token = $_SESSION['v_token'];

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
				"codigoVendedor" => $codigoVendedor,
				"codCliente" => $codCliente,
				"horaInicio" => $horaInicio,
				"status" => $status,
				"total" => $total,
				"data" => $data,
				"username" => $username,
				"fueraruta" => $fueraruta,
				"v_id_envio" => $v_id_envio,
				"v_direccion" => $v_direccion,
				"token" => $token,
			);


			$soap = new SoapClient($wsdl, $options);
			$result = $soap->GuardarVenta($param);
			$data = json_decode($result->GuardarVentaResult, true);

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
