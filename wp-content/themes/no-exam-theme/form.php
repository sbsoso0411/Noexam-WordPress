<?php
/**
 * Template Name: test_form
 * Description: A Page Template that displays at full width
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.4
 */
include("header2.php");
?> 

		<link rel="stylesheet" href="/form-css.css">
<!---->
<script type="text/javascript">
	$(document).ready(function(){
		getHealthClass();
		$("#Height_ft").change(function(){
			getHealthClass();
		});
		$("#Height_in").change(function(){
			getHealthClass();
		});
		$("#weight_input").change(function(){
			getHealthClass();
		});		
		$("#weight_input").keyup(function(){
			getHealthClass();
		});
		
		$("#submit1").click(function(){
			Validate();
		});
		
		function Validate(){
			if ($(".row .Health_class").text() != "Please enter correct weigth" && $(".row .Health_class").text() != "Please enter correct height"){
				$(".form-inputs").submit();
			}
		}
		
		function getHealthClass(){
		$.ajax({
				url: "/ratecalc/",
				type: 'POST',
				data:{
						ft : $("#Height_ft option:selected").val(),
						inches : $("#Height_in option:selected").val(),
						weight : $("#weight_input").val()
						},
				success:
					function (data) {
						if (JSON.parse(data) != "Please enter correct weigth" && JSON.parse(data) != "Please enter correct height")
						{
							document.getElementById("HC").style.color = "#555C60"; 
							$("#validation-error").html("");
							$(".Health_class").text(JSON.parse(data));
							$(".HealthHidden .lbl").val(JSON.parse(data));
						}
						else 
						{
							document.getElementById("HC").style.color = "#FF726D"; 
							$(".Health_class").text(JSON.parse(data));	
							$(".HealthHidden .lbl").val(JSON.parse(data));
						}
					}                          
		   });
		};
	});
</script>

