<?php
  if(!$_SESSION){
      session_start();
  }
  require_once($_SERVER['DOCUMENT_ROOT'].'/lib/db.php');
  require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
  $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
  $id = $_POST['id'];
  $password = $_POST['password'];
  $sql = 'select * from user where ident="'.$_POST['id'].'" AND auth="1"';
  $result  = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);

  if($result->num_rows == 1){
      //해당 ID 의 회원이 존재할 경우
      // 암호가 맞는지를 확인
      if( $row['password'] === $password){
          // 올바른 정보
          $_SESSION['is_login'] = true;
          $_SESSION['id'] = $id;
          $_SESSION['adminAuth'] = $row['adminAuth'];
          $_SESSION['name'] = $row['name'];

          header('Location: /voc/index.php');

      }else{
          // 암호가 틀렸음
            echo '<script>
                    alert("비밀번호가 틀립니다.");
                    location.href="/voc/phpdata/signin.php"
                  </script>';}
  }else{
      // 없거나, 비정상
        echo '<script>
                alert("아이디가 존재하지 않습니다.");
                location.href="/voc/phpdata/signin.php"
              </script>';}
?>
