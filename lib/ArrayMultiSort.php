<?php

class ArrayMultiSort {
	
	private $sortFields = array();
	private $data = array();
	private $sortField = null;
	private $index = null;
	private $_index = null;
	
	public function __construct($data){
		$this->data = $data;
		$this->index = 0;
		$this->_index = 0;
	}
	
	public function sort(){
		if(count($this->sortFields) > 1){
			return $this->multiSort();
		}
		usort($this->data, array($this, "cmp"));
	}
	
	private function multiSort(){
		usort($this->data, array($this, "rcmp"));
	}
	
	public function cmp($a, $b){
		if ($a[$this->sortField] == $b[$this->sortField]) {
			return 0;
		}
		return ($a[$this->sortField] < $b[$this->sortField]) ? -1 : 1;
	}
	
	public function rcmp($a, $b){
		if($this->index > $this->_index) {
			$this->index = $this->_index;
		}
		if(!isset($this->sortFields[$this->index])){
			return 0;
		}
		$this->sortField = $this->sortFields[$this->index];
		if ($a[$this->sortField] == $b[$this->sortField]) {
			if ($this->index < (count($this->sortFields) - 1)) {
				$this->index++;
				$this->_index++;
				return $this->rcmp($a, $b);
			}
			else {
				$this->_index--;
				return 0;
			}
		}
		if($this->_index > 0) $this->_index--;
		return ($a[$this->sortField] < $b[$this->sortField]) ? -1 : 1;
	}
	
	public function setData($newData){
		$this->data = $newData;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function setSortFields(array $fields){
		$this->sortFields = $fields;
		$this->sortField = $fields[0];
	}
	
	public function getSortFields(){
		return $this->sortFields;
	}
	
}