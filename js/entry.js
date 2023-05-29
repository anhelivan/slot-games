// JavaScript Document
var frmEntry = null;
var FormEntry = function(){
	var main = this;
	main.isShow = false;
	
	main.init = function(){
		main.isShow = false;
		$("#pay_close_btn").click(function(){
			main.form_hide();
			spinObj.other_form = false;
		});
	};
	main.form_show = function(){
		$('#pay_form').css('visibility','visible');
		main.isShow = true;
	}
	
	main.form_hide = function(){
		$('#pay_form').css('visibility','hidden');
		main.isShow = false;
	}
	
}
