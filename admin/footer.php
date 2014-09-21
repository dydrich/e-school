<footer id="footer">
	<span>Copyright <?php echo date("Y") ?> Riccardo Bachis | <a href="<?php print $_SESSION['__config__']['root_site'] ?>"><?php print $_SESSION['__config__']['intestazione_scuola'] ?></a></span>
</footer>
<div id="alert" style="display: none">
	<p>
		<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
		<span class="_bold" id="alertmessage" style="font-size: 1.2em"></span>
	</p>
</div>
<div id="error" style="display: none">
	<p>
		<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<span class="_bold" id="errormessage" style="font-size: 1.2em"></span>
	</p>
</div>
<div id='background_msg' style='width: 200px; text-align: center; font-size: 12px; font-weight: bold; padding-top: 30px; margin: auto'></div>
