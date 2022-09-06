<?php
/**
 * Template Name: additional-questions-2
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

include("header.php");
// Helpers
require("inc/helpers.php");
// Storage
$_POST['page'] = 5;
require("inc/storage.php");

if
(
	$_SESSION -> fanswer1 == "Yes" or
	$_SESSION -> fanswer2 == "Yes" or
	$_SESSION -> fanswer3 == "Yes" or
	$_SESSION -> fanswer4 == "Yes" or
	$_SESSION -> fanswer5 == "Yes" or
	$_SESSION -> fanswer6 == "Yes" or
	$_SESSION -> fanswer7 == "Yes" or
	$_SESSION -> fanswer8 == "Yes"
){
	header("Location: /under-review/");
}



?>

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
						<li class="selected"><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
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
										
									</header><!-- .entry-header -->

									<div class="entry-content form-con-area"><?php

										the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
										?>

										<?
										/*
                                        You can add as many questions to the form below as you like. At the bottom are a number of commented out questions. To use any of these remove the <!-- before it and the --> after the quetion.

                                        Any addition fields that were on the initial select page and sent on from the quotes.php page will need to have a hidden line added to pass the data onto the next page below: TYPE=HIDDEN
                                        */

										?>
										<script type="text/javascript">
											$(document).ready(function(){

												if ($("input[name=fanswer23]:checked").val() == "Yes"){
													$("#ext-answ-q23").show();
												};
												$("input[name=fanswer23]:radio").change(function(){
													if ($(this).val() == "Yes"){
														$("#ext-answ-q23").show();
													} else {
														$("#ext-answ-q23").hide();
													}
												});

												if ($("input[name=fanswer24]:checked").val() == "Yes"){
													$("#ext-answ-q24").show();
												};
												$("input[name=fanswer24]:radio").change(function(){
													if ($(this).val() == "Yes"){
														$("#ext-answ-q24").show();
													} else {
														$("#ext-answ-q24").hide();
													}
												});

												if ($("input[name=fanswer25]:checked").val() == "Yes"){
													$("#ext-answ-q25").show();
												};
												$("input[name=fanswer25]:radio").change(function(){
													if ($(this).val() == "Yes"){
														$("#ext-answ-q25").show();
													} else {
														$("#ext-answ-q25").hide();
													}
												});

												if ($("input[name=fanswer26]:checked").val() == "Yes"){
													$("#ext-answ-q26").show();
												};
												$("input[name=fanswer26]:radio").change(function(){
													if ($(this).val() == "Yes"){
														$("#ext-answ-q26").show();
													} else {
														$("#ext-answ-q26").hide();
													}
												});

												if ($("input[name=fanswer28]:checked").val() == "Yes"){
													$("#ext-answ-q28").show();
												};
												$("input[name=fanswer28]:radio").change(function(){
													if ($(this).val() == "Yes"){
														$("#ext-answ-q28").show();
													} else {
														$("#ext-answ-q28").hide();
													}
												});

                                                $("#frmsbmt").click(function(){
                                                    $("#additional-medical-frm").submit();
                                                })

											});
										</script>

										<div class="form_head-title">Please answer the following questions:</div>

										<form action = "/post-page/" id = "additional-medical-frm" method = "post" class = "form-inputs new">
											<div class="form-inputs-inner">
												<input type = "hidden" name = "pageID" value = "5">
												<div class="row">
													<div class = "question">
														<div style = "width:650px;float:left;"><div class="icon-ara"><strong>1</strong></div> <div class="text-con"> In the past 5 years, have you used illegal drugs, consulted a member of the medical profession or been treated, hospitalized, or taken medication for abuse of alcohol or drugs (including prescription drugs)?</div></div>
														<div style = "float:right; width:130px">
															<label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer23 == "Yes"){echo "checked";}?> name = "fanswer23" value = "Yes">Yes</label>
															<label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer23 == "No" or !isset($_SESSION -> fanswer23)){echo "checked";}?> name = "fanswer23" value = "No">No</label>
														</div>
													</div>
													<div class = "extended-answer" ID = "ext-answ-q23" style = "width:100%;float:left;margin-top: 5px;display:none">
														Extended answer:
														<input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q23" value = "<? if ($_SESSION -> fext_answ_q23 != ""){echo $_SESSION -> fext_answ_q23;} else {echo "";} ?>">
													</div>
												</div>

                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><strong>2</strong> In the past 5 years, have you been convicted of a felony?</div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer24 == "Yes"){echo "checked";}?> name = "fanswer24" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer24 == "No" or !isset($_SESSION -> fanswer24)){echo "checked";}?> name = "fanswer24" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer"  id = "ext-answ-q24" style = "float:left;width:100%;margin-top:5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q24" value = "<? if ($_SESSION -> fext_answ_q24 != ""){echo $_SESSION -> fext_answ_q24;} else {echo "";} ?>">
                                                    </div>
                                                </div>

                                                <? if ($_SESSION -> fSmoker == "Y"){
                                                    if ($_SESSION -> fanswer28 == "Yes"){$temp1 = "checked";}
                                                    if ($_SESSION -> fanswer28 == "No" or !isset($_SESSION -> fanswer28)){$temp2 = "checked";}
                                                    if ($_SESSION -> fext_answ_q28 != ""){ $ext_answ = $_SESSION -> fext_answ_q28;} else { $ext_answ = "";}
                                                    echo '<div class = "row">
                                                            <div class = "question">
                                                                <div style = "width:650px;float:left;"><b style="font-weight: bold; color: #333;">Additional</b> In the past 24 months have you used any form of tobacco or nicotine products?</div>
                                                                <div style = "float:right; width:130px">
                                                                    <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" '.$temp1.' name = "fanswer28" value = "Yes">Yes</label>
                                                                    <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" '.$temp2.' name = "fanswer28" value = "No">No</label>
                                                                </div>
                                                            </div>
                                                            <div class = "extended-answer"  id = "ext-answ-q28" style = "float:left;width:100%;margin-top:5px;display:none">
                                                                Extended answer:
                                                                <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q28" value = "'.$ext_answ.'">
                                                            </div>
                                                        </div>';
                                                };
                                                ?>
                                                <div class = "row" >
                                                    <h5 style="margin: 0px 0px 15px 0px !important;">Family History and Aviation/Avocation Questions</h5>
                                                    <div class = "question">
                                                        <div class = "q8-table">
                                                            <div class = "q8-table-row q8-table-row-head">
                                                                <div class = "q8-table-cell">
                                                                    Family member
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    Living
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    Cause of death
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    Age of death
                                                                </div>
                                                            </div>
                                                            <div class = "q8-table-row">
                                                                <div class = "q8-table-cell">
                                                                    Mother
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fmLiving == "Yes" or !isset($_SESSION -> fmLiving)){echo "checked";}?> name = "fmLiving" value = "Yes">Yes</label>
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fmLiving == "No"){echo "checked";}?> name = "fmLiving" value = "No">No</label>
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fmcdeath" value = "<? if ($_SESSION -> fmcdeath != ""){echo $_SESSION -> fmcdeath;} else {echo "";} ?>">
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fmadeath" value = "<? if ($_SESSION -> fmadeath != ""){echo $_SESSION -> fmadeath;} else {echo "";} ?>">
                                                                </div>
                                                            </div>
                                                            <div class = "q8-table-row">
                                                                <div class = "q8-table-cell">
                                                                    Father
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> ffLiving == "Yes" or !isset($_SESSION -> ffLiving)){echo "checked";}?> name = "ffLiving" value = "Yes">Yes</label>
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> ffLiving == "No"){echo "checked";}?> name = "ffLiving" value = "No">No</label>
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "ffcdeath" value = "<? if ($_SESSION -> ffcdeath != ""){echo $_SESSION -> ffcdeath;} else {echo "";} ?>">
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "ffadeath" value = "<? if ($_SESSION -> ffadeath != ""){echo $_SESSION -> ffadeath;} else {echo "";} ?>">
                                                                </div>
                                                            </div>
                                                            <div class = "q8-table-row">
                                                                <div class = "q8-table-cell">
                                                                    Sister(s)
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fsLiving == "Yes" or !isset($_SESSION -> fsLiving)){echo "checked";}?> name = "fsLiving" value = "Yes">Yes</label>
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fsLiving == "No"){echo "checked";}?> name = "fsLiving" value = "No">No</label>
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fscdeath" value = "<? if ($_SESSION -> fscdeath != ""){echo $_SESSION -> fscdeath;} else {echo "";} ?>">
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fsadeath" value = "<? if ($_SESSION -> fsadeath != ""){echo $_SESSION -> fsadeath;} else {echo "";} ?>">
                                                                </div>
                                                            </div>
                                                            <div class = "q8-table-row">
                                                                <div class = "q8-table-cell" >
                                                                    Brother(s)
                                                                </div>
                                                                <div class = "q8-table-cell">
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fbLiving == "Yes" or !isset($_SESSION -> fbLiving)){echo "checked";}?> name = "fbLiving" value = "Yes">Yes</label>
                                                                    <label class = "chk" style = "margin:0"><input style = "margin:3px 5px 0px 5px" type="radio" <? if ($_SESSION -> fbLiving == "No"){echo "checked";}?> name = "fbLiving" value = "No">No</label>
                                                                </div>
                                                                <div class = "q8-table-cell" style = "width:193px;border-bottom:2px solid #444;">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fbcdeath" value = "<? if ($_SESSION -> fbcdeath != ""){echo $_SESSION -> fbcdeath;} else {echo "";} ?>">
                                                                </div>
                                                                <div class = "q8-table-cell" style = "width:194px;border-right:2px solid #444;border-bottom:2px solid #444;">
                                                                    <input style = "height:22px;padding: 0px 0px 0px 5px;" type = "text" name = "fbadeath" value = "<? if ($_SESSION -> fbadeath != ""){echo $_SESSION -> fbadeath;} else {echo "";} ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row divider">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><strong>3</strong> In the past 24 months have you participated in Parachuting, Ballooning, Hang Gliding, Motorized Racing, Rock Climbing, Mountaineering, Rodeo, or Scuba Diving?</div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer25 == "Yes"){echo "checked";}?> name = "fanswer25" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer25 == "No" or !isset($_SESSION -> fanswer25)){echo "checked";}?> name = "fanswer25" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q25" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q25" value = "<? if ($_SESSION -> fext_answ_q25 != ""){echo $_SESSION -> fext_answ_q25;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><strong>4</strong> In the past 24 months have you flown, or in the next 24 months do you intend to fly as a pilot, student pilot, or crew member on any aircraft, (other than scheduled commercial flights)?</div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer26 == "Yes"){echo "checked";}?> name = "fanswer26" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer26 == "No" or !isset($_SESSION -> fanswer26)){echo "checked";}?> name = "fanswer26" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q26" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q26" value = "<? if ($_SESSION -> fext_answ_q26 != ""){echo $_SESSION -> fext_answ_q26;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><strong>5</strong> Are you a US citizen?</div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer27 == "Yes" or !isset($_SESSION -> fanswer27)){echo "checked";}?> name = "fanswer27" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer27 == "No"){echo "checked";}?> name = "fanswer27" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                </div>
											</div>

											<input type = "button" id = "frmsbmt" name = "sendreq" value = "Submit" class = "button">
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
		<div class="container"><p class="footer-text">&copy; NoExam.com 2014, All Rights Reserved.</p></div>
	</div>
	<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
	<script src="//static.getclicky.com/js" type="text/javascript"></script>
	<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
	<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
<?php //get_footer(); ?>