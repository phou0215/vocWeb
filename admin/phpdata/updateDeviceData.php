<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $id = trim($_POST['id']);
    $manu = trim($_POST['manu']);
    $model = trim($_POST['model']);
    $os = trim($_POST['os']);
    $petName = trim($_POST['petName']);
    $launDate = date('Y-m-d', strtotime(trim($_POST['launDate'])));
    $swVer = trim($_POST['swVer']);
    $flag = (int)trim($_POST['flag']);
    $extra = trim($_POST['extra']);
    $class = trim($_POST['class']);
    $osVer = trim($_POST['osVer']);
    $deployDate = date('Y-m-d', strtotime(trim($_POST['deployDate'])));
    $roundNo = (int)$_POST['roundNo'];
    $regiDate = date('Y-m-d H:i:s');
 // model='".$model[$i]."'
    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      $sql = "UPDATE voc_devices SET manu='".$manu."', model='".$model."', os='".$os."', petName='".$petName."', launDate='".$launDate."', swVer='".$swVer."',
      flag=".$flag.", extra='".$extra."', class='".$class."', osVer='".$osVer."', deployDate='".$deployDate."', roundNo=".$roundNo.", regiDate='".$regiDate."' WHERE id='".$id."'";
      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('업데이트가 완료 되었습니다.');
                  window.location='/voc/admin/devices.php';
                </script>";
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
