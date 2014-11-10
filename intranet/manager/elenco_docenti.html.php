<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
	<?php include $_SESSION['__administration_group__']."/menu.php" ?>
	</div>
	<div id="left_col">
		<div class="card_container">

	 	    <?php 
	 	    if($res_docenti->num_rows > $limit)
	 	    	$max = $limit;
	 	    else
	 	    	$max = $res_docenti->num_rows;
	 	    $x = 0;
	 	    $bgcolor = "";
	 	    while($docente = $res_docenti->fetch_assoc()){
	 	    	if($x >= $limit) break;
	 	    	
	 	    	if ($docente['id_materia'] != 27 && $docente['id_materia'] != 41){
			        if ($docente['ruolo'] == 'N') {
				        $sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_classi_supplenza, rb_supplenze WHERE id_classe = classe AND id_supplente = ".$docente['uid']." AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			        }
			        else {
		 	    	    $sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_cdc WHERE rb_classi.id_classe = rb_cdc.id_classe AND rb_cdc.id_docente = ".$docente['uid']." AND id_anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
			        }
		 	    	//print $sel_classes;
		 	    	$res_classes = $db->execute($sel_classes);
		 	    	$classi = array();
		 	    	while($cl = $res_classes->fetch_assoc()){
		 	    		if(!in_array($cl['anno_corso'].$cl['sezione'], $classi))
		 	    			array_push($classi, $cl['anno_corso'].$cl['sezione']);
		 	    	}
		 	    }
		 	    else {
					$sel_classes = "SELECT anno_corso, sezione FROM rb_classi, rb_assegnazione_sostegno WHERE rb_classi.id_classe = rb_assegnazione_sostegno.classe AND rb_assegnazione_sostegno.docente = ".$docente['uid']." AND anno = ".$_SESSION['__current_year__']->get_ID()." ORDER BY rb_classi.sezione, rb_classi.anno_corso";
					//print $sel_classes;
					$res_classes = $db->execute($sel_classes);
					$classi = array();
					while($cl = $res_classes->fetch_assoc()){
						if(!in_array($cl['anno_corso'].$cl['sezione'], $classi))
							array_push($classi, $cl['anno_corso'].$cl['sezione']);
					}
				}
	 	    ?>
				<a href="registro_docente.php?doc=<?php echo $docente['uid'] ?>" class="no_dec">
			        <div class="card">
				        <div class="card_title">
					        <?php echo $docente['cognome']." ".$docente['nome'] ?>
					        <div style="float: right; margin-right: 20px; width: 150px" class="normal">
						        <?php echo $docente['materia'] ?>
					        </div>
				        </div>
				        <div class="card_minicontent">
					        Classi: <?php echo join(", ", $classi) ?>
				        </div>
			        </div>
				</a>
	 	    <?php 
	 	    	$x++;
	 	    }
	 	    include "../../shared/navigate.php";
            ?>
	 	 </div>
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link"><a href="index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="profile.php"><img src="../../images/33.png" style="margin-right: 10px; position: relative; top: 5%" />Profilo</a></div>
		<div class="drawer_link"><a href="../../modules/documents/load_module.php?module=docs&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/11.png" style="margin-right: 10px; position: relative; top: 5%" />Documenti</a></div>
		<?php if(is_installed("com")){ ?>
			<div class="drawer_link"><a href="<?php echo $_SESSION['__path_to_root__'] ?>modules/communication/load_module.php?module=com&area=<?php echo $_SESSION['__area__'] ?>"><img src="../../images/57.png" style="margin-right: 10px; position: relative; top: 5%" />Comunicazioni</a></div>
		<?php } ?>
		<?php if ($_SESSION['__role__'] == "Dirigente scolastico"): ?>
			<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
		<?php endif; ?>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
