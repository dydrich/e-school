<?php

require_once "TeacherRecordBookPDF.php";
require_once "RBUtilities.php";
require_once 'Encoding.php';

class FirstGradeTeacherRecordBookPDF extends TeacherRecordBookPDF{
	
	protected $datasource;
	protected $year;
	private $studentsData;
	private $lessons;
	private $mat;
	protected $att;
	
	public function setDatasource($ds){
		$this->datasource = $ds;
	}
	
	public function setYear($y){
		$this->year = $y;
	}
	
	public function init($t, $c, $i, $sub, $att){
		$this->teacher = $t;
		$this->cls = $c;
		$this->pubblicationId = $i;
		$this->mat = $sub;
		$this->att = $att;

		/*
		 * PDF
		 */
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor("Istituto comprensivo Nivola, Iglesias");
		$this->SetTitle('Registro personale del docente');
		
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
	
	public function createWholeRecordBook($teacher){
		
	}
	
	public function createRecordBook($teacher, $_cls, $sub, $students, $lessons, $substitutions){
		$subject = $sub['id'];
		$cls = $_cls['id'];
		$this->createCover($teacher, $sub['mat'], $_cls['cls'], $substitutions);
		$this->studentsList($students);
		@$this->sessionGrades($students, $subject);
		$this->lessons($lessons);
		@$this->studentGrades($students, $subject);
		@$this->lessonAbsences($lessons);
		@$this->attachments();
		$file = $this->path."registro_".$this->year->get_ID()."_".$teacher->getUid()."_".$cls."_".$subject.".pdf";
		$this->Output($file, 'F');
	}
	
	public function getRecordBook($teacher, $cls, $subject){
		
	}
	
	protected function createCover($teacher, $mat, $cls, $subs){
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
		$this->Cell(0, 20, "Registro personale del docente ".$teacher->getFullName(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, "Classe {$cls}", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 15, "Materia: {$mat}", 0, 1, 'C', 0, '', 0);

		if ($subs != null && $subs !== false) {
			$this->AddPage("P", "A4");
			$this->setPage(2, true);
			$this->SetFont('helvetica', 'B', '13');
			$this->Cell(190, 8, "Elenco supplenze", 'B', 0, 'L', 0, '', 0);
			$this->Ln();
			$this->Cell(0, 7, "", 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '11');
			foreach ($subs as $k => $sub){
				$this->Cell(100, 7, $sub['doc'], array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200))), 0, 'L', 0, '', 0);
				$this->Cell(90, 7, format_date($sub['data_inizio_supplenza'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." - ".format_date($sub['data_fine_supplenza'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." ({$sub['days']} giorni)", array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200))), 0, 'L', 0, '', 0);
				$this->Ln();
			}
			$this->_page = 2;
		}
	}
	
