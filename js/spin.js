// JavaScript Document
var envObj = null;
var spinObj = null;
var LineCredits = 10;

var SpinBoard = function()
{
	var main 		= this;
	main.canvas		= null;
	main.slotTimer	= null;
	main.slotRTime	= 0;
	main.setTimerFlag = false;
	main.defUlCount 	= 0;
	
	main.resultSpin	= null;
	main.filicker		= 0;
	main.other_form		= false;
	
	main.autoFlag		= false;
	main.autoTimer		= null;
	
	main.maxPlayFlag	= false;
	
	main.deltaScore = 0;
	
	main.init	= function()
	{
		$("#help_img").click(function(){
			if(main.maxPlayFlag)
				return;
				
			if(!main.other_form && !main.setTimerFlag)
			{
				frmHelp.form_show();
				main.other_form = true;
			}
		});
		
		$("#home_img").click(function(){
			if(!main.other_form && !main.setTimerFlag)
				alert("Home button clicked!");
		});
		
		$("#autospin_btn").click(function(){
			if(main.maxPlayFlag)
				return;
			
			if(!main.other_form && !main.setTimerFlag)
			{
				if(envObj.getSelLines())
				{
					if(!main.autoFlag)
					{
						$("#autospin_btn").css("background","url(img/common/stopspin_btn.png) no-repeat");
						main.autoFlag = true;
						main.autoTimer = setInterval(main.setAutoSpin, 500);
					}
					else
					{
						$("#autospin_btn").css("background","url(img/common/autospin_btn.png) no-repeat");
						main.autoFlag = false;
						if(main.autoTimer)
						{
							clearInterval(main.autoTimer);
							main.autoTimer = null;
						}
					}
				}
			}
			else if(main.autoFlag)
			{
				$("#autospin_btn").css("background","url(img/common/autospin_btn.png) no-repeat");
				main.autoFlag = false;
				if(main.autoTimer)
				{
					clearInterval(main.autoTimer);
					main.autoTimer = null;
				}
			}
		});
		
		$("#donate_btn").click(function(){
			if(!main.other_form && !main.setTimerFlag)
			{
				$.ajax({
					type: "POST",
					url: "php/ajax.php", 
					data: ({type : 8}),
					cache: false,
					success: function(result)
					{
						if((result * 1) == 1)
						{
							frmDonate.form_show();
							main.other_form = true;
						}
						else
							alert("Can't use DONATE button again!");
					}
				});
			}
		});
		
		$("#maxplay_btn").click(function(){
			var i = 0;
			
			if(!main.other_form && !main.setTimerFlag && !main.autoTimer)
			{
				if(!main.maxPlayFlag)
				{
					for(i = 0; i < 25; i ++)
						envObj.SelPinItem[i] = true;
						
					$("#maxplay_btn").css("background","url(img/common/stopplay_btn.png) no-repeat");
					main.maxPlayFlag = true;
					
					main.autoFlag = true;
					main.autoTimer = setInterval(main.setAutoSpin, 500);
				}
				else
				{
					$("#maxplay_btn").css("background","url(img/common/maxplay_btn.png) no-repeat");
					main.maxPlayFlag = false;
					
					main.autoFlag = false;
					if(main.autoTimer)
					{
						clearInterval(main.autoTimer);
						main.autoTimer = null;
					}
					envObj.releasePin();
				}
			}
			else if(main.maxPlayFlag)
			{
				$("#maxplay_btn").css("background","url(img/common/maxplay_btn.png) no-repeat");
				main.maxPlayFlag = false;
				
				main.autoFlag = false;
				if(main.autoTimer)
				{
					clearInterval(main.autoTimer);
					main.autoTimer = null;
				}
				envObj.releasePin();
			}
		});
		
		$("#entries_btn").click(function(){
			if(!main.other_form && !main.setTimerFlag)
			{
				frmEntry.form_show();
				main.other_form = true;
			}
		});
		$("#spin_play").click(function(){
			if(!main.other_form && !main.setTimerFlag)
				main.click_spin_button();
		});
		
		mT1 = $("#spin_board").children("div")[0];
		mT2 = $(mT1).children("ul");
		main.defUlCount = mT2.children().length;
		
		main.initCanvas();
	}
	
	main.initCanvas	= function()
	{
		var iwidth = 0;
		var iheight = 0;
		
		var left	= 0;
		var top		= 0
		
		iwidth = $('#spin_board').width();
		iheight = $('#spin_board').height();
		
		left	= $('#spin_board').offset().left - 50;
		top		= $('#spin_board').offset().top;
		
		$('#spin_info').width(iwidth - 15);
		$('#spin_info').height(iheight - 1);
		$('#spin_info').css("left",left);
		$('#spin_info').css("top",top);
		
		main.canvas		= new CanvasAni();
		main.canvas.init();
	}
	main.click_spin_button = function()
	{
		if(main.setTimerFlag == false)
		{
			var selLines = envObj.getSelLines();
			
			if(selLines != 0)
			{
				main.canvas.clearContext();
				main.slotRTime = 0;
				main.setTimerFlag = true;
				
				$.ajax({
					type: "POST",
					url: "php/ajax.php", 
					data: ({type : 1, data : selLines, score : LineCredits}),
					cache: false,
					success: function(result)
					{
						if(envObj.TotalEntries < selLines.length * LineCredits)
						{
							alert("You haven't enough entries!");
							main.setTimerFlag = false;
							return;
						}
						envObj.TotalEntries -= (selLines.length * LineCredits);
						envObj.TotalPlayed = envObj.TotalPlayed * 1 + 1;
						envObj.Play = envObj.Play * 1 + 1;
						envObj.updateScore();
						main.resultSpin = $.parseJSON(result);
						main.addNewDiv(main.resultSpin.place);
						main.slotTimer = setInterval(main.spinAnim, 30);
						
						if(main.resultSpin.win == 0 || main.resultSpin.win == 0)
							return ;
							
						for(i = 0; i < main.resultSpin.win.length; i ++)
						{
							envObj.TotalEntries = (envObj.TotalEntries * 1) + (main.resultSpin.win[i][6] * 1);
							envObj.TotalWin = envObj.TotalWin * 1 + 1;
							envObj.Win = envObj.Win * 1 + 1;
							
							if(main.resultSpin.win[i][7] == "FREESPIN")
								envObj.FreeSpin = (envObj.FreeSpin * 1) + 1;
						}
					}
				});
			}
			else
				alert("Please select line.");
		}
	}
	
	main.spinAnim			= function()
	{
		$("#spin_board").children("div").each(function()
		{
			var obj		= $(this).children("ul");
			var margin = obj.css("margin-top").replace("px","") * 1;
			var space = margin % 145;
			if(margin >= 0)
				margin = (-1) * (obj.children().length - 6) * 145;
				
			switch($(this).index())
			{
				case 0:
					if(main.slotRTime > 60)
					{
						margin = (-1) * (obj.children().length - 3) * 145;
						obj.css('margin-top', margin + "px");
						return;
					}
					margin += 50;
					break;
				case 1:
					if(main.slotRTime > 80)
					{
						margin = (-1) * (obj.children().length - 3) * 145;
						obj.css('margin-top', margin + "px");
						return;
					}
					margin += 55;
					break;
				case 2	:
					if(main.slotRTime > 100)
					{
						margin = (-1) * (obj.children().length - 3) * 145;
						obj.css('margin-top', margin + "px");
						return;
					}
					margin += 60;
					break;
				case 3	:
					if(main.slotRTime > 120)
					{
						margin = (-1) * (obj.children().length - 3) * 145;
						obj.css('margin-top', margin + "px");
						return;
					}
					margin += 65;
					break;
				case 4	:
					if(main.slotRTime > 130)
					{
						clearInterval(main.slotTimer);
						margin = (-1)*(obj.children().length - 3)*145;
						obj.css('margin-top', margin + "px");
						main.canvasAniProc();
						return;
					}
					margin += 70;
					break;
			}
			
			obj.css('margin-top', margin + "px");
		});
		
		main.slotRTime ++;
	}
	
	main.canvasAniProc = function() {
		main.slotTimer = setInterval(main.canvasAni, 500);
	}
	
	main.canvasAni = function(){
		var i = 0;
		clearInterval(main.slotTimer);
		i = Math.floor(main.filicker / 3);
		if(i >= main.resultSpin.win.length)
		{
			main.filicker = 0;
			main.canvas.procAnimation(main.resultSpin.win, '#7fff00', -1);
			
			mElement = document.getElementById('sweep_entries');
			subElement = mElement.getElementsByTagName('h2')[0];
			var preValue = subElement.innerHTML * 1;
			var sumScore = (envObj.TotalEntries * 1) - preValue;
			
			if(sumScore != 0 )
			{
				mElement = document.getElementById('luck_board');
				subElement = mElement.getElementsByTagName('h2')[0];
				subElement.innerHTML = "Congratulations! Score = " + sumScore;
			}
			main.deltaScore = Math.floor((envObj.TotalEntries - preValue) / 51);
			if(main.deltaScore == 0)
			{
				main.deltaScore = 1;
				main.slotTimer = setInterval(main.scoreAni, 100);
			}
			else
				main.slotTimer = setInterval(main.scoreAni, 20);
		}
		else
		{
			
			if(i < main.resultSpin.win.length)
			{
				main.canvas.procAnimation(main.resultSpin.win, '#7fff00', i);
				mElement = document.getElementById('luck_board');
				subElement = mElement.getElementsByTagName('h2')[0];
				subElement.innerHTML = main.resultSpin.win[i][7] + " five match, score = " + main.resultSpin.win[i][6];
			}
			main.filicker ++;
			main.slotTimer = setInterval(main.canvasClear, 750);
		}
	}
	
	main.canvasClear = function(){
		clearInterval(main.slotTimer);
		
		main.canvas.clearContext();
		
		mElement = document.getElementById('luck_board');
		subElement = mElement.getElementsByTagName('h2')[0];
		subElement.innerHTML = "";
		main.slotTimer = setInterval(main.canvasAni, 300);
	}
	
	main.scoreAni = function(){
		mElement = document.getElementById('sweep_entries');
		subElement = mElement.getElementsByTagName('h2')[0];
		var preValue = subElement.innerHTML * 1;
		
		if(main.deltaScore == 0)
			main.deltaScore = 10;
			
		preValue += main.deltaScore;
		if(preValue < envObj.TotalEntries)
			subElement.innerHTML = preValue;
		else
		{
			clearInterval(main.slotTimer);
			main.slotTimer = null;
			envObj.updateScore();
			main.setTimerFlag = false;
		}
	}
	
	main.addNewDiv = function(lstStr){
		$("#spin_board").children("div").each(function(){
			var obj		= $(this).children("ul");
			var i = 0;
			var j = 0;
			var mT1;
			if(obj.children().length > main.defUlCount)
			{
				mT1 = obj.children();
				i = obj.children().length;
				$(mT1[i - 1]).remove();
				$(mT1[i - 2]).remove();
				$(mT1[i - 3]).remove();
			}
			for(i = 0; i < 3; i ++)
			{
				j = $(this).index();
				obj.append(lstStr[j][i]);
			}
		});
	}
	
	main.setAutoSpin = function(){
		var selLines = envObj.getSelLines();
		if(envObj.TotalEntries < selLines.length * LineCredits)
		{
			clearInterval(main.autoTimer);
			main.autoTimer = null;
			if(main.maxPlayFlag)
				$("#maxplay_btn").click();
			else
				$("#autospin_btn").click();
		}
		else if(main.autoTimer && !main.setTimerFlag)
			$("#spin_play").click();
	}
	
};


