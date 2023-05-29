<?php
	Class GameRule{
		var $rule			= array();
		function GameRule(){
		}
		
		function initRule($sqlConnection){
			if($sqlConnection)
			{
				$sql_result = $sqlConnection->getRuleInfo(-1);
				if($sql_result)
				{
					foreach ( $sql_result as $value )
					{
						$this->rule[$value[0] - 1][0] = $value[0];/*id*/
						$this->rule[$value[0] - 1][1] = $value[1];/*1 pane*/
						$this->rule[$value[0] - 1][2] = $value[2];/*2 pane*/
						$this->rule[$value[0] - 1][3] = $value[3];/*3 pane*/
						$this->rule[$value[0] - 1][4] = $value[4];/*4 pane*/
						$this->rule[$value[0] - 1][5] = $value[5];/*5 pane*/
						$this->rule[$value[0] - 1][6] = $value[6];/*score*/
					}
				}
			}
		}
		
		function getRuleInfo($mId, $mItem){
			if($mId > 0 && $mId <= 25)
			{
				$ret_res = array();
				if($mItem == -1)
					return $this->rule[$mId - 1];
				else if($mItem < 7)
					return $this->rule[$mId - 1][$mItem];
			}
			else if($mId == -1)
			{
				$ret_res = array();
				if($mItem == -1)
				{
					foreach($this->rule as $value)
						array_push($ret_res, $value);
				}
				else if($mItem < 7)
				{
					foreach($this->rule as $value)
						array_push($ret_res, $value[$mItem]);
				}
				else
					return null;
				return $ret_res;
			}
			return null;
		}
	}
?>
