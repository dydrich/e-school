<?php

require_once "../start.php";
require_once "../ClassbookManager.php";

check_session();
check_permission(ADM_PERM);

ini_set("display_errors", "1");

$school_order = 1;
$school_year = $_SESSION['__school_year__'][$school_order];
$school_year->setSchoolOrder($school_order);
$clmanager = new ClassbookManager($db, $school_year);

if (isset($_REQUEST['action'])) {
	switch ($_REQUEST['action']) {
		case "delete":
			$clmanager->init();
			$clmanager->delete();
			break;
		case "insert":
			$clmanager->init();
			$clmanager->insert();
			break;
		case "reinsert":
			$clmanager->init();
			$clmanager->reinsert();
			break;
		case "delete_class":
			$clmanager->init();
			$clmanager->deleteClass(43);
			break;
		case "insert_class":
			$clmanager->init();
			$clmanager->insertClass(43);
			break;
		case "reinsert_class":
			$clmanager->init();
			$clmanager->reinsertClass(43);
			break;
		case "delete_student":
			$clmanager->init();
			$clmanager->deleteStudent(4217);
			break;
		case "insert_student":
			$clmanager->init();
			$clmanager->insertStudent(4217);
			break;
		case "reinsert_student":
			$clmanager->init();
			$clmanager->reinsertStudent(4217);
			break;
		case "delete_day":
			$clmanager->init();
			$clmanager->deleteDay('2012-03-27');
			break;
		case "insert_day":
			$clmanager->init();
			$clmanager->insertDay('2012-03-27');
			break;
		case "reinsert_day":
			$clmanager->init();
			$clmanager->reinsertDay('2012-03-27');
			break;
		case "check":
			$clmanager->init();
			$print = $clmanager->checkIntegrity(false);
			break;
		case "correct":
			$clmanager->init();
			$print = $clmanager->checkIntegrity(true);
			break;
		case "delete_holydays":
			$clmanager->init();
			$print = $clmanager->deleteHolydays();
			break;
	}
}

$rec_classi = $db->executeCount("SELECT COUNT(*) FROM rb_reg_classi");
$classi = $db->executeCount("SELECT COUNT(DISTINCT id_classe) FROM rb_reg_classi");
$rec_alunni = $db->executeCount("SELECT COUNT(*) FROM rb_reg_alunni");
$classi_al = $db->executeCount("SELECT COUNT(DISTINCT id_classe) FROM rb_reg_alunni");
$count_days = $db->executeCount("SELECT COUNT(DISTINCT data) FROM rb_reg_classi");

$navigation_label = "Area amministrazione: sviluppo";
$admin_level = 0;

/*
 * for admin menu
 */
$_SESSION['__path_to_root__'] = "../../";
// admin area
$_SESSION['__path_to_mod_home__'] = "../../admin/";
$_SESSION['__area_label__'] = "Area amministrazione";

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Admin home page</title>
	<link rel="stylesheet" href="../../css/site_themes/blue_red/reg.css" type="text/css" />
	<link rel="stylesheet" href="../../css/general.css" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/window.js"></script>
<script type="text/javascript" src="../../js/window_effects.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script type="text/javascript">
</script>
</head>
<body>
<?php include "../../admin/header.php" ?>
<?php include "../../admin/navigation.php" ?>
<div id="main">
	<div id="right_col">
		<?php include "../../admin/dev_menu.php" ?>
	</div>
	<div id="left_col">
		<div class="group_head">Prova Classe ClassbookManager</div>
        <table style="width: 90%; margin: 20px auto 0 auto; border-collapse: collapse" class="admin_table">
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione classi</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=insert_class" id="head_lnk">Inserisci classe</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=insert_class" id="head_lnk">Inserisci classe</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=delete_class" id="head_lnk">Cancella classe</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=delete_class" id="head_lnk">Cancella classe</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=reinsert_class" id="head_lnk">Reinserisci classe</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=reinsert_class" id="head_lnk">Reinserisci classe</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione studenti</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=insert_student" id="head_lnk">Inserisci studente</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=insert_student" id="head_lnk">Inserisci studente</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=delete_student" id="head_lnk">Cancella studente</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=delete_student" id="head_lnk">Cancella studente</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=reinsert_student" id="head_lnk">Reinserisci studente</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=reinsert_student" id="head_lnk">Reinserisci studente</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione giorni</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=insert_day" id="head_lnk">Inserisci giorno</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=insert_day" id="head_lnk">Inserisci giorno</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=delete_day" id="head_lnk">Cancella giorno</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=delete_day" id="head_lnk">Cancella giorno</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=reinsert_day" id="head_lnk">Reinserisci giorno</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=reinsert_day" id="head_lnk">Reinserisci giorno</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione registro generale</td>
            </tr>
            <tr class="index_link">
                <td style=" width: 30%"><a href="classbook_manager_test.php?action=delete">Cancella tutto</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=delete">Cancella tutto</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style="width: 30%"><a href="classbook_manager_test.php?action=insert">Inserisci tutto</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=insert">Inserisci tutto</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style="width: 30%"><a href="classbook_manager_test.php?action=reinsert">Reinserisci tutto</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=reinsert">Reinserisci tutto</a>
                </td>
            </tr>
            <tr class="index_link" >
                <td style="width: 30%"><a href="classbook_manager_test.php?action=delete_holydays">Cancella vacanze</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=delete_holydays">Cancella vacanze</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: rgba(211, 222, 199, 0.7)" class="_bold _center">Gestione integrit&agrave; dati</td>
            </tr>
            <tr class="index_link">
                <td style="width: 30%"><a href="classbook_manager_test.php?action=check">Verifica i dati</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=check">Verifica i dati</a>
                </td>
            </tr>
            <tr class="index_link">
                <td style="width: 30%"><a href="classbook_manager_test.php?action=correct">Correggi i dati</a></td>
                <td style="color: #003366">
                    <a href="classbook_manager_test.php?action=correct">Correggi i dati</a>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2" style="height: 50px; border: 1px solid #BBBBBB">
                	<div style="text-align: center; width: 100%; margin-bottom: 20px">
                		<p>reg_classi:: <?php echo $rec_classi ?> records in <?php echo $classi ?> classi (<?php echo $count_days ?> giorni)<br />
                		reg_alunni:: <?php echo $rec_alunni ?> records in <?php echo $classi_al ?> classi</p>
                	</div>
                	<?php if (count($clmanager->getIntegrityErrors())) print_r($clmanager->getIntegrityErrors()) ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;&nbsp;&nbsp;</td>
            </tr>
        </table>
    </div>
	<p class="spacer"></p>
	</div>
	<?php include "../../admin/footer.php" ?>
</body>
</html>
