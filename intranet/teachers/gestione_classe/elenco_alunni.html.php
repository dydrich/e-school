<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Elenco alunni</title>
<link rel="stylesheet" href="../../../css/reg.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../css/general.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="../../../modules/communication/theme/jquery-ui-1.10.3.custom.min.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="../../../js/page.js"></script>
<script type="text/javascript">
$(function(){
	$('.show_ctx').click(function(event){
		event.preventDefault();
		var _id = this.id;
		data = _id.split("_");
		<?php //if((!$_SESSION['__user__']->isCoordinator($_SESSION['__classe__']->get_ID())) && (!$_SESSION['__user__']->isAdministrator()) ): ?>
		//return false;
		<?php //else: ?>
		show_menu(event, data[1]);
		<?php //endif; ?>
	});
	$('#std_list_ctx').mouseleave(function(event){
		$('#std_list_ctx').hide(300);
	});
	$('.profile_link').click(function(event){
		event.preventDefault();
		show_profile();
	});
	$('.profile_print').click(function(event){
		event.preventDefault();
		print_profile();
	});
});

var stid = 0;
var IE = document.all?true:false;
if (!IE) document.captureEvents(Event.MOUSEMOVE);
var tempX = 0;
var tempY = 0;

var show_menu = function(e, _stid){
	if (IE) { 
        tempX = event.clientX + document.body.scrollLeft;
        tempY = event.clientY + document.body.scrollTop;
    } else {  
        tempX = e.pageX;
        tempY = e.pageY;
    }  
    
    if (tempX < 0){tempX = 0;}
    if (tempY < 0){tempY = 0;}  
    $('#std_list_ctx').css({top: parseInt(tempY)+"px"});
    $('#std_list_ctx').css({left: parseInt(tempX)+"px"});
    $('#std_list_ctx').show(300);
    stid = _stid;
    return false;
};

var show_profile = function(){
	document.location.href = "dettaglio_alunno.php?stid="+stid;
};

var print_profile = function(){
	document.location.href = "pdf_dettaglio_alunno.php?stid="+stid;
};
</script>
<style>
tbody tr:hover {
	background-color: rgba(211, 222, 199, 0.6);
}
tbody a {
	text-decoration: none
}
</style>
</head> 
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
<div id="right_col">
<?php include "class_working.php" ?>
</div>
<div id="left_col">
<div class="page_label">
	Elenco alunni (<?php print $res_alunni->num_rows ?>)
</div>
<div class="outline_line">
	<div class="outline_cell wd_30">Nome e cognome</div>
	<div class="outline_cell wd_15 _right">Data nascita</div>
	<div class="outline_cell wd_30">Indirizzo</div>
	<div class="outline_cell wd_20">Telefono</div>
	<div class="outline_cell wd_5">Rip.</div>
</div>
<table id="std_list" class="wd_95 _elem_center">
<thead>
</thead>
<tbody>
	<?php 
	$background = "";
	$idx = 1;
	while($alunno = $res_alunni->fetch_assoc()){
		$ripetente = "NO";
		if($alunno['ripetente'] == 1)
			$ripetente = "SI";
			
		// estraggo l'indirizzo e il telefono
		$address = "Non presente";
		$phone = "Non presente";
		$sel_add = "SELECT * FROM rb_indirizzi_alunni WHERE id_alunno = ".$alunno['id_alunno'];
		$res_add = $db->execute($sel_add);
		if($res_add->num_rows > 0){
			$add = $res_add->fetch_assoc();
			if($add['indirizzo'] != ""){
				$address = $add['indirizzo'];
			}

			$tel = array();
			if (strlen($add['telefono']) > 0){
				$t = explode(";", $add['telefono']);
				foreach ($t as $row){
					list($number, $desc) = explode("#", $row);
					$tel[] = array("desc" => $desc, "number" => $number);
				}
			}
		}
		
		$data_nascita = "--";
		if ($alunno['data_nascita'] != ""){
			$data_nascita = format_date($alunno['data_nascita'], SQL_DATE_STYLE, IT_DATE_STYLE, "/");
		}
		
	?>
	<tr class="bottom_decoration">
		<td class="wd_35 first_cell"><a href="#" id="id_<?php print $alunno['id_alunno'] ?>" class="show_ctx"><?php print ($alunno['cognome']." ".$alunno['nome']) ?></a></td>
		<td class="wd_10 _center"><?php print $data_nascita ?></td>
		<td class="wd_30 _center"><span id="add<?php print $alunno['id_alunno'] ?>"><?php print $address ?></span></td>
		<td class="wd_20 _center"><span id="phn<?php print $alunno['id_alunno'] ?>"><?php if (isset($tel) && count($tel) > 0) echo $tel[0]['number']; if (isset($tel) && count($tel) > 1) echo " ..." ?></span></td>
		<td class="wd_5 _center"><?php print $ripetente ?></td>
	</tr>
	<?php 
		$idx++;
	}
	?>
</tbody>
</table>
<form id="testform" method="post">
<p>
	<input type="hidden" name="fname" id="fname" />
	<input type="hidden" name="lname" id="lname" />
	<input type="hidden" name="stid" id="stid" />
</p>
</form>
</div> 
<p class="spacer"></p>
<!-- menu contestuale -->
    <div id="std_list_ctx" class="context_menu">
    	<a href="#" class="profile_link">Visualizza il profilo</a><br />
    	<!-- <a href="#" class="profile_print">Stampa scheda</a><br /> -->
    </div>
<!-- fine menu contestuale -->
</div>
<?php include "../footer.php" ?>
</body>
</html>