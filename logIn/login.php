<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
    <title>Sign in</title>
</head>
<body>
<?php
    
   if(!empty($_GET['log-in'])){
        if ('false' === $_GET['log-in']) {
         $error = "*Incorrect E-mail or Password!";
         $errorBox = "<style> .error{   position:absolute;                                   
                                        top:80%;
                                        left:50%;
                                        margin-left:-150px; /* width / -2 */
                                        margin-top:-170px; 
                                        width:300px;
                                        background-color:rgba(231, 249, 255, 0.3);
                                        border-radius: 10px;
                                        padding: 10px;
                                        height: 35px;
                                        display:block;
                                    }</style>";
        }
    }
    ?>   
        <div class="outer" >
            <form method="post" action="/Program/code/Profile/profile.php" autocomplete="off">

                    <div name="error" id="error" class="error"><?php if(!empty($error) || !empty($errorBox)){echo  $errorBox.$error;}?>
                </div>
                <div class="box">
                    <h1>Sign in</h1>
                    <div id="input_container">
                        <input name="email" type="email" autocomplete="off" class="email" maxlength="50" placeholder="E-mail" readonly onfocus="this.removeAttribute('readonly');"  />
                        <img src="images/person.png" id="input_img">
                    </div>
                    <div id="input_container">
                        <input name="password"  type="password" autocomplete="off"  class="password" maxlength="50" placeholder="Password" readonly onfocus="this.removeAttribute('readonly');" />
                        <img src="images/lock.png" id="input_img">
                    </div>
                    <a href="/Program/code/logIn/send_mail.php"><p>Forgot username or password?</p></a>
                    <a href="#"><input class="btn" type="submit" value="Login"></a> <!-- End Btn -->
                </div> <!-- End Box -->
        </form>
        </div>
    <?php
?>
</body>
</html>