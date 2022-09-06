<?php

$sex = "unknown";
	if (isset($_REQUEST["Sex"])) $sex = $_REQUEST["Sex"];
$Aage = "unknown";
	if (isset($_REQUEST["ActualAge"])) $Aage= $_REQUEST["ActualAge"];
$Nage = "unknown";
	if (isset($_REQUEST["NearestAge"])) $Nage= $_REQUEST["NearestAge"];
$month = "unknown";
	if (isset($_REQUEST["BirthMonth"])) $month= $_REQUEST["BirthMonth"];
$day = "unknown";
	if (isset($_REQUEST["Birthday"])) $day = $_REQUEST["Birthday"];
$year = "unknown";
	if (isset($_REQUEST["BirthYear"])) $year= $_REQUEST["BirthYear"];
$smoker = "unknown";
	if (isset($_REQUEST["Smoker"])) $smoker = $_REQUEST["Smoker"];
$healthcat = "unknown";
	if (isset($_REQUEST["HealthCategory"])) $healthcat= $_REQUEST["HealthCategory"];
$health = "unknown";
	if (isset($_REQUEST["Health"])) $health= $_REQUEST["Health"];
$modeused = "unknown";
	if (isset($_REQUEST["ModeUsed"])) $modeused = $_REQUEST["ModeUsed"];
$state = "unknown";
	if (isset($_REQUEST["State"])) $state= $_REQUEST["State"];
$cat = "unknown";
	if (isset($_REQUEST["Category"])) $cat= $_REQUEST["Category"];
$Company = "unknown";
	if (isset($_REQUEST["Company"])) $Company = $_REQUEST["Company"];
$Product = "unknown";
	if (isset($_REQUEST["Product"])) $Product= $_REQUEST["Product"];
$Face = "unknown";
	if (isset($_REQUEST["FaceAmount"])) $Face= $_REQUEST["FaceAmount"];
$PremiumAnnual = "unknown";
	if (isset($_REQUEST["PremiumAnnual"])) $PremiumAnnual = $_REQUEST["PremiumAnnual"];
$Premium = "unknown";
	if (isset($_REQUEST["PremiumMode"])) $Premium= $_REQUEST["PremiumMode"];
$userlocation = "unknown";
	if (isset($_REQUEST["UserLocation"])) $userlocation= $_REQUEST["UserLocation"];
$email = "unknown";
	if (isset($_REQUEST["email"])) $email= $_REQUEST["email"];
$to = "unknown";
	if (isset($_REQUEST["email"])) $to= $_REQUEST["email"];
$from = "unknown";
	if (isset($_REQUEST["Email"])) $Email= $_REQUEST["Email"];
$css = "unknown";
	if (isset($_REQUEST["css"])) $css= $_REQUEST["css"];

	

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

if (!isset($_POST['submit']))
{
	// import the validation library
	require("validation.php");

	echo '11111111111111111111111111111111111111';
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
	if (empty($errors))
	{  
		$fields = $_POST;
	}

	// no errors! redirect the user to the thankyou page (or whatever)
	else 
	{

		$ThanksURL = "thankyou.php";	
		{
			//echo "<div class='notify'>$ThanksURL</div>";

			require_once "Mail.php";

			$from = "NOREPLY@YOUR_WEBSITE";
			$to = $email;
			$subject = "Application Request";
			$body = "Application Request\n\n";

			foreach($_POST as $Field=>$Value) 
			{
				$body .= "$Field: $Value\n";
			}

			$host = "MAIL.YOUR_WEBSITE.COM";
			$username = "ADDRESS@YOUR_WEBSITE.COM";
			$password = "YOUR_PASSWORD";

			$headers = array ('From' => $from,
					'To' => $to,
					'Subject' => $subject);
			$smtp = Mail::factory('smtp',
					array ('host' => $host,
						'auth' => true,
						'username' => $username,
						'password' => $password));
			$mail = $smtp->send($to, $headers, $body);
			if (PEAR::isError($mail)) {
				echo("<p>" . $mail->getMessage() . "</p>");
			} else {
				echo("<p>Message successfully sent!</p>");
			}
		}
		header("Location: $ThanksURL");
	}

}
echo '22222222222222222222222222222222222222222';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>Request Application</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">   
   <meta name="description" content="No other insurance comparison website will give you more competitive pricing information about a broader array of companies and life insurance products.">
   <meta name="keywords" content="term life insurance,term insurance,life insurance,insurance comparisons">
   <meta name="Author" content="COMPULIFE Software, Inc.">   
   <link href="http://www.your_website.com/css/<? echo "$css"; ?>" type="text/css" rel="stylesheet">
