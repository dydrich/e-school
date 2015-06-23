<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 18/06/15
 * Time: 8.36
 */

class MiddleSchoolClassbookPDF extends ClassbookPDF {

	protected function studentDetail() {
		$students =  $this->studentsData;
		foreach ($students as $student){
			$this->AddPage("P", "A4");
			$this->_page = $this->getPage();
			$this->setPage($this->_page, true);
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(0, 8, "Dettaglio alunno {$student['cognome']} {$student['nome']}", array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(25, 25, 25))), 1, "C", 0);
			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Assenze: ".count($student['assenze']), array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			foreach ($student['assenze'] as $assenza){
				if ($i > 4){
					$this->Ln();
					$i = 0;
				}
				$this->Cell(36, 5, format_date($assenza, SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 0, "C", 0);
				$i++;
			}
			$this->Ln();

			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Ritardi: ".count($student['ritardi']), array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			foreach ($student['ritardi'] as $ritardo){
				if ($i > 3){
					$this->Ln();
					$i = 0;
				}
				$this->Cell(45, 5, format_date($ritardo['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." (".substr($ritardo['ingresso'], 0, 5).")", 0, 0, "C", 0);
				$i++;
			}
			$this->Ln();

			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Uscite anticipate: ".count($student['anticipi']), array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(195, 195, 195))), 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			foreach ($student['anticipi'] as $anticipo){
				if ($i > 3){
					$this->Ln();
					$i = 0;
				}
				$this->Cell(45, 5, format_date($anticipo['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/")." (".substr($anticipo['uscita'], 0, 5).")", 0, 0, "C", 0);
				$i++;
			}
			$this->Ln();
		}
	}

	protected function days() {
		$days = $this->days;
		setlocale(LC_TIME, "it_IT.UTF8");
		foreach ($days as $day) {
			$giorno_str = strtolower(utf8_encode(strftime("%A %d %B", strtotime($day['data']))));
			$this->AddPage("P", "A4");
			$this->_page = $this->getPage();
			$this->setPage($this->_page, true);
			$this->SetFont('helvetica', 'B', '12');
			$this->Cell(0, 8, "Registro del giorno ".utf8_decode($giorno_str), "B", 1, "C", 0);
			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->Cell(90, 5, "Ingresso: " . substr($day['ingresso'], 0, 5), 0, 0, "C", 0);
			$this->Cell(90, 5, "Uscita: " . substr($day['uscita'], 0, 5), 0, 0, "C", 0);
			$this->Ln();
			$this->SetFont('helvetica', 'B', '10');

			$this->Cell(0, 5, "Lezioni: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			if ($day['firme']) {
				foreach ($day['firme'] as $firma) {
					$this->Cell(20, 5, $firma['ora'] . " ora", 0, 0, "L", 0);
					$this->Cell(80, 5, $firma['cognome'] . " " . $firma['nome'], 0, 0, "L", 0);
					$this->Cell(80, 5, $firma['materia'], 0, 0, "L", 0);
					$this->Ln();
					$this->MultiCell(180, 5, $firma['argomento'], "B", "L", 0);
				}
			}

			$this->Cell(0, 10, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Assenti: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			if ($day['assenti']) {
				foreach ($day['assenti'] as $abs) {
					if ($i > 2) {
						$this->Ln();
						$i = 0;
					}
					$this->Cell(60, 5, $abs, 0, 0, "L", 0);
					$i++;
				}
				$this->Ln();
			}


			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Ritardi: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			if ($day['ritardi']) {
				foreach ($day['ritardi'] as $ritardo) {
					if ($i > 2) {
						$this->Ln();
						$i = 0;
					}
					$this->Cell(60, 5, $ritardo['studente'] . " (" . substr($ritardo['ingresso'], 0, 5) . ")", 0, 0, "L", 0);
					$i++;
				}
				$this->Ln();
			}

			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Uscite anticipate: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			$i = 0;
			if ($day['anticipi']) {
				foreach ($day['anticipi'] as $anticipo) {
					if ($i > 2) {
						$this->Ln();
						$i = 0;
					}
					$this->Cell(60, 5, $anticipo['studente'] . " (" . substr($anticipo['uscita'], 0, 5) . ")", 0, 0, "L", 0);
					$i++;
				}
				$this->Ln();
			}

			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Assenze giustificate: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			if ($day['giustificazioni']) {
				$i = 0;
				foreach ($day['giustificazioni'] as $gius) {
					if ($i > 1) {
						$this->Ln();
						$i = 0;
					}
					$this->Cell(90, 5, $gius['stud'] . " (" . format_date($gius['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") . ")", 0, 0, "L", 0);
					$i++;
				}
				$this->Ln();
			}


			$this->Cell(0, 5, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Note: ", "B", 1, "L", 0);
			$this->SetFont('helvetica', '', '9');
			if ($day['note']) {
				foreach ($day['note'] as $note) {
					if (isset($note['desc_alunno'])) {
						$this->Multicell(180, 5, $note['desc_alunno'] . ": " . $note['descrizione'] . " (" . $note['doc'] . ")", 0, "L", 0);
					}
					else {
						$this->Multicell(180, 5, $note['descrizione'] . " (" . $note['doc'] . ")", 0, "L", 0);
					}
				}
			}
		}
	}

}
