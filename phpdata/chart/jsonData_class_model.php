<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));
    $select_model = $_POST['models'];

    $index_class = array();
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();
    $values_term = array();
    $values_rate = array();
    $values_subs = array();

    $weeks = array('일','월','화','수','목','금','토');
    $result = null;
    $returnData = array();

    $device_flag;
    $model_sort;
    //Device Flag check
    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);
    $device_flag = $row['deviceFlag'];

    //select model or model2
    if($device_flag == '1'){
      $model_sort = "model2";
    }else{
      $model_sort = "model";
    }

    //시작일에서 종료일까지 일수 구하기
    $days = (intval((strtotime($endDate)-strtotime($startDate)) / 86400));


    //일 수를 기준으로 날짜 값을 index_terms 배열에 넣는다
    array_push($index_terms, $startDate);
    array_push($index_weeks, $weeks[(int) date('w',strtotime($startDate))]);

    $i = 1;
    while($i <= $days){
      $temp_day = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
      $thisWeek = date('w',strtotime($temp_day));
      array_push($index_terms, $temp_day);
      array_push($index_weeks, $weeks[(int) $thisWeek]);
      $i++;
    }

    //모델 선택 달의 데이터 수치 $values_term에 넣기
    $i = 0;
    while($i < count($index_terms)){
      $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE '.$model_sort.' = "'.$select_model.'" AND regiDate = "'.$index_terms[$i].'"';
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $count_num = $row['cnt'];
      array_push($values_term, $count_num);
      $i++;
    }

    $i = 0;
    while($i < count($values_term)){
      $total_voc = (int)$values_term[$i];
      $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE '.$model_sort.' = "'.$select_model.'" AND subsDate = "'.$index_terms[$i].'"';
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $count_num = (int)$row['cnt'];
      $rate = 0.0;
      if ($count_num != 0 && $total_voc != 0){
        $rate = ($total_voc / $count_num)*1000;
        $rate = round($rate, 4);
      }
      array_push($values_subs, (string)$count_num);
      array_push($values_rate, (string)$rate);
      $i++;
    }

    // //model_name trader_trim
    // $name = substr($select_model[$i], 0, 15);
    // $index_model_trim = $name;


    //index_date add weeks
    for($i=0; $i<count($index_terms); $i++){
      $temp_string = $index_terms[$i].'('.$index_weeks[$i].')';
      array_push($index_add, $temp_string);
    }

    //send chart json data using ajax
    $returnData['index'] = $index_add;
    $returnData['models'] = $select_model;
    $returnData['values'] = $values_term;
    $returnData['rate'] = $values_rate;
    $returnData['subData'] = $values_subs;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