	protected function studentsList($st_list){
		$this->AddPage("P", "A4");
		$this->_page++;
		$this->setPage($this->_page, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(115, 8, "Elenco alunni", 'B', 0, 'L', 0, '', 0);
		$this->Cell(75, 8, "Esito finale", 'B', 0, 'L', 0, '', 0);
		$this->Ln();
		$this->Cell(0, 7, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '11');
		foreach ($st_list as $k => $st){
			$this->Cell(115, 7, $st['cognome']." ".$st['nome'], array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200))), 0, 'L', 0, '', 0);
			$this->Cell(55, 7, $st['esito'], array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200, 200, 200))), 0, 'L', 0, '', 0);
			$this->Ln();
		}
	}

	protected function sessionGrades($students, $subject){
		$this->AddPage("P", "A4");
		$this->_page++;
		$this->setPage($this->_page, true);
		$this->SetFont('helvetica', 'B', '11');
		$this->Cell(0, 12, "Valutazioni quadrimestrali", 'B', 1, 'C', 0, '', 0);
		$this->Cell(101, 8, "Alunno", 'LRTB', 0, 'L', 0, '', 0);
		$this->Cell(24, 8, "Voto", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(36, 8, "Assenze", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(24, 8, "Condotta", 'LRTB', 0, 'C', 0, '', 0);
		$this->Ln();
		$this->SetFont('helvetica', 'B', '11');
		$this->Cell(101, 5, "", 'LRTB', 0, 'L', 0, '', 0);
		$this->Cell(12, 5, "1Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "2Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "1Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "2Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "TOT", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "1Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Cell(12, 5, "2Q", 'LRTB', 0, 'C', 0, '', 0);
		$this->Ln();

		$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

		foreach ($students as $k => $v){
			$this->SetFont('helvetica', '', '11');
			$this->Cell(101, 5, $v['cognome']." ".$v['nome'], 'LRTB', 0, 'L', 0, '', 0);
			$row = $v;
			if ($subject == 26){
				$v1 = substr($voti_religione[RBUtilities::convertReligionGrade($row['voto1q'])], 0, 1);
			}
			else {
				$v1 = isset($row['voto1q']) ? $row['voto1q'] : "";
			}
			$this->Cell(12, 5, $v1, 'LRTB', 0, 'C', 0, '', 0);
			if ($subject == 26){
				$v2 = substr($voti_religione[RBUtilities::convertReligionGrade($row['voto2q'])], 0, 1);
			}
			else {
				$v2 = $row['voto2q'];
			}
			/*
			if ($v2 < 6 && $row['positivo'] == 1){
				$v2 = "6*";
			}
			*/
			$assenze1q = 0;
			if (isset($row['assenze1q'])) {
				$assenze1q = $row['assenze1q'];
			}
			else {
				$assenze1q = 0;
			}
			$this->Cell(12, 5, $v2, 'LRTB', 0, 'C', 0, '', 0);
			$this->Cell(12, 5, $assenze1q, 'LRTB', 0, 'C', 0, '', 0);
			$this->Cell(12, 5, $row['assenze2q'], 'LRTB', 0, 'C', 0, '', 0);
			$this->Cell(12, 5, $assenze1q + $row['assenze2q'], 'LRTB', 0, 'C', 0, '', 0);
			$this->Cell(12, 5, $row['comp1q'], 'LRTB', 0, 'C', 0, '', 0);
			$this->Cell(12, 5, $row['comp2q'], 'LRTB', 0, 'C', 0, '', 0);
			$this->Ln();
		}
		//$this->Cell(180, 12, "* Per voto di consiglio", 0, 1, 'L', 0, '', 0, 0, '', 'C');
	}

	protected function studentGrades($students, $subject){
		foreach ($students as $k => $student){
			$this->AddPage("P", "A4");
			$this->_page++;
			$this->setPage($this->_page, true);
			$this->SetFont('helvetica', 'B', '13');
			$this->Cell(180, 12, $student['cognome']." ".$student['nome'].": 1 quadrimestre", 0, 1, 'C', 0, '', 0, 0, '', 'C');
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(180, 8, "Valutazioni", "B", 1, 'L', 0, '', 0, 0, '', 'C');
			$this->Cell(180, 8, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$grades = $student['grades1q'];

			$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");

			foreach ($grades as $g){
				$this->SetFont('helvetica', '', '11');
				setlocale(LC_TIME, "it_IT.UTF8");
				list($y, $m, $d) = explode("-", $g['data']);
				$giorno_str = format_date($g['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
				$this->Cell(30, 7, $giorno_str, 0, 0, 'L', 0, '', 0);
				$voto = $g['voto'];
				if ($voto == "impreparato"){
					$voto = "IMP";
				}
				if ($subject == 26 && $voto != "IMP"){
					$voto = $voti_religione[RBUtilities::convertReligionGrade($voto)];
				}

				$this->Cell(30, 7, $voto, 0, 0, 'L', 0, '', 0);
				$this->SetFont('helvetica', '', '9');
				$this->Cell(120, 7, \ForceUTF8\Encoding::fixUTF8($g['desc']), 0, 0, 'L', 0, '', 0);
				$this->SetFont('helvetica', '', '11');
				$this->Ln();
			}
			$this->SetFont('helvetica', 'B', '12');
			$vq = $student['voto1q'];
			if ($subject == 26){
				$vq = $voti_religione[RBUtilities::convertReligionGrade($vq)];
			}
			$this->Cell(180, 8, "Valutazione quadrimestrale: ".$vq, 0, 1, 'L', 0, '', 0, 0, '', 'B');
			
			/*
			 * absences: first session
			 */
			// absences
			$this->Cell(180, 12, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(180, 8, "Assenze", "B", 1, 'L', 0, '', 0, 0, '', 'C');
			$this->Cell(180, 8, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$abs1 = $student['dett_abs1'];
			$cell = 0;
			foreach ($abs1 as $k => $_abs){
				if ($cell > 2){
					$this->Ln();
					$cell = 0;
				}
				$this->SetFont('helvetica', '', '11');
				setlocale(LC_TIME, "it_IT.UTF8");
				list($y, $m, $d) = explode("-", $k);
				$min = $_abs['time']%60;
				$h = intval($_abs['time'] - $min) / 60;
				if ($min > 0) {
					if ($min < 10){
						$min = "0{$min}";
					}
					$h .= ":{$min}";
				}
				
				//$giorno_str = format_date($k, SQL_DATE_STYLE, IT_DATE_STYLE, "/");
				$giorno_str = ucfirst(strftime("%a %d %B", strtotime($k)));
				$giorno_str .= " ({$h} ore)";
				$this->Cell(60, 7, $giorno_str, 0, 0, 'L', 0, '', 0);
				$cell++;
			}
			
			$this->AddPage("P", "A4");
			$this->_page++;
			$this->setPage($this->_page, true);
			$this->SetFont('helvetica', 'B', '13');
			$this->Cell(180, 12, $student['cognome']." ".$student['nome'].": 2 quadrimestre", 0, 1, 'C', 0, '', 0, 0, '', 'C');
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(180, 8, "Valutazioni", "B", 1, 'L', 0, '', 0, 0, '', 'C');
			$this->Cell(180, 8, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$grades2 = $student['grades2q'];
			foreach ($grades2 as $g){
				$this->SetFont('helvetica', '', '11');
				setlocale(LC_TIME, "it_IT.UTF8");
				list($y, $m, $d) = explode("-", $g['data']);
				$giorno_str = format_date($g['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
				$this->Cell(30, 7, $giorno_str, 0, 0, 'L', 0, '', 0);
				$voto = $g['voto'];
				if ($voto == "impreparato"){
					$voto = "IMP";
				}
				if ($subject == 26 && $voto != "IMP"){
					$voto = $voti_religione[RBUtilities::convertReligionGrade($voto)];
				}
				$this->Cell(30, 7, $voto, 0, 0, 'L', 0, '', 0);
				$this->SetFont('helvetica', '', '9');
				$this->Cell(120, 7, \ForceUTF8\Encoding::fixUTF8($g['desc']), 0, 0, 'L', 0, '', 0);
				$this->SetFont('helvetica', '', '11');
				$this->Ln();
			}
			$this->SetFont('helvetica', 'B', '12');
			$vq2 = $student['voto2q'];
			if ($subject == 26){
				$vq2 = $voti_religione[RBUtilities::convertReligionGrade($vq2)];
			}
			$this->Cell(180, 8, "Proposta di valutazione quadrimestrale: ".$vq2, 0, 1, 'L', 0, '', 0, 0, '', 'B');
			
			/*
			 * absences: second session
			 */
			$this->Cell(180, 12, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(180, 8, "Assenze", "B", 1, 'L', 0, '', 0, 0, '', 'C');
			$this->Cell(180, 8, "", 0, 1, 'L', 0, '', 0, 0, '', 'C');
			$abs2 = $student['dett_abs2'];
			$cell = 0;
			foreach ($abs2 as $k => $_abs){
				if ($cell > 2){
					$this->Ln();
					$cell = 0;
				}
				$this->SetFont('helvetica', '', '11');
				setlocale(LC_TIME, "it_IT.UTF8");
				list($y, $m, $d) = explode("-", $k);
				$min = $_abs['time']%60;
				$h = intval($_abs['time'] - $min) / 60;
				if ($min > 0) {
					if ($min < 10){
						$min = "0{$min}";
					}
					$h .= ":{$min}";
				}
				$giorno_str = ucfirst(strftime("%a %d %B", strtotime($k)));
				//$giorno_str = format_date($k, SQL_DATE_STYLE, IT_DATE_STYLE, "/");		
				$giorno_str .= " ({$h} ore)";
				$this->Cell(60, 7, $giorno_str, 0, 0, 'L', 0, '', 0);
				$cell++;
			}
		}
	}

	protected function lessons($lessons){
		$this->AddPage("P", "A4");
		$this->_page++;
		$this->setPage($this->_page, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 12, "Elenco lezioni", "B", 1, 'C', 0, '', 0, 0, '', 'C');
		$day = "";
		$mese = "";
		$cy = 12;
		foreach ($lessons as $les){
			setlocale(LC_TIME, "it_IT.UTF8");
			list($y, $m, $d) = explode("-", $les['data']);
			if ($cy > 370){
				$this->_page++;
				$this->AddPage("P", "A4");
				$this->setPage($this->_page, true);
				$cy = 0;
				$this->SetFont('helvetica', 'B', '11');
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				$this->Cell(180, 12, $str_month, "B", 1, 'C', 0, '', 0, 0, '', 'B');
				$cy += 12;
			}
			else if($mese != $m){
				$this->SetFont('helvetica', 'B', '11');
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				$this->Cell(180, 12, $str_month, "B", 1, 'C', 0, '', 0, 0, '', 'B');
				$cy += 12;
			}
			$this->SetFont('helvetica', '', '10');
			$giorno_str = ucfirst(strftime("%A %d", strtotime($les['data'])));
			$print_day = ($day != $les['data']) ? true : false;
			$this->Cell(30, 5, $giorno_str, 0, 0, 'L', 0, '', 0);
			$this->Cell(150, 5, \ForceUTF8\Encoding::fixUTF8($les['argomento']), 0, 0, 'L', 0, '', 0);
			$this->Ln();
			$cy += 10;
			$day = $les['data'];
			$mese = $m;
		}
	}

	protected function lessonAbsences($lessons){
		$this->AddPage("P", "A4");
		$this->_page++;
		$this->setPage($this->_page, true);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 12, "Elenco assenze per ora di lezione", "B", 1, 'C', 0, '', 0, 0, '', 'C');
		$day = "";
		$mese = "";
		$cy = 12;
		foreach ($lessons as $les){
			setlocale(LC_TIME, "it_IT.UTF8");
			list($y, $m, $d) = explode("-", $les['data']);
			if ($cy > 280){
				$this->_page++;
				$this->AddPage("P", "A4");
				$this->setPage($this->_page, true);
				$cy = 0;
				$this->SetFont('helvetica', 'B', '11');
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				$this->Cell(180, 12, $str_month, "B", 1, 'C', 0, '', 0, 0, '', 'B');
				$cy += 12;
			} 
			else if($mese != $m){
				$this->SetFont('helvetica', 'B', '11');
				$str_month = ucfirst(strftime("%B", strtotime($les['data'])));
				$this->Cell(180, 12, $str_month, "B", 1, 'C', 0, '', 0, 0, '', 'B');
				$cy += 12;
			}
			$this->SetFont('helvetica', '', '10');
			$giorno_str = ucfirst(strftime("%A %d", strtotime($les['data'])));
			$print_day = ($day != $les['data']) ? true : false;
			$this->Cell(30, 5, $giorno_str, 0, 0, 'L', 0, '', 0);
			$this->Cell(150, 5, utf8_decode(stripslashes($les['argomento'])), 0, 0, 'L', 0, '', 0);
			$this->Ln();
			$absents = array();
			$part_absents = array();
			foreach ($les['abs'] as $st_ab){
				$absents[] = $st_ab['cognome']." ".$st_ab['nome'];
			}
			foreach ($les['absh'] as $st_abh){
				$part_absents[] = $st_abh[1]." ".$st_abh[0]." ({$st_abh[2]} min)";
			}
			$this->SetFont('helvetica', 'B', '9');
			$this->Multicell(180, 5, (count($les['abs']) > 0) ? "Assenti: ".join(", ", $absents) : "Assenti: nessuno", 0, "L", 0, 1);
			$this->SetFont('helvetica', 'B', '9');
			$this->MultiCell(180, 5, (count($les['absh']) > 0) ? "Assenti parziali: ".join(", ", $part_absents) : "Assenti parziali: nessuno", 0, "L", 0, 1);
			//$this->Cell(180, 5, "Assenti parziali: ".join(", ", $part_absents), 0, 1, "L", 0, '', 0);
			$this->Cell(180, 2, "", 0, 1, "L", 0, '', 0);
			$cy += 28;
			$day = $les['data'];
			$mese = $m;
		}
	}

	protected function attachments(){
		$this->AddPage("P", "A4");
		//$this->_page++;
		//$this->setPage($this->_page, true);
		$this->lastPage();
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(180, 12, "Allegati al registro", "B", 1, 'C', 0, '', 0, 0, '', 'C');
		$this->Cell(0, 5, "", 0, 1, 'L', 0, '', 0);
		$this->SetFont('helvetica', '', '12');
		$this->Cell(180, 8, "Si allegano al presente i seguenti documenti:", 0, 1, 'L', 0, '', 0, 0, '', 'C');
		$this->SetFont('helvetica', '', '10');
		if (count($this->att) > 0){
			foreach ($this->att as $row){
				$this->Cell(180, 6, ucfirst(utf8_decode($row['file'])), 0, 1, 'L', 0, '', 0, 0, '', 'C');
			}
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
		$this->Cell($_cellw, 0, "{$this->teacher->getFullName()} - {$this->cls['cls']}: {$this->mat}", 'T', 0, 'L');
		// center aligned elements
		$this->Cell($_cellw, 0, date("d/m/Y").' '.date("H:i"), 'T', 0, 'C');
		//Print page number
		$this->Cell($_cellw, 0, $pagenumtxt, 'T', 0, 'R');
	}
	
}
