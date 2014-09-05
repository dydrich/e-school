<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco gruppi</title>
	<link href="../../../css/site_themes/blue_red/reg.css" rel="stylesheet" />
	<link href="../../../css/general.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../../css/jquery/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../../js/page.js"></script>
	<script type="text/javascript">

	</script>
</head>
<body>
<?php include "../../header.php" ?>
<?php include "../../navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Elenco gruppi: pagina <?php echo $page ?> di <?php echo $pagine ?></div>
		<table class="admin_table">
			<thead>
			<tr>
				<td style="width: 20%" class="adm_titolo_elenco_first">Nome</td>
				<td style="width: 20%" class="adm_titolo_elenco">Amministratore</td>
				<td style="width: 60%" class="adm_titolo_elenco_last _center">Membri</td>
			</tr>
			<tr class="admin_row_before_text">
				<td colspan="3"></td>
			</tr>
			</thead>
			<tbody>
			<?php
			$x = 1;
			if(count($threads) > $limit) {
				$max = $limit;
			}
			else {
				$max = count($threads);
			}

			foreach ($array[$page - 1] as $th) {
				$th->restoreThread(new MySQLDataLoader($db));
				$owner = $th->getOwner();
				if ($owner != null) {
					$owner_name = $owner->getFullName();
				}
				else {
					$owner_name = "Admin";
				}
				$us_array = array();
				foreach ($th->getUsers() as $user) {
					$ud = $rb->loadUserFromUniqID($user);
					$us_array[$user] = $ud->getFullName();
				}
				sort($us_array);

			?>
			<tr class="admin_row" id="row_<?php echo $th->getTid() ?>">
				<td style="padding-left: 10px; ">
					<span class="ov_red" style="font-weight: bold"><?php echo $th->getName() ?></span>
					<div id="link_<?php echo $th->getTid() ?>" style="display: none">
						<a href="../../adm_users/dettaglio_utente.php?id=<?php echo $th->getTid() ?>&page=<?php echo $page ?>" class="mod_link">Modifica</a>
						<span style="margin-left: 5px; margin-right: 5px">|</span>
						<a href="../../adm_users/users_manager.php?action=2&id=<?php echo $th->getTid() ?>" class="del_link">Cancella</a>
					</div>
				</td>
				<td><?php echo $owner_name ?></td>
				<td class="_center"><?php echo implode(", ", $us_array); ?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			<tfoot>
			<?php
			include "../../../shared/navigate.php";
			?>
			<tr class="admin_menu">
				<td colspan="3">

				</td>
			</tr>
			</tfoot>
		</table>
	</div>
	<p class="spacer"></p>
</div>
<?php include "../../footer.php" ?>
</body>
</html>
