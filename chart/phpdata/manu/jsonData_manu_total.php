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
    $index_manu_trim = array();
    $index_terms = array();
    $index_weeks = array();
    $index_add = array();
    $manusString = "";

    $index_manageCo = ['강남','강북','경인','광주','대구','대전','부산','원주','전주','제주','청주'];
    $index_network = ['1X', '2G', 'LTE', 'WCDMA', '5G'];

    //날짜별 또는 제조사 2차원 배열
    $values_term = array();
    $values_class =array();
    $values_manageCo = array();
    $values_update = array();
    $values_network = array();
    $values_tot_cate = array();
    $values_pearson = array();

    $where_type = false;
    $where_count = 0;

    //handling POST Parameter
    $startDate = date('Y-m-d', strtotime($_POST['startDate']));
    $endDate = date('Y-m-d', strtotime($_POST['endDate']));

    //(기본 total, array)
    $index_manus = $_POST['manus'];
    $type = $_POST['type'];
    $keyword1 = $_POST['keyword_1'];
    $keyword2 = $_POST['keyword_2'];
    $keyword3 = $_POST['keyword_3'];
    $keyStrings = array();

    //(기본 없음, array)
    $keys = [];
    if(isset($_POST['keys'])){
      $keys = $_POST['keys'];
      $where_type = true;
      $where_count = count($keys);
    }

    array_push($keyStrings, $keyword1);
    array_push($keyStrings, $keyword2);
    array_push($keyStrings, $keyword3);

    $weeks = array('일','월','화','수','목','금','토');
    $result = null;
    $returnData = array();

    //디바이스 목록 가져오기
    if(in_array("total", $index_manus)){
      $manusString = 'total';
    }else{
      $i=0;
      $index_focus = array();
      while($i< count($index_manus)){
        array_push($index_focus, '"'.$index_manus[$i].'"');
        $i++;
      }
      $manusString = implode(',', $index_focus);
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
    while($i < count($index_manus)){
      $j = 0;
      $temp_array = array();
      $temp_tot_array = array();

      if($index_manus[$i] == 'total'){
        while($j < count($index_terms)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];

          # 상관관계 지수를 위한 일별 총 VOC 건수
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $tot_num = (int)$row['cnt'];

          array_push($temp_array, $count_num);
          array_push($temp_tot_array, $tot_num);
          $j++;
        }
      }else{
        while($j < count($index_terms)){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND manu = "'.$index_manus[$i].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];

          # 상관관계 지수를 위한 일별 총 VOC 건수
          $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND manu = "'.$index_manus[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $tot_num = (int)$row['cnt'];

          array_push($temp_array, $count_num);
          array_push($temp_tot_array, $tot_num);
          $j++;
        }
      }
      array_push($values_term, [$temp_array, $temp_tot_array]);
      $i++;
    }

    ////////////////////////////////////////////////////일별 제조사 카테고리 VOC DATA 추출 ///////////////////////////////////////////////
    $i = 0;
    while($i < count($index_manus)){
      $c = 0;
      $temp_class = array();
      while($c < count($index_class)){
        $j = 0;
        $temp_array = array();
        if($index_manus[$i] == 'total'){
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
            $sql = 'SELECT COUNT(*) AS cnt '.$sql_part_1.' WHERE regiDate = "'.$index_terms[$j].'" AND manu = "'.$index_manus[$i].'"'.$sql_part_2.' AND class1="'.$index_class[$c].'"';
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

    ////////////////////////////////////////////////////제조사별 카테고리 상관지수 DATA 추출 ///////////////////////////////////////////////
    $i = 0;
    while($i < count($index_manus)){
      $j = 0;
      $total = $values_term[$i][1];
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
    while($i < count($index_manus)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_class)){
        if($index_manus[$i] == 'total'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND class1="'.$index_class[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.' 00:00:00" AND "'.$endDate.' 23:59:59") AND manu = "'.$index_manus[$i].'" AND class1="'.$index_class[$j].'"'.$sql_part_2;
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

    ////////////////////////////////////////////////////제조사 운용사 DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_manus)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_manageCo)){
        $temp_string = $index_manageCo[$j].'Access Infra팀';
        if($index_manus[$i] == 'total'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.'" AND "'.$endDate.'") AND manageTeam = "'.$temp_string.'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.'" AND "'.$endDate.'") AND manu = "'.$index_manus[$i].'"
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

    ////////////////////////////////////////////////////제조사 운용사 DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_manus)){
      $j = 0;
      $temp_array = array();
      while($j < count($index_network)){
        if($index_manus[$i] == 'total'){
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.'" AND "'.$endDate.'") AND netMethod2 = "'.$index_network[$j].'"'.$sql_part_2;
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }else{
          $sql = 'SELECT COUNT(*) as cnt '.$sql_part_1.' WHERE (regiDate BETWEEN "'.$startDate.'" AND "'.$endDate.'") AND manu = "'.$index_manus[$i].'"
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
    while($i<count($index_manus)){
      $name = "";
      if($index_manus[$i] == "total"){
        $name = "전체";
      }else{
        $name = substr($index_manus[$i], 0, 25);
      }
      array_push($index_manu_trim, $name);
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
    $returnData['index_manu'] = $index_manu_trim;
    $returnData['index_class'] = $index_class;
    $returnData['index_network'] = $index_network;

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
