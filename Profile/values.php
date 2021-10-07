
<?php
session_start();
?>
<?php 
    $year = date('Y');
    $next = $year + 5;
    if( !empty( $_POST['thisyear']) && is_numeric( $_POST['thisyear'] ) && $_POST['thisyear'] >= 2016 && $_POST['thisyear'] <= $next) {
        $year = $_POST['thisyear'];
    }

    if(!empty( $_POST['month'] ) && is_numeric( $_POST['month'] ) && $_POST['month'] >= 1 && $_POST['month'] <= 12 ) {
        $month = $_POST['month'];
        if( $month < 10 ) {
            $month = '0' . $month;
        }

        $seconds = strtotime($year . '-' . $month . '-01');
    } else {
        $date = date("Y-m-01");
        $seconds = strtotime($date);
        $month = date('m', strtotime($date));
    }
  
    $last_day = date('t', $seconds);
    $day_count = 1;
    $num = 0;
    for($i = 0; $i < 7; $i++){
        $dayofweek = date('w', mktime(0, 0, 0, $month, $day_count, $year));
        $dayofweek = $dayofweek - 1;
        if($dayofweek == -1){
            $dayofweek = 6;
        } 

        if($dayofweek == $i) {
            $week[$num][$i] = $day_count;
            $day_count++;
        }else{
            $week[$num][$i] = "";
        }
    }

    while(true){
        $num++;
        for($i = 0; $i < 7; $i++){
            $week[$num][$i] = $day_count;
            $day_count++;
            if($day_count > $last_day){
                break;
            } 
        }
        if($day_count > $last_day){
            break;
        } 

    }
    
    


    $html = ''; 
   
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "timeloggerdb";

    $conn = new mysqli($servername, $username, $password, $dbname);       
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $calendar_last = date("Y-m-t",$seconds);
    $calendar_first = date("Y-m-d", $seconds);
    $id = $_SESSION['id'];
    $sql = mysqli_query($conn, "SELECT w.lastname, w.name, w.dep_id, wt.* FROM worker w LEFT JOIN worktime wt ON (wt.user_id = w.id) WHERE wt.`date` >=                                                             '$calendar_first' AND wt.`date` <= '$calendar_last' AND user_id = '$id' ORDER BY wt.user_id");
    $arr = array();
    while($row = mysqli_fetch_array($sql)){
        $time = $row['time_begin']; 
        $day_1 = substr($row['date'], -2);
        if($day_1 < 10){
            $day_1 = substr($row['date'], - 1);
        }
        $arr[$day_1] = $row;
        //var_dump($arr);exit;
       // $arr[$day_1] = $row;
        
    }
    
    for($i = 0; $i < count($week); $i++){
        $html .= "<tr class='calendar'>";

        for($j = 0; $j < 7; $j++){

            if(!empty($week[$i][$j])){
                if($j != 5 || $j != 6){
                    $today = date('d');
                    if($today < 10){
                        $today = $today[1];
                    }
                    $s = '#e6e6e6';
                    if($week[$i][$j] == $today){
                         $s = "#00aeef";
                    }
                    if(!empty($arr[$week[$i][$j]]['time_begin'])){
                        $html .= '<td class="days"  bgcolor="#e6e6e6" style=" border: 2px solid '.$s.';" >'.$week[$i][$j].'<div class="day_hours" data ='.$week[$i][$j].'>'.$arr[$week[$i][$j]]['time_begin'].'-'.$arr[$week[$i][$j]]['time_end'].'</div>'.'</td>';
                        //var_dump($arr[$week[$i][$j]]['time_begin']);exit;
                    }else{
                        $html .= '<td class="days"  bgcolor="#e6e6e6" style=" border: 2px solid '.$s.';" >'.$week[$i][$j].'</td>';
                    }
                    //var_dump($arr[$week[$i][$j]]['time_begin']);exit;
                    
                } 
            }else{
                /*$prev_month = date('d', strtotime(date($seconds).'LAST DAY OF PREVIOUS MONTH'));
                if($j = 7){
                    for($m = 1; $m <= $i; $m++ ){
                    $html .= '<td class="days">'.$m.'</td>';
                }
                */
                $html .= "<td class='days'  bgcolor='#e6e6e6' '>&nbsp;</td>";


            } 

        }

    $html .= "</tr>";
    } 
   
    echo $html;

?>