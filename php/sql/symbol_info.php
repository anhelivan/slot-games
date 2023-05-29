<?php
require_once("db_class.php");

	Class SymbolInfo{
		var $symbols		= array();
		function SymbolInfo(){
		}

		function initSymbolInfo($sqlConnect){
			if($sqlConnect)
			{
				$sql_result = $sqlConnect->getSymbolInfo(-1);
				if($sql_result)
				{
					foreach ( $sql_result as $value)
					{
						$this->symbols[$value[0] - 1][0] = $value[0];/*id*/
						$this->symbols[$value[0] - 1][1] = $value[1];/*path*/
						$this->symbols[$value[0] - 1][2] = $value[2];/*animation path*/
						$this->symbols[$value[0] - 1][3] = $value[3];/*name*/
					}
				}
			}
		}
		
		function getSymbolCount(){
			return count($this->symbols);
		}
		
		function getSymbolInfo($mId, $mItem){
			if($mId > 0 && $this->getSymbolCount() >= $mId )
			{
				if($mItem == -1)
					return $this->symbols[$mId - 1];
				else if($mItem < 4)
					return $this->symbols[$mId - 1][$mItem];
			}
			else if($mId == -1)
			{
				$ret_res = array();
				if($mItem == -1)
				{
					foreach($this->symbols as $value)
						array_push($ret_res, $value);
				}
				else if($mItem < 4)
				{
					foreach($this->symbols as $value)
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
