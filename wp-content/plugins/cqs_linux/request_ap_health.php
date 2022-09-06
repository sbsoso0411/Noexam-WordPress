<?php

$css = " ";
	if (isset($_REQUEST["css"])) $css= $_REQUEST["css"];

$hdata = array();
$data = array();
$data['Submitted'] = date('l - F jS, Y  [h:i:s a  T]');
function AddIfPresentData($name)
{
global $data;
	if (isset($_REQUEST[$name])) $data[$name] = $_REQUEST[$name];
}
function AddData($name)
{
global $data;
	if (isset($_REQUEST[$name])) $data[$name] = $_REQUEST[$name];
	else $data[$name] = ' ';
}


$state_full_name = array('1' => 'Alabama',
'2' => 'Alaska',
'3' => 'Arizona',
'4' => 'Arkansas',
'5' => 'California',
'6' => 'Colorado',
'7' => 'Connecticut',
'8' => 'Delaware',
'9' => 'Dist. of Columbia',
'10' => 'Florida',
'11' => 'Georgia',
'12' => 'Hawaii',
'13' => 'Idaho',
'14' => 'Illinois',
'15' => 'Indiana',
'16' => 'Iowa',
'17' => 'Kansas',
'18' => 'Kentucky',
'19' => 'Louisiana',
'20' => 'Maine',
'21' => 'Maryland',
'22' => 'Massachusetts',
'23' => 'Michigan',
'24' => 'Minnesota',
'25' => 'Mississippi',
'26' => 'Missouri',
'27' => 'Montana',
'28' => 'Nebraska',
'29' => 'Nevada',
'30' => 'New Hampshire',
'31' => 'New Jersey',
'32' => 'New Mexico',
'52' => 'NY Non-Bus',
'33' => 'NY Business',
'34' => 'North Carolina',
'35' => 'North Dakota',
'36' => 'Ohio',
'37' => 'Oklahoma',
'38' => 'Oregon',
'39' => 'Pennsylvania',
'40' => 'Rhode Island',
'41' => 'South Carolina',
'42' => 'South Dakota',
'43' => 'Tennessee',
'44' => 'Texas',
'45' => 'Utah',
'46' => 'Vermont',
'47' => 'Virginia',
'48' => 'Washington',
'49' => 'West Virginia',
'50' => 'Wisconsin',
'51' => 'Wyoming',
'53' => 'Guam',
'54' => 'Puerto Rico',
'55' => 'Virgin Islands',
'56' => 'America Samoa');

function AddStateData($name)
{
global $data;
global $state_full_name;
	if (isset($_REQUEST[$name])) {
		$data[$name] = $_REQUEST[$name];
		$data['StateFullName'] = $state_full_name[$_REQUEST[$name]];
	}
	else $data[$name] = ' ';
}
function AddHData($name)
{
global $hdata;
	if (isset($_REQUEST[$name])) $hdata[$name] = $_REQUEST[$name];
	else $hdata[$name] = ' ';
}

function OutputDataAsText()
{
global $data;
global $hdata;
$result =  'The following Health Information was obtained:
';
	foreach($data as $name => $value)
		$result .=  "$name is $value
";
$result .=  'Additionally, the following Health Information was obtained
';
	foreach($hdata as $name => $value)
		$result .=  "$name is $value
";
	return $result;
}
function OutputDataAsTable()
{
global $data;
global $hdata;
$result = '<font size="3" face="Times New Roman"><b>The following Client Information was obtained</b></font>
';
$result .= '<table border="0">
';
	foreach($data as $name => $value)
		$result .=  "<tr><td>$name:</td><td>$value</td</tr>
";
	$result .=  '</table>
';
$result .=  '<br><br><font size="3" face="Times New Roman"><b>Additionally, the following Health Information was obtained</b></font><br>
';
$result .=  '<table border="0">
';
	foreach($hdata as $name => $value)
		$result .=  "<tr><td>$name:</td><td>$value</td</tr>
";
	$result .=  '</table>
';

//$result .= 'debug';
//foreach($_REQUEST as $name => $value)
//		$result .=  "<tr><td>$name</td><td>$value</td</tr>
//";
	return $result;
}

