<?php
/**
 * Template Name: Request
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

$_POST['page'] = 6;
// Header
include("header.php");
// Helpers
require("inc/helpers.php");
// Storage
require("inc/storage.php");

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
//            showcounters();
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

</script>


<?
// user data
$FirstName = $_SESSION['fFirstName'];
$MiddleName = $_SESSION['fMiddleName'];
$LastName = $_SESSION['fLastName'];
$BirthCountry = $_SESSION['fBirthCountry'];
$BirthState = $_SESSION['fBirthState'];

$Height_ft = $_SESSION['fHeight_ft'];
$Height_in = $_SESSION['fHeight_in'];
$weight = $_SESSION['fweight'];

$Email = $_SESSION['fEmail'];
$HomePhone = $_SESSION['fHomePhone'];
$Address = $_SESSION['fAddress'];
$City = $_SESSION['fCity'];
$State = $_SESSION['fState'];
$ZipCode = $_SESSION['fZipCode'];

$Occupation = $_SESSION['fOccupation'];
$AnnualIncome = $_SESSION['fAnnualIncome'];
$DLN = $_SESSION['fDLN'];
$DLS = $_SESSION['fDLS'];
$NOE = $_SESSION['fNOE'];

$OriginalHeight = $Height_ft."'".$Height_in;

$SocialSecurityNumber = $_SESSION['fSocialSecurityNumber'];
?>

<div id="form-area">
	<div class="container">
        <div class="row">

            <div class="five columns">
                <div class="entry-header">
                    <h1 class="entry-title">General Info</h1>
                </div><!-- .entry-header -->
            </div>
            <div class="eleven columns progresslist">
                <ul>
                    <li class="selected"><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
                    <li class="selected"><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
                    <li class="selected"><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
                    <li><span>4</span><a href="#">Payment</a><div class="arrw"></div></li>
                </ul>
            </div>
        </div>
    </div>
	<div class="container">
    	<div class="form-container row">
        	<div class="sixteen columns">
            	<div class="contentcon">

                    <?php
                    if ( have_posts() ) {
                    // Start of the Loop
                    while ( have_posts() ) {
                    the_post();
                    ?>

                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php //the_title(); ?></h1>
                        </header>

                        <div class="entry-content form-con-area">
                            <FORM ACTION="/post-page/" METHOD=POST  class="form-inputs-request">
                            <?php
                                the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
                                wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
                            ?>
                             <?
                            /*
                            You can add as many questions to the form below as you like. At the bottom are a number of commented out questions. To use any of these remove the <!-- before it and the --> after the quetion. Any addition fields that were on the initial select page and sent on from the quotes.php page will need to have a hidden line added to pass the data onto the next page below: TYPE=HIDDEN
                            */
                            ?>
                                <script type = "text/javascript">
                                    function resetErrorHighlighting(){
                                        document.getElementById("fFirstName").style.borderColor = "white";
                                        document.getElementById("fLastName").style.borderColor = "white";
                                        document.getElementById("fAddress").style.borderColor = "white";
                                        document.getElementById("fCity").style.borderColor = "white";
                                        document.getElementById("fZipCode").style.borderColor = "white";
                                        document.getElementById("fHomePhone").style.borderColor = "white";
                                        document.getElementById("fEmail").style.borderColor = "white";
                                        document.getElementById("fBirthCountry").style.borderColor = "white";
                                        document.getElementById("fOccupation").style.borderColor = "white";
                                        document.getElementById("fAnnualIncome").style.borderColor = "white";
                                        document.getElementById("fNOE").style.borderColor = "white";
                                        document.getElementById("fDLN").style.borderColor = "white";
                                        document.getElementById("fSocialSecurityNumber").style.borderColor = "white";
                                    }

                                    function userFieldsValidate(){
                                        var error = 0;
                                        resetErrorHighlighting();
                                        $("#error-field").html("");
                                        if ($("#fFirstName").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"First Name\" must be filled in.</div>");
                                            document.getElementById("fFirstName").style.borderColor = "red";
                                            error = 1;
                                        }
                    //                    if ($("#fMiddleName").val() == ""){
                    //                        $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Middle Name\" must be filled in.</div>");
                    //                        document.getElementById("fMiddleName").style.borderColor = "red";
                    //                        error = 1;
                    //                    }
                                        if ($("#fLastName").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Last Name\" must be filled in.</div>");
                                            document.getElementById("fLastName").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fAddress").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Address\" must be filled in.</div>");
                                            document.getElementById("fAddress").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fCity").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"City\" must be filled in.</div>");
                                            document.getElementById("fCity").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fZipCode").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Zip Code\" must be filled in.</div>");
                                            document.getElementById("fZipCode").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fHomePhone").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Home Phone\" must be filled in.</div>");
                                            document.getElementById("fHomePhone").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fEmail").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Email\" must be filled in.</div>");
                                            document.getElementById("fEmail").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fOriginalHeight").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Height\" must be filled in.</div>");
                                            document.getElementById("fOriginalHeight").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fweight").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Weight\" must be filled in.</div>");
                                            document.getElementById("fweight").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fBirthCountry").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Birth Country\" must be filled in.</div>");
                                            document.getElementById("fBirthCountry").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fOccupation").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Occupation\" must be filled in.</div>");
                                            document.getElementById("fOccupation").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fAnnualIncome").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Annual Income\" must be filled in.</div>");
                                            document.getElementById("fAnnualIncome").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fNOE").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Name of Employer\" must be filled in.</div>");
                                            document.getElementById("fNOE").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fDLN").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Drivers License Number\" must be filled in.</div>");
                                            document.getElementById("fDLN").style.borderColor = "red";
                                            error = 1;
                                        }
                                        if ($("#fSocialSecurityNumber").val() == ""){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Social Security Number\" must be filled in.</div>");
                                            document.getElementById("fSocialSecurityNumber").style.borderColor = "red";
                                            error = 1;
                                        } else if ($("#fSocialSecurityNumber").val().length < 9){
                                            $("#error-field").append("<div style = \"margin: 4px 0px 0px 0px\">The \"Social Security Number\" field length must be equal to 9 characters.</div>");
                                            document.getElementById("fSocialSecurityNumber").style.borderColor = "red";
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
                                            var beneficiaryError = validate(); //beneficiary fields
                                            var uFieldsValidate = userFieldsValidate();

                                            if (uFieldsValidate == 0 && beneficiaryError == 0) {
                                                $("#error-field").hide();
                                                getBeneficiariesFields();
                                                $(".form-inputs-request").submit();
                                            } else {
                                                $("#error-field").show();
                                            }
                                        });

                                        $("#add-beneficiary").click(function(){
                                            // template
                                            var beneficiaryTemplate = '\
				<div class = "row"  ID = "Beneficiary'+beneficiaryCounter+'" >\
				    <div class = "row" >\
						<div style = "float:left; padding: 41px 0px 0px 5px;">\
							\
						</div>\
						<div style = "float:right">\
							<a ID = "BeneficiaryDelete'+beneficiaryCounter+'" class = "BeneficiaryDelete" data-id="'+beneficiaryCounter+'"></a>\
						</div>\
					</div>\
					<div class = "row" >\
						<div class="div-50 divcontent">\
							<label>First Name</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryFirstName'+beneficiaryCounter+'" ID="BeneficiaryFirstName'+beneficiaryCounter+'" size=25/>\
						</div>\
						<div class="div-50 divcontent">\
							<label>Last Name</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryLastName'+beneficiaryCounter+'" ID="BeneficiaryLastName'+beneficiaryCounter+'" size=25/>\
						</div>\
                    </div>\
                    <div class = "row" >\
						<div class="div-50 divcontent">\
							<label>Relationship</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryRelationship'+beneficiaryCounter+'" ID="BeneficiaryRelationship'+beneficiaryCounter+'" size=25/>\
						</div>\
						<div class="div-50 divcontent" >\
							<label>Percentage</label>\
							<INPUT TYPE=TEXT Name="BeneficiaryPercentage'+beneficiaryCounter+'" ID="BeneficiaryPercentage'+beneficiaryCounter+'" value = "0" size=25 />\
						</div>\
                    </div>\
                    <div class = "row">\
						<div class="div-50 divcontent" >\
							<label>Street Address</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryAddress'+beneficiaryCounter+'" ID="BeneficiaryAddress'+beneficiaryCounter+'" SIZE=25 >\
						</div>\
						<div class="div-50 divcontent" >\
							<label>City</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryCity'+beneficiaryCounter+'" ID="BeneficiaryCity'+beneficiaryCounter+'" size=25>\
						</div>\
                    </div>\
                    <div class = "row">\
						<div class="div-50 divcontent">\
						<label>State</label>\
							<select name="BeneficiaryState'+beneficiaryCounter+'" ID="BeneficiaryState'+beneficiaryCounter+'" >\
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
						<div class = "div-50 divcontent" >\
							<label> Zip Code</label>\
							<INPUT TYPE=TEXT NAME="BeneficiaryZipCode'+beneficiaryCounter+'" ID="BeneficiaryZipCode'+beneficiaryCounter+'" size=8>\
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
                                    });
                                </script>

                                <div class="rightside-form">
                                    <h2>Your Personal Details</h2>
                                    <p>NoExam.com uses 256-Bit SSL encryption to protect your data, the same level of encryption used by financial institutions to secure online banking transactions. In addition to this, NoExam.com does not store sensitive information.</p>
