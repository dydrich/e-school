<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Elenco incarichi</title>
	<link rel="stylesheet" href="../../font-awesome/css/font-awesome.min.css">
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link href="../../css/general.css" rel="stylesheet" />
	<link href="../../css/site_themes/<?php echo getTheme() ?>/reg.css" rel="stylesheet" />
	<link rel="stylesheet" href="../../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../../js/page.js"></script>
	<script type="text/javascript">
        var rid = 0;
        var del_sede = function(){
            $('#confirm').fadeOut(10);
            var url = "role_manager.php";
            $.ajax({
                type: "POST",
                url: url,
                data:  {action: 'del', rid: rid},
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
                        j_alert("error", json.message);
                        console.log(json.dbg_message);
                    }
                    else {
                        j_alert("alert", json.message);
                        window.setTimeout(function() {
                            document.location.href = 'roles.php';
                        }, 2000);
                    }
                }
            });
        };

        $(function(){
            load_jalert();
            setOverlayEvent();
            $('a.del_link').click(function(event){
                event.preventDefault();
                rid = $(this).data('rid');
                var count = $(this).data('count');
                if (count > 0) {
                    j_alert("error", "Impossibile cancellare l'incarico in quanto risulta attualmente assegnato. Eliminare prima le assegnazioni");
                }
                else {
                    j_alert("confirm", "Eliminare questo incarico?");
                }
            });

            $('#okbutton').on('click', function (event) {
                event.preventDefault();
                del_sede();
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
			<a href="role.php?id=0">
				<img src="../../images/39.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div class="card_container" style="margin-top: 20px">
			<?php
			$x = 1;
			while($role = $res_roles->fetch_assoc()){
			    $count = 0;
			    if (isset($roles[$role['rid']])){
			        $count = count($roles[$role['rid']]);
                }
				?>
				<div class="card" id="row_<?php echo $role['rid'] ?>">
					<div class="card_title">
						<a href="role.php?rid=<?php echo $role['rid'] ?>" class="mod_link"><?php echo $role['nome'] ?></a>
						<div style="float: right; margin-right: 20px" id="del_<?php echo $role['rid'] ?>">
							<a href="#" data-rid="<?php echo $role['rid'] ?>" data-count="<?php echo $count ?>" class="del_link">
								<img src="../../images/51.png" style="position: relative; bottom: 2px" />
							</a>
						</div>
					</div>
					<div class="card_content">
						<?php
						if (isset($roles[$role['rid']])) {
							$str = "";
							foreach ($roles[$role['rid']] as $uid => $row) {
								$str .= $row['cognome']." ".$row['nome'].", ";
							}
							$str = substr($str, 0, (strlen($str) - 2));
							echo $str;
						}
						?>
					</div>
				</div>
				<?php
				$x++;
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
		<div class="drawer_link"><a href="https://www.istitutoiglesiasserraperdosa.gov.it"><img src="../../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../../shared/do_logout.php"><img src="../../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
