<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 31/12/15
 * Time: 13.18
 */

namespace eschool;

require_once "SchoolPDF.php";


class MonthlyReportPDF extends \SchoolPDF
{
	public function createReport ($student, $month, $subjects, $date) {
		$final_letter = "o";
		if($student['sesso'] == "F"){
			$final_letter = "a";
		}
		switch ($student['anno_corso']){
			case 1:
				$cls = "PRIMA";
				break;
			case 2:
				$cls = "SECONDA";
				break;
			case 3:
				$cls = "TERZA";
				break;
		}

		$this->setPage(1, true);
		$this->SetFont('', '', '11');
		$this->Cell(80, 25, "", 0, 1, 'L', 0, '', 0);
		$this->Cell(100, 10, '', 0, 0, 'C', 0, '', 0);
		$this->Cell(80, 5, "Ai genitori dell'alunn{$final_letter} ", 0, 1, 'C', 0, '', 0);
		$this->Cell(100, 5, '', 0, 0, 'C', 0, '', 0);
		$this->Cell(80, 10, $student['cognome']." ".$student['nome'], 0, 1, 'C', 0, '', 0);
		$this->Cell(80, 50, "Oggetto: segnalazione insufficienze - mese di {$month}", 0, 1, 'L', 0, '', 0);
		$text = "Gentilissimi genitori, a seguito del consiglio di classe del mese di {$month}, desideriamo comunicarVi che vostr{$final_letter} figli{$final_letter}, iscritt{$final_letter} ";
		$text .= "alla classe {$cls}, sezione {$student['sezione']}, presenta delle votazioni non sufficienti in una o più discipline, di seguito riportate: ";
		$this->setCellHeightRatio(2.5);
		$this->MultiCell(180, 25, $text, 0, 'L', false, 1, 15, 110);
		$this->setCellHeightRatio(2.0);
		foreach ($subjects as $subject) {
			$this->Cell(10, 5, '', 0, 0, 'C', 0, '', 0);
			$this->Cell(80, 5, "-  ".$subject, 0, 1, 'L', 0, '', 0);
		}
		$this->MultiCell(100, 10, "", 0, 'L', false, 0, 15);
		$this->MultiCell(40, 2, "Iglesias, ".format_date($date, SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 'C', false, 0, 25, 240);
		$this->MultiCell(60, 2, "Il Dirigente scolastico", 0, 'C', false, 1, 125, 250);
		$this->MultiCell(60, 2, "Prof.ssa Giorgia Floris", 0, 'C', false, 1, 125, 255);
		$this->SetFont('', 'I', '7');
		$this->MultiCell(170, 20, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 0, 'R', false, 0, 35, 260);
	}

}

