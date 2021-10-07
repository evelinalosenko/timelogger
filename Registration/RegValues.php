<?php 

if(!empty($_POST['name']) && !empty($_POST['lastname']) && !empty($_POST['levels']) && !empty($_POST['email']) && !empty($_POST['dep']) && !empty($_POST['password'])){
   
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $levels = $_POST['levels'];
    $email = $_POST['email'];
    $dep = $_POST['dep'];
    $password_1 = $_POST['password'];
    $work_start = $_POST['work_start'];
    if (!preg_match('/[a-zA-Z_äÄöÖüÜ|-]/', $name)){
        exit;
    }
    if(!preg_match('/[a-zA-Z_äÄöÖüÜ]/', $lastname)){
        exit;
    }
     if($_POST['levels'] == "Please select level of access"){
         exit;
    }
    filter_var('example@mail.com', FILTER_VALIDATE_EMAIL);
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        exit;       
    }
    if($_POST['dep'] == "Please select Department"){
       exit;
    }
    if(strlen($password_1) < 8 || strlen($password_1) > 20){
        exit;
    }
    if(!preg_match('/[0-9a-zA-Z]/', $password_1)){
        exit;
    }
    
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $timeloggerdb = "timeloggerdb";

    $conn = new mysqli($servername, $username, $password, $timeloggerdb);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = mysqli_query($conn, "SELECT * FROM worker WHERE email = '$email'");
    while($row = mysqli_fetch_array($query)) {
        if($email == $row['email']){
            exit("This email is already registered");
        }
    }
   
    $hash = password_hash($password_1,PASSWORD_DEFAULT);
    $sql = "INSERT INTO worker (name, lastname, email, password, dep_id, access_id, work_start) VALUES ('$name', '$lastname', '$email','$hash', '$dep', '$levels', '$work_start')";
    if ($conn->multi_query($sql) === TRUE) {
        echo "New employee registered ";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
    
    
}

?>