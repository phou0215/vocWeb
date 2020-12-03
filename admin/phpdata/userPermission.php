<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $id = trim($_GET['id']);
    $type = trim($_GET['type']);
    $current = date('Y-m-d H:i:s');

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      $sql = "UPDATE user SET auth=".$type.", aprDate='".$current."' WHERE id=".$id;
      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('저장이 완료 되었습니다.');
                  window.location='/voc/admin/accounts.php';
                </script>";
      }else{
          die('Query Send failed reason :'.mysqli_error($conn));
          mysqli_close($conn);
          echo "
                <script>
                  window.location='/voc/admin/accounts.php';
                </script>";
      }
    }
?>
