<?php
require_once("db_class.php");
require_once("symbol_info.php");
require_once("game_rule.php");
require_once("spin_info.php");
require_once("user_info.php");

$DBConf = new DBConfig();

function getDBConf(){
	global $DBConf;
	return $DBConf;
}

Class DBConfig{
	var $dbConnect;
	var $ruleInfo;
	var $symInfo;
	/*init configuration*/
	function DBConfig(){
		$this->sqlConnect();
		$this->initRuleInfo();
		$this->initSymbolInfo();
	}
	
	/*create new connection*/
	function sqlConnect(){
		$this->dbConnect = new DBAccess();
	}
	/*
	 * information related with rule
	 *
	 *
	*/
	/*init rule informaion from database*/
	function initRuleInfo(){
		if($this->dbConnect)
		{
			$this->ruleInfo = new GameRule();
			$this->ruleInfo->initRule($this->dbConnect);
		}
	}
	/*get rule information by id*/
	function getRulInfo($mId){
		return $this->ruleInfo->getRuleInfo($mId, -1);
	}
	
	/*
	 * information related with symbol
	 *
	 *
	*/
	/*init symbol information from database*/
	function initSymbolInfo(){
		if($this->dbConnect)
		{
			$this->symInfo = new SymbolInfo();
			$this->symInfo->initSymbolInfo($this->dbConnect);
		}
	}
	/*get symbol count*/
	function getSymbolCount(){
		return $this->symInfo->getSymbolCount();
	}
	/*get symbol info*/
	function getSymbolInfo($mId){
		return $this->symInfo->getSymbolInfo($mId, -1);
	}
	/*get symbol id*/
	function getSymbolId($mId){
		return $this->symInfo->getSymbolInfo($mId, 0);
	}
	/*get symbol path*/
	function getSymbolPath($mId){
		return $this->symInfo->getSymbolInfo($mId, 1);
	}
	/*get symbol animation path*/
	function getSymbolAnimPath($mId){
		return $this->symInfo->getSymbolInfo($mId, 2);
	}
	/*get symbol bouns*/
	function getSymbolBonus($mId){
		return $this->symInfo->getSymbolInfo($mId, 3);
	}
	
	/*
	 * information related with score
	 *
	 *
	*/
	function getScoreInfo($mCount){
		if($this->dbConnect && $mCount > 0)
		{
			$score = new SpinsInfo();
			return $score->getInfo($this->dbConnect, $mCount);
		}
		return 0;
	}
	
	/*User Infomation*/
	function verify_userlogin($name, $pwd){
		if($this->dbConnect > 0)
		{
			$users = new UserInfo();
			return $users->checkUser($this->dbConnect, $name, $pwd);
		}
		return -1;
	}
	
	function getUserInfo($mId){
		if($this->dbConnect > 0)
		{
			$users = new UserInfo();
			return $users->getUserInfo($this->dbConnect, $mId);
		}
		return 0;
	}
	
	function updateUserScore($mId, $win, $selCount, $lcredit){
		$value = array();
		if($this->dbConnect > 0){
			if($win == 0)
				$value[0] = 0;
			else
				$value[0] = count($win);
				
			$value[1] = 1;
			$value[2] = 0;
			if($value[0] > 0)
			{
				foreach($win as $iwin)
					$value[2] = ($value[2] * 1) + ($iwin[6] * 1);
			}
			$value[2] = ($value[2] * 1) - ($selCount * $lcredit);
			$users = new UserInfo();
			$users->updateScore($this->dbConnect, $mId, $value);
		}
	}
	
	function updateDonation($mId, $value){
		if($this->dbConnect > 0){
			$users = new UserInfo();
			return $users->updateDonation($this->dbConnect, $mId, $value);
		}
		return 0;
	}
}

?>
