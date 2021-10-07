<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link rel="stylesheet" type="text/css" href="mainstyle.css">
<script src="/Program/code/jq/jquery-3.2.1.min.js"></script>
    <meta charset="utf-8">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<title>Main table</title></head>
<body>
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<nav>
  <ul>
    <li><img src="images/sauleit.png">
    </li>
    <li><a href="#" id="active">Table edit</a></li>
      <?php
      $level = $_SESSION['AccessLevel'];
      /*if($level == 'Level 2'){*/
          echo '<li><a href="/Program/code/Registration/Registr.php">Registration</a></li>';
      
      ?>
    <li><a href="#"><?php  echo  $_SESSION['First'] ." ". $_SESSION['Last'] ?></a></li>
  </ul>
</nav>
    <div class="edittable">
        <div class="allpad">
            <div class="date">
                <div class="year">
                    <?php
                    //echo $_SESSION['AccessLevel'];
                    $date = date('Y') + 5;
                    for($i = 2016; $i < $date;$i++){
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
                      echo '<a href="#" class="btn js-link-peiod" data-month="' . $i . '">' . $value . '<a>';
                    }
                    
                    ?>
                   
                </div>
            </div>
        <div class="department">
        <?php
            $servername = "127.0.0.1";
            $username = "root";
            $password = "";
            $dbname = "timeloggerdb";

            $conn = new mysqli($servername, $username, $password, $dbname);

             if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
             } 
             
             
            $conn->close();
            
        ?>
            <form action="">
              <select name="dep" id="dep"></select>
            </form>
            <div class="edit">
                <?php
                $level ='Level 2';
                //if($level == 'Level 2'){
                    echo '<a href="#" class="ed"><p> Edit</p></a>';
                
                
                ?>
                <a href="#" class="set"><p>Save</p></a>
                <a href="#" class="set"><p>Cancele</p></a>
                <a href="#"><p>Download Excel</p></a>
            </div>
        </div>
           
        <?php
            date('d/m')
        /*$container= '<div class="container"><input id="datepicker"></div>';
            $_SESSION['container'] = $container;*/
        ?>
        <div class="scroll">
           <table class="workers" id="worker">
            </table>
           
        </div>
            <div id="datek"></div>   
        
        <script>
        var dep = ["It-department", "Support","Managment","Marketing"];     
        var sel = document.getElementById('dep');
        
        var fragment = document.createDocumentFragment();
        var month = 0;
        var year = <?php echo date('Y'); ?>;

        
        dep.forEach(function(cuisine) {
            var opt = document.createElement('option');
            opt.innerHTML = cuisine;
            opt.value = cuisine;
            fragment.appendChild(opt);
        });
        sel.appendChild(fragment);
        //function for ajax
        function getDep( month, year, vacation_start, vacation_end, user_id, sick_start, sick_end) {
          
            varname = $('select[name="dep"]').val();
            $.ajax({
                method: "post",
                url: "http://localhost/Program/code/MainTable/getValues.php",
                data: {name: varname, month: month, thisyear: year, vacation_start: vacation_start, vacation_end: vacation_end, user_id: user_id, sick_start: sick_start, sick_end: sick_end},    
                success: function(data){
                  $("#worker").html(data);
                }
            });
        }

        function setTime( datek, start, end, minutes, lunch_minutes, extra_minutes, employee, user_id) {
            $.ajax({
                method: "post",
                url: "http://localhost/Program/code/MainTable/setTime.php",
                data: {date: datek, start: start, end: end, minutes: minutes, breakfast: lunch_minutes, extra_minutes: extra_minutes, employee:  employee,  user_id: user_id},    
                success: function(data){
                  $("#datek").html(data);
                }
            });
        }
       
          
        
        $(function(){
            
            $('body').on("click", '.js-link-peiod', function(){
                month = $(this).attr('data-month');
                getDep( month, year );
                $(".js-link-peiod").removeClass('clicked');
                $(this).addClass("clicked");
            });

            $('body').on("click", '.js-link-year', function(){
                year = $(this).attr('data-year');
                getDep( month, year );
                $(".js-link-year").removeClass('clicked');
                $(this).addClass("clicked");
            });  

            $('body').on("click", '.ed', function(){
                $(".ed").hide();
            });
           //box close
           $('body').on("click", '.sections .close', function(){
               if( $(".ed").css('display') != 'none' ) {
                   return false;
               }
               
               $(this).parent().hide();
           });
           //click on Edit link 
           $('body').on("click", '.sections', function(){
              if( $(".ed").css('display') != 'none' ) {
                   return false;
              }
        
               //click td
              sect = $(this).attr('data');
              row = $('.workHours').attr('data');
              var is_box = 0;
              if( $(this).attr('data-box') != undefined ) {
                   is_box = $(this).attr('data-box');
              }
              if(sect && is_box == 0){
                  if( $(this).children('.box_new').attr('class') == undefined ) {
                  //create box where can add worked time
                     $(this).append('<div class="box_new"><h3 class="add_time">Hours</h3><br><a herf="#" class="close">X</a><p class="start">Start</p><input type="text" class="timepickerStart"><p class="end">End</p><input type="text" class="timepickerEnd"><p class="breakfast">breakfast</p><input type="text" class="timepickerBreakfast"><p class="extraTime">Extra time</p><input class="timepickerExtraTime" type="text"><br/><p class="vacation_1">Vacation</p><p class="vacation_2">Start</p><br><input class="datepicker_vacationstart" type="text"><p class="vacation_3">End</p><br><span class="er_1"></span><br><input class="datepicker_vacationend" type="text"><br><p class="sick">sick</p><p class="sick_start">start</p><input class="datepicker_sickstart" type="text"><p class="sick_end">end</p><input class="datepicker_sickend" type="text"><br><p class="freeday">Holiday</p><p class="freeday_start">start</p><input class="datepicker_freedaystart" type="text"><p class="freeday_end">end</p><input class="datepicker_freedayend" type="text"><input class="ok" type="button" value="OK"><input class="cancele" type="button" value="Cancele"></div>');
                     
                  }
                  $(this).append('<span class="span"></span>');
                  $(".workHours").append('<span class="spanTime"></span>');
                  
                  $('.box_new').hide();
                  $(this).children('.box_new').show();
                  $(this).attr('data-box', 1);
                 
               } else if( is_box == 1 ) {
                   $(this).attr('data-box', 0);
               }
               //box start/end/lunch/extra inputs with time
                $('.timepickerStart').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 15,
                    minTime: '00',
                    maxTime: '23:00',
                    defaultTime: '00',
                    startTime: '06:00',
                    dynamic: true,
                    dropdown: true,
                    scrollbar: true, 
                   /*change: function(time) {
                       //var s = $(this).timepicker().format(time);
                       alert(time.getHours() + ':' + time.getMinutes());
                       $(".timepickerEnd").timepicker('option',{'minTime': time});
                    }*/
                });
               $('.timepickerEnd').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 15,
                    minTime: '00',
                    maxTime: '23:00',
                    defaultTime: '00',
                    startTime: '06:00',
                    dynamic: true,
                    dropdown: true,
                    scrollbar: true
                });
               $('.timepickerBreakfast').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 10,
                    minTime: '00',
                    maxTime: '2:00',
                    defaultTime: '00:00',
                    startTime: '10:00',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
               $('.timepickerExtraTime').timepicker({
                    timeFormat: 'HH:mm',
                    interval: 30,
                    minTime: '00',
                    maxTime: '8:00',
                    defaultTime: '00',
                    startTime: '00:00',
                    dynamic: false,
                    dropdown: true,
                    scrollbar: true
                });
              
               $('.datepicker_vacationstart').datepicker({ dateFormat: 'dd/mm/yy' });
               $('.datepicker_vacationend').datepicker({ dateFormat: 'dd/mm/yy' });
               $('.datepicker_sickstart').datepicker({ dateFormat: 'dd/mm/yy' });
               $('.datepicker_sickend').datepicker({ dateFormat: 'dd/mm/yy' });
               $('.freeday_start').datepicker({ dateFormat: 'dd/mm/yy' });
               $('.freeday_end').datepicker({ dateFormat: 'dd/mm/yy' });
               /*var vacation_start = $( ".datepicker_vacationstart" ).val();
               var vacation_end = $( ".datepicker_vacationend" ).val();
               var vacation = vacation_start + '-' + vacation_end;
               var sick_start = $( ".datepicker_sickstart" ).val();
               var sick_end = $( ".datepicker_sickend" ).val();
               
               
               var freeday_start = $( ".datepicker_freedaystart" ).val();
               var freeday_end = $( ".datepicker_freedayend" ).val();*/
                
               /*if(freeday_start == freeday_end){
                    var today = new Date(),
                    inWeek = new Date();
                    inWeek.setDate(today.getDate()+1);
                    $( ".datepicker_freedayend" ).datepicker().datepicker("setDate", inWeek);
               }*/
              
  
             });
              
             $('body').on("click", '.sections .ok', function(){
                var start = $('input[class="timepickerStart"]').val();
                var end = $('input[class="timepickerEnd"]').val();
                var breakfast = $('input[class="timepickerBreakfast"]').val();
                var extra = $('input[class="timepickerExtraTime"]').val();
                var parent_tr = $(this).parents('tr');
                var index = $(this).parents('.sections').index();
                var datek = $('.day:eq(' + index + ')').attr('data-day');
                var z = '-';
                if(start == '00:00' && end == '00:00'){
                     start = '';
                     end = '';
                     minutes = '';
                     z = ''
                     var lunch_minutes = '';
                 }
                if(breakfast == "00:00"){
                    breakfast = "";
                    var out_breakfast = "";
                }else{
                    
                    out_breakfast = '<p>bf</p>' +  ' - <span class="work-lunch">' + breakfast + '</span>\n';
                }
                if(extra == "00:00"){
                    var out_extra = "";
                    extra = "";
                    
                }else{
                    out_extra = '<p>extra</p>' +  ' - <span class="work-extra">' + extra + '</span>\n';
                }
                var workTime_3 = '<span class="work-begin">' + start + '</span>' + z + '<span class="work-end"> ' + end + "</span>\n" + out_breakfast + out_extra;
                var time_1 = start +"-"+ end; 
                var vacation_start = $( ".datepicker_vacationstart" ).val();
                vacation_start = vacation_start.split("/").reverse().join("-");
                var vacation_end = $( ".datepicker_vacationend" ).val();
                vacation_end = vacation_end.split("/").reverse().join("-");
                var sick_start = $( ".datepicker_sickstart" ).val();
                sick_start = sick_start.split("/").reverse().join("-");
                var sick_end = $( ".datepicker_sickend" ).val();
                sick_end = sick_end.split("/").reverse().join("-");
                var first_index = $(this).parents('.sections').parent().prev().find('td[data-day="' + vacation_start + '"]').index();
                var last_index = $(this).parents('.sections').parent().prev().find('td[data-day="' + vacation_end + '"]').index();
                var first_sick = $(this).parents('.sections').parent().prev().find('td[data-day="' + sick_start + '"]').index();
                var last_sick = $(this).parents('.sections').parent().prev().find('td[data-day="' + sick_end + '"]').index();
                
                
            
                if(vacation_start != "" && vacation_end == ""){
                    $('.er_1').text('Please submit vacation end!');
                    return false;
                }else if(vacation_start == "" && vacation_end != ""){
                    $('.er_1').text('Please submit vacation start!');
                    return false;
                }
                if(vacation_start != "" && vacation_end != ""){
                     vacation = '1';
                    for( i = first_index; i <= last_index; i++ ) {
                        console.log($(this).parents('.sections').parent().find('.sections:eq(' + (i - 1) + ')'));
                        $(this).parents('.sections').parent().find('.sections:eq(' + (i - 1) + ')').css('background-color', '#f26c4f');
                    }
                }else{
                     if(vacation_start == "" && vacation_end == ""){
                        var vacation = "0";
                     }
                }
                 
                alert(sick_end);
                if(sick_start != ''){
                    var sick = sick_start;
                }
                if(sick_start == "" && sick_end != ""){
                    $('.er_1').text('Please submit sick start!');
                    return false;
                }
                if(sick_start != ""){
                    for( i = first_sick; i <= last_sick; i++ ) {
                        console.log($(this).parents('.sections').parent().find('.sections:eq(' + (i - 1) + ')'));
                        $(this).parents('.sections').parent().find('.sections:eq(' + (i - 1) + ')').css('background-color', '#00aeef');
                        sick = sick_start + '-' + sick_end;
                    }
                }else if(sick_start == "" && sick_end == ""){
                    sick = "";
                }
                $(this).parents('.sections').html(workTime_3);
                var start_actual_time  =  start.split(":")[0] * 60 * 60 + start.split(":")[1] * 60;
                var end_actual_time  =  end.split(":")[0] * 60 * 60 + end.split(":")[1] * 60;
                var extra_minutes = (extra.split(":")[0] * 60 * 60 + extra.split(":")[1] * 60)/60;
                lunch_minutes = (breakfast.split(":")[0] * 60 * 60 + breakfast.split(":")[1] * 60)/60;
                if(start < end){
                    var k = end_actual_time - start_actual_time;
                    var minutes = k / 60;
                    min = (k % 3600) /60;
                    parts =  k / 60 / 60;
                    hour = parseInt(parts);
                    formatted =  ((hour < 10)?("0" + hour):hour) + ":" + ((min < 10)?("0" + min):min);
                 }
                 
                 else if(start > end){
                    k = 24 * 60 * 60 - start_actual_time + end_actual_time;
                    minutes = k / 60;
                    min = (k % 3600) /60;
                    parts =  k / 60 / 60;
                    hour = parseInt(parts);
                    formatted =  ((hour < 10)?("0" + hour):hour) + ":" + ((min < 10)?("0" + min):min);
                    //parent_tr.find('.workHours').html('<span class="formatted">' + formatted + '</span>' + "<br>" + '<span class="extra">' + extra + '</span>');  */
                  
                 }
                  
                 var user_id = parent_tr.find('.employee').attr('data-value');
                 var employee = parent_tr.find('.employee').attr('data-name');
                 
                 getDep(  month, year, vacation_start, vacation_end, user_id, sick_start, sick_end);
                 if( start != '' || end != '' || vacation != '' || sick != ''){  
                     $('.box_new').hide();
                     setTime( datek, start, end, minutes, lunch_minutes, extra_minutes, employee, user_id, );
                 }
                 
                 
                 
            });

            /*var k =  "";
            var min = k %  60;
            var parts = k / 60 / 60;
            var hour = ( k - min )/ 60; 
            var formatted = hour.toString() + ":" + (min<10?"0":"") + min.toString();
            console.log(formatted);*/
            
               
            
        
            
           /* if(formatted < "20:10"){
             $(".formatted").css("background-color", "brown");
            }*/

            var varname = $('select[name="dep"]').val();
            getDep( month );
            $('body').on("change", 'select[name="dep"]', function(){
                getDep( month, year);
             });
           
        });
    </script>
             
        <div class="legend">
            <div><div id="blue"></div><p>Vacation</p></div>
            <div><div id="green"></div><p>Vacation</p></div>
            <div><div id="weekend"></div><p>Vacation</p></div>
            <div><div id="vacation"></div><p>Vacation</p></div>
            <div><div id="sick"></div><p>Vacation</p></div>
            <div><div id="nholiday"></div><p>Vacation</p></div>
        </div>
    </div>
    </div>
</body>
</html>
