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
    $result = mysqli_query($conn, "SELECT * FROM user");
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
      <!-- <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet"> -->
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
      <link rel="stylesheet" href="/voc/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
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
              <div class="influence-finder">
                  <div class="container-fluid dashboard-content">
                      <!-- ============================================================== -->
                      <!-- pageheader -->
                      <!-- ============================================================== -->
                      <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="page-header">
                                  <h2 class="pageheader-title">사용자관리</h2>
                                  <div class="page-breadcrumb">
                                      <nav aria-label="breadcrumb" style='margin-bottom:2px;'>
                                          <ol class="breadcrumb">
                                              <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">메인화면</a></li>
                                              <li class="breadcrumb-item active" aria-current="page">사용자관리</li>
                                          </ol>
                                      </nav>
                                      <div class="float-right" style="margin-bottom:5px">
                                          <button class='btn btn-secondary' onclick='removeUserData()'>삭제</a>
                                      </div>
                                      <div class="float-right" style="margin-bottom:5px;margin-right:8px;">
                                          <button class='btn btn-success' onclick='updateUserData()'>변경</a>
                                      </div>
                                      <div class="float-right" style="margin-bottom:5px">
                                          <button id='btn_totSelect' class='btn btn-primary' onclick='totalSelect()' checkFlag='false' style='margin-right:8px;'>전체선택</a>
                                      </div>
                                      <!-- <div class="float-right" style="margin-bottom:5px">
                                          <button id='btn_totFocus' class='btn btn-success' onclick='totalFocus()' checked='false' style='margin-right:8px;'>Total Focus</a>
                                      </div> -->
                                      <!-- <div class="float-right" style="margin-bottom:5px; margin-right:5px;">
                                          <button class="btn btn-success" onClick="moveDeviceAd()">UPDATE</button>
                                      </div> -->
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- ============================================================== -->
                      <!-- end pageheader -->
                      <!-- ============================================================== -->
                      <!-- ============================================================== -->
                      <!-- content -->
                      <!-- ============================================================== -->
                      <div class="row">
                          <!-- ============================================================== -->
                          <!-- search bar  -->
                          <!-- ============================================================== -->
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="card">
                                  <div class="card-body">
                                        <input class="form-control form-control-lg" type="search" placeholder="User Name" aria-label="Search" onkeypress="if(event.keyCode==13){searchUser(); return false;}">
                                        <button class="btn btn-primary search-btn" type="button" onclick='searchUser();'>검색</button>
                                  </div>
                              </div>
                          </div>
                          <!-- ============================================================== -->
                          <!-- end search bar  -->
                          <!-- ============================================================== -->
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <!-- ============================================================== -->
                              <!-- card Devices one -->
                              <!-- ============================================================== -->
                              <?php
                                $row_num = mysqli_num_rows($result);
                                if($row_num != 0){
                                  $i = 0;
                                  while($row = mysqli_fetch_array($result)){
                                    $flag1 = "";
                                    $flag2 = "";
                                    if((string) $row['auth'] == "1"){
                                      $flag1 = "Activity";
                                    }else{
                                      $flag1 = "Inctivity";
                                    }
                                    if((string) $row['adminAuth'] == "1"){
                                      $flag2 = "관리자";
                                    }else{
                                      $flag2 = "일반";
                                    }
                                    echo "
                                          <div id='object_".$i."' class='card'>
                                            <div class='card-body'>
                                                <div class='row align-items-center'>
                                                    <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                                        <div class='user-avatar float-xl-left pr-4 float-none'>
                                                            <img src='/voc/assets/images/avatar-1.jpg' alt='device img' class='rounded-circle user-avatar-xl'>
                                                                </div>
                                                            <div class='pl-xl-3'>
                                                                <div class='m-b-0'>
                                                                    <div class='user-avatar-name d-inline-block'>
                                                                        <h2 id='model_id".$i."' class='font-24 m-b-10'>".$row['name']."</h2>
                                                                    </div>
                                                                    <div class='rating-star d-inline-block pl-xl-2 mb-2 mb-xl-0'>
                                                                        <p class='d-inline-block text-dark'>구분 : ".$flag1."</p>
                                                                    </div>
                                                                    <div class='rating-star d-inline-block pl-xl-2 mb-2 mb-xl-0'>
                                                                        <p class='d-inline-block text-dark'>타입 : ".$flag2."</p>
                                                                    </div>
                                                                </div>
                                                                <div class='user-avatar-address'>
                                                                    <p class='mb-2'><i class='fas fa-info mr-2  text-primary'></i>
                                                                    <span class='m-l-10'>E-mail: ".$row['email']."<span class='m-l-10'>요청일: ".$row['reqDate']."<span class='m-l-10'>승인일: ".$row['aprDate']."</span></span></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                                            <div class='float-left' style='margin-bottom:5px'>
                                                                <button class='btn check' onclick='setfocusUser(".$row['id'].",".$row['auth'].")' checked='".$row['auth']."'>적용</button>
                                                            </div>
                                                            <div class='float-right'>
                                                                <input id='".$row['id']."' class='checkUp' type='checkbox' onClick='setCheck(".$row['id'].");'style='width: 30px; height: 30px;'>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                            $i++;
                                          }
                                    }else{
                                            echo "
                                            <div class='card'>
                                              <div class='card-body'>
                                                <p>No Devices</p>
                                              </div>
                                            </div>";
                                    }
                              ?>
                              <!-- ============================================================== -->
                              <!-- end influencer sidebar  -->
                              <!-- ============================================================== -->
                          </div>
                      </div>
                      <!-- ============================================================== -->
                      <!-- footer -->
                      <!-- ============================================================== -->
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
                      <!-- ============================================================== -->
                      <!-- end footer -->
                      <!-- ============================================================== -->
                  </div>
                  <!-- ============================================================== -->
                  <!-- end wrapper  -->
                  <!-- ============================================================== -->
              </div>
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
          <!-- ============================================================== -->
          <!-- end wrapper  -->
          <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- javascript -->
      <script type="text/javascript">
        //Check Box Rendering
        var idx_location = "";
        window.onload = function(){
            var i = 0;
            var card = document.getElementsByClassName('influence-finder')[0]
            card.scrollTop = 0;
            var elements = document.getElementsByClassName('checkUp');
            var count = elements.length;
            idx_location = "";
            while(i<count){
                elements[i].removeAttribute('checked');
                elements[i].checked = false;
                i++;
            }
            //Deploy button Rendering
            var checks = document.getElementsByClassName('check');
            count = checks.length;
            i = 0;
            while(i<count){
              if(checks[i].getAttribute('checked') == "0"){
                checks[i].setAttribute('style','color:white; height:40px; width:90px; background-color:grey; border-color:grey;');
              }else{
                checks[i].setAttribute('style','color:white; height:40px; width:90px; background-color:#ff407b; border-color:#ff407b;');
              }
              i++;
            }
        }
      </script>
  </body>

</html>
