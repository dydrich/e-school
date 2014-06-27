			<?php
			$sel_classes = "SELECT COUNT(id_classe) FROM rb_classi";
			$has_classes = $db->executeCount($sel_classes);
			
			$sel_cdc = "SELECT COUNT(id_docente) FROM rb_cdc WHERE id_docente IS NOT NULL";
			$has_cdc = $db->executeCount($sel_cdc);
			?>
			<script type="text/javascript">
			document.observe("dom:loaded", function(){
				$('sched_lnk').observe("click", function(event){
					event.preventDefault();
					crea_orario();
				});
				$('cdc_lnk').observe("click", function(event){
					event.preventDefault();
					crea_cdc();
				});				
			});
			</script>
			<ol>
				<li>
        		<p class="group_head">Gestione classi</p>
        		<p>1. Inserisci tutte le classi, indicando anno di corso, sezione e sede.<br />
        		<a href="adm_classes/classi.php" style="margin-right: 15px">Inserisci le classi</a>
        		<?php if($has_classes){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		<p>2. Prepara l'archivio per inserire i consigli di classe e l'orario delle lezioni (potrai in seguito modificare i dati usando le funzioni
        		di gestione orario e scrutini nell'area <strong>Gestione classi</strong> del menu amministrazione).<br />
        		<a href="../shared/no_js.php" id="sched_lnk" style="margin-right: 15px">Prepara orario</a>
        		<?php if($exist_sch){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		<br />
        		<a href="../shared/no_js.php" id="cdc_lnk" style="margin-right: 15px">Prepara consigli di classe</a>
        		<?php if($exist_cdc){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		<p>3. Torna all'elenco classi e gestisci i consigli di classe:<br />
        		<a href="adm_classes/classi.php" style="margin-right: 15px">Assegna i docenti alle classi</a>
        		<?php if($has_cdc){ ?><img src='../images/54.png' style='width: 15px; height: 15px; vertical-align: bottom' /><?php } ?>
        		</p>
        		<p>3. Ancora dall'elenco classi, inserisci l'orario delle lezioni (FACOLTATIVO):<br />
        		<a href="adm_classes/classi.php">Inserisci l'orario delle lezioni</a></p>
        		</li>
        	</ol>