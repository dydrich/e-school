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
		<div class="group_head">Genitori non registrati (<?php echo $num_alunni ?> totali)</div>
		<div style="margin-right: 20px; text-align: right; padding: 5px; margin-top: 10px">
			<a href="print_stat_reg_parents.php<?php if(isset($_GET['school_order'])) echo "?school_order=".$_GET['school_order'] ?>" class="standard_link">Stampa report</a>
		</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse" class="admin_table">
            <?php 
            foreach ($classi as $cls){
            ?>
            <tr>
                <td colspan="2" style="background-color: rgba(30, 67, 137, .6);" class="_bold _center">Classe <?php echo $cls['cls']['anno_corso'].$cls['cls']['sezione'] ?>: <?php echo $cls['cls']['nome'] ?> (<?php echo count($cls['alunni']) ?>)</td>
            </tr>
        	<?php 
				foreach ($cls['alunni'] as $alunno){
			?>
			<tr>
                <td style="" colspan="2"><?php echo $alunno['cognome'], " ", $alunno['nome'] ?></td>
            </tr>
			<?php
				}
			}
			?>
            <tr>
            	<td colspan="2">&nbsp;</td>
            </tr>
        </table>
    </div>
    <p class="spacer"></p>
	</div>
<?php include "../footer.php" ?>
</body>
</html>
