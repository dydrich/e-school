			<?php 
            $_classi = $dompath->query("classe", $classi);
            $num_cl = $_classi->length;
            ?>
    <div style="width: 95%; height: 30px; margin: 10px auto 0 auto; text-align: center; font-size: 1.1em; text-transform: uppercase">
		Riepilogo classi <?php echo $school ?>
	</div>
        <div style="width: 95%; margin: auto; height: 25px; text-align: center; text-transform: uppercase; font-weight: bold; border: 1px solid rgb(211, 222, 199); outline-style: double; outline-color: rgb(211, 222, 199); background-color: rgba(211, 222, 199, 0.7)">
			<div style="width: 10%; float: left; position: relative; top: 30%">Classe</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Non validati</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">A rischio</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Giorni</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Ore</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Limite giorni</div>
			<div style="width: 15%; float: left; position: relative; top: 30%">Limite ore</div>
		</div>
		<table style="width: 95%; margin: 20px auto 0 auto">
			<tbody>
	 	    <?php 
	 	    
 	    	$index = 0;
 	    	$bgcolor = "";
 	    	foreach ($_classi as $cl){
 	    		 	    		
 	    		$nm = $dompath->query("nome", $cl);
 	    		$nv = $dompath->query("non_validati", $cl);
				$ar = $dompath->query("a_rischio", $cl);
				$lesson_days = $dompath->query("giorni_lezione", $cl);
				$lesson_hours = $dompath->query("ore_lezione", $cl);
				//echo $lesson_hours->item(0)->nodeValue;
				$rb = new RBTime(0, 0, 0);
				$rb->setTime($lesson_hours->item(0)->nodeValue);
				$max_days = $dompath->query("giorni_limite", $cl);
				$max_hours = $dompath->query("ore_limite", $cl);
				$att = $cl->getAttribute("id");
				
 	    		if(($nv->item(0)->nodeValue) > 0){
 	    			$style = "font-weight: bold";
 	    			$css = "attention";
 	    		}
 	    		else if(($ar->item(0)->nodeValue) > 0){
 	    			$style = "";
 	    			$css = "attention";
 	    		}
 	    		else{
 	    			$style = "";
 	    			$css = "";
 	    		}
	 	    ?>
	 	    <tr style="border-bottom: 1px solid rgb(211, 222, 199)">
	 	    	<td class="<?php echo $css ?>" style="width: 10%; text-align: center; <?php print $style ?>"><a href="dettaglio_classe.php?id=<?php print $att ?>" class="<?php echo $css ?>" style="text-decoration: none; <?php print $style ?>"><?php print $nm->item(0)->nodeValue ?></a></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php print $nv->item(0)->nodeValue ?></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php print $ar->item(0)->nodeValue ?></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php print $lesson_days->item(0)->nodeValue ?></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php echo $rb->toString(RBTime::$RBTIME_SHORT) ?></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php print $max_days->item(0)->nodeValue ?></td>
	 	    	<td class="<?php echo $css ?>" style="width: 15%; text-align: center; <?php print $style ?>"><?php print substr($max_hours->item(0)->nodeValue, 0, -3) ?></td>
	 	    </tr>
	 	    <?php 
	 	    	$index++;
	 	    }
	 	    ?>
	 	    </tbody>
	 	    </table>