			<?php
			$sel_venues = "SELECT COUNT(*) FROM rb_sedi";
			$has_venues = $db->executeCount($sel_venues);
			
			$has_holydays = false;
			$sel_holydays = "SELECT vacanze FROM rb_anni WHERE id_anno = {$anno}";
			$res_holydays = $db->executeQuery($sel_holydays);
			$r = $res_holydays->fetch_assoc();
			if($r['vacanze'] != ""){
				$has_holydays = true;
			}
			?>
			<ol>
        		<li>
        		<p class="group_head">Parte prima: operazioni preliminari</p>
        		<p>1. Come prima cosa, verifica che le informazioni riguardanti la scuola, inserite durante l'installazione, siano corrette, e se necessario modificale.<br />
        		Per modificare tutte queste informazioni, hai a disposizione una pagina apposita, raggiungibile dal link "Informazioni di base" nel menu di amministrazione:<br />
        		<a href="">Modifica i dati essenziali della scuola</a> (facoltativo)</p>
        		<p>2. Ora, prima di procedere, &egrave; necessario inserire almeno una sede per la scuola:<br />
        		<a href="adm_school/sedi.php" style="margin-right: 15px">Gestisci le sedi della scuola</a>
        		<?php if($has_venues){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		<p>3. Verifica che le materie inserite si adattino alla tua scuola: il software viene installato con una serie di materie predefinite, che dovrebbero coprire le esigenze
        		della maggior parte delle scuole medie d'Italia. Nel caso avessi bisogno di apportare modifiche:<br />
        		<a href="adm_school/materie.php">Gestisci le materie d'insegnamento</a> (facoltativo)</p>
        		<p>4. Inserisci i giorni di vacanza e di interruzione delle lezioni nell'anno corrente, e conferma le date del calendario scolastico:<br />
        		<a href="year.php?do=basic_update" style="margin-right: 15px">Gestisci il calendario scolastico</a>
        		<?php if($has_holydays){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		</li>
        	</ol>