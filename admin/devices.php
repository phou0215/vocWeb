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
    $device_flag = "";
    $total_count = 0;
    $deploy_count = 0;
    $focusOn_count = 0;
    // $act_type = "";
    $device_keyword = "";
    $data_set = array();

    // $sql_flag_update = "UPDATE settings SET deviceFlag ";
    $sql_flag = "SELECT * FROM settings";
    $sql_model = "SELECT * FROM voc_models ORDER BY focusOn DESC, flag DESC , regiDate DESC";
    $sql_model2 = "SELECT * FROM voc_models2 ORDER BY focusOn DESC, flag DESC , regiDate DESC";

    $result = mysqli_query($conn, $sql_flag);
    $row = mysqli_fetch_array($result);
    $device_flag = $row['deviceFlag'];

    // $today = date('Y-m-d');

    /////////////////////////////////////////////////////////////////// 각 변수 및 설정 값 변경/////////////////////////////////////////////////////////////////////////////
    // Check whether change form action
    if(isset($_POST['device_type'])){
      if ($_POST['device_type'] == 'model2'){
        mysqli_query($conn, "UPDATE settings SET deviceFlag = 1");
      }
      else{
        mysqli_query($conn, "UPDATE settings SET deviceFlag = 0");
      }
      /* device flag refresh*/
      $result = mysqli_query($conn, $sql_flag);
      $row = mysqli_fetch_array($result);
      $device_flag = $row['deviceFlag'];
    }

    // Check whether searching device keyword form action
    if(isset($_GET['device_key'])){
      if ($device_flag == 1){
        $sql_model2 = "SELECT * FROM voc_models2 WHERE model REGEXP '^".$_GET["device_key"]."' ORDER BY focusOn DESC, flag DESC , regiDate DESC";
      }
      else{
        $sql_model = "SELECT * FROM voc_models WHERE model REGEXP '^".$_GET["device_key"]."' ORDER BY focusOn DESC, flag DESC , regiDate DESC";
      }
      $device_keyword = $_GET['device_key'];
    }
    // Set Result Data by each condition
    if($device_flag == 1){
      $result = mysqli_query($conn, $sql_model2);
      $total_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result)){
        if($row['flag'] == '1'){
          $deploy_count+=1;
        }
        if($row['focusOn'] == '1'){
          $focusOn_count +=1;
        }
        array_push($data_set, $row);
      }
    }
    else{
      $result = mysqli_query($conn, $sql_model);
      $total_count = mysqli_num_rows($result);
      while($row = mysqli_fetch_array($result)){
        if($row['flag'] == '1'){
          $deploy_count+=1;
        }
        if($row['focusOn'] == '1'){
          $focusOn_count +=1;
        }
        array_push($data_set, $row);
      }
    }
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
              <div class="influence-finder">
                  <div class="container-fluid dashboard-content">
                      <!-- ============================================================== -->
                      <!-- pageheader -->
                      <!-- ============================================================== -->
                      <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="page-header">
                                  <h2 class="pageheader-title">단말리스트관리</h2>
                                  <div class="page-breadcrumb">
                                      <nav aria-label="breadcrumb" style='margin-bottom:2px;'>
                                          <ol class="breadcrumb">
                                              <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">메인화면</a></li>
                                              <li class="breadcrumb-item active" aria-current="page">단말리스트관리</li>
                                          </ol>
                                      </nav>
                                      <div class="float-left" style="margin-bottom:5px;">
                                        <form class="" action="/voc/admin/devices.php" method="post" onsubmit="return checkChange();">
                                          <select id="deviceType" style="font-size:14px; margin-right:8px; width:100px; height:48px; padding:3px;" name="device_type">
                                            <?php
                                                if($device_flag == 1){
                                                  echo "<option value='model'>Origin</option>
                                                        <option value='model2' selected='selected'>Mapping</option>";
                                                }
                                                else{
                                                    echo "<option value='model' selected='selected'>Origin</option>
                                                          <option value='model2'>Mapping</option>";
                                                }
                                            ?>
                                          </select>
                                          <!-- <input type="text" name="act_type" value="true" hidden='hidden'> -->
                                          <button type="submit" class="btn btn-space btn-primary" style="font-size:14px;margin-top:4px;">변경</button>
                                        </form>
                                      </div>
                                      <div class="float-right" style="margin-bottom:5px; margin-right:8px;">
                                          <button class='btn btn-secondary' onclick='removeDeviceData(<?php echo $device_flag.",\"".$device_keyword."\""; ?>)'>삭제</button>
                                      </div>
                                      <div class="float-right" style="margin-bottom:5px; margin-right:8px;">
                                        <form class="" action="/voc/admin/phpdata/deviceUnDeploy.php" method="post" onsubmit="return deviceUndeploy()">
                                          <button class="btn btn-success" onClick="" type="submit">적용해제</button>
                                          <input id="device_models_un" type="text" name="items" value="" hidden>
                                          <input type="text" name="device_key" value="<?php echo $device_keyword; ?>" hidden>
                                          <input type="text" name="flag" value="<?php echo $device_flag; ?>" hidden>
                                        </form>
                                      </div>
                                      <div class="float-right" style="margin-bottom:5px; margin-right:8px;">
                                        <form class="" action="/voc/admin/phpdata/deviceTotDeploy.php" method="post" onsubmit="return deviceDeploy()">
                                          <button class="btn btn-info" onClick="" type="submit">적용</button>
                                          <input id="device_models_de" type="text" name="items" value="" hidden>
                                          <input type="text" name="device_key" value="<?php echo $device_keyword; ?>" hidden>
                                          <input type="text" name="flag" value="<?php echo $device_flag; ?>" hidden>
                                        </form>
                                      </div>
                                      <!-- <div class="float-right" style="margin-bottom:5px;margin-right:8px;">
                                          <button class='btn btn-success' onclick='updateDeviceData()'>Update</a>
                                      </div> -->
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
                                      <form class="" action="/voc/admin/devices.php" method="GET" onsubmit="">
                                        <input class="form-control form-control-lg" value="<?php echo $device_keyword; ?>" name="device_key" type="search" placeholder="Model Name" aria-label="Search" onkeypress="if(event.keyCode==13){return true;}">
                                        <button class="btn btn-primary search-btn" type="submit" onclick=''>검색</button>
                                        <!-- <input class="form-control form-control-lg" name="device_key" type="search" placeholder="Model Name" aria-label="Search" onkeypress="if(event.keyCode==13){searchDevice(); return false;}">
                                        <button class="btn btn-primary search-btn" type="submit" onclick='searchDevice();'>Search</button>                                         -->
                                        <!-- <button class="btn btn-primary search-btn" type="button" onclick='searchDevice();'>Search</button>                                         -->
                                      </form>
                                  </div>
                              </div>
                          </div>
                          <!-- ============================================================== -->
                          <!-- end search bar  -->
                          <!-- ============================================================== -->
                          <!-- ============================================================== -->
                          <!-- count bar  -->
                          <!-- ============================================================== -->
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <div class="card">
                                  <div class="card-body">
                                    <?php echo "<h4 class='text-muted' style='margin:12px;'>적용단말&nbsp;:&nbsp;<span style='color:orange;'>".$deploy_count."</span> &nbsp;&nbsp;/&nbsp;&nbsp; 관심등록단말&nbsp;:&nbsp;<span id='focusCount' style='color:orange;'>".$focusOn_count."</span>
                                    &nbsp;&nbsp;/&nbsp;&nbsp; 전체단말&nbsp;:&nbsp;<span>".$total_count."</span></h4>"
                                    ?>
                                  </div>
                              </div>
                          </div>
                          <!-- ============================================================== -->
                          <!-- end count bar  -->
                          <!-- ============================================================== -->
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                              <!-- ============================================================== -->
                              <!-- card Devices one -->
                              <!-- ============================================================== -->
                              <?php
                                if($total_count != 0){
                                  $i = 0;
                                  while($i < count($data_set)){
                                    //제조사 확인 후 이미지 설정
                                    $img_url = "";
                                    $img_url2 = "";
                                    $manufact = $data_set[$i]['manu'];
                                    $cell = $data_set[$i]['cellType'];
                                    // 제조사 분리
                                    if(preg_match("/.*삼성.*/", $manufact)){
                                      $img_url = '/voc/assets/images/삼성_로고.png';
                                    }else if(preg_match("/(.*APPLE.*|.*Apple.*)/", $manufact)){
                                      $img_url = '/voc/assets/images/apple.png';
                                    }else if(preg_match("/(.*LG전자.*|.*블랙리스트_LG.*)/", $manufact)){
                                      $img_url = '/voc/assets/images/LG_로고.png';
                                    }else if(preg_match("/(.*샤오미.*)/", $manufact)){
                                      $img_url = '/voc/assets/images/샤오미.png';
                                    }else if(preg_match("/(.*모토로라.*)/", $manufact)){
                                      $img_url = '/voc/assets/images/모토로라.png';
                                    }else{
                                      $img_url = '/voc/assets/images/avatar-1.jpg';
                                    }
                                    // Network 분리
                                    if($cell == "LTE"){
                                      $img_url2 = '/voc/assets/images/4G.png';
                                    }else if($cell == "5G"){
                                      $img_url2 = '/voc/assets/images/5G.png';
                                    }else if($cell == "WCDMA"){
                                      $img_url2 = '/voc/assets/images/3G.png';
                                    }else{
                                      $img_url2 = '/voc/assets/images/2G.png';
                                    }

                                    echo "
                                          <div class='card'>
                                            <div class='card-body'>
                                                <div class='row align-items-center'>
                                                    <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                                        <div class='user-avatar float-xl-left pr-4 float-none'>
                                                            <img src='".$img_url."' alt='device img' class='rounded-circle user-avatar-xl'>
                                                                </div>
                                                            <div class='pl-xl-3'>
                                                                <div class='m-b-0'>
                                                                    <div class='user-avatar-name d-inline-block'>
                                                                        <h2 id='model_id".$data_set[$i]['id']."' class='font-24 m-b-10'>".$data_set[$i]['model']."</h2>
                                                                    </div>
                                                                    <div class='rating-star d-inline-block pl-xl-2 mb-2 mb-xl-0'>
                                                                      <img src='".$img_url2."' alt='device img' class='rounded-circle user-avatar-lg'>
                                                                    </div>
                                                                </div>
                                                                <div class='user-avatar-address'>
                                                                    <p class='mb-2'><i class='fas fa-mobile-alt mr-2  text-primary'></i>
                                                                    <span class='m-l-10'><span class='m-l-20'>출시일: ".$data_set[$i]['launchDate']."<span class='m-l-20'>등록일: ".$data_set[$i]['regiDate']."</span></span></span>
                                                                    </p>
                                                                    <p class='mb-2'><i class='fas fa-info mr-2  text-primary'></i>
                                                                    <span class='m-l-10'><span class='m-l-20'>타입: ".$cell."<span class='m-l-20'>제조사: ".$manufact." </span></span></span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class='col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12'>
                                                            <div class='float-left' style='margin-bottom:5px'>
                                                                <button class='btn check' onclick='setFocus(".$data_set[$i]['id'].",".$data_set[$i]['flag'].",".$device_flag.",\"".$device_keyword."\")' checked='".$data_set[$i]['flag']."'>적용</button>
                                                            </div>
                                                            <div class='float-right'>
                                                                <input id='".$data_set[$i]['id']."' class='checkUp' type='checkbox' onClick='setCheck(".$data_set[$i]['id'].");'style='width: 30px; height: 30px;'>
                                                            </div>
                                                            <div class='float-xl-right float-none mt-xl-0 mt-4'>
                                                                <button id='focusOn".$i."' class='focus btn-wishlist m-r-10' onclick='setFocusOn(".$data_set[$i]['id'].",".$i.",".$device_flag.",".$data_set[$i]['flag'].")' data='".$data_set[$i]['focusOn']."'><i style='cursor:pointer;' class='far fa-star'></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                            $i++;
                                        }
                                  }
                                else{
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
              <script type="text/javascript" src="/voc/assets/vendor/multi-select-min/js/bootstrap-select-min.js"></script>
              <script src="/voc/assets/libs/js/toastr.min.js"></script>
          <!-- ============================================================== -->
          <!-- end wrapper  -->
          <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- javascript -->
      <script type="text/javascript">
        //Check Box Rendering
        // var idx_location = "";
        // window.onload = function(){
        //     var i = 0;
        //     var card = document.getElementsByClassName('influence-finder')[0]
        //     card.scrollTop = 0;
        //     var elements = document.getElementsByClassName('checkUp');
        //     var count = elements.length;
        //     while(i<count){
        //         elements[i].removeAttribute('checked');
        //         elements[i].checked = false;
        //         i++;
        //     }
        // }
      </script>
  </body>

</html>
