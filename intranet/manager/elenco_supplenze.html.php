<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
			$('.docs_row').mouseover(function(event){
				$('#'+this.id).css({cursor: 'pointer'});
			});
			$('.docs_row').click(function(event){
				event.preventDefault();
				var strs = this.id.split("_");
				document.location.href = "supplenza.php?id="+strs[1];
			});
		});
	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu_supplenze.php" ?>
	</div>
	<div id="left_col">
		<div class="outline_line_wrapper" style="margin-top: 20px">
			<div style="width: 24%; float: left; position: relative; top: 30%; padding-left: 10px">Titolare</div>
			<div style="width: 25%; float: left; position: relative; top: 30%">Supplente</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Classi</div>
			<div style="width: 12%; float: left; position: relative; top: 30%">Inizio</div>
			<div style="width: 12%; float: left; position: relative; top: 30%">Termine</div>
			<div style="width: 10%; float: left; position: relative; top: 30%">Giorni</div>
		</div>
		<table style="width: 95%; margin: 20px auto 0 auto">
			<?php
			foreach ($supplenze as $k => $supplenza) {
			?>
			<tr id="row_<?php echo $k ?>" class="<?php echo $row_class ?>">
				<td style="width: 25%; text-align: left"><?php echo $supplenza['tit'] ?></td>
				<td style="width: 25%; text-align: left"><?php echo $supplenza['sup'] ?></td>
				<td style="width: 15%; text-align: center"><?php echo join(", ", $supplenza['classi']) ?></td>
				<td style="width: 12%; text-align: center"><?php echo format_date($supplenza['data_inizio_supplenza'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
				<td style="width: 12%; text-align: center"><?php echo format_date($supplenza['data_fine_supplenza'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?></td>
				<td style="width: 10%; text-align: center"><?php echo $supplenza['days'] ?></td>
				</a>
			</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="6" style="height: 25px"></td>
			</tr>
		</table>
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
</body>
</html>
