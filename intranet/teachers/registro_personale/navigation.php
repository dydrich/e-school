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
	<div id="head_label" style="width: 390px"><p style="margin-top: 5px; vertical-align: top"><?php echo $navigation_label ?></p></div>
	<?php if($is_teacher_in_this_class && $_SESSION['__user__']->getSubject() != 27 && $_SESSION['__user__']->getSubject() != 44){ ?>
	<div class="nav_div" style="width: 95px"><a href="index.php?q=<?php echo $qd.$qsub ?>" style="position: relative; top: 5px">Registro</a></div>
		<?php if ($ordine_scuola == 1): ?>
	<div class="nav_div" style="width: 95px"><a href="absences.php" style="position: relative; top: 5px">Assenze</a></div>
		<?php endif; ?>
	<div class="nav_div" style="width: 95px"><a href="tests.php" style="position: relative; top: 5px">Verifiche</a></div>
	<div class="nav_div" style="width: 95px"><a href="lessons.php" style="position: relative; top: 5px">Lezioni</a></div>
	<div class="nav_div" style="width: 95px"><a href="scrutini.php?q=<?php echo $_q ?>" style="position: relative; top: 5px">Scrutini</a></div>
	<?php } else { ?>
	<div class="nav_div" style="width: 95px"><a href="scrutini_classe.php?q=<?php echo $_q ?>" style="position: relative; top: 5px">Scrutini</a></div>
	<?php } ?>
	<div class="nav_div" style="width: 95px"><a href="../index.php" style="position: relative; top: 5px">Home </a></div>
</nav>