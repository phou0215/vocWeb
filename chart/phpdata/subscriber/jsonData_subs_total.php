<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_class = array();
    $index_model_trim = array();
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();
    $modelsString = "";
    $modelsString_focus = "";

    $index_manageCo = ['강남','강북','경인','광주','대구','대전','부산','원주','전주','제주','청주'];
    $index_network = ['1X', '2G', 'LTE', 'WCDMA', '5G'];

    //날짜별 또는 모델별 2차원 배열
    $values_term = array();
    $values_class =array();
    $values_manageCo = array();
    $values_network = array();


    $where_type = false;
    $where_count = 0;

    //handling POST Parameter
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    //(기본 total, array)
    $index_models = $_POST['models'];
    $type = $_POST['type'];
    $keyword1 = $_POST['keyword_1'];
    $keyword2 = $_POST['keyword_2'];
    $keyword3 = $_POST['keyword_3'];
    $keyStrings = array();

    array_push($keyStrings, $keyword1);
    array_push($keyStrings, $keyword2);
    array_push($keyStrings, $keyword3);

    //(기본 없음, array)
    $keys = [];
    if(isset($_POST['keys'])){
      $keys = $_POST['keys'];
      $where_type = true;
      $where_count = count($keys);
    }

    $weeks = array('일','월','화','수','목','금','토');
    $result = null;
    $returnData = array();

    //디바이스 목록 가져오기
    $class_flag = '0';
    if(in_array("total_focus", $index_models)){
      $result = mysqli_query($conn, 'SELECT model FROM voc_devices WHERE flag = "1"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString = implode(',', $index_focus);
      $modelsString_focus = implode(',', $index_focus);
    }else{
    $i=0;
    $index_focus = array();
    while($i < count($index_models)){
      array_push($index_focus, '"'.$index_models[$i].'"');
      $i++;
    }
    $modelsString = implode(',', $index_focus);
    // }

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

    //카테고리 구분 값 목록 가져오기
    $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag = "1"');
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_class, $row['category']);
    }

    //generate part 1 table sql query
    $sql_part_1 = 'FROM '.$type;

    //generate part 2 select option sql query
    $sql_part_2 = '';
    if($where_type == true){
      $i = 0;
      while($i < $where_count){
        $sql_part_2 = $sql_part_2.' AND '.$keys[$i].' REGEXP "'.$keyStrings[$i].'"';
        $i++;
      }
    }

    ////////////////////////////////////////////////////VOC TOTAL DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();
      if($index_models[$i] == 'total_focus'){
        while($j < count($index_terms)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND model2 IN ('.$modelsString.')'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }else if($index_models[$i] == 'total_hole'){
        while($j < count($index_terms)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }else{
        while($j < count($index_terms)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND model2 = "'.$index_models[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }
      array_push($values_term, $temp_array);
      $i++;
    }

    ////////////////////////////////////////////////////Class Category DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_class)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_terms)){
        if(in_array("total_hole", $index_models)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1="'.$index_class[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND model2 IN ('.$modelsString.') AND class1="'.$index_class[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }
      array_push($values_class, $temp_array);
      $i++;
    }

    ////////////////////////////////////////////////////모델별 운용사 DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_manageCo)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_models)){
        $temp_string = $index_manageCo[$i].'Access Infra팀';
        if($index_models[$j] == 'total_hole'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else if($index_models[$j] == 'total_focus'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND model2 IN ('.$modelsString_focus.')
          AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND model2 = "'.$index_models[$j].'"
          AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }
      array_push($values_manageCo, $temp_array);
      $i++;
    }


    ////////////////////////////////////////////////////모델별 운용사 DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_network)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_models)){
        if($index_models[$j] == 'total_hole'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND netMethod2 = "'.$index_network[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else if($index_models[$j] == 'total_focus'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND model2 IN ('.$modelsString_focus.')
          AND netMethod2 = "'.$index_network[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate >= "'.$startDate.'" AND regiDate <="'.$endDate.'" AND model2 = "'.$index_models[$j].'"
          AND netMethod2 = "'.$index_network[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
      }
      array_push($values_network, $temp_array);
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
    $returnData['index_manage'] = $index_manageCo;
    $returnData['index_class'] = $index_class;
    $returnData['index_network'] = $index_network;

    $returnData['models'] =  $index_model_trim;
    $returnData['values'] = $values_term;
    $returnData['values_manage'] = $values_manageCo;
    $returnData['values_class'] = $values_class;
    $returnData['values_network'] = $values_network;


    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
