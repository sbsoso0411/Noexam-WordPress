<?php
/**
 * Template Name: additional-questions
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

include("header.php");
// Helpers
require("inc/helpers.php");
// Storage
$_POST['page'] = 4;
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
                                                if ($("input[name=fanswer11]:checked").val() == "Yes"){
                                                    $("#ext-answ-q11").show();
                                                };
                                                $("input[name=fanswer11]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q11").show();
                                                    } else {
                                                        $("#ext-answ-q11").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer12]:checked").val() == "Yes"){
                                                    $("#ext-answ-q12").show();
                                                };
                                                $("input[name=fanswer12]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q12").show();
                                                    } else {
                                                        $("#ext-answ-q12").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer13]:checked").val() == "Yes"){
                                                    $("#ext-answ-q13").show();
                                                };
                                                $("input[name=fanswer13]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q13").show();
                                                    } else {
                                                        $("#ext-answ-q13").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer14]:checked").val() == "Yes"){
                                                    $("#ext-answ-q14").show();
                                                };
                                                $("input[name=fanswer14]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q14").show();
                                                    } else {
                                                        $("#ext-answ-q14").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer15]:checked").val() == "Yes"){
                                                    $("#ext-answ-q15").show();
                                                };
                                                $("input[name=fanswer15]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q15").show();
                                                    } else {
                                                        $("#ext-answ-q15").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer16]:checked").val() == "Yes"){
                                                    $("#ext-answ-q16").show();
                                                };
                                                $("input[name=fanswer16]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q16").show();
                                                    } else {
                                                        $("#ext-answ-q16").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer17]:checked").val() == "Yes"){
                                                    $("#ext-answ-q17").show();
                                                };
                                                $("input[name=fanswer17]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q17").show();
                                                    } else {
                                                        $("#ext-answ-q17").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer18]:checked").val() == "Yes"){
                                                    $("#ext-answ-q18").show();
                                                };
                                                $("input[name=fanswer18]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q18").show();
                                                    } else {
                                                        $("#ext-answ-q18").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer19]:checked").val() == "Yes"){
                                                    $("#ext-answ-q19").show();
                                                };
                                                $("input[name=fanswer19]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q19").show();
                                                    } else {
                                                        $("#ext-answ-q19").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer20]:checked").val() == "Yes"){
                                                    $("#ext-answ-q20").show();
                                                };
                                                $("input[name=fanswer20]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q20").show();
                                                    } else {
                                                        $("#ext-answ-q20").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer21]:checked").val() == "Yes"){
                                                    $("#ext-answ-q21").show();
                                                };
                                                $("input[name=fanswer21]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q21").show();
                                                    } else {
                                                        $("#ext-answ-q21").hide();
                                                    }
                                                });

                                                if ($("input[name=fanswer22]:checked").val() == "Yes"){
                                                    $("#ext-answ-q22").show();
                                                };
                                                $("input[name=fanswer22]:radio").change(function(){
                                                    if ($(this).val() == "Yes"){
                                                        $("#ext-answ-q22").show();
                                                    } else {
                                                        $("#ext-answ-q22").hide();
                                                    }
                                                });

                                                var tempStrBack = $("#fext_txt_q11").val();
                                                $("#text_area_q11").val(tempStrBack.replace(",","\r\n"));

                                                $("#frmsbmt").click(function(){
                                                    var tempStr = $("#text_area_q11").val();
                                                    $("#fext_txt_q11").val(tempStr.replace(/\n/g,","));
                                                    $("#additional-medical-frm").submit();
                                                })
                                            });
                                        </script>

                                        <div class="form_head-title">Please answer the following questions:</div>

                                        <form action = "/post-page/" id = "additional-medical-frm" method = "post" class = "form-inputs new">
                                                        <div class="form-inputs-inner">
                                                            <input type = "hidden" name = "pageID" value = "4">
                                                            <div class="row">
                                                                <div class = "question">
                                                                    <div style = "width:650px;float:left;"><div class="icon-ara"><strong>1</strong></div> <div class="text-con"> Are you currently disabled and/or receiving disability benefits?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer11 == "Yes"){echo "checked";}?> name = "fanswer11" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer11 == "No" or !isset($_SESSION -> fanswer11)){echo "checked";}?> name = "fanswer11" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q11" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        <label>Please list any prescription medication used and the date of diagnosis.</label>
                                                        <div class = "row" style = "margin: 0px;">
                                                            <div class = "additional-text-area">
                                                                <label>Prescription medication</label>
                                                                <textarea class = "noex-text-area" style = "margin:7px 0px 0px 0px" type="textarea" id = "text_area_q11" ></textarea>
                                                                <input type = "hidden" id = "fext_txt_q11" name = "fext_txt_q11" value = "<? if ($_SESSION -> fext_txt_q11 != ""){echo $_SESSION -> fext_txt_q11;} else {echo "";} ?>"/>
                                                            </div>
                                                            <div class = "additional-text">
                                                                <label>Date of diagnosis</label>
                                                                <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q11" value = "<? if ($_SESSION -> fext_answ_q11 != ""){echo $_SESSION -> fext_answ_q11;} else {echo "";} ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row shaded">
                                                    <div style = "float:left;"><strong>2</strong> In the past 10 years, have you consulted or been given medical advice by a member of the medical profession for:</div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>2.1</strong></div> <div class="text-con"> Cancer (other than Basal Cell or Squamous Cell skin cancer), Malignant Tumor, Lymphoma or Leukemia?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer12 == "Yes"){echo "checked";}?> name = "fanswer12" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer12 == "No" or !isset($_SESSION -> fanswer12)){echo "checked";}?> name = "fanswer12" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q12" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q12" value = "<? if ($_SESSION -> fext_answ_q12 != ""){echo $_SESSION -> fext_answ_q12;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>2.2</strong></div> <div class="text-con"> Heart Disease including Coronary Artery Disease, Heart Attack, Heart Failure and Irregular Heartbeat, or Vascular Disease involving the Arteries?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer13 == "Yes"){echo "checked";}?> name = "fanswer13" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer13 == "No" or !isset($_SESSION -> fanswer13)){echo "checked";}?> name = "fanswer13" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q13" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q13" value = "<? if ($_SESSION -> fext_answ_q13 != ""){echo $_SESSION -> fext_answ_q13;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>2.3</strong></div> <div class="text-con"> Stroke, Transient Ischemic Attack (TIA)?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer14 == "Yes"){echo "checked";}?> name = "fanswer14" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer14 == "No" or !isset($_SESSION -> fanswer14)){echo "checked";}?> name = "fanswer14" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q14" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q14" value = "<? if ($_SESSION -> fext_answ_q14 != ""){echo $_SESSION -> fext_answ_q14;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row shaded">
                                                    <div style = "float:left;"><strong>3</strong> In the past 5 years, have you consulted or been given medical advice by a member of the medical profession for:</div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.1</strong></div> <div class="text-con"> Parkinson's Disease, Cerebral Palsy, Seizures, Paralysis, Multiple Sclerosis, or any Loss of Memory or Mental Capacity?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer15 == "Yes"){echo "checked";}?> name = "fanswer15" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer15 == "No" or !isset($_SESSION -> fanswer15)){echo "checked";}?> name = "fanswer15" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q15" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q15" value = "<? if ($_SESSION -> fext_answ_q15 != ""){echo $_SESSION -> fext_answ_q15;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.2</strong></div> <div class="text-con"> Kidney Disease?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer16 == "Yes"){echo "checked";}?> name = "fanswer16" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer16 == "No" or !isset($_SESSION -> fanswer16)){echo "checked";}?> name = "fanswer16" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q16" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q16" value = "<? if ($_SESSION -> fext_answ_q16 != ""){echo $_SESSION -> fext_answ_q16;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.3</strong></div> <div class="text-con"> Any Lung or Breathing Disorder including Asthma, Chronic Obstructive Pulmonary Disease (COPD), Chronic Bronchitis, Emphysema, and Sleep Apnea?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer17 == "Yes"){echo "checked";}?> name = "fanswer17" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer17 == "No" or !isset($_SESSION -> fanswer17)){echo "checked";}?> name = "fanswer17" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q17" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q17" value = "<? if ($_SESSION -> fext_answ_q17 != ""){echo $_SESSION -> fext_answ_q17;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.4</strong></div> <div class="text-con"> Depression, Bipolar Disorder, Anxiety or any other Psychiatric Disorder?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer18 == "Yes"){echo "checked";}?> name = "fanswer18" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer18 == "No" or !isset($_SESSION -> fanswer18)){echo "checked";}?> name = "fanswer18" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q18" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q18" value = "<? if ($_SESSION -> fext_answ_q18 != ""){echo $_SESSION -> fext_answ_q18;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.5</strong></div> <div class="text-con"> Rheumatoid Arthritis (not Osteoarthritis), Systemic Lupus (SLE), Progressive Systemic Sclerosis (PSS or Scleroderma), or Polymyositis?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer19 == "Yes"){echo "checked";}?> name = "fanswer19" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer19 == "No" or !isset($_SESSION -> fanswer19)){echo "checked";}?> name = "fanswer19" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q19" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q19" value = "<? if ($_SESSION -> fext_answ_q19 != ""){echo $_SESSION -> fext_answ_q19;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.6</strong></div> <div class="text-con"> Hepatitis or other Liver Disorder, Crohn's Disease, Ulcerative Colitis, or a Disorder of the Pancreas?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer20 == "Yes"){echo "checked";}?> name = "fanswer20" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer20 == "No" or !isset($_SESSION -> fanswer20)){echo "checked";}?> name = "fanswer20" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q20" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q20" value = "<? if ($_SESSION -> fext_answ_q20 != ""){echo $_SESSION -> fext_answ_q20;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.7</strong></div> <div class="text-con"> High Blood Pressure (Hypertension)?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer21 == "Yes"){echo "checked";}?> name = "fanswer21" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer21 == "No" or !isset($_SESSION -> fanswer21)){echo "checked";}?> name = "fanswer21" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q21" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q21" value = "<? if ($_SESSION -> fext_answ_q21 != ""){echo $_SESSION -> fext_answ_q21;} else {echo "";} ?>">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class = "question">
                                                        <div style = "width:650px;float:left;"><div class="icon-ara"><strong>3.8</strong></div> <div class="text-con"> Diabetes, Immune System Disorder (other than related to HIV infection) or Blood Disorder?</div></div>
                                                        <div style = "float:right; width:130px">
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 10px" type="radio" <? if ($_SESSION -> fanswer22 == "Yes"){echo "checked";}?> name = "fanswer22" value = "Yes">Yes</label>
                                                            <label class = "chk" style = "margin:0px"><input style = "margin:3px 3px 0px 15px" type="radio" <? if ($_SESSION -> fanswer22 == "No" or !isset($_SESSION -> fanswer22)){echo "checked";}?> name = "fanswer22" value = "No">No</label>
                                                        </div>
                                                    </div>
                                                    <div class = "extended-answer" ID = "ext-answ-q22" style = "width:100%;float:left;margin-top: 5px;display:none">
                                                        Extended answer:
                                                        <input style = "margin:7px 0px 0px 0px" type="text" name = "fext_answ_q22" value = "<? if ($_SESSION -> fext_answ_q22 != ""){echo $_SESSION -> fext_answ_q22;} else {echo "";} ?>">
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