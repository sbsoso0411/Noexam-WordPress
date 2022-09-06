<?php
/**
 * Template Name: Quote Results
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
$_POST['page'] = 2;
include("header2.php");
// Helpers
require("inc/helpers.php");
require("inc/LoadFromDb.php");
// Storage
require("inc/storage.php");
$FirstName 		=	$_SESSION -> fFirstName;
$LastName 		=	$_SESSION -> fLastName;
$HomePhone 		=	$_SESSION -> fHomePhone;
$Email			=	$_SESSION -> fEmail;
$BirthMonth 	= 	$_SESSION -> fBirthMonth;
$Birthday	 	= 	$_SESSION -> fBirthday;
$BirthYear		=	$_SESSION -> fBirthYear;
$Sex 			=	$_SESSION -> fSex;
$State 			=	$_SESSION -> fState;
$Smoker 		=	$_SESSION -> fSmoker;
$HealthClass 	=	$_SESSION -> fHealthClass;
$FaceAmount 	=	$_SESSION -> fFaceAmount;
$height 		=	$_SESSION -> fHeight_ft*12	+	$_SESSION -> fHeight_in;
$Height_ft 		=	$_SESSION -> fHeight_ft;
$Height_in		=	$_SESSION -> fHeight_in;
$weight 		=	$_SESSION -> fweight;
$OriginalHeight = 	$Height_ft."'".$Height_in;
$isFaceAmountChanged = $_SESSION -> isFaceAmountChanged;
$form_short = $_SESSION -> form_short;
$referrer = $_SESSION -> referrer;
?>
<?php
// Post to host
$quotesdata = "";
function PostToHost($host, $path, $data_to_send)
{
	$fp = fsockopen($host, 80);
	$rf1 = @$_SERVER['HTTP_HOST']; 
	$rf2 = @$_SERVER['SCRIPT_NAME']; 
	$rf3 = @$_SERVER['SCRIPT_FILENAME']; 

	fputs($fp, "POST $path HTTP/1.1\n");
	fputs($fp, "Host: $host\n");
	fputs($fp, "Referer: $rf1$rf2\r\n"); 
	fputs($fp, "Ref2: $rf3\r\n"); 
	fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-length: ".strlen($data_to_send)."\n");
	fputs($fp, "Connection: close\n\n");
	fputs($fp, $data_to_send);
	
	$y=0;
	while(!feof($fp))
	{
		$datavalue=fgets($fp, 1280);
		if(preg_match("/VAMdB/", $datavalue)) 
		{
			$result[$y]=$datavalue;
			$y++;
		}
	}
	fclose($fp);
	return $result;
}
// Get offers
function getProducts($cat,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer ){
	$host = "www.vamdb.com";
	$path = "/quotes/qts.php";
	$Cat = $cat;
	$dataA = "BirthMonth=$BirthMonth&Birthday=$Birthday&BirthYear=$BirthYear&Sex=$Sex&State=$State&Smoker=$Smoker&Health=$Health&FaceAmount=$FaceAmount&NewCategory=$Cat&ModeUsed=M&usl=JH74964HS7&cid=dfg84kd84ks3";
	// var_dump($HealthClass);
	$dataDB = array(
		'Birthday'=> $BirthYear.'-'.$BirthMonth.'-'.$Birthday,
		'Sex'=> $Sex,
		'FaceAmount'=> $FaceAmount,
		'HealthClass'=> $HealthClass,
		'Smoker'=>$Smoker,
		'Health'=>$Health,
		'Cat'=>$Cat
		);
	
	// $resA = PostToHost($host, $path, $dataA);
	// echo "<pre>";
	// var_dump($resA);
	// echo "</pre>";
	$resNew = PostToDB($dataDB);

	switch ($Cat) {
		case '5':
		$Term = '20';
		break;
		case '4':
		$Term = '15';
		break;
		case '3':
		$Term = '10';
		break;
		
	}
	switch ($Smoker) {
		case 'Y':
		$Smok = 'Tobacco';
		break;
		case 'N':
		$Smok = 'Non Tobacco';
		break;
	}

	// prepered data 
	if($resNew[0]!=NULL){
		$resDb = "";
		$resDb .= "Sagicor Life Insurance Company";
		$resDb .= " VAMdB ";
		$resDb .= "SI - Sage Term ".$Term." (Simplified Issue)";
		$resDb .= " VAMdB ";
		$resDb .= $resNew[0]->price;
		$resDb .= " VAMdB ";
		$resDb .= $HealthClass.' '.$Smok;
		$resDb .= " VAMdB ";
		$resDb .= $resNew[0]->price*12;
		$resDb .= " VAMdB ";
		$resDb .= "SAGI";
		$resA = array('0' => $resDb);
	} else
	{
		$resA ='';
	}

	$total = count($resA);
	for($i=0;$i<$total;$i++)
	{
		$q = ($i +1);
		$sec = explode(" VAMdB ", $resA[$i]);
		$sec5=trim("$sec[5]");
		
		$carrier=trim("$sec[0]");
		if ($carrier =="National Life Insurance Co of Vermont")
			$url = "https://secure.noexam.com/RunQuote.aspx";  // in between the quotes place the url for this company
		else
			$url = "/post-page/";  // in between the quotes you would put the current url
		
		$carrier=trim("$sec[0]");
		if ($carrier =="National Life Insurance Co of Vermont")
			$button = "Apply Online";  
		else
			$button = "Apply Now";  
		// if ($HealthClass == "Rated"){
		// 	$HealthClass = "Standard"; 
		// }
		if (stristr($sec[3],$HealthClass,false)){
			if ($_SESSION -> fgclid_field != ""){
				$gclid = $_SESSION -> fgclid_field;
			} else if ($_SESSION -> fbing_field != ""){
				$bingid = $_SESSION -> fbing_field;
			}

			$quotesdata .= "<TR>
			<TD class=\"bb\"><IMG SRC=\"../logos/$sec5.gif\" BORDER=0 ></TD>
			<TD class=\"s10\"><font class=\"s12b\">$sec[0]</font><BR>$sec[1]</TD>
			<TD class=\"s12\" align=right>$$sec[4]</TD>
			<TD class=\"s12\" align=right>$$sec[2] &nbsp;</TD>
			<TD class=\"s10\" align=center>$sec[3]</TD>
			<TD class=\"bb\"><br>
				<form action=\"$url\" method=post>
					<input type=hidden name=\"pageID\" value=\"2\">
					<input type=hidden name=\"fFirstName\" value=\"$FirstName\">
					<input type=hidden name=\"fLastName\" value=\"$LastName\">
					<input type=hidden name=\"fHomePhone\" value=\"$HomePhone\">
					<input type=hidden name=\"fEmail\" value=\"$Email\">
					<input type=hidden name=\"fBirthMonth\" value=\"$BirthMonth\">
					<input type=hidden name=\"fBirthday\" value=\"$Birthday\">
					<input type=hidden name=\"fBirthYear\" value=\"$BirthYear\">
					<input type=hidden name=\"fSex\" value=\"$Sex\">
					<input type=hidden name=\"fState\" value=\"$State\">
					<input type=hidden name=\"fSmoker\" value=\"$Smoker\">
					<input type=hidden name=\"fHealthClass\" value=\"$HealthClass\">
					<input type=hidden name=\"fFaceAmount\" value=\"$FaceAmount\">
					<input type=hidden name=\"fheight\" value=\"$height\">
					<input type=hidden name=\"fHeight_ft\" value=\"$Height_ft\">
					<input type=hidden name=\"fHeight_in\" value=\"$Height_in\">
					<input type=hidden name=\"fweight\" value=\"$weight\">
					<input type=hidden name=\"fCatagory\" value=\"$Cat\">
					<input type=hidden name=\"form_short\" value=\"$form_short\">
					<input type=hidden name=\"referrer\" value=\"$referrer\">
					<!--fields from VAMdB-->
					<input type=hidden name=\"frateclass\" value=\"$sec[3]\">
					<input type=hidden name=\"fterm\" value=\"$dbCat\">
					<input type=hidden name=\"fpremium\" value=\"$sec[2]\">
					<input type=hidden name=\"fannualpremium\" value = \"$sec[4]\">
					<input type=hidden name=\"fcompany\" value=\"$sec[0]\">
					<input type=hidden name=\"fproduct\" value=\"$sec[1]\">
					<!--undefined fields-->
					<input type=hidden name=\"fHealth\" value=\"$Health\">
					<input type=hidden name=\"fgclid_field\" value=\"$gclid\"> 
					<input type=hidden name=\"fbing_field\" value=\"$bingid\"> 
					<input type=submit value=\"$button\">
				</form>
			</TD>
		</TR>
		";
	}
}
if ($quotesdata != ""){
	echo "
	<div class=\"table-container\">
		<TABLE cellpadding=0 cellspacing=0 border=0 class='table'>
			<thead>
				<TR>
					<th class=\"s14\" align=center width=\"120px\">Company</th>
					<th class=\"s14\" align=center >Product</th>
					<th class=\"s14\" align=center width=\"60px\">Annual</th>
					<th class=\"s14\" align=center width=\"60px\">Monthly</th>
					<th class=\"s14\" align=center width=\"80px\">Rate Class</th>
					<th class=\"s14\" align=center width=\"70px\">Apply</th>
				</TR>
			</thead>
			$quotesdata
		</TABLE>
	</div>
	";
} else {
	echo "
	<div class=\"table-container\">
		<TABLE cellpadding=0 cellspacing=0 border=0 class='table'>
			<thead>
				<TR>
					<th class=\"s14\" align=center width=\"120px\">Company</th>
					<th class=\"s14\" align=center >Product</th>
					<th class=\"s14\" align=center width=\"60px\">Annual</th>
					<th class=\"s14\" align=center width=\"60px\">Monthly</th>
					<th class=\"s14\" align=center width=\"80px\">Rate Class</th>
					<th class=\"s14\" align=center width=\"70px\">Apply</th>
				</TR>
			</thead>
		</TABLE>
		<div style=\"margin:0; padding:10px; text-align:center;\">There are no offers for this request</div>
	</div>
	";
}
}
?>

<script type="text/javascript">
	$(document).ready(function(){
		getHealthClass();
		$('.info-icon').hover(
			function () {
				$(this).next().fadeIn();
			}, 
			function () {
				$(this).next().fadeOut();
			}
			);
		$("#recalck").click(function(){
			$("#Famount").hide();
			$(".height_class").hide();
			$(".weight_class").hide();
			$("#coverage-amount-lbl").hide();
			$("#progress-lbl").show();
			$("#progress-bar").show();
			setTimeout(
				function() 
				{			$.ajax({
					type: "POST",
					url: "/quote-reload/",
					data: {
						userID: $("#UserSessionID").attr('data-sessionID'),
						currentFaceAmount: $(".FaceAmount").val(),
						currentHeight_ft: $(".Height_ft").val(),
						currentHeight_in: $(".Height_in").val(),
						currentweight: $("#weight").val(),
						currentHealth: $(".detail-cell .helth_class").text()
					},
					success: function(data){
						$(".form-face-amount").submit();
					}
				});
			    //do something special
			}, 1500);
			
		});
		$('.rec_class').change(function(){
			getHealthClass();
		});
		function getHealthClass(){
			$("input[name='fHeight_ft']" ).val($(".Height_ft option:selected").val());
			$("input[name='fHeight_in']" ).val($(".Height_in option:selected").val());
			$("input[name='fweight']" ).val($(".weight_input").val());
			$("input[name='fheight']" ).val(($(".Height_ft option:selected").val()*12)+$(".Height_in option:selected").val());
			$.ajax({
				url: "/ratecalc/",
				type: 'POST',
				data:{
					ft : $(".Height_ft option:selected").val(),
					inches : $(".Height_in option:selected").val(),
					weight : $(".weight_input").val()
				},
				success:
				function (data) {
					if (JSON.parse(data) != "Please enter correct weight" && JSON.parse(data) != "Please enter correct height")
					{
						document.getElementById("health-class").style.color = "#555C60"; 
						$("#validation-error").html("");
						$(".detail-cell .helth_class").text(JSON.parse(data));
						$("input[name='fHealth']" ).val(JSON.parse(data));
					}
					else 
					{
						document.getElementById("health-class").style.color = "#FF726D"; 
						$(".detail-cell .helth_class").text(JSON.parse(data));	
						$("input[name='fHealth']" ).val(JSON.parse(data));
					}
				}                          
			});
		};
	});

	
</script>


<div id="form-area">
	<div id = "UserSessionID" style = "display:none;" data-sessionID = "<? echo session_id();?>"></div>
	<div class="container">
		<div class="form-container row">
			
			<div class="columns qr">
				<div class="">
					<header class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><!-- .entry-header -->  
					<ul class="bread">
						<li class="selected"><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
						<li><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
						<li><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
						<li><span>4</span><a href="#">Payment</a><div class="arrw"></div></li>
					</ul>             
				</div>
			</div>
			
            <!--<div class="seven columns">
            	<div class="form-right">
                  <img src="<?php bloginfo('template_directory'); ?>/images/security-logo-top.png" alt="" border="0" usemap="#Top" class="top_badge">
               <map name="Top">
                   <area shape="rect" coords="1,2,149,57" href="http://www.bbb.org/atlanta/business-reviews/insurance-life/no-exam-life-insurance-in-marietta-ga-27496155" target="_blank">
                   <area shape="rect" coords="164,3,269,60" href="http://secure.trust-guard.com/privacy/8683" target="_blank">
                   <area shape="rect" coords="280,1,406,57" href="http://www.shopperapproved.com/reviews/noexam.com/" target="_blank">
                 </map>      
              </div>
          </div> -->
          <div class="row">
          	
          	<?php

          	if ( have_posts() ) {

	// Start of the Loop
          		while ( have_posts() ) {
          			the_post();
          			?>
          			<div>
          				<h4 class="qr_top">Client details</h4>
          				<div class="table-container">
          					<div class = "row" ID = "client-details">
          						<div class = "details-row">
          							<div class = "detail-cell">
          								<label>First name:</label> 
          								<div class="detail">
          									<?php echo $FirstName;?>
          								</div>
          							</div>
          							<div class = "detail-cell">
          								<label>Last name:</label>
          								<div class="detail">
          									<?php echo $LastName;?>
          								</div>
          							</div>
          							<div class = "detail-cell">
          								<label>Email:</label> 
          								<div class = "detail">
          									<?php echo $Email;?>
          								</div>
          							</div>
          						</div>
          						<div class = "details-row">
          							<div class = "detail-cell">
          								<label>Birthdate:</label> 
          								<div class = "detail">
          									<?php echo $BirthMonth." / ".$Birthday." / ".$BirthYear;?>
          								</div>
          							</div>
          							<div class = "detail-cell">
          								<label>Sex:</label> 
          								<div class = "detail">
          									<?php echo $Sex?>
          								</div>
          							</div>
          							<div class = "detail-cell">
          								<label>Phone:</label> 
          								<div class = "detail">
          									<?php echo $HomePhone;?>
          								</div>
          							</div>
          							<div class = "detail-cell">
          							</div>
          						</div>
          						<div class = "details-row">
          							
          							<div class = "detail-cell height_class">
          								<label>Height:</label> 
          								<div class = "detail">
          									<select class="rec_class small Height_ft" name="fHeight_ft" id = "fHeight_ft">
          										<option value = "4" <?php if ($Height_ft == 4) 										{echo "selected";}?>>4</option>
          										<option value = "5" <?php if ($Height_ft == 5 or !isset($Height_ft))  	{echo "selected";}?>>5</option>
          										<option value = "6" <?php if ($Height_ft == 6) 										{echo "selected";}?>>6</option>
          									</select>
          									<label class="label-ft">ft</label>
          									<select class = "rec_class small Height_in" name="fHeight_in" id = "fHeight_in">
          										<option value = "0"	<?php if ($Height_in == 0)  										{echo "selected";}?>>0</option>
          										<option value = "1" <?php if ($Height_in == 1)  										{echo "selected";}?>>1</option>
          										<option value = "2" <?php if ($Height_in == 2)  										{echo "selected";}?>>2</option>
          										<option value = "3" <?php if ($Height_in == 3)  										{echo "selected";}?>>3</option>
          										<option value = "4" <?php if ($Height_in == 4)  										{echo "selected";}?>>4</option>
          										<option value = "5" <?php if ($Height_in == 5)  										{echo "selected";}?>>5</option>
          										<option value = "6" <?php if ($Height_in == 6)  										{echo "selected";}?>>6</option>
          										<option value = "7" <?php if ($Height_in == 7)  										{echo "selected";}?>>7</option>
          										<option value = "8" <?php if ($Height_in == 8)  										{echo "selected";}?>>8</option>
          										<option value = "9" <?php if ($Height_in == 9)  										{echo "selected";}?>>9</option>
          										<option value = "10" <?php if ($Height_in == 10 or !isset($Height_in))  	{echo "selected";}?>>10</option>
          										<option value = "11" <?php if ($Height_in == 11)  										{echo "selected";}?>>11</option>
          									</select>
          									<label class="label-in">in</label>

          									<?php /*echo "{$Height_ft}'{$Height_in}\""*/?>
          								</div>
          							</div>
          							<div class = "detail-cell weight_class">
          								<label>Weight:</label> 
          								<div class = "detail">
          									<?php /*echo $weight." lbs"*/?>
          									<input class="rec_class weight_input" id = "weight" type="text" size=3  value="<?php if (isset($weight)){echo $weight;} else {echo "165";}?>" min="1" max="500" name="fweight"> 
          									<label class="label-lbs">lbs</label>
          								</div>
          							</div>
          							<div class = "detail-cell" ID="health-class">
          								<label>Health class:</label>
          								<div class="detail helth_class" ID="txt" style="float:left">
          									<?php echo $HealthClass;?>
          									<!-- <input type="hidden" class="lbl" name="fHealthClass" id = "fHealthClass" value="<?php echo $HealthClass;?>" /> -->
          								</div>
          								
          							</div>
          						</div>
          					</div>



          					<div class = "products-filter">
          						<!--<h4>Filter</h4> -->
