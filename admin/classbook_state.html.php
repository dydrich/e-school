<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Gestione record registro di classe</title>
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,600italic,700,700italic,900,200' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="../css/general.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/reg.css" type="text/css" />
	<link rel="stylesheet" href="../css/site_themes/<?php echo getTheme() ?>/jquery-ui.min.css" type="text/css" media="screen,projection" />
	<script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script type="text/javascript" src="../js/page.js"></script>
	<script type="text/javascript">
		var selected_class = {"id": 0, "desc": ""};
		var need_class = false;
		var student = 0;
		var selected_day = 0;

		var show_div = function(e, off){
			if ($('#menu_cls').is(":visible")) {
				$('#menu_cls').slideUp(500);
				$('#list_div').hide();
				$('#tr'+selected_class.id).removeClass("accent_decoration");
				return;
			}
			off.left -= $('#menu_cls').width() - 30;
			$('#menu_cls').css({top: off.top+"px"});
			$('#menu_cls').css({left: off.left+"px"});
			$('#menu_cls').slideDown(500);
			$('#tr'+selected_class.id).addClass("accent_decoration");
		};

		var show_list = function(e, off){
			if ($('#list_div').is(":visible")) {
				$('#list_div').slideUp(500);
				$('#tr'+selected_class.id).removeClass("accent_decoration");
				return;
			}
			off.left -= $('#list_div').width() - 30;
			$('#list_div').css({top: off.top+"px"});
			$('#list_div').css({left: off.left+"px"});
			$('#list_div').slideDown(500);
			$('#tr'+selected_class.id).addClass("accent_decoration");
		};

		var show_menu = function(el) {
			if($('#menu_div').is(":hidden")) {
			    position = getElementPosition(el);
			    ftop = position['top'] + $('#'+el).height();
			    fleft = position['left'] + $('#'+el).width();
			    console.log("top: "+ftop+"\nleft: "+fleft);
			    $('#menu_div').css({top: ftop+"px", left: fleft+"px", position: "absolute", zIndex: 100});
			    $('#menu_div').show();
			}
			else {
				$('#menu_div').hide();
			}
		};

		var do_action = function(action){
			var url = "classbook_manager.php";
			//leftS = (screen.width - 200) / 2;
			//$('wait_label').setStyle({left: leftS+"px"});
			//$('wait_label').setStyle({top: "300px"});
			//$('wait_label').update("Operazione in corso");
			//$('over1').show();
			//$('wait_label').appear({duration: 0.8});
			background_process("Operazione in corso", 20, true);

			$.ajax({
				type: "POST",
				url: url,
				data: {action: action, cls: selected_class.id, std: student, day: selected_day, school_order: <?php echo $school_order ?>},
				dataType: 'json',
				error: function() {
					clearTimeout(bckg_timer);
					$('#background_msg').text("Errore di trasmissione dei dati");
					setTimeout(function() {
						$('#background_msg').dialog("close");
					}, 2000);
					console.log(json.dbg_message);
					//j_alert("error", "Errore di trasmissione dei dati");
				},
				succes: function() {

				},
				complete: function(data){
					clearTimeout(bckg_timer);
					r = data.responseText;
					if(r == "null"){
						return false;
					}
					var json = $.parseJSON(r);
					if (json.status == "kosql"){
						$('#background_msg').text(json.message);
						setTimeout(function() {
							$('#background_msg').dialog("close");
						}, 2000);
						console.log(json.dbg_message);
					}
					else {
						$('#background_msg').text("Operazione conclusa");
						setTimeout(function() {
							document.location.href = document.location.href;
						}, 1500);
					}
				}
			});
		};

		var get_day = function(action){
			if(action == "day_insert" || action == "day_class_insert"){
				_prompt = "Inserisci il giorno da aggiungere nel formato gg/mm/aaaa:";
			}
			else if (action == "day_delete" || action == "day_class_delete"){
				_prompt = "Inserisci il giorno da cancellare nel formato gg/mm/aaaa:";
			}
			else{
				_prompt = "Inserisci il giorno da reimpostare nel formato gg/mm/aaaa:";
			}

			selected_day = prompt(_prompt);
			if(!valida_data(selected_day)){
				j_alert("error", "Data non valida");
				return false;
			}
			do_action(action);
		};

		var get_student = function(action){
			var url = "get_students.php";

			$.ajax({
				type: "POST",
				url: url,
				data: {cls: selected_class.id, action: action},
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
						j_alert("error", json.message);
						console.log(json.dbg_message);
					}
					else if (json.status == "ko") {
						j_alert("error", json.message);
					}
					else {
						links = json.data;
						$('#list_div').html("");

						if(action == "student_delete") {
							sstr = "Elimina uno studente";
							_cl = "del_link";
						}
						else if(action == "student_insert"){
							sstr = "Inserisci uno studente";
							_cl = "ins_link";
						}
						else if(action == "student_reinsert"){
							sstr = "Reinserisci uno studente";
							_cl = "reins_link";
						}
						_p = document.createElement("p");
						_p.appendChild(document.createTextNode(sstr));
						$(_p).addClass("pop_label");

						$(_p).appendTo($('#list_div'));

						for(i in links){
							dt = links[i];
							$("<a href='../shared/no_js.php' id='std_"+dt.id+"' class='st_link' style='padding-left: 10px'>"+dt.name+"</a><br />").appendTo($('#list_div'));
						}

						$('#list_div').mouseleave(function(event){
							event.preventDefault();
							$('#list_div').hide();
						});
						$('.st_link').click(function(event){
							event.preventDefault();
							var strs = this.id.split("_");
							student = strs[1];
							do_action(action);
						});
					}
				}
			});
		};

		$(function(){
			load_jalert();
			setOverlayEvent();
			$('#imglink').click(function(event){
				event.preventDefault();
				show_menu('imglink');
			});
			$('#menu_div').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_div').hide();
				$('#list_div').hide();
				$('#tr'+selected_class.id).removeClass("accent_decoration");
		    });
			$('.img_click').mouseover(function(event){
				event.preventDefault();
				p = this.parentNode.parentNode;
				$(p).css({backgroundColor: "rgba(231, 231, 231, 0.8)", border: "1px solid #AAAAAA", borderRadius: "5px"});
			});
			$('.img_click').mouseout(function(event){
				event.preventDefault();
				p = this.parentNode.parentNode;
				$(p).css({backgroundColor: "", border: "0", borderRadius: "5px"});
			});
			$('.img_link').click(function(event){
				event.preventDefault();
				selected_class.id =$(this).attr("data-id");
				selected_class.desc = $(this).attr("data-desc");
				$('#menu_label').text("Classe "+selected_class.desc);
				var off = $(this).parent().parent().offset();
				off.top += $(this).parent().parent().height();
				show_div(event, off);
			});
			$('#menu_cls').mouseleave(function(event){
				event.preventDefault();
		        $('#menu_cls').hide();
				$('#list_div').hide();
				$('#tr'+selected_class.id).removeClass("accent_decoration");
		    });

			$('.do_link').click(function(event){
				event.preventDefault();
				do_action(this.id);
		    });
			$('.day_link').click(function(event){
				event.preventDefault();
				get_day(this.id);
		    });
			$('.student_link').click( function(event){
				event.preventDefault();
				get_student(this.id);
				var off = $(this).parent().offset();
				off.left -= 30;
				show_list(event, off);
		    });

		});
	</script>
	<style>
	.small{
		height: 20px
	}
	</style>
