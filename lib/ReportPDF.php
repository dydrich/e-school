<?php

require_once "SchoolPDF.php";

class ReportPDF extends SchoolPDF {

	public function createFirstPage($st, $esito, $vt1, $vt2, $vr, $cdc, $doc_rel, $esonerato){
		$final_letter = "o";
		$suffix = "gli";
		if($st['sesso'] == "F"){
			$final_letter = "a";
			$suffix = "le";
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
		}
		$this->setPage(1, true);
		$this->Image($_SESSION['__path_to_root__'].'images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '16');
		$this->Cell(0, 20, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '14');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 10, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '15');
		$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '16');
		$this->Cell(0, 10, "SCHEDA PERSONALE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		if ($st['data_nascita'] != "" && $st['luogo_nascita'] != ""){
			$this->SetFont('', '', '11');
			$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '13');
		}
		$this->Cell(0, 10, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 20, "Vista la valutazione del consiglio di classe, si attesta che", 'LTR', 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		if ($esito['id_esito'] != 17){
			$this->Cell(0, 10, "l'alunn{$final_letter} ".utf8_encode($esito['desc_pagella'])." ", 'LR', 1, 'C', 0, '', 0);
			$this->Cell(0, 10, "della scuola secondaria di primo grado", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
		}
		else {
			if ($cls != 3){
				$this->Cell(0, 10, "l'alunn{$final_letter} non è stat{$final_letter} ammess{$final_letter} alla classe successiva ", 'LR', 1, 'C', 0, '', 0);
				$this->Cell(0, 10, "ai sensi dell'art. 3, c. 2 del DPR 122/2009", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
			}
			else {
				$this->Cell(0, 10, "l'alunn{$final_letter} non è stat{$final_letter} ammess{$final_letter} all'esame di licenza ", 'LR', 1, 'C', 0, '', 0);
				$this->Cell(0, 10, "ai sensi dell'art. 3, c. 2 del DPR 122/2009", 'LBR', 1, 'C', 0, '', 0, 0, 'T', 'T');
			}
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
		$this->SetFont('', 'B', '13');
		// Header
		$w = array(100, 30);
		$this->Cell(120, 7, "Materia", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 1Q", 1, 0, 'C', 1);
		$this->Cell(30, 7, "Voto 2Q", 1, 0, 'C', 1);
		$this->Ln();
		// Color and font restore
		$this->SetFillColor(232, 234, 236);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Data
		$fill = 0;
		$max = count($vt1);
		$has_negative_grades = false;
		$grades_5 = array();
		$grades_4 = array();
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
			$this->Cell(120, 6, $vt1[$i]['desc_mat'], 'LR', 0, 'L', $fill);
			$this->SetCellPadding(0);
			$this->Cell(30, 6, $vt1[$i]['voto'], 'LR', 0, 'C', $fill);
			$fgrade = $vt2[$i]['voto'];
			if ($fgrade < 6){
				if ($fgrade == 5){
					$grades_5[] = $vt1[$i]['desc_mat'];
				}
				else {
					$grades_4[] = $vt1[$i]['desc_mat'];
				}
			}
			if ($fgrade < 6 && $esito['positivo'] == 1){
				$has_negative_grades = true;
				$fgrade = 6;
			}
			if ($esito['id_esito'] == 17){
				$fgrade = "";
			}
			$this->Cell(30, 6, $fgrade, 'LR', 0, 'C', $fill);
			$this->Ln();
			$fill=!$fill;
		}

		$this->Cell(180, 0, '', 'T');
		$this->Ln();
		$this->Ln();
		$this->SetFont('helvetica', '', '10');
		$this->MultiCell(100, 10, "Il consiglio di classe: {$cdc}", 0, 'L', false, 0, 15);
		$this->MultiCell(60, 2, "Il Dirigente scolastico", 0, 'C', false, 1, 125);
		$this->MultiCell(60, 2, "Prof.ssa Giorgia Floris", 0, 'C', false, 1, 125);
		$this->SetFont('', 'I', '7');
		$this->MultiCell(150, 20, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 0, 'R', false, 0, 35);

		$voti_religione = array("4" => "Insufficiente", "6" => "Sufficiente", "8" => "Buono", "9" => "Distinto", "10" => "Ottimo");
		$rel = true;
		/*
		if ($vr[2] == 0 || $vr[2] == ''){
			$rel = false;
		}
		*/
		if ($esonerato > 0) {
			$rel = false;
		}

		// religione
		if ($rel){
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
			$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 0, "Via Pacinotti, Iglesias (CI)", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'BI', '12');
			$this->Cell(0, 20, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
			if ($st['data_nascita'] != "" && $st['luogo_nascita'] != ""){
				$this->SetFont('', '', '11');
				$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
				$this->SetFont('', '', '12');
			}
			$this->Cell(0, 10, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
			$this->SetY(140);
			$this->SetFont('times', '', '14');
			$this->Cell(180, 22, "Dio e l'uomo. I valori etici e religiosi. La Bibbia e le altre fonti. Il linguaggio religioso", "LTRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '13');
			$this->Cell(180, 8, "VALUTAZIONI PERIODICHE", "LRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '11');
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "I Quadrimestre:   ".strtoupper($voti_religione[$vr[1]]), "LRB", 0, 'L', 0, '', 0);
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "II Quadrimestre: ".strtoupper($voti_religione[$vr[2]]), "RB", 0, 'L', 0, '', 0);
			$this->Ln();
			$this->Cell(180, 10, "", "LRB", 1, 'C', 0, '', 0);
			$this->Cell(180, 12, "", "LR", 1, 'L', 0, '', 0);
			$this->SetFont('times', '', '10');
			$this->Cell(90, 10, "Il docente: {$doc_rel}", "LB", 0, 'C', 0, '', 0);
			$this->Cell(90, 10, "", "RB", 0, 'C', 0, '', 0);
			$this->Ln();
			$this->SetFont('times', '', '10');
			$this->SetCellPaddings(16);
			$this->Cell(90, 30, "", "LB", 0, 'L', 0, '', 0);
			$this->SetCellPaddings(0, 0, 16, 0);
			$this->Cell(90, 30, "Il dirigente scolastico", "RB", 0, 'R', 0, '', 0);
			$this->Ln();
			$this->SetFont('', 'I', '7');
			$this->MultiCell(180, 20, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 0, 'R', false, 0, 35);
			$this->setY(230);
			$this->setX(148);
			$this->SetFont('times', 'I', '9');
			$this->Write(10, "Prof.ssa Giorgia Floris");
		}

		/*
		 * lettera di segnalazione insufficienze
		 */
		if ($has_negative_grades){
			if ($rel){
				$this->AddPage("P", "A4");
				$this->setPage(3, true);
			}
			else {
				$this->setPage(2, true);
			}
			$string_5 = implode(", ", $grades_5);
			$string_4 = implode(", ", $grades_4);
			$this->SetFont('', 'B');
			$this->SetDrawColor(120, 120, 120);
			$this->Image('../../images/ministero.jpg', 90, 8, 15, 15, 'JPG', '', '', false, '');
			$this->SetFont('times', 'B', '11');
			$this->Cell(0, 10, "MINISTERO DELL'ISTRUZIONE, DELL'UNIVERSITÀ E DELLA RICERCA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('helvetica', 'B', '14');
			$this->SetFont('', 'I', '12');
			$this->Cell(0, 20, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '14');
			$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 0, "Via Pacinotti, Iglesias (CI)", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'BI', '12');
			$this->Cell(0, 20, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '11');
			$this->Cell(100, 10, '', 0, 0, 'C', 0, '', 0);
			$this->Cell(80, 10, "Ai genitori dell'alunn{$final_letter} ", 0, 1, 'C', 0, '', 0);
			$this->Cell(100, 5, '', 0, 0, 'C', 0, '', 0);
			$this->Cell(80, 10, $st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
			$text = "Gentilissimi genitori, a seguito delle operazioni di scrutinio desideriamo comunicarVi che vostr{$final_letter} figli{$final_letter}, iscritt{$final_letter} ";
			$text .= "alla classe {$cls}, sezione {$st['sezione']}, è stat{$final_letter} ammess{$final_letter} alla classe successiva con decisione assunta dal Consiglio di classe, ";
			$text .= "pur non avendo conseguito gli obiettivi minimi previsti in tutte le materie.\nL'alunn{$final_letter} presenta lacune in una o più discipline, ";
			$text .= "che necessitano di un adeguato lavoro di recupero, per permetter{$suffix} di iniziare il prossimo anno scolastico senza carenze che potrebbero comprometterne gli apprendimenti futuri.\n";
			$text .= "Le attività rivolte alla verifica e valutazione degli apprendimenti sulle materie indicate verranno effettuate entro le prime settimane di ripresa delle attività scolastiche.\n";
			$text .= "\n - Discipline con lacune che necessitano di un rinforzo (voto 5): ".$string_5;
			$text .= "\n\n - Discipline che necessitano di un recupero a causa di gravi lacune (voto inferiore al 5): ".$string_4."\n";
			$this->MultiCell(180, 20, $text, 0, 'J', false, 0, 15, 125);
			$this->MultiCell(100, 10, "", 0, 'L', false, 0, 15);
			$this->MultiCell(60, 2, "Il Dirigente scolastico", 0, 'C', false, 1, 125, 220);
			$this->MultiCell(60, 2, "Prof.ssa Giorgia Floris", 0, 'C', false, 1, 125, 225);
			$this->SetFont('', 'I', '7');
			$this->MultiCell(170, 20, "\n\nfirma autografa sostituita a mezzo stampa ai sensi dell'articolo 3 comma 2 del decreto legislativo 12 dicembre 1993, n. 39", 0, 'R', false, 0, 35, 240);
		}
		
	}

	public function onFlyReport($st, $vt, $rel = 0, $cdc, $doc_religione) {
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
		}

		if ($rel == ""){
			$rel = "NC";
		}

		$this->setPage(1, true);
		$this->SetFont('', 'B');
		$this->Image('../../images/ministero.jpg', 90, 8, 20, 20, 'JPG', '', '', false, '');
		$this->SetFont('times', 'B', '16');
		$this->Cell(0, 30, "Ministero dell'Istruzione, dell'Università e della Ricerca", 0, 1, 'C', 0, '', 0);
		$this->SetFont('helvetica', 'B', '22');
		$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', '', '12');
		$this->Cell(0, 5, "Via Pacinotti snc - (loc. Serra Perdosa), Iglesias (CI) ", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 15, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 25, $_SESSION['__current_year__']->to_string(), 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '14');
		$this->Cell(0, 10, "SCHEDA PERSONALE PRIMO QUADRIMESTRE", 0, 1, 'C', 0, '', 0);
		$this->SetFont('', 'B', '13');
		$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
		if ((isset($st['data_nascita']) && $st['data_nascita'] != "") && (isset($st['luogo_nascita']) && $st['luogo_nascita'] != "")){
			$this->SetFont('', '', '11');
			$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'B', '13');
		}
		$this->Cell(0, 10, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
		//$this->Write(9, $_SESSION['__current_year__']->to_string()."  -  Classe ".$st['anno_corso'].$st['sezione'], $align='C');
		$this->SetY(160.0);
		
		//$this->Write(9, "Scheda di valutazione di ".$st['cognome']." ".$st['nome'], $align='L');
		//$this->SetY(50.0);
		// Colors, line width and bold font
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
			if ($row['voto'] == ""){
				$row['voto'] = 'NC';
			}
			$this->Cell(30, 6, $row['voto'], 'LR', 0, 'C', $fill);
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
			$this->Cell(0, 10, "ISTITUTO COMPRENSIVO C. NIVOLA", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 0, "Via Pacinotti, Iglesias (CI)", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', 'BI', '12');
			$this->Cell(0, 20, "Scuola statale - secondaria di primo grado", 0, 1, 'C', 0, '', 0);
			$this->SetFont('', '', '12');
			$this->Cell(0, 5, "dell'alunn{$final_letter} ".$st['cognome']." ".$st['nome'], 0, 1, 'C', 0, '', 0);
			if ((isset($st['data_nascita']) && $st['data_nascita'] != "") && (isset($st['luogo_nascita']) && $st['luogo_nascita'] != "")){
				$this->SetFont('', '', '11');
				$this->Cell(0, 5, "nat{$final_letter} a ".$st['luogo_nascita']." il ".format_date($st['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/"), 0, 1, 'C', 0, '', 0);
				$this->SetFont('', '', '12');
			}
			$this->Cell(0, 10, "Iscritt{$final_letter} alla classe {$cls}, sezione ".$st['sezione'], 0, 1, 'C', 0, '', 0);
			$this->SetY(140);
			$this->SetFont('times', '', '14');
			$this->Cell(180, 22, "Dio e l'uomo. I valori etici e religiosi. La Bibbia e le altre fonti. Il linguaggio religioso", "LTRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '13');
			$this->Cell(180, 8, "VALUTAZIONI PERIODICHE", "LRB", 1, 'C', 0, '', 0);
			$this->SetFont('times', 'B', '11');
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "I Quadrimestre:   ".strtoupper($rel), "LRB", 0, 'L', 0, '', 0);
			$this->SetCellPaddings(10, 0, 0, 0);
			$this->Cell(90, 12, "II Quadrimestre: ", "RB", 0, 'L', 0, '', 0);
			$this->Ln();
			$this->Cell(180, 30, "", "LRB", 1, 'C', 0, '', 0);
			$this->Cell(180, 12, "", "", 1, 'C', 0, '', 0);
			$this->SetFont('times', '', '12');
			$this->Cell(90, 2, "Il docente", "", 1, 'C', 0, '', 0);
			$this->Cell(90, 2, $doc_religione, "", 0, 'C', 0, '', 0);
			$this->Ln();


		}
		
		
		/*
		$this->SetFont('helvetica', '', 12);
		$this->SetY(160.0);
		$this->Write(9, "Il coordinatore", $align='L');
		$this->SetX(80.0);
		$this->Write(9, "Il segretario", $align='L');
		$this->SetX(140.0);
		$this->Write(9, "Il Dirigente scolastico", $align='L');
		*/
	}
}
