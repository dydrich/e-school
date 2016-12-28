<?php

require_once "classes.php";
require_once "data_source.php";
require_once "RBUtilities.php";
require_once "RBTime.php";

class ClassbookManager 
{
	
	private $datasource 		= null;
	private $year				= null;
	private $days				= array();
	private $classes 			= array();
	private $min				= null;
	private $integrityErrors 	= array();
	
	function __construct($ds, SchoolYear $year){
		$this->datasource = new MySQLDataLoader($ds);
		$this->year = $year;
		$this->setMin();
	}
	
	public function getClassFromStudent($st){
		$q = "SELECT id_classe FROM rb_alunni WHERE id_alunno = {$st}";
		return $this->datasource->executeCount($q);

	}
	
	public function getClassFromID($classID){
		foreach ($this->classes as $_cl){
			if($_cl->get_ID() == $classID){
				return $_cl;
			}
		}
		return null;
	}
	
	public function setMin(){
		$this->min = $this->datasource->executeCount("SELECT MIN(id_reg) FROM rb_reg_classi WHERE id_anno = {$this->year->getYear()->get_ID()}");
	}
	
	public function init(){
		$this->setClasses();
		$this->setDays();
	}
	
	public function setClasses(){
		$query = "SELECT * FROM rb_classi WHERE ordine_di_scuola = ".$this->year->getSchoolOrder();
		$_classes = $this->datasource->executeQuery($query);
		foreach($_classes as $_class){
			$this->classes[] = new Classe($_class, $this->datasource->getSource());
		}
	}
	
