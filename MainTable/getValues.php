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

    $html = '';
    $string = "Employee name";
    $html .= '<tr>';
    $html .= "<td class='day'>$string</td>";
    
    for( $i = 1; $i <= $last_day; $i++ ) {
        $day_num = $i;

        if( $i < 10 ) {
            $day_num = '0' . $i;
        }
        $day_name = date('D', strtotime($year . '-' . $month . '-' . $day_num));
        $html .= "<td class='day' data-day=".$year.'-' .$month.'-' .$day_num."> $day_name <br/> $day_num</td>";
    }
    $html .= "<td class='day' >Work Hours</td>";
    $html .= '</tr>';

    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "timeloggerdb";

    $conn = new mysqli($servername, $username, $password, $dbname);       
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $department = $_POST['name'];
    $query_2 = mysqli_query($conn, "SELECT id FROM department WHERE title = '$department'");
    while( $row_2 = mysqli_fetch_array($query_2)) {
        $department_id = $row_2['id'];
    }
        
    $calendar_first = $year.'-'.$month.'-01';
    //$_SESSION['calendar_first'] = $calendar_first;
    $calendar_last = $year.'-'.$month.'-'.$last_day;
    //$_SESSION['calendar_last'] = $calendar_last;
    $calender_day = $year.'-'.$month.'-' .$day_num;
    //$_SESSION['calendar_day'] = $calender_day;
     
    $sql = "SELECT w.lastname, w.name, w.dep_id, wt.* FROM worker w LEFT JOIN worktime wt ON (wt.user_id = w.id) WHERE wt.`date` >= '$calendar_first' AND wt.`date` <= '$calendar_last' AND w.dep_id = '$department_id' ORDER BY wt.user_id";
    $res = mysqli_query($conn,$sql);

    $arr = array();
    $arr_id = array();
    $arr_users = array();
    $arr_minutes = array();
    $arr_vac = array();
    $arr_sick = array();
    $arr_vac_all = array();
    $arr_sick_all = array();
    while($row_3 = mysqli_fetch_array($res)) {
        $minutes = $row_3['minutes'];
        $d = $row_3['date'];
        $sick = $row_3['is_sick'];
        $day_1 = substr($row_3['date'], -2);
        $user = $row_3['user_id'];
        $arr[$day_1][$user] = $row_3;
        if(!in_array($user, $arr_id)) {
             $arr_id[] = $user;
             $arr_users[] = $row_3;
        }

    }


    $query = mysqli_query($conn, "SELECT id AS user_id, lastname, name, dep_id FROM worker WHERE dep_id = '$department_id'");
    while($row = mysqli_fetch_array($query)){
        $user = $row['user_id'];
        if(!in_array($user, $arr_id)){
             $arr_id[] = $user;
             $arr_users[] = $row;
        }
    }
    if(!empty($_POST['vacation_start']) && !empty($_POST['vacation_end']) && !empty($_POST['user_id'])){
         $employee_id = $_POST['user_id'];
         $first_vac = $_POST['vacation_start'];
         $last_vac = $_POST['vacation_end']; 

         $date_from_vac = strtotime($first_vac);
         $date_to_vac = strtotime($last_vac);

         $sql_vac = mysqli_query($conn,"SELECT * FROM worktime WHERE `date` >= '$first_vac' AND `date` <= '$last_vac' AND user_id = '$employee_id'");
            while($row_vac = mysqli_fetch_array($sql_vac)){   
                $rd = $row_vac['date'];
                $arr_vac[] = $rd;
            }
        
         for ($i=$date_from_vac; $i<=$date_to_vac; $i+=86400) {  
            $period_vac =  date('Y-m-d',$i);
        
            $arr_vac_all[] = $period_vac;

         }

        $sql_vac = '';
       
        foreach( $arr_vac_all as $v ) {  
            if(isset($arr_vac[$v])){
                 mysqli_query( $conn, "UPDATE worktime SET is_vacation = 1 WHERE `date`='".$v."' AND user_id = ".$employee_id.";");
            }else{
                 mysqli_query( $conn, "INSERT INTO worktime (`date`, user_id, is_vacation) VALUES ('".$v."', ".$employee_id.", 1);");
            }
        }
    }
    if(!empty($_POST['sick_start']) && !empty($_POST['sick_end']) && !empty($_POST['user_id'])){
         $employee_id = $_POST['user_id'];
         $first_sick = $_POST['sick_start'];
         $last_sick = $_POST['sick_end']; 

         $date_from_sick = strtotime($first_sick);
         $date_to_sick = strtotime($last_sick);

         $sql_sick = mysqli_query($conn,"SELECT * FROM worktime WHERE `date` >= '$first_sick' AND `date` <= '$last_sick' AND user_id = '$employee_id'");
            while($row_sick = mysqli_fetch_array($sql_sick)){   
                $rd_sick = $sql_sick['date'];
                $arr_sick[] = $rd_sick;
            }
        
         for ($p=$date_from_sick; $p<=$date_to_sick; $p+=86400) {  
            $period_sick =  date('Y-m-d',$p);
        
            $arr_sick_all[] = $period_sick;

         }

        $sql_sick = '';
       
        foreach( $arr_sick_all as $val ) {  
            if(isset($arr_sick[$val])){
                 mysqli_query( $conn, "UPDATE worktime SET is_sick = 1 WHERE `date`='".$val."' AND user_id = ".$employee_id.";");
            }else{
                 mysqli_query( $conn, "INSERT INTO worktime (`date`, user_id, is_sick) VALUES ('".$val."', ".$employee_id.", 1);");
            }
        }
    }

    $count = count( $arr_id );
    for( $j = 0; $j < $count; $j++ ) {
        $dep_id = $arr_users[$j]['dep_id'];

        $sum_min = 0;

        $html .= '<tr class="clickable" data-row='.$arr_users[$j]['name'].'>';
        $html .= "<td class='employee' data-name=".$arr_users[$j]['name']." data-value=".$arr_id[$j]." data_dep=".$dep_id." style='width: 150px; height: 30px;' >" .$arr_users[$j]['name'].' '.$arr_users[$j]['lastname']."</td>";
        for( $i = 1; $i <= $last_day; $i++ ) {
            $day = $i;
            if( $day < 10 ) {
                $day = '0' . $day;
            }
            
           
            if(!empty($arr[$day][$arr_id[$j]])){
               $arr_minutes = $arr[$day][$arr_id[$j]]['minutes'];
               $sum_min += $arr[$day][$arr_id[$j]]['minutes'];
               $minutes_extra = $arr[$day][$arr_id[$j]]['minutes_extra'];
               $minutes_lunch = $arr[$day][$arr_id[$j]]['minutes_lunch'];
               if($minutes_extra == '0'){
                   $minutes_extra = NULL;
               }
               if($minutes_lunch == '0'){
                   $minutes_lunch = NULL;
               }

                if(!empty($arr[$day][$arr_id[$j]]['time_begin']) && !empty($arr[$day][$arr_id[$j]]['time_end'])){
                    $w = '-';
                }else{
                    $w = '';
                }
                if($arr[$day][$arr_id[$j]]['is_vacation'] || $arr[$day][$arr_id[$j]]['is_sick']){
                    if($arr[$day][$arr_id[$j]]['is_sick']){
                        $is_sic = '<td class="sections" bgcolor="#00aeef" data="'.$day.'"></td>';
                        $html .=  '<td class="sections" bgcolor="#00aeef" data="'.$day.'"></td>';
                    }
                    if($arr[$day][$arr_id[$j]]['is_vacation']){
                    $is_vac = '<td class="sections" bgcolor="#f26c4f" data="'.$day.'"></td>';
                    $html .= '<td class="sections" bgcolor="#f26c4f" data="'.$day.'"></td>';
                    }
                }else{
                    $is_vac = '';
                    $is_sic = '';
                    $html .= '<td class="sections" data="'.$day.'">'.$arr[$day][$arr_id[$j]]['time_begin'].$w.$arr[$day][$arr_id[$j]]['time_end'].'<br>'.$minutes_lunch.'<br>'.$minutes_extra .'</td>';
                }
            
            }else{
               $html .= '<td class="sections" data="'.$day.'">&nbsp;</td>';
            }
         }
         $sgsh = '<div>'.$sum_min.'</div>';
         $_SESSION['sum_min'] = $sum_min;
         $m = $sum_min % 60;
         $h = intdiv($sum_min, 60);
         if($h < 10){
             $h = '0'.$h;
         }
         if($m == '0'){
             $m = '00';
         }
         $hours = $h.':'. $m;
         if($hours == '0:0'){
             $hours = '';
         }   

         $html .= '<td class="workHours" data="">'.$hours.'</td>';
         $html .= '</tr>';

            
        
    }
    $conn->close();
    echo $html;

?>