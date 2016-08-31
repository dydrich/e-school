<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 23/06/15
 * Time: 11.56
 * stampa tabellone esiti esame conclusivo del primo ciclo
 */
require_once "SchoolPDF.php";

class MiddleSchoolFinalExamPDF extends SchoolPDF{

	public function createTable($data, $cls){

		$classe = $cls->get_anno().$cls->get_sezione();
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 90, 8, 15, 15, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '15');
		$this->Cell(0, 14, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '13');
		$this->Cell(0, 9, "ISTITUTO COMPRENSIVO \"C. NIVOLA\"", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '11');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '12');
		$this->Cell(0, 9, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);

		$this->SetFont('', 'B', '12');
		$this->Cell(0, 9, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 9, "Esiti finali classe {$classe}", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '10');
		$this->Cell(80, 7, "Alunno", 1, 0, 'C', 0);
		$this->Cell(70, 7, "Esito", 1, 0, 'C', 0);
		$this->Cell(30, 7, "Voto", 1, 0, 'C', 0);
		$this->Ln();
		foreach ($data as $row) {
			$this->Cell(80, 7, $row['cognome']." ".$row['nome'], 1, 0, 'L', 0);
			$t = $row['esito'];
			$v = $row['voto'];
			if ($v == 11) {
				$v = "10 e lode";
			}
			$val = true;
			$this->Cell(70, 7, "     ".$t, 1, 0, 'L', 0);
			$this->Cell(30, 7, $v, 1, 0, 'C', 0);
			$this->Ln();
		}
		$this->Cell(0, 10, "", 0, 1, 'L', 0);
		$this->Cell(130, 10, "", 0, 0, 'L', 0);
		$this->Cell(50, 10, "Il Presidente di commissione", 0, 0, 'C', 0);
		$this->Ln();
		$this->Cell(130, 2, "", 0, 0, 'L', 0);
		$this->SetFont('', 'I', '10');
		$this->Cell(50, 2, "Prof.ssa Lai Maria Romina", 0, 0, 'C', 0);
		$this->Ln();
	}
}
