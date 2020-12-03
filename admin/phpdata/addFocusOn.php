<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $id = trim($_GET['id']);
    $type = trim($_GET['type']);
    $flag = trim($_GET['flag']);

    $returnData = array();
    $jsonData;
    $result;
    $sql = "";
    $sql_check = "";

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      if($type == '1'){
        if($flag == "0"){
          $sql = "UPDATE voc_models2 SET focusOn=1 WHERE id=".$id;
        }else{
          $sql = "UPDATE voc_models2 SET focusOn=0 WHERE id=".$id;
        }
        $sql_check = "SELECT * FROM voc_models2 WHERE id=".$id;
      }else{
        if($flag == "0"){
          $sql = "UPDATE voc_models SET focusOn=1 WHERE id=".$id;
        }else{
          $sql = "UPDATE voc_models SET focusOn=0 WHERE id=".$id;
        }
        $sql_check = "SELECT * FROM voc_models WHERE id=".$id;
      }
      $result = mysqli_query($conn, $sql);
      //send chart json data using ajax
      $result = mysqli_query($conn, $sql_check);
      $row = mysqli_fetch_array($result);
      $returnData['message'] = $row['focusOn'];

      $jsonData = json_encode($returnData, JSON_UNESCAPED_UNICODE);
      header('Content-Type: application/json');
      echo $jsonData;
  }
?>
