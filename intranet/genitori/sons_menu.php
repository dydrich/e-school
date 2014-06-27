<?php 
if(count($_SESSION['__sons__']) > 1){
?>
<div class="smallbox">
<p style="font-size: 1.1em; font-weight: bold">Scegli l'alunno</p>
<?php 
	$indice = 1;
	reset($_SESSION['__sons__']);
	while(list($key, $val) = each($_SESSION['__sons__'])){
		$font = "1.0em";
		$decoration = "underline";
		if($key == $_SESSION['__current_son__']){
			$font = "0.8em";
			$decoration = "none";
		}
?>
<a style="font-size: <?php print $font ?>; text-decoration: <?php print $decoration ?>" href="<?php print $page ?>?son=<?php print $key ?>"><?php print $val[0] ?></a><br />
<?php 
		$indice++;
	}
?>
</div>
<?php 
}
?>