function OutputDataAsHiddenFields()
{
global $data;
global $hdata;
	foreach($data as $name=> $value)
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">
		";
	foreach($hdata as $name=> $value)
		echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">
		";
}
	AddData("----");
	AddIfPresentData('Name');
	AddIfPresentData('Area_code');
	AddIfPresentData('Phone');
	AddIfPresentData('Email');
	AddIfPresentData('Best_time_to_call');
	AddIfPresentData('Message');
	
	AddData("-----");
	AddData('Sex');
	AddData('ActualAge');
	AddData('NearestAge');
	AddData("BirthMonth");
	AddData("Birthday");
	AddData("BirthYear");
	AddData("Smoker");
	//AddData("ModeUsed");
	AddStateData("State");
	AddData("Category");
	AddData("Company");
	AddData("Product");
	AddData("FaceAmount");
	AddData("PremiumAnnual");
	AddData("PremiumMode");
	//AddData("UserLocation");
	AddData("email");
	//AddHData("css");
	
	AddHData("HealthCat");
	AddHData("Health");
	AddHData("------");
	AddHData("Feet");
	AddHData("Inches");
	AddHData("Weight");
	AddHData("-------");
	AddHData("DoCigarettes");
	AddHData("PeriodCigarettes");
	AddHData("NumCigarettes");
	AddHData("DoCigars");
	AddHData("PeriodCigars");
	AddHData("NumCigars");
	AddHData("DoPipe");
	AddHData("PeriodPipe");
	AddHData("DoChewingTobacco");
	AddHData("PeriodChewingTobacco");
	AddHData("DoNicotinePatchesOrGum");
	AddHData("PeriodNicotinePatchesOrGum");
	AddHData("--------");
	AddHData("BloodPressureMedication");
	AddHData("Systolic");
	AddHData("Dystolic");
	AddHData("PeriodBloodPressure");
	AddHData("PeriodBloodPressureControlDuration");
	AddHData("---------");
	AddHData("CholesterolMedication");
	AddHData("CholesterolLevel");
	AddHData("HDLRatio");
	AddHData("PeriodCholesterol");
	AddHData("PeriodCholesterolControlDuration");
	AddHData("-----------");
	AddHData("HadDriversLicense");
	AddHData("DwiConviction");
	AddHData("PeriodDwiConviction");
	AddHData("RecklessConviction");
	AddHData("PeriodRecklessConviction");
	AddHData("SuspendedConviction");
	AddHData("PeriodSuspendedConviction");
	AddHData("MoreThanOneAccident");
	AddHData("PeriodMoreThanOneAccident");
	AddHData("MovingViolations0");
	AddHData("MovingViolations1");
	AddHData("MovingViolations2");
	AddHData("MovingViolations3");
	AddHData("MovingViolations4");
	AddHData("------------");
	AddHData("NumDeaths");
	AddHData("-------------");
	AddHData("AgeDied00");
	AddHData("AgeContracted00");
	AddHData("IsParent00");
	AddHData("CVD00");
	AddHData("CAD00");
	AddHData("CVI00");
	AddHData("CVA00");
	AddHData("Diabetes00");
	AddHData("KidneyDisease00");
	AddHData("ColonCancer00");
	AddHData("IntestinalCancer00");
	AddHData("BreastCancer00");
	AddHData("ProstateCancer00");
	AddHData("OvarianCancer00");
	AddHData("OtherInternalCancer00");
	AddHData("MalignantMelanoma00");
	AddHData("BasalCellCarcinoma00");
	AddHData("--------------");
	AddHData("AgeDied01");
	AddHData("AgeContracted01");
	AddHData("IsParent01");
	AddHData("CVD01");
	AddHData("CAD01");
	AddHData("CVI01");
	AddHData("CVA01");
	AddHData("Diabetes01");
	AddHData("KidneyDisease01");
	AddHData("ColonCancer01");
	AddHData("IntestinalCancer01");
	AddHData("BreastCancer01");
	AddHData("ProstateCancer01");
	AddHData("OvarianCancer01");
	AddHData("OtherInternalCancer01");
	AddHData("MalignantMelanoma01");
	AddHData("BasalCellCarcinoma01");
	AddHData("---------------");
	AddHData("AgeDied02");
	AddHData("AgeContracted02");
	AddHData("IsParent02");
	AddHData("CVD02");
	AddHData("CAD02");
	AddHData("CVI02");
	AddHData("CVA02");
	AddHData("Diabetes02");
	AddHData("KidneyDisease02");
	AddHData("ColonCancer02");
	AddHData("IntestinalCancer02");
	AddHData("BreastCancer02");
	AddHData("ProstateCancer02");
	AddHData("OvarianCancer02");
	AddHData("OtherInternalCancer02");
	AddHData("MalignantMelanoma02");
	AddHData("BasalCellCarcinoma02");
	AddHData("----------------");
	AddHData("NumContracted");
	AddHData("-----------------");
	AddHData("AgeContracted10");
	AddHData("IsParent10");
	AddHData("CVD10");
	AddHData("CAD10");
	AddHData("CVI10");
	AddHData("CVA10");
	AddHData("Diabetes10");
	AddHData("KidneyDisease10");
	AddHData("ColonCancer10");
	AddHData("IntestinalCancer10");
	AddHData("BreastCancer10");
	AddHData("ProstateCancer10");
	AddHData("OvarianCancer10");
	AddHData("OtherInternalCancer10");
	AddHData("MalignantMelanoma10");
	AddHData("BasalCellCarcinoma10");
	AddHData("------------------");
	AddHData("AgeContracted11");
	AddHData("IsParent11");
	AddHData("CVD11");
	AddHData("CAD11");
	AddHData("CVI11");
	AddHData("CVA11");
	AddHData("Diabetes11");
	AddHData("KidneyDisease11");
	AddHData("ColonCancer11");
	AddHData("IntestinalCancer11");
	AddHData("BreastCancer11");
	AddHData("ProstateCancer11");
	AddHData("OvarianCancer11");
	AddHData("OtherInternalCancer11");
	AddHData("MalignantMelanoma11");
	AddHData("BasalCellCarcinoma11");
	AddHData("-------------------");
	AddHData("AgeContracted12");
	AddHData("IsParent12");
	AddHData("CVD12");
	AddHData("CAD12");
	AddHData("CVI12");
	AddHData("CVA12");
	AddHData("Diabetes12");
	AddHData("KidneyDisease12");
	AddHData("ColonCancer12");
	AddHData("IntestinalCancer12");
	AddHData("BreastCancer12");
	AddHData("ProstateCancer12");
	AddHData("OvarianCancer12");
	AddHData("OtherInternalCancer12");
	AddHData("MalignantMelanoma12");
	AddHData("BasalCellCarcinoma12");
	AddHData("--------------------");
	//AddHData("DoSubAbuse");
	AddHData("Alcohol");
	AddHData("AlcYearsSinceTreatment");
	AddHData("Drugs");
	AddHData("DrugsYearsSinceTreatment");


