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
include("header.php");
// helpers
require("inc/helpers.php");
// storage
require("inc/storage.php");


$BankAccount = $_SESSION['fBankAccount'];
$RoutingNumber = $_SESSION['fRoutingNumber'];
$BankName = $_SESSION['fBankName'];
$monthly = $_SESSION['fpremium'];
$SocialSecurityNumber = $_SESSION['fSocialSecurityNumber'];


?>

    <script type="text/javascript">


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
            $("#request-button").click(function(){
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
            <div class="row">
                <div class="five columns">
                    <div class="entry-header">
                        <h1 class="entry-title">Payment</h1>
                    </div><!-- .entry-header -->
                </div>
                <div class="eleven columns progresslist">
                    <ul>
                        <li class="selected"><span>1</span><a href="#">Quote Results</a><div class="arrw"></div></li>
                        <li class="selected"><span>2</span><a href="#">Medical History</a><div class="arrw"></div></li>
                        <li class="selected"><span>3</span><a href="#">General Info</a><div class="arrw"></div></li>
                        <li class="selected"><span>4</span><a href="#">Payment</a><div class="arrw"></div></li>
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
                                        <div>

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



                                        <div class="clearfix" style=" clear:both; width:100%; display:block;"></div>
                                        <div class="leftside-form">
                                            <FORM ACTION="/post-page/" METHOD=POST  class=" form-inputs-request">
                                                <input type = "hidden" name = "pageID" value = "7">
                                                <div class = "row">


                                                    <div class="div-50 divcontent no-pad">
                                                        <div class="div-100 divcontent">
                                                            <label><span style="font-size:16px; font-weight:bold; color:#5e8fa1">Payment Method</span></label>
                                                            <p>EFT bank draft<br />(Checking account)</p>
                                                        </div>
                                                        <div class="div-100 divcontent payment-frequency">
                                                            <label><span style="font-size:16px; font-weight:bold; color:#5e8fa1">Payment Frequency</span></label>
                                                            <div>
                                                                <label class = "chk"><input type="radio" name = "fMonthlyCheck" value = "0" <? if($_SESSION['fMonthlyCheck'] == "0" or $_SESSION['fMonthlyCheck'] == "") { echo "checked";}?>>Monthly(<?php echo $monthly?>)</label>
                                                                <label class = "chk"><input type="radio" name = "fMonthlyCheck" value = "1" <? if($_SESSION['fMonthlyCheck'] == "1") { echo "checked";}?>>Annual(<?php echo $_SESSION['fannualpremium'];?>)<label>save 8%!</label></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="div-50 divcontent no-pad">
                                                        <div class="div-100 divcontent">
                                                            <label>Bank Name</label>
                                                            <INPUT TYPE=TEXT NAME="fBankName" id = "fBankName"  SIZE=25 value = "<? if ($BankName != "" ) {echo $BankName;}?>">
                                                        </div>
                                                        <div class="div-100 divcontent">
                                                            <label>Account Number</label>
                                                            <INPUT TYPE=TEXT NAME="fBankAccount" id = "fBankAccount"  SIZE=25 value = "<? if ($BankAccount != "" ) {echo $BankAccount;}?>">
                                                        </div>
                                                        <div class="div-100 divcontent">
                                                            <label>Routing Number</label>
                                                            <INPUT TYPE=TEXT NAME="fRoutingNumber" id = "fRoutingNumber"  SIZE=25 value = "<? if ($RoutingNumber != "" ) {echo $RoutingNumber;}?>">
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class = "row" id = "main-error-field" style = "margin: 5px 0px 0px 5px;padding:0px;display:none;color: #FF726D;">

                                                </div>

                                                <div class=" btncon form-inputs">
                                                    <input type="button" id="request-button" class="button" value="SUBMIT" name="sendreq">
                                                </div>
                                            </FORM>
                                        </div>

                                        <div class="rightside-form">

                                            <p><span style="font-size:16px; font-weight:bold; color:#5e8fa1">Note:</span> The submission of this form does not bind your life insurance policy. Policy approvals occur within 24-48 hours of the initial application submission to the insurance company. Upon the time of your policy approval you will be notified by e-mail. If approved, please expect 3-5 business days to receive a hard copy of your policy in the mail.</p>

                                            <div class="count-con">
                                                <div class="lineone">
                                                    <div class="newline"> <span>$</span></div>
                                                    <div class="inputnew"><input name="" type="text" readonly/></div>
                                                </div>
                                                <div class="linetwo">

                                                </div>

                                                <div class="numbercon">
                                                    <div class="number-one">
                                                        <p>22222222</p>
                                                        <span>Routing Number</span>
                                                    </div>
                                                    <div class="number-two">
                                                        <p>000 111 555</p>
                                                        <span>Account Number</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix" style=" clear:both; width:100%; display:block; margin-bottom:35px"></div>
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