<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $index_class = array();
    $values = array();

    $result = null;
    $returnData = array();
    $total_num = 0;
    $limitDate = date('Y-m-d',strtotime('-1 month'));
    $today = date('Y-m-d');

    // total voc Data num 가져오기
    $result = mysqli_query($conn, 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate BETWEEN "'.$limitDate.'" AND "'.$today.'"');
    $row = mysqli_fetch_assoc($result);
    $total_num = $row['cnt'];

    //서버 분류 정보 가져오기
    $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag="1"');
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_class, $row['category']);
    }

    $i = 0;
    while($i < count($index_class)){
      $result = mysqli_query($conn, 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE (class1 = "'.$index_class[$i].'") AND (regiDate BETWEEN "'.$limitDate.'" AND "'.$today.'") ORDER BY regiDate');
      $row = mysqli_fetch_assoc($result);
      $temp_count = $row['cnt'];
      $temp_value = ((int)$temp_count / (int)$total_num) * 100;
      array_push($values, round($temp_value,2));
      $i++;
    }

    //send chart json data using ajax
    $returnData['index'] = $index_class;
    $returnData['values'] = $values;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
