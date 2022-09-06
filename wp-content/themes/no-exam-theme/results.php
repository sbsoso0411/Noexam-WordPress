<?php
/**
 * Template Name: Results
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */


$_POST['page'] = 2;
include("header.php");
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

$url = "/post-page-new/";
switch ($Smoker) {
    case 'Y':
        $Smok = 'Tobacco';
        break;
    case 'N':
        $Smok = 'Non Tobacco';
        break;
}
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
//	$dataA = "BirthMonth=$BirthMonth&Birthday=$Birthday&BirthYear=$BirthYear&Sex=$Sex&State=$State&Smoker=$Smoker&Health=$Health&FaceAmount=$FaceAmount&NewCategory=$Cat&ModeUsed=M&usl=JH74964HS7&cid=dfg84kd84ks3";
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


    $obj = new stdClass();

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
        $obj->annual= $resNew[0]->price*12;
        $obj->monthly= $resNew[0]->price;
        $obj->rate_class= $HealthClass.' '.$Smok;
        $obj->category = $Cat;
	} else
	{
		$resA ='';
	}

	return $obj;

    /**
     * I don't use below code
     */
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
			$url = "/post-page-new/";  // in between the quotes you would put the current url
		
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
				<form action=\"$url\" method=post style='margin-bottom: 0px;'>
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
					<input type=hidden name=\"fterm\" value=\"$Term\">
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

// get rate for SBLI
function getSBLIRate($data) {
	global $wpdb;

	$table_name = 'wp_sbli_rates';

	$sql = "SELECT * FROM $table_name  WHERE `foot` = " .$data['Height_ft']. "  and `inch` = ".$data['Height_in'];
	$result = $wpdb->get_results($sql);

    if (!$result) {
        return null;
    }

    if ($data['smoker'] == 'N') {
        if ($data['weight'] <= $result[0]->ppnn_hi) {
            return 'PPNN';
        }
        else if ($data['weight'] <= $result[0]->pnn_hi) {
            return 'PNN';
        }
        else if ($data['weight'] <= $result[0]->snn_hi) {
            return 'SNN';
        }
        else if ($data['weight'] <= $result[0]->stnn_hi) {
            return 'STNN';
        }
    }
    else{
        if ($data['weight'] <= $result[0]->pn_hi) {
            return 'PN';
        }
        else if ($data['weight'] <= $result[0]->stn_hi) {
            return 'STN';
        }
        else {
            return '';
        }
    }

}

// generate sbli rate class name
function getSBLIRateClassName($type) {
    if ($type == 'PPNN') {
        return 'Preferred + Non-Nicotine';
    }
    else if ($type == 'PNN') {
        return 'Preferred Non-Nicotine';
    }
    else if ($type == 'SNN') {
        return 'Select Non-Nicotine';
    }
    else if ($type == 'STNN') {
        return 'Standard Non-Nicotine';
    }
    else if ($type == 'PN') {
        return 'Preferred Nicotine';
    }
    else if ($type == 'STN') {
        return 'Standard Nicotine';
    }

    return 'Unknown';
}

// Get Annual Price
function getSBLIAnnualPrice($sbli_price, $band) {

    global $FaceAmount;
    // (500*.276+72)
    // $500: $500,000 / 1000, $72: fee : Constant

    $price = 0;
    if ($band == 1) {
        $price = $sbli_price->band1;
    }
    else if ($band == 2) {
        $price = $sbli_price->band2;
    }
    else if ($band == 3) {
        $price = $sbli_price->band3;
    }
    else if ($band == 4) {
        $price = $sbli_price->band4;
    }
    else if ($band == 5) {
        $price = $sbli_price->band5;
    }
    else {
        return 0;
    }

    return ($FaceAmount / 1000.0 * $price + 72);
}

// Get SBLI Band
function getSBLIBand($amount) {
    if ($amount >= 100000 && $amount <= 249999) {
        return 1;
    }
    else if ($amount >= 250000 && $amount <= 499999) {
        return 2;
    }
    else if ($amount == 500000) {
        return 3;
    }
    else if ($amount >= 500001 && $amount <= 999999) {
        return 4;
    }
    else if ($amount >= 100000) {
        return 5;
    }

    return 0;
}

