<div id="welcome" style="<?php if($free_day) echo "margin-top: 70px"; ?>">
	<p id="w_head">I tuoi ultimi voti</p>
	<div style="position: relative; top: -12px; width: 379px; background-color: rgba(211, 222, 199, 0.2); border-radius: 0 0 10px 10px; border: 1px solid rgb(211, 222, 199)">
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
</div>