<?php
	require_once("manage.php");
	
	switch ($_REQUEST['type'])
	{
		case "1" :
			$data = $_REQUEST['data'];
			$lcredit = $_REQUEST['score'];
			echo onClickSpinButton($data, $lcredit);
			break;
		case "2":
			$data = $_REQUEST['data'];
			echo onClickLineButton($data);
			break;
		case "3":
			$name = $_REQUEST['name'];
			$pwd = $_REQUEST['pwd'];
			echo onClickLogInButton( $name, $pwd );
			break;
		case "4":
			echo getScoreItems();
			break;
		case "5":
			echo checkUser();
			break;
		case "6":
			session_destroy();
			break;
		case "7":
			$data = $_REQUEST['data'];
			echo procDonation($data);
			break;
		case "8":
			echo isAbleDonate();
	}
	
	function onClickSpinButton($selLines, $lcredit)
	{
		$gMng = new Manager($selLines, 0);
		return $gMng->procPlacement($lcredit);
	}
	
	function onClickLineButton($selLine)
	{
		$gMng = new Manager($selLines, 0);
		return $gMng->getLineRule($selLine + 1);
	}
	
	function onClickLogInButton( $strName, $strPwd )
	{
		$gMng = new Manager(null, 0);
		$id = $gMng->procUserLogIn($strName, $strPwd);
		if($id == -1)
			return -1;
			
		session_start();
		$_SESSION['id'] = $id;
		return 'index01.php';
	}
	
	function getScoreItems(){
		$gMng = new Manager(null, 0);
		if(empty($_SESSION['id']))
			return 0;
		$id = $_SESSION['id'] * 1;
		$result = $gMng->procScoreItem($id);
		return $result;
	}
	
	function checkUser(){
		if($_SESSION['id'] != 0)
			return 0;
		return 'index.php';
	}
	
	function procDonation($data){
		if($_SESSION['id'] == 0 || $data * 1 > 2000 || $data * 1 <= 0)
			return 0;
		/*update*/
		$gMng = new Manager(null, 0);
		return $gMng->updateDonation($_SESSION['id'] * 1, $data * 1);
	}
	
	function isAbleDonate(){
		if($_SESSION['id'] == 0)
			return 0;
		$gMng = new Manager(null, 0);
		return $gMng->isAbleDonate($_SESSION['id'] * 1);
	}
?>