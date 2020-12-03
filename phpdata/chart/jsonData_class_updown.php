<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_date = array();
    $index_date_updown = array();

    $index_weeks = array();
    $index_weeks_updown = array();
    $index_add = array();
    $index_add_updown = array();
    $index_class = array();
    $base_order = 0;

    //each model static values of 4 weeks
    $values_term = array();
    $values_term_updown = array();

    $result = null;
    $modelsString = "";
    $returnData = array();
    $model = $_POST['model'];
    $date = $_POST['date'];
    $days = $_POST['days'];


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

    //focus model 명 가져오기
    if($device_flag == '1'){
      $result = mysqli_query($conn, 'SELECT model FROM voc_models2 WHERE focusOn = "1"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString = implode(',', $index_focus);
    }else{
      $result = mysqli_query($conn, 'SELECT model FROM voc_models WHERE focusOn = "1"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString = implode(',', $index_focus);
    }

    //최근 데이터 날짜 가져오기
    $result = mysqli_query($conn, 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1');
    $row = mysqli_fetch_assoc($result);
    $base_date = date($date);
    $index_date = array();
    $weeks = array('일','월','화','수','목','금','토');

    //전 3일
    $i = (int) $days;
    while($i != 0){
      $temp_date = date('Y-m-d', strtotime($base_date.'- '.$i.' days'));
      $thisWeek = date('w',strtotime($temp_date));
      array_push($index_date, $temp_date);
      array_push($index_date_updown, $temp_date);
      array_push($index_weeks, $weeks[(int) $thisWeek]);
      array_push($index_weeks_updown, $weeks[(int) $thisWeek]);
      $i--;
    }

    //기준일
    array_push($index_date, $base_date);
    $base_order = count($index_date)-1;
    $thisWeek = date('w',strtotime($base_date));
    array_push($index_weeks, $weeks[(int) $thisWeek]);


    //후 3일
    $i = 1;
    while($i <= (int) $days){
      $temp_date = date('Y-m-d', strtotime($base_date.'+ '.$i.' days'));
      $thisWeek = date('w',strtotime($temp_date));
      array_push($index_date, $temp_date);
      array_push($index_date_updown, $temp_date);
      array_push($index_weeks, $weeks[(int) $thisWeek]);
      array_push($index_weeks_updown, $weeks[(int) $thisWeek]);
      $i++;
    }

    //카테고리 구분 값 목록 가져오기
    $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag = "1"');
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_class, $row['category']);
    }

    //각 모델별  calss 데이터 수치 $values_term에 넣기(Total hole, Total focus 구분)
    $i=0;
    if($model == 'total_hole'){
      while($i < count($index_class)){
        $j = 0;
        $temp_array = array();
        while($j < count($index_date)){
          $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate="'.$index_date[$j].'" AND class1 = "'.$index_class[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
        array_push($values_term, $temp_array);
        $i++;
      }
    }else if($model == 'total_focus'){
      while($i < count($index_class)){
        $j = 0;
        $temp_array = array();
        while($j < count($index_date)){
          $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate="'.$index_date[$j].'" AND '.$model_sort.' IN ('.$modelsString.') AND class1 = "'.$index_class[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
        array_push($values_term, $temp_array);
        $i++;
      }
    }else{
      while($i < count($index_class)){
        $j = 0;
        $temp_array = array();
        while($j < count($index_date)){
          $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate="'.$index_date[$j].'" AND '.$model_sort.' = "'.$model.'" AND class1 = "'.$index_class[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
        array_push($values_term, $temp_array);
        $i++;
      }
    }


    //index_date add weeks
    for($i=0; $i<count($index_date); $i++){
      if($i == $base_order){
        $temp_string = $index_date[$i].'(기준일)';
        array_push($index_add, $temp_string);
      }else{
        $temp_string = $index_date[$i].'('.$index_weeks[$i].')';
        array_push($index_add, $temp_string);
      }
    }

    //index_date_updown add weeks
    for($i=0; $i<count($index_date_updown); $i++){
      $temp_string = $index_date_updown[$i].'('.$index_weeks_updown[$i].')';
      array_push($index_add_updown, $temp_string);
    }

    /////////////////////////////////////////증감차트 부분/////////////////////////////////////
    //날짜별 카테고리 기준데이터 추출
    $base_data = array();
    for($i=0;$i<count($index_class);$i++){
      $temp_data = $values_term[$i][$base_order];
      array_push($base_data, $temp_data);
    }
    //날짜별 카테고리 증감데이터 계산
    for($i=0;$i<count($index_class);$i++){
      $temp_list = array();
      $value_list = $values_term[$i];
      for($j=0;$j<count($value_list);$j++){
        $result_value = (int) $value_list[$j] - (int) $base_data[$i];
        array_push($temp_list, (string) $result_value);
      }
      array_push($values_term_updown, $temp_list);
    }

    $returnData['index'] = $index_add;
    $returnData['indexUpDown'] = $index_add_updown;
    $returnData['class'] =  $index_class;
    $returnData['values'] = $values_term;
    $returnData['valuesUpDown'] = $values_term_updown;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
