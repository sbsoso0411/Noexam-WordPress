<?php
/**
 * Template Name: lead sender
 */
 
require("inc/helpers.php");
 
	function PostToHost($host, $path, $data)
	{
		$fp = fsockopen($host, 80);
		$rf1 = @$_SERVER['HTTP_HOST']; 
		$rf2 = @$_SERVER['SCRIPT_NAME']; 
		$rf3 = @$_SERVER['SCRIPT_FILENAME']; 

		fputs($fp, "POST $path HTTP/1.1\n");
		fputs($fp, "Host: $host\n");
		fputs($fp, "Referer: $rf1$rf2\r\n"); 
		fputs($fp, "Ref2: $rf3\r\n"); 
		fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		fputs($fp, "Content-length: ".strlen($data)."\n");
		fputs($fp, "Connection: close\n\n");
		fputs($fp, $data);

		$y=0;
		while(!feof($fp))
		{
			$datavalue=fgets($fp, 1280);
			if(preg_match("/VAMdB/", $datavalue)) 
			{
				$result[$y]=$datavalue;
				$y++;
			}
		}
		fclose($fp);
		return $result;
	}
 
	$HealthQ = HealthClass2Health($_POST['ajax_hc']);
	$NewCategory1 = 1;

	$host = "www.mscc2.com";
	$path = "/HollowayJ/request-quote.php";
	$data = "height=".$_POST['ajax_oh']."&weight=".$_POST['ajax_we']."&BirthMonth=".$_POST['ajax_bm']."&Birthday=".$_POST['ajax_bd']."&BirthYear=".$_POST['ajax_by']."&Sex=".$_POST['ajax_sx']."&State=".$_POST['ajax_st']."&Smoker=".$_POST['ajax_sm']."&Health=".$HealthQ."&FaceAmount=".$_POST['ajax_fa']."&Catagory=".$NewCategory1."&ModeUsed=".$_POST['ajax_mu']."&HomePhone=".$_POST['ajax_hp']."&Email=".$_POST['ajax_em']."&FirstName=".$_POST['ajax_fn']."&LastName=".$_POST['ajax_ln'];
	$res = PostToHost($host, $path, $data);
	
	die();
	
	//echo json_encode($_POST['ajax_fn']);
?>	