var envObject = function(){
	var mThis = this;
	mThis.SelPinItem = new Array(25);
	mThis.TotalWin = 0;
	mThis.Play = 0;
	mThis.TotalPlayed = 0;
	mThis.Win = 0;
	mThis.TotalEntries = 0;
	mThis.FreeSpin = 0;
	
	mThis.init = function(){
		for(i = 0; i < 25; i ++)
			mThis.SelPinItem[i] = false;
			
		$("#left_ctrl").find("div").click(function(){
			var mSelIndex = -1;
			switch($(this).index()){
			case 0:	mSelIndex = 23;	break;
			case 1:	mSelIndex = 3;	break;
			case 2:	mSelIndex = 11;	break;
			case 3:	mSelIndex = 19;	break;
			case 4:	mSelIndex = 13;	break;
			case 5:	mSelIndex = 9;	break;
			case 6:	mSelIndex = 0;	break;
			case 7:	mSelIndex = 10;	break;
			case 8:	mSelIndex = 14;	break;
			case 9:	mSelIndex = 18;	break;
			case 10:mSelIndex = 12;	break;
			case 11:mSelIndex = 2;	break;
			case 12:mSelIndex = 4;	break;
			}
			mThis.setSelPin(mSelIndex, 0);
		})

		$("#right_ctrl").find("div").click(function(){
			var mSelIndex = -1;
			switch($(this).index()){
			case 0:	mSelIndex = 5; break;
			case 1:	mSelIndex = 1; break;
			case 2:	mSelIndex = 24;	break;
			case 3:	mSelIndex = 15;	break;
			case 4:	mSelIndex = 22;	break;
			case 5:	mSelIndex = 7; break;
			case 6:	mSelIndex = 0; break;
			case 7: mSelIndex = 8; break;
			case 8:	mSelIndex = 21;	break;
			case 9:	mSelIndex = 16;	break;
			case 10: mSelIndex = 17; break;
			case 11: mSelIndex = 6; break;
			case 12: mSelIndex = 20; break;
			}
			mThis.setSelPin(mSelIndex, 1);
		})
		
		$.ajax({
			type: "POST",
			url: "php/ajax.php",
			data: ({type : 4}),
			cache: false,
			success: function(result)
			{
				var jResult = $.parseJSON(result);
				
				mThis.TotalWin = jResult[0];
				mThis.TotalPlayed = jResult[1];
				mThis.TotalEntries = jResult[2] * 20;
				mThis.Play = 0;
				mThis.Win = 0;
				mThis.FreeSpin = 0;
				
				mThis.updateScore();
			}
		});
		
	}
	
	mThis.setSelPin = function(index, flag){
		var m_index = mThis.getIndexfromPandId(index);
		if(mThis.SelPinItem[index] != true)
		{
			mThis.SelPinItem[index] = true;
			if(!flag)
			{
				mElement = document.getElementById("left_ctrl");
				subElement = mElement.getElementsByTagName("div")[m_index];
				$(subElement).css({'background':'url("img/common/sel_01.png") no-repeat', 'margin-left':'25px'});
				
				if(index == 0)
				{
					mElement = document.getElementById("right_ctrl");
					subElement = mElement.getElementsByTagName("div")[m_index];
					$(subElement).css('background','url("img/common/rsel_01.png") no-repeat');
				}
			}
			else
			{
				mElement = document.getElementById("right_ctrl");
				subElement = mElement.getElementsByTagName("div")[m_index];
				$(subElement).css('background','url("img/common/rsel_01.png") no-repeat');
				
				if(index == 0)
				{
					mElement = document.getElementById("left_ctrl");
					subElement = mElement.getElementsByTagName("div")[m_index];
					$(subElement).css({'background':'url("img/common/sel_01.png") no-repeat', 'margin-left':'25px'});
				}
			}
			
			$.ajax({
				type: "POST",
				url: "php/ajax.php",
				data: ({type : 2, data : index}),
				cache: false,
				success: function(result)
				{
					var jResult = $.parseJSON(result);
					mThis.selLineRuleProc(jResult);
				}
			});
		}
		else
		{
			mThis.SelPinItem[index] = false;
			if(!flag)
			{
				mElement = document.getElementById("left_ctrl");
				subElement = mElement.getElementsByTagName("div")[m_index];
				$(subElement).css({'background':'url("img/common/sel.png") no-repeat', 'margin-left':'20px'});
				
				if(index == 0)
				{
					mElement = document.getElementById("right_ctrl");
					subElement = mElement.getElementsByTagName("div")[m_index];
					$(subElement).css('background','url("img/common/rsel.png") no-repeat');
				}
			}
			else
			{
				mElement = document.getElementById("right_ctrl");
				subElement = mElement.getElementsByTagName("div")[m_index];
				$(subElement).css('background','url("img/common/rsel.png") no-repeat');
				if(index == 0)
				{
					mElement = document.getElementById("left_ctrl");
					subElement = mElement.getElementsByTagName("div")[m_index];
					$(subElement).css({'background':'url("img/common/sel.png") no-repeat', 'margin-left':'20px'});
				}
			}
			spinObj.canvas.clearContext();
		}
		
		var lines = mThis.getSelLines();
		
		mElement = document.getElementById('spincredits');
		subElement = mElement.getElementsByTagName('h3')[0];
		if(lines == 0)
			subElement.innerHTML = 0;
		else
			subElement.innerHTML = lines.length * LineCredits;

		return mThis.SelPinItem[index];
	}
	
	mThis.releasePin = function(){
		var i = 0;
		spinObj.canvas.clearContext();
		for(i = 0; i < 25; i++)
			mThis.SelPinItem[i] = false;
			
		for(i = 0; i < 13; i++)
		{
			mElement = document.getElementById("left_ctrl");
			subElement = mElement.getElementsByTagName("div")[i];
			$(subElement).css({'background':'url("img/common/sel.png") no-repeat', 'margin-left':'20px'});
			
			mElement = document.getElementById("right_ctrl");
			subElement = mElement.getElementsByTagName("div")[i];
			$(subElement).css('background','url("img/common/rsel.png") no-repeat');
		}
	}
	
	mThis.getSelPin = function(index){
		return mThis.SelPinItem[index];
	}
	
	mThis.getIndexfromPandId = function(mId){
		switch(mId)
		{
		case 23:
		case 5:
			return 0;
		case 3:
		case 1:
			return 1;
		case 11:
		case 24:
			return 2;
		case 19:
		case 15:
			return 3;
		case 13:
		case 22:
			return 4;
		case 9:
		case 7:
			return 5;
		case 0:
			return 6;
		case 10:
		case 8:
			return 7;
		case 14:
		case 21:
			return 8;
		case 18:
		case 16:
			return 9;
		case 12:
		case 17:
			return 10;
		case 2:
		case 6:
			return 11;
		case 4:
		case 20:
			return 12;
		}
		return -1;
	}
	
	mThis.getSelLines = function(){
		var mRet = new Array();
		var j = 0;
		for(i = 0; i < 25; i ++)
		{
			if(mThis.getSelPin(i))
				mRet[j++] = i + 1;
		}
		if(j == 0)
			return 0;
		return mRet;
	}
	
	mThis.selLineRuleProc = function(selRule){
		var aSel = new Array();
		spinObj.canvas.clearContext();
		aSel[0] = selRule;
		spinObj.canvas.procAnimation(aSel, 'Red', -1);
	}
	
	mThis.updateScore = function(){
		mElement = document.getElementById('sweep_entries');
		subElement = mElement.getElementsByTagName('h2')[0];
		subElement.innerHTML = mThis.TotalEntries;
		
		mElement = document.getElementById('totalwin_board');
		subElement = mElement.getElementsByTagName('h2')[0];
		subElement.innerHTML = mThis.TotalWin;
		
		mElement = document.getElementById('win_entries');
		subElement = mElement.getElementsByTagName('h3')[0];
		subElement.innerHTML = mThis.Win;
		
		mElement = document.getElementById('linecredits');
		subElement = mElement.getElementsByTagName('h3')[0];
		subElement.innerHTML = LineCredits;
		
		mElement = document.getElementById('freespin_board');
		subElement = mElement.getElementsByTagName('h3')[0];
		subElement.innerHTML = mThis.FreeSpin;
		
		mElement = document.getElementById('luck_board');
		subElement = mElement.getElementsByTagName('h2')[0];
		subElement.innerHTML = "GOOD LUCK!";
	}
};
