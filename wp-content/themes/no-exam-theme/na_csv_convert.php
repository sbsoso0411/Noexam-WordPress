<?php /* Template Name: CSV Page */ ?>

<?php
/**
 * The main template file.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */

include("header.php");
// Helpers
require("inc/helpers.php");
require("inc/LoadFromDb.php");

// Storage
require("inc/storage.php");

// config variables
require_once("inc/config_variables.php");

// returns the na company record filtered by age, type, sex
function getNAPrices($data){
    global $wpdb;
    global $DISALLOWED_STATES;

    if (in_array($data['state'], $DISALLOWED_STATES['na'], true)) {
        return [];
    }

    $table_name = 'wp_na_prices';

    $sql = "SELECT * FROM $table_name  WHERE `age` = " .$data['age']. "  and `type` = '".$data['type']. "'  and `sex` = '".$data['sex']."'";
    $result = $wpdb->get_results($sql);
    return $result;

}

// Get Annual Price: NA
function getNAPrice($na_price, $band, $duration, $FaceAmount) {

    // $1.20 * 250 * 1 + $65
    // 250: $FaceAmount / 1000, $65: annual

    $price = 0;
    if ($band == 1) {
        $price = $na_price->band1;
    }
    else if ($band == 2) {
        $price = $na_price->band2;
    }
    else if ($band == 3) {
        $price = $na_price->band3;
    }
    else {
        return 0;
    }


    if ($duration == 'year') {
        return ($FaceAmount / 1000.0 * $price * 1 + 65);
    }
    else if ($duration == 'semi') {
        return ($FaceAmount / 1000.0 * $price * 0.522 + 33.93);
    }
    else if ($duration == 'quarter') {
        return ($FaceAmount / 1000.0 * $price * 0.274 + 17.81);
    }
    else if ($duration == 'month') {
        return ($FaceAmount / 1000.0 * $price * 0.088 + 5.72);
    }

}


// generate na rate class name
function getNARateClassName($type) {
    if ($type == 'PP') {
        return 'Preferred Plus'; // Super Plus = Referred Plus
    }
    else if ($type == 'P') {
        return 'Preferred';
    }
    else if ($type == 'NT') {
        return 'Standard Non Tobacco';
    }
    else if ($type == 'SSNT') {
        return 'Sub Standard Non Tobacco';
    }
    else if ($type == 'PT') {
        return 'Preferred Tobacco';
    }
    else if ($type == 'T') {
        return 'Tobacco';
    }
    else if ($type == 'SST') {
        return 'Sub Standard Tobacco';
    }

    return 'Unknown';
}

// get rate for NA
function getNARate($data) {
    global $wpdb;

    $table_name = 'wp_na_rates';

    $sql = "SELECT * FROM $table_name  WHERE `foot` = " .$data['Height_ft']. "  and `inch` = ".$data['Height_in']. "  and `sex` = '".$data['sex']."'";
    $result = $wpdb->get_results($sql);


    if (!$result) {
        return null;
    }

    if ($data['smoker'] == 'N') {
        if ($data['weight'] >= $result[0]->sp_lo && $data['weight'] <= $result[0]->sp_hi) {
            return 'PP'; //super preferred, preferred plus
        }
        else if ($data['weight'] >= $result[0]->p_lo && $data['weight'] <= $result[0]->p_hi) {
            return 'P';
        }
        else if ($data['weight'] >= $result[0]->snt_lo && $data['weight'] <= $result[0]->snt_hi) {
            return 'NT';
        }
        else if ($data['weight'] >= $result[0]->ssnt_lo && $data['weight'] <= $result[0]->ssnt_hi) {
            return 'SSNT';
        }
    }
    else{
        if ($data['weight'] >= $result[0]->sp_lo && $data['weight'] <= $result[0]->p_hi) {
            return 'PT';
        }
        else if ($data['weight'] >= $result[0]->snt_lo && $data['weight'] <= $result[0]->snt_hi) {
            return 'T';
        }
//        else if ($data['weight'] >= $result[0]->ssnt_lo && $data['weight'] <= $result[0]->ssnt_hi) {
//            return 'SST';
//        }
    }

    return '';

}

