
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
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $mysql = "";
            $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
            mysqli_select_db($conn, $config['database']);
            $keyword = "";

            if (isset($_GET['device_keyword'])){
              $keyword = trim($_GET['device_keyword']);
            }
            if ($_GET['flag'] == 0){
              $mysql = "DELETE FROM voc_models WHERE id IN (".$id.")";
            }else{
              $mysql = "DELETE FROM voc_models2 WHERE id IN (".$id.")";
            }
            $result = mysqli_query($conn, $mysql);
            if($result){
                mysqli_close($conn);
                if ($keyword == ""){
                  echo "<script>
                          alert('모니터링 대상 단말 데이터가 정상적으로 삭제되었습니다.');
                          location.href='/voc/admin/devices.php';
                      </script>";
                }else{
                  echo "<script>
                          alert('모니터링 대상 단말 데이터가 정상적으로 삭제되었습니다.');
                          location.href='/voc/admin/devices.php?device_key=".$keyword."';
                      </script>";
                }

            }else{
              echo mysqli_error($conn);
            }
        }else{
          mysqli_close($conn);
					echo "<script>
									alert('정상적인 데이터 페이지 로딩이 불가능합니다.');
									location.href='/voc/admin/devices.php';
							</script>";
        }
      }

 ?>
