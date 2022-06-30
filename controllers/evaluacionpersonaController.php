<?php

class evaluacionpersonaController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		if (isset($_SESSION['usuario'])) {
			$this->_view->conctructor_menu('evaluacionpersona', '');
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
					'plugins/vendors/js/forms/cleave/cleave.min',
					'plugins/highcharts9/js/highcharts',
					'plugins/highcharts9/modules/variable-pie',
					'plugins/highcharts9/js/highcharts-more',
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
			$result = $soap->ListadoEvaluacion();
			$ListadoEvaluacion = json_decode($result->ListadoEvaluacionResult, true);

			$this->_view->ListadoEvaluacion = $ListadoEvaluacion;


			$this->_view->setJs(array('index'));
			$this->_view->renderizar('index');
		} else {
			$this->redireccionar('index/logout');
		}
	}







	public function ver_pdf_evaluacion($i_id)
	// public function ver_pdf_evaluacion()
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

			$pdf = array(
				'i_id' => $i_id,
				// 'i_id' =>   1,
			);


			$soap = new SoapClient($wsdl, $options);
			$result = $soap->ListadoEvaluacionPdf($pdf);
			$ListadoEvaluacionPdf = json_decode($result->ListadoEvaluacionPdfResult, true);


			$timezone = -5;

			$dia2 =  strval(gmdate("YmdHis", time() + 3600 * ($timezone + date("I")))) . "A";
			$dia3 =  strval(gmdate("YmdHis", time() + 3600 * ($timezone + date("I")))) . "B";
			$dia4 =  strval(gmdate("YmdHis", time() + 3600 * ($timezone + date("I")))) . "C";


			function html_caracteres($string)
			{
				$string = str_replace(
					array('&amp;', '&Ntilde;', '&Aacute;', '&Eacute;', '&Iacute;', '&Oacute;', '&Uacute;', 'Ã?;'),
					array('&', 'Ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'),
					$string
				);
				return $string;
			}




			// $this->getLibrary('mpdf/mpdf');

			// $mpdf = new  Mpdf();

			// $mpdf->SetHeader('Document Title|Center Text|{PAGENO}');
			// $mpdf->SetFooter('Document Title');

			// $mpdf->defaultheaderfontsize = 10;
			// $mpdf->defaultheaderfontstyle = 'B';
			// $mpdf->defaultheaderline = 0;
			// $mpdf->defaultfooterfontsize = 10;
			// $mpdf->defaultfooterfontstyle = 'BI';
			// $mpdf->defaultfooterline = 0;
			// $mpdf->WriteHTML("RESULTADO INDIVIDUAL DE LA EVALUACIÓN DE DESEMPEÑO PERIODO 2021 (EJECUCIÓN 2022)");


			$this->getLibrary('fpdf/fpdf');
			$pdf = new FPDF('P', 'mm', 'A4');
			$pdf->AddPage();

			// $pdf->SetMargins(25, 4, 28);
			// $pdf->SetAutoPageBreak(true, 30);

			$pdf->SetXY(25, 10);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', 'U', 10);
			$pdf->MultiCell(160, 5, utf8_decode("RESULTADO INDIVIDUAL DE LA EVALUACIÓN DE DESEMPEÑO PERIODO 2021 (EJECUCIÓN 2022)"), 0, "C", false);

			///TABLA 1
			$pdf->SetXY(18, 20);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(23, 4, utf8_decode("EVALUADO "), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(56, 4, $ListadoEvaluacionPdf[0]['v_nombre'], 1, 0, 'L');

			$pdf->SetXY(93, 20);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(18, 4, utf8_decode("FEC.ING. "), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(55, 4, $ListadoEvaluacionPdf[0]['v_fingreso'], 1, 0, 'L');

			///cuadrito superior
			$pdf->SetXY(166, 20);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(28, 16, '', 1, 0, 'C');

			$pdf->SetXY(166, 20);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->MultiCell(28, 5, utf8_decode($ListadoEvaluacionPdf[0]['v_cuadro_ajuste']), 0, "C", false);




			$pdf->SetXY(166, 36);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->MultiCell(28, 4, $ListadoEvaluacionPdf[0]['f_pt_final_01'], 1, "C", false);
			//TERMINADO



			$pdf->SetXY(18, 24);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(23, 4, utf8_decode("GERENCIA "), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(56, 4, $ListadoEvaluacionPdf[0]['v_gerencia'], 1, 0, 'L');

			$pdf->SetXY(93, 24);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(18, 4, utf8_decode("ZONA PAIS "), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(55, 4,  $ListadoEvaluacionPdf[0]['v_zona_pais'], 1, 0, 'L');


			$pdf->SetXY(18, 28);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(23, 4, utf8_decode("Area             "), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(56, 4, $ListadoEvaluacionPdf[0]['v_area'], 1, 0, 'L');

			$pdf->SetXY(93, 28);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(18, 4, utf8_decode("Sede"), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(55, 4,  $ListadoEvaluacionPdf[0]['v_sede'], 1, 0, 'L');

			$pdf->SetXY(18, 32);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(23, 4, utf8_decode("CARGO"), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(56, 4, $ListadoEvaluacionPdf[0]['v_cargo'], 1, 0, 'L');

			$pdf->SetXY(93, 32);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(18, 4, utf8_decode("EVALUADOR"), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(55, 4, $ListadoEvaluacionPdf[0]['v_jefe'], 1, 0, 'L');


			$pdf->SetXY(18, 36);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(23, 4, utf8_decode("TIPO CARGO"), 1, 0, 'L', true);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(52, 4, $ListadoEvaluacionPdf[0]['v_tipo_cargo'], 1, 0, 'L');



			/// FIN TABLA 2

			$pdf->SetXY(10, 30);
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->MultiCell(60, 40, "1.Innovacion", 0, "C", false);

			$pdf->SetXY(30, 30);
			$pdf->MultiCell(60, 40, "2.Impacto", 0, "C", false);

			$pdf->SetXY(50, 30);
			$pdf->MultiCell(60, 40, "3.Pensamiento", 0, "C", false);

			$pdf->SetXY(70, 30);
			$pdf->MultiCell(60, 40, "4.Excelencia", 0, "C", false);

			$pdf->SetXY(90, 30);
			$pdf->MultiCell(60, 40, "5.Pasion", 0, "C", false);

			$pdf->SetXY(110, 30);
			$pdf->MultiCell(60, 40, "6.Orgullo", 0, "C", false);

			$pdf->SetXY(130, 30);
			$pdf->MultiCell(60, 40, "7.Compromiso", 0, "C", false);



			$pdf->SetXY(18, 53);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 8);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(25, 4, utf8_decode(""), 1, 0, 'C', false);
			$pdf->Cell(151, 4, 'Valores', 1, 0, 'L');


			//Fila2 

			$pdf->SetXY(18, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 6);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->Cell(25, 12, utf8_decode("EVALUADO"), 1, 0, 'C', false);
			$pdf->MultiCell(17, 6, utf8_decode("Cuenta de EVALUADO"), 1, 'C');


			$pdf->SetXY(60, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode("Pro.de C.1. Innovación y creatividad"), 1, 'C');

			$pdf->SetXY(78, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode("Pro.de C.2 .Impacto e influencia"), 1, 'C');


			$pdf->SetXY(95, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode("Pro.de C.3. Pensamiento estratégico"), 1, 'C');


			$pdf->SetXY(113, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode("Pro.de C.4. Excelencia       "), 1, 'C');


			$pdf->SetXY(130, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode("Pro.de  C.5Pasión          "), 1, 'C');

			$pdf->SetXY(146, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode("Pro. de C.6.Orgullo     "), 1, 'C');


			$pdf->SetXY(162, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode("Pro.de C.7.Compromiso"), 1, 'C');

			$pdf->SetXY(178, 57);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 6, utf8_decode("Pro. de PT.FINAL"), 1, 'C');


			//Fila2 
			$pdf->SetXY(18, 69);
			$pdf->AddFont('CenturyGothic', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic', '', 7);
			$pdf->Cell(25, 9, '', 1, 0, 'C', true);

			$pdf->SetXY(18, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 6);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(25, 3, utf8_decode($ListadoEvaluacionPdf[0]['v_nombre']), 0,  'C', false);


			//NUMEROS

			$pdf->SetXY(43, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 9, utf8_decode("1"), 1,  'C', false);


			$pdf->SetXY(60, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c1_inova_creatividad']), 1, 'C');

			$pdf->SetXY(78, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c2_impacto_influencia']), 1, 'C');


			$pdf->SetXY(95, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c3_pensamiento_estrategico']), 1, 'C');


			$pdf->SetXY(113, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c4_excelecia']), 1, 'C');

			$pdf->SetXY(130, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c5_pasiion']), 1, 'C');

			$pdf->SetXY(146, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c6_orgullo']), 1, 'C');


			$pdf->SetXY(162, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_c7_compromiso']), 1, 'C');

			$pdf->SetXY(178, 69);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 9, utf8_decode($ListadoEvaluacionPdf[0]['f_pt_final_01']), 1, 'C');


			//hasta aqui esta con bd (tuta)
			//Fila3
			$pdf->SetXY(18, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("Puntaje Máximo de Dimensión"), 1,  'L', false);

			$pdf->SetXY(60, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_inova_creatividad']), 1, 'C');

			$pdf->SetXY(78, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia']), 1, 'C');


			$pdf->SetXY(95, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra']), 1, 'C');


			$pdf->SetXY(113, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia']), 1, 'C');

			$pdf->SetXY(130, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion']), 1, 'C');

			$pdf->SetXY(146, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo']), 1, 'C');


			$pdf->SetXY(162, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso']), 1, 'C');

			$pdf->SetXY(178, 82);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma']), 1, 'C');



			//Fila4
			$pdf->SetXY(18, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("Debajo de expectativas"), 1,  'L', false);


			$pdf->SetXY(60, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_1']), 1, 'C');

			$pdf->SetXY(78, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_1']), 1, 'C');

			$pdf->SetXY(95, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_1']), 1, 'C');

			$pdf->SetXY(113, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia_1']), 1, 'C');

			$pdf->SetXY(130, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion_1']), 1, 'C');

			$pdf->SetXY(146, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo_1']), 1, 'C');

			$pdf->SetXY(162, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso_1']), 1, 'C');

			$pdf->SetXY(178, 86);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma1']), 1, 'C');




			//Fila5
			$pdf->SetXY(18, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("Cumple expectativas obs."), 1,  'L', false);

			$pdf->SetXY(60, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_2']), 1, 'C');

			$pdf->SetXY(78, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_2']), 1, 'C');

			$pdf->SetXY(95, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_2']), 1, 'C');

			$pdf->SetXY(113, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia_2']), 1, 'C');

			$pdf->SetXY(130, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion_2']), 1, 'C');

			$pdf->SetXY(146, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo_2']), 1, 'C');

			$pdf->SetXY(162, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso_2']), 1, 'C');

			$pdf->SetXY(178, 90);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma2']), 1, 'C');



			//Fila5
			$pdf->SetXY(18, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("g. Excede Expectativas"), 1,  'L', false);

			$pdf->SetXY(60, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_3']), 1, 'C');

			$pdf->SetXY(78, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_3']), 1, 'C');

			$pdf->SetXY(95, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_3']), 1, 'C');

			$pdf->SetXY(113, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia_3']), 1, 'C');

			$pdf->SetXY(130, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion_3']), 1, 'C');

			$pdf->SetXY(146, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo_3']), 1, 'C');

			$pdf->SetXY(162, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso_3']), 1, 'C');

			$pdf->SetXY(178, 94);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma3']), 1, 'C');




			//Fila5
			$pdf->SetXY(18, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("% Alcanzado en Dimensión"), 1,  'L', false);

			$pdf->SetXY(60, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_4'] . '%'), 1, 'C');

			$pdf->SetXY(78, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_4'] . '%'), 1, 'C');

			$pdf->SetXY(95, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_4'] . '%'), 1, 'C');

			$pdf->SetXY(113, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia_4'] . '%'), 1, 'C');

			$pdf->SetXY(130, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion_4'] . '%'), 1, 'C');

			$pdf->SetXY(146, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo_4'] . '%'), 1, 'C');

			$pdf->SetXY(162, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso_4'] . '%'), 1, 'C');

			$pdf->SetXY(178, 102);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma4'] . '%'), 1, 'C');



			//Fila5
			$pdf->SetXY(18, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(42, 4, utf8_decode("Brecha x Cubrir                  "), 1,  'L', false);

			$pdf->SetXY(60, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_2'] . '%'), 1, 'C');

			$pdf->SetXY(78, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_impact_influencia_5'] . '%'), 1, 'C');

			$pdf->SetXY(95, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(18, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_5'] . '%'), 1, 'C');

			$pdf->SetXY(113, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(17, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_excelencia_5'] . '%'), 1, 'C');

			$pdf->SetXY(130, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_pasion_5'] . '%'), 1, 'C');

			$pdf->SetXY(146, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_orgullo_5'] . '%'), 1, 'C');

			$pdf->SetXY(162, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_esp_compromiso_5'] . '%'), 1, 'C');

			$pdf->SetXY(178, 106);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetFillColor(225, 239, 220);
			$pdf->MultiCell(16, 4, utf8_decode($ListadoEvaluacionPdf[0]['f_suma5'] . '%'), 1, 'C');

			// // raaaa
			$this->getLibrary('jpgraph/src/jpgraph');
			$this->getLibrary('jpgraph/src/jpgraph_radar');
			$this->getLibrary('jpgraph/src/jpgraph_bar');
			$this->getLibrary('jpgraph/src/jpgraph_line');
			$this->getLibrary('jpgraph/src/jpgraph_pie');
			$this->getLibrary('jpgraph/src/jpgraph_pie3d');



			$graph = new RadarGraph(510, 400);
			// $graph->SetScale('lin', 0, 28);
			$graph->yscale->ticks->Set(12, 5);
			$graph->SetColor('#EFF7E5');
			$graph->SetShadow();

			$graph->SetCenter(0.5, 0.6);
			$graph->axis->SetFont(FF_FONT1, FS_BOLD);
			$graph->axis->SetWeight(2);

			// Uncomment the following lines to also show grid lines.
			$graph->grid->SetLineStyle('dashed');
			$graph->grid->SetColor('navy@0.5');
			$graph->grid->Show();
			$graph->ShowMinorTickMarks();

			// $graph->title->Set('Grafico 1');
			$graph->title->SetFont(FF_FONT1, FS_BOLD);
			$graph->SetTitles(array('1.Innovacion', '2.Impacto', '3.Pensamiento', '4.Excelencia', '5.Pasion', '6.Orgullo', '7.Compromiso'));

			// INNOVACION	
			$plot = new RadarPlot(array(
				$ListadoEvaluacionPdf[0]['f_c1_inova_creatividad'],
				$ListadoEvaluacionPdf[0]['f_c2_impacto_influencia'],
				$ListadoEvaluacionPdf[0]['f_c3_pensamiento_estrategico'],
				$ListadoEvaluacionPdf[0]['f_c4_excelecia'],
				$ListadoEvaluacionPdf[0]['f_c5_pasiion'],
				$ListadoEvaluacionPdf[0]['f_c6_orgullo'],
				$ListadoEvaluacionPdf[0]['f_c7_compromiso']
			));
			$plot->SetLegend('Evaluado');
			$plot->SetColor("red", "#f6bfbb");
			$plot->SetFillColor('#f6bfbb');
			$plot->SetLineWeight(2);

			// IMPACTO		
			$plot1 = new RadarPlot(array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso']
			));
			$plot1->SetLegend('Puntaje Maximo');
			$plot1->SetColor('blue', 'lightblue');
			$plot1->SetFillColor('lightblue');

			// PENSAMIENTO		
			$plot2 = new RadarPlot(array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_2'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_2'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_2'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_2'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_2']
			));

			$plot2->SetLegend('Comple Expectativas');
			$plot2->SetColor('#b45f06', '#e3c8ac');
			$plot2->SetFillColor('#e3c8ac');

			// PENSAMIENTO		
			$plot3 = new RadarPlot(array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_2'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_2'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_2'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_2'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_2']
			));

			$plot3->SetLegend('Debajo de Expectativas');
			$plot3->SetColor('#c90076', '#e3b2cf');
			$plot3->SetFillColor('#e3b2cf');

			$graph->Add($plot1);
			$graph->Add($plot2);
			$graph->Add($plot3);
			$graph->Add($plot);
			///FINAL DE GRAFICO 1	


			/////////////////////////////////////////////////////////////////////////////////////////////// GRAFICO 2
			// $datay1 = array(
			// 	// $ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_4'],
			// 	10 + '%',
			// 	$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_4'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_4'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_excelencia_4'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_pasion_4'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_orgullo_4'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_compromiso_4']
			// );

			// $datay2 = array(
			// 	$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_excelencia_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_pasion_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_orgullo_5'],
			// 	$ListadoEvaluacionPdf[0]['f_esp_compromiso_5']
			// );

			// // Create the graph. These two calls are always required
			// $graph1 = new Graph(600, 300);
			// $graph1->SetScale("textlin");
			// $graph1->yscale->ticks->Set(12, 5);
			// $graph1->SetColor('#EFF7E5');
			// $graph1->SetShadow();


			// $theme_class = new UniversalTheme;
			// $graph1->SetTheme($theme_class);


			// // $graph1->yaxis->SetTickPositions(array(0, 30, 60, 90, 120, 150, 180, 210), array(15, 45, 75, 105, 135));
			// $graph1->SetBox(false);
			// $graph1->SetUserFont(FF_FONT1, FS_BOLD, 9);
			// $graph1->title->Set('VALORES EN PORCENTAJE');

			// $graph1->ygrid->SetFill(false);
			// $graph1->xaxis->SetTickLabels(array('1.Innov', '2.Imp', '3.Pens', '4.Exc', '5.Pas.', '6.Org.', '7.Com.'));

			// $graph1->yaxis->HideLine(false);
			// $graph1->yaxis->HideTicks(false, false);


			// // Create the bar plots
			// $b1plot = new BarPlot($datay1);
			// $b2plot = new BarPlot($datay2);

			// // Create the grouped bar plot
			// $gbplot = new GroupBarPlot(array($b1plot, $b2plot));
			// // ...and add it to the graPH
			// $graph1->Add($gbplot);
			// $b1plot->SetFillGradient('blue', 'blue', GRAD_VER);
			// $b1plot->SetColor('blue');
			// $b1plot->SetFillColor('blue');
			// $b1plot->value->Show();
			// $b1plot->legend = 'Cumple';

			// $b2plot->SetFillGradient('red', 'red', GRAD_VER);
			// $b2plot->SetColor('red');
			// $b2plot->SetFillColor('red');

			// $graph->title->SetFont(FF_FONT1, FS_BOLD, 50);
			// $b2plot->value->Show();
			// $b2plot->legend = 'Brecha';

			// // $graph1->title->Set("Grafico 2");
			// $graph1->SetMargin(40, 16, 40, 40);
			// $graph1->legend->Pos(0.09, 0.3, 'right', 'center');
			// $graph1->legend->SetColumns(1);



			// $datay1b = array(13, 8, 19, 7, 17, 6);
			// $datay2b = array(4, 5, 2, 7, 5, 25);

			$datay1b = array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_4'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_4'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_4'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_4'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_4'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_4'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_4']
			);

			$datay2b = array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_5'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_5'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_5'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_5'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_5'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_5'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_5']
			);



			// Create the graph.
			$graph1 = new Graph(600, 380);
			$graph1->SetScale('textlin');
			$graph1->SetMarginColor('white');
			$graph1->SetColor('#EFF7E5');
			$graph1->SetShadow();
			$graph1->title->Set('VALORES EN PROCENTAJE');
			$graph1->title->SetFont(FF_FONT1, FS_BOLD, 14);
			$graph1->xaxis->SetTickLabels(array('1.Innovacion', '2.Impacto', '3.Pensa.', '4.Excelencia', '5.Pasion', '6.Organi.', '7.Compromiso'));

			// Create the first bar
			$bplot1b = new BarPlot($datay1b);
			$bplot1b->SetFillGradient('blue', 'blue', GRAD_VER);
			$bplot1b->SetColor('darkred');
			$bplot1b->SetWeight(0);
			$bplot1b->value->SetColor('white');
			$bplot1b->value->Show();
			$bplot1b->value->SetFont(FF_FONT1, FS_BOLD, 9);
			$bplot1b->value->SetFormat('%.0f%%');
			$bplot1b->legend = 'Cumple';

			// Create the second bar
			$bplot2b = new BarPlot($datay2b);
			$bplot2b->SetFillGradient('red', 'red', GRAD_VER);
			$bplot2b->SetColor('darkgreen');
			$bplot2b->SetWeight(0);
			$bplot2b->value->SetColor('white');
			$bplot2b->value->SetFont(FF_FONT1, FS_BOLD, 9);
			$bplot2b->value->Show();
			$bplot2b->value->SetFormat('%.0f%%');
			$bplot2b->legend = 'Brecha';

			// And join them in an accumulated bar
			$accbplot = new AccBarPlot(array($bplot1b, $bplot2b));
			$accbplot->SetColor('darkgray');
			$accbplot->SetWeight(1);
			$graph1->Add($accbplot);



			/////////////////////////////////////////////////////////////////////////////////////////////// grafico 3
			// Puntaje Máximo de Dimensión 		
			$l1datay = array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso']
			);
			// Debajo de expectativas			
			$l1datay1 = array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_1'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_1'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_1'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_1'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_1'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_1'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_1']
			);

			// d. Cumple expectativas con observaciones
			$l1datay2 = array(
				$ListadoEvaluacionPdf[0]['f_esp_inova_creatividad_2'],
				$ListadoEvaluacionPdf[0]['f_esp_impact_influencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pensamiento_estra_2'],
				$ListadoEvaluacionPdf[0]['f_esp_excelencia_2'],
				$ListadoEvaluacionPdf[0]['f_esp_pasion_2'],
				$ListadoEvaluacionPdf[0]['f_esp_orgullo_2'],
				$ListadoEvaluacionPdf[0]['f_esp_compromiso_2']
			);


			$l2datay = array(
				$ListadoEvaluacionPdf[0]['f_c1_inova_creatividad'],
				$ListadoEvaluacionPdf[0]['f_c2_impacto_influencia'],
				$ListadoEvaluacionPdf[0]['f_c3_pensamiento_estrategico'],
				$ListadoEvaluacionPdf[0]['f_c4_excelecia'],
				$ListadoEvaluacionPdf[0]['f_c5_pasiion'],
				$ListadoEvaluacionPdf[0]['f_c6_orgullo'],
				$ListadoEvaluacionPdf[0]['f_c7_compromiso']
			);

			$datax = array('1.Inn.', '2.Imp', '3.Pens', '4.Exc', '5.Pas.', '6.Org.', '7.Com.');

			// Create the graph. 
			$graph2 = new Graph(500, 300);
			$graph2->SetScale('textlin');
			$graph2->SetMargin(40, 23, 20, 70);
			$graph2->SetShadow();

			$theme_class = new VividTheme;
			$graph2->SetTheme($theme_class);

			// Create the linear error plot
			$l1plot = new LinePlot($l1datay);
			$l1plot->SetLegend('Puntaje Máximo');

			// Debajo de expectativas//////////////////////////////////////////	
			$l2plot = new LinePlot($l1datay1);
			$l2plot->SetLegend('Debajo expectativas');

			// Create the linear error plot
			$l3plot = new LinePlot($l1datay2);
			$l3plot->SetLegend('Cumple expectativas');
			$l3plot->SetColor('#FFAAAA:0.8', '#FFAAAA:2.8', GRAD_VER);
			// Create the bar plot

			$bplot = new BarPlot($l2datay);
			$bplot->SetLegend($ListadoEvaluacionPdf[0]['v_nombre']);
			$bplot->SetWeight(0);
			$bplot->SetFillGradient('#1669f7:0.7', '#1669f7:1.2', GRAD_VER);

			$graph2->Add($l3plot); //Cumple expectativas'
			$graph2->Add($bplot);
			$graph2->Add($l1plot); //Puntaje Maximo
			$graph2->Add($l2plot); //'Debajo de expectativas'

			$graph2->title->SetFont(FF_FONT1, FS_BOLD);
			$graph2->yaxis->title->SetFont(FF_FONT1, FS_BOLD);
			$graph2->xaxis->title->SetFont(FF_FONT1, FS_BOLD);
			$graph2->xaxis->SetTickLabels($datax);




			// $graph1->Stroke();


			$graph->Stroke($dia2 . ".png");
			$graph1->Stroke($dia3 . ".png");
			$graph2->Stroke($dia4 . ".png");


			$pdf->Image($dia2 . ".png", 18, 115, 80, 72);
			$pdf->Image($dia3 . ".png", 100, 115, 95, 72);
			$pdf->Image($dia4 . ".png", 50, 190, 120, 65);

			// $pdf->Ln(10);


			$pdf->SetXY(30, 266);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->Cell(44, 8, utf8_decode($ListadoEvaluacionPdf[0]['v_nombre']), 0, 0, 'C');

			$pdf->SetXY(30, 268);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->Cell(44, 8, utf8_decode("(Firma del Trabajador)"), 0, 0, 'C');





			$pdf->SetXY(130, 266);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->Cell(44, 8, utf8_decode($ListadoEvaluacionPdf[0]['v_jefe']), 0, 0, 'C');

			$pdf->SetXY(130, 268);
			$pdf->AddFont('CenturyGothic-Bold', '', 'GOTHICB.php');
			$pdf->SetFont('CenturyGothic-Bold', '', 7);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->SetFillColor(255, 255, 255);
			$pdf->Cell(44, 8, utf8_decode("(Firma del Jefe Inmediato)"), 0, 0, 'C');


			$pdf->Output();


			//////////// PARA TRABAJAR CON UNA NUEVA LIBRERIAS
			// $graph->Stroke($dia2 . ".png");
			// $graph1->Stroke($dia3 . ".png");
			// $graph2->Stroke($dia4 . ".png");

			// $mpdf->Image($dia2 . ".png", 18, 115, 80, 70);
			// $mpdf->Image($dia3 . ".png", 100, 120, 100, 70);
			// $mpdf->Image($dia4 . ".png", 50, 200, 120, 80);
			// $mpdf->Output();
		} else {
			$this->redireccionar('index/logout');
		}
	}
}