<!-- 			    <div class="row weight_class">
    	<div class="label"><label>Weight</label></div>
    	<div class="inputs" style = "width:128px;float:left;"><div class = "small_input">
			
		</div></div>
		<div><label style="padding:12px 0px 0px 6px;width:20px;float:left">lbs</label></div>
	</div> -->
		<!-- <div class="row height_class">
    	<div class="label"><label>Height</label></div>
    	<div class="inputs"><select class="rec_class small Height_ft" name="fHeight_ft" id = "fHeight_ft">
			<option value = "4" <?php if ($Height_ft == 4) 										{echo "selected";}?>>4</option>
			<option value = "5" <?php if ($Height_ft == 5 or !isset($Height_ft))  	{echo "selected";}?>>5</option>
			<option value = "6" <?php if ($Height_ft == 6) 										{echo "selected";}?>>6</option>
		</select>
		<label style="padding-top:15px;width:20px;float:left">ft</label>
		<select class = "rec_class small Height_in" name="fHeight_in" id = "fHeight_in">
			<option value = "0"	<?php if ($Height_in == 0)  										{echo "selected";}?>>0</option>
			<option value = "1" <?php if ($Height_in == 1)  										{echo "selected";}?>>1</option>
			<option value = "2" <?php if ($Height_in == 2)  										{echo "selected";}?>>2</option>
			<option value = "3" <?php if ($Height_in == 3)  										{echo "selected";}?>>3</option>
			<option value = "4" <?php if ($Height_in == 4)  										{echo "selected";}?>>4</option>
			<option value = "5" <?php if ($Height_in == 5)  										{echo "selected";}?>>5</option>
			<option value = "6" <?php if ($Height_in == 6)  										{echo "selected";}?>>6</option>
			<option value = "7" <?php if ($Height_in == 7)  										{echo "selected";}?>>7</option>
			<option value = "8" <?php if ($Height_in == 8)  										{echo "selected";}?>>8</option>
			<option value = "9" <?php if ($Height_in == 9)  										{echo "selected";}?>>9</option>
			<option value = "10" <?php if ($Height_in == 10 or !isset($Height_in))  	{echo "selected";}?>>10</option>
			<option value = "11" <?php if ($Height_in == 11)  										{echo "selected";}?>>11</option>
		</select>
		<label style="padding-top:15px;width:20px;float:left">in</label></div>
	</div> -->
	<div class = "row">
		<label ID = "coverage-amount-lbl" style="margin:3px 4px 4px 4px; padding:10px 0px 10px 0px">Coverage Amount</label>
		<label ID = "progress-lbl" style="margin:3px 4px 4px 4px; padding:10px 0px 10px 0px; display:none;">Progress</label>
		<img ID = "progress-bar" src="<?php bloginfo('template_directory'); ?>/images/progress-bar.gif" alt="Loading..." style = "display:none;"> 
		<form class = "form-face-amount" name = "form-face-amount" method = "post" action="/quote-results/">
			
			<select ID = "Famount" class = "FaceAmount" name="fFaceAmount" style="width:140px">
				<option value="50000" 	<?php if ($FaceAmount == "50000") 	{echo "selected";}?>>$50,000</option>
				<option value="100000" 	<?php if ($FaceAmount == "100000") 	{echo "selected";}?>>$100,000</option>
				<option value="150000" 	<?php if ($FaceAmount == "150000") 	{echo "selected";}?>>$150,000</option>
				<option value="200000"	<?php if ($FaceAmount == "200000") 	{echo "selected";}?>>$200,000</option>
				<option value="250000"	<?php if ($FaceAmount == "250000") 	{echo "selected";}?>>$250,000</option>
				<option value="300000"	<?php if ($FaceAmount == "300000") 	{echo "selected";}?>>$300,000</option>
				<option value="400000"	<?php if ($FaceAmount == "400000") 	{echo "selected";}?>>$400,000</option>
				<option value="500000"	<?php if ($FaceAmount == "500000") 	{echo "selected";}?>>$500,000</option>
			</select>
			
			<input type=hidden name="fBirthMonth" value="<?php echo $BirthMonth;?>">
			<input type=hidden name="fBirthday" value="<?php echo $Birthday;?>">
			<input type=hidden name="fBirthYear" value="<?php echo $BirthYear;?>">
			<input type=hidden name="fSex" value="<?php echo $Sex;?>">
			<input type=hidden name="fState" value="<?php echo $State;?>">
			<input type=hidden name="fSmoker" value="<?php echo $Smoker;?>">
			<input type=hidden name="fHealthClass" value="<?php echo $HealthClass;?>">
			<input type=hidden name="fFirstName" value="<?php echo $FirstName;?>">
			<input type=hidden name="form_short" value="<?php echo $form_short;?>">
			<input type=hidden name="referrer" value="<?php echo $referrer;?>">
			<input type=hidden name="fLastName" value="<?php echo $LastName;?>">
			<input type=hidden name="fHomePhone" value="<?php echo $HomePhone;?>">
			<input type=hidden name="fEmail" value="<?php echo $Email;?>">
			<input type=hidden name="fHeight_ft" value="<?php echo $Height_ft;?>">
			<input type=hidden name="fHeight_in" value="<?php echo $Height_in;?>">
			<input type=hidden name="fheight" value="<?php echo ($Height_ft*12)+$Height_in;?>">
			<input type=hidden name="fweight" value="<?php echo $weight;?>">
			<input type=hidden name="fHealth" value="<?php echo HealthClass2Health($HealthClass)?>">
			<input type=hidden name="isFaceAmountChanged" value = "1">
		</form>
		
	</div>
	<button id="recalck">Update</button>
