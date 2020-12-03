<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    //Check Date
    if(!isset($_GET['secondDate'])){
      $date_type = "0";
      $date = $_GET['firstDate'];
    }else if(!isset($_GET['firstDate'])){
      $date_type = "1";
      $date = $_GET['secondDate'];
    }else{
      $date_type = "2";
      $firstDate = $_GET['firstDate'];
      $secondDate = $_GET['secondDate'];
    }
    //generate Time index
    $index_time = array();
    $i = 0;
    while($i < 24){
      // $temp_num =  sprintf('%02d',$i);
      array_push($index_time, (string)$i);
      $i++;
    }

    //each model static values of 4 weeks
    $values_term = array();
    $value_aData = array();
    $value_bData = array();

    $temp_time_array = array();
    $temp_timeA_array = array();
    $temp_timeB_array = array();

    $temp_value_array = array();
    $temp_valueA_array = array();
    $temp_valueB_array = array();

    $value_array = array();
    $valueA_array = array();
    $valueB_array = array();

    $result = null;
    $returnData = array();

    //generate sql query depend on dateType
    if ($date_type == "0" || $date_type == "1"){
      $sql = "SELECT HOUR(regiTime) AS HOUR, COUNT(*) AS cnt FROM voc_tot_data WHERE (regiDate='".$date."') AND (regiTime BETWEEN '00:00:00' AND '23:59:59') GROUP BY HOUR(regiTime)";
      $result = mysqli_query($conn, $sql);
      while($row = mysqli_fetch_assoc($result)){
            array_push($temp_time_array, $row['HOUR']);
            array_push($temp_value_array, $row['cnt']);
      }
      //시간대에 건수가 없는 경우 0으로 Setting
      $i = 0;
      $index = 0;
      while($i < 24){
          if(in_array((string)$i, $temp_time_array)){
            array_push($value_array, $temp_value_array[$i-$index]);
          }else{
            array_push($value_array, 0);
            $index++;
          }
          $i++;
      }
      //send chart json data using ajax
      $returnData["index"] = $index_time;
      $returnData["values"] = $value_array;
    }else{
      $sqlA = "SELECT HOUR(regiTime) AS HOUR, COUNT(*) AS cnt FROM voc_tot_data WHERE (regiDate='".$firstDate."') AND (regiTime BETWEEN '00:00:00' AND '23:59:59') GROUP BY HOUR(regiTime)";
      $sqlB = "SELECT HOUR(regiTime) AS HOUR, COUNT(*) AS cnt FROM voc_tot_data WHERE (regiDate='".$secondDate."') AND (regiTime BETWEEN '00:00:00' AND '23:59:59') GROUP BY HOUR(regiTime)";
      $result = mysqli_query($conn, $sqlA);
      //first A date voc count timeline
      while($row = mysqli_fetch_assoc($result)){
            array_push($temp_timeA_array, $row['HOUR']);
            array_push($temp_valueA_array, $row['cnt']);
      }
      //first B date voc count timeline
      $result = mysqli_query($conn, $sqlB);
      while($row = mysqli_fetch_assoc($result)){
            array_push($temp_timeB_array, $row['HOUR']);
            array_push($temp_valueB_array, $row['cnt']);
      }
      //first A date voc 시간대에 건수가 없는 경우 0으로 Setting
      $i = 0;
      $index = 0;
      while($i < 24){
          if(in_array((string)$i, $temp_timeA_array)){
            array_push($valueA_array, $temp_valueA_array[$i-$index]);
          }else{
            array_push($valueA_array, 0);
            $index++;
          }
          $i++;
      }

      //first B date voc 시간대에 건수가 없는 경우 0으로 Setting
      $i = 0;
      $index = 0;
      while($i < 24){
          if(in_array((string)$i, $temp_timeB_array)){
            array_push($valueB_array, $temp_valueB_array[$i-$index]);
          }else{
            array_push($valueB_array, 0);
            $index++;
          }
          $i++;
      }
      //send chart json data using ajax
      $returnData["index"] = $index_time;
      $returnData["values"] = [$valueA_array, $valueB_array];
    }

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
