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

        $this->loadActivities();
    }

    private function loadActivities() {
        if ($this->class != null || $this->teacher != null) {
            $this->loadHomeworks();
        }
    }

    private function loadHomeworks() {
        $sel_act = "SELECT rb_impegni.*, rb_materie.materia AS mat 
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
        $sel_act .= " ORDER BY data_inizio";

        $hws = $this->datasource->executeQuery($sel_act);
        foreach ($hws as $hw) {
            if (!isset($this->activities[$hw['data_inizio']])) {
                $this->activities[$hw['data_inizio']] = [];
            }
            $this->activities[$hw['data_inizio']][] = $hw;
        }
    }

    public function getActivities() {
        return $this->activities;
    }

}