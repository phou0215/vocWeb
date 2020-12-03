<?php
    require($_SERVER['DOCUMENT_ROOT']."/lib/db.php");
    require($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = db_int($config['host'],$config['user'],$config['password'],$config['database']);
    //단말종합관리
    $oneweek_normal = trim($_POST['oneweekNormal']);
    $oneweek_caution1 = trim($_POST['oneweekCaution1']);
    $oneweek_caution2 = trim($_POST['oneweekCaution2']);
    $oneweek_danger = trim($_POST['oneweekDanger']);

    //지역종합관리
    $holiday_normal = trim($_POST['holidayNormal']);
    $holiday_caution1 = trim($_POST['holidayCaution1']);
    $holiday_caution2 =trim($_POST['holidayCaution2']);
    $holiday_danger = trim($_POST['holidayDanger']);

    //카테고리종합관리
    $threeweek_normal = trim($_POST['threeweekNormal']);
    $threeweek_caution1 = trim($_POST['threeweekCaution1']);
    $threeweek_caution2 = trim($_POST['threeweekCaution2']);
    $threeweek_danger = trim($_POST['threeweekDanger']);


    if(!$conn){
        die('Could not coonect Server : '.mysqli_error($conn));
    }else{
      $sql = "UPDATE settings SET oneweekNormal=".$oneweek_normal.", oneweekCaution1=".$oneweek_caution1.", oneweekCaution2=".$oneweek_caution2.", oneweekDanger=".$oneweek_danger.",
      holidayNormal=".$holiday_normal.", holidayCaution1=".$holiday_caution1.", holidayCaution2=".$holiday_caution2.", holidayDanger=".$holiday_danger.",
      threeweekNormal=".$threeweek_normal.", threeweekCaution1=".$threeweek_caution1.", threeweekCaution2=".$threeweek_caution2.", threeweekDanger=".$threeweek_danger." WHERE no=1";

      $result = mysqli_query($conn, $sql);
      if($result){
          mysqli_close($conn);
          echo "
                <script>
                  alert('업데이트가 완료 되었습니다.');
                  window.location='/voc/admin/settings.php';
                </script>";
      }else{
          die('Query Send failed reason :'.mysqli_error($conn));
          mysqli_close($conn);
          echo "
                <script>
                  window.location='/voc/admin/settings.php';
                </script>";
      }
    }
?>
