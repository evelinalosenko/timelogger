<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="Profile/style_profile.css">
</head>
<body>
    <nav>
  <ul>
    <li><img src="images/sauleit.png">
    </li>
    <li><a href="#" id="active">Table edit</a></li>
    <li><a href="#">Registration</a></li>
    <li><a href="#">Minu Nimi</a></li>
  </ul>
</nav>
<?php
        if(isset($_POST['email'])){
            $email = $_POST['email'];
        }
    
        if(isset($_POST['password'])){
            $pass = $_POST['password'];
        }
            
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            $servername = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "timeloggerdb";
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }else{
                echo "connected<br>";
            }
          
            $email = mysqli_real_escape_string($conn, $email);
            $pass = mysqli_real_escape_string($conn, $pass);
            $sql = "SELECT  * FROM employees AccessLevel WHERE Email = '$email' and Password = '$pass'";
            $result = mysqli_query($conn, $sql) or die ("Log in is failed". mysqli_error());
           
            if($email == "" && $pass == ""){
                header('Location: login.php?log-in=false');
            }
            $row = mysqli_fetch_array($result);
            if($row["Email"] == $email && $row["Password"] == $pass){
                echo "Welcom!";
                
            }else{
                header('Location: login.php?log-in=false'); 
            }
           
            $access = $row['AccessLevel'];
            if($access == 'Level 2'){
                
            }
            echo $access;
            
            $conn->close();
       }
            if($email == "" || $pass == ""){
            header('Location: login.php?log-in=false');
        }
       
    ?>
    </body>
</html>