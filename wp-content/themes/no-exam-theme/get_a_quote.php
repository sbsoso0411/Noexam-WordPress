<?php
/**
 * Template Name: Quotes
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */

//declare(ticks=1);
$_POST['page'] = 1;
// header
include("header3.php");
// Helpers
require("inc/helpers.php");

// require("data/questions.php");
// $pathToDir = getcwd()."/wp-content/themes/no-exam-theme/storage/";
// ManagerOfObsoleteFiles($pathToDir,$Questions);

//$parentPid = posix_getpid();
//$child = pcntl_fork();
//phpinfo();

// Storager
require("inc/storage.php");

?>

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

		var vamdbDataStruct = {
			ajax_fn: null,
			ajax_ln: null,
			ajax_hp: null,
			ajax_em: null,
			ajax_bm: null,
			ajax_bd: null,
			ajax_by: null,
			ajax_sx: null,
			ajax_st: null,
			ajax_sm: null,
			ajax_hc: null,
			ajax_fa: null,
			ajax_oh: null,
			ajax_we: null,
			ajax_mu: null,
		}

		function getSubParams(elemName)
		{
			var value = "";
			var elements = document.getElementsByName(elemName);
			$.each(elements, function(index, element){
				if (element.checked)
				{
					value = $(element).val();
				}
			})

			return value;
		}

		function getParams()
		{

			vamdbDataStruct.ajax_fn = $('#fFirstName').val();
			vamdbDataStruct.ajax_ln = $('#fLastName').val();
			vamdbDataStruct.ajax_hp = $('#fHomePhone').val();
			vamdbDataStruct.ajax_em = $('#fEmail').val();
			vamdbDataStruct.ajax_bm = $('#fBirthMonth').val();
			vamdbDataStruct.ajax_bd = $('#fBirthday').val();
			vamdbDataStruct.ajax_by = $('#fBirthYear').val();
			vamdbDataStruct.ajax_sx = getSubParams('fSex');
			vamdbDataStruct.ajax_st = $('#fState').val();
			vamdbDataStruct.ajax_sm = getSubParams('fSmoker');
			vamdbDataStruct.ajax_hc = $('#fHealthClass').val();
			vamdbDataStruct.ajax_fa = $('#fFaceAmount').val();
			vamdbDataStruct.ajax_oh = $('#fHeight_ft').val()+"'"+$('#fHeight_in').val();
			vamdbDataStruct.ajax_we = $('#weight').val();
			vamdbDataStruct.ajax_mu = $('#fModeUsed').val();

		}

		function sendLeadToVamdb()
		{
			getParams();
			$.ajax({
				url: "/send-clientdata-to-db/",
				type: 'POST',
				data: vamdbDataStruct,
				success: function()
				{


					//$('#already-sended').val("true");
					//$(".form-inputs").submit();
				},
				complete: function (){
					$('#already-sended').val("true");
					$(".form-inputs").submit();
				}
			})
		}

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
			$(".Height_ft").change(function(){
				getHealthClass();
			});
			$(".Height_in").change(function(){
				getHealthClass();
			});
			$(".weight_input").change(function(){
				getHealthClass();
			});
			$(".weight_input").keyup(function(){
				getHealthClass();
			});



			$("#submit1").click(function(){
				var rValidate = rateValidate();
				var	uFieldsValidate = userFieldsValidate();
				if (rValidate == 0 && uFieldsValidate == 0){
					/*if ($('#already-sended').val() != "")
					 {

					 $(".form-inputs").submit();
					 }
					 else
					 {

					 sendLeadToVamdb();
					 }*/
					$(".form-inputs").submit();
				}
			});

			function resetErrorHighlighting(){
				document.getElementById("fFirstName").style.borderColor = "white";
				document.getElementById("fLastName").style.borderColor = "white";
				document.getElementById("fHomePhone").style.borderColor = "white";
				document.getElementById("fEmail").style.borderColor = "white";
			}

			function userFieldsValidate(){
				var error = 0;
				$("#validation-error").html("");
				resetErrorHighlighting()

				if ($("#fFirstName").val() == ""){
					$("#validation-error").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"First Name\" must be filled in.</div>");
					document.getElementById("fFirstName").style.borderColor = "red";
					error = 1;
				}
				if ($("#fLastName").val() == ""){
					$("#validation-error").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Last Name\" must be filled in.</div>");
					document.getElementById("fLastName").style.borderColor = "red";
					error = 1;
				}
				if ($("#fHomePhone").val() == ""){
					$("#validation-error").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Phone\" must be filled in.</div>");
					document.getElementById("fHomePhone").style.borderColor = "red";
					error = 1;
				}
				if ($("#fEmail").val() == ""){
					$("#validation-error").append("<div style = \"margin: 4px 0px 0px 0px\">Field \"Email\" must be filled in.</div>");
					document.getElementById("fEmail").style.borderColor = "red";
					error = 1;
				}
				return error;
			}

			function rateValidate(){
				if ($(".row .Health_class").text() != "Please enter correct weigth" && $(".row .Health_class").text() != "Please enter correct height"){
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
						ft : $(".Height_ft option:selected").val(),
						inches : $(".Height_in option:selected").val(),
						weight : $(".weight_input").val()
					},
					success:
						function (data) {
							if (JSON.parse(data) != "Please enter correct weigth" && JSON.parse(data) != "Please enter correct height")
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

	<div id="form-area" class="land-form">
		<h1 class="land-top-text">No Exam Life Insurance. Fast and Affordable Coverage.</h1>
		<div class="container">
			<div class="form-container row">

				<div class="eight columns"  style="float: right;">
					<div class="form-right">

						<iframe width="460" height="259" src="https://www.youtube.com/embed/fq0u-VtbNnw?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
						<ul class="land-listing">
							<li class="one">
								<h5>No Medical Exam</h5>
								Fast and simple questionnaire will show you coverage options in just a few minutes. No exam or waiting period.

							</li>
							<li class="two">
								<h5>Easy To Qualify</h5>
								Answer our short medical health application to see if you qualify for immediate coverage.

							</li>
							<li class="three">
								<h5>Low Monthly Payments</h5>
								Fixed monthly rates that will never change during the level term period of your policy.

							</li>
						</ul>
						<h5 class="ltt">Easy To Qualify</h5>
						<p class="lp">NoExam.com is a one stop shop for all of your life insurance needs. We specialize in selling no exam term life insurance products. We are an A rated company with the Better Business Bureau and are Trust Guard approved.</p>
					</div>
				</div>

				<div class="eight columns">
					<div class="form-left">

						<?php

						if ( have_posts() ) {

							// Start of the Loop
							while ( have_posts() ) {
								the_post();
								?>

								<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<!--<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header> --><!-- .entry-header -->

									<div class="entry-content"><?php

										the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'hellish' ) );
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'hellish' ), 'after' => '</div>' ) );
										?>
										<!--
                        You can add questions below and change any of the formating you like. The field names and values need to stay the same to insure the quotes can be run.

                        To switch the mode from annual to monthly change the value of the hidden field called ModeUsed to M instead of A

                        To ask for their name, phone and email uncomment the lines for the questions you want.
                        -->

										<form action="/post-page/" method="post"  class="form-inputs apply">
											<input type="hidden" id="gclid_field" name="fgclid_field" value="">
											<input type="hidden" id="bing_field" name="fbing_field" value="">
											<div class="row" style = "margin:0px">
												<h3>Get Your Quote Today</h3>
												<!-- <p>Fill out the form below to see a custom life insurance quote</p> -->
												<p>Compare Quotes To Get The Right Policy At The Right Price</p>
											</div>
											<input type = "hidden" name = "pageID" value = "1">
											<INPUT TYPE=HIDDEN id = "fModeUsed" NAME="fModeUsed" VALUE="M">
											<input id = "already-sended" type = "hidden" name = "fsendedToDb" value = "<?if ($_SESSION['fsendedToDb'] != "") {echo $_SESSION['fsendedToDb'];}?>" />

											<div class="row" ID = "coverage-amount">
												<div class="label"><label>Coverage Amount</label></div>
												<div class="inputs"><select id = "fFaceAmount" name="fFaceAmount">

														<option value="100000" 	<?php if ($_SESSION['fFaceAmount'] == 100000)										{ echo "selected";}?>>$100,000</option>
														<option value="150000" 	<?php if ($_SESSION['fFaceAmount'] == 150000)										{ echo "selected";}?>>$150,000</option>
														<option value="200000" 	<?php if ($_SESSION['fFaceAmount'] == 200000)										{ echo "selected";}?>>$200,000</option>
														<option value="250000" 	<?php if ($_SESSION['fFaceAmount'] == 250000 or !isset($_SESSION['FaceAmount']))	{ echo "selected";}?>>$250,000</option>
														<option value="300000" 	<?php if ($_SESSION['fFaceAmount'] == 300000)										{ echo "selected";}?>>$300,000</option>
														<option value="400000" 	<?php if ($_SESSION['fFaceAmount'] == 400000)										{ echo "selected";}?>>$400,000</option>
														<option value="500000" 	<?php if ($_SESSION['fFaceAmount'] == 500000)										{ echo "selected";}?>>$500,000</option>
													</select></div>
											</div>


											<div class="row clearfix">
												<div class="label"><label>Birthdate</label></div>
												<div class="inputs">
													<select name="fBirthMonth" class="small" id = "fBirthMonth">
														<option value="1" 	<?php if ($_SESSION['fBirthMonth'] == 1 or !isset($_SESSION['fBirthMonth'])) 	{echo "selected";}?>>January</option>
														<option value="2" 	<?php if ($_SESSION['fBirthMonth'] == 2) 										{echo "selected";}?>>February</option>
														<option value="3" 	<?php if ($_SESSION['fBirthMonth'] == 3) 										{echo "selected";}?>>March</option>
														<option value="4" 	<?php if ($_SESSION['fBirthMonth'] == 4) 										{echo "selected";}?>>April</option>
														<option value="5" 	<?php if ($_SESSION['fBirthMonth'] == 5) 										{echo "selected";}?>>May</option>
														<option value="6" 	<?php if ($_SESSION['fBirthMonth'] == 6) 										{echo "selected";}?>>June</option>
														<option value="7" 	<?php if ($_SESSION['fBirthMonth'] == 7) 										{echo "selected";}?>>July</option>
														<option value="8" 	<?php if ($_SESSION['fBirthMonth'] == 8) 										{echo "selected";}?>>August</option>
														<option value="9" 	<?php if ($_SESSION['fBirthMonth'] == 9) 										{echo "selected";}?>>September</option>
														<option value="10"	<?php if ($_SESSION['fBirthMonth'] == 10) 										{echo "selected";}?>>October</option>
														<option value="11"	<?php if ($_SESSION['fBirthMonth'] == 11) 										{echo "selected";}?>>November</option>
														<option value="12"	<?php if ($_SESSION['fBirthMonth'] == 12) 										{echo "selected";}?>>December</option>
													</select>
													<select name="fBirthday" class="small" id = "fBirthday">
														<option <?php if ($_SESSION['fBirthday'] == 1 or !isset($_SESSION['fBirthday']))		{echo "selected";}?>>1</option>
														<option <?php if ($_SESSION['fBirthday'] == 2) 										{echo "selected";}?>>2</option>
														<option <?php if ($_SESSION['fBirthday'] == 3) 										{echo "selected";}?>>3</option>
														<option <?php if ($_SESSION['fBirthday'] == 4) 										{echo "selected";}?>>4</option>
														<option <?php if ($_SESSION['fBirthday'] == 5) 										{echo "selected";}?>>5</option>
														<option <?php if ($_SESSION['fBirthday'] == 6) 										{echo "selected";}?>>6</option>
														<option <?php if ($_SESSION['fBirthday'] == 7) 										{echo "selected";}?>>7</option>
														<option <?php if ($_SESSION['fBirthday'] == 8) 										{echo "selected";}?>>8</option>
														<option <?php if ($_SESSION['fBirthday'] == 9) 										{echo "selected";}?>>9</option>
														<option <?php if ($_SESSION['fBirthday'] == 10) 										{echo "selected";}?>>10</option>
														<option <?php if ($_SESSION['fBirthday'] == 11) 										{echo "selected";}?>>11</option>
														<option <?php if ($_SESSION['fBirthday'] == 12) 										{echo "selected";}?>>12</option>
														<option <?php if ($_SESSION['fBirthday'] == 13) 										{echo "selected";}?>>13</option>
														<option <?php if ($_SESSION['fBirthday'] == 14) 										{echo "selected";}?>>14</option>
														<option <?php if ($_SESSION['fBirthday'] == 15) 										{echo "selected";}?>>15</option>
														<option <?php if ($_SESSION['fBirthday'] == 16) 										{echo "selected";}?>>16</option>
														<option <?php if ($_SESSION['fBirthday'] == 17) 										{echo "selected";}?>>17</option>
														<option <?php if ($_SESSION['fBirthday'] == 18) 										{echo "selected";}?>>18</option>
														<option <?php if ($_SESSION['fBirthday'] == 19) 										{echo "selected";}?>>19</option>
														<option <?php if ($_SESSION['fBirthday'] == 20) 										{echo "selected";}?>>20</option>
														<option <?php if ($_SESSION['fBirthday'] == 21) 										{echo "selected";}?>>21</option>
														<option <?php if ($_SESSION['fBirthday'] == 22) 										{echo "selected";}?>>22</option>
														<option <?php if ($_SESSION['fBirthday'] == 23) 										{echo "selected";}?>>23</option>
														<option <?php if ($_SESSION['fBirthday'] == 24) 										{echo "selected";}?>>24</option>
														<option <?php if ($_SESSION['fBirthday'] == 25) 										{echo "selected";}?>>25</option>
														<option <?php if ($_SESSION['fBirthday'] == 26) 										{echo "selected";}?>>26</option>
														<option <?php if ($_SESSION['fBirthday'] == 27) 										{echo "selected";}?>>27</option>
														<option <?php if ($_SESSION['fBirthday'] == 28) 										{echo "selected";}?>>28</option>
														<option <?php if ($_SESSION['fBirthday'] == 29) 										{echo "selected";}?>>29</option>
														<option <?php if ($_SESSION['fBirthday'] == 30) 										{echo "selected";}?>>30</option>
														<option <?php if ($_SESSION['fBirthday'] == 31) 										{echo "selected";}?>>31</option>
													</select>
													<select name="fBirthYear" class="small last" id = "fBirthYear">
														<option <?php if ($_SESSION['fBirthYear'] == 1940) 										{echo "selected";}?>>1940</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1941) 										{echo "selected";}?>>1941</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1942) 										{echo "selected";}?>>1942</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1943) 										{echo "selected";}?>>1943</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1944) 										{echo "selected";}?>>1944</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1945) 										{echo "selected";}?>>1945</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1946) 										{echo "selected";}?>>1946</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1947) 										{echo "selected";}?>>1947</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1948) 										{echo "selected";}?>>1948</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1949) 										{echo "selected";}?>>1949</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1950 or !isset($_SESSION['fBirthYear']))		{echo "selected";}?>>1950</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1951) 										{echo "selected";}?>>1951</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1952) 										{echo "selected";}?>>1952</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1953) 										{echo "selected";}?>>1953</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1954) 										{echo "selected";}?>>1954</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1955) 										{echo "selected";}?>>1955</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1956) 										{echo "selected";}?>>1956</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1957) 										{echo "selected";}?>>1957</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1958) 										{echo "selected";}?>>1958</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1959) 										{echo "selected";}?>>1959</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1960) 										{echo "selected";}?>>1960</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1961) 										{echo "selected";}?>>1961</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1962) 										{echo "selected";}?>>1962</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1963) 										{echo "selected";}?>>1963</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1964) 										{echo "selected";}?>>1964</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1965) 										{echo "selected";}?>>1965</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1966) 										{echo "selected";}?>>1966</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1967) 										{echo "selected";}?>>1967</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1968) 										{echo "selected";}?>>1968</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1969) 										{echo "selected";}?>>1969</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1970) 										{echo "selected";}?>>1970</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1971) 										{echo "selected";}?>>1971</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1972) 										{echo "selected";}?>>1972</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1973) 										{echo "selected";}?>>1973</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1974) 										{echo "selected";}?>>1974</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1975) 										{echo "selected";}?>>1975</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1976) 										{echo "selected";}?>>1976</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1977) 										{echo "selected";}?>>1977</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1978) 										{echo "selected";}?>>1978</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1979) 										{echo "selected";}?>>1979</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1980) 										{echo "selected";}?>>1980</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1981) 										{echo "selected";}?>>1981</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1982) 										{echo "selected";}?>>1982</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1983) 										{echo "selected";}?>>1983</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1984) 										{echo "selected";}?>>1984</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1985) 										{echo "selected";}?>>1985</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1986) 										{echo "selected";}?>>1986</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1987) 										{echo "selected";}?>>1987</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1988) 										{echo "selected";}?>>1988</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1989) 										{echo "selected";}?>>1989</option>
														<option <?php if ($_SESSION['fBirthYear'] == 1990) 										{echo "selected";}?>>1990</option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="label"><label>Height</label></div>
												<div class="inputs"><select class="small Height_ft" name="fHeight_ft" id = "fHeight_ft">
														<option value = "4" <?php if ($_SESSION['fHeight_ft'] == 4) 										{echo "selected";}?>>4</option>
														<option value = "5" <?php if ($_SESSION['fHeight_ft'] == 5 or !isset($_SESSION['fHeight_ft']))  	{echo "selected";}?>>5</option>
														<option value = "6" <?php if ($_SESSION['fHeight_ft'] == 6) 										{echo "selected";}?>>6</option>
													</select>
													<label style="padding-top:15px;width:20px;float:left">ft</label>
													<select class = "small Height_in" name="fHeight_in" id = "fHeight_in">
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
													<label style="padding-top:15px;width:20px;float:left">in</label></div>
											</div>

											<div class="row">
												<div class="label"><label>Weight</label></div>
												<div class="inputs" style = "width:128px;float:left;"><div class = "small_input">
														<input class="weight_input" id = "weight" type="text" size=3  value="<?php if (isset($_SESSION['fweight'])){echo $_SESSION['fweight'];} else {echo "165";}?>" min="1" max="500" name="fweight">
													</div></div>
												<div><label style="padding:12px 0px 0px 6px;width:20px;float:left">lbs</label></div>
											</div>
											<!--<div class = "row">


                                            </div>-->
											<div class="row" style = "display:none;">
												<div class="label"><label>Health class</label></div>
												<div class="inputs"><div class = "Health_class" ID = "HC">
													</div>
													<div class = "HealthHidden">
														<input type="hidden" class="lbl" name="fHealthClass" id = "fHealthClass" /> <!--value set by ratecalc-->
													</div></div>
											</div>
											<div class="row">
												<div class="label"><label>State</label></div>
												<div class="inputs">		<div class="three columns alpha">

														<select name="fState" id = "fState" style = "width:145px;">
															<OPTION VALUE="AL" <?php if ($_SESSION['fState'] == "AL" or !isset($_SESSION['State'])) 	{echo "selected";}?>>Alabama</OPTION>

															<OPTION VALUE="AZ" <?php if ($_SESSION['fState'] == "AZ") 								{echo "selected";}?>>Arizona</OPTION>
															<OPTION VALUE="AR" <?php if ($_SESSION['fState'] == "AR") 								{echo "selected";}?>>Arkansas</OPTION>
															<OPTION VALUE="CA" <?php if ($_SESSION['fState'] == "CA") 								{echo "selected";}?>>California</OPTION>
															<OPTION VALUE="CO" <?php if ($_SESSION['fState'] == "CO") 								{echo "selected";}?>>Colorado</OPTION>

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
															<OPTION VALUE="MD" <?php if ($_SESSION['fState'] == "MD") 								{echo "selected";}?>>Maryland</OPTION>

															<OPTION VALUE="MI" <?php if ($_SESSION['fState'] == "MI") 								{echo "selected";}?>>Michigan</OPTION>
															<OPTION VALUE="MN" <?php if ($_SESSION['fState'] == "MN") 								{echo "selected";}?>>Minnesota</OPTION>
															<OPTION VALUE="MS" <?php if ($_SESSION['fState'] == "MS") 								{echo "selected";}?>>Mississippi</OPTION>
															<OPTION VALUE="MO" <?php if ($_SESSION['fState'] == "MO") 								{echo "selected";}?>>Missouri</OPTION>

															<OPTION VALUE="NE" <?php if ($_SESSION['fState'] == "NE") 								{echo "selected";}?>>Nebraska</OPTION>
															<OPTION VALUE="NV" <?php if ($_SESSION['fState'] == "NV") 								{echo "selected";}?>>Nevada</OPTION>
															<OPTION VALUE="NJ" <?php if ($_SESSION['fState'] == "NJ") 								{echo "selected";}?>>New Jersey</OPTION>


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

															<OPTION VALUE="VA" <?php if ($_SESSION['fState'] == "VA") 								{echo "selected";}?>>Virginia</OPTION>

															<OPTION VALUE="WV" <?php if ($_SESSION['fState'] == "WV") 								{echo "selected";}?>>West Virginia</OPTION>
															<OPTION VALUE="WI" <?php if ($_SESSION['fState'] == "WI") 								{echo "selected";}?>>Wisconsin</OPTION>
															<OPTION VALUE="WY" <?php if ($_SESSION['fState'] == "WY") 								{echo "selected";}?>>Wyoming</OPTION>

														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="label"><label>Sex</label></div>
												<div class="inputs"><label class="chk"><input type="radio" <?php if(!isset($_SESSION['fSex']) or $_SESSION['fSex'] == "M"){echo "checked";} ?> name="fSex" value="M"> Male </label>
													<label class="chk"><input type="radio" <?php if($_SESSION['fSex'] == "F"){echo "checked";} ?> name="fSex" value="F">Female </label></div>
											</div>


											<div class="row">
												<div class="label"><label>Used Tobacco in Past 2 years:</label></div>
												<div class="inputs"><label class="chk"><input type="radio" <?php if ($_SESSION['fSmoker'] == "Y"){echo "checked";}?> name="fSmoker" value="Y">Yes</label>
													<label class="chk"><input type="radio" <? if (($_SESSION['fSmoker'] == "N") or (!isset($_SESSION['fSmoker']))){echo "checked";}?> name="fSmoker" value="N">No</label></div>
											</div>

											<div class="row">
												<div class="label"><label>First Name</label></div>
												<div class="inputs"><INPUT TYPE=TEXT id = "fFirstName" NAME="fFirstName" value = "<?php if ($_SESSION['fFirstName'] != "") {echo $_SESSION['fFirstName'];}?>" SIZE=30></div>
											</div>
											<div class="row">
												<div class="label"><label>Last Name</label></div>
												<div class="inputs"><INPUT TYPE=TEXT id = "fLastName" NAME="fLastName" value = "<?php if ($_SESSION['fLastName'] != "") {echo $_SESSION['fLastName'];}?>" SIZE=30></div>
											</div>
											<div class="row">
												<div class="label"><label>Phone</label></div>
												<div class="inputs"><INPUT TYPE=TEXT id = "fHomePhone" NAME="fHomePhone" value = "<?php if ($_SESSION['fHomePhone'] != "") {echo $_SESSION['fHomePhone'];}?>" SIZE=30></div>
											</div>
											<div class="row">
												<div class="label"><label>Email</label></div>
												<div class="inputs"><INPUT TYPE=TEXT id = "fEmail" NAME="fEmail" value = "<?php if ($_SESSION['fEmail'] != "") {echo $_SESSION['fEmail'];}?>" SIZE=30></div>
											</div>
											<div class = "row" ID = "validation-error">
											</div>


											<div>
												<input type="button" value="Show Me My Quotes ▶" ID="submit1" name="submit1" class="button">
											</div>
											<!--<p><span style="font-size:14px; color:#ff3e11;">*</span> Field Required</p> -->

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
						<h5 class="ltt" style="margin-top:30px;">Our Promise:</h5>
						<p class="lp">We do not share your information with any 3rd parties. Your information is only used to generate a quote. </p>

					</div>
				</div>

			</div>

			<div class="form-footer">
				<h4>Customer Reviews</h4>
				<div style="min-height: 100px; overflow: hidden;" class="shopperapproved_widget sa_rotate sa_horizontal sa_count4 sa_rounded sa_large sa_bgBlue sa_colorWhite sa_borderWhite sa_nodate"></div><script type="text/javascript">var sa_interval = 10000;function saLoadScript(src) { var js = window.document.createElement('script'); js.src = src; js.type = 'text/javascript'; document.getElementsByTagName("head")[0].appendChild(js); } if (typeof(shopper_first) == 'undefined') saLoadScript('//www.shopperapproved.com/widgets/testimonial/12271.js'); shopper_first = true; </script><div style="text-align:right;"><a href="http://www.shopperapproved.com/reviews/noexam.com/" target="_blank" rel="nofollow" onclick="return sa_openurl(this.href);"><img class="sa_widget_footer" alt="" src="https://www.shopperapproved.com/widgets/widgetfooter-darklogo.png" style="border: 0;"></a></div>

			</div>
		</div>
	</div><!-- #content-area -->
	<div class="land_bottom">
		<div class="container">
			<p><strong>NoExam.com's Security Promise</strong></p>
			<p>NoExam.com is committed to helping our customers shop for life insurance coverage online securely.As a company that sells life insurance, we understand just how important it is to safeguard your information.</p>
			<p>NoExam.com utilizes a strict privacy policy that governs data security and collection procedures. This policy includes that NoExam.com:</p>
			<ul class="bullet">
				<li>Does not share your personal contact information with third parties unrelated to your application.&nbsp;</li>
				<li>Encrypts sensitive data and does not store it.</li>
				<li>Does not sell, rent or share your personal information to any third parties.</li>
			</ul>
			<p>In addition to these measures, NoExam.com partners with Comodo Security, Trust Guard, The Better Business Bureau, and Shopper Approved to verify that we are upholding the highest security and customer satisfaction standards.</p>
		</div>
	</div>
	<div id="footer" class="footer-wrapper land-footer">
		<div class="container"><p class="footer-text">Copyright &copy; 2016 No Exam Life Insrurance, Inc.   <a href="https://www.noexam.com/about/">About Us</a>  |  <a href="https://www.noexam.com/privacy-policy/">Privacy Policy</a></p></div>
	</div>
	<a title="Web Analytics" href="http://clicky.com/100764367"><img alt="Web Analytics" src="//static.getclicky.com/media/links/badge.gif" border="0" /></a>
	<script src="//static.getclicky.com/js" type="text/javascript"></script>
	<script type="text/javascript">try{ clicky.init(100764367); }catch(e){}</script>
	<noscript><p><img alt="Clicky" width="1" height="1" src="//in.getclicky.com/100764367ns.gif" /></p></noscript>
	<script src="//cdn.optimizely.com/js/2456450336.js"></script>
	<!-- Google Code for Remarketing Tag -->
	<!--------------------------------------------------
    Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
    --------------------------------------------------->
	<script type="text/javascript">
		/* <![CDATA[ */
		var google_conversion_id = 1021976105;
		var google_custom_params = window.google_tag_params;
		var google_remarketing_only = true;
		/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1021976105/?value=0&amp;guid=ON&amp;script=0"/>
		</div>
	</noscript>

<?php //get_footer(); ?>