<?php
	require_once("config.php");
	
	Class DBAccess
	{
		var $link = null;
		var $table_UserInfo = Array('table' => 'users', 
							'field_id' => 'id',
							'field_username' => 'name', 
							'filed_userpwd' => 'password',
							'filed_useremail' => 'email', 
							'filed_lastlogin' => 'lastdata',
							'filed_totalwin' => 'totalwin',
							'filed_totalplayed' => 'totalplayed',
							'filed_price' => 'price', 
							'filed_donate' => 'donate', 
							'filed_level' => 'level');
							
		var $table_SymbolInfo = Array('table' => 'symbol', 
							'field_id' => 'id',
							'field_symbolname' => 'path', 
							'field_symbolani' => 'animation', 
							'field_alias' => 'alias');

		var $table_RuleInfo = Array('table' => 'rule', 
							'field_id' => 'id',
							'field_first' => 'Pan1', 
							'field_second' => 'Pan2', 
							'field_third' => 'Pan3', 
							'field_fourth' => 'Pan4', 
							'field_fifth' => 'Pan5', 
							'field_score' => 'Score');
							
		var $table_SpinInfo = Array('table' => 'spins', 
							'field_id' => 'id',
							'field_win' => 'win', 
							'field_bonus' => 'bonus');
		
		function DBAccess()
		{
			$this->link = mysql_connect(IP_ADDR,USER_NAME,USER_PASS);
			if($this->link)
			{
				if(!mysql_select_db(DB_NAME,$this->link))
				{
					$sql = 'create database '.DB_NAME;
					$this->run_mysql($sql);
					
					mysql_select_db(DB_NAME,$this->link);
				}
				$this->create_usertable();
				$this->create_symboltable();
				$this->create_ruletable();
				$this->create_spininfotable();
			}
			
		}
		
		function create_usertable(){
			$sql = 'create table if not exists '.$this->table_UserInfo['table'].'(';
			$sql .= ($this->table_UserInfo['field_id'].' bigint unsigned not null auto_increment key, ');
			$sql .= ($this->table_UserInfo['field_username'].' char(128) not null, ');
			$sql .= ($this->table_UserInfo['filed_userpwd'].' char(128) not null, ');
			$sql .= ($this->table_UserInfo['filed_useremail'].' char(128), ');
			$sql .= ($this->table_UserInfo['filed_lastlogin'].' datetime, ');
			$sql .= ($this->table_UserInfo['filed_totalwin'].' bigint unsigned, ');
			$sql .= ($this->table_UserInfo['filed_totalplayed'].' bigint unsigned, ');
			$sql .= ($this->table_UserInfo['filed_price'].' double, ');
			$sql .= ($this->table_UserInfo['filed_donate'].' boolean, ');
			$sql .= ($this->table_UserInfo['filed_level'].' int');
			$sql .= ')';
			mysql_query($sql,$this->link);
		}
		
		function create_symboltable(){
			$sql = 'create table if not exists '.$this->table_SymbolInfo['table'].'(';
			$sql .= ($this->table_SymbolInfo['field_id'].' bigint unsigned not null auto_increment key, ');
			$sql .= ($this->table_SymbolInfo['field_symbolname'].' char(255) not null, ');
			$sql .= ($this->table_SymbolInfo['field_symbolani'].' char(255) not null, ');
			$sql .= ($this->table_SymbolInfo['field_alias'].' char(100) not null');
			$sql .= ')';
			mysql_query($sql);
		}
		
		function create_ruletable(){
			$sql = 'create table if not exists '.$this->table_RuleInfo['table'].'(';
			$sql .= ($this->table_RuleInfo['field_id'].' bigint unsigned not null auto_increment key, ');
			$sql .= ($this->table_RuleInfo['field_first'].' int unsigned, ');
			$sql .= ($this->table_RuleInfo['field_second'].' int unsigned, ');
			$sql .= ($this->table_RuleInfo['field_third'].' int unsigned, ');
			$sql .= ($this->table_RuleInfo['field_fourth'].' int unsigned, ');
			$sql .= ($this->table_RuleInfo['field_fifth'].' int unsigned, ');
			$sql .= ($this->table_RuleInfo['field_score'].' int unsigned');
			$sql .= ')';
			mysql_query($sql);
		}
		
		function create_spininfotable(){
			$sql = 'create table if not exists '.$this->table_SpinInfo['table'].'(';
			$sql .= ($this->table_SpinInfo['field_id'].' bigint unsigned not null auto_increment key, ');
			$sql .= ($this->table_SpinInfo['field_win'].' int unsigned, ');
			$sql .= ($this->table_SpinInfo['field_bonus'].' int unsigned');
			$sql .= ')';
			mysql_query($sql);
		}
		
		function verify_adminlogin($user,$pass)
		{
			$sql	= "select * from admin_access where username='".$user."' and password='".$pass."'";

			$result = mysql_query($sql,$this->link);
			$count	= mysql_num_rows($result);
			
			return $count;
		}
		
		function verify_userlogin($user,$pass)
		{
			$sql	= "select * from ".$this->table_UserInfo["table"]." where name='".$user."' and password='".$pass."'";
			$result = $this->get_data($sql);
			if(count($result) == 0)
				return -1;
			return $result[0][0];
		}
		
		function run_mysql($sql)
		{
			mysql_query($sql,$this->link);
			
			return mysql_insert_id();
		}

		function get_data($sql)
		{
			$ret_arr = array();
			
			$result = mysql_query($sql);
			while($res = mysql_fetch_array($result))
				array_push($ret_arr,$res);
			
			return $ret_arr;
		}
		
		/* rule sql relation */
		function getRuleInfo($ruleId){
			$sql = "select * from ";
			$sql .= $this->table_RuleInfo["table"];
			if($ruleId != -1)
			{
				$sql .= " where ";
				$sql .= $this->table_RuleInfo["field_id"];
				$sql .= ("=".$ruleId);
			}
			return $this->get_data($sql);
		}
		
		/**/
		function getSymbolInfo($symId){
			$sql = "select * from ";
			$sql .= $this->table_SymbolInfo["table"];
			if($symId != -1)
			{
				$sql .= " where ";
				$sql .= $this->table_SymbolInfo["field_id"];
				$sql .= ("=".$symId);
			}
			return $this->get_data($sql);
		}
		
		/**/
		function getSpinsInfo($mCount){
			$sql = "select * from ";
			$sql .= $this->table_SpinInfo["table"];
			if($mCount)
			{
				$sql .= " limit 0,";
				$sql .= $mCount;
			}
			return $this->get_data($sql);
		}
		
		/**/
		function getUserInfo($mId){
			$sql = "select * from ";
			$sql .= $this->table_UserInfo['table'];
			$sql .= " where ";
			$sql .= $this->table_UserInfo["field_id"];
			$sql .= ("=".$mId);
			return $this->get_data($sql);
		}
		
		function updateScore($mId, $value)
		{
			$prevInfo = $this->getUserInfo($mId);
			
			
			if((($prevInfo[0][7] * 1) + ($value[2] / 20)) < 0)
				return ;
			
			$sql = "update ";
			$sql .= $this->table_UserInfo["table"];
			$sql .= " set ";
			$sql .= $this->table_UserInfo["filed_totalwin"];
			$sql .= "=";
			$sql .= (($prevInfo[0][5] * 1) + ($value[0] * 1));
			$sql .= " , ";

			$sql .= $this->table_UserInfo["filed_totalplayed"];
			$sql .= "=";
			$sql .= (($prevInfo[0][6] * 1) + ($value[1] * 1));
			$sql .= " , ";

			$sql .= $this->table_UserInfo["filed_price"];
			$sql .= "=";
			$sql .= (($prevInfo[0][7] * 1) + ($value[2] / 20));

			if((($prevInfo[0][5] * 1) + ($value[0] * 1)) > 1200 && ($prevInfo[0][9] * 1) != 1)
			{
				$sql .= " , ";
				$sql .= $this->table_UserInfo["filed_level"];
				$sql .= "=";
				$sql .= 1;
			}
			
			$sql .= " where ";
			$sql .= $this->table_UserInfo["field_id"];
			$sql .= ("=".$mId);
			
			mysql_query($sql);
		}
		
		function updateDonation($mId, $value){
			$prevInfo = $this->getUserInfo($mId);
			
			if($prevInfo == 0 || (($prevInfo[0][7] * 1) + ($value[2] / 20)) < 0)
				return 0;
				
			$sql = "update ";
			$sql .= $this->table_UserInfo["table"];
			$sql .= " set ";
		
			$sql .= $this->table_UserInfo["filed_price"];
			$sql .= "=";
			$sql .= (($prevInfo[0][7] * 1) + ($value / 20));
			$sql .= " , ";

			$sql .= $this->table_UserInfo["filed_donate"];
			$sql .= "=";
			$sql .= 1;
			
			if((($prevInfo[0][7] * 1) + ($value / 20)) > 100 && $prevInfo[0][9] * 1 != 1)
			{
				$sql .= " , ";
	
				$sql .= $this->table_UserInfo["filed_level"];
				$sql .= "=";
				$sql .= 1;
			}
			
			$sql .= " where ";
			$sql .= $this->table_UserInfo["field_id"];
			$sql .= ("=".$mId);
			
			mysql_query($sql);
			return 1;
		}
		
	}
?>
