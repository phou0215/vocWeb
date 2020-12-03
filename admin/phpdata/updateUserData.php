<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //variables
    $id = trim($_POST['id']);
    $ident = trim($_POST['ident']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phoneNum = trim($_POST['phoneNum']);
    $auth = (int)trim($_POST['auth']);
    $adminAuth = (int)trim($_POST['adminAuth']);
    $regiDate = date('Y-m-d H:i:s');
    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      $sql = "UPDATE user SET ident='".$ident."', name='".$name."', email='".$email."', phoneNum='".$phoneNum."', aprDate='".$regiDate."',
      auth=".$auth.", adminAuth=".$adminAuth." WHERE id='".$id."'";
      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('업데이트가 완료 되었습니다.');
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