$errors = array(); // set the errors array to empty, by default
$fields = array(); // stores the field values

$fields['Name'] = "";
$fields['Area_code'] = "";
$fields['Phone'] = "";
$fields['Email'] = "";
$fields['Best_time_to_call'] = "";
$fields['Message'] = "";
$fields['css'] = "";

$success_message = "";

if (isset($_POST['submit']))
{
	// import the validation library
	require("validation.php");


	$rules = array(); // stores the validation rules

	// standard form fields
	$rules[] = "required,Name,Please enter your name.";
	$rules[] = "required,Area_code,Please enter your area code.";
	$rules[] = "digits_only,Area_code,Please enter only numbers for your area code.";
	$rules[] = "length=3,Area_code,Your area code can only be 3 numbers long.";
	$rules[] = "required,Phone,Please enter your phone number.";
	//$rules[] = "required,Email,Please enter your email address.";
	//$rules[] = "valid_email,Email,Please enter a valid email address.";



	$errors = validateFields($_POST, $rules);

	// if there were errors, re-populate the form fields
	if (!empty($errors))
	{  
		$fields = $_POST;
	}

	// no errors! redirect the user to the thankyou page (or whatever)
	else 
	{

		$ThanksURL = "thankyou.php";	
		{
			//echo "<div class='notify'>$ThanksURL</div>";

			include("Mail.php");
			include('Mail/mime.php');

			$message = new Mail_mime();

			$text = OutputDataAsText();
			$html = OutputDataAsTable();

			$message->setTXTBody($text);
			$message->setHTMLBody($html);
			$body = $message->get();

			$to = $data["email"];

			$host = "MAIL.YOUR_WEBSITE.COM";
			$username = "ADDRESS@YOUR_WEBSITE.COM";
			$password = "YOUR_PASSWORD";

			$extraheaders = array ('From' => 'NOREPLY@YOUR_WEBSITE',
					'To' => $to,
					'Subject' => "Application Request with Health Data");

			$headers = $message->headers($extraheaders);
			$smtp = Mail::factory('smtp',
					array ('host' => $host,
						'auth' => true,
						'username' => $username,
						'password' => $password));
	
			$mail = $smtp->send($to, $headers, $body);
			if (PEAR::isError($mail)) {
				echo("<p>" . $mail->getMessage() . "</p>");
			} /*else {		// don't send out header before redirect
				echo("<p>Message successfully sent!</p>");
			}*/
		}
		header("Location: $ThanksURL");
	}

}

?>