</div>
</div>
</div>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	
	<div class="entry-content" style="overflow:visible;">
		
		<?php
		the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
		wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
		?>
		
		<?
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate");  
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

## To have the information sent to VAM dB as a new lead remove the "#" from the next 4 lines. For a new lead to be created there must be a name and either an email address or a phone included or the information will be dropped and no new lead created. Be advised some people will run multiple quotes for different amounts or term lengths which may create duplicate records in VAM dB.
/*
$sec[0] is the name of the company
$sec[1] is the name of the product
$sec[2] is the monthly premium
$sec[3] is the rate class
$sec[4] is the annual premium
$sec[5] is the image name for the logo
**/

$Health = HealthClass2Health($HealthClass);
$rateclass = HealthClass2RateClass($HealthClass);


$quotesdata = "";
echo 	"
<div class = \"level-term-contaiter\">
	<div style = \"float:left;\">
		<h4 class = \"qr_top1\">20 Year level term</h4>
	</div>
	<div style = \"float:left;\">
		<a class = \"info-icon\" href = \"#\" style = \"margin: 17px 10px;\">info</a>
		<div class=\"quote-results-tooltip\">
			A 20 Year Term policy is coverage that will last 20 years from the time you are approved for that coverage. Example: If your coverage was effective on 01/01/2015 the coverage would expire on 01/01/2035.
		</div>
	</div>
