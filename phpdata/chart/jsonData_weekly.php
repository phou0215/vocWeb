<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $days_1 = array();
    $days_2 = array();
    $days_3 = array();
    $days_4 = array();
    $index_week = array();

    $values_term_1 = array();
    $values_term_2 = array();
    $values_term_3 = array();
    $values_term_4 = array();
    $result = null;
    $returnData = array();

    //최근 데이터 날짜 요일 계산
    $recent_result = mysqli_query($conn, 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1');
    $recent_row = mysqli_fetch_assoc($recent_result);
    $recent = date($recent_row['regiDate']);

    //index 요일 계산(오늘부터 7일 전 요일 자동 계산)
    $today = date('Y-m-d');
    $weeks = array('일','월','화','수','목','금','토');

    $thisWeek = date('w',strtotime($recent));

    $i = 1;
    while($i<8){
      $temp = (int)$thisWeek + $i;
      if($temp > 6){
        $temp = $temp-7;
      }
      array_push($index_week, $weeks[$temp]);
      $i++;
    }
    //등록된 최신 날짜 해당 주 넣기
    $i = 6;
    while($i != -1){
      $the_day = date('Y-m-d', strtotime($recent.'-'.$i.' day'));
      array_push($days_1, $the_day);
      $i--;
    }
    //2주전 날짜 넣기
    $i = 13;
    while($i != 6){
      $the_day = date('Y-m-d', strtotime($recent.'-'.$i.' day'));
      array_push($days_2, $the_day);
      $i--;
    }
    //3주전 날짜 넣기
    $i = 20;
    while($i != 13){
      $the_day = date('Y-m-d', strtotime($recent.'-'.$i.' day'));
      array_push($days_3, $the_day);
      $i--;
    }
    //4주전 날짜 넣기
    $i = 27;
    while($i != 20){
      $the_day = date('Y-m-d', strtotime($recent.'-'.$i.' day'));
      array_push($days_4, $the_day);
      $i--;
    }
    //주간
    // SELECT seq, title FROM TABLE WHERE DATE_COLUMN BETWEEN DATE_ADD(NOW(),INTERVAL -1 WEEK ) AND NOW();

    // check devicedb matching tester with user name
    //월간
    // SELECT seq, title FROM TABLE WHERE DATE_COLUMN BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND NOW();

    //1주전 날짜 Data select
    $i=0;
    while($i<7){
      $result = mysqli_query($conn, 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate = "'.$days_1[$i].'"');
      $row = mysqli_fetch_assoc($result);
      $count_num = $row['cnt'];
      array_push($values_term_1, $count_num);
      $i++;
    }

    //2주전 날짜 Data select
    $i=0;
    while($i<7){
      $result = mysqli_query($conn, 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate = "'.$days_2[$i].'"');
      $row = mysqli_fetch_assoc($result);
      $count_num = $row['cnt'];
      array_push($values_term_2, $count_num);
      $i++;
    }
    //3주전 날짜 Data select
    $i=0;
    while($i<7){
      $result = mysqli_query($conn, 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate = "'.$days_3[$i].'"');
      $row = mysqli_fetch_assoc($result);
      $count_num = $row['cnt'];
      array_push($values_term_3, $count_num);
      $i++;
    }
    //4주전 날짜 Data select
    $i=0;
    while($i<7){
      $result = mysqli_query($conn, 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate = "'.$days_4[$i].'"');
      $row = mysqli_fetch_assoc($result);
      $count_num = $row['cnt'];
      array_push($values_term_4, $count_num);
      $i++;
    }
    //send chart json data using ajax
    $returnData['index'] = $index_week;
    $returnData['week1'] = $values_term_1;
    $returnData['week2'] = $values_term_2;
    $returnData['week3'] = $values_term_3;
    $returnData['week4'] = $values_term_4;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
