<?php
/*  Collector (Garcia, Kornell, Kerr, Blake & Haffey)
    A program for running experiments on the web
    Copyright 2012-2016 Mikey Garcia & Nate Kornell


    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License version 3 as published by
    the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
 
		Kitten release (2019) author: Dr. Anthony Haffey (a.haffey@reading.ac.uk)
*/

require_once ("cleanRequests.php");

require_once("libraries.html");

if(isset($_SESSION['user_email'])){    
  $login_style = "display:none";
  $logout_style = '';
} else {
  $login_style = "";
	$logout_style = "display:none";    
}
if(isset($_SESSION['user_email'])){
	$user_email = $_SESSION['user_email'];
} 

if(isset($_POST['skin'])){
  $_SESSION['skin'] = $_POST['skin']; 
}

if(!isset($_SESSION['skin'])){
  $_SESSION['skin'] = "Collector";
}

$error_message = '';
if(isset($_SESSION['login_error'])){
  $error_message = ($_SESSION['login_error']);
  unset($_SESSION['login_error']);
}

$url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );

?>


<script>
error_message = "<?= $error_message ?>";
</script>

<script>
if(error_message !== ""){
  bootbox.alert(error_message);
} 
</script>

<style>
	#login_register_card{
		margin: 0 auto; /* Added */
		float: none; /* Added */
		margin-bottom: 10px; /* Added */
		
	}
</style>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<form action="login.php" method="post" style="padding:0px">	
	<span id="logout_span" style="<?= $logout_style ?>">
    <button id="logout_btn" type="submit" name="login_type" value="logout" class="btn btn-primary">Log out</button>
	</span>	
    <input type='hidden' name='return_page' value='<?= $url ?>' />
    <div id="login_register_span"  style="<?= $login_style ?>; height:90%">
			<div id="login_register_card" class="card text-primary bg-white align-middle" style="max-width: 36rem; position: relative; top: 50%; transform: translateY(-50%); ">
				<div class="card-header bg-primary text-white">Collector Kitten Login</div>
				<div class="card-body">
				
					<span id="username"></span>
					<div class="row">
						<input id="username_input" name="user_email" type="email" class="form-control" placeholder="email">
					</div>
					<div class="row">
							<input id="password_input" name="user_password" type="password" class="form-control" placeholder="password">
					</div>
          <div class="row">  
            <div class="g-recaptcha" data-sitekey="6Lcg0awUAAAAAL5nO7Kp-IJ7IEdARN9z1ryYCNi6"></div>
          </div>
					<div class="row">
						<input class="btn btn-primary" type="button" style="margin:3px" id="login_button" 		value="login">										
            <button type="button" class="btn btn-primary" style="display:none" data-toggle="modal" data-target="#institute_div" onclick="list_institutes()" id="register_map_button"> Register </button>
            <input class="btn btn-primary" type="button" style="margin:3px" id="register_button" value="register" style="display:none">
				
            <input class="btn btn-primary" type="button" style="margin:3px" id="forgot_button" 	value="forgot password">
            
           
						<!-- hidden inputs -->
						<input type="submit" class="collectorButton" id="forgot_button_submit" name='login_type' value="forgot" style="display:none">
						<input type='hidden' name='participant_researcher' id='participant_researcher'>
						<input  type="submit" class="collectorButton" id="login_button_submit" name='login_type' value="login" style="display:none">
						<input style="display:none" type="submit" class="collectorButton" id="register_submit" name='login_type' value="register">
					</div>
				</div>
			</div>
    </div>
</form>

<div id="institute_div" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">      
      <div class="modal-body">
        <?php
          require("ResearcherInstitution.html");
        ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="skip_map_button">Skip</button>
        <button type="button" class="btn btn-primary" id="register_proceed_button">Proceed</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<script>

$("#register_proceed_button").on("click",function(){
  $("#country_selected").click();
});
$("#skip_map_button").on("click",function(){
  $("#register_submit").click();
});

$("#forgot_button").on("click",function(){
	var email_val = $("#username_input").val();
	bootbox.prompt({
		title: "Please confirm your email:",
		value: email_val,
		callback: function (result) {
			$("#forgot_button_submit").click();
		}
	});
});



$("#register_button").on("click",function(){
  // checks
	// password long enough?
	if($("#password_input").val().length < 8){
		bootbox.alert("Your password is too short. Please make a password of at least 8 characters. Ideally with a mixture of capital letters, numbers and characters.");			
	} else {
    
    
		bootbox.confirm("Making sure you don't lose your password is extremely important! Your password is used as part of the encryption and decryption of your data. <br><br>In short, if you lose your password then you lose your data. <br><br> Once you've made sure your password will not be lost, click on <b>OK</b> to proceed",function(result){
			if(result){
				bootbox.prompt({
					title: "Please confirm your password:",
					inputType: 'text',
					callback: function (result) {
						if(result == $("#password_input").val()){
							$("#participant_researcher").val("both");
              $("#register_map_button").click();							
						} else {
							alert("Passwords did not match.");
						}
					}
				});
			}			
		});		
	}    
});

$("#login_button").on("click",function(){
	$("#login_button_submit").click(); // to submit form
});
</script>