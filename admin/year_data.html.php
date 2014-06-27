<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<link rel="stylesheet" href="../css/reg.css" type="text/css" />
<link rel="stylesheet" href="../css/skins/aqua/theme.css" type="text/css" />
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/scriptaculous.js"></script>
<script type="text/javascript" src="../js/controls.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-it.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
<script type="text/javascript" src="../js/page.js"></script>
<script type="text/javascript">
var holidays = new Array();
var vacanze = new Array();
var hol = new Object();
hol['09'] = [<?php if(isset($hol['09'])){$x = 0; foreach($hol['09'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['09']) - 1)) print(","); $x++; } }?>];
hol['10'] = [<?php if(isset($hol['10'])){$x = 0; foreach($hol['10'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['10']) - 1)) print(","); $x++; } }?>];
hol['11'] = [<?php if(isset($hol['11'])){$x = 0; foreach($hol['11'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['11']) - 1)) print(","); $x++; } }?>];
hol['12'] = [<?php if(isset($hol['12'])){$x = 0; foreach($hol['12'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['12']) - 1)) print(","); $x++; } }?>];
hol['01'] = [<?php if(isset($hol['01'])){$x = 0; foreach($hol['01'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['01']) - 1)) print(","); $x++; } }?>];
hol['02'] = [<?php if(isset($hol['02'])){$x = 0; foreach($hol['02'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['02']) - 1)) print(","); $x++; } }?>];
hol['03'] = [<?php if(isset($hol['03'])){$x = 0; foreach($hol['03'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['03']) - 1)) print(","); $x++; } }?>];
hol['04'] = [<?php if(isset($hol['04'])){$x = 0; foreach($hol['04'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['04']) - 1)) print(","); $x++; } }?>];
hol['05'] = [<?php if(isset($hol['05'])){$x = 0; foreach($hol['05'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['05']) - 1)) print(","); $x++; } }?>];
hol['06'] = [<?php if(isset($hol['06'])){$x = 0; foreach($hol['06'] as $a){ list($y, $m, $d) = explode("-", $a); print("new Date($y, ".(intval($m) - 1).", ".intval($d).")"); if($x < (count($hol['06']) - 1)) print(","); $x++; } }?>];

var y = new Date();
var year = y.getFullYear();
var write_date = function(cal){
	mt = cal.date.print("%m");
	month_array = hol[mt];
	hol[mt].length = 0;
	for (var i in cal.multiple) {
        var d = cal.multiple[i];
        if (d) {
           	month_array[month_array.length] = d;
        }
    }
    holidays.length = 0;
    vacanze.length = 0;
    for(a in hol){
        //alert(a);
		a1 = hol[a];
		for(var z = 0; z < a1.length; z++){
			//alert(a1[z]);
			if(!in_array(holidays, a1[z].print("%A %d %B"))){
				holidays.push(a1[z].print("%A %d %B"));
			}
			if(!in_array(vacanze, a1[z].print("%Y-%m-%d"))){
				vacanze.push(a1[z].print("%Y-%m-%d"));
			}
		}
    }
    $('holydays').innerHTML = holidays.join(", ");
    $('vacanze').value = vacanze.join(",");
	cal.hide();
	return true;	
};

var go = function(){
	//alert($('vacanze').value);
	var url = "school_year_manager.php?action=save_data";
	
    req = new Ajax.Request(url,
	  {
	    	method:'post',
	    	parameters: $('myform').serialize(true),
	    	onSuccess: function(transport){
	    		var response = transport.responseText || "no response text";
	    		dati = response.split("#");
	    		if(dati[0] == "kosql"){
		    		sqlalert();
	    			console.log("Errore SQL. \nQuery: "+dati[1]+"\nErrore: "+dati[2]);
	    			return;
	    		}
	    		else{
					alert("Anno scolastico modificato con successo");
					document.location.href = "index.php";
	    		}
	    	},
	    	onFailure: function(){ alert("Si e' verificato un errore..."); }
	  });
};

