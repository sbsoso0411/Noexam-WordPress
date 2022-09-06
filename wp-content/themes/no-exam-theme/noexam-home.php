<?php /* Template Name: Home Page */ ?>

<?php
/**
 * The main template file.
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */

get_header();

// Storager
require("inc/storage.php");
?>

<!-- Primary Page Layout
================================================== -->

<div id="plans">

    <div class="container">
        <form action="/post-page/" method="post" id="home-form">
            <input type="hidden" id="gclid_field" name="fgclid_field" value="">
            <input type="hidden" id="bing_field" name="fbing_field" value="">
            <input type = "hidden" name = "pageID" value = "0">
            <INPUT TYPE=HIDDEN id = "fModeUsed" NAME="fModeUsed" VALUE="M">
            <input id = "already-sended" type = "hidden" name = "fsendedToDb" value = "<?if ($_SESSION['fsendedToDb'] != "") {echo $_SESSION['fsendedToDb'];}?>" />

            <h1>Life Insurance Without the Exam</h1>
            <h2>Get covered in minutes</h2>
            <div class="bannercontent">
                <div class="bannercontenttextlt">
                    <h3 class="green-txt">Term <br><span>Life Insurance</span></h3>
                    <img src="<?php bloginfo('template_directory'); ?>/images/plan-icon-green.png" width="35px">
                    <h4>Up to :<span> $500,000</span></h4>
                    <p>Age Groups: <span>20 - 65</span><br>
                        Health: <span> Medium to great</span></p>

                    <p>Works for:<br>
                        <span>Family Income Protection</span></p>

                </div>
                <div class="quotecontent">
                    <div class="quoutrow">
                        <label class="d-line">COVERAGE AMOUNT</label>
                        <select id = "fFaceAmount" name="fFaceAmount">
                            <option value="100000" 	<?php if ($_SESSION['fFaceAmount'] == 100000)										{ echo "selected";}?>>$100,000</option>
                            <option value="150000" 	<?php if ($_SESSION['fFaceAmount'] == 150000)										{ echo "selected";}?>>$150,000</option>
                            <option value="200000" 	<?php if ($_SESSION['fFaceAmount'] == 200000)										{ echo "selected";}?>>$200,000</option>
                            <option value="250000" 	<?php if ($_SESSION['fFaceAmount'] == 250000 or !isset($_SESSION['FaceAmount']))	{ echo "selected";}?>>$250,000</option>
                            <option value="300000" 	<?php if ($_SESSION['fFaceAmount'] == 300000)										{ echo "selected";}?>>$300,000</option>
                            <option value="400000" 	<?php if ($_SESSION['fFaceAmount'] == 400000)										{ echo "selected";}?>>$400,000</option>
                            <option value="500000" 	<?php if ($_SESSION['fFaceAmount'] == 500000)										{ echo "selected";}?>>$500,000</option>
                        </select>
                    </div>

                    <?php
                    $month_names= array(
                        1 => 'January',
                        2 => 'February',
                        3 => 'March',
                        4 => 'April',
                        5 => 'May',
                        6 => 'June',
                        7 => 'July',
                        8 => 'August',
                        9 => 'September',
                        10 => 'October',
                        11 => 'November',
                        12 => 'December',
                    );
                    if (!isset($_SESSION['fBirthMonth'])) {
                        $_SESSION['fBirthMonth']  = 1;
                    }

                    if (!isset($_SESSION['fBirthday'])) {
                        $_SESSION['fBirthday'] = 1;
                    }


                    if (!isset($_SESSION['fBirthYear'])) {
                        $_SESSION['fBirthYear'] = 1970;
                    }

                    ?>

                    <div class="quoutrow">
                        <label>BIRTH DATE</label>
                        <select name="fBirthMonth" class="month" id = "fBirthMonth">
                            <?php
                            for ($i=1; $i<=12; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>" 	<?php if ($_SESSION['fBirthMonth'] == $i ) 	{echo "selected";}?>><?php echo $month_names[$i]; ?></option>
                            <?php
                            }
                            ?>
                        </select>

                        <select name="fBirthday" class="date" id = "fBirthday">
                            <?php
                            for ($i=1; $i<=31; $i++) {
                                ?>
                                <option value="<?php echo $i; ?>" 	<?php if ($_SESSION['fBirthday'] == $i ) 	{echo "selected";}?>><?php echo $i; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <select name="fBirthYear" class="year" id = "fBirthYear">
                            <?php
                            for ($i=1940; $i<=1990; $i++) {
                            ?>
                                <option value="<?php echo $i; ?>" 	<?php if ($_SESSION['fBirthYear'] == $i ) 	{echo "selected";}?>><?php echo $i; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="quoutrow">
                        <div class="quoutrowlt" style="width: 58%;">
                            <label>HEIGHT</label>
                            <select class="Height_ft" name="fHeight_ft" id="fHeight_ft" style="float: left;width: auto;">
                                <option value = "4" <?php if ($_SESSION['fHeight_ft'] == 4) 										{echo "selected";}?>>4</option>
                                <option value = "5" <?php if ($_SESSION['fHeight_ft'] == 5 or !isset($_SESSION['fHeight_ft']))  	{echo "selected";}?>>5</option>
                                <option value = "6" <?php if ($_SESSION['fHeight_ft'] == 6) 										{echo "selected";}?>>6</option>
                            </select>
                            <span style="font-size: small;padding-left: 2px;padding-right: 2px;">ft</span>
                            <select class="Height_in" name="fHeight_in" id="fHeight_in" style="float: left;width: auto;">
                                <option value = "0"	<?php if ($_SESSION['fHeight_in'] == 0)  										{echo "selected";}?>>0</option>
                                <option value = "1" <?php if ($_SESSION['fHeight_in'] == 1)  										{echo "selected";}?>>1</option>
                                <option value = "2" <?php if ($_SESSION['fHeight_in'] == 2)  										{echo "selected";}?>>2</option>
                                <option value = "3" <?php if ($_SESSION['fHeight_in'] == 3)  										{echo "selected";}?>>3</option>
                                <option value = "4" <?php if ($_SESSION['fHeight_in'] == 4)  										{echo "selected";}?>>4</option>
                                <option value = "5" <?php if ($_SESSION['fHeight_in'] == 5)  										{echo "selected";}?>>5</option>
                                <option value = "6" <?php if ($_SESSION['fHeight_in'] == 6)  										{echo "selected";}?>>6</option>
                                <option value = "7" <?php if ($_SESSION['fHeight_in'] == 7)  										{echo "selected";}?>>7</option>
                                <option value = "8" <?php if ($_SESSION['fHeight_in'] == 8)  										{echo "selected";}?>>8</option>
                                <option value = "9" <?php if ($_SESSION['fHeight_in'] == 9)  										{echo "selected";}?>>9</option>
                                <option value = "10" <?php if ($_SESSION['fHeight_in'] == 10 or !isset($_SESSION['fHeight_in']))  	{echo "selected";}?>>10</option>
                                <option value = "11" <?php if ($_SESSION['fHeight_in'] == 11)  										{echo "selected";}?>>11</option>
                            </select>
                            <span style="font-size: small;padding-left: 2px;padding-right: 2px;">in</span>
                        </div>
                        <div class="quoutrowrt" style="width: 42%;">
                            <label>WEIGHT</label>
                            <input name="fweight" type="text" id="weight" size="3" value="165" min="1" max="500">
                            <span style="font-size: small;padding-left: 2px;">lbs</span>
                        </div>
                    </div>

                    <div class="row" style = "display:none;">
                        <div class="label"><label>Health class</label></div>
                        <div class="inputs">
                            <div class = "Health_class" ID = "HC"></div>
                            <div class = "HealthHidden">
                                <input type="hidden" class="lbl" name="fHealthClass" id = "fHealthClass" /> <!--value set by ratecalc-->
                            </div>
                        </div>
                    </div>

                    <div class="quoutrow">
                        <label>STATE</label>
                        <select name="fState" id = "fState">
                            <OPTION VALUE="AL" <?php if ($_SESSION['fState'] == "AL" or !isset($_SESSION['State'])) 	{echo "selected";}?>>Alabama</OPTION>
                            <OPTION VALUE="AK" <?php if ($_SESSION['fState'] == "AK") 	                            {echo "selected";}?>>Alaska</OPTION>
                            <OPTION VALUE="AZ" <?php if ($_SESSION['fState'] == "AZ") 								{echo "selected";}?>>Arizona</OPTION>
                            <OPTION VALUE="AR" <?php if ($_SESSION['fState'] == "AR") 								{echo "selected";}?>>Arkansas</OPTION>
                            <OPTION VALUE="CA" <?php if ($_SESSION['fState'] == "CA") 								{echo "selected";}?>>California</OPTION>
                            <OPTION VALUE="CO" <?php if ($_SESSION['fState'] == "CO") 								{echo "selected";}?>>Colorado</OPTION>
                            <OPTION VALUE="CT" <?php if ($_SESSION['fState'] == "CT") 								{echo "selected";}?>>Connecticut</OPTION>
                            <OPTION VALUE="DE" <?php if ($_SESSION['fState'] == "DE") 								{echo "selected";}?>>Delaware</OPTION>
                            <OPTION VALUE="DC" <?php if ($_SESSION['fState'] == "DC") 								{echo "selected";}?>>Dist. Columbia</OPTION>
                            <OPTION VALUE="FL" <?php if ($_SESSION['fState'] == "FL") 								{echo "selected";}?>>Florida</OPTION>
                            <OPTION VALUE="GA" <?php if ($_SESSION['fState'] == "GA") 								{echo "selected";}?>>Georgia</OPTION>
                            <OPTION VALUE="ID" <?php if ($_SESSION['fState'] == "ID") 								{echo "selected";}?>>Idaho</OPTION>
                            <OPTION VALUE="IL" <?php if ($_SESSION['fState'] == "IL") 								{echo "selected";}?>>Illinois</OPTION>
                            <OPTION VALUE="IN" <?php if ($_SESSION['fState'] == "IN") 								{echo "selected";}?>>Indiana</OPTION>
                            <OPTION VALUE="IA" <?php if ($_SESSION['fState'] == "IA") 								{echo "selected";}?>>Iowa</OPTION>
                            <OPTION VALUE="KS" <?php if ($_SESSION['fState'] == "KS") 								{echo "selected";}?>>Kansas</OPTION>
                            <OPTION VALUE="KY" <?php if ($_SESSION['fState'] == "KY") 								{echo "selected";}?>>Kentucky</OPTION>
                            <OPTION VALUE="LA" <?php if ($_SESSION['fState'] == "LA") 								{echo "selected";}?>>Louisiana</OPTION>
                            <OPTION VALUE="ME" <?php if ($_SESSION['fState'] == "ME") 								{echo "selected";}?>>Maine</OPTION>
                            <OPTION VALUE="MD" <?php if ($_SESSION['fState'] == "MD") 								{echo "selected";}?>>Maryland</OPTION>
                            <OPTION VALUE="MI" <?php if ($_SESSION['fState'] == "MI") 								{echo "selected";}?>>Michigan</OPTION>
                            <OPTION VALUE="MN" <?php if ($_SESSION['fState'] == "MN") 								{echo "selected";}?>>Minnesota</OPTION>
                            <OPTION VALUE="MS" <?php if ($_SESSION['fState'] == "MS") 								{echo "selected";}?>>Mississippi</OPTION>
                            <OPTION VALUE="MO" <?php if ($_SESSION['fState'] == "MO") 								{echo "selected";}?>>Missouri</OPTION>
                            <OPTION VALUE="MT" <?php if ($_SESSION['fState'] == "MT") 								{echo "selected";}?>>Montana</OPTION>
                            <OPTION VALUE="NE" <?php if ($_SESSION['fState'] == "NE") 								{echo "selected";}?>>Nebraska</OPTION>
                            <OPTION VALUE="NV" <?php if ($_SESSION['fState'] == "NV") 								{echo "selected";}?>>Nevada</OPTION>
                            <OPTION VALUE="NJ" <?php if ($_SESSION['fState'] == "NJ") 								{echo "selected";}?>>New Jersey</OPTION>
                            <OPTION VALUE="NY" <?php if ($_SESSION['fState'] == "NY") 								{echo "selected";}?>>New York</OPTION>
                            <OPTION VALUE="NC" <?php if ($_SESSION['fState'] == "NC") 								{echo "selected";}?>>North Carolina</OPTION>
                            <OPTION VALUE="ND" <?php if ($_SESSION['fState'] == "ND") 								{echo "selected";}?>>North Dakota</OPTION>
                            <OPTION VALUE="OH" <?php if ($_SESSION['fState'] == "OH") 								{echo "selected";}?>>Ohio</OPTION>
                            <OPTION VALUE="OK" <?php if ($_SESSION['fState'] == "OK") 								{echo "selected";}?>>Oklahoma</OPTION>
                            <OPTION VALUE="OR" <?php if ($_SESSION['fState'] == "OR") 								{echo "selected";}?>>Oregon</OPTION>
                            <OPTION VALUE="PA" <?php if ($_SESSION['fState'] == "PA") 								{echo "selected";}?>>Pennsylvania</OPTION>
                            <OPTION VALUE="RI" <?php if ($_SESSION['fState'] == "RI") 								{echo "selected";}?>>Rhode Island</OPTION>
                            <OPTION VALUE="SC" <?php if ($_SESSION['fState'] == "SC") 								{echo "selected";}?>>South Carolina</OPTION>
                            <OPTION VALUE="SD" <?php if ($_SESSION['fState'] == "SD") 								{echo "selected";}?>>South Dakota</OPTION>
                            <OPTION VALUE="TN" <?php if ($_SESSION['fState'] == "TN") 								{echo "selected";}?>>Tennessee</OPTION>
                            <OPTION VALUE="TX" <?php if ($_SESSION['fState'] == "TX") 								{echo "selected";}?>>Texas</OPTION>
                            <OPTION VALUE="UT" <?php if ($_SESSION['fState'] == "UT") 								{echo "selected";}?>>Utah</OPTION>
                            <OPTION VALUE="VT" <?php if ($_SESSION['fState'] == "VT") 								{echo "selected";}?>>Vermont</OPTION>
                            <OPTION VALUE="VA" <?php if ($_SESSION['fState'] == "VA") 								{echo "selected";}?>>Virginia</OPTION>

                            <OPTION VALUE="WV" <?php if ($_SESSION['fState'] == "WV") 								{echo "selected";}?>>West Virginia</OPTION>
                            <OPTION VALUE="WI" <?php if ($_SESSION['fState'] == "WI") 								{echo "selected";}?>>Wisconsin</OPTION>
                            <OPTION VALUE="WY" <?php if ($_SESSION['fState'] == "WY") 								{echo "selected";}?>>Wyoming</OPTION>
                        </select>
                    </div>

                    <div class="quoutrow">
                        <label>GENDER</label>
                        <select name="fSex">
                            <option value="M" <?php if ($_SESSION['fSex'] == "M") {echo "selected";}?> >Male</option>
                            <option value="F" <?php if ($_SESSION['fSex'] == "F") {echo "selected";}?> >Female</option>
                        </select>
                    </div>

                    <div class="quoutrow checkboxarea">
                        <p> USED TOBACCO IN<br />PAST 2 YEARS?</p>
                        <label>YES <input name="fSmoker" type="radio" value="Y" <?php if ($_SESSION['fSmoker'] == "Y"){echo "checked";}?> /></label>
                        <label>NO <input name="fSmoker" type="radio" value="N" <? if (($_SESSION['fSmoker'] == "N") or (!isset($_SESSION['fSmoker']))){echo "checked";}?>  /></label>
                    </div>

                    <buton class="button green-btn" ID="submit1" name="submit1" >GET A QUOTE</buton>
                </div>
            </div>

        </form>
    </div>
</div>

<section class="reviews-Area">
	<div class="container">
    	 <div class="sixteen columns">
        	 <h2>What our customers think</h2>
            </div>
         <div class="clear"></div>
         <div style="min-height: 100px; overflow: hidden;" class="shopperapproved_widget sa_rotate sa_horizontal sa_count4 sa_rounded sa_large sa_bgBlue sa_colorWhite sa_borderWhite sa_nodate"></div><script type="text/javascript">var sa_interval = 5000;function saLoadScript(src) { var js = window.document.createElement('script'); js.src = src; js.type = 'text/javascript'; document.getElementsByTagName("head")[0].appendChild(js); } if (typeof(shopper_first) == 'undefined') saLoadScript('//www.shopperapproved.com/widgets/testimonial/3.0/12271.js'); shopper_first = true; </script>
    </div>
</section>

<section class="pointsVid">
	<div class="container">
    	 <div class="sixteen columns">
       	   <h2>About No Exam Life Insurance</h2>
         	<div class="points">
            	<ul>
                	<li>
                    	<img src="<?php bloginfo('template_directory'); ?>/images/icon-1.png" width="38" height="38" />
                    	<h1>No Medical Exam</h1>
                        <p>The fast and simple questionnaire will show you coverage options in just a few minutes. No medical exam or waiting.</p>
                    </li>
                    <li>
                    	<img src="<?php bloginfo('template_directory'); ?>/images/icon-2.png" width="38" height="38" />
                    	<h1>Easy to Qualify</h1>
                        <p>Answer our short medical health application to see if you qualify for immediate coverage.</p>
                    </li>
                    <li>
                    	<img src="<?php bloginfo('template_directory'); ?>/images/icon-3.png" width="38" height="38" />
                    	<h1>Low Monthly Payments</h1>
                        <p>Fixed monthly rates that will never change during the level term period of your policy.</p>
                    </li>
                </ul>
            </div>
            <div class="video">
            	 <script src="//fast.wistia.com/embed/medias/b2qixvddpl.jsonp" async></script><script src="//fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_b2qixvddpl videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>
            </div>
         </div>
   </div>
</section>

<section class="blogcon">
	<div class="alt-wrapper-seactin">
      <div class="alt-wrapper wrapperevent">
        <div class="container section serif-paras" id="security">
        <div class="one-third column">
        <div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/images/safe-icon.png" width="250" height="250">
        </div>
        </div>
        <div class="two-thirds column">
        <h3>Secure Online Application</h3>
            <ol>
            <li>Buy Life Insurance Online In Minutes</li>
            <li>Norton Symantec Protected Service</li>
            <li>Receive Your Policy Via Mail</li>
            </ol>
            <p>NoExam.com only uses your information to provide instant life insurance quotes. Select a quote and buy life insurance online in under 15 minutes. Our underwriting carriers consist of <a href="http://www3.ambest.com/lh/default.asp">A.M. BEST</a> life insurance companies with a proven track record of financial stability.</p>
        </div>
        </div>
      </div>  
        
        
        <div class="alt-wrapper wrapperodd">
        <div class="container section serif-paras">
        <div class="one-third column"><div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/images/calendar.png" width="250" height="250">
        </div>
        </div>
        <div class="two-thirds column">
        <h3>Choose Your Monthly Payments</h3>
        <p>Create your own monthly payment plan for your insurance coverage with our payment date options tool. With flexible payment options you can rest assured your policy premium payments are scheduled to your own convenience.</p>
        <p>Your premium payments do not change at any point during the duration of your coverage.</p>
        </div>
        
        </div>
        </div>
        
        
       <div class="alt-wrapper wrapperevent">  
        <div class="container section serif-paras">
        <div class="one-third column">
        <div class="large-icon"><img src="<?php bloginfo('template_directory'); ?>/images/money-back.png" width="250" height="250">
        </div>
        </div>
        <div class="two-thirds column">
        <h3>Money Back Guarantee</h3>
        <p>If you are not fully satisfied with your policy you may cancel your policy at any time within the first month and receive a full refund of your first months premium payment. A policy cancellation must be done within each applicants “free look” period.</p>
        </div>
        </div>
	   </div>
       </div>
    <div class="life-wrapper">
      <div class="container">
      <h3>Life Insurance Companies We Work With</h3>
        <div class="one-third column company">
        <div class="company-inner">
        <img src="https://lfgok25vr8k38ia9z17sg8ga-wpengine.netdna-ssl.com/wp-content/themes/no-exam-theme/images/national-life-group.png">
        <div class="description company-description">
        <p>National Life Group offers term life insurance with no medical exam available for purchase directly on our website. With thirty billion dollars in assets National Life Group is recognized by A.M. Best as an “A / Excellent” rated insurance provider with an outstanding financial outlook.</p>
        </div>
        </div>
        </div>
        <div class="one-third column company">
        <div class="company-inner"> <img src="<?php bloginfo('template_directory'); ?>/images/SBLI.png">
        <div class="description company-description">
        <p>Founded in 1907, SBLI is one of the longest standing and most reputable insurance providers in the market today. With over two billion in assets and an “A+ / Excellent” rating from A.M. Best, SBLI is a stable leader in the marketplace.</p>
        </div>
        </div>
        </div>
        <div class="one-third column company">
        <div class="company-inner"> <img src="<?php bloginfo('template_directory'); ?>/images/legal-and-general.png">
        <div class="description company-description">
        <p>Founded in 1836, Legal & General currently operates with over three hundred billion in assets. With an A.M. Best rating of “A+ / Excellent”, Legal and General remains one of the top financial institutions in the country.</p>
        </div>
        </div>
        </div>
        <div class="one-third column company">
        <div class="company-inner"> <img src="<?php bloginfo('template_directory'); ?>/images/met-life.png">
        <div class="description company-description">
        <p>Metlife began its operation in 1868. Currently, Metlife is recognized as one of the largest companies in the world boasting over seven hundred billion in assets. Metlife offers up to $250,000 of term life insurance without a physical exam. Their “A++ / Superior” A.M. Best rating puts them in a class of their own.</p>
        </div>
        </div>
        </div>
        <div class="one-third column company">
        <div class="company-inner"> <img src="<?php bloginfo('template_directory'); ?>/images/sagicor.png">
        <div class="description company-description">
            <p>Sagicor Life Insurance Company specializes in no medical life insurance coverage for individuals up to age 65. Established in 1840, Sagicor has nearly six billion in assets and remains a reputable national coverage provider.</p>
        </div>
        </div>
        </div>
        <div class="one-third column company">
        <div class="company-inner"> <img src="<?php bloginfo('template_directory'); ?>/images/mutual-and-omaha.png"><div class="description company-description"><p>Mutual Of Omaha has been helping individuals secure coverage through the independent insurance agent channel since 1909. Holding an “A+ / Excellent” rating with A.M Best, along with being a fortune 500 company, has secured Mutual Of Omaha as a top provider in the United States.</p>
        </div>
        </div>
        </div>
    </div></div>    
    <div class="container">
	  <h2>Most Popular Articles From the Blog</h2>
      
     <?php query_posts('cat=12'); while (have_posts()) : the_post(); ?>
     <div class="eight columns">
         	<div class="blog-post">
         	<?php the_post_thumbnail( );  ?>
            <h1><a href="<?php the_permalink() ?>"> <?php the_title( ); ?></a></h1>
             <?php the_excerpt(); ?>
            <a href="<?php the_permalink() ?>" class="more">Continue Reading</a>
         </div>
         </div>
     <?php endwhile; ?>
   </div>
 </section>


<?php get_footer(); ?>

<script type="text/javascript">

    function readCookie(name) {
        var n = name + "=";
        var cookie = document.cookie.split(';');
        for(var i=0;i < cookie.length;i++) {
            var c = cookie[i];
            while (c.charAt(0)==' '){c = c.substring(1,c.length);}
            if (c.indexOf(n) == 0){return c.substring(n.length,c.length);}
        }
        return null;
    }

    function readParam(){
        var search = window.location.search.substr(1),
            keys = {};
        search.split('&').forEach(function(item) {
            item = item.split('=');
            keys[item[0]] = item[1];
        });
        console.log(keys);
        return keys['utm_term'];
    }

	var site = $('#site');

    $(document).ready(function() {
        $('#content-slider').lightSlider({
            minSlide:1,
            maxSlide:1,
            keyPress:false,
            controls:true,
            onSliderLoad: function() {
                $('#content-slider').removeClass('cS-hidden');
            }
        })
        var clk = true;
        $('.btn-navbar').on('click',function(){
            if(site.hasClass('translate')){
                clk = false;
                site.removeClass('translate');
                setTimeout(function(){
                    $("#mast-head").css('display','none');
                    clk = true;
                },700);
            }else if(clk){
                $("#mast-head").css('display','block');
                site.addClass('translate');
            }
        });
        $('#site').on('touchmove', function(e) {
            if($(this).hasClass('translate')){
                e.preventDefault();
            }
        });
        $('#site > .nav-over').on('click touchstart',function(e){
            e.preventDefault();
            e.stopPropagation();
            clk = false;
            site.removeClass('translate');
            setTimeout(function(){
                $("#mast-head").css('display','none');
                clk = true;
            },700);
        })
        $(window).on("resize orientationchange", function(){
            if($(window).width() > 767){
                $("#mast-head").css('display','block');
                site.removeClass('translate');
            }else if(!site.hasClass('translate')){
                $("#mast-head").css('display','none');
            }
        });
    });

    $(document).ready(function(){

        $.ajax({
            url: "/send-obsolete-files/",
            type: 'POST',
            success:
                function () {

                }
        });

        readParam();

        $("#gclid_field").val(readCookie('gclid'));
        $("#bing_field").val(readParam());


        getHealthClass();
        $("#fHeight_ft").change(function(){
            getHealthClass();
        });
        $("#fHeight_in").change(function(){
            getHealthClass();
        });
        $("#weight").change(function(){
            getHealthClass();
        });
        $("#weight").keyup(function(){
            getHealthClass();
        });



        $("#submit1").click(function(){
            var rValidate = rateValidate();

            if (rValidate == 0){
                $("#home-form").submit();
            }
        });



        function rateValidate(){
            if ($(".row .Health_class").text() != "Please enter correct weight" && $(".row .Health_class").text() != "Please enter correct height"){
                return 0;
            } else {
                return 1;
            }
        }

        function getHealthClass(){
            $.ajax({
                url: "/ratecalc/",
                type: 'POST',
                data:{
                    ft : $("#fHeight_ft").val(),
                    inches : $("#fHeight_in").val(),
                    weight : $("#weight").val()
                },
                success:
                    function (data) {
                        if (JSON.parse(data) != "Please enter correct weight" && JSON.parse(data) != "Please enter correct height")
                        {
                            document.getElementById("HC").style.color = "#555C60";
                            $("#validation-error").html("");
                            $(".row .Health_class").text(JSON.parse(data));
                            $(".row .HealthHidden .lbl").val(JSON.parse(data));
                        }
                        else
                        {
                            document.getElementById("HC").style.color = "#FF726D";
                            $(".row .Health_class").text(JSON.parse(data));
                            $(".row .HealthHidden .lbl").val(JSON.parse(data));
                        }
                        // alert($(".row .HealthHidden .lbl").val());
                    }
            });
        };
    });
</script>
<!-- End Document
================================================== -->
