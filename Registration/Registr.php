<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="Rmainstyle.css">
<script src="/Program/code/jq/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
<title>Table edit</title></head>
<body>
<nav>
  <ul>
    <li><img src="images/sauleit.png">
    </li>
      
    <li><a href="/Program/code/MainTable/index.php" id="active">Table edit</a></li>
    <li>
        <div class="dropdown">
              <button class="dropbtn"><?php  echo  $_SESSION['First'] ." ". $_SESSION['Last'] ?></button>
              <div class="dropdown-content">
                <a href="/Program/code/Profile/profile.php">Profile</a>
                <a href="/Program/code/logIn/logout.php">Log-out</a>
              </div>
        </div>
    </li>
  </ul>
</nav>
    <div class="edittable">
        <div class="registration">
        <h1>Worker Registration</h1>
        
            <label for="name">First name:</label> 
            <input type="text" class="name" name="firstname" maxlength="50">
            <br>
            <span class="wrong_name" id="wrong_name" ></span>
            <br>
            <label for="lastname">Last name:</label>
            <input type="text" class="lastname" name="lastname" maxlength="50">
            <br>
            <span class="wrong_lastname" id="wrong_lastname"></span>
            <br>
            <label for="levels">Level of access:</label>
            <select class="levels" name="levels">
                <option selected disabled>Please select level of access</option>
                <option value="level_1" data="1">Level 1</option>
                <option value="level_2" data="2">Level 2</option>
                </select>
            <br>
            <p class="wrong_levels">Please select level of access!</p>
            <br>
            <label for="email">Email:</label>
            <input type="text" class="email" name="Email"  maxlength="50" autocomplete="off">
            <br>
            <span class="wrong_email" id="wrong_email"></span>
            <br>
            <label for="department">Department:</label>
            <select class="dep" name="dep">
                <option selected disabled value="">Please select Department</option>
                <option value="1" data="1">It-department</option>
                <option value="2" data="2">Support</option>
                <option value="3" data="3">Managment</option>
                <option value="4"data="4">Marketing</option>
            </select>
            <br>
            <p class="wrong_dep">Please select department!</p>
            <br>
            <label for="password">Password:</label>
            <input type="password" class="password" name="password" autocomplete="off" maxlength="20">
            <br>
            <span id="wrong_password" class="wrong_password"></span>
            <br>
            <input type="submit" class="reg" value="Registrate">
            <div class="error" id="error"></div>
        </div>
    </div>
    <script>
        function getValues(name,lastname,levels,email,dep,password,work_start) {
            $.ajax({
                method: "post",
                url: "http://localhost/Program/code/Registration/RegValues.php",
                data: {name: name, lastname: lastname, levels: levels, email: email, dep: dep, password: password,work_start: work_start},    
                success: function(data){
                  $(".error").html(data);
                }
            });
         }
         $(function(){
           var name = $('input[class="name"]').text();
           var output = $('div[class="error"]').text();
           
           $('body').on("click", '.reg', function(){
               var name = $('input[class="name"]').val();
               var lastname = $('input[class="lastname"]').val();
               var levels = $( ".levels option:selected" ).attr('data');
               var email = $('input[class="email"]').val();
               var dep = $( ".dep option:selected" ).attr('data');
               var password = $('input[class="password"]').val();
               var regexp1 = /^[A-Za-z_ÄäÖöÕõÜü|-]+$/;
               var regexp2 = /^[A-Za-z_ÄäÖöÕõÜü]+$/;
               var regexp3 = /^[0-9a-zA-Z]+$/;
               var output = $('div[class="error"]').val();
               var work_start = new Date();
              if(name != ""){
                  $('#wrong_name').text('');
				  if(!name.match(regexp1)){
                        $('#wrong_name').css({'color' : '#ff0000'});
						$('#wrong_name').text('Wrong firstname');
				  } else {
						$('#wrong_name').css("color", "black");
                        $('#wrong_name').text('');
				  }
              } else {
                  $('#wrong_name').css({'color' : '#ff0000'});
                  $('#wrong_name').text('Enter firstname');
              }
              if(lastname != ""){
                  $('#wrong_lastname').text('');
				  if(!lastname.match(regexp2)){
                        $('#wrong_lastname').css({'color' : '#ff0000'});
						$('#wrong_lastname').text('Wrong lastname');
				  } else {
						$('#wrong_lastname').css("color", "black");
                        $('#wrong_lastname').text('');
				  }
              } else {
                  $('#wrong_lastname').css({'color' : '#ff0000'});
                  $('#wrong_lastname').text('Enter lastname');
              } 
               

               if($( ".levels option:selected" ).text() == "Please select level of access"){
                  $(".wrong_levels").css("display", "block");
               }else{
                  $(".wrong_levels").css("display", "none");
               }
               if(email != ""){
                  var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
                  $('#wrong_email').text('');
					if(!pattern.test(email)){
                        $('#wrong_email').css({'color' : '#ff0000'});
						$('#wrong_email').text('Wrong email');
					} else {
						$('#wrong_email').css("color", "black");
                        $('#wrong_email').text('');
					}
				} else {
                    $('#wrong_email').css({'color' : '#ff0000'});
					$('#wrong_email').text('Enter email');
				}
               if($( ".dep option:selected" ).text() == "Please select Department"){
                   $(".wrong_dep").css("display", "block");
               }
               else{
                   $(".wrong_dep").css("display", "none");
                }
               
               if(password != ""){
                  $('#wrong_password').text('');
                  if(password.length >= 8 && password.length <= 20){
                      if(!password.match(regexp3)){
                          $('#wrong_password').css({'color' : '#ff0000'});
                          $('#wrong_password').text('Use for password only numbers and letters.');
                      }
                      
				  }else{
                      $('#wrong_password').text('Password length minimum 8 symbol, maximum 20 symbol.');
                      $('#wrong_password').css({'color' : '#ff0000'});  
                  }
                 
              } else {
                  $('#wrong_password').css({'color' : '#ff0000'});
                  $('#wrong_password').text('Please write password');
              }
               getValues(name, lastname, levels, email, dep, password, work_start);
            });
             if(output == "New employee registered"){
               $('#name').removeAttr('value');
             }
          });
       
    </script>
      
</body>
</html>