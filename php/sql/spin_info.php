<?php
require_once("db_class.php");
	Class SpinsInfo{
		var $spins			= array();
		function SpinsInfo(){
		}
		
		function getInfo($sqlConnect, $mCount){
			if(!$mCount || !$sqlConnect)
				return null;
			return $sqlConnect->getSpinsInfo($mCount);
		}
	}
?>
