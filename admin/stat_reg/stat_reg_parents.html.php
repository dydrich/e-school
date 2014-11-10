<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Admin home page</title>
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
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
<?php include "../header.php" ?>
<?php include "../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "stat_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: 10px" class="rb_button">
			<a href="print_stat_reg_parents.php<?php if(isset($_GET['school_order'])) echo "?school_order=".$_GET['school_order'] ?>">
				<img src="../../images/pdf-32.png" style="padding: 4px 0 0 7px" />
			</a>
		</div>
		<table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse" class="admin_table">
            <?php 
            foreach ($classi as $cls){
            ?>
            <tr>
                <td colspan="2" class="_bold _center accent_decoration" style="padding-top: 15px">
	                Classe <?php echo $cls['cls']['anno_corso'].$cls['cls']['sezione'] ?>: <?php echo $cls['cls']['nome'] ?> (<?php echo count($cls['alunni']) ?>)
                </td>
            </tr>
        	<?php 
				foreach ($cls['alunni'] as $alunno){
			?>
			<tr class="bottom_decoration">
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