</div>	
";
getProducts(5,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);

$quotesdata = "";
echo 	"
<div class = \"level-term-contaiter\">
	<div style = \"float:left;\">
		<h4 class = \"qr_top1\">15 Year level term</h4>
	</div>
	<div style = \"float:left;\">
		<a class = \"info-icon\" href = \"#\" style = \"margin: 17px 10px;\">info</a>
		<div class=\"quote-results-tooltip\">
			A 15 Year Term policy is coverage that will last 15 years from the time you are approved for that coverage. Example: If your coverage was effective on 01/01/2015 the coverage would expire on 01/01/2030.
		</div>
	</div>
</div>	
";
getProducts(4,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);

$quotesdata = "";
echo 	"
<div class = \"level-term-contaiter\">
	<div style = \"float:left;\">
		<h4 class = \"qr_top1\">10 Year level term</h4>
	</div>
	<div style = \"float:left;\">
		<a class = \"info-icon\" href = \"#\" style = \"margin: 17px 10px;\">info</a>
		<div class=\"quote-results-tooltip\">
			A 10 Year Term policy is coverage that will last 10 years from the time you are approved for that coverage. Example: If your coverage was effective on 01/01/2015 the coverage would expire on 01/01/2025.
		</div>
	</div>
</div>
";
getProducts(3,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);