</head>
<body>
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "reg_menu.php" ?>
	</div>
	<div id="left_col">
		<div style="position: absolute; top: 75px; margin-left: 625px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="reinsert" class="do_link">
				<img src="../images/45.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<div style="position: absolute; top: 75px; margin-left: 555px; margin-bottom: -5px" class="rb_button">
			<a href="../shared/no_js.php" id="delete" class="do_link">
				<img src="../images/52.png" style="padding: 12px 0 0 12px" />
			</a>
		</div>
		<table style="width: 90%; margin: 0 auto 0 auto; border-collapse: collapse">
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <?php
            foreach ($cls as $k => $cl) {
            	$num_days = 0;
            	$check_students = true;
            	$check_days = true;
            	if($cl['count_reg_stud'] > 0){
            		$num_days = $cl['count_reg_rec'] / $cl['count_reg_stud'];
            	}
            	if ($cl['count_reg_stud'] != $cl['c_alunni']){
            		$check_students = false;
            	}
            	if (!is_int($num_days)){
            		$check_days = false;
            	}
            	$supposed_days = 0;
            ?>
            <tr class="bottom_decoration" style="height: 25px" id="tr<?php echo $k ?>">
	            <td style="width:  5%; font-weight: bold"><?php echo $cl['anno_corso'],$cl['sezione'] ?></td>
	            <td style="width: 45%; padding-left: 15px">Numero di alunni in registro: <span style="font-weight: bold<?php if(!$check_students) echo "; color: red" ?>"><?php echo $cl['count_reg_stud']." di {$cl['c_alunni']}" ?></span></td>
	            <td style="width: 45%; padding-left: 15px">Record totali: <span style='font-weight: bold<?php if(!$check_days) echo "; color: red" ?>'><?php echo "{$cl['count_reg_rec']} in {$num_days} giorni" ?></span></td>
	            <td style="width:  5%">
	            	<p style="width: 15px; height: 15px; text-align: center; margin: 0">
	            		<a href="../shared/no_js.php" class="img_link" id="imglink_<?php echo $k ?>" data-id="<?php echo $k ?>" data-desc="<?php echo $cl['anno_corso'],$cl['sezione'] ?>">
	            			<img src="../images/click.png" style="opacity: 0.5" class="img_click" />
	            		</a>
	            	</p>
	            </td>
	        </tr>
	        <?php
            }
	        ?>
	        <tr class="admin_void">
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr class="admin_void">
                <td colspan="4">&nbsp;</td>
            </tr>
        </table>
    </div>
    <div id="list_div" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border-radius: 4px 8px 8px 8px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888"></div>
    <div id="menu_cls" style="width: 200px; position: absolute; padding: 0px 0 10px 0px; border-radius: 4px 4px 4px 4px; display: none; background-color: #FFFFFF; box-shadow: 0 0 8px  #888">
    	<p id="menu_label" style="text-align: center; padding: 2px 0 2px 0; width: 100%; font-weight: bold; margin-bottom: 8px; "></p>
    	<a href="../shared/no_js.php" id="class_reinsert" class="do_link" style="padding-left: 10px;">Reinserisci la classe</a><br />
    	<a href="../shared/no_js.php" id="class_delete" class="do_link" style="padding-left: 10px;">Cancella la classe</a><br />
    	<a href="../shared/no_js.php" id="class_insert" class="do_link" style="padding-left: 10px;">Inserisci la classe</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="student_reinsert" class="student_link" style="padding-left: 10px;">Reinserisci uno studente</a><br />
    	<a href="../shared/no_js.php" id="student_delete" class="student_link" style="padding-left: 10px;">Elimina uno studente</a><br />
    	<a href="../shared/no_js.php" id="student_insert" class="student_link" style="padding-left: 10px;">Aggiungi uno studente</a><br />
    	<hr style="width: 95%; margin: auto; padding: 0 10px 0 10px; color: rgba(250, 250, 250, 0.2)" />
    	<a href="../shared/no_js.php" id="day_class_delete" class="day_link" style="padding-left: 10px;">Elimina un giorno</a><br />
    	<a href="../shared/no_js.php" id="day_class_insert" class="day_link" style="padding-left: 10px;">Aggiungi un giorno</a><br />
    </div>
    <div class="overlay" id="over1" style="display: none">
        <div id="wait_label" style="position: absolute; display: none; padding-top: 25px">Operazione in corso</div>
    </div>
    <input type="hidden" id="day" />

	</div>
