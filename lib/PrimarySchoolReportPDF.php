<?php

require_once "SchoolPDF.php";

class PrimarySchoolReportPDF extends SchoolPDF {

	public function createFirstPage($st, $esito, $vt1, $vt2, $vr, $param, $vals1q, $vals2q, $cdc, $doc_religione, $esonerato) {
		$final_letter = "o";
		if($st['sesso'] == "F"){
			$final_letter = "a";
		}
		$cls = "";
		switch ($st['anno_corso']){
			case 1:
				$cls = "PRIMA";
				break;
			case 2:
				$cls = "SECONDA";
				break;
			case 3:
				$cls = "TERZA";
				break;
			case 4:
				$cls = "QUARTA";
				break;
			case 5:
				$cls = "QUINTA";
				break;
		}
		
		/*
		 * voti di comportamento scuola primaria
		*/
		$voti_comportamento_primaria = array("0" => array("nome" => "", "codice" => ""), "4" => array("nome" => "non adeguato", "codice" => "NA"),
				"5" => array("nome" => "parzialmente adeguato", "codice" => "PA"),
				"6" => array("nome" => "adeguato", "codice" => "AD")
		);
		
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('helvetica', 'B', '16');
		$this->Cell(0, 20, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 10, "Scuola primaria statale", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 10, "SCHEDA PERSONALE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '11');
		$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 8, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 20, "Vista la valutazione del consiglio di classe, si attesta che", 'LTR', 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		if ($esito['id_esito'] != 17){
			if ($st['anno_corso'] != 5){
				$this->Cell(0, 10, "l'alunn{$final_letter} ".utf8_encode($esito['desc_pagella'])." ", 'LR', 1, 'C', 0, '', 0);
				$this->Cell(0, 10, "della scuola primaria", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
			}
			else {
				$this->Cell(0, 10, "l'alunn{$final_letter} ".utf8_encode($esito['desc_pagella'])." ", 'LRB', 1, 'C', 0, '', 0);
				//$this->Cell(0, 10, "della scuola secondaria di primo grado", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
			}
		}
		else {
			$this->Cell(0, 10, "l'alunn{$final_letter} non è stat{$final_letter} ammess{$final_letter} alla classe successiva ", 'LR', 1, 'C', 0, '', 0);
			$this->Cell(0, 10, "ai sensi dell'art. 3, c. 2 del DPR 122/2009", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
		}
		$this->Cell(0, 10, "", '', 1, 'C', 0, '', 0);
		/*
		 * provvisorio
		 */
		// Colors, line width and bold font
		$this->SetFillColor(245, 245, 245);
		$this->SetTextColor(0);
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '12');
		// Header
		$w = array(100, 30);
		$this->Cell(100, 7, "Materia", 1, 0, 'C', 1);
		$this->Cell(40, 7, "Voto 1Q", 1, 0, 'C', 1);
		$this->Cell(40, 7, "Voto 2Q", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restore
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$max = count($vt1);
		for ($i = 0; $i < $max; $i++){
			//$this->SetX(40);
			if ($esonerato == 2 && $vt1[$i]['desc_mat'] == "Comportamento") {
				$this->setCellPaddings(10, 0, 0, 0);
				$this->Cell(120, 6, "Materia alternativa", 'LR', 0, 'L', $fill);
				$this->SetCellPadding(0);
				$this->Cell(30, 6, $vr[1], 'LR', 0, 'C', $fill);
				$this->Cell(30, 6, $vr[2], 'LR', 0, 'C', $fill);
				$this->Ln();
				$fill=!$fill;
			}
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(100, 6, $vt1[$i]['desc_mat'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			if ($vt1[$i]['id_materia'] == 40){
				$this->SetFont('', '', 9);
				if ($vt1[$i]['voto'] == ''){
					$this->Cell(40, 6, '', 'LR', 0, 'C', $fill);
				}
				else {
					$this->Cell(40, 6, $voti_comportamento_primaria[$vt1[$i]['voto']]['nome'], 'LR', 0, 'C', $fill);
				}
				$this->SetFont('');
			}
			else {
				$this->Cell(40, 6, $vt1[$i]['voto'], 'LR', 0, 'C', $fill);
			}
			$fgrade = $vt2[$i]['voto'];
			if ($vt1[$i]['id_materia'] == 40){
				$this->SetFont('', '', 9);
				$this->Cell(40, 6, $voti_comportamento_primaria[$fgrade]['nome'], 'LR', 0, 'C', $fill);
				$this->SetFont('');
			}
			else {
				$this->Cell(40, 6, $fgrade, 'LR', 0, 'C', $fill);
			}
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(180, 0, '', 'T');
		
		$this->setPage(2, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('helvetica', 'B', '16');
		$this->Cell(0, 20, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 10, "Scuola primaria statale", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 10, "SCHEDA PERSONALE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '11');
		$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 8, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->SetFont('helvetica', 'B', '16');
		$this->Cell(0, 15, "", 0, 1, 'C', 0, '', 0);
		$this->Cell(180, 15, "Giudizio sul livello globale di maturazione", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', '', '14');
		$this->Cell(0, 15, "", 0, 1, 'C', 0, '', 0);
		$this->SetFillColor(232, 234, 236);
		$this->Cell(180, 9, "I Quadrimestre", "LTRB", 1, 'C', 1, '', 0);
		
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '11');
		// Color and font restore
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach ($param as $row){
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(60, 6, $row['nome'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			$this->Cell(120, 6,utf8_encode($vals1q[$row['id']]), 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->SetFont('helvetica', '', '14');
		$this->Cell(180, 9, "II Quadrimestre", "LTRB", 1, 'C', 1, '', 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '11');
		// Color and font restore
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		reset($param);
		foreach ($param as $row){
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(60, 6, $row['nome'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			$this->Cell(120, 6, utf8_encode($vals2q[$row['id']]), 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->Cell(180, 0, '', 'T');
		$this->Ln();
		$this->SetFont('helvetica', '', '10');
		$this->MultiCell(100, 10, "Il consiglio di classe: {$cdc}", 0, 'L', false, 0, 15);
		$this->MultiCell(60, 2, "Il Dirigente scolastico", 0, 'C', false, 1, 125);
		$this->MultiCell(60, 2, "Prof.ssa Giorgia Floris", 0, 'C', false, 1, 125);
		$this->SetFont('', 'I', '7');
		$this->MultiCell(150, 20, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 0, 'R', false, 0, 35);


		$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		$rel = true;
		if ($esonerato > 0) {
			$rel = false;
		}
		// religione
		if ($rel){
			$this->setPage(3, true);
			$this->SetFont('', 'B');
			$this->SetDrawColor(120, 120, 120);
			$this->Image('../../images/ministero.jpg', 90, 8, 15, 15, 'JPG', '', '', false, '');
			$this->SetFont('helvetica', 'B', '11');
			$this->Cell(0, 10, "MINISTERO DELL'ISTRUZIONE, DELL'UNIVERSITÀ E DELLA RICERCA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '14');
			$this->Cell(0, 10, "NOTA PER LA VALUTAZIONE RELATIVA ALL'INSEGNAMENTO", 0, 1, 'C', 0, '', 0);
			$this->Cell(0, 0, "DELLA RELIGIONE CATTOLICA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'I', '12');
			$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '14');
			$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 0, "Via Pacinotti, Iglesias (CI)", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'BI', '12');
			$this->Cell(0, 20, "Scuola primaria statale", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '11');
			$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
			$this->SetY(140);
			$this->SetFont('helvetica', '', '12');
			$this->Cell(180, 22, "Dio e l'uomo. I valori etici e religiosi. La Bibbia e le altre fonti. Il linguaggio religioso", "LTRB", 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '11');
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "I Quadrimestre:   ".strtoupper($voti_religione[$vr[1]]), "LRB", 0, 'L', 0, '', 0);
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "II Quadrimestre: ".strtoupper($voti_religione[$vr[2]]), "RB", 0, 'L', 0, '', 0);
			$this->Ln();
			$this->Cell(180, 30, "", "LRB", 1, 'C', 0, '', 0);
			$this->Cell(180, 12, "", "", 1, 'L', 0, '', 0);
			$this->SetFont('helvetica', '', '10');
			$this->Cell(90, 5, "Il docente", "", 1, 'C', 0, '', 0);
			$this->Cell(90, 5, $doc_religione, "", 0, 'C', 0, '', 0);
			$this->Ln();
			$this->SetFont('helvetica', 'I', '9');
			$this->setY(215);
			$this->setX(148);
			$this->Write(10, "Il Dirigente scolastico");
			$this->setY(220);
			$this->setX(148);
			$this->Write(10, "Prof.ssa Giorgia Floris");
			$this->SetFont('', 'I', '7');
			$this->setY(240);
			$this->setX(68);
			$this->Write(10, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39");
		}
		
	}

	public function onFlyReport($st, $vt, $rel = "", $params, $vals, $cdc, $doc_religione) {
		$final_letter = "o";
		if($st['sesso'] == "F"){
			$final_letter = "a";
		}
		$cls = "";
		switch ($st['anno_corso']){
			case 1:
				$cls = "PRIMA";
				break;
			case 2:
				$cls = "SECONDA";
				break;
			case 3:
				$cls = "TERZA";
				break;
			case 4:
				$cls = "QUARTA";
				break;
			case 5:
				$cls = "QUINTA";
				break;
		}
		
		/*
		 * voti di comportamento scuola primaria
		*/
		$voti_comportamento_primaria = array("4" => array("nome" => "non adeguato", "codice" => "NA"),
				"5" => array("nome" => "parzialmente adeguato", "codice" => "PA"),
				"6" => array("nome" => "adeguato", "codice" => "AD")
		);
		
		$this->setPage(1, true);
		$this->SetFont('', 'B');
		$this->Image('../../images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '16');
		$this->Cell(0, 20, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '22');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 10, "Scuola primaria statale", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 10, "SCHEDA PERSONALE PRIMO QUADRIMESTRE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		if (isset($st['luogo_nascita'])){
			$this->SetFont('', '', '11');
			$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
		}
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
		//$this->Write(9, $_SESSION['__current_year__']->to_string()."  -  Classe ".$st['anno_corso'].$st['sezione'], $align='C');
		$this->SetY(120.0);
		
		/*
		 * parametri di maturazione
		*/
		$this->SetFillColor(131, 2, 29);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '13');
		// Header
		$w = array(100, 30);
		$this->SetX(40);
		$this->Cell(100, 7, "Materia", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$max = count($vt);
		foreach ($vt as $row){
			$this->SetX(40);
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(100, 6, $row['desc_mat'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);

			if ($row['desc_mat'] != "Comportamento"){
				if ($row['voto'] == ""){
					$row['voto'] = 'NC';
				}
				$this->Cell(30, 6, $row['voto'], 'LR', 0, 'C', $fill);
			}
			else {
				$this->SetFont('', '', 9);
				//$row['voto'] = 6;
				$this->Cell(30, 6, $voti_comportamento_primaria[$row['voto']]['nome'], 'LR', 0, 'C', $fill);
				$this->SetFont('');
			}
			$this->Ln();
			$fill=!$fill;
		}
		$this->SetX(40);
		$this->Cell(130, 0, '', 'T');
		
		$this->SetY(200.0);
		$this->SetFillColor(131, 2, 29);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(0.3);
		$this->SetFont('', 'B', '11');
		// Header
		$w = array(100, 30);
		$this->SetX(40);
		$this->Cell(130, 7, "Giudizio sul livello globale di maturazione", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restoration
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		foreach ($params as $row){
			$this->SetX(40);
			$this->setCellPaddings(10, 0, 0, 0);
			$this->Cell(60, 6, $row['nome'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);

			if (isset($vals[$row['id']])){
				$this->Cell(70, 6, $vals[$row['id']], 'LR', 0, 'C', $fill);
			}
			else {
				$this->Cell(70, 6, "", 'LR', 0, 'C', $fill);
			}
			$this->Ln();
			$fill=!$fill;
		}
		$this->SetX(40);
		$this->Cell(130, 0, '', 'T');
		$this->Ln();
		$this->SetFont('helvetica', '', '12');
		$this->MultiCell(0, 15, "Il consiglio di classe: {$cdc}", 0, 'L', false, 1, 40);
		
		// religione
		if ($rel != ""){
			$this->setPage(2, true);
			$this->SetFont('', 'B');
			$this->SetDrawColor(120, 120, 120);
			$this->Image('../../images/ministero.jpg', 90, 8, 15, 15, 'JPG', '', '', false, '');
			$this->SetFont('times', 'B', '11');
			$this->Cell(0, 10, "MINISTERO DELL'ISTRUZIONE, DELL'UNIVERSITÀ E DELLA RICERCA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '14');
			$this->Cell(0, 10, "NOTA PER LA VALUTAZIONE RELATIVA ALL'INSEGNAMENTO", 0, 1, 'C', 0, '', 0);
			$this->Cell(0, 0, "DELLA RELIGIONE CATTOLICA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'I', '12');
			$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '14');
			$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. Nivola", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 0, "Via Pacinotti, Iglesias (CI)", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'BI', '12');
			$this->Cell(0, 20, "Scuola primaria  statale", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
			if (isset($st['luogo_nascita'])){
				$this->SetFont('', '', '11');
				$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
			}
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
			$this->SetY(140);
			$this->SetFont('times', '', '14');
			$this->Cell(180, 22, "", "LTRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '13');
			$this->Cell(180, 8, "VALUTAZIONI PERIODICHE", "LRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '11');
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "I Quadrimestre:   ".strtoupper($rel), "LRB", 0, 'L', 0, '', 0);
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "II Quadrimestre: ", "RB", 0, 'L', 0, '', 0);
			$this->Ln();
			$this->Cell(180, 30, "", "LRB", 1, 'C', 0, '', 0);
			$this->Cell(180, 12, "", "", 1, 'L', 0, '', 0);
			$this->SetFont('times', '', '12');
			$this->Cell(90, 10, "Il docente", "", 1, 'C', 0, '', 0);
			$this->Cell(90, 10, $doc_religione, "", 0, 'C', 0, '', 0);
			$this->Ln();
			$this->SetFont('helvetica', 'I', '9');
			$this->setY(215);
			$this->setX(148);
			$this->Write(10, "Il Dirigente scolastico");
			$this->setY(220);
			$this->setX(148);
			$this->Write(10, "Prof.ssa Giorgia Floris");
			$this->SetFont('', 'I', '7');
			$this->setY(240);
			$this->setX(68);
			$this->Write(10, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39");
		}
	}
}
