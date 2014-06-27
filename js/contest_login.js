is_visible = false;
function show_login(){
	if(!is_visible){
		$('login').appear({duration: 2.0});
		is_visible = true;
	}
	else{
		$('login').fade({duration: 2.0});;
		is_visible = false;
	}
}

function log_in(){
	var logname = $('logname_field').value;
	var pwd = $('pwd_field').value;
	var req = new Ajax.Request('login.php',
			  {
			    	method:'post',
			    	parameters: {logname: logname, pwd: pwd},
			    	onSuccess: function(transport){
			      		var response = transport.responseText || "no response text";
			      		var dati = response.split(";");
			      		if(dati[0] == "ok"){
			      			$('menu').innerHTML += "<li style='float: right'><a href='#'>"+dati[3]+"</a></li>";
			      			$('login_link').innerHTML = "Logout";
			      			$('login_link').onclick = function(){do_logout()};
			      			show_login();
			      		}
			      		else{
							$('login').innerHTML = "<span style='color: red'>"+dati[1]+$('login').innerHTML;
			      		}
			     		
			    	},
			    	onFailure: function(){ alert("Si e' verificato un errore...") }
			  });
}