<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_class = array();
    $index_terms = array();
    $index_weeks = array();
    // $index_manus_trim = array();
    $index_add = array();

    //each model static values of 4 weeks
    $values_term = array();
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));
    $index_manus = $_POST['manus'];
    $index_manus_trim = [];
    $weeks = array('일','월','화','수','목','금','토');
    $result = null;
    $returnData = array();

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

    //각 모델 선택 달의 데이터 수치 $values_term에 넣기
    $i=0;
    while($i < count($index_manus)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_terms)){
        $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE manu = "'.$index_manus[$i].'" AND regiDate = "'.$index_terms[$j].'"';
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
    while($i<count($index_manus)){
      $name = substr($index_manus[$i], 0, 25);
      array_push($index_manus_trim, $name);
      $i++;
    }

    //index_date add weeks
    for($i=0; $i<count($index_terms); $i++){
      $temp_string = $index_terms[$i].'('.$index_weeks[$i].')';
      array_push($index_add, $temp_string);
    }

    //send chart json data using ajax
    $returnData['index'] = $index_add;
    $returnData['manus'] =  $index_manus_trim;
    $returnData['values'] = $values_term;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
