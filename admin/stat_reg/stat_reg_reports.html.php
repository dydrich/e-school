<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Admin home page</title>
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
</head>
<body>
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Pagelle scaricate<?php if ($school_order != 0) echo ": ",$school_orders[$school_order] ?></div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse">
            <?php
            if (count($classi) > 0){
                foreach ($classi as $k => $cls){
            ?>
            <tr>
                <td colspan="2" style="background-color: rgba(30, 67, 137, .6);" class="_bold _center">Classe <?php echo $cls['cls']['anno_corso'].$cls['cls']['sezione'] ?> <span id="cls_<?php echo $k ?>"></span></td>
            </tr>
        	<?php 
        		$ok = $ko = 0;
				foreach ($cls['alunni'] as $alunno){
					$color = "#003366";
					if($alunno['dw'] == ""){
						$alunno['dw'] = "Non scaricata";
						$color = "rgba(131, 2, 29, 0.8)";
						$ko++;
					}
					else{
						$ok++;
					}
			?>
			<tr class="bottom_decoration">
                <td style="width: 50%; color: <?php echo $color ?>"><?php echo $alunno['cognome'], " ", $alunno['nome'] ?></td>
                <td style="width: 50%; color: <?php echo $color ?>"><?php echo $alunno['dw'] ?></td>
            </tr>
			<?php
				}
			?>
			<script>
				$('cls_<?php echo $k ?>').update("<?php echo '(', $ok, ' di ', count($cls['alunni']), ')' ?>");
			</script>
			<?php
				}
            }
			?>
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td style=""></td>
                <td style="text-align: right">
	                <a href="<?php print $_SESSION['__config__']['root_site'] ?>" class="standard_link">Torna all'Home Page</a>
                </td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
</body>
</html>
