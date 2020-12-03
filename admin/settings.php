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
      if($_SESSION['adminAuth']==0){
        echo "<script>
                alert('관리자 계정으로 로그인 하셔야 이용이 가능합니다.');
                location.href = '/voc/index.php';
              </script>";
      }
    }
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);


    // $sql_flag_update = "UPDATE settings SET deviceFlag ";
    $sql_flag = "SELECT * FROM settings";

    $result = mysqli_query($conn, $sql_flag);
    $row = mysqli_fetch_array($result);

    //단말종합관리
    $oneweek_normal = $row['oneweekNormal'];
    $oneweek_caution1 = $row['oneweekCaution1'];
    $oneweek_caution2 = $row['oneweekCaution2'];
    $oneweek_danger = $row['oneweekDanger'];
    //지역종합관리
    $holiday_normal = $row['holidayNormal'];
    $holiday_caution1 = $row['holidayCaution1'];
    $holiday_caution2 = $row['holidayCaution2'];
    $holiday_danger = $row['holidayDanger'];
    //카테고리종합관리
    $threeweek_normal = $row['threeweekNormal'];
    $threeweek_caution1 = $row['threeweekCaution1'];
    $threeweek_caution2 = $row['threeweekCaution2'];
    $threeweek_danger = $row['threeweekDanger'];


    mysqli_close($conn);
 ?>
