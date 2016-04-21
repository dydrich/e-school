<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 18/06/15
 * Time: 8.33
 */

require_once 'SchoolPDF.php';
require_once 'RBTime.php';
require_once 'ClassbookPDF.php';

class PrimarySchoolClassbookPDF extends ClassbookPDF {

	protected function studentDetail(){
		$students =  $this->studentsData;
		$this->AddPage("P", "A4");
		$this->_page = $this->getPage();
		$this->setPage($this->_page, true);
		$idx = 1;
		foreach ($students as $student){
			if ($idx > 5) {
				$this->AddPage("P", "A4");
				$this->_page = $this->getPage();
				$this->setPage($this->_page, true);
				$idx = 1;
			}
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
			$idx++;
		}
	}

	protected function days(){
		$days = $this->days;
		setlocale(LC_TIME, "it_IT.UTF8");

		$this->AddPage("P", "A4");
		$this->_page = $this->getPage();
		$this->setPage($this->_page, true);
		$index = 0;

		foreach ($days as $day) {
			$giorno_str = strtolower(strftime("%A %d %B", strtotime($day['data'])));
			if ($index > 5) {
				$this->AddPage("P", "A4");
				$this->_page = $this->getPage();
				$this->setPage($this->_page, true);
				$index = 0;
			}
			$this->SetFont('helvetica', 'B', '10');
			$this->Cell(0, 5, "Registro del giorno ".$giorno_str, array("B" => array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(25, 25, 25))), 1, "C", 0);
			$this->Cell(0, 2, "", 0, 1, "C", 0);
			$this->Cell(90, 2, "Ingresso: " . substr($day['ingresso'], 0, 5), 0, 0, "C", 0);
			$this->Cell(90, 2, "Uscita: " . substr($day['uscita'], 0, 5), 0, 0, "C", 0);
			$this->Ln();
			$this->SetFont('helvetica', 'B', '9');

			$this->Cell(0, 2, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', '', '9');
			if ($day['assenti']) {
				$assenti = implode (", ", $day['assenti']);
			}
			else {
				$assenti = "nessuno";
			}
			$this->Cell(0, 4, "Assenti: $assenti", 0, 0, "L", 0);
			$this->Ln();

			$this->Cell(0, 2, "", 0, 1, "C", 0);
			$this->SetFont('helvetica', '', '9');
			$this->Cell(0, 4, "Note: ", "", 1, "L", 0);
			$this->SetFont('helvetica', '', '8');
			if ($day['note']) {
				foreach ($day['note'] as $note) {
					if (isset($note['desc_alunno'])) {
						$this->Cell(180, 5, $note['desc_alunno'] . ": " . $note['descrizione'] . " (" . $note['doc'] . ")", 0, 1, "L", 0);
					}
					else {
						$this->Cell(180, 5, $note['descrizione'] . " (" . $note['doc'] . ")", 0, 1, "L", 0);
					}
				}
			}
			$this->Cell(0, 8, "", 0, 1, "C", 0);
			$index++;
		}
	}

}
