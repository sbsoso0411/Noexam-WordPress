<?php
/**
 * Template Name: medical-questions
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
$_POST['page'] = 3;
include("header.php");
// Helpers
require("inc/helpers.php");
// Storage
require("inc/storage.php");

?>

<script type="text/javascript">
	$(document).ready(function(){
		if ($("input[name=fanswer9]:checked").val() == "Yes"){
			$(".hidden-question").show();
		} else {
			$(".hidden-question").hide();
		}
		
		$("input[type=radio][name=fanswer9]").change(function(){
			if ($(this).val() == "Yes"){
				$(".hidden-question").show();
			} else {
				$(".hidden-question").hide();
			}
		});
	});
</script>

<div id="form-area">
	<div class="container">
    	<div class="row">
        	<div class="five columns">
				<div class="entry-header">
                    <h1 class="entry-title">Medical History</h1>
                </div><!-- .entry-header -->
            </div>
            <div class="eleven columns progresslist">
                <ul>
                    <li><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
                    <li class="selected"><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
                    <li><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
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
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->

                    <div class="entry-content form-con-area">
                        <?php
                        the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
                        wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
                        ?>
                
                        <?
                        /*
                        You can add as many questions to the form below as you like. At the bottom are a number of commented out questions. To use any of these remove the <!-- before it and the --> after the quetion.

                        Any addition fields that were on the initial select page and sent on from the quotes.php page will need to have a hidden line added to pass the data onto the next page below: TYPE=HIDDEN
                        */
                        ?>

                        <div class="form_head-title">Please answer the following questions:</div>
                        <form action = "/post-page/" method = "post" class = "form-inputs new">
                            <div class="form-inputs-inner">
                            <!--Page3??-->
                            <input type = "hidden" name = "pageID" value = "3">
                            <div class = "row">
                                <div class = "question" >
                                    <div style = "float:left;width:650px">
                                        <div class="icon-ara">
                                            <strong>1</strong>
                                        </div>
                                        <div class="text-con">
                                            Does the Proposed Insured currently receive health care at home, or require assistance with activities of daily living such as bathing, dressing, feeding, taking medications or use of toilet?
                                        </div>
                                    </div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio"	<? if ($_SESSION -> fanswer1 == "Yes"){echo "checked";}?> name = "fanswer1" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer1 == "No" or !isset($_SESSION -> fanswer1)){echo "checked";}?> name = "fanswer1" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question" style = "margin-top:15px">
                                    <div style = "float:left;width:650px">
                                        <div class="icon-ara"><strong>2</strong></div>
                                        <div class="text-con">Is the Proposed Insured currently in a Hospital, Psychiatric, Extended or Assisted Care, Nursing Facility?</div>
                                    </div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer2 == "Yes"){echo "checked";}?> name = "fanswer2" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer2 == "No" or !isset($_SESSION -> fanswer2)){echo "checked";}?> name = "fanswer2" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>3</strong></div> <div class="text-con">Is the Proposed Insured currently in a Prison or Correctional facility due to a misdemeanor or felony conviction?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer3 == "Yes"){echo "checked";}?> name = "fanswer3" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio"	<? if ($_SESSION -> fanswer3 == "No" or !isset($_SESSION -> fanswer3)){echo "checked";}?> name = "fanswer3" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px">
                                        <div class="icon-ara">
                                            <strong>4</strong>
                                        </div>
                                        <div class="text-con">
                                            Has the Proposed Insured ever tested positive for the HIV virus or been diagnosed by a member of the medical profession as having AIDS or the AIDS Related Complex (ARC)?
                                        </div>
                                    </div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer4 == "Yes"){echo "checked";}?> name = "fanswer4" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer4 == "No" or !isset($_SESSION -> fanswer4)){echo "checked";}?> name = "fanswer4" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px">
                                        <div class="icon-ara">
                                            <strong>5</strong>
                                        </div>
                                        <div class="text-con">
                                            Has the Proposed Insured ever tested positive for or been diagnosed by a member of the medical profession as having Alzheimer’s or Dementia, Cirrhosis, Emphysema or Chronic Obstructive Pulmonary Disease (COPD)?
                                        </div>
                                    </div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer5 == "Yes"){echo "checked";}?> name = "fanswer5" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer5 == "No" or !isset($_SESSION -> fanswer5)){echo "checked";}?> name = "fanswer5" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row shaded">
                                <div style = "float:left"><strong>6</strong> Has the Proposed Insured:</div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>6.1</strong></div><div class="text-con"> In the past 12 months been advised by a physician to be hospitalized or to have Diagnostic Tests, Surgery, or any medical procedure that has not yet been completed or for which the results are not yet available, except those tests related to the Human Immunodeficiency Virus (AIDS)?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer6 == "Yes"){echo "checked";}?> name = "fanswer6" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer6 == "No" or !isset($_SESSION -> fanswer6)){echo "checked";}?> name = "fanswer6" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>6.2</strong></div> <div class="text-con">In the past 24 months been diagnosed as having or advised by a physician to have treatment for Cancer (other than Basal Cell Carcinoma), Heart Attack, Stroke or TIA (Transient Ischemic Attack), Alcohol or Drug Abuse?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer7 == "Yes"){echo "checked";}?> name = "fanswer7" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer7 == "No" or !isset($_SESSION -> fanswer7)){echo "checked";}?> name = "fanswer7" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>6.3</strong></div><div class="text-con">In the past 24 months had a Driver’s License revoked or suspended, or been convicted of 2 or more moving violations, or been convicted of a violation for driving while intoxicated or under the influence, or for driving while ability impaired because of the use of alcohol and/or drugs?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer8 == "Yes"){echo "checked";}?> name = "fanswer8" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer8 == "No" or !isset($_SESSION -> fanswer8)){echo "checked";}?> name = "fanswer8" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "question">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>7</strong></div> <div class="text-con">Do you currently have any in force life insurance or annuity contracts?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer9 == "Yes"){echo "checked";}?> name = "fanswer9" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer9 == "No" or !isset($_SESSION -> fanswer9)){echo "checked";}?> name = "fanswer9" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                            <div class = "row">
                                <div class = "hidden-question" ID = "hidden-question8">
                                    <div style = "float:left;width:650px"><div class="icon-ara"><strong>8</strong></div> <div class="text-con">Will the policy applied for replace or change an existing life insurance or annuity contract?</div></div>
                                    <div style = "float:right;width:130px;">
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer10 == "Yes"){echo "checked";}?> name = "fanswer10" value = "Yes">Yes</label>
                                        <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer10 == "No" or !isset($_SESSION -> fanswer10)){echo "checked";}?> name = "fanswer10" value = "No">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
	                        <input type = "submit" name = "sendreq" value = "Submit" ID = "form-input-submit" class = "button">
                        </form>

                        <form action = "/under-review/" method = "post" class = "form-abort">
                            <!--HiddenFields-->
                            <INPUT TYPE=HIDDEN NAME="rateclass" 	VALUE="<?echo"$rateclass";?>">
                            <INPUT TYPE=HIDDEN NAME="FaceAmount" 	VALUE="<?echo"$FaceAmount";?>">
                            <INPUT TYPE=HIDDEN NAME="term" 			VALUE="<?echo"$Catagory";?>">
                            <INPUT TYPE=HIDDEN NAME="ModeUsed" 		VALUE="<?echo"$ModeUsed";?>">
                            <INPUT TYPE=HIDDEN NAME="premium" 		VALUE="<?echo"$premium";?>">
                            <INPUT TYPE=HIDDEN NAME="company" 		VALUE="<?echo"$company";?>">
                            <INPUT TYPE=HIDDEN NAME="product" 		VALUE="<?echo"$product";?>">
                            <INPUT TYPE=HIDDEN NAME="apremium" 		VALUE="<?echo"$apremium";?>">
                            <INPUT TYPE=HIDDEN NAME="mpremium" 		VALUE="<?echo"$mpremium";?>">

                            <INPUT TYPE=HIDDEN NAME="BirthMonth" 	VALUE="<?echo"$BirthMonth";?>">
                            <INPUT TYPE=HIDDEN NAME="Birthday" 		VALUE="<?echo"$Birthday";?>">
                            <INPUT TYPE=HIDDEN NAME="BirthYear" 	VALUE="<?echo"$BirthYear";?>">
                            <INPUT TYPE=HIDDEN NAME="Sex" 			VALUE="<?echo"$Sex";?>">
                            <INPUT TYPE=HIDDEN NAME="Smoker" 		VALUE="<?echo"$Smoker";?>">
                            <INPUT TYPE=HIDDEN NAME="Health" 		VALUE="<?echo"$Health";?>">
                            <INPUT TYPE=HIDDEN NAME="Catagory" 		VALUE="<?echo"$Catagory";?>">
                            <INPUT TYPE=HIDDEN NAME="FirstName" 	VALUE="<?echo"$FirstName";?>">
                            <INPUT TYPE=HIDDEN NAME="LastName" 		VALUE="<?echo"$LastName";?>">
                            <INPUT TYPE=HIDDEN NAME="HomePhone" 	VALUE="<?echo"$HomePhone";?>">
                            <INPUT TYPE=HIDDEN NAME="height" 		VALUE ="<?echo"$height"?>">
                            <INPUT TYPE=HIDDEN NAME="weight" 		VALUE ="<?echo"$weight"?>">
                            <INPUT TYPE=HIDDEN NAME="State" 		VALUE ="<?echo"$State"?>">
                            <INPUT TYPE=HIDDEN NAME="Email" 		VALUE ="<?echo"$Email"?>">
                            <input type=hidden name="Height_ft" 	value="<?php echo "$Height_ft";?>">
                            <input type=hidden name="Height_in" 	value="<?php echo "$Height_in";?>">
                        </form>
	
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
    <div class="container">
        <p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p>
    </div>
</div>
<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
<script src="//static.getclicky.com/js" type="text/javascript"></script>
<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<?php //get_footer(); ?>