<!doctype html>
<html lang="en">

  <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="/voc/assets/vendor/bootstrap/css/bootstrap.min.css">
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/circular-std/style.css">
      <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
      <link rel="stylesheet" href="/voc/assets/vendor/multi-select-min/css/bootstrap-select-min.css">
      <!-- <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet"> -->
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/weather-icons/css/weather-icons.css">
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/weather-icons/css/weather-icons-wind.css">
      <link rel="stylesheet" href="/voc/assets/libs/css/toastr.min.css">
      <title>VOMS 모니터링</title>
  </head>

  <body>
      <!-- ============================================================== -->
      <!-- main wrapper -->
      <!-- ============================================================== -->
      <div class="dashboard-main-wrapper">
          <!-- ============================================================== -->
          <!-- navbar -->
          <!-- ============================================================== -->
          <div class="dashboard-header">
              <nav class="navbar navbar-expand-lg bg-white fixed-top">
                  <a class="navbar-brand" href="/voc/index.php">VOMS <samll id="navbar-brand-small">  Voc Monitoring System</small></a>
                  <div class="collapse navbar-collapse " id="navbarSupportedContent">
                      <ul class="navbar-nav ml-auto navbar-right-top">
                        <!-- ============================================================== -->
                        <!-- ============================GBN Menu========================== -->
                        <li class="nav-item dropdown connection">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-fw fa-th"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right connection-dropdown">
                                <li class="connection-list">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" ><span class>메인</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" ><span>데이터</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/chart/modelChart.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" ><span>차트</span></a>
                                        </div>
                                        <?php
                                          if ($_SESSION['adminAuth']== 1){
                                            echo
                                            '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                <a href="/voc/admin/devices.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>관리</span></a>
                                            </div>';
                                          }
                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                          <!-- ================================================================== -->
                          <!-- ============================End GBN Menu========================== -->
                          <!-- ================================================================== -->
                          <!-- =======================Account Profile Menu======================= -->
                          <li class="nav-item dropdown nav-user">
                              <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/voc/assets/images/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
                              <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                  <div class="nav-user-info">
                                      <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?> </h5>
                                      <span class="status"></span><span class="ml-2"><?php if($_SESSION['adminAuth'] == 1){echo "Available  (관리자)";}else{echo "Available  (일반)";}?></span>
                                  </div>
                                  <a class="dropdown-item" href="/voc/admin/account.php"><i class="fas fa-user mr-2"></i>Account</a>
                                  <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                                  <a class="dropdown-item" href="/voc/phpdata/logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                              </div>
                          </li>
                          <!-- ================================================================== -->
                          <!-- =======================End Account Profile Menu=================== -->
                      </ul>
                  </div>
              </nav>
          </div>
          <!-- ============================================================== -->
          <!-- end navbar -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- left sidebar -->
          <!-- ============================================================== -->
          <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="/voc/index.php">메인화면</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle"></i>메인화면<span class="badge badge-success">6</span></a>
                                <div id="submenu-1" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/index.php">메인화면</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i>VOC데이터</a>
                                <div id="submenu-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/data/index.php">VOC 통품전체</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/data/sort.php">VOC 불만성유형</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/data/subscribe.php">가입자 정보</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-fw fa-chart-pie"></i>VOC차트</a>
                                <div id="submenu-3" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/chart/modelChart.php">모델차트</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/chart/manuChart.php">제조사차트</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/chart/keywordChart.php">키워드차트</a>
                                      </li>
                                    </ul>
                                </div>
                            </li>
                            <?php
                              if($_SESSION['adminAuth'] == 1){
                                    echo '
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fab fa-fw fa-wpforms"></i>관리페이지</a>
                                        <div id="submenu-4" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/devices.php">단말리스트관리</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/deviceAd.php">단말추가</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/classes.php">분류리스트관리</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/classAd.php">분류추가</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/accounts.php">사용자관리</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/settings.php">상태설정</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    ';
                                }
                            ?>
                        </ul>
                    </div>
                </nav>
            </div>
          </div>
          <!-- ============================================================== -->
          <!-- end left sidebar -->
          <!-- ============================================================== -->
          <!-- ============================================================== -->
          <!-- wrapper  -->
          <!-- ============================================================== -->
          <div class="dashboard-wrapper">
              <div class="container-fluid dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">상태설정</h2>
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">메인화면</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">상태설정</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                          </div>
                      </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                <form id="settings" data-parsley-validate="" novalidate="" action="/voc/admin/phpdata/updateSettings.php" method="post" onsubmit='return settingCheck();'>
                  <!-- Header -->
                  <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card">
                        <div class="card-header d-flex">
                          <button class="w-auto btn btn-info" type="button" onclick='settingInit();' style='margin-right:3px;'>초기화</button>
                          <button class="w-auto btn btn-primary" type="submit" onclick=''>변경</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- 1row -->
                  <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                      <div class="card">
                        <h5 class="card-header">전주기준설정</h5>
                        <div class="card-body">
                          <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <div class="metric-label d-inline-block float-left text-primary">
                                      <i class="m-r-10 wi wi-day-sunny"></i>
                                      <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                      <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                      <span><strong>양호(미만)</strong></span>
                                      <!-- <span><strong>주의</strong></span> -->
                                      <!-- <span><strong>심각</strong></span>-->
                                  </div>
                                  <div class="col-xs-2">
                                    <input id="oneweek_normal" class="form-control setVal" value="<?php echo $oneweek_normal;?>" type="text" name='oneweekNormal' style="width:150px; text-align:right;">
                                  </div>
                                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <div class="metric-label d-inline-block float-left text-warning">
                                      <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                      <i class="m-r-10 wi wi-day-cloudy"></i>
                                      <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                      <!-- <span><strong>양호<strong></span> -->
                                      <span><strong>주의(이상 ~ 이하)</strong></span>
                                      <!-- <span><strong>심각</strong></span>-->
                                  </div>
                                  <div class="col-xs-2">
                                    <input id="oneweek_caution1" class="form-control setVal" value="<?php echo $oneweek_caution1;?>" type="text" name="oneweekCaution1" style="width:150px; text-align:right; margin-bottom:3px;">
                                    <input id="oneweek_caution2" class="form-control setVal" value="<?php echo $oneweek_caution2;?>" type="text" name="oneweekCaution2" style="width:150px; text-align:right;">
                                  </div>
                                </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                  <div class="metric-label d-inline-block float-left text-danger">
                                        <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                        <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                        <i class="m-r-10 wi wi-storm-showers"></i>
                                        <!-- <span><strong>양호<strong></span> -->
                                        <!-- <span><strong>주의</strong></span> -->
                                        <span><strong>심각(초과)</strong></span>
                                  </div>
                                  <div class="col-xs-2">
                                    <input id="oneweek_danger" class="form-control setVal" value="<?php echo $oneweek_danger;?>" type="text" name='oneweekDanger' style="width:150px; text-align:right;">
                                  </div>
                            </li>
                          </ul>
                        </div>
                      </div>
                     </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                       <div class="card">
                         <h5 class="card-header">평일기준설정</h5>
                         <div class="card-body">
                           <ul class="list-group">
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-primary">
                                       <i class="m-r-10 wi wi-day-sunny"></i>
                                       <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                       <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                       <span><strong>양호(미만)</strong></span>
                                       <!-- <span><strong>주의</strong></span> -->
                                       <!-- <span><strong>심각</strong></span>-->
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="holiday_normal" class="form-control setVal" value="<?php echo $holiday_normal;?>" type="text" name='holidayNormal' style="width:150px; text-align:right;">
                                   </div>
                                 </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-warning">
                                       <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                       <i class="m-r-10 wi wi-day-cloudy"></i>
                                       <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                       <!-- <span><strong>양호<strong></span> -->
                                       <span><strong>주의(이상 ~ 이하)</strong></span>
                                       <!-- <span><strong>심각</strong></span>-->
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="holiday_caution1" class="form-control setVal" value="<?php echo $holiday_caution1;?>" type="text" name="holidayCaution1" style="width:150px; text-align:right; margin-bottom:3px;">
                                     <input id="holiday_caution2" class="form-control setVal" value="<?php echo $holiday_caution2;?>" type="text" name="holidayCaution2" style="width:150px; text-align:right;">
                                   </div>
                                 </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-danger">
                                         <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                         <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                         <i class="m-r-10 wi wi-storm-showers"></i>
                                         <!-- <span><strong>양호<strong></span> -->
                                         <!-- <span><strong>주의</strong></span> -->
                                         <span><strong>심각(초과)</strong></span>
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="holiday_danger" class="form-control setVal" value="<?php echo $holiday_danger;?>" type="text" name='holidayDanger' style="width:150px; text-align:right;">
                                   </div>
                             </li>
                           </ul>
                         </div>
                       </div>
                      </div>
                   </div>
                   <!-- 2row -->
                   <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                       <div class="card">
                         <h5 class="card-header">3주설정기준</h5>
                         <div class="card-body">
                           <ul class="list-group">
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-primary">
                                       <i class="m-r-10 wi wi-day-sunny"></i>
                                       <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                       <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                       <span><strong>양호(미만)</strong></span>
                                       <!-- <span><strong>주의</strong></span> -->
                                       <!-- <span><strong>심각</strong></span>-->
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="threeweek_normal" class="form-control setVal" value="<?php echo $threeweek_normal;?>" type="text" name='threeweekNormal' style="width:150px; text-align:right;">
                                   </div>
                                 </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-warning">
                                       <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                       <i class="m-r-10 wi wi-day-cloudy"></i>
                                       <!-- <i class="m-r-10 wi wi-storm-showers"></i>-->
                                       <!-- <span><strong>양호<strong></span> -->
                                       <span><strong>주의(이상 ~ 이하)</strong></span>
                                       <!-- <span><strong>심각</strong></span>-->
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="threeweek_caution1" class="form-control setVal" value="<?php echo $threeweek_caution1;?>" type="text" name="threeweekCaution1" style="width:150px; text-align:right; margin-bottom:3px;">
                                     <input id="threeweek_caution2" class="form-control setVal" value="<?php echo $threeweek_caution2;?>" type="text" name="threeweekCaution2" style="width:150px; text-align:right;">
                                   </div>
                                 </li>
                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                   <div class="metric-label d-inline-block float-left text-danger">
                                         <!-- <i class="m-r-10 wi wi-day-sunny"></i> -->
                                         <!-- <i class="m-r-10 wi wi-day-cloudy"></i>-->
                                         <i class="m-r-10 wi wi-storm-showers"></i>
                                         <!-- <span><strong>양호<strong></span> -->
                                         <!-- <span><strong>주의</strong></span> -->
                                         <span><strong>심각(초과)</strong></span>
                                   </div>
                                   <div class="col-xs-2">
                                     <input id="threeweek_danger" class="form-control setVal" value="<?php echo $threeweek_danger;?>" type="text" name='threeweekDanger' style="width:150px; text-align:right;">
                                   </div>
                             </li>
                           </ul>
                         </div>
                       </div>
                      </div>
                   </div>
                </form>
                  <!-- ============================================================== -->
                  <!-- end wrapper  -->
                  <!-- ============================================================== -->
              </div>
              <div class="footer">
                  <div class="container-fluid">
                      <div class="row">
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                Copyright © 2019 TestEnC. All rights reserved.
                          </div>
                          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                              <div class="text-md-right footer-links d-none d-sm-block">
                                  <a href="http://gw.bwlee325.cafe24.com/groupware/index.php" target="_blank">Visit Groupware</a>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>

          <!-- ============================================================== -->
          <!-- end wrapper  -->
          <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- javascript -->
      <!-- ============================================================== -->
      <!-- end main wrapper  -->
      <!-- ============================================================== -->
      <!-- Optional JavaScript -->
      <!-- jquery 3.3.1 -->
      <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
      <!-- bootstap bundle js -->
      <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
      <!-- slimscroll js -->
      <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
      <!-- main js -->
      <script src="/voc/assets/libs/js/main-js.js"></script>
      <script src="/voc/assets/vendor/multi-select-min/js/bootstrap-select-min.js"></script>
      <script src="/voc/assets/libs/js/toastr.min.js"></script>
  </body>

</html>
