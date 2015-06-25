<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 02/04/14
 * Time: 20.30
 */

require_once 'Encoding.php';

class FirstGradeSupportTeacherRecordBookPDF extends FirstGradeTeacherRecordBookPDF {

	private $student;
	private $activities;

	public function init($t, $c, $i, $st, $att){
		$this->teacher = $t;
		$this->cls = $c;
		$this->pubblicationId = $i;
		$this->student = $st;
		$this->activities = $st['act'];
		$this->att = $att;

		/*
		 * PDF
		 */
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor("Istituto comprensivo Nivola, Iglesias");
		$this->SetTitle('Registro del docente di sostegno');

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
		$this->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

		//set image scale factor
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);

		//set some language-dependent strings
		//$this->setLanguageArray($l);

		// ---------------------------------------------------------
		$this->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
		// set font
		$this->SetFont('helvetica', '', 12);

		$this->setJPEGQuality(75);

		$this->AddPage("P", "A4");
		$this->_page = 1;
	}

	public function createRecordBook($teacher = null, $_cls = null, $sub = null, $students = null, $lessons = null, $substitutions = null){
		$cls = $this->cls['id_classe'];
		$this->createCover();
		$this->classData();
		$this->studentData();
		$this->medicalData();
		$this->activities();
		@$this->attachments();
		$file = $this->path."registro-sostegno_".$this->year->get_ID()."_".$this->teacher->getUid()."_".$cls."_".$this->student['id'].".pdf";
		$this->Output($file, 'F');
	}

	public function getRecordBook($teacher, $cls, $subject){

	}

	protected function createCover($teacher = null, $mat = null, $cls = null, $subs = null){
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 95, 28, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 50, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '15');
		$this->Cell(0, 20, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 25, "", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 20, "Registro personale del docente ".$this->teacher->getFullName(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, "Classe {$this->cls['cls']}", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 15, "Alunno: {$this->student['name']}", 0, 1, 'C', 0, '', 0);
	}

	protected function classData(){
		$this->AddPage("P", "A4");
		$this->setPage(2, true);
		$this->_page++;
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 10, "Elenco docenti della classe", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$teachers = $this->cls['teachers'];
		$this->SetFont('helvetica', 'B', '12');
		$this->Cell(90, 10, "   Docente", 'B', 0, 'L', 0, '', 0);
		$this->Cell(90, 10, "Materia", 'B', 0, 'C', 0, '', 0);
		$this->Ln();
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', '', '12');
		foreach ($teachers as $t){
			$this->Cell(90, 8, "   ".$t['name'], array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
			$this->Cell(90, 8, $t['subj'], 'B', 0, 'C', 0, '', 0);
			$this->Ln();
		}
		$this->Cell(0, 15, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 10, "Tipologia della classe", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', '', '11');
		$this->MultiCell(0, 0, $this->student['sostegno']['tipologia_classe']);
	}

	protected function studentData(){
		$this->AddPage("P", "A4");
		$this->setPage(3, true);
		$this->_page++;
		$this->SetFont('helvetica', 'B', '15');
		$this->SetFillColor(246, 246, 246);
		$this->Cell(0, 15, "Scheda anagrafica dell'alunno", array('BLRT' => array('color' => array(168, 168, 168))), 1, 'C', 1, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Cognome", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['cognome'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Nome", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['nome'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Nato a", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['luogo_nascita'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Data di nascita", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, format_date($this->student['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Residenza", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['indirizzo']." - ".$this->student['citta'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->Cell(0, 10, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 10, "Composizione nucleo familiare", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Padre", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['sostegno']['padre'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Madre", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['sostegno']['madre'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Fratelli/Sorelle", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['sostegno']['fratelli_sorelle'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(50, 8, "Altro", array('B' => array('color' => array(168, 168, 168))), 0, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(130, 8, $this->student['sostegno']['altro'], array('B' => array('color' => array(168, 168, 168))), 1, 'L', 0, '', 0);
		$this->Cell(0, 10, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 10, "Scuola e classe di provenienza", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$from = "";
		if ($this->student['sostegno']['scuola_provenienza'] != ""){
			$from = $this->student['sostegno']['scuola_provenienza'];
		}
		if ($this->student['sostegno']['classe_provenienza'] != ""){
			if ($from != ""){
				$from .= ", classe ".$this->student['sostegno']['classe_provenienza'];
			}
			else {
				$from = "Classe ".$this->student['sostegno']['classe_provenienza'];
			}
		}
		$this->Cell(180, 10, $from, '', 1, 'L', 0, '', 0);
	}

	protected function medicalData(){
		$this->AddPage("P", "A4");
		$this->setPage(4, true);
		$this->_page++;
		$this->SetFont('helvetica', 'B', '14');
		$this->SetFillColor(246, 246, 246);
		$this->Cell(0, 15, "Informazioni medico-diagnostiche", array('BLRT' => array('color' => array(168, 168, 168))), 1, 'C', 1, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 10, "Difficolta prevalenti", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->MultiCell(180, 70, \ForceUTF8\Encoding::fixUTF8($this->student['sostegno']['difficolta_prevalenti']), 0, 'L', 0, 1);
		$this->Cell(0, 10, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 10, "Terapia", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->SetFont('helvetica', '', '12');
		if ($this->student['sostegno']['terapia'] == ""){
			$this->Cell(0, 10, "Nessuna terapia in atto", 0, 1, 'C', 0, '', 0);
		}
		else {
			$this->Cell(0, 5, "In atto, di tipo: ", 0, 1, 'L', 0, '', 0);
			$terapies = explode("#", $this->student['sostegno']['tipo_terapia']);
			$index = 0;
			foreach ($terapies as $t){
				if ($t != '0'){
					switch ($index){
						case 3:
							$this->Cell(30, 5, "", 0, 0, 'C', 0, '', 0);
							$this->Cell(150, 5, " - Neuropsichiatrica ", 0, 1, 'L', 0, '', 0);
							break;
						case 1:
							$this->Cell(30, 5, "", 0, 0, 'C', 0, '', 0);
							$this->Cell(150, 5, " - Psicologica ", 0, 1, 'L', 0, '', 0);
							break;
						case 0:
							$this->Cell(30, 5, "", 0, 0, 'C', 0, '', 0);
							$this->Cell(150, 5, " - Ortofonica ", 0, 1, 'L', 0, '', 0);
							break;
						case 2:
							$this->Cell(30, 5, "", 0, 0, 'C', 0, '', 0);
							$this->Cell(150, 5, " - Psicomotoria ", 0, 1, 'L', 0, '', 0);
							break;
						case 4:
							$this->Cell(30, 5, "", 0, 0, 'C', 0, '', 0);
							$this->Cell(150, 5, " - ".$t, 0, 1, 'L', 0, '', 0);
							break;
					}
				}
				$index++;
			}

		}
		$this->AddPage("P", "A4");
		//$this->setPage(5, true);
		//$this->_page++;
		$this->SetFont('helvetica', 'B', '15');
		$this->Cell(0, 15, "Informazioni medico-diagnostiche", array('BLRT' => array('color' => array(168, 168, 168))), 1, 'C', 1, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 10, "Profilo dinamico-funzionale", array('B' => array('color' => array(68, 68, 68))), 1, 'L', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->MultiCell(180, 0, \ForceUTF8\Encoding::fixUTF8($this->student['sostegno']['profilo']), 0, 'L', 0, 1);
	}

	protected function activities(){
		$this->AddPage("P", "A4");
		//$this->_page = 6;
		//$this->setPage(6, true);
		$this->SetFont('helvetica', 'B', '15');
		$this->SetFillColor(246, 246, 246);
		$this->Cell(0, 15, "Elenco attivita svolte", array('BLRT' => array('color' => array(168, 168, 168))), 1, 'C', 1, '', 0);
		$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', '', '11');
		$h = 55.0;
		$i = 0;
		foreach ($this->activities as $les){
			//if ($i > 0) break;
			setlocale(LC_TIME, "it_IT.UTF8");
			$giorno_str = ucfirst(strftime("%A %d %B", strtotime($les['data'])));
			$this->SetFont('helvetica', 'B', '11');
			$h += 5;
			$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
			$this->Cell(180, 5, $giorno_str, array('B' => array('color' => array(108, 108, 108))), 1, 'L', 0, '', 0);
			//$this->SetY($h);
			$this->SetFont('helvetica', '', '11');
			$this->MultiCell(180, 10, \ForceUTF8\Encoding::fixUTF8($les['attivita']), 0, 'L', 0, '1', 15, $this->GetY() + 2);
			$h += $this->getLastH();
			$h += 5;/*
			if ($h > 250){
				$this->AddPage("P", "A4");
				$this->_page++;
				$this->setPage($this->_page, true);
				$this->SetFont('times', 'B', '15');
				$this->SetFillColor(246, 246, 246);
				$this->Cell(0, 15, "Elenco attivita svolte", array('BLRT' => array('color' => array(168, 168, 168))), 1, 'C', 1, '', 0);
				$this->Cell(0, 5, "", 0, 1, 'C', 0, '', 0);
				$this->SetFont('times', '', '11');
				$h = 55;
			}
			*/
			$i++;
		}
	}

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
			$this->Cell($cw, 5, "Scuola statale - secondaria di primo grado", "B", 1, 'C', 0, '', 0);

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
		$this->Cell($_cellw, 0, "{$this->teacher->getFullName()} - {$this->cls['cls']}: {$this->student['name']}", 'T', 0, 'L');
		// center aligned elements
		$this->Cell($_cellw, 0, date("d/m/Y").' '.date("H:i"), 'T', 0, 'C');
		//Print page number
		$this->Cell($_cellw, 0, $pagenumtxt, 'T', 0, 'R');
	}

} 
