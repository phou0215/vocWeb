<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    //각 그래프 index 관련 list
    $index_extract = array();
    $index_class = array();

    //각 그래프 날짜 관련 list
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();
    $index_local = array();

    //모델별에서 관심모델 및 단일 모델 여러개 선택 시 문자열
    $modelSort = "";
    //검색어 관련
    $key = "";
    $keySql = "";
    $modelsString_focus = "";
    $modelsString_tot = "";

    //상수 리터럴 관련
    $index_manageCo = ['강북','강남','인천','경기','충북','충남','강원','경북','경남','전북','전남'];
    $index_manageCo_core = ['강북본부','강남본부','인천본부','경기본부','충북본부','충남본부','강원본부','경북본부','경남본부','전북본부','전남본부'];
    $index_network = ['LTE', '5G'];
    $weeks = array('일','월','화','수','목','금','토');

    //리턴 리터럴 관련
    $values = array();
    // $values_rate = array();
    // $values_manageCo = array();
    $values_extract = array();

    //공용 변수
    $result = null;
    $returnData = array();

    //POST Parmeter deploy
    $key = $_POST['key'];
    $sort = $_POST['sort'];
    $index_model = $_POST['model'];
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));
    $modelType = $_POST['modelType'];
    $netType = $_POST['netType'];

    // Set model sort value
    if($modelType == "voc_models"){
      $modelSort = "model";
    }else{
      $modelSort = "model2";
    }
    //검색어 존재 확인
    if(isset($_POST['key'])){
      $key = $_POST['key'];
      $keySql = " memo REGEXP ".$key;
    }


    //관심 디바이스 목록 가져오기
    if($index_model == "total_focus"){
      $result = mysqli_query($conn, 'SELECT model FROM '.$modelType.' WHERE focusOn = "1" AND cellType="'.$netType.'"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString_focus = implode(',', $index_focus);
    }

    //전체 디바이스 목록 가져오기
    if($index_model == "total_hole"){
      $result = mysqli_query($conn, 'SELECT model FROM '.$modelType.' WHERE flag = "1" AND cellType="'.$netType.'"');
      $index_focus = array();
      while($row = mysqli_fetch_assoc($result)){
        array_push($index_focus, '"'.$row['model'].'"');
      }
      $modelsString_tot = implode(',', $index_focus);
    }

    //카테고리 구분 값 목록 가져오기
    $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag = "1"');
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_class, $row['category']);
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

    //generate part 1 table sql query
    $sql_part_1 = 'FROM '.$sort;

    //generate part 2 select option sql query
    $sql_part_2 = "";
    if($key != ""){
      $sql_part_2 = ' AND memo REGEXP "'.$key.'"';
    }

    ////////////////////////////////////////////////////키워드 날짜별 모델 데이터 추출 ///////////////////////////////////////////////

    $i=0;
    while($i < count($index_class)){

      $j = 0;
      $temp_array = array();
      $temp_subs_array = array();
      $temp_rate_array = array();
      $temp_local_array = array();

      #관심단말 데이터 조회
      if($index_model == 'total_focus'){

        while($j < count($index_terms)){

          // local manageCo calculate
          // $sql = 'SELECT manageCo, COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'"AND '.$modelSort.' IN ('.$modelsString_focus.')'.$sql_part_2.' GROUP BY manageCo ORDER BY manageCo DESC';
          // $result = mysqli_query($conn, $sql);
          // $localName = array();
          // $localValue = array();
          // while($row = mysqli_fetch_assoc($result)){
          //   array_push($localName, substr($row['manageCo'], 0, 2 ));
          //   array_push($localValue, (int)$row['cnt']);
          // }

          // local manageCo calculate
          $z = 0;
          $localValue = array();
          while($z < count($index_manageCo_core)){
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'" AND manageCo = "'.$index_manageCo_core[$z].'" AND '.$modelSort.' IN ('.$modelsString_focus.')'.$sql_part_2;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            array_push($localValue, $row['cnt']);
            $z++;
          }
          $count_num = array_sum($localValue);

          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$modelSort.' IN ('.$modelsString_focus.')';
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
          array_push($temp_local_array, $localValue);

          $j++;
        }
      }else if($index_model == 'total_hole'){

        while($j < count($index_terms)){

          // #local manageCo calculate
          // $sql = 'SELECT manageCo, COUNT(manageCo) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'"AND '.$modelSort.' IN ('.$modelsString_tot.')'.$sql_part_2.' GROUP BY manageCo ORDER BY manageCo DESC';
          // $result = mysqli_query($conn, $sql);
          // # 본부 이름 및 통계 수치
          // $localName = array();
          // $localValue = array();
          // while($row = mysqli_fetch_assoc($result)){
          //   array_push($localName, substr($row['manageCo'], 0, 2 ));
          //   array_push($localValue, (int)$row['cnt']);
          // }
          // $count_num = array_sum($localValue);

          // local manageCo calculate
          $z = 0;
          $localValue = array();
          while($z < count($index_manageCo_core)){
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'" AND manageCo = "'.$index_manageCo_core[$z].'" AND '.$modelSort.' IN ('.$modelsString_tot.')'.$sql_part_2;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            array_push($localValue, $row['cnt']);
            $z++;
          }
          $count_num = array_sum($localValue);


          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$modelSort.' IN ('.$modelsString_focus.')';
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
          array_push($temp_local_array, $localValue);
          $j++;
        }
      }else{

        while($j < count($index_terms)){

          // #local manageCo calculate
          // $sql = 'SELECT manageCo, COUNT(manageCo) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'"AND '.$modelSort.' = "'.$index_model.'"'.$sql_part_2.' GROUP BY manageCo ORDER BY manageCo DESC';
          // $result = mysqli_query($conn, $sql);
          // # 본부 이름 및 통계 수치
          // $localName = array();
          // $localValue = array();
          // while($row = mysqli_fetch_assoc($result)){
          //   array_push($localName, substr($row['manageCo'], 0, 2 ));
          //   array_push($localValue, (int)$row['cnt']);
          // }
          // $count_num = array_sum($localValue);

          // local manageCo calculate
          $z = 0;
          $localValue = array();
          while($z < count($index_manageCo_core)){
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND class1 = "'.$index_class[$i].'" AND manageCo = "'.$index_manageCo_core[$z].'" AND '.$modelSort.' = "'.$index_model.'"'.$sql_part_2;
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            array_push($localValue, $row['cnt']);
            $z++;
          }
          $count_num = array_sum($localValue);

          # subscriber
          $sql = 'SELECT SUM(numSubs) AS cnt FROM voc_subscriber WHERE subsDate = "'.$index_terms[$j].'" AND '.$modelSort.' = "'.$index_model.'"';
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
          array_push($temp_local_array, $localValue);
          $j++;
        }
      }
      array_push($values, [$temp_array, $temp_subs_array, $temp_rate_array, $temp_local_array]);
      $i++;
    }

    ////////////////////////////////////////////////////keyword extract 모델 DATA 추출 ///////////////////////////////////////////////
    $extract_word = array();
    $sql_extract = '';
    if($index_model == 'total_focus'){
      $sql_extract = 'SELECT extractWord '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$modelSort.' IN ('.$modelsString_focus.')'.$sql_part_2;
    }else if($index_model == 'total_hole'){
      $sql_extract = 'SELECT extractWord '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59")'.$modelSort.' IN ('.$modelsString_tot.')'.$sql_part_2;
    }else{
      $sql_extract = 'SELECT extractWord '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND '.$modelSort.' = "'.$index_model.'"'.$sql_part_2;
    }
    $result = mysqli_query($conn, $sql_extract);
    //$extract_word에 추출된 단어 넣기
    $i = 0;
    while($row = mysqli_fetch_assoc($result)){
      $words = explode(',',$row['extractWord']);
      $j = 0;
      while($j < count($words)){
        array_push($extract_word, $words[$j]);
        $j++;
      }
      $i++;
    }

    //$extract_word에 중복 개수 count 및 key(키워드)와 value(갯수)를 분리
    $num = array_count_values($extract_word);
    arsort($num);
    foreach( $num as $key => $value ){
      if(count($index_extract) == 20){
        break;
      }else{
        if(mb_strlen($key , 'utf-8') > 1){
          array_push($index_extract, $key);
          array_push($values_extract, $value);
        }else{
          continue;
        }
      }
    }

    //index_date add weeks
    for($i=0; $i<count($index_terms); $i++){
      $temp_string = $index_terms[$i].'('.$index_weeks[$i].')';
      array_push($index_add, $temp_string);
    }

    //index_model adjust text
    if($index_model == "total_focus"){
      $index_model = "관심전체";
    }else if($index_model == "total_hole"){
      $index_model = "단말전체";
    }

    //send chart json data using ajax
    $returnData['index_date'] = $index_add;
    $returnData['index_model'] = $index_model;
    $returnData['index_class'] = $index_class;
    $returnData['index_extract'] = $index_extract;
    $returnData['index_manageCo'] = $index_manageCo;

    $returnData['values'] =  $values;
    $returnData['values_extract'] = $values_extract;

    $jsonTable = json_encode($returnData, JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
