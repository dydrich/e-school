			<?php
			$sel_teachers = "SELECT COUNT(rb_utenti.uid) FROM rb_utenti, rb_gruppi_utente WHERE rb_utenti.uid = rb_gruppi_utente.uid AND gid = 2";
			$has_teachers = $db->executeCount($sel_teachers);
			
			$sel_subj = "SELECT COUNT(id_docente) FROM rb_docenti WHERE materia IS NOT NULL";
			$has_subj = $db->executeCount($sel_subj);
			?>
			<ol>
				<li>
        		<p class="group_head">Gestione utenti</p>
        		<p>1. Inserisci nel sistema gli utenti della scuola.<br /> Per il funzionamento del software, &egrave; necessario l'inserimento dei docenti: potrai
        		provvedere agli altri utenti (dirigenza, segreteria) in un secondo momento. Ogni utente dovr&agrave; essere assegnato ai relativi gruppi (docente, segreteria, dirigenza, ecc.).<br />
        		Attenzione: NON assegnare al gruppo admin utenti della scuola. Se chi amministra il sito &egrave; anche un utente della scuola (docente o altro), 
        		creare per lui un secondo account e mantenere separato quello di amministratore. <br />Ricorda di prendere nota dei dati di account (username, password) assegnati, per comunicarli ai tuoi utenti.<br />
        		<a href="adm_users/users.php" style="margin-right: 15px">Inserisci gli utenti</a> (facoltativo, ma consigliato)
        		<?php if($has_teachers){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		<p>2. Indica le materie insegnate dai docenti:<br />
        		<a href="adm_users/teachers.php" style="margin-right: 15px">Gestisci i docenti</a>
        		<?php if($has_subj){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		</li>
        	</ol>