<?php include "footer.php" ?>
<div id="drawer" class="drawer" style="display: none; position: absolute">
	<div style="width: 100%; height: 430px">
		<div class="drawer_link separator"><a href="../shared/no_js.php" id="delete_vacation" class="do_link" style="padding-left: 10px;"><img src="../images/56.png" style="margin-right: 10px; position: relative; top: 5%" />Elimina i giorni di vacanza</a></div>
		<div class="drawer_link"><a href="../shared/no_js.php" id="verify" class="do_link" style="padding-left: 10px;"><img src="../images/62.png" style="margin-right: 10px; position: relative; top: 5%" />Verifica i dati</a></div>
		<div class="drawer_link separator"><a href="../shared/no_js.php" id="correct" class="do_link" style="padding-left: 10px;"><img src="../images/39.png" style="margin-right: 10px; position: relative; top: 5%" />Verifica e correggi i dati</a></div>
		<div class="drawer_link"><a href="../shared/no_js.php" id="day_reinsert" class="day_link" style="padding-left: 10px;"><img src="../images/46.png" style="margin-right: 10px; position: relative; top: 5%" />Reinserisci un giorno</a></div>
		<div class="drawer_link"><a href="../shared/no_js.php" id="day_delete" class="day_link" style="padding-left: 10px;"><img src="../images/52.png" style="margin-right: 10px; position: relative; top: 5%" />Cancella un giorno</a></div>
		<div class="drawer_link separator"><a href="../shared/no_js.php" id="day_insert" class="day_link" style="padding-left: 10px;"><img src="../images/70.png" style="margin-right: 10px; position: relative; top: 5%" />Inserisci un giorno</a></div>
		<div class="drawer_link"><a href="../index.php"><img src="../images/6.png" style="margin-right: 10px; position: relative; top: 5%" />Home</a></div>
		<div class="drawer_link"><a href="index.php"><img src="../images/31.png" style="margin-right: 10px; position: relative; top: 5%" />Admin</a></div>
		<div class="drawer_link"><a href="http://www.istitutoiglesiasserraperdosa.it"><img src="../images/78.png" style="margin-right: 10px; position: relative; top: 5%" />Home Page Nivola</a></div>
	</div>
	<div class="drawer_lastlink"><a href="../shared/do_logout.php"><img src="../images/51.png" style="margin-right: 10px; position: relative; top: 5%" />Logout</a></div>
</div>
</body>
</html>
