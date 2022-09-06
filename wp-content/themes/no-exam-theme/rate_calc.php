<?php
/**
 * Template Name: RateCalc
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
require("data/health-rate.php");

$ft = $_POST['ft'];
$in = $_POST['inches'];
$height = ((12*$ft) + $in);
$weight = $_POST['weight'];
$err = 1;
foreach($Health_rate as $key => $rate_info)
{
	if ($height == $key)
	{	
		$err = 0;
		$counter = count($rate_info);
		if ($weight < $rate_info[0][1] or $weight >= $rate_info[$counter-1][2])
		{
			echo json_encode("Please enter correct weigth");
			break;
		} 
		else 
		{
			for ($i = 0; $i < $counter; $i++)
			{
				if ($weight >= $rate_info[$i][1] and $weight < $rate_info[$i][2])
				{
					echo json_encode($rate_info[$i][0]);
					break;
				}
			}
		}
	}
}

if ($err == 1){
	echo json_encode("Please enter correct height");
}
?>	