// Get NA Band
function getNABand($amount) {
    if ($amount >= 50000 && $amount <= 99999) {
        return 1;
    }
    else if ($amount >= 100000 && $amount <= 249999) {
        return 2;
    }
    else if ($amount >= 250000 && $amount <= 500000) {
        return 3;
    }


    return 0;
}


//require_once("inc/LoadFromDb.php");
$csv = array_map('str_getcsv', file('c:/na-csv.csv'));
array_walk($csv, function(&$a) use ($csv) {
    $a = array_combine($csv[0], $a);
});
array_shift($csv); # remove column header

// now it contains csv file as array like this
// [2] => Array
//    (
//        [Campaign ID] => 295095038
//        [Ad group ID] => 22460178158
//        [Keyword ID] => 3993587178

$years = [15, 20, 30];
$amounts = [100 * 1000, 250*1000, 500*1000];

$values = [];

foreach ($csv as $client) {

    // get feet and inch from one column
    $feet = substr($client['Height'], 0, 1);  // feet
    $inch = substr($client['Height'], 2, 1);  // inch
    $sex = substr($client['Gender'], 0, 1); // sex
    $weight = $client['Weight'];
    $smoker = $client['Tobacco Use'];
    $na_age = calculate_na_age($client['Date of Birth']);
    $state = $client['State'];

    $na_rate = getNARate(array ('Height_ft' => $feet, 'Height_in' => $inch, 'sex' => $sex,
        'weight' => $weight, 'smoker' => $smoker,));

    if ($na_rate != '') {
        //get na prices, it is using an independent age measurement system
        $na_prices = getNAPrices(array('sex' => $sex, 'type' => $na_rate, 'age' => $na_age, 'state' => $state));

        $na_rate_class = getNARateClassName($na_rate);

        // fill monthly value
        $client['100k 15yr'] = getNAPrice($na_prices[0], 2, 'month', 100 * 1000); // 100K: band2
        $client['250k 15yr'] = getNAPrice($na_prices[0], 3, 'month', 250 * 1000); // 250K: band3
        $client['500k 15yr'] = getNAPrice($na_prices[0], 3, 'month', 500 * 1000); // 500K: band3

        $client['100k 20yr'] = getNAPrice($na_prices[1], 2, 'month', 100 * 1000); // 100K: band2
        $client['250k 20yr'] = getNAPrice($na_prices[1], 3, 'month', 250 * 1000); // 250K: band3
        $client['500k 20yr'] = getNAPrice($na_prices[1], 3, 'month', 500 * 1000); // 500K: band3

        $client['100k 30yr'] = getNAPrice($na_prices[2], 2, 'month', 100 * 1000); // 100K: band2
        $client['250k 30yr'] = getNAPrice($na_prices[2], 3, 'month', 250 * 1000); // 250K: band3
        $client['500k 30yr'] = getNAPrice($na_prices[2], 3, 'month', 500 * 1000); // 500K: band3
    }
    else {
        $client['100k 15yr'] = 0; // 100K: band2
        $client['250k 15yr'] = 0; // 250K: band3
        $client['500k 15yr'] = 0; // 500K: band3

        $client['100k 20yr'] = 0; // 100K: band2
        $client['250k 20yr'] = 0; // 250K: band3
        $client['500k 20yr'] = 0; // 500K: band3

        $client['100k 30yr'] = 0; // 100K: band2
        $client['250k 30yr'] = 0; // 250K: band3
        $client['500k 30yr'] = 0; // 500K: band3
    }

    $values[] = $client;
//    if ($client['Email'] == 'cherokee.mcneil@yahoo.com') {
//        var_dump($feet);
//        var_dump($inch);
//        var_dump($sex);
//        var_dump($weight);
//        var_dump($smoker);
//        var_dump($na_age);
//        var_dump($na_rate);
//        echo 111;
//        var_dump($client);
//        die();
//    }
}

$fp = fopen('c:/na-csv-output.csv', 'w');
foreach ($values as &$value) {
    fputcsv($fp, $value);
}

fclose($fp);