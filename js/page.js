var logged = false;

function include_dom(script_filename) {
    var html_doc = document.getElementsByTagName('head').item(0);
    var js = document.createElement('script');
    js.setAttribute('language', 'javascript');
    js.setAttribute('type', 'text/javascript');
    js.setAttribute('src', script_filename);
    html_doc.appendChild(js);
    return false;
}
//var md5_script = root+"/js/md5-min.js";
//include_dom(md5_script);

/*
 * funzione di login unica
 */


function coming_soon(){
    alert('Coming soon');
}

function trim (str) {
	str = str.replace(/^\s+/, '');
	for (var i = str.length - 1; i >= 0; i--) {
		if (/\S/.test(str.charAt(i))) {
			str = str.substring(0, i + 1);
			break;
		}
	}
	return str;
}


function register(){
    coming_soon();
}

function do_logout(){
    document.location.href = "/logout.php";
}

function in_array(ar, val){
    //alert("==="+val+"===");
    for(var i = 0; i < ar.length; i++)
        if(ar[i] == val)
            return true;

    return false;
}

var genera_password = function(path, _alert, get_clear_password){
    var url = path+"shared/account_manager.php";
    $.ajax({
        type: "POST",
        url: url,
        data: {action: 'get_pwd'},
        dataType: 'json',
        async: false,
        error: function() {
            j_alert("error", "Errore di trasmissione dei dati");
        },
        succes: function() {

        },
        complete: function(data){
            r = data.responseText;
            if(r == "null"){
                return false;
            }
            var json = $.parseJSON(r);
            if (json.status == "ko"){
                j_alert("error", json.message);
                return;
            }
            else {
                pass = json.pwd;
                epass = json.epwd;

            }
        }
    });
    if(_alert){
        alert("La nuova password e': "+pass);
    }
    if(get_clear_password){
        var ret = epass+";"+pass;
        return ret;
    }
    return epass;
};

window.open_centered = function(url, name, width, height, options){
	var leftS = (screen.width - width) / 2;
	var topS = (screen.height - height) / 2;
	var pop = this.open(url, name, 'width='+width+', height='+height+', left='+leftS+', top='+topS);
	return pop;
};

Array.prototype.remove_by_value = function(val) {
    for(var i=0; i<this.length; i++) {
        if(this[i] == val) {
            this.splice(i, 1);
            break;
        }
    }
};

window._alert = function(string){
	openInfoDialog(string, 3);
};

window.sqlalert = function(){
	j_alert("error", "Si e` verificato un errore. Si prega di contattare il responsabile del software per la risoluzione", 3);
};

function valida_data(strData){
	var strLen = strData.length;
    var newData = "";
    if(strLen != 10)
    	return false;
    var reg_exp = /\d\d\/\d\d\/\d\d\d\d/;
    if(!strData.match(reg_exp))
    	return false;
    if (strLen > 0){
    	for (var ic=0;ic<=strLen;ic++){
        	if (strData.charAt(ic) != " ")
            	newData = newData + strData.charAt(ic);
        }
        // alert(newData);
    }
    else{
        return (false);
    }

    //divido la stringa in 3 pezzi
    if (newData.length > 0){
    	var arData = newData.split(/[^\d]/);
        if (arData.length != 3)
        	return(false);
    }
    else{
    	return (false);
    }

    giorno = parseInt(arData[0]);
    mese = parseInt(arData[1]);
    anno = parseInt(arData[2]);
    var trueFlag = true;

    if ((arData[2] > 2200)||(arData[2] < 1800))//>
    	trueFlag = false;

    if ((arData[1] > 12)||(arData[1] < 1))//>
        trueFlag = false;

    if ((arData[0] > 31)||(arData[0] < 1))//>
        trueFlag = false;
    if ((arData[1] == 4 || arData[1] == 6 || arData[1] == 9 || arData[1] == 11) && arData[0] == 31)
        trueFlag = false;
    if (arData[1] == 2){
        var isleap=(arData[2]%4==0 && (arData[2]%100!=0 || arData[2]%400==0));
        if (arData[0] > 29 || (arData[0] == 29 && !isleap))
    	    trueFlag = false;
    }
    return(trueFlag);
}

