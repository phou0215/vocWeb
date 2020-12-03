<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $id = trim($_GET['id']);
    $type = trim($_GET['type']);
    $flag = trim($_GET['flag']);
    $keyword = "";
    $result = NULL;

    if (isset($_GET['device_keyword'])){
      $keyword = trim($_GET['device_keyword']);
    }

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      if($flag == '1'){
        if($type == 0){
          $sql = "UPDATE voc_models2 SET flag=".$type." , focusOn=0 WHERE id=".$id;
        }else{
          $sql = "UPDATE voc_models2 SET flag=".$type." WHERE id=".$id;
        }
        $result = mysqli_query($conn, $sql);
      }else{
        if($type == 0){
          $sql = "UPDATE voc_models SET flag=".$type." , focusOn=0 WHERE id=".$id;
        }else{
          $sql = "UPDATE voc_models SET flag=".$type." WHERE id=".$id;
        }

        $result = mysqli_query($conn, $sql);
      }
    if($result){
      mysqli_close($conn);
      if ($keyword == ""){
        echo "
              <script>
                alert('저장이 완료 되었습니다.');
                window.location='/voc/admin/devices.php';
              </script>";
      }else{
        echo "
              <script>
                alert('저장이 완료 되었습니다.');
                window.location='/voc/admin/devices.php?device_key=".$keyword."';
              </script>";
      }
    }else{
        die('Query Send failed reason :'.mysqli_error($conn));
        mysqli_close($conn);
        echo "
              <script>
                window.location='/voc/admin/devices.php';
              </script>";
    }
  }
?>
