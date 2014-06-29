<?php

abstract class Widget {
	
	protected $id;
	protected $style = array();
	protected $cssClass;
	protected $element;
	protected $innerHTML;
	
	abstract public function toHTML();
	
	public function getId(){
		return $this->id;
	}
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function setStyle(array $s){
		$this->style = $s;
	}
	
	public function getStyle(){
		return $this->style;
	}
	
	public function getCssClass(){
		return $this->cssClass;
	}
	
	public function setCssClass($css){
		$this->cssClass = $css;
	}
	
	public function addCssClass($css){
		$this->cssClass .= " {$css}";
	}
	
	public function setInnerHTML($html){
		$this->innerHTML = $html;
	}
	
	public function getInnerHTML(){
		return $this->innerHTML;
	}
}