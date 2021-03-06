<footer id="footer">
	<span>Copyright <?php echo date("Y") ?> Riccardo Bachis | <a href="<?php print $_SESSION['__config__']['root_site'] ?>"><?php print $_SESSION['__config__']['intestazione_scuola'] ?></a></span>
</footer>
<div id="alert" class="alert_msg" style="display: none">
	<div class="alert_title">
		<i class="fa fa-thumbs-up"></i>
		<span>Successo</span>
	</div>
	<p id="alertmessage" class="alertmessage"></p>
</div>
<div id="error" class="error_msg" style="display: none">
	<div class="error_title">
		<i class="fa fa-warning"></i>
		<span>Errore</span>
	</div>
	<p class="errormessage" id="errormessage"></p>
</div>
<div id='background' class="alert_msg" style='display: none'>
	<div class="alert_title">
		<i class="fa fa-spin fa-circle-o-notch"></i>
		<span>Attendi...</span>
	</div>
	<p id="background_msg" class="alertmessage"></p>
</div>
<div class="overlay" id="overlay" style="display:none;"></div>
<div id="confirm" class="confirm_msg" style="display: none">
	<div class="confirm_title">
		<i class="fa fa-question-circle"></i>
		<span>Conferma</span>
	</div>
	<p class="confirmmessage" id="confirmmessage"></p>
	<div class="confirmbuttons _center">
		<div class="confirmbuttonscontainer">
			<a href="#" id="okbutton">
				<div class="alert_button material_dark_bg">
					<span class="material_link">OK</span>
				</div>
			</a>
		</div>
		<div class="confirmbuttonscontainer">
			<a href="#" id="nobutton">
				<div class="alert_button material_dark_bg">
					<span>NO</span>
				</div>
			</a>
		</div>
	</div>
</div>