<HTML>
<HEAD>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
   <META NAME="GENERATOR" CONTENT="Mozilla/4.02 [en]C-DIAL  (Win95; U) [Netscape]">
   <META name="description" content="No other term insurance comparison website will give you more competitive pricing information about a broader array of companies and term life insurance products.">
   <META name="keywords" content="term life insurance,term insurance,life insurance,insurance comparisons">
   <META NAME="Author" CONTENT="COMPULIFE Software, Inc.">
   <TITLE>Request Application</TITLE>
   <link href="http://www.compulife.net/css/<? echo "$css"; ?>" type="text/css" rel="stylesheet"/>
   <script language="JavaScript" type="text/javascript">
   <!--
   function checkform()
   {
   var error = "";
   var crlf = "";
  	if (document.hrequest.Name.value=="")
	{
		error =  "Please enter your name ";
		crlf = "\r\n";
	}
	if (document.hrequest.Area_code.value=="")
	{
		error = error + crlf + "Please enter your area code ";
		crlf = "\r\n";
	}
	if (isNaN(parseInt(document.hrequest.Area_code.value)))
	{
		error = error + crlf + "Area code must be a number ";
		crlf = "\r\n";
	}
		
	if (document.hrequest.Area_code.value.length!=3)
	{
		error = error + crlf + "Area code must be 3 digits long ";
		crlf = "\r\n";
	}
	
	if (document.hrequest.Phone.value=="")
	{
		error = error + crlf + "Please enter your phone number ";
		crlf = "\r\n";
	}

	var strippedphone = document.hrequest.Phone.value.replace(/[\(\)\.\-\ ]/g, '');
	if (isNaN(strippedphone))
	{
		error = error + crlf + "Phone number must be a number ";
	}
	if (error!="")
	{
		alert(error);
		return false;
	}
	return true;
  }
  -->
  </script>
</HEAD>
<BODY>

<table width="400" align="center">
<tr>
  
  <td>
<?php
// if $errors is not empty, the form must have failed one or more validation 
// tests. Loop through each and display them on the page for the user
if (!empty($errors))
{
  echo "<div class='error'>Please fix the following errors:\n<ul>";
  foreach ($errors as $error)
    echo "<li>$error</li>\n";

  echo "</ul></div>"; 
}
?>
</td>
</tr>
</table>

<h5>Application Request</h5>


<table width="430" align="center" border="0">
<tr>
<td>
<p>You have requested an application for <? echo $data["Product"]; ?> from <? echo $data["Company"]; ?>  for a face amount of $<? echo $data["FaceAmount"]; ?>.
<p>Please complete the following information and we will get you started on the application process. 
</td>
</tr>
</table> 

<br><br>
<?// echo $_SERVER['PHP_SELF']; ?>

<form name="hrequest" action="<?=$_SERVER['PHP_SELF']?>" onSubmit="return checkform()" method="post">

<INPUT type="hidden" name="Submitted" value="<?= date('l - F jS, Y  [h:i:s a  T]'); ?>">
<INPUT type="hidden" name="----------------" value="----------------">

<table class="default_header" align="center" border="0">
<tr>
   <td colspan="2" align="center">
<b>Application Request</b>
</td>
</tr>


<tr>
  <td class="default_sub_header_center" colspan="2"><b>Required fields</b><font color="#D90000">*</font></td>
</tr>

  <tr><!-- Row 2 -->
    <td class="gray_cell_right" align="right"><b>Name<font color="#D90000">&nbsp;*&nbsp;</font>:</b></td>
    <td class="gray_cell"><input type="text" name="Name" size="40" value="<?=$fields['Name']?>" /></td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b> &nbsp;&nbsp;Phone Number<font color="#D90000">&nbsp;*&nbsp;</font>:</b></td>
    <td class="gray_cell"><font style="font-size:16px; font-family:verdana; color:#808080;">(</font><input type="text" name="Area_code" size="3" value="<?=$fields['Area_code']?>" /><font style="font-size:16px; font-family:verdana; color:#808080;">)</font>&nbsp;&nbsp;<input type="text" name="Phone" size="10" value="<?=$fields['Phone']?>" /></td>
  </tr>
  
  <tr><!-- Row 1 -->
    <td class="gray_cell_right">&nbsp;<b>Best time to call<font color="#D90000">&nbsp;*&nbsp;</font>:</b></td>
    <td class="gray_cell"><select size="1" id="Best_time_to_call" name="Best_time_to_call" value="<?=$fields['Best_time_to_call']?>">
	
<option value="Morning">Morning</option>
<option value="Afternoon">Afternoon</option>
<option value="Evening">Evening</option>
<option value="ASAP">ASAP</option>
</select>
</td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b>E-mail Address<font color="#D90000"><!-- &nbsp;*&nbsp; --></font>:</b></td>
    <td class="gray_cell"><input type="text" name="Email" size="45" value="<?=$fields['Email']?>" />&nbsp;&nbsp;</td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b>Message:</b></td>
    <td class="gray_cell"><textarea name="Message" rows="4" cols="34" value="<?=$fields['Message']?>"></textarea></td>
  </tr>


<tr class="default_header">
  <td colspan="2" align="center"><input type="submit" name="submit" value="Submit Request" />
</td>
</tr>
</table>

<br><br>

<?php	OutputDataAsHiddenFields(); ?>
</form>



</body>
</html>