</head>
<body>

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
<p>You have requested an application for <? echo "$Product"; ?> from <? echo "$Company"; ?>  for a face amount of $<? echo "$Face"; ?>.
<p>Please complete the following information and we will get you started on the application process. 
</td>
</tr>
</table> 

<br><br>
<?// echo $_SERVER['PHP_SELF']; ?>
<? //die(var_dump($_SERVER['PHP_SELF'])); ?>

<form action="<?=$_SERVER['PHP_SELF']?>" method="post">

<input type="hidden" name="Submitted" value="<?= date('l - F jS, Y  [h:i:s a  T]'); ?>">
<input type="hidden" name="----------" value="----------">

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
    <td class="gray_cell"><input type="text" name="Name" size="40" value="<?=$fields['Name']?>"></td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b> &nbsp;&nbsp;Phone Number<font color="#D90000">&nbsp;*&nbsp;</font>:</b></td>
    <td class="gray_cell"><font style="font-size:16px; font-family:verdana; color:#808080;">(</font><input type="text" name="Area_code" size="3" value="<?=$fields['Area_code']?>"><font style="font-size:16px; font-family:verdana; color:#808080;">)</font>&nbsp;&nbsp;<input type="text" name="Phone" size="10" value="<?=$fields['Phone']?>"></td>
  </tr>
  
  <tr><!-- Row 1 -->
    <td class="gray_cell_right">&nbsp;<b>Best time to call<font color="#D90000">&nbsp;*&nbsp;</font>:</b></td>
    <td class="gray_cell"><select size="1" id="Best_time_to_call" name="Best_time_to_call" value="<?=$fields['Best_time_to_call']?>">
	
<option value="Morning">Morning</option>
<option value="Afternoon">Afternoon</option>
<option value="Evening">Evening</option>
<option value="ASAP">ASAP</option>
</td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b>E-mail Address<font color="#D90000"><!-- &nbsp;*&nbsp; --></font>:</b></td>
    <td class="gray_cell"><input type="text" name="Email" size="45" value="<?=$fields['Email']?>">&nbsp;&nbsp;</td>
  </tr>
  
  <tr><!-- Row 2 -->
    <td class="gray_cell_right"><b>Message:</b></td>
    <td class="gray_cell"><textarea name="Message" rows="4" cols="34" value="<?=$fields['Message']?>"></textarea></td>
  </tr>

<input type="hidden" name="---------" value="---------">

<input type="hidden" name="Sex" value="<?echo "$sex"; ?>">
<input type="hidden" name="ActualAge" value="<?echo "$Aage"; ?>">
<input type="hidden" name="NearestAge" value="<?echo "$Nage"; ?>">
<input type="hidden" name="BirthMonth" value="<?echo "$month"; ?>">
<input type="hidden" name="Birthday" value="<?echo "$day"; ?>">
<input type="hidden" name="BirthYear" value="<?echo "$year"; ?>">
<input type="hidden" name="Smoker" value="<?echo "$smoker"; ?>">
<input type="hidden" name="Health" value="<?echo "$health"; ?>">
<input type="hidden" name="State" value="<?echo "$state"; ?>">
<input type="hidden" name="FaceAmount" value="<? echo "$$Face"; ?>">
<input type="hidden" name="Category" value="<?echo "$cat"; ?>">
<input type="hidden" name="Company" value="<? echo "$Company"; ?>">
<input type="hidden" name="Product" value="<? echo "$Product"; ?>">
<input type="hidden" name="HealthCategory" value="<?echo "$healthcat"; ?>">
<!-- <input type="hidden" name="ModeUsed" value="<?echo "$modeused"; ?>"> -->
<input type="hidden" name="PremiumAnnual" value="<?echo "$$PremiumAnnual"; ?>">
<input type="hidden" name="PremiumMode" value="<?echo "$$Premium"; ?>">
<input type="hidden" name="--------" value="--------">
<input type="hidden" name="email" value="<? echo "$email"; ?>">

<!-- <input type="hidden" name="UserLocation" value="<?echo "$userlocation"; ?>"> -->
<input type="hidden" name="-------" value="-------">
<input type="hidden" name="css" value="<? echo "$css"; ?>">
<input type="hidden" name="------" value="------">
  
<tr class="default_header">
  <td colspan="2" align="center"><input type="submit" name="submit" value="Submit Request">
</td>
</tr>
</table>

<br><br>

</form>



</body>
</html>
