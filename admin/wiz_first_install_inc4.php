			<?php
			$sel_students = "SELECT COUNT(id_alunno) FROM rb_alunni WHERE attivo = '1'";
			$has_students = $db->executeCount($sel_students);
			
			
			?>
			<script type="text/javascript">
			document.observe("dom:loaded", function(){
				$('reg_lnk').observe("click", function(event){
					event.preventDefault();
					crea_registro();
				});
				$('sc_lnk').observe("click", function(event){
					event.preventDefault();
					pop_scrutini();
				});				
			});
			</script>
			<ol>
				<li>
        		<p class="group_head">Gestione alunni</p>
        		<p>1. Inserisci tutti gli alunni della scuola, assegnandoli alle classi.
        		<?php if($has_students){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		<br />
        		Questa &egrave; la parte pi&ugrave; lunga della procedura. Attualmente sono disponibili 3 diversi modi per farlo:</p>
        		<ol style="list-style-type: lower-alpha">
        			<li style="margin-left: 30px"><strong>usare la funzione di gestione Alunni e inserirli uno per uno</strong>: questo &egrave; il metodo meno rapido, 
        			ma &egrave; l'unico che ti permette di inserire tutti i dati, controllandoli. I dati che puoi inserire sono nome, cognome, data di nascita, codice 
        			fiscale, sesso e classe, oltre ai dati di account (username e password). Se invece non ti interessano tutti questi dati, puoi usare uno degli altri metodi.<br />
        			<a href="adm_students/alunni.php">Vai alla gestione alunni</a>
        			</li>
        			<li style="margin-left: 30px"><strong>usare la funzione di inserimento rapido</strong>: in questo modo si possono indicare solo nome, cognome, sesso e classe - il sistema si occuper&agrave;
        			di creare gli account per tutti gli alunni.<br />
        			<a href="adm_students/insert_students.php">Inserisci gli alunni velocemente</a>
        			<a href="adm_classes/alunni_liberi.php">Assegna gli alunni alle classi</a>
        			</li>
        			<li style="margin-left: 30px"><strong>creare un file csv e usare la procedura di importazione automatica</strong>: in questo modo potrai indicare tutti
        			i dati (compresi codice fiscale e data di nascita), ma non la classe - dovrai usare la funzione <strong>Alunni senza classe</strong>
        			del menu amministrazione, per farlo (&egrave; comunque una procedura molto veloce).<br />
        			<a href="adm_students/load_students.php">Importa gli alunni nel sistema</a><br />
        			<a href="adm_classes/alunni_liberi.php">Assegna gli alunni alle classi</a>
        			</li>
        		</ol>
        		</li>
        		<li>
        		<p class="group_head" style="margin-top: 20px">Gestione registri</p>
        		<p>Questa &egrave; l'ultima parte della procedura: creare la struttura dati che verr&agrave; usata nei registri, di classe e del docente.
        		ATTENZIONE: dovresti attivare queste funzioni dopo avere completato tutti i passaggi precedenti, e solo se sicuro che non ci 
        		saranno modifiche. Puoi ovviamente modificare i registri anche in seguito (aggiungendo o togliendo alunni, classi, giorni di lezione),
        		ma alcune modifiche potrebbero <strong>cancellare i dati inseriti dai docenti</strong>. Cerca quindi di usare le funzioni di gestione 
        		con attenzione.
        		<br />
        		<a href="../shared/no_js.php" id="reg_lnk" style="margin-right: 15px">Crea registri di classe</a>
        		<?php if($exist_reg){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		<br />
        		<a href="../shared/no_js.php" id="sc_lnk" style="margin-right: 15px">Crea registri dei docenti</a>
        		<?php if($count_data2){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		</li>
        	</ol>