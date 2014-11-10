			<?php 
            $alunni = $dompath->query("alunno", $non_validati);
            $num_nv = $alunni->length;
            $alunni_ar = $dompath->query("alunno", $a_rischio);
            $num_ar = $alunni_ar->length;
            //print $alunni_ar->length;
            ?>
        <div class="accent_color" style="width: 95%; margin: 10px auto 0 auto; text-align: left; font-size: 1.2em; font-weight: normal; position: relative; top: -5px">
			Alunni non validati
		</div>
        <div class="outline_line_wrapper" style="margin-top: 20px">
	        <div style="width: 40%; float: left; position: relative; top: 30%"><span style="padding-left: 15px">Alunno</span></div>
			<div style="width: 10%; float: left; position: relative; top: 30%; text-align: center">Classe</div>
			<div style="width: 10%; float: left; position: relative; top: 30%; text-align: center">Assenze</div>
			<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">Ore assenza</div>
			<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">% assenze orarie</div>
		</div>
		<table style="width: 95%; margin: 0 auto 0 auto">
			<tbody>
	 	    <?php 
	 	    if($num_nv == 0){
	 	    ?>
	 	    <tr class="manager_row_small">
	 	    	<td colspan="5" style="height: 50px; vertical-align: middle; font-weight: bold; text-align: center">Nessun alunno con oltre il 25% di assenze</td>
	 	    </tr>
	 	    <?php 
	 	    }
	 	    else{
	 	    	$index = 1;
	 	    	$bgcolor = "";
	 	    	foreach($alunni as $a){
	 	    		$att = $a->getAttribute("id");
	 	    		$nm = $dompath->query("nome", $a);
	 	    		$cls = $dompath->query("classe_app", $a);
					$abs = $dompath->query("assenze", $a);
					$h_abs = $dompath->query("ore_assenza", $a);
					$per = $dompath->query("perc_ore", $a);
	 	    ?>
	 	    <tr class="bottom_decoration">
	 	    	<td style="width: 40%; "><a href="dettaglio_alunno.php?id=<?php print $att ?>" style="text-decoration: none"><?php print $nm->item(0)->nodeValue ?></a></td>
	 	    	<td style="width: 10%; text-align: center"><?php print $cls->item(0)->nodeValue ?></td>
	 	    	<td style="width: 10%; text-align: right; padding-right: 25px"><?php print $abs->item(0)->nodeValue ?></td>
	 	    	<td style="width: 20%; text-align: right; padding-right: 25px"><?php print $h_abs->item(0)->nodeValue ?></td>
	 	    	<td style="width: 20%; text-align: right; padding-right: 35px"><?php print $per->item(0)->nodeValue ?>%</td>
	 	    </tr>
	 	    <?php 
	 	    		$index++;
	 	    	}
	 	    }
	 	    ?>
	 	    </tbody>
	 	    <tfoot>
	 	    <tr>
	 	    	<td colspan="5" style="padding-bottom: 30px">&nbsp;</td>
	 	    </tr>
	 	    </tfoot>
	 	</table>
	 	<div class="accent_color" style="width: 95%; height: 30px; margin: 0 auto 0 auto; text-align: left; font-size: 1.2em; font-weight: normal; position: relative; top: -5px">
			Alunni a rischio
		</div>
        <div class="outline_line_wrapper" style="margin-top: 15px">
			<div style="width: 40%; float: left; position: relative; top: 30%"><span style="padding-left: 15px">Alunno</span></div>
			<div style="width: 10%; float: left; position: relative; top: 30%; text-align: center">Classe</div>
			<div style="width: 10%; float: left; position: relative; top: 30%; text-align: center">Assenze</div>
			<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">Ore assenza</div>
			<div style="width: 20%; float: left; position: relative; top: 30%; text-align: center">% assenze orarie</div>
		</div>
		<table style="width: 95%; margin: 0 auto 0 auto">
			<tbody>
	 	    <?php 
	 	    if($num_ar == 0){
	 	    ?>
	 	    <tr class="manager_row_small">
	 	    	<td colspan="5" style="height: 50px; vertical-align: middle; font-weight: bold; text-align: center">Nessun alunno con oltre il 20% di assenze</td>
	 	    </tr>
	 	    <?php 
	 	    }
	 	    else{
	 	    	$index = 0;
	 	    	$bgcolor = "";
	 	    	foreach($alunni_ar as $a){
	 	    		if($index%2)
	 	    			$bgcolor = "background-color: #D5C5AC";
	 	    		else 
	 	    			$bgcolor = "";
	 	    		$att = $a->getAttribute("id");
	 	    		$nm = $dompath->query("nome", $a);
	 	    		$cls = $dompath->query("classe_app", $a);
					$abs = $dompath->query("assenze", $a);
					$h_abs = $dompath->query("ore_assenza", $a);
					$per = $dompath->query("perc_ore", $a);
	 	    ?>
	 	    <tr class="bottom_decoration">
	 	    	<td style="width: 40%"><a href="dettaglio_alunno.php?id=<?php print $att ?>" style="text-decoration: none"><?php print $nm->item(0)->nodeValue ?></a></td>
	 	    	<td style="width: 10%; text-align: right; padding-right: 25px"><?php print $cls->item(0)->nodeValue ?></td>
	 	    	<td style="width: 10%; text-align: right; padding-right: 25px"><?php print $abs->item(0)->nodeValue ?></td>
	 	    	<td style="width: 20%; text-align: right; padding-right: 25px"><?php print $h_abs->item(0)->nodeValue ?></td>
	 	    	<td style="width: 20%; text-align: right; padding-right: 35px"><?php print $per->item(0)->nodeValue ?>%</td>
	 	    </tr>
	 	    <?php 
	 	    		$index++;
	 	    	}
	 	    }
	 	    ?>
	 	    </table>
