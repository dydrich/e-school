<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php print $_SESSION['__config__']['intestazione_scuola'] ?> gestione incarichi</title>
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
        var uid = 0;
        var role = 0;
        var action = '';
		$(function(){
			load_jalert();
			setOverlayEvent();

            $(".autouser").autocomplete({
                source: "../../shared/get_users.php?group=teachers&ord=<?php echo $_SESSION['__school_order__'] ?>",
                minLength: 2,
                select: function(event, ui){
                    uid = ui.item.uid;
                }
            });

            $('.add_user').on('click', function(event) {
                event.preventDefault();
                role = $(this).data('role');
                register('new', role, uid);
            });

            $('.del_usr').on('mouseover', function (event) {
               $(this).parent().css({backgroundColor: '#fff9c4'});
            }).on('mouseout', function (event) {
                $(this).parent().css({backgroundColor: ''});
            }).on('click', function (event) {
                event.preventDefault();
                uid = $(this).data('uid');
                role = $(this).data('role');
                j_alert("confirm", "Eliminare questo incarico per l'utente?");
            });

            $('#okbutton').on('click', function (event) {
                event.preventDefault();
                register('del', role, uid);
            });
		});

        var register = function(action, role, uid){
            if (action == 'del') {
                $('#confirm').fadeOut(10);
            }
            var url = "gestore_incarichi.php";
            $.ajax({
                type: "POST",
                url: url,
                data: {action: action, role: role, uid: uid},
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
                        return false;
                    }
                    else {
                        j_alert("alert", json.message);
                        window.setTimeout(function () {
                            document.location = document.location;
                        }, 2000);
                    }
                }
            });

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
		<?php
		while($role = $res_roles->fetch_assoc()) {
			?>
			<div class="welcome">
				<p id="w_head"><?php echo $role['nome'] ?></p>
					<?php
					if (isset($roles[$role['rid']])) {
						foreach ($roles[$role['rid']] as $uid => $user) {
					?>
				<p class="w_text" style="width: 350px">
					<?php echo $user['cognome']." ".$user['nome'] ?>
                    <a href="#" class="del_usr fright" data-uid="<?php echo $uid ?>" data-role="<?php echo $role['rid'] ?>" style="margin-right: 50px">
                        <i class="fa fa-trash"></i>
                    </a>
				</p>
				<?php
						}
					}
					?>
				<div>
                    <p><input type="text" name="user_<?php echo $role['rid'] ?>" id="user_<?php echo $role['rid'] ?>" class="autouser" style="width: 120px"></p>
					<a href="#" class="add_user" data-role="<?php echo $role['rid'] ?>">Aggiungi utente</a>
				</div>
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
