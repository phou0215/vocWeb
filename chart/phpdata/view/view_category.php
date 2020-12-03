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
    $index_model_trim = array();
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();

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
    $class = $_POST['selectClass'];

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
      $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE focusOn = "1" AND cellType = "'.$type.'"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString_focus = implode(',', $index_focus);
    }

     if(in_array("total_hole", $index_models)){
      $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE flag = "1" AND cellType = "'.$type.'"');
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


    ////////////////////////////////////////////////////VOC TOTAL DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();

      if($index_models[$i] == 'total_focus'){

        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString_focus.') AND class1="'.$class.'" ';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }else if($index_models[$i] == 'total_hole'){
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.') AND class1="'.$class.'" ';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }else{
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' = "'.$index_models[$i].'" AND class1="'.$class.'" ';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }
      array_push($values, $temp_array);
      $i++;
    }

    //model_name trader_trim
    $i = 0;
    while($i<count($index_models)){
      $name = "";
      if($type == "5G"){
        if($index_models[$i] == "total_hole"){
          $name = "5G전체";
        }else if($index_models[$i] == "total_focus"){
          $name = "5G관심";
        }else{
          $name = substr($index_models[$i], 0, 10);
        }
      }else{
        if($index_models[$i] == "total_hole"){
          $name = "LTE전체";
        }else if($index_models[$i] == "total_focus"){
          $name = "LTE관심";
        }else{
          $name = substr($index_models[$i], 0, 10);
        }
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
