// JavaScript Document
var frmHelp = null;
var FormHelp = function(){
	var main = this;
	main.isShow = false;
	
	main.init = function(){
		main.isShow = false;
		$("#help_close_btn").click(function(){
			main.form_hide();
			spinObj.other_form = false;
		});
	};
	main.form_show = function(){
		$('#help_form').css('visibility','visible');
		main.isShow = true;
	}
	
	main.form_hide = function(){
		$('#help_form').css('visibility','hidden');
		main.isShow = false;
		spinObj.other_form = false;
	}
}

