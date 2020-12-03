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
    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once($root."/config/config.php");
    if(!isset($_GET['id'])){
      echo "<script>
              alert('잘못된 경로 입니다.');
              location.href = '/voc/admin/devices.php';
            </script>";
    }
    $id = $_GET['id'];
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $sql = "SELECT manu, os, swVer FROM voc_devices";
    $result = mysqli_query($conn, $sql);

    //temperate list array
    $temp_list_1 = array();
    $temp_list_2 = array();
    $temp_list_3 = array();

    //Unique list array
    $manu_list = array();
    $os_list = array();
    $sw_list = array();

    while($row=mysqli_fetch_array($result)){
      array_push($temp_list_1, $row['manu']);
      array_push($temp_list_2, $row['os']);
      array_push($temp_list_3, $row['swVer']);
    }

    for($i=0; $i<count($temp_list_1); $i++){
      if(!in_array($temp_list_1[$i],$manu_list)){
        array_push($manu_list, $temp_list_1[$i]);
      }
      if(!in_array($temp_list_2[$i],$os_list)){
        array_push($os_list, $temp_list_2[$i]);
      }
      if(!in_array($temp_list_3[$i],$sw_list)){
        array_push($sw_list, $temp_list_3[$i]);
      }
    }

    $sql = "SELECT * FROM voc_devices WHERE id=".trim($_GET['id']);
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
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
    <link href="/voc/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/voc/assets/vendor/datepicker/tempusdominus-bootstrap-4.css" />
    <link rel="stylesheet" href="/voc/assets/vendor/inputmask/css/inputmask.css" />
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
                                            <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" > <span>HOME</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" > <span>DATA</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/chart/newChart.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" > <span>CHART</span></a>
                                        </div>
                                        <?php
                                          if ($_SESSION['adminAuth']== 1){
                                            echo
                                            '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                <a href="/voc/admin/devices.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>ADMIN</span></a>
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
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/voc/assets/images/logout64.png" alt="" class="user-avatar-md rounded-circle"></a>
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
                                          <a class="nav-link" href="/voc/data/index.php">VOC 통품전체 데이터</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/sort.php">VOC 단말조치 데이터</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/subscribe.php">가입자 데이터</a>
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
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/subsChart.php">가입자차트</a>
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
                <div class="row">
                    <div class="col-xl-12">
                        <!-- ============================================================== -->
                        <!-- pageheader  -->
                        <!-- ============================================================== -->
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="page-header" id="top">
                                    <h2 class="pageheader-title">Update Device</h2>
                                    <p class="pageheader-text"></p>
                                    <div class="page-breadcrumb">
                                        <nav aria-label="breadcrumb">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">Dashboard</a></li>
                                                <li class="breadcrumb-item"><a href="/voc/admin/devices.php" class="breadcrumb-link">Device List</a></li>
                                                <li class="breadcrumb-item active" aria-current="page">Update Device</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end pageheader  -->
                        <!-- ============================================================== -->
                        <div class="page-section" id="overview">
                            <!-- ============================================================== -->
                            <!-- valifation types -->
                            <!-- ============================================================== -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="padding-left:7px;padding-right:7px;">
                                <div class="card">
                                    <h5 class="card-header">Device Info</h5>
                                    <div class="card-body">
                                        <form id="regiDeviceForm" data-parsley-validate="" novalidate="" action="/voc/admin/phpdata/updateDeviceData.php?" method="post" onsubmit='return infoCheck();'>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">ManuFacturer*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input id="manu_input" type="text" required="" placeholder="Manufacturer" class="form-control" style='display:none;'>
                                                    <input id="id" type="text" class="form-control" name="id" style='display:none;' value="<?php echo $id;?>">
                                                    <i id='manu_type' value='select' style="display:none;"></i>
                                                    <select id="manu_select" name="manu" style="width:100px;height:35px;" onchange="executeSelf('manu');">
                                                      <?php
                                                        for($i=0; $i<count($manu_list); $i++){
                                                          if($manu_list[$i] == $row['manu']){
                                                            echo "
                                                              <option value='".$manu_list[$i]."' selected='selected'>".$manu_list[$i]."</option>
                                                              ";
                                                          }else{
                                                            echo "
                                                              <option value='".$manu_list[$i]."'>".$manu_list[$i]."</option>
                                                              ";
                                                          }
                                                        }
                                                      ?>
                                                      <option value=''>직접입력</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Model Name*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input type="text" required="" name="model" placeholder="Model Name" class="form-control" value="<?php echo $row['model']; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">OS*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input id="os_input" type="text" required="" placeholder="OS" class="form-control" style='display:none;'>
                                                    <i id='os_type' value='select' style="display:none;"></i>
                                                    <select id="os_select" name="os" style="width:100px;height:35px;" onchange="executeSelf('os');">
                                                      <?php
                                                        for($i=0;$i<count($os_list);$i++){
                                                          if($manu_list[$i] == $row['os']){
                                                            echo "
                                                              <option value='".$os_list[$i]."' selected='selected'>".$os_list[$i]."</option>
                                                              ";
                                                          }else{
                                                            echo "
                                                              <option value='".$os_list[$i]."'>".$os_list[$i]."</option>
                                                              ";
                                                          }
                                                        }
                                                      ?>
                                                      <option value=''>직접입력</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Pet Name</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input type="text" required="" name="petName" placeholder="Pet Name" class="form-control" value="<?php echo $row['petName'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Launch Date*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input id="launch_date" type="date" name="launDate" class="form-control" value="<?php echo $row['launDate'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Software Version*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input type="text" name="swVer" placeholder="SW Version" class="form-control" value="<?php echo $row['swVer'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Type*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                  <select id="flag_select" name="class" style="width:100px;height:35px;">
                                                    <?php
                                                      $index = ['NEW','OS','MR','OMD'];
                                                      for($i=0; $i<count($index); $i++){
                                                        if($index[$i] == $row['class']){
                                                          echo'<option value="'.$index[$i].'" selected="selected">'.$index[$i].'</option>';
                                                        }else{
                                                          echo'<option value="'.$index[$i].'">'.$index[$i].'</option>';
                                                        }
                                                      }
                                                    ?>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Deploy Date*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input id="launch_date" type="date" name="deployDate" class="form-control" value="<?php echo $row['deployDate'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">OS Version*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input type="text" name="osVer" placeholder="OS Version" class="form-control" value="<?php echo $row['osVer'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Round</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <input type="text" name="roundNo" placeholder="Round Only number" class="form-control" value="<?php echo (string) $row['roundNo'];?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Focus On*</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                  <select id="flag_select" name="flag" style="width:100px;height:35px;">
                                                    <?php
                                                      $index = ['0','1'];
                                                      $names = ['No Deploy','Deploy'];
                                                      for($i=0; $i<count($index); $i++){
                                                        if($index[$i] == (string) $row['flag']){
                                                          echo'<option value="'.$index[$i].'" selected="selected">'.$names[$i].'</option>';
                                                        }else{
                                                          echo'<option value="'.$index[$i].'">'.$names[$i].'</option>';
                                                        }
                                                      }
                                                    ?>
                                                  </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Description</label>
                                                <div class="col-12 col-sm-8 col-lg-6">
                                                    <textarea id="extra" name="extra" placeholder="Description" rows="5" class="form-control"><?php echo $row['extra'];?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row text-right">
                                                <div class="col col-sm-10 col-lg-9 offset-sm-1 offset-lg-0">
                                                    <button type="submit" class="btn btn-space btn-primary">Update Device</button>
                                                    <button type="button" class="btn btn-space btn-secondary" onclick="window.location='/voc/admin/devices.php';">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end valifation types -->
                            <!-- ============================================================== -->
                        </div>
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
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="/voc/assets/libs/js/main-js.js"></script>
    <script src="/voc/assets/vendor/inputmask/js/jquery.inputmask.bundle.js"></script>
    <script>
    </script>
</body>

</html>
