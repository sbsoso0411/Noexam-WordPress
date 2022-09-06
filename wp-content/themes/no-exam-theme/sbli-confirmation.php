<?php
/**
 * Template Name:  SBLI Confirmation
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

// Storage
get_header();
$_POST['page'] = 3;

// Helpers
require("inc/helpers.php");
// Storage
require("inc/storage.php");

$FirstName 		=	$_SESSION->fFirstName;
$LastName 		=	$_SESSION->fLastName;
$HomePhone 		=	$_SESSION->fHomePhone;
$Email			=	$_SESSION->fEmail;
$Sex 			=	$_SESSION->fSex;
$State 			=	$_SESSION -> fState;

?>

<script type="text/javascript">
    function resetErrorHighlighting(){
        document.getElementById("fFirstName").style.borderColor = "#dddddd";
        document.getElementById("fLastName").style.borderColor = "#dddddd";
        document.getElementById("fAddress").style.borderColor = "#dddddd";
        document.getElementById("fCity").style.borderColor = "#dddddd";
        document.getElementById("fState").style.borderColor = "#dddddd";
        document.getElementById("fZipCode").style.borderColor = "#dddddd";
        document.getElementById("fHomePhone").style.borderColor = "#dddddd";
        document.getElementById("fSocialSecurityNumber").style.borderColor = "#dddddd";
        document.getElementById("fEmail").style.borderColor = "#dddddd";
    }
    function userFieldsValidate(){
        var error = 0;
        resetErrorHighlighting();
        $("#main-error-field").html("");
        if ($("#fFirstName").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"First Name\" must be filled in.</div>");
            document.getElementById("fFirstName").style.borderColor = "red";
            error = 1;
        }
        if ($("#fLastName").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Last Name\" must be filled in.</div>");
            document.getElementById("fLastName").style.borderColor = "red";
            error = 1;
        }
        if ($("#fAddress").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Address\" must be filled in.</div>");
            document.getElementById("fAddress").style.borderColor = "red";
            error = 1;
        }
        if ($("#fCity").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"City\" must be filled in.</div>");
            document.getElementById("fCity").style.borderColor = "red";
            error = 1;
        }
        if ($("#fState").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"State\" must be filled in.</div>");
            document.getElementById("fState").style.borderColor = "red";
            error = 1;
        }
        if ($("#fZipCode").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Zip Code\" must be filled in.</div>");
            document.getElementById("fZipCode").style.borderColor = "red";
            error = 1;
        }
        if ($("#fHomePhone").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Home Phone\" must be filled in.</div>");
            document.getElementById("fHomePhone").style.borderColor = "red";
            error = 1;
        }
        if ($("#fSocialSecurityNumber").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Social Security Number\" must be filled in.</div>");
            document.getElementById("fSocialSecurityNumber").style.borderColor = "red";
            error = 1;
        }
        if ($("#fEmail").val() == ""){
            $("#main-error-field").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Email\" must be filled in.</div>");
            document.getElementById("fEmail").style.borderColor = "red";
            error = 1;
        }
        return error;
    }

    $(document).ready(function(){
        $("#form-input-submit").click(function(){
            var mainError = userFieldsValidate();
            if (mainError == 0) {
                $("#error-field").hide();
                $("#main-error-field").hide();
                $(".form-inputs-request").submit();
            } else {
                $("#error-field").show();
                $("#main-error-field").show();
            }
        });
    });

</script>

<div id="content-area">
    <div class="sbli-appcon">
		<div class="sbliconfifmation-area">
            <form action = "/post-page/" method = "post" class="form-inputs-request">
                <div class="sbiltitle-row">
                    <img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sbli-title.png"/>
                </div>
                <div class="topPartsbliconfifmation">
                    <div class="sbliconfifmation-rt">
                        <div class="video">
                            <script src="//fast.wistia.com/embed/medias/b2qixvddpl.jsonp" async></script>
                            <script src="//fast.wistia.com/assets/external/E-v1.js" async></script>
                            <div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;">
                                <div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
                                    <div class="wistia_embed wistia_async_b2qixvddpl videoFoam=true" style="height:100%;width:100%">&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p>Upon completing this application, a representative from the insurance company will call within 24 hours to complete a phone interview. Once this is complete you will get a decision within 2-4 weeks.</p>
                    </div>
                    <div class="sbliconfifmation-lt">

                        <input type = "hidden" name = "pageID" value = "101">
                        <div class="input-row-lt">
                            <label>First Name</label>
                            <input name="fFirstName" id="fFirstName" type="text" value="<?php echo $FirstName;?>"/>
                        </div>
                        <div class="input-row-rt">
                            <label>Last name</label>
                            <input name="fLastName" id="fLastName" type="text" value="<?php echo $LastName;?>" />
                        </div>

                        <div class="input-row-lt">
                            <label>Street Address</label>
                             <input name="fAddress" id="fAddress" type="text" />
                        </div>
                        <div class="input-row-rt">
                            <label>City</label>
                            <input name="fCity" id="fCity" type="text" />
                        </div>

                        <?php
                        $state_names = array('Alabama', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Delware',
                            'Dist. Columbia', 'Florida', 'Georgia', 'Idaho', 'Illinois', 'Indiana', 'Iowa',
                            'Kansas', 'Kentucky', 'Louisiana', 'Maryland', 'Michigan', 'Minnesota', 'Mississippi',
                            'Missouri', 'Nebraska', 'Nevada', 'New Jersey', 'North Carolina', 'North Dakota', 'Ohio',
                            'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee',
                            'Texas', 'Utah', 'Virginia', 'West Virginia', 'Wisconsin', 'Wyoming');
                        $state_letters = array('AL', 'AZ', 'AR', 'CA', 'CO', 'DE', 'DC', 'FL',
                            'GA', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'MD', 'MI',
                            'MN', 'MS', 'MO', 'NE', 'NV', 'NJ', 'NC', 'ND', 'OH', 'OK', 'OR',
                            'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VA', 'WV', 'WI', 'WY')
                        ?>

                        <div class="input-row-lt">
                            <label>State</label>
                            <select name="fState" id="fState">
                            <?php for ($i=0; $i<count($state_letters); $i++ ): ?>
                                <option value="<?php echo $state_letters[$i]; ?>" <?php if ( $state_letters[$i] == $State ) {echo "selected";}?>><?php echo $state_names[$i]; ?></option>
                            <?php endfor; ?>
                            </select>
                        </div>
                        <div class="input-row-rt">
                            <label>Postcode</label>
                            <input name="fZipCode" id="fZipCode" type="text" />
                        </div>

                        <div class="input-row-lt">
                            <label>Phone Number</label>
                            <input name="fHomePhone" id="fHomePhone" type="text" value="<?php echo $HomePhone ?>"/>
                        </div>
                        <div class="input-row-rt">
                            <label>Social Security Number</label>
                            <input name="fSocialSecurityNumber" id="fSocialSecurityNumber" type="text" />
                            
                            
                            <div class="tooltipcon">
                            	<a href="#"><span>?</span>
                                	<div class="tooltipcontent">
                                    	<i></i>
                                    	<p>Used by life insurance company to verify identity only and will not hurt credit score. Transmitted securely via 256-Bit encryption. NoExam.com does not store this data.</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="input-row-lt">
                            <label>Email</label>
                            <input type="text" name="fEmail" id = "fEmail"  SIZE=25 value = "<? if ($Email != "" ) {echo $Email;}?>">
                        </div>

                        <p style="clear: both;"></p>

                    </div>
                
                </div>
            
                <div class="sblitable-con">
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Do you have any history of cancer, diabetes or cardiovascular disease?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer1" type="radio" value="No" <? if ($_SESSION -> fanswer1 == "No" or !isset($_SESSION -> fanswer1)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer1" type="radio" value="Yes" <? if ($_SESSION -> fanswer1 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Have you been treated for alcohol or drug abuse?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer2" type="radio" value="No" <? if ($_SESSION -> fanswer2 == "No" or !isset($_SESSION -> fanswer2)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer2" type="radio" value="Yes" <? if ($_SESSION -> fanswer2 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Have you ever taken medication for high blood pressure?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer3" type="radio" value="No" <? if ($_SESSION -> fanswer3 == "No" or !isset($_SESSION -> fanswer3)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer3" type="radio" value="Yes" <? if ($_SESSION -> fanswer3 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Have you ever been convicted of DUI/DWI or reckless driving?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer4" type="radio" value="No" <? if ($_SESSION -> fanswer4 == "No" or !isset($_SESSION -> fanswer4)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer4" type="radio" value="Yes" <? if ($_SESSION -> fanswer4 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Has your driver license been suspended or revoked</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer5" type="radio" value="No" <? if ($_SESSION -> fanswer5 == "No" or !isset($_SESSION -> fanswer5)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer5" type="radio" value="Yes" <? if ($_SESSION -> fanswer5 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Do you intend to replace existing life insurance cover with this policy</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer6" type="radio" value="No" <? if ($_SESSION -> fanswer6 == "No" or !isset($_SESSION -> fanswer6)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer6" type="radio" value="Yes" <? if ($_SESSION -> fanswer6 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                </div>

                <div class = "row" id = "main-error-field" style = "margin: 5px 0px 0px 5px;padding:0px;display:none;color: #FF726D;">

                </div>

                <input type = "button" name = "sendreq" value = "Submit" id="form-input-submit" class = "sbli-submit">
            </form>
        </div>
     </div>
</div> 
 
<?php get_footer(); ?>