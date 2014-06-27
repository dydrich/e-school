<?php 
/*
 * costruzione della stringa da passare come argomento a change_subject()
 */
$update_string = "";
//if(!isset($_SESSION['materie'])){
	$sel_materie = "SELECT * FROM materie WHERE has_sons = 0 AND id_materia < 20 AND id_materia != 11 AND id_materia != 10";
	$res_materie = $db->executeQuery($sel_materie);
	$materie = array();
	while($m = $res_materie->fetch_assoc()){
		$mat = strtolower($m['materia']);
		$mat = preg_replace("/ /", "_", $mat);
		$materie[$m['id_materia']] = $mat;
		$update_string .= "<a href='#' onclick='document.location.href=\\\"stats.php?do=subject&sbj=".$m['id_materia']."\\\"'>".$m['materia']."</a><br />";
	}
	$_SESSION['materie'] = $materie;
	$_SESSION['update_string'] = $update_string;
//}

	/*
 * costruzione della stringa da passare come argomento a change_class()
 */
$update_string_classes = "<table style='width: 90%; margin: auto; text-align: center; border-collapse: collapse'><tr>";
//if(!isset($_SESSION['materie'])){
	$select = "SELECT * FROM classi ORDER BY sezione, classe";
	$res = $db->executeQuery($select);
	$classi = array();
	$sezione = "A";
	// contatore delle celle per riga, per la divisione per sezioni
	$count_cell = 1;
	$index_row = 0;
	$bgcolor = "";
	while($c = $res->fetch_assoc()){
		if($sezione != $c['sezione']){
			$index_row++;
			$bgcolor = "";
			if($index_row%2)
				$bgcolor = "background-color: #eaeaea";
			if($count_cell < 4){
				if($count_cell == 3){
					$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td>";
					$count_cell++;
				}
				else {
					$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td><td style='$bgcolor'>&nbsp;</td>";
					$count_cell += 2;
				}
			}
			if($count_cell == 4)
				$count_cell = 1;
			$update_string_classes .= "</tr><tr>";
			if(($count_cell == 1 ) || ($count_cell == 2)){
				if($count_cell != $c['classe']){
					if(($c['classe'] - $count_cell) == 1){
						$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td>";
						$count_cell++;
					}
					else {
						$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td><td style='$bgcolor'>&nbsp;</td>";
						$count_cell += 2;
					}
				}
			}

		}
		$classi[$c['id_classe']] = $c['classe'].$c['sezione'];
		$update_string_classes .= "<td style='width: 33%; $bgcolor'><a href='#' onclick='document.location.href=\\\"stats.php?do=class_summary&cls=".$c['id_classe']."\\\"'>".$c['classe'].$c['sezione']."</a></td>";
		$sezione = $c['sezione'];
		$count_cell++;
	}
	if($count_cell < 4){
		if($count_cell == 3){
			$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td>";
		}
		else {
			$update_string_classes .= "<td style='$bgcolor'>&nbsp;</td><td style='$bgcolor'>&nbsp;</td>";
		}
	}
	$update_string_classes .= "</tr></table>";
	$_SESSION['classi'] = $materie;
	$_SESSION['update_string_classes'] = $update_string_classes;
//}
?>
<script type="text/javascript">
var change_subject = function(){
	var win = new Window({className: "mac_os_x",  width:400, height:null, zIndex: 100, resizable: true, title: "Cambia materia", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.getContent().update("<div style='width: 90%; text-align: left; padding-left: 20px; font-size: 13px; margin-top: 10px; margin-bottom: 20px'><p style='text-align: center; font-weight: bold'>Seleziona la materia</p><?php print $_SESSION['update_string'] ?></div>");     
	win.showCenter(true);
};

var change_class = function(){
	var win = new Window({className: "mac_os_x",  width:240, height:null, zIndex: 100, resizable: true, title: "Cambia materia", showEffect:Effect.Appear, hideEffect: Effect.Fade, draggable:true, wiredDrag: true});
	win.getContent().update("<div style='width: 90%; text-align: center; padding-left: 20px; font-size: 13px; margin-top: 10px; margin-bottom: 20px'><p style='text-align: center; font-weight: bold'>Seleziona la classe</p><?php print $_SESSION['update_string_classes'] ?></div>");     
	win.showCenter(true);
};
</script>
			<!-- SIDEBAR -->	
			<?php include "about.php" ?>
			<h4>Alunni</h4>
			<ul class="blocklist">
				<li><a href="#">Assenze e ritardi</a></li>
				<li style="padding-left: 15px"><a href="registro.php?do=abs">Alunni a rischio</a></li>
				<li style="padding-left: 15px"><a href="registro.php?do=cls">Riepilogo classi</a></li>
				<li><a href="#">Media voto generale</a></li>
					<li style="padding-left: 15px"><a href="stats.php?do=student">Per alunno</a></li>
					<li style="padding-left: 15px"><a href="stats.php?do=class">Per classe</a></li>
					<li style="padding-left: 15px"><a href="stats.php?do=class_sex">Per classe e sesso</a></li>
				<!-- <li><a href="stats.php?do=subject">Media voto per materia</a></li> -->
				<li><a href="#" onclick="change_subject()">Media voto per materia</a></li>
				<li><a href="#" onclick="change_class()">Media classe: riepilogo</a></li>
			</ul>
			<!-- SIDEBAR -->