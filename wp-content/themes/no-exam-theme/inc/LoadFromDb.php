<?php
function PostToDB($data)
{
	global $wpdb;
	// print_r($data);
	$age = calculate_age($data['Birthday']);
	switch ($data['Sex']) {
		case 'M':
		$sex = 'male';
		break;
		case 'F':
		$sex = 'female';
		break;
	}

	switch ($data['Smoker']) {
		case 'Y':
		$Smoker = 'T';
		break;
		case 'N':
		$Smoker = 'NT';
		break;
	}
	$Health = $data['Health']; 
	if($data['Health']=='RP'){
		$Health ='S';
	}
	switch ($data['Cat']) {
		case '5':
		$table_name = 'wp_sagicor_20';
		break;
		case '4':
		$table_name = 'wp_sagicor_15';
		break;
		case '3':
		$table_name = 'wp_sagicor_10';
		break;
		
	}
	$sql = "SELECT * FROM `".$table_name."` WHERE `sex`='".$sex."' AND `age`='".$age."' AND `benefits`='".$data['FaceAmount']."' AND `type`='".$Health.$Smoker."'";
	$result = $wpdb->get_results($sql);
	// var_dump($Health);
	return $result;
	
}

function calculate_age($birthday) {
	$birthday_timestamp = strtotime($birthday);
	$age = date('Y') - date('Y', $birthday_timestamp);
	if (date('md', $birthday_timestamp) > date('md')) {
		$age--;
	}
	return $age;
}

//naNA company calculalte age in nearest way
function calculate_na_age($birthday) {

    $interval = date_diff(date_create(), date_create($birthday.' 00:00:00'));
    $age = $interval->format('%Y');
    if ($interval->format('%M') > 6) {
        $age++;
    }
    return $age;
}

?>