			<?php 
            $_classi = $dompath->query("classe", $classi);
            $num_cl = $_classi->length;
            ?>
        <div class="outline_line_wrapper" style="margin-top: 30px">
			<div style="width: 10%; float: left; position: relative; top: 30%"><span style="padding-left: 15px">Classe</span></div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Non validati</div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">A rischio</div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Giorni</div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Ore</div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Limite giorni</div>
			<div style="width: 15%; float: left; position: relative; top: 30%; text-align: center">Limite ore</div>
		</div>
		<table style="width: 95%; margin: 5px auto 0 auto">
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
	 	    <tr class="bottom_decoration">
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
