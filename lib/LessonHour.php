<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 10/9/16
 * Time: 8:07 PM
 */

namespace eschool;

require_once "RBUtilities.php";

class LessonHour
{

	private $ID;
	private $ora;
	private $giorno;
	private $materia;
	private $compresenza;
	private $sostegno;
	private $classe;
	private $docente;
	private $descrizione;
	private $datasource;
	private $rb;

	public function __construct($record, \MySQLDataLoader $dl){
		$this->datasource = $dl;
		$this->ID = $record['id'];
		$this->ora = $record['ora'];
		$this->materia = array("id" => $record['materia'], "desc", "");
		if ($this->materia['id'] == 0 || $this->materia['id'] == null) {
			$this->materia['id'] = 1;
		}
		else {
			$this->materia['desc'] = $this->datasource->executeCount("SELECT materia FROM rb_materie WHERE id_materia = ".$this->materia['id']);
		}
		$this->compresenza = $record['materia2'];
		$this->sostegno = array();
		$this->giorno = $record['giorno'];
		$this->classe = $record['classe'];
		$this->docente = $record['docente'];
		$this->descrizione = $record['descrizione'];

		$this->rb = \RBUtilities::getInstance($dl->getSource());
	}

	public function getMateria(){
		return $this->materia['id'];
	}

	public function getID(){
		return $this->ID;
	}

	public function getClasse(){
		return $this->classe;
	}

	public function getOra(){
		return $this->ora;
	}

	public function getGiorno(){
		return $this->giorno;
	}

	public function getDescrizione(){
		return $this->descrizione;
	}

	public function getDocente(){
		return $this->docente;
	}

	public function setSostegno($s) {
		$this->sostegno = $s;
	}

	/**
	 * print for schedule pdf
	 * @param boolean $full: if true print support teachers data
	 * @return string
	 */
	public function toString($full = true) {
		if ($this->materia['id'] == '' || $this->materia['id'] == null || $this->materia['id'] == 1) {
			return '---';
		}
		else if (count($this->sostegno) > 0 && $full) {
			$supp_teachers = array();
			foreach ($this->sostegno as $item) {
				$us = $this->rb->loadUserFromUid($item, 'simple_school');
				$supp_teachers[] = $us->getInitials(0, 0);
			}
			$text = $this->materia['desc']."/S (".implode(", ", $supp_teachers).")";
			return $text;
		}
		else {
			return $this->materia['desc'];
		}
	}

}