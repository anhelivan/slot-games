/**/
var CanvasAni = function(){
	var main = this;
	main.canvas		= null;
	main.context	= null;
	
	main.init = function(){
		main.canvas		= document.getElementById("spin_info");
		main.context	= main.canvas.getContext("2d");
		main.context.clearRect(0, 0, main.canvas.width, main.canvas.height);
	}
	
	main.procAnimation = function(lstWin, iColor, index){
		var i = 0;
		
		if(lstWin == 0 || lstWin.length == 0 || index >= lstWin.length)
			return ;
			
		if(index == -1)
		{
			for(i = 0; i < lstWin.length; i++)
				main.drawAnimation(lstWin[i], iColor);
		}
		else
			main.drawAnimation(lstWin[index], iColor);
	}
	
	main.drawAnimation = function(rule, iColor){
		var mX = 0;
		var mY = 0;
		var dY = main.canvas.height / 3;
		var dX = main.canvas.width / 5;
		if(rule == 0 || rule.length > 8)
			return ;
			
		mX = dX / 2;
		main.context.lineWidth = 2;
		main.context.strokeStyle = iColor;
		main.context.beginPath();
		main.context.moveTo(0, (rule[1] - 1) * dY + dY / 2);
		
		for(i = 1; i <= 5; i ++){
			main.context.lineTo((dX * (i - 1)) + (dX / 2), ((rule[i] - 1) * dY) + dY / 2);
		}
		main.context.lineTo(main.canvas.width, ((rule[5] - 1) * dY) + dY / 2);
		
		main.context.stroke();
	}
	
	main.clearContext = function(){
		main.context.clearRect(0, 0, main.canvas.width, main.canvas.height);
	}
};

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}