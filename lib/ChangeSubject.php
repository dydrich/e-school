<?php

class ChangeSubject extends Widget {

	private $datasource;
	private $link;
	private $javascript;

	public function __construct($id, $css, $style, $element, $data) {
		$this->id = $id;
		$this->cssClass = $css;
		$pieces = explode(";", $style);
		foreach ($pieces as $p) {
			list($k, $v) = explode(":", $p);
			$this->style[] = array("key" => $k, "value" => $v);
		}
		$this->element = $element;
		$this->datasource = $data;
		$this->setJavascript("");
	}

	public function createInnerHTML(){
		$idm = 0;
		$_mat = "";
		$html = "";

		if(count($this->datasource) > 0){
			$k = 0;
			$materie = array();
			foreach ($this->datasource as $mt) {
				//print "while";
				if(isset($_REQUEST['subject'])){
					if($_REQUEST['subject'] == $mt['id']){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
				else if(isset($_SESSION['__materia__'])){
					if($_SESSION['__materia__'] == $mt['id']){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
					else if($k == 0){
						$idm = $mt['id'];
						$_mat = $mt['mat'];
					}
				}
				else if($k == 0){
					//print "k==0";
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
				$html .= "<a href='#' onclick='change_subject(".$mt['id'].");'>". truncateString($mt['mat'], 25) ."</a><br />\n";
				$k++;
			}
			$_SESSION['__materia__'] = $idm;
		}
		$this->innerHTML = $html;
	}

	public function createLink($style = "", $position = "left"){
		$k = 0;
		foreach ($this->datasource as $mt) {
			//print "while";
			if(isset($_REQUEST['subject'])){
				if($_REQUEST['subject'] == $mt['id']){
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
			else if(isset($_SESSION['__materia__'])){
				if($_SESSION['__materia__'] == $mt['id']){
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
				else if($k == 0){
					$idm = $mt['id'];
					$_mat = $mt['mat'];
				}
			}
			else if($k == 0){
				//print "k==0";
				$idm = $mt['id'];
				$_mat = $mt['mat'];
			}
			$k++;
		}
		if(count($this->datasource) > 1) {
			$this->link = "<a href='#' onclick='visualizza(event, \"{$position}\")' style='".$style."'>".$_mat."</a>";
		}
		else {
			$this->link = $_mat;
		}
	}

	public function printLink(){
		echo $this->link;
	}

	public function toHTML(){
		if($this->innerHTML == ""){
			$this->createInnerHTML();
		}
		$style = "";
		foreach ($this->style as $st) {
			$style .= $st['key'].": ".$st['value'].";";
		}
		$html = "<".$this->element." id='".$this->id."' class='".$this->cssClass."' style='{$style}'>";
		echo $html;
		echo $this->innerHTML;
		echo "</".$this->element.">";
	}

	public function subjectNumber(){
		return count($this->datasource);
	}
	
	public function setJavascript($code){
		if($code == ""){
$this->javascript = <<<EDT
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

function visualizza(e, position) {
    var hid = document.getElementById("hid");
    if (IE) {
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;} 
    if(position == "center"){
		tempX -= 90;
    }
    else if (position == "right"){
    	tempX -= 180;
    }
    hid.style.top = parseInt(tempY)+"px";
    hid.style.left = parseInt(tempX)+"px";
    hid.show();
    return true;
}

document.observe("dom:loaded", function(){
	$('hid').observe("mouseleave", function(event){
		this.hide();
	});
	
});
			
EDT;
		}
		else {
			$this->javascript = $code;
		}
	}
	
	public function getJavascript(){
		return $this->javascript;
	}
}