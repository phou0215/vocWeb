<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    function pearson_correlation($x, $y){
      if(count($x)!= count($y)){
        return -1;
      }
      $x = array_values($x);
      $y = array_values($y);
      if(array_sum($x) == 0 || array_sum($y) == 0){
        return 0;
      }
      $xs = array_sum($x)/count($x);
      $ys = array_sum($y)/count($y);
      $a = 0;
      $bx = 0;
      $by = 0;
      for($z = 0; $z<count($x); $z++){
          $xr = $x[$z]-$xs;
          $yr = $y[$z]-$ys;
          $a += $xr * $yr;
          $bx += pow($xr, 2);
          $by += pow($yr, 2);
      }
      $b = sqrt($bx * $by);
      return round($a / $b, 3);
    }

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
    $values_rate = array();
    $values_subs = array();
    $values_tot_cate = array();
    $values_pearson = array();


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
    $model_type = $_POST['modelType'];
    $keyStrings = array();
    $model_sort = "";

    #select voc model column
    if($model_type == "voc_models"){
      $model_sort = "model";
    }else{
      $model_sort = "model2";
    }
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
    // $class_flag = '0';
    if(in_array("total_focus", $index_models)){
      $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE focusOn = "1"');
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
      $temp_subs_array = array();
      $temp_rate_array = array();
      $temp_tot_array = array();

      if($index_models[$i] == 'total_focus'){
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.')'.$sql_part_2;
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
          # 상관관계 지수를 위한 일별 총 VOC 건수
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $tot_num = (int)$row['cnt'];

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          array_push($temp_tot_array, $tot_num);
          $j++;
        }
      }else if($index_models[$i] == 'total_hole'){
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = (int)$row['cnt'];
          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $subs_num = (int)$row['cnt'];
          $temp_rate = 0.0;
          # rate calculate
          if ($count_num != 0 && $subs_num != 0){
            $temp_rate = ($count_num / $subs_num)*1000;
            $temp_rate = round($temp_rate, 4);
          }
          # 상관관계 지수를 위한 일별 총 VOC 건수
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $tot_num = (int)$row['cnt'];

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          array_push($temp_tot_array, $tot_num);
          $j++;
        }
      }else{
        while($j < count($index_terms)){
          #voc data count
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' = "'.$index_models[$i].'"'.$sql_part_2;
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
          # 상관관계 지수를 위한 일별 총 VOC 건수
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' = "'.$index_models[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $tot_num = (int)$row['cnt'];

          array_push($temp_array, $count_num);
          array_push($temp_subs_array, $subs_num);
          array_push($temp_rate_array, $temp_rate);
          array_push($temp_tot_array, $tot_num);
          $j++;
        }
      }
      array_push($values_term, [$temp_array, $temp_subs_array, $temp_rate_array, $temp_tot_array]);
      $i++;
    }

    ////////////////////////////////////////////////////일별 모델 카테고리 VOC DATA 추출 ///////////////////////////////////////////////
    $i = 0;
    while($i < count($index_models)){
      $c = 0;
      $temp_class = array();
      while($c < count($index_class)){
        $j = 0;
        $temp_array = array();
        if($index_models[$i] == 'total_focus'){
          while($j < count($index_terms)){
            #voc data count
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' IN ('.$modelsString.')'.$sql_part_2.' AND class1="'.$index_class[$c].'"';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $count_num = (int)$row['cnt'];

            array_push($temp_array, $count_num);
            $j++;
          }
        }
        else if($index_models[$i] == 'total_hole'){
          while($j < count($index_terms)){
            #voc data count
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"'.$sql_part_2.' AND class1="'.$index_class[$c].'"';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $count_num = (int)$row['cnt'];

            array_push($temp_array, $count_num);
            $j++;
          }
        }
        else{
          while($j < count($index_terms)){
            #voc data count
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND '.$model_sort.' = "'.$index_models[$i].'"'.$sql_part_2.' AND class1="'.$index_class[$c].'"';
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $count_num = (int)$row['cnt'];

            array_push($temp_array, $count_num);
            $j++;
          }
        }
        array_push($temp_class, $temp_array);
        $c++;
      }
      array_push($values_tot_cate, $temp_class);
      $i++;
    }
    ////////////////////////////////////////////////////모델별 카테고리 상관지수 DATA 추출 ///////////////////////////////////////////////
    $i = 0;
    while($i < count($index_models)){
      $j = 0;
      $total = $values_term[$i][3];
      $temp_array = array();
      while($j < count($index_class)){
        $target = $values_tot_cate[$i][$j];
        $r = pearson_correlation($target, $total);
        array_push($temp_array, $r);
        $j++;
      }
      array_push($values_pearson, $temp_array);
      $i++;
    }

    ////////////////////////////////////////////////////Class Category DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_class)){
        if($index_models[$i] == "total_hole"){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND class1="'.$index_class[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else if($index_models[$i] == 'total_focus'){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.' IN ('.$modelsString.') AND class1="'.$index_class[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.'="'.$index_models[$i].'" AND class1="'.$index_class[$j].'"'.$sql_part_2;
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
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_manageCo)){
        $temp_string = $index_manageCo[$j].'Access Infra팀';
        if($index_models[$i] == 'total_hole'){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else if($index_models[$i] == 'total_focus'){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.' IN ('.$modelsString_focus.')
          AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.' = "'.$index_models[$i].'"
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

    ////////////////////////////////////////////////////모델별 네트워크 DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_models)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_network)){
        if($index_models[$i] == 'total_hole'){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND netMethod2 = "'.$index_network[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else if($index_models[$i] == 'total_focus'){
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.' IN ('.$modelsString_focus.')
          AND netMethod2 = "'.$index_network[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$model_sort.' = "'.$index_models[$i].'"
          AND netMethod2 = "'.$index_network[$j].'"'.$sql_part_2;
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
    $returnData['values_pearson'] = $values_pearson;
    $returnData['values_tot_cate'] = $values_tot_cate;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
