<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 8/25/16
 * Time: 1:42 PM
 */

namespace eschool;


class Calendar
{
    private $activities;
    private $schoolOrder;
    private $class;
    private $teacher;
    private $subject;
    private $datasource;

    public function __construct($school_order = null, $cls = null, $teacher = null, $subj = null, \MySQLDataLoader $db) {
        $this->activities = [];
        $this->schoolOrder = $school_order;
        $this->class = $cls;
        $this->teacher = $teacher;
        $this->subject = $subj;
        $this->datasource = $db;

        $this->loadData();
    }

    private function loadData() {
        if ($this->class != null || $this->teacher != null) {
            $this->loadHomeworks();
			$this->loadActivities();
        }
    }

    private function loadHomeworks() {
        $sel_act = "SELECT rb_impegni.*, DATE(rb_impegni.data_inizio) AS start_date, rb_materie.materia AS mat 
                    FROM rb_impegni, rb_materie 
                    WHERE rb_materie.id_materia = rb_impegni.materia
                    AND data_inizio >= NOW() 
                    AND rb_impegni.tipo = 2";
        if ($this->class != null) {
            $sel_act .= " AND classe = ".$this->class;
        }
        if ($this->teacher != null) {
            $sel_act .= " AND docente = ".$this->teacher;
        }
        if ($this->subject != null) {
            $sel_act .= " AND rb_impegni.materia = ".$this->subject;
        }
        $sel_act .= " ORDER BY data_inizio DESC";

        $hws = $this->datasource->executeQuery($sel_act);
        foreach ($hws as $hw) {
            if (!isset($this->activities[$hw['start_date']])) {
                $this->activities[$hw['start_date']] = [];
            }
            $this->activities[$hw['start_date']][] = $hw;
        }
    }

    private function loadActivities() {
		$sel_act = "SELECT rb_impegni.*, DATE(rb_impegni.data_inizio) AS start_date,
					rb_materie.materia AS mat 
					FROM rb_impegni LEFT JOIN rb_materie 
					ON rb_materie.id_materia = rb_impegni.materia 
					WHERE classe = ".$this->class." 
					AND data_fine >= NOW() 
					AND rb_impegni.tipo = 1 ";
		if ($this->class != null) {
			$sel_act .= " AND classe = ".$this->class;
		}
		if ($this->teacher != null) {
			$sel_act .= " AND docente = ".$this->teacher;
		}
		if ($this->subject != null) {
			$sel_act .= " AND rb_impegni.materia = ".$this->subject;
		}
		$sel_act .= " ORDER BY data_inizio DESC";

		$acts = $this->datasource->executeQuery($sel_act);
		foreach ($acts as $hw) {
			if (!isset($this->activities[$hw['start_date']])) {
				$this->activities[$hw['start_date']] = [];
			}
			$this->activities[$hw['start_date']][] = $hw;
		}
	}

    public function getActivities() {
        return $this->activities;
    }

}