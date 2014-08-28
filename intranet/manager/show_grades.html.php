<div style="text-align: center; font-weight: bold; padding-bottom: 25px">Elenco valutazioni - docente <?php echo $teacher_name ?>
	<p>Alunno: <?php echo $alunno['cognome']." ".$alunno['nome'] ?>, classe: <?php echo $alunno['anno_corso'].$alunno['sezione'] ?></p>
	<p>Materia: <?php echo $materia ?>
</div>
<div style="width: 90%; text-align: left; margin: auto; font-size: 12px">
<?php
setlocale(LC_TIME, "it_IT.UTF-8");
while($voto = $res_voti->fetch_assoc()){
	$giorno_str = strftime("%A", strtotime($voto['data_voto']));
?>
	<div style="height: 20px; padding-bottom: 0px; vertical-align: middle; border-bottom: 1px solid #CCCCCC; padding-left: 20px"><?php echo $giorno_str." ". format_date($voto['data_voto'], SQL_DATE_STYLE, IT_DATE_STYLE, "/") ?> - <span class="_bold <?php if ($voto['voto'] < 6) echo "attention" ?>"><?php echo $voto['voto'] ?></span>::<?php echo $voto['descrizione'] ?></div>
<?php
}
?>
</div>