var load_default = function(load_on_update){
	<?php if($year): ?>
	if(load_on_update){
		$('data_inizio').update('<?php echo format_date($year->getYear()->get_data_apertura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>');
		$('data_fine').update('<?php echo format_date($year->getYear()->get_data_chiusura(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>');
		$('data_inizio_lezioni').value = '<?php echo format_date($year->getClassesStartDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		$('data_fine_lezioni').value = '<?php echo format_date($year->getClassesEndDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		$('session1').value = '<?php echo format_date($year->getFirstSessionEndDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		$('session2').value = '<?php echo format_date($year->getSecondSessionEndDate(), SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?>';
		for(a in hol){
			//alert(a);
			a1 = hol[a];
			for(var z = 0; z < a1.length; z++){
				//alert(a1[z]);
				if(!in_array(holidays, a1[z].print("%A %d %B"))){
					holidays.push(a1[z].print("%A %d %B"));
				}
				if(!in_array(vacanze, a1[z].print("%Y-%m-%d"))){
					vacanze.push(a1[z].print("%Y-%m-%d"));
				}
			}
	    }
	    $('holydays').innerHTML = holidays.join(", ");
	    $('vacanze').value = vacanze.join(",");
	}
	<?php endif; ?>
};

document.observe("dom:loaded", function(){
	$('sessions').observe("change", function(event){
		if($F('sessions') == 2){
			$('s2_td1').hide();
			$('s2_td2').hide();
		}
		else{
			$('s2_td1').show();
			$('s2_td2').show();
		}
	});
});

</script>
<title>Nuovo anno</title>
<style>
input {
	font-size: 11px
}
</style>
</head>
<body onload="load_default(<?php if($_SESSION['__school_year__']) echo "true"; else echo "false" ?>); ">
<?php include "header.php" ?>
<?php include "navigation.php" ?>
<div id="main">
<div id="right_col">
	<?php include "adm_school/menu.php" ?>
</div>
<div id="left_col">
<div class="group_head">Anno scolastico <?php if($year) echo $year->getYear()->get_descrizione(); else echo $_SESSION['__current_year__']->get_descrizione(); echo " ".$label ?></div>
<form method="post" id="myform" class="popup_form" style="width: 90%">
		<table style="width: 90%; margin-right: auto; margin-left: auto; margin-bottom: 20px;">
            <tr>
            	<td colspan="4">&nbsp;</td>
            </tr>
           	<tr>
                <td style="width: 25%; font-weight: normal" class="">Data inizio anno scolastico</td>
                <td style="width: 25%"><span id="data_inizio" style="font-weight: bold"></span></td>
                <td style="width: 25%; padding-left: 10px; font-weight: normal" class="">Data fine anno scolastico</td>
                <td style="width: 25%"><span id="data_fine" style="font-weight: bold"></span></td>
            </tr>
            <tr>
            	<td colspan="4" style="height: 15px"></td>
            </tr>
            <tr>
                <td style="width: 25%; font-weight: normal" class="">Data inizio lezioni</td>
                <td style="width: 25%; color: #003366"><input type="text" name="data_inizio_lezioni" id="data_inizio_lezioni" style="width: 70%" readonly="readonly" /></td>
                <td style="width: 25%; padding-left: 10px; font-weight: normal" class="">Data fine lezioni</td>
                <td style="width: 25%; color: #003366"><input type="text" name="data_fine_lezioni" id="data_fine_lezioni" style="width: 70%" readonly="readonly" /></td>
            </tr>
            <tr>
            	<td colspan="4" style="height: 15px"></td>
            </tr>
            <tr>
                <td style="width: 25%; font-weight: normal" class="">Divisione anno scolastico</td>
                <td style="width: 25%; color: #003366">
                	<select name="sessions" id="sessions" style="width: 70%">
                		<option value="0" <?php if($year && $year->getSessions() == 0) echo "selected" ?>>.</option>
                		<option value="2" <?php if($year && $year->getSessions() == 2) echo "selected" ?>>Quadrimestri</option>
                		<option value="3" <?php if($year && $year->getSessions() == 3) echo "selected" ?>>Trimestri</option>
                	</select>
                </td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="width: 25%; font-weight: normal" class="">Data fine 1 sessione</td>
                <td style="width: 25%; color: #003366"><input type="text" name="session1" id="session1" style="width: 70%" readonly="readonly" /></td>
                <td style="width: 25%; padding-left: 10px; font-weight: normal<?php if($year && $year->getSessions() == 2) echo "; display: none" ?>" class="" id="s2_td1">Data fine 2 sessione</td>
                <td style="width: 25%; color: #003366<?php if($year && $year->getSessions() == 2) echo "; display: none" ?>" id="s2_td2"><input type="text" name="session2" id="session2" style="width: 70%" readonly="readonly" /></td>
            </tr>
            <tr>
            	<td colspan="4">
            	<script type="text/javascript">
				Calendar.setup({
					date		: new Date(year, 8),
					inputField	: "data_inizio_lezioni",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24",
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 5),
					inputField	: "data_fine_lezioni",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24",
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 0),
					inputField	: "session1",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24"
				});
				Calendar.setup({
					date		: new Date(year+1, 0),
					inputField	: "session2",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					firstDay	: 1,
					timeFormat	: "24"
				});
				</script>
            	</td>
            </tr>
            <tr>
            	<td colspan="4" style="padding-top: 15px; text-decoration: underline">Festivit&agrave; e giorni di sospensione delle attivit&agrave;</td>
            </tr>
            <tr>
                <td style="width: 25%; font-weight: normal; padding-bottom: 20px" class="" colspan="4">
                	<span id="settembre" style="">Settembre</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="ottobre" style="">Ottobre</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="novembre" style="">Novembre</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="dicembre" style="">Dicembre</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="gennaio" style="">Gennaio</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="febbraio" style="">Febbraio</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="marzo" style="">Marzo</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="aprile" style="">Aprile</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="maggio" style="">Maggio</span>&nbsp;&nbsp;|&nbsp;&nbsp;
                	<span id="giugno" style="">Giugno</span>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #eeeeee; margin-top: 20px" colspan="4" id="holydays"></td>
			</tr>
			<tr>
				<td colspan="4">
				<input type="hidden" id="hid_date" />
				<input type="hidden" id="id_anno" name="id_anno" value="<?php if($year && $action == "update") print $year->get_ID(); ?>" />
				<input type="hidden" id="vacanze" name="vacanze" />
				<script type="text/javascript">
				var anno = new Date(2012, 8);
				//alert(anno);
				Calendar.setup({
					date		: new Date(year, 8),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "settembre",
					firstDay	: 1,
					multiple	: hol['09'],
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					},
					onClose		: write_date
				});
				Calendar.setup({
					
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "ottobre",
					firstDay	: 1,
					multiple	: hol['10'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					},
					date		: new Date(year, 9, 1)
				});
				Calendar.setup({
					date		: new Date(year, 10),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "novembre",
					firstDay	: 1,
					multiple	: hol['11'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year, 11),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "dicembre",
					firstDay	: 1,
					multiple	: hol['12'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 0),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "gennaio",
					firstDay	: 1,
					multiple	: hol['01'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 1),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "febbraio",
					firstDay	: 1,
					multiple	: hol['02'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 2),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "marzo",
					firstDay	: 1,
					multiple	: hol['03'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 3),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "aprile",
					firstDay	: 1,
					multiple	: hol['04'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 4),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "maggio",
					firstDay	: 1,
					multiple	: hol['05'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});
				Calendar.setup({
					date		: new Date(year+1, 5),
					inputField	: "hid_date",
					ifFormat	: "%d/%m/%Y",
					showsTime	: false,
					button		: "giugno",
					firstDay	: 1,
					multiple	: hol['06'],
					onClose		: write_date,
					dateStatusFunc :    function (date) {
                        return (date.getDay() == 0) ? true : false;
					}
				});

				
				</script>
				</td>
			</tr>
			<tr>
                <td style="padding-top: 20px; text-align: right" colspan="4">
                	<a href="#" onclick="go()" class="standard_link nav_link_first">Registra</a>|
                	<a href="index.php" class="standard_link nav_link_last">Torna al menu</a>
                	<input type="hidden" name="school_order" id="school_order" value="<?php echo $_GET['school_order'] ?>" />
                </td>
			</tr>
		</table>
		</form>
<p class="spacer"></p>
</div>
</div>
<?php include "footer.php" ?>
</body>
</html>