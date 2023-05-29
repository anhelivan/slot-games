<?php require_once("php/game_main.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Slot Game</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<script type="text/javascript" src="js/lib/jquery.min.js"></script>
<script type="text/javascript" src="js/game_main.js"></script>
<script type="text/javascript" src="js/canvas_ani.js"></script>
<script type="text/javascript" src="js/spin.js"></script>
<script type="text/javascript" src="js/donate.js"></script>
<script type="text/javascript" src="js/entry.js"></script>
<script type="text/javascript" src="js/help.js"></script>
</head>

<body>
    <div id="game_area">
    	<div id="game_header">
        	<div id="home_btn">
            	<img src="img/common/home_btn.png" id="home_img" />
            </div>
            <div id="sweep_entries">
            	<center><span>Sweep Entries</span></center>
                <h2>4450</h2>
            </div>
            <div id="ads_board">
            </div>
            <div id="totalwin_board"><center><span>Total Win</span></center><h2>250</h2></div>
            <div id="help_btn">
            	<img src="img/common/help_btn.png" id="help_img" />
            </div>
        </div>
        <div id="game_body">
        	<canvas id="spin_info"></canvas>
            <div id="donate_form">
            	<div id="donateclose"><div id="donate_close_btn"></div></div>
                Welcome<span>Giryong!</span>
                <br />You are currently donating to 
                <a href="#" id="Organization" name="organization">American Red Cross Organization.</a>
                &nbsp;&nbsp;<span style="font-style:italic">Choose a new charity?</span>
                <h2>Make a Donation</h2>
                <div id="charity">
                	<div id="donate_5" class="change_donate"><span>$5</span></div>
                	<div id="donate_25" class="change_donate"><span>$25</span></div>
                	<div id="donate_50" class="change_donate"><span>$50</span></div>
                	<div id="donate_100" class="change_donate"><span>$100</span></div>
                    
                    <div id="out_area">
                    	<label for='donate_out'>Donation</label>
                        <div id="donate_out"></div>
                    	<label for='credits'>Credits</label>
                        <div id="credits"></div>
                    </div>
                </div>
                
                <div id="button_area">
                    <div id="submit_btn"></div>
                    <div id="clear_btn"></div>
                </div>
            </div>
            <div id="pay_form">
            	<div id="payclose"><div id="pay_close_btn"></div></div>
            </div>
            
            <div id="help_form">
            	<div id="helpcolse"><div id="help_close_btn"></div></div>
            </div>
            
            <div id="left_ctrl">
            	<div class="li_left"><center><span>24</span></center></div>
            	<div class="li_left"><center><span>4</span></center></div>
            	<div class="li_left"><center><span>12</span></center></div>
            	<div class="li_left"><center><span>20</span></center></div>
            	<div class="li_left"><center><span>14</span></center></div>
            	<div class="li_left"><center><span>10</span></center></div>
            	<div class="li_left"><center><span>1</span></center></div>
            	<div class="li_left"><center><span>11</span></center></div>
            	<div class="li_left"><center><span>15</span></center></div>
            	<div class="li_left"><center><span>19</span></center></div>
            	<div class="li_left"><center><span>13</span></center></div>
            	<div class="li_left"><center><span>3</span></center></div>
            	<div class="li_left"><center><span>5</span></center></div>
            </div>
            <div id="spin_board">
            	<div class="pan_board" id="fitst_pan">
                	<ul><?php drawImage(1); ?></ul>
                </div>
            	<div class="pan_board" id="second_pan">
                	<ul><?php drawImage(2); ?></ul>
                </div>
            	<div class="pan_board" id="third_pan">
                	<ul><?php drawImage(3); ?></ul>
                </div>
            	<div class="pan_board" id="fourth_pan">
                	<ul><?php drawImage(4); ?></ul>
                </div>
            	<div class="pan_board" id="fifth_pan" style="margin:1px 0px 1px 0px; text-align:left">
                	<ul><?php drawImage(5); ?></ul>
                </div>
            </div>
            <div id="right_ctrl">
            	<div class="li_right"><center><span>6</span></center></div>
            	<div class="li_right"><center><span>2</span></center></div>
            	<div class="li_right"><center><span>25</span></center></div>
            	<div class="li_right"><center><span>16</span></center></div>
            	<div class="li_right"><center><span>23</span></center></div>
            	<div class="li_right"><center><span>8</span></center></div>
            	<div class="li_right"><center><span>1</span></center></div>
            	<div class="li_right"><center><span>9</span></center></div>
            	<div class="li_right"><center><span>22</span></center></div>
            	<div class="li_right"><center><span>17</span></center></div>
            	<div class="li_right"><center><span>18</span></center></div>
            	<div class="li_right"><center><span>7</span></center></div>
            	<div class="li_right"><center><span>21</span></center></div>
            </div>
        </div>
        <div id="game_footer">
        	<div id="green_bar"></div>
        	<div id="auto_play">
            	<div id="autospin_btn"></div>
                <div id="donate_btn"></div>
            </div>
        	<div id="detail_info">
            	<div id="luck_board"><h2>GOOD LUCK!</h2></div>
                <div id="win_entries"><center><span>Win</span></center><h3>2400</h3></div>
                <div id="linecredits"><center><span>Line Credits</span></center><h3>10</h3></div>
                <div id="spincredits"><center><span>Spin Credits</span></center><h3>0</h3></div>
                <div id="freespin_board"><center><span>Free Spins</span></center><h3>0</h3></div>
            </div>
        	<div id="entry_play">
            	<div id="maxplay_btn"></div>
                <div id="entries_btn"></div>
            </div>
        	<div id="spin_play">
            </div>
        </div>
    </div>
</body>
</html>
