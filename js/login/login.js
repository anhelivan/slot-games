// JavaScript Document
$(document).ready(function(){
	$.ajax({
		type: "POST",
		url: "php/ajax.php", 
		data: ({type : 6}),
		cache: false,
	});
	
	$('#login_btn').click(function(){
		var UserName = $('#username').val();
		var Password = $('#password').val();
		if( UserName.length == 0 || Password.length == 0 )
		{
			alert("Please type UserName and Password!");
			return ;
		}
		$.ajax({
			type: "POST",
			url: "php/ajax.php", 
			data: ({type : 3, name : UserName, pwd : Password}),
			cache: false,
			success: function(result)
			{
				if(result != -1)
					window.open (result,'_self',false);
				else
					alert("Invalid username and password");
			}
		});
	});
});
