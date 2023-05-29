<?php
require_once("db_class.php");

	Class UserInfo{
		var $id				= 0;
		var $name			= '';
		var $password		= '';
		var $email			= '';
		var $login_data 	= '';
		var $total_win		= 0;
		var $total_played 	= 0;
		var $donate_falg	= false;
		var $level			= 0;
		var $cur_win		= 0;
		var $cur_played		= 0;
		
		function UserInfo(){
		}
		
		function checkUser($sqlConnect, $name, $pwd){
			return $sqlConnect->verify_userlogin($name, $pwd);
		}
		
		function getUserInfo($sqlConnect, $mId){
			return $sqlConnect->getUserInfo($mId);
		}
		
		function updateScore($sqlConnect, $mId, $value){
			$sqlConnect->updateScore($mId, $value);
		}
		
		function updateDonation($sqlConnect, $mId, $value){
			return $sqlConnect->updateDonation($mId, $value);
		}
	}
?>
