<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Workflow home page</title>
<link rel="stylesheet" href="../../css/main.css" type="text/css" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<style type="text/css">
table tbody tr:hover{
	background-color: #FAF6B7;
}
.admin_row{
	height: 25px;
}
</style>
</head>
<body>
    <div id="header">
		<div class="wrap" style="text-align: center">
			<?php include "../header.php" ?>
		</div>
	</div>
	<div class="wrap">
	<div id="main" style="background-color: #FFFFFF; padding-bottom: 30px; width: 100%">
        <table class="admin_table">
        	<thead>
            <tr class="admin_title_row">
                <td style="font-weight: bold" colspan="2" align="center">Gestione workflow</td>
            </tr>
            </thead>
			<tbody>
            <tr class="admin_row">
                <td style="width: 30%"><a href="uffici.php">Uffici</a></td>
                <td style="color: #003366;">
                    <a href="uffici.php">Gestisci gli uffici...</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td style="width: 30%"><a href="workflow.php">Worflow</a></td>
                <td style="color: #003366">
                    <a href="workflow.php">Gestisci i processi di richiesta...</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td style="width: 30%"><a href="step.php">Step</a></td>
                <td style="color: #003366;">
                    <a href="step.php">Gestisci gli step che compongono i flussi di lavoro...</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td style="width: 30%"><a href="status.php">Status</a></td>
                <td style="color: #003366">
                    <a href="status.php">Gestisci i vari status dei flussi di lavoro...</a>
                </td>
            </tr>
            <tr class="admin_row">
                <td style="width: 30%"><a href="richieste.php">Richieste</a></td>
                <td style="color: #003366;">
                    <a href="richieste.php">Visualizza e gestisci le richieste...</a>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr class="admin_row_menu">
                <td style="width: 30%"><a href="../index.php">Torna indietro</a></td>
                <td style="color: #003366">
                    <a href="../index.php">Senza commento...</a>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            </tfoot>
        </table>
   		</div>
        <?php include "../footer.php" ?>
    </div>				
</body>
</html>