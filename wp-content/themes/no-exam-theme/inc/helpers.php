<?php
// includes
	// PDF Library
	require(getcwd()."/wp-content/themes/no-exam-theme/lib/FPDF/fpdf.php");
	// PHPMailer
	require (getcwd()."/wp-content/themes/no-exam-theme/lib/PHPMailer/PHPMailerAutoload.php");
	// Questions array
	//require(getcwd()."/wp-content/themes/no-exam-theme/data/questions.php");

    //basecrm integration
    require_once getcwd()."/wp-content/themes/no-exam-theme/vendor/autoload.php";

    //include configration variables
    require_once getcwd()."/wp-content/themes/no-exam-theme/inc/config_variables.php";

// Functions
	// Un-quote string quoted
	/// unQuote(string)
	function unQuote($str)
	{
		return stripcslashes($str);
	}
	
	// HealthClass(preferred,standard etc.) -> Health(P,RP etc.)
	function HealthClass2Health($HealthClass)
	{
		switch ($HealthClass)
		{
			case "Preferred";
				return "P";
				break;
			case "Standard";
				return "RP";
				break;
			case "Rated";
				return "R";
				break;
		}

	}
	// HealthClass (preferred,standard) -> vamdb RateClass(preferred, standard)
	function HealthClass2RateClass($HealthClass)
	{
		switch ($HealthClass)
		{
			case "Preferred";
				return "Preferred";
				break;
			case "Standard";
				return "Standard";
				break;
			case "Rated";
				return "Rated";
				break;
		}
	}
	// VamDB term(1,2,3,4...) -> Text term (10 Years...)
	function TermVamdb2Term($term)
	{
		switch ($term)
		{
			case "3";
				return "10 Years";
				break;
			case "4";
				return "15 Years";
				break;
			case "5";
				return "20 Years";
				break;
			case "7";
				return "30 Years";
				break;				
		}
	}
	// Create storage file ID = session id
	function CreateStorageFileWithID($path,$fileID){
		$fileName = $path."/".$fileID.".txt";
		$storageFile = fopen($fileName,"w");
		fclose($storageFile);
	}
	// Create file with ID of prev page
	function CreateFileWithPrevPageID($path,$fileID){
		$fileName = $path."/temp_".$fileID.".txt";
		$tempFile = fopen($fileName,"w");
		fclose($tempFile);
	}
	
	// Saving user data to storage file
	function SaveDataToStorageFile($data,$path,$fileID){
		$fileName = $path."/".$fileID.".txt";
		if (file_exists($fileName)){
			$storageFile = fopen($fileName,"w");
			fwrite($storageFile, $data);
			fclose($storageFile);
		}
	}
	// Saving current id to temp file
	function SaveCurrentPageID($ID,$path,$fileID){
		$fileName = $path."/temp_".$fileID.".txt";
		if (file_exists($fileName)){
			$tempFile = fopen($fileName,"w");
			fwrite($tempFile,$ID);
			fclose($tempFile);
		}
	}
	
	// Reading user data from storage file
	function ReadDataFromStorageFile($path,$fileID){
		$fileName = $path."/".$fileID.".txt";
		if (file_exists($fileName)){
//			$byteLength = filesize($fileName);
//			$storageFile = fopen($fileName,"r");
//			$data = fgets($storageFile, $byteLength+1);
//			fclose($storageFile);
            return file_get_contents($fileName);
//			return $data;
		} else {
			return 0;
		}
	}
	
	// Read prev ID from temp file
	function ReadPrevPageID($path,$fileID){
		$fileName = $path."/temp_".$fileID.".txt";
		if (file_exists($fileName)){
			$byteLength = filesize($fileName);
			$tempFile = fopen($fileName,"r");
			$ID = fgets($tempFile, $byteLength+1);
			fclose($tempFile);
			return $ID;
		}
	}
	
	function SetUnsetFlag(){
		$file = getcwd()."/wp-content/themes/no-exam-theme/data/flag.txt";
		if (file_exists($file)){
			$value = GetFlag();
			switch ($value){
				case 1  :	$val = 0;
							$temp = fopen($file, "w");
							fwrite($temp, $val);
							fclose($temp);
							break;
				case 0	:	$val = 1;
							$temp = fopen($file, "w");
							fwrite($temp, $val);
							fclose($temp);
							break;
			}
		}
	}
	
	function GetFlag(){
		$file = getcwd()."/wp-content/themes/no-exam-theme/data/flag.txt";
		if (file_exists($file)){
			$byteLength = filesize($file);
			$temp = fopen($file, "r");
			$value = fgets($temp, $byteLength+1);
			fclose($temp);
			return $value;
		}
	}
	
	// Delete storage file
	function DeleteStorageFile($path,$fileID){
		if (file_exists($path."/".$fileID.".txt")){
			unlink($path."/".$fileID.".txt");
		}
		if (file_exists($path."/temp_".$fileID.".txt")){
			unlink($path."/temp_".$fileID.".txt");
		}
	}
	// update post data
	function UpdatePostData($_TEMPDATA){
		if (is_array($_POST)){
			foreach ($_POST as $key => $value){
				 $_TEMPDATA[$key] = unQuote($_POST[$key]);
			};
		}
		return $_TEMPDATA;
	}
	// post to vamdb
	function PostToVamDB($host, $path, $data_to_send){
		 $posturl = "ssl://".$host;
		 $fp = fsockopen($posturl, 443, $errno, $errstr, 30);
		 fputs($fp, "POST $path HTTP/1.1\n");
		 fputs($fp, "Host: $host\n");
		 fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		 fputs($fp, "Content-length: ".strlen($data_to_send)."\n");
		 fputs($fp, "Connection: close\n\n");
		 fputs($fp, $data_to_send);
		 fclose($fp);
	}
	
	// GUID of PDF file
	function getGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"
			return $uuid;
		}
	}
	
	// Send data to vamdb
	function SendToVamDB($sessionId, $leadtype, $Questions){

	    global $DROPBOX_FOLDER;
        global $DROPBOX_COMPLETED_FOLDER;

		// Read data from storage
		$path = getcwd()."/wp-content/themes/no-exam-theme/storage";
		$fileID = $sessionId;

		if (file_exists($path."/".$fileID.".txt")){

            $_DATA = json_decode(ReadDataFromStorageFile($path,$fileID));

            if (json_last_error() != 0){
				$_DATA = json_decode(ReadDataFromStorageFile($path,$fileID));
			}

			// old: here is sending to vamdb
			$vMiddleName = $_DATA -> fMiddleName;
			$vPlaceOfBirth = $_DATA -> fBirthState." ".$_DATA -> fBirthCountry;
			$vOccupation	= $_DATA -> fOccupation;
			$vDLN = $_DATA -> fDLN;
			$vDLS = $_DATA -> fDLS;
			$vAnnualIncome = $_DATA -> fAnnualIncome;
			$vNOE = $_DATA -> fNOE;
			$vagent = "1";
			$vrateclass = $_DATA -> frateclass;
			$vFaceAmount = $_DATA -> fFaceAmount;
			$vterm = TermVamdb2Term($_DATA -> fCatagory);
			$vpremium = $_DATA -> fpremium;
			$vcompany = $_DATA -> fcompany;
			$vproduct = $_DATA -> fproduct;
			$vBirthMonth = $_DATA -> fBirthMonth;
            $vBirthday = $_DATA -> fBirthday;
            $vBirthYear = $_DATA -> fBirthYear;
            $vBirthCountry = $_DATA -> fBirthCountry;
            $vBirthState = $_DATA -> fBirthState;
            $vSex = $_DATA -> fSex;
            $vSmoker = $_DATA -> fSmoker;
            $vHealth = $_DATA -> fHealth;
            $vFirstName = $_DATA -> fFirstName;
            $vLastName = $_DATA -> fLastName;
            $vAddress = $_DATA -> fAddress;
            $vCity = $_DATA -> fCity;
			$vState = $_DATA -> fState;
			$vZipCode = $_DATA -> fZipCode;
			$vHomePhone = $_DATA -> fHomePhone;
			$vEmail = $_DATA -> fEmail;
			$vOriginalHeight = $_DATA -> fHeight_ft."'".$_DATA -> fHeight_in;
			$vweight = $_DATA -> fweight;
			$vannualp =$_DATA -> fannualpremium;
			// if ($_DATA -> fgclid_field != ""){
				// $gclid = $_DATA -> fgclid_field;
			// } else if ($_DATA -> fbing_field != ""){
				// $gclid = $_DATA -> fbing_field;
			// } else {
				// $gclid = "";
			// }
			$referrer = $_DATA -> referrer;

			$gclid = $_DATA -> fgclid_field;
			$bingid = $_DATA -> fbing_field;
			
			$idToVamDb = $gclid.$bingid;

			// pdf gen
			$pdf = new FPDF();
			$pdf -> SetFont('Arial','',22);
			$pdf -> AddPage();
			// title
			$pdf -> cell(0,10, "Client details",15);
			$pdf -> ln(10);
			switch ($leadtype){
				case 25:
				    $clientType = "Quote Request";
                    break;
				case 26:
				    $clientType = "App Request";
                    break;
                case 27:
				    $clientType = "Medical Questions";
                    break;
                case 28:
                    $clientType = "Additional Questions";
                    break;
                case 29:
                    $clientType = "Additional Questions 2";
                    break;
                case 30:
                    $clientType = "General Info";
                    break;
                case 31:
                    $clientType = "NA";
                    break;
				case 137:
				    $clientType = "Declined Through Kill Question";
                    break;
			}
			$pdf -> cell(0,10, "Lead type: $clientType",15);
			$pdf -> ln(10);
			
			
			// client info
				// Client data
				$pdf -> SetFont('Arial','',16);
				$pdf -> cell(0,10, "Personal info",15);
				$pdf -> ln(5);
				$pdf -> SetFont('Arial','',10);
				
				if (isset($_DATA -> fgclid_field)){
					$pdf -> cell(0,10, "GCLID: ".$gclid,15);
					$pdf -> ln(5);
				}
				
				if (isset($_DATA -> fbing_field)){
					$pdf -> cell(0,10, "Bing keywords: ".$bingid,15);
					$pdf -> ln(5);
				}
				
				$pdf -> cell(0,10, "Name: $vFirstName $vMiddleName $vLastName",15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Date of birthday: $vBirthMonth / $vBirthday / $vBirthYear",15);
				$pdf -> ln(5);
				
				if ($vBirthCountry != ""){
					$pdf -> cell(0,10, "Country of birth: $vBirthCountry",15);
					$pdf -> ln(5);
				}
				if ($vBirthState != ""){
					$pdf -> cell(0,10, "State of birth: $vBirthState",15);
					$pdf -> ln(5);
				}
				$pdf -> cell(0,10, "Height: ".$vOriginalHeight,15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Weight: ".$_DATA -> fweight,15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Sex: $vSex",15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Marital status: ".$_DATA -> fMaritalStatus,15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Smoker: $vSmoker",15);
				$pdf -> ln(5);
				$pdf -> cell(0,10, "Rate class: $vrateclass",15);
				$pdf -> ln(5);
				
				if ($vOccupation != ""){
					$pdf -> cell(0,10, "Occupation: $vOccupation",15); 
					$pdf -> ln(5);
				}
				if ($vDLN != ""){
					$pdf -> cell(0,10, "Drivers license number: $vDLN",15);
					$pdf -> ln(5);
				}
				if ($vDLS != ""){
					$pdf -> cell(0,10, "Drivers license state: $vDLS",15);
					$pdf -> ln(5);
				}
				if ($vAnnualIncome != ""){
					$pdf -> cell(0,10, "Annual income: $$vAnnualIncome",15);
					$pdf -> ln(5);
				}
				if ($vNOE != ""){
					$pdf -> cell(0,10, "Name of employer: $vNOE",15);
				
				}
				
				$pdf -> ln(10);
				
				// Address
				$pdf -> SetFont('Arial','',16);
				$pdf -> cell(0,10, "Address",15);
				$pdf -> ln(5);
				$pdf -> SetFont('Arial','',10);
				
				if ($vAddress != ""){
					$pdf -> cell(0,10, "Address: $vAddress",15);
					$pdf -> ln(5);
				}
				if ($vCity != ""){
				$pdf -> cell(0,10, "City: $vCity",15);
				$pdf -> ln(5);
				}
				if ($vState != ""){
					$pdf -> cell(0,10, "State: $vState",15);
					$pdf -> ln(5);
				}
				if ($vZipCode != ""){
				$pdf -> cell(0,10, "ZipCode: $vZipCode",15);
				}
				$pdf -> ln(10);
				
				// Contact data
				$pdf -> SetFont('Arial','',16);
				$pdf -> cell(0,10, "Contact info",15);
				$pdf -> ln(5);
				$pdf -> SetFont('Arial','',10);
				if ($vHomePhone != ""){
					$pdf -> cell(0,10, "Home phone: $vHomePhone",15);
					$pdf -> ln(5);
				}
				if ($vEmail != ""){
					$pdf -> cell(0,10, "Email: $vEmail",15);
				}
				$pdf -> ln(10);
				
				// Product 
				$pdf -> SetFont('Arial','',16);
				$pdf -> cell(0,10, "Product",15);
				$pdf -> ln(5);
				$pdf -> SetFont('Arial','',10);
				
				if ($vFaceAmount != ""){
					$pdf -> cell(0,10, "Amount: $$vFaceAmount",15);
					$pdf -> ln(5);
				}
				if ($vpremium != ""){
					$pdf -> cell(0,10, "Premium: $$vpremium",15);
					$pdf -> ln(5);
				}
				if ($vcompany != ""){
					$pdf -> cell(0,10, "Company: $vcompany",15);
					$pdf -> ln(5);
				}
				if ($vproduct != ""){
					$pdf -> cell(0,10, "Product: $vproduct",15);
				}
				$pdf -> ln(10);
				
				if (isset($_DATA -> fSocialSecurityNumber)){
					if ($_DATA -> fSocialSecurityNumber != ""){
						$pdf -> cell(0,10, "SSN: ".$_DATA -> fSocialSecurityNumber,15);
						$pdf -> ln(10);
					}
				}
			
				if (isset($_DATA -> fBankAccount)){
					//payment data
					$pdf -> SetFont('Arial','',16);
					$pdf -> cell(0,10, "Payment info",15);
					$pdf -> ln(5);
					$pdf -> SetFont('Arial','',10);
					if ($_DATA -> fMonthlyCheck != ""){
						if ($_DATA -> fMonthlyCheck == "0"){
							$pdf -> cell(0,10, "User checked monthly payment with: ".$vpremium."$ per month.",15);
							$pdf -> ln(5);
						} else if ($_DATA -> fMonthlyCheck == "1"){
							$pdf -> cell(0,10, "User checked annual payment with: ".$_DATA->fannualpremium."$ per year.",15);
							$pdf -> ln(5);
						}
					}
					if ($_DATA -> fBankName != ""){
						$pdf -> cell(0,10, "Bank name: ".$_DATA -> fBankName,15);
						$pdf -> ln(5);
					}
					if ($_DATA -> fBankAccount != ""){
						$pdf -> cell(0,10, "Bank account: ".$_DATA -> fBankAccount,15);
						$pdf -> ln(5);
					}
					if ($_DATA -> fRoutingNumber != ""){
						$pdf -> cell(0,10, "Routing number: ".$_DATA -> fRoutingNumber,15);
						$pdf -> ln(5);
					}
					if ($_DATA -> fPaymentMethod != ""){
						$pdf -> cell(0,10, "Payment method: "."EFT Bank Draft(Checking Account)",15);
						$pdf -> ln(5);
					}
					if ($_DATA -> fAddPaymentMethod != ""){
						$pdf -> cell(0,10, "Additional payment method: ".$_DATA -> fAddPaymentMethod,15);
						$pdf -> ln(5);
					}
					
					$pdf -> ln(10);
				}
				
			// Answers 
				// Medical and history questions
				if (isset($_DATA -> fanswer1)){
					$pdf -> SetFont('Arial','',14);
					$pdf -> cell(0,10, "Answers to medical and personal history questions",15);
					$pdf -> ln(10);
					$pdf -> SetFont('Arial','',10);
				
					// 1
					$pdf -> MultiCell(0,5,$Questions[1],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer1,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);
					// 2
					$pdf -> MultiCell(0,5,$Questions[2],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer2,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);
					// 3
					$pdf -> MultiCell(0,5,$Questions[3],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer3,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);
					// 4
					$pdf -> MultiCell(0,5,$Questions[4],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer4,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);
					// 5
					$pdf -> MultiCell(0,5,$Questions[5],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer5,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);

                    // 6
                    if ($vcompany == 'NA') {
                        // 6
                        $pdf -> MultiCell(0,5,$Questions[6],0);
                        $pdf -> SetFont('Arial','B',10) ;
                        $pdf -> cell(0,10, $_DATA -> fanswer7,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(5);

                        if ($_DATA -> fext_answ_q7 != ""){
                            $pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q7,15);
                        } else {
                            $pdf -> cell(0,10, "Extended answer: -",15);
                        }
                        $pdf -> ln(10);

                        // 7
                        $pdf -> MultiCell(0,5,$Questions[7],0);
                        $pdf -> SetFont('Arial','B',10) ;
                        $pdf -> cell(0,10, $_DATA -> fanswer8,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(5);

                        if ($_DATA -> fext_answ_q8 != ""){
                            $pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q8,15);
                        } else {
                            $pdf -> cell(0,10, "Extended answer: -",15);
                        }
                        $pdf -> ln(10);

                    }
                    else {
                        $pdf -> MultiCell(0,5,$Questions[6],0);
                        // 6.1
                        $pdf -> MultiCell(0,5,$Questions[7],0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fanswer6,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                        // 6.2
                        $pdf -> MultiCell(0,5,$Questions[8],0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fanswer7,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                        // 6.3
                        $pdf -> MultiCell(0,5,$Questions[9],0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fanswer8,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }


					// 7
					$pdf -> MultiCell(0,5,$Questions[10],0);
					$pdf -> SetFont('Arial','B',10);
					$pdf -> cell(0,10, $_DATA -> fanswer9,15);
					$pdf -> SetFont('Arial','',10);
					$pdf -> ln(10);
					
					if ($_DATA -> fanswer9 == "Yes")
					{
						$pdf -> MultiCell(0,5,"$Questions[11]",0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer10,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(10);
					}
				}
				if (isset($_DATA -> fanswer11)){
					// Additional Medical and history questions
					$pdf -> SetFont('Arial','',14);
					$pdf -> cell(0,10, "Answers to additional medical and personal history questions",15);
					$pdf -> ln(10);
					$pdf -> SetFont('Arial','',10);
					
					// 1
					if (isset($_DATA -> fanswer11)){
						$pdf -> MultiCell(0,5,$Questions[12],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer11,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q11 != ""){
							$pdf -> cell(0,10, "Date of diagnosis: ".$_DATA -> fext_answ_q11,15);
						} else {
							$pdf -> cell(0,10, "Date of diagnosis: -",15);
						}
						$pdf -> ln(5);
						if ($_DATA -> fext_txt_q11 != ""){
							$pdf -> cell(0,10, "Prescription medication: ".$_DATA -> fext_txt_q11,15);
						} else {
							$pdf -> cell(0,10, "Prescription medication: -",15);
						}
						$pdf -> ln(10);
					}
					// 2
					$pdf -> MultiCell(0,5,$Questions[13],0);
						// 2.1
						if (isset($_DATA -> fanswer12)){
							$pdf -> MultiCell(0,5,$Questions[14],0);
							$pdf -> SetFont('Arial','B',10) ;
							$pdf -> cell(0,10, $_DATA -> fanswer12,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q12 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q12,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						
						// 2.2
						if (isset($_DATA -> fanswer13)){
							$pdf -> MultiCell(0,5,$Questions[15],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer13,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q13 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q13,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);	
						}
						
						// 2.3
						if (isset($_DATA -> fanswer14)){
							$pdf -> MultiCell(0,5,$Questions[16],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer14,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q14 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q14,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// TO DO
					// 3
					$pdf -> MultiCell(0,5,$Questions[17],0);
						// 3.1
						if (isset($_DATA -> fanswer15)){
							$pdf -> MultiCell(0,5,$Questions[18],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer15,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q15 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q15,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.2
						if (isset($_DATA -> fanswer16)){
							$pdf -> MultiCell(0,5,$Questions[19],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer16,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q16 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q16,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.3
						if (isset($_DATA -> fanswer17)){
							$pdf -> MultiCell(0,5,$Questions[20],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer17,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q17 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fanswer17,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.4
						if (isset($_DATA -> fanswer18)){
							$pdf -> MultiCell(0,5,$Questions[21],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer18,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q18 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q18,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.5
						if (isset($_DATA -> fanswer19)){
							$pdf -> MultiCell(0,5,$Questions[22],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer19,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q19 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q19,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.6
						if (isset($_DATA -> fanswer20)){
							$pdf -> MultiCell(0,5,$Questions[23],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer20,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q20 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q20,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.7
						if (isset($_DATA -> fanswer21)){
							$pdf -> MultiCell(0,5,$Questions[24],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer21,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q21 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q21,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
						// 3.8
						if (isset($_DATA -> fanswer22)){
							$pdf -> MultiCell(0,5,$Questions[25],0);
							$pdf -> SetFont('Arial','B',10);
							$pdf -> cell(0,10, $_DATA -> fanswer22,15);
							$pdf -> SetFont('Arial','',10);
							$pdf -> ln(5);
							if ($_DATA -> fext_answ_q22 != ""){
								$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q22,15);
							} else {
								$pdf -> cell(0,10, "Extended answer: -",15);
							}
							$pdf -> ln(10);
						}
					// 4
					if (isset($_DATA -> fanswer23)){
						$pdf -> MultiCell(0,5,$Questions[26],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer23,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q23 != ""){
							$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q23,15);
						} else {
							$pdf -> cell(0,10, "Extended answer: -",15);
						}
						$pdf -> ln(10);
					}
					// 5
					if (isset($_DATA -> fanswer24)){
						$pdf -> MultiCell(0,5,$Questions[27],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer24,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q24 != ""){
							$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q24,15);
						} else {
							$pdf -> cell(0,10, "Extended answer: -",15);
						}
						$pdf -> ln(10);
					}
					
					if (isset($_DATA -> fanswer28)){
						$pdf -> MultiCell(0,5,$Questions[31],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer28,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q28 != ""){
							$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q28,15);
						} else {
							$pdf -> cell(0,10, "Extended answer: -",15);
						}
						$pdf -> ln(10);
					}
					
					if (isset($_DATA -> fmLiving)){
					// table
					$pdf -> cell(0,10,"6.",15);
					$pdf -> ln(10);
					$pdf -> SetFillColor(128,128,128);
					$pdf -> SetTextColor(255);
					$pdf -> SetDrawColor(92,92,92);
					$pdf -> SetLineWidth(.3);
						//table header
						$pdf -> Cell(30,7,"Family member",1,0,'C',true);
						$pdf -> Cell(20,7,"Living",1,0,'C',true);
						$pdf -> Cell(70,7,"Cause of death",1,0,'C',true);
						$pdf -> Cell(70,7,"Age of death",1,0,'C',true);
						$pdf -> ln(7);
						// row 1
						$pdf -> Cell(30,7,"Mother",1,0,'C',true);
						$pdf -> Cell(20,7,$_DATA -> fmLiving,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fmcdeath,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fmadeath,1,0,'C',true);
						$pdf -> ln(7);
						// row 2
						$pdf -> Cell(30,7,"Father",1,0,'C',true);
						$pdf -> Cell(20,7,$_DATA -> ffLiving,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> ffcdeath,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> ffadeath,1,0,'C',true);
						$pdf -> ln(7);
						// row 3
						$pdf -> Cell(30,7,"Sister(s)",1,0,'C',true);
						$pdf -> Cell(20,7,$_DATA -> fsLiving,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fscdeath,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fsadeath,1,0,'C',true);
						$pdf -> ln(7);
						// row 4
						$pdf -> Cell(30,7,"Brother(s)",1,0,'C',true);
						$pdf -> Cell(20,7,$_DATA -> fbLiving,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fbcdeath,1,0,'C',true);
						$pdf -> Cell(70,7,$_DATA -> fbadeath,1,0,'C',true);
						$pdf -> ln(10);
					}
					//
					
					$pdf -> SetTextColor(000);
					
					if (isset($_DATA -> fanswer25)){
						$pdf -> MultiCell(0,5,$Questions[28],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer25,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q25 != ""){
							$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q25,15);
						} else {
							$pdf -> cell(0,10, "Extended answer: -",15);
						}
						$pdf -> ln(10);
					}
					
					if (isset($_DATA -> fanswer26)){
						$pdf -> MultiCell(0,5,$Questions[29],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer26,15);
						$pdf -> SetFont('Arial','',10);
						$pdf -> ln(5);
						if ($_DATA -> fext_answ_q26 != ""){
							$pdf -> cell(0,10, "Extended answer: ".$_DATA -> fext_answ_q26,15);
						} else {
							$pdf -> cell(0,10, "Extended answer: -",15);
						}
						$pdf -> ln(10);
					}
					
					if (isset($_DATA -> fanswer27)){
						$pdf -> MultiCell(0,5,$Questions[30],0);
						$pdf -> SetFont('Arial','B',10);
						$pdf -> cell(0,10, $_DATA -> fanswer27,15);
						$pdf -> SetFont('Arial','',10);
					}
					$pdf -> ln(10);
				}

				//Request and General Info
                if (isset($_DATA -> fSocialSecurityNumber)){
                    //Request and General Info
                    $pdf -> SetFont('Arial','',14);
                    $pdf -> cell(0,10, "General Info",15);
                    $pdf -> ln(10);
                    $pdf -> SetFont('Arial','',10);

                    // First Name
                    if (isset($_DATA -> fFirstName)){
                        $pdf -> MultiCell(0,5,"First Name",0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fFirstName,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Middle Name
                    if (isset($_DATA -> fMiddleName)){
                        $pdf -> MultiCell(0,5,"Middle Name",0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fMiddleName,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Last Name
                    if (isset($_DATA -> fLastName)){
                        $pdf -> MultiCell(0,5,"Last Name",0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fLastName,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Address
                    if (isset($_DATA -> fAddress)){
                        $pdf -> MultiCell(0,5,"Address",0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fAddress,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // City
                    if (isset($_DATA -> fCity)){
                        $pdf -> MultiCell(0,5,"City",0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fCity,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // State
                    if (isset($_DATA -> fState)){
                        $pdf -> MultiCell(0,5, "State", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fState,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // ZipCode
                    if (isset($_DATA -> fZipCode)){
                        $pdf -> MultiCell(0,5, "State", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fZipCode,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // HomePhone
                    if (isset($_DATA -> fHomePhone)){
                        $pdf -> MultiCell(0,5, "Home Phone", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fHomePhone,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Email
                    if (isset($_DATA -> fEmail)){
                        $pdf -> MultiCell(0,5, "Email", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fEmail,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Original Height
                    if (isset($_DATA -> fOriginalHeight)){
                        $pdf -> MultiCell(0,5, "Height", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fOriginalHeight,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Weight
                    if (isset($_DATA -> fweight)){
                        $pdf -> MultiCell(0,5, "Weight", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fweight,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Birth Country
                    if (isset($_DATA -> fBirthCountry)){
                        $pdf -> MultiCell(0,5, "Birth Country", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fBirthCountry,15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Birth State
                    if (isset($_DATA -> fBirthState)){
                        $pdf -> MultiCell(0,5, "Birth State", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fBirthState, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Martial Status: Married
                    if (isset($_DATA -> fMaritalStatus)){
                        $pdf -> MultiCell(0,5, "Martial Status", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fMaritalStatus, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Occupation
                    if (isset($_DATA -> fOccupation)){
                        $pdf -> MultiCell(0,5, "Occupation", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fOccupation, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Name of Employer
                    if (isset($_DATA -> fNOE)){
                        $pdf -> MultiCell(0,5, "Name of Employer", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fNOE, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Annual Income
                    if (isset($_DATA -> fAnnualIncome)){
                        $pdf -> MultiCell(0,5, "Annual Income", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fAnnualIncome, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Social Security Number
                    if (isset($_DATA -> fSocialSecurityNumber)){
                        $pdf -> MultiCell(0,5, "Social Security Number", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fSocialSecurityNumber, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }


                    // Driver License Number
                    if (isset($_DATA -> fDLN)){
                        $pdf -> MultiCell(0,5, "Driver License Number", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fDLN, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                    // Driver License State
                    if (isset($_DATA -> fDLS)){
                        $pdf -> MultiCell(0,5, "Driver License State", 0);
                        $pdf -> SetFont('Arial','B',10);
                        $pdf -> cell(0,10, $_DATA -> fDLS, 15);
                        $pdf -> SetFont('Arial','',10);
                        $pdf -> ln(10);
                    }

                }

				$commaPos = 0;
				$subString = "";
				$BeneficiaryData = json_decode(unQuote($_DATA -> fBeneficiaryData),true);
				
				for ($i = 0; $i < count($BeneficiaryData); $i++){
					if ($BeneficiaryData[$i] != null){
						for ($j = 0; $j < 8; $j++){
							$BeneficiaryDecodeData = $BeneficiaryDecodeData.$BeneficiaryData[$i][$j].",";
						}
					}
				}
				
				$BeneficiaryData = $BeneficiaryDecodeData;
				if ($BeneficiaryData != ""){
					$pdf -> SetFont('Arial','',16);
					$pdf -> cell(0,10, "Beneficiaries",15);
					$pdf -> ln(10);
					$pdf -> SetFont('Arial','',10);
					$count = substr_count($BeneficiaryData, ",");
					
					$beneficiaryAttrCount = 0;
					for ($i = 0; $i < $count; $i++){
						$commaPos = strpos($BeneficiaryData, ",");
						$subString = substr($BeneficiaryData, 0, $commaPos);
						$BeneficiaryData = substr($BeneficiaryData, $commaPos+1, strlen($BeneficiaryData) - strlen($subString));
						if ($beneficiaryAttrCount <= 7){
							switch ($beneficiaryAttrCount){
								case 0: $pdf -> SetFont('Arial','',13);
										$pdf -> cell(0,10,"Beneficiary ".$subString,15);
										$pdf -> ln(7);
										$pdf -> SetFont('Arial','',10);
										$pdf -> cell(0,10,"First name: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 1: $pdf -> cell(0,10,"Last name: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 2: $pdf -> cell(0,10,"Relationship: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 3: $pdf -> cell(0,10,"Percentage: ".$subString." %",15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 4: $pdf -> cell(0,10,"Street: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 5: $pdf -> cell(0,10,"City: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 6: $pdf -> cell(0,10,"State: ".$subString,15);
										$pdf -> ln(5);
										$beneficiaryAttrCount += 1;
										break;
								case 7: $pdf -> cell(0,10,"Zip code: ".$subString,15);
										$pdf -> ln(10);
										$beneficiaryAttrCount = 0;
										break;
							}
						} 
					}
				}
				
				$GUID = getGUID();
				$name =getcwd()."/wp-content/uploads/".$vFirstName."_".$vLastName."_".$GUID.".pdf";
			// Create file
			$pdf -> Output("$name");
			
			// $mail = new PHPMailer;
			// $mail -> isSMTP();
			// $mail -> CharSet = "utf-8";
			// $mail -> SMTPDebug = 0;
			// $mail -> Debugoutput = 'html';
			// $mail -> Host = "ssl://smtp.gmail.com";
			// $mail -> SMTPSecure = 'ssl';
			// $mail -> Port = 465;
			// $mail -> SMTPAuth = true;
			// $mail -> Username = "support@noexam.com";
			// $mail -> Password = "coltlinus7";
			// $mail -> From = 'support@noexam.com';
			// $mail -> FromName = 'NoExam.com';
			// $mail -> addAddress('support@noexam.com');
			// $mail -> addAddress('andrew.rad.dev@gmail.com');
			// $mail -> addAttachment(getcwd()."/wp-content/uploads/".$vFirstName."_".$vLastName."_".$GUID.".pdf");
			// $mail -> Subject = "New $clientType Received";
			// $mail -> Body    = "$vFirstName $vLastName has just completed an application for life insurance with $vcompany with a monthly premium of $vpremium";
			// $mail->send();
			$pdfFileName = getcwd()."/wp-content/uploads/".$vFirstName."_".$vLastName."_".$GUID.".pdf";

            // to do code for sending file to vamdb
			// fields from storage
			
			
			// data for sending file to vamdb
			$vFileName = $vFirstName."_".$vLastName."_".$GUID.".pdf";
			$vUserdata = file_get_contents($pdfFileName);
			$vUserdata = urlencode($vUserdata);
			
			// data string
			if ($leadtype == 26){
				$data = "FileName=$vFileName&FileData=$vUserdata&refnum=$idToVamDb&leadtype=$leadtype&mi=$vMiddleName&birthpl=$vPlaceOfBirth&job=$vOccupation&dlnum=$vDLN&dlstate=$vDLS&income=$vAnnualIncome&employer=$vNOE&agent=$vagent&rateclass=$vrateclass&FaceAmount=$vFaceAmount&term=$vterm&premium=$vannualp&oppremium=$vpremium&company=$vcompany&product=$vproduct&BirthMonth=$vBirthMonth&Birthday=$vBirthday&BirthYear=$vBirthYear&Sex=$vSex&Smoker=$vSmoker&Health=$vHealth&FirstName=$vFirstName&LastName=$vLastName&Address=$vAddress&City=$vCity&State=$vState&ZipCode=$vZipCode&HomePhone=$vHomePhone&Email=$vEmail&Height=$vOriginalHeight&Weight=$vweight&Referrer=$referrer";
			} else {
				$data = "FileName=$vFileName&FileData=$vUserdata&refnum=$idToVamDb&leadtype=$leadtype&mi=$vMiddleName&birthpl=$vPlaceOfBirth&job=$vOccupation&dlnum=$vDLN&dlstate=$vDLS&income=$vAnnualIncome&employer=$vNOE&rateclass=$vrateclass&FaceAmount=$vFaceAmount&term=$vterm&premium=$vannualp&oppremium=$vpremium&company=$vcompany&product=$vproduct&BirthMonth=$vBirthMonth&Birthday=$vBirthday&BirthYear=$vBirthYear&Sex=$vSex&Smoker=$vSmoker&Health=$vHealth&FirstName=$vFirstName&LastName=$vLastName&Address=$vAddress&City=$vCity&State=$vState&ZipCode=$vZipCode&HomePhone=$vHomePhone&Email=$vEmail&Height=$vOriginalHeight&Weight=$vweight&Referrer=$referrer";
			}

			// post to vamdb
//			$host = "www1.mscc2.com";
//			$path = "/HollowayJ/request-application.php";
//			$res = PostToVamDB($host,$path,$data);

            // post pdf to dropbox and then update base crm contact
            // try to search deal with $email, if it has application field (dropbox link),
            // upload with same name

            $contact = getBaseCrmContact($vEmail, $vFirstName, $vLastName);
            $existing_dropbox_url = null;

            if ($contact != null) {
                $contact_id = $contact['data']['id'];

                // search deal with this contact_id and status not closed (approved, lost, declined)
                // and update it
                $deal = getBaseCrmDeal($contact_id, 'open');
                if ($deal != null) {
                    $existing_dropbox_url = $deal['data']['custom_fields']['Application'];
                }
            }
            // we will overwrite dropbox file instead of creating new file
            if ($existing_dropbox_url) {
                // the existing url is something like this:
                // https://www.dropbox.com/home?preview=noexam/georgi_k_{acd36006-7519-ef0d-059c-983f77627f8f}.pdf
                $existing_dropbox_url = substr($existing_dropbox_url, 44);
            }

            if ($leadtype == 26) {
                $upload_response = uploadFileToDropbox($pdfFileName, $existing_dropbox_url, $DROPBOX_COMPLETED_FOLDER);
            }
            else {
                $upload_response = uploadFileToDropbox($pdfFileName, $existing_dropbox_url, $DROPBOX_FOLDER);
            }

            $response=json_decode($upload_response);

            if ($response->id && $response->path_lower) {

                //update deal pdf link
                updateBaseCrmPdfLink(substr($response->path_lower, 1), $vEmail, $vFirstName, $vLastName);
            }

			unlink($pdfFileName);
		}
	}

	function ManagerOfObsoleteFiles($path,$Questions){

		if (GetFlag() == 0){
		    SetUnsetFlag();
		// if directory is created and file type = directory
			if (is_dir($path)){
				// get names of all files in directory
				$files = scandir($path);
				foreach ($files as $value){
					// get names of data files
					if (($value != ".") and ($value != "..") and (substr($value,0,5)) != "temp_"){
						// if last modification time of the file for more than 15 minutes
						// 900

						if (time() - filemtime($path.$value) > 900){
							echo time()." => ".filemtime($path.$value)."</br>";
							$obsoleteSessionId = substr($value, 0, strlen($value)-4);
							echo $obsoleteSessionId;
							$leadtype = 25;
							$pathToObsoleteFile = substr($path,0,strlen($path)-1);
							$_DATA = json_decode(ReadDataFromStorageFile($pathToObsoleteFile,$obsoleteSessionId),true);		
							$_DATA_new =  json_decode(ReadDataFromStorageFile($pathToObsoleteFile,$obsoleteSessionId),false);
													
							if ($_DATA != "" and filesize($path.$value) > 200){

							    //differentiate questions

                                $q = null;

                                if ($_DATA_new ->fcompany == 'SBLI') {
                                    $q =$Questions['sbli'];
                                }
                                else if ($_DATA_new ->fcompany == 'Sagicor Life Insurance Company') {
                                    $q = $Questions['sagicor'];
                                }

                                if($_DATA_new -> form_short){
                                    if($_DATA_new -> fanswer27!= ''){
                                        SendToVamDB($obsoleteSessionId,$leadtype,$q);
                                        DeleteStorageFile($pathToObsoleteFile,$obsoleteSessionId);
                                    }
                                }
                                else {
                                    SendToVamDB($obsoleteSessionId,$leadtype,$q);
                                    DeleteStorageFile($pathToObsoleteFile,$obsoleteSessionId);
                                }




							} else {
								DeleteStorageFile($pathToObsoleteFile, $obsoleteSessionId);
							}
						}
					}
				}
			}
		SetUnsetFlag();
		}
	}
	
	function DestroySessionData(){
		if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1800);
                setcookie($name, '', time()-1800, '/');
            }
            setcookie('PHPSESSID', '', time()-1800);
        }
	}

	function getAssignee($state) {
        global $CRM_USERS;

        // timezone comparison should be done in EST
        date_default_timezone_set('US/Eastern');

        $current_time_str = date('H:i a');

        $current_time = DateTime::createFromFormat('H:i a', $current_time_str);

        $heidi_start_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Heidi']['start_time_str']);
        $heidi_end_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Heidi']['end_time_str']);
        $chris_end_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Chris']['start_time_str']);
        $chris_end_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Chris']['end_time_str']);
        $tom_start_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Tom']['start_time_str']);
        $tom_end_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Tom']['end_time_str']);
        $melissa_start_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Melissa']['start_time_str']);
        $melissa_end_time = DateTime::createFromFormat('H:i a', $CRM_USERS['Melissa']['end_time_str']);

        if ($heidi_start_time <= $current_time && $current_time <= $heidi_end_time && $CRM_USERS['Heidi']['enabled'] == true) {
            // between: 16:00pm to 11:59pm assign it to Heidi

            return $CRM_USERS['Heidi'];
        }
        else if ($heidi_start_time <= $current_time && $current_time <= $heidi_end_time && $CRM_USERS['Heidi']['enabled'] != true) {
            // between: 16:00pm to 11:59pm, Heidi is disabled, so it will be assigned to rest of Melissa, Chris and Tom
            if ($CRM_USERS['Tom']['enabled'] == true && (in_array($state, $CRM_USERS['Tom']['states']) == true) ) {
                return $CRM_USERS['Tom'];
            }
            else if ($CRM_USERS['Chris']['enabled'] == true && (in_array($state, $CRM_USERS['Chris']['states']) == true) ) {
                return $CRM_USERS['Chris'];
            }
            else if ($CRM_USERS['Melissa']['enabled'] == true) {
                return $CRM_USERS['Melissa'];
            }
            else{
                return $CRM_USERS['John'];
            }
        }
        else if ($tom_start_time <= $current_time && $current_time < $heidi_start_time
            && $CRM_USERS['Tom']['enabled'] == true  && (in_array($state, $CRM_USERS['Tom']['states']) == true)) {
            // between 11:00 am to 16:00pm: Assign it to Chris

            return $CRM_USERS['Tom'];
        }
        else if ($tom_start_time <= $current_time && $current_time < $heidi_start_time) {
            // between 11:00 am to 16:00pm:

            if ($CRM_USERS['Chris']['enabled'] == true && in_array($state, $CRM_USERS['Chris']['states'])== true) {
                return $CRM_USERS['Chris'];
            }
            else if ($CRM_USERS['Melissa']['enabled'] == true) {
                return $CRM_USERS['Melissa'];
            }
            else if ($CRM_USERS['Heidi']['enabled'] == true) {
                return $CRM_USERS['Heidi'];
            }
            // assign it to John
            return $CRM_USERS['John'];
        }
        else if ($chris_end_time <= $current_time && $current_time < $tom_start_time
            && $CRM_USERS['Chris']['enabled'] == true  && (in_array($state, $CRM_USERS['Chris']['states']) == true)) {
            // between 10:00 am to 10:59am: Assign it to Chris
            return $CRM_USERS['Chris'];
        }
        else if ($chris_end_time <= $current_time && $current_time < $tom_start_time) {
            // between 10:00 am to 10:59am:

            if ($CRM_USERS['Melissa']['enabled'] == true) {
                return $CRM_USERS['Melissa'];
            }
            else if ($CRM_USERS['Heidi']['enabled'] == true) {
                return $CRM_USERS['Heidi'];
            }
            // assign it to John
            return $CRM_USERS['John'];
        }
        else if ($melissa_start_time <= $current_time && $current_time < $chris_end_time) {
            // between 12:00 am to 9:59am:

            if ($CRM_USERS['Melissa']['enabled'] == true) {
                return $CRM_USERS['Melissa'];
            }
            else if ($CRM_USERS['Heidi']['enabled'] == true) {
                return $CRM_USERS['Heidi'];
            }
            // assign it to John
            return $CRM_USERS['John'];
        }

        return $CRM_USERS['John'];

    }

    // create/update base crm contact and deal with initial param
    function initializeBaseCrmContactAndDeal($stage_type) {
        global $crm_client;
        global $_DATA;
        global $CRM_DEAL_STAGE_IDS;
        global $CRM_USERS;
        global $EMAIL_DEAL_CREATED_SUBJECT_PREFIX;
        global $STATES_TIMEZONES;

        $owner_id = '';
        $owner_email = '';
        $owner_name = '';
        $owner_phone = '';


        if ($stage_type== 'PARTIAL_APPLICATION') {
            $stage_id = $CRM_DEAL_STAGE_IDS['PARTIAL_APPLICATION'];
        }
        else {
            $stage_id = $CRM_DEAL_STAGE_IDS['QUOTE_PRESENTED'];
        }

        $owner = getAssignee($_DATA['fState']);
        $owner_id = $owner['id'];
        $owner_name = $owner['name'];
        $owner_email = $owner['email'];
        $owner_phone = $owner['phone'];


        $contact_param = [
            'first_name' => $_DATA['fFirstName'],
            'last_name' => $_DATA['fLastName'],
            'email' => $_DATA['fEmail'],
            'phone' => $_DATA['fHomePhone'],
            'owner_id' => $owner_id,
            "custom_fields" => [
                "Height" => $_DATA['fHeight_ft']. ' '. $_DATA['fHeight_in'],
                "Weight" => $_DATA['fweight'],
                "Coverage Amount" => $_DATA['fFaceAmount'],
                "Date Of Birth" => $_DATA['fBirthMonth'] . '/'. $_DATA['fBirthday']. '/'. $_DATA['fBirthYear'],
                "Gender" => ($_DATA['fSex'] == 'M' ? 'Male' : 'Female'),
                "Tobacco Use" => ($_DATA['fSmoker'] == 'Y' ? 'Yes' : 'No')
            ],
            'address' => [
                "state" => $_DATA['fState'],
                "city" => $_DATA['fCity'],
                "postal_code" => $_DATA['fZipCode'],
                "line1" => $_DATA["fAddress"]
            ]
        ];


        $contact = getBaseCrmContact($_DATA['fEmail'], $_DATA['fFirstName'], $_DATA['fLastName']);

        $contact_id=0;

        if ($contact) {
            // update existing contact
            $contact_id = $contact['data']['id'];
            $crm_client->contacts->update($contact['data']['id'], $contact_param);
        }
        else {
            // create contact, need to get create contact id
            $rtv = $crm_client->contacts->create($contact_param);
            $contact_id = $rtv['id'];

        }

        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it

        $deal = getBaseCrmDeal($contact_id, 'open');

        // create deal associated with contact id

        $annualpremium = 0;
        if ($_DATA['fannualpremium']) {
            $annualpremium = str_replace(",", "",$_DATA['fannualpremium']);
        }


        // check company if NA, update Age with fAge
        $age = $_DATA['fAge'];
        if ($_DATA['fcompany'] == 'NA') {
            $age = $_DATA['fNaAge'] ;
        }

        $deal_param = [
            'name' => $_DATA['fFirstName']. ' '.$_DATA['fLastName'],
            'value' => $annualpremium,
            "contact_id" => $contact_id,
            'owner_id' =>  $owner_id,
            "stage_id" => $stage_id,
            "custom_fields" => [
                "Height" => $_DATA['fHeight_ft']. ' '. $_DATA['fHeight_in'],
                "Weight" => $_DATA['fweight'],
                "Coverage Amount" => $_DATA['fFaceAmount'],
                "Date Of Birth" => $_DATA['fBirthMonth'] . '/'. $_DATA['fBirthday']. '/'. $_DATA['fBirthYear'],
                "Gender" => ($_DATA['fSex'] == 'M' ? 'Male' : 'Female'),
                "Tobacco Use" => ($_DATA['fSmoker'] == 'Y' ? 'Yes' : 'No'),
                'Email' => $_DATA['fEmail'],
                'Company' => $_DATA['fcompany'],
                "Rate Class" => $_DATA['frateclass'],
                'Annual Premium' => $annualpremium,
                'Monthly Premium' => $_DATA['fpremium'],
                'GCLID' => $_DATA['fgclid_field'],
                "Term Length" => $_DATA['fterm'],
                'State' => $_DATA['fState'],
                'Time Zone' => $STATES_TIMEZONES[$_DATA['fState']],
                'Age' => $age
            ]
        ];

        $subject = '';
        if ($deal) {
            //update deal
            $crm_client->deals->update($deal['data']['id'], $deal_param);
//            $subject = $_DATA['fFirstName']. ' '. $_DATA['fLastName']. ' has updated an deal';
        }
        else {
            $crm_client->deals->create($deal_param);
            $subject = $_DATA['fFirstName']. ' '. $_DATA['fLastName']. $EMAIL_DEAL_CREATED_SUBJECT_PREFIX;

            $rpl = array(
                '$fName' => $_DATA['fFirstName'],
                '$lName' => $_DATA['fLastName'],
                '$ownerName' => $owner_name,
                '$ownerPhone' => $owner_phone
            );

            $e_tpl = file_get_contents(getcwd().'/wp-content/themes/no-exam-theme/emails/deal_created.php');
            $body = str_replace(array_keys($rpl), array_values($rpl), $e_tpl);

//            sendEmail($owner_email, $owner_name, $_DATA['fEmail'], $_DATA['fFirstName']. ' '.$_DATA['fLastName']
//                , $subject, $body);
        }


    }

    // this function is called to update when user selects the quote application
    function updateBaseCrmContactAndDeal($email, $fname, $lname) {
        global $crm_client;
        global $_DATA;
        global $CRM_DEAL_STAGE_IDS;
        global $STATES_TIMEZONES;

        // CHECK contact with current email exists already
        $contact = getBaseCrmContact($email, $fname, $lname);

        if (!$contact) {
            return null;
        }

        $contact_id=0;
        $contact_id = $contact['data']['id'];

        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it

        $deal = getBaseCrmDeal($contact_id, 'open');

        $annualpremium = str_replace(",", "",$_DATA['fannualpremium']);

        // check company if NA, update Age with fAge
        $age = $_DATA['fAge'];
        if ($_DATA['fcompany'] == 'NA') {
            $age = $_DATA['fNaAge'] ;
        }

        // create deal associated with contact id
        // name should be decided.
        $deal_param = [
            'value' => $annualpremium,
            "stage_id" => $CRM_DEAL_STAGE_IDS['PARTIAL_APPLICATION'],
            "custom_fields" => [
                'Company' => $_DATA['fcompany'],
                "Rate Class" => $_DATA['frateclass'],
                'Annual Premium' => $_DATA['fannualpremium'],
                'Monthly Premium' => $_DATA['fpremium'],
                'GCLID' => $_DATA['fgclid_field'],
                "Term Length" => $_DATA['fterm'],
                'Time Zone' => $STATES_TIMEZONES[$_DATA['fState']],
                'State' => $_DATA['fState'],
                'Age' => $age
            ]
        ];

        // if doesn't exist: return;
        if (!$deal) {
            return null;
        }

        //update deal
        $crm_client->deals->update($deal['data']['id'], $deal_param);

    }

    // update generated pdf file to dropbox
    // it works for less than 150MB
    function uploadFileToDropbox($filename, $existing_url, $dropbox_folder) {

        global $DROP_BOX_API_URL;
        global $DROP_BOX_API_TOKEN;

        $upload_file_url = $filename;

        if ($existing_url) {
            $upload_file_url = $existing_url;
        }

        $headers = array('Authorization: Bearer '. $DROP_BOX_API_TOKEN,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: '.
            json_encode(
                array(
                    "path"=> '/'. $dropbox_folder. '/'. basename($upload_file_url),
                    "mode" => "overwrite",
                    "autorename" => false,
                    "mute" => false
                )
            )

        );

        $ch = curl_init($DROP_BOX_API_URL);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);

        $path = $filename;
        $fp = fopen($path, 'rb');
        $filesize = filesize($path);

        curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
        return $response;

    }

    // return crm contact. it can be used in several places later
    function getBaseCrmContact($email, $fname, $lname) {
        global $crm_client;
        global $_DATA;

        // CHECK contact with current email exists already
        $exists = $crm_client->contacts->all(['email' => $email, 'first_name' => $fname, 'last_name' => $lname]);

        $contact_id=0;

        if (count($exists)) {
            // return existing contact
            return $exists[0];
        }
        return null;
    }

    // return crm contact id. it can be used in several places later
    function getBaseCrmDeal($contact_id, $stage_status) {
        global $crm_client;
        global $CRM_DEAL_STAGE_IDS;

        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it
        $existing_deals = $crm_client->deals->all([
            'contact_id' => $contact_id
        ]);


        // check whether it has any deals which doesn't belong to stage : lost, approved, declined
        if (count($existing_deals) > 0) {
            return $existing_deals[0];
        }
//        foreach ($existing_deals as &$ele) {
//            if ($stage_status == 'open') {
//                if($ele['data']['stage_id'] != $CRM_DEAL_STAGE_IDS['APPROVED']
//                    || $ele['data']['stage_id'] != $CRM_DEAL_STAGE_IDS['DECLINED']
//                    || $ele['data']['stage_id'] != $CRM_DEAL_STAGE_IDS['LOST']
//                ) {
//                    return $ele;
//                }
//            }
//            else {
//
//            }
//
//        }

        return null;
    }


    // update pdf link field in deal
    function updateBaseCrmPdfLink($file_url, $email, $fname, $lname) {

        global $crm_client;

        $contact = getBaseCrmContact($email, $fname, $lname);

        if ($contact == null) {
            return null;
        }

        $contact_id = $contact['data']['id'];

        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it
        $deal = getBaseCrmDeal($contact_id, 'open');

        if (!$deal) {
            return null;
        }
        // create deal associated with contact id
        // name should be decided.
        $deal_param = [
            "value" => $deal['data']['value'], // value should be present
            "custom_fields" => [
                'Application' => 'https://www.dropbox.com/home?preview='. $file_url
            ]
        ];

        //update deal
        $crm_client->deals->update($deal['data']['id'], $deal_param);
        return true;
    }

    // reject base crm deal: if company is sagicor and select yes in any options in first page 'medical-questions/'
    function rejectBaseCrmDeal($email, $fname, $lname) {

        global $CRM_DEAL_STAGE_IDS;
        global $crm_client;
        global $CRM_USER_LOST_REASON_IDS;
        global $CRM_USERS;

        if (!$email || !$fname || !$lname) {
            return null;
        }

        $contact = getBaseCrmContact($email, $fname, $lname);

        if ($contact == null) {
            return null;
        }

        $contact_id = $contact['data']['id'];


        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it
        $deal = getBaseCrmDeal($contact_id, 'open');

                // LOST stage
        $deal_param = [
            "value" => $deal['data']['value'], // value should be present
            "stage_id" => $CRM_DEAL_STAGE_IDS['UNQUALIFIED'],
            "loss_reason_id" => $CRM_USER_LOST_REASON_IDS['KILL_QUESTIONS']
        ];

        //reject deal to LOST stage
        $crm_client->deals->update($deal['data']['id'], $deal_param);


        // contact param
        $contact_param = [
            "custom_fields" => [
                "Lead Type" => 'Declined Through Kill Questions'
            ]
        ];

        // update contact lead type with reject option
        $crm_client->contacts->update($contact['data']['id'], $contact_param);
//
//        $subject = $contact['data']['fFirstName']. ' '. $contact['data']['fLastName']. ': deal is rejected';
//
//        $owner_email = '';
//        $owner_name = '';
//
//        if ($contact['data']['id'] == $CRM_USERS['Heidi']['id']) {
//            $owner_email =$CRM_USERS['Heidi']['email'];
//            $owner_name =  $CRM_USERS['Heidi']['name'];
//        }
//        else {
//            $owner_email =$CRM_USERS['Melissa']['email'];
//            $owner_name =  $CRM_USERS['Melissa']['name'];
//        }

//        sendEmail($owner_email, $owner_name, $email, $contact['data']['name']
//            , 'Deal Rejected', $subject);

    }

    // complete base crm deal and contact
    //Contact and Deal are updated to have owner set to Heidi Blaser.
    //Deal is updated and assigned to the “Service Pipeline” with stage of “Awaiting Entry"
    function completeBaseCrm($email, $fname, $lname) {

        global $CRM_DEAL_STAGE_IDS;
        global $crm_client;
        global $CRM_USERS;
        global $_DATA;
        global $EMAIL_DEAL_COMPLETE_SUBJECT;

        $contact = getBaseCrmContact($email, $fname, $lname);

        if ($contact == null) {
            return null;
        }

        $contact_id = $contact['data']['id'];

        $contact_param = [
            'owner_id' => $CRM_USERS['Heidi']['id']
        ];

        // update contact with owner heidi
        $crm_client->contacts->update($contact_id, $contact_param);

        // search deal with this contact_id and status not closed (approved, lost, declined)
        // and update it
        $deal = getBaseCrmDeal($contact_id, 'open');

        if (!$deal) {
            return;
        }

        // COMPLETED stage
        $deal_param = [
            "value" => $deal['data']['value'], // value should be present
            "stage_id" => $CRM_DEAL_STAGE_IDS['APPLICATION_TAKEN'],
            "deal_loss_id" => 2164344, // Declined Through Kill Questions
            'owner_id' => $CRM_USERS['Heidi']['id']
        ];

        //UPDATE THE DEAL TO application_taken stage
        $crm_client->deals->update($deal['data']['id'], $deal_param);

        $rpl = array(
            '$user_fname' => $contact['data']['first_name'],
            '$agent_fname' => $CRM_USERS['Heidi']['fname'],
            '$agent_lname' => $CRM_USERS['Heidi']['lname'],
            '$agent_phone' => $CRM_USERS['Heidi']['phone']
        );

        $e_tpl = null;
        $body = null;

        if ($_DATA['fcompany']==='SBLI') {
            $e_tpl = file_get_contents(getcwd().'/wp-content/themes/no-exam-theme/emails/sbli_completed.php');
            $body = str_replace(array_keys($rpl), array_values($rpl), $e_tpl);
        }
        else if ($_DATA['fcompany']==='NA') {
            $e_tpl = file_get_contents(getcwd().'/wp-content/themes/no-exam-theme/emails/na_completed.php');
            $body = str_replace(array_keys($rpl), array_values($rpl), $e_tpl);
        }
        else {
            $e_tpl = file_get_contents(getcwd().'/wp-content/themes/no-exam-theme/emails/deal_completed.php');
            $body = str_replace(array_keys($rpl), array_values($rpl), $e_tpl);
        }

        sendEmail($CRM_USERS['Heidi']['email'], $CRM_USERS['Heidi']['name'], $email, $contact['data']['name']
            , $EMAIL_DEAL_COMPLETE_SUBJECT, $body);
    }

    // send email using mandrill
    function sendEmail($from, $fromname, $to, $toname, $subject, $body) {
        global $MANDRILL_USERNAME;
        global $MANDRILL_PWD;

        $mail = new PHPMailer;

        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.mandrillapp.com';                 // Specify main and backup server
        $mail->Port = 2525;                                    // Set the SMTP port
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $MANDRILL_USERNAME;                // SMTP username
        $mail->Password = $MANDRILL_PWD;                  // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = $from;
        $mail->FromName = $fromname;
        $mail->AddAddress($to, $toname);  // Add a recipient

        $mail->IsHTML(true);                                  // Set email format to HTML

        $mail->Subject = $subject;
        $mail->Body    = $body;

        if(!$mail->Send()) {
            echo 'Email could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
//            exit;
        }

        echo 'Email has been sent';
    }
?>