
<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/voc/phpdata/signin.php'
                  </script>";}
    }else{
         $root = $_SERVER['DOCUMENT_ROOT'];
         require_once($root."/config/config.php");
        /* Paging Start */
        if(isset($_GET['no'])){
            $no = $_GET['no'];
            $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
            mysqli_select_db($conn, $config['database']);
            $mysql = "DELETE FROM voc_classes WHERE no IN (".$no.")";
            if(mysqli_query($conn, $mysql)){
                mysqli_close($conn);
                echo "<script>
                        alert('통계 반영 Category 데이터가 정상적으로 삭제되었습니다.');
                        location.href='/voc/admin/classes.php';
                    </script>";
            }else{
              echo mysqli_error($conn);
            }
        }else{
          mysqli_close($conn);
					echo "<script>
									alert('정상적인 데이터 페이지 로딩이 불가능합니다.');
                  location.href='/voc/admin/classes.php';
							</script>";
        }
      }

 ?>
