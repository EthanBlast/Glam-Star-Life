/* Coded by: Giovanni Caputo <giovannicaputo86@gmail.com> */

function filter(){
   var filter=document.getElementById('myfilter').value.toLowerCase();
   var output=document.getElementsByTagName('span');
   //var regex=new RegExp("^"+filter+"|\\s"+filter,"i");
   var regex=new RegExp(filter+"|\\s"+filter,"i");
   //alert(filter);
  for (var i = 0; i < output.length; i++) {
		var mail=output[i].getAttribute('u_mail');
		var childEl=output[i];
		if (mail != null) {
			//alert (i+" "+mail);
			if (regex.exec(mail)) {
				childEl.style.display = '';
			}
			else {
				childEl.style.display = 'none';
			}
		}
  }
}

function checkAll(check) {
  var checks = document.getElementsByName('mail[]'); 
  var lbl_filter=document.getElementById('myfilter');
  var filter=lbl_filter.value.toLowerCase();
  var regex=new RegExp(filter+"|\\s"+filter,"i");
  var boxLength = checks.length;
      for ( i=0; i < boxLength; i++ ) {
	     // TO DO : filtrare 
		 if (regex.exec(checks[i].value)) 
        checks[i].checked = check;
      }
	  lbl_filter.focus();
}


function checkUsPwd(){
var mymodulo = document.inviteFriendsForm;
var CarErrCogn = new String("|\!£$%&/()=?^§*°;:[]@#,-+");

var checks = document.getElementsByName('facebook-webmailType-emailParam-getContactsForm');
// controllo se è stato inserita username
	if (((mymodulo.Email.value == "") || (mymodulo.Passwd.value==""))&&(mymodulo.CSVfile.value=="") && checks.checked==false){
		alert ("Empty value");
		return false;
	}
	return true;
}

function someSelected(){
	var checks = document.getElementsByName('mail[]'); 
	var boxLength = checks.length;
	var sel=false;
    for ( i=0; i < boxLength; i++ ) {
		if (checks[i].checked == true) sel=true;
    }
	if (sel== false){
	  alert("You must select some contact");
	}
	return sel;
}

function inputSelection(rad, tipologia){
	var sel = rad.value; 
	divMie = new Array("yahooAPI","usr_pwd", "uploadFile","facebookDiv","msnAPI");
	for (var i = 0; i < divMie.length; i++) {
		style = document.getElementById(divMie[i]).style;
		 style.display ='none';	 
	}
	if (tipologia=='cURL'){
		style = document.getElementById('usr_pwd').style;	
	} 
	 if (tipologia=='CSV'){
	    style = document.getElementById('uploadFile').style;	 
	 }
	  if (tipologia=='facebook'){
	    style = document.getElementById('facebookDiv').style;	 
	 }
	 if (tipologia=='API'){
	//tipologia API
		if (sel=='yahoo'){
			style = document.getElementById('yahooAPI').style;
		}
		if (sel=='hotmail'){
			style = document.getElementById('msnAPI').style;
		}
		if ((sel!='hotmail')&&(sel!='yahoo')){
			style = document.getElementById('usr_pwd').style;
		}
	}
	
   style.display ='block';
}