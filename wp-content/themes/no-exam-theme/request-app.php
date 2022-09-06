<?php
/**
 * Template Name: Request App
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

$_POST['page'] = 8;
get_header();

require("inc/helpers.php");
// Storage
require("inc/storage.php");
require("data/questions.php");

?>

	<div id="content-area">
		<div class="sbli-appcon">
			<div class="sbli-titlearea">
				<h2>Congratulations! You are almost done!</h2>
				<h5>Here's what happens now</h5>
			</div>

			<div class="sbli-details-area">
				<div class="sbli-row">
					<img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sbli-icon-1.png"  class="alignleft"/>
					<p>You will receive a confirmation email from NoExam.com. An agent will be contacting you to confirm the information on your application. This is done to ensure the correctness of your application before it is sent for approval.</p>
				</div>


				<div class="sbli-row">
					<img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sbli-icon-2.png"  class="alignleft"/>
					<p>Shortly after your application has been confirmed you will receive an email containing a link to e-sign your application. This is another opportunity to review the policy for correctness before it is issued. After signing, you will receive an email notification regarding the decision on your application within 24-48 hours.</p>
				</div>


				<div class="sbli-row">
					<img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/sbli-icon-3.png"  class="alignleft"/>
					<p>Welcome to the new era of shopping for and buying life insurance. Noexam.com has leveraged technology to simplify the process of securing life insurance coverage. If you are satisified please share your experience with your friends and family.</p>
				</div>


			</div>
		</div>

		<div class="specialrequests">
			<p>Or if you have any special requests please do not hesitate to contact our office</p>
			<h3>888 407 0714</h3>
		</div>
	</div>

	<div class="sagicor-testimonial">
		<div id="content-area" class="no-mar">
			<div class="textimonialarea">
				<img src="https://noexam.staging.wpengine.com/wp-content/themes/no-exam-theme/images/pic1.png" width="168" height="168" class="alignleft"/>
				<h1>Thank you for Choosing NoExam.com</h1>
				<p>Our goal is to align developments in insurance technology and the modern consumer.
					It is our privilege to serve you and we are looking forward to the opportunity of earning your business.</p>
				<h5>Jonathan Fritz </h5>
			</div>
		</div>
	</div>
<script type="text/javascript"> var sa_values = { 'site':12271, 'forcecomments':1 };  function saLoadScript(src) { var js = window.document.createElement("script"); js.src = src; js.type = "text/javascript"; document.getElementsByTagName("head")[0].appendChild(js); } var d = new Date(); if (d.getTime() - 172800000 > 1453746931000) saLoadScript("//www.shopperapproved.com/thankyou/rate/12271.js"); else saLoadScript("//direct.shopperapproved.com/thankyou/rate/12271.js?d=" + d.getTime()); </script>

<!-- Google Code for Application Submitted Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1021976105;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "RMOKCOT70lkQqbyo5wM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1021976105/?label=RMOKCOT70lkQqbyo5wM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script type="text/javascript">
    var vm_conversion_type = 'sale';
    var vm_conversion_adv = '38364';
</script>
<script type="text/javascript" src="//marketplaces.vantagemedia.com/conversion.js" ></script>
<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"4018398"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script>

<?php get_footer(); ?>