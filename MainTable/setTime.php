<?php
session_start();
?>
<?php

if(!empty($_POST['minutes'])){
    $minutes = $_POST['minutes'];
}else{
    $minutes = '';
}
if(!empty( $_POST['breakfast'])){
    $lunch = $_POST['breakfast'];
}else{
    $lunch = '';
}
if(!empty($_POST['extra_minutes'])){
    $extra_minutes = $_POST['extra_minutes'];
}else{
    $extra_minutes = '';
}
$date = $_POST['date'];
$start = $_POST['start'];
$end = $_POST['end'];
$name = $_POST['employee'];
$user_id = $_POST['user_id'];
$_SESSION['user_id'] = $user_id;
$_SESSION['employee'] = $name;

$servername = "127.0.0.1";
$username = "root";
$password = "";
$timeloggerdb = "timeloggerdb";

$conn = new mysqli($servername, $username, $password, $timeloggerdb);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = mysqli_query($conn, "SELECT id,lastname FROM worker WHERE name = '$name'");
while($row = mysqli_fetch_array($query)) {
    $id = $row['id'];
    $lastname = $row['lastname'];
}
//if( $start != '' && $end != '' && $minutes != '' && $lunch != '' && $extra_minutes != '' && $vacation != '' && $sick != ''){
if( $start != '' || $end != ''){ 
    
    $sql = mysqli_query($conn,"INSERT INTO worktime (user_id, date, time_begin, time_end, minutes, minutes_lunch,  minutes_extra)
    VALUES ('$user_id', '$date','$start', '$end', '$minutes', '$lunch', '$extra_minutes')");

}else{
    $sql = '';
}

$conn->close();
?>