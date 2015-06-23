<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?></title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../css/general.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
		$(function(){
			load_jalert();
			setOverlayEvent();
		});
		var show_div = function(div, elem){
			if($('#'+div).is(":hidden")){
				$('#'+div).show(1000);
				parent = elem.parentNode;
				parent.css({backgroundColor: "rgba(30, 67, 137, .1)"});
			}
			else{
				$('#'+div).hide(1000);
				parent = elem.parentNode;
				parent.css({backgroundColor: ""});
			}
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
	<div class="outline_line_wrapper" style="margin-top: 20px">
		<div style="width: 33%; float: left; position: relative; top: 30%"><span style="padding-left: 15px">Assenze: <?php print $tot_assenze ?></span></div>
		<div style="width: 33%; float: left; position: relative; top: 30%; text-align: center">Ritardi: <?php print $somma_ritardi['giorni_ritardo']?> (<?php print substr($somma_ritardi['ore_ritardo'], 0, 5) ?>)</div>
		<div style="width: 33%; float: left; position: relative; top: 30%">Uscite anticipate: <?php print $somma_uscite['giorni_anticipo']?> (<?php print substr($somma_uscite['ore_perse'], 0, 5) ?>)</div>
	</div>
    <table style="width: 98%; margin: 20px auto 0 auto">
            <?php 
			$x = 9;
			if(isset($quadrimestre) && $quadrimestre == 2)
				$x = 2;
			foreach($mesi as $mese){
				if($x == 13) {
					$x = 1;
				}
				$x_str = $x;
				if(strlen($x_str) < 2){
					$x_str = "0".$x;
				}
			?>
		<tr class="manager_row_small">
            <td style="width: 32%">
            	<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_assenza', this)" style="text-decoration: none; <?php if(count($assenze[$x_str])) print("font-weight: bold") ?>">Mese di <?php print $mese ?>: <?php print count($assenze[$x_str]) ?> assenze</a>
				</div>
				<div id="<?php print $mese ?>_assenza" style="display: none; text-align: left; margin-bottom: 0; padding-top: 10px">
				<?php 
				foreach ($assenze[$x_str] as $abs){
					$giorno_str = strftime("%A", strtotime($abs));
				?>
				<span style="padding-left: 40px; font-weight: normal; color: rgba(30, 67, 137, 1)">
				<?php print $giorno_str." " . format_date($abs, SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>
				</span><br />
				<?php 
				}
				?>
					<span>&nbsp;</span>
				</div>
            </td>
            <td style="width: 32%">
				<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_anticipata', this)" style='text-decoration: none; <?php if(isset($ritardi[$x_str]) && count($ritardi[$x_str]) > 0) print("font-weight: bold") ?>'>Mese di <?php print $mese ?>: <?php if (isset($ritardi[$x_str])) print count($ritardi[$x_str]) ?> ritardi</a>
				</div>
				<div id="<?php print $mese ?>_anticipata" style="display: none; text-align: left; margin-bottom: 0; padding-top: 10px;">&nbsp;
					<?php 
					foreach($ritardi[$x_str] as $day){
						$giorno_str = strftime("%A", strtotime($day['data']));
					?>
						<span style="color: rgba(30, 67, 137, 1)"><?php print $giorno_str." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['ingresso'], 0, 5) ?></span><br />
					<?php 
					}
					?>	
					<span>&nbsp;</span>				
				</div>
			</td>
            <td style="width: 32%">
				<div style="padding-left: 15px; text-align: left; font-weight: normal; height: 15px; padding-top: 8px;">
					<a href="#" onclick="show_div('<?php print $mese ?>_ritardo', this)" style='text-decoration: none; <?php if(isset($uscite[$x_str]) && count($uscite[$x_str]) > 0) print("font-weight: bold") ?>'>Mese di <?php print $mese ?>: <?php if(isset($uscite[$x_str])) print count($uscite[$x_str]) ?> anticipi</a>
				</div>
				<div id="<?php print $mese ?>_ritardo" style="display: none; text-align: left; margin-bottom: 15px">&nbsp;
					<?php
					if (isset($uscite[$x_str])) {
						foreach($uscite[$x_str] as $day){
							$giorno_str = strftime("%A", strtotime($day['data']));
					?>
						<span style="color: rgba(30, 67, 137, 1)"><?php print $giorno_str." ".format_date($day['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>: ore <?php print substr($day['uscita'], 0, 5) ?></span><br />
					<?php 
						}
					}
					?>	
					<span>&nbsp;</span>				
				</div>
			</td>
            </tr>
			<?php
				$x++;
				if(isset($quadrimestre) && $quadrimestre == 1 && $x == 2)
					break;
			} 
			?> 
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
