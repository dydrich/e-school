<?php

require_once "../../lib/start.php";
require_once "../../lib/SchoolPDF.php";

class MYPDF extends SchoolPDF {

	public function createFirstPage($st){
		$final_letter = "o";
		if($st['sesso'] == "F"){
			$final_letter = "a";
		}
		$this->setPage(1, true);
		$this->Image('../../images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '24');
		$this->Cell(0, 30, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '16');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO IGLESIAS SUD EST", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 15, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 25, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '18');
		$this->Cell(0, 10, "SCHEDA PERSONALE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 5, "Iscritt{$final_letter} alla classe SECONDA, sezione B", 0, 1, 'C', 0, '', 0);	
	}

	public function report($st, $vt) {
		 
		$this->setPage(2, true);
		$this->SetFont('', 'B');
		$this->Write(9, $_SESSION['__current_year__']->to_string()."  -  Classe ".$st['anno_corso'].$st['sezione'], $align='C');
		$this->SetY(40.0);
		$this->Write(9, "Scheda di valutazione di ".$st['cognome']." ".$st['nome'], $align='L');
		$this->SetY(50.0);
		// Colors, line width and bold font
		$this->SetFillColor(131, 2, 29);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '13');
		// Header
		$w = array(100, 30);
		$this->Cell(100, 7, "Materia", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 1Q", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 2Q", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$max = $vt->num_rows;
		while ($row = $vt->fetch_assoc()){
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(100, 6, $row['desc_mat'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			$this->Cell(30, 6, $row['voto'], 'LR', 0, 'C', $fill);
			$this->Cell(30, 6, '', 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(160, 0, '', 'T');
		$this->SetFont('helvetica', '', 12);
		$this->SetY(160.0);
		$this->Write(9, "Il coordinatore", $align='L');
		$this->SetX(80.0);
		$this->Write(9, "Il segretario", $align='L');
		$this->SetX(140.0);
		$this->Write(9, "Il Dirigente scolastico", $align='L');
	}
}

?>