<form action="/noexam/quote-results/" method="POST" class = "form-inputs">
  <INPUT TYPE=HIDDEN NAME="ModeUsed" VALUE="M">
       
        <table width="100%" cellpadding="5" cellspacing="0" border="0" style="border-collapse:collapse;" class="gutformbox" required >
          <tr>
            <td width="40%" class="labelbox">First Name*</td>
            <td width="60%"><input type="text" width="100%" id="first_name" name="FirstName" class="inputT"   required  /></td>
          </tr>
          <tr>
            <td class="labelbox">Last Name*</td> 
            <td><input type="text" id="last_name" name="LastName"  class="inputT"   required /></td>
          </tr>
          <tr>
            <td class="labelbox">Phone*</td>
            <td><input type="text" id="phone" name="HomePhone"   class="inputT"  required /></td>
          </tr>
          <tr>
            <td class="labelbox">Email*</td>
            <td><input type="text" id="email" name="Email"  class="inputT"  required /></td>
          </tr>
          <tr>
            <td class="labelbox">Birthdate* </td>
           
            <td><select name="BirthMonth"  style="width:35%;" class="selectT"  required  >
                <option value="1"  >January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
              </select>
              <select name="Birthday" style="width:20%; margin:0 5px;" required  class="selectT" >
                <option  >1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
                <option>14</option>
                <option>15</option>
                <option>16</option>
                <option>17</option>
                <option>18</option>
                <option>19</option>
                <option>20</option>
                <option>21</option>
                <option>22</option>
                <option>23</option>
                <option>24</option>
                <option>25</option>
                <option>26</option>
                <option>27</option>
                <option>28</option>
                <option>29</option>
                <option>30</option>
                <option>31</option>
              </select>
              <select name="BirthYear" required style="width:35%;" class="selectT">
                <option>1940</option>
                <option>1941</option>
                <option>1942</option>
                <option>1943</option>
                <option>1944</option>
                <option>1945</option>
                <option>1946</option>
                <option>1947</option>
                <option>1948</option>
                <option>1949</option>
                <option>1950</option>
                <option>1951</option>
                <option>1952</option>
                <option>1953</option>
                <option>1954</option>
                <option>1955</option>
                <option>1956</option>
                <option>1957</option>
                <option>1958</option>
                <option>1959</option>
                <option>1960</option>
                <option>1961</option>
                <option>1962</option>
                <option>1963</option>
                <option selected>1964</option>
                <option>1965</option>
                <option>1966</option>
                <option>1967</option>
                <option>1968</option>
                <option>1969</option>
                <option>1970</option>
                <option>1971</option>
                <option>1972</option>
                <option>1973</option>
                <option>1974</option>
                <option>1975</option>
                <option>1976</option>
                <option>1977</option>
                <option>1978</option>
                <option>1979</option>
                <option>1980</option>
                <option>1981</option>
                <option>1982</option>
                <option>1983</option>
                <option>1984</option>
                <option>1985</option>
                <option>1986</option>
                <option>1987</option>
                <option>1988</option>
                <option>1989</option>
                <option>1990</option>
              </select></td>
          </tr>
            <tr><td class="labelbox" >State</td>
		  <td>
		    <select   id="state" name="State" class="selectT" >
				<option value="1" selected>Alabama</option>
				<option value="2">Alaska</option>
				<option value="3">Arizona</option>
				<option value="4">Arkansas</option>
				<option value="5">California</option>
				<option value="6">Colorado</option>
				<option value="7">Connecticut</option>
				<option value="8">Delaware</option>
				<option value="9">Dist. Columbia</option>
				<option value="10">Florida</option>
				<option value="11">Georgia</option>
				<option value="13">Idaho</option>
				<option value="14">Illinois</option>
				<option value="15">Indiana</option>
				<option value="16">Iowa</option>
				<option value="17">Kansas</option>
				<option value="18">Kentucky</option>
				<option value="19">Louisiana</option>
				<option value="20">Maine</option>
				<option value="21">Maryland</option>
				<option value="22">Massachusetts</option>
				<option value="23">Michigan</option>
				<option value="24">Minnesota</option>
				<option value="25">Mississippi</option>
				<option value="26">Missouri</option>
				<option value="27">Montana</option>
				<option value="28">Nebraska</option>
				<option value="29">Nevada</option>
				<option value="30">New Hampshire</option>
				<option value="31">New Jersey</option>
				<option value="32">New Mexico</option>
				<option value="52">NY Personal</option>
				<option value="33">NY Business</option>
				<option value="34">North Carolina</option>
				<option value="35">North Dakota</option>
				<option value="36">Ohio</option>
				<option value="37">Oklahoma</option>
				<option value="38">Oregon</option>
				<option value="39">Pennsylvania</option>
				<option value="40">Rhode Island</option>
				<option value="41">South Carolina</option>
				<option value="42">South Dakota</option>
				<option value="43">Tennessee</option>
				<option value="44">Texas</option>
				<option value="45">Utah</option>
				<option value="46">Vermont</option>
				<option value="47">Virginia</option>
				<option value="48">Washington</option>
				<option value="49">West Virginia</option>
				<option value="50">Wisconsin</option>
				<option value="51">Wyoming</option>
			  </select>
		  </td></tr>
          <tr>
            <td class="labelbox">Sex*</td>
            <td><input type="radio" value="M" required id="sex_Male" name="Sex"   checked="">
              <label for="sex_Male">Male</label>
              <input type="radio" value="F" required id="sex_Female" name="Sex" >
              <label for="sex_Female">Female</label></td>
          </tr>
          <tr>
            <td class="labelbox">Used Tobacco in Past 2 years*</td>
            <td><input type="radio" value="Y" name="Smoker" required  >
              <label for="yes">Yes</label>
              <input type="radio" value="N"   name="Smoker" required >
              <label for="no">No</label></td>
          </tr>
          <tr>
            <td class="labelbox">Height*</td>
            <td>
				<select   id="Height_ft" name="Height_ft" required  class="selectT">
					<option value="4">4</option>
					<option value="5" selected>5</option>
					<option value="6">6</option>
				</select>
				<select   id="Height_in" name="Height_in" required  class="selectT">
					<option value = "0">0</option>
					<option value = "1">1</option>
					<option value = "2">2</option>
					<option value = "3">3</option>
					<option value = "4">4</option>
					<option value = "5">5</option>
					<option value = "6">6</option>
					<option value = "7">7</option>
					<option value = "8">8</option>
					<option value = "9">9</option>
					<option value = "10" selected>10</option>
					<option value = "11">11</option>
				</select>
			</td>
			
          </tr>
          <tr>
            <td class="labelbox">Weight*</td> 
            <td><input type="text" id="weight_input" name="weight"  class="inputT" value = "165"  required /></td>
          </tr>
		  <tr>
            <td class="labelbox">Health rate</td> 
			<td>
				<div class = "Health_class" ID = "HC">
				</div>
				<div class = "HealthHidden">
					<input type="hidden" class="lbl" name="HealthClass"/>
				</div>
			</td>
          </tr>
          <tr>
            <td class="labelbox">Coverage Amount*</td>
            <td><select name="FaceAmount" required   class="selectT">
                <option value="50000">$50,000</option>
                <option value="75000">$75,000</option>
                <option value="100000">$100,000</option>
                <option value="125000">$125,000</option>
                <option value="150000">$150,000</option>
                <option value="175000">$175,000</option>
                <option value="200000">$200,000</option>
                <option value="225000">$225,000</option>
                <option value="250000">$250,000</option>
                <option value="275000">$275,000</option>
                <option value="300000">$300,000</option>
                <option value="325000">$325,000</option>
                <option value="350000">$350,000</option>
                <option value="375000">$375,000</option>
                <option value="400000">$399,000</option>
              </select></td>
          </tr>
		  <tr>
			<td>
			</td>
			<td>
				<div class = "row" ID = "validation-error">
				</div>
			</td>
          </tr>
          <tr>
            <td align="center"  colspan="2"><button type="submit" value="Instant Quote" ID="submit1" name="submit1" class="button">See Your Rates</button></td>
          </tr>
        </table>
    </form>
