<?php
/**
 * Template Name: Payment
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
$_POST['page'] = 7;
// header
include("header2.php"); 
// helpers
require("inc/helpers.php");
// storage
require("inc/storage.php");


$BankAccount = $_SESSION['fBankAccount'];
$RoutingNumber = $_SESSION['fRoutingNumber'];
$BankName = $_SESSION['fBankName'];
$monthly = $_SESSION['fpremium'];
$SocialSecurityNumber = $_SESSION['fSocialSecurityNumber'];
// beneficiary data
$BeneficiaryCounter = $_SESSION['fBeneficiaryCounter'];
?>

<script type="text/javascript">
	var beneficiaryCounter = 1;
	var deletedBeneficiaryCounter = 0;
	var enabledBeneficiaryCounter = 0;
	var element = "";
	var beneficiaryElement = "";
	var percentage = 0;

	if ($("#BeneficiaryCounter").val() != null){
		beneficiaryCounter = $("#BeneficiaryCounter").val();
		alert($("#BeneficiaryCounter").val());
	} else {
		beneficiaryCounter = 1;
	};
	
	function initialize(initBeneficiary){
		$(initBeneficiary).click(function(){
			element = "#Beneficiary"+$(this).attr("data-id");
			$(element).remove();
			deletedBeneficiaryCounter = deletedBeneficiaryCounter +1;
			buttonChangeCaption();
			showcounters();
		});
	};
	
	function enabledBeneficiaryCount(){
		var counter = 0;
		for (var i = 1; i <= beneficiaryCounter; i++){
			element = "#Beneficiary"+i;
			if ($(element).length){
				counter += 1;
			};
		};
		enabledBeneficiaryCounter = counter;
		return counter;
	};
	
	function beneficiaryValidate(){
		var error = 0;
		for (var i = 0; i <= beneficiaryCounter; i++ ){
			element = "#Beneficiary"+i;
			errorElement = "#BeneficiaryError"+i;
			if ($(element).length){
				$(errorElement).html("");
				element = "#BeneficiaryFirstName"+i;
				elem = "BeneficiaryFirstName"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"First Name\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryLastName"+i;
				elem = "BeneficiaryLastName"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Last Name\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryRelationship"+i;
				elem = "BeneficiaryRelationship"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Relationship\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryPercentage"+i;
				elem = "BeneficiaryPercentage"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Percentage\" must be filled in</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryAddress"+i;
				elem = "BeneficiaryAddress"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Address\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryCity"+i;
				elem = "BeneficiaryCity"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"City\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryState"+i;
				elem = "BeneficiaryState"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"State\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				}
				element = "#BeneficiaryZipCode"+i;
				elem = "BeneficiaryZipCode"+i;
				document.getElementById(elem).style.borderColor = "white";
				if ($(element).val() == ""){
					$(errorElement).append("<div style = \"margin: 4px 0px 0px 0px\">Field \"ZipCode\" must be filled in.</div>");
					document.getElementById(elem).style.borderColor = "red";
					error = 1;
				};
			};
		};
		return error;
	};
	
	function validate(){
	//var beneficiaryErr = 
		
		return beneficiaryValidate();
	
		// percentage = 0;
		// if (enabledBeneficiaryCount() > 0){
			// for (var i = 1; i <= beneficiaryCounter; i++ ){
				// element = "#BeneficiaryPercentage"+i;
				// if ($(element).length){
					// percentage += Number($(element).val());
				// };
			// };
			// if (percentage > 100 && beneficiaryErr != 0){
				// return 1;
			// } else if (percentage < 100 && beneficiaryErr != 0) {
				// return 2;
			// } else if (percentage == 100 && beneficiaryErr == 0){
				// return 0;
			// };
		// } else {
			// return 0;
		// }
	};
	
	
	function getBeneficiariesFields(){		
		var beneficiaryArray = new Array(beneficiaryCounter);
		// attr counter
		var beneficiaryAttr = 8;
		for (var i = 0; i <= beneficiaryCounter; i++){
			element = "#Beneficiary"+i;
			if ($(element).length){
				beneficiaryArray[i] = new Array(beneficiaryAttr);
				for (var j = 0; j < beneficiaryAttr; j++){
					switch (j) {
						case 0:	beneficiaryElement = "#BeneficiaryFirstName"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 1:	beneficiaryElement = "#BeneficiaryLastName"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 2:	beneficiaryElement = "#BeneficiaryRelationship"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 3:	beneficiaryElement = "#BeneficiaryPercentage"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 4:	beneficiaryElement = "#BeneficiaryAddress"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 5:	beneficiaryElement = "#BeneficiaryCity"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 6:	beneficiaryElement = "#BeneficiaryState"+i;
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
						case 7:	beneficiaryElement = "#BeneficiaryZipCode"+i
								beneficiaryArray[i][j] = $(beneficiaryElement).val();
								break;
					};
				};
			};
		};
		$("#BeneficiaryData").val(JSON.stringify(beneficiaryArray));
		$("#BeneficiaryCounter").val("");
		$("#BeneficiaryCounter").val(beneficiaryCounter);
	};
	
	function buttonChangeCaption(){
		if (beneficiaryCounter - deletedBeneficiaryCounter > 1) {
			$("#add-beneficiary").val("Add Another Beneficiary");
			$("#beneficiary-title").show();
		} else if (beneficiaryCounter - deletedBeneficiaryCounter == 1) {
			$("#add-beneficiary").val("Add Beneficiary");
			//$("#beneficiary-title").hide();
		}
	};
	
	function resetErrorHighlighting(){
		document.getElementById("fBankName").style.borderColor = "white";
		document.getElementById("fBankAccount").style.borderColor = "white";
		document.getElementById("fRoutingNumber").style.borderColor = "white";
	}
	
	function userFieldsValidate(){
		var error = 0;
		resetErrorHighlighting();
		$("#main-error-field").html("");
		if ($("#fBankName").val() == ""){
			$("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Bank Name\" must be filled in.</div>");
			document.getElementById("fBankName").style.borderColor = "red";
			error = 1;
		}
		if ($("#fBankAccount").val() == ""){
			$("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Bank Account\" must be filled in.</div>");
			document.getElementById("fBankAccount").style.borderColor = "red";
			error = 1;
		}
		if ($("#fRoutingNumber").val() == ""){
			$("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Routing Number\" must be filled in.</div>");
			document.getElementById("fRoutingNumber").style.borderColor = "red";
			error = 1;
		}
		return error;
	}
	
	$(document).ready(function(){
		if ($("#BeneficiaryCounter").val() != ""){
			beneficiaryCounter = Number($("#BeneficiaryCounter").val());
		} else {
			beneficiaryCounter = 1;
		}
		$("#request-button").click(function(){
			var error = validate(); 
			var mainError = userFieldsValidate();
			if (error == 0 && mainError == 0) {
				$("#error-field").hide();
				$("#main-error-field").hide();
				getBeneficiariesFields();
				$(".form-inputs").submit();
			} else {
				$("#error-field").show();
				$("#main-error-field").show();
			}
		});
		
		$("#add-beneficiary").click(function(){
			// template
			var beneficiaryTemplate = '\
				<div class = "row"  ID = "Beneficiary'+beneficiaryCounter+'" style = "padding:10px 0px 10px 10px; border: 2px solid #E2F2FF; border-radius:5px;margin: 10px 0px 0px 0px;">\
					<div class = "row" style = "margin:0px 0px 0px 0px; padding: 10px 0px 0px 0px;">\
						<div class="row" style = "width:195px;float:left">\
							<label>First Name</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryFirstName'+beneficiaryCounter+'" ID="BeneficiaryFirstName'+beneficiaryCounter+'" style = "width:195px"/>\
						</div>\
						<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 15px">\
							<label>Last Name</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryLastName'+beneficiaryCounter+'" ID="BeneficiaryLastName'+beneficiaryCounter+'" style = "width:195px"/>\
						</div>\
						<div class="row" style = "margin:0px 0px 0px 15px;float:left;padding-right:0px;">\
							<label>Relationship</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryRelationship'+beneficiaryCounter+'" ID="BeneficiaryRelationship'+beneficiaryCounter+'" style = "width:195px"/>\
						</div>\
						<div class="row" style = "margin:0px 0px 0px 15px;float:left;padding-right:0px;">\
							<label>Percentage</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryPercentage'+beneficiaryCounter+'" ID="BeneficiaryPercentage'+beneficiaryCounter+'" value = "0" style = "width:95px"/>\
						</div>\
						<div style = "float:left; padding: 41px 0px 0px 5px;">\
							%\
						</div>\
						<div style = "float:right">\
							<a ID = "BeneficiaryDelete'+beneficiaryCounter+'" class = "BeneficiaryDelete" data-id="'+beneficiaryCounter+'"></a>\
						</div>\
					</div>\
					<div class = "row" style = "margin:0px 0px 0px 0px">\
						<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 0px">\
							<label>Address</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryAddress'+beneficiaryCounter+'" ID="BeneficiaryAddress'+beneficiaryCounter+'" style = "width:195px">\
						</div>\
						<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 15px">\
							<label>City</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryCity'+beneficiaryCounter+'" ID="BeneficiaryCity'+beneficiaryCounter+'" style = "width:195px">\
						</div>\
						<div class="row" style = "float:left;width:195px;margin:0px 0px 0px 15px">\
						<label>State</label>\
							<select name="BeneficiaryState'+beneficiaryCounter+'" ID="BeneficiaryState'+beneficiaryCounter+'" style = "width:195px;">\
								<option value="AL" selected>Alabama</option>\
								<option value="AK">Alaska</option>\
								<option value="AZ">Arizona</option>\
								<option value="AR">Arkansas</option>\
								<option value="CA">California</option>\
								<option value="CO">Colorado</option>\
								<option value="CT">Connecticut</option>\
								<option value="DE">Delaware</option>\
								<option value="DC">Dist. Columbia</option>\
								<option value="FL">Florida</option>\
								<option value="GA">Georgia</option>\
								<option value="ID">Idaho</option>\
								<option value="IL">Illinois</option>\
								<option value="IN">Indiana</option>\
								<option value="IA">Iowa</option>\
								<option value="KS">Kansas</option>\
								<option value="KY">Kentucky</option>\
								<option value="LA">Louisiana</option>\
								<option value="ME">Maine</option>\
								<option value="MD">Maryland</option>\
								<option value="MA">Massachusetts</option>\
								<option value="MI">Michigan</option>\
								<option value="MN">Minnesota</option>\
								<option value="MS">Mississippi</option>\
								<option value="MO">Missouri</option>\
								<option value="MT">Montana</option>\
								<option value="NE">Nebraska</option>\
								<option value="NV">Nevada</option>\
								<option value="NH">New Hampshire</option>\
								<option value="NJ">New Jersey</option>\
								<option value="NM">New Mexico</option>\
								<option value="NY">NY Personal</option>\
								<option value="NY">NY Business</option>\
								<option value="NC">North Carolina</option>\
								<option value="ND">North Dakota</option>\
								<option value="OH">Ohio</option>\
								<option value="OK">Oklahoma</option>\
								<option value="OR">Oregon</option>\
								<option value="PA">Pennsylvania</option>\
								<option value="RI">Rhode Island</option>\
								<option value="SC">South Carolina</option>\
								<option value="SD">South Dakota</option>\
								<option value="TN">Tennessee</option>\
								<option value="TX">Texas</option>\
								<option value="UT">Utah</option>\
								<option value="VT">Vermont</option>\
								<option value="VA">Virginia</option>\
								<option value="WA">Washington</option>\
								<option value="WV">West Virginia</option>\
								<option value="WI">Wisconsin</option>\
								<option value="WY">Wyoming</option>\
							</select>\
						</div>\
						<div class = "row" style = "float:left;width:95px;margin:0px 0px 0px 15px;padding-right:0px;">\
							<label> Zip Code</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryZipCode'+beneficiaryCounter+'" ID="BeneficiaryZipCode'+beneficiaryCounter+'">\
						</div>\
					</div>\
					<div class = "row" ID = "BeneficiaryError'+beneficiaryCounter+'" style = "margin: 10px 0px 0px 0px;color: #FF726D">\
					</div>\
				</div>\
		';
		// template end
		
		$("#beneficiary-row").append(beneficiaryTemplate);
		var initBeneficiary = "#BeneficiaryDelete"+beneficiaryCounter;
		initialize(initBeneficiary);
		beneficiaryCounter = beneficiaryCounter+1;
		//
		buttonChangeCaption();
		});
	});
	
	$(document).ready(function(){
		$('.payment-icon').hover(
			function () {
				$(this).next().fadeIn();
			}, 
			function () {
				$(this).next().fadeOut();
			}
		);
		
		$('.info-icon').hover(
			function () {
				$(this).next().fadeIn();
			}, 
			function () {
				$(this).next().fadeOut();
			}
		);
		
		$('.info-icon1').hover(
			function () {
				$(this).next().fadeIn();
			}, 
			function () {
				$(this).next().fadeOut();
			}
		);
	});
</script>


<div id="form-area">
	<div class="container">
    	<div class="form-container row">
        	<div class="nine columns" style="width:100%;">
            	<div class="form-left">
                	
	<?php

if ( have_posts() ) {

	// Start of the Loop
	while ( have_posts() ) {
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header><!-- .entry-header -->
		
			<div class="entry-content" style = "padding-right:35px">
			<?php

				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
				wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
				?>
<div style = "height:160px">
	<div style = "float:left">
		<h4 class="title">Payment</h4>
		<ul class="bread">
			<li><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
			<li><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
			<li><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
			<li class="selected"><span>4</span><a href="#">Payment</a><div class="arrw"></div></li>
		</ul>
	</div>
	<script type = "text/javascript">
		$(document).ready(function(){
			if (window.innerWidth < 945){
				$('#norton-logo').css('display','none');
			}
		})
	</script>
	<div id = "norton-logo" style = "float:left;margin: 0px 0px 0px 100px">
		<table style = "width:135px;" width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose Symantec SSL for secure e-commerce and confidential communications.">
		<tr>
		<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.websecurity.norton.com/getseal?host_name=NoExam.com&amp;size=L&amp;use_flash=NO&amp;use_transparent=NO&amp;lang=en"></script><br />
		<!--<a href="http://www.symantec.com/ssl-certificates" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">ABOUT SSL CERTIFICATES</a>--></td>
		</tr>
		</table>
	</div>
</div>
<form action="/post-page/" method="post"  class="form-inputs payment-page">

	<div class = "row" ID = "beneficiary-title" style = "margin:0px;display:block !important">
		<div style = "float:left;">
			<h4>Beneficiaries</h4>
		</div>
		<div style = "float:left; margin:8px;">
			<a class = "info-icon1" href = "#">info</a>
			<div class="payment-tooltip3">
				The beneficiary of your life insurance policy is the person (or persons) who will receive the proceeds of the life insurance policy when the insured person dies.
			</div>
		</div>		
	</div>
	
	<div class = "row" ID = "beneficiary-row" style = "margin-bottom:0px; padding-right:0px" >
		<div class = "row"  ID = "Beneficiary0" style = "padding:10px 0px 10px 10px; border: 2px solid #E2F2FF; border-radius:5px;margin-bottom:0px;">
			<div class = "row" style = "margin:0px 0px 0px 0px">
				<div class="row" style = "width:195px;float:left">
					<label>First Name</label>
					<INPUT TYPE=TEXT Name="BeneficiaryFirstName0" ID="BeneficiaryFirstName0" value = "<? if ($_SESSION['BeneficiaryFirstName0'] != "") {echo $_SESSION['BeneficiaryFirstName0'];}?>" style = "width:195px"/>
				</div>
				<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 15px">
					<label>Last Name</label>
					<INPUT TYPE=TEXT Name="BeneficiaryLastName0" ID="BeneficiaryLastName0" value = "<? if ($_SESSION['BeneficiaryLastName0'] != "") {echo $_SESSION['BeneficiaryLastName0'];}?>" style = "width:195px"/>
				</div>
				<div class="row" style = "margin:0px 0px 0px 15px;float:left;width:195px">
					<label>Relationship</label>
					<INPUT TYPE=TEXT Name="BeneficiaryRelationship0" ID="BeneficiaryRelationship0" value = "<? if ($_SESSION['BeneficiaryRelationship0'] != "") {echo $_SESSION['BeneficiaryRelationship0'];}?>" style = "width:195px"/>
				</div>
				<div class="row" style = "margin:0px 0px 0px 15px;float:left;padding-right:0px;">
					<label>Percentage</label>
					<INPUT TYPE=TEXT Name="BeneficiaryPercentage0" ID="BeneficiaryPercentage0" value = "<? if (!isset($_SESSION['BeneficiaryPercentage0'])) {echo "0";} else { echo $_SESSION['BeneficiaryPercentage0'];;}?>" style = "width:95px"/>
				</div>
				<div style = "float:left; padding: 41px 0px 0px 5px;">
					%
				</div>
			</div>
			<div class = "row" style = "margin:0px 0px 0px 0px">
				<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 0px">
					<label>Address</label>
					<INPUT TYPE=TEXT NAME="BeneficiaryAddress0" ID="BeneficiaryAddress0" value = "<? if ($_SESSION['BeneficiaryAddress0'] != "") {echo $_SESSION['BeneficiaryAddress0'];}?>" style = "width:195px">
				</div>
				<div class="row" style = "width:195px;float:left;margin:0px 0px 0px 15px">
					<label>City</label>
					<INPUT TYPE=TEXT NAME="BeneficiaryCity0" ID="BeneficiaryCity0" value = "<? if ($_SESSION['BeneficiaryCity0'] != "") {echo $_SESSION['BeneficiaryCity0'];}?>" style = "width:195px">
				</div>
				<div class="row" style = "float:left;width:195px;margin:0px 0px 0px 15px">
				<label>State</label>
					<select name="BeneficiaryState0" ID="BeneficiaryState0" style = "width:195px;">
						<option value="AL" <? if ($_SESSION['BeneficiaryState0'] != "" or $_SESSION['BeneficiaryState0'] == "AL"){echo "selected";}?>>Alabama</option>
						<option value="AK" <? if ($_SESSION['BeneficiaryState0'] == "AK"){echo "selected";}?>>Alaska</option>
						<option value="AZ" <? if ($_SESSION['BeneficiaryState0'] == "AZ"){echo "selected";}?>>Arizona</option>
						<option value="AR" <? if ($_SESSION['BeneficiaryState0'] == "AR"){echo "selected";}?>>Arkansas</option>
						<option value="CA" <? if ($_SESSION['BeneficiaryState0'] == "CA"){echo "selected";}?>>California</option>
						<option value="CO" <? if ($_SESSION['BeneficiaryState0'] == "CO"){echo "selected";}?>>Colorado</option>
						<option value="CT" <? if ($_SESSION['BeneficiaryState0'] == "CT"){echo "selected";}?>>Connecticut</option>
						<option value="DE" <? if ($_SESSION['BeneficiaryState0'] == "DE"){echo "selected";}?>>Delaware</option>
						<option value="DC" <? if ($_SESSION['BeneficiaryState0'] == "DC"){echo "selected";}?>>Dist. Columbia</option>
						<option value="FL" <? if ($_SESSION['BeneficiaryState0'] == "FL"){echo "selected";}?>>Florida</option>
						<option value="GA" <? if ($_SESSION['BeneficiaryState0'] == "GA"){echo "selected";}?>>Georgia</option>
						<option value="ID" <? if ($_SESSION['BeneficiaryState0'] == "ID"){echo "selected";}?>>Idaho</option>
						<option value="IL" <? if ($_SESSION['BeneficiaryState0'] == "IL"){echo "selected";}?>>Illinois</option>
						<option value="IN" <? if ($_SESSION['BeneficiaryState0'] == "IN"){echo "selected";}?>>Indiana</option>
						<option value="IA" <? if ($_SESSION['BeneficiaryState0'] == "IA"){echo "selected";}?>>Iowa</option>
						<option value="KS" <? if ($_SESSION['BeneficiaryState0'] == "KS"){echo "selected";}?>>Kansas</option>
						<option value="KY" <? if ($_SESSION['BeneficiaryState0'] == "KY"){echo "selected";}?>>Kentucky</option>
						<option value="LA" <? if ($_SESSION['BeneficiaryState0'] == "LA"){echo "selected";}?>>Louisiana</option>
						<option value="ME" <? if ($_SESSION['BeneficiaryState0'] == "ME"){echo "selected";}?>>Maine</option>
						<option value="MD" <? if ($_SESSION['BeneficiaryState0'] == "MD"){echo "selected";}?>>Maryland</option>
						<option value="MA" <? if ($_SESSION['BeneficiaryState0'] == "MA"){echo "selected";}?>>Massachusetts</option>
						<option value="MI" <? if ($_SESSION['BeneficiaryState0'] == "MI"){echo "selected";}?>>Michigan</option>
						<option value="MN" <? if ($_SESSION['BeneficiaryState0'] == "MN"){echo "selected";}?>>Minnesota</option>
						<option value="MS" <? if ($_SESSION['BeneficiaryState0'] == "MS"){echo "selected";}?>>Mississippi</option>
						<option value="MO" <? if ($_SESSION['BeneficiaryState0'] == "MO"){echo "selected";}?>>Missouri</option>
						<option value="MT" <? if ($_SESSION['BeneficiaryState0'] == "MT"){echo "selected";}?>>Montana</option>
						<option value="NE" <? if ($_SESSION['BeneficiaryState0'] == "NE"){echo "selected";}?>>Nebraska</option>
						<option value="NV" <? if ($_SESSION['BeneficiaryState0'] == "NV"){echo "selected";}?>>Nevada</option>
						<option value="NH" <? if ($_SESSION['BeneficiaryState0'] == "NH"){echo "selected";}?>>New Hampshire</option>
						<option value="NJ" <? if ($_SESSION['BeneficiaryState0'] == "NJ"){echo "selected";}?>>New Jersey</option>
						<option value="NM" <? if ($_SESSION['BeneficiaryState0'] == "NM"){echo "selected";}?>>New Mexico</option>
						<option value="NY" <? if ($_SESSION['BeneficiaryState0'] == "NY"){echo "selected";}?>>NY Personal</option>
						<option value="NY" <? if ($_SESSION['BeneficiaryState0'] == "NY"){echo "selected";}?>>NY Business</option>
						<option value="NC" <? if ($_SESSION['BeneficiaryState0'] == "NC"){echo "selected";}?>>North Carolina</option>
						<option value="ND" <? if ($_SESSION['BeneficiaryState0'] == "ND"){echo "selected";}?>>North Dakota</option>
						<option value="OH" <? if ($_SESSION['BeneficiaryState0'] == "OH"){echo "selected";}?>>Ohio</option>
						<option value="OK" <? if ($_SESSION['BeneficiaryState0'] == "OK"){echo "selected";}?>>Oklahoma</option>
						<option value="OR" <? if ($_SESSION['BeneficiaryState0'] == "OR"){echo "selected";}?>>Oregon</option>
						<option value="PA" <? if ($_SESSION['BeneficiaryState0'] == "PA"){echo "selected";}?>>Pennsylvania</option>
						<option value="RI" <? if ($_SESSION['BeneficiaryState0'] == "RI"){echo "selected";}?>>Rhode Island</option>
						<option value="SC" <? if ($_SESSION['BeneficiaryState0'] == "SC"){echo "selected";}?>>South Carolina</option>
						<option value="SD" <? if ($_SESSION['BeneficiaryState0'] == "SD"){echo "selected";}?>>South Dakota</option>
						<option value="TN" <? if ($_SESSION['BeneficiaryState0'] == "TN"){echo "selected";}?>>Tennessee</option>
						<option value="TX" <? if ($_SESSION['BeneficiaryState0'] == "TX"){echo "selected";}?>>Texas</option>
						<option value="UT" <? if ($_SESSION['BeneficiaryState0'] == "UT"){echo "selected";}?>>Utah</option>
						<option value="VT" <? if ($_SESSION['BeneficiaryState0'] == "VT"){echo "selected";}?>>Vermont</option>
						<option value="VA" <? if ($_SESSION['BeneficiaryState0'] == "VA"){echo "selected";}?>>Virginia</option>
						<option value="WA" <? if ($_SESSION['BeneficiaryState0'] == "WA"){echo "selected";}?>>Washington</option>
						<option value="WV" <? if ($_SESSION['BeneficiaryState0'] == "WV"){echo "selected";}?>>West Virginia</option>
						<option value="WI" <? if ($_SESSION['BeneficiaryState0'] == "WI"){echo "selected";}?>>Wisconsin</option>
						<option value="WY" <? if ($_SESSION['BeneficiaryState0'] == "WY"){echo "selected";}?>>Wyoming</option>
					</select>
				</div>
				<div class = "row" style = "float:left;width:95px;margin:0px 0px 0px 15px;padding-right:0px;">
					<label> Zip Code</label>
					<INPUT TYPE=TEXT NAME="BeneficiaryZipCode0" ID="BeneficiaryZipCode0" value = "<? if ($_SESSION['BeneficiaryLastName0'] != "") {echo $_SESSION['BeneficiaryLastName0'];}?>">
				</div>
			</div>
			<div class = "row" ID = "BeneficiaryError0" style = "margin: 10px 0px 0px 0px;color: #FF726D">
			</div>
		</div>
	
		<? 	// redrawing beneficiaries after return to this page;
			if ($BeneficiaryCounter > 1){
				for ($i = 1; $i < $BeneficiaryCounter; $i++){
					// template parts
					$BeneficiaryFirstName		=	"BeneficiaryFirstName".$i;
					$BeneficiaryLastName		=	"BeneficiaryLastName".$i;
					$BeneficiaryRelationship	=	"BeneficiaryRelationship".$i;
					$BeneficiaryPercentage		=	"BeneficiaryPercentage".$i;
					$BeneficiaryAddress			=	"BeneficiaryAddress".$i;
					$BeneficiaryCity			=	"BeneficiaryCity".$i;
					$BeneficiaryState			=	"BeneficiaryState".$i;
					$BeneficiaryZipCode			=	"BeneficiaryZipCode".$i;
					// drawing template
					if ($_SESSION[$BeneficiaryFirstName] != ""){
					echo "
						<div class = \"row\"  ID = \"Beneficiary$i\" style = \"padding:10px 0px 10px 10px; border: 2px solid #E2F2FF; border-radius:5px;margin: 10px 0px 0px 0px;\">
							<div class = \"row\" style = \"margin:0px 0px 0px 0px\">
								<div class=\"row\" style = \"width:195px;float:left\">
									<label>First Name</label>
									<INPUT TYPE=TEXT Name=\"BeneficiaryFirstName$i\" ID=\"BeneficiaryFirstName$i\" style = \"width:195px\" value = \"$_SESSION[$BeneficiaryFirstName]\" />
								</div>
								<div class=\"row\" style = \"width:195px;float:left;margin:0px 0px 0px 15px\">
									<label>Last Name</label>
									<INPUT TYPE=TEXT Name=\"BeneficiaryLastName$i\" ID=\"BeneficiaryLastName$i\" style = \"width:195px\" value = \"$_SESSION[$BeneficiaryLastName]\" />
								</div>
								<div class=\"row\" style = \"margin:0px 0px 0px 15px;float:left\">
									<label>Relationship</label>
									<INPUT TYPE=TEXT Name=\"BeneficiaryRelationship$i\" ID=\"BeneficiaryRelationship$i\" style = \"width:195px\" value = \"$_SESSION[$BeneficiaryRelationship]\" />
								</div>
								<div class=\"row\" style = \"margin:0px 0px 0px 15px;float:left\">
									<label>Percentage</label>
									<INPUT TYPE=TEXT Name=\"BeneficiaryPercentage$i\" ID=\"BeneficiaryPercentage$i\" style = \"width:95px\" value = \"$_SESSION[$BeneficiaryPercentage]\" />
								</div>
								<div style = \"float:left; padding: 41px 0px 0px 5px;\">
									%
								</div>
								<div style = \"float:right\">
									<a ID = \"BeneficiaryDelete$i\" class = \"BeneficiaryDelete\" data-id=\"$i\"></a>
								</div>
							</div>
							<div class = \"row\" style = \"margin:0px 0px 0px 0px\">
								<div class=\"row\" style = \"width:195px;float:left;margin:0px 0px 0px 0px\">
									<label>Address</label>
									<INPUT TYPE=TEXT NAME=\"BeneficiaryAddress$i\" ID=\"BeneficiaryAddress$i\" style = \"width:195px\" value = \"$_SESSION[$BeneficiaryAddress]\" >
								</div>
								<div class=\"row\" style = \"width:195px;float:left;margin:0px 0px 0px 15px\">
									<label>City</label>
									<INPUT TYPE=TEXT NAME=\"BeneficiaryCity$i\" ID=\"BeneficiaryCity$i\" style = \"width:195px\" value = \"$_SESSION[$BeneficiaryCity]\" >
								</div>
								<div class=\"row\" style = \"float:left;width:195px;margin:0px 0px 0px 15px\">
								<label>State</label>
									<select name=\"BeneficiaryState$i\" ID=\"BeneficiaryState$i\">
										<option value=\"AL\" if ($_SESSION[$BeneficiaryState] == \"AL\" or $_SESSION[$BeneficiaryState] == \"\") {echo \"selected\"}>Alabama</option>
										<option value=\"AK\" if ($_SESSION[$BeneficiaryState] == \"AK\"){echo \"selected\"} >Alaska</option>
										<option value=\"AZ\" if ($_SESSION[$BeneficiaryState] == \"AZ\"){echo \"selected\"} >Arizona</option>
										<option value=\"AR\" if ($_SESSION[$BeneficiaryState] == \"AR\"){echo \"selected\"} >Arkansas</option>
										<option value=\"CA\" if ($_SESSION[$BeneficiaryState] == \"CA\"){echo \"selected\"} >California</option>
										<option value=\"CO\" if ($_SESSION[$BeneficiaryState] == \"CO\"){echo \"selected\"} >Colorado</option>
										<option value=\"CT\" if ($_SESSION[$BeneficiaryState] == \"CT\"){echo \"selected\"} >Connecticut</option>
										<option value=\"DE\" if ($_SESSION[$BeneficiaryState] == \"DE\"){echo \"selected\"} >Delaware</option>
										<option value=\"DC\" if ($_SESSION[$BeneficiaryState] == \"DC\"){echo \"selected\"} >Dist. Columbia</option>
										<option value=\"FL\" if ($_SESSION[$BeneficiaryState] == \"FL\"){echo \"selected\"} >Florida</option>
										<option value=\"GA\" if ($_SESSION[$BeneficiaryState] == \"GA\"){echo \"selected\"} >Georgia</option>
										<option value=\"ID\" if ($_SESSION[$BeneficiaryState] == \"ID\"){echo \"selected\"} >Idaho</option>
										<option value=\"IL\" if ($_SESSION[$BeneficiaryState] == \"IL\"){echo \"selected\"} >Illinois</option>
										<option value=\"IN\" if ($_SESSION[$BeneficiaryState] == \"IN\"){echo \"selected\"} >Indiana</option>
										<option value=\"IA\" if ($_SESSION[$BeneficiaryState] == \"IA\"){echo \"selected\"} >Iowa</option>
										<option value=\"KS\" if ($_SESSION[$BeneficiaryState] == \"KS\"){echo \"selected\"} >Kansas</option>
										<option value=\"KY\" if ($_SESSION[$BeneficiaryState] == \"KY\"){echo \"selected\"} >Kentucky</option>
										<option value=\"LA\" if ($_SESSION[$BeneficiaryState] == \"LA\"){echo \"selected\"} >Louisiana</option>
										<option value=\"ME\" if ($_SESSION[$BeneficiaryState] == \"ME\"){echo \"selected\"} >Maine</option>
										<option value=\"MD\" if ($_SESSION[$BeneficiaryState] == \"MD\"){echo \"selected\"} >Maryland</option>
										<option value=\"MA\" if ($_SESSION[$BeneficiaryState] == \"MA\"){echo \"selected\"} >Massachusetts</option>
										<option value=\"MI\" if ($_SESSION[$BeneficiaryState] == \"MI\"){echo \"selected\"} >Michigan</option>
										<option value=\"MN\" if ($_SESSION[$BeneficiaryState] == \"MN\"){echo \"selected\"} >Minnesota</option>
										<option value=\"MS\" if ($_SESSION[$BeneficiaryState] == \"MS\"){echo \"selected\"} >Mississippi</option>
										<option value=\"MO\" if ($_SESSION[$BeneficiaryState] == \"MO\"){echo \"selected\"} >Missouri</option>
										<option value=\"MT\" if ($_SESSION[$BeneficiaryState] == \"MT\"){echo \"selected\"} >Montana</option>
										<option value=\"NE\" if ($_SESSION[$BeneficiaryState] == \"NE\"){echo \"selected\"} >Nebraska</option>
										<option value=\"NV\" if ($_SESSION[$BeneficiaryState] == \"NV\"){echo \"selected\"} >Nevada</option>
										<option value=\"NH\" if ($_SESSION[$BeneficiaryState] == \"NH\"){echo \"selected\"} >New Hampshire</option>
										<option value=\"NJ\" if ($_SESSION[$BeneficiaryState] == \"NJ\"){echo \"selected\"} >New Jersey</option>
										<option value=\"NM\" if ($_SESSION[$BeneficiaryState] == \"NM\"){echo \"selected\"} >New Mexico</option>
										<option value=\"NY\" if ($_SESSION[$BeneficiaryState] == \"NY\"){echo \"selected\"} >NY Personal</option>
										<option value=\"NY\" if ($_SESSION[$BeneficiaryState] == \"NY\"){echo \"selected\"} >NY Business</option>
										<option value=\"NC\" if ($_SESSION[$BeneficiaryState] == \"NC\"){echo \"selected\"} >North Carolina</option>
										<option value=\"ND\" if ($_SESSION[$BeneficiaryState] == \"ND\"){echo \"selected\"} >North Dakota</option>
										<option value=\"OH\" if ($_SESSION[$BeneficiaryState] == \"OH\"){echo \"selected\"} >Ohio</option>
										<option value=\"OK\" if ($_SESSION[$BeneficiaryState] == \"OK\"){echo \"selected\"} >Oklahoma</option>
										<option value=\"OR\" if ($_SESSION[$BeneficiaryState] == \"OR\"){echo \"selected\"} >Oregon</option>
										<option value=\"PA\" if ($_SESSION[$BeneficiaryState] == \"PA\"){echo \"selected\"} >Pennsylvania</option>
										<option value=\"RI\" if ($_SESSION[$BeneficiaryState] == \"RI\"){echo \"selected\"} >Rhode Island</option>
										<option value=\"SC\" if ($_SESSION[$BeneficiaryState] == \"SC\"){echo \"selected\"} >South Carolina</option>
										<option value=\"SD\" if ($_SESSION[$BeneficiaryState] == \"SD\"){echo \"selected\"} >South Dakota</option>
										<option value=\"TN\" if ($_SESSION[$BeneficiaryState] == \"TN\"){echo \"selected\"} >Tennessee</option>
										<option value=\"TX\" if ($_SESSION[$BeneficiaryState] == \"TX\"){echo \"selected\"} >Texas</option>
										<option value=\"UT\" if ($_SESSION[$BeneficiaryState] == \"UT\"){echo \"selected\"} >Utah</option>
										<option value=\"VT\" if ($_SESSION[$BeneficiaryState] == \"VT\"){echo \"selected\"} >Vermont</option>
										<option value=\"VA\" if ($_SESSION[$BeneficiaryState] == \"VA\"){echo \"selected\"} >Virginia</option>
										<option value=\"WA\" if ($_SESSION[$BeneficiaryState] == \"WA\"){echo \"selected\"} >Washington</option>
										<option value=\"WV\" if ($_SESSION[$BeneficiaryState] == \"WV\"){echo \"selected\"} >West Virginia</option>
										<option value=\"WI\" if ($_SESSION[$BeneficiaryState] == \"WI\"){echo \"selected\"} >Wisconsin</option>
										<option value=\"WY\" if ($_SESSION[$BeneficiaryState] == \"WY\"){echo \"selected\"} >Wyoming</option>
									</select>
								</div>
								<div class = \"row\" style = \"float:left;width:95px;margin:0px 0px 0px 15px\">
									<label> Zip Code</label>
									<INPUT TYPE=TEXT NAME=\"BeneficiaryZipCode$i\" ID=\"BeneficiaryZipCode$i\" value = \"$_SESSION[$BeneficiaryZipCode]\" >
								</div>
							</div>
							<div class = \"row\" ID = \"BeneficiaryError$i\" style = \"margin: 10px 0px 0px 0px;color: #FF726D\">
							</div>
						</div>
					";
					}
				}
			}
		?>
	</div>
	
	<div class = "row" ID = "error-field" style = "display: none; color: #FF726D;">
		
	</div>
	
	<!-- Hidden field for beneficiaries -->
	<input type = "hidden" name = "fBeneficiaryCounter" ID = "BeneficiaryCounter" value = "<? echo $BeneficiaryCounter;?>">
	<input type = "hidden" name = "fBeneficiaryData" ID = "BeneficiaryData" value = "">
	
	<div class = "row" style = "padding-right:0px;margin-top:10px">
		<input type = "button" value = "Add Beneficiary" class = "button" ID = "add-beneficiary">
	</div>

	<input type = "hidden" name = "pageID" value = "7">
	
	<div class = "row" style = "margin:0px;padding:0px">
		
		<div class = "row" style = "margin:0px;padding:0px;width:222px;float:left;">
			<div class = "payment-summary-left">
				<div style = "float:left"><label style = "padding-bottom: 5px">Payment method:</label></div>
				<div style = "float:left">
					<a class = "info-icon" href = "#">info</a>
					<div class="payment-tooltip1">EFT information is required to submit application to insurance company.</div>
				</div>
				
				<div style = "text-size: 5px;">
					<label class = "chk" style = "margin:0px 0px 0px 10px;font-size:12px;">
						<input style = "margin:0px 10px 0px 0px" type="checkbox" name = "fPaymentMethod" value = "Yes" checked>EFT Bank Draft(Checking Account)</label>
					<label class = "chk" style = "display:none;margin:0px 0px 0px 10px;padding-bottom:8px;font-size:12px;">Additional method of payment</label>
						<input style = "margin:0px 0px 0px 10px; height:25px;" type="hidden" name = "fAddPaymentMethod" value = "" <? if ($_SESSION['fAddPaymentMethod'] != ""){echo $_SESSION['fAddPaymentMethod'];}?>>
				</div>
			</div>
			<div class = "payment-summary-right"> 
				<div style = "float:left"><label style = "padding-bottom: 5px">Payment Frequency:</label></div>
				<div style = "float:left">
					<a class = "info-icon1" href = "#">info</a>
					<div class="payment-tooltip2">Your initial payment will be drafted upon policy's approval.</div>
				</div>
				<div>
					<label class = "chk" style = "margin:0px 0px 0px 10px;font-size:12px;"><input style = "margin:0px 10px 0px 0px" type="radio" name = "fMonthlyCheck" value = "0" <? if($_SESSION['fMonthlyCheck'] == "0" or $_SESSION['fMonthlyCheck'] == "") { echo "checked";}?>>Monthly(<?php echo $monthly?>)</label></br>
					<label class = "chk" style = "margin:0px 0px 0px 10px;font-size:12px;"><input style = "margin:0px 10px 0px 0px" type="radio" name = "fMonthlyCheck" value = "1" <? if($_SESSION['fMonthlyCheck'] == "1") { echo "checked";}?>>Annual(<?php echo $_SESSION['fannualpremium'];?>)<label>save 8%!</label></label>
				</div>
			</div>
		</div>
		
		<div class = "row" style = "float:left;margin:0px 0px 0px 5px;padding:0px;">
			<a class = "payment-info" href = "#">payment info</a>
		</div>
		
		<div class = "row" style = "float:left;margin:0px 0px 0px 15px;padding:0 0 0 15px !important;">
			<div class = "row" style = "margin: 0px 0px 5px 0px;padding:0px;">
				<div style = "float:left;">
					<label style = "padding: 0px 0px 5px 0px; font-size: 12px;">Bank Name</label>
					<input type = "text" name = "fBankName" id = "fBankName" style = "width:215px;height:30px;" placeholder = "Name of bank" required = "" title = "Please enter name of your bank" value = "<?if ($BankName != ""){echo $BankName;}?>">
				</div>
				<div style = "float:left;padding: 23px 0px 0px 5px;">
					<a href = "#" class = "payment-icon">icon</a>
					<div class = "payment-tooltip">This information is transmitted securely via 128 bit encryption. NoExam.com does not store this information.</div>
				</div>
			</div>
			<div class = "row" style = "margin: 0px 0px 5px 0px;padding:0px;">
				<div style = "float:left;">
					<label style = "padding: 0px 0px 5px 0px; font-size: 12px;">Account Number</label>
					<input type = "text" name = "fBankAccount" id = "fBankAccount" style = "width:215px;height:30px;" placeholder = "Account Number"  required = ""  title = "Please enter your account number" value = "<? if ($BankAccount != ""){echo $BankAccount;}?>"> <!-- --> 
				</div>
				<div style = "float:left;padding: 23px 0px 0px 5px;">
					<a href="#" class="payment-icon">icon</a>
					<div class="payment-tooltip">This information is transmitted securely via 128 bit encryption. NoExam.com does not store this information.</div>
				</div>
			</div> 
			<div class = "row" style = "margin: 0px 0px 5px 0px;padding:0px;">
				<div style = "float:left;">
					<label style = "padding: 0px 0px 5px 0px; font-size: 12px;">Routing Number</label>
					<input type = "text" name = "fRoutingNumber" id = "fRoutingNumber"style = "width:215px;height:30px;" placeholder = "Routing Number" required = "" title = "Please enter your routing number" value = "<? if ($RoutingNumber != ""){echo $RoutingNumber;}?>"><!--min = "9" size = "9" maxlength = "9"-->
				</div>
				<div style = "float:left;padding: 23px 0px 0px 5px;">
					<a href="#" class="payment-icon">icon</a>
					<div class="payment-tooltip">This information is transmitted securely via 128 bit encryption. NoExam.com does not store this information.</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<div class = "row" id = "main-error-field" style = "margin: 5px 0px 0px 5px;padding:0px;display:none;color: #FF726D;">
			
	</div>
	
	<div class="payment-inner">
		<div>
			<input type="button" value="Submit" ID="request-button" name="submit1" class="button"> 
		</div>
		<br />
		<p><strong>Note</strong>: The submission of this form does not bind your life insurance policy. Policy approvals occur within 24 hours of the initial application submission to the insurance company.  Upon the time of your policy approval you will be notified by e-mail. If approved, please expect 3-5 business days to receive a hard copy of your policy in the mail.</p>
	</div>
</form>
<p>&nbsp;</p>

			</div><!-- .entry-content --><?php

			// Comments info.
			if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) { ?>
			<span class="sep"> | </span>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'hellish' ), __( '1 Comment', 'hellish' ), __( '% Comments', 'hellish' ) ); ?></span><?php
			}

			// Edit link
			//edit_post_link( __( 'Edit', 'hellish' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' );
			?>

		</article><!-- #post-<?php the_ID(); ?> --><?php

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
</div><!-- #content-area -->

<div id="footer" class="footer-wrapper">
        <div class="container"><p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p></div>
    </div>
<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<?php //get_footer(); ?>