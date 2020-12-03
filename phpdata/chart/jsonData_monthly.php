<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_month = array();
    $values_term = array();
    $index_types = $_POST['types'];

    $result = null;
    $index_trim = array();
    $returnData = array();


    //최근 데이터 날짜 요일 계산
    $recent_result = mysqli_query($conn, 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1');
    $recent_row = mysqli_fetch_assoc($recent_result);
    $recent = date($recent_row['regiDate']);

    //index 월별 계산(오늘이 속한 달 부터 4달 전까지 월별 자동 계산)
    // $thisMonth = date('Y-m',strtotime($recent));
    $i = 4;
    while($i != -1){
      $the_month = date('Y-m', strtotime($recent.'-'.$i.' months'));
      array_push($index_month, $the_month);
      $i--;
    }
    array_push($index_month, date('Y-m', strtotime($recent.'+1 months')));


    //각 단말조치 또는 통품전체 5개월 별 데이터 추출
    $i=0;
    while($i < count($index_types)){
      $j = 0;
      $temp_array = array();
      if($index_types[$i] == 'total'){
        while($j<5){
          $sql = 'SELECT COUNT(*) as cnt FROM voc_tot_data WHERE regiDate >= "'.$index_month[$j].'" AND regiDate < "'.$index_month[$j+1].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
        array_push($index_trim, "통품전체");
      }else{
        while($j<5){
          $sql = 'SELECT COUNT(*) as cnt FROM voc_sort_data WHERE regiDate >= "'.$index_month[$j].'" AND regiDate < "'.$index_month[$j+1].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $count_num = $row['cnt'];
          array_push($temp_array, $count_num);
          $j++;
        }
        array_push($index_trim, "단말조치");
      }
      array_push($values_term, $temp_array);
      $i++;
    }
    array_pop($index_month);


    //send chart json data using ajax
    $returnData['index'] = $index_month;
    $returnData['types'] = $index_trim;
    $returnData['values'] = $values_term;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
