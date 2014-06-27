<?php

require_once "SchoolPDF.php";

class OutcomeTablePDF extends SchoolPDF{
	
	private $datasource = null;
	
	public function createTable($data, $cls, $source, $school_order){
		
		$this->datasource = $source;
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
		if ($school_order == 1){
			$this->Cell(0, 9, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
		}
		else if ($school_order == 2) {
			$this->Cell(0, 9, "Scuola primaria statale", 0, 1, 'C', 0, '', 0);
		}
		$this->SetFont('', 'B', '12');
		$this->Cell(0, 9, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->Cell(0, 9, "Esiti finali classe {$classe}", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '10');
		if ($cls->get_anno() == 3 && $school_order == 1){
			$this->Cell(80, 7, "Alunno", 1, 0, 'C', 0);
			$this->Cell(70, 7, "Esito", 1, 0, 'C', 0);
			$this->Cell(30, 7, "Voto ammissione", 1, 0, 'C', 0);
			$this->Ln();
			while ($row = $data->fetch_assoc()){
				$this->Cell(80, 7, $row['cognome']." ".$row['nome'], 1, 0, 'L', 0);
				$t = $row['esito'];
				$val = true;
				if ($row['id_esito'] == 17 || $row['positivo'] == 0){
					if ($row['id_esito'] == 17)
						$t .= " ai sensi dell'art. 3, c. 2 del DPR 122/2009";
					$avg = "--";
					$val = false;
					$this->Cell(100, 7, $t, 1, 0, 'L', 0);
					$this->Ln();
				}
				else {
					$sel_avg = "SELECT AVG(CASE WHEN voto > 5 THEN voto ELSE 6 END) FROM rb_scrutini WHERE anno = ".$_SESSION['__current_year__']->get_ID()." AND quadrimestre = 2 AND materia != 26 AND alunno = {$row['id_alunno']}";
					$res_avg = $this->datasource->executeCount($sel_avg);
					$avg = round($res_avg);
					$this->Cell(70, 7, $t, 1, 0, 'L', 0);
					$this->Cell(30, 7, $avg, 1, 0, 'C', 0);
					$this->Ln();
				}
				
				
			}
		}
		else {
			$this->Cell(80, 7, "Alunno", 1, 0, 'C', 0);
			$this->Cell(100, 7, "Esito", 1, 0, 'C', 0);
			$this->Ln();
			while ($row = $data->fetch_assoc()){
				$this->Cell(80, 7, $row['cognome']." ".$row['nome'], 1, 0, 'L', 0);
				$t = $row['esito'];
				if ($row['id_esito'] == 17){
					$t .= " ai sensi dell'art. 3, c. 2 del DPR 122/2009";
				}
				$this->Cell(100, 7, $t, 1, 0, 'L', 0);
				$this->Ln();
			}
		}
		$this->Cell(90, 15, "", 0, 0, 'L', 0);
		$this->Cell(80, 15, "Il Dirigente scolastico", 0, 0, 'R', 0);
		$this->Ln();
	}
}