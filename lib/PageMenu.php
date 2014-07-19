<?php

require_once "Widget.php";

class PageMenu extends Widget {
	
	private $link;
	private $javascript;
	private $path_to_root;
	private $datasource = null;
	
	public function __construct($id, $css, $style, $element) {
		$this->id = $id;
		$this->cssClass = $css;
		$pieces = explode(";", $style);
		foreach ($pieces as $p) {
			list($k, $v) = explode(":", $p);
			$this->style[] = array("key" => $k, "value" => $v);
		}
		$this->element = $element;
		$this->setJavascript("");
	}
	
	public function setInnerHTML($html){
		parent::setInnerHTML($html);
	}
	
	public function setPathToRoot($path){
		$this->path_to_root = $path;
	}
	
	public function setDatasource($data){
		$this->datasource = $data;
	}
	
	public function createLink(){
$this->link = <<<EEE
<a href="../../shared/no_js.php" id="show_menu">
	<span id="cont_menu" style="padding: 5px 5px 1px 30px; ">
    	<img src="{$this->path_to_root}/images/19.png" style="opacity: .5" />
    </span>
</a>
EEE;
	}
	
	public function printLink(){
		echo $this->link;
	}
	
	public function toHTML(){
		$style = "";
		foreach ($this->style as $st) {
			$style .= $st['key'].": ".$st['value'].";";
		}
		$html = "<".$this->element." id='".$this->id."' class='".$this->cssClass."' style='{$style}'>";
		echo $html;
		echo $this->innerHTML;
		echo "</".$this->element.">";
	}
	
	public function setJavascript($code, $library = "prototype"){
		if($code == ""){
			if ($library == "prototype"){
$this->javascript = <<<EDT
function show_menu(el) {
	if($('cmenu').style.display == "none") {
	    position = getElementPosition(el);
	    dimensions = $(el).getDimensions();
	    ftop = position['top'] + dimensions.height;
	    fleft = position['left'] - 182 + dimensions.width;
	    $('cmenu').setStyle({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    Effect.BlindDown('cmenu', { duration: 1.0 });
	}
	else {
		Effect.BlindUp('cmenu', { duration: 1.0 });
	}
}
document.observe("dom:loaded", function(){
	$('show_menu').observe("click", function(event){
		event.preventDefault();
		show_menu("cont_menu");
	});
	$('cmenu').observe("mouseleave", function(event){
		event.preventDefault();
		show_menu("cmenu");
	})
});
EDT;
			}
			else if ($library == "jquery"){
				$this->javascript = <<<EDT
function show_menu(el) {
	if($('#cmenu').css('display') == "none") {
	    position = getElementPosition(el);
	    dimensions_h = $('#'+el).height();
	    dimensions_w = $('#'+el).width();
	    ftop = position['top'] + dimensions_h;
	    fleft = position['left'] - 91 + dimensions_w;
	    $('#cmenu').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
	    $('#cmenu').slideDown(1000);
	}
	else {
		$('#cmenu').slideUp(500);
	}
}
$(function(){
	$('#show_menu').click(function(event){
		event.preventDefault();
		show_menu("cont_menu");
	});
	$('#cmenu').mouseleave(function(event){
		event.preventDefault();
		show_menu("cmenu");
	})
});
EDT;
			}
		}
		else {
			$this->javascript = $code;
		}
	}
	
	public function createInnerHTML(){
		$html = '<br /><a href="materie.php" style="display: block; padding: 0px 0 0 5px; margin: 10px 0 0 0; line-height: 18px">&middot;&nbsp;&nbsp;&nbsp;Tutte le materie</a>';
		foreach ($this->datasource as $data){
		  	$html .= '<a href="materie.php?sc='.$data['id'].'" style="display: block; padding: 0px 0 0 5px; margin: 0; text-transform: capitalize; line-height: 18px">&middot;&nbsp;&nbsp;&nbsp;'.$data['desc'].'</a>';
		}
		$this->innerHTML = $html;
	}
	
	public function getJavascript(){
		return $this->javascript;
	}
	
}

?>