$quotesdata = "";
echo 	"
<div class = \"level-term-contaiter\">
	<div style = \"float:left;\">
		<h4 class = \"qr_top1\">No Lapse Universal Life - Best Value</h4>
	</div>
	<div style = \"float:left;\">
		<a class = \"info-icon\" href = \"#\" style = \"margin: 17px 10px;\">info</a>
		<div class=\"quote-results-tooltip\">
			This policy is designed to last for an individuals entire lifetime as long as the policy premiums are paid.
		</div>
	</div>
</div>
";
getProducts(8,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);
?>

</div><!-- .entry-content --><?php

			// Comments info.
if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
<span class="sep"> | </span>
<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'hellish' ), __( '1 Comment', 'hellish' ), __( '% Comments', 'hellish' ) ); ?></span><?php
}

			// Edit link
			//edit_post_link( __( 'Edit', 'hellish' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
?>

</div><!-- #post-<?php the_ID(); ?> --><?php

		// If comments are open or we have at least one comment, load up the comment template
if ( comments_open() || '0' != get_comments_number() )
	comments_template( '', true );

}

get_template_part( 'template-parts/numeric-pagination' );

}
else {
	get_template_part( 'template-parts/no-results' );
}
?>

</div>

</div>
<div class="form-footer">
	<img src="<?php bloginfo('template_directory'); ?>/images/security-logo.png" alt="" border="0" usemap="#Bottom">
	<map name="Bottom">
		<area shape="rect" coords="0,3,112,58" href="https://www.comodo.com/" target="_blank">
		<area shape="rect" coords="121,4,272,59" href="http://www.bbb.org/atlanta/business-reviews/insurance-life/no-exam-life-insurance-in-marietta-ga-27496155" target="_blank">
		<area shape="rect" coords="286,4,391,61" href="http://secure.trust-guard.com/privacy/8683" target="_blank">
		<area shape="rect" coords="401,4,527,60" href="http://www.shopperapproved.com/reviews/noexam.com/" target="_blank">
	</map>