<p>SSN is used by the insurance company to verify identity and will not affect your credit score.</p>

                                </div>
                                <div class="leftside-form" style="margin-bottom: 20px;">

                                        <input type = "hidden" name = "pageID" value = "6">
                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>First Name</label>
                                                <INPUT TYPE=TEXT NAME="fFirstName" id = "fFirstName"   SIZE=25 VALUE="<? if ($FirstName != ""){ echo $FirstName;}?>">
                                            </div>

                                            <div class="div-50 divcontent">
                                                <label>Last Name</label>
                                                <INPUT TYPE=TEXT NAME="fLastName" id = "fLastName"   SIZE=25 VALUE="<? if ($LastName != "" ) {echo $LastName;}?>">
                                            </div>
                                        </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>State Address</label>
                                                <INPUT TYPE=TEXT NAME="fAddress" id = "fAddress"   SIZE=25 value = "<? if ($Address != "" ) {echo $Address;}?>">
                                            </div>
                                            <div class="div-50 divcontent">
                                                <label>City</label>
                                                <INPUT TYPE=TEXT NAME="fCity" id = "fCity"  SIZE=25 value = "<? if ($City != "" ) {echo $City;}?>">
                                            </div>
                                        </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>State</label>
                                                <SELECT NAME="fState">
                                                    <OPTION VALUE="AK" <?php if ($State == "AK" or $State == "") {echo "selected";}?>>AK</OPTION>
                                                    <OPTION VALUE="AL" <?php if ($State == "AL") {echo "selected";}?>>AL</OPTION>
                                                    <OPTION VALUE="AZ" <?php if ($State == "AZ") {echo "selected";}?>>AZ</OPTION>
                                                    <OPTION VALUE="AR" <?php if ($State == "AR") {echo "selected";}?>>AR</OPTION>
                                                    <OPTION VALUE="CA" <?php if ($State == "CA") {echo "selected";}?>>CA</OPTION>
                                                    <OPTION VALUE="CO" <?php if ($State == "CO") {echo "selected";}?>>CO</OPTION>
                                                    <OPTION VALUE="CT" <?php if ($State == "CT") {echo "selected";}?>>CT</OPTION>
                                                    <OPTION VALUE="DC" <?php if ($State == "DC") {echo "selected";}?>>DC</OPTION>
                                                    <OPTION VALUE="DE" <?php if ($State == "DE") {echo "selected";}?>>DE</OPTION>
                                                    <OPTION VALUE="FL" <?php if ($State == "FL") {echo "selected";}?>>FL</OPTION>
                                                    <OPTION VALUE="GA" <?php if ($State == "GA") {echo "selected";}?>>GA</OPTION>
                                                    <OPTION VALUE="HI" <?php if ($State == "HI") {echo "selected";}?>>HI</OPTION>
                                                    <OPTION VALUE="IA" <?php if ($State == "IA") {echo "selected";}?>>IA</OPTION>
                                                    <OPTION VALUE="ID" <?php if ($State == "ID") {echo "selected";}?>>ID</OPTION>
                                                    <OPTION VALUE="IL" <?php if ($State == "IL") {echo "selected";}?>>IL</OPTION>
                                                    <OPTION VALUE="IN" <?php if ($State == "IN") {echo "selected";}?>>IN</OPTION>
                                                    <OPTION VALUE="KS" <?php if ($State == "KS") {echo "selected";}?>>KS</OPTION>
                                                    <OPTION VALUE="KY" <?php if ($State == "KY") {echo "selected";}?>>KY</OPTION>
                                                    <OPTION VALUE="LA" <?php if ($State == "LA") {echo "selected";}?>>LA</OPTION>
                                                    <OPTION VALUE="MA" <?php if ($State == "MA") {echo "selected";}?>>MA</OPTION>
                                                    <OPTION VALUE="MD" <?php if ($State == "MD") {echo "selected";}?>>MD</OPTION>
                                                    <OPTION VALUE="ME" <?php if ($State == "ME") {echo "selected";}?>>ME</OPTION>
                                                    <OPTION VALUE="MI" <?php if ($State == "MI") {echo "selected";}?>>MI</OPTION>
                                                    <OPTION VALUE="MN" <?php if ($State == "MN") {echo "selected";}?>>MN</OPTION>
                                                    <OPTION VALUE="MO" <?php if ($State == "MO") {echo "selected";}?>>MO</OPTION>
                                                    <OPTION VALUE="MS" <?php if ($State == "MS") {echo "selected";}?>>MS</OPTION>
                                                    <OPTION VALUE="MT" <?php if ($State == "MT") {echo "selected";}?>>MT</OPTION>
                                                    <OPTION VALUE="NC" <?php if ($State == "NC") {echo "selected";}?>>NC</OPTION>
                                                    <OPTION VALUE="ND" <?php if ($State == "ND") {echo "selected";}?>>ND</OPTION>
                                                    <OPTION VALUE="NE" <?php if ($State == "NE") {echo "selected";}?>>NE</OPTION>
                                                    <OPTION VALUE="NH" <?php if ($State == "NH") {echo "selected";}?>>NH</OPTION>
                                                    <OPTION VALUE="NJ" <?php if ($State == "NJ") {echo "selected";}?>>NJ</OPTION>
                                                    <OPTION VALUE="NM" <?php if ($State == "NM") {echo "selected";}?>>NM</OPTION>
                                                    <OPTION VALUE="NV" <?php if ($State == "NV") {echo "selected";}?>>NV</OPTION>
                                                    <OPTION VALUE="NY" <?php if ($State == "NY") {echo "selected";}?>>NY</OPTION>
                                                    <OPTION VALUE="OH" <?php if ($State == "OH") {echo "selected";}?>>OH</OPTION>
                                                    <OPTION VALUE="OK" <?php if ($State == "OK") {echo "selected";}?>>OK</OPTION>
                                                    <OPTION VALUE="OR" <?php if ($State == "OR") {echo "selected";}?>>OR</OPTION>
                                                    <OPTION VALUE="PA" <?php if ($State == "PA") {echo "selected";}?>>PA</OPTION>
                                                    <OPTION VALUE="RI" <?php if ($State == "RI") {echo "selected";}?>>RI</OPTION>
                                                    <OPTION VALUE="SC" <?php if ($State == "SC") {echo "selected";}?>>SC</OPTION>
                                                    <OPTION VALUE="SD" <?php if ($State == "SD") {echo "selected";}?>>SD</OPTION>
                                                    <OPTION VALUE="TN" <?php if ($State == "TN") {echo "selected";}?>>TN</OPTION>
                                                    <OPTION VALUE="TX" <?php if ($State == "TX") {echo "selected";}?>>TX</OPTION>
                                                    <OPTION VALUE="UT" <?php if ($State == "UT") {echo "selected";}?>>UT</OPTION>
                                                    <OPTION VALUE="VA" <?php if ($State == "VA") {echo "selected";}?>>VA</OPTION>
                                                    <OPTION VALUE="VT" <?php if ($State == "VT") {echo "selected";}?>>VT</OPTION>
                                                    <OPTION VALUE="WA" <?php if ($State == "WA") {echo "selected";}?>>WA</OPTION>
                                                    <OPTION VALUE="WI" <?php if ($State == "WI") {echo "selected";}?>>WI</OPTION>
                                                    <OPTION VALUE="WV" <?php if ($State == "WV") {echo "selected";}?>>WV</OPTION>
                                                    <OPTION VALUE="WY" <?php if ($State == "WY") {echo "selected";}?>>WY</OPTION>
                                                </SELECT>
                                            </div>
                                            <div class="div-50 divcontent">
                                                <label> Zip Code</label>
                                                <INPUT TYPE=TEXT NAME="fZipCode" id = "fZipCode" SIZE=8 value = "<? if ($ZipCode != "" ) {echo $ZipCode;}?>">
                                            </div>
                                        </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>Phone Number</label>
                                                <INPUT TYPE=TEXT NAME="fHomePhone" id = "fHomePhone"  SIZE=25 value = "<? if ($HomePhone != "" ) {echo $HomePhone;}?>">
                                            </div>
                                            <div class="div-50 divcontent">
                                                <label>Email</label>
                                                <INPUT TYPE=TEXT NAME="fEmail" id = "fEmail"  SIZE=25 value = "<? if ($Email != "" ) {echo $Email;}?>">
                                            </div>
                                         </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent no-pad">
                                                <div class="div-50 divcontent">
                                                    <label>Height</label>
                                                    <INPUT type=text Name="fOriginalHeight" id = "fOriginalHeight"   SIZE="5" value="<?if ($Height_ft != "" and $Height_in != "") {echo $Height_ft."'".$Height_in;} ?>" readonly />
                                                </div>
                                                <div class="div-50 divcontent">
                                                    <label>Weight</label>
                                                    <INPUT TYPE=TEXT Name="fweight" id = "fweight"   SIZE="5" value = "<? if ($weight != "" ) {echo $weight;}?>" readonly />
                                                </div>
                                            </div>

                                            <div class="div-50 divcontent no-pad">

                                            </div>
                                        </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>Birth Country</label>
                                                <INPUT TYPE=TEXT Name="fBirthCountry" id = "fBirthCountry"  SIZE="40" value = "<? if ($BirthCountry != "") {echo $BirthCountry;}?>"/>
                                            </div>
                                            <div class="div-50 divcontent">
                                                <label>Birth State</label>
                                                <select name="fBirthState" >
                                                    <option value="AL" <? if ($BirthState == ""	or $BirthState == "AL") { echo "selected";}?>>Alabama</option>
                                                    <option value="AK" <? if ($BirthState == "AK") { echo "selected";}?>>Alaska</option>
                                                    <option value="AZ" <? if ($BirthState == "AZ") { echo "selected";}?>>Arizona</option>
                                                    <option value="AR" <? if ($BirthState == "AR") { echo "selected";}?>>Arkansas</option>
                                                    <option value="CA" <? if ($BirthState == "CA") { echo "selected";}?>>California</option>
                                                    <option value="CO" <? if ($BirthState == "CO") { echo "selected";}?>>Colorado</option>
                                                    <option value="CT" <? if ($BirthState == "CT") { echo "selected";}?>>Connecticut</option>
                                                    <option value="DE" <? if ($BirthState == "DE") { echo "selected";}?>>Delaware</option>
                                                    <option value="DC" <? if ($BirthState == "DC") { echo "selected";}?>>Dist. Columbia</option>
                                                    <option value="FL" <? if ($BirthState == "FL") { echo "selected";}?>>Florida</option>
                                                    <option value="GA" <? if ($BirthState == "GA") { echo "selected";}?>>Georgia</option>
                                                    <option value="ID" <? if ($BirthState == "ID") { echo "selected";}?>>Idaho</option>
                                                    <option value="IL" <? if ($BirthState == "IL") { echo "selected";}?>>Illinois</option>
                                                    <option value="IN" <? if ($BirthState == "IN") { echo "selected";}?>>Indiana</option>
                                                    <option value="IA" <? if ($BirthState == "IA") { echo "selected";}?>>Iowa</option>
                                                    <option value="KS" <? if ($BirthState == "KS") { echo "selected";}?>>Kansas</option>
                                                    <option value="KY" <? if ($BirthState == "KY") { echo "selected";}?>>Kentucky</option>
                                                    <option value="LA" <? if ($BirthState == "LA") { echo "selected";}?>>Louisiana</option>
                                                    <option value="ME" <? if ($BirthState == "ME") { echo "selected";}?>>Maine</option>
                                                    <option value="MD" <? if ($BirthState == "MD") { echo "selected";}?>>Maryland</option>
                                                    <option value="MA" <? if ($BirthState == "MA") { echo "selected";}?>>Massachusetts</option>
                                                    <option value="MI" <? if ($BirthState == "MI") { echo "selected";}?>>Michigan</option>
                                                    <option value="MN" <? if ($BirthState == "MN") { echo "selected";}?>>Minnesota</option>
                                                    <option value="MS" <? if ($BirthState == "MS") { echo "selected";}?>>Mississippi</option>
                                                    <option value="MO" <? if ($BirthState == "MO") { echo "selected";}?>>Missouri</option>
                                                    <option value="MT" <? if ($BirthState == "MT") { echo "selected";}?>>Montana</option>
                                                    <option value="NE" <? if ($BirthState == "NE") { echo "selected";}?>>Nebraska</option>
                                                    <option value="NV" <? if ($BirthState == "NV") { echo "selected";}?>>Nevada</option>
                                                    <option value="NH" <? if ($BirthState == "NH") { echo "selected";}?>>New Hampshire</option>
                                                    <option value="NJ" <? if ($BirthState == "NJ") { echo "selected";}?>>New Jersey</option>
                                                    <option value="NM" <? if ($BirthState == "NM") { echo "selected";}?>>New Mexico</option>
                                                    <option value="NY" <? if ($BirthState == "NY") { echo "selected";}?>>NY Personal</option>
                                                    <option value="NY" <? if ($BirthState == "NY") { echo "selected";}?>>NY Business</option>
                                                    <option value="NC" <? if ($BirthState == "NC") { echo "selected";}?>>North Carolina</option>
                                                    <option value="ND" <? if ($BirthState == "ND") { echo "selected";}?>>North Dakota</option>
                                                    <option value="OH" <? if ($BirthState == "OH") { echo "selected";}?>>Ohio</option>
                                                    <option value="OK" <? if ($BirthState == "OK") { echo "selected";}?>>Oklahoma</option>
                                                    <option value="OR" <? if ($BirthState == "OR") { echo "selected";}?>>Oregon</option>
                                                    <option value="PA" <? if ($BirthState == "PA") { echo "selected";}?>>Pennsylvania</option>
                                                    <option value="RI" <? if ($BirthState == "RI") { echo "selected";}?>>Rhode Island</option>
                                                    <option value="SC" <? if ($BirthState == "SC") { echo "selected";}?>>South Carolina</option>
                                                    <option value="SD" <? if ($BirthState == "SD") { echo "selected";}?>>South Dakota</option>
                                                    <option value="TN" <? if ($BirthState == "TN") { echo "selected";}?>>Tennessee</option>
                                                    <option value="TX" <? if ($BirthState == "TX") { echo "selected";}?>>Texas</option>
                                                    <option value="UT" <? if ($BirthState == "UT") { echo "selected";}?>>Utah</option>
                                                    <option value="VT" <? if ($BirthState == "VT") { echo "selected";}?>>Vermont</option>
                                                    <option value="VA" <? if ($BirthState == "VA") { echo "selected";}?>>Virginia</option>
                                                    <option value="WA" <? if ($BirthState == "WA") { echo "selected";}?>>Washington</option>
                                                    <option value="WV" <? if ($BirthState == "WV") { echo "selected";}?>>West Virginia</option>
                                                    <option value="WI" <? if ($BirthState == "WI") { echo "selected";}?>>Wisconsin</option>
                                                    <option value="WY" <? if ($BirthState == "WY") { echo "selected";}?>>Wyoming</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>Occupation</label>
                                                <INPUT TYPE=TEXT Name="fOccupation" id = "fOccupation"SIZE="5" value = "<? if ($Occupation != "") {echo $Occupation;}?>" />
                                            </div>

                                            <div class="div-50 divcontent">
                                                <label>Name of Employer</label>
                                                <INPUT TYPE=TEXT Name="fNOE" id = "fNOE"  SIZE="5" value = "<? if ($NOE != "") {echo $NOE;}?>" />
                                            </div>
                                               </div>
                                        <div class = "row">
                                            <div class="div-50 divcontent">
                                                <label>Annual Income</label>
                                                <INPUT TYPE=TEXT Name="fAnnualIncome" id = "fAnnualIncome"  SIZE="5" value = "<? if ($AnnualIncome != "") {echo $AnnualIncome;}?>" />
                                            </div>

                                            <div class="div-50 divcontent" style="position:relative;">

                                                <label>Social Security Number</label>
                                                    <input type = "text" name = "fSocialSecurityNumber" id = "fSocialSecurityNumber" placeholder = "9 numbers" min = "9" size = "9" maxlength = "9" pattern="[0-9]{9,9}" title = "Please enter 9 numbers" value = "<? if ($SocialSecurityNumber != ""){echo $SocialSecurityNumber;}?>">


                                            </div>
                                        </div>

                                        <div class = "row" style = "margin-bottom:0px">
                                            <div class="div-50 divcontent">
                                                <label>Drivers License Number</label>
                                                <INPUT TYPE=TEXT Name="fDLN" id = "fDLN"   SIZE="5" value = "<? if ($DLN != "") {echo $DLN;}?>"/>
                                            </div>
                                            <div class="div-50 divcontent">
                                                <label>Drivers License State</label>
                                                <select name="fDLS" style = "width:250px">
                                                    <option value="AL" <? if ($DLS == "" or $DLS == "AL") {echo "selected";}?>>Alabama</option>
                                                    <option value="AK" <? if ($DLS == "AK") {echo "selected";}?>>Alaska</option>
                                                    <option value="AZ" <? if ($DLS == "AZ") {echo "selected";}?>>Arizona</option>
                                                    <option value="AR" <? if ($DLS == "AR") {echo "selected";}?>>Arkansas</option>
                                                    <option value="CA" <? if ($DLS == "CA") {echo "selected";}?>>California</option>
                                                    <option value="CO" <? if ($DLS == "CO") {echo "selected";}?>>Colorado</option>
                                                    <option value="CT" <? if ($DLS == "CT") {echo "selected";}?>>Connecticut</option>
                                                    <option value="DE" <? if ($DLS == "DE") {echo "selected";}?>>Delaware</option>
                                                    <option value="DC" <? if ($DLS == "DC") {echo "selected";}?>>Dist. Columbia</option>
                                                    <option value="FL" <? if ($DLS == "FL") {echo "selected";}?>>Florida</option>
                                                    <option value="GA" <? if ($DLS == "GA") {echo "selected";}?>>Georgia</option>
                                                    <option value="ID" <? if ($DLS == "ID") {echo "selected";}?>>Idaho</option>
                                                    <option value="IL" <? if ($DLS == "IL") {echo "selected";}?>>Illinois</option>
                                                    <option value="IN" <? if ($DLS == "IN") {echo "selected";}?>>Indiana</option>
                                                    <option value="IA" <? if ($DLS == "IA") {echo "selected";}?>>Iowa</option>
                                                    <option value="KS" <? if ($DLS == "KS") {echo "selected";}?>>Kansas</option>
                                                    <option value="KY" <? if ($DLS == "KY") {echo "selected";}?>>Kentucky</option>
                                                    <option value="LA" <? if ($DLS == "LA") {echo "selected";}?>>Louisiana</option>
                                                    <option value="ME" <? if ($DLS == "ME") {echo "selected";}?>>Maine</option>
                                                    <option value="MD" <? if ($DLS == "MD") {echo "selected";}?>>Maryland</option>
                                                    <option value="MA" <? if ($DLS == "MA") {echo "selected";}?>>Massachusetts</option>
                                                    <option value="MI" <? if ($DLS == "MI") {echo "selected";}?>>Michigan</option>
                                                    <option value="MN" <? if ($DLS == "MN") {echo "selected";}?>>Minnesota</option>
                                                    <option value="MS" <? if ($DLS == "MS") {echo "selected";}?>>Mississippi</option>
                                                    <option value="MO" <? if ($DLS == "MO") {echo "selected";}?>>Missouri</option>
                                                    <option value="MT" <? if ($DLS == "MT") {echo "selected";}?>>Montana</option>
                                                    <option value="NE" <? if ($DLS == "NE") {echo "selected";}?>>Nebraska</option>
                                                    <option value="NV" <? if ($DLS == "NV") {echo "selected";}?>>Nevada</option>
                                                    <option value="NH" <? if ($DLS == "NH") {echo "selected";}?>>New Hampshire</option>
                                                    <option value="NJ" <? if ($DLS == "NJ") {echo "selected";}?>>New Jersey</option>
                                                    <option value="NM" <? if ($DLS == "NM") {echo "selected";}?>>New Mexico</option>
                                                    <option value="NY" <? if ($DLS == "NY") {echo "selected";}?>>NY Personal</option>
                                                    <option value="NY" <? if ($DLS == "NY") {echo "selected";}?>>NY Business</option>
                                                    <option value="NC" <? if ($DLS == "NC") {echo "selected";}?>>North Carolina</option>
                                                    <option value="ND" <? if ($DLS == "ND") {echo "selected";}?>>North Dakota</option>
                                                    <option value="OH" <? if ($DLS == "OH") {echo "selected";}?>>Ohio</option>
                                                    <option value="OK" <? if ($DLS == "OK") {echo "selected";}?>>Oklahoma</option>
                                                    <option value="OR" <? if ($DLS == "OR") {echo "selected";}?>>Oregon</option>
                                                    <option value="PA" <? if ($DLS == "PA") {echo "selected";}?>>Pennsylvania</option>
                                                    <option value="RI" <? if ($DLS == "RI") {echo "selected";}?>>Rhode Island</option>
                                                    <option value="SC" <? if ($DLS == "SC") {echo "selected";}?>>South Carolina</option>
                                                    <option value="SD" <? if ($DLS == "SD") {echo "selected";}?>>South Dakota</option>
                                                    <option value="TN" <? if ($DLS == "TN") {echo "selected";}?>>Tennessee</option>
                                                    <option value="TX" <? if ($DLS == "TX") {echo "selected";}?>>Texas</option>
                                                    <option value="UT" <? if ($DLS == "UT") {echo "selected";}?>>Utah</option>
                                                    <option value="VT" <? if ($DLS == "VT") {echo "selected";}?>>Vermont</option>
                                                    <option value="VA" <? if ($DLS == "VA") {echo "selected";}?>>Virginia</option>
                                                    <option value="WA" <? if ($DLS == "WA") {echo "selected";}?>>Washington</option>
                                                    <option value="WV" <? if ($DLS == "WV") {echo "selected";}?>>West Virginia</option>
                                                    <option value="WI" <? if ($DLS == "WI") {echo "selected";}?>>Wisconsin</option>
                                                    <option value="WY" <? if ($DLS == "WY") {echo "selected";}?>>Wyoming</option>
                                                </select>
                                            </div>

                                        </div>


                                </div>
                                <div class="clearfix" style="margin-bottom:55px;"></div>
                                <div class="rightside-form">
                                    <h2>Beneficiaries</h2>
                                    <p>Enter the beneficiary information here. Addional beneficiaries can be added by selecing "Add Beneficiary". Please ensure the combined percentage equals 100%.</p>
                                </div>

                                <div class="leftside-form">
                                    <div class="row" id="beneficiary-row">
                                        <div class = "row"  ID = "Beneficiary0" >
                                            <div class = "row">
                                                <div class="div-50 divcontent">
                                                    <label>First Name</label>
                                                    <INPUT TYPE=TEXT Name="BeneficiaryFirstName0" ID="BeneficiaryFirstName0" SIZE=25 value = "<? if ($_SESSION['BeneficiaryFirstName0'] != "") {echo $_SESSION['BeneficiaryFirstName0'];}?>"/>
                                                </div>

                                                <div class="div-50 divcontent">
                                                    <label>Last Name</label>
                                                    <INPUT TYPE=TEXT Name="BeneficiaryLastName0" ID="BeneficiaryLastName0" SIZE=25 value = "<? if ($_SESSION['BeneficiaryLastName0'] != "") {echo $_SESSION['BeneficiaryLastName0'];}?>"/>
                                                </div>
                                            </div>
                                            <div class = "row">
                                                <div class="div-50 divcontent">
                                                    <label>Relationship</label>
                                                    <INPUT TYPE=TEXT NAME="BeneficiaryRelationship0" id = "BeneficiaryRelationship0"  SIZE=25 value = "<? if ($_SESSION['BeneficiaryRelationship0'] != "" ) {echo $_SESSION['BeneficiaryRelationship0'];}?>">
                                                </div>
                                                <div class="div-50 divcontent">
                                                    <label>Percentage</label>
                                                    <INPUT TYPE=TEXT Name="BeneficiaryPercentage0" ID="BeneficiaryPercentage0" SIZE=25  value = "<? if (!isset($_SESSION['BeneficiaryPercentage0'])) {echo "0";} else { echo $_SESSION['BeneficiaryPercentage0'];;}?>" />
                                                </div>
                                            </div>
                                            <div class = "row">
                                                <div class="div-50 divcontent">
                                                    <label>State Address</label>
                                                    <INPUT TYPE=TEXT NAME="BeneficiaryAddress0" ID="BeneficiaryAddress0" SIZE=25 value = "<? if ($_SESSION['BeneficiaryAddress0'] != "") {echo $_SESSION['BeneficiaryAddress0'];}?>" >
                                                </div>
                                                <div class="div-50 divcontent">
                                                    <label>City</label>
                                                    <INPUT TYPE=TEXT NAME="BeneficiaryCity0" ID="BeneficiaryCity0" SIZE=25 value = "<? if ($_SESSION['BeneficiaryCity0'] != "") {echo $_SESSION['BeneficiaryCity0'];}?>">
                                                </div>
                                            </div>
                                            <div class = "row">
                                                <div class="div-50 divcontent">
                                                    <label>State</label>
                                                    <SELECT name="BeneficiaryState0" ID="BeneficiaryState0" >
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
                                                    </SELECT>
                                                </div>
                                                <div class="div-50 divcontent">
                                                    <label> Zip Code</label>
                                                    <INPUT TYPE=TEXT NAME="BeneficiaryZipCode0" ID="BeneficiaryZipCode0" SIZE=8 value = "<? if ($_SESSION['BeneficiaryLastName0'] != "") {echo $_SESSION['BeneficiaryLastName0'];}?>">
                                                </div>

                                                <div class = "row" ID = "BeneficiaryError0" style = "margin: 10px 0px 0px 0px;color: #FF726D">
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
                                        </div>
                                    </div>

                                    <!-- Hidden field for beneficiaries -->
                                    <input type = "hidden" name = "fBeneficiaryCounter" ID = "BeneficiaryCounter" value = "<? echo $BeneficiaryCounter;?>">
                                    <input type = "hidden" name = "fBeneficiaryData" ID = "BeneficiaryData" value = "">
                                    <div class=" btncon form-inputs">
                                        <div class="" style="margin:20px 0 0 0; padding:0; clear:both;">
                                            <input type = "button" name = "sendreq" value = "ADD BENEFICIARY"  style="height:40px!important;" class = "button" ID = "add-beneficiary">
                                        </div>
                                    </div>

                                    <div class = "row" ID = "error-field" style = "display: none; color: #FF726D;">
                                    </div>

                                    <div class=" btncon form-inputs">
                                        <input type = "button" name = "sendreq" value = "CONTINUE" class = "button" ID = "request-button">
                                    </div>
                                </div>

                            </FORM>
                        </div><!-- .entry-content -->
                        <?php

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

<div id="footer" class="footer-wrapper">
        <div class="container"><p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p></div>
    </div>
<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<?php //get_footer(); ?>