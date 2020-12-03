<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $model = strtoupper(trim($_POST['model']));
    $manu = trim($_POST['manu']);
    $netType = trim($_POST['netType']);
    $flag = trim($_POST['flag']);
    $type= trim($_POST['type']);
    $focus = trim($_POST['focusOn']);
    $regiDate = date('Y-m-d', strtotime(trim($_POST['regiDate'])));
    $launchDate = date('Y-m-d', strtotime(trim($_POST['launchDate'])));
    $sql = "";

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      if ($type == "voc_models"){
        $sql = "INSERT INTO voc_models(model, regiDate, flag, focusOn, launchDate, cellType) VALUES('".$model."','".$regiDate."',".$flag.",".$focus.", '".$launchDate."', '".$netType."')";
      }else{
        $sql = "INSERT INTO voc_models2(model, regiDate, flag, focusOn, launchDate, cellType) VALUES('".$model."','".$regiDate."',".$flag.",".$focus.", '".$launchDate."', '".$netType."')";
      }
      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('저장이 완료 되었습니다.');
                  window.location='/voc/admin/deviceAd.php';
                </script>";
      }else{
          die('Query Send failed reason :'.mysqli_error($conn));
          mysqli_close($conn);
          echo "
                <script>
                  window.location='/voc/admin/deviceAd.php';
                </script>";
      }
    }
?>