</div>
</div>

	<div class="container">
		<div class="row">
         
				<div class="five columns">
				 <div class="entry-header">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</div><!-- .entry-header -->  
                    </div>
                    <div class="eleven columns progresslist">
					<ul>
						<li class="selected"><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
						<li><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
						<li><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
						<li><span>4</span><a href="#">Payment</a><div class="arrw"></div></li>
					</ul>             
				</div>
		 
        </div>
    </div>
    <div class="container">
		<div class="row whitecon">         
				<div class="sixteen columns titlearealist">
                	<ul>
                    	<li><strong>Name:</strong> James Smith</li>   
                        <li><strong>Email:</strong> james@email.com </li>
                        <li><strong>Sex:</strong> Male</li>     
                        <li><strong>DOB:</strong> 15/10/1982</li>   
                        <li><strong>Phone:</strong> +44726 438  990</li>   
                        <li><strong>Health Class:</strong> Preferred</li>
                    </ul>
                    
                    <div class="heightcon">
                    	<label>Height</label>
                        <select name="">
                        	<option>5ft</option>
                            <option>6ft</option>
                        </select>
                        <select name="">
                        	<option>10in</option>
                            <option>9in</option>
                        </select>
                   </div>
                    <div class="weightcon">
                    	<label>Weight</label>
                        <select name="">
                        	<option>165lb</option>
                            <option>160lb</option>
                        </select>
                    </div>
                    <div class="coveragecon">
                    	<label>Coverage Amount</label>
                        <select name="">
                        	<option>$250,000</option>
                            <option>$50,000</option>
                        </select>
                    </div>
                    
                    <div class="btnarea">
                    	<a href="#">Update</a>
                    </div>
                </div>
         </div>
    </div>
    <div class="container">
		<div class="row whitecon">         
		 <div class="resultcon">
         	<h2>10 Year Level Term</h2>
            
            <div class="resultrow">
            	<div class="resultTitle">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/logo-s.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Sage Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$900</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$75</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>No</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>24 - 72 hours</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sb-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>SBLI Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Standard</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$1200</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$100</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/banner-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Banner</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred Plus</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$600</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$50</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>Yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>3-5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                
            </div>
         </div>
       </div>
   </div>
    <div class="container">
		<div class="row whitecon">         
		 <div class="resultcon">
         	<h2>15 Year Level Term</h2>
            
            <div class="resultrow">
            	<div class="resultTitle">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/logo-s.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Sage Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$900</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$75</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>No</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>24 - 72 hours</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sb-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>SBLI Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Standard</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$1200</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$100</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/banner-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Banner</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred Plus</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$600</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$50</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>Yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>3-5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                
            </div>
         </div>
       </div>
   </div>
    <div class="container">
		<div class="row whitecon">         
		 <div class="resultcon">
         	<h2>20 Year Level Term</h2>
            
            <div class="resultrow">
            	<div class="resultTitle">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/logo-s.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Sage Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$900</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$75</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>No</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>24 - 72 hours</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sb-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>SBLI Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Standard</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$1200</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$100</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/banner-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Banner</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred Plus</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$600</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$50</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>Yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>3-5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                
            </div>
         </div>
       </div>
   </div>
    <div class="container">
		<div class="row whitecon">         
		 <div class="resultcon">
         	<h2>30 Year Level Term</h2>
            
            <div class="resultrow">
            	<div class="resultTitle">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/logo-s.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Sage Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$900</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$75</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>No</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>24 - 72 hours</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sb-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>SBLI Term</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Standard</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$1200</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$100</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                <div class="resultcontent">
               		<div class="resultTitle mobile" style="display:none;">
                	<div class="titlecol company">
                    	<p>Company</p>
                    </div>
                    <div class="titlecol product">
                    	<p>Product</p>
                    </div>
                    <div class="titlecol rateclass">
                    	<p>Rate Class</p>
                    </div>
                    <div class="titlecol ammial">
                    	<p>Ammial</p>
                    </div>
                    <div class="titlecol monthly">
                    	<p>Monthly</p>
                    </div>
                    <div class="titlecol p-number">
                    	<p>Phone Interview</p>
                    </div>
                    <div class="titlecol avgtime">
                    	<p>Avg Approval Time</p>
                    </div>
                    <div class="titlecol apply">
                    	<p>Apply</p>
                    </div>
                </div>
                
                	<div class="resultcontentcol company">
                    	<a href="#"><img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/banner-logo.png"></a>
                    </div>
                    <div class="resultcontentcol product">
                    	<p>Banner</p>
                    </div>
                    <div class="resultcontentcol rateclass">
                    	<p>Preferred Plus</p>
                    </div>
                    <div class="resultcontentcol ammial">
                    	<p>$600</p>
                    </div>
                    <div class="resultcontentcol monthly">
                    	<p>$50</p>
                    </div>
                    <div class="resultcontentcol p-number">
                    	<p>Yes</p>
                    </div>
                    <div class="resultcontentcol avgtime">
                    	<p>3-5 Days</p>
                    </div>
                    <div class="resultcontentcol apply">
                    	<button id="recalck">Apply Now</button>
                    </div>
                 
                </div>
                
            </div>
         </div>
       </div>
   </div>
           
</div><!-- #content-area -->

<div id="footer" class="footer-wrapper">
	<div class="container"><p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p></div>
</div>
<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<script type="text/javascript"> 
	var _ubaq = _ubaq || [];
	_ubaq.push(['trackGoal', 'convert']);
	
	(function() {
		var ub_script = document.createElement('script');
		ub_script.type = 'text/javascript';
		ub_script.src = 
		('https:' == document.location.protocol ? 'https://' : 'http://') + 
		'd3pkntwtp2ukl5.cloudfront.net/uba.js';
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(ub_script, s);
	}) ();
</script>
<!-- Google Code for Lead Capture Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 1021976105;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "d1-gCOKfl1cQqbyo5wM";
	var google_remarketing_only = false;
	/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
	<div style="display:inline;">
		<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1021976105/?label=d1-gCOKfl1cQqbyo5wM&amp;guid=ON&amp;script=0"/>
	</div>
</noscript>
<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"4000536"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>
<script type="text/javascript">
	var vm_conversion_type = 'lead';
	var vm_conversion_adv = '38364';
</script>
<script type="text/javascript" src="//marketplaces.vantagemedia.com/conversion.js" ></script>
<?php //get_footer(); ?>