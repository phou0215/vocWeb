<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    // date_default_timezone_set("Asia/Seoul")
    $days = array();
    $values_term = array();
    $result = null;
    $recent_result = mysqli_query($conn, "SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1");
    $recent_row = mysqli_fetch_array($recent_result);
    $recent = date($recent_row["regiDate"]);
    //날짜 칼럼 넣기
    $i = 6;
    while($i != -1){
      $the_day = date("Y-m-d", strtotime($recent."-".$i." day"));
      array_push($days, $the_day);
      $i--;
    }

    //주간
    // SELECT seq, title FROM TABLE WHERE DATE_COLUMN BETWEEN DATE_ADD(NOW(),INTERVAL -1 WEEK ) AND NOW();

    // check devicedb matching tester with user name
    //월간
    // SELECT seq, title FROM TABLE WHERE DATE_COLUMN BETWEEN DATE_ADD(NOW(),INTERVAL -1 MONTH ) AND NOW();

    $i=0;
    while($i<7){
      $result = mysqli_query($conn, "SELECT * FROM voc_tot_data WHERE regiDate = '".$days[$i]."'");
      if($result != null){
          $temp = mysqli_num_rows($result);
          array_push($values_term, $temp);
        }else{
          array_push($values_term, 0);
        }
      $i++;
    }

    //send chart json data using ajax
    $rows = array();
    $table = array();

    $table['cols'] = array(
        array('label' => 'items', 'type' => 'string'),
        array('label' => 'Count', 'type' => 'number')
    );

    $i=0;
    while($i<count($days)){
        $temp = array();
        $temp[] = array('v' => (string) $days[$i]);
        $temp[] = array('v' => (int) $values_term[$i]);
        $rows[] = array('c' => $temp);
        $i++;
    }

    $table['rows'] = $rows;
    $jsonTable = json_encode($table,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    //close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
