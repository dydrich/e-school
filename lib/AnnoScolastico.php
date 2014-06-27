<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 28/12/13
 * Time: 21.20
 */

//namespace eschool;


class AnnoScolastico {
	private $ID;
	private $data_apertura;
	private $data_chiusura;
	private $descrizione;

	function __construct($record){
		$this->ID = $record['id_anno'];
		$this->data_apertura = $record['data_inizio'];
		$this->data_chiusura = $record['data_fine'];
		$this->descrizione = $record['descrizione'];
	}

	public function get_ID(){
		return $this->ID;
	}

	public function get_data_apertura(){
		return $this->data_apertura;
	}

	public function set_data_apertura($d){
		$this->data_apertura = $d;
	}

	public function get_data_chiusura(){
		return $this->data_chiusura;
	}

	public function set_data_chiusura($d){
		$this->data_chiusura = $d;
	}

	public function set_descrizione($s){
		$this->descrizione = $s;
	}

	public function get_descrizione(){
		return $this->descrizione;
	}

	public function to_string(){
		return "Anno scolastico ".$this->descrizione;
	}
} 