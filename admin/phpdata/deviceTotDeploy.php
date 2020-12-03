<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $items = trim($_POST['items']);
    $flag = trim($_POST['flag']);
    $keyword = trim($_POST['device_key']);
    $sql = "";
    // $array_id = explode(",", $items);
    // $num_count = count($array_id);

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }
    else{
      if($flag == '1'){
        $sql = "UPDATE voc_models2 SET flag=1 WHERE id IN (".$items.")";

      }
      else{
        $sql = "UPDATE voc_models SET flag=1 WHERE id IN (".$items.")";
      }
      $result = mysqli_query($conn, $sql);
      if($result){
        mysqli_close($conn);
        if ($keyword == ""){
          echo "
                <script>
                  alert('설정을 변경하였습니다.');
                  window.location='/voc/admin/devices.php';
                </script>";
        }else{
          echo "
                <script>
                  alert('설정을 변경하였습니다.');
                  window.location='/voc/admin/devices.php?device_key=".$keyword."';
                </script>";
        }
      }
      else{
        die('Query Send failed reason :'.mysqli_error($conn));
        mysqli_close($conn);
        echo "
              <script>
                window.location='/voc/admin/devices.php';
              </script>";
      }
    }
?>
