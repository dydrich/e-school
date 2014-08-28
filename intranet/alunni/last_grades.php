<div id="welcome" style="<?php if($free_day) echo "margin-top: 70px"; ?>">
	<p id="w_head">I tuoi ultimi voti</p>

</div>
<div style="position: relative; margin-left: 48px; top: -12px; width: 500px; background-color: rgba(30, 67, 137, .1); border-radius: 0 0 5px 5px; border: 1px solid rgba(30, 67, 137, .3);">
	<?php
	while($voto = $res_voti->fetch_assoc()){
		?>
		<p class="w_text <?php if($voto['voto'] < 6) echo "attention" ?>" style="padding-left: 10px">
			<?php echo $voto['voto'] ?>
			<span style="margin-left: 15px"><?php echo $voto['materia']." (".$voto['descrizione'] ?><?php if($voto['argomento']) echo "::",$voto['argomento'] ?>)</span>
		</p>
	<?php
	}
	?>
</div>
