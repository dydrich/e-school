<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 8/29/16
 * Time: 10:40 PM
 */

namespace eschool;

abstract class Activity
{
    protected $ID;
    protected $startDate;
    protected $description;
    protected $type;
    protected $insertDateTime;
    protected $owner;
    protected $class;

    protected $datasource;

    public static $CLASS_ACTIVITY = 1;
    public static $HOMEWORK = 2;
    public static $EVENT = 3;

    /**
     * Activity constructor.
     */
    public function __construct($id, \MySQLDataLoader $db, $data) {
        $this->ID = $id;
        $this->datasource = $db;
    }

    /**
     * @return mixed
     */
    public function getID() {
        return $this->ID;
    }

    /**
     * @param mixed $ID
     */
    public function setID($ID) {
        $this->ID = $ID;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     */
    public function setStartDate(\DateTime $startDate) {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public abstract function getStartDateToString();

    /**
     * @param string
     */
    public function setStartDateFromString($startdate) {
        $this->startDate = new \DateTime($startdate);
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getInsertDateTime() {
        return $this->insertDateTime;
    }

    /**
     * @param \DateTime $insertDateTime
     */
    public function setInsertDateTime(\DateTime $insertDateTime) {
        $this->insertDateTime = $insertDateTime;
    }

    /**
     * @param string $datetime
     */
    public function setInsertDateTimeFromString($datetime) {
        $this->insertDateTime = new \DateTime($datetime);
    }

    /**
     * @return string
     */
    public function insertDateTimeToString() {
        return $this->insertDateTime->format('d/m/Y H:i:s');
    }

    /**
     * @return mixed
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner) {
        $this->owner = $owner;
    }

    /**
     * @return mixed
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class) {
        $this->class = $class;
    }

    /**
     * @return \MySQLDataLoader
     */
    public function getDatasource() {
        return $this->datasource;
    }

    /**
     * @param \MySQLDataLoader $datasource
     */
    public function setDatasource($datasource) {
        $this->datasource = $datasource;
    }

    /**
     * @return integer or null
     */
    public abstract function insert();

    /**
     * @return integer or null
     */
    public abstract function update();

    /**
     * @return integer or null
     */
    public abstract function delete();

    public abstract function toJSON();
}