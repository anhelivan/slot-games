// JavaScript Document
$(document).ready(function(){
						   
	envObj = new envObject();
	spinObj = new SpinBoard();
	frmDonate = new FormDonate();
	frmEntry = new FormEntry();
	frmHelp = new FormHelp();
	
	envObj.init();
	spinObj.init();
	frmDonate.init();
	frmEntry.init();
	frmHelp.init();
	$.ajax({
		type: "POST",
		url: "php/ajax.php", 
		data: ({type : 5}),
		cache: false,
		success: function(result)
		{
			if(result != 0)
				window.open (result,'_self',false);
		}
	});
	
	$("#body").show();
});