	public function setDays(){
		$first_day = format_date($this->year->getClassesStartDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$last_day = format_date($this->year->getClassesEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");
		$current_day = $first_day;
		$vacation_days = $this->year->getHolydays();
		while($current_day <= $last_day){
			if((date("w", strtotime($current_day)) == "0") || in_array($current_day, $vacation_days)){
				
			}
			else{
				$this->days[] = $current_day;
			}
			$current_day = date("Y-m-d", strtotime("$current_day +1 days"));
		}
	}
	
	public function delete(){
		$query = "DELETE FROM rb_reg_classi, rb_reg_alunni USING rb_reg_classi JOIN rb_reg_alunni WHERE id_reg = rb_reg_alunni.id_registro AND id_anno = {$this->year->getYear()->get_ID()}";
		$this->datasource->executeUpdate("BEGIN");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni USING rb_classi JOIN rb_reg_classi ON rb_reg_classi.id_classe = rb_classi.id_classe JOIN rb_reg_alunni WHERE rb_reg_classi.id_reg = id_registro AND rb_classi.ordine_di_scuola = {$this->year->getSchoolOrder()} AND id_anno = {$this->year->getYear()->get_ID()}");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_classi USING rb_classi JOIN rb_reg_classi ON rb_reg_classi.id_classe = rb_classi.id_classe WHERE rb_classi.ordine_di_scuola = {$this->year->getSchoolOrder()} AND id_anno = {$this->year->getYear()->get_ID()}");
		$this->datasource->executeUpdate("COMMIT");		
		return true;
	}
	
	public function deleteStudent($st){
		$this->datasource->executeUpdate("BEGIN");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni WHERE id_alunno = {$st} AND id_registro >= {$this->min}");
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function deleteClass($cl){
		$this->datasource->executeUpdate("BEGIN");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni USING rb_reg_classi JOIN rb_reg_alunni WHERE rb_reg_classi.id_reg = id_registro AND rb_reg_classi.id_classe = {$cl} AND id_anno = {$this->year->getYear()->get_ID()}");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_classi WHERE rb_reg_classi.id_classe = {$cl} AND id_anno = {$this->year->getYear()->get_ID()}");
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function deleteDay($day){
		$this->datasource->executeUpdate("BEGIN");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni USING rb_classi JOIN rb_reg_classi ON rb_reg_classi.id_classe = rb_classi.id_classe JOIN rb_reg_alunni WHERE rb_reg_classi.id_reg = id_registro AND rb_classi.ordine_di_scuola = {$this->year->getSchoolOrder()} AND data = '{$day}'");
		$this->datasource->executeUpdate("DELETE rb_reg_classi FROM rb_reg_classi JOIN rb_classi ON rb_reg_classi.id_classe = rb_classi.id_classe WHERE rb_classi.ordine_di_scuola = {$this->year->getSchoolOrder()} AND data = '{$day}'");
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function deleteClassDay($day, $cl){
		$sel_lesson_days = "SELECT data FROM rb_reg_classi WHERE id_classe = {$cl} AND id_anno = {$this->year->getYear()->get_ID()} ORDER BY data";
		$lesson_days = $this->datasource->executeQuery($sel_lesson_days);
		if(!in_array($day, $lesson_days)){
			return false;
		}
		$this->datasource->executeUpdate("BEGIN");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni USING rb_reg_classi JOIN rb_reg_alunni WHERE rb_reg_classi.id_reg = id_registro AND rb_reg_classi.data = '{$day}' AND rb_reg_alunni.id_classe = {$cl}");
		$this->datasource->executeUpdate("DELETE FROM rb_reg_classi WHERE data = '{$day}' AND id_classe = {$cl}");
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function insert(){
		$this->datasource->executeUpdate("BEGIN");
		/* step #1: classes in rb_reg_classi */
		foreach ($this->classes as $_classe){
			$id_classe = $_classe->get_ID();
			$mod = $_classe->get_modulo_orario();
			$no_lesson_days = $mod->getNoLessonDays();
			foreach ($this->days as $day) {
				$day_number = date("w", strtotime($day));
				if(in_array($day_number, $no_lesson_days)){
					continue;
				}
				else{
					$enter = $mod->getDay($day_number)->getEnterTime()->toString(RBTime::$RBTIME_LONG);
					$exit = $mod->getDay($day_number)->getExitTime()->toString(RBTime::$RBTIME_LONG);
					$insert = "INSERT INTO rb_reg_classi (id_classe, id_anno, data, ingresso, uscita) VALUES ($id_classe, ".$this->year->getYear()->get_ID().",'$day', '{$enter}', '{$exit}')";
					$this->datasource->executeUpdate($insert);
				}
			}
		
			/*step #2: students */
			$sel_alunni = "SELECT id_alunno, id_classe FROM rb_alunni WHERE attivo = '1' AND id_classe = $id_classe";
			$res_alunni = $this->datasource->executeQuery($sel_alunni);
			$param = "";

			foreach ($res_alunni as $alunno){
				$id_alunno = $alunno['id_alunno'];
				$sel_registro = "SELECT id_reg, ingresso, uscita FROM rb_reg_classi WHERE id_classe = $id_classe $param AND id_anno = ".$this->year->getYear()->get_ID();
				$res_registro = $this->datasource->executeQuery($sel_registro);
				foreach($res_registro as $day){
					$insert_al = "INSERT INTO rb_reg_alunni VALUES (".$day['id_reg'].", $id_alunno, '".$day['ingresso']."', '".$day['uscita']."', NULL, NULL, $id_classe)";
					//echo $insert_al;
					$this->datasource->executeUpdate($insert_al);
				}
			}
		}
		$this->datasource->executeUpdate("COMMIT");
	}
	
	public function insertClass($cl){
		$this->datasource->executeUpdate("BEGIN");
		/* step #1: class */
		$cls = $this->getClassFromID($cl);
		$id_classe = $cls->get_ID();
		$mod = $cls->get_modulo_orario();
		$no_lesson_days = $mod->getNoLessonDays();
		foreach ($this->days as $day) {
			$day_number = date("w", strtotime($day));
			if(in_array($day_number, $no_lesson_days)){
				continue;
			}
			else{
				
				$enter = $mod->getDay($day_number)->getEnterTime()->toString(RBTime::$RBTIME_LONG);
				$exit = $mod->getDay($day_number)->getExitTime()->toString(RBTime::$RBTIME_LONG);
				$insert = "INSERT INTO rb_reg_classi (id_classe, id_anno, data, ingresso, uscita) VALUES ({$id_classe}, ".$this->year->getYear()->get_ID().",'$day', '{$enter}', '{$exit}')";
				$this->datasource->executeUpdate($insert);
			}
		}
		/*step #2: students */
		$sel_alunni = "SELECT id_alunno, id_classe FROM rb_alunni WHERE attivo = '1' AND id_classe = {$cl}";
		$res_alunni = $this->datasource->executeQuery($sel_alunni);

		foreach ($res_alunni as $alunno){
			$id_alunno = $alunno['id_alunno'];
			$id_classe = $alunno['id_classe'];
			$sel_registro = "SELECT id_reg, ingresso, uscita FROM rb_reg_classi WHERE id_classe = {$cl} AND id_anno = ".$this->year->getYear()->get_ID();
			$res_registro = $this->datasource->executeQuery($sel_registro);
			foreach($res_registro as $day){
				$insert_al = "INSERT INTO rb_reg_alunni VALUES (".$day['id_reg'].", $id_alunno, '".$day['ingresso']."', '".$day['uscita']."', NULL, NULL, {$cl})";
				$this->datasource->executeUpdate($insert_al);
			}
		}
		$this->datasource->executeUpdate("COMMIT");
	}
	
	public function insertDay($d){
		if(in_array($d, $this->days)){
			return false;
		}
		$day_number = date("w", strtotime($d));
		$this->datasource->executeUpdate("BEGIN");
		/* step #1: classes */
		foreach ($this->classes as $cls){
			$id_classe = $cls->get_ID();
			$mod = $cls->get_modulo_orario();
			$enter = $mod->getDay($day_number)->getEnterTime()->toString(RBTime::$RBTIME_LONG);
			$exit = $mod->getDay($day_number)->getExitTime()->toString(RBTime::$RBTIME_LONG);
			$has_record = $this->datasource->executeCount("SELECT COUNT(*) FROM rb_reg_classi WHERE id_classe = {$id_classe} AND id_anno = ".$this->year->getYear()->get_ID()." AND data = '{$d}'");
			if ($has_record < 1) {
				$insert = "INSERT INTO rb_reg_classi (id_classe, id_anno, data, ingresso, uscita) VALUES ($id_classe, " . $this->year->getYear()->get_ID() . ",'{$d}', '{$enter}', '{$exit}')";
				$this->datasource->executeUpdate($insert);

				/*step #2: students */
				$sel_alunni = "SELECT id_alunno, id_classe FROM rb_alunni WHERE attivo = '1' AND id_classe = $id_classe";
				$res_alunni = $this->datasource->executeQuery($sel_alunni);

				foreach ($res_alunni as $alunno) {
					$id_alunno = $alunno['id_alunno'];
					$sel_registro = "SELECT id_reg, ingresso, uscita FROM rb_reg_classi WHERE id_classe = $id_classe AND data = '{$d}' ";
					$res_registro = $this->datasource->executeQuery($sel_registro);
					foreach ($res_registro as $day) {
						$insert_al = "INSERT INTO rb_reg_alunni VALUES (" . $day['id_reg'] . ", $id_alunno, '" . $day['ingresso'] . "', '" . $day['uscita'] . "', NULL, NULL, $id_classe)";
						$this->datasource->executeUpdate($insert_al);
					}
				}
			}
		}
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function insertClassDay($d, $cl){
		$sel_lesson_days = "SELECT data FROM rb_reg_classi WHERE id_classe = {$cl} AND id_anno = {$this->year->getYear()->get_ID()} AND data <= NOW() ORDER BY data";
		$lesson_days = $this->datasource->executeQuery($sel_lesson_days);
		if(in_array($d, $lesson_days)){
			return false;
		}
		$day_number = date("w", strtotime($d));
		$this->datasource->executeUpdate("BEGIN");
		
		$cls = $this->getClassFromID($cl);
		$id_classe = $cls->get_ID();
		$mod = $cls->get_modulo_orario();
		$enter = ($mod->getDay($day_number)) ? $mod->getDay($day_number)->getEnterTime()->toString(RBTime::$RBTIME_LONG) : "08:30";
		$exit = ($mod->getDay($day_number)) ? $mod->getDay($day_number)->getExitTime()->toString(RBTime::$RBTIME_LONG) : "13:30";
		$insert = "INSERT INTO rb_reg_classi (id_classe, id_anno, data, ingresso, uscita) VALUES ($id_classe, ".$this->year->getYear()->get_ID().",'{$d}', '{$enter}', '{$exit}')";
		$this->datasource->executeUpdate($insert);
				
		$sel_alunni = "SELECT id_alunno, id_classe FROM rb_alunni WHERE attivo = '1' AND id_classe = $id_classe";
		$res_alunni = $this->datasource->executeQuery($sel_alunni);
		foreach ($res_alunni as $alunno){
			$id_alunno = $alunno['id_alunno'];
			$sel_registro = "SELECT id_reg, ingresso, uscita FROM rb_reg_classi WHERE id_classe = $id_classe AND data = '{$d}' ";
			$res_registro = $this->datasource->executeQuery($sel_registro);
			foreach($res_registro as $day){
				$insert_al = "INSERT INTO rb_reg_alunni VALUES (".$day['id_reg'].", $id_alunno, '".$day['ingresso']."', '".$day['uscita']."', NULL, NULL, $id_classe)";
				$this->datasource->executeUpdate($insert_al);
			}
		}
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function insertStudent($st){
		$cl = $this->getClassFromStudent($st);
		$this->datasource->executeUpdate("BEGIN");
		$sel_registro = "SELECT id_reg, ingresso, uscita FROM rb_reg_classi WHERE id_classe = {$cl} AND id_anno = ".$this->year->getYear()->get_ID();
		$res_registro = $this->datasource->executeQuery($sel_registro);
		foreach($res_registro as $day){
			$insert_al = "INSERT INTO rb_reg_alunni VALUES (".$day['id_reg'].", {$st}, '".$day['ingresso']."', '".$day['uscita']."', NULL, NULL, {$cl})";
			$this->datasource->executeUpdate($insert_al);
		}
		$this->datasource->executeUpdate("COMMIT");
	}
	
	public function reinsert(){
		$return = true;
		if ($return = $this->delete()) {
			return $this->insert();
		}
		return $return;
	}
	
	public function reinsertStudent($st){
		$return = true;
		if ($return = $this->deleteStudent($st)) {
			return $this->insertStudent($st);
		}
		return $return;
	}
	
	public function reinsertClass($cl){
		//echo "reinsert class";
		$return = true;
		if ($return = $this->deleteClass($cl)) {
			return $this->insertClass($cl);
		}
		return $return;
	}
	
	public function reinsertDay($day){
		$return = true;
		if ($return = $this->deleteDay($day)) {
			return $this->insertDay($day);
		}
		return $return;
	}
	
	public function checkIntegrity($autocorrect = false){
		$this->setClasses();
		$return = true;
		$classes_to_delete = array();
		$classes_to_insert = array();
		$student_to_delete = array();
		$student_to_insert = array();
		$classes_in_regclass = array();
		$classes_in_regal = array();
		$classes_in_regclass = $this->datasource->executeQuery("SELECT DISTINCT(id_classe) FROM rb_reg_classi WHERE id_anno = {$this->year->getYear()->get_ID()}");
		$classes_in_regal = $this->datasource->executeQuery("SELECT DISTINCT(rb_reg_alunni.id_classe) FROM rb_reg_alunni, rb_reg_classi WHERE id_anno = {$this->year->getYear()->get_ID()} AND id_reg = id_registro");
		$this->datasource->executeUpdate("BEGIN");
		
		/*
		 * records in rb_reg_alunni with broken reference in rb_reg_classi
		 * questa e` l'unica situazione di reale rottura dell'integrita`, e va corretta 
		 */
		$student_to_delete = $this->datasource->executeQuery("SELECT DISTINCT(id_registro) FROM rb_reg_alunni WHERE id_registro NOT IN (SELECT id_reg FROM rb_reg_classi)");
		if (count($student_to_delete) > 0){
			if ($autocorrect){
				$st_del = join(",", $student_to_delete);
				$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni WHERE id_registro IN ({$st_del})");
			}
			else {
				$this->integrityErrors['student_to_delete'] = $student_to_delete;
			}
			$return = false;
		}
		
		/* classes in rb_reg_classi but not in rb_classi */
		$idclasses = array();
		foreach ($this->classes as $cl){
			$idclasses[] = $cl->get_ID();
		}
		$classes_to_delete = array_diff($classes_in_regclass, $idclasses);
		if (count($classes_to_delete) > 0){
			if ($autocorrect){
				$cl_del = join(",", $classes_to_delete);
				$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni USING rb_reg_alunni, rb_reg_classi WHERE id_reg = id_registro AND rb_reg_alunni.id_classe IN ({$cl_del}) AND rb_reg_classi.id_anno = {$this->year->getYear()->get_ID()}");
				$this->datasource->executeUpdate("DELETE FROM rb_reg_classi WHERE rb_reg_classi.id_classe IN ({$cl_del}) AND id_anno = {$this->year->getYear()->get_ID()}");
			}
			else {
				$this->integrityErrors['classes_to_delete'] = $classes_to_delete;
			}
			$return = false;
		}
		
		/* classes in rb_classi but not in rb_reg_classi */
		$classes_to_insert = array_diff($idclasses, $classes_in_regclass);
		if (count($classes_to_insert) > 0){
			if ($autocorrect){
				foreach ($classes_to_insert as $cl){
					$this->datasource->executeUpdate("DELETE FROM rb_reg_alunni WHERE id_classe = {$cl}");
					$this->insertClass($cl);
				}
			}
			else {
				$this->integrityErrors['classes_to_insert'] = $classes_to_insert;
			}
			$return = false;
		}
		
		/* classes in rb_reg_classi but not in rb_reg_alunni */
		$student_to_insert = array_diff($classes_in_regclass, $classes_in_regal);
		if (count($student_to_insert) > 0){
			if ($autocorrect){
				foreach ($student_to_insert as $cl){
					$this->datasource->executeUpdate("DELETE FROM rb_reg_classi WHERE id_classe = {$cl}");
					$this->insertClass($cl);
				}
			}
			else {
				$this->integrityErrors['student_to_insert'] = $student_to_insert;
			}
			return false;
		}
		$this->datasource->executeUpdate("COMMIT");
		return true;
	}
	
	public function getIntegrityErrors(){
		return $this->integrityErrors;
	}
	
	public function deleteHolydays(){
		$holydays = $this->year->getHolydays();
		foreach ($holydays as $hd){
			$this->deleteDay($hd);
		}
	}
	
}