// returns the record filtered by age, type, sex
function getSBLIPrices($data){

    global $wpdb;

    $table_name = 'wp_sbli_prices';

    $sql = "SELECT * FROM $table_name  WHERE `age` = " .$data['age']. "  and `type` = '".$data['type']. "'  and `sex` = '".$data['sex']."'";
    $result = $wpdb->get_results($sql);
    return $result;

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
		$("#recalc").click(function(){
			$("#Famount").hide();
			$(".height_class").hide();
			$(".weight_class").hide();
			$("#coverage-amount-lbl").hide();
			$("#progress-lbl").show();
			$("#progress-bar").show();
			setTimeout(
				function() 
				{
				    $.ajax({
					type: "POST",
					url: "/quote-reload/",
					data: {
						userID: $("#UserSessionID").attr('data-sessionID'),
						currentFaceAmount: $("#FaceAmount").val(),
						currentHeight_ft: $(".Height_ft").val(),
						currentHeight_in: $(".Height_in").val(),
						currentweight: $("#weight").val(),
						currentHealth: $(".helth_class").text()
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
						$(".helth_class").text(JSON.parse(data));
						$("input[name='fHealth']" ).val(JSON.parse(data));
					}
					else 
					{
						document.getElementById("health-class").style.color = "#FF726D"; 
						$(".helth_class").text(JSON.parse(data));
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
					<li><strong>Name:</strong> <?php echo $FirstName. ' '. $LastName;?></li>
					<li><strong>Email:</strong> <?php echo $Email;?> </li>
					<li><strong>Sex:</strong> <?php echo $Sex;?></li>
					<li><strong>DOB:</strong> <?php echo $BirthMonth." / ".$Birthday." / ".$BirthYear;?></li>
					<li><strong>Phone:</strong> <?php echo $HomePhone;?></li>
					<li id="health-class">
                        <strong>Health Class:</strong>
                        <div class="detail helth_class" ID="txt" style="display: inline-block;">
                            <?php echo $HealthClass;?>
                        </div>
                    </li>
				</ul>

				<div class="heightcon">
					<label>Height</label>
					<select name="fHeight_ft" id = "fHeight_ft" class="Height_ft rec_class">
                        <?php for( $i= 4 ; $i <= 6 ; $i++ ) {?>
                            <option value = "<?php echo $i; ?>" <?php if ($Height_ft == $i || ($i ==5 && !isset($Height_ft))) {echo "selected";}?>><?php echo $i; ?>ft</option>
                        <?php } ?>
					</select>
                    <select name="fHeight_in" id = "fHeight_in" class="Height_in rec_class">
                        <?php for( $i= 0 ; $i <= 11 ; $i++ ) {?>
                            <option value = "<?php echo $i; ?>" <?php if ($Height_in == $i || ($i ==10 && !isset($Height_in))) {echo "selected";}?>><?php echo $i; ?>in</option>
                        <?php } ?>
                    </select>
			   </div>
				<div class="weightcon">
					<label>Weight</label>
                    <input style="float: left; height: 30px;" id = "weight" type="text" size=3  value="<?php if (isset($weight)){echo $weight;} else {echo "165";}?>" min="1" max="500" name="fweight" class="rec_class weight_input">
                    <label>&nbsp;lbs</label>
				</div>
				<div class="coveragecon">
					<label>Coverage Amount</label>

                    <form class = "form-face-amount" name = "form-face-amount" method = "post" action="/quote-results/" style="margin-bottom: 0; display: inline-block; ">

                        <select name="" id="FaceAmount" name="fFaceAmount">
                            <?php for( $i= 50000 ; $i <= 500000 ; $i = $i + 50000 ) {?>
                                <option value = "<?php echo $i; ?>" <?php if ($FaceAmount == $i) {echo "selected";}?>>$<?php echo number_format($i); ?></option>
                            <?php } ?>
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

				<div class="btnarea">
					<a id="recalc" href="#">Update</a>
				</div>
			</div>
        </div>
    </div>

    <?php

    // get prices
    the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
    wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );


    /**
     * SBLI Rate Calculation
     */
    // it will return one of these rate class string: PPNN, PNN, SNN, STNN, PN, STN
    $sbli_rate = getSBLIRate(array ('Height_ft' => $Height_ft, 'Height_in' => $Height_in, 'weight' => $weight, 'smoker' => $Smoker,));
    $age = calculate_age($BirthYear.'-'.$BirthMonth.'-'.$Birthday);


    //get sbli prices
    $sbli_prices = getSBLIPrices(array('sex' => $Sex, 'type' => $sbli_rate, 'age' => $age));

    ?>

    <?php
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");


    $Health = HealthClass2Health($HealthClass);
    $rateclass = HealthClass2RateClass($HealthClass);

    $quotesdata = "";
    $terms = array(10, 15, 20, 25, 30);

    // build price array
    $prices = array('sbli' => array(), 'sagicor' => array());
    $sbli_band = getSBLIBand($FaceAmount);
    $sbli_rate_class = getSBLIRateClassName($sbli_rate);


    //product collection information
    $product_collection = array(

        'sagicor' => array(
            'logo_url' => 'http://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/logo-s.png',
            'product_name' => 'Sage Term',
            'phone_interview' => 'No',
            'avg_approval_time' => '24 - 48 hours',
            'rate_class' => $rateclass
        ),
        'sbli' => array(
            'logo_url' => 'http://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sb-logo.png',
            'product_name' => 'SBLI Term',
            'phone_interview' => 'Yes',
            'avg_approval_time' => '5 - 7 Days',
            'rate_class' => $sbli_rate_class
        )
    );


    foreach ($sbli_prices as &$sbli_price):
        $sbli_price ->annual = getSBLIAnnualPrice($sbli_price, $sbli_band);
        $sbli_price ->monthly = 0.087 * $sbli_price ->annual; //0.087 is const
        $prices['sbli'][(string)$sbli_price->term] = $sbli_price;
    endforeach;

    // this is for 10 year
    $prices['sagicor']['10'] = getProducts(3,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);
    $prices['sagicor']['15'] = getProducts(4,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);
    $prices['sagicor']['20'] = getProducts(5,$BirthMonth,$Birthday,$BirthYear,$Sex,$State,$Smoker,$Health,$FaceAmount,$FirstName,$LastName,$HealthClass,$HomePhone,$Email,$weight,$height,$Height_ft,$Height_in,$form_short, $referrer);

    ?>
    <?php foreach ($terms as &$term):  ?>
        <div class="container">
            <div class="row whitecon">
                <div class="resultcon">
                    <h2><?=$term?> Year Level Term</h2>

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
                                <p>Annual</p>
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

                        <?php foreach ($product_collection as  $product_key => $product_item):  ?>
                            <?php
//                                var_dump($product_item);
                            ?>
                            <?php if ($prices[$product_key][$term] && $prices[$product_key][$term]->annual > 0 ): ?>
                                <div class="resultcontent" style="clear: both;">
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
                                            <p>annual</p>
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
                                        <a href="#"><img src="<?=$product_item['logo_url']?>"></a>
                                    </div>
                                    <div class="resultcontentcol product">
                                        <p><?=$product_item['product_name']?></p>
                                    </div>
                                    <div class="resultcontentcol rateclass">
                                        <p><?=$product_item['rate_class']?></p>
                                    </div>
                                    <div class="resultcontentcol ammial">
                                        <p>$<?=number_format($prices[$product_key][$term]->annual, 2)?></p>
                                    </div>
                                    <div class="resultcontentcol monthly">
                                        <p>$<?=number_format($prices[$product_key][$term]->monthly, 2)?></p>
                                    </div>
                                    <div class="resultcontentcol p-number">
                                        <p><?=$product_collection[$product_key]['phone_interview']?></p>
                                    </div>
                                    <div class="resultcontentcol avgtime">
                                        <p><?=$product_collection[$product_key]['avg_approval_time']?></p>
                                    </div>
                                    <div class="resultcontentcol apply">
										<?php if ($product_key == 'sagicor'): ?>

                                            <form action="<?php echo $url; ?>" method=post style='margin-bottom: 0px;'>
                                                <input type=hidden name="pageID" value="2">
                                                <input type=hidden name="fFirstName" value="<?php echo $FirstName; ?>">
                                                <input type=hidden name="fLastName" value="<?php echo $LastName; ?>">
                                                <input type=hidden name="fHomePhone" value="<?php echo $HomePhone; ?>">
                                                <input type=hidden name="fEmail" value="<?php echo $Email; ?>">
                                                <input type=hidden name="fBirthMonth" value="<?php echo $BirthMonth; ?>">
                                                <input type=hidden name="fBirthday" value="<?php echo $Birthday; ?>">
                                                <input type=hidden name="fBirthYear" value="<?php echo $BirthYear; ?>">
                                                <input type=hidden name="fSex" value="<?php echo $Sex; ?>">
                                                <input type=hidden name="fState" value="<?php echo $State; ?>">
                                                <input type=hidden name="fSmoker" value="<?php echo $Smoker; ?>">
                                                <input type=hidden name="fHealthClass" value="<?php echo $HealthClass; ?>">
                                                <input type=hidden name="fFaceAmount" value="<?php echo $FaceAmount; ?>">
                                                <input type=hidden name="fheight" value="<?php echo $height; ?>">
                                                <input type=hidden name="fHeight_ft" value="<?php echo $Height_ft; ?>">
                                                <input type=hidden name="fHeight_in" value="<?php echo $Height_in; ?>">
                                                <input type=hidden name="fweight" value="<?php echo $weight; ?>">
                                                <input type=hidden name="fCatagory" value="<?php echo $prices[$product_key][$term]->category; ?>">
                                                <input type=hidden name="form_short" value="<?php echo $form_short; ?>">
                                                <input type=hidden name="referrer" value="<?php echo $referrer; ?>">
                                                <!--fields from VAMdB-->
                                                <input type=hidden name="frateclass" value="<?php echo $HealthClass.' '.$Smok;?>">
                                                <input type=hidden name="fterm" value="<?php echo $term?>">
                                                <input type=hidden name="fpremium" value="<?php echo number_format($prices[$product_key][$term]->monthly, 2); ?>">
                                                <input type=hidden name="fannualpremium" value = "<?php echo number_format($prices[$product_key][$term]->annual, 2);?>">
                                                <input type=hidden name="fcompany" value="Sagicor Life Insurance Company">
                                                <input type=hidden name="fproduct" value="SI - Sage Term <?php echo $term;?> (Simplified Issue)">
                                                <!--undefined fields-->
                                                <input type=hidden name="fHealth" value="<?php echo $Health; ?>">
                                                <input type=hidden name="fgclid_field" value="<?php echo $gclid; ?>">
                                                <input type=hidden name="fbing_field" value="<?php echo $bingid; ?>">
                                                <input id="recalck" type=submit value="Apply Now">
                                            </form>

                                        <?php elseif ($product_key == 'sbli'): ?>
                                            <form action="<?php echo $url; ?>" method=post style="margin-bottom: 0px;">
                                                <input type=hidden name="pageID" value="100">
                                                <input type=hidden name="fFirstName" value="<?php echo $FirstName; ?>">
                                                <input type=hidden name="fLastName" value="<?php echo $LastName; ?>">
                                                <input type=hidden name="fHomePhone" value="<?php echo $HomePhone; ?>">
                                                <input type=hidden name="fEmail" value="<?php echo $Email; ?>">
                                                <input type=hidden name="fBirthMonth" value="<?php echo $BirthMonth; ?>">
                                                <input type=hidden name="fBirthday" value="<?php echo $Birthday; ?>">
                                                <input type=hidden name="fBirthYear" value="<?php echo $BirthYear; ?>">
                                                <input type=hidden name="fSex" value="<?php echo $Sex; ?>">
                                                <input type=hidden name="fState" value="<?php echo $State; ?>">
                                                <input type=hidden name="fSmoker" value="<?php echo $Smoker; ?>">
                                                <input type=hidden name="fHealthClass" value="<?php echo $HealthClass; ?>">
                                                <input type=hidden name="fFaceAmount" value="<?php echo $FaceAmount; ?>">
                                                <input type=hidden name="fheight" value="<?php echo $height; ?>">
                                                <input type=hidden name="fHeight_ft" value="<?php echo $Height_ft; ?>">
                                                <input type=hidden name="fHeight_in" value="<?php echo $Height_in; ?>">
                                                <input type=hidden name="fweight" value="<?php echo $weight; ?>">
                                                <input type=hidden name="form_short" value="<?php echo $form_short; ?>">
                                                <input type=hidden name="referrer" value="<?php echo $referrer; ?>">
                                                <!--fields from VAMdB-->
                                                <input type=hidden name="frateclass" value="<?php echo $product_item['rate_class'];?>">
                                                <input type=hidden name="fterm" value="<?php echo $term?>">
                                                <input type=hidden name="fpremium" value="<?php echo number_format($prices[$product_key][$term]->monthly, 2); ?>">
                                                <input type=hidden name="fannualpremium" value = "<?php echo number_format($prices[$product_key][$term]->annual, 2);?>">
                                                <input type=hidden name="fcompany" value="SBLI">
                                                <input type=hidden name="fproduct" value="SBLI Term <?php echo $term;?>">
                                                <!--undefined fields-->
                                                <input type=hidden name="fHealth" value="<?php echo $Health; ?>">
                                                <input type=hidden name="fgclid_field" value="<?php echo $gclid; ?>">
                                                <input type=hidden name="fbing_field" value="<?php echo $bingid; ?>">
                                                <input id="recalck" type=submit value="Apply Now">
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>


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