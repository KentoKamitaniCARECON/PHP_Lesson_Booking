<?php 
  //in out日のエスケープ＆日付の差を確認
  if(!empty($_POST['check_in'])&&!empty($_POST['check_out'])){
    $date1=htmlspecialchars($_POST['check_in'],ENT_QUOTES,'UTF-8');
    $date2=htmlspecialchars($_POST['check_out'],ENT_QUOTES,'UTF-8');
        $day1 = new DateTime($date1);
        $day2 = new DateTime($date2);
        $interval = $day1->diff($day2);
        $date3= $interval->format('%a');
  }

  //予約対象日
  $date4 = new DateTime();
  $datein=$date4->modify('+1 day')->format('Y-m-d');
  $date5 = new DateTime();
  $dateout=$date5->modify('+2 day')->format('Y-m-d'); 
  $date6 = new DateTime();
  $datein_limit=$date6->modify('+2 month')->format('Y-m-d'); 
  $date7 = new DateTime() ;
  $dateout_limit=$date7->modify('+2month +1 day')->format('Y-m-d');

  //cxl　変更は5日前  
  $date8 = new DateTime();
  $date_set=$date8->modify('+5 day')->format('Y-m-d');

  //最高100才
  $date9 = new DateTime() ;
  $set_birth=$date9->modify('-100 year')->format('Y-m-d');

  //前後5年の範囲
  $date10 = new DateTime() ;
  $res_past=$date10->modify('-5 year')->format('Y-m-d');
  $date11 = new DateTime() ;
  $res_pre=$date11->modify('+3 month')->format('Y-m-d');

?>