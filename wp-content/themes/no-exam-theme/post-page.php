<?php
/**
 * Template Name: post-page-new
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

require("inc/helpers.php");
require_once("inc/LoadFromDb.php");

//questions
require("data/questions.php");

// apply temp data to data
function ApplyFromTempData(&$_DATA, &$_TEMPDATA) {

    // set some fields from storage json file

    if (!$_DATA['fSex']) {
        $_DATA['fSex'] = $_TEMPDATA['fSex'];
    }
    if (!$_DATA['fHeight_ft']) {
        $_DATA['fHeight_ft'] = $_TEMPDATA['fHeight_ft'];
    }
    if (!$_DATA['fHeight_in']) {
        $_DATA['fHeight_in'] = $_TEMPDATA['fHeight_in'];
    }
    if (!$_DATA['fweight']) {
        $_DATA['fweight'] = $_TEMPDATA['fweight'];
    }
    if (!$_DATA['fFaceAmount']) {
        $_DATA['fFaceAmount'] = $_TEMPDATA['fFaceAmount'];
    }
    if (!$_DATA['fSmoker']) {
        $_DATA['fSmoker'] = $_TEMPDATA['fSmoker'];
    }
    if (!$_DATA['fcompany']) {
        $_DATA['fcompany'] = $_TEMPDATA['fcompany'];
    }
    if (!$_DATA['frateclass']) {
        $_DATA['frateclass'] = $_TEMPDATA['frateclass'];
    }
    if (!$_DATA['fannualpremium']) {
        $_DATA['fannualpremium'] = $_TEMPDATA['fannualpremium'];
    }
    if (!$_DATA['fpremium']) {
        $_DATA['fpremium'] = $_TEMPDATA['fpremium'];
    }
    if (!$_DATA['fgclid_field']) {
        $_DATA['fgclid_field'] = $_TEMPDATA['fgclid_field'];
    }
    if (!$_DATA['fterm']) {
        $_DATA['fterm'] = $_TEMPDATA['fterm'];
    }
    if (!$_DATA['fBirthMonth']) {
        $_DATA['fBirthMonth'] = $_TEMPDATA['fBirthMonth'];
    }
    if (!$_DATA['fBirthday']) {
        $_DATA['fBirthday'] = $_TEMPDATA['fBirthday'];
    }
    if (!$_DATA['fBirthYear']) {
        $_DATA['fBirthYear'] = $_TEMPDATA['fBirthYear'];
    }
    if (!$_DATA['fAge']) {
        $_DATA['fAge'] = $_TEMPDATA['fAge'];
    }
    if (!$_DATA['fNaAge']) {
        $_DATA['fNaAge'] = $_TEMPDATA['fNaAge'];
    }
}

session_start();

// work with storage
$path = getcwd()."/wp-content/themes/no-exam-theme/storage";
$fileID = session_id();

if (!file_exists($path."/".$fileID.".txt")){
    CreateStorageFileWithID($path,$fileID);
    CreateFileWithPrevPageID($path,$fileID);
}

//Post vars to session vars
foreach($_POST as $key => $value){
    $_DATA[$key] = unQuote($_POST[$key]);
};

$data = json_encode($_DATA);

//function time_elapsed_A($secs){
//    $bit = array(
//        'y' => $secs / 31556926 % 12,
//        'w' => $secs / 604800 % 52,
//        'd' => $secs / 86400 % 7,
//        'h' => $secs / 3600 % 24,
//        'm' => $secs / 60 % 60,
//        's' => $secs % 60
//    );
//    $ret = [];
//    foreach($bit as $k => $v)
//        if($v > 0)$ret[] = $v . $k;
//
//    return join(' ', $ret);
//}


// page controller
switch ($_POST['pageID']){
    case 0:
    case 1:

        // calculate age and store it as well

        $_DATA['fAge'] = calculate_age($_DATA['fBirthYear'].'-'.$_DATA['fBirthMonth'].'-'.$_DATA['fBirthday']);
        $_DATA['fNaAge'] = calculate_na_age($_DATA['fBirthYear'].'-'.$_DATA['fBirthMonth'].'-'.$_DATA['fBirthday']);
        $data = json_encode($_DATA);

        SaveDataToStorageFile($data,$path,$fileID);

        if ($_POST['pageID']=='1') {
            //update crm info
            initializeBaseCrmContactAndDeal(null);
        }

        header("Location: /noexam/quote-results/");
        break;

    case 2:

        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));

        ApplyFromTempData($_DATA, $_TEMPDATA);
        SaveDataToStorageFile($data,$path,$fileID);

        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLastName']);

        header("Location: /medical-questions/");
        break;

    case 3:
        // this is for medical-question submit
        $_POST['page'] = "4";
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));

        ApplyFromTempData($_DATA, $_TEMPDATA);
        SaveDataToStorageFile($data,$path,$fileID);

        // if it is sagicor and one of first page application = Yes,
        // decline crm deal to lost and reason, Declined Through Kill Questions
        if ( !stripos($_TEMPDATA['fcompany'], 'Sagicor')) {
            if ($_DATA['fanswer1'] == 'Yes' || $_DATA['fanswer2'] == 'Yes' ||
                $_DATA['fanswer3'] == 'Yes' || $_DATA['fanswer4'] == 'Yes' ||
                $_DATA['fanswer5'] == 'Yes' || $_DATA['fanswer6'] == 'Yes') {

                rejectBaseCrmDeal($_TEMPDATA['fEmail'], $_TEMPDATA['fFirstName'], $_TEMPDATA['fLastName']);
                header("Location: /under-review/");
                break;
            }
        }

        // update pdf file and send to dropbox
        $leadtype = 27;
        SendToVamDB($fileID,$leadtype,$Questions['sagicor']);
        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLastName']);

        header("Location: /additional-questions/");
        break;

    case 4:
        // this is for additional-questions submit
        $_POST['page'] = "5";
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));

        SaveDataToStorageFile($data,$path,$fileID);

        // if it is sagicor and one of first page application = Yes,
        // decline crm deal to lost and reason, Declined Through Kill Questions
//        if ( !stripos($_TEMPDATA->fcompany, 'Sagicor')) {
//            if ($_DATA['fanswer1'] == 'Yes' || $_DATA['fanswer2'] == 'Yes' ||
//                $_DATA['fanswer3'] == 'Yes' || $_DATA['fanswer4'] == 'Yes' ||
//                $_DATA['fanswer5'] == 'Yes' || $_DATA['fanswer6'] == 'Yes' ||
//                $_DATA['fanswer9'] == 'Yes' || $_DATA['fanswer10'] == 'Yes') {
//                rejectBaseCrmDeal($_TEMPDATA['fEmail']);
//            }
//        }

        // update pdf file and send to dropbox
        $leadtype = 28;
        ApplyFromTempData($_DATA, $_TEMPDATA);
        SendToVamDB($fileID,$leadtype,$Questions['sagicor']);
        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLirstName']);

        header("Location: /additional-questions-2/");
        break;

    case 5:
        // this is for additional-questions-2 submit
        $_POST['page'] = "6";
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        SaveDataToStorageFile($data,$path,$fileID);

        // update pdf file and send to dropbox
        $leadtype = 29;
        ApplyFromTempData($_DATA, $_TEMPDATA);
        SendToVamDB($fileID,$leadtype,$Questions['sagicor']);
        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLirstName']);

        header("Location: /request/");
        break;

    case 6:
        // this is for /request submit
        $_POST['page'] = "7";
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID), true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        SaveDataToStorageFile($data,$path,$fileID);

        // set some fields from storage json file
        ApplyFromTempData($_DATA, $_TEMPDATA);

        // crm update
        initializeBaseCrmContactAndDeal('PARTIAL_APPLICATION');

        // update pdf file and send to dropbox
        $leadtype = 30;
        SendToVamDB($fileID,$leadtype,$Questions['sagicor']);

        header("Location: /payment/");
        break;

    case 7:
        $_POST['page'] = "8";
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID), true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        SaveDataToStorageFile($data,$path,$fileID);

        //call crm completion
        completeBaseCrm($_TEMPDATA['fEmail'], $_TEMPDATA['fname'], $_TEMPDATA['lname']);

        //prepare pdf and update payment info in pdf file
        $leadtype = 26;
        SendToVamDB($fileID,$leadtype,$Questions['sagicor']);
        header("Location: /request-app/");
        break;

    case 100:
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        // set some fields from storage json file
        ApplyFromTempData($_DATA, $_TEMPDATA);
        SaveDataToStorageFile($data,$path,$fileID); //na-app

        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLirstName']);

        header("Location: /sbli-confirmation/");
        break;

    case 101:
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        SaveDataToStorageFile($data,$path,$fileID);

        // set some fields from storage json file
        ApplyFromTempData($_DATA, $_TEMPDATA);

        initializeBaseCrmContactAndDeal(null);
        completeBaseCrm($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLastName']);
        header("Location: /sbli-app/");
        break;

    case 200:
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));
        // set some fields from storage json file
        ApplyFromTempData($_DATA, $_TEMPDATA);
        SaveDataToStorageFile($data,$path,$fileID); //na-app

        // update crm contact and deal if they already exists
        updateBaseCrmContactAndDeal($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLirstName']);

        header("Location: /na-confirmation/");
        break;

    case 201:
        $_TEMPDATA = json_decode(ReadDataFromStorageFile($path,$fileID),true);
        $data = json_encode(UpdatePostData($_TEMPDATA));

        // set some fields from storage json file
        ApplyFromTempData($_DATA, $_TEMPDATA);
//        $oldtime = time();

        SaveDataToStorageFile($data,$path,$fileID);

//        $newtime = time();
//        echo "<br/>time_elapsed_A: ".time_elapsed_A($newtime-$oldtime)."<br/>";

//        $oldtime = time();
        initializeBaseCrmContactAndDeal(null);

        $newtime = time();
//        echo "<br/>time_elapsed_A: ".time_elapsed_A($newtime-$oldtime)."<br/>";

//        $oldtime = time();
        completeBaseCrm($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLastName']);
//        $newtime = time();
//        echo "<br/>time_elapsed_A: ".time_elapsed_A($newtime-$oldtime)."<br/>";

        header("Location: /na-app/");
        break;
}

?>
