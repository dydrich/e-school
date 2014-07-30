
<div style="text-align: center; font-weight: bold; padding-bottom: 25px">Elenco note didattiche
	<p>Alunno: <?php echo $alunno['cognome']." ".$alunno['nome'] ?></p>
	<p>Classe: <?php echo $alunno['anno_corso'].$alunno['sezione'] ?></p>
	<p>Docente: <?php echo $teacher_name ?>
</div>
<div style="width: 90%; text-align: left; margin: auto;">
<?php
setlocale(LC_TIME, "it_IT.utf8");
if ($res_note->num_rows > 0) {
	while($note = $res_note->fetch_assoc()){
		$giorno_str = strftime("%A", strtotime($note['data']));
?>
	<div style="height: 20px; padding-bottom: 0px; vertical-align: middle; border-bottom: 1px solid #CCCCCC; padding-left: 20px"><?php echo $giorno_str." ". format_date($note['data'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?> - <?php echo $note['mat'] ?>::<?php echo $note['tipo_nota']; if ($note['note'] != "") echo "({$note['note']})" ?></div>
<?php
	}
}
else {
?>
	<div style="height: 20px; padding-bottom: 0px; vertical-align: middle; border-bottom: 1px solid #CCCCCC; padding-left: 20px">Nessuna nota inserita</div>
<?php
}
?>
</div>
