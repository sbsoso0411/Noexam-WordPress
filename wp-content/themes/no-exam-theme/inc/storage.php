<?php
	session_start();
	
	// work with storage
	$path = getcwd()."/wp-content/themes/no-exam-theme/storage";
	$fileID = session_id();

	// sbli and sagicor
	if ($_POST['page'] == 1 ){
		// file_put_contents('storage_txt.txt',$_POST['page'] );
		if (!file_exists($path."/".$fileID.".txt")){
			CreateStorageFileWithID($path,$fileID);
			CreateFileWithPrevPageID($path,$fileID);
		}
	}
	// die(var_dump($_POST['page']));
	// file_put_contents('storage_txt.txt',$_POST['page'] );
	//vars to storage
	switch ($_POST['page']){
		// apply-now
		case 1 :
		    if (file_exists($path."/".$fileID.".txt")){
			    $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID),true);
				// file_put_contents('storage_txt_ses.txt',$_SESSION);
            } else {
                DestroySessionData();
                header("Location: /");
                // file_put_contents('storage_txt_ses.txt',"destr");
            }
            break;
		// quote-result
		
		case 2 :	if (file_exists($path."/".$fileID.".txt")){
						if (ReadPrevPageID($path,$fileID) != 3){
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
						} else {
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
						}
						SaveCurrentPageID($_POST['page'],$path,$fileID);
					} else {
						DestroySessionData(); 
						header("Location: /");
					}
					break;
					
		// medical-questions
		case 3 : 	if (file_exists($path."/".$fileID.".txt")){
						if (ReadPrevPageID($path,$fileID) != 4){
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
						} else {
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
						}
						SaveCurrentPageID($_POST['page'],$path,$fileID);
					} else {
						DestroySessionData(); 
						header("Location: /");
					}
					break;
					
		// additional-questions
		case 4 :	if (file_exists($path."/".$fileID.".txt")){
						if (ReadPrevPageID($path,$fileID) != 5){
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
							if (json_last_error() == 4){
								header("Location: /additional-questions/");
							}
						} else {
							$_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
						}
						SaveCurrentPageID($_POST['page'],$path,$fileID);
					} else {
						DestroySessionData(); 
						header("Location: /");
					}
					break;
        // additional-questions-2
        case 5:
            if (file_exists($path."/".$fileID.".txt")){
                if (ReadPrevPageID($path,$fileID) != 6){
                    $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
                    if (json_last_error() == 4){
                        header("Location: /additional-questions-2/");
                    }
                } else {
                    $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
                }
                SaveCurrentPageID($_POST['page'],$path,$fileID);
            } else {
                DestroySessionData();
                header("Location: /");
            }
            break;
					
		// request
		case 6:
		    if (file_exists($path."/".$fileID.".txt")){
                if (ReadPrevPageID($path,$fileID) != 7){
                    $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID), true);
                    if (json_last_error() == 4){
                        header("Location: /request/");
                    }
                } else {
                    $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID), true);
                }
                SaveCurrentPageID($_POST['page'],$path,$fileID);
            } else {
                DestroySessionData();
                header("Location: /");
            }
            break;
		// payment
		case 7:
		    if (file_exists($path."/".$fileID.".txt")){
                $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID), true);
                if (json_last_error() == 4){
                    header("Location: /payment/");
                }
            } else {
                DestroySessionData();
                header("Location: /");
            }
            SaveCurrentPageID($_POST['page'],$path,$fileID);
            break;
					
		// request-app
		case 8:
		    if (file_exists($path."/".$fileID.".txt")){
                $_SESSION = json_decode(ReadDataFromStorageFile($path,$fileID));
                if (json_last_error() == 4){
                    header("Location: /request-app/");
                }
                SaveCurrentPageID($_POST['page'],$path,$fileID);
            } else {
                DestroySessionData();
                header("Location: /");
            }
            break;
		// under-review
		case 9 :
		    $_POST['page'] = "9";
			    break;
	}
?>

