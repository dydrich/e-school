<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 8/30/16
 * Time: 11:19 PM
 */

namespace eschool;


class Homework extends Activity
{
    protected $year;
    protected $subject;
    protected $notes;

    /**
     * Homework constructor.
     * @param $id
     * @param \MySQLDataLoader $db
     * @param $data
     */
    public function __construct($id, \MySQLDataLoader $db, $data = null) {
        parent::__construct($id, $db, $data);
        $this->type = Activity::$HOMEWORK;
        if ($data != null) {
            $this->startDate = new \DateTime($data['data_inizio']);
            $this->description = $data['descrizione'];
            $this->insertDateTime = new \DateTime($data['data_fine']);
            $this->owner = $data['docente'];
            $this->class = $data['classe'];
            $this->year = $data['anno'];
            $this->subject = $data['materia'];
            $this->notes = $data['note'];
        }
        else {

        }
    }

    /**
     * @return mixed
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     */
    public function setNotes($notes) {
        $this->notes = $notes;
    }

    /**
     * @return mixed
     */
    public function getStartDateToString() {
        return $this->startDate->format("Y-m-d");
    }

    /**
     * @inheritDoc
     */
    public function insert() {
        $query = "INSERT INTO rb_impegni (data_assegnazione, data_inizio, docente, classe, anno, materia, descrizione, note, tipo) 
                  VALUES (NOW(), '".$this->getStartDateToString()."', $this->owner, $this->class, $this->year, $this->subject, '{$this->description}', '$this->notes', 2)";
        $id = $this->datasource->executeUpdate($query);
        $this->ID = $id;
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function update() {
        $query = "UPDATE rb_impegni SET data_inizio = '".$this->getStartDateToString()."', 
        docente = {$this->owner}, classe = {$this->class}, anno = {$this->year}, materia = {$this->subject}, descrizione = '{$this->description}', note = '{$this->notes}' 
        WHERE id_impegno = ".$this->ID;
        $id = $this->datasource->executeUpdate($query);
        return $id;
    }

    /**
     * @inheritDoc
     */
    public function delete() {
        $query = "DELETE FROM rb_impegni WHERE id_impegno = ".$this->ID;
        $id = $this->datasource->executeUpdate($query);
        return $id;
    }

    public function toJSON() {
        $homework = [
            'id' => $this->ID,
            'startDate' => $this->getStartDateToString(),
            'description' => $this->description,
            'type' =>$this->type,
            'insertDateTime' => $this->insertDateTime->format("d/m/Y H:m:s"),
            'owner' => $this->owner,
            'class' => $this->class,
            'subject' => $this->subject,
            'notes' => $this->notes
            ];
        return $homework;

    }

}