var timeout;
var exec_code;
function openInfoDialog(msg, tm) {
	var html = "<div id='msg_div' style='width: 100%; text-align: center; font-size: 12px; font-weight: bold; padding-top: 30px; margin: auto'>"+msg+"</div>";
	Dialog.info(html, 
	{
		width:250, 
		height:100, 
		showProgress: false,
		className: "alphacube"
	}); 
	timeout = tm; 
	setTimeout(infoTimeout, 1000);
} 
function infoTimeout() { 
	timeout--; 
	if (timeout > 0) { 
		//Dialog.setInfoMessage(messages[index]); 
		setTimeout(infoTimeout, 1000); 
	} 
	else{
		Dialog.closeInfo();
	}
}

function valida_orario(orario){
	tm = orario.split(":");
	if(tm[0] < 6 || tm[0] > 23 || tm[1] > 59 || (orario.match(/[^\d:]/))){
		return false;
	}
	return true;
}

function getElementPosition(elemID) {
	var offsetTrail = document.getElementById(elemID);
	var offsetLeft = 0;
	var offsetTop = 0;
	 
	while (offsetTrail) {
		offsetLeft += offsetTrail.offsetLeft;
		offsetTop += offsetTrail.offsetTop;
		offsetTrail = offsetTrail.offsetParent;
	}
	 
	if (navigator.userAgent.indexOf("Mac") != -1 && typeof document.body.leftMargin != "undefined") {
		offsetLeft += document.body.leftMargin;
		offsetTop += document.body.topMargin;
	}
	 
	return {left:offsetLeft, top:offsetTop};
}

var yellow_fade = function(elem){
	var trasp = 1;
	$(elem).setStyle({backgroundColor: "rgba(238, 238, 76, 1)"});
	var i = 0;
	var intv = window.setInterval(function(){trasp -= 0.1; i++; $(elem).setStyle({backgroundColor: "rgba(238, 238, 76, "+trasp+")"});}, 200);
	if(i > 10)
		window.clearInterval(intv);	
};

var deleteArrayElement = function(elem, myarray){
    var c = myarray.length;
    for(var i = 0; i < c; i++){
        if(myarray[i] == elem){
            //alert("JS: Ho trovato "+elem);
            for(var x = i+1; x < c; x++){
                myarray[i++] = myarray[x];
            }
            myarray.pop();
        }
    }
};

var select_level = function(path, page, level){
	var url = path+"shared/set_school_order.php";
	$.ajax({
		type: "POST",
		url: url,
		data: {id: level},
		dataType: 'text',
		error: function() {
			alert("Errore di trasmissione dati");
		},
		succes: function() {
			
		},
		complete: function(data){
			r = data.responseText;
			document.location.href = page;
		}
    });
};

var load_jalert = function(){
    $('#alert').dialog({
        autoOpen: false,
        dialogClass: 'no_display ui-state-highlight',
        show: {
            effect: "fadeIn",
            duration: 800
        },
        hide: {
            effect: "fadeOut",
            duration: 500
        },
        modal: true,
        width: 200,
        title: '',
        open: function(event, ui){

        }
    });

    $('#error').dialog({
        autoOpen: false,
        dialogClass: 'no_display ui-state-error',
        show: {
            effect: "fadeIn",
            duration: 800
        },
        hide: {
            effect: "fadeOut",
            duration: 500
        },
        modal: true,
        width: 200,
        title: '',
        open: function(event, ui){

        }
    });
};

var j_alert = function(type, msg){
    if (type == "alert") {
        $('#alertmessage').text(msg);
        $('#alert').dialog("open");
        window.setTimeout(function(){
            $('#alert').dialog("close");
        }, 3000);
    }
    else {
        $('#errormessage').text(msg);
        $('#error').dialog("open");
        window.setTimeout(function(){
            $('#error').dialog("close");
        }, 3000);
    }
};

/*
codice per la visualizzazione durante processi in background
versione per jquery
 */
var exec_code;
var background_process = function(msg, tm) {
    $('#background_msg').text(msg);
    $('#background_msg').dialog({
        autoOpen: true,
        dialogClass: 'no_display ui-state-highlight',
        show: {
            effect: "fadeIn",
            duration: 800
        },
        hide: {
            effect: "fadeOut",
            duration: 1000
        },
        modal: true,
        width: 200,
        title: '',
        open: function(event, ui){

        }
    });
    timeout = tm;
    setTimeout(background_progress, 1000);
};

var background_progress = function() {
    timeout--;
    if (timeout > 0) {
        //Dialog.setInfoMessage(messages[index]);
        setTimeout(background_progress, 1000);
    }
    else{
        $('#background_msg').dialog("close");
    }
};
