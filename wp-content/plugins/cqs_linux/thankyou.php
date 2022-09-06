<?php


$css = "unknown";
	if (isset($_REQUEST["css"])) $css= $_REQUEST["css"];

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
   <title>Application Request</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">   
   <meta name="description" content="No other insurance comparison website will give you more competitive pricing information about a broader array of companies and life insurance products.">
   <meta name="keywords" content="term life insurance,term insurance,life insurance,insurance comparisons">
   <meta name="Author" content="COMPULIFE Software, Inc.">   
   <link href="http://www.compulife.net/css/<? echo "$css"; ?>" type="text/css" rel="stylesheet">
</head>
<body>


<br><br>


<table align="center" border="0">
<tr>

  <td valign="center" align="center" style="color: #000000; font-family: Arial, Verdana, Helvetica, sans-serif; 
font-size: 12pt; font-weight: bold; text-align: center;">Application Request</td>

</tr>

<tr>
   <td align="center"><br><p style="color: #000000; font-family: Arial, Verdana, Helvetica, sans-serif;
font-size:10pt; font-weight: normal; text-align: center;">&nbsp;&nbsp;Your application request was successful!<br><br>&nbsp;&nbsp;A licensed agent will contact you to start the application<br>&nbsp;&nbsp;process and answer any further questions you may have.&nbsp;&nbsp;<br><br>
</td>
</tr>
</table>

<br>

<table align="center" border="0">
<tr>
<td>

&nbsp;&nbsp;<input type="button" VALUE="Run Another Quote" onClick="history.go(-3);return true;"></form>
</td>


</body>
</html>
