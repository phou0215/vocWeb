<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    // Function increase decrease
    function get_compared($pre , $cur){

      if($pre == 0){
        return 0;
      }
      else{
        $temp = round((($cur-$pre) / $pre)*100, 2);
        return $temp;
      }
    }
    // Function get Average
    function get_avg($data, $size){
      if($data == 0 || $size == 0){
        return 0;
      }
      else{
        $temp = round(($data / $size), 4);
        return $temp;
      }
    }
    // php code start
    $index_class = array();
    $index_model_trim = array();
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();
    $index_ex_holiday = array();
    $index_three_week = array();


    $modelsString = "";
    $modelsString_focus = "";

    //날짜별 또는 모델별 2차원 배열
    $values = array();

    //handling POST Parameter
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));
    $endWeek = "";

    //(기본 total, array)
    $index_models = $_POST['models'];
    $type = $_POST['type'];
    $model_type = $_POST['modelType'];
    $model_sort = "";

    #select voc model column
    if($model_type == "voc_models"){
      $model_sort = "model";
    }else{
      $model_sort = "model2";
    }

    $weeks = array('일','월','화','수','목','금','토');
    $result = null;
    $returnData = array();

    //디바이스 목록 가져오기
    if(in_array("total_focus", $index_models)){
      $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE cellType = "5G" AND focusOn = "1"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString_focus = implode(',', $index_focus);
    }

     if(in_array("total_hole", $index_models)){
      $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE cellType = "5G" AND flag = "1"');
      $index_total = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_total, '"'.$row['model'].'"');
      }
      $modelsString = implode(',', $index_total);
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
    // 최근일 요일 값 import
    $endWeek = $index_weeks[count($index_weeks)-1];
    // array_search(2020-02-23, $a);
    // 공휴일 제외 날짜와 3주전 날짜 값 추출
    $i = 0;
    while($i< count($index_weeks)){
      // $temp_day = $index_terms[$i];
      //holiday 제외 날짜 배열 Add
      if($index_weeks[$i] != "토" && $index_weeks[$i] != "일"){
        array_push($index_ex_holiday, $i);
      }
      //3week 동일 날짜 배열 Add
      if($index_weeks[$i] == $endWeek && $i != count($index_terms)-1){
        array_push($index_three_week, $i);
      }
      $i++;
    }
    // print_r($index_ex_holiday);
    // print_r($index_three_week);
    //holiday 제외 날짜 3weeks 동일 날짜 make strings
    // $exHolidayString = implode(',', $index_ex_holiday);
    // $threeWeekString = implode(',', $index_three_week);

    //generate part 1 table sql query
    $sql_part_1 = 'FROM '.$type;

    ////////////////////////////////////////////////////VOC TOTAL DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();
      $temp_subs_array = array();
      $temp_rate_array = array();
      $temp_avg_array = array();
      $temp_per_array = array();

      if($index_models[$i] == 'total_focus'){

        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];

          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $subs_num = (int)$row['cnt'];
          $temp_rate = 0.0;

          # rate calculate
          if ($count_num != 0 && $subs_num != 0){
            $temp_rate = ($count_num / $subs_num)*1000;
            $temp_rate = round($temp_rate, 4);
          }

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          $j++;
        }

        //전주 당일 데이터
        array_push($temp_avg_array, $temp_rate_array[count($temp_rate_array)-8]);
        $temp_per = get_compared($temp_rate_array[count($temp_rate_array)-8], $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

        // holiday 제외 평일 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_ex_holiday);
        while($j < count($index_ex_holiday)){
          if($temp_rate_array[$index_ex_holiday[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_ex_holiday[$j]];
          }
          $j++;
        }
        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

        // 3week 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_three_week);
        while($j < count($index_three_week)){
          if($temp_rate_array[$index_three_week[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_three_week[$j]];
          }
          $j++;
        }

        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

      }else if($index_models[$i] == 'total_hole'){
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];

          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $subs_num = (int)$row['cnt'];
          $temp_rate = 0.0;

          # rate calculate
          if ($count_num != 0 && $subs_num != 0){
            $temp_rate = ($count_num / $subs_num)*1000;
            $temp_rate = round($temp_rate, 4);
          }

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          $j++;
        }

        //전주 당일 데이터
        array_push($temp_avg_array, $temp_rate_array[count($temp_rate_array)-8]);
        $temp_per = get_compared($temp_rate_array[count($temp_rate_array)-8], $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

        // holiday 제외 평일 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_ex_holiday);
        while($j < count($index_ex_holiday)){
          if($temp_rate_array[$index_ex_holiday[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_ex_holiday[$j]];
          }
          $j++;
        }
        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

        // 3week 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_three_week);
        while($j < count($index_three_week)){
          if($temp_rate_array[$index_three_week[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_three_week[$j]];
          }
          $j++;
        }
        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

      }else{
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' = "'.$index_models[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];
          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$model_sort.'="'.$index_models[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $subs_num = (int)$row['cnt'];
          $temp_rate = 0.0;

          # rate calculate
          if ($count_num != 0 && $subs_num != 0){
            $temp_rate = ($count_num / $subs_num)*1000;
            $temp_rate = round($temp_rate, 4);
          }

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          $j++;
        }

        //전주 당일 데이터
        array_push($temp_avg_array, $temp_rate_array[count($temp_rate_array)-8]);
        $temp_per = get_compared($temp_rate_array[count($temp_rate_array)-8], $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);

        // holiday 제외 평일 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_ex_holiday);
        while($j < count($index_ex_holiday)){
          if($temp_rate_array[$index_ex_holiday[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_ex_holiday[$j]];
          }
          $j++;
        }
        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);
        // 3week 평균
        $j=0;
        $temp_avg = 0;
        $temp_data = 0;
        $temp_size = count($index_three_week);
        while($j < count($index_three_week)){
          if($temp_rate_array[$index_three_week[$j]] == 0){
            $temp_size = $temp_size - 1;
          }else{
            $temp_data = $temp_data + $temp_rate_array[$index_three_week[$j]];
          }
          $j++;
        }
        $temp_avg = get_avg($temp_data, $temp_size);
        array_push($temp_avg_array, $temp_avg);
        $temp_per = get_compared($temp_avg, $temp_rate_array[count($temp_rate_array)-1]);
        array_push($temp_per_array, $temp_per);
      }
      array_push($values, [$temp_array, $temp_subs_array, $temp_rate_array, $temp_avg_array, $temp_per_array]);
      $i++;
    }

    //model_name trader_trim
    $i = 0;
    while($i<count($index_models)){
      $name = "";
      if($index_models[$i] == "total_hole"){
        $name = "단말전체";
      }else if($index_models[$i] == "total_focus"){
        $name = "관심전체";
      }else{
        $name = substr($index_models[$i], 0, 10);
      }
      array_push($index_model_trim, $name);
      $i++;
    }

    //index_date add weeks
    for($i=0; $i<count($index_terms); $i++){
      $temp_string = $index_terms[$i].'('.$index_weeks[$i].')';
      array_push($index_add, $temp_string);
    }

    //send chart json data using ajax
    $returnData['index'] = $index_add;
    $returnData['models'] =  $index_model_trim;
    $returnData['values'] = $values;
    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
