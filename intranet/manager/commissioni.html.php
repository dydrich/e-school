<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?>Esami di stato: commissioni d'esame</title>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/communication.css" type="text/css" media="screen,projection" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
        var _vice = 0;
        var segretario = 0;

        $(function(){
            load_jalert();
            setOverlayEvent();

            $(".autouser").autocomplete({
                source: "../../shared/get_users.php?group=teachers&ord=1",
                minLength: 2,
                select: function(event, ui){
                    if ($(this).attr('id') == 'vice') {
                        _vice = ui.item.uid;
                    }
                    else {
                        segretario = ui.item.uid;
                    }
                }
            });

            $('#create_data').on('click', function (event) {
                event.preventDefault();
                registra();
            });
        });

        var registra = function(){
            $.ajax({
                type: "POST",
                url: 'gestione_commissioni.php',
                data: {action: 'create_records'},
                dataType: 'json',
                error: function() {
                    j_alert("error", "Errore di trasmissione dei dati");
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
                    else if(json.status == "ko") {
                        j_alert("error", "Impossibile completare l'operazione richiesta. Riprovare tra qualche secondo o segnalare l'errore al webmaster");
                        return;
                    }
                    else {
                        j_alert("alert", json.message);
                        setTimeout(function () {
                            document.location = document.location;
                        }, 2000);
                    }
                }
            });
        }

	</script>
</head>
<body>
<?php include "header.php" ?>
<?php include $_SESSION['__administration_group__']."/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include $_SESSION['__administration_group__']."/menu_esami.php" ?>
	</div>
	<div id="left_col">
        <?php
        if ($create) {
        ?>
        <div style="width: 90%; margin: auto">
            <p class="normal _bold">Nessuna commissione presente</p>
            <p>
                <a href="#" id="create_data">Inserisci commissioni</a>
            </p>
        </div>
        <?php
        }
        else {
			?>
            <div class="card_container" style="margin-top: 20px">
                <div class="card">
                    <div class="card_title">
                        Commissione d'esame
                    </div>
                    <div class="card_varcontent">
						<?php
						$index = 0;
						foreach ($commissione as $k => $teacher) {
							if ($index != 0) {
								echo ", ";
							}
							echo $teacher['cognome'] . " " . $teacher['nome'];
							$index++;
						}
						?>
                    </div>
                </div>
                <div class="card">
                    <div class="card_title card_nocontent normal _center">
                        Sottocommissioni d'esame
                    </div>
                </div>
				<?php
				$comm = 1;
				foreach ($cls as $id_sc => $sc) {
					?>
                    <div class="card">
                        <div class="card_title">
                            <a href="commissione.php?idc=<?php echo $id_sc ?>">
                                Sottocommissione n. <?php echo $sc['numero'] ?> (classe <?php echo "3".$sc['sezione'] ?>)
                            </a>
                        </div>
                        <div class="card_varcontent">
							<?php
							$index = 0;
							$cl_ams = new ArrayMultiSort($sc['cdc']);
							$cl_ams->setSortFields(array('cognome'));
							$cl_ams->sort();
							$sc['cdc'] = $cl_ams->getData();
							foreach ($sc['cdc'] as $k => $teacher) {
								if ($index != 0) {
									echo ", ";
								}
								echo $teacher['cognome'] . " " . $teacher['nome'];
								$index++;
							}
							?>
                        </div>
                    </div>
					<?php
					$comm++;
				}
				?>
            </div>
			<?php
		}
        ?>
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
