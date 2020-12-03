<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/db.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
  $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);

  $id = $_POST['id'];
  $password = $_POST['password'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $reqDate = $_POST['reqDate'];
//insert query
  $inputSql = 'INSERT INTO user (ident, password, email, name, auth, adminAuth, reqDate) VALUES("'.$id.'", "'.$password.'", "'.$email.'", "'.$name.'", 0, 0, "'.$reqDate.'")';
//find query
  $findSql = 'SELECT * FROM user WHERE ident="'.$id.'"';
  $findResult = mysqli_query($conn, $findSql);

  if($findResult->num_rows == 1){
      //저장하려는 아이디가 이미 있는 경우
      echo '<script>
              alert("저장하려는 계정의 이메일이 이미 존재합니다.");
              location.href="/voc/phpdata/signup.php";
            </script>';

  }else{
      //새로운 아이디
      mysqli_query($conn, $inputSql);
      echo '<script>
              alert("계정 승인요청을 하였습니다. 관리자 승인 후 사용하세요.");
              location.href="/voc/phpdata/signin.php";
            </script>';
   }
?>
