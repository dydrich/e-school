<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 04/06/14
 * Time: 18.09
 */

include_once 'SchoolPDF.php';

class SchoolReportPDF extends SchoolPDF {

	public function Footer() {
		$cur_y = $this->y;
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
			$pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' / '.$this->getAliasNbPages();
		} else {
			$pagenumtxt = $this->l['w_page'].' '.$this->getPageNumGroupAlias().' / '.$this->getPageGroupAlias();
		}
		$this->SetY($cur_y);
		$this->setCellPaddings(0, 1, 0, 0);
		if($this->CurOrientation == "P"){
			$_cellw = 60;
		}
		else{
			$_cellw = 88.6;
		}
		// left aligned elements
		$this->Cell($_cellw, 0, 'Stampato il '.date("d/m/Y").' alle ore'.date("H:i"), 'T', 0, 'L');
		// center aligned elements
		$this->Cell($_cellw, 0, '', 'T', 0, 'C');
		//Digital sign
		$this->SetFont('helvetica', 'I', '8');
		$this->Cell($_cellw, 0, "firma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 'T', 0, 'R');
	}

} 