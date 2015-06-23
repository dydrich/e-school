<?php

require_once 'SchoolPDF.php';
require_once 'RBTime.php';

ini_set("display_errors", "0");

abstract class ClassbookPDF extends SchoolPDF {
	
	protected $_page;
	protected $classData;
	protected $days;
	protected $year;
	protected $cls;
	protected $studentsData;
	protected $filePath;
	protected $cdc;
	protected $schedule;
	protected $max_h;
	
	public function init(ClassbookData $classData, $studentsData, $cls, $year, $days, $cdc, $schedule, $h){
		$this->classData = $classData;
		$this->studentsData = $studentsData;
		$this->cls = $cls;
		$this->year = $year;
		$this->days = $days;
		$this->cdc = $cdc;
		$this->schedule = $schedule;
		$this->max_h = $h;
		/*
		 * PDF
		*/
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor("");
		$this->SetTitle('Registro di classe');
		
		// set default monospaced font
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		
		// set header and footer fonts
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 9.0));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 9.0));
		
		//set margins
		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);
		
		$this->setPrintHeader(true);
		$this->setPrintFooter(true);
		
		//set auto page breaks
		$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		
		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		// ---------------------------------------------------------
		$this->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
		// set font
		$this->SetFont('helvetica', '', 12);
		
		$this->setJPEGQuality(75);
		
		$this->AddPage("P", "A4");
		$this->_page = 1;
		
	}
	
	public function setFilePath($fp){
		$this->filePath = $fp;
	}
	
	public function createClassbook(){
		$this->createCover();
		$this->cdc();
		$this->schedule();
		$this->studentsList();
		$this->summary();
		$this->studentDetail();
		$this->days();
		$file = $this->filePath."registro_".$this->year->getYear()->get_descrizione()."_".$this->cls->get_anno().$this->cls->get_sezione().".pdf";
		$this->Output($file, 'F');
	}

	protected function createCover(){
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 95, 28, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 50, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '15');
		$this->Cell(0, 20, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $this->year->getYear()->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 25, "", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 20, "Registro di classe", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, $this->cls->to_string(false), 0, 1, 'C', 0, '', 0);
	}

	protected function cdc(){
		$this->_page = 2;
		$this->AddPage("P", "A4");
		$this->setPage(2, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 10, "Docenti del consiglio di classe", "B", 1, "C", 0);
		$this->Cell(0, 10, "", 0, 1, "C", 0);
		$this->SetFont('helvetica', '', '11');
		foreach ($this->cdc as $doc) {
			$materie = implode(", ", $doc['sec_f']);
			$this->Cell(60, 10, $doc['cognome']." ".$doc['nome'], array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100))), 0, "L", 0);
			$this->Cell(120, 10, $materie, array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(100, 100, 100))), 0, "L", 0);
			$this->Ln();
		}
	}
	
	protected function schedule(){
		$this->_page = 3;
		$this->AddPage("P", "A4");
		$this->setPage(3, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 10, "Orario definitivo delle lezioni", "B", 1, "C", 0);
		$this->Cell(0, 10, "", 0, 1, "C", 0);
		$this->SetFont('helvetica', 'B', '11');
		$schedule = $this->schedule;
		$this->Cell(12, 10, "Ora", "B", 0, "C", 0);
		$this->Cell(56, 10, "Lunedì", "B", 0, "C", 0);
		$this->Cell(56, 10, "Martedì", "B", 0, "C", 0);
		$this->Cell(56, 10, "Mercoledì", "B", 0, "C", 0);
		$this->Ln();
		$this->SetFont('helvetica', '', '10');
		for ($i = 1; $i <= $this->max_h; $i++) {
			$this->Cell(12, 10, $i, "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[1][$i]['materia'] != "Scegli") ? $schedule[1][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[2][$i]['materia'] != "Scegli") ? $schedule[2][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[3][$i]['materia'] != "Scegli") ? $schedule[3][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Ln();
		}
		$this->Cell(0, 20, "", 0, 1, "C", 0);
		
		$this->SetFont('helvetica', 'B', '11');
		$this->Cell(12, 10, "Ora", "B", 0, "C", 0);
		$this->Cell(56, 10, "Giovedì", "B", 0, "C", 0);
		$this->Cell(56, 10, "Venerdì", "B", 0, "C", 0);
		$this->Cell(56, 10, "Sabato", "B", 0, "C", 0);
		$this->Ln();
		$this->SetFont('helvetica', '', '10');
		for ($i = 1; $i <= $this->max_h; $i++) {
			$this->Cell(12, 10, $i, "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[4][$i]['materia'] != "Scegli") ? $schedule[4][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[5][$i]['materia'] != "Scegli") ? $schedule[5][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Cell(56, 10, ($schedule[6][$i]['materia'] != "Scegli") ? $schedule[6][$i]['materia'] : "", "B", 0, "C", 0);
			$this->Ln();
		}
	}
	
	protected function studentsList(){
		$this->_page = 4;
		$this->AddPage("P", "A4");
		$this->setPage(4, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 8, "Elenco alunni", "B", 1, "C", 0);
		$this->Cell(0, 5, "", 0, 1, "C", 0);
		$this->SetFont('helvetica', '', '9');		
		foreach ($this->studentsData as $student){
			$this->SetFont('helvetica', 'B', '9');
			$this->Cell(100, 5, $student['cognome']." ".$student['nome'], 0, 0, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$this->Cell(80, 5, "Data di nascita: ".format_date($student['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0, "L", 0);
			$this->Ln();
			$this->Cell(100, 3, "Indirizzo: ".$student['indirizzi']['indirizzo'], "B", 0, "L", 0);
			$telefono = "";
			if (!empty($student['indirizzi']["t1"])){
				$telefono = $student['indirizzi']["t1"];
			}
			if (!empty($student['indirizzi']["t2"])){
				$telefono .= " / ".$student['indirizzi']["t2"];
			}if (!empty($student['indirizzi']["t3"])){
				$telefono .= " / ".$student['indirizzi']["t3"];
			}
			$this->Cell(80, 3, "Telefono: ".$telefono, "B", 0, "L", 0);
			$this->Ln();
			//$this->Cell(0, 2, "", 0, 1, "C", 0);
		}
	}
	
	protected function summary(){
		$this->AddPage("P", "A4");
		$this->_page = $this->getPage();
		$this->setPage($this->_page, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 8, "Riepilogo dati assenze e lezioni", "B", 1, "C", 0);
		$this->Cell(0, 5, "", 0, 1, "C", 0);
		$totali = $this->classData->getClassSummary();
		$this->SetFont('helvetica', 'B', '11');
		$this->Cell(0, 5, "Dati classe", "B", 1, "L", 0);
		$this->SetFont('helvetica', '', '11');
		$this->Cell(90, 5, "Giorni di lezione: {$totali['giorni']} ({$totali['limite_giorni']})", 0, 0, "C", 0);
		$this->Cell(90, 5, "Ore di lezione: {$totali['ore']->toString(RBTime::$RBTIME_SHORT)} ({$totali['limite_ore']->toString(RBTime::$RBTIME_SHORT)})", 0, 0, "C", 0);
		$this->Ln();
		$this->Cell(0, 5, "", 0, 1, "C", 0);
		$this->SetFont('helvetica', 'B', '11');
		$this->Cell(0, 5, "Dati alunni", "B", 1, "L", 0);
		$this->SetFont('helvetica', 'B', '10');
		$this->Cell(80, 7, "Alunno", "B", 0, "L", 0);
		$this->Cell(25, 7, "Assenze", "B", 0, "C", 0);
		$this->Cell(25, 7, "% assenze", "B", 0, "C", 0);
		$this->Cell(25, 7, "Ore assenza", "B", 0, "C", 0);
		$this->Cell(25, 7, "%ore assenza", "B", 0, "C", 0);
		$this->Ln();
		$this->SetFont('helvetica', '', '10');
		$presence = $this->classData->getStudentsSummary();
		foreach ($presence as $k => $row){
			$perc_day = round((($row['absences'] / $totali['giorni']) * 100), 2);
			$absences = new RBTime(0, 0, 0);
			$absences->setTime($totali['ore']->getTime() - $row['presence']->getTime());
			$perc_hour = round((($absences->getTime() / $totali['ore']->getTime()) * 100), 2);
			if($perc_day == 0){
				$perc_day = "--";
			}
			else{
				$perc_day .= "%";
			}
			if($perc_hour == 0){
				$perc_hour = "--";
			}
			else{
				$perc_hour .= "%";
			}
			$this->Cell(80, 7, $row['name'], array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))), 0, "L", 0);
			$this->Cell(25, 7, $row['absences'], array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))), 0, "C", 0);
			$this->Cell(25, 7, $perc_day, array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))), 0, "C", 0);
			$this->Cell(25, 7, ($absences->getTime() > 0) ? $absences->toString(RBTime::$RBTIME_SHORT) : "--", array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))), 0, "C", 0);
			$this->Cell(25, 7, $perc_hour, array("B" => array('width' => .1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150))), 0, "C", 0);
			$this->Ln();
		}
	}
	
	abstract protected function studentDetail();
	
	abstract protected function days();
	
	public function Header() {
		if ($this->header_xobjid < 0) {
			// start a new XObject Template
			$this->header_xobjid = $this->startTemplate($this->w, $this->tMargin);
			$headerfont = $this->getHeaderFont();
			$headerdata = $this->getHeaderData();
			$this->y = $this->header_margin;
			if ($this->rtl) {
				$this->x = $this->w - $this->original_rMargin;
			} else {
				$this->x = $this->original_lMargin;
			}
			if (($headerdata['logo']) AND ($headerdata['logo'] != K_BLANK_IMAGE)) {
				$imgtype = $this->getImageFileType(K_PATH_IMAGES.$headerdata['logo']);
				if (($imgtype == 'eps') OR ($imgtype == 'ai')) {
					$this->ImageEps(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} elseif ($imgtype == 'svg') {
					$this->ImageSVG(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				} else {
					$this->Image(K_PATH_IMAGES.$headerdata['logo'], '', '', $headerdata['logo_width']);
				}
				$imgy = $this->getImageRBY();
			} else {
				$imgy = $this->y;
			}
			$cell_height = round(($this->cell_height_ratio * $headerfont[2]) / $this->k, 2) + 3.2;
			// set starting margin for text data cell
			if ($this->getRTL()) {
				$header_x = $this->original_rMargin + ($headerdata['logo_width'] * 1.1);
			} else {
				$header_x = $this->original_lMargin + ($headerdata['logo_width'] * 1.1);
			}
			$cw = $this->w - $this->original_lMargin - $this->original_rMargin - ($headerdata['logo_width'] * 1.1);
			$this->SetTextColor(0, 0, 0);
			// header title
			$this->SetFont("helvetica", "B", "9");
			$this->SetX(34);
			//echo $cw;
			$this->Cell($cw, 5, "ISTITUTO COMPRENSIVO \"C. NIVOLA\"", 0, 1, 'C', 0, '', 0);
			$this->SetFont("helvetica", "", "8");
			$this->SetX(34);
			$this->Cell($cw, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
			$this->SetFont("helvetica", "B", "8");
			$this->SetX(34);
			$cls = $this->classData->getCbClass();
			$school_order = $cls->getSchoolOrder();
			if ($school_order == 1) {
				$this->Cell($cw, 5, "Scuola statale - secondaria di primo grado", "B", 1, 'C', 0, '', 0);
			}
			else {
				$this->Cell($cw, 5, "Scuola primaria statale", "B", 1, 'C', 0, '', 0);
			}
				
			// header string
			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
	
			$this->endTemplate();
		}
		// print header template
		$x = 0;
		$dx = 0;
		if ($this->booklet AND (($this->page % 2) == 0)) {
			// adjust margins for booklet mode
			$dx = ($this->original_lMargin - $this->original_rMargin);
		}
		if ($this->rtl) {
			$x = $this->w + $dx;
		} else {
			$x = 0 + $dx;
		}
		$this->printTemplate($this->header_xobjid, $x, 0, 0, 0, '', '', false);
	}
	
	public function Footer() {
		$cur_y = $this->y;
		//echo $cur_y;
		$this->SetTextColor(0, 0, 0);
		//set style for cell border
		$line_width = 0.25;
		$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(128, 128, 128)));
		//print document barcode
		$barcode = $this->getBarcode();
		if (!empty($barcode)) {
			$this->Ln($line_width);
			$barcode_width = round(($this->w - $this->original_lMargin - $this->original_rMargin) / 3);
			$style = array(
					'position' => $this->rtl?'R':'L',
					'align' => $this->rtl?'R':'L',
					'stretch' => false,
					'fitwidth' => true,
					'cellfitalign' => '',
					'border' => false,
					'padding' => 0,
					'fgcolor' => array(0,0,0),
					'bgcolor' => false,
					'text' => false
			);
			$this->write1DBarcode($barcode, 'C128B', '', $cur_y + $line_width, '', (($this->footer_margin / 3) - $line_width), 0.3, $style, '');
		}
		if (empty($this->pagegroups)) {
			$pagenumtxt = $this->l['w_page'].' Pagina '.$this->getAliasNumPage().' di '.$this->getAliasNbPages();
		} else {
			$pagenumtxt = $this->l['w_page'].' Pagina '.$this->getPageNumGroupAlias().' di '.$this->getPageGroupAlias();
		}
		$this->SetY(290);
		$this->setCellPaddings(0, 1, 0, 0);
		if($this->CurOrientation == "P"){
			$_cellw = 62;
		}
		else{
			$_cellw = 88.6;
		}
		$this->SetFont("helvetica", "", "8");
		// left aligned elements
		$this->Cell($_cellw, 0, "{$this->year->getYear()->to_string()} -{$this->cls->to_string(false)}", 'T', 0, 'L');
		// center aligned elements
		$this->Cell($_cellw, 0, date("d/m/Y").' '.date("H:i"), 'T', 0, 'C');
		//Print page number
		$this->Cell($_cellw, 0, $pagenumtxt, 'T', 0, 'R');
	}
	
}
