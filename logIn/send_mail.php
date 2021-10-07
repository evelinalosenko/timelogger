<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style_send_mail.css">
    <title>Send E-mail</title>
</head>
<body>

    
   
<div class="outer" >
    <form method="post"  >
        <div class="box">
            <h1>Re-send  password</h1>
            <div id="input_container">
                <input name="email" type="email" autocomplete="off" class="email" maxlength="50" placeholder="Enter e-mail" readonly onfocus="this.removeAttribute('readonly');"  />
                <img src="images/person.png" id="input_img">
            </div>
            <a href="#"><input class="btn" type="submit" value="Send a password"></a> 
        </div> 
    </form>
</div>

    <?php
    if(!empty($_POST['email'])){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "timeloggerdb";
            // Create connection
        $conn = new mysqli ($servername, $username, $password, $dbname);
            
            // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else{
            echo "connected<br>";
        }
          
        $email = $_POST['email'];
        $query = mysqli_query($conn, "SELECT * FROM employees WHERE Email = '$email'");
        $numrow = mysqli_num_rows($query);
        if($numrow != 0){
            while($row = mysqli_fetch_assoc($query)){
                $db_email = $row['Email'];
            }
            if($email == $db_email){
                $code = rand (10000,1000000);
                $to = $db_email;
                $subject = "Password Reset";
                $body = "This is automated email. Click the link or paste it into your browser: http://localhost/Program/code/send_mail.php?code=$code&email=$email";
                
                $headers = "From: evelinalosenko@gmail.com";
                mysqli_query($conn, "SELECT employees SET passreset='$code' WHERE Email='$email'");
                mail($to,$subject,$body,$headers);
                echo "Check Your Email";
            }else{
                echo"Email is inncorrect";
            }
        }else{
            echo "This email doesn't exist";
        }
    }
    ?> 
</body>
</html>