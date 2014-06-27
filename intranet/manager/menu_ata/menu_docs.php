<?php 
$sel_types = "SELECT * FROM rb_document_types WHERE (id <> 1 AND id <> 8)  ORDER BY id ";
try{
	$res_types = $db->executeQuery($sel_types);
} catch (MySQLException $ex){
	$ex->alert();
}
$types = array();
while($type = $res_types->fetch_assoc()){
	if( ($type['codice'] == "AP" && (!is_installed("albo"))) || ($type['codice'] == "PG" && (!is_installed("project"))) ){
		continue;
	}
	$types[$type['id']] = $type['commento'];
}
?>
		
<div class="smallbox" id="working">
<h2 class="menu_head">Documenti</h2>
	<p class="menu_label class_icon">Gestisci</p>
	<ul class="menublock" style="" dir="rtl">
		<li><a href="school_docs.php">Documenti della scuola</a></li>
	</ul>
	<p class="menu_label act_icon">Vedi</p>
	<ul class="menublock" style="" dir="rtl">
		<?php 
		while(list($k, $v) = each($types)){
		?>
		<li><a href="documenti.php?tipo=<?php echo $k ?>"><?php echo $v ?></a></li>
		<?php 
		} 
		?>
	</ul>
</div>