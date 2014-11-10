<?php 
if(date("Y-m-d") < $fine_q){
	$q = 1;
}
else{
	$q = 2;
}
?>
<div class="smallbox" id="working">
	<p class="menu_label class_icon">Registro</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="registro.php">Registro di classe</a></li>
		<li><a href="voti.php">Voti</a></li>
	</ul>
	<p class="menu_label act_icon">In classe</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="orario.php">Orario</a></li>
		<li><a href="compiti.php">Compiti</a></li>
		<li><a href="attivita.php">Attivit&agrave;</a></li>
		<li><a href="lezioni.php">Lezioni</a></li>
	</ul>
</div>
