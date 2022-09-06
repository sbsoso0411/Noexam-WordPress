<?php
/**
 * Template Name:  NA Confirmation
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

        if ($("input[name=fanswer7]:checked").val() == "Yes"){
            $("#ext-answ-q7").show();
        };
        $("input[name=fanswer7]:radio").change(function(){
            if ($(this).val() == "Yes"){
                $("#ext-answ-q7").show();
            } else {
                $("#ext-answ-q7").hide();
            }
        });

        if ($("input[name=fanswer8]:checked").val() == "Yes"){
            $("#ext-answ-q8").show();
        };
        $("input[name=fanswer8]:radio").change(function(){
            if ($(this).val() == "Yes"){
                $("#ext-answ-q8").show();
            } else {
                $("#ext-answ-q8").hide();
            }
        });
    });

</script>

<div id="content-area">
    <div class="sbli-appcon">
		<div class="sbliconfifmation-area">
            <form action = "/post-page/" method = "post" class="form-inputs-request">
                <div class="sbiltitle-row">
                    <img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/na-logo.png"/>
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

                        <p>Upon completing this application, a representative from NoExam.com will contact you to complete your application and provide you with an instant over-the-phone decision.</p>
                    </div>
                    <div class="sbliconfifmation-lt">

                        <input type = "hidden" name = "pageID" value = "201">
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
                            <label>Email</label>
                            <input type="text" name="fEmail" id = "fEmail"  SIZE=25 value = "<? if ($Email != "" ) {echo $Email;}?>">
                        </div>

                        <p style="clear: both;"></p>
                    </div>
                
                </div>
            
                <div class="sblitable-con">
                    <div class="yesnorow">
                        <div class="yesnotext"><p>In the past 10 years, has the proposed insured been involved in any bankruptcy proceedings or currently planning such proceedings?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer1" type="radio" value="No" <? if ($_SESSION -> fanswer1 == "No" or !isset($_SESSION -> fanswer1)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer1" type="radio" value="Yes" <? if ($_SESSION -> fanswer1 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>In the past 5 years, has the proposed insured had a driver's license revoked or suspended or been convicted of reckless driving or driving under the influence of alcohol or drugs (DWI, DUI)?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer2" type="radio" value="No" <? if ($_SESSION -> fanswer2 == "No" or !isset($_SESSION -> fanswer2)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer2" type="radio" value="Yes" <? if ($_SESSION -> fanswer2 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>In the past 5 years, has the proposed insured used marijuana, cocaine, heroin or other illicit or street drugs, or any controlled substances not prescribed to you by a licensed medical professional, or been treated for drug abuse, or been a member of a drug support group?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer3" type="radio" value="No" <? if ($_SESSION -> fanswer3 == "No" or !isset($_SESSION -> fanswer3)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer3" type="radio" value="Yes" <? if ($_SESSION -> fanswer3 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Has the proposed insured been advised by a licensed medical professional to discontinue or reduce alcohol use or been treated for alcohol abuse in the last 5 years?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer4" type="radio" value="No" <? if ($_SESSION -> fanswer4 == "No" or !isset($_SESSION -> fanswer4)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer4" type="radio" value="Yes" <? if ($_SESSION -> fanswer4 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Is the proposed insured currently receiving illness or disability benefits or compensation or have an application pending for such benefits or do you require assistance with activities of daily living including bathing, dressing, eating or use of the toilet?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer5" type="radio" value="No" <? if ($_SESSION -> fanswer5 == "No" or !isset($_SESSION -> fanswer5)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer5" type="radio" value="Yes" <? if ($_SESSION -> fanswer5 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext"><p>Does the proposed insured have any unpaid liens, judgments, or collection accounts totaling $1,000 or more?</p></div>
                        <div class="yesnoaction"><label>No<input name="fanswer6" type="radio" value="No" <? if ($_SESSION -> fanswer6 == "No" or !isset($_SESSION -> fanswer6)){echo "checked";}?> /></label></div>
                        <div class="yesnoaction"><label>Yes<input name="fanswer6" type="radio" value="Yes" <? if ($_SESSION -> fanswer6 == "Yes"){echo "checked";}?> /></label></div>
                    </div>
                    <div class="yesnorow">
                        <div class="yesnotext">
                            <p>Has the proposed insured been diagnosed with any medical conditions?</p>
                        </div>
                        <div class="yesnoaction">
                            <label class = "chk"><input type="radio" <? if ($_SESSION -> fanswer7 == "No" or !isset($_SESSION -> fanswer7)){echo "checked";}?> name = "fanswer7" value = "No">No</label>
                        </div>
                        <div class="yesnoaction">
                            <label class = "chk"><input type="radio" <? if ($_SESSION -> fanswer7 == "Yes"){echo "checked";}?> name = "fanswer7" value = "Yes">Yes</label>
                        </div>

                        <div class = "extended-answer" ID = "ext-answ-q7" style = "width:100%;float:left;margin-top: 5px;display:none">
                            Extended answer:
                            <input style = "margin:7px 0px 0px 0px; padding: 4px; width: 100%; height: 41px;" type="text" name = "fext_answ_q7" value = "<? if ($_SESSION -> fext_answ_q7 != ""){echo $_SESSION -> fext_answ_q7;} else {echo "";} ?>">
                        </div>
                    </div>

                    <div class="yesnorow">
                        <div class="yesnotext">
                            <p>In the past 12 months, has the proposed insured been prescribed any medications?</p>
                        </div>
                        <div class="yesnoaction">
                            <label class = "chk"><input type="radio" <? if ($_SESSION -> fanswer8 == "No" or !isset($_SESSION -> fanswer8)){echo "checked";}?> name = "fanswer8" value = "No">No</label>
                        </div>
                        <div class="yesnoaction">
                            <label class = "chk"><input type="radio" <? if ($_SESSION -> fanswer8 == "Yes"){echo "checked";}?> name = "fanswer8" value = "Yes">Yes</label>
                        </div>

                        <div class = "extended-answer" ID = "ext-answ-q8" style = "width:100%;float:left;margin-top: 5px;display:none">
                            Extended answer:
                            <input style = "margin:7px 0px 0px 0px; padding: 4px; width: 100%; height: 41px;" type="text" name = "fext_answ_q8" value = "<? if ($_SESSION -> fext_answ_q8 != ""){echo $_SESSION -> fext_answ_q8;} else {echo "";} ?>">
                        </div>
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