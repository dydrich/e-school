document.observe("dom:loaded", function(){
	$$('.form_input').invoke("observe", "focus", function(event){
		this.setStyle({outline: '1px solid blue'});
	});
	$$('.form_input').invoke("observe", "blur", function(event){
		this.setStyle({outline: ''});
	});
	$('save_button').observe("click", function(event){
		event.preventDefault();
		go(<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0) print("3, ".$_REQUEST['id']); else print("1, 0"); ?>);
	});
	<?php if(isset($_REQUEST['id']) && $_REQUEST['id'] != 0){
    ?>
    $('del_button').observe("click", function(event){
		event.preventDefault();
		go(2, <?php print $_REQUEST['id'] ?>);
	}); 
    <?php
    }
    ?>
});