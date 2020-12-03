<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_model = array();
    $index_model_trim = array();
    $index_start_date = array();
    $index_end_date = array();

    //each model static values of 4 weeks
    $values_term = array();

    $result = null;
    $returnData = array();

    //최근 데이터 날짜 가져오기
    $result = mysqli_query($conn, 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1');
    $recent_row = mysqli_fetch_assoc($result);
    $recent = date($recent_row['regiDate']);

    //관심등록 디바이스 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM voc_devices WHERE flag = "1"');
    while($row_models = mysqli_fetch_assoc($result)){
      array_push($index_model, $row_models['model']);
    }

    //최근 데이터 날짜에서 4주간 기간을 start/end date로 나누어 배열에 넣는다
    $i = 4;
    while($i != 0){
      $the_start_day = date('Y-m-d', strtotime($recent.'-'.(7*$i).' days'));
      $the_end_day = date('Y-m-d', strtotime($recent.'-'.(7*($i-1)).' days'));
      array_push($index_start_date, $the_start_day);
      array_push($index_end_date, $the_end_day);
      $i--;
    }

    //각 모델별 4주간 데이터 수치 $values_term에 넣기
    $i=0;
    while($i < 4){
      $j = 0;
      $temp_array = array();
      while($j < count($index_model)){
        $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE (model2 = "'.$index_model[$j].'") AND (regiDate >= "'.$index_start_date[$i].'" AND regiDate <= "'.$index_end_date[$i].'")';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $count_num = $row['cnt'];
        array_push($temp_array, $count_num);
        $j++;
      }
      array_push($values_term, $temp_array);
      $i++;
    }

    //model_name trader_trim
    $i = 0;
    while($i<count($index_model)){
      $name = substr($index_model[$i], 0, 9);
      array_push($index_model_trim, $name);
      $i++;
    }
    //send chart json data using ajax
    $returnData['index'] = $index_model_trim;
    $returnData['values'] = $values_term;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
