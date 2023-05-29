<?php

require_once("sql/sqlquery.php");

function drawImage($panId){
	$imgList = getImageList();
	
	foreach ($imgList as $imgName)
	{
		$imagPath = "img/";
		$imagPath .= $imgName;
		list($width, $height, $type, $attr) = getimagesize($imagPath);
		if($height > $width)
		{
			$height = (140 * 80) / 100;
			$top_padding = (140 - $height) / 2;
		}
		else
		{
			$height = (140 * 80) / (100 * $width) * $height;
			$top_padding = (140 - $height) / 2;
		}
		echo '<li><img src="'.$imagPath.'" style="height:'.$height.'px; margin-top:'.$top_padding.'px;"/ ></li>';
	}
}

function getImageList(){
	$gConf = getDBConf();
	$ret_res = array();
	$rand_res = array();
	
	$ret_res = $gConf->getSymbolPath(-1);
	gen_rand($ret_res, $rand_res);
	return $rand_res;
}

function gen_rand($arr,&$rand_arr)
{
	if(!count($arr))
		return;
	if(count($arr) == 1)
		return $arr[0];
	srand(make_seed());
	$index	= rand(0,count($arr) - 1);
	$item	= $arr[$index];
	array_splice($arr,$index,1);
	$rand_arr[count($rand_arr)] = $item;
	gen_rand($arr,$rand_arr);
}

function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}

?>
