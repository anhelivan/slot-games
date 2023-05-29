<?php

require_once("sql/sqlquery.php");

	Class Manager{
		var $EntryPerLine			= 0;
		var $selLineIDs				= null;
		
		function Manager($mLines, $mEntry){
			$this->selLineIDs = $mLines;
			$this->EntryPerLine = $mEntry;
		}
		/**/
		function procPlacement($lcredit){
			$sumScore = 0;
			$selRule = 0;
			$sqlConf = getDBConf();
			$selList = $sqlConf->getScoreInfo(count($this->selLineIDs));
			$Rules = array();
			if(empty($_SESSION['id']))
				return 0;

			if($selList != 0)
			{
				foreach ($selList as $value)
					$sumScore += $value[1];
					
				foreach ($this->selLineIDs as $value)
					array_push($Rules, $sqlConf->getRulInfo($value));
					
				$Rules = $this->selectGameRule($sumScore, $Rules);
				$mPlace = $this->makeSymbolPlace($Rules);
				$mPlace = $this->getImagePath($mPlace);
				$mResult['place'] = $mPlace;
				$mResult['win'] = $Rules;
				
				$sqlConf->updateUserScore($_SESSION['id'], $Rules, count($this->selLineIDs), $lcredit);
				/* update database */
				return json_encode($mResult);
			}
			return 0;
		}
		
		function selectGameRule($mScore, $lst_Rules){
			$sumRuleScore = 0;
			$ret_res = array();
			$count = count($lst_Rules);
			
			if($mScore == 0 || $count == 0 )
				return $ret_res;
			if($count == 1)
			{
				if($mScore > $lst_Rules[0][6])
				{
					array_push($ret_res, $lst_Rules[0]);
					return $ret_res;
				}
				return $ret_res;
			}
			$i = 0;
			while(count($lst_Rules) != 0){
				$index	= rand(0, count($lst_Rules) - 1);
				if($mScore > $sumRuleScore + $lst_Rules[$index][6])
				{
					if( !$this->checkCollide($ret_res, $lst_Rules[$index]) )
					{
						$sumRuleScore += $lst_Rules[$index][6];
						array_push($ret_res, $lst_Rules[$index]);
						$i ++;
					}
				}
				array_splice($lst_Rules,$index, 1);
				if($i >= 3)
					break;
			}
			return $ret_res;
		}
		
		function checkCollide($mainList, $subValue){
			foreach($mainList as $value)
			{
				for( $i = 1; $i < 6; $i ++)
				{
					if($value[$i] == $subValue[$i])
						return true;
				}
			}
			return false;
		}
		
		function makeSymbolPlace(&$winRule){
			$sqlConf = getDBConf();
			$otherImgList = $sqlConf->getSymbolInfo(-1);
			
			if($winRule == 0 || count($winRule) == 0)
			{
				for($i = 0; $i < 5; $i++)
				{
					$mTemp = $otherImgList;
					for($j = 0; $j < 3; $j++)
					{
						$k = rand(0, (count($mTemp) - 1));
						$retPlace[$i][$j] = $mTemp[$k][0];
						if($i == 1)
							array_splice($otherImgList, $k + $j, 1);
						array_splice($mTemp, $k, 1);
					}
				}
			}
			else{
			
				for($i = 0; $i < count($winRule); $i ++)
				{
					$temp = rand(0, count($otherImgList) - 1);
					$winImgList[$i][0] = $winRule[$i][0]; //id
					$winImgList[$i][1] = $otherImgList[$temp][0];
					$winRule[$i][7] = $otherImgList[$temp][3];
					array_splice($otherImgList, $temp + 1, 1);
				}

				for($i = 0; $i < 5; $i++)
				{
					for($j = 0; $j < 3; $j++)
					{
						$rId = $this->checkWinPlace($i + 1, $j + 1, $winRule);
						if($rId)
						{
							foreach($winImgList as $value)
							{
								if($value[0] == $rId)
								{
									$retPlace[$i][$j] = $value[1];
									break;
								}
							}
						}
						else
						{
							$temp = rand(0, count($otherImgList) - 1);
							$retPlace[$i][$j] = $otherImgList[$temp][0];
						}
					}
				}
			}
			return $retPlace;
		}
		
		function checkWinPlace($pannum, $index, $winRule){
			for($i = 0; $i < count($winRule); $i++)
			{
				if($winRule[$i][$pannum] == $index)
					return $winRule[$i][0];
			}
			return 0;
		}
		
		function getImagePath($placeInfo){
			$htmlStr = array();
			$sqlConf = getDBConf();
			for($i = 0; $i < 5; $i++)
			{
				for($j = 0; $j < 3; $j++)
				{
					$imgPath = "../img/".$sqlConf->getSymbolPath($placeInfo[$i][$j]);
					if($imgPath == "../img/"){
					}
					else{
						list($width, $height, $type, $attr) = getimagesize($imgPath);
						if($height > $width)
						{
							$height = (int)((140 * 80) / 100);
							$top_padding = (int)((140 - $height) / 2);
						}
						else
						{
							$height = (int)((140 * 80) / (100 * $width) * $height);
							$top_padding = (int)((140 - $height) / 2);
						}
						$imgPath = "img/".$sqlConf->getSymbolPath($placeInfo[$i][$j]);
						$htmlStr[$i][$j] = ('<li><img src="'.$imgPath);
						$htmlStr[$i][$j].= ('" style="height:'.$height);
						$htmlStr[$i][$j].= ('px; margin-top:'.$top_padding.'px;"/ ></li>');
					}
				}
			}		
			return $htmlStr;
		}
		
		function getLineRule($iIndex){
			if($iIndex <= 0 || $iIndex >= 26)
				return 0;
			$sqlConf = getDBConf();
			$selList = $sqlConf->getRulInfo($iIndex);
			if($selList == null)
				return 0;
				
			return json_encode($selList);
		}
		
		function procUserLogIn($strName, $strPwd)
		{
			$sqlConf = getDBConf();
			return $sqlConf->verify_userlogin($strName, $strPwd);
		}
		
		function procScoreItem($id){
			$res = array();
			$sqlConf = getDBConf();
			$result = $sqlConf->getUserInfo($id);
			if($result == 0)
				return 0;
			$res[0] = $result[0][5];
			$res[1] = $result[0][6];
			$res[2] = $result[0][7];
			$res[3] = $result[0][8];
			$res[4] = $result[0][9];
			return json_encode($res);
		}
		
		function updateDonation($id, $value){
			$sqlConf = getDBConf();
			$result = $sqlConf->getUserInfo($id);
			if($result == 0)
				return 0;
			$sqlConf = getDBConf();
			return $sqlConf->updateDonation($id, $value);
		}
		
		function isAbleDonate($id){
			$sqlConf = getDBConf();
			$result = $sqlConf->getUserInfo($id);
			if($result == 0)
				return 0;
			return ($result[0][8] * 1) ? 0 : 1;
		}
	}
?>
