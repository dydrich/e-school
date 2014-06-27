<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Docenti</title>
<link rel="stylesheet" href="../teachers/reg.css" type="text/css" media="screen,projection" />
<script type="text/javascript" src="../../js/prototype.js"></script>
<script type="text/javascript" src="../../js/scriptaculous.js"></script>
<script type="text/javascript" src="../../js/controls.js"></script>
<script type="text/javascript" src="../../js/page.js"></script>
<script>

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
	<div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Elenco docenti di sostegno
	</div>
	<div style="width: 95%; margin: auto; height: 25px; text-align: left; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
		<div style="width: 30%; float: left; position: relative; top: 30%">Docente</div>
		<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">Classi</div>
		<div style="width: 50%; float: left; position: relative; top: 30%; text-align: center">Alunni</div>
	</div>
   		<table style="width: 95%; margin: 20px auto 0 auto">
	 	    <?php
	 	    if ($res_sos->num_rows < 1){
			?>
			<tr>
				<td colspan="5" style="height: 55px; font-weight: bold; text-align: center; font-size: 1.1em">Nessun docente trovato</td>
			</tr> 	
			<?php
			}
			else {
	 	    	foreach ($sos as $k => $docente){
					$classi = implode(", ", $docente['classi']);
					$sel_stud = "SELECT cognome, nome FROM rb_alunni, rb_assegnazione_sostegno WHERE anno = {$anno} AND id_alunno = alunno AND docente = {$k} ORDER BY cognome, nome";
					$res_stud = $db->executeQuery($sel_stud);
					$studenti = array();
					while ($row = $res_stud->fetch_assoc()){
						$studenti[] = $row['cognome']." ".$row['nome'];
					}
					$stds = implode(", ", $studenti);
	 	    ?>
 	    	<tr id="row<?php echo $k ?>" class="docs_row">
 	    		<td style="width: 30%; text-align: left"><a href="docente_sostegno.php?did=<?php echo $k ?>" style="text-decoration: none"><?php echo $docente['nome'] ?></a></td>
 	    		<td style="width: 20%; text-align: center"><?php echo $classi ?></td>
 	    		<td style="width: 50%; text-align: center"><?php echo $stds ?></td>	
 	    	</tr>
	 	    
	 	    <?php
	 	    	}
	 	    }
            ?>
            <tr>
	    		<td colspan="5" style="height: 25px"></td> 
		    </tr>
		</table>		
	</div>
<p class="spacer"></p>		
</div>
<?php include "footer.php" ?>	
</body>
</html>
