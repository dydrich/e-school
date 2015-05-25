<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		var cls = 0;
		var desc = "";
		var tp = 0;
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.showmenu').click(function(event){
				event.preventDefault();
				cls = $(this).attr("data-idclass");
				desc = $(this).attr("data-descclass");
				tp = $(this).attr("data-tp");
				_offset = $(this).parent().offset();
				show_menu(event, _offset);
			});
			$('#context_menu').mouseleave(function(){
				$('#context_menu').hide();
			})
			$('.cdc_link').click(function(event){
				event.preventDefault();
				document.location.href = "classe.php?id="+cls+"&show=cdc&tp="+tp+"&desc="+desc;
			})
			$('.schedule_link').click(function(event){
				event.preventDefault();
				document.location.href = "classe.php?id="+cls+"&show=orario&tp="+tp+"&desc="+desc;
			})
			$('.students_link').click(function(event){
				event.preventDefault();
				document.location.href = "classe.php?id="+cls+"&show=alunni&tp="+tp+"&desc="+desc;
			})
			$('.grades_link').click(function(event){
				event.preventDefault();
				document.location.href = "medie_classe.php?cls="+cls;
			})
			$('.notes_link').click(function(event){
				event.preventDefault();
				document.location.href = "note_classe.php?cls="+cls;
			})
		});

		var show_menu = function(e, _offset) {
			if ($('#context_menu').is(":visible")) {
				$('#context_menu').slideUp(500);
				return;
			}
			var _top = _offset.top + $('#cm_container').height();
			var _left = _offset.left - $('#context_menu').width();
			$('#context_menu').css({top: _top+'px', left: _left+'px'});
			$('#context_menu').slideDown(500);
		};
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
        if($res_cls->num_rows > $limit)
            $max = $limit;
        else
            $max = $res_cls->num_rows;
        $x = 0;
        $bgcolor = "";
        while($cls = $res_cls->fetch_assoc()){
            if($x >= $limit) break;
        ?>
	        <div class="card">
		        <div class="card_title">
			        <?php echo $cls['anno_corso'].$cls['sezione'] ?><span style="margin-left: 8px"><?php if (!$_SESSION['__school_order__']) echo $cls['tipo'] ?></span>
			        <div id="cm_container" style="float: right; width: 20px; margin-right: 10px">
				        <a href="#" class="showmenu" data-idclass="<?php echo $cls['id_classe'] ?>" data-descclass="<?php print $cls['anno_corso'].$cls['sezione'] ?>" data-tp="<?php echo $cls['tempo_prolungato'] ?>">
					        <img src="../../images/menu.png" />
				        </a>
			        </div>
		        </div>
		        <div class="card_minicontent">
			<?php if (!$_SESSION['__school_order__']) : ?>
			        <div class="minicard">
				        <?php echo $cls['nome'] ?>
			        </div>
		    <?php endif; ?>
			        <div class="minicard">
				        <?php echo $cls['num_alunni'] ?> alunni
			        </div>
		        </div>
	        </div>
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
		<div class="drawer_link"><a href="utility.php"><img src="../../images/59.png" style="margin-right: 10px; position: relative; top: 5%" />Utility</a></div>
	</div>
	<?php if (isset($_SESSION['__sudoer__'])): ?>
		<div class="drawer_lastlink"><a href="<?php echo $_SESSION['__path_to_root__'] ?>admin/sudo_manager.php?action=back"><img src="../../images/14.png" style="margin-right: 10px; position: relative; top: 5%" />DeSuDo</a></div>
	<?php endif; ?>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
<div id="context_menu" class="context_menu" style="display: none; width: 155px; height: 100px;">
	<a href="#" class="cdc_link">Consiglio di classe</a><br />
	<a href="#" class="schedule_link">Orario</a><br />
	<a href="#" class="students_link">Alunni</a><br />
	<a href="#" class="grades_link">Voti</a><br />
	<a href="#" class="notes_link">Provvedimenti disciplinari</a>
</div>
</body>
</html>
