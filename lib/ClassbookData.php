<?php

class ClassbookData{
	
	private $cbClass;
	private $year;
	private $queryParams;
	private $datasource;
	
	public function __construct(Classe $c, SchoolYear $y, $ld, $ds){
		$this->cbClass = $c;
		$this->year = $y;
		$this->queryParams = $ld;
		if($ds instanceof MySQLDataLoader){
			$this->datasource = $ds;
		}
		else {
			$this->datasource = new MySQLDataLoader($ds);
		}
	}
	
	public function getClassSummary(){
		$classID = $this->cbClass->get_ID();
		$yearID = $this->year->getYear()->get_ID();
		$mod = $this->cbClass->get_modulo_orario();
		$totali = array();

		$sel_giorni = "SELECT ingresso, uscita, data FROM rb_reg_classi WHERE id_classe = {$classID} AND id_anno = {$yearID} {$this->queryParams}";
		$res_giorni = $this->datasource->executeQuery($sel_giorni);
		$totali = array();
		$totali['giorni'] = count($res_giorni);
		$totali['limite_giorni'] = intval($totali['giorni'] / 4);
		$totali['ore'] = 0;
		foreach ($res_giorni as $giorno){
			$day_number = date("w", strtotime($giorno['data']));
			$day = $mod->getDay($day_number);
			list($h, $m, $s) = explode(":", $giorno['ingresso']);
			$ingresso = new RBTime(intval($h), intval($m), intval($s));
			list($h, $m, $s) = explode(":", $giorno['uscita']);
			$uscita = new RBTime(intval($h), intval($m), intval($s));
			$totali['ore'] += $uscita->getTime() - $ingresso->getTime();
			if($day->hasCanteen()){
				$totali['ore'] -= $day->getCanteenDuration()->getTime();
			}
		}
		$totali['limite_ore'] = $totali['ore'] / 4;
		
		$times_array = array();
		$times_array['giorni'] = $totali['giorni'];
		$times_array['limite_giorni'] = $totali['limite_giorni'];
		$ore = new RBTime(0, 0, 0);
		$ore->setTime($totali['ore']);
		$lim_ore = new RBTime(0, 0, 0);
		$lim_ore->setTime($totali['limite_ore']);
		$times_array['ore'] = $ore;
		$times_array['limite_ore'] = $lim_ore;
		return $times_array;
	}
	
	public function getStudentSummary($student){
		$sums = $this->getStudentsSummary();
		return $sums[$student];
	}
	
	public function getStudentsSummary(){
		$class_total = $this->getClassSummary();
		$mod = $this->cbClass->get_modulo_orario();
		$sel_alunno = "SELECT id_alunno, cognome, nome FROM rb_alunni WHERE id_classe = {$this->cbClass->get_ID()} AND attivo = '1'";
		$alunni = $this->datasource->executeQuery($sel_alunno);
		$sel_assenze_alunni = "SELECT rb_reg_alunni.ingresso, rb_reg_alunni.uscita, data, rb_reg_alunni.id_alunno, cognome, nome FROM rb_reg_classi, rb_reg_alunni, rb_alunni WHERE rb_reg_alunni.id_alunno = rb_alunni.id_alunno AND rb_reg_classi.id_classe = {$this->cbClass->get_ID()} AND rb_reg_classi.id_reg = rb_reg_alunni.id_registro AND id_anno = ".$_SESSION['__current_year__']->get_ID()." {$this->queryParams} AND id_reg = id_registro ORDER BY cognome, nome, data";
		$res_assenze_alunni = $this->datasource->executeQuery($sel_assenze_alunni);
		$absences = 0;
		$time = 0;
		$students_data = array();
		$current_student = 0;
		$presence = new RBTime(0, 0, 0);
		$previous = "";
		foreach ($res_assenze_alunni as $abs){
			if($current_student != $abs['id_alunno'] && $current_student != 0){
				$presence->setTime($time);
				$students_data[$current_student] = array("name" => $previous, "absences" => $absences, "presence" => $presence);
				$absences = 0;
				$time = 0;
				$presence = new RBTime(0, 0, 0);
			}
			$current_student = $abs['id_alunno'];
			$previous = $abs['cognome']." ".$abs['nome'];
			$day_number = date("w", strtotime($abs['data']));
			if ($abs['ingresso'] == ""){
				$absences++;
			}
			else {
				list($h, $m, $s) = explode(":", $abs['ingresso']);
				$ing = new RBTime($h, $m, $s);
				list($h, $m, $s) = explode(":", $abs['uscita']);
				$usc = new RBTime($h, $m, $s);
				//$presence = new RBTime(0, 0, 0);
				
				$day = $mod->getDay($day_number);
				if($day->hasCanteen()){
					$cstart = $day->getCanteenStart()->getTime();
					$cduration = $day->getCanteenDuration()->getTime();
					$restart = new RBTime(0, 0, 0);
					$restart->setTime($cstart + $cduration);
					if ($usc->compare($restart) == 1 || $usc->compare($restart) == 0){
						$time += ($usc->getTime() - $ing->getTime() - $cduration);
					}
					else {
						if ($usc->compare($day->getCanteenStart()) == 1){
							$time += ($cstart - $ing->getTime());
						}
					}
				}
				else {
					$time += ($usc->getTime() - $ing->getTime());
				}
			}
		}
		$presence->setTime($time);
		$students_data[$current_student] = array("name" => $previous, "absences" => $absences, "presence" => $presence);
		return $students_data;
	}
	
	public function getCbClass(){
		return $this->cbClass;
	}
	
	public function getQueryParams(){
		return $this->queryParams;
	}
	
}
