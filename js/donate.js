// JavaScript Document
var frmDonate = null;
var FormDonate = function(){
	var main = this;
	main.isShow = false;
	main.donation = 0;
	
	main.init = function(){
		main.isShow = false;
		$("#donate_close_btn").click(function(){
			main.form_hide();
			spinObj.other_form = false;
		});
		
		$(".change_donate").click(function(){
			var index = $(".change_donate").index(this);
			main.selectDonateStyle(index);
		});
		
		$("#submit_btn").click(function(){
			main.submitDonation();
		});
		$("#clear_btn").click(function(){
			main.clearDonation();
		});
	};
	main.form_show = function(){
		$('#donate_form').css('visibility','visible');
		main.isShow = true;
	}
	
	main.form_hide = function(){
		$('#donate_form').css('visibility','hidden');
		main.isShow = false;
		spinObj.other_form = false;
	}
	
	main.selectDonateStyle = function(index){
		var i = 0;
		var tObj;
		main.donation = 0;
		for(i = 0; i < 4; i ++)
		{
			tObj = $(".change_donate").eq(i);
			if(i == 0)
				main.donation = 5;
			else if(i == 1)
				main.donation = 25;
			else if(i == 2)
				main.donation = 50;
			else if(i == 3)
				main.donation = 100
			else
				main.donation = 0;
				
			if(i == index)
			{
				tObj.css('background-color','#FCD743');
				main.donation = main.donation*20;
				$("#donate_out").text(main.donation);
				$("#donate_out").css({"color":"#000000", "font-size":"20px"});
			}
			else
				tObj.css('background-color','#FFFFFF');
		}
	}
	
	main.submitDonation = function(){
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: ({type : 7, data : main.donation}),
			cache: false,
			success: function(result)
			{
				if( result[0] == 0)
					alert("data error!");
				else
				{
					$("#donate_close_btn").click();
					envObj.TotalEntries = (envObj.TotalEntries * 1) + (main.donation * 1);
					envObj.updateScore();
				}
			}
		});
	}
	
	main.clearDonation = function(){
		var i = 0;
		var tObj;
		main.donation = 0;
		for(i = 0; i < 4; i ++)
		{
			tObj = $(".change_donate").eq(i);
			$("#donate_out").text("");
			tObj.css('background-color','#FFFFFF');
		}
	}
}

