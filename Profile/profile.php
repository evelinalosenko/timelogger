
<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<script src="/Program/code/jq/jquery-3.2.1.min.js"></script>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style_profile.css">
</head>
<body> 

<?php
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "timeloggerdb";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        

        

              
        if(!empty($_POST['email']) && !empty($_POST['password'])){
            //$email = mysqli_real_escape_string($conn, $email);
            //$pass = mysqli_real_escape_string($conn, $pass);
            $email = $_POST['email'];
            $pass = $_POST['password'];
            
            $for_hash = mysqli_query($conn, "SELECT  * FROM worker  WHERE Email = '$email'");
            while($row_hash = mysqli_fetch_array($for_hash)){
                $id = $row_hash['id'];
                $_SESSION['id'] = $id;
                $hash_password = $row_hash['password'];
                $lastname = $row_hash['lastname'];
                $access = $row_hash['access_id'];
                $_SESSION['AccessLevel'] = $access;
                $name = $row_hash['name'];
                $_SESSION['First'] = $name;
                $_SESSION['Last'] = $lastname;
                $db_email = $row_hash['email'];
                //$minutes = $row_hash['minutes'];
               
            }
            /*function convertToHoursMins($time, $format = '%02d:%02d') {
                if ($time < 1) {
                    return;
                }
                $hours = floor($time / 60);
                $minutes = ($time % 60);
                 //sprintf($format, $hours, $minutes);
            }*/
             if(password_verify($pass, $hash_password)){
                 if($db_email == $email){
                    ?>
                        <nav>
                          <ul>
                            <li><img src="images/sauleit.png">
                            </li>
                            <li><a href="/Program/code/MainTable/index.php" id="active">Table edit</a></li>
                            <?php
                               /*if($access == 'Level 2'){*/
                                   echo '<li><a href="/Program/code/Registration/Registr.php">Registration</a></li>';
                               
                             ?>
                                <li>
                                    <div class="dropdown">
                                          <button class="dropbtn"><?php  echo  $name ." ". $lastname ?></button>
                                          <div class="dropdown-content">
                                            <a href="/Program/code/logIn/logout.php">Log-out</a>
                                            
                                          </div>
                                    </div>
                              </li>
                          </ul>
                        </nav>
                         <div class="workhours">
                            <img alt="Clock" src="images/clock_w.svg">
                            <div class="worktext">
                                <?php
                                    $servername = "127.0.0.1";
                                    $username = "root";
                                    $password = "";
                                    $dbname = "timeloggerdb";

                                    $conn = new mysqli($servername, $username, $password, $dbname);       
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    $calendar_first =  date("Y-m-01");
                                    $calendar_last = date("Y-m-t", strtotime($calendar_first));
                                    
                                    
                                    $sql = mysqli_query($conn, "SELECT w.lastname, w.name, w.dep_id, wt.* FROM worker w LEFT JOIN worktime wt ON (wt.user_id = w.id) WHERE wt.`date` >=                             '$calendar_first' AND wt.`date` <= '$calendar_last' AND user_id = '$id' ORDER BY wt.user_id");
                                    $min = 0;
                                    while($row = mysqli_fetch_array($sql)){
                                        $min += $row['minutes'];
                                    } 
                                    $m = $min % 60;
                                    $h = intdiv($min, 60);
                                    if($h < 10){
                                        $h = '0'.$h;
                                    }
                                    if($m == '0'){
                                        $m = '00';
                                    }
                                    $hours = $h.':'. $m;
                                    if($hours == '0:0'){
                                         $hours = '00:00';
                                    } 
                                    echo "<h1>".$hours."</h1>";
                                    echo "<p>Work Hours<p>";
  
                                ?>
                                
                            </div>
                        </div>
                        <div class="vacationhours">
                            <img alt="Clock" src="images/chair_w.svg">
                            <div class="worktext">
                                  <p>Vacation</p>
                            </div>
                        </div>
                    <div class="multipletables">
                    <div class="edittable">
                            <div class="notifications">
                                <?php
                                      $current_date = date("d");
                                      if($current_date < 10){
                                           $current_date = $current_date[1];
                                      }
                                      $current_week_day = date("l");
                                      echo '<h1 class="today_nr">'.$current_date.'</h1>';
                                      echo '<p class="today_word">'.$current_week_day.'</p>';
                                ?>
                                <div class="current_events">
                                    <p>Current events</p>
                                    <ul>
                                      <li>Minu Nimi's birthday</li>
                                      <li>National holiday</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="allpad">
                                <div class="date_1">
                                    <div class="year">
                                         <?php
                                        //echo $_SESSION['AccessLevel'];
                                            $date = date('Y') + 5;
                                            for($i = 2016; $i < $date;$i++){
                                                /*if($i == date('Y')){
                                                     echo '<a href="#" class="btn js-link-year" data-year="' . $i . '" id="clicked">' . $i . '<a>';
                                                    // echo '<a href="#" class="btn js-link-year" id="clicked" data-year="' . $i . '">' . $i . '<a>';
                                                }*/
                                                echo '<a href="#" class="btn js-link-year" data-year="' . $i . '">' . $i . '<a>';
                                             }
                                         ?>
                                    </div>
                                    <div class="months">
                                         <?php
                                              $monthName = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                                               $i = 0;
                                               foreach ($monthName as $value) {
                                                   $i++;
                                               $sg = date("M");
                                                  /*if($value == $sg){
                                                      echo '<a href="#" class="btn js-link-peiod" data-month="' . $i . '"><font color="black">'. $value .'</font><a>';
                                                  }*/
                                                  echo '<a href="#" class="btn js-link-peiod" data-month="' . $i . '">' . $value . '<a>';
                                                }

                                           ?>
                                    </div>
                                    <div class="wdays">
                                         <?php
                                                $weekName = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
                                                $i = 0;
                                                foreach ($weekName as $value) {
                                                    $i++;
                                                  echo '<a href="#" data-week="' . $value . '">' . $value . '<a>';
                                                }
                                          ?>
                                    </div>
                                    
                                    <table class="worker_"></table>    
                                </div>
                            </div>
                            
                    
                    <script>
                     var month = 0;
                     var year = <?php echo date('Y'); ?>;
                     function getDep( month, year) {
                        
                        $.ajax({
                            method: "post",
                            url: "http://localhost/Program/code/Profile/values.php",
                            data: { month: month, thisyear: year},    
                            success: function(data){
                              $(".worker_").html(data);
                            }
                        });
                     }
                     $(function(){
                        $('body').on("click", '.js-link-year', function(){
                            year = $(this).attr('data-year');
                            getDep( month,year ); 
                            $(".js-link-year").removeClass('clicked');
                            $(this).addClass("clicked");
                        });
                       
                        $('body').on("click", '.js-link-peiod', function(){
                            month = $(this).attr('data-month');
                            getDep( month, year );
                            $(".js-link-peiod").removeClass('clicked');
                            $(this).addClass("clicked");
                        });
                        getDep( month,year )
                     });
                    </script>
                    </div>


                    
                    </div>
                    

    
                    <?php
                    
                 }else{
                      header('Location: /Program/code/login/login.php?log-in=false');
                 }
             }else{
                 header('Location: /Program/code/login/login.php?log-in=false');
             }
        }else{
            header('Location: /Program/code/login/login.php?log-in=false');
        }
        $conn->close();  
    ?>
    <script>
        
    </script>
    </body>
</html>