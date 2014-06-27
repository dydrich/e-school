<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Area dirigenza::gestione nuove classi</title>
<link rel="stylesheet" href="../styles.css" type="text/css" />
<script type="text/javascript" src="/js/page.js"></script>
</head>
<body>
<div class="pagewidth">
	<div class="header">
		<!-- TITLE -->
		<h1><a href="htp://www.scuolamediatre.it">Scuola Media Statale Iglesias</a></h1>
		<h2>Area riservata::dirigenza</h2>
		<!-- END TITLE -->
	</div>
	<?php include "navbar.php" ?>
	<div class="page-wrap">
		<div class="content">	
			<!-- CONTENT -->
            <h3>Gestione nuove classi prime</h3>
	 	    <p style="margin-top: 20px; font-weight: bold">
	 	    <?php if($n_cls < 1){ ?>
	 	     - Non hai ancora inserito nessuna classe. 
	 	    <?php 
	 	    }
	 	    else{
	 	    	print "Hai inserito $n_cls classi";
	 	    }
	 	    ?>
	 	    </p>
	 	    <p style="font-weight: bold">
	 	    <?php if($n_std < 1){ ?>
	 	     - Non hai ancora inserito nessun alunno. 
	 	    <?php 
	 	    } 
	 	    else{
	 	    	print "Sono presenti $n_std alunni<br />$not_assigned alunni non sono ancora stati assegnati ad una classe";
	 	    }
	 	    ?>
	 	    </p>	
			<br /><br />
			<!-- END CONTENT -->		
		</div>
		<div class="sidebar">	
			<?php include 'menu.php'; ?>
		</div>
		<div class="clear"></div>		
	</div>
    <?php include "../footer.php" ?>	
</div>
</body>
</html>
