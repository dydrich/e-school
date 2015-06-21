<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 20/06/15
 * Time: 19.26
 */

require_once 'SchoolPDF.php';
require_once 'PlanningMeeting.php';

class PlanningBookPDF extends SchoolPDF {

	protected $_page;
	protected $classes;
	protected $meetings;
	protected $year;
	protected $file;

	public function init(AnnoScolastico $y, $cl, $meets, $dir){

		$this->year = $y;
		$this->classes = $cl;
		$this->meetings = $meets;
		$this->file = $dir;
		/*
		 * PDF
		 */
		$this->SetCreator(PDF_CREATOR);
		$this->SetAuthor("Istituto comprensivo Nivola, Iglesias");
		$this->SetTitle('Registro della programmazione');

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

	public function createPDF() {
		$this->createCover();
		$this->meetings();
		$this->Output($this->file, 'F');
		return date("Y-m-d H:i:s");
	}

	protected function meetings() {
		$this->AddPage("P", "A4");
		$this->setPage(2, true);
		$this->_page = 2;
		foreach ($this->meetings as $meeting) {
			$string_date = format_date($meeting->getMeetingDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/");
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(0, 15, "Riunione del ".$string_date, 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', '', '9');
			$this->Cell(0, 2, "Ora d'inizio: ".substr($meeting->getStartTime(), 0, 5), 0, 1, 'L', 0, '', 0);
			$this->Cell(0, 2, "Ora di termine: ".substr($meeting->getEndTime(), 0, 5), 0, 1, 'L', 0, '', 0);
			$this->SetFont('helvetica', 'I', '9');
			$abs = ($meeting->getAbsents() != "") ? $meeting->getAbsents() : "nessuno";
			$this->Cell(0, 2, "Assenti: ".$abs, 0, 1, 'L', 0, '', 0);
			$this->Cell(0, 2, "", 0, 1, 'L', 0, '', 0);
			foreach ($meeting->getSubjects() as $subject => $text) {
				$this->SetFont('helvetica', 'B', '9');
				$this->Cell(30, 2, ucwords($subject), array('B' => array('color' => array(150, 150, 150))), 1, 'L', 0, '', 0);
				$this->SetFont('helvetica', '', '9');
				$this->MultiCell(0, 0, $text, 0, 'L', false, 1);
				$this->Cell(0, 1, "", 0, 1, 'L', 0, '', 0);
			}
			$this->SetFont('helvetica', 'B', '9');
			$this->Cell(30, 2, "Altro", 'B', 1, 'L', 0, '', 0);
			$this->SetFont('helvetica', '', '9');
			$this->MultiCell(0, 10, $meeting->getOther(), 0, 'L', false, 1);
			$this->AddPage("P", "A4");
			$this->_page++;
			$this->setPage($this->_page, true);
		}

	}

	protected function createCover(){
		$cls = array();
		foreach ($this->classes as $idc => $class) {
			$cls[] = $class['desc_cls'];
		}
		$f = implode(", ", $cls);
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 95, 28, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 50, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '15');
		$this->Cell(0, 20, "", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $this->year->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 25, "", 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 20, "Registro della programmazione ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, "Modulo: classi $f", 0, 1, 'C', 0, '', 0);
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
			$this->Cell($cw, 5, "Scuola primaria statale", "B", 1, 'C', 0, '', 0);

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
		$this->Cell($_cellw, 0, "Registro della programmazione", 'T', 0, 'L');
		// center aligned elements
		$this->Cell($_cellw, 0, date("d/m/Y").' '.date("H:i"), 'T', 0, 'C');
		//Print page number
		$this->Cell($_cellw, 0, $pagenumtxt, 'T', 0, 'R');
	}

}
