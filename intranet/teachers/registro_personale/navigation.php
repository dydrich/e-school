<?php

$ordine_scuola = $_SESSION['__user__']->getSchoolOrder();
$school_year = $_SESSION['__school_year__'][$ordine_scuola];
$fine_q = format_date($school_year->getFirstSessionEndDate(), IT_DATE_STYLE, SQL_DATE_STYLE, "-");

if (isset($_GET['q'])){
	$qd = $_GET['q'];
}
else if(date("Y-m-d") <= $fine_q){
	$qd = 1;
}
else{
	$qd = 2;
}

$date_max = date("Y-m-d", strtotime($fine_q." +35 days"));
if(date("Y-m-d") > $date_max){
	$_q = 2;
}
else{
	$_q = 1;
}
// subject
$qsub = "";
if (isset($_GET['subject'])){
	$qsub = "&subject=".$_GET['subject']; 
}
else {
	$qsub = "&subject=".$_SESSION['__materia__'];
}

$is_teacher_in_this_class = $_SESSION['__user__']->isTeacherInClass($_SESSION['__classe__']->get_ID());

?>
<nav id="navigation">
	<div id="head_label">
		<img src="<?php echo $_SESSION['__path_to_root__'] ?>images/ic_navigation_drawer3.png" id="open_drawer" style="float: left; position: relative; top: 18px" />
		<p id="drawer_label" style="margin-top: 17px; vertical-align: top; margin-left: 10px; float: left; color: white"><?php echo $drawer_label ?></p>
	</div>
	<div class="nav_div" style="float: right; margin-right: 50px; position: relative; top: 20px; text-align: right">Area docenti::<span id="navlabel"><?php echo $navigation_label ?></span></div>
	<div class="nav_div" style="clear: both"></div>
</nav>

