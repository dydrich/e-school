<div id="welcome" style="<?php if($free_day) echo "margin-top: 70px"; ?>">
	<p id="w_head">I tuoi ultimi voti</p>

</div>
<div style="position: relative; margin-left: 48px; top: -12px; width: 500px; border-radius: 0 0 5px 5px; border: 1px solid rgba(30, 67, 137, .2);">
	<?php
	while($voto = $res_voti->fetch_assoc()){
		$grade_label = $voto['materia']." (".$voto['descrizione'];
		if(isset($voto['argomento'])) {
			$grade_label .= "::".$voto['argomento'].")";
		}
		?>
		<p class="w_text bottom_decoration <?php if($voto['voto'] < 6) echo "attention"; else echo "normal" ?>" style="width: 90%; margin: auto">
			<?php echo $voto['voto'] ?>
			<span style="margin-left: 15px"><?php echo truncateString($grade_label, 80) ?></span>
		</p>
	<?php
	}
	?>
</div>
