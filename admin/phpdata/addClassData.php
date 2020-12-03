<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $category = trim($_POST['category']);
    $flag = trim($_POST['flag']);

    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      $sql = "INSERT INTO voc_classes(category, flag) VALUES('".$category."','".$flag."')";
      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('저장이 완료 되었습니다.');
                  window.location='/voc/admin/classes.php';
                </script>";
      }else{
          die('Query Send failed reason :'.mysqli_error($conn));
          mysqli_close($conn);
          echo "
                <script>
                  window.location='/voc/admin/classes.php';
                </script>";
      }
    }
?>
