<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione materie</title>
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link href="../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">

		var del_subject = function(id){
			children = $('#children'+id).html();
			if(children != "") {
				j_alert("error", "Impossibile cancellare la materia: sono presenti delle sotto materie. Cancellare prima le sotto materie.");
				return false;
			}
			if(!confirm("Sei sicuro di voler cancellare questa materia?"))
		        return false;
			var url = "subject_manager.php";

			$.ajax({
				type: "POST",
				url: url,
				data:  {action: 2, _i: id},
				dataType: 'json',
				error: function() {
					show_error("Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						alert(json.message);
						console.log(json.dbg_message);
					}
					else {
						link = "materie.php?offset=<?php print $offset ?>";
						j_alert("alert", json.message);
						window.setTimeout(function() {
							document.location.href = link;
						}, 2000);
					}
				}
			});
		};

		<?php if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0){ echo $page_menu->getJavascript(); } ?>

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('a.del_link').click(function(event){
				event.preventDefault();
				var strs = this.parentNode.id.split("_");
				del_subject(strs[1]);
			});
		});

	</script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col" class="cardbody">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="dettaglio_materia.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
        <?php
        $index = 1;
        $printed = array();

        foreach ($subjects as $subject){
            $children = array();
            if ($subject->hasChildren()){
                foreach ($subject->getChildren() as $child) {
                    array_push($children, $child->getDescription());
                }
            }
        ?>
			<div class="card" id="row_<?php echo $subject->getId() ?>">
				<div class="card_title">
					<a href="dettaglio_materia.php?id=<?php echo $subject->getId() ?>" class="mod_link"><?php echo $subject->getDescription(); if((count($_SESSION['__school_level__']) > 1) && $_SESSION['__school_order__'] == 0) echo " (".$tipologie[$subject->getSchoolType()]['code'].")";  ?></a>
					<div style="float: right; margin-right: 20px" id="del_<?php echo $subject->getId() ?>">
						<a href="subject_manager.php?action=2&_id=<?php echo $subject->getId() ?>" class="del_link">
							<img src="../../images/51.png" style="position: relative; bottom: 2px" />
						</a>
					</div>
					<div class="normal" style="float: right; margin-right: 220px; text-align: center; width: 100px; <?php if (!$subject->isInReport()) echo "color: #656565" ?>"><?php echo ($subject->isInReport() ? "In pagella" : "Non in pagella") ?></div>
				</div>
				<div class="card_content">
					Sottomaterie: <?php if (count($children) > 0) echo join(", ", $children); else echo "nessuna" ?>
				</div>
			</div>
            <?php
            	$index++;
            }
            ?>
        </div>
	<p class="spacer"></p>
    </div>
</div>
<?php include "../footer.php" ?>
	<div id="drawer" class="drawer" style="display: none; position: absolute">
		<div style="width: 100%; height: 430px">
			<div class="drawer_link"><a href="../../index.php"><img src="../../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
			<div class="drawer_link"><a href="../index.php"><img src="../../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
			<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
		</div>
		<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
	</div>
</body>
</html>
