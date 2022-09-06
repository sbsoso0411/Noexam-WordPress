<?php
	/**
	 * Template Name: QuoteReload
	 * Description: A Page Template that displays at full width
	 *
	 * @package Hellish Simplicity
	 * @since Hellish Simplicity 1.4
	 */
	// includes
	require("helpers.php");
	
	// var
	$path = getcwd()."/wp-content/themes/no-exam-theme/storage";
	$UserSessionId = $_POST['userID'];
	$currentFaceAmount = $_POST['currentFaceAmount'];
	$currentHeight_ft = $_POST['currentHeight_ft'];
	$currentHeight_in = $_POST['currentHeight_in'];
	$currentweight = $_POST['currentweight'];
	$currentHealth = $_POST['currentHealth'];
	// die(print_r($_POST));
	// die(var_dump($currentHealth));
	// code
	$_CURRENT_DATA = json_decode(ReadDataFromStorageFile($path,$UserSessionId),true);
	if (json_last_error() == 4){
		$_CURRENT_DATA = json_decode(ReadDataFromStorageFile($path,$UserSessionId),true);
	}
	$_CURRENT_DATA['fFaceAmount'] = $currentFaceAmount;
	$_CURRENT_DATA['fHeight_ft'] = $currentHeight_ft;
	$_CURRENT_DATA['fHeight_in'] = $currentHeight_in;
	$_CURRENT_DATA['fweight'] = $currentweight;
	$_CURRENT_DATA['fHealthClass'] = $currentHealth;
	
	$data = json_encode($_CURRENT_DATA);
	SaveDataToStorageFile($data, $path, $UserSessionId);
?>